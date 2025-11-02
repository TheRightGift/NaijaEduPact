<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\University;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        $query = User::with('university');

        // Allow searching
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
        }
        
        $users = $query->latest()->paginate(25);
        return view('admin.superadmin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user (role/university).
     */
    public function edit(User $user)
    {
        $universities = University::where('status', 'approved')->get();
        return view('admin.superadmin.users.edit', compact('user', 'universities'));
    }

    /**
     * Update the specified user's role and university.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|in:donor,student,universityadmin,superadmin',
            'university_id' => 'nullable|required_if:role,universityadmin|required_if:role,student|exists:universities,id'
        ]);

        $user->update([
            'role' => $request->role,
            'university_id' => $request->university_id,
        ]);

        return redirect()->route('superadmin.users.index')->with('success', 'User role updated.');
    }
}