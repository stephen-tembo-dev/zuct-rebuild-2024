<?php

use App\Http\Controllers\Academics\CourseLevelController;
use App\Http\Controllers\Academics\DepartmentController;
use App\Http\Controllers\Academics\IntakeController;
use App\Http\Controllers\Academics\PeriodTypeController;
use App\Http\Controllers\Academics\PrerequisiteController;
use App\Http\Controllers\Academics\ProgramController;
use App\Http\Controllers\Academics\ProgramCoursesController;
use App\Http\Controllers\Academics\QualificationController;
use App\Http\Controllers\Academics\SchoolController;
use App\Http\Controllers\Academics\StudyModeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Academics\CourseController;



Route::get('/', function () { return redirect()->route('login'); });

Auth::routes(['register' => false]);
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    Route::resource('courses', CourseController::class);
    Route::resource('programs', ProgramController::class);
    Route::resource('study-modes', StudyModeController::class);
    Route::resource('period-types', PeriodTypeController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('qualifications', QualificationController::class);
    Route::resource('levels', CourseLevelController::class);
    Route::resource('intakes', IntakeController::class);
    Route::resource('schools', SchoolController::class);
    Route::resource('prerequisites', PrerequisiteController::class);
    Route::resource('program-courses', ProgramCoursesController::class);



