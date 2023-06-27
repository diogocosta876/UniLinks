<article class="post" data-id="{{ $post->id }}">
<header>
  <h2><a href="/posts/{{ $post->id }}">{{ $post->name }}</a></h2>
  <a href="#" class="delete">&#10761;</a>
</header>
<ul>
  @each('partials.item', $post->items()->orderBy('id')->get(), 'item')
</ul>
<form class="new_item">
  <input type="text" name="description" placeholder="new item">
</form>
</article>
