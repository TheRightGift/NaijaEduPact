@extends('layouts.landing')

@section('content')
    <div class="section no-pad-bot hero">
        <div class="container center">
            <h1 class="header white-text">Reconnect. Rebuild. Reinforce.</h1>
            <div class="row">
                <h5 class="header col s12 light">Empowering Nigerian universities through the collective effort of their global alumni.</h5>
            </div>
            <div class="row">
                <a href="#projects" class="btn-large waves-effect waves-light indigo lighten-1">Explore Projects</a>
            </div>
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

    <div id="projects" class="section grey lighten-4">
        <div class="container">
            <h3 class="center">Featured Projects</h3>
            <div class="row">
                @if($featuredProjects->isEmpty())
                    <p class="center light">There are no featured projects at the moment. Please check back later!</p>
                @else
                    @foreach($featuredProjects as $project)
                        <div class="col s12 m4">
                            <div class="card">
                                <div class="card-image">
                                    <img src="{{ $project->cover_image_path ?? 'https://via.placeholder.com/400x300.png?text=Project+Image' }}">
                                    <span class="card-title">{{ $project->title }}</span>
                                </div>
                                <div class="card-content">
                                    <p><strong>{{ $project->university->name }}</strong></p>
                                    <p>{{ Str::limit($project->description, 100) }}</p>
                                    <br>
                                    <div class="progress">
                                        @php
                                            $progress = ($project->current_amount / $project->goal_amount) * 100;
                                        @endphp
                                        <div class="determinate" style=`"width: {{ $progress }}%"`></div>
                                    </div>
                                    <p>₦{{ number_format($project->current_amount) }} raised of ₦{{ number_format($project->goal_amount) }}</p>
                                </div>
                                <div class="card-action">
                                    <a href="#" class="indigo-text">View Project Details</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection