@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" style="margin-top: 50px;">
        <div class="col s12 m8 offset-m2 l6 offset-l3">
            <div class="card-panel">
                <h4 class="center-align">Reset Password</h4>
                <div class="row">
                    <form class="col s12" method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">email</i>
                                <input id="email" type="email" name="email" class="@error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                <label for="email">Email Address</label>
                                @error('email')
                                    <span class="helper-text red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">lock</i>
                                <input id="password" type="password" name="password" class="@error('password') is-invalid @enderror" required autocomplete="new-password">
                                <label for="password">New Password</label>
                                @error('password')
                                    <span class="helper-text red-text">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">lock_outline</i>
                                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                                <label for="password-confirm">Confirm New Password</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 center-align">
                                <button type="submit" class="btn waves-effect waves-light indigo">
                                    Reset Password
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