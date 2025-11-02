@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit: {{ $campaign->title }}</h3>
    <div class="card-panel">
        <form action="{{ route('uadmin.campaigns.update', $campaign) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="input-field">
                <input id="title" type="text" name="title" value="{{ old('title', $campaign->title) }}" required>
                <label for="title">Campaign Title</label>
            </div>
            <div class="input-field">
                <textarea id="description" name="description" class="materialize-textarea">{{ old('description', $campaign->description) }}</textarea>
                <label for="description">Campaign Description</label>
            </div>
            <div class="input-field">
                <input id="goal_amount" type="number" name="goal_amount" value="{{ old('goal_amount', $campaign->goal_amount) }}" required>
                <label for="goal_amount">Overall Goal Amount (₦)</label>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <input id="start_time" type="datetime-local" name="start_time" value="{{ old('start_time', $campaign->start_time->format('Y-m-d\TH:i')) }}" required>
                    <label for="start_time">Start Time</label>
                </div>
                <div class="input-field col s6">
                    <input id="end_time" type="datetime-local" name="end_time" value="{{ old('end_time', $campaign->end_time->format('Y-m-d\TH:i')) }}" required>
                    <label for="end_time">End Time</label>
                </div>
            </div>
            
            <div class="input-field">
                <select name="status" id="status" required>
                    <option value="draft" {{ $campaign->status == 'draft' ? 'selected' : '' }}>Draft (Not visible)</option>
                    <option value="active" {{ $campaign->status == 'active' ? 'selected' : '' }}>Active (Live)</option>
                    <option value="completed" {{ $campaign->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <label for="status">Campaign Status</label>
            </div>
            <button type="submit" class="btn waves-effect waves-light indigo">Update Campaign</button>
        </form>
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