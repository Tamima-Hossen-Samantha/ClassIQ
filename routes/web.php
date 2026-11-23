

<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Auth controllers
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// Admin


use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminTeacherController;


// teacjer

use App\Http\Controllers\Teacher\Auth\TeacherRegisterController;
use App\Http\Controllers\Teacher\Auth\TeacherLoginController;
use App\Http\Controllers\Teacher\TeacherDashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


// learner
use App\Http\Controllers\Learner\Auth\LearnerRegisterController;
use App\Http\Controllers\Learner\Auth\LearnerLoginController;
use App\Http\Controllers\Learner\LearnerDashboardController;


// Landing page
Route::get('/', [LandingController::class, 'index'])->name('Home');






// Admin: one-time token login + protected module
Route::prefix('admin')->name('admin.')->group(function () {

    // /admin/login from browser -> never show a form
    Route::get('/login', function () {
        return redirect()->route('Home');
    })->name('login');

    // one-time URL: /admin/login/{token}
    // IMPORTANT: no guest:admin middleware here
   Route::group([], function () {
    Route::get('/login/{token}', [AdminLoginController::class, 'showLoginForm'])
        ->name('login.token');

    Route::post('/login/{token}', [AdminLoginController::class, 'login'])
        ->name('login.token.submit');
});


    //  admin layout
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::post('/logout', [AdminLoginController::class, 'logout'])
            ->name('logout');

        Route::get('/teachers', [AdminTeacherController::class, 'index'])
            ->name('teachers');
        Route::post('/teachers/{teacher}/approve', [AdminTeacherController::class, 'approve'])
            ->name('teachers.approve');
        Route::delete('/teachers/{teacher}', [AdminTeacherController::class, 'destroy'])
            ->name('teachers.destroy');

        Route::post('/teachers/{teacher}/reject', [AdminTeacherController::class, 'reject'])
            ->name('teachers.reject');

        Route::post('/teachers/{teacher}/toggle-suspend', [AdminTeacherController::class, 'toggleSuspend'])
            ->name('teachers.toggle-suspend');

        Route::post('/teachers/{teacher}/verify-documents', [AdminTeacherController::class, 'verifyDocuments'])
            ->name('teachers.verify-documents');

        Route::get('/teachers/{teacher}', [AdminTeacherController::class, 'show'])
            ->name('teachers.show');

        Route::put('/teachers/{teacher}', [AdminTeacherController::class, 'updateProfile'])
            ->name('teachers.update');

// studentsection


    Route::get('/students', [AdminDashboardController::class, 'studentsIndex'])
        ->name('students'); // used by sidebar: route('admin.students')

    Route::post('/students/{learner}/toggle-status', [AdminDashboardController::class, 'studentsToggleStatus'])
        ->name('students.toggle-status'); // suspend / activate

    Route::post('/students/{learner}/change-class', [AdminDashboardController::class, 'studentsChangeClass'])
        ->name('students.change-class'); // change class/section



        //  courses 
   Route::get('/courses', [AdminDashboardController::class, 'coursesIndex'])
    ->name('courses');   


    Route::post('/courses/{course}/approve', [AdminDashboardController::class, 'coursesApprove'])
        ->name('courses.approve');

    Route::post('/courses/{course}/reject', [AdminDashboardController::class, 'coursesReject'])
        ->name('courses.reject');

    Route::post('/courses/{course}/toggle-featured', [AdminDashboardController::class, 'coursesToggleFeatured'])
        ->name('courses.toggle-featured');

    Route::post('/courses/{course}/toggle-visibility', [AdminDashboardController::class, 'coursesToggleVisibility'])
        ->name('courses.toggle-visibility');


        Route::get('/subjects-classes', fn () => view('admin.subjects_classes'))->name('subjects_classes');
        
            // CHAPTER/TOPIC MODERATION
    Route::get('/courses/{course}/chapters', [AdminDashboardController::class, 'moderateChapters'])
        ->name('courses.chapters.moderate');

    Route::post('/chapters/{chapter}/moderate', [AdminDashboardController::class, 'chapterModerate'])
        ->name('chapters.moderate');

    Route::post('/topics/{topic}/moderate', [AdminDashboardController::class, 'topicModerate'])
        ->name('topics.moderate');

    Route::delete('/topics/{topic}', [AdminDashboardController::class, 'topicDestroyByAdmin'])
        ->name('topics.destroy.admin');
});

