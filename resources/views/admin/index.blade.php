@extends('layouts.mdc')

@section('content')

    <?php if(count($users)) { ?>
    <h3>All Users</h3>
    <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
        <thead>
        <tr>
            <th class="mdl-data-table__cell--non-numeric">ID</th>
            <th class="mdl-data-table__cell--non-numeric">Username</th>
            <th class="mdl-data-table__cell--non-numeric">First Name</th>
            <th class="mdl-data-table__cell--non-numeric">Surname</th>
            <th class="mdl-data-table__cell--non-numeric">Email</th>
            <th class="mdl-data-table__cell--non-numeric">Registered</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td class="mdl-data-table__cell--non-numeric">{{ $user->id }}</td>
                <td class="mdl-data-table__cell--non-numeric">{{ substr($user->username, 0, 20) }}</td>
                <td class="mdl-data-table__cell--non-numeric">{{ substr($user->firstname, 0, 20) }}</td>
                <td class="mdl-data-table__cell--non-numeric">{{ substr($user->surname, 0, 20) }}</td>
                <td class="mdl-data-table__cell--non-numeric">{{ substr($user->email, 0, 40) }}</td>
                <td class="mdl-data-table__cell--non-numeric">{{ \Carbon\Carbon::parse($user->created_at)->format('d F Y H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <?php } ?>

@endsection
