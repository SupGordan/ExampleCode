<?php
/**
 * Created by PhpStorm.
 * User: riabo
 * Date: 06.04.2018
 * Time: 15:23
 */

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A user'
    ];

    public function fields() {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => ''
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
            'confirm_email' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'sub_count' => [
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
            'deleted' => [
                'type' => Type::int(),
                'description' => ''
            ],
            'post' => [
                'type' => Type::listOf(\GraphQL::type('Post')),
                'description' => ''
            ],
            'friend' => [
                'type' => Type::listOf(\GraphQL::type('Friend')),
                'description' => ''
            ],
        ];
    }

}