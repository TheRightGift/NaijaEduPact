@extends('layouts.landing')
@section('content')
<div class="container">
    <h2>{{ $project->title }}</h2>
    <p>{{ $project->description }}</p>
    <p><strong>Goal:</strong> ₦{{ number_format($project->goal_amount) }}</p>
</div>
@endsection