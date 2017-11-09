<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShortenTest extends \TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * It also tests creating with custom alias/slug.
     */
    public function it_may_create_a_new_shorten()
    {
        $shorten = factory('App\Url')->make();

        $this->post('/shorten', $shorten->toArray())
            ->seePageIs('/shorten')
            ->assertResponseStatus(200);
    }

    /** @test */
    public function it_may_create_a_new_private_shorten()
    {
        $shorten = factory('App\Url')->make(['is_private' => true]);

        $this->post('/shorten', $shorten->toArray())
            ->seePageIs('/shorten')
            ->seeStatusCode(200);
    }

    /** @test */
    public function it_redirects_properly()
    {
        $shorten = factory('App\Url')->create(['url' => 'https://google.com']);

        $this->get($shorten->shortenUrl())
            ->assertRedirectedTo($shorten->url);
    }

    /** @test */
    public function it_should_add_a_new_shorten_in_my_shortens_at_home_page()
    {
        $myShorten = factory('App\Url')->create();

        $this->withSession([['myShortens' => $myShorten->slug]])
            ->visit('/')
            ->see($myShorten->url);
    }

    /** @test */
    public function it_should_add_a_new_shorten_in_newest_shortens_at_home_page()
    {
        $newestShorten = factory('App\Url')->create();

        $this->visit('/')
            ->see($newestShorten->url);
    }

    /** @test */
    public function are_stats_count_properly()
    {
        $shortenWithoutStats = factory('App\Url')->create();
        $shortenWithStats = factory('App\Url')->create();

        for($i = 0; $i < 5; $i++)
            $shortenWithStats->createStat();

        $this->visit($shortenWithoutStats->shortenUrl() . '/stats')
            ->seeStatusCode(200)
            ->seeInElement('span.tag.is-dark.is-large', 0);

        $this->visit($shortenWithStats->shortenUrl() . '/stats')
            ->seeStatusCode(200)
            ->seeInElement('span.tag.is-dark.is-large', 5);
    }
}
