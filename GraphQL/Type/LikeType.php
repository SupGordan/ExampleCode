<?php
/**
 * Created by PhpStorm.
 * User: riabo
 * Date: 07.04.2018
 * Time: 14:22
 */

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class LikeType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Like',
        'description' => 'A like'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => ''
            ],
            'user' => [
                'type' => \GraphQL::type('User'),
                'description' => ''
            ],
        ];
    }
}