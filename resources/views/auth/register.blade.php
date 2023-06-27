@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register.post') }}" class = "rounded-2xl bg-white flex flex-col justify-between">
    {{ csrf_field() }} 
    <p class="font-medium text-2xl" id="register-title"> Register </p>

    <!-- Obligatory -->
    <label for="accounttag" class="mt-4">Account Tag</label>
    <input id="accounttag"  type="text" name="accounttag" value="{{ old('accounttag') }}" required>
    @if ($errors->has('accounttag'))
      <span class="error">
          {{ $errors->first('accounttag') }} {{-- Need to create validator --}}
      </span>
    @endif
    
    <!-- Obligatory and Unique-->
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

    <button type="submit" class="mt-4">
      Register
    </button>
    <a class="mt-2" href="{{ route('login') }}"> I already have an account </a>
</form>
@endsection
