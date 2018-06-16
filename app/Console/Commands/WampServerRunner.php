<?php

namespace App\Console\Commands;

use App\Models\Token;
use React\ZMQ\Context;
use App\Realtime\Pusher;
use Thruway\Peer\Router;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Realtime\Auth\TokenAuth;
use Thruway\Transport\RatchetTransportProvider;
use Thruway\Authentication\AuthenticationManager;


class WampServerRunner extends Command{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wamp:server:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start Wamp Server';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $router = new Router();
        $loop = $router->getLoop();

        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind(getenv('ZMQ_HOST'));

        $router->registerModule(new AuthenticationManager);

        $router->addInternalClient(new TokenAuth(['default']));

        $router->addTransportProvider(new RatchetTransportProvider('0.0.0.0', 7474));
        $router->addInternalClient(new Pusher('default', $loop, $pull));

        $router->start();
    }


}