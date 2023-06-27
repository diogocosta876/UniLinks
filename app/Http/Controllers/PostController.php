<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\PostCreationRequest;

use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Shows the post for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
      $post = User::join('post', 'account.id_account', '=', 'post.owner_id')
                  ->where('post.id_post', '=', $id)
                  ->first(['post.*', 'account.name', 'account.account_tag']);

      return view('pages.post', ['post' => $post]);
    }

    /**
     * Shows all posts.
     *
     * @return Response
     */
    public function list()
    {
      if (!Auth::check()) return redirect('/login');
      //$this->authorize('list', Post::class);
      $posts = Post::all()->sortBy('edited_date');
      return view('pages.posts', ['posts' => $posts]);
    }

    /**
     * Creates a new post.
     *
     * @return Post The post created.
     */
    public function create(PostCreationRequest $request)
    {
      $validated = $request->validated();

      $post = new Post();

      $this->authorize('create', $post);
      $post->parent_post = null;
      $post->owner_id = Auth::user()->id_account;
      $post->group_id = $request->input('group') ? $request->input('group') : null;
      $post->description = $request->input('description');
      $post->has_images = false;
      $thisdate = now();
      $post->publication_date = $thisdate;
      $post->edited_date = $thisdate;
      $post->comments_count = 0;
      $post->is_visible = true;
      $post->save();

      return redirect()->back();
    }

    public function delete(Request $request, $id)
    {
      $post = Post::find($id);

      $this->authorize('delete', $post);
      $post->delete();

      return $post;
    }
}
