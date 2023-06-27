<article class="grid grid-cols-[auto_1fr_auto] right-sidepanel-tab-item-size bg-gray-200 p-2 w-full">
    <div class="group-square bg-blue-500 w-16 h-16 items-center"></div>
    <div class="ml-1">
        <div class="flex flex-row justify-start">
            <p class="text-black text-base">{{ $group->name }}</p>
            <span></span>
        </div>
        @if($group->is_public)
        <p class="text-green-600 text-sm">Public</p>
        @else
        <p class="text-red-600 text-sm">Private</p>
        @endif
    </div>
    <a href="/group/{{$group->id_community}}" class="btn m-0 py-1 px-2 bg-transparent hover:bg-slate-400 text-black self-center">
        VISIT
    </a>
</article>