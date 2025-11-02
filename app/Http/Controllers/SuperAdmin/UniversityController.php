<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UniversityController extends Controller
{
    /**
     * Display a listing of all universities.
     */
    public function index()
    {
        $universities = University::latest()->paginate(20);
        return view('admin.superadmin.universities.index', compact('universities'));
    }

    /**
     * Show the form for creating a new university.
     */
    public function create()
    {
        return view('admin.superadmin.universities.create');
    }

    /**
     * Store a newly created university in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:universities',
            'description' => 'nullable|string',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:1024' // 1MB Max
        ]);

        $logoPath = null;
        if ($request->hasFile('logo_path')) {
            $logoPath = $request->file('logo_path')->store('university-logos', 'public');
        }

        University::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'logo_path' => $logoPath,
            'status' => 'approved', // Super Admins approve by default
        ]);

        return redirect()->route('superadmin.universities.index')->with('success', 'University created successfully.');
    }

    /**
     * Show the form for editing the specified university.
     */
    public function edit(University $university)
    {
        return view('admin.superadmin.universities.edit', compact('university'));
    }

    /**
     * Update the specified university in storage.
     */
    public function update(Request $request, University $university)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:universities,name,' . $university->id,
            'description' => 'nullable|string',
            'status' => 'required|string|in:pending,approved',
            'logo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:1024'
        ]);
        
        $logoPath = $university->logo_path;
        if ($request->hasFile('logo_path')) {
            // TODO: Delete old logo
            $logoPath = $request->file('logo_path')->store('university-logos', 'public');
        }

        $university->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->status,
            'logo_path' => $logoPath,
        ]);

        return redirect()->route('superadmin.universities.index')->with('success', 'University updated successfully.');
    }

    /**
     * Remove the specified university from storage.
     */
    public function destroy(University $university)
    {
        // TODO: Delete logo from S3/storage
        $university->delete();
        return back()->with('success', 'University deleted.');
    }
}