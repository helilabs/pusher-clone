<?php

use App\Models\Token;
use App\Models\PushRequest;
use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthorizationApiTest extends TestCase
{
    use DatabaseTransactions;


    public function setUp()
    {
        parent::setUp();

        $this->token = Token::where('app_id', '9176bf73-f569-47bb-a50e-74e20f709b83')->first();
    }

    /** @test */
    public function can_give_permission_to_subscribe_to_channel()
    {
        $res = $this->json('post', 'api/push/auth', [
            'channel' => 'chat',
            // 'session_id' => '1234'
            ],[
                'authorization' => "Bearer {$this->token->secret}",
                'X-APP-ID' => $this->token->app_id
            ]);

        $this->seeStatusCode(200);
        $this->seeJson(['meta' => generate_meta('success')]);
        $this->seeInDatabase('authorizations', [
            'channel' => 'chat',
            'session_id' => '1234'
        ]);
    }
}
