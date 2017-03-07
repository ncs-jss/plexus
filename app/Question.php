<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
         * Table name.
         *
         * @var string
         */
        protected $table = 'questions';

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'eventId', 'question', 'options', 'image', 'html', 'type', 'level'
        ];
}
