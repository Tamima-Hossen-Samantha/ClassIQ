@extends('layouts.learner_lays')

@section('title', $lesson->title)

@section('content')
    <div class="l-page">
        {{-- Breadcrumb --}}
        <p class="text-xs text-slate-500">
            {{ $course->title }} · {{ $chapter->title }} · Topic: {{ $topic->title }}
        </p>

        {{-- Header --}}
        <div class="mt-1 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900">
                    {{ $lesson->title }}
                </h1>
                @if ($lesson->description)
                    <p class="mt-1 text-sm text-slate-600">
                        {{ $lesson->description }}
                    </p>
                @endif
            </div>

            <div class="flex items-center gap-2">
                {{-- Autism-friendly toggle (UI only for now) --}}
                <label class="inline-flex items-center gap-2">
                    <input type="checkbox"
                           id="autismModeToggle"
                           class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                           {{ $lesson->is_autism_friendly ? 'checked' : '' }}>
                    <span class="text-xs text-slate-700">
                        Autism-friendly mode
                    </span>
                </label>
            </div>
        </div>

        {{-- Video section --}}
        @if ($lesson->video_type && ($lesson->video_path || $lesson->video_url))
            <div class="mt-4 rounded-lg border border-slate-200 bg-black/95 p-2">
                @if ($lesson->video_type === 'upload' && $lesson->video_path)
                    <video controls class="h-56 w-full rounded-md md:h-72">
                        <source src="{{ Storage::url($lesson->video_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="aspect-video w-full overflow-hidden rounded-md bg-black">
                        <iframe
                            src="{{ $lesson->video_url }}"
                            class="h-full w-full border-0"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    </div>
                @endif
            </div>
        @endif

        {{-- Summary + vocabulary + important points --}}
        <div class="mt-5 grid gap-4 md:grid-cols-3">
            <div class="md:col-span-2 rounded-lg border border-slate-200 bg-white p-4">
                <h2 class="text-sm font-semibold text-slate-900">Summary</h2>
                <p class="mt-2 text-sm text-slate-700 whitespace-pre-line">
                    {{ $lesson->summary_text ?? 'No summary added yet.' }}
                </p>
            </div>

            <div class="space-y-4">
                <div class="rounded-lg border border-slate-200 bg-white p-3">
                    <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Vocabulary
                    </h2>
                    @php
                        $vocabLines = $lesson->vocabulary_list
                            ? preg_split("/\r\n|\n|\r/", $lesson->vocabulary_list)
                            : [];
                    @endphp
                    @if (! empty($vocabLines))
                        <ul class="mt-2 space-y-1 text-xs text-slate-700">
                            @foreach ($vocabLines as $line)
                                @if (trim($line) !== '')
                                    <li>• {{ $line }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-2 text-xs text-slate-500">No vocabulary listed.</p>
                    @endif
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-3">
                    <h2 class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Important points
                    </h2>
                    @php
                        $pointLines = $lesson->important_points
                            ? preg_split("/\r\n|\n|\r/", $lesson->important_points)
                            : [];
                    @endphp
                    @if (! empty($pointLines))
                        <ul class="mt-2 space-y-1 text-xs text-slate-700">
                            @foreach ($pointLines as $line)
                                @if (trim($line) !== '')
                                    <li>• {{ $line }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="mt-2 text-xs text-slate-500">No key points added.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Resources --}}
        <div class="mt-5 rounded-lg border border-slate-200 bg-white p-4">
            <h2 class="text-sm font-semibold text-slate-900">Resources</h2>
            <div class="mt-2 flex flex-wrap gap-3 text-xs">
                @if ($lesson->notes_path)
                    <a href="{{ Storage::url($lesson->notes_path) }}" target="_blank"
                       class="inline-flex items-center rounded-md border border-slate-300 bg-slate-50 px-3 py-1 hover:bg-slate-100">
                        📄 Download notes
                    </a>
                @endif

                @if ($lesson->worksheet_path)
                    <a href="{{ Storage::url($lesson->worksheet_path) }}" target="_blank"
                       class="inline-flex items-center rounded-md border border-slate-300 bg-slate-50 px-3 py-1 hover:bg-slate-100">
                        📝 Worksheet
                    </a>
                @endif

                @if ($lesson->slides_path)
                    <a href="{{ Storage::url($lesson->slides_path) }}" target="_blank"
                       class="inline-flex items-center rounded-md border border-slate-300 bg-slate-50 px-3 py-1 hover:bg-slate-100">
                        📊 Slides
                    </a>
                @endif

                @if ($lesson->image_paths)
                    <span class="inline-flex items-center rounded-md border border-slate-300 bg-slate-50 px-3 py-1">
                        🖼 {{ count($lesson->image_paths) }} image(s)
                    </span>
                @endif

                @if (! $lesson->notes_path && ! $lesson->worksheet_path && ! $lesson->slides_path && ! $lesson->image_paths)
                    <p class="text-xs text-slate-500">No downloadable resources yet.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Simple autism-friendly JS toggle: you can improve later --}}
    @push('scripts')
        <script>
            const toggle = document.getElementById('autismModeToggle');
            if (toggle) {
                toggle.addEventListener('change', function () {
                    document.body.classList.toggle('l-autism-mode', this.checked);
                });
            }
        </script>
    @endpush
@endsection
