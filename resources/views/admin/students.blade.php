@extends('layouts.admin_lays')

@section('title', 'Students')

 @vite(['resources/css/app.css', 'resources/js/app.js'])


@section('content')
    <div class="max-w-6xl mx-auto">
        {{-- Welcome --}}
        @auth('admin')
            <p class="mb-4 text-lg font-semibold text-gray-900">
                Welcome back, {{ auth('admin')->user()->name }}!
            </p>
        @endauth

        {{-- Header + search --}}
        <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Student Management</h1>
                <p class="mt-1 text-sm text-gray-500">
                    View learner accounts, change classes, and suspend or activate access.
                </p>
            </div>

            <form method="GET" action="{{ route('admin.students') }}" class="flex items-center gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Search by name or email"
                    class="w-48 rounded-md border border-gray-300 px-2 py-1 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                <button
                    type="submit"
                    class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                >
                    Search
                </button>
            </form>
        </div>

        {{-- Flash --}}
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        {{-- Empty --}}
        @if ($learners->isEmpty())
            <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center">
                <h3 class="text-sm font-semibold text-gray-900">No students found</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Try searching with a different name or email.
                </p>
            </div>
        @else
            {{-- Table --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Class / Section</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Last login</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($learners as $learner)
                                <tr class="hover:bg-gray-50/70">
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        <div class="font-medium text-gray-900">
                                            {{ $learner->name }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        <div class="text-sm text-gray-900">{{ $learner->email }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        <div class="text-sm text-gray-900">
                                            Class {{ $learner->class_level ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            Section {{ $learner->section ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        @php
                                            $status = $learner->status ?? 'inactive';
                                            $badge = $status === 'active'
                                                ? 'bg-emerald-50 text-emerald-700 ring-emerald-100'
                                                : 'bg-red-50 text-red-700 ring-red-100';
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $badge }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 align-top text-sm text-gray-500">
                                        {{ optional($learner->last_login_at)->diffForHumans() ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        <div class="flex justify-end gap-2">
                                            {{-- Change class/section (simple inline form) --}}
                                            <form method="POST" action="{{ route('admin.students.change-class', $learner) }}" class="flex items-center gap-1">
                                                @csrf
                                                <input
                                                    type="number"
                                                    name="class_level"
                                                    value="{{ $learner->class_level }}"
                                                    min="1"
                                                    max="12"
                                                    class="w-14 rounded-md border border-gray-300 px-1 py-0.5 text-xs focus:border-blue-500 focus:outline-none"
                                                >
                                                <input
                                                    type="text"
                                                    name="section"
                                                    value="{{ $learner->section }}"
                                                    placeholder="Sec"
                                                    class="w-14 rounded-md border border-gray-300 px-1 py-0.5 text-xs focus:border-blue-500 focus:outline-none"
                                                >
                                                <button
                                                    type="submit"
                                                    class="rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800 hover:bg-gray-200"
                                                >
                                                    Save
                                                </button>
                                            </form>

                                            {{-- Suspend / Activate --}}
                                            <form method="POST" action="{{ route('admin.students.toggle-status', $learner) }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="rounded-md px-3 py-1.5 text-xs font-medium text-white shadow-sm
                                                        {{ $status === 'active'
                                                            ? 'bg-red-500 hover:bg-red-600 focus:ring-red-500'
                                                            : 'bg-green-500 hover:bg-green-600 focus:ring-green-500' }}
                                                        focus:outline-none focus:ring-2 focus:ring-offset-1"
                                                >
                                                    {{ $status === 'active' ? 'Suspend' : 'Activate' }}
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
                    {{ $learners->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
