<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'userDetails';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'admissionNo', 'college', 'userId', 'contact', 'details'
    ];

}
