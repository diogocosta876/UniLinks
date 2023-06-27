<article class="flex flex-row bg-gray-100 rounded-lg pl-4 pt-4 pb-2 pr-4 m-4" href="/posts/{{ $post->id_post }}">
    <a id="postCardLeftBar" class="flex flex-col" href="/user/{{ $post->owner_id }}">
        <div class="w-20 h-20 bg-blue-500 rounded-full"></div>
    </a>
    <div id="postCardRightBar" class="flex flex-row justify-between w-full break-all">
        <div id="postCardInfo" class="flex flex-col ml-2">
            <header class="flex flex-row gap-x-2 mb-2 items-center">
                <a class="text-lg" href="/user/{{ $post->owner_id }}">{{ $post->name }}</a>
                <a class="text-base" href="/user/{{ $post->owner_id }}"><span>@</span>{{ $post->account_tag }}</a>
                <a class="text-base" href="/posts/{{ $post->id_post }}">{{ $post->edited_date }}</a>
            </header>
            <p class="mb-2"> {{ $post->description }} </p>
            <div class="flex flex-row justify-between mt-2">
                <a href=""><i class="fa-regular fa-message"></i></a>
                <a href=""> <i class="fa-solid fa-rocket"></i></a>
                <a href=""><i class="fa-solid fa-share"></i></a>
                <a href=""><i class="fa-solid fa-ellipsis"></i></a>
            </div>
        </div>
        <div id="postCardInfo" class="flex flex-col ml-7 pt-2 pb-2 items-center justify-center justify-items-center">
            <a href=""><i class="fa-regular fa-square-caret-up arrows "></i></a>
            <a href=""><i class="fa-solid fa-circle"></i></a>
            <a href=""><i class="fa-regular fa-square-caret-down arrows "></i></a>
        </div>
    </div>
</article>
