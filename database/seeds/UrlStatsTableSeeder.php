<?php

use Illuminate\Database\Seeder;

class UrlStatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(App\Url::class, 2)->create()->each(function($u) {
            $u->stats()->save(factory(App\UrlStat::class)->make());
        });
    }
}
