@extends('layouts.app') {{-- Or a dedicated admin layout --}}

@section('content')
<div class="container">
    <h3>Pending Projects for Approval</h3>
    @if(session('success'))
        <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
    @endif

    <table class="striped">
        <thead>
            <tr>
                <th>Project Title</th>
                <th>University</th>
                <th>Goal Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingProjects as $project)
                <tr>
                    <td>{{ $project->title }}</td>
                    <td>{{ $project->university->name }}</td>
                    <td>₦{{ number_format($project->goal_amount) }}</td>
                    <td class="center">
                        <form action="{{ route('superadmin.projects.approve', $project) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-small waves-effect waves-light green">Approve</button>
                        </form>
                        <form action="{{ route('superadmin.projects.reject', $project) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-small waves-effect waves-light red">Reject</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No pending projects found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection