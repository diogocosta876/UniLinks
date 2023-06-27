@extends('layouts.app')

@section('content')

<link href="{{ asset('css/search.css') }}" rel="stylesheet">

<section class="sidepanel" id="left"> 
    <h2>Search Filters</h2>

    <div class="search_by_user flex flex-row" >
        <input type="radio" name="search" id="users" checked>
        <label for="users">User tag</label>
    </div>

    <div class="search_by_posts flex flex-row" >
        <input type="radio" name="search" id="posts">
        <label for="posts">Post content</label>
    </div>
</section>

<section id="timeline">


@if (isset($bool_posts))
    <div id="posts">
        <h2> Posts </h2>
    </div>
@else
    <div id="users">
        <h2> Users </h2>

        @foreach ($accounts as $acc)
        <div class="profile_info">
            <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" class="profile_image" class="m-auto rounded-full">
            
            <div class="user_identity_info" class="m-auto flex flex-col">
                <a href="/user/{{$acc['account_tag']}}"><div class="tag"> {{$acc['account_tag']}}</div></a>
                <div class="university text-gray-400">{{$acc['university']}}</div>  
            </div>
        </div>
        @endforeach

        @if($accounts == [] || $accounts->isEmpty())
            <p>Nothing found</p>
        @endif


    </div>
@endif

{{--
<div id="posts">
    <h2> Posts </h2>
    <div class="profile_info">
        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" class="profile_image" class="m-auto rounded-full">
        <div class="name"> @Manuel_Amorim</div>
    </div>

    <div id="posts" class="bg-white rounded-2xl">
        @each('partials.post', $posts, 'post')
    </div>

</div>

--}}


</section>

<section class="sidepanel" id="right"> 
</section>

@endsection
