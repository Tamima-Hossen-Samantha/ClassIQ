@extends('layouts.teacher_lays')

@section('title', 'Create Course | ClassIQ')
@section('page_title', 'Create course')

@section('content')
    <div class="t-page">
        <h2 class="mb-2">
            New course
        </h2>

        <div class="t-course-form-card">
            <form method="POST"
                  action="{{ route('teacher.courses.store') }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- Basic info --}}
                <div class="t-course-section">
                    <p class="t-course-section-title">Basic details</p>

                    <div class="mb-3">
                        <label class="t-field-label">Title</label>
                        <input type="text" name="title" class="t-input" value="{{ old('title') }}" required>
                    </div>

                    <div>
                        <label class="t-field-label">Description</label>
                        <textarea name="description" class="t-input" rows="3">{{ old('description') }}</textarea>
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
                                    <option value="{{ $i }}" @selected(old('class_level') == $i)>Class {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label class="t-field-label">Subject</label>
                            <input type="text" name="subject" class="t-input" value="{{ old('subject') }}" required>
                        </div>
                    </div>
                </div>

                {{-- Meta --}}
                <div class="t-course-section">
                    <p class="t-course-section-title">Course settings</p>

                    <div class="t-course-grid">
                        <div>
                            <label class="t-field-label">Difficulty</label>
                            <select name="difficulty" class="t-input">
                                <option value="easy">Easy</option>
                                <option value="medium" selected>Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>

                        <div>
                            <label class="t-field-label">Visibility</label>
                            <select name="visibility" class="t-input">
                                <option value="private">Only my learners</option>
                                <option value="school">School catalog</option>
                                <option value="public">Public (future)</option>
                            </select>
                        </div>
                    </div>

                    <div class="t-course-grid" style="margin-top:10px;">
                        <div>
                            <label class="t-field-label">
                                Estimated duration
                            </label>
                            <input type="text" name="estimated_duration" class="t-input"
                                   value="{{ old('estimated_duration') }}">
                            <p class="t-field-help">Example: 8 weeks or 20 hours.</p>
                        </div>

                        <div>
                            <label class="t-field-label">
                                Tags
                            </label>
                            <input type="text" name="tags" class="t-input" value="{{ old('tags') }}">
                            <p class="t-field-help">Example: Board Exam Prep, Algebra, Crash course.</p>
                        </div>
                    </div>

                    <div style="margin-top:10px;">
                        <label class="t-field-label">
                            Prerequisite note
                        </label>
                        <input type="text" name="prerequisite" class="t-input" value="{{ old('prerequisite') }}">
                    </div>
                </div>

                {{-- Media & status --}}
                <div class="t-course-section">
                    <p class="t-course-section-title">Thumbnail & status</p>

                    <div class="t-course-grid">
                        <div>
                            <label class="t-field-label">
                                Thumbnail image
                            </label>
                            <input type="file" name="thumbnail" class="t-input">
                            <p class="t-field-help">PNG or JPG, up to 2 MB.</p>
                        </div>

                        <div>
                            <label class="t-field-label">
                                Status
                            </label>
                            <select name="status" class="t-input">
                                <option value="draft">Save as draft</option>
                                <option value="published">Publish now</option>
                            </select>
                            <p class="t-field-help">
                                Published courses are visible to learners and get a class code.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="t-course-actions">
                    <button type="submit" class="t-btn-primary">
                        Save course
                    </button>

                    <a href="{{ route('teacher.courses') }}" class="t-btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
