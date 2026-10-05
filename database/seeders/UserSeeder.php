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