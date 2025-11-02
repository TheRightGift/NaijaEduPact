@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Edit {{ $university->name }}</h3>
    <div class="card-panel">
        <form action="{{ route('superadmin.universities.update', $university) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="input-field">
                <input id="name" type="text" name="name" value="{{ $university->name }}" required>
                <label for="name">University Name</label>
            </div>
            <div class="input-field">
                <textarea id="description" name="description" class="materialize-textarea">{{ $university->description }}</textarea>
                <label for="description">Description</label>
            </div>
            <div class="input-field">
                <select name="status">
                    <option value="approved" {{ $university->status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ $university->status == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <label>Status</label>
            </div>
            <div class="file-field input-field">
                <div class="btn indigo">
                    <span>New Logo</span>
                    <input type="file" name="logo_path">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <button type="submit" class="btn indigo waves-effect waves-light">Update University</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('select');
        M.FormSelect.init(elems);
    });
</script>
@endpush