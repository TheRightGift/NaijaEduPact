@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Add New University</h3>
    <div class="card-panel">
        <form action="{{ route('superadmin.universities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-field">
                <input id="name" type="text" name="name" required>
                <label for="name">University Name</label>
            </div>
            <div class="input-field">
                <textarea id="description" name="description" class="materialize-textarea"></textarea>
                <label for="description">Description</label>
            </div>
            <div class="file-field input-field">
                <div class="btn indigo">
                    <span>Logo</span>
                    <input type="file" name="logo_path">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <button type="submit" class="btn indigo waves-effect waves-light">Save University</button>
        </form>
    </div>
</div>
@endsection