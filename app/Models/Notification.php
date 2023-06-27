<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps  = false;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
    protected $primaryKey = 'id_notification';

    protected $table = 'notification';

    function receiver() {
        return $this->belongsTo('App\Models\User');
    }
}
