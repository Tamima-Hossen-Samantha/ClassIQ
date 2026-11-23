@extends('layouts.admin_lays')

@section('title', 'Lessons')

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <div class="max-w-6xl mx-auto">
        {{-- Header --}}
        <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Lessons</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Review and moderate lessons to keep content safe and child-friendly.
                </p>
            </div>

            {{-- Simple filters --}}
            <form method="GET" class="flex flex-wrap items-center gap-2 text-xs">
                <select name="status" class="rounded-md border border-gray-300 px-2 py-1">
                    <option value="">Status: All</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>

                <select name="approval_status" class="rounded-md border border-gray-300 px-2 py-1">
                    <option value="">Approval: All</option>
                    <option value="pending" {{ request('approval_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('approval_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('approval_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <button type="submit"
                        class="rounded-md bg-gray-800 px-3 py-1 font-medium text-white hover:bg-gray-900">
                    Filter
                </button>
            </form>
        </div>

        {{-- Flash --}}
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($lessons->isEmpty())
            <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center text-sm text-gray-600">
                No lessons found.
            </div>
        @else
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Lesson</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Course / Topic</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Teacher</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($lessons as $lesson)
                                @php
                                    $topic   = $lesson->topic;
                                    $chapter = $topic->chapter ?? null;
                                    $course  = $chapter->course ?? null;

                                    $statusBadge = $lesson->status === 'published'
                                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-100'
                                        : 'bg-slate-50 text-slate-700 ring-slate-100';

                                    $approval = $lesson->approval_status;
                                    $approvalBadge = $approval === 'approved'
                                        ? 'bg-emerald-50 text-emerald-700 ring-emerald-100'
                                        : ($approval === 'rejected'
                                            ? 'bg-red-50 text-red-700 ring-red-100'
                                            : 'bg-amber-50 text-amber-700 ring-amber-100');
                                @endphp

                                <tr class="hover:bg-gray-50/70">
                                    {{-- Lesson --}}
                                    <td class="px-4 py-3 align-top">
                                        <div class="font-medium text-gray-900">
                                            {{ $lesson->title }}
                                        </div>
                                        <div class="mt-0.5 text-xs text-gray-500">
                                            Created {{ $lesson->created_at?->diffForHumans() }}
                                        </div>
                                    </td>

                                    {{-- Course / topic --}}
                                    <td class="px-4 py-3 align-top">
                                        <div class="text-sm text-gray-900">
                                            {{ optional($course)->title ?? 'No course' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            @if ($chapter)
                                                {{ $chapter->title }} ·
                                            @endif
                                            @if ($topic)
                                                Topic: {{ $topic->title }}
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Teacher --}}
                                    <td class="px-4 py-3 align-top">
                                        <div class="text-sm text-gray-900">
                                            {{ optional($course->teacher ?? null)->name ?? '—' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ optional($course->teacher ?? null)->email }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-medium ring-1 ring-inset {{ $statusBadge }}">
                                                {{ ucfirst($lesson->status) }}
                                            </span>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-medium ring-1 ring-inset {{ $approvalBadge }}">
                                                {{ ucfirst($lesson->approval_status) }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-3 align-top">
                                        <div class="flex flex-col items-end gap-2 sm:flex-row sm:justify-end">
                                            <a href="{{ route('admin.lessons.show', $lesson) }}"
                                               class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                                Preview / moderate
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('admin.lessons.destroy', $lesson) }}"
                                                  onsubmit="return confirm('Delete this lesson?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-md bg-red-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-600">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-100 bg-gray-50 px-4 py-3">
                    {{ $lessons->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
