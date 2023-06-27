@extends ('layouts.app')

@section('content')
<section class="content">
    <div class="flex flex-col mt-4">
        <form method="POST" id="create_user" action="{{ route('admin.store') }}" class = "rounded-2xl bg-white flex flex-col justify-between">
            {{ csrf_field() }} 
            <div class="text-black text-3xl mb-4">Create New User</div>
            <label for="name" class="mt-4">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required>
            @if ($errors->has('name'))
                <span class="error">
                    {{ $errors->first('name') }} {{-- Need to create validator --}}
                </span>
            @endif
            <label for="account_tag" class="mt-4">Account Tag</label>
            <input id="account_tag"  type="text" name="account_tag" value="{{ old('account_tag') }}" required>
            @if ($errors->has('account_tag'))
                <span class="error">
                    {{ $errors->first('account_tag') }} {{-- Need to create validator --}}
                </span>
            @endif
            <label for="email" class="mt-4">E-Mail (use educational email for premium verification)</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @if ($errors->has('email'))
                <span class="error">
                    {{ $errors->first('email') }} {{-- Need to create validator --}}
                </span>
            @endif
                <!-- Obligatory-->
            <label for="password" class="mt-4">Password</label>
            <input id="password" type="password" name="password" required>
            @if ($errors->has('password'))
            <span class="error">
                {{ $errors->first('password') }}
            </span>
            @endif

            <label for="password-confirm" class="mt-2">Confirm Password</label>
            <input id="password-confirm" type="password" name="password_confirmation" required>

            <!-- Obligatory-->
            <label for="birthday" class="mt-2">Birthday</label>
            <input id="birthday" type="date" name="birthday" value="{{ old('birthday') }}" required>
            @if ($errors->has('birthday'))
            <span class="error">
                {{ $errors->first('birthday') }} {{-- Need to create validator --}}
            </span>
            @endif

            <!-- TO DO: UNIVERSITIES LIST -->
            <!-- Obligatory -->
            <label for="university" class="mt-4">University</label>
            <select name="university" id="university" value="{{ old('university') }}" required>
            <option value="porto">Universidade Porto</option>
            <option value="minho">Universidade Minho</option>
            <option value="coimbra">Universidade Coimbra</option>
            <option value="lisboa">Universidade Lisboa</option>
            </select>

            <!-- Obligatory -->
            <label for="course" class="mt-4">Course</label>
            <input id="course" type="text" name="course" value="{{ old('course') }}" required>
            @if ($errors->has('course'))
            <span class="error">
                {{ $errors->first('course') }} {{-- Need to create validator --}}
            </span>
            @endif

            <label for="privacy" class="mt-3">Privacy</label>
            <div class="flex flex-row">
                <input type="radio" name="privacy" value="private" id="profilePrivacy" checked>
                <label for="privacy">Private</label>
            </div>
            <div class="flex flex-row">
                <input type="radio" name="privacy" value="public" id="profilePrivacy">
                <label for="privacy">Public</label>
            </div>

            <label for="pronouns" class="mt-2">Pronouns</label>
            <input type="text" name="pronouns" id="pronouns" maxlength="20">
            @if ($errors->has('pronouns'))
            <span class="error">
                {{ $errors->first('pronouns') }}
            </span>
            @endif

            <label for="is_admin" class="mt-2">Is Admin?</label>
            <div class="flex flex-row">
                <input type="radio" name="is_admin" value="true" id="is_admin">
                <label for="is_admin">Yes</label>
            </div>
            <div class="flex flex-row">
                <input type="radio" name="is_admin" value="false" id="is_admin" checked>
                <label for="is_admin">No</label>
            </div>

            <label for="location" class="mt-2">Location</label>
            <input type="text" name="location" id="location" maxlength="32">
            @if ($errors->has('location'))
            <span class="error">
                {{ $errors->first('location') }}
            </span>
            @endif

            <label for="description " class="mt-3">Description</label>
            <textarea name="description" id="description" cols="30" rows="10" autocomplete=off maxlength="255" class="resize-none h-36"></textarea>
            @if ($errors->has('description'))
            <span class="error">
                {{ $errors->first('description') }} {{-- Need to create validator --}}
            </span>
            @endif

            <button type="submit" class="mx-auto mt-4 w-36 bg-blue-400 hover:bg-blue-700 action-button">
            Register
            </button>
        </form>
    </div>
</section>
@endsection