<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Center;
use App\Models\Committee;
use App\Models\Profile;
use App\Models\Question;
use App\Models\Questionset;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

	public function run(): void{

		User::create([
		 'name' => 'إبراهيم البيماني',
		 'user_type' => 'admin',
		 'gender' => 'male',
		 'national_id' => '91171747',
		 'password' => Hash::make('91171747'),
		]);

		User::create([
		 'name' => 'أحمد البكاري',
		 'user_type' => 'commite',
		 'gender' => 'male',
		 'national_id' => '96702426',
		 'password' => Hash::make('96702426'),
		]);

		User::create([
		 'name' => 'طاهر العزواني',
		 'user_type' => 'commite',
		 'gender' => 'male',
		 'national_id' => '98184264',
		 'password' => Hash::make('98184264'),
		]);

		User::create([
		 'name' => 'سالم القصابي',
		 'user_type' => 'commite',
		 'gender' => 'male',
		 'national_id' => '92156779',
		 'password' => Hash::make('92156779'),
		]);

		Center::create([
			'title'=>'مسقط',
		]);
		Center::create([
			'title'=>'البريمي',
		]);
		Center::create([
			'title'=>'ظفار',
		]);
		Center::create([
			'title'=>'الداخلية',
		]);

		Questionset::create([
			'title'=>'الباقة 1',
			'level'=>'حفظ',
			'selected'=>0,
		]);
		Questionset::create([
			'title'=>'الباقة 2',
			'level'=>'حفظ',
			'selected'=>0,
		]);
		Questionset::create([
			'title'=>'الباقة 3',
			'level'=>'حفظ',
			'selected'=>0,
		]);
		Questionset::create([
			'title'=>'الباقة 1',
			'level'=>'حفظ وتفسير',
			'selected'=>0,
		]);

		Question::create([
			'questionset_id'=>1,
			'content'=>' إقرا من قوله تعالى ( وإذ قال ربك للملائكة إني جاعل في الأرض خليفة ) إلى قوله تعالى ( أتجعل فيها من يفسد فيها ويسفك الدماء ) ',
			'difficulties'=>'السهلة',
		]);
		Question::create([
			'questionset_id'=>1,
			'content'=>' إقرا من قوله تعالى ( وإذ قال ربك للملائكة إني جاعل في الأرض خليفة ) إلى قوله تعالى ( أتجعل فيها من يفسد فيها ويسفك الدماء ) ',
			'difficulties'=>'المتوسطة',
		]);

		Committee::create([
			'title'=>'اللجنة الأولى',
			'branch'=>'male',
			'level'=>'حفظ وتفسير',
			'center_id'=>1,
		]);
		Committee::create([
			'title'=>'اللجنة الثانية',
			'branch'=>'female',
			'level'=>'حفظ',
			'center_id'=>1,
		]);

	}
}
