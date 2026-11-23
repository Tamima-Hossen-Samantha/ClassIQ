@extends('layouts.landing_lays')

@section('title', 'ClassIQ | Brain Boost')

@section('content')
    <!-- 
LANDING -->
  <section id="home" class="cq-hero">
    <div class="cq-container cq-hero-inner">
      <!-- LEFT: CLASSIQ + copy -->
      <div class="cq-left">
        <div class="cq-logo-wrap">
          <h1 class="cq-logo-word">
            CLASS<span style="color: #39a15d9e; font-size: 7rem; font-weight: 650">I</span>Q
          </h1>
        </div>

        <h2 class="cq-subheading">
          LEARNING DATE??
        </h2>
        <h2 class="cq-subheading">
          ALWAYS YES!!!
        </h2>

        <p class="cq-body">
          An engaging learning platform built to support students with clear explanations,
          playful visuals, and interactive lessons. Education made simple, friendly,
          and enjoyable.
        </p>

        <div class="cq-bottom-inner">
  <a href="#courses" class="cq-pill cq-pill-orange">Explore learning</a>

  <button
    type="button"
    class="cq-pill cq-pill-green"
    onclick="window.location='{{ route('login') }}'">
    Log in
  </button>
</div>

      </div>

      <!-- CENTER: HIPPO IMAGE -->
      <div class="cq-center">
        <img src="{{ asset('images/hippo.png') }}" alt="Hippo sitting on books with pencil" class="cq-hero-img">
      </div>

      <!-- RIGHT: BRAIN BOOST -->
      <div class="cq-right">
        <div class="cq-brain-wrap">
          <div class="cq-brain-boost">
            <span class="cq-brain">BRAIN</span>
            <span class="cq-boost">BOOST</span>
          </div>
        </div>
      </div>
    </div>
  </section>

 

  <!-- about start -->
<section id="About" class="about">
  <div class="container">
    <div class="about-content-wrapper">
      
      <div class="agency-right-side">
        <div class="img">
          <img src="{{ asset('images/about.png') }}" alt="Students learning with ClassIQ">
        </div>
      </div>

     
      <div class="agency-left-side">
        <p class="heading-normal-txt">Why Choose ClassIQ?</p>
        <h2 class="headings">
          Learning becomes an <span>adventure</span> with ClassIQ
        </h2>

        <p class="lead">
          Every child learns differently — and ClassiQ was created to celebrate that. We believe learning should feel
          welcoming, playful, and stress-free. That’s why we designed ClassiQ with colorful visuals, easy
          explanations, and tools that support special-needs learners. From curious beginners to growing achievers,
          ClassiQ is here to guide every student through a brighter, more confident learning journey.
        </p>

        <p class="lead1">
          Don’t forget to tap into ClassiQ AI — your new study buddy who never gets tired!
        </p>
      </div>
    </div>
  </div>
</section>



<section class="about-features">
  <div class="about-features-container">
    <h2 class="about-features-heading">How ClassIQ works!!!</h2>

    <div class="about-features-grid">
      <div class="feature-item">
        <img src="{{ asset('images/studentA.png') }}" alt="" class="feature-icon">
        <h3 class="feature-title">Personalized learning</h3>
        <p class="feature-text">
          Learn at your own pace with colorful lessons, fun visuals, and tools made to make studying easy and enjoyable for every learner.
        </p>
       

      </div>

      <div class="feature-item">
        <img src="{{ asset('images/teacherA.png') }}" alt="" class="feature-icon">
        <h3 class="feature-title">Trusted content</h3>
        <p class="feature-text">
          Create lessons, track progress, and guide students using smart tools designed to support teaching in a simple, organized, and powerful way.
        </p>


        
      </div>

      <div class="feature-item">
        <img src="{{ asset('images/robotA.png') }}" alt="" class="feature-icon">
        <h3 class="feature-title">Learning Assistant</h3>
        <p class="feature-text">
          Your friendly learning assistant — always ready to explain, simplify, and boost your understanding with just one tap.
        </p>
        
      </div>
    </div>
  </div>
</section>

