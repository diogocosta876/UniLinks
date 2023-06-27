@extends('layouts.app')

@section('title', 'UserProfile')

@section('content')

    <?php echo view('partials.leftPanel.panel'); ?>

    <section id="timeline">

        @if ((!$user->is_private ||
            $linkStatus == 'linked' ||
            $user->id_account == Auth::user()->id_account ||
            Auth::user()->is_admin == true) &&
            !$user->is_blocked)
            <section id="profile">
                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" id="profile_image"
                    class="m-auto rounded-full">
                <div id="user_life_info_container" class="m-auto flex flex-col justify-between ">
                    <div class="user_school flex flex-row">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span class="text-base">{{ $user->course }}</span>
                    </div>
                    <div class="user_school flex flex-row">
                        <i class="fa-solid fa-school"></i>
                        <span class="text-base">{{ $user->university }}</span>
                    </div>
                    <div class="user_school flex flex-row">
                        <i class="fa-sharp fa-solid fa-location-dot"></i>
                        <span class="text-base">{{ $user->location }}</span>
                    </div>
                </div>
                <div id="user_identity_info" class="flex flex-col">
                    <div id="name">{{ $user->name }}</div>
                    <div id="details" class="flex flex-row justify-between items-center text-gray-400">
                        <div id="username"> {{ '@' . $user->account_tag }}</div>
                        <i class="fa-solid fa-circle"></i>
                        <div id="pronouns">{{ $user->pronouns }}</div>
                        <i class="fa-solid fa-circle"></i>
                        <div id="age">{{ $user->age }} years old</div>
                    </div>
                </div>
                @if ($user->id_account == Auth::user()->id_account || Auth::user()->is_admin === true)
                    <span id="link_button" data-method="edit" class="bg-cyan-400 action-button">Edit</span>
                @elseif ($linkStatus == 'linked')
                    <span id="link_button" data-method="delete" data-id={{ $user->id_account }}
                        class="bg-red-400 action-button">Unlink</span>
                @elseif ($linkStatus == 'received')
                    <div class="flex flex-col w-fit h-fit relative group">
                        <span id="link_button" data-method="expand" data-id={{ $user->id_account }}
                            class="bg-blue-400 action-button flex flex-row">
                            <p class="flex-shrink-0">Link Received</p>
                        </span>
                        <div
                            class="hidden group-hover:block bg-blue-400 w-fit p-2 rounded-xl absolute right-0 translate-x-full">
                            <span id="link_button_accept" data-method="accept" data-id={{ $user->id_account }}
                                class="hover:bg-green-600 action-button px-4 gap-x-4 rounded-xl w-full flex flex-row">
                                <p>Accept</p>
                                <img src="/icons/accept_white.svg" alt="" height=24 width=24>
                            </span>
                            <span id="link_button_decline" data-method="decline" data-id={{ $user->id_account }}
                                class="hover:bg-red-600 action-button px-4 gap-x-4 rounded-xl w-full flex flex-row">
                                <p>Refuse</p>
                                <img src="/icons/cancel_white.svg" alt="" height=24 width=24>
                            </span>
                        </div>
                    </div>
                @elseif ($linkStatus == 'pending')
                    <span id="link_button" data-method="cancel" data-id={{ $user->id_account }}
                        class="bg-yellow-400 action-button">Pending</span>
                @else
                    <span id="link_button" data-method="link" data-id={{ $user->id_account }}
                        class="bg-blue-400 action-button">Link</span>
                @endif
                <div id="user_bio_section" class="flex flex-col">
                    <div id="bio">{{ $user->description }}</div>
                    <div id="userProfilelinks" class="cursor-pointer select-none">{{ count($friendships) }} links</div>
                    <!-- <div>X, Y and Z and H other friends in common</div> -->
                    <div id="userProfileFriendlinks"
                        class="flex flex-row items-center cursor-pointer select-none hover:underline">
                        <?php
                        if ($user->id_account != Auth::user()->id_account) {
                            $i = 0;
                            foreach ($commonFriendships as $friend) {
                                echo view('partials.UserProfile.commonFriend', ['number' => $i]);
                                $i++;
                                if ($i > 2) {
                                    break;
                                }
                            }
                            echo '<p class="text-base mr-2 ml-4"></p>';
                            $i = 0;
                            foreach ($commonFriendships as $friend) {
                                echo $friend->name;
                                $i++;
                                if ($i == count($commonFriendships) - 1) {
                                    echo ' and ';
                                    continue;
                                } elseif ($i > 1 || $i == count($commonFriendships)) {
                                    break;
                                } else {
                                    echo ', ';
                                }
                            }
                        
                            if ($i > 2) {
                                echo ' and ' . count($friendships) - $i . ' other links in common.';
                            } elseif ($i == 0) {
                                echo 'No friends in common.';
                            } else {
                                echo ' in common.';
                            }
                            echo '</p>';
                        }
                        ?>
                    </div>
                </div>
                <div id="filters" class="grid grid-cols-5 gap-x-4 mx-4 h-fit">
                    <a class="profile-page-filter-button profile-page-filter-button-selected"> All</a>
                    <a class="profile-page-filter-button">Posts</a>
                    <a class="profile-page-filter-button">Promotions</a>
                    <a class="profile-page-filter-button">Reactions</a>
                    <a class="profile-page-filter-button">Responses</a>
                </div>
            </section>


            @if ($user->id_account == Auth::user()->id_account || Auth::user()->is_admin === true)
                <?php echo view('partials.UserProfile.editModal', ['user' => $user]); ?>
            @endif

            <div id="posts">
                @each('partials.post', $posts, 'post')
            </div>
        @elseif($user->is_private && !$user->is_blocked)
            <section id="private_profile">
                <div id="private_profile_info" class="flex flex-col">
                    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" id="profile_image"
                        class="m-auto rounded-full">
                    <div id="user_identity_info" class="m-auto flex flex-col">
                        <div id="name">{{ $user->name }}</div>
                        <div id="username"> {{ '@' . $user->account_tag }}</div>
                    </div>
                </div>
                <div id="warning_private_profile">
                    <span>This profile is private</span><br>
                    <span>Link with {{ $user->name }} to see it </span>
                </div>
                @if ($linkStatus == 'received')
                    <div class="flex flex-col w-fit h-fit relative group">
                        <span id="link_button" data-method="expand" data-id={{ $user->id_account }}
                            class="bg-blue-400 action-button flex flex-row">
                            <p class="flex-shrink-0">Link Received</p>
                        </span>
                        <div
                            class="hidden group-hover:block bg-blue-400 w-fit p-2 rounded-xl absolute right-0 translate-x-full">
                            <span id="link_button_accept" data-method="accept" data-id={{ $user->id_account }}
                                class="hover:bg-green-600 action-button px-4 gap-x-4 rounded-xl w-full flex flex-row">
                                <p>Accept</p>
                                <img src="/icons/accept_white.svg" alt="" height=24 width=24>
                            </span>
                            <span id="link_button_decline" data-method="decline" data-id={{ $user->id_account }}
                                class="hover:bg-red-600 action-button px-4 gap-x-4 rounded-xl w-full flex flex-row">
                                <p>Refuse</p>
                                <img src="/icons/cancel_white.svg" alt="" height=24 width=24>
                            </span>
                        </div>
                    </div>
                @elseif ($linkStatus == 'pending')
                    <span id="link_button" data-method="cancel" data-id={{ $user->id_account }}
                        class="bg-yellow-400 action-button before:content-['Pending'] hover:bg-orange-400 hover:before:content-['Cancel']"></span>
                @else
                    <span id="link_button" data-method="link" data-id={{ $user->id_account }}
                        class="bg-red-400 action-button">Link</span>
                @endif
            </section>
        @elseif($user->is_blocked)
            <section id="private_profile">
                <div id="private_profile_info" class="flex flex-col">
                    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" id="profile_image"
                        class="m-auto rounded-full">
                    <div id="user_identity_info" class="m-auto flex flex-col">
                        <div id="name">{{ $user->name }}</div>
                        <div id="username"> {{ '@' . $user->account_tag }}</div>
                    </div>
                </div>
                <div id="warning_private_profile">
                    <span>This profile is blocked</span><br>
                </div>
                @if (Auth::user()->is_admin)
                    <a class="bg-red-400 hover:bg-red-700 action-button" id="unblock_button"
                        href={{ '/users/unblock/' . $user->id_account }}>Unblock</a>
                @endif
            </section>
        @endif
    </section>

    <?php echo view('partials.rightPanel.panel', ['type' => 'profile', 'friends' => $friendships, 'userID' => $user->id_account, 'groups' => $groups]); ?>

@endsection
