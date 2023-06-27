@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('endregister') }}" class = "rounded-2xl bg-white flex flex-col">
    {{ csrf_field() }} 

    <p class="font-normal text-2xl" id="register-title"> Almost There! </p>

    <!-- Obligatory -->
    <label for="name" class="mt-2">Name</label>
    <input id="name" type="text" name="name" value="{{ old('accounttag') }}" maxlength="32">
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }} {{-- Need to create validator --}}
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


    <div class="flex flex-row justify-between mt-8">
        <button type="submit">
            Continue
        </button>
        <a href="{{ route('timeline') }}" class="self-center">
            Skip
        </a>
    </div>
</form>
@endsection
