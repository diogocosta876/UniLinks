<div id="editUserModal"
    class="hidden fixed flex justify-center items-center overflow-y-auto overflow-x-hidden h-screen w-screen top-0 right-0 left-0 z-50">
    <div id="editUserModalBack" class="absolute w-full h-full top-0 bg-black opacity-20">
    </div>
    <div class="relative w-fit h-fit bg-white opacity-100 flex flex-col justify-start items-center p-4 rounded-lg mt-4">
        <Form method="POST" id="edit_profile" action={{ route('profile.edit', ['id' => $user->id_account]) }}>
            {{ csrf_field() }}

            <h3 class="text-4xl mb-4">Edit Profile</h3>

            <!-- Obligatory -->
            <label for="name">Name</label>
            <input id="editUserName" type="text" name="name" <?php echo 'data-default-name="' . $user->name . '"'; ?> value={{ $user->name }}
                maxlength="32">
            @if ($errors->has('name'))
                <span class="error">
                    {{ $errors->first('name') }}
                </span>
            @endif

            <label id="editUserPrivacy" data-default-privacy={{ $user->is_private }} for="privacy">Privacy</label>
            <div class="flex flex-row justify-start">
                <input id="editUserPrivacyPrivate" type="radio" name="privacy" value="private" id="profilePrivacy"
                    <?php if ($user->is_private) {
                        echo 'checked';
                    } ?>>
                <label for="privacy">Private</label>
            </div>
            <div class="flex flex-row justify-star mb-4">
                <input id="editUserPrivacyPublic" type="radio" name="privacy" value="public" id="profilePrivacy"
                    <?php if (!$user->is_private) {
                        echo 'checked';
                    } ?>>
                <label for="privacy">Public</label>
            </div>
            <label for="pronouns">Pronouns</label>
            <input id="editUserPronouns" <?php echo 'data-default-pronouns="' . $user->pronouns . '"'; ?> value={{ $user->pronouns }} type="text" name="pronouns"
                id="pronouns" maxlength="20">
            @if ($errors->has('pronouns'))
                <span class="error">
                    {{ $errors->first('pronouns') }}
                </span>
            @endif

            <label for="location">Location</label>
            <input id="editUserLocation" <?php echo 'data-default-location="' . $user->location . '"'; ?> value={{ $user->location }} type="text" name="location"
                id="location" maxlength="32">
            @if ($errors->has('location'))
                <span class="error">
                    {{ $errors->first('location') }}
                </span>
            @endif

            <div class="flex flex-col">
                <label for="description">Description</label>
                <textarea id="editUserDescription" <?php echo 'data-default-description="' . $user->description . '"'; ?> value={{ $user->description }} name="description"
                    id="description" autocomplete=off cols="30" rows="10" maxlength="255"></textarea>
                @if ($errors->has('description'))
            </div>
            <span class="error">
                {{ $errors->first('description') }} {{-- Need to create validator --}}
            </span>
            @endif

            <div class="flex flex-row justify-center w-72 m-auto">
                <button type="submit" class="w-36 mr-10 bg-blue-400 hover:bg-blue-700 action-button">Save</button>
                <button id="editUserExit" type="button"
                    class="w-36 bg-red-400 hover:bg-red-700 action-button">Exit</button>
            </div>
        </Form>
    </div>
</div>
</div>

</div>
