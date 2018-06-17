<?php
/**
 * Created by PhpStorm.
 * User: riabo
 * Date: 06.04.2018
 * Time: 15:54
 */

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class PostType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Post',
        'description' => 'A post'
    ];

    public function fields()
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => ''
            ],
            'text' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'photo' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'rank' => [
                'type' => Type::int(),
                'description' => ''
            ],
            'private' => [
                'type' => Type::int(),
                'description' => ''
            ],
            'count_like' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'count_comments' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'count_posts' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'tag' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => ''
            ],
            'deleted' => [
                'type' => Type::int(),
                'description' => ''
            ],
            'user' => [
                'type' => \GraphQL::type('User'),
                'description' => ''
            ],
            'comment' => [
                'type' => Type::listOf(\GraphQL::type('Comment')),
                'description' => ''
            ],
            'like' => [
                'type' => Type::listOf(\GraphQL::type('Like')),
                'description' => ''
            ],
        ];
    }
}

