<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ClassIQ learner')</title>

    <link rel="shortcut icon" href="{{ asset('images/Fevicon.png') }}">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('css/learner.css') }}">
</head>
<body class="s-body">
<div class="s-shell">

    {{-- SIDEBAR --}}
    <aside class="s-sidebar">
        <div class="s-sidebar-top">
            <div class="s-logo">
                <img src="{{ asset('images/Fevicon.png') }}" alt="ClassIQ logo" class="s-logo-img">
                <span class="s-logo-text">
                    CLASS<span class="s-logo-i">I</span>Q
                </span>
            </div>
        </div>

        <nav class="s-nav">
            <a href="{{ route('learner.dashboard') }}"
               class="s-nav-link {{ request()->routeIs('learner.dashboard') ? 's-nav-link--active' : '' }}">
                <i class="bx bxs-home-heart"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('learner.classes') }}"
               class="s-nav-link {{ request()->routeIs('learner.classes') ? 's-nav-link--active' : '' }}">
                <i class="bx bxs-graduation"></i>
                <span>My Class</span>
            </a>
            <a href="{{ route('learner.subjects') }}"
               class="s-nav-link {{ request()->routeIs('learner.subjects') ? 's-nav-link--active' : '' }}">
                <i class="bx bxs-book-alt"></i>
                <span>Subjects</span>
            </a>
            <a href="{{ route('learner.lessons') }}"
               class="s-nav-link {{ request()->routeIs('learner.lessons') ? 's-nav-link--active' : '' }}">
                <i class="bx bxs-videos"></i>
                <span>Lessons</span>
            </a>
            <a href="{{ route('learner.quizzes') }}"
               class="s-nav-link {{ request()->routeIs('learner.quizzes') ? 's-nav-link--active' : '' }}">
                <i class="bx bxs-joystick-button"></i>
                <span>Quizzes</span>
            </a>
            <a href="{{ route('learner.progress') }}"
               class="s-nav-link {{ request()->routeIs('learner.progress') ? 's-nav-link--active' : '' }}">
                <i class="bx bxs-bar-chart-alt-2"></i>
                <span>Progress</span>
            </a>
           
            <a href="{{ route('learner.accessibility') }}"
               class="s-nav-link {{ request()->routeIs('learner.accessibility') ? 's-nav-link--active' : '' }}">
                <i class="bx bxs-bar-chart-alt-2"></i>
                <span>accessibility</span>
            </a>
            
            <a href="{{ route('learner.notifications') }}"
               class="s-nav-link {{ request()->routeIs('learner.notifications') ? 's-nav-link--active' : '' }}">
                <i class="bx bxs-bell"></i>
                <span>Alerts</span>
            </a>
            <a href="{{ route('learner.profile') }}"
               class="s-nav-link {{ request()->routeIs('learner.profile') ? 's-nav-link--active' : '' }}">
                <i class="bx bxs-user"></i>
                <span>Profile</span>
            </a>
        </nav>

        <form method="POST" action="{{ route('learner.logout') }}">
    @csrf
    <button type="submit" class="s-logout-btn">
        <i class="bx bx-log-out"></i>
        <span>Log out</span>
    </button>
</form>

    </aside>

    {{-- MAIN AREA --}}
    <div class="s-main">
        <header class="s-topbar">
            <div>
                <h1 class="s-page-title">@yield('page_title', 'Home')</h1>
                <p class="s-page-subtitle">@yield('page_subtitle', 'Let’s learn something fun today!')</p>
            </div>
            <div class="s-topbar-right">
                <button class="s-icon-btn" title="Notifications">
                    <i class="bx bx-bell"></i>
                </button>
                <button class="s-icon-btn" title="Calm mode">
                    <i class="bx bx-moon"></i>
                </button>
                <div class="s-learner-chip">
                    <div class="s-avatar">S</div>
                    <div class="s-learner-info">
                        <span class="s-learner-name">learner Name</span>
                        <span class="s-learner-meta">Class 7 · Explorer</span>
                    </div>
                </div>
            </div>
        </header>
<!-- ai -->
        <button class="s-ai-fab"
        title="Ask ClassiQ AI"
        type="button"
        onclick="window.location='{{ route('learner.ai') }}'">
    <img src="{{ asset('images/ai.png') }}" alt="ClassiQ AI" class="s-ai-fab-img">
</button>



        <main class="s-content">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
