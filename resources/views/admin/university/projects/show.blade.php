@extends('layouts.landing')

@section('content')
<div class="container section">
    <div class="row">
        <div class="col s12 m8">
            <div class="card-panel">
                <h3>{{ $project->title }}</h3>
                <p class="grey-text">from {{ $project->university->name }}</p>
                <div class="progress">
                    @php
                        $progress = $project->goal_amount > 0 ? min(($project->current_amount / $project->goal_amount) * 100, 100) : 0;
                    @endphp
                    <div class="determinate" style="width: {{ $progress }}%"></div>
                </div>
                <p>
                    <strong>₦{{ number_format($project->current_amount) }} raised</strong>
                    of ₦{{ number_format($project->goal_amount) }} goal
                </p>
                
                <div class="divider" style="margin: 20px 0;"></div>

                <div class="project-content">
                    {!! $project->description !!}
                </div>
            </div>

            <div class="card-panel">
                <h4>Project Timeline</h4>
                <ul class="collection">
                    @forelse($project->timelineUpdates as $update)
                        <li class="collection-item avatar">
                            <i class="material-icons circle indigo">update</i>
                            <span class="title"><strong>{{ $update->title }}</strong></span>
                            <p class="grey-text">{{ $update->created_at->format('M d, Y') }}</p>
                            <p>{{ $update->content }}</p>

                            @if($update->media_type == 'image')
                                <img src="{{ Storage::url($update->media_url) }}" style="width: 100%; margin-top: 15px;">
                            @elseif($update->media_type == 'video_embed')
                                <div class="video-container" style="margin-top: 15px;">
                                    <iframe width="853" height="480" src="{{ $update->media_url }}" frameborder="0" allowfullscreen></iframe>
                                </div>
                            @endif
                        </li>
                    @empty
                        <li class="collection-item">
                            <p>No updates have been posted for this project yet. Check back soon!</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col s12 m4">
            <div class="card-panel" style="position: sticky; top: 20px;">
                <h5>Support this Project</h5>
                @auth
                    <form action="{{ route('donate.start') }}" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <div class="input-field">
                            <input type="number" name="amount" id="amount" min="{{ config('services.rates.usd_to_ngn', 1450) }}" required>
                            <label for="amount">Amount (NGN)</label>
                            <span class="helper-text">Min: ₦{{ config('services.rates.usd_to_ngn', 1450) }}</span>
                        </div>
                        <button type="submit" class="btn-large waves-effect waves-light indigo" style="width: 100%;">
                            Donate Now
                        </button>
                    </form>
                @else
                    <p>You must be logged in to make a donation.</p>
                    <a href="{{ route('login') }}" class="btn-large waves-effect waves-light indigo" style="width: 100%;">
                        Login to Donate
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection