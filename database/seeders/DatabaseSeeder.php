<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        User::create([
            'name' => 'الدعم الفني',
            'user_type' => 'admin',
            'gender' => 'male',
            'national_id' => '9117',
            'password' => Hash::make('alBi@1747'),
        ]);

        User::create([
            'name' => 'عبدالله الحوسني',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '2000',
            'password' => Hash::make('20@10'),
        ]);

        User::create([
            'name' => 'أيمن المشيفري',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '3000',
            'password' => Hash::make('30@20'),
        ]);

        User::create([
            'name' => 'سعيد آل ثاني',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '4000',
            'password' => Hash::make('40@30'),
        ]);

        User::create([
            'name' => 'أحمد الهنائي',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '5000',
            'password' => Hash::make('50@40'),
        ]);

        User::create([
            'name' => 'عبدالله الهنائي',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '6000',
            'password' => Hash::make('60@50'),
        ]);

        User::create([
            'name' => 'طاهر العزواني',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '7000',
            'password' => Hash::make('70@60'),
        ]);
        
        User::create([
            'name' => 'المنظم ذكور ',
            'user_type' => 'organizer',
            'gender' => 'male',
            'national_id' => '8000',
            'password' => Hash::make('80@70'),
        ]);

        User::create([
            'name' => 'المنظم إناث ',
            'user_type' => 'organizer',
            'gender' => 'female',
            'national_id' => '9000',
            'password' => Hash::make('90@80'),
        ]);

        User::create([
            'name' => 'مراجع الأسئلة',
            'user_type' => 'reviewer',
            'gender' => 'male',
            'national_id' => '10000',
            'password' => Hash::make('100@90'),
        ]);
    }
}
