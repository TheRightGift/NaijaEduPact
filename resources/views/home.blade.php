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

            <div class="card-panel">
                <h5>Alumni Dashboard</h5>
                <p>Welcome, {{ Auth::user()->name }}.</p>
                <a href="{{ route('jobs.index') }}" class="btn-large indigo waves-effect waves-light" style="margin-right: 10px;">
                    Manage My Job Postings
                </a>
                <a href="{{ route('mentorship.index') }}" class="btn-large indigo waves-effect waves-light">
                    Mentorship Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endSection