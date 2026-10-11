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
            'password' => Hash::make('t@Hk66m20'),
        ]);

        User::create([
            'name' => 'أيمن المشيفري',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '3000',
            'password' => Hash::make('t@Hk66m30'),
        ]);

        User::create([
            'name' => 'سعيد آل ثاني',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '4000',
            'password' => Hash::make('t@Hk66m40'),
        ]);

        User::create([
            'name' => 'أحمد الهنائي',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '5000',
            'password' => Hash::make('t@Hk66m50'),
        ]);

        User::create([
            'name' => 'عبدالله الهنائي',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '6000',
            'password' => Hash::make('t@Hk66m60'),
        ]);

        User::create([
            'name' => 'طاهر العزواني',
            'user_type' => 'judge',
            'gender' => 'male',
            'national_id' => '7000',
            'password' => Hash::make('t@Hk66m70'),
        ]);
        
        User::create([
            'name' => 'المنظم ذكور ',
            'user_type' => 'organizer',
            'gender' => 'male',
            'national_id' => '8000',
            'password' => Hash::make('8000@8000'),
        ]);

        User::create([
            'name' => 'المنظم إناث ',
            'user_type' => 'organizer',
            'gender' => 'female',
            'national_id' => '9000',
            'password' => Hash::make('9000@9000'),
        ]);

        User::create([
            'name' => 'مراجع الأسئلة',
            'user_type' => 'reviewer',
            'gender' => 'male',
            'national_id' => '10000',
            'password' => Hash::make('10000@10000'),
        ]);
       
    }
}
