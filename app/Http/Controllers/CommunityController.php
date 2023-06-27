<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Community;
use App\Models\Notification;
use App\Models\Relationship;
use App\Models\User;
use App\Models\Post;

use Validator;

class CommunityController extends Controller
{
    /**
     * Shows the post for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
      $community = Community::find($id);
      if (!$community) return redirect(route('timeline'));
      //$this->authorize('show', $post); //TODO: show only if authenticated
      $posts = Post::where('group_id', '=', $id)->get()->sortByDesc('edited_date');
      
      $admins = User::join('relationship', 'relationship.id_account', '=', 'account.id_account')
                      ->where('relationship.id_community', '=', $id)
                      ->where('relationship.status', '=', 'admin')
                      ->get();

      $members = User::join('relationship', 'relationship.id_account', '=', 'account.id_account')
                      ->where('relationship.id_community', '=', $id)
                      ->where('relationship.status', '=', 'member')
                      ->get();

      foreach ($members as $friend) {
        $friend->group = $id;
      }
      
      $status = "visitor";

      if (Auth::check()) {
        $status = Relationship::where('id_account', '=', Auth::user()->id_account)
                                ->where('id_community', '=', $id)
                                ->first();
  
        if (!$status) {
          $status = "visitor";
        } else {
          $status = $status->status;
        }

      }
      

      $members = $admins->merge($members);

      return view('pages.group', ['posts' => $posts, 'members' => $members, 'group' => $community, 'status' => $status]);
    }

    /**
     * Creates a group.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request) {
      
      $validator = Validator::make($request->all(), [ 
          'groupname' => 'string|required|min:2|max:32|regex:/^[a-zA-Z][a-zA-Z0-9- ]+[a-zA-Z0-9]*$/',
          'groupdesc' => 'string|max:255|nullable',
        ],
        [
          'groupname.required'=> 'Your group should have a name',
          'groupname.min'=> 'Your group\'s name should have at least 2 characters',
          'groupname.max'=> 'Your group\'s name should have 32 or less characters',
          'groupname.regex'=> 'Your group\'s name should not have any special character',
          'groupdesc.max'=> 'Your group\'s description should have 255 or less characters'
        ]
      );


      if (!Auth::check()) return response(null, 401);

      if ($validator->fails()) {
        return back()->withErrors($validator)->withInput()->with('redirectCommand', 'openModalCreateGroup');
        // return response()->json("Something wrong happened", 400);
      }

      $group = new Community();

      $group->name = $request['groupname'];
      $group->description = $request['groupdesc'];
      if ($request['groupprivate'] === "on") {
        $group->is_public = false;
      } else {
        $group->is_public = true;
      }


      if ($group->save()) {
        $new_admin = new Relationship();
        $new_admin->id_community = $group->id_community;
        $new_admin->id_account = Auth::user()->id_account;
        $new_admin->status = 'admin';
        $new_admin->save();
        return redirect(route('group.show', ['id' => $group->id_community]));
      }

      return back();
    }

    /**
     * Edits a group.
     *
     * @param  Request  $request
     * @return Response
     */
    public function edit(Request $request) {
      
      $validator = Validator::make($request->all(), [ 
          'groupid' => 'integer|required',
          'groupname' => 'string|required|min:2|max:32|regex:/^[a-zA-Z][a-zA-Z0-9- ]+[a-zA-Z0-9]*$/',
          'groupdesc' => 'string|max:255|nullable',
        ],
        [
          'groupname.required'=> 'Your group should have a name',
          'groupname.min'=> 'Your group\'s name should have at least 2 characters',
          'groupname.max'=> 'Your group\'s name should have 32 or less characters',
          'groupname.regex'=> 'Your group\'s name should not have any special character',
          'groupdesc.max'=> 'Your group\'s description should have 255 or less characters'
        ]
      );

      if (!Auth::check()) return response(null, 401);


      if ($validator->fails()) {
        return back()->withErrors($validator)->withInput()->with('redirectCommand', 'showEditInformation');
        // return response()->json("Something wrong happened", 400);
      }

      $public = false;
      if ($request['groupprivate'] === "on") {
        $public = true;
      }

      $user = Auth::user();

      $admin = Relationship::where("id_account", "=", $user->id_account)
                                    ->where("id_community", "=", $request["groupid"])
                                    ->where("status", "=", "admin")
                                    ->exists();
      
      if (!$admin) return response(null, 401);

      Community::where('id_community', '=', $request["groupid"])
                  ->update(
                    [
                      "name" => $request["groupname"],
                      "description" => $request["groupdesc"],
                      "is_public" => $public
                    ]);

      return back();
    }

