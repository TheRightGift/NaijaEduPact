@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Submit a New Project</h3>
    <div class="card-panel">
        <form action="{{ route('uadmin.projects.store') }}" method="POST">
            @csrf
            <div class="input-field">
                <input id="title" type="text" name="title" required>
                <label for="title">Project Title</label>
            </div>
            <div class="input-field">
                <textarea id="description" name="description" class="materialize-textarea" required></textarea>
                <label for="description">Project Description</label>
            </div>
            <div class="input-field">
                <input id="goal_amount" type="number" name="goal_amount" step="1000" required>
                <label for="goal_amount">Goal Amount (₦)</label>
            </div>
            <button type="submit" class="btn waves-effect waves-light indigo">Submit for Approval</button>
        </form>
    </div>
</div>
@endsection