// lesson
        Route::get('/lessons', [AdminDashboardController::class, 'lessonsIndex'])
        ->name('lessons');

    Route::get('/lessons/{lesson}', [AdminDashboardController::class, 'lessonsShow'])
        ->name('lessons.show');

    Route::post('/lessons/{lesson}/moderate', [AdminDashboardController::class, 'lessonsModerate'])
        ->name('lessons.moderate');

    Route::delete('/lessons/{lesson}', [AdminDashboardController::class, 'lessonsDestroy'])
        ->name('lessons.destroy');


        Route::get('/quizzes-homework', fn () => view('admin.quizzes_homework'))->name('quizzes_homework');
        Route::get('/accessibility', fn () => view('admin.accessibility'))->name('accessibility');
        Route::get('/notifications', fn () => view('admin.notifications'))->name('notifications');
        Route::get('/analytics', fn () => view('admin.analytics'))->name('analytics');
    });






// teachjer

Route::prefix('teacher')->name('teacher.')->group(function () {

    // Register + login, NO guest middleware for now
    Route::get('/register', [TeacherRegisterController::class, 'showRegistrationForm'])
        ->name('register');

    Route::post('/register', [TeacherRegisterController::class, 'register'])
        ->name('register.submit');

    Route::get('/login', [TeacherLoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [TeacherLoginController::class, 'login'])
        ->name('login.submit');

    // Email verification (must be logged in as teacher)
    Route::middleware('auth:teacher')->group(function () {
        Route::get('/email/verify', function () {
            return view('auth.verify-email');
        })->name('verification.notice');

        Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
            $request->fulfill();
            return redirect()->route('teacher.login')
                ->with('status', 'Email verified, please wait for admin approval.');
        })->middleware('signed')->name('verification.verify');

        Route::post('/email/verification-notification', function (Request $request) {
            $request->user('teacher')->sendEmailVerificationNotification();
            return back()->with('status', 'Verification link sent.');
        })->middleware('throttle:6,1')->name('verification.send');
    });

    // Dashboard protected only by auth:teacher for now
    Route::middleware(['auth:teacher'])->group(function () {
        Route::get('/dashboard', [TeacherDashboardController::class, 'index'])
            ->name('dashboard');

    // COURSES
        Route::get('/courses', [TeacherDashboardController::class, 'coursesIndex'])
            ->name('courses');                    // list page (teacher.courses)
        Route::get('/courses/create', [TeacherDashboardController::class, 'coursesCreate'])
            ->name('courses.create');            // create form (teacher.course_create)
        Route::post('/courses', [TeacherDashboardController::class, 'coursesStore'])
            ->name('courses.store');             // save new course
        Route::get('/courses/{course}/edit', [TeacherDashboardController::class, 'coursesEdit'])
            ->name('courses.edit');              // edit form
        Route::put('/courses/{course}', [TeacherDashboardController::class, 'coursesUpdate'])
            ->name('courses.update');            // update existing
        Route::delete('/courses/{course}', [TeacherDashboardController::class, 'coursesDestroy'])
            ->name('courses.destroy');           // delete
        Route::post('/courses/{course}/toggle-status', [TeacherDashboardController::class, 'coursesToggleStatus'])
            ->name('courses.toggle-status');     // publish/unpublish
        Route::post('/courses/{course}/regenerate-code', [TeacherDashboardController::class, 'coursesRegenerateCode'])
            ->name('courses.regenerate-code');   // new class code

Route::post('/courses/{course}/request-publish', [TeacherDashboardController::class, 'coursesRequestPublish'])
    ->name('courses.request-publish');
    


// CHAPTERS
Route::get('/courses/{course}/chapters', [TeacherDashboardController::class, 'chaptersIndex'])
    ->name('courses.chapters.index');

Route::post('/courses/{course}/chapters', [TeacherDashboardController::class, 'chaptersStore'])
    ->name('courses.chapters.store');

Route::put('/chapters/{chapter}', [TeacherDashboardController::class, 'chaptersUpdate'])
    ->name('chapters.update');

Route::post('/chapters/{chapter}/reorder', [TeacherDashboardController::class, 'chaptersReorder'])
    ->name('chapters.reorder');

Route::post('/chapters/{chapter}/toggle-status', [TeacherDashboardController::class, 'chaptersToggleStatus'])
    ->name('chapters.toggle-status');

Route::delete('/chapters/{chapter}', [TeacherDashboardController::class, 'chaptersDestroy'])
    ->name('chapters.destroy');

    

// TOPICS
Route::post('/chapters/{chapter}/topics', [TeacherDashboardController::class, 'topicsStore'])
    ->name('chapters.topics.store');

Route::put('/topics/{topic}', [TeacherDashboardController::class, 'topicsUpdate'])
    ->name('topics.update');

Route::post('/topics/{topic}/reorder', [TeacherDashboardController::class, 'topicsReorder'])
    ->name('topics.reorder');

Route::delete('/topics/{topic}', [TeacherDashboardController::class, 'topicsDestroy'])
    ->name('topics.destroy');

       // LESSONS

 Route::get('/lessons', [TeacherDashboardController::class, 'lessonsIndex'])
            ->name('lessons.index');

        Route::get('/topics/{topic}/lessons/create', [TeacherDashboardController::class, 'lessonCreate'])
            ->name('topics.lessons.create');

        Route::post('/topics/{topic}/lessons', [TeacherDashboardController::class, 'lessonStore'])
            ->name('topics.lessons.store');

        Route::get('/lessons/{lesson}/edit', [TeacherDashboardController::class, 'lessonEdit'])
            ->name('lessons.edit');

        Route::put('/lessons/{lesson}', [TeacherDashboardController::class, 'lessonUpdate'])
            ->name('lessons.update');

        Route::delete('/lessons/{lesson}', [TeacherDashboardController::class, 'lessonDestroy'])
            ->name('lessons.destroy');

            // doubt

Route::get('/doubts', [TeacherDashboardController::class, 'doubtsIndex'])
    ->name('doubts.index');

Route::post('/doubts/{doubt}/reply', [TeacherDashboardController::class, 'doubtsReply'])
    ->name('doubts.reply');

        Route::view('/quizzes', 'teacher.quizzes')->name('quizzes');
        Route::view('/students', 'teacher.students')->name('students');
        Route::view('/progress', 'teacher.progress')->name('progress');
        Route::view('/notifications', 'teacher.notifications')->name('notifications');
        Route::view('/settings', 'teacher.settings')->name('settings');

        Route::post('/logout', [TeacherLoginController::class, 'logout'])
        ->name('logout');
    });
});

