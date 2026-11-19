<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Learning Application Monitoring Report</title>
    <style>
        @page {
            margin: 0;
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
        body {
            margin: 0;
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            color: #000;
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
            padding: 10px 50px 30px 50px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin: 6px 0 18px;
        }

        /* Learner / L&D Attended / Date / Competency */
        table.fields {
            width: 100%;
            border-collapse: collapse;
            margin-left: 10px;
        }

        table.fields td {
            padding: 0;
            height: 17px;
            vertical-align: bottom;
            font-size: 11px;
        }

        table.fields td.label {
            width: 150px;
            white-space: nowrap;
            font-weight: bold;
        }

        table.fields td.colon {
            width: 12px;
            font-weight: bold;
        }

        table.fields td.value {
            border-bottom: 1px solid #000;
            padding-left: 4px;
        }

        /* Checkbox */
        .cb {
            display: inline-block;
            width: 8px;
            height: 8px;
            border: 1px solid #000;
            margin-right: 5px;
            text-align: center;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            line-height: 8px;
            vertical-align: middle;
        }

        /* Competency areas */
        table.areas {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin: 16px 0 18px;
        }

        table.areas td {
            vertical-align: top;
            border: 1px solid #000;
            padding: 3px 6px;
            font-size: 10px;
        }

        table.areas td.areas-title {
            text-align: center;
        }

        table.areas td.area-head {
            text-align: center;
            width: 33.33%;
        }

        .item {
            margin-bottom: 3px;
            line-height: 1.2;
        }

        /* Answer boxes */
        table.boxes {
            width: 92%;
            margin: 0 auto;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        table.boxes td {
            border: 1px solid #000;
            padding: 4px 8px;
            vertical-align: top;
        }

        .q {
            font-weight: bold;
        }

        .answer {
            margin-top: 3px;
            white-space: pre-line;
            line-height: 1.4;
        }

        /* Status as of ____ */
        .status-text {
            padding-left: 4px;
        }

        /* Signatures */
        table.sign {
            width: 92%;
            margin: 50px auto 0;
            border-collapse: collapse;
        }

        table.sign td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
            padding: 0 30px;
        }

        .sline {
            border-top: 1px solid #000;
            padding-top: 2px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('assets/image/header.png') }}" alt="" class="header-img">
    </div>

    <div class="content">

        <div class="title">LEARNING APPLICATION MONITORING REPORT</div>

        <table class="fields">
            <tr>
                <td class="label">Learner</td>
                <td class="colon">:</td>
                <td class="value">{{ $learner }}</td>
            </tr>
            <tr>
                <td class="label">L&amp;D Attended</td>
                <td class="colon">:</td>
                <td class="value">{{ $lndAttended }}</td>
            </tr>
            <tr>
                <td class="label">Date of Attendance</td>
                <td class="colon">:</td>
                <td class="value">{{ $dateOfAttendance }}</td>
            </tr>
            <tr>
                <td class="label">Competency Developed/Acquired</td>
                <td class="colon">:</td>
                <td class="value">{{ $competencyDeveloped }}</td>
            </tr>
        </table>

        {{-- Competency areas --}}
        <table class="areas">
            <tr>
                <td class="areas-title" colspan="3">COMPETENCY AREAS AND PROFICIENCY LEVELS</td>
            </tr>
            <tr>
                <td class="area-head">CORE AREAS</td>
                <td class="area-head">TECHNICAL AREAS</td>
                <td class="area-head">LEADERSHIP AREAS</td>
            </tr>
            <tr>
                <td style="height: 70px;">
                    @foreach ($core as $label => $on)
                    <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
                <td>
                    @foreach ($technical as $label => $on)
                    <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
                <td>
                    @foreach ($leadership as $label => $on)
                    <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
            </tr>
        </table>

        {{-- Goals ... Remarks --}}
        <table class="boxes">
            <tr>
                <td style="height: 42px;">
                    <div class="q">Goals</div>
                    <div class="answer">{{ $goals }}</div>
                </td>
            </tr>
            <tr>
                <td style="height: 42px;">
                    <div class="q">Key Performance Indicator</div>
                    <div class="answer">{{ $kpi }}</div>
                </td>
            </tr>
            <tr>
                <td style="height: 42px;">
                    <div class="q">Learning Strategies Applied</div>
                    <div class="answer">{{ $strategies }}</div>
                </td>
            </tr>
            <tr>
                <td style="height: 42px;">
                    <div class="q">Required Resources</div>
                    <div class="answer">{{ $resources }}</div>
                </td>
            </tr>
            <tr>
                <td style="height: 42px;">
                    <div class="q">Target Date of Completion</div>
                    <div class="answer">{{ $targetDate }}</div>
                </td>
            </tr>
            <tr>
                <!-- <td style="height: 42px;">
        Status as of <span class="status-text">{{ $statusV1 }}</span>
    </td> -->
                <td style="height: 42px;">
                    <div class="q">Status as of</div>
                    <div class="answer">{{ $statusV1 }}</div>
                </td>
            </tr>
            </tr>
            <tr>
                <td style="height: 42px;">
                    <div class="q">Status as of</div>
                    <div class="answer">{{ $statusV2 }}</div>
                </td>
            </tr>
            <tr>
                <td style="height: 80px;">
                    <div>Remarks:</div>
                    <div class="answer">{{ $remarks }}</div>
                </td>
            </tr>
        </table>

        {{-- Signatures --}}
        <table class="sign">
            <tr>
                <td>
                    <div class="sline">Immediate Supervisor</div>
                </td>
                <td>
                    <div class="sline">Head of Office</div>
                </td>
            </tr>
        </table>

    </div>
    
    <div class="footer">
        <img src="{{ public_path('assets/image/footer.jpg') }}" alt="">
   
    </div>

</body>

</html>