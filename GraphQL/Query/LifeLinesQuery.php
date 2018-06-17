<?php
/**
 * Created by PhpStorm.
 * User: riabo
 * Date: 07.04.2018
 * Time: 16:03
 */

namespace App\GraphQL\Query;

use App\Comments;
use App\Like;
use App\Post;
use App\Subscriber;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use App\User;

class LifeLinesQuery extends Query
{
    protected $attributes = [
        'name' => 'lifeline'
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Post'));
    }

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::listOf(Type::string())
            ],
            'user_id' => [
                'name' => 'user_id',
                'type' => Type::nonNull(Type::string())
            ],
            'rank' => [
                'name' => 'rank',
                'type' => Type::string()
            ],
            'private' => [
                'name' => 'private',
                'type' => Type::string()
            ],
            'page' => [
                'name' => 'page',
                'type' => Type::int()
            ],
        ];
    }

    public function resolve($root, array $args = [])
    {
        $sub = Subscriber::where('user_id', $args['user_id'])
            ->select(['sub_id'])
            ->get()
            ->toArray();
        list($keys, $sub_list) = array_divide($sub);
        array_push($sub_list, $args['user_id']);
        $query = Post::query()
            ->with('user')
            ->with('comments')
            ->with('comments.user')
            ->with('likes')
            ->where('deleted', '0')
            ->where('private', '1')
            ->latest();
        foreach ($args as $key => $value) {
            if ($key == "rank") {
                $query->where($key, ">=", $value);
            }
        }
        $query->whereIn('user_id', $sub_list);

        return $query->get()->forPage($args['page'], 5)
            ->map(function (Post $post) {
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

                    'user'           => [
                        'id'                  => $post->user->id,
                        'email'               => $post->user->email,
                        'name'                => $post->user->name,
                        'auth_token'          => $post->user->auth_token,
                        'confirm_email'       => $post->user->confirm_email,
                        'sub_count'           => $post->user->sub_count,
                        'nickname'            => $post->user->nickname,
                        'age'                 => $post->user->age,
                        'profile_photo'       => $post->user->profile_photo,
                        'profile_photo_mini'  => $post->user->profile_photo_mini,
                        'status'              => $post->user->status,
                        'background_photo'    => $post->user->background_photo,
                        'deleted'             => $post->user->deleted,
                    ],

                    'comment' => $post->comments->map(function (Comments $comments) {
                        return [
                            'id' => $comments->id,
                            'comment' => $comments->comment,
                            'user' => [
                                'id'                  => $comments->user->id,
                                'email'               => $comments->user->email,
                                'name'                => $comments->user->name,
                                'auth_token'          => $comments->user->auth_token,
                                'confirm_email'       => $comments->user->confirm_email,
                                'sub_count'           => $comments->user->sub_count,
                                'nickname'            => $comments->user->nickname,
                                'age'                 => $comments->user->age,
                                'profile_photo'       => $comments->user->profile_photo,
                                'profile_photo_mini'  => $comments->user->profile_photo_mini,
                                'status'              => $comments->user->status,
                                'background_photo'    => $comments->user->background_photo,
                                'deleted'             => $comments->user->deleted,
                            ]
                        ];
                    }),

                    'like' => $post->likes->map(function (Like $like) {
                        return [
                            'id' => $like->id,
                            'user' => [
                                'id'                  => $like->user->id,
                                'email'               => $like->user->email,
                                'name'                => $like->user->name,
                                'auth_token'          => $like->user->auth_token,
                                'confirm_email'       => $like->user->confirm_email,
                                'sub_count'           => $like->user->sub_count,
                                'nickname'            => $like->user->nickname,
                                'age'                 => $like->user->age,
                                'profile_photo'       => $like->user->profile_photo,
                                'profile_photo_mini'  => $like->user->profile_photo_mini,
                                'status'              => $like->user->status,
                                'background_photo'    => $like->user->background_photo,
                                'deleted'             => $like->user->deleted,
                            ]
                        ];
                    })
                ];
            });
    }
}

