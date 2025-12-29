<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Center;
use App\Models\Committee;
use App\Models\CommitteeUser;
use App\Models\Question;
use App\Models\Questionset;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

	public function run(): void
	{

		User::create([
			'name' => 'إبراهيم البيماني',
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
			'password' => Hash::make('B@w200200'),
		]);

		User::create([
			'name' => 'طالب القنوبي',
			'user_type' => 'judge',
			'gender' => 'male',
			'national_id' => '300300300',
			'password' => Hash::make('B@w300300'),
		]);

		User::create([
			'name' => 'يوسف البلوشي',
			'user_type' => 'judge',
			'gender' => 'male',
			'national_id' => '400400400',
			'password' => Hash::make('B@w400400'),
		]);

		User::create([
			'name' => 'طاهر العزواني',
			'user_type' => 'judge',
			'gender' => 'male',
			'national_id' => '500500500',
			'password' => Hash::make('B@w500500'),
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
		]);

		CommitteeUser::create([
			'stage_id' => 2,
			'committee_id' => 1,
			'user_id' => 3,
		]);
		CommitteeUser::create([
			'stage_id' => 2,
			'committee_id' => 1,
			'user_id' => 4,
		]);
		CommitteeUser::create([
			'stage_id' => 2,
			'committee_id' => 1,
			'user_id' => 5,
		]);

		$data = [];

		foreach (range(1, 100) as $i) {
			$title = 'الباقة ' . $i;

			$data[] = [
				'title' => $title,
				'level' => 'حفظ',
				'selected' => 0,
			];

			$data[] = [
				'title' => $title,
				'level' => 'حفظ وتفسير',
				'selected' => 0,
			];
		}

		Questionset::insert($data);




		Question::create([
			'questionset_id' => 1,
			'content' => ' إقرا من قوله تعالى ( وإذ قال ربك للملائكة إني جاعل في الأرض خليفة ) إلى قوله تعالى ( أتجعل فيها من يفسد فيها ويسفك الدماء ) ',
			'difficulties' => 'السهلة',
		]);
		Question::create([
			'questionset_id' => 1,
			'content' => ' إقرا من قوله تعالى ( وإذ قال ربك للملائكة إني جاعل في الأرض خليفة ) إلى قوله تعالى ( أتجعل فيها من يفسد فيها ويسفك الدماء ) ',
			'difficulties' => 'المتوسطة',
		]);

		
	}
}
