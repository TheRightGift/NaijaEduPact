@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="card-panel green lighten-4 green-text text-darken-4">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="card-panel red lighten-4 red-text text-darken-4">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="row">
        <div class="col s12 l6">
            <div class="card-panel">
                <h4>Edit Project Details</h4>
                <form action="{{ route('uadmin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="input-field">
                        <input id="title" type="text" name="title" value="{{ old('title', $project->title) }}" required>
                        <label for="title">Project Title</label>
                    </div>

                    <div class="input-field">
                        <input id="goal_amount" type="number" name="goal_amount" value="{{ old('goal_amount', $project->goal_amount) }}" required>
                        <label for="goal_amount">Goal Amount (₦)</label>
                    </div>

                    <div class="file-field input-field">
                        <div class="btn indigo">
                            <span>New Cover Image</span>
                            <input type="file" name="cover_image">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text" placeholder="Upload to change image">
                        </div>
                    </div>
                    
                    <div class="input-field">
                        <textarea id="description" name="description" class="rich-editor">{{ old('description', $project->description) }}</textarea>
                    </div>
                    <button type="submit" class="btn indigo">Save Changes</button>
                </form>
            </div>
        </div>
        
        <div class="col s12 l6">
            <div class="card-panel">
                <h4>Post a Timeline Update</h4>
                <form action="{{ route('uadmin.projects.updates.store', $project) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-field">
                        <input id="title" type="text" name="title" required>
                        <label for="title">Update Title (e.g., "Groundbreaking!")</label>
                    </div>
                    <div class="input-field">
                        <textarea id="content" name="content" class="materialize-textarea" required></textarea>
                        <label for="content">Update Details</label>
                    </div>
                    <div class="input-field">
                        <select name="media_type" id="media_type">
                            <option value="" selected>No Media</option>
                            <option value="image">Upload Image</option>
                            <option value="video_embed">Video URL (YouTube)</option>
                        </select>
                        <label>Media Type</label>
                    </div>
                    <div class="file-field input-field" id="image-upload" style="display:none;">
                        <div class="btn indigo">
                            <span>Image</span>
                            <input type="file" name="media_image">
                        </div>
                        <div class="file-path-wrapper"><input class="file-path validate" type="text"></div>
                    </div>
                    <div class="input-field" id="video-upload" style="display:none;">
                        <input id="media_video" type="url" name="media_video">
                        <label for="media_video">YouTube URL</label>
                    </div>
                    <button type="submit" class="btn-large indigo">Post Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        M.FormSelect.init(elems);
        
        // Logic to show/hide media upload fields
        var mediaTypeSelect = document.getElementById('media_type');
        var imageUpload = document.getElementById('image-upload');
        var videoUpload = document.getElementById('video-upload');
        
        mediaTypeSelect.addEventListener('change', function() {
            imageUpload.style.display = (this.value == 'image') ? 'block' : 'none';
            videoUpload.style.display = (this.value == 'video_embed') ? 'block' : 'none';
        });
    });
</script>
@endpush