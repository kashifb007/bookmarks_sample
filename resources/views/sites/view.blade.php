@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent copy-button" href="{{ route('bookmarks.create') }}" class="button is-success">{{ __(' Create Bookmark') }}</a>

    <div class="mdl-typography--font-light mdl-typography--display-1-color-contrast">
        Selected Site = {{ $site->rootLink->url }}
    </div>

    @include('layouts.bookmarks_content')

    <script type="text/javascript">

        var throttled = _.throttle(search, 1000);

        function search(search_string = '', site_id) {
            $.ajax({
                type: 'get',
                url: '{{URL::to('sitesearch')}}',
                data: {'search': search_string, 'site_id': site_id},
                success: function (data) {
                    $('.bookmark_content').html(data);
                }
            });
        }

        var search_input = $('#search');

        if (search_input.val().length === 0) {
            throttled('', {{ $site->id }});
        }

        search_input.on('keyup', function () {
            if (search_input.val().length > 2) {
                throttled($(this).val(), {{ $site->id }});
            } else {
                throttled('', {{ $site->id }});
            }
        })
        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});

    </script>
@endsection
