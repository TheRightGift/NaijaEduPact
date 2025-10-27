@extends('layouts.app')

@section('content')
<div class="container">
    <div class="section">
        <h3>Student Dashboard</h3>
        <p class="flow-text">Welcome, {{ Auth::user()->name }}. Here are career opportunities from your university's alumni network.</p>
    </div>

    <div class="divider"></div>

    <div class="section">
        <h4>Job Board</h4>
        <div class="row">
            {{-- Use @forelse to loop and handle the empty case --}}
            @forelse($jobs as $job)
                <div class="col s12 m6">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4">
                                <strong>{{ $job->title }}</strong>
                            </span>
                            <p class="truncate">Posted by: {{ $job->user->name }}</p>
                            <br>
                            <p>
                                <span class="new badge blue" data-badge-caption="">{{ $job->job_type }}</span>
                                <span class="new badge" data-badge-caption="">{{ $job->location }}</span>
                            </p>
                            <br>
                            <p>{{ Str::limit($job->description, 150) }}</p>
                        </div>
                        <div class="card-action">
                            <a href="#" class="indigo-text">View Details & Apply</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col s12">
                    <div class="card-panel">
                        <p class="center-align">No job postings from your university at this time. Check back soon!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <div class="divider"></div>

    <div class="section">
        <h4>Find a Mentor</h4>
        <p>Connect with alumni from your university for career guidance.</p>
        <div class="row">
            @forelse($mentors as $mentor)
                <div class="col s12 m6 l4">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title activator grey-text text-darken-4">
                                <strong>{{ $mentor->name }}</strong>
                                <i class="material-icons right">more_vert</i>
                            </span>
                            <p>Available to Mentor</p>
                        </div>
                        <div class="card-action">
                            <form action="{{ route('mentorship.request.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">
                                <button type="submit" class="btn-flat indigo-text waves-effect">
                                    Request Mentorship
                                </button>
                            </form>
                        </div>
                        <div class="card-reveal">
                            <span class="card-title grey-text text-darken-4">
                                {{ $mentor->name }}<i class="material-icons right">close</i>
                            </span>
                            <h5>About {{ $mentor->name }}</h5>
                            <p>{{ $mentor->mentoring_bio ?? 'No bio provided.' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col s12">
                    <div class="card-panel">
                        <p class="center-align">No mentors are available from your university at this time. Check back soon!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection