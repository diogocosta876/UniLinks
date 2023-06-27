<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $primaryKey = 'id_community';

    protected $table = 'community';

    /**
     * The posts this user owns.
     */
    public function members() {
      return $this->hasMany('App\Models\Account')
                            ->using('App\Models\Relationship');
    }
}
