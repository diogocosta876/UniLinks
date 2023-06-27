@extends ('layouts.app')

@section('title', 'Admin')

@section('content')
<section class="content">
    <div class="text-center text-black text-6xl">User Profiles</div>
    <div class="text-center mt-5">
        <a class="text-violet-700" href="{{ route('admin.create') }}">Create new user</a>
    </div>
    <div id="admin_usershow_container" class="flex flex-col mt-4">
        @foreach ($users as $user)
            @if($user->name === "Anonymous")
                @continue
            @endif
            <div id={{$user->id_account}} class="card-body flex flex-row m-6 text-center items-center h-28 break-words">
                <img src="{{ asset('storage/' . $user->profile_picture) }}" class="img-thumbnail" alt="">
                <a class="underline hover:no-underline" href="/user/{{ $user->id_account }}">{{ $user->name }}</a> 
                <p class="col-auto">{{ $user->account_tag }}</p>
                <p class="col-auto">{{ $user->email }}</p>
                <p class="col-auto">{{ $user->birthday }}</p>
                <p class="col-auto">{{ $user->university }}</p>
                <p class="col-auto">{{ $user->course }}</p>
                
                @if ($user->is_blocked == true)
                    <input class="bg-green-400 hover:bg-green-700 rounded py-2 px-3 cursor-pointer text-center" type="button" value="Unblock" onclick="block(this);">
                @else
                    <input class="bg-red-400 hover:bg-red-700 rounded py-2 px-3 cursor-pointer text-center" type="button" value="Block" onclick="block(this);">
                @endif
                <i id="deleteUser" class="fa-solid fa-trash" onclick="deleteUser(this);"></i>
            </div>
        @endforeach 
    </div>
</section>
@endsection