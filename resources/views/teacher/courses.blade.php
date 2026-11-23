@extends('layouts.teacher_lays')

@section('title', 'My Courses | ClassIQ')
@section('page_title', 'Courses')

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <div class="t-page">
        {{-- Welcome text --}}
        <p class="text-3xl font-extrabold text-black">
            Welcome back, {{ auth('teacher')->user()->name }}!
        </p>

        {{-- Flash message --}}
        @if (session('status'))
            <div class="t-alert mt-3">{{ session('status') }}</div>
        @endif

        {{-- Header row: title + button --}}
        <div class="flex items-center justify-between mt-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900">
                My courses
            </h2>

            <a href="{{ route('teacher.courses.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-500 focus-visible:ring-offset-2">
                <span class="text-lg leading-none">+</span>
                <span>New course</span>
            </a>
        </div>

        {{-- Courses list --}}
        @if ($courses->isEmpty())
            <p class="t-empty">No courses yet. Create your first course.</p>
        @else
            <div class="t-courses-grid">
                @foreach ($courses as $course)
                    <div class="t-course-card">
                        <div class="t-course-main">
                            <h3 class="t-course-title">{{ $course->title }}</h3>

                            <p class="t-course-meta">
                                Class {{ $course->class_level }} · {{ $course->subject }} ·
                                {{ ucfirst($course->difficulty) }}
                            </p>

                            <p class="t-course-meta-small">
                                Status:
                                <span class="font-semibold">
                                    {{ ucfirst($course->status) }}
                                </span>

                                @if ($course->status === 'published' && $course->join_code)
                                    · Class code:
                                    <code class="t-course-code">{{ $course->join_code }}</code>
                                @endif
                            </p>

                            {{-- Admin/homepage status --}}
                            <p class="t-course-meta-small">
                                Homepage status:
                                <span class="font-semibold">
                                    {{ ucfirst($course->admin_status ?? 'draft') }}
                                </span>
                            </p>

                            @if ($course->estimated_duration)
                                <p class="t-course-meta-small">
                                    Duration: {{ $course->estimated_duration }}
                                </p>
                            @endif

                            @if ($course->tags)
                                <p class="t-course-meta-small">
                                    Tags: {{ $course->tags }}
                                </p>
                            @endif
                        </div>

                        <div class="t-course-actions">
                            {{-- publish / unpublish --}}
                            <form method="POST" action="{{ route('teacher.courses.toggle-status', $course) }}">
                                @csrf
                                <button type="submit" class="t-btn-secondary">
                                    {{ $course->status === 'published' ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>

                            {{-- regenerate code --}}
                            @if ($course->status === 'published')
                                <form method="POST" action="{{ route('teacher.courses.regenerate-code', $course) }}">
                                    @csrf
                                    <button type="submit" class="t-btn-secondary">
                                        New code
                                    </button>
                                </form>
                            @endif

                            {{-- request homepage publish (only if not approved) --}}
                            @if ($course->admin_status !== 'approved')
                                <form method="POST" action="{{ route('teacher.courses.request-publish', $course) }}">
                                    @csrf
                                    <button type="submit" class="t-btn-secondary">
                                        Request Homepage Publish
                                    </button>
                                </form>
                            @endif

                            {{-- edit --}}
                            <a href="{{ route('teacher.courses.edit', $course) }}" class="t-btn-link">
                                Edit
                            </a>

                            {{-- manage chapters --}}
<a href="{{ route('teacher.courses.chapters.index', $course) }}" class="t-btn-link">
    Chapters
</a>

                            {{-- delete --}}
                            <form method="POST"
                                  action="{{ route('teacher.courses.destroy', $course) }}"
                                  onsubmit="return confirm('Delete this course?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="t-btn-danger">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
