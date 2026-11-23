@extends('layouts.learner_lays')

@section('title', 'Accessibility & special needs')
@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <div class="l-page max-w-xl">
        <h1 class="text-xl font-semibold text-slate-900 mb-3">
            Accessibility & special needs
        </h1>

        @if (session('status'))
            <div class="mb-3 rounded-md bg-emerald-50 px-3 py-2 text-xs text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('learner.accessibility.update') }}" class="space-y-3">
            @csrf

            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" name="calm_mode" value="1"
                       {{ ($settings['calm_mode'] ?? false) ? 'checked' : '' }}
                       class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600">
                <span>
                    <span class="font-semibold">Calm mode</span><br>
                    <span class="text-xs text-slate-500">Soft colors and gentle borders to reduce sensory overload.</span>
                </span>
            </label>

            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" name="dyslexia_font" value="1"
                       {{ ($settings['dyslexia_font'] ?? false) ? 'checked' : '' }}
                       class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600">
                <span>
                    <span class="font-semibold">Dyslexia-friendly font</span><br>
                    <span class="text-xs text-slate-500">Use simpler, more readable fonts for text content.</span>
                </span>
            </label>

            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" name="large_text" value="1"
                       {{ ($settings['large_text'] ?? false) ? 'checked' : '' }}
                       class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600">
                <span>
                    <span class="font-semibold">Larger text</span><br>
                    <span class="text-xs text-slate-500">Slightly increase text size and line spacing.</span>
                </span>
            </label>

            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" name="reduced_motion" value="1"
                       {{ ($settings['reduced_motion'] ?? false) ? 'checked' : '' }}
                       class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600">
                <span>
                    <span class="font-semibold">Reduced motion</span><br>
                    <span class="text-xs text-slate-500">Turn off most animations and transitions.</span>
                </span>
            </label>

            <label class="flex items-start gap-2 text-sm">
                <input type="checkbox" name="low_distraction" value="1"
                       {{ ($settings['low_distraction'] ?? false) ? 'checked' : '' }}
                       class="mt-1 h-4 w-4 rounded border-slate-300 text-emerald-600">
                <span>
                    <span class="font-semibold">Reduced distractions</span><br>
                    <span class="text-xs text-slate-500">Hide decorative emojis and extra visual clutter.</span>
                </span>
            </label>

            <button type="submit"
                    class="mt-3 rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                Save settings
            </button>
        </form>
    </div>
@endsection
