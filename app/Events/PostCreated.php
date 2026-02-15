<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post_html;
    public $threadId;

    public function __construct(Post $post)
    {
        $this->threadId = $post->thread_id;

        // threadもロードして渡す
        $post->load('thread');
        $this->post_html = view('components.posts.post-item', [
            'post' => $post,
            'thread' => $post->thread
        ])->render();
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
