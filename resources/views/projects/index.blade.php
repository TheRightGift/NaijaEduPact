@extends('layouts.landing') 

@section('content')
<div class="container section">
    <h2 class="header center">Explore All Projects</h2>
    <p class="center-align">Find a cause you care about and make a difference today.</p>

    <project-browser :is-logged-in="{{ auth()->check() ? 'true' : 'false' }}"></project-browser>
</div>
@endsection