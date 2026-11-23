<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Models\Chapter;
use App\Models\Topic;

use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use App\Models\Doubt;

use App\Models\Learner;

use Illuminate\Support\Facades\DB;



class TeacherDashboardController extends Controller
{
    
public function index(Request $request)
{
    $teacher = $request->user('teacher');

    // Total courses uploaded by this teacher
    $totalCourses = Course::where('teacher_id', $teacher->id)->count();

    // Total unique students across all their published courses
    $totalStudents = Learner::whereHas('courses', function ($q) use ($teacher) {
        $q->where('teacher_id', $teacher->id)
          ->where('status', 'published');
    })->distinct()->count('learners.id');

    // Total lessons in this teacher's courses
    $totalLessons = Lesson::whereHas('course', function ($q) use ($teacher) {
        $q->where('teacher_id', $teacher->id);
    })->count();

    // Simple quick stats: published vs draft courses
    $publishedCourses = Course::where('teacher_id', $teacher->id)
        ->where('status', 'published')
        ->count();

    $draftCourses = Course::where('teacher_id', $teacher->id)
        ->where('status', 'draft')
        ->count();

    // Recent lessons uploaded
    $recentLessons = Lesson::whereHas('course', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
        ->with(['topic.chapter.course'])
        ->latest()
        ->take(5)
        ->get();

    // Notifications (if you don't have a model yet, set to collect())
    $notifications = collect();

    // Upcoming tasks: for now, show open doubts (unanswered questions) as tasks
    $openDoubts = Doubt::where('teacher_id', $teacher->id)
        ->whereNull('teacher_reply')
        ->with(['lesson.topic.chapter.course', 'learner'])
        ->latest()
        ->take(5)
        ->get();

    // Student performance alerts: placeholder list (later use quiz scores)
    $performanceAlerts = collect();

    return view('teacher.dashboard', compact(
        'teacher',
        'totalCourses',
        'totalStudents',
        'totalLessons',
        'publishedCourses',
        'draftCourses',
        'recentLessons',
        'notifications',
        'openDoubts',
        'performanceAlerts'
    ));
}

    // COURSES: list
    public function coursesIndex(Request $request)
    {
        $teacher = $request->user('teacher');

        $courses = $teacher->courses()
            ->orderByDesc('updated_at')
            ->get();

        
        return view('teacher.courses', compact('courses'));
    }

    // COURSES: show create form
    public function coursesCreate()
    {
        
        return view('teacher.courses_create');
    }

    // helper: generate unique join code
    protected function generateJoinCode(): string
    {
        do {
            $code = Str::upper(Str::random(7));
        } while (Course::where('join_code', $code)->exists());

        return $code;
    }

    // COURSES: store new course
    public function coursesStore(Request $request)
    {
        $teacher = $request->user('teacher');

        $data = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'class_level'        => ['required', 'integer', 'between:1,12'],
            'subject'            => ['required', 'string', 'max:100'],
            'difficulty'         => ['required', 'in:easy,medium,hard'],
            'visibility'         => ['required', 'in:private,school,public'],
            'estimated_duration' => ['nullable', 'string', 'max:50'],
            'tags'               => ['nullable', 'string', 'max:255'],
            'prerequisite'       => ['nullable', 'string', 'max:255'],
            'status'             => ['required', 'in:draft,published'],
            'thumbnail'          => ['nullable', 'image', 'max:2048'],
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('course_thumbnails', 'public');
        }

        $course = new Course($data);
        $course->teacher_id = $teacher->id;
        $course->thumbnail_path = $thumbnailPath;

        if ($course->status === 'published') {
            $course->join_code = $this->generateJoinCode();
            $course->join_code_expires_at = null;
        }

        $course->save();

        return redirect()->route('teacher.courses')
            ->with('status', 'Course created successfully.');
    }

    // COURSES: edit form
    public function coursesEdit(Course $course)
    {
        $this->authorizeCourse($course);

        return view('teacher.courses_edit', compact('course'));
       
    }

    // COURSES: update
    public function coursesUpdate(Request $request, Course $course)
    {
        $this->authorizeCourse($course);

        $data = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string'],
            'class_level'        => ['required', 'integer', 'between:1,12'],
            'subject'            => ['required', 'string', 'max:100'],
            'difficulty'         => ['required', 'in:easy,medium,hard'],
            'visibility'         => ['required', 'in:private,school,public'],
            'estimated_duration' => ['nullable', 'string', 'max:50'],
            'tags'               => ['nullable', 'string', 'max:255'],
            'prerequisite'       => ['nullable', 'string', 'max:255'],
            'status'             => ['required', 'in:draft,published'],
            'thumbnail'          => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('course_thumbnails', 'public');
        }

        if ($course->status === 'draft' && $data['status'] === 'published' && ! $course->join_code) {
            $data['join_code'] = $this->generateJoinCode();
            $data['join_code_expires_at'] = null;
        }

        $course->update($data);

        return redirect()->route('teacher.courses')
            ->with('status', 'Course updated.');
    }

    // COURSES: delete
    public function coursesDestroy(Course $course)
    {
        $this->authorizeCourse($course);

        $course->delete();

        return redirect()->route('teacher.courses')
            ->with('status', 'Course deleted.');
    }

    // COURSES: publish / unpublish
    public function coursesToggleStatus(Course $course)
    {
        $this->authorizeCourse($course);

        if ($course->status === 'published') {
            $course->status = 'draft';
            $course->is_joinable = false;
        } else {
            $course->status = 'published';
            $course->is_joinable = true;
            if (! $course->join_code) {
                $course->join_code = $this->generateJoinCode();
            }
        }

        $course->save();

        return back()->with('status', 'Course status updated.');
    }

    // COURSES: regenerate join code
    public function coursesRegenerateCode(Course $course)
    {
        $this->authorizeCourse($course);

        $course->join_code = $this->generateJoinCode();
        $course->join_code_expires_at = null;
        $course->save();

        return back()->with('status', 'New class code generated.');
    }

    protected function authorizeCourse(Course $course): void
    {
        if (auth('teacher')->id() !== $course->teacher_id) {
            abort(403);
        }
    }

    //  teacher requests homepage/catalog publish
public function coursesRequestPublish(Course $course)
{
   
    $this->authorizeCourse($course);

    
    if ($course->admin_status === 'approved') {
        return back()->with('status', 'Course is already approved for homepage/catalog.');
    }

   
    $course->admin_status = 'pending';
    $course->save();

    return back()->with('status', 'Publish request sent to admin.');
}

// chapters

    
public function chaptersIndex(Request $request, Course $course)
{
    $this->authorizeCourse($course);

    $course->load(['chapters.topics']);

    return view('teacher.chapters', compact('course'));
}

// add
public function chaptersStore(Request $request, Course $course)
{
    $this->authorizeCourse($course);

    $data = $request->validate([
        'title'          => ['required', 'string', 'max:255'],
        'description'    => ['nullable', 'string'],
        'estimated_time' => ['nullable', 'string', 'max:50'],
        'difficulty'     => ['nullable', 'in:easy,medium,hard'],
    ]);

    $maxPosition = $course->chapters()->max('position') ?? 0;

    $chapter = new Chapter($data);
    $chapter->course_id = $course->id;
    $chapter->position = $maxPosition + 1;
    $chapter->status = 'draft';

    $chapter->save();

    return back()->with('status', 'Chapter added.');
}

// CHAPTERS: reorder (up/down)
public function chaptersReorder(Request $request, Chapter $chapter)
{
    $this->authorizeCourse($chapter->course);

    $data = $request->validate([
        'direction' => ['required', 'in:up,down'],
    ]);

    $course = $chapter->course;

    if ($data['direction'] === 'up') {
        $swap = $course->chapters()
            ->where('position', '<', $chapter->position)
            ->orderByDesc('position')
            ->first();
    } else {
        $swap = $course->chapters()
            ->where('position', '>', $chapter->position)
            ->orderBy('position')
            ->first();
    }

    if ($swap) {
        $current = $chapter->position;
        $chapter->position = $swap->position;
        $swap->position = $current;
        $chapter->save();
        $swap->save();
    }

    return back()->with('status', 'Chapter order updated.');
}

// CHAPTERS: toggle draft/published
public function chaptersToggleStatus(Chapter $chapter)
{
    $this->authorizeCourse($chapter->course);

    $chapter->status = $chapter->status === 'published' ? 'draft' : 'published';
    $chapter->save();

    return back()->with('status', 'Chapter status updated.');
}

// CHAPTERS: delete
public function chaptersDestroy(Chapter $chapter)
{
    $this->authorizeCourse($chapter->course);

    $chapter->delete(); // topics cascade

    return back()->with('status', 'Chapter deleted.');
}

// TOPICS: add
public function topicsStore(Request $request, Chapter $chapter)
{
    $this->authorizeCourse($chapter->course);

    $data = $request->validate([
        'title'        => ['required', 'string', 'max:255'],
        'content'      => ['nullable', 'string'],
        'video_url'    => ['nullable', 'url'],
        'resource_url' => ['nullable', 'url'],
    ]);

    $maxPosition = $chapter->topics()->max('position') ?? 0;

    $topic = new Topic($data);
    $topic->chapter_id = $chapter->id;
    $topic->position = $maxPosition + 1;
    $topic->status = 'draft';

    $topic->save();

    return back()->with('status', 'Topic added.');
}

// TOPICS: reorder
public function topicsReorder(Request $request, Topic $topic)
{
    $this->authorizeCourse($topic->chapter->course);

    $data = $request->validate([
        'direction' => ['required', 'in:up,down'],
    ]);

    $chapter = $topic->chapter;

    if ($data['direction'] === 'up') {
        $swap = $chapter->topics()
            ->where('position', '<', $topic->position)
            ->orderByDesc('position')
            ->first();
    } else {
        $swap = $chapter->topics()
            ->where('position', '>', $topic->position)
            ->orderBy('position')
            ->first();
    }

    if ($swap) {
        $current = $topic->position;
        $topic->position = $swap->position;
        $swap->position = $current;
        $topic->save();
        $swap->save();
    }

    return back()->with('status', 'Topic order updated.');
}

public function topicsUpdate(Request $request, Topic $topic)
{
    $this->authorizeCourse($topic->chapter->course);

    $data = $request->validate([
        'status' => ['required', 'in:draft,published'],
    ]);

    $topic->update($data);

    return back()->with('status', 'Topic updated.');
}

// TOPICS: delete
public function topicsDestroy(Topic $topic)
{
    $this->authorizeCourse($topic->chapter->course);

    $topic->delete();

    return back()->with('status', 'Topic deleted.');
}


// lessons

public function lessonsIndex(Request $request)
{
    $teacher = $request->user('teacher');

    $lessons = Lesson::query()
        ->whereHas('course', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
        ->with(['topic.chapter.course'])
        ->orderByDesc('created_at')
        ->paginate(12);

    return view('teacher.lessons', compact('lessons'));
}

public function lessonCreate(Request $request, Topic $topic)
{
    $course = $topic->chapter->course;

    $this->authorizeCourse($course); // ensure this teacher owns the course

    return view('teacher.lessons_create', compact('topic', 'course'));
}

public function lessonStore(Request $request, Topic $topic)
{
    $course = $topic->chapter->course;
    $this->authorizeCourse($course);

    $data = $request->validate([
        'title'            => ['required', 'string', 'max:255'],
        'description'      => ['nullable', 'string'],

        // video
        'video_type'       => ['nullable', 'in:upload,youtube,external'],
        'video_file'       => ['nullable', 'file', 'mimetypes:video/mp4', 'max:512000'], // ~500 MB
        'video_url'        => ['nullable', 'url'],

        // files
        'notes_file'       => ['nullable', 'file', 'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:51200'],
        'worksheet_file'   => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
        'slides_file'      => ['nullable', 'file', 'mimetypes:application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation', 'max:102400'],

        // images (multiple)
        'images.*'         => ['nullable', 'image', 'max:10240'],

        // structured text
        'summary_text'     => ['nullable', 'string'],
        'vocabulary_list'  => ['nullable', 'string'],
        'important_points' => ['nullable', 'string'],

        // status
        'status'           => ['required', 'in:draft,published'],

        // accessibility
        'is_autism_friendly' => ['nullable', 'boolean'],
    ]);

    $lesson = new Lesson();
    $lesson->topic_id = $topic->id;
    $lesson->title = $data['title'];
    $lesson->description = $data['description'] ?? null;
    $lesson->summary_text = $data['summary_text'] ?? null;
    $lesson->vocabulary_list = $data['vocabulary_list'] ?? null;
    $lesson->important_points = $data['important_points'] ?? null;
    $lesson->status = $data['status'];
    $lesson->approval_status = 'pending'; // require admin review
    $lesson->is_autism_friendly = $request->boolean('is_autism_friendly');

    // Video handling
    $lesson->video_type = $data['video_type'] ?? null;

    if (($data['video_type'] ?? null) === 'upload' && $request->hasFile('video_file')) {
        $path = $request->file('video_file')->store('lesson_videos', 'public');
        $lesson->video_path = $path;
        $lesson->video_url = null;
    } elseif (in_array($data['video_type'] ?? null, ['youtube', 'external']) && ! empty($data['video_url'])) {
        $lesson->video_url = $data['video_url'];
        $lesson->video_path = null;
    }

    // Notes
    if ($request->hasFile('notes_file')) {
        $lesson->notes_path = $request->file('notes_file')->store('lesson_notes', 'public');
    }

    // Worksheet
    if ($request->hasFile('worksheet_file')) {
        $lesson->worksheet_path = $request->file('worksheet_file')->store('lesson_worksheets', 'public');
    }

    // Slides
    if ($request->hasFile('slides_file')) {
        $lesson->slides_path = $request->file('slides_file')->store('lesson_slides', 'public');
    }

    // Images
    if ($request->hasFile('images')) {
        $paths = [];
        foreach ($request->file('images') as $image) {
            $paths[] = $image->store('lesson_images', 'public');
        }
        $lesson->image_paths = $paths;
    }

    $lesson->save();

    return redirect()
        ->route('teacher.topics.lessons.create', $topic)
        ->with('status', 'Lesson saved as ' . $lesson->status . ' and sent for admin review.');
}



public function doubtsIndex(Request $request)
{
    $teacher = $request->user('teacher');

    $doubts = Doubt::where('teacher_id', $teacher->id)
        ->with(['lesson.topic.chapter.course', 'learner'])
        ->orderByDesc('created_at')
        ->paginate(15);

    return view('teacher.doubts', compact('doubts'));
}

public function doubtsReply(Request $request, Doubt $doubt)
{
    $teacher = $request->user('teacher');

    if ($doubt->teacher_id !== $teacher->id) {
        abort(403);
    }

    $data = $request->validate([
        'teacher_reply' => ['required', 'string', 'max:2000'],
    ]);

    $doubt->teacher_reply = $data['teacher_reply'];
    $doubt->status = 'answered';
    $doubt->save();

    return back()->with('status', 'Reply sent to learner.');
}


}
