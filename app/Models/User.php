<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_account';
    protected $table = "account";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'password',
        'account_tag',
        'name',
        'age',
        'birthday',
        'is_private',
        'email',
        'university',
        'course',
        'is_verified',
        'description',
        'location',
        'pronouns',
        'is_admin',
        'is_blocked'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The posts this user owns.
     */
    public function posts() {
        return $this->hasMany('App\Models\Post', 'owner_id');
    }

    /**
     * The communities this user owns.
     */
    public function communities() {
        return $this->hasMany('App\Models\Community');
    }

    /**
     * The account reports this user owns.
     */
    public function accountReports() {
        return $this->hasMany('App\Models\AccountReport');
    }

    /**
     * The post reports this user owns.
     */
    public function postReports() {
        return $this->hasMany('App\Models\PostReport');
    }

    /**
     * The friendRequests this user owns.
     */
    public function friendRequests() {
        return $this->hasMany('App\Models\FriendRequest');
    }

    /**
     * The notifications this user owns.
     */
    public function notifications() {
        return $this->hasMany('App\Models\Notification');
    }

    /**
     * The recovery code this user owns.
     */
    public function recoveryCode() {
        return $this->hasOne('App\Models\RecoveryCode');
    }

    /**
     * The relationships this user owns.
     */
    public function relationships() { // related to communities
        return $this->hasMany('App\Models\Relationship');
    }

    /**
     * The posts promoted by this user.
     */
    public function promotedPosts() {
        return $this->hasMany('App\Models\PostPromotion');
    }

    /**
     * The posts reacted by this user.
     */
    public function reactedPosts() {
        return $this->hasMany('App\Models\PostReaction');
    }

    /**
     * The friendships this user owns.
     */
    public function friends() { 
        return $this->hasMany('App\Models\Friendship');
    }

    /**
     * This user's friendships.
     */
    public function friendships() {
        $friendships1 = \App\Models\User::join('friendship', 'account.id_account', '=', 'friendship.account2_id')->where('friendship.account1_id', $this->id_account)->get();
        $friendships2 = \App\Models\User::join('friendship', 'account.id_account', '=', 'friendship.account1_id')->where('friendship.account2_id', $this->id_account)->get();
        $friendships = $friendships1->merge($friendships2);
        return $friendships;
    }

}
