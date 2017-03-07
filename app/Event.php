<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'eventName', 'eventDes', 'startTime', 'endTime', 'duration', 'totalQues', 'societyId', 'type', 'approve', 'active', 'forum', 'winners'
    ];
}
