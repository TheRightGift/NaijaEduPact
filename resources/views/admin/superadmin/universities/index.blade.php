@extends('layouts.app')
@section('content')
<div class="container">
    <a href="{{ route('superadmin.universities.create') }}" class="btn indigo right"><i class="material-icons left">add</i>Add University</a>
    <h3>Manage Universities</h3>
    
    @if(session('success'))
        <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
    @endif

    <table class="striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($universities as $uni)
                <tr>
                    <td>{{ $uni->name }}</td>
                    <td>
                        <span class="new badge {{ $uni->status == 'approved' ? 'green' : 'grey' }}" data-badge-caption="">
                            {{ $uni->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('superadmin.universities.edit', $uni) }}" class="btn-small waves-effect">Edit</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $universities->links('pagination::materialize-css') }}
</div>
@endsection