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
            ],
        ];

        EvaluationElement::insert($evaluationElements);