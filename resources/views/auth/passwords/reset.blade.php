@extends('layouts.app')

@section('content')


    <h5 class="title is-5 is-bold is-spaced">
        {{ __('Reset Password') }}
    </h5>


    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <div class="control">
                {{ __('E-Mail Address') }}

                <input id="email" type="email" class="input is-medium is-info @error('email') is-invalid @enderror" name="email"
                       placeholder="{{ __('E-Mail Address') }}" value="{{ $email ?? old('email') }}" required
                       autocomplete="email" autofocus>

                @error('email')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
        </div>

        <div class="field">
            <div class="control">
                {{ __('Password') }}

                <input id="password" type="password" class="input is-medium is-info @error('password') is-invalid @enderror"
                       name="password" required autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
        </div>

        <div class="field">
            <div class="control">
                {{ __('Confirm Password') }}
                <input id="password-confirm" type="password" class="input is-medium is-info" name="password_confirmation" required>
            </div>
        </div>

        <button type="submit" class="button is-link">
            {{ __('Reset Password') }}
        </button>

    </form>
@endsection
