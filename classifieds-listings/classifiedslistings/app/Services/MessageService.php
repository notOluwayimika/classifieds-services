<?php
// app/Services/MessageService.php
namespace App\Services;

use App\Models\Listings;
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

        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, '', $queue);

        $this->channel->close();
        $this->connection->close();
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
            // $newMessage = 'Processed ' . $msg->body;
            // $this->sendMessage('processed_queue', $newMessage);
        };

        $this->channel->basic_consume($queue, '', false, true, false, false, $callback);

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function processMessage($message)
    {
        // Your message processing logic here
        echo " [x] Processing ", $message, "\n";
        $response = json_decode($message);
        // echo json_encode($response->listings);
        // $listing = Listings::findOrFail();
        $listing = Listings::findOrFail($response->id);
        $listing->stock = $listing->stock - $response->quantity;
        $listing->save();
    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
