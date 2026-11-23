@extends('layouts.learner_lays')

@section('title', $course->title)

@section('content')
    <div class="l-page">
        {{-- Header --}}
        <h1 class="text-2xl font-extrabold text-slate-900">
            {{ $course->title }}
        </h1>
        <p class="mt-1 text-sm text-slate-600">
            Class {{ $course->class_level }} · {{ $course->subject }}
        </p>

        {{-- Back to My Class --}}
        <div class="mt-3">
            <a href="{{ route('learner.my-classes') }}" class="text-sm text-blue-600 underline">
                ← Back to My classes
            </a>
        </div>

        {{-- Chapters and topics --}}
        @if ($course->chapters->isEmpty())
            <div class="mt-6 rounded-lg border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-600">
                Your teacher hasn’t added any chapters yet.
            </div>
        @else
            <div class="mt-6 space-y-4">
                @foreach ($course->chapters as $chapter)
                    <section class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        {{-- Chapter header --}}
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <h2 class="text-base font-semibold text-slate-900">
                                    {{ $chapter->position }}. {{ $chapter->title }}
                                </h2>

                                @if ($chapter->description)
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ $chapter->description }}
                                    </p>
                                @endif

                                <p class="mt-1 text-xs text-slate-500">
                                    {{ $chapter->estimated_time ?? 'Flexible time' }}
                                    @if ($chapter->difficulty)
                                        · {{ ucfirst($chapter->difficulty) }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Topics inside this chapter --}}
                        <div class="mt-4 border-t border-slate-100 pt-3">
                            @if ($chapter->topics->isEmpty())
                                <p class="text-xs text-slate-500">
                                    No topics in this chapter yet.
                                </p>
                            @else
                                <@foreach ($chapter->topics as $topic)
    <li class="flex items-center justify-between rounded-md bg-slate-50 px-3 py-2">
        <div>
            <p class="text-sm font-medium text-slate-900">
                {{ $topic->position }}. {{ $topic->title }}
            </p>
            @if ($topic->content)
                <p class="mt-0.5 text-xs text-slate-600 line-clamp-2">
                    {{ $topic->content }}
                </p>
            @endif
        </div>

        <div class="flex items-center gap-2">
            @if ($topic->lessons->isNotEmpty())
                @php $firstLesson = $topic->lessons->first(); @endphp
                <a href="{{ route('learner.lessons.show', $firstLesson) }}"
                   class="inline-flex items-center rounded-md border border-slate-300 bg-white px-2.5 py-1 text-[11px] font-medium text-slate-700 hover:bg-slate-50">
                    Open lesson
                </a>
            @else
                <span class="text-[11px] text-slate-400">
                    Lesson coming soon
                </span>
            @endif
        </div>
    </li>
@endforeach

                            @endif
                        </div>
                    </section>
                @endforeach
            @endforeach
            </div>
        @endif
    </div>
@endsection
