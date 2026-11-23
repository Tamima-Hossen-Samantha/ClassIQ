{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin_lays')

@section('title', 'Admin dashboard')
@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        {{-- Top stats cards --}}
        <div class="grid gap-4 md:grid-cols-3 lg:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Teachers</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalTeachers }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Students</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalStudents }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Courses</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalCourses }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Subjects</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalSubjects }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Lessons</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalLessons }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Quizzes</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalQuizzes }}</p>
            </div>
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm md:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">AI usage</p>
                <p class="mt-2 text-sm text-gray-700">
                    Today: <span class="font-semibold">{{ $aiUsageToday }}</span> call(s),
                    Total: <span class="font-semibold">{{ $aiUsageTotal }}</span>
                </p>
            </div>
        </div>

        {{-- Pending teacher verifications + new registrations --}}
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-gray-900">Pending teacher verifications</h2>
                    <span class="rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-medium text-amber-700">
                        {{ $pendingVerifications->count() }} pending
                    </span>
                </div>
                @if ($pendingVerifications->isEmpty())
                    <p class="mt-3 text-xs text-gray-500">No pending requests.</p>
                @else
                    <ul class="mt-3 space-y-2 text-sm">
                        @foreach ($pendingVerifications as $requestItem)
                            <li class="rounded-md bg-gray-50 px-3 py-2">
                                <p class="font-medium text-gray-900">
                                    {{ $requestItem->teacher->name ?? 'Teacher' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Requested {{ $requestItem->created_at->diffForHumans() }}
                                </p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-900">New registrations (last 7 days)</h2>
                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Teachers</p>
                        @if ($newTeachers->isEmpty())
                            <p class="mt-1 text-xs text-gray-500">No new teachers.</p>
                        @else
                            <ul class="mt-2 space-y-1 text-xs">
                                @foreach ($newTeachers as $t)
                                    <li class="flex items-center justify-between">
                                        <span class="text-gray-800">{{ $t->name }}</span>
                                        <span class="text-gray-400">{{ $t->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Students</p>
                        @if ($newStudents->isEmpty())
                            <p class="mt-1 text-xs text-gray-500">No new students.</p>
                        @else
                            <ul class="mt-2 space-y-1 text-xs">
                                @foreach ($newStudents as $s)
                                    <li class="flex items-center justify-between">
                                        <span class="text-gray-800">{{ $s->name }}</span>
                                        <span class="text-gray-400">{{ $s->created_at->diffForHumans() }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent activity + notifications --}}
        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-900">Recent activity</h2>
                @if ($recentActivity->isEmpty())
                    <p class="mt-2 text-xs text-gray-500">No activity recorded yet.</p>
                @else
                    <ul class="mt-2 space-y-2 text-xs">
                        @foreach ($recentActivity as $log)
                            <li class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $log->title ?? $log->action }}</p>
                                    <p class="text-gray-500">{{ $log->description ?? '' }}</p>
                                </div>
                                <span class="whitespace-nowrap text-gray-400">
                                    {{ $log->created_at->diffForHumans() }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <h2 class="text-sm font-semibold text-gray-900">System notifications</h2>
                @if ($notifications->isEmpty())
                    <p class="mt-2 text-xs text-gray-500">No notifications.</p>
                @else
                    <ul class="mt-2 space-y-2 text-xs">
                        @foreach ($notifications as $n)
                            <li class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-medium text-gray-800">{{ $n->title }}</p>
                                    <p class="text-gray-500">{{ $n->message }}</p>
                                </div>
                                <span class="whitespace-nowrap text-gray-400">
                                    {{ $n->created_at->diffForHumans() }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endsection
