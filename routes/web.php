<?php

use App\Http\Controllers\CenterController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationElementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QuestionsetController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StageController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {

    Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
    Route::get('final_report/{competitionId}', [HomeController::class, 'finalReport'])->name('final_report');
    Route::get('finish_student/{competitionId}', [HomeController::class, 'finishStudent'])->name('finish_student');
    Route::get('unfinish_student/{competitionId}', [HomeController::class, 'unFinishStudent'])->name('unfinish_student');
    Route::get('final_result', [HomeController::class, 'finalResult'])->name('final_result');
    Route::get('dashboard', [HomeController::class, 'dashboard'])
        ->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('student/start_evaluation/{student_question_selection_id}', [EvaluationController::class, 'startEvaluation'])->name('student.start_evaluation');
    Route::post('student/save_evaluation', [EvaluationController::class, 'saveEvaluation'])->name('student.save_evaluation');
    Route::get('student/show_evaluation/{student_question_selection_id}', [EvaluationController::class, 'showEvaluation'])->name('student.show_evaluation');
    Route::get('student/show_final_result/{competition}', [EvaluationController::class, 'showFinalResult'])->name('student.show_final_result');
   
    Route::get('student/evaluation-status/{id}',[EvaluationController::class, 'evaluationStatus']);
});

Route::middleware(['auth'])->group(function () {
    // Students
    Route::get('student/index', [StudentController::class, 'index'])->name('student.index');
    Route::get('student/present_index', [StudentController::class, 'presentIndex'])->name('student.present_index');
    Route::get('student/choose_questionset/{competition}', [StudentController::class, 'chooseQuestionset'])->name('student.choose_questionset');
    Route::get('student/save_questionset/{competition}/{questionset}', [StudentController::class, 'saveQuestionset'])->name('student.save_questionset');
    Route::get('student/create', [StudentController::class, 'create'])->name('student.create');
    Route::post('student/store', [StudentController::class, 'store'])->name('student.store');
    Route::get('student/show/{student}', [StudentController::class, 'show'])->name('student.show');
    Route::get('student/edit/{student}', [StudentController::class, 'edit'])->name('student.edit');
    Route::put('student/update/{student}', [StudentController::class, 'update'])->name('student.update');
    Route::delete('student/destroy/{student}', [StudentController::class, 'destroy'])->name('student.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::post('competition/student/present', [CompetitionController::class, 'present'])
        ->name('competition.student.present');
});

Route::middleware(['auth'])->group(function () {
    Route::get('user/index', [JudgeController::class, 'index'])
        ->name('user.index');

    Route::get('user/show/{user}', [JudgeController::class, 'show'])
        ->name('user.show');

    Route::get('user/create', [JudgeController::class, 'create'])->name('user.create');
    Route::post('user/store', [JudgeController::class, 'store'])->name('user.store');

    Route::post('judge/assign_committee', [JudgeController::class, 'assignCommittees'])
        ->name('judge.assign_committee');
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
    Route::get('questionset/index/{id?}', [QuestionsetController::class, 'index'])->name('questionset.index');
    Route::get('questionset/create', [QuestionsetController::class, 'create'])->name('questionset.create');
    Route::post('questionset', [QuestionsetController::class, 'store'])->name('questionset.store');
    Route::get('questionset/show/{questionset}', [QuestionsetController::class, 'show'])->name('questionset.show');
    Route::get('questionset/edit/{questionset}', [QuestionsetController::class, 'edit'])->name('questionset.edit');
    Route::put('questionset/{questionset}', [QuestionsetController::class, 'update'])->name('questionset.update');
    Route::delete('questionset/{questionset}', [QuestionsetController::class, 'destroy'])->name('questionset.destroy');

    // Questions CRUD
    Route::get('question/index', [QuestionController::class, 'index'])->name('question.index');
    Route::get('question/create/{questionset}', [QuestionController::class, 'create'])->name('question.create');
    Route::post('question/{questionset}', [QuestionController::class, 'store'])->name('question.store');
    Route::get('question/edit/{question}', [QuestionController::class, 'edit'])->name('question.edit');
    Route::put('question/{question}', [QuestionController::class, 'update'])->name('question.update');
    Route::delete('question/{question}', [QuestionController::class, 'destroy'])->name('question.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::get('evaluation_element/index/{id}', [EvaluationElementController::class, 'index'])
        ->name('evaluation_element.index');
    Route::get('evaluation_element/edit/{evaluationElement}', [EvaluationElementController::class, 'edit'])
        ->name('evaluation_element.edit');
    Route::put('evaluation_element/update/{evaluationElement}', [EvaluationElementController::class, 'update'])
        ->name('evaluation_element.update');
    Route::post('evaluation_element/store', [EvaluationElementController::class, 'store'])
        ->name('evaluation_element.store');
});





Route::middleware(['auth'])->group(function () {
    Route::get('stage/index', [StageController::class, 'index'])
        ->name('stage.index');

    Route::post('stage/{stage}/toggle-active', [StageController::class, 'toggleActive'])
        ->name('stage.toggle');
});


require __DIR__ . '/auth.php';
