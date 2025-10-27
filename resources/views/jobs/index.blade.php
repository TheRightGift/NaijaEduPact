@extends('layouts.app')

@section('content')
<div class="container">
    <h3>My Job Postings</h3>
    <a href="{{ route('jobs.create') }}" class="btn indigo waves-effect waves-light">
        <i class="material-icons left">add</i>Post a New Job
    </a>

    @if(session('success'))
        <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
    @endif

    <div class="section">
        <ul class="collection">
            @forelse($jobs as $job)
                <li class="collection-item avatar">
                    <i class="material-icons circle indigo">work</i>
                    <span class="title"><strong>{{ $job->title }}</strong> ({{ $job->job_type }})</span>
                    <p>{{ $job->location }} <br>
                       Status: <span class="new badge {{ $job->status == 'active' ? 'green' : 'grey' }}" data-badge-caption="">{{ $job->status }}</span>
                    </p>
                    <div class="secondary-content">
                        <a href="{{ route('jobs.edit', $job) }}" class="waves-effect waves-light btn-small">Edit</a>
                        <form action="{{ route('jobs.destroy', $job) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-small red waves-effect waves-light" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </li>
            @empty
                <li class="collection-item">
                    <p>You have not posted any jobs yet.</p>
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection