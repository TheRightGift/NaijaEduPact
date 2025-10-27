@extends('layouts.landing') {{-- Use the public layout --}}

@section('content')
<div class="container section">
    <h2 class="header center">Explore All Projects</h2>
    <p class="center-align">Find a cause you care about and make a difference today.</p>

    {{-- This is where our Vue component will be mounted --}}
    <project-browser></project-browser>
</div>
@endsection