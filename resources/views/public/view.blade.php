@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    <div class="mdl-typography--font-light mdl-typography--display-1-color-contrast">
        Bookmarks in "{{ $catz->title }}" category
    </div>

    @if($catTags->count() > 0)
        <div class="mdl-cell mdl-cell--6-col center">
            <div class="paragraph tags">
                <strong>Tags in "{{ $catz->title }}" category</strong>
            </div>

            <form method="post" action="{{ route('show_public_cat_tags') }}" id="catTag" name="catTag">
                @csrf
                <input type="hidden" name="category" id="category" value="{{ $catz->id }}"/>
                <input type="hidden" name="username" id="username" value="{{ $username }}"/>
                <div class="paragraph">
                    <div class="field">
                        <div class="control">
                            <div class="ui fluid multiple search normal selection dropdown">
                                <input type="hidden" name="catTags" id="catTags" value="{{ $tagz }}">
                                <i class="dropdown icon"></i>
                                <div class="default text">{{ __('Select Tags') }}</div>
                                <div class="menu">
                                    @foreach($catTags as $catTag)
                                        <div class="item" data-value="{{ $catTag->id }}">{{ $catTag->title }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit"
                        class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">{{ __('Select Tags') }}</button>
            </form>
        </div>
    @endif

    @if ($showImport)
        @auth
            <form id="save-category" action="{{ route('copy_category') }}" method="POST">
                @csrf
                <input type="hidden" name="category_id" value="{{ $category_id }}"/>
                <input type="hidden" name="category_title" value="{{ $catz->title }}"/>
                <button type="submit" class="button is-link">{{ __('Import this Category and its bookmarks') }}</button>
            </form>
        @endauth
    @endif

    @guest
        <p></p>
        <p>Please <a href="{{ route('register') }}">register</a> if you want to import this category into your bookmarks.</p>
    @endguest

    <br/>
    <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent copy-button" href="{{ url('/public') }}/{{ $username }}" class="button is-success">{{ $username }}{{ __(' Bookmarks') }}</a>

    <br/><br/>
    <p>{{ $catz->description }}</p>

    @include('layouts.bookmarks_content')

    <script type="text/javascript">

        var throttled = _.throttle(search, 1000);

        function search(search_string = '', user_id, category_id = '', tags = 0) {
            $.ajax({
                type: 'get',
                url: '{{URL::to('publicsearch')}}',
                data: {'search': search_string, 'user_id': user_id, 'category_id': category_id, 'tags': tags},
                success: function (data) {
                    $('.bookmark_content').html(data);
                }
            });
        }

        var search_input = $('#search');

        if (search_input.val().length === 0) {
            throttled('', {{ $user_id }}, {{ $category_id }}, {!! json_encode($tagz, JSON_HEX_TAG) !!});
        }

        search_input.on('keyup', function () {
            if (search_input.val().length > 2) {
                throttled($(this).val(), {{ $user_id }}, {{ $category_id }}, {!! json_encode($tagz, JSON_HEX_TAG) !!});
            } else {
                throttled('', {{ $user_id }}, {{ $category_id }}, {!! json_encode($tagz, JSON_HEX_TAG) !!});
            }
        })

    </script>
    <script type="text/javascript">
        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
    </script>
@endsection
