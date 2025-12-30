<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>قائمة النتائج النهائية</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background: #f4f7f6;
            padding: 30px;
        }

        .results-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table-custom th {
            background: #2c3e50;
            color: white;
            padding: 15px;
            text-align: right;
        }

        .table-custom td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        .rank-badge {
            background: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 50%;
            font-weight: bold;
        }

        .progress-container {
            width: 100%;
            background: #eee;
            border-radius: 10px;
            height: 10px;
            margin-top: 5px;
        }

        .progress-bar {
            height: 10px;
            border-radius: 10px;
            background: #27ae60;
        }

        .btn-report {
            background: #34495e;
            color: white;
            text-decoration: none;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="results-card">
            <h2 style="margin-bottom: 20px; color: #2c3e50; border-right: 5px solid #3498db; padding-right: 15px;">
                نتائج المتسابقين النهائية
            </h2>

            <table class="table-custom">
                <thead>
                    <tr>
                        <th>الترتيب</th>
                        <th>اسم المتسابق</th>
                        <th>المركز / المرحلة</th>
                        <th>المجموع</th>
                        <th>النسبة المئوية</th>
                        <th>الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($competitions as $index => $comp)
                        <tr>
                            <td><span class="rank-badge">{{ $index + 1 }}</span></td>
                            <td><strong>{{ $comp->student->name }}</strong></td>
                            <td>
                                <small>{{ $comp->center->title }}</small><br>
                                <span style="color: #7f8c8d; font-size: 12px;">{{ $comp->stage->title }}</span>
                            </td>
                            <td>{{ number_format($comp->grand_total, 1) }}</td>
                            <td>
                                <strong style="color: #27ae60;">{{ number_format($comp->percentage, 2) }}%</strong>
                                <div class="progress-container">
                                    <div class="progress-bar" style="width: {{ $comp->percentage }}%"></div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('final_report', $comp->id) }}" class="btn-report">عرض التقرير
                                    التفصيلي</a>
                                @if (auth()->user()->user_type == 'admin')
                                    <a href="{{ route('unfinish_student', $comp->id) }}" class="btn-report">إعادة فتح
                                        المسابقة</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
