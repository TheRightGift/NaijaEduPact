@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col s12 l8">

            @if(session('success'))
                <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="card-panel red lighten-4 red-text text-darken-4">{{ session('error') }}</div>
            @endif

            <h4>Projects from {{ Auth::user()->university->name ?? 'your Alma Mater' }}</h4>
            
            <div class="row">
                @forelse($universityProjects as $project)
                    <div class="col s12 m6 l4">
                        <div class="card sticky-action">
                            <div class="card-image">
                                @if(in_array($project->id, $donatedProjectIds))
                                    <span class="donated-badge">Donated</span>
                                @endif
                                <img src="{{ getImageUrl($project->cover_image_path) }}" onerror="this.src='https://via.placeholder.com/400x300.png?text=Project+Image'">
                            </div>
                            <div class="card-content">
                                <span class="card-title activator grey-text text-darken-4" style="font-size: 1.2rem; line-height: 1.4;">
                                    {{ $project->title }}
                                </span>
                                <div class="progress" style="margin-top: 15px;">
                                    @php
                                        $progress = $project->goal_amount > 0 ? min(($project->current_amount / $project->goal_amount) * 100, 100) : 0;
                                    @endphp
                                    <div class="determinate" style="width: {{ $progress }}%"></div>
                                </div>
                                <p class="grey-text">
                                    ₦{{ number_format($project->current_amount) }} raised of ₦{{ number_format($project->goal_amount) }}
                                </p>
                            </div>
                            <div class="card-action">
                                <a href="{{ route('projects.show', $project) }}" class="indigo-text">View Project</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col s12">
                        <div class="card-panel">
                            <p class="center-align">Your university has no active projects right now. Check back soon!</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>

        <div class="col s12 l4">
            
            <div class="card-panel center-align">
                <h5 class="grey-text text-darken-2">My Impact</h5>
                
                <h3 class="indigo-text" style="margin: 10px 0;">₦{{ number_format($totalDonations, 0) }}</h3>
                <p class="grey-text" style="margin-top: -10px;">Total Donated</p>
                
                <h3 class="indigo-text" style="margin: 10px 0;">{{ $projectsSupported }}</h3>
                <p class="grey-text" style="margin-top: -10px;">Projects Supported</p>
            </div>

            <div class="card-panel">
                <h5 class="grey-text text-darken-2">Quick Actions</h5>
                <ul class="collection">
                    <a href="{{ route('donations.history') }}" class="collection-item waves-effect indigo-text">
                        <i class="material-icons left indigo-text">history</i>My Donation History
                    </a>
                    <a href="{{ route('jobs.index') }}" class="collection-item waves-effect indigo-text">
                        <i class="material-icons left indigo-text">work</i>Manage My Job Postings
                    </a>
                    <a href="{{ route('mentorship.index') }}" class="collection-item waves-effect indigo-text">
                        <i class="material-icons left indigo-text">school</i>Mentorship Dashboard
                    </a>
                </ul>
            </div>
            
        </div>
    </div>
</div>
@endsection

@php
    function getImageUrl($path) {
        if (!$path) {
            return 'https://via.placeholder.com/400x300.png?text=Project+Image';
        }
        if (Str::startsWith($path, '/storage/')) {
            return asset($path);
        }
        return Storage::url($path);
    }
@endphp

@push('styles')
<style>
    .card-image {
        position: relative; /* Required for the badge to position correctly */
    }
    .donated-badge {
        position: absolute;
        top: 15px;
        left: -8px;
        background-color: #4CAF50; /* Material Green */
        color: white;
        padding: 6px 12px;
        border-radius: 0 3px 3px 0;
        font-weight: bold;
        z-index: 10;
        box-shadow: 0 2px 5px 0 rgba(0,0,0,0.16), 0 2px 10px 0 rgba(0,0,0,0.12);
    }
    .collection-item {
        color: #3f51b5; /* Indigo */
    }
</style>
@endpush