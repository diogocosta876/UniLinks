<div id="groupInviteModal" data-id={{ $id }} class="fixed flex justify-center items-center overflow-y-auto overflow-x-hidden h-screen w-screen top-0 right-0 left-0 z-50 hidden">
    <div class="absolute w-full h-full top-0 bg-black opacity-20"></div>
    <div id="left_panel_groups_create_modal" class="relative w-fit h-fit bg-white opacity-100 flex flex-col justify-start items-center p-4 rounded-lg mt-4 gap-y-4">
        <h3 class="text-2xl -mb-3 font-semibold">Invite to Group</h3>
        <div class="flex flex-col w-80 h-80">
            <div class="bg-slate-200 flex flex-col h-20 w-full items-center rounded-t-lg justify-evenly border-b-2 border-black">
                <p class="text-black text-lg text-center">Friends</p>
                <input type="text" name="query" id="inviteGroupQuery" class="text-input w-72" placeholder="Search">
            </div>
            <div id="groupInviteModalContent" class="flex flex-col gap-y-1 p-2 w-full overflow-y-scroll ">
                Loading friends...
            </div>
        </div>
        <div class="flex flex-row w-full justify-evenly">
            <button id="toggleGroupInviteModalClose" type="reset" class="btn font-bold">DONE</button>
        </div>
    </div>
</div>