@extends('layouts.teacher_lays')

@section('title', 'Chapters | ' . $course->title)
@section('page_title', 'Chapters')

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <div class="t-page">
        {{-- Course header --}}
        <p class="text-2xl font-extrabold text-black">
            {{ $course->title }}
        </p>
        <p class="mt-1 text-sm text-gray-600">
            Class {{ $course->class_level }} · {{ $course->subject }} · {{ ucfirst($course->difficulty) }}
        </p>

        {{-- Flash message --}}
        @if (session('status'))
            <div class="t-alert mt-3">{{ session('status') }}</div>
        @endif

        {{-- Add new chapter form --}}
        <div class="mt-6 mb-4 rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <h2 class="mb-3 text-lg font-semibold text-gray-900">Add chapter</h2>

            <form method="POST" action="{{ route('teacher.courses.chapters.store', $course) }}" class="grid gap-3 md:grid-cols-4">
                @csrf

                <div class="md:col-span-2">
                    <label class="t-label">Title</label>
                    <input type="text" name="title" required class="t-input" placeholder="Chapter title">
                </div>

                <div>
                    <label class="t-label">Estimated time</label>
                    <input type="text" name="estimated_time" class="t-input" placeholder="e.g. 45 min">
                </div>

                <div>
                    <label class="t-label">Difficulty</label>
                    <select name="difficulty" class="t-input">
                        <option value="">Select</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <div class="md:col-span-4">
                    <label class="t-label">Description (optional)</label>
                    <textarea name="description" rows="2" class="t-input" placeholder="Short summary of this chapter"></textarea>
                </div>

                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="t-btn-primary">
                        Add chapter
                    </button>
                </div>
            </form>
        </div>

        {{-- Chapters list --}}
        @if ($course->chapters->isEmpty())
            <p class="t-empty mt-6">No chapters yet. Create your first chapter above.</p>
        @else
            <div class="mt-6 space-y-4">
                @foreach ($course->chapters as $chapter)
                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            {{-- Left: chapter info --}}
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-base font-semibold text-gray-900">
                                        {{ $chapter->position }}. {{ $chapter->title }}
                                    </h3>

                                    {{-- status badge --}}
                                    @php
                                        $isPublished = $chapter->status === 'published';
                                        $badgeClass = $isPublished
                                            ? 'bg-green-50 text-green-700 ring-green-100'
                                            : 'bg-gray-50 text-gray-600 ring-gray-100';
                                    @endphp
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $badgeClass }}">
                                        {{ ucfirst($chapter->status) }}
                                    </span>

                                    @if ($chapter->estimated_time)
                                        <span class="text-xs text-gray-500">
                                            · {{ $chapter->estimated_time }}
                                        </span>
                                    @endif

                                    @if ($chapter->difficulty)
                                        <span class="text-xs text-gray-500">
                                            · {{ ucfirst($chapter->difficulty) }}
                                        </span>
                                    @endif
                                </div>

                                @if ($chapter->description)
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ $chapter->description }}
                                    </p>
                                @endif
                            </div>

                            {{-- Right: chapter actions --}}
                            <div class="flex flex-wrap gap-2">
                                {{-- Reorder --}}
                                <form method="POST" action="{{ route('teacher.chapters.reorder', $chapter) }}">
                                    @csrf
                                    <input type="hidden" name="direction" value="up">
                                    <button type="submit" class="t-btn-secondary text-xs">
                                        ↑
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('teacher.chapters.reorder', $chapter) }}">
                                    @csrf
                                    <input type="hidden" name="direction" value="down">
                                    <button type="submit" class="t-btn-secondary text-xs">
                                        ↓
                                    </button>
                                </form>

                                {{-- Publish / Draft --}}
                                <form method="POST" action="{{ route('teacher.chapters.toggle-status', $chapter) }}">
                                    @csrf
                                    <button type="submit" class="t-btn-secondary">
                                        {{ $isPublished ? 'Move to draft' : 'Publish' }}
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('teacher.chapters.destroy', $chapter) }}"
                                      onsubmit="return confirm('Delete this chapter and its topics?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="t-btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Topics list --}}
                        <div class="mt-4 border-t border-gray-100 pt-3">
                            <h4 class="mb-2 text-sm font-semibold text-gray-800">
                                Topics in this chapter
                            </h4>

                            @if ($chapter->topics->isEmpty())
                                <p class="text-xs text-gray-500">No topics yet. Add one below.</p>
                            @else
                                <div class="space-y-2">
                                    @foreach ($chapter->topics as $topic)
                                        @php
                                            $topicPublished = $topic->status === 'published';
                                            $topicBadge = $topicPublished
                                                ? 'bg-green-50 text-green-700 ring-green-100'
                                                : 'bg-gray-50 text-gray-600 ring-gray-100';
                                        @endphp
                                        <div class="flex flex-col gap-2 rounded-md bg-gray-50 p-2 md:flex-row md:items-center md:justify-between">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $topic->position }}. {{ $topic->title }}
                                                    </span>
                                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-medium ring-1 ring-inset {{ $topicBadge }}">
                                                        {{ ucfirst($topic->status) }}
                                                    </span>
                                                </div>
                                                @if ($topic->content)
                                                    <p class="mt-0.5 text-xs text-gray-600 line-clamp-2">
                                                        {{ $topic->content }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="flex flex-wrap gap-2">
                                                {{-- Reorder topic --}}
                                                <form method="POST" action="{{ route('teacher.topics.reorder', $topic) }}">
                                                    @csrf
                                                    <input type="hidden" name="direction" value="up">
                                                    <button type="submit" class="t-btn-secondary text-xs">↑</button>
                                                </form>
                                                <form method="POST" action="{{ route('teacher.topics.reorder', $topic) }}">
                                                    @csrf
                                                    <input type="hidden" name="direction" value="down">
                                                    <button type="submit" class="t-btn-secondary text-xs">↓</button>
                                                </form>

                                                {{-- Simple edit: just status & title for now --}}
                                                <form method="POST" action="{{ route('teacher.topics.update', $topic) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="{{ $topicPublished ? 'draft' : 'published' }}">
                                                    <button type="submit" class="t-btn-secondary text-xs">
                                                        {{ $topicPublished ? 'Hide' : 'Publish' }}
                                                    </button>
                                                </form>

                                                {{-- Delete topic --}}
                                                <form method="POST" action="{{ route('teacher.topics.destroy', $topic) }}"
                                                      onsubmit="return confirm('Delete this topic?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="t-btn-danger text-xs">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Add topic form --}}
                            <form method="POST" action="{{ route('teacher.chapters.topics.store', $chapter) }}" class="mt-3 grid gap-2 md:grid-cols-4">
                                @csrf
                                <div class="md:col-span-2">
                                    <input type="text" name="title" required class="t-input" placeholder="New topic title">
                                </div>
                                <div class="md:col-span-2">
                                    <input type="text" name="video_url" class="t-input" placeholder="Video URL (optional)">
                                </div>
                                <div class="md:col-span-4">
                                    <textarea name="content" rows="2" class="t-input" placeholder="Short description / notes (optional)"></textarea>
                                </div>
                                <div class="md:col-span-4 flex justify-end">
                                    <button type="submit" class="t-btn-secondary">
                                        Add topic
                                    </button>
                                </div>
                            </form>

                            <a href="{{ route('teacher.topics.lessons.create', $topic) }}"
   class="t-btn-secondary text-xs">
    New lesson
</a>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
