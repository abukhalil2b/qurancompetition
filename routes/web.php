<?php

use App\Http\Controllers\CenterController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\CompetitionResultController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationElementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\MemorizationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\QuestionsetController;
use App\Http\Controllers\TafseerController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StageController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->group(function () {
    
    // --- 1. General Dashboard ---
    Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');


    // --- 2. Memorization (Hifz) Evaluation ---memorization.start
    // Renamed controller to MemorizationController for clarity
    Route::get('memorization/start/{student_question_selection_id}', [MemorizationController::class, 'start'])->name('memorization.start');
    Route::post('memorization/store', [MemorizationController::class, 'store'])->name('memorization.store');
    Route::get('memorization/show/{student_question_selection_id}', [MemorizationController::class, 'show'])->name('memorization.show');


    // --- 3. Tafseer Evaluation ---
    Route::get('tafseer/start/{competition}', [TafseerController::class, 'start'])->name('tafseer.start');
    Route::post('tafseer/store', [TafseerController::class, 'store'])->name('tafseer.store');

    // A. Individual Student Result
    Route::get('result/show/{competition}', [CompetitionResultController::class, 'show'])->name('result.show');
    Route::post('result/finalize/{competition}', [CompetitionResultController::class, 'finalize'])->name('competition.finalize');
    
    // B. Admin/Management Actions (Moved from HomeController)
    Route::get('result/reopen/{competition}', [CompetitionResultController::class, 'unFinishStudent'])->name('unfinish_student');
    
    // C. Public/Dashboard Lists (The one you asked about)
    Route::get('results/list', [CompetitionResultController::class, 'index'])->name('finished_student_list');
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

    Route::get('committee/show/{committee}', [CommitteeController::class, 'show'])
        ->name('committee.show');

    Route::post('committee/store', [CommitteeController::class, 'store'])
        ->name('committee.store');

    Route::put('committee/{committee}', [CommitteeController::class, 'update'])->name('committee.update');

    Route::post('committee/{committee}/set-leader', [CommitteeController::class, 'setLeader'])
        ->name('committee.set-leader');

    Route::delete('committee/{committee}/remove-judge/{user}', [CommitteeController::class, 'removeJudge'])
        ->name('committee.remove-judge');
});



Route::middleware(['auth'])->group(function () {

    // Questionsets CRUD
    Route::get('questionset/index/{id?}', [QuestionsetController::class, 'index'])->name('questionset.index');
    Route::get('questionset/create/{level}', [QuestionsetController::class, 'create'])->name('questionset.create');
    Route::post('questionset', [QuestionsetController::class, 'store'])->name('questionset.store');
    Route::get('questionset/show/{questionset}', [QuestionsetController::class, 'show'])->name('questionset.show');
    Route::get('questionset/print', [QuestionsetController::class, 'print'])->name('questionset.print');
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
    Route::get('stage/index', [StageController::class, 'index'])
        ->name('stage.index');

    Route::post('stage/{stage}/toggle-active', [StageController::class, 'toggleActive'])
        ->name('stage.toggle');
});


require __DIR__ . '/auth.php';
