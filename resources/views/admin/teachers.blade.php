@extends('layouts.admin_lays')

@section('title', 'Manage Teachers | ClassIQ')
@section('page_title', 'Teacher Management')
@section('page_subtitle', 'Review new teacher signups and manage accounts.')

@section('content')

@if (session('status'))
    <div class="at-alert">
        {{ session('status') }}
    </div>
@endif

<div class="at-grid">

    {{-- Pending teachers --}}
    <div class="at-column">
        <h2 class="at-heading">Pending approval</h2>

        @if ($pendingTeachers->isEmpty())
            <p class="at-empty">No pending teachers right now.</p>
        @else
            <div class="at-list">
                @foreach ($pendingTeachers as $teacher)
                    <div class="at-card">
                        <div class="at-card-main">
                            <p class="at-name">{{ $teacher->name }}</p>
                            <p class="at-meta">{{ $teacher->email }} · {{ $teacher->phone }}</p>
                            <p class="at-meta-small">{{ $teacher->school_name }}</p>
                            <p class="at-meta-small">
                                Subjects: {{ $teacher->subjects_taught }}<br>
                                Classes: {{ $teacher->classes_taught }}
                            </p>
                            @if ($teacher->nid_file_path)
                                <p class="at-link-line">
                                    <a href="{{ asset('storage/'.$teacher->nid_file_path) }}" target="_blank" class="at-link">
                                        View NID / Teacher ID
                                    </a>
                                </p>
                            @endif
                        </div>

                        <div class="at-card-actions">
                            {{-- Approve --}}
                            <form method="POST" action="{{ route('admin.teachers.approve', $teacher) }}">
                                @csrf
                                <button type="submit" class="at-btn at-btn-approve">
                                    Approve
                                </button>
                            </form>

                            {{-- Reject (keep but not delete) --}}
                            <form method="POST" action="{{ route('admin.teachers.reject', $teacher) }}"
                                  onsubmit="return confirm('Mark this teacher as rejected?');">
                                @csrf
                                <button type="submit" class="at-btn at-btn-secondary">
                                    Reject
                                </button>
                            </form>

                            {{-- Remove (soft delete) --}}
                            <form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}"
                                  onsubmit="return confirm('Remove this teacher?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="at-btn at-btn-remove">
                                    Remove
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Approved teachers --}}
    <div class="at-column">
        <h2 class="at-heading">Approved teachers</h2>

        @if ($approvedTeachers->isEmpty())
            <p class="at-empty">No approved teachers yet.</p>
        @else
            <div class="at-list at-list-approved">
                @foreach ($approvedTeachers as $teacher)
                    <div class="at-card at-card-small">
                        <div>
                            <p class="at-name-small">{{ $teacher->name }}</p>
                            <p class="at-meta-small">{{ $teacher->email }}</p>
                            <p class="at-meta-small">
                                @if ($teacher->is_suspended)
                                    <span class="at-pill at-pill-danger">Suspended</span>
                                @else
                                    <span class="at-pill">Active</span>
                                @endif
                            </p>
                        </div>

                        <div class="at-card-actions">
                            {{-- Toggle suspend/activate --}}
                            <form method="POST" action="{{ route('admin.teachers.toggle-suspend', $teacher) }}">
                                @csrf
                                <button type="submit" class="at-btn at-btn-secondary">
                                    {{ $teacher->is_suspended ? 'Activate' : 'Suspend' }}
                                </button>
                            </form>

                            {{-- Verify documents --}}
                            <form method="POST" action="{{ route('admin.teachers.verify-documents', $teacher) }}">
                                @csrf
                                <button type="submit" class="at-btn at-btn-secondary">
                                    @if ($teacher->documents_verified_at)
                                        Docs Verified
                                    @else
                                        Verify Docs
                                    @endif
                                </button>
                            </form>

                            {{-- View / Edit profile page --}}
                            <a href="{{ route('admin.teachers.show', $teacher) }}" class="at-btn at-btn-link">
                                View
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div> {{-- end .at-grid --}}

<hr class="at-divider">

<div class="at-overview">
    <h2 class="at-heading at-heading--center">All teachers overview</h2>

    <table class="at-table at-table--full">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email / Phone</th>
                <th>School</th>
                <th>Subjects / Classes</th>
                <th>NID file</th>
                <th>Docs verified</th>
                <th>Students</th>
                <th>Classes</th>
                <th>Last login</th>
            </tr>
        </thead>
        <tbody>
        @php
            $allTeachers = $pendingTeachers->merge($approvedTeachers);
        @endphp

        @forelse ($allTeachers as $teacher)
            <tr>
                <td>{{ $teacher->name }}</td>
                <td>
                    <div>{{ $teacher->email }}</div>
                    <div class="at-table-meta">{{ $teacher->phone }}</div>
                </td>
                <td>{{ $teacher->school_name }}</td>
                <td>
                    <div>{{ $teacher->subjects_taught }}</div>
                    <div class="at-table-meta">Classes: {{ $teacher->classes_taught }}</div>
                </td>
                <td>
                    @if ($teacher->nid_file_path)
                        <a href="{{ asset('storage/'.$teacher->nid_file_path) }}" target="_blank" class="at-link">
                            View
                        </a>
                    @else
                        <span class="at-table-meta">None</span>
                    @endif
                </td>
                <td>
                    @if ($teacher->documents_verified_at)
                        <span class="at-pill">Yes</span>
                    @else
                        <span class="at-pill at-pill-danger">No</span>
                    @endif
                </td>
                <td>0</td>
                <td>0</td>
                <td>
                    @if ($teacher->last_login_at)
                        {{ $teacher->last_login_at->format('Y-m-d H:i') }}
                    @else
                        <span class="at-table-meta">Never</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="at-table-empty">No teachers found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

@endsection
