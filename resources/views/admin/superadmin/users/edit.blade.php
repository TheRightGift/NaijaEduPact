@extends('layouts.app')
@section('content')
<div class="container">
    <h3>Edit User: {{ $user->name }}</h3>
    <p>{{ $user->email }}</p>
    <div class="card-panel">
        <form action="{{ route('superadmin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="input-field">
                <select name="role">
                    <option value="donor" {{ $user->role == 'donor' ? 'selected' : '' }}>Donor</option>
                    <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="universityadmin" {{ $user->role == 'universityadmin' ? 'selected' : '' }}>University Admin</option>
                    <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                </select>
                <label>User Role</label>
            </div>

            <div class="input-field">
                <select name="university_id">
                    <option value="">N/A</option>
                    @foreach($universities as $uni)
                        <option value="{{ $uni->id }}" {{ $user->university_id == $uni->id ? 'selected' : '' }}>
                            {{ $uni->name }}
                        </option>
                    @endforeach
                </select>
                <label>Assign to University</label>
                <span class="helper-text">Required if role is Student or University Admin.</span>
            </div>

            <button type="submit" class="btn indigo waves-effect waves-light">Update User</button>
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