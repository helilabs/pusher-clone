<?php

use App\Models\Token;
use App\Models\PushRequest;
use Illuminate\Support\Facades\Artisan;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class PushApiTest extends TestCase
{
    use DatabaseTransactions;


    public function setUp()
    {
        parent::setUp();

        $this->token = Token::where('app_id', '9176bf73-f569-47bb-a50e-74e20f709b83')->first();
    }

    /** @test */
    public function can_send_push_request()
    {
        $res = $this->json('post', 'api/push', [
            'channel' => 'chat',
            'data' => [
                'text' => 'My name is Mohammed Manssour and I\'m a senior software engineer'
            ]
            ],[
                'authorization' => "Bearer {$this->token->secret}",
                'X-APP-ID' => $this->token->app_id
            ]);

        $this->seeStatusCode(200);
        $this->seeJson(['meta' => generate_meta('success')]);
        $this->seeInDatabase('push_requests', [
            'channel' => 'chat'
        ]);
    }
}
