@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    <?php
    if (stripos($title, 'list') !== false) {
        $showTrash = 0;
    } else {
        $showTrash = 1;
    }
    ?>

    <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent copy-button" href="{{ route('bookmarks.create') }}" class="button is-success">{{ __(' Create Bookmark') }}</a>

    @include('layouts.bookmarks_content')

    <script type="text/javascript">

        var throttled = _.throttle(search, 1000);

        function search(search_string = '', show_trash) {
            $.ajax({
                type: 'get',
                url: '{{URL::to('search')}}',
                data: {'search': search_string, 'show_trash': show_trash},
                success: function (data) {
                    $('.bookmark_content').html(data);
                }
            });
        }

        var search_input = $('#search');

        if (search_input.val().length === 0) {
            throttled('', {{ $showTrash }});
        }

        search_input.on('keyup', function () {
            if (search_input.val().length > 2) {
                throttled($(this).val(), {{ $showTrash }});
            } else {
                throttled('', {{ $showTrash }});
            }
        })

    </script>
    <script type="text/javascript">
        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
    </script>

@endsection
