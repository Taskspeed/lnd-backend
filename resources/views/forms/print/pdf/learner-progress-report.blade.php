<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Learner's Progress Report</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            margin: 0;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10px;
            color: #000;
        }

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

        .header-img {
            width: 100%;
            height: auto;
            display: block;
        }

        .header {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .content {
            padding: 14px 40px 110px 40px;
        }

        .title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin: 4px 0 14px;
        }

        .info p {
            margin: 0 0 3px;
        }

        .info b {
            font-weight: bold;
        }

        .intro {
            margin: 3px 0 10px;
            line-height: 1.35;
        }

        /* Competency areas */
        table.areas {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
        }

        table.areas th {
            font-weight: normal;
            font-size: 11px;
            text-align: center;
            padding: 2px 0;
            border-bottom: 1px solid #000;
        }

        table.areas td {
            vertical-align: top;
            padding: 6px 6px 4px;
            width: 33.33%;
            border-left: 1px solid #000;
        }

        table.areas td:first-child {
            border-left: 0;
        }

        .area-item {
            margin-bottom: 4px;
            font-size: 10px;
        }

        .box {
            display: inline-block;
            width: 8px;
            height: 8px;
            border: 1px solid #000;
            margin-right: 4px;
            text-align: center;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            line-height: 8px;
        }

        /* Rating legend */
        .rate-intro {
            margin: 16px 0 8px 0;
            padding: 0 0 0 0;
            line-height: 1.35;
        }

        .legend p {
            margin: 0 0 9px 52px;
        }

        /* Rating table */
        table.rating {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin-top: 14px;
        }

        table.rating th {
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #000;
            padding: 3px 2px;
            text-align: center;
        }

        table.rating td {
            border: 1px solid #000;
            padding: 1px 4px;
            font-size: 9.5px;
            height: 11px;
        }

        table.rating td.mark {
            text-align: center;
            width: 8.4%;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            padding: 0;
        }

        table.rating td.comp {
            width: 58%;
        }

        /* Remarks */
        .remarks {
            margin-top: 24px;
            border: 2px solid #000;
            height: 110px;
            padding: 3px 6px;
            font-size: 10px;
        }

        .remarks .text {
            margin-top: 6px;
            white-space: pre-line;
        }

        /* Signatures */
        table.sign {
            width: 100%;
            border-collapse: collapse;
            margin-top: 50px;
        }

        table.sign td {
            vertical-align: top;
            width: 50%;
            padding: 0;
        }

        .sign .by {
            font-weight: bold;
            margin-bottom: 26px;
        }

        .line {
            border-top: 1px solid #000;
            width: 160px;
            margin-bottom: 4px;
        }

        .role {
            font-weight: bold;
            margin-bottom: 26px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('assets/image/header.png') }}" alt="" class="header-img">
    </div>

    <div class="content">
        <div class="title">LEARNER’S PROGRESS REPORT</div>

        <div class="info">
            <p><b>Learner:</b> {{ $learner }}</p>
            <p><b>L&amp;D Attended:</b> {{ $lndAttended }}</p>
            <p><b>Date of Attendance:</b> {{ $dateOfAttendance }}</p>
        </div>

        <div class="intro">
            As a result of the training/seminar, the learner manifested improvement in the following
            areas: (As indicated in Learning Application Plan (LAP).
        </div>

        <table class="areas">
            <tr>
                <th colspan="3">COMPETENCY AREAS AND PROFICIENCY LEVELS</th>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid #000;">CORE AREAS</th>
                <th style="border-bottom: 1px solid #000; border-left: 1px solid #000;">TECHNICAL AREAS</th>
                <th style="border-bottom: 1px solid #000; border-left: 1px solid #000;">LEADERSHIP AREAS</th>
            </tr>
            <tr>
                <td>
                    @foreach ($core as $label => $on)
                    <div class="area-item"><span class="box">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
                <td>
                    @foreach ($technical as $label => $on)
                    <div class="area-item"><span class="box">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
                <td>
                    @foreach ($leadership as $label => $on)
                    <div class="area-item"><span class="box">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
            </tr>
        </table>

        <div class="rate-intro">
            Kindly indicate the learner’s application of his/her acquired competency/ies based on the
            following rating; (Please check (/) the column that indicates your rating)
        </div>

        <div class="legend">
            <p>5- Outstandingly contributed to the effective discharge of the learner’s functions</p>
            <p>4- Very satisfactorily contributed to the effective discharge of the learner’s functions</p>
            <p>3- Satisfactorily contributed to the effective discharge of the learner’s functions</p>
            <p>2- Is somehow helpful to the effective discharge of the learner’s functions</p>
            <p>1- Is of no significant effect to the effective discharge of the learner’s functions.</p>
        </div>

        <table class="rating">
            <tr>
                <th rowspan="2" style="width: 58%;">COMPETENCY</th>
                <th colspan="5">DEGREE OF APPLICATION</th>
            </tr>
            <tr>
                @foreach ([5, 4, 3, 2, 1] as $n)
                <th>{{ $n }}</th>
                @endforeach
            </tr>
            @foreach ($ratings as $label => $value)
            <tr>
                <td class="comp">{{ $label }}</td>
                @foreach ([5, 4, 3, 2, 1] as $n)
                <td class="mark">{!! (int) $value === $n ? '&#10003;' : '&nbsp;' !!}</td>
                @endforeach
            </tr>
            @endforeach
        </table>

        <div class="remarks">
            Remarks: (Observed learner’s behavioral changes)
            <div class="text">{{ $remarks }}</div>
        </div>

        <table class="sign">
            <tr>
                <td>
                    <div class="by">Evaluated by:</div>
                    <div class="line"></div>
                    <div class="role">Immediate Supervisor</div>
                    <div class="line"></div>
                    <div class="role">Date of Evaluation:</div>
                </td>
                <td>
                    <div class="by">Noted by:</div>
                    <div class="line"></div>
                    <div class="role">Head of Office</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <img src="{{ public_path('assets/image/footer.jpg') }}" alt="">
   
    </div>
</body>

</html>