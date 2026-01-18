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

class UpdateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id'       => 1,
                'national_id'    => '1000',
                'password' => 'xxx@xxx',
            ],
            [
                'id'       => 2,
                'national_id'    => '2000',
                'password' => '20xx@20xx',
            ],
            [
                'id'       => 3,
                'national_id'    => '3000',
                'password' => '30xx@30xx',
            ],
            [
                'id'       => 4,
                'national_id'    => '4000',
                'password' => '40xx@40xx',
            ],
            [
                'id'       => 5,
                'national_id'    => '5000',
                'password' => '50xx@50xx',
            ],
            [
                'id'       => 6,
                'national_id'    => '6000',
                'password' => '6000@6000',
            ],
            [
                'id'       => 7,
                'national_id'    => '7000',
                'password' => '7000@7000',
            ],
        ];

        foreach ($users as $user) {
            User::where('id', $user['id'])->update([
                'national_id'    => $user['national_id'],
                'password' => Hash::make($user['password']),
            ]);
        }
		
    }
}
