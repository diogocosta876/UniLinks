<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Validator;

use \App\Models\User;
use \App\Models\Friendship;
use \App\Models\FriendRequest;
use \App\Models\Notification;
use \App\Models\Community;
use \App\Models\Relationship;


class UserDataController extends Controller
{
    public function getData(Request $request) {
        if (!Auth::check()) return response(null, 401);

        $limit = 15;

        $friendRequests = User::join('friend_request', 'account.id_account', '=', 'friend_request.id_sender')->where('friend_request.id_receiver', Auth::user()->id_account)->get();

        $friendRequestViews = [];

        foreach ($friendRequests as $friendRequest) {
            $friendRequestViews[] = view('partials.leftPanel.linkRequest', ['name' => $friendRequest->name, 'id' => $friendRequest->id_sender])->render();
        }


        $notifications = Notification::where('id_receiver', '=', Auth::user()->id_account)->orderByDesc('notification_date')->limit($limit)->get();
        //$notifications2 = Notification::where('id_receiver', '=', Auth::user()->id_account)->where('is_read', '=', true)->orderByDesc('notification_date')->limit($limit - count($notifications))->get();
        

        $notificationViews = [];
        $notificationsCount = 0;
        // $notificationsNotRead = [];
        // $notificationsRead = [];


        foreach ($notifications as $notification) {
            if (!$notification->is_read) $notificationsCount += 1;
            $notificationViews[] = view('partials.leftPanel.notification', ['read' => $notification->is_read, 'description' => $notification->description, 'id' => $notification->id_notification])->render();
        }

        // foreach ($notifications as $notification) {
        //     $notificationsNotRead[] = view('partials.leftPanel.notification', ['read' => false, 'description' => $notification->description, 'id' => $notification->id_notification])->render();
        // }
        // foreach ($notifications2 as $notification) {
        //     $notificationsRead[] = view('partials.leftPanel.notification', ['read' => true, 'description' => $notification->description, 'id' => $notification->id_notification])->render();
        // }


        $groupViews = [];

        $groups = Relationship::join('community', 'community.id_community', '=', 'relationship.id_community')
                                        ->where('id_account', '=', Auth::user()->id_account)
                                        ->limit($limit)
                                        ->get();
        
        foreach ($groups as $group) {
            $groupViews[] = view('partials.leftPanel.groups', ['id' => $group->id_community, 'name' => $group->name])->render();
        }


        $linkViews = [];

        $friendships1 = User::join('friendship', 'account.id_account', '=', 'friendship.account2_id')->where('friendship.account1_id', Auth::user()->id_account)->get();
        $friendships2 = User::join('friendship', 'account.id_account', '=', 'friendship.account1_id')->where('friendship.account2_id', Auth::user()->id_account)->get();
        $friendships = $friendships1->merge($friendships2);
        
        foreach ($friendships as $link) {
            $linkViews[] = view('partials.leftPanel.links', ['id' => $link->id_account, 'name' => $link->name, 'tag' => $link->account_tag])->render();
        }


        
        $groupRequests = Community::join('relationship', 'community.id_community', '=', 'relationship.id_community')
                                    ->where('relationship.id_account', Auth::user()->id_account)
                                    ->where('relationship.status', "pending")
                                    ->limit($limit)
                                    ->get();
        
        $groupRequestViews = [];

        foreach ($groupRequests as $groupRequest) {
            $groupRequestViews[] = view('partials.leftPanel.groupRequest', ['name' => $groupRequest->name, 'id' => $groupRequest->id_community])->render();
        }


        return response()->json([
            'friends' => $friendships,
            'links' => $linkViews,
            'link_requests' => $friendRequestViews,
            'notifications' => $notificationViews,
            'groups' => $groupViews,
            'group_requests' => $groupRequestViews,
            'new_notis' => $notificationsCount,
            'more_data' => true
        ]);
    }

