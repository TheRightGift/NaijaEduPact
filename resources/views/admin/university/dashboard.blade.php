@extends('layouts.app')

@section('content')
<div class="container">
    <div class="section">
        <h3>University Admin Dashboard</h3>
        <p class="flow-text">Welcome, {{ Auth::user()->name }}. Manage your university's projects and campaigns here.</p>
    </div>

    <div class="divider"></div>

    <div class="section">
        <div class="row">
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title"><i class="material-icons left">assignment</i> Manage Projects</span>
                        <p>Create new projects, post updates, and manage existing fundraising goals.</p>
                    </div>
                    <div class="card-action">
                        <a href="{{ route('uadmin.projects.create') }}" class="indigo-text">Create New Project</a>
                        <a href="{{ route('uadmin.projects.index') }}" class="indigo-text">View All Projects</a>
                    </div>
                </div>
            </div>

            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title"><i class="material-icons left">event</i> Manage Campaigns</span>
                        <p>Create and manage high-energy Giving Days and special crowdfunding campaigns.</p>
                    </div>
                    <div class="card-action">
                        <a href="{{ route('uadmin.campaigns.create') }}" class="indigo-text">Create New Campaign</a>
                        <a href="{{ route('uadmin.campaigns.index') }}" class="indigo-text">View All Campaigns</a>
                    </div>
                </div>
            </div>
            
            <div class="col s12 m6">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title"><i class="material-icons left">analytics</i> Donor Intelligence</span>
                        <p>View analytics on your donors to find the best opportunities for outreach.</p>
                    </div>
                    <div class="card-action">
                        <a href="{{ route('uadmin.analytics.index') }}" class="indigo-text">View Analytics</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection