@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col s10">
            <h3>My Campaigns</h3>
            <p>Manage your Giving Days and special fundraising events.</p>
        </div>
        <div class="col s2" style="margin-top: 20px;">
            <a href="{{ route('uadmin.campaigns.create') }}" class="btn-floating btn-large waves-effect waves-light indigo tooltipped" data-position="bottom" data-tooltip="Create New Campaign">
                <i class="material-icons">add</i>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
    @endif

    <div class="card-panel">
        <table class="striped">
            <thead>
                <tr>
                    <th>Campaign Title</th>
                    <th>Status</th>
                    <th>Goal Amount</th>
                    <th>Starts</th>
                    <th>Ends</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td>{{ $campaign->title }}</td>
                        <td>
                            @php
                                $color = 'grey';
                                if ($campaign->status == 'active') $color = 'green';
                                if ($campaign->status == 'completed') $color = 'blue';
                            @endphp
                            <span class="new badge {{ $color }}" data-badge-caption="">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                        <td>₦{{ number_format($campaign->goal_amount) }}</td>
                        <td>{{ $campaign->start_time->format('M d, Y') }}</td>
                        <td>{{ $campaign->end_time->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('uadmin.campaigns.show', $campaign) }}" class="btn-small waves-effect waves-light">
                                View/Manage
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="center-align">You have not created any campaigns yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Links --}}
    <div class="center-align">
        {{ $campaigns->links('pagination::materialize-css') }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize Materialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.tooltipped');
        M.Tooltip.init(elems);
    });
</script>
@endpush