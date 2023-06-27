<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
    </script>


    <script src="https://kit.fontawesome.com/343294b271.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
</head>

<body>
    <main>
        <div id="background_color_1"></div>
        <header class="sticky w-full">
            <h1 class="desktop"><a href="{{ url('/timeline') }}">UniLinks</a></h1>
            @if (Auth::check())
                <h1 class="mobile">
                    <a class="bars-menu">
                        <i class=" fa-sharp fa-solid fa-bars"></i>
                    </a>
                </h1>
            @else
                <h1 class="mobile"><a href="{{ url('/timeline') }}">UniLinks</a></h1>
            @endif

            @if (Auth::check())
                <div class="search_bar flex flex-row">
                    <!-- TODO -->
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <form type="get" action="{{ url('/user_search') }}">
                        <input id="account_tag" type="text" name="account_tag" required>
                    </form>
                </div>
                <div class="flex flex-row justify-evenly items-center">
                    @if (Auth::user()->is_admin === true)
                        <a class="justify-self-center desktop" href="{{ url('/users') }}">Users</a>
                        <a class="justify-self-center mobile" href="{{ url('/users') }}">
                            <i class="fa-solid fa-hammer"></i>
                        </a>
                    @endif
                    <a class="logout_button desktop" href="{{ url('/logout') }}"> Logout </a>
                    <a class="logout_button mobile"\ href="{{ url('/logout') }}">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                    <a class="mobile" href="{{ route('profile', Auth::user()->id_account) }}">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <a class="desktop" href="{{ route('profile', Auth::user()->id_account) }}">
                        {{ Auth::user()->name }} </a>
                </div>
            @endif
        </header>

        <section id="content">

            @yield('content')
        </section>


        <footer class=" bottom-0 w-full flex flex-row justify-evenly items-center">
            <h1>UniLinks</h1>
            <a href="{{ url('/contacts') }}">Contacts</a>
            <a href="{{ url('/about_us') }}">About Us</a>
        </footer>

    </main>
</body>

</html>
