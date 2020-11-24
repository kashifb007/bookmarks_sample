@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    @include('layouts.multiselect')

    <p>Remember, every bookmark you save is <strong>private</strong>, you can later share your bookmarks with other
        users or share
        categories, or make your categories Public.</p>

    <form method="post" action="/bookmarks/{{ $bookmark->id }}" id="bookmark_form" name="bookmark_form">
        {{ method_field('PATCH') }}
        @csrf

        <div class="paragraph">
            <img src="{{ $bookmark->link->og_image }}" alt="{{ $bookmark->link->og_title }}"
                 title="{{ $bookmark->link->og_title }}" style="width: 200px;"/>
        </div>

        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input @error('url') is-invalid @enderror"
                       id="url" type="text" name="url" value="{{ $bookmark->link->url }}" required/>
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
                       id="title" type="text" name="title" value="{{ $bookmark->title }}"/>
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
                          name="description" id="description">{{ $bookmark->description }}</textarea>
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
                <option @if($bookmark->visibility === 'private')selected="selected"
                        @endif value="private">{{ __('Private') }}</option>
                <option @if($bookmark->visibility === 'public')selected="selected"
                        @endif value="public">{{ __('Public') }}</option>
                <option @if($bookmark->visibility === 'shared')selected="selected"
                        @endif value="shared">{{ __('Friends') }}</option>
            </select>
        </div>

        <div class="paragraph">
            {{ __('Status of this Bookmark') }}
            <select name="bookmark_status" id="bookmark_status">
                <option @if($bookmark->bookmark_status === 'live')selected="selected"
                        @endif value="live">{{ __('Live') }}</option>
                <option @if($bookmark->bookmark_status === 'trash')selected="selected"
                        @endif value="trash">{{ __('Trash') }}</option>
            </select>
        </div>

        @if($categoriesCount > 0)
            <div class="paragraph">
                <div class="field">
                    <div class="control">
                        {{ __('Add to existing Categories, you can type and press Enter') }}
                        <div class="ui fluid multiple search normal selection dropdown">
                            <input type="hidden" name="categories" id="categories" value="{{ $selCats }}">
                            <i class="dropdown icon"></i>
                            <div class="default text">{{ __('Select Categories') }}</div>
                            <div class="menu">
                                @foreach($categories as $category)
                                    <div class="item" data-value="{{ $category->id }}">{{ $category->title }}</div>
                                @endforeach
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
                            <input type="hidden" name="tags" id="tags" value="{{ $selTags }}">
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
                <label class="mdl-textfield__label"
                       for="the_category">{{ __('Insert into this new category') }}</label>
            </div>
        </div>

        <div class="paragraph">
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input"
                       id="the_tag" type="text" name="the_tag" value="{{ old('the_tag') }}"/>
                <label class="mdl-textfield__label"
                       for="the_tag">{{ __('Insert into this new tag') }}</label>
            </div>
        </div>

        <div class="paragraph">
        <button type="submit"
                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">
            {{ __('Update') }}
        </button>
        </div>
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>
    </form>

    <div class="paragraph">
    <button onclick="location.href='{{ route('bookmarks.index') }}'"
            class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
        {{ __('Back to List') }}</button>
    </div>


@endsection
