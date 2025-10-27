@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" style="margin-top: 50px;">
        <div class="col s12 m8 offset-m2">
            <div class="card-panel">
                <h4 class="center-align">Verify Your Email Address</h4>

                @if (session('resent'))
                    <div class="card-panel green lighten-4 green-text text-darken-4">
                        A fresh verification link has been sent to your email address.
                    </div>
                @endif

                <p>Before proceeding, please check your email for a verification link.</p>
                <p>If you did not receive the email, you can request another one.</p>

                <form class="col s12" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <div class="row">
                        <div class="col s12 center-align">
                            <button type="submit" class="btn waves-effect waves-light indigo">
                                Click here to request another
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection