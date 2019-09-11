<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector;
use IMSGlobal\LTI\ToolProvider\ToolConsumer;

class CreateLTIConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kpas:lti-consumer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates LTI consumer keys';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $db = DB::connection()->getPdo();
        $dataConnector = DataConnector::getDataConnector('', $db, 'pdo');

        $consumer = new ToolConsumer(Str::random(32), $dataConnector);
        $consumer->name = 'KPAS Canvas';
        $consumer->secret = Str::random(32);
        $consumer->enabled = true;
        $consumer->save();

        $this->info('Consumer client: ' . $consumer->getKey());
        $this->info('Consumer secret: ' . $consumer->secret);
    }
}
