@extends('layouts.app')

@section('content')
<div class="container">
    <h3>My Donation History</h3>
    <p>Thank you for your generous contributions.</p>

    <div class="card-panel">
        <table class="striped">
            <thead>
                <tr>
                    <th>Donation Target</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Reference ID</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donations as $donation)
                    <tr>
                        <td>
                            @if ($donation->project)
                                {{-- It's a project donation --}}
                                <a href="{{ route('projects.show', $donation->project) }}" class="indigo-text">
                                    {{ $donation->project->title }}
                                </a>
                            @else
                                {{-- It's a general fund donation --}}
                                <a href="{{ route('universities.show', $donation->university) }}" class="indigo-text">
                                    {{ $donation->university->name }} General Fund
                                </a>
                            @endif
                        </td>
                        <td>₦{{ number_format($donation->amount) }}</td>
                        <td>{{ $donation->created_at->format('M d, Y') }}</td>
                        <td>{{ $donation->reference }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="center-align">You have not made any successful donations yet.</td>
                    </tr>
                {{-- THIS IS THE FIX (all lowercase) --}}
                @endforelse 
            </tbody>
        </table>
    </div>

    {{ $donations->links('pagination::materialize-css') }}
</div>
@endsection