@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            @if (session('status'))
                <div class="card-panel green lighten-4 green-text text-darken-4">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('success'))
                <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="card-panel red lighten-4 red-text text-darken-4">{{ session('error') }}</div>
            @endif
            
            @if(Auth::user()->role == 'donor')
                <div class="card-panel">
                    <h5>Alumni Dashboard</h5>
                    <p>Welcome, {{ Auth::user()->name }}.</p>
                    
                    <a href="{{ route('jobs.index') }}" class="btn-large indigo waves-effect waves-light" style="margin: 5px;">
                        Manage My Job Postings
                    </a>
                    <a href="{{ route('mentorship.index') }}" class="btn-large indigo waves-effect waves-light" style="margin: 5px;">
                        Mentorship Dashboard
                    </a>
                    <a href="{{ route('donations.history') }}" class="btn-large indigo waves-effect waves-light" style="margin: 5px;">
                        My Donation History
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection