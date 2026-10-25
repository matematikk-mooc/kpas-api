<?php

namespace App\Utils;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Throwable;

class RemindersUtil {
    const CACHE_TTL_MS = 30000;
    const DEFAULT_DOMAIN = 'utdanningsdirektoratet-my.sharepoint.com';

    /** @var array<string, array{data: array, timestamp: int}> */
    private static array $cache = [];

    /**
     * Get tasks from cache if still valid
     */
    public static function getFromCache(string $key): ?array {
        $cached = self::$cache[$key] ?? null;
        $nowMs = (int) round(microtime(true) * 1000);
        if ($cached !== null && ($nowMs - $cached['timestamp']) < self::CACHE_TTL_MS) {
            return $cached['data'];
        }

        return null;
    }

    /**
     * Store tasks into cache with timestamp
     */
    public static function setCache(string $key, array $data): void {
        self::$cache[$key] = [
            'data' => $data,
            'timestamp' => (int) round(microtime(true) * 1000),
        ];
    }

    /**
     * Parse date from DateTimeInterface, Excel serial number, slash date (MM/DD/YYYY), or ISO string
     */
    public static function parseDate(mixed $value): ?DateTimeImmutable {
        if (!$value) return null;
        if ($value instanceof DateTimeImmutable) return $value;
        if ($value instanceof DateTimeInterface) return DateTimeImmutable::createFromInterface($value);

        if (is_int($value) || is_float($value)) {
            if (is_nan((float) $value) || is_infinite((float) $value)) return null;
            $excelEpoch = new DateTimeImmutable('1899-12-30 00:00:00', new DateTimeZone('UTC'));
            $seconds = (int) round($value * 86400);
            return $excelEpoch->modify(($seconds >= 0 ? '+' : '') . $seconds . ' seconds');
        }

        if (is_string($value)) {
            $trimmed = trim($value);
            if ($trimmed === '') return null;

            if (str_contains($trimmed, '/')) {
                $parts = explode('/', $trimmed);
                $month = isset($parts[0]) && is_numeric(trim($parts[0])) ? (int) trim($parts[0]) : 0;
                $day = isset($parts[1]) && is_numeric(trim($parts[1])) ? (int) trim($parts[1]) : 0;
                $year = isset($parts[2]) && is_numeric(trim($parts[2])) ? (int) trim($parts[2]) : 0;
                if ($year && $month && $day) {
                    return (new DateTimeImmutable('now', new DateTimeZone('UTC')))
                        ->setDate($year, $month, $day)
                        ->setTime(0, 0, 0);
                }
            }

            try {
                return new DateTimeImmutable($trimmed, new DateTimeZone('UTC'));
            } catch (Throwable $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * Fetch HTTP URL with exponential backoff retry
     */
    public static function fetchWithRetry(string $url, array $options = [], int $maxRetries = 3): array {
        $lastError = null;

        for ($i = 0; $i < $maxRetries; $i++) {
            $responseHeaders = '';
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $options['follow'] ?? true);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($curl, string $headerLine) use (&$responseHeaders): int {
                $responseHeaders .= $headerLine;
                return strlen($headerLine);
            });

            if (!empty($options['headers'])) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $options['headers']);
            }

            $body = curl_exec($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($body !== false && (($httpCode >= 200 && $httpCode < 300) || $httpCode === 302)) {
                return [
                    'code' => $httpCode,
                    'headers' => $responseHeaders,
                    'body' => (string) $body,
                ];
            }

            $lastError = new Exception($curlError ?: "HTTP {$httpCode}");
            if ($i < $maxRetries - 1) {
                usleep((int) (pow(2, $i) * 1000000));
            }
        }

        throw $lastError ?: new Exception('Failed after retries');
    }

    /**
     * Authenticate against SharePoint to get redirect URL and authentication cookie
     */
    public static function authenticateSharePoint(string $fileUrl): array {
        $domain = self::DEFAULT_DOMAIN;
        $url = 'https://' . $domain . (str_starts_with($fileUrl, '/') ? $fileUrl : '/' . $fileUrl);

        $res = self::fetchWithRetry($url, [
            'headers' => [
                'User-Agent: PostmanRuntime/7.43.3',
                'Accept: */*',
                'Host: ' . $domain,
            ],
            'follow' => false,
        ]);

        preg_match("/^[Ll]ocation:\s*(.*?)\r?$/m", $res['headers'], $locMatches);
        preg_match_all("/^[Ss]et-[Cc]ookie:\s*(.*?)\r?$/m", $res['headers'], $cookieMatches);

        $redirectUrl = !empty($locMatches[1]) ? trim($locMatches[1]) : null;
        $rawCookies = !empty($cookieMatches[1]) ? $cookieMatches[1] : [];

        if (!$redirectUrl || empty($rawCookies)) {
            throw new Exception('Authentication failed');
        }

        $cookieValue = trim(explode(';', $rawCookies[0])[0]);
        if ($cookieValue === '') {
            throw new Exception('Invalid cookie format');
        }

        return [
            'url' => str_starts_with($redirectUrl, 'http')
                ? $redirectUrl
                : 'https://' . $domain . (str_starts_with($redirectUrl, '/') ? $redirectUrl : '/' . $redirectUrl),
            'cookie' => $cookieValue,
        ];
    }

