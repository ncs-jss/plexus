<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reset extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'token'
    ];
}
