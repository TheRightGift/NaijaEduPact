@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col s12 l9">

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
                                <img src="{{ getImageUrl($project->cover_image_path) }}" onerror="this.src='https://placehold.co/600x400/black/white?text=No Cover Image'">
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
                                
                                @if (isset($userDonationsPerProject[$project->id]))
                                    <a href="#donation-details-{{ $project->id }}" class="modal-trigger indigo-text right">
                                        My Donation
                                    </a>
                                @endif
                            </div>
                        </div>
                        
                        @if (isset($userDonationsPerProject[$project->id]))
                            <div id="donation-details-{{ $project->id }}" class="modal" style="max-width: 400px; border-radius: 8px;">
                                <div class="modal-content center-align">
                                    <h5 class="grey-text">Your Impact on</h5>
                                    <h6>{{ $project->title }}</h6>
                                    <h3 class="indigo-text" style="margin: 10px 0;">₦{{ number_format($userDonationsPerProject[$project->id]) }}</h3>
                                    <p class="grey-text" style="margin-top: -10px;">Total Donated</p>
                                </div>
                                <div class="modal-footer">
                                    <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
                                </div>
                            </div>
                        @endif

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

        <div class="col s12 l3">
            
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
            return 'https://placehold.co/600x400/black/white?text=No Cover Image';
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

    .card .card-action a:not(.btn):not(.btn-large):not(.btn-small):not(.btn-large):not(.btn-floating) {
        font-size: 0.9rem;  /* Make the base font size slightly smaller */
        font-weight: 500; /* Make it a bit bolder */
        margin-right: 10px !important;
    }

    /* On extra-small screens (phones), make the text even smaller */
    @media (max-width: 600px) {
        .card .card-action a {
            font-size: 0.8rem;
        }
    }
</style>
@endpush