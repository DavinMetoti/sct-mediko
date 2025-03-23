<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class QuizUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $quizAttempt;

    public function __construct($quizAttempt)
    {
        $this->quizAttempt = $quizAttempt;
    }

    public function broadcastOn()
    {
        return new Channel('quiz-channel');
    }

    public function broadcastAs()
    {
        return 'quiz-updated';
    }

    public function broadcastWith()
    {
        return ['data' => $this->quizAttempt];
    }
}
