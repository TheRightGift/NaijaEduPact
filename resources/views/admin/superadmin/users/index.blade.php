@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Manage Users</h3>
    <div class="card-panel">
        <form method="GET" action="{{ route('superadmin.users.index') }}">
            <div class="input-field">
                <input id="search" type="text" name="search" value="{{ request('search') }}">
                <label for="search">Search by Name or Email</label>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
    @endif

    <table class="striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>University</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->university->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('superadmin.users.edit', $user) }}" class="btn-small waves-effect">Edit Role</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $users->appends(request()->except('page'))->links('pagination::materialize-css') }}
</div>
@endsection