@extends('layouts.admin_lays')

@section('title', 'Chapters | ' . $course->title)

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <div class="max-w-6xl mx-auto">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">
                Chapter & topic moderation
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $course->title }} · Class {{ $course->class_level }} · {{ $course->subject }}
                — Teacher: {{ optional($course->teacher)->name }}
            </p>
        </div>

        {{-- Flash --}}
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($course->chapters->isEmpty())
            <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center text-sm text-gray-600">
                No chapters created by teacher yet.
            </div>
        @else
            <div class="space-y-4">
                @foreach ($course->chapters as $chapter)
                    <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                        {{-- Chapter moderation form --}}
                        <form method="POST" action="{{ route('admin.chapters.moderate', $chapter) }}" class="space-y-2">
                            @csrf

                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div class="flex-1">
                                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Chapter title
                                    </label>
                                    <input type="text" name="title" value="{{ $chapter->title }}"
                                           class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Position
                                    </label>
                                    <input type="number" name="position" value="{{ $chapter->position }}"
                                           class="mt-1 w-20 rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        Approval
                                    </label>
                                    <select name="approval_status"
                                            class="mt-1 w-32 rounded-md border border-gray-300 px-2 py-1.5 text-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="approved" {{ $chapter->approval_status === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="pending"  {{ $chapter->approval_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="rejected" {{ $chapter->approval_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Admin note to teacher
                                </label>
                                <textarea name="admin_note" rows="2"
                                          class="mt-1 w-full rounded-md border border-gray-300 px-2 py-1.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                          placeholder="Explain any changes or reasons for rejection.">{{ $chapter->admin_note }}</textarea>
                            </div>

                            <div class="flex items-center justify-between text-xs text-gray-400">
                                <div>
                                    @if ($chapter->last_moderated_at)
                                        Last moderated {{ $chapter->last_moderated_at->diffForHumans() }}
                                    @else
                                        Never moderated
                                    @endif
                                </div>
                                <button type="submit"
                                        class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                    Save chapter changes
                                </button>
                            </div>
                        </form>

                        {{-- Topics under this chapter --}}
                        <div class="mt-4 border-t border-gray-100 pt-3">
                            <h3 class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Topics
                            </h3>

                            @if ($chapter->topics->isEmpty())
                                <p class="text-xs text-gray-500">No topics in this chapter yet.</p>
                            @else
                                <div class="space-y-2">
                                    @foreach ($chapter->topics as $topic)
                                        <div class="flex flex-col gap-2 rounded-md bg-gray-50 p-2 md:flex-row md:items-start md:justify-between">
                                            <form method="POST" action="{{ route('admin.topics.moderate', $topic) }}" class="flex-1 space-y-1">
                                                @csrf

                                                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                                    <div class="flex-1">
                                                        <input type="text" name="title" value="{{ $topic->title }}"
                                                               class="w-full rounded-md border border-gray-300 px-2 py-1 text-xs font-medium text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                    </div>

                                                    <div class="flex items-center gap-2">
                                                        <select name="approval_status"
                                                                class="rounded-md border border-gray-300 px-2 py-1 text-[11px] focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                                            <option value="approved" {{ $topic->approval_status === 'approved' ? 'selected' : '' }}>Approved</option>
                                                            <option value="pending"  {{ $topic->approval_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                            <option value="rejected" {{ $topic->approval_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <textarea name="content" rows="2"
                                                          class="w-full rounded-md border border-gray-300 px-2 py-1 text-xs text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                          placeholder="Edit topic text if needed.">{{ $topic->content }}</textarea>

                                                <textarea name="admin_note" rows="1"
                                                          class="w-full rounded-md border border-amber-200 bg-amber-50 px-2 py-1 text-[11px] text-amber-900 focus:border-amber-400 focus:outline-none focus:ring-1 focus:ring-amber-400"
                                                          placeholder="Optional note to teacher">{{ $topic->admin_note }}</textarea>

                                                <div class="flex items-center justify-between text-[11px] text-gray-400">
                                                    <span>
                                                        @if ($topic->last_moderated_at)
                                                            Last moderated {{ $topic->last_moderated_at->diffForHumans() }}
                                                        @else
                                                            Never moderated
                                                        @endif
                                                    </span>

                                                    <button type="submit"
                                                            class="rounded-md bg-blue-600 px-2.5 py-1 text-[11px] font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                                                        Save topic
                                                    </button>
                                                </div>
                                            </form>

                                            <form method="POST"
                                                  action="{{ route('admin.topics.destroy.admin', $topic) }}"
                                                  onsubmit="return confirm('Delete this topic?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-md bg-red-500 px-2 py-1 text-[11px] font-medium text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
