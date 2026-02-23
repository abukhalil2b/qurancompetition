<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
</head>

<body dir="rtl">
    <table>
        <thead>
            {{-- Main Title --}}
            <tr>
                <th colspan="{{ 6 + $maxJudges * 2 }}" style="text-align: center; height: 40px; vertical-align: middle;">
                    {{ $title }}
                </th>
            </tr>
            {{-- Header Row 1 --}}
            <tr>
                <th rowspan="2" style="width: 5px; text-align: center; vertical-align: middle;">#</th>
                <th rowspan="2" style="width: 30px; text-align: center; vertical-align: middle;">اسم المتسابق</th>
                <th rowspan="2" style="width: 15px; text-align: center; vertical-align: middle;">الجنس</th>
                <th rowspan="2" style="width: 15px; text-align: center; vertical-align: middle;">باقة المشاركة</th>
                <th rowspan="2" style="width: 15px; text-align: center; vertical-align: middle;">المستوى</th>

                {{-- Dynamic Judges --}}
                @foreach ($judges as $judge)
                    <th colspan="2" style="text-align:center; border:1px solid #000;">
                        المحكم: {{ $judge['judge_name'] }}
                    </th>
                @endforeach

                {{-- Empty columns to keep layout consistent --}}
                @for ($i = $judges->count(); $i < $maxJudges; $i++)
                    <th colspan="2" style="border:1px solid #000;"></th>
                @endfor


                <th rowspan="2" style="width: 15px; text-align: center; vertical-align: middle;">المجموع النهائي</th>
                <th rowspan="2" style="width: 15px; text-align: center; vertical-align: middle;">النسبة</th>
            </tr>
            {{-- Header Row 2 --}}
            <tr>
                {{-- Evaluation Elements per Judge --}}
                @for ($i = 1; $i <= $maxJudges; $i++)
                    <th style="text-align: center; border: 1px solid #000000;">حفظ</th>
                    <th style="text-align: center; border: 1px solid #000000;">تفسير</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($competitions as $index => $comp)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: right;">{{ $comp->student->name ?? '' }}</td>
                    <td style="text-align: center;">{{ $comp->student->gender == 'male' ? 'ذكر' : 'أنثى' }}</td>
                    <td style="text-align: center;">{{ $comp->questionset->title ?? '-' }}</td>
                    <td style="text-align: center;">{{ __($comp->level) }}</td>

                    @foreach ($judges as $judge)
                        @php
                            $scoreData = $comp->detailedScores->firstWhere('judge_id', $judge['judge_id']);
                        @endphp

                        <td style="text-align: center;">
                            {{ $scoreData['memorization_score'] ?? '-' }}
                        </td>

                        <td style="text-align: center;">
                            {{ isset($scoreData) && $scoreData['tafseer_score'] > 0 ? $scoreData['tafseer_score'] : '-' }}
                        </td>
                    @endforeach

                    {{-- Fill remaining empty judge columns --}}
                    @for ($i = $judges->count(); $i < $maxJudges; $i++)
                        <td style="text-align: center;">-</td>
                        <td style="text-align: center;">-</td>
                    @endfor


                    <td style="text-align: center; font-weight: bold;">{{ $comp->final_score }}</td>

                    {{-- Percentage Calculation --}}
                    @php
                        $percentage = '-';
                        if (is_numeric($comp->final_score)) {
                            if ($comp->level === 'memorize_with_tafseer') {
                                $calc = ($comp->final_score / 140) * 100;
                                $percentage = number_format($calc, 2) . '%';
                            } else {
                                $percentage = number_format($comp->final_score, 2) . '%';
                            }
                        }
                    @endphp
                    <td style="text-align: center; font-weight: bold; color: blue;">{{ $percentage }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
