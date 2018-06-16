<?php

use Thruway\Realm;
use React\ZMQ\Context;
use App\Realtime\Pusher;
use Thruway\Peer\Router;
use App\Realtime\Auth\TokenAuth;
use Thruway\Transport\RatchetTransportProvider;
use Thruway\Authentication\AuthenticationManager;

require __DIR__.'/bootstrap/app.php';

$router = new Router();
$loop = $router->getLoop();

$context = new Context($loop);
$pull = $context->getSocket(\ZMQ::SOCKET_PULL);
$pull->bind(getenv('ZMQ_HOST'));

$router->registerModule(new \Thruway\Authentication\AuthenticationManager);

$router->addInternalClient(new TokenAuth(['default']));

$router->addTransportProvider(new RatchetTransportProvider('0.0.0.0', 7474));
$router->addInternalClient(new Pusher('default', $loop, $pull));

$router->start();