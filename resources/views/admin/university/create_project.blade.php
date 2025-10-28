@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Submit a New Project</h3>
    <div class="card-panel">
        
        {{-- 1. Add enctype for file uploads --}}
        <form action="{{ route('uadmin.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-field">
                <input id="title" type="text" name="title" value="{{ old('title') }}" required>
                <label for="title">Project Title</label>
                @error('title')
                    <span class="helper-text red-text">{{ $message }}</span>
                @enderror
            </div>
            <div class="input-field">
                <textarea id="description" name="description" class="materialize-textarea" required>{{ old('description') }}</textarea>
                <label for="description">Project Description</label>
                @error('description')
                    <span class="helper-text red-text">{{ $message }}</span>
                @enderror
            </div>
            <div class="input-field">
                <input id="goal_amount" type="number" name="goal_amount" step="1000" value="{{ old('goal_amount') }}" required>
                <label for="goal_amount">Goal Amount (₦)</label>
                @error('goal_amount')
                    <span class="helper-text red-text">{{ $message }}</span>
                @enderror
            </div>

            {{-- 2. Add the File Input Field --}}
            <div class="file-field input-field">
                <div class="btn indigo">
                    <span>Cover Image</span>
                    <input type="file" name="cover_image">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="Upload a project cover image (Optional)">
                </div>
                @error('cover_image')
                    <span class="helper-text red-text">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn waves-effect waves-light indigo">Submit for Approval</button>
        </form>
    </div>
</div>
@endsection