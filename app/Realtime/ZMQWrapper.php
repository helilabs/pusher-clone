<?php

namespace App\Realtime;

class ZMQWrapper{

    /**
     * ZMQSocket
     *
     * @var \ZMQSocket
     */
    private $socket;

    /**
     * create ZMQWrapper Context
     *
     * @param \ZMQContext $context
     * @param string $host
     */
    public function __construct(\ZMQContext $context, $host)
    {
        $this->socket = $context->getSocket(\ZMQ::SOCKET_PUSH);
        $this->socket->connect($host);
    }

    public function send($payload)
    {
        if(is_array($payload)){
            $payload = json_encode($payload);
        }

        $this->socket->send($payload);
    }

}