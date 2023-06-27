<a href="/user/{{$id}}" data-id="{{$id}}" class="flex flex-row justify-start items-center w-full gap-x-4 p-2 my-1 cursor-pointer rounded-md bg-slate-200">
    <div class="group-square w-14 h-14 bg-blue-300 overflow-hidden hover:backdrop-saturate-125"></div>
    <div class="flex-flex-col">
        <p class="select-none text-black text-base">
            {{$name}}
        </p>
        <p class="select-none text-gray-600 text-sm">
            {{'@' . $tag}}
        </p>
    </div>
</a>