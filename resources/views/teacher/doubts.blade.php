@extends('layouts.teacher_lays')

@section('title', 'Learner doubts')
@section('page_title', 'Learner doubts')

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <div class="t-page">
        <h1 class="text-xl font-semibold text-slate-900 mb-3">Learner doubts</h1>

        @if (session('status'))
            <div class="mb-3 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($doubts->isEmpty())
            <p class="text-sm text-slate-500">No questions yet.</p>
        @else
            <div class="space-y-3">
                @foreach ($doubts as $doubt)
                    @php
                        $lesson = $doubt->lesson;
                        $course = optional($lesson->topic->chapter)->course;
                    @endphp
                    <div class="rounded-lg border border-slate-200 bg-white p-3 shadow-sm">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-xs text-slate-500">
                                    {{ $doubt->learner->name }} · {{ $course->title ?? 'Course' }} · {{ $lesson->title }}
                                </p>
                                <p class="mt-1 text-sm text-slate-900 whitespace-pre-line">
                                    {{ $doubt->question }}
                                </p>
                            </div>
                            <span class="text-[11px] text-slate-500">
                                {{ $doubt->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <form method="POST" action="{{ route('teacher.doubts.reply', $doubt) }}" class="mt-3 space-y-2">
                            @csrf
                            <textarea name="teacher_reply" rows="2"
                                      class="w-full rounded-md border border-slate-300 px-2 py-1.5 text-xs focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                      placeholder="Write your answer...">{{ old('teacher_reply', $doubt->teacher_reply) }}</textarea>

                            <button type="submit"
                                    class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                                Send reply
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $doubts->links() }}
            </div>
        @endif
    </div>
@endsection
