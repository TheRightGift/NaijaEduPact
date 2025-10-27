@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Create a New Fundraising Campaign</h3>
    <div class="card-panel">
        <form action="{{ route('uadmin.campaigns.store') }}" method="POST">
            @csrf
            <div class="input-field">
                <input id="title" type="text" name="title" required>
                <label for="title">Campaign Title (e.g., Annual Giving Day 2025)</label>
            </div>
            <div class="input-field">
                <textarea id="description" name="description" class="materialize-textarea"></textarea>
                <label for="description">Campaign Description</label>
            </div>
            <div class="input-field">
                <input id="goal_amount" type="number" name="goal_amount" required>
                <label for="goal_amount">Overall Goal Amount (₦)</label>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <input id="start_time" type="datetime-local" name="start_time" required>
                    <label for="start_time">Start Time</label>
                </div>
                <div class="input-field col s6">
                    <input id="end_time" type="datetime-local" name="end_time" required>
                    <label for="end_time">End Time</label>
                </div>
            </div>
            
            {{-- A simplified section for adding challenges --}}
            <h5>Add a Challenge (Optional)</h5>
            <p>You can add detailed challenges after creating the campaign.</p>

            <button type="submit" class="btn waves-effect waves-light indigo">Create Campaign</button>
        </form>
    </div>
</div>
@endsection