<?php
// app/Services/MessageService.php
namespace App\Services;

use App\Models\Orders;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class MessageService {
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
    }
    public function sendMessage($queue, $message) {
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

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    public function processMessage($message)
    {
        // Your message processing logic here
        echo " [x] Processing ", $message, "\n";
        $response = json_decode($message);

        
        try{
            $order = Orders::findOrFail($response->id);
            $order->status = $response->status;
            $order->save();
            echo "order delivered";
        }catch(Exception $e){
            echo $e->getMessage();
        }

    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
