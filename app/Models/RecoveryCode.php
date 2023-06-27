<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecoveryCode extends Model
{
    public $timestamps  = false;

    protected $primaryKey = 'id_recovery_code';

    protected $table = 'recovery_code';

    protected $fillable = [
        'id_account',
        'code',
        'valid_until'
    ];


    /**
     * The recovery code this user owns.
     */
    public function user() {
        return $this->hasOne('App\Models\User');
    }

}
