<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ClassIQ Workspace')</title>


<!-- favicon -->
  <link rel="shortcut icon" href="{{ asset('images/Fevicon.png') }}">


    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/teacher.css') }}">
    <link rel="stylesheet" href="{{ asset('css/learner.css') }}">
</head>
<body class="t-body">
<div class="t-shell">

    {{-- SIDEBAR + TOPBAR ALL HERE --}}
    <aside class="t-sidebar">
        <div class="t-sidebar-top">
            <div class="t-logo">
                <img src="{{ asset('images/Fevicon.png') }}" alt="ClassIQ logo" class="t-logo-img">
                <span class="t-logo-text">
                    CLASS<span class="t-logo-i">I</span>Q
                </span>
            </div>
            
        </div>

        <nav class="t-nav">
    <a href="{{ route('teacher.dashboard') }}"
       class="t-nav-link {{ request()->routeIs('teacher.dashboard') ? 't-nav-link--active' : '' }}">
        <i class="bx bxs-dashboard"></i>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('teacher.courses') }}"
       class="t-nav-link {{ request()->routeIs('teacher.courses') ? 't-nav-link--active' : '' }}">
        <i class="bx bxs-book-alt"></i>
        <span>Courses</span>
    </a>
    
    <a href="{{ route('teacher.lessons.index') }}"
   class="t-nav-link {{ request()->routeIs('teacher.lessons.*') ? 't-nav-link--active' : '' }}">
    <i class="bx bx-book-open"></i>
    <span>Lessons</span>
</a>

<a href="{{ route('teacher.doubts.index') }}"
   class="t-nav-link {{ request()->routeIs('teacher.doubts.*') ? 't-nav-link--active' : '' }}">
    <i class="bx bxs-message-dots"></i>
    <span>Doubts</span>
</a>


    <a href="{{ route('teacher.quizzes') }}"
       class="t-nav-link {{ request()->routeIs('teacher.quizzes') ? 't-nav-link--active' : '' }}">
        <i class="bx bxs-edit-alt"></i>
        <span>Quizzes</span>
    </a>
    <a href="{{ route('teacher.students') }}"
       class="t-nav-link {{ request()->routeIs('teacher.students') ? 't-nav-link--active' : '' }}">
        <i class="bx bxs-user-detail"></i>
        <span>Students</span>
    </a>
    <a href="{{ route('teacher.progress') }}"
       class="t-nav-link {{ request()->routeIs('teacher.progress') ? 't-nav-link--active' : '' }}">
        <i class="bx bxs-bar-chart-alt-2"></i>
        <span>Progress</span>
    </a>
    <a href="{{ route('teacher.notifications') }}"
       class="t-nav-link {{ request()->routeIs('teacher.notifications') ? 't-nav-link--active' : '' }}">
        <i class="bx bxs-bell"></i>
        <span>Notifications</span>
    </a>
    <a href="{{ route('teacher.settings') }}"
       class="t-nav-link {{ request()->routeIs('teacher.settings') ? 't-nav-link--active' : '' }}">
        <i class="bx bxs-cog"></i>
        <span>Settings</span>
    </a>
</nav>


        <div class="t-sidebar-bottom">
    <form method="POST" action="{{ route('teacher.logout') }}">
        @csrf
        <button type="submit" class="t-logout-btn">
            <i class="bx bx-log-out"></i>
            <span>Log out</span>
        </button>
    </form>
</div>

    </aside>

<!-- ai -->
        <button class="s-ai-fab"
        title="Ask ClassiQ AI"
        type="button"
        onclick="window.location='{{ route('learner.ai') }}'">
    <img src="{{ asset('images/ai.png') }}" alt="ClassiQ AI" class="s-ai-fab-img">
</button>


    <div class="t-main">
        <header class="t-topbar">
            <div class="t-topbar-left">
                <h1 class="t-page-title">@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="t-topbar-right">
                <button class="t-icon-btn">
                    <i class="bx bx-bell"></i>
                </button>
                <div class="t-teacher-chip">
                    <div class="t-avatar">T</div>
                    <div class="t-teacher-info">
                        <span class="t-teacher-name">Tamima Teacher</span>
                        <span class="t-teacher-meta">Class 6–8 · Math</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="t-content">
            @yield('content')
        </main>
    </div>

</div>
</body>
</html>
