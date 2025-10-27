@extends('layouts.app')

@section('content')
<div class="container">
    <h3>My Mentorship Dashboard</h3>

    @if(session('success'))
        <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="card-panel red lighten-4 red-text text-darken-4">{{ session('error') }}</div>
    @endif

    {{-- This section is only for Alumni (Mentors) --}}
    @if($user->role == 'donor')
        <div class="card-panel">
            <h4>My Mentor Profile</h4>
            <p>Make yourself available to mentor students from your university.</p>
            <form action="{{ route('mentorship.profile.update') }}" method="POST">
                @csrf
                <div class="switch">
                    <label>
                        Not Available
                        <input type="checkbox" name="is_available_for_mentoring" {{ $user->is_available_for_mentoring ? 'checked' : '' }}>
                        <span class="lever"></span>
                        Available for Mentoring
                    </label>
                </div>
                
                <br>

                <div class="input-field">
                    <textarea id="mentoring_bio" name="mentoring_bio" class="materialize-textarea">{{ old('mentoring_bio', $user->mentoring_bio) }}</textarea>
                    <label for="mentoring_bio">Your Mentoring Bio</label>
                    <span class="helper-text">Share your industry experience, what you can help with, etc.</span>
                </div>

                <button type="submit" class="btn indigo waves-effect waves-light">Save Profile</button>
            </form>
        </div>

        <div class="section">
            <h4>Incoming Mentorship Requests</h4>
            <ul class="collection">
                @forelse($incomingRequests as $request)
                    <li class="collection-item avatar">
                        <i class="material-icons circle green">person</i>
                        <span class="title"><strong>{{ $request->mentee->name }}</strong></span>
                        <p>Wants to connect with you. <br>
                           Status: <span class="new badge" data-badge-caption="">{{ $request->status }}</span>
                        </p>
                        <div class="secondary-content">
                            <form action="{{ route('mentorship.request.respond', $request) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="action" value="accept">
                                <button type="submit" class="btn-small green waves-effect waves-light">Accept</button>
                            </form>
                            <form action="{{ route('mentorship.request.respond', $request) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="hidden" name="action" value="decline">
                                <button type="submit" class="btn-small red waves-effect waves-light">Decline</button>
                            </form>
                        </div>
                    </li>
                @empty
                    <li class="collection-item">
                        <p>You have no pending mentorship requests.</p>
                    </li>
                @endforelse
            </ul>
        </div>
    @endif

    {{-- This section is only for Students (Mentees) --}}
    @if($user->role == 'student')
        <div class="section">
            <h4>My Sent Requests</h4>
            <ul class="collection">
                @forelse($sentRequests as $request)
                    <li class="collection-item avatar">
                        <i class="material-icons circle indigo">school</i>
                        <span class="title"><strong>{{ $request->mentor->name }}</strong></span>
                        <p>
                           Status: <span class="new badge" data-badge-caption="">{{ $request->status }}</span>
                        </p>
                    </li>
                @empty
                    <li class="collection-item">
                        <p>You have not sent any mentorship requests. <a href="#">Find a Mentor</a></p>
                    </li>
                @endforelse
            </ul>
        </div>
    @endif

</div>
@endsection