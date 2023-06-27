@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('recovery.change') }}" class="flex flex-col">

    <h1 class="text-2xl mb-6 whitespace-nowrap">Password Recovery</h1>
        
    {{ csrf_field() }}
    <label for="email">E-mail</label>
    <input id="email" type="email" name="email" class="mb-4" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
            {{ $errors->first('email') }}
        </span>
    @endif

    <!-- Obligatory-->
    <label for="token">Token</label>
    <input id="token" type="token" name="token" required>
    @if ($errors->has('token'))
        <span class="error">
            {{ $errors->first('token') }}
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
    <div class="flex flex-row justify-evenly mt-5" >
        <button type="submit">Recover</button>
    </div>

    <span class="text-center mt-4 bg-red-600 text-white font-semibold rounded-md">
        @if(session('response'))
            {{ session('response')}}
        @endif
    </span>
</form>

@endsection