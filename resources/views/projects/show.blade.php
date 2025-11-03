@extends('layouts.landing')

@section('content')
<div class="container section">
    <div class="row">
        <div class="col s12 m7 l8">
            
            <div class="card-panel" style="margin-top: 0;">
                <span class="new badge indigo" data-badge-caption="">
                    {{ $project->university->name }}
                </span>

                <h1>{{ $project->title }}</h1>

                <div class="progress">
                    @php
                        $progress = $project->goal_amount > 0 ? min(($project->current_amount / $project->goal_amount) * 100, 100) : 0;
                    @endphp
                    <div class="determinate" style="width: {{ $progress }}%"></div>
                </div>
                <p>
                    <strong style="font-size: 1.2rem;">₦{{ number_format($project->current_amount) }}</strong>
                    <span class="grey-text"> raised of ₦{{ number_format($project->goal_amount) }} goal</span>
                </p>
            </div>

            <div class="card-panel">
                <h2>Project Story</h2>
                
                <div class="project-content">
                    {!! $project->description !!}
                </div>
            </div>

            <div class="card-panel">
                <h2>Project Timeline</h2>
                <ul class="collection">
                    @forelse($project->timelineUpdates as $update)
                        <li class="collection-item avatar">
                            <i class="material-icons circle indigo">update</i>
                            <span class="title"><strong>{{ $update->title }}</strong></span>
                            <p class="grey-text">{{ $update->created_at->format('M d, Y') }}</p>
                            <p>{!! nl2br(e($update->content)) !!}</p>

                            @if($update->media_type == 'image')
                                <img src="{{ Storage::url($update->media_url) }}" class="responsive-img" style="width: 100%; margin-top: 15px;">
                            @elseif($update->media_type == 'video_embed')
                                <div class="video-container" style="margin-top: 15px;">
                                    <iframe src="{{ $update->media_url }}" frameborder="0" allowfullscreen></iframe>
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

        <div class="col s12 m5 l4">
            <div class="card-panel" style="position: sticky; top: 20px;">
                <h5 class="center-align">Support this Project</h5>
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
                    <a href="{{ route('login') }}?redirect_to={{ url()->current() }}" class="btn-large waves-effect waves-light indigo" style="width: 100%;">
                        Login to Donate
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-panel h1 {
        font-size: 2.92rem; /* Materialize h3 size */
        font-weight: 500;
        line-height: 1.2;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    /* This is the main H2 section title (e.g., "Project Story").
      It's clean, with a bottom border to separate sections.
    */
    .card-panel h2 {
        font-size: 2.28rem; /* Materialize h4 size */
        font-weight: 400;
        line-height: 1.3;
        margin-top: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 10px;
        border-bottom: 1px solid #e0e0e0; /* Grey lighten-4 */
    }

    /* This is the H3 *from the rich text editor*.
      (e.g., "Support the Next Generation...")
      Large, but lighter weight.
    */
    .project-content h3 {
        font-size: 2.0rem;
        font-weight: 300;
        line-height: 1.3;
    }

    /* This is the H4 *from the rich text editor*.
      (e.g., "Our Goals")
      Smaller, but bolder to stand out.
    */
    .project-content h4 {
        font-size: 1.64rem; /* Materialize h5 size */
        font-weight: 500;
        line-height: 1.3;
    }

    /* Standard text from the editor.
    */
    .project-content p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #424242; /* Grey darken-3 */
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
    .project-content p {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #424242; /* Grey darken-3 */
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
    .project-content .video-container iframe {
        width: 100% !important;
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