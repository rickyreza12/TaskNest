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

            $serverKey = getenv('FCM_SERVER_KEY');
            $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

            $notification = [
                'title' => $payload['title'],
                'body' => $payload['body'],
            ];
        
            $deviceToken = '/topics/user-' . $payload['targetUserId'];

            $fields = [
                'to' => $deviceToken,
                'notification' => $notification,
                'data' => $payload,
            ];

            $headers = [
                'Authorization: key=' . $serverKey,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $fcmUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            $result = curl_exec($ch);
            if ($result === FALSE) {
                log_message('error', 'FCM Send Error: ' . curl_error($ch));
                CLI::error('FCM Send Error: ' . curl_error($ch));
            } else {
                log_message('info', 'FCM Send Success: ' . $result);
                CLI::write('Notification sent to FCM for User ID ' . $payload['targetUserId'], 'yellow');
            }
            curl_close($ch);
        };

        $channel->basic_consume('tasknest_notifications', '', false, true, false, false, $callback);

        while (true) {
            try {
                $channel->wait(null, false, 5); // wait 5 seconds max
            } catch (\PhpAmqpLib\Exception\AMQPTimeoutException $e) {
                // No new message within 5 seconds = NORMAL
                CLI::write('No new messages. Worker still alive...', 'light_blue');
                continue;
            } catch (\Exception $e) {
                // REAL Error
                log_message('error', 'Worker crashed: ' . $e->getMessage());
                CLI::error('Worker crashed: ' . $e->getMessage());
                break;
            }
        }
    
        $channel->close();
        $connection->close();
    }
}
