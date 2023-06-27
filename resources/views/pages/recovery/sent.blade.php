@extends('layouts.app')

@section('content')
<div>
    <h2 class="text-xl text-black">Password Recovery</h2>

    <p id="recovery-status" data-id="{{$email}}">We are preparing an email for you.</p>
</div>
@endsection