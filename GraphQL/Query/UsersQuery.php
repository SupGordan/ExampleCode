<?php
/**
 * Created by PhpStorm.
 * User: riabo
 * Date: 06.04.2018
 * Time: 15:28
 */

namespace App\GraphQL\Query;

use App\Friends;
use App\Post;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use App\User;
use Illuminate\Support\Facades\Auth;

class UsersQuery extends Query
{
    protected $attributes = [
        'name' => 'users'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('User'));
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::string()
            ],
            'email' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'deleted' => [
                'type' => Type::int(),
                'description' => ''
            ],
        ];
    }

    public function resolve($root,array $args = [])
    {
        $query = User::query()
            ->with('posts')
            ->with('friends');
        foreach ($args as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get()
            ->map(function (User $user){
               return [
                   'id'                  => $user->id,
                   'email'               => $user->email,
                   'name'                => $user->name,
                   'auth_token'          => $user->auth_token,
                   'confirm_email'       => $user->confirm_email,
                   'sub_count'           => $user->sub_count,
                   'nickname'            => $user->nickname,
                   'age'                 => $user->age,
                   'profile_photo'       => $user->profile_photo,
                   'profile_photo_mini'  => $user->profile_photo_mini,
                   'status'              => $user->status,
                   'background_photo'    => $user->background_photo,
                   'deleted'             => $user->deleted,
                   'post'                => $user->posts->map(function (Post $post) {
                       return [
                           'id'             => $post->id,
                           'text'           => $post->text,
                           'photo'          => $post->photo,
                           'rank'           => $post->rank,
                           'tag'            => $post->tag,
                           'private'        => $post->private,
                           'count_comments' => $post->count_comments,
                           'count_like'     => $post->count_like,
                           'deleted'        => $post->deleted,
                           'created_at'     => $post->created_at->format('YmdHi'),
                       ];
                   }),
                   'friend'                => $user->friends->where('confirm', 1)->map(function (Friends $friend) {
                       return [
                           'id' => $friend->id,
                           'confirm' => $friend->confirm,
                           'user' => [
                               'id'                  => $friend->user->id,
                               'email'               => $friend->user->email,
                               'name'                => $friend->user->name,
                               'auth_token'          => $friend->user->auth_token,
                               'confirm_email'       => $friend->user->confirm_email,
                               'sub_count'           => $friend->user->sub_count,
                               'nickname'            => $friend->user->nickname,
                               'age'                 => $friend->user->age,
                               'profile_photo'       => $friend->user->profile_photo,
                               'profile_photo_mini'  => $friend->user->profile_photo_mini,
                               'status'              => $friend->user->status,
                               'background_photo'    => $friend->user->background_photo,
                               'deleted'             => $friend->user->deleted,
                           ]
                       ];
                   }),
               ];
            });

    }
}