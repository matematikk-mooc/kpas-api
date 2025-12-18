<?php

namespace App\Utils;

use Sentry\SentrySdk;
use Sentry\State\Scope;
use Sentry\Tracing\SpanContext;
use Sentry\Tracing\SpanStatus;
use Sentry\Tracing\TransactionContext;
use GuzzleHttp\Client;

use function Sentry\getBaggage;
use function Sentry\getTraceparent;
use function Sentry\startTransaction;

class SentryTrace {
    public static function getHub() {
        return SentrySdk::getCurrentHub();
    }
    
    public static function getSpan() {
        $hub = SentryTrace::getHub();
        return $hub->getSpan();
    }

    public static function getTransaction() {
        $hub = SentryTrace::getHub();
        return $hub->getTransaction();
    }

    public static function setSpan($span) {
        $hub = SentryTrace::getHub();
        $hub->setSpan($span);
    }

    public static function setData($data) {
        $span = SentryTrace::getSpan();
        $span->setData($data);
    }

    public static function new(string $name, string $op, ?string $description = null) {
        $transactionContext = TransactionContext::make()
            ->setName($name)
            ->setOp($op);

        $span = startTransaction($transactionContext);
        SentryTrace::setSpan($span);

        return $span;
    }

    public static function newChild($parentSpan, string $op, ?string $description = null) {
        $spanContext = SpanContext::make()
            ->setOp($op)
            ->setDescription($description);
        $span = $parentSpan ? $parentSpan->startChild($spanContext) : null;

        if (!$span) return false;

        SentryTrace::setSpan($span);
        return $span;
    }

    public static function setContext(string $key, $value) {
        $hub = SentryTrace::getHub();
        $hub->configureScope(function (Scope $scope) use ($key, $value) {
            $scope->setContext($key, $value);
        });
    }

    public static function finish($parentSpan, $statusCode = 200) {
        $hub = SentryTrace::getHub();
        $client = $hub->getClient();
        $span = $hub->getSpan();

        if ($span) {
            $span->setStatus(SpanStatus::createFromHttpStatusCode($statusCode));
            $span->finish();
        }

        if ($parentSpan != null) $hub->setSpan($parentSpan);
    }

    public static function fileGetContents($url, $useIncludePath = false, $context = null, $offset = 0, $maxLen = null): string {
        logger("SentryTrace::fileGetContents url=$url");

        $hub = SentrySdk::getCurrentHub();
        $parentSpan = $hub->getSpan();

        $isRemote = filter_var($url, FILTER_VALIDATE_URL);
        $operation = $isRemote ? 'http.client' : 'resource.file';
        $description = $isRemote ? "GET $url" : "Read File: $url";

        $spanContext = new SpanContext();
        $spanContext->setOp($operation);
        $spanContext->setDescription($description);
        $span = $parentSpan ? $parentSpan->startChild($spanContext) : null;

        $startTime = microtime(true);

        try {
            $result = file_get_contents($url, $useIncludePath, $context, $offset, $maxLen);
            if ($result === false) {
                throw new \RuntimeException("Failed to fetch content from $url");
            }

            $duration = round((microtime(true) - $startTime) * 1000, 2);

            if ($span) {
                $metadata = ['duration_ms' => $duration];

                if ($isRemote) {
                    $metadata += [
                        'http.request.method' => 'GET',
                        'http.url' => $url,
                        'http.response.status_code' => 200,
                        'response.size' => strlen($result),
                    ];
                } else {
                    $metadata += [
                        'file.path' => $url,
                        'file.name' => basename($url),
                        'file.size' => strlen($result),
                    ];
                }

                $span->setData($metadata);
                $span->setStatus(SpanStatus::ok());
            }

            return $result;
        } catch (\Throwable $e) {
            if ($span) {
                $errorMetadata = [
                    'error' => $e->getMessage(),
                    'error.type' => get_class($e),
                ];

                if ($isRemote) {
                    $errorMetadata['http.url'] = $url;
                } else {
                    $errorMetadata['file.path'] = $url;
                }

                $span->setData($errorMetadata);
                $span->setStatus(SpanStatus::internalError());
            }

            throw $e;
        } finally {
            if ($span) {
                $span->finish();
                $hub->setSpan($parentSpan);
            }
        }
    }
    
