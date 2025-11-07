@extends('layouts.landing')

@section('content')

<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container hero-content">
        <h1 class="header center">Give Back to the Halls That Built You.</h1>
        <p class="flow-text center">Join thousands of Nigerian alumni in funding vetted, high-impact projects at your alma mater. 100% transparent. 100% for your university.</p>
        <div class="row center">
            <a href="{{ route('projects.index') }}" class="btn-large waves-effect waves-light indigo darken-1">Explore Projects</a>
        </div>
        <div class="row center">
            <div class="col s12 m8 offset-m2">
                <div class="input-field hero-search">
                    <i class="material-icons prefix">search</i>
                    <input id="university_search" type="text" class="validate">
                    <label for="university_search">Search for your university... (e.g., "UNIZIK", "OAU")</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="trust-bar">
    <div class="container">
        <div class="row center">
            <div class="col s12 m4">
                <h5>150+</h5>
                <p>Projects Funded</p>
            </div>
            <div class="col s12 m4">
                <h5>₦50,000,000+</h5>
                <p>Raised for Education</p>
            </div>
            <div class="col s12 m4">
                <h5>2</h5>
                <p>Universities Partnered</p>
            </div>
        </div>
    </div>
</div>

<div class="container section" id="how-it-works">
    <h2 class="header center-align">A Simple Path to Real Impact</h2>
    <div class="row" style="margin-top: 40px;">
        <div class="col s12 m4">
            <div class="icon-block center">
                <h2 class="center indigo-text"><i class="material-icons">search</i></h2>
                <h5 class="center">1. Discover a Project</h5>
                <p class="light">Browse a list of vetted projects from your university. Find a cause you care about, from new labs to student scholarships.</p>
            </div>
        </div>
        <div class="col s12 m4">
            <div class="icon-block center">
                <h2 class="center indigo-text"><i class="material-icons">lock</i></h2>
                <h5 class="center">2. Donate Securely</h5>
                <p class="light">Make a contribution in minutes using our secure Stripe-powered payment system. Every transaction is transparent.</p>
            </div>
        </div>
        <div class="col s12 m4">
            <div class="icon-block center">
                <h2 class="center indigo-text"><i class="material-icons">timeline</i></h2>
                <h5 class="center">3. Track Your Impact</h5>
                <p class="light">Receive live timeline updates. See photos and videos as your project moves from "Funded" to "Complete."</p>
            </div>
        </div>
    </div>
</div>

<div class="section grey lighten-4" id="projects">
    <div class="container">
        <h2 class="header center-align">Projects That Need You Now</h2>
        <div class="row">
            @forelse ($featuredProjects as $project)
                <div class="col s12 m4">
                    <div class="card featured-card">
                        <div class="card-image featured-image-container">
                            <img src="{{ getImageUrl($project->cover_image_path) }}" alt="{{ $project->title }}" class="responsive-img featured-image">
                            <span class="card-title project-card-title">{{ Str::limit($project->title, 40) }}</span>
                        </div>
                        <div class="card-content">
                            <p>
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
                            <a href="{{ route('projects.show', $project->slug) }}" class="indigo-text">VIEW PROJECT DETAILS</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col s12 center-align">
                    <div class="card-panel white">
                        <p>No featured projects available at the moment. Check back soon!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="section testimonial-section">
    <div class="container">
        <div class="row">
            <div class="col s12 m8 offset-m2">
                
                <blockquote class="center-align">
                    "I always wanted to give back to my department, but I was never sure where the money would go. With NaijaEdu-Pact, I funded a specific piece of lab equipment and saw the photos when it was installed. It's the transparency for me."
                </blockquote>
                <p class="center-align"><strong>Dr. Adaeze Okonjo</strong><br>Alumna (Class of '95)</p>
            </div>
        </div>
    </div>
</div>

<div class="section center-align grey lighten-4">
    <div class="container">
        <h2 class="header">Is Your University on NaijaEdu-Pact?</h2>
        <p class="flow-text">Partner with us to build a sustainable funding pipeline. Onboard your university, empower your alumni, and bring your most critical projects to life.</p>
        <a href="#" class="btn-large waves-effect waves-light indigo darken-1" style="margin-top: 20px;">Partner With Us</a>
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
    .header {
        font-weight: 300;
    }
    h2.header {
        font-size: 2.5rem;
        margin-bottom: 40px;
    }
    /* --- Hero Section --- */
    .hero-section {
        position: relative;
        height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-image: url('/images/NaijaUndergradsInLibrary.png');
        background-size: cover;
        background-position: center;
        color: white;
    }
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(30, 30, 70, 0.6); /* Dark indigo overlay */
    }
    .hero-content {
        position: relative;
        z-index: 2;
    }
    .hero-content h1 {
        font-weight: 500;
        letter-spacing: 1px;
    }
    .hero-content .flow-text {
        font-weight: 300;
        margin-bottom: 30px;
    }
    .hero-search .input-field {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
    }
    .hero-search .input-field label,
    .hero-search .input-field .prefix {
        color: #424242 !important; /* Grey darken-3 */
    }
    .hero-search .input-field input {
        color: #212121 !important; /* Grey darken-4 */
        border-bottom: 1px solid #9e9e9e !important; /* Grey */
        box-shadow: none !important;
    }
    .hero-search .input-field input:focus {
        border-bottom: 1px solid #3f51b5 !important; /* Indigo */
    }

    /* --- Trust Bar --- */
    .trust-bar {
        background: #f4f4f4; /* Grey lighten-4 */
        padding: 30px 0;
        border-bottom: 1px solid #e0e0e0;
    }
    .trust-bar h5 {
        font-size: 2.2rem;
        font-weight: 500;
        color: #3f51b5; /* Indigo */
        margin-bottom: 0;
    }
    .trust-bar p {
        font-size: 1.1rem;
        color: #616161; /* Grey darken-2 */
        margin-top: 5px;
    }

    /* --- Featured Projects Fixes --- */
    .featured-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .featured-image-container {
        height: 200px;
        overflow: hidden;
    }
    .featured-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .card-content {
        flex-grow: 1;
    }
    .project-card-title {
        font-size: 1.25rem;
        line-height: 1.3;
        font-weight: 500;
    }

    /* --- Testimonial Section --- */
    .testimonial-section {
        background: white;
    }
    .testimonial-section blockquote {
        font-size: 1.5rem;
        font-weight: 300;
        font-style: italic;
        border-left: 5px solid #3f51b5; /* Indigo */
        color: #424242;
    }
</style>
@endpush