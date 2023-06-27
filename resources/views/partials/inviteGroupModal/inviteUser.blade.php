<article data-id="{{ $id }}" class="flex flex-row bg-slate-200 p-2 rounded-md items-center justify-between">
    <div class="flex flex-row items-center">
        <div class="bg-blue-500 rounded-full w-12 h-12 items-center flex-shrink-0"></div>
        <div class="flex flex-col ml-1 w-32 overflow-x-clip whitespace-nowrap">
            <p class="text-black text-base">{{ $name }}</p>
            <p class="text-gray-600 text-sm">{{ '@' . $account_tag }}</p>
        </div>
    </div>
    @if($status == "pending")
        <button type="reset" class="btn px-2 bg-orange-400" disabled>INVITED</button>
    @else
        <button type="reset" class="btn px-2 invite-button">INVITE</button>
    @endif
</article>