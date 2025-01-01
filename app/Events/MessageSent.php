<?php

namespace App\Events;

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;

        // Menambahkan log untuk memastikan event dipanggil
        Log::info("MessageSent event triggered", ['message' => $this->message]);
    }

    public function broadcastOn()
    {
        return new Channel('public-message');
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }
}

