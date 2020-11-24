@extends('layouts.app')

@section('content')
    <h5 class="title is-5 is-bold is-spaced">
        {{ __('Reset Password') }}
    </h5>

    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field">
            <div class="control">
                {{ __('E-mail Address') }}
                <input id="email" type="email" class="input is-medium is-info @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}"
                       required autocomplete="email" autofocus placeholder="{{ __('E-mail Address') }}">

                @error('email')
                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                @enderror
            </div>
        </div>

        <button type="submit" class="button is-link"> {{ __('Send Password Reset Link') }}</button>

    </form>

@endsection
