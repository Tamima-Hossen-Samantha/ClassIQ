<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ClassIQ Admin')</title>

    <link rel="shortcut icon" href="{{ asset('images/Fevicon.png') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="a-body">
<div class="a-shell">

    {{-- SIDEBAR --}}
    <aside class="a-sidebar">
        <div class="a-sidebar-top">
            <div class="a-logo">
                <img src="{{ asset('images/Fevicon.png') }}" class="a-logo-img" alt="ClassIQ logo">
                <span class="a-logo-text">CLASS<span class="a-logo-i">I</span>Q</span>
            </div>
           
        </div>

        <nav class="a-nav">
            <a href="{{ route('admin.dashboard') }}"
               class="a-nav-link {{ request()->routeIs('admin.dashboard') ? 'a-nav-link--active' : '' }}">
                <i class="bx bxs-dashboard"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.teachers') }}"
               class="a-nav-link {{ request()->routeIs('admin.teachers') ? 'a-nav-link--active' : '' }}">
                <i class="bx bxs-user-badge"></i><span>Teachers</span>
            </a>
            <a href="{{ route('admin.students') }}"
               class="a-nav-link {{ request()->routeIs('admin.students') ? 'a-nav-link--active' : '' }}">
                <i class="bx bxs-user"></i><span>Students</span>
            </a>
            <a href="{{ route('admin.courses') }}"
               class="a-nav-link {{ request()->routeIs('admin.courses') ? 'a-nav-link--active' : '' }}">
                <i class="bx bxs-book-alt"></i><span>Courses</span>
            </a>
            <a href="{{ route('admin.subjects_classes') }}"
               class="a-nav-link {{ request()->routeIs('admin.subjects_classes') ? 'a-nav-link--active' : '' }}">
                <i class="bx bxs-chalkboard"></i><span>Subjects & Classes</span>
            </a>
            
            <a href="{{ route('admin.lessons') }}"
               class="a-nav-link {{ request()->routeIs('admin.lessons') ? 'a-nav-link--active' : '' }}">
                <i class="bx bxs-videos"></i><span>Lessons</span>
            </a>
            <a href="{{ route('admin.quizzes_homework') }}"
               class="a-nav-link {{ request()->routeIs('admin.quizzes_homework') ? 'a-nav-link--active' : '' }}">
                <i class="bx bxs-edit-alt"></i><span>Quizzes & Homework</span>
            </a>
            <a href="{{ route('admin.accessibility') }}"
               class="a-nav-link {{ request()->routeIs('admin.accessibility') ? 'a-nav-link--active' : '' }}">
                <i class="bx bxs-universal-access"></i><span>Accessibility</span>
            </a>
            <a href="{{ route('admin.notifications') }}"
               class="a-nav-link {{ request()->routeIs('admin.notifications') ? 'a-nav-link--active' : '' }}">
                <i class="bx bxs-bell"></i><span>Announcements</span>
            </a>
            <a href="{{ route('admin.analytics') }}"
               class="a-nav-link {{ request()->routeIs('admin.analytics') ? 'a-nav-link--active' : '' }}">
                <i class="bx bxs-bar-chart-alt-2"></i><span>Analytics</span>
            </a>
        </nav>

<div class="a-sidebar-bottom">
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="a-logout-btn">
            <i class="bx bx-log-out"></i><span>Log out</span>
        </button>
    </form>
</div>



    </aside>

    {{-- MAIN --}}
    <div class="a-main">
        <header class="a-topbar">
            <div>
                <h1 class="a-page-title">
    @hasSection('page_title')
        @yield('page_title')
    @else
        Welcome back!
    @endif
</h1>

                
            </div>
            <div class="a-topbar-right">
                <button class="a-icon-btn" title="System notifications">
                    <i class="bx bx-bell"></i>
                </button>
                <div class="a-admin-chip">
                    <div class="a-avatar">A</div>
                    <div class="a-admin-info">
                        <span class="a-admin-name">Admin</span>
                        <span class="a-admin-meta">Super control</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="a-content">
            @yield('content')
        </main>
    </div>

</div>
</body>
</html>
