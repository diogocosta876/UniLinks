@extends('layouts.app')

@section('title', 'Timeline')

@section('content')

<?php echo view('partials.leftPanel.panel'); ?>


<section class="flex flex-col justify-start">
  <section class="bg-white rounded-2xl p-4">
    <div class="flex flex-row justify-between">
      <div class="group-square w-24 h-24 bg-blue-300 overflow-hidden hover:backdrop-saturate-125"></div>
      <div class="flex flex-col justify-evenly">
        @if ($status == "admin" || ($status == "member" && $group->is_public))
        <div id="groupInviteModalButton" class="btn bg-orange-500 hover:bg-orange-600">Invite</div>
        <?php echo view('partials.groupInvite', ['id' => $group->id_community]); ?>
        @elseif ($status == "visitor" && $group->is_public)
          PUBLIC GROUP
        @elseif ($status == "visitor" && !$group->is_public)
          PRIVATE GROUP
        @elseif ($status == "pending")
          <div id="group-join-button" class="btn bg-orange-500 hover:bg-orange-600" data-id="{{ $group->id_community; }}">Join</div>
        @endif
      </div>
    </div>
    <h3 class="text-2xl mt-2">{{ $group->name }}</h3>
    <p class="text-base mt-2">
      {{ $group->description }}
    </p>
  </section>

  <span class="my-2"></span>
  
  <form method="POST" action="{{ route('newpost') }}"  class="bg-white rounded-2xl p-4">
    {{ csrf_field() }}
    <div class="flex flex-row">
      <div id="newPostCardLeftBar" class="flex flex-col">
        <div class="w-24 h-24 bg-blue-500 rounded-full"></div>
      </div>
      <input type="textarea" name="description" id="newPostDescription" placeholder="What are you thinking?" required autocomplete=off class="w-full">
    </div>
    <div class="flex flex-row justify-end mt-4">
      <select name="group" id="newpostvisibility" class="w-fit hidden">
        <option for="group" value="{{ $group->id_community}} ">Friends</option>
      </select>
      <button class="px-4 btn"> Publish </button>
    </div>
  </form>

  <span class="my-4"></span>
    
  <div id="posts" class="bg-white rounded-2xl">
    @each('partials.post', $posts, 'post')
  </div>

</section>


<?php echo view('partials.rightPanel.panel', ['type' => 'group', 'members' => $members, 'group' => $group, 'status' => $status]); ?>


@if (session('redirectCommand'))
  <span data-function="{{ session('redirectCommand'); }}" class="redirect-cmd hidden" ></span>
@endif

@endsection