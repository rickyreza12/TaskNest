<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class NotificationWorker extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'notification:worker';
    protected $description = 'Consume tasknest_notifications queue and simulate sending notification';

    public function run(array $params)
    {
        CLI::write('Starting Notification Worker...', 'green');

        $connection = new AMQPStreamConnection(
            getenv('RABBITMQ_HOST'),
            getenv('RABBITMQ_PORT'),
            getenv('RABBITMQ_USER'),
            getenv('RABBITMQ_PASSWORD')
        );
        $channel = $connection->channel();

        $channel->queue_declare('tasknest_notifications', false, true, false, false);

        $callback = function ($msg) {
            $payload = json_decode($msg->body, true);

            // Simulate sending notification (you can replace this with real FCM logic later)
            log_message('info', 'Sending notification: ' . json_encode($payload));
            CLI::write('Notification sent to User ID ' . $payload['targetUserId'] . ': ' . $payload['title'], 'yellow');
        };

        $channel->basic_consume('tasknest_notifications', '', false, true, false, false, $callback);

        try {
            while (count($channel->callbacks)) { // <= Check if there are active consumers
                $channel->wait(null, false, 5); // 5 seconds timeout
            }
        } catch (\PhpAmqpLib\Exception\AMQPTimeoutException $e) {
            // Just catch timeout, continue running
            CLI::write('Waiting for message timeout, still alive...', 'light_blue');
        } catch (\Exception $e) {
            log_message('error', 'Worker crashed: ' . $e->getMessage());
            CLI::error('Worker crashed: ' . $e->getMessage());
        }
        
    
        $channel->close();
        $connection->close();
    }
}
