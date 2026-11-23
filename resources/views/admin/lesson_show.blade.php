@extends('layouts.admin_lays')

@section('title', 'Lesson moderation | ' . $lesson->title)

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    @php
        $topic   = $lesson->topic;
        $chapter = $topic->chapter ?? null;
        $course  = $chapter->course ?? null;
        $teacher = $course->teacher ?? null;
    @endphp

    <div class="max-w-5xl mx-auto">
        {{-- Breadcrumb --}}
        <p class="text-xs text-gray-500">
            {{ $course->title ?? 'No course' }}
            @if ($chapter) · {{ $chapter->title }} @endif
            @if ($topic) · Topic: {{ $topic->title }} @endif
        </p>

        {{-- Header --}}
        <div class="mt-1 mb-4 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Lesson moderation
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Teacher: {{ $teacher->name ?? '—' }} · {{ $teacher->email ?? '' }}
                </p>
            </div>

            <a href="{{ route('admin.lessons') }}"
               class="inline-flex items-center rounded-full border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                ← Back to lessons
            </a>
        </div>

        {{-- Flash --}}
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Video preview (read-only) --}}
        @if ($lesson->video_type && ($lesson->video_path || $lesson->video_url))
            <div class="mb-5 rounded-lg border border-gray-200 bg-black/95 p-2">
                @if ($lesson->video_type === 'upload' && $lesson->video_path)
                    <video controls class="h-56 w-full rounded-md md:h-72">
                        <source src="{{ Storage::url($lesson->video_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="aspect-video w-full overflow-hidden rounded-md bg-black">
                        <iframe src="{{ $lesson->video_url }}"
                                class="h-full w-full border-0"
                                allowfullscreen
                                loading="lazy"></iframe>
                    </div>
                @endif
            </div>
        @endif

        {{-- Moderation form --}}
        <form method="POST" action="{{ route('admin.lessons.moderate', $lesson) }}" class="space-y-4">
            @csrf

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-900">Editable content</h2>

                <div class="mt-3 space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Title</label>
                        <input type="text" name="title" value="{{ old('title', $lesson->title) }}"
                               class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Short description</label>
                        <textarea name="description" rows="2"
                                  class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('description', $lesson->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Summary text</label>
                        <textarea name="summary_text" rows="3"
                                  class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('summary_text', $lesson->summary_text) }}</textarea>
                    </div>

                    <div class="grid gap-3 md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600">
                                Vocabulary list (one per line)
                            </label>
                            <textarea name="vocabulary_list" rows="4"
                                      class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('vocabulary_list', $lesson->vocabulary_list) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600">
                                Important points (one per line)
                            </label>
                            <textarea name="important_points" rows="4"
                                      class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('important_points', $lesson->important_points) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-900">Moderation decision</h2>

                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Approval status</label>
                        <select name="approval_status"
                                class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="pending" {{ $lesson->approval_status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $lesson->approval_status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $lesson->approval_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600">Admin note to teacher</label>
                        <textarea name="admin_note" rows="3"
                                  class="mt-1 w-full rounded-md border border-amber-200 bg-amber-50 px-2 py-1.5 text-sm text-amber-900 focus:border-amber-400 focus:outline-none focus:ring-1 focus:ring-amber-400"
                                  placeholder="Explain any changes or reasons, e.g. 'Please remove copyrighted images', 'Too advanced for Class 6'.">{{ old('admin_note', $lesson->admin_note) }}</textarea>
                    </div>
                </div>

                <p class="mt-2 text-xs text-gray-400">
                    @if ($lesson->last_moderated_at)
                        Last moderated {{ $lesson->last_moderated_at->diffForHumans() }}.
                    @else
                        Not moderated yet.
                    @endif
                </p>
            </div>

            <div class="flex items-center justify-between">
                <form method="POST"
                      action="{{ route('admin.lessons.destroy', $lesson) }}"
                      onsubmit="return confirm('Delete this lesson permanently?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="rounded-md bg-red-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-600">
                        Delete lesson
                    </button>
                </form>

                <button type="submit"
                        class="rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                    Save moderation
                </button>
            </div>
        </form>
    </div>
@endsection
