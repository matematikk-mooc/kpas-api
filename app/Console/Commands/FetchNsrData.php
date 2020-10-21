<?php

namespace App\Console\Commands;

use App\Services\DataNsrService;
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
        $client = new Client();
        $nsr = new DataNsrService($client);
        $nsr->store_counties();
        $nsr->store_communities();
        logger("Store schools");
        $nsr->store_schools();
        logger("Store kindergartens");
        $nsr->store_kindergartens();
    }
}
