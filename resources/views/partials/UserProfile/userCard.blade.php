<article class="bg-gray-100 p-4 m-4 rounded-xl flex flex-row justify-between">
    <section class="flex flex-row justify-start">
        <div class="w-24 h-24 bg-blue-500 rounded-full">
        </div>
        <div class="flex flex-col justify-start ml-3">
            <p class="text-base">
                {{$user->name}}
            </p>
            <p class="text-xl"> 
                {{'@' . $user->account_tag}}
            </p>
        </div>
    </section>
    <section class="self-center ml-3">
        <span class="rounded-full bg-blue-400 p-2 text-white select-none cursor-pointer">
            Connect
        </span>
    </section>
</article>