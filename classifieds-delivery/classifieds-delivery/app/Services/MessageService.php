<?php 
// app/Services/MessageService.php
namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessageService
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
    }

    public function sendMessage($queue, $message)
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage($message, ['delivery_mode' => 2]);
        $this->channel->basic_publish($msg, '', $queue);

        echo " [x] Sent ", $message, "\n";
    }

    public function receiveMessages($queue)
    {
        $this->channel->queue_declare($queue, false, true, false, false);

        echo " [*] Waiting for messages in ", $queue, ". To exit press CTRL+C\n";

        $callback = function ($msg) {
            echo " [x] Received ", $msg->body, "\n";
            sleep(5);
            // Process the received message
            $this->processMessage($msg->body);
            // Send a new message after processing
            $body = json_decode($msg->body);
            $statusresponse= [
                "id"=>$body->id,
                "status"=>"delivered"
            ];
            $this->sendMessage('orders', json_encode($statusresponse));
            foreach ($body->listings as $listing ) {
                $stockresponse= [
                    "id"=>$listing->id,
                    "quantity"=>$listing->quantity
                ];
                sleep(5);
                $this->sendMessage('listings', json_encode($stockresponse));
            }
        };

        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function processMessage($message)
    {
        // Your message processing logic here
        echo " [x] Processing ", $message, "\n";
        sleep(5);
    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
