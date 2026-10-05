<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>عرض السؤال</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            color: #222;
        }


        /*
|--------------------------------------------------------------------------
| Layout
|--------------------------------------------------------------------------
*/

        .page-layout {
            display: flex;
            min-height: 100vh;
        }


        /*
|--------------------------------------------------------------------------
| Right Sidebar
|--------------------------------------------------------------------------
*/

        .questions-sidebar {
            width: 280px;
            flex-shrink: 0;

            background: #ffffff;
            border-left: 1px solid #ddd;

            position: fixed;
            top: 0;
            right: 0;

            height: 100vh;

            overflow-y: auto;

            z-index: 10;
        }


        /*
|--------------------------------------------------------------------------
| Sidebar Header
|--------------------------------------------------------------------------
*/

        .sidebar-header {
            position: sticky;
            top: 0;

            background: #ffffff;

            padding: 20px;

            border-bottom: 1px solid #eee;

            z-index: 2;
        }

        .sidebar-title {
            font-size: 20px;
            font-weight: bold;
            color: #222;
        }

        .sidebar-count {
            margin-top: 5px;

            font-size: 13px;
            color: #777;
        }


        /*
|--------------------------------------------------------------------------
| Question List
|--------------------------------------------------------------------------
*/

        .questions-list {
            padding: 10px;
        }

        .question-link {
            display: flex;
            align-items: center;

            gap: 12px;

            padding: 10px;

            margin-bottom: 6px;

            text-decoration: none;

            color: #333;

            border-radius: 8px;

            transition: 0.2s;
        }

        .question-link:hover {
            background: #f3f4f6;
        }


        /*
|--------------------------------------------------------------------------
| Active Question
|--------------------------------------------------------------------------
*/

        .question-link.active {
            background: #ecfdf5;

            border-right: 4px solid #166534;

            color: #166534;
        }

        .question-link.active .question-number {
            background: #166534;
            color: #ffffff;
        }


        /*
|--------------------------------------------------------------------------
| Question Number
|--------------------------------------------------------------------------
*/

        .question-number {
            width: 36px;
            height: 36px;

            flex-shrink: 0;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #f3f4f6;

            font-size: 14px;
            font-weight: bold;

            color: #555;
        }


        /*
|--------------------------------------------------------------------------
| Question Information
|--------------------------------------------------------------------------
*/

        .question-info {
            min-width: 0;
        }

        .question-title {
            font-size: 14px;
            font-weight: bold;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .question-range {
            margin-top: 3px;

            font-size: 12px;

            color: #888;
        }


        /*
|--------------------------------------------------------------------------
| Main Content
|--------------------------------------------------------------------------
*/

        .main-content {
            width: 100%;

            margin-right: 280px;

            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }


        /*
|--------------------------------------------------------------------------
| Card
|--------------------------------------------------------------------------
*/

        .card {
            background: #fff;

            border: 1px solid #ddd;

            border-radius: 10px;

            padding: 25px;

            margin-bottom: 20px;
        }


        /*
|--------------------------------------------------------------------------
| Header
|--------------------------------------------------------------------------
*/

        .header {
            display: flex;

            justify-content: space-between;
            align-items: center;

            margin-bottom: 25px;

            border-bottom: 1px solid #eee;

            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;

            font-size: 24px;
        }

        .back {
            color: #166534;

            text-decoration: none;
        }


        /*
|--------------------------------------------------------------------------
| Details
|--------------------------------------------------------------------------
*/

        .details {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 15px;

            margin-bottom: 25px;
        }

        .detail {
            background: #f8f8f8;

            padding: 12px;

            border-radius: 6px;
        }

        .label {
            font-size: 13px;

            color: #777;

            margin-bottom: 5px;
        }

        .value {
            font-weight: bold;
        }


        /*
|--------------------------------------------------------------------------
| Questionset
|--------------------------------------------------------------------------
*/

        .questionset-title {
            margin-bottom: 20px;
        }

        .questionset-title-1 {
            display: flex;
            justify-content: space-between;
        }

        /*
|--------------------------------------------------------------------------
| Quran
|--------------------------------------------------------------------------
*/

        .quran {
            background: #faf8f0;

            border: 1px solid #e4dfd0;

            border-radius: 8px;

            padding: 30px;

            font-size: 24px;

            line-height: 2.5;

            text-align: justify;

            font-family: 'Amiri', 'Traditional Arabic', serif;
        }

        .ayah {
            display: inline;
        }

        .ayah-number {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            width: 36px;
            height: 36px;

            margin: 0 6px;

            border: 1px solid #9ca3af;

            border-radius: 50%;

            font-family: Arial, sans-serif;

            font-size: 14px;

            font-weight: normal;

            line-height: 1;

            vertical-align: middle;
        }

        .empty {
            padding: 30px;

            text-align: center;

            color: #777;
        }


        /*
|--------------------------------------------------------------------------
| Mobile
|--------------------------------------------------------------------------
*/

        @media (max-width: 900px) {

            .questions-sidebar {
                width: 220px;
            }

            .main-content {
                margin-right: 220px;

                padding: 15px;
            }

            .details {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media (max-width: 700px) {

            .questions-sidebar {
                position: static;

                width: 100%;

                height: auto;

                border-left: none;

                border-bottom: 1px solid #ddd;
            }

            .page-layout {
                display: block;
            }

            .main-content {
                margin-right: 0;

                padding: 15px;
            }

            .questions-list {
                display: flex;

                overflow-x: auto;

                gap: 6px;
            }

            .question-link {
                flex-shrink: 0;

                margin-bottom: 0;
            }

            .question-info {
                display: none;
            }

            .question-link {
                padding: 5px;
            }

            .question-number {
                width: 38px;
                height: 38px;
            }

            .header {
                flex-direction: column;

                align-items: flex-start;
            }

            .details {
                grid-template-columns: 1fr 1fr;
            }

            .quran {
                font-size: 20px;

                line-height: 2.3;

                padding: 20px;
            }

        }
    </style>
</head>

<body>

    <div class="page-layout">

        {{-- Right Sidebar --}}
        <aside class="questions-sidebar">

            <div class="sidebar-header">
                <div class="sidebar-title">
                    الأسئلة
                </div>

                <div class="sidebar-count">
                    {{ $questions->count() }} سؤال
                </div>
            </div>

            <div class="questions-list">

                @foreach ($questions as $item)
                    <a href="{{ route('review.question.show', $item) }}"
                        class="question-link {{ $item->id == $question->id ? 'active' : '' }}">

                        <div class="question-number">
                            {{ $item->id }}
                        </div>

                        <div class="question-info">

                            <div class="question-title">
                                {{ $item->surat?->title ?? 'غير محددة' }}
                            </div>

                            <div class="question-range">
                                {{ $item->aya_from }} - {{ $item->aya_to }}
                            </div>

                        </div>

                    </a>
                @endforeach

            </div>

        </aside>


        {{-- Main Content --}}
        <main class="main-content">

            <div class="container">

                <div class="card">

                    <div class="header">

                        <h1>
                            سؤال رقم {{ $question->id }}
                        </h1>

                        <a href="{{ route('review.question.index') }}" class="back">
                            ← العودة للأسئلة
                        </a>

                    </div>


                    {{-- Question Details --}}
                    <div class="details">

                        <div class="detail">

                            <div class="label">
                                السورة
                            </div>

                            <div class="value">
                                {{ $question->surat?->title ?? 'غير محددة' }}
                            </div>

                        </div>


                        <div class="detail">

                            <div class="label">
                                من الآية
                            </div>

                            <div class="value">
                                {{ $question->aya_from }}
                            </div>

                        </div>


                        <div class="detail">

                            <div class="label">
                                إلى الآية
                            </div>

                            <div class="value">
                                {{ $question->aya_to }}
                            </div>

                        </div>


                        <div class="detail">

                            <div class="label">
                                الرواية
                            </div>

                            <div class="value">
                                {{ $question->riwaya }}
                            </div>

                        </div>

                    </div>


                    @if ($question->questionset)
                        <div class="questionset-title questionset-title-1">

                            <div>
                                <strong>
                                    الباقة:
                                </strong>

                                {{ $question->questionset->title }}
                            </div>

                            <div>
                                <strong>
                                    المستوى:
                                </strong>

                                {{ $question->questionset->level }}
                            </div>

                        </div>
                    @endif


                    {{-- Quran --}}
                    <div class="quran">

                        @forelse($ayas as $aya)
                            <span class="ayah">

                                {{ $aya->content }}

                                <span class="ayah-number">
                                    {{ $aya->number }}
                                </span>

                            </span>

                        @empty

                            <div class="empty">
                                لم يتم العثور على نص الآيات.
                            </div>
                        @endforelse

                    </div>

                </div>

            </div>

        </main>

    </div>

</body>

</html>
