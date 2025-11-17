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
                <h4>Edit General Fund Story</h4>
                <p>This is the main "pitch" donors will see when they visit your university's page. Use text, images, and video to make it compelling.</p>
                <form action="{{ route('uadmin.general-fund.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="input-field">
                        <textarea id="general_fund_story" name="general_fund_story" class="rich-editor">{{ old('general_fund_story', $university->general_fund_story) }}</textarea>
                    </div>
                    <button type="submit" class="btn indigo">Save Story</button>
                </form>
            </div>
        </div>
        
        <div class="col s12 l6">
            <div class="card-panel">
                <h4>Post a Timeline Update</h4>
                <p>Show donors how their General Fund contributions are being used.</p>
                <form action="{{ route('uadmin.general-fund.storeUpdate') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-field">
                        <input id="title" type="text" name="title" required>
                        <label for="title">Update Title (e.g., "New Laptops for Library")</label>
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