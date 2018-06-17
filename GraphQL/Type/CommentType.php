<?php
/**
 * Created by PhpStorm.
 * User: riabo
 * Date: 06.04.2018
 * Time: 23:30
 */

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class CommentType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Post',
        'description' => 'A post'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
                'description' => ''
            ],
            'comment' => [
                'type' => Type::nonNull(Type::string()),
                'description' => ''
            ],
            'user' => [
                'type' => \GraphQL::type('User'),
                'description' => ''
            ],
        ];
    }
}