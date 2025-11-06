@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        <div class="col s12 l8">

            @if(session('success'))
                @endif

            <h4>Projects from {{ Auth::user()->university->name ?? 'your Alma Mater' }}</h4>
            
            <div class="row">
                @forelse($universityProjects as $project)
                    <div class="col s12 m6">
                        <div class="card sticky-action">
                            <div class="card-image">
                                <img src="{{ getImageUrl($project->cover_image_path) }}" onerror="this.src='https://via.placeholder.com/400x300.png?text=Project+Image'">
                            </div>
                            <div class="card-content">
                                </div>
                            <div class="card-action">
                                <a href="{{ route('projects.show', $project) }}" class="indigo-text">View Project</a>
                                
                                @if (isset($userDonationsPerProject[$project->id]))
                                    <a href="#donation-details-{{ $project->id }}" class="modal-trigger indigo-text" style="float: right;">
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
                    @endforelse
            </div>

        </div>

        <div class="col s12 l4">
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