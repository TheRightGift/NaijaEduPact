@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" style="margin-top: 50px;">
        <div class="col s12 m8 offset-m2 l6 offset-l3">
            <div class="card-panel">
                <h4 class="center-align">Reset Password</h4>

                @if (session('status'))
                    <div class="card-panel green lighten-4 green-text text-darken-4">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="row">
                    <form class="col s12" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">email</i>
                                <input id="email" type="email" name="email" class="@error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <label for="email">Email Address</label>
                                @error('email')
                                    <span class="helper-text red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 center-align">
                                <button type="submit" class="btn waves-effect waves-light indigo">
                                    Send Password Reset Link
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