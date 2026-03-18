<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;


class PostCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public int $postId;
    public int $threadId;

    public function __construct(Post $post)
    {
        $this->postId = $post->id;
        $this->threadId = $post->thread_id;
    }


    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('thread.' . $this->threadId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'post.created';
    }
}
