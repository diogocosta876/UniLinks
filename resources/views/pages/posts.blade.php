@extends('layouts.app')

@section('title', 'Posts')

@section('content')

<section id="posts">
  @each('partials.post', $posts, 'post')
  <article class="post">
    <form class="new_card">
      <input type="text" name="name" placeholder="new post">
    </form>
  </article>
</section>

@endsection
