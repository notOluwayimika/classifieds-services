<?php 
// app/Console/Commands/ReceiveAndSendMessages.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MessageService;

class ReceiveAndSendMessages extends Command
{
    protected $signature = 'receive:andsend {queue}';
    protected $description = 'Receive messages from RabbitMQ and send a new message after processing';

    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        parent::__construct();
        $this->messageService = $messageService;
    }

    public function handle()
    {
        $queue = $this->argument('queue');
        $this->messageService->receiveMessages($queue);
    }
}