    /**
     * Gets an user's friends so he can invite them.
     *
     * @param  int  $offset
     * @return Response
     */
    public function friendSuggestions(Request $request) {
      
      if (!Auth::check()) return response("Not logged in", 401);
      $user =  Auth::user();

      $validator = Validator::make($request->all(), [ 
        'group' => 'integer|required',
        'offset' => 'integer|required',
        'query' => 'string|nullable|regex:/^[a-zA-Z][a-zA-Z0-9-]*$/'
      ]);


      if ($validator->fails()) {
        return response()->json("Something wrong happened", 400);
      }

      $group = $request['group'];
      $offset = $request['offset'];
      $query = $request['query'];

      


      $limit = 10;
      
      $friendships1 = User::join('friendship', 'account.id_account', '=', 'friendship.account2_id')
                            ->where('friendship.account1_id', $user->id_account)
                            ->where('account.account_tag', 'ilike', $query . '%')
                            ->get();
      $friendships2 = User::join('friendship', 'account.id_account', '=', 'friendship.account1_id')
                            ->where('friendship.account2_id', $user->id_account)
                            ->where('account.account_tag', 'ilike', $query . '%')
                            ->get();
                            
      $friends = [];

      foreach($friendships1 as $friend) {
        $membershipCheck = User::join('relationship', 'relationship.id_account', 'account.id_account')
                            ->where('relationship.id_community', '=', $group)
                            ->where('account.id_account', '=', $friend->id_account)
                            ->first();
        if (!$membershipCheck) {
          array_push($friends, $friend);
        }
        else if ($membershipCheck->status = "pending") {
          $friend->status = "pending";
          array_push($friends, $friend);
        }
      }

      foreach($friendships2 as $friend) {
        $membershipCheck = User::join('relationship', 'relationship.id_account', 'account.id_account')
                            ->where('relationship.id_community', '=', $group)
                            ->where('account.id_account', '=', $friend->id_account)
                            ->first();
        if (!$membershipCheck) {
          $friend->status = "none";
          array_push($friends, $friend);
        }
        else if ($membershipCheck->status = "pending")
          array_push($friends, $friend);
      }

      $friends = array_splice($friends, $offset, $limit);
      $friendsViews = [];



      foreach ($friends as $friend) {
          $friendsViews[] = view('partials.inviteGroupModal.inviteUser', ['name' => $friend->name, 'account_tag' => $friend->account_tag, 'id' => $friend->id_account, 'status' => $friend->status])->render();
      }
      

      return response()->json([
        'results' => $friendsViews,
      ]);
    }

    public function searchMember(Request $request) {
      if (!Auth::check()) return response(null, 401);
  
      $validator = Validator::make($request->all(), [ 
          'id' => 'integer|required',
          'text' => 'string|nullable|regex:/^[a-zA-Z][a-zA-Z0-9-]*$/',
      ]);
  
  
      if ($validator->fails()) {
        return response()->json(
          [
          'results' => [],
          ]
        );
      }

      $user = Auth::user();
      $id = $request['id'];
      $text = $request['text'];

      $admins = User::join('relationship', 'account.id_account', 'relationship.id_account')
                        ->where('relationship.id_community', '=', $id)
                        ->where('relationship.status', '=', 'admin')
                        ->where('account.account_tag', 'ilike', $text . '%')
                        ->get();
  
      $members = User::join('relationship', 'account.id_account', 'relationship.id_account')
                        ->where('relationship.id_community', '=', $id)
                        ->where('relationship.status', '=', 'member')
                        ->where('account.account_tag', 'ilike', $text . '%')
                        ->get();
      
      $members = $admins->merge($members);

      
      $admin = Relationship::where("id_account", "=", $user->id_account)
                            ->where("id_community", "=", $id)
                            ->where("status", "=", "admin")
                            ->exists();
      
      $membersViews = [];

      if ($admin) {
        foreach ($members as $friend) {
          $membersViews[] = view('partials.rightPanel.member', ['user' => $friend])->render();
        }
      } else { 
        foreach ($members as $friend) {
          $membersViews[] = view('partials.rightPanel.friend', ['user' => $friend])->render();
        }
      }
  
      return response()->json([
          'results' => $membersViews,
      ]);
    }

