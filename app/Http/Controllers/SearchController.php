<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;
use App\Models\User;


class SearchController extends Controller
{
    public function show_user(Request $search_string)
    {
      if (!Auth::check()) return redirect('/login');
      $stag = $search_string['account_tag'];
      if(strlen($stag) !=0) {
        $accounts = User::where('account_tag', 'ILIKE', '%'.$stag.'%')->get();
        return view('pages.search', ['accounts' => $accounts]);
      } 
      else return view('pages.search', ['accounts' => []]);
    }

    public function show_posts(Request $search_string){
        if (!Auth::check()) return redirect('/login');
        $stag = $search_string['text_search'];
        if(strlen($stag) !=0) {
            $accounts = User::where('account_tag', 'ILIKE', '%'.$stag.'%')->get();
            return view('pages.search', ['accounts' => $accounts, 'bool_posts' => TRUE]);
        } 
        else return view('pages.search', ['accounts' => [], 'bool_posts' => TRUE]);
    }
}
