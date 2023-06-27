@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('login') }}" class="flex flex-col">
    {{ csrf_field() }}
    <label for="email">E-mail</label>
    <input id="email" type="email" name="email" class="mb-4" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <label for="password" >Password</label>
    <input id="password" type="password" name="password" class="mb-4" required>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <label class="hidden">
        <input type="checkbox" name="remember2" {{old('remember3') ? 'checked' : ''}}> Remember Me
    </label>

    <a href="{{ route('recovery') }}" class="underline">I forgot my password</a>

    <div class="flex flex-row justify-evenly mt-5" >
        <button type="submit">Login</button>
        <a class="button button-outline" href="{{ route('register') }}">Register</a>
    </div>
</form>
@endsection