    public function getMoreNotifications($offset, Request $request) {
        if (!Auth::check()) return response(null, 401);

        $limit = 15 + $offset;

        $notifications = Notification::where('id_receiver', '=', Auth::user()->id_account)->orderByDesc('notification_date')->limit($limit)->get();
        //$notifications2 = Notification::where('id_receiver', '=', Auth::user()->id_account)->where('is_read', '=', true)->orderByDesc('notification_date')->limit($limit - count($notifications))->get();
        

        $notificationViews = [];
        $notificationsCount = 0;
        // $notificationsNotRead = [];
        // $notificationsRead = [];


        foreach ($notifications as $notification) {
            if (!$notification->is_read) $notificationsCount += 1;
            $notificationViews[] = view('partials.leftPanel.notification', ['read' => $notification->is_read, 'description' => $notification->description, 'id' => $notification->id_notification])->render();
        }


        return response()->json([
            'notifications' => $notificationViews,
            'new_notis' => $notificationsCount,
            'more_data' => count($notifications) >= $limit
        ]);
    }

    public function searchLinks(Request $request) {
        if (!Auth::check()) return response(null, 401);
    
        $validator = Validator::make($request->all(), [ 
            'text' => 'string|nullable|regex:/^[a-zA-Z][a-zA-Z0-9-]*$/',
        ]);
    
    
        if ($validator->fails()) {
          return response()->json("Something wrong happened", 400);
        }
    
        $id = Auth::user()->id_account;
        $text = $request['text'];                                  
        
        $friendships3 = \App\Models\User::join('friendship', 'account.id_account', '=', 'friendship.account2_id')->where('friendship.account1_id', $id)->where('account.account_tag', 'ilike', $text . '%')->get();
        $friendships4 = \App\Models\User::join('friendship', 'account.id_account', '=', 'friendship.account1_id')->where('friendship.account2_id', $id)->where('account.account_tag', 'ilike', $text . '%')->get();
        $userFriendships = $friendships3->merge($friendships4);
    
        $linkViews = [];
    
        foreach ($userFriendships as $link) {
            $linkViews[] = view('partials.leftPanel.links', ['id' => $link->id_account, 'name' => $link->name, 'tag' => $link->account_tag])->render();
        }
    
        return response()->json([
            'results' => $linkViews,
        ]);
    }

    public function getMoreLinkRequests($offset, Request $request) {
        if (!Auth::check()) return response(null, 401);

        $limit = 15 + $offset;



        $friendRequests = User::join('friend_request', 'account.id_account', '=', 'friend_request.id_sender')->where('friend_request.id_receiver', Auth::user()->id_account)->limit($limit)->get();

        $friendRequestViews = [];

        foreach ($friendRequests as $friendRequest) {
            $friendRequestViews[] = view('partials.leftPanel.linkRequest', ['name' => $friendRequest->name, 'id' => $friendRequest->id_sender])->render();
        }

        return response()->json([
            'link_requests' => $friendRequestViews,
            'more_data' => count($friendRequests) >= $limit
        ]);
    }

    public function getMoreGroups($offset, Request $request) {
        if (!Auth::check()) return response(null, 401);

        $limit = 15 + $offset;



        $groupViews = [];

        $groups = Relationship::join('community', 'community.id_community', '=', 'relationship.id_community')
                                        ->where('id_account', '=', Auth::user()->id_account)
                                        ->limit($limit)
                                        ->get();
        
        foreach ($groups as $group) {
            $groupViews[] = view('partials.leftPanel.groups', ['id' => $group->id_community, 'name' => $group->name])->render();
        }

        return response()->json([
            'groups' => $groupViews,
            'more_data' => count($groups) >= $limit
        ]);
    }

    public function readNotification(Request $request) {
        if (!Auth::check()) return response(null, 401);


        if (!(count(Notification::where('id_notification', '=', $request['id'])->get()) > 0)) {
            return response("No notification found", 300);
        }

        Notification::where('id_notification', '=', $request['id'])->update(['is_read' => true]);

        $obj = Notification::where('id_notification', '=', $request['id'])->first();

        return response($obj, 200);
    }

    public function deleteNotification(Request $request) {
        if (!Auth::check()) return response(null, 401);


        if (!(count(Notification::where('id_notification', '=', $request['id'])->get()) > 0)) {
            return response("No notification found", 300);
        }

        Notification::where('id_notification', '=', $request['id'])->delete();

        return response(null, 200);
    }
}
