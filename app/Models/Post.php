<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  // Don't add create and update timestamps in database.
  public $timestamps  = false;

  protected $primaryKey = 'id_post';

  protected $table = 'post';

  /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
  public $incrementing = true;

  /**
   * The user this post belongs to
   */
  public function user() {
    return $this->belongsTo(User::class);
  }

  /**
   * This post's parent
   */
  public function parent() {
    return $this->belongsTo(Post::class);
  }

  /**
   * This post's comments
   */
  public function comments() {
    return $this->hasMany(Post::class);
  }
}
