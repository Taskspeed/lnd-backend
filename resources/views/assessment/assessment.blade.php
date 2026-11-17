@php
    // Ilang tanong ang puwede sa bawat bond paper (isaayos kung kailangan)
    $perSheet = 10;

    // Puno muna bago lumipat: 14 tanong = 10 + 4, 20 tanong = 10 + 10, 25 = 10 + 10 + 5
    $sheets = $assessment->questions->values()->chunk($perSheet);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>{{ $assessment->title }}</title>
<style>
    @page { margin: 0; }

    body {
        margin: 0;
        font-family: Helvetica, Arial, sans-serif;
        font-size: 9px;
        color: #000;
    }

    /* Bawat bond paper: dalawang kopya magkatabi, may putol-putol na linya sa gitna */
    .sheet.break { page-break-after: always; }
    .sheet { page-break-inside: avoid; }

    table.page {
        width: 100%;
        height: 970pt;
        border-collapse: collapse;
        table-layout: fixed;
    }
    table.page td.col {
        width: 50%;
        padding: 0;
        vertical-align: top;
    }
    table.page td.col.first { border-right: 1px dashed #000; }

    .header-img { width: 100%; height: auto; display: block; }
    .content { padding: 5px 10px 0 10px; }

    .title {
        text-align: center;
        font-size: 9px;
        font-weight: bold;
        margin: 2px 0 5px;
    }

    /* Learner info */
    table.info { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
    table.info td { padding: 2px 0; vertical-align: bottom; }
    table.info .label { width: 34px; font-weight: bold; }
    table.info .fill { border-bottom: 1px solid #000; }

    .instructions {
        margin: 2px 0 5px;
        padding: 3px 4px;
        border: 1px solid #000;
        line-height: 1.1;
        font-size: 8px;
    }

    .page-no { text-align: right; font-size: 7px; color: #555; margin-bottom: 3px; }

    /* Questions */
    .question { margin-bottom: 4px; page-break-inside: avoid; }
    .q-text { font-weight: bold; line-height: 1.05; margin-bottom: 1px; font-size: 8.5px; }
    .q-points { font-weight: normal; font-size: 7px; color: #555; }

    .option { margin: 0 0 1px 8px; line-height: 1.05; font-size: 8px; }
    .mark {
        display: inline-block;
        width: 6px;
        height: 6px;
        border: 1px solid #000;
        margin-right: 3px;
    }
    .mark.round { border-radius: 7px; }
</style>
</head>
<body>

@foreach ($sheets as $sheetIndex => $chunk)
<div class="sheet {{ $loop->last ? '' : 'break' }}">
<table class="page">
    <tr>
        @foreach ([1, 2] as $copy)
            <td class="col {{ $copy === 1 ? 'first' : '' }}">

                <img src="{{ public_path('assets/image/header.png') }}" alt="" class="header-img">

                <div class="content">
                    <div class="title">{{ $assessment->title }}</div>

                    <table class="info">
                        <tr>
                            <td class="label">Name:</td>
                            <td class="fill"></td>
                        </tr>
                        <tr>
                            <td class="label">Date:</td>
                            <td class="fill"></td>
                        </tr>
                    </table>

                    <div class="instructions">
                        <b>Instructions:</b> Read each question carefully and mark your answer.
                        Total points: {{ $assessment->questions->sum('points') }}
                        @if (!is_null($assessment->passing_score))
                            &nbsp;|&nbsp; Passing score: {{ $assessment->passing_score }}
                        @endif
                    </div>

                    @if ($sheets->count() > 1)
                        <div class="page-no">Page {{ $loop->parent->iteration }} of {{ $sheets->count() }}</div>
                    @endif

                    @foreach ($chunk as $index => $question)
                        <div class="question">
                            <div class="q-text">
                                {{ ($sheetIndex * $perSheet) + $index + 1 }}. {{ $question->question }}
                                <span class="q-points">({{ $question->points }} {{ $question->points > 1 ? 'pts' : 'pt' }})</span>
                            </div>

                            @foreach ($question->options as $option)
                                <div class="option">
                                    <span class="mark {{ $question->question_type === 'checkboxes' ? '' : 'round' }}"></span>{{ $option->option_text }}
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

            </td>
        @endforeach
    </tr>
</table>
</div>
@endforeach

</body>
</html>
