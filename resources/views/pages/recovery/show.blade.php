@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('recovery') }}" class="flex flex-col">

    <h1 class="text-2xl mb-6 whitespace-nowrap">Password Recovery</h1>

    {{ csrf_field() }}
    <label for="email">E-mail</label>
    <input id="email" type="email" name="email" class="mb-4" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @else
        @if(session('emailnotfound'))
        <span class="error">
            {{ session('emailnotfound') }}
          </span>
        @endif
    @endif

    <div class="flex flex-row justify-evenly mt-5" >
        <button type="submit">Recover</button>
    </div>
</form>
@endsection