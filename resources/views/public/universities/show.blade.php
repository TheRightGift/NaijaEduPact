@extends('layouts.landing')

@section('content')
<div class="container section">
    <div class="row">
        <div class="col s12 m7 l8">
            <div class="card-panel">
                <span class="new badge indigo" data-badge-caption="">University</span>
                <h3>{{ $university->name }}</h3>
                <p>{{ $university->description }}</p>
            </div>
            
            @if($university->general_fund_story)
            <div class="card-panel">
                <h2>General Fund Story</h2>
                <div class="project-content">
                    {!! $university->general_fund_story !!}
                </div>
            </div>
            @endif

            <div class="card-panel">
                <h2>General Fund Timeline</h2>
                <ul class="collection">
                    @forelse($university->timelineUpdates as $update)
                        <li class="collection-item avatar">
                            <i class="material-icons circle indigo">update</i>
                            <span class="title"><strong>{{ $update->title }}</strong></span>
                            <p class="grey-text">{{ $update->created_at->format('M d, Y') }}</p>
                            <p>{!! nl2br(e($update->content)) !!}</p>

                            @if($update->media_type == 'image')
                                <img src="{{ Storage::url($update->media_url) }}" class="responsive-img" style="width: 100%; margin-top: 15px;">
                            @elseif($update->media_type == 'video_embed')
                                <div class="video-container" style="margin-top: 15px;">
                                    <iframe width="853" height="480" src="{{ $update->media_url }}" frameborder="0" allowfullscreen></iframe>
                                </div>
                            @endif
                        </li>
                    @empty
                        <li class="collection-item">
                            <p>No updates have been posted for the general fund yet.</p>
                        </li>
                    @endforelse
                </ul>
            </div>
            
            <div class="card-panel">
                <h4>Active Projects</h4>
                <div class="row">
                    @forelse($projects as $project)
                        <div class="col s12 m6">
                            <div class="card">
                                <div class="card-image">
                                    <img src="{{ getImageUrl($project->cover_image_path) }}" onerror="this.src='https://placehold.co/600x400/black/white?text=No Cover Image'">
                                </div>
                                <div class="card-content">
                                    <span class="card-title">{{ $project->title }}</span>
                                </div>
                                <div class="card-action">
                                    <a href="{{ route('projects.show', $project) }}" class="indigo-text">View Project</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>This university has no active projects at this time.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col s12 m5 l4">
            <div class="card-panel" style="position: sticky; top: 20px;">
                <h5 class="center-align">Support {{ $university->name }}</h5>
                <h6 class="center-align grey-text">General Fund</h6>
                
                <h3 class="indigo-text center-align">₦{{ number_format($university->general_fund_balance) }}</h3>
                <p class="grey-text center-align" style="margin-top: -10px;">Raised for general needs</p>

                @auth
                    @if(auth()->user()->role == 'donor')
                        <form action="{{ route('donate.start') }}" method="POST">
                            @csrf
                            <input type="hidden" name="university_id" value="{{ $university->id }}">
                            <div class="input-field">
                                <input type="number" name="amount" id="amount" min="{{ config('services.rates.usd_to_ngn', 1450) }}" required>
                                <label for="amount">Amount (NGN)</label>
                                <span class="helper-text">Min: ₦{{ config('services.rates.usd_to_ngn', 1450) }}</span>
                            </div>
                            <button type="submit" class="btn-large waves-effect waves-light indigo" style="width: 100%;">
                                Donate to General Fund
                            </button>
                        </form>
                    @else
                        <p class="center-align">Donations can only be made from an Alumnus/Donor account.</p>
                    @endif
                @else
                    <p class="center-align">You must be logged in to make a donation.</p>
                    <a href="{{ route('login') }}?redirect_to={{ url()->current() }}" class="btn-large waves-effect waves-light indigo" style="width: 100%;">
                        Login to Donate
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Helper function for images --}}
@php
    function getImageUrl($path) {
        if (!$path) { return 'https://placehold.co/600x400/black/white?text=No Cover Image'; }
        if (Str::startsWith($path, 'storage/')) { return asset($path); }
        return Storage::url($path);
    }
@endphp

{{-- 
  These styles are needed for the rich-text content
  (like embedded videos) to look correct.
--}}
@push('styles')
<style>
    .project-content h2,
    .project-content h3,
    .project-content h4 {
        font-size: 2.0rem;
        font-weight: 300;
        line-height: 1.3;
    }
    .project-content p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #424242;
    }
    .project-content ul {
        list-style-type: disc;
        padding-left: 30px;
    }
    .project-content ul li {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #424242;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var content = document.querySelector('.project-content');
        if (content) {
            var iframes = content.querySelectorAll('iframe');
            iframes.forEach(function(iframe) {
                var wrapper = document.createElement('div');
                wrapper.className = 'video-container';
                iframe.parentNode.insertBefore(wrapper, iframe);
                wrapper.appendChild(iframe);
            });
        }
    });
</script>
@endpush