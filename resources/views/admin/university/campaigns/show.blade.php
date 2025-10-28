@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
    @endif

    <div class="card-panel">
        <a href="{{ route('uadmin.campaigns.index') }}" class="btn-flat waves-effect"><i class="material-icons left">arrow_back</i>Back to Campaigns</a>
        <h3>{{ $campaign->title }}</h3>
        <p>{{ $campaign->description }}</p>
        <div class="row">
            <div class="col s4"><strong>Status:</strong> <span class="new badge {{ $campaign->status == 'active' ? 'green' : 'grey' }}" data-badge-caption="">{{ ucfirst($campaign->status) }}</span></div>
            <div class="col s4"><strong>Goal:</strong> ₦{{ number_format($campaign->goal_amount) }}</div>
            <div class="col s4"><strong>Ends:</strong> {{ $campaign->end_time->format('M d, Y') }}</div>
        </div>
        <a href="{{ route('uadmin.campaigns.edit', $campaign) }}" class="btn indigo waves-effect waves-light">Edit Campaign Details</a>
    </div>

    <div class="row">
        <div class="col s12 m7">
            <div class="card-panel">
                <h5>Projects in this Campaign</h5>
                <ul class="collection">
                    @forelse($campaign->projects as $project)
                        <li class="collection-item">
                            <strong>{{ $project->title }}</strong>
                            <p>Goal: ₦{{ number_format($project->goal_amount) }}</p>
                        </li>
                    @empty
                        <li class="collection-item">No projects have been added to this campaign yet.</li>
                    @endforelse
                </ul>
            </div>
            <div class="card-panel">
                <h5>Add a Project</h5>
                <form action="{{ route('uadmin.campaigns.addProject', $campaign) }}" method="POST">
                    @csrf
                    <div class="input-field">
                        <select name="project_id" required>
                            <option value="" disabled selected>Choose an active project to add</option>
                            @foreach($unassignedProjects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</Goption>
                            @endforeach
                        </select>
                        <label>Select Project</label>
                    </div>
                    <button type="submit" class="btn waves-effect waves-light">Add Project</button>
                </form>
            </div>
        </div>
        <div class="col s12 m5">
            <div class="card-panel">
                <h5>Active Challenges</h5>
                <ul class="collection">
                    @forelse($campaign->challenges as $challenge)
                        <li class="collection-item">
                            <strong>{{ $challenge->donor_name }}</strong> will match <strong>₦{{ number_format($challenge->match_amount) }}</strong>
                            if {{ $challenge->challenge_threshold }} 
                            {{ $challenge->challenge_type == 'donor_count' ? 'donors contribute' : 'Naira is raised' }}.
                        </li>
                    @empty
                        <li class="collection-item">No challenges created for this campaign.</li>
                    @endforelse
                </ul>
            </div>
            <div class="card-panel">
                <h5>Add New Challenge</h5>
                <form action="{{ route('uadmin.campaigns.addChallenge', $campaign) }}" method="POST">
                    @csrf
                    <div class="input-field">
                        <input id="donor_name" type="text" name="donor_name" required>
                        <label for="donor_name">Matching Donor's Name</label>
                    </div>
                    <div class="input-field">
                        <input id="match_amount" type="number" name="match_amount" required>
                        <label for="match_amount">Match Amount (₦)</label>
                    </div>
                    <div class="input-field">
                        <select name="challenge_type" required>
                            <option value="" disabled selected>Challenge Type</option>
                            <option value="donor_count">Number of Donors</option>
                            <option value="total_amount">Total Amount Raised</option>
                        </select>
                        <label>Challenge Type</label>
                    </div>
                    <div class="input-field">
                        <input id="challenge_threshold" type="number" name="challenge_threshold" required>
                        <label for="challenge_threshold">Threshold (e.g., 100 donors or 500000 Naira)</label>
                    </div>
                    <button type="submit" class="btn waves-effect waves-light">Add Challenge</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize Materialize select dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        M.FormSelect.init(elems);
    });
</script>
@endpush