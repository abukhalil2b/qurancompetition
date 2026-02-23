<?php

namespace Tests\Feature;

use App\Models\Center;
use App\Models\Committee;
use App\Models\Competition;
use App\Models\EvaluationElement;
use App\Models\JudgeEvaluation;
use App\Models\Question;
use App\Models\Questionset;
use App\Models\Stage;
use App\Models\Student;
use App\Models\StudentQuestionSelection;
use App\Models\User;
use App\Services\JudgeScoreCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class JudgeScoreCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_calculates_scores_per_judge()
    {
        // 1. Setup Dependencies
        $center = Center::create(['title' => 'Main Center']);
        $stage = Stage::create(['title' => 'Final Stage']);
        $committee = Committee::create([
            'title' => 'Committee 1',
            'center_id' => $center->id
        ]);

        $student = Student::create([
            'name' => 'Test Student',
            'level' => 'حفظ', // Arabic
            'gender' => 'male',
            'dob' => now()->subYears(10),
            'registration_date' => now(),
        ]);

        $competition = Competition::create([
            'student_id' => $student->id,
            'center_id' => $center->id,
            'stage_id' => $stage->id,
            'committee_id' => $committee->id,
            'level' => 'memorize', // English enum as per migration
        ]);

        $judge1 = User::create([
            'name' => 'Judge One',
            'email' => 'judge1@example.com',
            'password' => Hash::make('password'),
        ]);

        $judge2 = User::create([
            'name' => 'Judge Two',
            'email' => 'judge2@example.com',
            'password' => Hash::make('password'),
        ]);

        $questionset = Questionset::create(['title' => 'Set 1', 'level' => 'حفظ']);
        $question = Question::create(['content' => 'Q1', 'questionset_id' => $questionset->id]);
        $element = EvaluationElement::create(['title' => 'Element 1', 'max_score' => 20]);

        $selection = StudentQuestionSelection::create([
            'competition_id' => $competition->id,
            'question_id' => $question->id,
            'position' => 1,
            'is_passed' => 1,
            'level' => 'حفظ'
        ]);

        // Judge 1 Deducts 2 points -> Score 18
        JudgeEvaluation::create([
            'student_question_selection_id' => $selection->id,
            'evaluation_element_id' => $element->id,
            'judge_id' => $judge1->id,
            'reduct_point' => 2
        ]);

        // Judge 2 Deducts 5 points -> Score 15
        JudgeEvaluation::create([
            'student_question_selection_id' => $selection->id,
            'evaluation_element_id' => $element->id,
            'judge_id' => $judge2->id,
            'reduct_point' => 5
        ]);

        // 2. Run Calculator
        $result = JudgeScoreCalculator::calculateStudentResults($competition);

        // 3. Verify
        $this->assertCount(2, $result);

        // Judge 1
        $j1Result = $result->firstWhere('judge_id', $judge1->id);
        $this->assertEquals(18, $j1Result['memorization_score']);
        $this->assertEquals(18, $j1Result['total_score']);

        // Judge 2
        $j2Result = $result->firstWhere('judge_id', $judge2->id);
        $this->assertEquals(15, $j2Result['memorization_score']);
        $this->assertEquals(15, $j2Result['total_score']);
    }

    public function test_it_handles_failed_questions()
    {
        $center = Center::create(['title' => 'Main Center']);
        $stage = Stage::create(['title' => 'Final Stage']);
        $committee = Committee::create(['title' => 'Committee 1', 'center_id' => $center->id]);

        $student = Student::create([
            'name' => 'Student 2',
            'level' => 'حفظ',
            'gender' => 'male',
        ]);

        $competition = Competition::create([
            'student_id' => $student->id,
            'center_id' => $center->id,
            'stage_id' => $stage->id,
            'committee_id' => $committee->id,
            'level' => 'memorize',
        ]);

        $judge1 = User::create([
            'name' => 'Judge One',
            'email' => 'judge1a@example.com',
            'password' => Hash::make('password'),
        ]);

        $element = EvaluationElement::create(['title' => 'E1', 'max_score' => 20]);
        $questionset = Questionset::create(['title' => 'Set 1', 'level' => 'حفظ']);
        $question = Question::create(['content' => 'Q1', 'questionset_id' => $questionset->id]);

        // Failed Question
        $selection = StudentQuestionSelection::create([
            'competition_id' => $competition->id,
            'question_id' => $question->id,
            'is_passed' => 0, // FAILED
            'position' => 1,
            'level' => 'حفظ'
        ]);

        JudgeEvaluation::create([
            'student_question_selection_id' => $selection->id,
            'evaluation_element_id' => $element->id,
            'judge_id' => $judge1->id,
            'reduct_point' => 0
        ]);

        $result = JudgeScoreCalculator::calculateStudentResults($competition);

        $this->assertCount(0, $result);
    }
}
