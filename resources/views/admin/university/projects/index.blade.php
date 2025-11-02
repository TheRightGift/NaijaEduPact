@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ route('uadmin.projects.create') }}" class="btn indigo right"><i class="material-icons left">add</i>New Project</a>
    <h3>My Projects</h3>
    <p>Manage all your projects, from pending to completed.</p>

    @if(session('success'))
        <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
    @endif

    <div class="card-panel">
        <table class="striped">
            <thead>
                <tr>
                    <th>Project Title</th>
                    <th>Status</th>
                    <th>Goal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td>
                            @php
                                $color = 'grey';
                                if ($project->status == 'active') $color = 'green';
                                if ($project->status == 'pending') $color = 'orange';
                                if ($project->status == 'rejected') $color = 'red';
                            @endphp
                            <span class="new badge {{ $color }}" data-badge-caption="">
                                {{ ucfirst($project->status) }}
                            </span>
                        </td>
                        <td>₦{{ number_format($project->goal_amount) }}</td>
                        <td>
                            <a href="{{ route('uadmin.projects.edit', $project) }}" class="btn-small waves-effect waves-light orange">
                                Edit
                            </a>
                            <form action="{{ route('uadmin.projects.destroy', $project) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small waves-effect waves-light red">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="center-align">You have not created any projects yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $projects->links('pagination::materialize-css') }}
</div>
@endsection