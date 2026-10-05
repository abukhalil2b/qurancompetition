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