@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Donor Intelligence</h3>
    <p>Focus your personal outreach on the highest-potential donors.</p>

    <a href="{{ route('uadmin.analytics.index') }}" class="btn-flat">All</a>
    <a href="{{ route('uadmin.analytics.index', ['segment' => 'Potential Major Donor']) }}" class="btn-flat">Potential Major Donors</a>
    <a href="{{ route('uadmin.analytics.index', ['segment' => 'Likely to Upgrade']) }}" class="btn-flat">Likely to Upgrade</a>
    <a href="{{ route('uadmin.analytics.index', ['segment' => 'At Risk of Lapsing']) }}" class="btn-flat">At Risk of Lapsing</a>

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
                    <td colspan="5">No donors found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    {{ $donors->links('pagination::materialize-css') }}
</div>
@endsection