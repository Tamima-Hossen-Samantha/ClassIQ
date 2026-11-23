<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Notifications\TeacherApprovedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminTeacherController extends Controller
{
   
    public function index()
{
    // add withCount if relationships exist
    $pendingTeachers = Teacher::where('is_approved', false)
        // ->withCount('classes', 'students')  // uncomment when you have these relations
        ->get();

    $approvedTeachers = Teacher::where('is_approved', true)
        // ->withCount('classes', 'students')
        ->get();

    return view('admin.teachers', compact('pendingTeachers', 'approvedTeachers'));
}


    public function approve(Teacher $teacher)
    {
        if ($teacher->is_approved) {
            return back()->with('status', 'Teacher is already approved.');
        }

        $teacher->update([
            'is_approved' => true,
        ]);

        $teacher->notify(new TeacherApprovedNotification());

        return back()->with('status', 'Teacher approved and email sent.');
    }

    public function destroy(Teacher $teacher)
    {
        // Optionally delete NID file here
        $teacher->delete();

        return back()->with('status', 'Teacher removed.');
    }

    

    // Reject teacher (mark as not approved)
    public function reject(Teacher $teacher)
    {
        if (! $teacher->is_approved) {
            return back()->with('status', 'Teacher is already pending/rejected.');
        }

        $teacher->update([
            'is_approved' => false,
        ]);

        return back()->with('status', 'Teacher marked as rejected.');
    }

    // Toggle suspend/activate teacher account
    public function toggleSuspend(Teacher $teacher)
    {
        $teacher->update([
            'is_suspended' => ! $teacher->is_suspended,
        ]);

        return back()->with('status', $teacher->is_suspended
            ? 'Teacher account suspended.'
            : 'Teacher account activated.');
    }

    // Mark teacher documents verified
    public function verifyDocuments(Teacher $teacher)
    {
        $teacher->update([
            'documents_verified_at' => now(),
        ]);

        return back()->with('status', 'Teacher documents marked as verified.');
    }

    // Show single teacher detail (for edit/profile/actions page)
    public function show(Teacher $teacher)
    {
        return view('admin.teacher_show', compact('teacher'));
    }

    // Edit teacher profile fields
    public function updateProfile(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:teachers,email,' . $teacher->id],
            'phone'           => ['nullable', 'string', 'max:20'],
            'school_name'     => ['nullable', 'string', 'max:255'],
            'subjects_taught' => ['nullable', 'string', 'max:255'],
            'classes_taught'  => ['nullable', 'string', 'max:255'],
        ]);

        $teacher->update($validated);

        return back()->with('status', 'Teacher profile updated.');
    }

    
}
