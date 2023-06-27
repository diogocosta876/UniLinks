<div id="leftPanelCreateGroupModal" class="fixed flex justify-center items-center overflow-y-auto overflow-x-hidden h-screen w-screen top-0 right-0 left-0 z-50 hidden">
    <div class="absolute w-full h-full top-0 bg-black opacity-20"></div>
    <form action={{ route('group.create') }} method="post" enctype="multipart/form-data" id="left_panel_groups_create_modal" class="relative w-fit h-fit bg-white opacity-100 flex flex-col justify-start items-center p-4 rounded-lg mt-4 gap-y-4">
        @csrf
        <h3 class="text-2xl -mb-3 font-semibold">Create Group</h3>
        <div class="flex flex-row gap-x-12">
            <section class="flex flex-col justify-center items-center">
                <div class="group-square w-36 h-36 bg-blue-300 overflow-hidden hover:backdrop-saturate-125">
                    <span class="flex justify-center items-center w-full h-full cursor-pointer group">
                        <img src={{ asset('icons/edit.svg') }} alt="edit icon" width=28" height=28" class="hidden h-9 w-9 group-hover:block">
                    </span>
                </div>
                <input type="file" class="hidden" name="groupimg" id="left_panel_groups_create_groupimg">
            </section>
            <section class="gap-y-4"">
                <div class="flex flex-col gap-y-1">
                    <label for="groupdesc" class="mt-2">Group name:</label>
                    <input type="text" placeholder="Type Here" autocomplete=off class="text-input" name="groupname" minlength="2" maxlength="32" required id="left_panel_groups_create_groupname">
                    <span class="text-red-500 text-sm">
                        {{ $errors->first('groupname'); }}
                    </span>
                </div>
                <div class="flex flex-col gap-y-1">
                    <label for="groupdesc" class="mt-2">Group description:</label>
                    <textarea name="groupdesc" placeholder="Type Here" autocomplete=off class="textarea-input" id="left_panel_groups_create_groupdesc" cols="30" rows="3" maxlength="255"></textarea>
                    {{ $errors->first('groupdesc'); }}
                </div>
                <div class="w-full flex justify-start gap-x-2 mt-2 items-center">
                    <input type="checkbox" name="groupprivate" class="w-5 h-5" id="left_panel_groups_create_groupprivate">
                    <label for="groupprivate">Private group</label>
                </div>
            </section>
        </div>
        <div class="flex flex-row w-full justify-evenly">
            <button type="submit" class="btn font-bold">CREATE</button>
            <button id="toggleCreateGroupModalClose" type="reset" class="btn font-bold">CANCEL</button>
        </div>
    </form>
</div>