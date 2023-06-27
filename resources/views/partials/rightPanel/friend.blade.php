<article class="grid grid-cols-[auto_1fr_auto] right-sidepanel-tab-item-size bg-gray-200 p-2 w-full">
    <div class="bg-blue-500 rounded-full w-16 h-16 items-center"></div>
    <div class="ml-1">
        <div class="flex flex-row justify-start">
            <p class="text-black text-base">{{ $user->name }}</p>
            <span></span>
        </div>
        <p class="text-gray-600 text-sm">{{ "@" . $user->account_tag }}</p>
        @if(isset($user->status))
            @if($user->status == 'admin')
             <p class="text-green-600 text-sm">Group Admin</p>
            @endif
        @endif
    </div>
    <a href="/user/{{$user->account_tag}}" class="btn m-0 py-1 px-2 bg-transparent hover:bg-slate-400 text-black self-center">
        VIEW
    </a>
</article>