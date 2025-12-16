<?php

namespace App\Console\Commands;

use App\Services\DataNsrService;
use App\Utils\SentryTrace;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class FetchNsrData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch_from:nsr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching data from nsr ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        SentryTrace::new($this->signature, 'console');
        SentryTrace::setContext('monitor', [
            'job_name' => $this->signature,
            'job_description' => $this->description,
            'name' => 'scheduled_artisan-fetch-from-nsr',
            'slug' => 'scheduled_artisan-fetch-from-nsr',
        ]);

        $transaction = SentryTrace::getTransaction();
        SentryTrace::setSpan(null); // ?NOTE: Disable tracing for this command to avoid wasting quota for long running command that runs daily

        try {
            $client = new Client();
            $nsr = new DataNsrService($client);

            $nsr->store_counties();
            $nsr->store_communities();

            logger("nsr::store_schools");
            $nsr->store_schools();

            logger("nsr::store_kindergartens");
            $nsr->store_kindergartens();
            
            SentryTrace::setSpan($transaction);
            SentryTrace::finish(null);
            return Command::SUCCESS;
        } catch (\Throwable $th) {
            SentryTrace::setSpan($transaction);
            
            logger()->error('NSR data synchronization failed.', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            \Sentry\captureException($e);

            SentryTrace::finish(null, 500);
            throw $th;
        }
    }
}
