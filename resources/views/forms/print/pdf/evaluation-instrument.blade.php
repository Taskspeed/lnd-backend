<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Level I Evaluation Instrument</title>
<style>
    @page { margin: 0; }
     .footer {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .footer img {
            width: 100%;
            height: auto;
            display: block;
        }
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10.5px;
        color: #000;
    }

    .header-img { width: 100%; height: auto; display: block; }
    .content { padding: 8px 40px 30px 40px; }

    /* Event info */
    table.info { width: 90%; margin: 6px auto 0; border-collapse: collapse; border: 1px solid #000; }
    table.info td { border: 1px solid #000; padding: 3px 6px; font-size: 10.5px; vertical-align: top; }
    table.info td.label { width: 32%; background: #dce6f1; text-transform: uppercase; }

    .title { text-align: center; font-weight: bold; font-size: 13px; margin: 16px 0 10px; }
    .instruction { font-weight: bold; font-style: italic; font-size: 11px; margin: 0 0 10px; }

    /* Checkbox */
    .cb {
        display: inline-block;
        width: 9px;
        height: 9px;
        border: 1px solid #000;
        text-align: center;
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 8px;
        line-height: 9px;
        vertical-align: middle;
    }

    /* Questions */
    table.q { width: 100%; border-collapse: collapse; }
    table.q td { padding: 3px 4px; vertical-align: middle; }
    table.q td.item { width: 58%; padding-left: 28px; }
    table.q td.opt { width: 10.5%; text-align: center; }
    table.q th { font-size: 9.5px; text-align: center; padding-bottom: 6px; }
    table.q tr.head td { padding-top: 10px; font-weight: bold; }
    table.q tr.head td small { font-weight: normal; font-style: italic; font-size: 9px; }

    .section-title { font-weight: bold; font-size: 12px; margin: 16px 0 6px; }
    .row { margin: 4px 0 4px 18px; line-height: 1.5; }
    .row .opt { display: inline-block; margin-right: 22px; font-weight: bold; }
    .row .opt .cb { margin-right: 4px; }

    .lines { white-space: pre-line; line-height: 1.7; margin-top: 4px; min-height: 70px; border-bottom: 1px solid #000; }
    .lines2 { border-bottom: 1px solid #000; height: 18px; }
</style>
</head>
<body>

    <img src="{{ public_path('assets/image/header.png') }}" alt="" class="header-img">

    <div class="content">

        <table class="info">
            <tr><td class="label">Name of L&amp;D Course/Program/Training</td><td><b>{{ $event }}</b></td></tr>
            <tr><td class="label">Date</td><td>{{ $date }}</td></tr>
            <tr><td class="label">Venue</td><td>{{ $venue }}</td></tr>
            <tr><td class="label">Name of Participant</td><td style="height: 22px;">{{ $participant }}</td></tr>
        </table>

        <div class="title">LEVEL I EVALUATION INSTRUMENT</div>

        <div class="instruction">
            Please write a check (&#10003;) to indicate your impressions of the items listed below.
        </div>

        <table class="q">
            <tr>
                <th></th>
                @foreach ($scale as $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>

            @foreach ($sections as $section)
                <tr class="head">
                    <td colspan="5">
                        {{ $section['heading'] }}
                        @if (!empty($section['sub']))<small>({{ $section['sub'] }})</small>@endif
                    </td>
                </tr>
                @foreach ($section['items'] as $item)
                    <tr>
                        <td class="item">{{ $item['text'] }}</td>
                        @foreach ($scale as $value => $label)
                            <td class="opt"><span class="cb">{!! $item['value'] === $value ? '&#10003;' : '' !!}</span></td>
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </table>

        <div class="section-title">Additional Feedback</div>

        <div>How do you rate the training/briefing overall? Please check (&#10003;)</div>
        <div class="row">
            @foreach ($overall as $label => $on)
                <span class="opt"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</span>
            @endforeach
        </div>

        <div style="margin-top: 8px;">Will you recommend the same training/conference/seminar to other government employees?</div>
        <div class="row">
            @foreach ($recommend as $label => $on)
                <span class="opt"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</span>
            @endforeach
        </div>

        <div class="section-title">Comments/Suggestions</div>
        @if (filled($comments))
            <div class="lines">{{ $comments }}</div>
        @else
            <div class="lines2"></div><div class="lines2"></div><div class="lines2"></div><div class="lines2"></div>
        @endif

    </div>

    <div class="footer">
        <img src="{{ public_path('assets/image/footer.jpg') }}" alt="">
   
    </div>
</body>
</html>