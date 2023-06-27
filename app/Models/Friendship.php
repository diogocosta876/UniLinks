<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    public $timestamps  = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    protected $primaryKey = ['account1_id', 'account2_id'];

    protected $table = 'friendship';

    function friend1() {
        return $this->hasMany('App\Models\Friendship');
    }

    function friend2() {
        return $this->hasMany('App\Models\Friendship');
    }
}
