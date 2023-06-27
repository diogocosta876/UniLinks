<div data-id="{{$id}}" class="flex flex-col justify-between items-center w-full gap-y-2 p-2 my-1 cursor-pointer rounded-md bg-slate-200">
    <p>
        {{$name}} sent you a link request
    </p>
    <div class="flex flex-row justify-evenly w-full text-white mb-1">
        <div class="link-request-accept py-1 px-2 bg-blue-500 rounded-md flex-shrink-0">
            Accept
        </div>
        <div class="link-request-refuse py-1 px-2 bg-red-500 rounded-md flex-shrink-0">
            Refuse
        </div>
    </div>
</div>