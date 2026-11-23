@extends('layouts.teacher_lays')

@section('title', 'Edit Course | ClassIQ')
@section('page_title', 'Edit course')

@section('content')
    <div class="t-page">
        <h2 class="text-2xl font-semibold text-gray-900 mb-4">
            Edit course
        </h2>

        @if (session('status'))
            <div class="t-alert mb-3">{{ session('status') }}</div>
        @endif

        <form method="POST"
              action="{{ route('teacher.courses.update', $course) }}"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Basic details --}}
            <div class="t-course-section">
                <p class="t-course-section-title">Basic details</p>

                <div class="mb-3">
                    <label class="t-field-label">Title</label>
                    <input type="text" name="title" class="t-input"
                           value="{{ old('title', $course->title) }}" required>
                </div>

                <div>
                    <label class="t-field-label">Description</label>
                    <textarea name="description" class="t-input" rows="3">{{ old('description', $course->description) }}</textarea>
                </div>
            </div>

            {{-- Class & subject --}}
            <div class="t-course-section">
                <p class="t-course-section-title">Class and subject</p>

                <div class="t-course-grid">
                    <div>
                        <label class="t-field-label">Class level</label>
                        <select name="class_level" class="t-input" required>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}"
                                    @selected(old('class_level', $course->class_level) == $i)>
                                    Class {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="t-field-label">Subject</label>
                        <input type="text" name="subject" class="t-input"
                               value="{{ old('subject', $course->subject) }}" required>
                    </div>
                </div>
            </div>

            {{-- Settings --}}
            <div class="t-course-section">
                <p class="t-course-section-title">Course settings</p>

                <div class="t-course-grid">
                    <div>
                        <label class="t-field-label">Difficulty</label>
                        <select name="difficulty" class="t-input">
                            @foreach (['easy' => 'Easy', 'medium' => 'Medium', 'hard' => 'Hard'] as $value => $label)
                                <option value="{{ $value }}"
                                    @selected(old('difficulty', $course->difficulty) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="t-field-label">Visibility</label>
                        <select name="visibility" class="t-input">
                            @foreach (['private' => 'Only my learners', 'school' => 'School catalog', 'public' => 'Public'] as $value => $label)
                                <option value="{{ $value }}"
                                    @selected(old('visibility', $course->visibility) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="t-course-grid" style="margin-top:10px;">
                    <div>
                        <label class="t-field-label">Estimated duration</label>
                        <input type="text" name="estimated_duration" class="t-input"
                               value="{{ old('estimated_duration', $course->estimated_duration) }}">
                        <p class="t-field-help">Example: 8 weeks or 20 hours.</p>
                    </div>

                    <div>
                        <label class="t-field-label">Tags</label>
                        <input type="text" name="tags" class="t-input"
                               value="{{ old('tags', $course->tags) }}">
                        <p class="t-field-help">Example: Board Exam Prep, Algebra, Crash course.</p>
                    </div>
                </div>

                <div style="margin-top:10px;">
                    <label class="t-field-label">Prerequisite note</label>
                    <input type="text" name="prerequisite" class="t-input"
                           value="{{ old('prerequisite', $course->prerequisite) }}">
                </div>
            </div>

            {{-- Thumbnail & status --}}
            <div class="t-course-section">
                <p class="t-course-section-title">Thumbnail & status</p>

                <div class="t-course-grid">
                    <div>
                        <label class="t-field-label">Thumbnail image</label>
                        <input type="file" name="thumbnail" class="t-input">
                        @if ($course->thumbnail_path)
                            <p class="t-field-help">
                                Current: <a href="{{ asset('storage/'.$course->thumbnail_path) }}" target="_blank" class="t-btn-link">
                                    View thumbnail
                                </a>
                            </p>
                        @else
                            <p class="t-field-help">No thumbnail uploaded yet.</p>
                        @endif
                    </div>

                    <div>
                        <label class="t-field-label">Status</label>
                        <select name="status" class="t-input">
                            <option value="draft" @selected(old('status', $course->status) === 'draft')>
                                Draft (not visible to learners)
                            </option>
                            <option value="published" @selected(old('status', $course->status) === 'published')>
                                Published (visible & gets class code)
                            </option>
                        </select>
                        <p class="t-field-help">
                            When set to published, the course will auto‑generate a class code if it doesn’t have one.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="t-course-actions">
                <button type="submit" class="t-btn-primary">
                    Save changes
                </button>

                <a href="{{ route('teacher.courses') }}" class="t-btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
