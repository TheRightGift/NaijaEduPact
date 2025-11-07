@extends('layouts.landing') {{-- Use the public-facing layout --}}

@section('content')
<div class="container section">
    <h2 class="header center">Explore All Projects</h2>
    <p class="center-align">Find a cause you care about and make a difference today.</p>

    {{-- 
      This is where the Vue component is mounted.
      We pass the auth status and the app's base URL as props.
    --}}
    <project-browser 
        :app-url="'{{ rtrim(config('app.url'), '/') }}'"
    ></project-browser>
</div>
@endsection