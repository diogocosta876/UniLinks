<section class="sidepanel right-sidepanel" id="leftPanel"> 
    @if($type=="profile")
        <div class="flex flex-row w-full mb-2">
            <h2 id="right-sidepanel-left-tab-button" class="right-sidepanel-tab-button right-sidepanel-tab-button-selected" >Links</h2>
            <h2 id="right-sidepanel-right-tab-button" class="right-sidepanel-tab-button" >Groups</h2>
        </div>
        <div class="flex overflow-x-hidden">
            <div id="right-sidepanel-left-tab" class="right-sidepanel-tab">
                <div class="flex flex-row w-full mb-2 gap-x-1">
                    <input type="text" name="usersearch" data-id="{{ $userID }}" id="linksfilter" placeholder="Search" class="rounded-lg border-blue-400 border-2 w-full">
                    <span data-id="{{ $userID }}" id="right-panel-common-link-filter" class="w-fit flex flex-row whitespace-nowrap p-1 gap-x-1 items-center cursor-pointer select-none border-2 border-blue-400 rounded-lg active:common-link-filter-active">
                        <img src="/icons/diversity.svg" alt="diversity icon" h="24" w="24" class="w-7 h-7">
                        <p class="mr-7">Common links</p>
                    </span>
                </div>
                <div id="right-sidepanel-left-tab-content" class="right-sidepanel-tab">
                    @each('partials.rightPanel.friend', $friends, 'user')
                </div>
            </div>
            <div id="right-sidepanel-right-tab" class="right-sidepanel-tab">
                @each('partials.rightPanel.group', $groups, 'group')
            </div>
        </div>
    @elseif($type=="timeline")
    @elseif($type=="group")
        <div class="flex flex-row w-full mb-2">
            <h2 id="right-sidepanel-left-tab-button" class="right-sidepanel-tab-button right-sidepanel-tab-button-selected" >Members</h2>
            <h2 id="right-sidepanel-right-tab-button" class="right-sidepanel-tab-button" >Options</h2>
        </div>
        <div class="flex overflow-x-hidden">
            <div id="right-sidepanel-left-tab" class="right-sidepanel-tab">
                <div class="flex flex-row h-9 w-full gap-x-1">
                    <input type="text" name="usersearch" data-id="{{ $group->id_community }}" id="membersfilter" placeholder="Search" class="rounded-lg border-blue-400 border-2 w-full">
                </div>
                @if ($status == "admin")
                    <div id="right-sidepanel-left-tab-content" class="right-sidepanel-tab">
                        @each('partials.rightPanel.member', $members, 'user')
                    </div>
                @else
                    <div id="right-sidepanel-left-tab-content" class="right-sidepanel-tab">
                        @each('partials.rightPanel.friend', $members, 'user')
                    </div>
                @endif
            </div>
            <div id="right-sidepanel-right-tab" class="right-sidepanel-tab gap-0">
                @if ($status == "member")
                <form action="/group/leave" method="post" class="flex flex-col h-full justify-evenly items-center">
                    {{ csrf_field() }}
                    @if ($group->is_public)
                        This group is public.
                    @else
                        This group is private.
                    @endif
                    <input type="number" name="groupid" value="{{ $group->id_community }}" class="hidden">
                    <button type="submit" class="btn bg-red-500 hover:bg-red-600">LEAVE</button>
                </form>
                @elseif ($status == "admin")
                <h3 class="text-lg font-semibold">Edit Group Information</h3>
                <form action="/group/edit" method="post" class="flex flex-col">
                    {{ csrf_field() }}
                    <input type="number" name="groupid" value="{{ $group->id_community; }}" class="hidden">
                    <div class="flex flex-col gap-y-1">
                        <label for="groupdesc" class="mt-2">Group name:</label>
                        <input type="text" placeholder="Type Here" value="{{ $group->name; }}" autocomplete=off class="text-input" name="groupname" minlength="2" maxlength="32" required id="left_panel_groups_create_groupname">
                        <span class="text-red-500 text-sm">
                            {{ $errors->first('groupname'); }}
                        </span>
                    </div>
                    <div class="flex flex-col gap-y-1">
                        <label for="groupdesc" class="mt-2">Group description:</label>
                        <textarea name="groupdesc" placeholder="Type Here" autocomplete=off class="textarea-input" id="left_panel_groups_create_groupdesc" cols="30" rows="3" maxlength="255">{{ $group->description; }}</textarea>
                        {{ $errors->first('groupdesc'); }}
                    </div>
                    <div class="w-full flex justify-start gap-x-2 mt-2 items-center">
                        <input type="checkbox" name="groupprivate" class="w-5 h-5" id="left_panel_groups_create_groupprivate">
                        <label for="groupprivate">Private group</label>
                    </div>
                    <button type="submit" class="btn bg-red-500 hover:bg-red-600 self-center px-3 mt-3">SAVE CHANGES</button>
                </form>
                @else
                    @if ($group->is_public)
                        This group is public.
                    @else
                        This group is private.
                    @endif
                @endif
            </div>
        </div>
    @else
    @endif
</section>