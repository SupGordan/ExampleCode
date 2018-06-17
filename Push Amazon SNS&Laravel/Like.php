<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\Event;
use App\Events\LikeAddedEvent;

class Like extends Model
{
    const STATUS_PUBLISHED = 1;

    public function post() {
        return $this->belongsTo('App\Post');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public static function boot() {
        static::saving(function($instance) {
            if($instance->notify_status < self::STATUS_PUBLISHED) {
                $instance->notify_status = self::STATUS_PUBLISHED;
                \Event::fire(new LikeAddedEvent($instance));
            }
        });
        parent::boot();
    }
}
