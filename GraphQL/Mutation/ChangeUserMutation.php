<?php
/**
 * Created by PhpStorm.
 * User: riabo
 * Date: 09.04.2018
 * Time: 21:48
 */

namespace App\GraphQL\Mutation;


use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\User;


class ChangeUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'ChangeUser'
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args() {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::string()
            ],
            'email' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'name' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'auth_token' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'nickname' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'age' => [
                'type' => Type::int(),
                'description' => ''
            ],
            'profile_photo' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'profile_photo_mini' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'status' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'background_photo' => [
                'type' => Type::string(),
                'description' => ''
            ],
        ];
    }

    public function resolve($root,array $args = [])
    {
        $user = User::find($args['id']);
        if (!$user || $user->auth_token != $args['auth_token']) {
            return null;
        }
        if(isset($args['name']))
            $user->name = $args['name'];
        if(isset($args['nickname']))
            $user->nickname = $args['nickname'];
        if(isset($args['status']))
            $user->status = $args['status'];
        if(isset($args['age']))
            $user->age = $args['age'];
        $user->save();

        return $user;
    }
}