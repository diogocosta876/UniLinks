@extends('layouts.app')

@section('title', $post->name)

@section('content')

    <?php echo view('partials.leftPanel.panel'); ?>


    @include('partials.post', ['post' => $post])

    <?php echo view('partials.rightPanel.panel', ['type' => 'post']); ?>

@endsection
