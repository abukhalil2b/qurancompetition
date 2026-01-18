<?php

namespace Database\Seeders;


use App\Models\Center;
use App\Models\Committee;
use App\Models\CommitteeUser;
use App\Models\EvaluationElement;
use App\Models\Question;
use App\Models\Questionset;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
		User::create([
			'name' => 'الدعم الفني',
			'user_type' => 'admin',
			'gender' => 'male',
			'national_id' => '91171747',
			'password' => Hash::make('alBim@n!'),
		]);

		User::create([
			'name' => 'عبدالله الهنائي',
			'user_type' => 'judge',
			'gender' => 'male',
			'national_id' => '200200200',
			'password' => Hash::make('200200200'),
		]);

		User::create([
			'name' => 'عبدالله القنوبي',
			'user_type' => 'judge',
			'gender' => 'male',
			'national_id' => '300300300',
			'password' => Hash::make('300300300'),
		]);

		User::create([
			'name' => 'يوسف البلوشي',
			'user_type' => 'judge',
			'gender' => 'male',
			'national_id' => '400400400',
			'password' => Hash::make('400400400'),
		]);

		User::create([
			'name' => 'طاهر العزواني',
			'user_type' => 'judge',
			'gender' => 'male',
			'national_id' => '500500500',
			'password' => Hash::make('500500500'),
		]);

		User::create([
			'name' => 'المنظم ذكور ',
			'user_type' => 'organizer',
			'gender' => 'male',
			'national_id' => '600600600',
			'password' => Hash::make('600600600'),
		]);

		User::create([
			'name' => 'المنظم إناث ',
			'user_type' => 'organizer',
			'gender' => 'female',
			'national_id' => '700700700',
			'password' => Hash::make('700700700'),
		]);

		Stage::create([
			'title' => 'التصفيات الأولية',
			'active' => 0,
		]);

		Stage::create([
			'title' => 'التصفيات النهائية',
			'active' => 1,
		]);

		Center::create([
			'title' => 'مسقط',
		]);

		Committee::create([
			'title' => 'اللجنة الأولى (مركز مسقط)',
			'center_id' => 1,
			'gender' => 'males',
		]);

		CommitteeUser::create([
			'stage_id' => 2,
			'committee_id' => 1,
			'user_id' => 2,
			'is_judge_leader' => 1,
			'role' => 'judge',
		]);

		CommitteeUser::create([
			'stage_id' => 2,
			'committee_id' => 1,
			'user_id' => 3,
			'is_judge_leader' => 0,
			'role' => 'judge',
		]);
		CommitteeUser::create([
			'stage_id' => 2,
			'committee_id' => 1,
			'user_id' => 4,
			'is_judge_leader' => 0,
			'role' => 'judge',
		]);
		CommitteeUser::create([
			'stage_id' => 2,
			'committee_id' => 1,
			'user_id' => 5,
			'is_judge_leader' => 0,
			'role' => 'judge',
		]);
		CommitteeUser::create([
			'stage_id' => 2,
			'committee_id' => 1,
			'user_id' => 6,
			'is_judge_leader' => 0,
			'role' => 'organizer',
		]);
		CommitteeUser::create([
			'stage_id' => 2,
			'committee_id' => 1,
			'user_id' => 7,
			'is_judge_leader' => 0,
			'role' => 'organizer',
		]);

		$data = [];

		foreach (range(1, 14) as $i) {
			$title = 'الباقة ' . $i;

			$data[] = [
				'title' => $title,
				'level' => 'حفظ',
				'selected' => 0,
			];
		}
		Questionset::insert($data);

		foreach (range(1, 26) as $i) {
			$title = 'الباقة ' . $i;
			$data[] = [
				'title' => $title,
				'level' => 'حفظ وتفسير',
				'selected' => 0,
			];
		}

		Questionset::insert($data);

		$evaluationElements = [
			[
				'title' => 'الحفظ',
				'max_score' => 14,
				'scope' => 'question',
			],
			[
				'title' => 'المخارج والصفات',
				'max_score' => 3,
				'scope' => 'competition',
			],
			[
				'title' => 'الوقف والابتداء',
				'max_score' => 2,
				'scope' => 'competition',
			],
			[
				'title' => 'تناسق الأداء وحسن الصوت',
				'max_score' => 1,
				'scope' => 'competition',
			]
		];

		EvaluationElement::insert($evaluationElements);
    }
}
