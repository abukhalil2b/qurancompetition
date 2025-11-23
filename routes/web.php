<?php

use App\Http\Controllers\CenterController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\EvaluationElementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QuestionsetController;
use App\Http\Controllers\QuestionController;

use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'welcome'])
        ->name('welcome');

    Route::get('dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard');
});

Route::middleware(['auth'])->group(function () {

    // Students
    Route::get('student/index', [StudentController::class, 'index'])->name('student.index');
    Route::get('student/create', [StudentController::class, 'create'])->name('student.create');
    Route::post('student/store', [StudentController::class, 'store'])->name('student.store');
    Route::get('student/show/{student}', [StudentController::class, 'show'])->name('student.show');
    Route::get('student/edit/{student}', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('student/update/{student}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('student/destroy/{student}', [StudentController::class, 'destroy'])->name('student.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('judge/index', [JudgeController::class, 'index'])
        ->name('judge.index');

    Route::get('judge/show/{judge}', [JudgeController::class, 'show'])
        ->name('judge.show');

    Route::get('judge/create', [JudgeController::class, 'create'])->name('judge.create');
    Route::post('judge/store', [JudgeController::class, 'store'])->name('judge.store');

    Route::post('judge/assign-committee', [JudgeController::class, 'assignCommittee'])
        ->name('judge.assignCommittee');
});


Route::middleware(['auth'])->group(function () {
    Route::get('center/index', [CenterController::class, 'index'])
        ->name('center.index');

    Route::get('center/show/{center}', [CenterController::class, 'show'])
        ->name('center.show');
});

Route::middleware(['auth'])->group(function () {
    Route::get('committee/index', [CommitteeController::class, 'index'])
        ->name('committee.index');

    Route::post('committee/store', [CommitteeController::class, 'store'])
        ->name('committee.store');
});



Route::middleware(['auth'])->group(function () {

    // Questionsets CRUD
    Route::get('questionsets', [QuestionsetController::class, 'index'])->name('questionset.index');
    Route::get('questionsets/create', [QuestionsetController::class, 'create'])->name('questionset.create');
    Route::post('questionsets', [QuestionsetController::class, 'store'])->name('questionset.store');
    Route::get('questionsets/{questionset}', [QuestionsetController::class, 'show'])->name('questionset.show');
    Route::get('questionsets/{questionset}/edit', [QuestionsetController::class, 'edit'])->name('questionset.edit');
    Route::put('questionsets/{questionset}', [QuestionsetController::class, 'update'])->name('questionset.update');
    Route::delete('questionsets/{questionset}', [QuestionsetController::class, 'destroy'])->name('questionset.destroy');

    // Questions CRUD
    Route::get('questions/{questionset}/index', [QuestionController::class, 'index'])->name('question.index');
    Route::get('questions/{questionset}/create', [QuestionController::class, 'create'])->name('question.create');
    Route::post('questions/{questionset}', [QuestionController::class, 'store'])->name('question.store');
    Route::get('questions/{question}/edit', [QuestionController::class, 'edit'])->name('question.edit');
    Route::put('questions/{question}', [QuestionController::class, 'update'])->name('question.update');
    Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('question.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('evaluation_element/index', [EvaluationElementController::class, 'index'])
        ->name('evaluation_element.index');
        Route::get('evaluation_element/edit/{evaluationElement}', [EvaluationElementController::class, 'edit'])
        ->name('evaluation_element.edit');
        // PUT/PATCH: Update the specified resource in storage
    Route::put('evaluation_element/update/{evaluationElement}', [EvaluationElementController::class, 'update'])
        ->name('evaluation_element.update');
        
    // DELETE: Remove the specified resource from storage (The requested method)
    Route::delete('evaluation_element/destroy/{evaluationElement}', [EvaluationElementController::class, 'destroy'])
        ->name('evaluation_element.destroy');
    Route::get('evaluation_element/create', [EvaluationElementController::class, 'create'])
        ->name('evaluation_element.create');
    Route::post('evaluation_element/store', [EvaluationElementController::class, 'store'])
        ->name('evaluation_element.store');

});


Route::middleware(['auth'])->group(function () {
    Route::get('evaluation', [EvaluationElementController::class, 'evaluation'])
        ->name('evaluation');

});

require __DIR__ . '/auth.php';
