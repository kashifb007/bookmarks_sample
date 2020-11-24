@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    <form method="post" action="/tags/{{ $tag->id }}" id="tag_form" name="tag_form">
        {{ method_field('PATCH') }}
        @csrf

        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input @error('title') is-invalid @enderror"
                       id="title" type="text" name="title" value="{{ $tag->title }}" required />
                <label class="mdl-textfield__label" for="title">{{ __('Title') }}</label>
                <span class="mdl-textfield__error">Please enter a title</span>

                @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

            <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                    type="submit">
                {{ __('Update') }}
            </button>

            @if($tag->tag_status === 'deleted')
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                        onclick="location.href='{{ url('/tags/'.$tag->id.'/restore') }}'">
                    {{ __('Restore') }}
                </button>
            @else
                <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent"
                        onclick="location.href='{{ url('/tags/'.$tag->id.'/delete') }}'">
                    {{ __('Delete') }}
                </button>
            @endif

            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored"
                    onclick="location.href='{{ route('categories.index') }}'">
                {{ __('Back to List') }}
            </button>

            <div class="errors">
                <ul id='errors'>
                    <li ng-show="tag_form.title.$invalid && !tag_form.title.$pristine">
                        Please enter a tag
                    </li>
                </ul>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

    </form>

@endsection
