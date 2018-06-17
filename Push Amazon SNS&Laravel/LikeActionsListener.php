<?php

namespace App\Listeners;

use App\Events\LikeAddedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Handlers\OneSignalHandler;
use AWS;
use App\User;
use App\Post;


class LikeActionsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(LikeAddedEvent $event)
    {
        $like = $event->like;
        $user = User::find($like->user_id);
        $id =  Post::find($like->post_id)->user_id;
        $title = $user->name.' оценил вашу запись';
            $client = AWS::createClient('sns');
             $result = $client->publish([
            'MessageStructure' => 'json',
            'Message' => json_encode([
                    'default' => $title,
                    'email' => json_encode([
                        'aps' => array(
                            'alert' => $title,
                        ),
                        'user_id' => $id, 
                    ])
                ]),
            'TopicArn' => 'TopicArn',
            ]);
    }
}
