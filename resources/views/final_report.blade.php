<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>  المسابقة القرآنية فاستمسك - {{ $competition->stage->title }} - {{ $student->name }}</title>
    @vite(['resources/css/app.css'])
    <style>
        :root {
            --primary: #1e293b;
            --accent: #3b82f6;
            --success: #10b981;
            --bg-body: #f1f5f9;
            --glass: rgba(255, 255, 255, 0.9);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica', 'Tahoma', sans-serif;
            background-color: var(--bg-body);
            color: #334155;
            padding: 40px 15px;
            line-height: 1.6;
        }

        .container {
            max-width: 950px;
            margin: 0 auto;
            background: white;
            padding: 50px;
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            position: relative;
            border: 1px solid rgba(0,0,0,0.05);
        }

        /* Top Action Bar */
        .action-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 15px;
        }
        .btn {
            padding: 12px 25px;
            border-radius: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            border: none;
        }
        .btn-print { background: var(--primary); color: white; }
        .btn-finish { background: var(--success); color: white; }

        /* Document Header */
        .doc-header {
            text-align: center;
            margin-bottom: 50px;
            position: relative;
        }
        .doc-header h1 { font-size: 32px; color: var(--primary); margin-bottom: 10px; letter-spacing: -1px; }
        .doc-header .subtitle { color: #64748b; font-size: 16px; }
        
        /* Information Grid */
        .student-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
            padding: 25px;
            background: #f8fafc;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
        }
        .info-cell label { display: block; font-size: 12px; color: #94a3b8; font-weight: 600; margin-bottom: 5px; }
        .info-cell p { font-size: 16px; font-weight: 700; color: var(--primary); }

        /* Question Cards */
        .question-card {
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            overflow: hidden;
            background: white;
            transition: 0.3s;
        }
        .question-card:hover { border-color: var(--accent); }
        .q-head {
            background: #f8fafc;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .q-num { font-weight: 800; color: var(--accent); font-size: 18px; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: right; padding: 15px 25px; font-size: 13px; color: #64748b; background: #fafafa; }
        td { padding: 15px 25px; border-top: 1px solid #f1f5f9; font-size: 15px; }
        .total-row { background: #f0fdf4; font-weight: 800; color: var(--success); }

        /* Final Score Section */
        .final-result-section {
            margin-top: 60px;
            padding: 40px;
            background: var(--primary);
            border-radius: 30px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .score-box { text-align: center; }
        .percentage-display { font-size: 64px; font-weight: 900; color: #fbbf24; line-height: 1; }
        .grade-badge {
            background: rgba(255,255,255,0.1);
            padding: 8px 20px;
            border-radius: 10px;
            font-size: 18px;
            margin-top: 10px;
            display: inline-block;
        }

        @media print {
            .action-bar { display: none; }
            body { background: white; padding: 0; }
            .container { box-shadow: none; border: none; width: 100%; }
            .question-card { break-inside: avoid; }
            .final-result-section,.percentage-display {
                background-color: lightgray !important;
                color: black !important;
            }
        }
    </style>
</head>
<body>

    @php
        // 1. PRE-CALCULATE ALL TOTALS TO AVOID "UNDEFINED VARIABLE" ERRORS
        $judgeTotals = [];
        $judgeNames = [];
        foreach ($reportData as $data) {
            foreach ($data['judge_scores'] as $id => $score) {
                $judgeTotals[$id] = ($judgeTotals[$id] ?? 0) + $score['total'];
                $judgeNames[$id] = $score['judge']->name;
            }
        }

        $div = $reportData->count() * $judgeCount;
        $finalP = $div > 0 ? $grandTotal / $div : 0;

        // 2. Logic for Grading
        $grade = 'مقبول';
        if($finalP >= 90) $grade = 'ممتاز';
        elseif($finalP >= 80) $grade = 'جيد جداً';
        elseif($finalP >= 70) $grade = 'جيد';
    @endphp

    <div class="container">
        
        {{-- Action Buttons --}}
        <div class="action-bar">
            <button class="btn btn-print" onclick="window.print()">طباعة التقرير</button>
            @if($competition->student_status != 'finish_competition')
                <a href="{{ route('finish_student', $competition->id) }}" class="btn btn-finish">أنهى المسابقة</a>
            @endif
        </div>

        <div class="doc-header">
            <h1>المسابقة القرآنية فاستمسك - {{ $competition->stage->title }}</h1>
            <p>{{ $competition->created_at->format('Y/m/d') }}</p>
            <p class="subtitle">2025/1447</p>
        </div>

        {{-- Student Info Dashboard --}}
        <div class="student-dashboard">
            <div class="info-cell"><label>اسم المتسابق</label><p>{{ $student->name }}</p></div>
            <div class="info-cell"><label>المستوى</label><p>{{ $competition->questionset->level }}</p></div>
            <div class="info-cell"><label>اللجنة</label><p>{{ $competition->committee->title }}</p></div>
            <div class="info-cell"><label>المركز</label><p>{{ $competition->center->title }}</p></div>
            <div class="info-cell"><label>عدد المحكمين</label><p>{{ $judgeCount }}</p></div>
        </div>

        {{-- Questions --}}
        @foreach ($reportData as $index => $data)
            <div class="question-card">
                <div class="q-head">
                    <span class="q-num">السؤال {{ $index + 1 }}</span>
                    <span style="font-size: 14px; color: #64748b;">{{ $data['question']->difficulties }}</span>
                </div>
                
                <div style="padding: 25px;">
                    <p style="color: #64748b; font-size: 14px; margin-bottom: 15px;">{{ $data['question']->content }}</p>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>المحكم</th>
                                <th>الدرجة</th>
                                <th>الملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['judge_scores'] as $js)
                                <tr>
                                    <td style="font-weight: 600;">{{ $js['judge']->name }}</td>
                                    <td>{{ number_format($js['total'], 1) }}</td>
                                    <td style="color: #94a3b8; font-size: 13px;">{{ $js['note'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                            <tr class="total-row">
                                <td>المتوسط</td>
                                <td colspan="2">{{ number_format($data['average'], 1) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        {{-- Final Result Banner --}}
        <div class="final-result-section">
            <div>
                <h2 style="font-size: 24px; margin-bottom: 5px;">النتيجة النهائية</h2>
                <p style="opacity: 0.7; font-size: 14px;">تم احتساب النتيجة بناءً على متوسط تقييمات المحكمين</p>
                
                <div style="margin-top: 20px; display: flex; gap: 10px;">
                    @foreach ($judgeTotals as $id => $total)
                        <div style="font-size: 11px; background: rgba(255,255,255,0.1); padding: 5px 12px; border-radius: 8px;">
                            {{ $judgeNames[$id] }}: {{ number_format($total / $reportData->count(), 1) }}%
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="score-box">
                <div class="percentage-display">{{ number_format($finalP, 2) }}%</div>
                <div class="grade-badge">التقدير: {{ $grade }}</div>
            </div>
        </div>
    </div>

</body>
</html>