    /**
     * Removes the authenticated user from a group
     *
     * @param  int  $offset
     * @return Response
     */
    public function leave(Request $request) {
      $validator = Validator::make($request->all(), [ 
          'groupid' => 'integer|required',
        ]
      );

      if (!Auth::check()) return response(null, 401);


      if ($validator->fails()) {
        return response()->json("Something wrong happened", 400);
      }

      $user = Auth::user();

      $member = Relationship::where("id_account", "=", $user->id_account)
                                    ->where("id_community", "=", $request["groupid"])
                                    ->where("status", "=", "member")
                                    ->exists();
      
      if (!$member) return response(null, 401);

      Relationship::where("id_account", "=", $user->id_account)
                                    ->where("id_community", "=", $request["groupid"])
                                    ->where("status", "=", "member")
                                    ->delete();

      return back();
    }

    /**
     * Removes an user from the group if the authenticated user is an admin
     *
     * @return Response
     */
    public function kick(Request $request) {
      $validator = Validator::make($request->all(), [ 
          'userid' => 'integer|required',
          'groupid' => 'integer|required'
        ]
      );

      if (!Auth::check()) return response(null, 401);


      if ($validator->fails()) {
        return response()->json("Something wrong happened", 400);
      }

      $target_user = $request["userid"];
      $group = $request["groupid"];
      $user = Auth::user();

      $admin = Relationship::where("id_account", "=", $user->id_account)
                                    ->where("id_community", "=", $group)
                                    ->where("status", "=", "admin")
                                    ->exists();
      
      if (!$admin) return response(null, 401);

      Relationship::where("id_account", "=", $target_user)
                                    ->where("id_community", "=", $group)
                                    ->where("status", "=", "member")
                                    ->delete();

      $group_name = Community::find($group)->name;

      $newNotification = new Notification();
      $newNotification->id_receiver = $target_user;
      $newNotification->url = "/group/" . $group;
      $newNotification->notification_date = now();
      $newNotification->description = "You were kicked from " . $group_name;
      $newNotification->is_read = false;
      $newNotification->save();

      return response("", 200);
    }

    public function invite(Request $request) {
      if (!Auth::check()) return response("Not logged in", 401);
      $user =  Auth::user();

      $validator = Validator::make($request->all(), [ 
        'group' => 'integer|required',
        'invitee' => 'integer|required'
      ]);

      $group = $request['group'];
      $invitee = $request['invitee'];

      if ($validator->fails()) {
        return response("Something wrong happened", 400);
      }

      $existing = Relationship::where('id_community', '=', $group)
                    ->where('id_account', '=', $invitee)
                    ->first();
      
      if ($existing) return response("User has a relation with the group", 301);

      $relationship = new Relationship();

      $relationship->id_community = $group;
      $relationship->id_account = $invitee;
      $relationship->status = "pending";

      if ($relationship->save())
        return response($invitee, 200);
      else 
        return response("Something wrong happened", 400);
    }

    public function accept(Request $request) {
      if (!Auth::check()) return response("Not logged in", 401);
      $user =  Auth::user();

      $validator = Validator::make($request->all(), [ 
        'group_id' => 'integer|required',
      ]);

      $group_id = $request['group_id'];

      if ($validator->fails()) {
        return response("Something wrong happened", 400);
      }

      $relationship = Relationship::where("id_account", "=", $user->id_account)
                                    ->where("id_community", "=", $group_id)
                                    ->where("status", "=", "pending")
                                    ->first();
      if (!$relationship) return response()->json([
        "user" => $user->id_account,
        "id_community" => $group_id
      ], 301);
      
      Relationship::where("id_account", "=", $user->id_account)
                                    ->where("id_community", "=", $group_id)
                                    ->where("status", "=", "pending")
                                    ->update(["status" => "member"]);

      return response("Action sucessful", 200);
    }

    public function decline(Request $request) {
      if (!Auth::check()) return response("Not logged in", 401);
      $user =  Auth::user();

      $validator = Validator::make($request->all(), [ 
        'group_id' => 'integer|required',
      ]);

      $group_id = $request['group_id'];

      if ($validator->fails()) {
        return response("Something wrong happened", 400);
      }

      $relationship = Relationship::where("id_account", "=", $user->id_account)
                                    ->where("id_community", "=", $group_id)
                                    ->where("status", "=", "pending")
                                    ->first();
      if (!$relationship) return response()->json([
        "user" => $user->id_account,
        "id_community" => $group_id
      ], 301);
      
      Relationship::where("id_account", "=", $user->id_account)
                                    ->where("id_community", "=", $group_id)
                                    ->where("status", "=", "pending")
                                    ->delete();

      return response("Action successful", 200);
    }
}