    /**
     * Download Excel workbook from SharePoint
     */
    public static function downloadExcel(string $url, string $cookie): string {
        $res = self::fetchWithRetry($url, [
            'headers' => [
                'Cookie: ' . $cookie,
            ],
            'follow' => true,
        ]);

        if ($res['code'] < 200 || $res['code'] >= 300) {
            throw new Exception("Download failed: {$res['code']}");
        }

        return $res['body'];
    }

    /**
     * Parse binary Excel (XLSX) buffer into a 2D array of rows and cells
     * Uses phpoffice/phpspreadsheet (matching ExcelJS behavior)
     */
    public static function parseExcel(string $buffer): array {
        if ($buffer === '') {
            throw new Exception('No sheets found');
        }

        $tmpFile = tempnam(sys_get_temp_dir(), 'xlsx_reminders_');
        if ($tmpFile === false) {
            throw new Exception('Failed to create temporary file for Excel parsing');
        }

        try {
            file_put_contents($tmpFile, $buffer);

            $spreadsheet = IOFactory::load($tmpFile);
            if ($spreadsheet->getSheetCount() === 0) {
                throw new Exception('No sheets found');
            }

            $worksheet = $spreadsheet->getSheet(0);
            $data = [];

            foreach ($worksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);

                $rowCells = [];
                $lastNonEmptyIndex = -1;
                $colIdx = 0;

                foreach ($cellIterator as $cell) {
                    $val = $cell->getDataType() === DataType::TYPE_FORMULA
                        ? ($cell->getOldCalculatedValue() ?? $cell->getValue())
                        : $cell->getValue();

                    if ($val instanceof RichText) {
                        $val = $val->getPlainText();
                    }

                    if ($cell->hasHyperlink() && !empty($cell->getHyperlink()->getUrl())) {
                        $val = [
                            'text' => (string) ($val ?? ''),
                            'hyperlink' => $cell->getHyperlink()->getUrl(),
                        ];
                    } elseif ((is_int($val) || is_float($val)) && ExcelDate::isDateTime($cell)) {
                        $val = DateTimeImmutable::createFromMutable(
                            ExcelDate::excelToDateTimeObject($val, new DateTimeZone('UTC'))
                        );
                    }

                    if ($val !== null && $val !== '') {
                        $lastNonEmptyIndex = $colIdx;
                    }

                    $rowCells[] = $val;
                    $colIdx++;
                }

                if ($lastNonEmptyIndex >= 0) {
                    $data[] = array_slice($rowCells, 0, $lastNonEmptyIndex + 1);
                }
            }

            if (count($data) < 2) {
                throw new Exception('No data found');
            }

            return $data;
        } finally {
            if (file_exists($tmpFile)) {
                @unlink($tmpFile);
            }
        }
    }

    /**
     * Transform parsed Excel rows into Task models
     */
    public static function transformToTasks(array $data): array {
        $headers = $data[0] ?? [];
        $rows = array_slice($data, 1);
        $todayMs = (int) round(microtime(true) * 1000);

        $tasks = [];
        foreach ($rows as $row) {
            $task = [];
            foreach ($headers as $i => $h) {
                $headerKey = (string) $h;
                $cellVal = $row[$i] ?? '';
                $task[$headerKey] = ($cellVal === null) ? '' : $cellVal;
            }

            $expirationDate = self::parseDate($task['expiration_date'] ?? null);
            if (!$expirationDate) {
                continue;
            }

            // Convert any other DateTimeInterface fields to ISO strings to match JS Date.toJSON()
            foreach ($task as $k => $v) {
                if ($v instanceof DateTimeInterface && $k !== 'expiration_date') {
                    $task[$k] = $v->format('Y-m-d\TH:i:s.v\Z');
                }
            }

            $urlString = isset($task['url']) && is_string($task['url']) ? $task['url'] : '';
            $taskId = null;
            if ($urlString !== '' && preg_match('/DIT-(\d+)/i', $urlString, $m)) {
                $taskId = strtoupper($m[0]);
            }

            $title = isset($task['title']) && is_string($task['title']) ? $task['title'] : '';

            $task['title'] = $taskId ? "[{$taskId}] {$title}" : $title;
            $task['expiration_date'] = $expirationDate->format('Y-m-d');

            $expMs = (int) $expirationDate->format('Uv');
            $task['days_left'] = max(0, (int) ceil(($expMs - $todayMs) / 86400000));

            $tasks[] = $task;
        }

        return $tasks;
    }
}
