<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Global site tag (gtag.js) - Google Analytics -->
@if (config('app.env') === 'production')
    <!-- Global site tag (gtag.js) - Google Analytics -->
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
    <meta name="description" content="@yield('meta_desc', 'Laravel Bookmarks')">

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">

    <title>@if (!empty($title)){{ $title . ' - ' . config('app.name') ?? config('app.name') }}@else{{ config('app.name', 'My Bookmarks') }}@endif</title>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
            integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script src="{{ asset('js/jquery.emailHide.js') }}"></script>

    <!-- Google MDL -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/fa.css') }}" rel="stylesheet">

    {{-- BEGIN Multi Select Dropdowns--}}
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/semantic.min.css') }}">
    <script src="{{ asset('js/transition_js.min.js') }}"></script>
    <script src="{{ asset('js/dropdown_js.min.js') }}"></script>
    {{-- END Multi Select Dropdowns--}}

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500" rel="stylesheet">

    <script src="{{ asset('js/underscore-min.js') }}"></script>

    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">

    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

    <script defer src="{{ asset('js/jquery.matchHeight.js') }}" type="text/javascript"></script>
</head>
<body class="mdc-typography">

<!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <!-- Title -->
            <span class="mdl-layout-title"><h4>
        @auth
                        <a href="/bookmarks">{{ Auth::user()->firstname }}'s Bookmarks</a>
                        - {{ Auth::user()->username }}
                    @else
                        <a href="/">My Bookmarks</a>
                    @endauth
      </h4></span>
            <!-- Add spacer, to align navigation to the right -->
            <div class="mdl-layout-spacer"></div>
            <!-- Navigation. We hide it in small screens. -->
            <nav class="mdl-navigation mdl-layout--large-screen-only">
                <a class="mdl-navigation__link mdl-typography--text-uppercase" href="{{ route('contact-us') }}">Contact
                    Us</a>
                <a class="mdl-navigation__link mdl-typography--text-uppercase" href="{{ route('instructions') }}">Instructions</a>
                @guest
                    <a class="mdl-navigation__link mdl-typography--text-uppercase"
                       href="{{ route('register') }}">{{ __('Register') }}</a>
            @endguest
            <!-- Add spacer, to align navigation to the right in desktop -->
                <div class="android-header-spacer mdl-layout-spacer"></div>
                <button class="android-more-button mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect"
                        id="more-button">
                    <i class="material-icons">account_circle</i>
                </button>
                <ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" for="more-button">
                    @auth
                        <a href="{{ route('login') }}">
                            <li disabled class="mdl-menu__item">{{ __('Login') }}</li>
                        </a>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <li class="mdl-menu__item">{{ __('Logout') }}</li>
                        </a>
                    @else
                        <a href="{{ route('login') }}">
                            <li class="mdl-menu__item">{{ __('Login') }}</li>
                        </a>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <li disabled class="mdl-menu__item">{{ __('Logout') }}</li>
                        </a>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>
    @include('layouts.left_menu')

    <div class="android-content mdl-layout__content android-customized-section">
        <div class="android-customized-section-text @if (!empty($fullWidth)) full-width @endif">

            <?php if (isset($showFilter)) { ?>
                @include('layouts.multiselect')
                @include('layouts.filter', ['treeData' => $treeData, 'showSites' => $showSites])
            <?php } ?>

            <div class="mdl-typography--font-light mdl-typography--display-1-color-contrast">

                @if (!empty($title))
                    @if (strpos($title, '{') !== false)
                        {{ config('app.name', 'My Bookmarks') }}
                    @else
                        {{ $title }}
                    @endif
                @else
                    {{ config('app.name', 'My Bookmarks') }}
                @endif
            </div>
            @yield('content')
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
<script>
    jQuery(document).ready(function () {
        jQuery('.mdc-card').matchHeight();
    });
</script>
</body>
</html>
