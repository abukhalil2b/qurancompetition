$data = [];

        foreach (range(1, 14) as $i) {
            $title = 'الباقة '.$i;

            $data[] = [
                'title' => $title,
                'level' => 1,
                'selected' => 0,
            ];
        }
        Questionset::insert($data);

        foreach (range(1, 26) as $i) {
            $title = 'الباقة '.$i;
            $data[] = [
                'title' => $title,
                'level' => 2,
                'selected' => 0,
            ];
        }

        Questionset::insert($data);