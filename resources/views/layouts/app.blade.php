<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Global site tag (gtag.js) - Google Analytics -->

    @if (config('app.env') === 'production')

    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-148138734-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-148138734-1');
    </script>

    @endif

    <title>@if (!empty($title)){{ $title . ' - ' . config('app.name') ?? config('app.name') }}@else{{ config('app.name', 'My Bookmarks') }}@endif</title>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <!-- Scripts -->
    <script src="{{ asset('js/app/app.min.js') }}" defer></script>
    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script src="{{ asset('js/jquery.emailHide.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/fa.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('dist/semantic.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/components/dropdown.min.css') }}">

    <script src="{{ asset('dist/components/transition.js') }}"></script>
    <script src="{{ asset('dist/components/dropdown.js') }}"></script>
{{--    <script src="{{ asset('js/library/library.min.js') }}"></script>--}}
    <script src="{{ asset('js/underscore-min.js') }}"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav id="navbar" class="bd-navbar navbar has-shadow is-spaced">
        <div class="container">

            <div class="columns">
                @guest
                    <div class="column">
                        <h3 class="title is-3 is-bold is-spaced">
                            <a href="{{ url('/bookmarks') }}">
                                {{ __('My Bookmarks') }}
                            </a>
                        </h3>
                    </div>
                @else
                    <div class="column">
                        <h3 class="title is-3 is-bold is-spaced">
                            <a href="{{ url('/bookmarks') }}">
                                {{ __('My Bookmarks') }}
                            </a>
                        </h3>
                    </div>
                @endguest

            <!-- Authentication Links -->
                @guest
                    <div class="column">
                        <p>
                            <a href="{{ route('login') }}">{{ __('Login') }}</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        </p>
                    </div>
                    <div class="column">
                        <p></p>
                        <p>Developed by&nbsp;<a href="https://www.dreamsites.uk" target="_blank">Dream Sites</a></p>
                        <p><a href="{{ url('/contact-us') }}">{{ __('Contact Us') }}</a>&nbsp;&nbsp;
                            <a href="{{ route('instructions') }}">{{ __('Instructions') }}</a></p>
                    </div>
                @else
                    <div class="column">
                        <p>
                            <a id="navbarDropdown" href="{{ route('home') }}" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ __('Welcome ') }} {{ Auth::user()->firstname }} ({{ Auth::user()->username }}) <span
                                    class="caret"></span>
                            </a>

                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('<Logout>') }}
                            </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">
                            @csrf
                        </form>

                    </div>

                    <div class="column">
                        <div class="buttons are-small">
                            <a href="{{ route('bookmarks.create') }}"
                               class="button is-success">{{ __('Create Bookmark') }}</a>
                            <a href="{{ route('bookmarks.index') }}"
                               class="button is-link">{{ __('List Bookmarks') }}</a>
                            <a href="{{ route('categories.index') }}"
                               class="button is-info">{{ __('List Categories') }}</a>
                            <a href="{{ route('tags.index') }}" class="button is-warning">{{ __('List Tags') }}</a>Developed
                            by&nbsp;<a href="https://www.dreamsites.uk" target="_blank">Dream Sites</a>
                            &nbsp;&nbsp;&nbsp;<a href="{{ route('contact-us') }}">{{ __('Contact Us') }}</a>&nbsp;&nbsp;
                            <a href="{{ route('instructions') }}">{{ __('Instructions') }}</a>
                        </div>
                    </div>
        @endguest

    </nav>

    <main>
        <div class="container">

            <div class="container is-fluid">
                <h3 class="title is-3 is-bold is-spaced">
                    @if (!empty($title))
                    @if (strpos($title, '{') !== false)
                        {{ config('app.name', 'My Bookmarks') }}
                        @endif
                        @else
                    @if (!empty($title)){{ $title ?? __('Empty Title!') }}@else{{ config('app.name', 'My Bookmarks') }}@endif
                        @endif
                </h3>

                @yield('content')

            </div>
        </div>
    </main>
</div>
</body>
</html>
