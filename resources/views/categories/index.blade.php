@extends('layouts.mdc')

@section('title', 'Welcome to My Bookmarks')
@section('meta_desc', 'Welcome to My Bookmarks')

@section('heading', 'My Bookmarks Home')

@section('content')

    <main class="mdl-layout__content">
        <div class="paragraph">
            <a href="{{ $create_route }}" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">{{ $create_text }}</a>
            <?php
            if (stripos($title, 'list') !== false) {
            ?>
            <a href="{{ route('categories_trash') }}" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">{{ __('View Recycle Bin') }}</a>
            <?php
            } else {
            ?>
            <a href="{{ route('categories.index') }}" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">{{ __('View Categories') }}</a>
            <?php
            }
            ?>
        </div>
        <div class="paragraph">

            <div class="mdc-data-table">
                <div class="mdc-data-table__table-container">
                    <table class="mdc-data-table__table" aria-label="Dessert calories">
                        <thead>
                        <tr class="mdc-data-table__header-row">
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">Category Name</th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">Parent Category</th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">Created</th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">Last Updated</th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="mdc-data-table__content">
                        @foreach($models as $category)
                            <tr class="mdc-data-table__row">
                                <td class="mdc-data-table__cell">{{ $category->title }}</td>
                                <td class="mdc-data-table__cell">{{ $category->parentCategory->title }}</td>
                                <td class="mdc-data-table__cell">{{$category->created_at->format('d M Y H:i:s')}}</td>
                                <td class="mdc-data-table__cell">{{$category->updated_at->format('d M Y H:i:s')}}</td>
                                <td class="mdc-data-table__cell">
                                    <button id="toggle_show"
                                            onclick="location.href='{{ url('categories/'.$category->id.'/edit') }}'"
                                            class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                                            title="Edit" data-mdc-ripple-is-unbounded="true">
                                        <i class="material-icons mdc-icon-button__icon">create</i>
                                    </button>

                                    @if($category->category_status === 'live' || $category->category_status === 'base')
                                        <button id="toggle_show"
                                                onclick="location.href='{{ url('categories/'.$category->id.'/0/show') }}'"
                                                class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                                                title="Show" data-mdc-ripple-is-unbounded="true">
                                            <i class="material-icons mdc-icon-button__icon">visibility</i>
                                        </button>
                                    @endif
                                    @if($category->category_status !== 'base')@if($category->category_status === 'live')
                                        <button id="toggle_show"
                                                onclick="location.href='{{ url('categories/'.$category->id.'/delete') }}'"
                                                class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                                                title="Delete" data-mdc-ripple-is-unbounded="true">
                                            <i class="material-icons mdc-icon-button__icon">delete</i>
                                        </button>
                                    @else
                                        <a href="{{ url('categories/'.$category->id.'/restore') }}">Restore</a></td>
                                @endif
                                @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Required MDC Web JavaScript library -->
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <script>
        const dataTable = [].map.call(document.querySelectorAll('.mdc-data-table'), function (el) {
            return new mdc.dataTable.MDCDataTable(el);
        });
    </script>

@endsection
