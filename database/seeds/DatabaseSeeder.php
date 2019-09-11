<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		DB::insert('INSERT INTO lti2_consumer (consumer_pk, name, consumer_key256, consumer_key, secret, lti_version, consumer_name, consumer_version, consumer_guid, profile, tool_proxy, settings, protected, enabled, enable_from, enable_until, last_access, created, updated) VALUES (1, \'Testing\', \'45a79088c716dc26c068d2b601b4d9af\', null, \'552fc36963781cef0de2c33f1a891c4b\', \'LTI-1p0\', \'Class 713\', \'canvas-cloud\', \'8865aa05b4b79b64a91a86042e43af5ea8ae79eb.localhost\', null, null, \'a:0:{}\', 0, 1, null, null, null, \'2019-08-29 10:29:41\', \'2019-08-30 13:48:49\');');
    }
}
