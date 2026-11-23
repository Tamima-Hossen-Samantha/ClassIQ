@extends('layouts.teacher_lays')

@section('title', 'Create lesson | ' . $course->title)
@section('page_title', 'Create lesson')

@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')
    <div class="t-page">
        {{-- Header --}}
        <div class="mb-4">
            <p class="text-xs text-slate-500">
                {{ $course->title }} · {{ $topic->chapter->title }} · Topic: {{ $topic->title }}
            </p>
            <h1 class="text-xl font-semibold text-slate-900">
                New lesson
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Upload video, notes, worksheet, and add a simple summary for learners.
            </p>
        </div>

        {{-- Flash --}}
        @if (session('status'))
            <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-800">
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

        <form method="POST"
              action="{{ route('teacher.topics.lessons.store', $topic) }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            {{-- Basic info --}}
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Lesson basics</h2>
                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="t-label">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="t-input" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="t-label">Short description (optional)</label>
                        <textarea name="description" rows="2" class="t-input"
                                  placeholder="What will students learn in this lesson?">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Video section --}}
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Video</h2>
                <p class="mt-1 text-xs text-slate-500">
                    Choose either upload or paste a YouTube link.
                </p>

                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    <div>
                        <label class="t-label">Video source</label>
                        <select name="video_type" class="t-input">
                            <option value="">No video</option>
                            <option value="upload" {{ old('video_type') === 'upload' ? 'selected' : '' }}>Upload mp4</option>
                            <option value="youtube" {{ old('video_type') === 'youtube' ? 'selected' : '' }}>YouTube link</option>
                            <option value="external" {{ old('video_type') === 'external' ? 'selected' : '' }}>Other link</option>
                        </select>
                    </div>

                    <div>
                        <label class="t-label">Video file (mp4)</label>
                        <input type="file" name="video_file" accept="video/mp4"
                               class="t-input-file">
                    </div>

                    <div class="md:col-span-2">
                        <label class="t-label">Video URL (YouTube or external)</label>
                        <input type="url" name="video_url" value="{{ old('video_url') }}"
                               class="t-input" placeholder="https://youtube.com/...">
                    </div>
                </div>
            </div>

            {{-- Files: notes, worksheet, slides --}}
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Notes & resources</h2>

                <div class="mt-3 grid gap-3 md:grid-cols-3">
                    <div>
                        <label class="t-label">Notes (PDF / DOC)</label>
                        <input type="file" name="notes_file"
                               class="t-input-file"
                               accept=".pdf,.doc,.docx">
                    </div>
                    <div>
                        <label class="t-label">Worksheet (PDF)</label>
                        <input type="file" name="worksheet_file"
                               class="t-input-file"
                               accept=".pdf">
                    </div>
                    <div>
                        <label class="t-label">Slides (PPT / PDF)</label>
                        <input type="file" name="slides_file"
                               class="t-input-file"
                               accept=".pdf,.ppt,.pptx">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="t-label">Images / diagrams (you can select multiple)</label>
                    <input type="file" name="images[]" multiple
                           class="t-input-file"
                           accept="image/*">
                </div>
            </div>

            {{-- Text content --}}
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Lesson text</h2>

                <div class="mt-3 space-y-3">
                    <div>
                        <label class="t-label">Summary (simple explanation)</label>
                        <textarea name="summary_text" rows="3" class="t-input"
                                  placeholder="Explain the core idea in simple words.">{{ old('summary_text') }}</textarea>
                    </div>

                    <div>
                        <label class="t-label">Vocabulary list (one per line)</label>
                        <textarea name="vocabulary_list" rows="3" class="t-input"
                                  placeholder="atom – the smallest unit of matter&#10;molecule – a group of atoms">{{ old('vocabulary_list') }}</textarea>
                    </div>

                    <div>
                        <label class="t-label">Important points (one per line)</label>
                        <textarea name="important_points" rows="3" class="t-input"
                                  placeholder="Use short bullet-style lines for key ideas.">{{ old('important_points') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Settings --}}
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-900">Settings</h2>

                <div class="mt-3 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <label class="t-label">Lesson status</label>
                        <select name="status" class="t-input w-40">
                            <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Save as draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publish</option>
                        </select>
                        <p class="mt-1 text-xs text-slate-500">
                            Admin will still review before learners see the lesson.
                        </p>
                    </div>

                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="is_autism_friendly" value="1"
                               {{ old('is_autism_friendly') ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs text-slate-700">
                            Mark as autism‑friendly (calm visuals, simple text).
                        </span>
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end gap-2">
                <a href="{{ url()->previous() }}"
                   class="rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">
                    Cancel
                </a>

                <button type="submit"
                        class="rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    Save lesson
                </button>
            </div>
        </form>
    </div>
@endsection
