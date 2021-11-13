@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    @include('layouts.multiselect')

    <p class="mdl-typography--font-light">Remember, every bookmark you save is <strong>private</strong> by default, you
        can later share your bookmarks with
        other
        users or share
        categories, or make your categories Public.</p>

    <form method="post" action="{{ route('bookmarks.store') }}" id="bookmark_form" name="bookmark_form">
        @csrf
        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input @error('url') is-invalid @enderror"
                       id="url" type="text" name="url" value="{{ old('url') }}" required/>
                <label class="mdl-textfield__label" for="url">{{ __('Link Address (URL)') }}</label>
                <span class="mdl-textfield__error">Please enter a URL</span>

                @error('url')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input @error('title') is-invalid @enderror"
                       id="title" type="text" name="title" value="{{ old('title') }}"/>
                <label class="mdl-textfield__label" for="title">{{ __('Title') }}</label>

                @error('title')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <textarea class="mdl-textfield__input" type="text" rows="3"
                          name="description" id="description">{{ old('description') }}</textarea>
                <label class="mdl-textfield__label" for="description">{{ __('Description') }}</label>

                @error('description')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="paragraph">
            {{ __('Visibility of this Bookmark') }}
            <select name="visibility" id="visibility">
                <option selected="selected" value="private">{{ __('Private') }}</option>
                <option value="public">{{ __('Public') }}</option>
                <option value="shared">{{ __('Friends') }}</option>
            </select>
        </div>

        @if($categoriesCount > 0)
            <div class="paragraph">
                <div class="field">
                    <div class="control">
                        {{ __('Add to existing Categories, you can type and press Enter') }}
                        <div class="ui fluid multiple search normal selection dropdown">
                            <input type="hidden" name="categories" id="categories" value="{{ old('categories') }}">
                            <i class="dropdown icon"></i>
                            <div class="default text">{{ __('Select Categories') }}</div>
                            <div class="menu">
                                <?php
                                echo $treeData;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($tagsCount > 0)
            <div class="paragraph">
                <div class="field">
                    <div class="control">
                        {{ __('Add to existing Tags, you can type and press Enter') }}
                        <div class="ui fluid multiple search normal selection dropdown">
                            <input type="hidden" name="tags" id="tags" value="{{ old('tags') }}">
                            <i class="dropdown icon"></i>
                            <div class="default text">{{ __('Select Tags') }}</div>
                            <div class="menu">
                                @foreach($tags as $tag)
                                    <div class="item" data-value="{{ $tag->id }}">{{ $tag->title }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input"
                       id="the_category" type="text" name="the_category" value="{{ old('the_category') }}"/>
                <label class="mdl-textfield__label" for="the_category">{{ __('Insert into this new category') }}</label>
            </div>
        </div>

        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input"
                       id="the_tag" type="text" name="the_tag" value="{{ old('the_tag') }}"/>
                <label class="mdl-textfield__label" for="the_tag">{{ __('Insert into this new tag') }}</label>
            </div>
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

    <div class="errors">

        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>
@endsection
