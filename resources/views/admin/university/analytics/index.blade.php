@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Donor Intelligence</h3>
    <p>Focus your personal outreach on the highest-potential donors.</p>

    <div class="section">
        <a href="{{ route('uadmin.analytics.index') }}" class="btn-flat waves-effect">All</a>
        <a href="{{ route('uadmin.analytics.index', ['segment' => 'Potential Major Donor']) }}" class="btn-flat waves-effect">Potential Major Donors</a>
        <a href="{{ route('uadmin.analytics.index', ['segment' => 'Likely to Upgrade']) }}" class="btn-flat waves-effect">Likely to Upgrade</a>
        <a href="{{ route('uadmin.analytics.index', ['segment' => 'At Risk of Lapsing']) }}" class="btn-flat waves-effect">At Risk of Lapsing</a>
        <a href="{{ route('uadmin.analytics.index', ['segment' => 'Engaged Non-Donor']) }}" class="btn-flat waves-effect">Engaged Non-Donors</a>
        <a href="{{ route('uadmin.analytics.index', ['segment' => 'General Donor']) }}" class="btn-flat waves-effect">General Donors</a>
    </div>

    <table class="striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Segment</th>
                <th>Likelihood Score</th>
                <th>Capacity Score</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donors as $donor)
                <tr>
                    <td>{{ $donor->name }}</td>
                    <td><span class="new badge" data-badge-caption="">{{ $donor->segment }}</span></td>
                    <td>{{ $donor->likelihood_score }}%</td>
                    <td>{{ $donor->capacity_score }}%</td>
                    <td>{{ $donor->email }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center-align">No donors found in this segment.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    {{-- This ensures the pagination links keep the segment filter --}}
    {{ $donors->appends(request()->except('page'))->links('pagination::materialize-css') }}
</div>
@endsection