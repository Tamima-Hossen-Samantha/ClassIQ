<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Topic;
use App\Models\TopicUserProgress;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Lesson;
use App\Models\Doubt;



class LearnerDashboardController extends Controller
{
   public function dashboard(Request $request)
{
    $learner = $request->user('learner');

    // All courses this learner has joined (published)
    $courses = $learner->courses()
        ->with('teacher')
        ->where('status', 'published')
        ->orderBy('title')
        ->get();

    // Today’s learning suggestion
    $suggestedCourse = $courses->first();
    $suggestedLesson = null;

    if ($suggestedCourse) {
        $suggestedLesson = Lesson::whereHas('topic.chapter', function ($q) use ($suggestedCourse) {
                $q->where('course_id', $suggestedCourse->id);
            })
            ->orderBy('created_at')
            ->first();
    }

    // Recently viewed lessons (for now: latest lessons from joined courses)
    $recentLessons = Lesson::whereHas('topic.chapter.course.learners', function ($q) use ($learner) {
            $q->where('learners.id', $learner->id);
        })
        ->with(['topic.chapter.course'])
        ->latest()
        ->take(5)
        ->get();

    // Pending quizzes placeholders
    $pendingQuizzes      = collect();
    $pendingQuizzesCount = 0;

    // Progress overview
    $totalJoinedCourses = $courses->count();
    $completedCourses   = 0;

    $courseProgress = [
        'joined'    => $totalJoinedCourses,
        'completed' => $completedCourses,
    ];

    // Daily streak placeholders
    $currentStreak = 0;
    $bestStreak    = 0;

    return view('learner.dashboard', compact(
        'learner',
        'courses',
        'suggestedCourse',
        'suggestedLesson',
        'recentLessons',
        'pendingQuizzes',
        'pendingQuizzesCount',
        'courseProgress',
        'currentStreak',
        'bestStreak'
    ));
}

public function markComplete(Request $request, Topic $topic)
{
    $learner = $request->user('learner'); // or Auth::guard('learner')->user()

    TopicUserProgress::updateOrCreate(
        [
            'topic_id'   => $topic->id,
            'learner_id' => $learner->id,
        ],
        [
            'status'       => 'completed',
            'completed_at' => Carbon::now(),
        ]
    );

    return back()->with('status', 'Marked as completed.');
}

    public function classes(Request $request)
    {
        $learner = $request->user('learner');

        // all courses this learner has joined
        $courses = $learner->courses()
            ->with('teacher')
            ->orderByDesc('pivot_joined_at')
            ->get();

        return view('learner.classes', compact('courses'));
    }

    public function subjects()
    {
        return view('learner.subjects');
    }

    public function lessons()
    {
        return view('learner.lessons');
    }

    public function quizzes()
    {
        return view('learner.quizzes');
    }

    public function progress()
    {
        return view('learner.progress');
    }

    public function accessibility()
    {
        return view('learner.accessibility');
    }

    public function ai()
    {
        return view('learner.ai');
    }

    public function notifications()
    {
        return view('learner.notifications');
    }

    public function profile()
    {
        return view('learner.profile');
    }

    /* ===== Join course by class code (inside My Class page) ===== */

    public function joinCourse(Request $request)
    {
        $learner = $request->user('learner');

        // 1) Validate input
        $data = $request->validate([
            'join_code' => ['required', 'string'],
        ]);

        $code = strtoupper(trim($data['join_code']));

        // 2) Find a published + joinable + not expired course with this code
        $course = Course::where('join_code', $code)
            ->where('status', 'published')
            ->where('is_joinable', true)
            ->where(function ($q) {
                $q->whereNull('join_code_expires_at')
                  ->orWhere('join_code_expires_at', '>', now());
            })
            ->first();

        if (! $course) {
            return back()
                ->withErrors(['join_code' => 'Invalid or inactive class code.'])
                ->withInput();
        }

        // 3) If not already enrolled, attach in pivot
        $alreadyEnrolled = $learner->courses()
            ->where('course_id', $course->id)
            ->exists();

        if (! $alreadyEnrolled) {
            $learner->courses()->attach($course->id, [
                'joined_at' => now(),
            ]);

            $course->increment('enrolled_learners_count');
        }

        // 4) Redirect back to My Class page with status
        return redirect()
            ->route('learner.classes')
            ->with('status', 'You joined the course successfully.');
    }




public function courseChapters(Request $request, Course $course)
{
   
    $learner = $request->user('learner');

 
    if (! $learner->courses->contains($course->id)) {
        abort(403, 'You are not enrolled in this course.');
    }

    
   $course->load([
    'chapters' => function ($q) {
        $q->where('status', 'published')
          ->orderBy('position');
    },
    'chapters.topics' => function ($q) {
        $q->where('status', 'published')
          ->orderBy('position');
    },
    'chapters.topics.lessons', // add this line
]);


    

    // 4) Send data to a learner-facing view
    return view('learner.course_chapters', compact('course', 'learner'));
}





public function storeDoubt(Request $request, Lesson $lesson)
{
    $learner = $request->user('learner');
    $lesson->load(['topic.chapter.course.teacher']);
    $course  = $lesson->topic->chapter->course;

    // Ensure learner is in this course
    if (! $learner->courses->contains($course->id)) {
        abort(403);
    }

    $data = $request->validate([
        'question' => ['required', 'string', 'max:2000'],
    ]);

    Doubt::create([
        'lesson_id'  => $lesson->id,
        'learner_id' => $learner->id,
        'teacher_id' => $course->teacher_id,
        'question'   => $data['question'],
        'status'     => 'open',
    ]);

    return back()->with('status', 'Your question was sent to the teacher.');
}



public function showLesson(Request $request, Lesson $lesson)
{
    $learner = $request->user('learner');

    $lesson->load(['topic.chapter.course', 'doubts.learner']);

    $course  = $lesson->topic->chapter->course;
    $topic   = $lesson->topic;
    $chapter = $lesson->topic->chapter;

    if (! $learner->courses->contains($course->id)) {
        abort(403);
    }

    // (Optionally re‑enable published+approved check here)

    $doubts = $lesson->doubts()
        ->where('learner_id', $learner->id)
        ->latest()
        ->take(5)
        ->get();

    return view('learner.lesson_show', compact(
        'lesson', 'course', 'topic', 'chapter', 'learner', 'doubts'
    ));
}

public function accessibilitySettings(Request $request)
{
    $learner = $request->user('learner');

    return view('learner.accessibility', [
        'learner' => $learner,
        'settings' => $learner->accessibility_settings ?? [],
    ]);
}

public function accessibilityUpdate(Request $request)
{
    $learner = $request->user('learner');

    $data = $request->validate([
        'calm_mode'       => ['nullable', 'boolean'],
        'dyslexia_font'   => ['nullable', 'boolean'],
        'large_text'      => ['nullable', 'boolean'],
        'reduced_motion'  => ['nullable', 'boolean'],
        'low_distraction' => ['nullable', 'boolean'],
    ]);

    $settings = [
        'calm_mode'       => $request->boolean('calm_mode'),
        'dyslexia_font'   => $request->boolean('dyslexia_font'),
        'large_text'      => $request->boolean('large_text'),
        'reduced_motion'  => $request->boolean('reduced_motion'),
        'low_distraction' => $request->boolean('low_distraction'),
    ];

    $learner->accessibility_settings = $settings;
    $learner->save();

    return back()->with('status', 'Accessibility preferences saved.');
}


}
