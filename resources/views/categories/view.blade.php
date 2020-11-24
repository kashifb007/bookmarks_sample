@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent copy-button" href="{{ route('bookmarks.create') }}" class="button is-success">{{ __(' Create Bookmark') }}</a>

    <div class="mdl-typography--font-light mdl-typography--display-1-color-contrast">
        Bookmarks in "{{ $catz->title }}" category
    </div>

    <p>{{ $catz->description }}</p>

    @if($catTags->count() > 0)
        <div class="mdl-cell mdl-cell--6-col center">
            <div class="paragraph tags">
                <strong>Tags in "{{ $catz->title }}" category</strong>
            </div>

            <form method="post" action="{{ route('show_cat_tags') }}" id="catTag" name="catTag">
                @csrf
                <input type="hidden" name="category" id="category" value="{{ $catz->id }}"/>
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

    @include('layouts.bookmarks_content')

    <script type="text/javascript">

        var throttled = _.throttle(search, 1000);

        function search(search_string = '', category_id, tagz = 0) {
            $.ajax({
                type: 'get',
                url: '{{URL::to('catsearch')}}',
                data: {'search': search_string, 'category_id': category_id, 'tagz': tagz},
                success: function (data) {
                    $('.bookmark_content').html(data);
                }
            });
        }

        var search_input = $('#search');

        if (search_input.val().length === 0) {
            throttled('', {{ $category_id }}, {!! json_encode($tagz, JSON_HEX_TAG) !!});
        }

        search_input.on('keyup', function () {
            if (search_input.val().length > 2) {
                throttled($(this).val(), {{ $category_id }}, {!! json_encode($tagz, JSON_HEX_TAG) !!});
            } else {
                throttled('', {{ $category_id }}, {!! json_encode($tagz, JSON_HEX_TAG) !!});
            }
        })

    </script>
    <script type="text/javascript">
        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
    </script>

@endsection
