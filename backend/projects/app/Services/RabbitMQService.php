<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            getenv('RABBITMQ_HOST'),
            getenv('RABBITMQ_PORT'),
            getenv('RABBITMQ_USER'),
            getenv('RABBITMQ_PASSWORD')
        );
        $this->channel = $this->connection->channel();
    }

    public function publish(string $queue, array $data)
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage(json_encode($data), [
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($msg, '', $queue);
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
