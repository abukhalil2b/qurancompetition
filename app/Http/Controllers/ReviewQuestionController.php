<?php

namespace App\Http\Controllers;

use App\Models\Question;

class ReviewQuestionController extends Controller
{
    public function reviewQuestionIndex()
    {
        $loggedUser = auth()->user();

        if ($loggedUser->user_type != 'reviewer') {
            abort(403);
        }

        $questions = Question::with(['questionset', 'surat'])
            ->orderBy('id')
            ->get();

        return view('review.question.index', compact('questions'));
    }

    public function reviewQuestionShow(Question $question)
    {
        $loggedUser = auth()->user();

        if ($loggedUser->user_type != 'reviewer') {
            abort(403);
        }

        $question->load([
            'questionset',
            'surat',
        ]);

        $ayas = $question->ayas()
            ->whereBetween('number', [
                $question->aya_from,
                $question->aya_to,
            ])
            ->orderBy('number')
            ->get();

        // All questions for the right-side navigation
        $questions = Question::with('surat')
            ->orderBy('id')
            ->get();

        return view('review.question.show', compact(
            'question',
            'ayas',
            'questions'
        ));
    }
}
