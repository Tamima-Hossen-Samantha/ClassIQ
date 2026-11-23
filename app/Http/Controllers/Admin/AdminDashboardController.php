<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Learner; 
use App\Models\Course; 
use App\Models\Chapter;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;

use App\Models\Lesson;
use App\Models\Teacher;

class AdminDashboardController extends Controller
{
    
   public function index()
{
    $totalTeachers = Teacher::count();
    $totalStudents = Learner::count();
    $totalCourses  = Course::count();
    $totalLessons  = Lesson::count();

    // Placeholders until you build these features
    $totalSubjects = 0;
    $totalQuizzes  = 0;

    $aiUsageToday  = 0;
    $aiUsageTotal  = 0;

    $recentActivity       = collect();
    $pendingVerifications = collect();
    $newTeachers          = Teacher::where('created_at', '>=', now()->subDays(7))
                                   ->latest()->take(5)->get();
    $newStudents          = Learner::where('created_at', '>=', now()->subDays(7))
                                   ->latest()->take(5)->get();
    $notifications        = collect();

    return view('admin.dashboard', compact(
        'totalTeachers',
        'totalStudents',
        'totalCourses',
        'totalSubjects',
        'totalLessons',
        'totalQuizzes',
        'aiUsageToday',
        'aiUsageTotal',
        'recentActivity',
        'pendingVerifications',
        'newTeachers',
        'newStudents',
        'notifications'
    ));
}

    


    //student

    // table
public function studentsIndex(Request $request)
{
    $search = $request->query('q');

    $learners = Learner::query()
        ->when($search, function ($q) use ($search) {
            $q->where('name', 'like', '%'.$search.'%')
              ->orWhere('email', 'like', '%'.$search.'%');
        })
        ->orderByDesc('last_login_at')
        ->paginate(15)
        ->withQueryString();

    return view('admin.students', compact('learners', 'search'));
}

// suspend / activate 
public function studentsToggleStatus(Learner $learner)
{
    // Example: active / suspended boolean or status field
    if ($learner->status === 'active') {
        $learner->status = 'suspended';
    } else {
        $learner->status = 'active';
    }

    $learner->save();

    return back()->with('status', 'Student status updated.');
}

// hange class/sectio
public function studentsChangeClass(Request $request, Learner $learner)
{
    $data = $request->validate([
        'class_level' => ['required', 'integer', 'between:1,12'],
        'section'     => ['nullable', 'string', 'max:20'],
    ]);

    $learner->class_level = $data['class_level'];
    $learner->section     = $data['section'] ?? null;
    $learner->save();

    return back()->with('status', 'Student class/section updated.');
}

    public function coursesIndex(Request $request)
    {
        $statusFilter = $request->query('admin_status', 'all');

        $query = Course::with('teacher');

        if ($statusFilter !== 'all') {
            $query->where('admin_status', $statusFilter);
        }

        $courses = $query
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        // use resources/views/admin/courses.blade.php
        return view('admin.courses', compact('courses', 'statusFilter'));
    }

    public function coursesApprove(Course $course)
    {
        $course->admin_status = 'approved';
        $course->save();

        return back()->with('status', 'Course approved.');
    }

    public function coursesReject(Course $course)
    {
        $course->admin_status = 'rejected';
        $course->save();

        return back()->with('status', 'Course rejected.');
    }

    public function coursesToggleFeatured(Course $course)
    {
        $course->is_featured = ! $course->is_featured;
        $course->save();

        return back()->with('status', 'Featured flag updated.');
    }

    public function coursesToggleVisibility(Course $course)
    {
        $course->is_visible_in_catalog = ! $course->is_visible_in_catalog;
        $course->save();

        return back()->with('status', 'Catalog visibility updated.');
    }

