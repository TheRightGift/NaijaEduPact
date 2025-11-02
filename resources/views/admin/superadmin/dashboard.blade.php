@extends('layouts.app')

@section('content')
<div class="container">
    <div class="section">
        <h3>Super Admin Dashboard</h3>
        <p class="flow-text">Platform-wide management and oversight.</p>
    </div>

    <div class="divider"></div>

    <div class="section">
        <div class="row">
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title"><i class="material-icons left">check_circle</i> Project Vetting</span>
                        <p>Review and approve new projects submitted by universities to make them live.</p>
                    </div>
                    <div class="card-action">
                        <a href="{{ route('superadmin.projects.pending') }}" class="indigo-text">View Pending Projects</a>
                    </div>
                </div>
            </div>

            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title"><i class="material-icons left">school</i> Manage Universities</span>
                        <p>Onboard new partner universities, edit profiles, and manage university admins.</p>
                    </div>
                    <div class="card-action">
                        <a href="{{ route('superadmin.universities.index') }}" class="indigo-text">View All Universities</a>
                    </div>
                </div>
            </div>

            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title"><i class="material-icons left">people</i> Manage Users</span>
                        <p>Assign roles and manage user accounts.</p>
                    </div>
                    <div class="card-action">
                        <a href="{{ route('superadmin.users.index') }}" class="indigo-text">View All Users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection