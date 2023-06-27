<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class PostPolicy
{
    use HandlesAuthorization;

    // REMOVE
    public function show(User $user, Post $post)
    {
      // The post should be visible if the owner's profile isn't private or in case it is, if the user is frinds with him
      return $user->id == $post->user_id;
    }

    // REMOVE
    public function list(User $user)
    {
      // Any user can list posts
      return Auth::check();
    }

    public function create(User $user)
    {
      // Any user can create a new post
      return Auth::check();
    }

    public function delete(User $user, Post $post)
    {
      // Only a post owner can delete it
      return $user->id == $post->user_id;
    }
}
