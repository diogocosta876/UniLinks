<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Validator;

use App\Models\User;
use App\Models\Friendship;
use App\Models\FriendRequest;
use App\Models\Notification;


class FriendshipController extends Controller
{
    public function relationships($id) {
        $friendships1 = User::join('friendship', 'account.id_account', '=', 'friendship.account2_id')->where('friendship.account1_id', $id)->get();
        $friendships2 = User::join('friendship', 'account.id_account', '=', 'friendship.account1_id')->where('friendship.account2_id', $id)->get();
        $friendships = $friendships1->merge($friendships2);
        return $friendships;
    }

    public function accept(Request $request) {
        if (!Auth::check()) return response(null, 401);
        $validator = Validator::make($request->all(), [ 
            'id' => 'integer|required'
        ]);

        if ($validator->fails()) {
            return response()->json("Something wrong happened", 400);
        }
        
        $sender = Auth::user()->id_account;
        $target = $request['id'];
        
        if (count(FriendRequest::where('id_sender', $target)->where('id_receiver', $sender)->get()) == 0) return response("No request found", 400);
        
        $friendships1 = Friendship::where('account1_id', $sender)->where('account2_id', $target)->get();
        $friendships2 = Friendship::where('account2_id', $sender)->where('account1_id', $target)->get();
        $friendships_count = count($friendships1) + count($friendships2);

        if ($friendships_count) return response("Already linked", 400);
        
        FriendRequest::where('id_sender', $target)->where('id_receiver', $sender)->delete();
        
        $newFriendship = new Friendship();
        $newFriendship->account1_id = $sender;
        $newFriendship->account2_id = $target;
        $newFriendship->save();

        $notified = User::find($sender);

        if ($notified) {
            $newNotification = new Notification();
            $newNotification->id_receiver = $target;
            $newNotification->url = "/user/" . $notified->account_tag;
            $newNotification->notification_date = now();
            $newNotification->description = $notified->name . " accepted your link request.";
            $newNotification->is_read = false;
            $newNotification->save();
        }
        
        return response("Request accepted", 200);
    }

    public function decline(Request $request) {
        if (!Auth::check()) return response(null, 401);
        $validator = Validator::make($request->all(), [ 
            'id' => 'integer|required'
        ]);

        if ($validator->fails()) {
            return response()->json("Something wrong happened", 400);
        }
        
        $sender = Auth::user()->id_account;
        $target = $request['id'];
        
        if (count(FriendRequest::where('id_sender', $target)->where('id_receiver', $sender)->get()) == 0) return response("No request found", 400);
        
        $friendships1 = Friendship::where('account1_id', $sender)->where('account2_id', $target)->get();
        $friendships2 = Friendship::where('account2_id', $sender)->where('account1_id', $target)->get();
        $friendships_count = count($friendships1) + count($friendships2);

        if ($friendships_count) return response("Already linked", 400);
        
        FriendRequest::where('id_sender', $target)->where('id_receiver', $sender)->delete();

        return response("Request declined", 200);
    }

    public function create(Request $request) {
        if (!Auth::check()) return response(null, 401);
        $validator = Validator::make($request->all(), [ 
            'id' => 'integer|required'
        ]);

        if ($validator->fails()) {
            return response()->json("Something wrong happened", 400);
        }

        $sender = Auth::user()->id_account;
        $target = $request['id'];

        // check if they are already friends

        $friendships1 = Friendship::where('account1_id', $sender)->where('account2_id', $target)->get();
        $friendships2 = Friendship::where('account2_id', $sender)->where('account1_id', $target)->get();
        $friendships_count = count($friendships1) + count($friendships2);

        if ($friendships_count) return response("Already linked", 400);
        
        // check if a request has already been sent
        
        $friend_requests1 = FriendRequest::where('id_sender', $sender)->where('id_receiver', $target)->get();
        $friend_requests2 = FriendRequest::where('id_sender', $target)->where('id_receiver', $sender)->get();
        $friend_requests_count = count($friend_requests1) + count($friend_requests2);
        
        if ($friend_requests_count) {
            if (count($friend_requests2)) {
                $newFriendship = new Friendship();
                $newFriendship->account1_id = $sender;
                $newFriendship->account2_id = $target;
                FriendRequest::where('id_sender', $target)->where('id_receiver', $sender)->delete();
                $newFriendship->save();
                return response("Link complete", 200);
            } else return response("Request already sent", 400);
        }

        $friend_request = new FriendRequest();
        $friend_request->id_sender = Auth::user()->id_account;
        $friend_request->id_receiver = $request['id'];

        $friend_request->save();

        return response("Request sent", 200);
    }

    public function cancel(Request $request) {
        if (!Auth::check()) return response(null, 401);
        $validator = Validator::make($request->all(), [ 
            'id' => 'integer|required'
        ]);

        if ($validator->fails()) {
            return response()->json("Something wrong happened", 400);
        }

        $sender = Auth::user()->id_account;
        $target = $request['id'];

        $friend_requests = FriendRequest::where('id_sender', $sender)->where('id_receiver', $target)->get();
        
        if ($friend_requests) {
            FriendRequest::where('id_sender', $sender)->where('id_receiver', $target)->delete();
            return response("Link request canceled", 200);
        }


        return response("Failed to cancel link request", 400);
    } 

    public function delete(Request $request) {
        if (!Auth::check()) return response(null, 401);
        $validator = Validator::make($request->all(), [ 
            'id' => 'integer|required'
        ]);

        if ($validator->fails()) {
            return response()->json("Something wrong happened", 400);
        }

        $sender = Auth::user()->id_account;
        $target = $request['id'];

        $friendships1 = Friendship::where('account1_id', $sender)->where('account2_id', $target)->get();
        $friendships2 = Friendship::where('account2_id', $sender)->where('account1_id', $target)->get();
        $friendships_count = count($friendships1) + count($friendships2);
        
        if ($friendships_count) {
            if (count($friendships1)) {
                Friendship::where('account1_id', $sender)->where('account2_id', $target)->delete();
                return response("Unlink completed", 200);
            } else {
                Friendship::where('account2_id', $sender)->where('account1_id', $target)->delete();
                return response("Unlink completed", 200);
            }
        }


        return response("Failed to unlink", 400);
    } 
}
