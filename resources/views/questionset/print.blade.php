<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طباعة مجموعات الأسئلة</title>

    <style>
        body {
            font-family: Arial, Tahoma, sans-serif;
            background: #fff;
            color: #000;
            margin: 20px;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 18px;
            margin-top: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 10px;
            font-size: 14px;
            vertical-align: top;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
            text-align: right;
        }

        .page-break {
            page-break-before: always;
        }

        .meta {
            font-size: 13px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

@foreach ($questionsets as $questionset)

    <div class="page-break"></div>

    {{-- Question Set Title --}}
    <h1>{{ $questionset->title }}</h1>

    <div class="meta">
        عدد الأسئلة: {{ $questionset->questions->count() }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:60px;">#</th>
                <th>نص السؤال</th>
                <th style="width:150px;">الصعوبة</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($questionset->questions as $question)
                <tr>
                    <td style="text-align:center;">
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ $question->content }}
                    </td>
                    <td>
                        {{ $question->difficulties }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endforeach

</body>
</html>
