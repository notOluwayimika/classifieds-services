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
            // Process the received message
            $this->processMessage($msg->body);
            // Send a new message after processing
            $newMessage = 'Processed ' . $msg->body;
            $this->sendMessage('processed_queue', $newMessage);
        };

        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        // Loop to keep the script running
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function processMessage($message)
    {
        // Your message processing logic here
        echo " [x] Processing ", $message, "\n";
    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
