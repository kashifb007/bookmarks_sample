<div class="mdl-layout__drawer">
    <span class="mdl-layout-title">My Bookmarks</span>
    <nav class="mdl-navigation">
        <a class="mdl-navigation__link" href="/">{{ __('Home') }}</a>
        @guest
            <a class="mdl-navigation__link" href="{{ route('register') }}">{{ __('Register') }}</a>
        @endguest
        @auth
            <a class="mdl-navigation__link" href="{{ route('bookmarks.create') }}">{{ __('Create Bookmark') }}</a>
            <a class="mdl-navigation__link" href="{{ route('bookmarks.index') }}">List Bookmarks</a>
            <a class="mdl-navigation__link" href="{{ route('bookmarks_trash') }}">Deleted Bookmarks</a>
            <a class="mdl-navigation__link" href="{{ route('categories.create') }}">Create Category</a>
            <a class="mdl-navigation__link" href="{{ route('categories.index') }}">List Categories</a>
            <a class="mdl-navigation__link" href="{{ route('categories_trash') }}">Deleted Categories</a>
            <a class="mdl-navigation__link" href="{{ route('tags.index') }}">List Tags</a>
            <a class="mdl-navigation__link" href="{{ route('tags.create') }}">Create Tag</a>
            <a class="mdl-navigation__link" href="{{ route('tags_trash') }}">Deleted Tags</a>
            <a class="mdl-navigation__link" target="_blank"
               href="{{ url('/public') }}/{{ Auth::user()->username }}">{{ __('View Your Public Bookmarks') }}</a>
            <a class="mdl-navigation__link" href="{{ route('logout') }}"
               onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
        @else
            <a class="mdl-navigation__link" href="{{ route('login') }}">{{ __('Login') }}</a>
        @endauth
        <a class="mdl-navigation__link" href="{{ route('instructions') }}">{{ __('Instructions') }}</a>
        <a class="mdl-navigation__link" href="{{ route('contact-us') }}">{{ __('Contact Us') }}</a>
    </nav>
</div>