    public function moderateChapters(Request $request, Course $course)
{
    // Load all chapters and topics; admin sees everything, including draft/pending
    $course->load([
        'teacher',
        'chapters' => function ($q) {
            $q->orderBy('position');
        },
        'chapters.topics' => function ($q) {
            $q->orderBy('position');
        },
    ]);

    return view('admin.chapters', compact('course'));
}

public function chapterModerate(Request $request, Chapter $chapter)
{
    $data = $request->validate([
        'title'           => ['required', 'string', 'max:255'],
        'position'        => ['nullable', 'integer', 'min:1'],
        'approval_status' => ['required', 'in:approved,rejected,pending'],
        'admin_note'      => ['nullable', 'string', 'max:2000'],
    ]);

    $chapter->title = $data['title'];
    $chapter->approval_status = $data['approval_status'];
    $chapter->admin_note = $data['admin_note'] ?? null;

    if (! empty($data['position']) && $data['position'] != $chapter->position) {
        // Update sequence by just changing number; teacher-side ordering logic can later respect this.
        $chapter->position = $data['position'];
    }

    $chapter->last_moderated_by_admin_id = Auth::guard('admin')->id();
    $chapter->last_moderated_at = now();
    $chapter->save();

    return back()->with('status', 'Chapter updated by admin.');
}

public function topicModerate(Request $request, Topic $topic)
{
    $data = $request->validate([
        'title'           => ['required', 'string', 'max:255'],
        'content'         => ['nullable', 'string'],
        'approval_status' => ['required', 'in:approved,rejected,pending'],
        'admin_note'      => ['nullable', 'string', 'max:2000'],
    ]);

    $topic->title = $data['title'];
    $topic->content = $data['content'] ?? null;
    $topic->approval_status = $data['approval_status'];
    $topic->admin_note = $data['admin_note'] ?? null;

    $topic->last_moderated_by_admin_id = Auth::guard('admin')->id();
    $topic->last_moderated_at = now();
    $topic->save();

    return back()->with('status', 'Topic updated by admin.');
}

public function topicDestroyByAdmin(Topic $topic)
{
    $topic->delete();

    return back()->with('status', 'Topic deleted.');
}


// lessons

public function lessonsIndex(Request $request)
{
    $query = Lesson::query()
        ->with(['topic.chapter.course', 'topic.chapter.course.teacher'])
        ->orderByDesc('created_at');

    // Optional filters
    if ($request->filled('status')) {
        $query->where('status', $request->string('status'));
    }

    if ($request->filled('approval_status')) {
        $query->where('approval_status', $request->string('approval_status'));
    }

    $lessons = $query->paginate(15)->withQueryString();

    return view('admin.lessons', compact('lessons'));
}

public function lessonsShow(Lesson $lesson)
{
    $lesson->load(['topic.chapter.course', 'topic.chapter.course.teacher']);

    return view('admin.lesson_show', compact('lesson'));
}


public function lessonsModerate(Request $request, Lesson $lesson)
{
    $data = $request->validate([
        'title'            => ['required', 'string', 'max:255'],
        'description'      => ['nullable', 'string'],
        'summary_text'     => ['nullable', 'string'],
        'vocabulary_list'  => ['nullable', 'string'],
        'important_points' => ['nullable', 'string'],
        'approval_status'  => ['required', 'in:approved,rejected,pending'],
        'admin_note'       => ['nullable', 'string', 'max:2000'],
    ]);

    $lesson->title            = $data['title'];
    $lesson->description      = $data['description'] ?? null;
    $lesson->summary_text     = $data['summary_text'] ?? null;
    $lesson->vocabulary_list  = $data['vocabulary_list'] ?? null;
    $lesson->important_points = $data['important_points'] ?? null;
    $lesson->approval_status  = $data['approval_status'];
    $lesson->admin_note       = $data['admin_note'] ?? null;

    $lesson->last_moderated_by_admin_id = Auth::guard('admin')->id();
    $lesson->last_moderated_at          = now();

    $lesson->save();

    return back()->with('status', 'Lesson updated and moderation status saved.');
}


public function lessonsDestroy(Lesson $lesson)
{
    $lesson->delete();

    return redirect()
        ->route('admin.lessons')
        ->with('status', 'Lesson deleted.');
}

}
