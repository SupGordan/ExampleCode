<?php
/**
 * Created by PhpStorm.
 * User: riabo
 * Date: 23.05.2018
 * Time: 21:13
 */

namespace App\GraphQL\Mutation;


use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use App\User;


class NullMutation extends Mutation
{
    protected $attributes = [
        'name' => 'NullMutation'
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
    }
}