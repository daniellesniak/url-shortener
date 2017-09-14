<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeTest extends TestCase
{
    use DatabaseMigrations;
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
     * Test shorten form creating.
     *
     * @return void
     */
    public function testCreateShortenForm()
    {
        $this->visit('/')
            ->select('https://', 'protocol_select')
            ->type('https://stackoverflow.com/questions/11828270/how-to-exit-the-vim-editor', 'url')
            ->press('SHORTEN')
            ->seePageIs('/')
            ->seeStatusCode(200);
    }

    /**
     * Test shorten post creating.
     *
     * @return void
     */
    public function testCreateShortenPostWithSession()
    {
        $response = $this->call('POST', '/', [
           'protocol_select' => 'https://',
           'url' => 'google.com',
            'is_private' => false
        ]);

        $this->assertResponseOk()->assertSessionHas('urlIDs');
    }
}
