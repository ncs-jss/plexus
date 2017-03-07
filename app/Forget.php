<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forget extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'forget';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'socEmail', 'token'
    ];
}
