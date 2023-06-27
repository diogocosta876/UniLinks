<section class="sidepanel left-sidepanel mobile-menu" id="leftPanel"> 
    <a href={{ route('timeline') }} class="left-panel-button <?php if ($_SERVER['REQUEST_URI'] == "/timeline") echo "left-panel-button-selected"; ?>">
        <img src={{ asset('icons/home.svg') }} alt="house icon" width=28" height=28" class="h-7 w-7">
        <p>FEED</p>
    </a>
    <a href="/user/{{ Auth::user()->account_tag }}" class="left-panel-button <?php if ($_SERVER['REQUEST_URI'] == "/user/" . Auth::user()->account_tag) echo "left-panel-button-selected"; ?>">
        <img src={{ asset('icons/person.svg') }} alt="person icon" width=28" height=28" class="h-7 w-7">
        <p>PROFILE</p>
    </a>
    <div>
        <div id="left_panel_link_button" class="left-panel-button">
            <img src={{ asset('icons/group.svg') }} alt="links icon" width=28" height=28" class="h-7 w-7">
            <p>LINKS</p>
            <span id="left_panel_link_counter" class="left-panel-button-counter hidden">0</span>
        </div>
        <ul id="left_panel_links_list" class="left_panel_list hidden">
            <div class="flex flex-row h-9 w-full gap-x-1">
                <input type="text" name="usersearch" id="leftpanellinksfilter" placeholder="Search" class="rounded-lg border-blue-400 border-2 w-full">
            </div>                
            <div id="left_panel_links_list_content" class="w-full flex flex-col items-center">
                No links to show.
            </div>
        </ul>
    </div>
    <div>
        <div id="left_panel_link_add_button" class="left-panel-button">
            <img src={{ asset('icons/group_add.svg') }} alt="link add icon" width=28" height=28" class="h-7 w-7">
            <p>LINK REQUESTS</p>
            <span id="left_panel_link_add_counter" class="left-panel-button-counter hidden">0</span>
        </div>
        <ul id="left_panel_links_add_list" class="left_panel_list hidden">
            No link requests to show.
        </ul>
    </div>
    <div>
        <div id="left_panel_group_button" class="left-panel-button">
            <img src={{ asset('icons/groups.svg') }} alt="groups icon" width=28" height=28" class="h-7 w-7">
            <p>GROUPS</p>
        </div>
        <ul id="left_panel_groups_list" class="left_panel_list hidden">
            <div id="left_panel_groups_create" class="left-panel-button my-2">
                Create new group
            </div>
            <div id="left_panel_groups_list_content" class="w-full flex flex-col items-center">
                No groups to show.
            </div>
        </ul>
    </div>
    <div>
        <div id="left_panel_group_add_button" class="left-panel-button">
            <img src={{ asset('icons/groups_add.svg') }} alt="groups add icon" width=28" height=28" class="h-7 w-7">
            <p>GROUP REQUESTS</p>
            <span id="left_panel_group_add_counter" class="left-panel-button-counter hidden">0</span>
        </div>
        <ul id="left_panel_groups_add_list" class="left_panel_list hidden">
            No group requests to show.
        </ul>
    </div>
    <div>
        <div id="left_panel_notification_button" class="left-panel-button">
            <img src={{ asset('icons/notifications.svg') }} alt="notifications icon" width=28" height=28" class="h-7 w-7">
            <p>NOTIFICATIONS</p>
            <span id="left_panel_notification_counter" class="left-panel-button-counter hidden">0</span>
        </div>
        <ul id="left_panel_notifications_list" class="left_panel_list hidden">
            No notifications to show.
            <img src={{ asset('icons/refresh.svg') }} alt="refresh icon" width=28" height=28" class="h-7 w-7 m-2 cursor-pointer">
        </ul>
    </div>
</section>

<?php echo view('partials.createGroup'); ?>