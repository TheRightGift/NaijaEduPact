@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" style="margin-top: 50px;">
        <div class="col s12 m8 offset-m2">
            <div class="card-panel">
                <h4 class="center-align">Register</h4>
                <div class="row">
                    <form class="col s12" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">account_circle</i>
                                <input id="name" type="text" name="name" class="@error('name') is-invalid @enderror" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                <label for="name">Name</label>
                                @error('name')
                                    <span class="helper-text red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">email</i>
                                <input id="email" type="email" name="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email">
                                <label for="email">Email Address</label>
                                @error('email')
                                    <span class="helper-text red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">school</i>
                                <select name="university_id" id="university_id" required>
                                    <option value="" disabled selected>Choose your University</option>
                                    @foreach($universities as $university)
                                        <option value="{{ $university->id }}" {{ old('university_id') == $university->id ? 'selected' : '' }}>
                                            {{ $university->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label for="university_id">University</label>
                                @error('university_id')
                                    <span class="helper-text red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">lock</i>
                                <input id="password" type="password" name="password" class="@error('password') is-invalid @enderror" required autocomplete="new-password">
                                <label for="password">Password</label>
                                @error('password')
                                    <span class="helper-text red-text">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-field col s12 m6">
                                <i class="material-icons prefix">lock_outline</i>
                                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                                <label for="password-confirm">Confirm Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">school</i>
                                <select name="role" id="role" onchange="toggleGradYear(this.value)" required>
                                    <option value="" disabled selected>Register as...</option>
                                    <option value="donor" {{ old('role') == 'donor' ? 'selected' : '' }}>Alumnus / Donor</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Current Student</option>
                                </select>
                                <label for="role">I am a</label>
                                @error('role')
                                    <span class="helper-text red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row" id="grad_year_field" style="display:{{ old('role') == 'student' ? 'block' : 'none' }};">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">calendar_today</i>
                                <input id="expected_graduation_year" type="number" name="expected_graduation_year" min="1980" max="2050" value="{{ old('expected_graduation_year') }}">
                                <label for="expected_graduation_year">Expected Graduation Year</label>
                                @error('expected_graduation_year')
                                    <span class="helper-text red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 center-align">
                                <button type="submit" class="btn waves-effect waves-light indigo">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
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
    });

    function toggleGradYear(role) {
        var gradYearField = document.getElementById('grad_year_field');
        if (role === 'student') {
            gradYearField.style.display = 'block';
        } else {
            gradYearField.style.display = 'none';
        }
    }
</script>
@endpush