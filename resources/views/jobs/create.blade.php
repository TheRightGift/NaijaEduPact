@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Post a New Job</h3>
    <div class="card-panel">
        <form action="{{ route('jobs.store') }}" method="POST">
            @csrf
            <div class="input-field">
                <input id="title" type="text" name="title" required>
                <label for="title">Job Title</label>
            </div>
            <div class="input-field">
                <input id="location" type="text" name="location" required>
                <label for="location">Location (e.g., "Lagos, Nigeria / Remote")</label>
            </div>
            <div class="input-field">
                <select name="job_type" id="job_type" required>
                    <option value="" disabled selected>Choose job type</option>
                    <option value="full-time">Full-Time</option>
                    <option value="part-time">Part-Time</option>
                    <option value="internship">Internship</option>
                </select>
                <label for="job_type">Job Type</label>
            </div>
            <div class="input-field">
                <textarea id="description" name="description" class="materialize-textarea" required></textarea>
                <label for="description">Job Description</label>
            </div>
            <button type="submit" class="btn waves-effect waves-light indigo">Submit Job</button>
        </form>
    </div>
</div>

<script>
    // Initialize Materialize select
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        M.FormSelect.init(elems);
    });
</script>
@endpush