    public static function guzzleRequest($method, $url, $options = []) {
        $canvasPingEndpoint = "/api/v1/courses/360";
        if (!str_contains($url, $canvasPingEndpoint)) {
            logger("SentryTrace::guzzleRequest url=$url");
        }

        $hub = SentrySdk::getCurrentHub();
        $parentSpan = $hub->getSpan();
    
        $spanContext = new SpanContext();
        $spanContext->setOp('http.client');
        $spanContext->setDescription(strtoupper($method) . " Request: $url");
    
        $span = $parentSpan ? $parentSpan->startChild($spanContext) : null;
        $startTime = microtime(true);
    
        try {
            $client = new Client();
            $response = $client->request($method, $url, $options);
            $duration = round((microtime(true) - $startTime) * 1000, 2);
    
            if ($span) {
                $span->setData([
                    'http.request.method' => strtoupper($method),
                    'http.url' => $url,
                    'http.response.status_code' => $response->getStatusCode(),
                    'http.request.size' => array_key_exists('body', $options) && $options['body'] 
                        ? strlen($options['body']) 
                        : null,
                    'http.response.size' => $response->getBody()->getSize(),
                    'duration_ms' => $duration,
                ]);
                $span->setStatus(SpanStatus::ok());
            }

            /*
            // Debugging HTTP requests
            error_log(print_r([
                'url' => $url,
                'method' => $method,
                'status' => $response->getStatusCode(),
                'date' => date('c')
            ], true));
            */
    
            return $response;
        } catch (RequestException $e) {
            if ($span) {
                $span->setStatus(SpanStatus::internalError());
                $span->setData([
                    'error' => $e->getMessage(),
                    'error.type' => get_class($e),
                    'http.url' => $url,
                    'http.request.method' => strtoupper($method),
                    'http.response.status_code' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                ]);
            }
    
            throw $e;
        } catch (\Throwable $e) {
            if ($span) {
                $span->setStatus(SpanStatus::internalError());
                $span->setData([
                    'error' => $e->getMessage(),
                    'error.type' => get_class($e),
                    'http.url' => $url,
                    'http.request.method' => strtoupper($method),
                ]);
            }
    
            throw $e;
        } finally {
            if ($span) {
                $span->finish();
                $hub->setSpan($parentSpan);
            }
        }
    }

    private static function cleanGraphQLQuery(string $query): string {
        return preg_replace('/\s+/', ' ', trim($query));
    }

    private static function extractOperationName(string $query): ?string {
        if (preg_match('/(query|mutation)\s+(\w+)/', $query, $matches)) return $matches[2];
        return null;
    }

    public static function graphqlRequest(string $url, string $query, array $variables = [], array $headers = []): ?array {
        logger("SentryTrace::graphqlRequest url=$url");

        $hub = SentrySdk::getCurrentHub();
        $parentSpan = $hub->getSpan();

        $parsedUrl = parse_url($url);
        $domain = $parsedUrl['host'] ?? 'unknown';
        $path = $parsedUrl['path'] ?? '/';
        $operationName = self::extractOperationName($query);

        $spanContext = new SpanContext();
        $spanContext->setOp('graphql.client');
        $spanContext->setDescription("POST $path" . ($operationName ? " - $operationName" : ""));

        $span = $parentSpan ? $parentSpan->startChild($spanContext) : null;
        $startTime = microtime(true);

        try {
            $client = new Client();
            $mergedHeaders = array_merge([
                'Content-Type' => 'application/json',
                'sentry-trace' => getTraceparent(),
                'baggage' => getBaggage(),
            ], $headers);

            $response = $client->post($url, [
                'headers' => $mergedHeaders,
                'json' => [
                    'query' => self::cleanGraphQLQuery($query),
                    'variables' => $variables,
                ],
                'http_errors' => true,
            ]);

            $duration = round((microtime(true) - $startTime) * 1000, 2);
            $responseBody = (string) $response->getBody();
            $responseData = json_decode($responseBody, true);
            $responseSize = $response->hasHeader('Content-Length')
                ? (int) $response->getHeaderLine('Content-Length')
                : strlen($responseBody);

            if ($span) {
                $span->setStatus(SpanStatus::createFromHttpStatusCode($response->getStatusCode()));
                $span->setData([
                    'http.request.method' => 'POST',
                    'http.url' => $url,
                    'http.response.status_code' => $response->getStatusCode(),
                    'http.response.size' => $responseSize,
                    'graphql.query' => self::cleanGraphQLQuery($query),
                    'server.address' => $domain,
                    'request.duration_ms' => $duration,
                ]);
            }

            /*
            // Debugging HTTP requests
            error_log(print_r([
                'url' => $url,
                'method' => 'POST',
                'status' => $response->getStatusCode(),
                'date' => date('c')
            ], true));
            */

            return $responseData;
        } catch (RequestException $e) {
            if ($span) {
                $span->setStatus(SpanStatus::internalError());
                $span->setData([
                    'error' => $e->getMessage(),
                    'http.url' => $url,
                    'http.response.status_code' => $e->hasResponse() ? $e->getResponse()->getStatusCode() : null,
                    'graphql.query' => self::cleanGraphQLQuery($query),
                    'server.address' => $domain,
                ]);
            }

            throw $e;
        } catch (\Throwable $e) {
            if ($span) {
                $span->setStatus(SpanStatus::internalError());
                $span->setData([
                    'error' => $e->getMessage(),
                    'http.url' => $url,
                    'graphql.query' => self::cleanGraphQLQuery($query),
                    'server.address' => $domain,
                ]);
            }

            throw $e;
        } finally {
            if ($span) {
                $span->finish();
                $hub->setSpan($parentSpan);
            }
        }
    }
}