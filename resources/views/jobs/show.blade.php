@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s12">
            <a href="{{ url()->previous() }}" class="btn-flat waves-effect"><i class="material-icons left">arrow_back</i>Back</a>
            <div class="card-panel">
                <span class="new badge blue" data-badge-caption="">{{ $job->job_type }}</span>
                <span class="new badge" data-badge-caption="">{{ $job->location }}</span>
                <h3>{{ $job->title }}</h3>
                <p class="grey-text">Posted by: {{ $job->user->name }} on {{ $job->created_at->format('M d, Y') }}</p>
                
                <div class="divider" style="margin: 20px 0;"></div>
                
                <h5>Job Description</h5>
                {{-- Use nl2br to respect line breaks in the description --}}
                <p>{!! nl2br(e($job->description)) !!}</p>
            </div>
        </div>
    </div>
</div>
@endsection