@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')
    <h2>Welcome to My Bookmarks</h2>

    @if (Route::has('login'))
        @auth
            <!-- if logged in -->
            <a href="{{ route('bookmarks.index') }}" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">View My Bookmarks</a>
            <p>You need to receive a password reset email to change your password for security reasons.</p>
            <a href="{{ url('/password/reset') }}">{{ __('Reset Password') }}</a>
        @else
            <!-- if has login and not logged in -->
            <form method="POST" action="{{ route('login') }}" id="login" name="login" ng-controller="AppCtrl">
                @csrf

                <div class="paragraph">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input @error('url') is-invalid @enderror"
                               id="username" type="text" name="username" value="{{ old('username') }}" required/>
                        <label class="mdl-textfield__label" for="username">
                            {{ __('Username or E-mail') }}</label>
                        <span class="mdl-textfield__error">Please enter a username/email</span>
                    </div>
                </div>

                <div class="paragraph">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input @error('url') is-invalid @enderror"
                               id="password" type="password" name="password" value="{{ old('password') }}" required/>
                        <label class="mdl-textfield__label" for="password">
                            {{ __('Password') }}</label>
                        <span class="mdl-textfield__error">Please enter a password</span>
                    </div>
                </div>

                <div id="remember_me">
                    <input class="form-check-input" type="checkbox" name="remember"
                           id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                        type="submit">
                    {{ __('Login') }}
                </button>

                @guest
                    @if (Route::has('register'))
                        <p><a class="btn btn-link" href="{{ route('register') }}">
                                {{ __('Register') }}
                            </a></p>
                    @endif
                @endguest

                @if (Route::has('password.request'))
                    <p class="subtitle is-muted">
                        <a href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </p>
                @endif

                <div id="errors">
                    <ul id='errors'>
                        <li ng-show="login.username.$invalid && !login.username.$pristine">Please enter a valid
                            username or e-mail
                            address.
                        </li>
                        <li ng-show="login.password.$invalid && !login.password.$pristine">Please enter a
                            password.
                        </li>
                    </ul>
                </div>

            </form>

        @endauth
    @endif
@endsection
