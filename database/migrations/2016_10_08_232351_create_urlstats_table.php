<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrlstatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_stats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('url_id');
            $table->string('platform')->nullable();
            $table->string('browser')->nullable();
            $table->string('ip')->nullable();
            $table->string('country_name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('http_referer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('url_stats');
    }
}
