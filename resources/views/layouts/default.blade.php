<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_desc', 'Laravel Bookmarks')">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">

    <title>@yield('title', 'Laravel Bookmarks')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->

    <!-- Styles -->
    <link href="{{ asset('css/reset-min.css') }}" rel="stylesheet">    

    <!-- Google MDL -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">

    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

    <link href="{{ asset('css/material-min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-min.css') }}" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

</head>

<body ng-app="MyApp">

<!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
  <header class="mdl-layout__header">
    <div class="mdl-layout__header-row">
      <!-- Title -->
      <span class="mdl-layout-title"><h4>
        @auth
        <a href="/bookmarks">{{ Auth::user()->firstname }}'s Bookmarks</a> - {{ Auth::user()->email }}
        @else
        <a href="/">My Bookmarks</a>
        @endauth
      </h4></span>
      <!-- Add spacer, to align navigation to the right -->
      <div class="mdl-layout-spacer"></div>
      <!-- Navigation. We hide it in small screens. -->
      <nav class="mdl-navigation mdl-layout--large-screen-only">                        
              <a class="mdl-navigation__link mdl-typography--text-uppercase" href="/">Home</a>
              @guest
              <a class="mdl-navigation__link mdl-typography--text-uppercase" href="{{ route('register') }}">Register</a>
              @endguest
        <!-- Add spacer, to align navigation to the right in desktop -->
          <div class="android-header-spacer mdl-layout-spacer"></div>
        <button class="android-more-button mdl-button mdl-js-button mdl-button--icon mdl-js-ripple-effect" id="more-button">
            <i class="material-icons">account_circle</i>
          </button>
          <ul class="mdl-menu mdl-js-menu mdl-menu--bottom-right mdl-js-ripple-effect" for="more-button">
            @auth
            <a href="{{ route('login') }}"><li disabled class="mdl-menu__item">{{ __('Login') }}</li></a>
            <a href="{{ route('logout') }}"  onclick="event.preventDefault();document.getElementById('logout-form').submit();">
              <li class="mdl-menu__item">{{ __('Logout') }}</li></a>
            @else
            <a href="{{ route('login') }}"><li class="mdl-menu__item">{{ __('Login') }}</li></a>
            <a href="{{ route('logout') }}"  onclick="event.preventDefault();document.getElementById('logout-form').submit();">
              <li disabled class="mdl-menu__item">{{ __('Logout') }}</li></a>
            @endauth         
          </ul>    
      </nav>
    </div>
  </header>
  <div class="mdl-layout__drawer">
    <span class="mdl-layout-title">My Bookmarks</span>
    <nav class="mdl-navigation">
      <a class="mdl-navigation__link" href="/bookmarks/create">Create Bookmark</a>
      <a class="mdl-navigation__link" href="/bookmarks">List Bookmarks</a>
      <a class="mdl-navigation__link" href="/tags/create">Create Tag</a>
      <a class="mdl-navigation__link" href="/tags">List Tags</a>
      <a class="mdl-navigation__link" href="/categories/create">Create Category</a>
      <a class="mdl-navigation__link" href="/categories">List Categories</a>
    </nav>
  </div>
  <main class="mdl-layout__content">
    <div class="page-content">
        
<h1>@yield('heading', 'Laravel Bookmarks')</h1>

@yield('content')

    </div>
  </main>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>  
</div>
    <!-- Angular -->
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js"></script>
    <script src="{{ asset('js/angular-material.js') }}"></script>
    <script src="{{ asset('js/assets-cache.js') }}"></script>
    <script>
      angular.module('MyApp')
      .controller('AppCtrl', function($scope) {})    
    </script>      
</body>
</html>