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
                <th colspan="{{ 8 + $maxJudges }}" style="text-align: center; vertical-align: middle; font-size: 16px; font-weight: bold; background-color: #1E3A8A; color: #FFFFFF; height: 45px;">
                    {{ $title }}
                </th>
            </tr>

            {{-- Column Headers --}}
            <tr style="background-color: #E5E7EB; height: 32px;">
                <th style="width: 8px; text-align: center; vertical-align: middle; font-weight: bold;">#</th>
                <th style="width: 32px; text-align: center; vertical-align: middle; font-weight: bold;">اسم المتسابق</th>
                <th style="width: 25px; text-align: center; vertical-align: middle; font-weight: bold;">المركز</th>
                <th style="width: 12px; text-align: center; vertical-align: middle; font-weight: bold;">الجنس</th>
                <th style="width: 25px; text-align: center; vertical-align: middle; font-weight: bold;">باقة المشاركة</th>
                <th style="width: 18px; text-align: center; vertical-align: middle; font-weight: bold;">المستوى</th>

                {{-- Dynamic Judges Headers --}}
                @foreach ($judges as $judge)
                    <th style="width: 20px; text-align: center; vertical-align: middle; font-weight: bold;">
                        المحكم: {{ $judge['judge_name'] }}
                    </th>
                @endforeach

                {{-- Empty Filler Judge Columns --}}
                @for ($i = $judges->count(); $i < $maxJudges; $i++)
                    <th style="width: 20px; text-align: center; vertical-align: middle; font-weight: bold;">
                        المحكم {{ $i + 1 }}
                    </th>
                @endfor

                <th style="width: 18px; text-align: center; vertical-align: middle; font-weight: bold;">المجموع النهائي</th>
                <th style="width: 18px; text-align: center; vertical-align: middle; font-weight: bold;">النسبة</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($competitions as $index => $comp)
                <tr style="height: 28px;">
                    <td style="text-align: center; vertical-align: middle;">{{ $index + 1 }}</td>
                    <td style="text-align: right; vertical-align: middle;">{{ $comp->student->name ?? '-' }}</td>
                    <td style="text-align: right; vertical-align: middle;">{{ $comp->center->title ?? '-' }}</td>
                    <td style="text-align: center; vertical-align: middle;">{{ ($comp->student->gender ?? '') === 'male' ? 'ذكر' : 'أنثى' }}</td>
                    <td style="text-align: center; vertical-align: middle;">{{ $comp->questionset->title ?? '-' }}</td>
                    <td style="text-align: center; vertical-align: middle;">
                        {{ (int) $comp->level === 1 ? 'المستوى الأول' : 'المستوى الثاني' }}
                    </td>

                    {{-- Judge Scores --}}
                    @foreach ($judges as $judge)
                        @php
                            $scoreData = $comp->detailedScores->firstWhere('judge_id', $judge['judge_id']);
                        @endphp
                        <td style="text-align: center; vertical-align: middle;">
                            {{ isset($scoreData['total']) ? number_format($scoreData['total'], 2) : '-' }}
                        </td>
                    @endforeach

                    {{-- Fillers --}}
                    @for ($i = $judges->count(); $i < $maxJudges; $i++)
                        <td style="text-align: center; vertical-align: middle;">-</td>
                    @endfor

                    {{-- Final Score --}}
                    <td style="text-align: center; vertical-align: middle; font-weight: bold;">
                        {{ is_numeric($comp->final_score) ? number_format($comp->final_score, 2) : '-' }}
                    </td>

                    {{-- Percentage --}}
                    @php
                        $percentage = '-';
                        if (is_numeric($comp->final_score)) {
                            $maxScore = ((int) $comp->level === 1) ? 120 : 100;
                            $percentage = number_format(($comp->final_score / $maxScore) * 100, 2) . '%';
                        }
                    @endphp
                    <td style="text-align: center; vertical-align: middle; font-weight: bold; color: #1E40AF;">
                        {{ $percentage }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>