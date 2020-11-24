@extends('layouts.mdc')

@section('content')

    <a class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent copy-button" href="{{ route('bookmarks.create') }}" class="button is-success">{{ __(' Create Bookmark') }}</a>

    <div class="mdl-typography--font-light mdl-typography--display-1-color-contrast">Bookmarks tagged in "{{ $theTag->title }}"</div>

    @include('layouts.bookmarks_content')

    <script type="text/javascript">

        var throttled = _.throttle(search, 1000);

        function search(search_string = '', tag_id) {
            $.ajax({
                type: 'get',
                url: '{{URL::to('tagsearch')}}',
                data: {'search': search_string, 'tag_id': tag_id},
                success: function (data) {
                    $('.bookmark_content').html(data);
                }
            });
        }

        var search_input = $('#search');

        if (search_input.val().length === 0) {
            throttled('', {{ $theTag->id }});
        }

        search_input.on('keyup', function () {
            if (search_input.val().length > 2) {
                throttled($(this).val(), {{ $theTag->id }});
            } else {
                throttled('', {{ $theTag->id }});
            }
        })

    </script>
    <script type="text/javascript">
        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
    </script>

@endsection
