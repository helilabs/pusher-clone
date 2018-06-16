<?php

namespace App\Realtime;

use Thruway\Peer\Client;
use React\ZMQ\SocketWrapper;
use React\EventLoop\LoopInterface;

class Pusher extends Client{
    /**
     * ZMQ SOCKET
     *
     * @var \React\ZMQ\SocketWrapper
     */
    private $socket;

    public function __construct( $realm,LoopInterface $loop, SocketWrapper $socket )
    {
        parent::__construct($realm, $loop);

        $this->socket = $socket;
    }

    public function onSessionStart($session, $transport)
    {
        $this->socket->on('message', [$this, 'transmit']);
    }

    public function transmit($payload)
    {
        $decodedPayload = json_decode($payload);
        dump($this->uniqueChannelName($decodedPayload));
        $this->getSession()->publish($this->uniqueChannelName($decodedPayload), [$decodedPayload->payload]);
    }

    private function uniqueChannelName($payload)
    {
        return str_replace('-','.', $payload->app_id . '-' . $payload->channel);
    }

}