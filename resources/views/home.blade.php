@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')
    @if (session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif

    <p>You need to receive a password reset email to change your password for security reasons.</p>
    <a href="{{ url('/password/reset') }}">{{ __('Reset Password') }}</a>

@endsection
