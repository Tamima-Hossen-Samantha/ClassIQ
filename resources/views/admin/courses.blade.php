@extends('layouts.admin_lays')

@section('title', 'Courses')
 @vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <div class="max-w-6xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Courses</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Review teacher requests and decide which courses appear on the homepage and catalog.
                </p>
            </div>

            {{-- Filter pills --}}
            <form method="GET" action="{{ route('admin.courses') }}" class="flex flex-wrap gap-2">
                @php
                    $options = ['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'];
                @endphp

                @foreach ($options as $value => $label)
                    @php
    $isActive = $statusFilter === $value;
@endphp

<button
    type="submit"
    name="admin_status"
    value="{{ $value }}"
    class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium transition
        {{ $isActive
            ? 'border-blue-500 bg-blue-50 text-blue-700'
            : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50'
        }}"
>
    {{ $label }}
</button>

                @endforeach
            </form>
        </div>

        {{-- Flash message --}}
        @if (session('status'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        {{-- Empty state --}}
        @if ($courses->isEmpty())
            <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center">
                <h3 class="text-sm font-semibold text-gray-900">No courses found</h3>
                <p class="mt-2 text-sm text-gray-500">
                    Try changing the filter above to see other courses.
                </p>
            </div>
        @else
            {{-- Table card --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Course
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Teacher
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Class / Subject
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Course status
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Homepage status
                                </th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach ($courses as $course)
                                <tr class="hover:bg-gray-50/70">
                                    {{-- Course --}}
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        <div class="font-medium text-gray-900">
                                            {{ $course->title }}
                                        </div>
                                        <div class="mt-0.5 text-xs text-gray-500">
                                            Updated {{ $course->updated_at->diffForHumans() }}
                                        </div>
                                    </td>

                                    {{-- Teacher --}}
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        <div class="text-sm text-gray-900">
                                            {{ $course->teacher->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            ID: {{ $course->teacher_id }}
                                        </div>
                                    </td>

                                    {{-- Class / Subject --}}
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        <div class="text-sm text-gray-900">
                                            Class {{ $course->class_level ?? '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $course->subject ?? 'No subject' }}
                                        </div>
                                    </td>

                                    {{-- Course status (teacher side) --}}
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        @php
                                            $statusColor = match ($course->status) {
                                                'published' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
                                                'draft' => 'bg-gray-50 text-gray-600 ring-gray-100',
                                                default => 'bg-gray-50 text-gray-600 ring-gray-100',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $statusColor }}">
                                            {{ ucfirst($course->status) }}
                                        </span>
                                    </td>

                                    {{-- Homepage status (admin_status) --}}
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        @php
                                            $adminStatus = $course->admin_status ?? 'draft';
                                            $adminColor = match ($adminStatus) {
                                                'approved' => 'bg-green-50 text-green-700 ring-green-100',
                                                'pending' => 'bg-amber-50 text-amber-700 ring-amber-100',
                                                'rejected' => 'bg-red-50 text-red-700 ring-red-100',
                                                default => 'bg-gray-50 text-gray-600 ring-gray-100',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $adminColor }}">
                                            {{ ucfirst($adminStatus) }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="whitespace-nowrap px-4 py-3 align-top">
                                        <div class="flex justify-end gap-2">
                                            {{-- Approve --}}
                                            <form method="POST" action="{{ route('admin.courses.approve', $course) }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center rounded-md bg-green-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1"
                                                >
                                                    Approve
                                                </button>
                                            </form>

                                            {{-- Reject --}}
                                            <form method="POST" action="{{ route('admin.courses.reject', $course) }}">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center rounded-md bg-red-500 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                                >
                                                    Reject
                                                </button>
                                            </form>

<a href="{{ route('admin.courses.chapters.moderate', $course) }}"
   class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
    Moderate chapters
</a>


                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="border-t border-gray-100 bg-gray-50 px-4 py-3">
                    {{ $courses->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
