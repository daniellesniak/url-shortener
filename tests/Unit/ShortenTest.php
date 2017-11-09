<?php

namespace Tests\Unit;


use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShortenTest extends \TestCase
{
    use DatabaseMigrations;

    protected $shorten;

    public function setUp()
    {
        parent::setUp();
        $this->shorten = factory('App\Url')->create();
    }

    /** @test */
    public function a_shorten_has_stats()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->shorten->stats);
    }

    /** @test */
    public function a_shorten_generates_a_valid_shorten_url()
    {
        $excepted = url('/') . '/' . $this->shorten->slug;
        $this->assertEquals($excepted, $this->shorten->shortenUrl());
    }

    /** @test */
    public function a_shorten_counts_redirects_correctly()
    {
        $this->shorten->createStat();
        $this->shorten->createStat();

        $this->assertEquals($this->shorten->stats()->count(), $this->shorten->getRedirectsCount());
    }

    /** @test */
    public function a_shorten_can_create_the_stats()
    {
        for($i = 0; $i < 5; $i++)
            $this->shorten->createStat();

        $this->assertCount(5, $this->shorten->stats);
    }

    /** @test */
    public function a_shorten_returns_a_valid_country_code_for_given_country_name()
    {
        $stat = factory('App\UrlStat')->create(['url_id' => $this->shorten->id, 'country_name' => 'Poland', 'country_code' => 'PL']);
        $country_code = $this->shorten->getCountryCode('Poland');

        $this->assertEquals('PL', $country_code);
    }
}