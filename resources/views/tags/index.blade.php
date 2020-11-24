@extends('layouts.mdc')

@section('content')

    <main class="mdl-layout__content">

        <md-content>
            <div class="mdc-data-table">
                <div class="mdc-data-table__table-container">
                    <table class="mdc-data-table__table" aria-label="Dessert calories">
                        <thead>
                        <tr class="mdc-data-table__header-row">
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">Tag Name</th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">Created</th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">Last Updated</th>
                            <th class="mdc-data-table__header-cell" role="columnheader" scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="mdc-data-table__content">
                        @foreach($models as $tag)
                            <tr class="mdc-data-table__row">
                                <td class="mdc-data-table__cell">{{ $tag->title }}</td>
                                <td class="mdc-data-table__cell">{{$tag->created_at->format('d M Y H:i:s')}}</td>
                                <td class="mdc-data-table__cell">{{$tag->updated_at->format('d M Y H:i:s')}}</td>
                                <td class="mdc-data-table__cell">
                                    <button id="toggle_show"
                                            onclick="location.href='{{ url('tags/'.$tag->id.'/edit') }}'"
                                            class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                                            title="Edit" data-mdc-ripple-is-unbounded="true">
                                        <i class="material-icons mdc-icon-button__icon">create</i>
                                    </button>
                                    @if($tag->tag_status === 'live')
                                        <button id="toggle_show"
                                                onclick="location.href='{{ url('tags/'.$tag->id.'/show') }}'"
                                                class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                                                title="Show" data-mdc-ripple-is-unbounded="true">
                                            <i class="material-icons mdc-icon-button__icon">visibility</i>
                                        </button>
                                        <button id="toggle_show"
                                                onclick="location.href='{{ url('tags/'.$tag->id.'/delete') }}'"
                                                class="mdc-icon-button material-icons mdc-card__action mdc-card__action--icon--unbounded"
                                                title="Delete" data-mdc-ripple-is-unbounded="true">
                                            <i class="material-icons mdc-icon-button__icon">delete</i>
                                        </button>
                                    @else
                                        <a href="{{ url('tags/'.$tag->id.'/restore') }}">Restore</a></td>
                                @endif</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </md-content>

    </main>

    <!-- Required MDC Web JavaScript library -->
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <script>
        const dataTable = [].map.call(document.querySelectorAll('.mdc-data-table'), function (el) {
            return new mdc.dataTable.MDCDataTable(el);
        });
    </script>

@endsection
