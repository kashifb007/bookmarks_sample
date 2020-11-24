@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')


    <div class="mdl-typography--font-light mdl-typography--display-1-color-contrast">
        {{ $username }} {{ __('Bookmarks') }}
    </div>

    @include('layouts.bookmarks_content')

    <script type="text/javascript">

        var throttled = _.throttle(search, 1000);

        function search(search_string = '', user_id, category_id = '') {
            $.ajax({
                type: 'get',
                url: '{{URL::to('publicsearch')}}',
                data: {'search': search_string, 'user_id': user_id, 'category_id': category_id},
                success: function (data) {
                    $('.bookmark_content').html(data);
                }
            });
        }

        var search_input = $('#search');

        if (search_input.val().length === 0) {
            throttled('', {{ $user_id }}, '');
        }

        search_input.on('keyup', function () {
            if (search_input.val().length > 2) {
                throttled($(this).val(), {{ $user_id }}, '');
            } else {
                throttled('', {{ $user_id }}, '');
            }
        })

    </script>
    <script type="text/javascript">
        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
    </script>

@endsection
