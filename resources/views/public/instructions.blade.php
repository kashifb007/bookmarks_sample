@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    <h3 class="title is-3 is-bold is-spaced">
        {{ __('Copy link from Twitter') }}
    </h3>

    <ol>
        <li>Simply click the option to "Copy link to Tweet"</li>
        <li>Paste into the URL when creating a new bookmark</li>
    </ol>

    <img src="{{ asset('images/twitter.png/') }}" alt="Twitter Instructions" />

    <br/><br/><br/>

    <h3 class="title is-3 is-bold is-spaced">
        {{ __('Copy link from YouTube') }}
    </h3>

    <ol>
        <li>Click on "Share", then click "Copy". You may also set a start time then click "Copy".</li>
        <li>Paste into the URL when creating a new bookmark</li>
    </ol>

    <img src="{{ asset('images/youtube.png') }}" alt="YouTube Instructions" />

@endsection
