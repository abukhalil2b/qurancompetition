<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>مراجعة الأسئلة</title>
</head>
<body>

<table border="1" cellpadding="8" cellspacing="0">
<thead>
    <tr>
        <th>#</th>
        <th>المجموعة</th>
        <th>السورة</th>
        <th>الآيات</th>
        <th>الرواية</th>
        <th>الإجراء</th>
    </tr>
</thead>

<tbody>

    @forelse($questions as $question)

        <tr>

            <td>
                {{ $question->id }}
            </td>

            <td>
                {{ $question->questionset?->title ?? '-' }}
            </td>

            <td>
                {{ $question->surat?->number }}
                -
                {{ $question->surat?->title ?? 'غير محددة' }}
            </td>

            <td>
                {{ $question->aya_from }}
                -
                {{ $question->aya_to }}
            </td>

            <td>
                {{ $question->riwaya }}
            </td>

            <td>
                <a href="{{ route('review.question.show', $question) }}">
                    عرض
                </a>
            </td>

        </tr>

    @empty

        <tr>
            <td colspan="6">
                لا توجد أسئلة للمراجعة.
            </td>
        </tr>

    @endforelse

</tbody>

</table>

</body>

</html>
