<div data-id="{{$id}}" class="flex flex-row justify-between items-center w-full gap-x-4 p-2 my-1 cursor-pointer rounded-md <?php if ($read) echo "bg-slate-200"; else echo "bg-blue-100"; ?>">
    <p>
        {{$description}}
    </p>
    <div class="notification-delete p-1 bg-red-500 rounded-md flex-shrink-0 active:bg-red-700">
        <img src="/icons/delete.svg" alt="delete icon" width=18 height=18 class="w-6 h-6">
    </div>
</div>