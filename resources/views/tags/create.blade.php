@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    <form method="post" action="{{ route('tags.store') }}" id="tag_form" name="tag_form">
        @csrf
        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input @error('title') is-invalid @enderror"
                       id="title" type="text" name="title" value="{{ old('title') }}" required />
                <label class="mdl-textfield__label" for="title">{{ __('Title') }}</label>
                <span class="mdl-textfield__error">Please enter a title</span>

                @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

        <button type="submit"
                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
            {{ __('Create') }}
        </button>
    </form>

    <br/><br/>

    <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
            onclick="location.href='{{ route('bookmarks.index') }}'">
        {{ __('Back to List') }}
    </button>

@endsection
