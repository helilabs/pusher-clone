<?php

use App\Models\Token;
use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TokenApiTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_create_new_token_via_command()
    {
        Artisan::call('make:token');

        // assert the command have created a new token
        $this->assertNotNull(Token::first());
    }

}