// Global auth (learner/teacher register + main login)


Route::get('/register/learner', [RegisterController::class, 'showLearnerForm'])
    ->name('register.learner');


Route::get('/register/teacher', [RegisterController::class, 'showTeacherForm'])
    ->name('register.teacher');

Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');


// LEARNER MODULE
Route::prefix('learner')->name('learner.')->group(function () {

    // Public routes: only for guests (not logged in as learner)
    Route::middleware('guest:learner')->group(function () {
        Route::get('/register', [LearnerRegisterController::class, 'showRegistrationForm'])
            ->name('register');

        Route::post('/register', [LearnerRegisterController::class, 'register'])
            ->name('register.submit');

        Route::get('/login', [LearnerLoginController::class, 'showLoginForm'])
            ->name('login');

        Route::post('/login', [LearnerLoginController::class, 'login'])
            ->name('login.submit');
    });

    // Protected learner area: must be logged in as learner
    Route::middleware('auth:learner')->group(function () {
        Route::get('/dashboard',     [LearnerDashboardController::class, 'dashboard'])->name('dashboard');

       Route::get('/classes', [LearnerDashboardController::class, 'classes'])->name('classes');
    Route::post('/classes/join', [LearnerDashboardController::class, 'joinCourse'])
        ->name('classes.join');
        Route::get('/subjects',      [LearnerDashboardController::class, 'subjects'])->name('subjects');
        Route::get('/lessons', [LearnerDashboardController::class, 'lessons'])
            ->name('lessons');
        Route::get('/lessons/{lesson}', [LearnerDashboardController::class, 'showLesson'])
            ->name('lessons.show');
        Route::get('/quizzes',       [LearnerDashboardController::class, 'quizzes'])->name('quizzes');
        Route::get('/progress',      [LearnerDashboardController::class, 'progress'])->name('progress');
        Route::get('/accessibility',   [LearnerDashboardController::class, 'accessibility'])->name('accessibility');
        Route::get('/ai',            [LearnerDashboardController::class, 'ai'])->name('ai');
        Route::get('/notifications', [LearnerDashboardController::class, 'notifications'])->name('notifications');
        Route::get('/profile',       [LearnerDashboardController::class, 'profile'])->name('profile');


        // NEW: join course by class code
        Route::get('/join-course', [LearnerDashboardController::class, 'showJoinCourseForm'])
            ->name('join-course.form');

        Route::post('/join-course', [LearnerDashboardController::class, 'joinCourse'])
            ->name('join-course.submit');

// rtopic completed

Route::post('/topics/{topic}/complete', [LearnerDashboardController::class, 'markComplete'])
            ->name('topics.complete');

            Route::get('/courses/{course}/chapters', [LearnerDashboardController::class, 'courseChapters'])
    ->name('course.chapters');

    
//   doubt chat  
 

Route::post('/lessons/{lesson}/doubts', [LearnerDashboardController::class, 'storeDoubt'])
    ->name('lessons.doubts.store');

        Route::post('/logout', [LearnerLoginController::class, 'logout'])
            ->name('logout');
    });

    Route::get('/lessons', [LearnerDashboardController::class, 'lessons'])
            ->name('lessons');

    Route::post('/topics/{topic}/complete', [LearnerDashboardController::class, 'markComplete'])
            ->name('topics.complete');

Route::post('/accessibility', [LearnerDashboardController::class, 'accessibilityUpdate'])
    ->name('accessibility.update');

});
