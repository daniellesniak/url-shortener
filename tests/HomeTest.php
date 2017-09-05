<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicThings()
    {
        $this->visit('/')
            ->seeStatusCode(200);

        $this->visit('/')
            ->click('Home')
            ->seePageIs('/');
    }

    /**
     * Test shorten creating.
     *
     * @return void
     */
    public function testCreateShorten()
    {
        $this->visit('/')
            ->select('https://', 'url_with_protocol')
            ->type('https://stackoverflow.com/questions/11828270/how-to-exit-the-vim-editor', 'url')
            ->press('SHORTEN')
            ->seePageIs('/')
            ->seeStatusCode(200);
    }
}
