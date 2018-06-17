<?php
/**
 * Created by PhpStorm.
 * User: riabo
 * Date: 07.04.2018
 * Time: 14:46
 */

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class FriendType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Friend',
        'description' => 'A friend'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => ''
            ],
            'user_id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => ''
            ],
            'friend' => [
                'type' => Type::nonNull(Type::string()),
                'description' => ''
            ],
            'confirm' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'user' => [
                'type' => \GraphQL::type('User'),
                'description' => ''
            ],
        ];
    }
}