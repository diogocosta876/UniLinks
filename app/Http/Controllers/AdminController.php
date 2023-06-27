<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all()->sortBy("name");
        return view('admin.adminShow', ['users'=>$users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'account_tag'=> 'required|unique:account',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:account',
            'birthday' => 'required|date',
            'university' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($request->get('password') != $request->get('password_confirmation')){
            return redirect('/users/create')->with('error', 'Passwords do not match');
        }
        if($request->get('privacy') === 'public'){
            $is_private = false;
        } else if ($request->get('privacy') === 'private'){
            $is_private = true;
        }

        if($request->get('is_admin') === "true"){
            $is_admin = true;
        } else if ($request->get('is_admin') === "false"){
            $is_admin = false;
        }

        $user = new User([
            'account_tag' => $request->get('account_tag'),
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'birthday' => $request->get('birthday'),
            'university' => $request->get('university'),
            'course' => $request->get('course'),
            'password' => Hash::make($request->get('password')),
            'age' => Carbon::parse($request->get('birthday'))->age,
            'is_private' => $is_private,
            'pronouns' => $request->get('pronouns'),
            'location' => $request->get('location'),
            'description' => $request->get('description'),
            'is_admin' => $is_admin,
            'is_blocked' => 'false',
            'is_verified' => 'true',

        ]);
        $user->save();
        return redirect('/users')->with('success', 'User has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        $user = User::find($id);
        return view('admin.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.edit', compact('user'));
    }
    */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->save();
    }

    public function block($id_user)
    {
        $user = User::find($id_user);
        $user->is_blocked = true;
        $user->save();
        return redirect('/users')->with('success', 'User has been blocked');
    }

    public function unblock($id_user)
    {   
        $user = User::find($id_user);
        $user->is_blocked = false;
        $user->save();
        return redirect('/users')->with('success', 'User has been unblocked');
    }

    public function delete(Request $request, $id)
    {
        $user = User::find($id);
        $lastAnnon = User::where('name', 'Anonymous')->orderBy('account_tag', 'desc')->first();
        if($lastAnnon == null){
            $annonNum = 0;
        } else {
            $annonNum = explode("anonymous", $lastAnnon->account_tag)[1];
        }

        $user->account_tag = "anonymous" . ($annonNum + 1);
        $user->name = "Anonymous";
        $user->email = "anonymous" . ($annonNum + 1) . "@anonymous.com";
        $user->birthday = "2000-01-01";
        $user->university = "None";
        $user->course = "None";
        $user->password = Hash::make("anonymous" . ($annonNum + 1) . "anonymous" . ($annonNum + 1) . "@anonymous.com" . "2000-01-01");
        $user->save();
        return redirect('/users')->with('success', 'User has been deleted');
    }
}