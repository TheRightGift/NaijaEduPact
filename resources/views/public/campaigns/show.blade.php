@extends('layouts.landing')

@section('content')
<div class="container section">
    <div class="center-align">
        <h1>{{ $campaign->title }}</h1>
        <p class="flow-text">{{ $campaign->description }}</p>
    </div>
    
    <campaign-page initial-campaign="{{ json_encode($campaign) }}"></campaign-page>

</div>
@endsection