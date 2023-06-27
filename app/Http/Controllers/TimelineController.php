<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\User;
use App\Models\Relationship;


class TimelineController extends Controller
{
    /**
     * Shows all posts.
     *
     * @return Response
     */
    public function list()
    {
      if (!Auth::check()) return redirect('/login');
      $this->authorize('list', Post::class);
      $posts = User::join('post', 'account.id_account', '=', 'post.owner_id')->get(['post.*', 'account.name', 'account.account_tag'])->sortByDesc('edited_date'); //TODO: Show interesting posts

      $groups = Relationship::join('community', 'community.id_community', '=', 'relationship.id_community')
                              ->where('id_account', '=', Auth::user()->id_account)
                              ->get();

                              if (!Auth::check()) return redirect('/login');
                              $user = Auth::user();
                              $account_tag = $user->account_tag;
                              $user = User::where('account_tag', '=', $account_tag)->first();
                              if (!$user) return "No user found";
                        
                              $posts = User::join('post', 'account.id_account', '=', 'post.owner_id')
                                          ->where('account.id_account', '=', $user->id_account)
                                          ->get(['post.*', 'account.name', 'account.account_tag'])->sortByDesc('edited_date');
                        
                              $friendships1 = \App\Models\User::join('friendship', 'account.id_account', '=', 'friendship.account2_id')->where('friendship.account1_id', $user->id_account)->get();
                              $friendships2 = \App\Models\User::join('friendship', 'account.id_account', '=', 'friendship.account1_id')->where('friendship.account2_id', $user->id_account)->get();
                              $friendships = $friendships1->merge($friendships2);
                        
                              $strangerFriendIDs = [];
                              foreach ($friendships as $friend) {
                                if ($friend->id_account != Auth::user()->id_account)
                                  array_push($strangerFriendIDs, $friend->id_account);
                              }
                        
                              $friendships3 = \App\Models\User::join('friendship', 'account.id_account', '=', 'friendship.account2_id')->where('friendship.account1_id', Auth::user()->id_account)->get();
                              $friendships4 = \App\Models\User::join('friendship', 'account.id_account', '=', 'friendship.account1_id')->where('friendship.account2_id', Auth::user()->id_account)->get();
                              $userFriendships = $friendships3->merge($friendships4);


      return view('pages.timeline', ['posts' => $posts, 'groups' => $groups, 'type' => 'profile', 'friendships' => $friendships, 'user' => $user]);
    }
}
