<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Resource Speaker Evaluation Form</title>
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
        font-family: 'Times New Roman', Times, serif;
        font-size: 11px;
        color: #000;
    }

    .header-img { width: 100%; height: auto; display: block; }
    .content { padding: 8px 40px 30px 40px; }

    /* Event info */
    table.info { width: 90%; margin: 6px auto 0; border-collapse: collapse; border: 1px solid #000; }
    table.info td { border: 1px solid #000; padding: 3px 6px; font-size: 10.5px; vertical-align: top; }
    table.info td.label { width: 32%; background: #dce6f1; font-family: Arial, Helvetica, sans-serif; text-transform: uppercase; }
    table.info td.value { font-family: Arial, Helvetica, sans-serif; }

    .title { text-align: center; font-weight: bold; font-size: 13px; margin: 16px 0 10px; font-family: Arial, Helvetica, sans-serif; }
    .instruction { font-family: Arial, Helvetica, sans-serif; margin: 0 14px 8px; line-height: 1.35; }
    .scale-title { font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; margin: 8px 14px 3px; }
    .scale { font-family: Arial, Helvetica, sans-serif; margin: 0 14px 8px; font-size: 11px; }
    .scale span { margin-right: 14px; white-space: nowrap; }

    /* Ratings table */
    table.rate { width: 100%; border-collapse: collapse; border: 1px solid #000; }
    table.rate td, table.rate th { border: 1px solid #000; padding: 3px 4px; vertical-align: middle; }
table.rate th { font-family: Arial, Helvetica, sans-serif; font-size: 9px; text-align: center; vertical-align: middle; height: 52px; width: 13.5%; }
table.rate th.factor { width: 32.5%; font-size: 11px; text-align: left; vertical-align: middle; }
    table.rate td.factor { font-family: Arial, Helvetica, sans-serif; font-size: 10px; }
    table.rate td.nums { text-align: center; white-space: nowrap; }
    .spk-name { font-weight: normal; margin-top: 3px; }

    /* Circle around the chosen rating */
    .n {
        display: inline-block;
        width: 11px;
        height: 11px;
        line-height: 11px;
        text-align: center;
        font-weight: bold;
        font-size: 9.5px;
        margin: 0 1px;
        border: 1px solid #fff;
        border-radius: 50%;
    }
    .n.on { border: 1px solid #000; }

    .comments { vertical-align: top; height: 90px; font-family: Arial, Helvetica, sans-serif; font-size: 10px; }
    .comments .txt { margin-top: 3px; white-space: pre-line; font-size: 9px; line-height: 1.3; }

    .participant { margin: 24px 14px 0; font-family: 'Times New Roman', Times, serif; }
    .p-line { display: inline-block; border-bottom: 1px solid #000; min-width: 300px; padding-left: 4px; }
    .p-cap { margin-left: 60px; font-size: 10.5px; }
</style>
</head>
<body>

    <img src="{{ public_path('assets/image/header.png') }}" alt="" class="header-img">

    <div class="content">

        <table class="info">
            <tr><td class="label">Name of L&amp;D Course/Program/Training</td><td class="value"><b>{{ $event }}</b></td></tr>
            <tr><td class="label">Date</td><td class="value">{{ $date }}</td></tr>
            <tr><td class="label">Venue/Platform</td><td class="value">{{ $venue }}</td></tr>
            <tr><td class="label">Learning Service Provider</td><td class="value">{{ $provider }}</td></tr>
        </table>

        <div class="title">RESOURCE SPEAKER EVALUATION FORM</div>

        <div class="instruction">
            Please encircle your rating about the speaker during the L&amp;D Program and write the full name of each speaker.
        </div>

        <div class="scale-title">Rating scale:</div>
        <div class="scale">
            <span>1 - Poor</span>
            <span>2 - Unsatisfactory</span>
            <span><b>3 -</b> Satisfactory</span>
            <span>4 - Very Satisfactory</span>
            <span>5 - Excellent</span>
        </div>

        <table class="rate">
            <tr>
                <th class="factor">Factors</th>
                @foreach ($speakers as $i => $name)
                    <th>SPEAKER {{ $i + 1 }}:<div class="spk-name">{{ $name }}</div></th>
                @endforeach
            </tr>

            @foreach ($rows as $label => $values)
                <tr>
                    <td class="factor">{{ $label }}</td>
                    @foreach ($values as $value)
                        <td class="nums">
                            @for ($n = 1; $n <= 5; $n++)
                                <span class="n {{ $value === $n ? 'on' : '' }}">{{ $n }}</span>
                            @endfor
                        </td>
                    @endforeach
                </tr>
            @endforeach

            <tr>
                <td class="comments">Other comments:</td>
                @foreach ($comments as $text)
                    <td class="comments"><div class="txt">{{ $text }}</div></td>
                @endforeach
            </tr>
        </table>

        <div class="participant">
            Participant: <span class="p-line">{{ $participant ?? '' }}</span>
            <div class="p-cap">(Signature over printed name)</div>
        </div>

    </div>

    <div class="footer">
        <img src="{{ public_path('assets/image/footer.jpg') }}" alt="">
   
    </div>
</body>
</html>