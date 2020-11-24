@extends('layouts.mdc')

@section('title', 'My Bookmarks - Register')
@section('meta_desc', 'My Bookmarks - Register')

@section('heading', 'My Bookmarks -  Registration')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}" id="register_form"
                              name="register_form">
                            @csrf

                            <div class="paragraph">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input @error('firstname') is-invalid @enderror"
                                           id="firstname" type="text" name="firstname" value="{{ old('firstname') }}"
                                           required/>
                                    <label class="mdl-textfield__label" for="firstname">{{ __('First name') }}</label>
                                    <span class="mdl-textfield__error">Please enter your first name</span>

                                    @error('firstname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="paragraph">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input @error('surname') is-invalid @enderror"
                                           id="surname" type="text" name="surname" value="{{ old('surname') }}"
                                           required/>
                                    <label class="mdl-textfield__label" for="firstname">{{ __('Last name') }}</label>
                                    <span class="mdl-textfield__error">Please enter your last name</span>

                                    @error('surname')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="paragraph">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input @error('surname') is-invalid @enderror"
                                           id="username" type="text" name="username" value="{{ old('username') }}"
                                           required/>
                                    <label class="mdl-textfield__label" for="username">{{ __('Username') }}</label>
                                    <span class="mdl-textfield__error">Please enter your username</span>

                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="paragraph">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input @error('email') is-invalid @enderror"
                                           id="email" type="email" name="email" value="{{ old('email') }}"
                                           required/>
                                    <label class="mdl-textfield__label" for="username">{{ __('Email Address') }}</label>
                                    <span class="mdl-textfield__error">Please enter your Email Address</span>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="paragraph">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input class="mdl-textfield__input @error('password') is-invalid @enderror"
                                           id="password" type="password" name="password" value="{{ old('password') }}"
                                           required/>
                                    <label class="mdl-textfield__label" for="password">{{ __('Password') }}</label>
                                    <span class="mdl-textfield__error">Please enter a password</span>

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="paragraph">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                    <input
                                        class="mdl-textfield__input @error('password_confirmation') is-invalid @enderror"
                                        id="password_confirmation" type="password" name="password_confirmation"
                                        value="{{ old('password_confirmation') }}"
                                        required/>
                                    <label class="mdl-textfield__label"
                                           for="password_confirmation">{{ __('Confirm Password') }}</label>
                                    <span class="mdl-textfield__error">Please confirm your password</span>

                                    @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <button type="submit"
                                    class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
                                {{ __('Register') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
