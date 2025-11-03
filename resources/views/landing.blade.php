@extends('layouts.landing')

@section('content')
    <div class="section no-pad-bot" id="index-banner">
        <div class="container">
            <br><br>
            <h1 class="header center indigo-text">NaijaEdu-Pact</h1>
            <div class="row center">
                <h5 class="header col s12 light">Empowering Futures, One Project at a Time</h5>
            </div>
            <div class="row center">
                <a href="{{ route('projects.index') }}" id="download-button" class="btn-large waves-effect waves-light indigo lighten-1">Explore Projects</a>
            </div>
            <br><br>
        </div>
    </div>

    <div class="container section">
        <div class="row">
            <div class="col s12 m4">
                <div class="icon-block center">
                    <h2 class="center indigo-text"><i class="material-icons">search</i></h2>
                    <h5 class="center">Discover Projects</h5>
                    <p class="light">Browse through a list of vetted projects from your alma mater. Find a cause that resonates with you, from infrastructure to student welfare.</p>
                </div>
            </div>

            <div class="col s12 m4">
                <div class="icon-block center">
                    <h2 class="center indigo-text"><i class="material-icons">payment</i></h2>
                    <h5 class="center">Donate Securely</h5>
                    <p class="light">Make a contribution with confidence using our secure payment system. Support a project with a one-time or recurring donation.</p>
                </div>
            </div>

            <div class="col s12 m4">
                <div class="icon-block center">
                    <h2 class="center indigo-text"><i class="material-icons">track_changes</i></h2>
                    <h5 class="center">Track Impact</h5>
                    <p class="light">Follow the progress of projects you support. Receive transparent updates and see the tangible impact of your generosity.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="section">
            <h3 class="center featured-projects-header">Featured Projects</h3>
            <div class="row">
                @forelse ($featuredProjects as $project)
                    <div class="col s12 m4">
                        <div class="card featured-card"> {{-- Added custom class for consistent height --}}
                            <div class="card-image featured-image-container"> {{-- Added container for image height --}}
                                <img src="{{ getImageUrl($project->cover_image_path) }}" alt="{{ $project->title }}" class="responsive-img featured-image">
                                <span class="card-title project-card-title">{{ Str::limit($project->title, 40) }}</span>
                            </div>
                            <div class="card-content">
                                <p>
                                    {{-- Remove HTML tags and limit description --}}
                                    {{ Str::limit(strip_tags($project->description), 80) }}
                                </p>
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
                                {{-- Corrected the route to project.show --}}
                                <a href="{{ route('projects.show', $project->slug) }}" class="indigo-text">VIEW PROJECT DETAILS</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col s12 center-align">
                        <p>No featured projects available at the moment. Check back soon!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@php
    function getImageUrl($path) {
        if (!$path) {
            return 'https://via.placeholder.com/400x300.png?text=Project+Image';
        }
        
        // 1. Check if the path incorrectly contains "storage/"
        if (Str::startsWith($path, '/storage/')) {
            // 2. If it does, just return the path using the asset() helper
            // This will resolve to http://localhost:8000/storage/project-covers/...
            return asset($path);
        }
        
        // 3. Otherwise (if path is clean, e.g., "project-covers/..."), 
        //    use Storage::url() to build the correct path
        return Storage::url($path);
    }
@endphp

@push('styles')
<style>
    .featured-card {
        height: 100%; /* Ensures all cards try to be the same height */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .featured-image-container {
        height: 200px; /* Fixed height for image container */
        overflow: hidden; /* Hide overflow if image is larger */
        display: flex;
        align-items: center; /* Center image vertically */
        justify-content: center; /* Center image horizontally */
    }
    .featured-image {
        width: 100%;
        height: auto; /* Allow image to scale within container */
        object-fit: cover; /* Covers the area, cropping if necessary */
    }
    .project-card-title {
        font-size: 1.2rem; /* Adjust title font size if needed */
        line-height: 1.3;
    }
    .card-content {
        flex-grow: 1; /* Allows content to expand and push action to bottom */
    }
    .featured-projects-header {
        margin-bottom: 40px;
        font-weight: 300;
        letter-spacing: 1px;
    }
</style>
@endpush