<section id="courses" class="courses-section">
  <div class="courses-container">
    <h2 class="courses-heading">Popular topics</h2>

       <div class="courses-grid">
      @forelse ($homepageCourses as $course)
        <article class="course-card">
          @if ($course->thumbnail_path)
            <img src="{{ asset('storage/' . $course->thumbnail_path) }}" alt="" class="course-icon">
          @else
            {{-- fallback icon --}}
            <img src="{{ asset('images/Math.png') }}" alt="" class="course-icon">
          @endif

          <h3 class="course-title">
            {{ $course->title }}
          </h3>

          <p class="course-subtitle">
            Class {{ $course->class_level ?? '-' }} · {{ $course->subject ?? 'Subject' }}
          </p>
        </article>
      @empty
        {{-- fallback when no approved courses yet --}}
        <article class="course-card">
          <img src="{{ asset('images/Math.png') }}" alt="" class="course-icon">
          <h3 class="course-title">More courses coming soon</h3>
        </article>
      @endforelse
    </div>

  </div>
</section>

<section id="teachers" class="teacher-workspace">
  <div class="about-content-wrapper">
    
    <div class="agency-left-side">
      <h1 style="font-size: 30px; margin-bottom: 1rem; letter-spacing: 6px; color: #0f172a;"><span  style="color: #c9942a;">Teacher</span> Workspace</h1>
      <h2  style="font-size: 20px; margin-bottom: 1rem; letter-spacing: 3px; color: #0f172a;">
        A calm, powerful space for every teacher
      </h2>

      <p class="lead">
        On ClassiQ, teachers turn ordinary lessons into interactive, bilingual learning journeys.
        They create and share curriculum‑aligned content, set quick quizzes, and instantly see
        which students are struggling and which topics need more support. With simple dashboards
        and feedback tools, teachers can save time, personalize help, and keep both students and
        parents actively involved in learning.
      </p>

<button
  type="button"
  class="teacher-btn"
  onclick="window.location='{{ route('register.teacher') }}'">
  Start Teaching
</button>

      
    </div>

    
    <div class="agency-right-side">
      <div class="img">
        <img src="{{ asset('images/teacherM.png') }}" alt="Teacher workspace on ClassIQ">
      </div>
    </div>
  </div>
</section>

  <!-- about start -->
<section id="student" class="student">
  <div class="container">
    <div class="about-content-wrapper">
      
      <div class="agency-right-side">
        <div class="img">
          <img src="{{ asset('images/studentM.png') }}" alt="Students learning with ClassIQ">
        </div>
      </div>

     
      <div class="agency-left-side">
        <h1 style="font-size: 30px; margin-bottom: 1rem; letter-spacing: 6px; color: #0f172a;"><span style="color: #c9942a;">Student</span> Zone</h1>
      <h2  style="font-size: 20px; margin-bottom: 1rem; letter-spacing: 3px; color: #0f172a;">
        Learn in your language, at your pace, with a buddy that never gets tired of explaining.
      </h2>

        <p class="lead">
        Students use ClassiQ as a friendly study partner that explains tough science and math topics in simple Bangla and
        English. They can watch or read lessons, try quick quizzes, and instantly see their progress so they know where to focus
        next. Special‑needs students get extra support through calm layouts, large text, and flexible pacing, helping them learn
        comfortably and confidently at their own speed.
        </p>

<button
  type="button"
  class="Student-btn"
  onclick="window.location='{{ route('register.learner') }}'">
  Start Learning
</button>

        
       
      </div>
    </div>
  </div>
</section>

<!-- ai -->

<section id="AI" class="AI">
  <div class="about-content-wrapper">
    
    <div class="agency-left-side">
      <h1 style="font-size: 30px; margin-bottom: 1rem; letter-spacing: 6px; color: #0f172a;">Learning<span  style="color: #c9942a;">Assistant</span> </h1>
      <h2  style="font-size: 20px; margin-bottom: 1rem; letter-spacing: 3px; color: #0f172a;">
        AI that listens, learns, and teaches the way Bangladeshi students actually study.
      </h2>

      <p class="lead">
        AI in ClassiQ works like a quiet co‑teacher that learns from each student’s progress and gently adjusts the difficulty,
        pace, and questions they see. It can turn textbook content into smart quizzes, highlight weak topics for teachers and
        parents, and eventually support speech‑based interactions so students can ask questions naturally in Bangla or English.
      </p>

      <button class="teacher-btn">Activate</button>
    </div>

   
    <div class="agency-right-side">
      <div class="img">
        <img src="{{ asset('images/robo.png') }}" alt="robo workspace on ClassIQ">
      </div>
    </div>
  </div>
</section>
@endsection



