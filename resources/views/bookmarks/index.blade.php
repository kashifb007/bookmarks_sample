@extends('layouts.bookmarks')

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

    <div id="app"></div>

    <script type="text/javascript">
        $.ajaxSetup({headers: {'csrftoken': '{{ csrf_token() }}'}});
    </script>

@endsection
