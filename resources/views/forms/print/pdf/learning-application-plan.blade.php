<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Learning Application Plan</title>
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
        font-size: 10.5px;
        color: #000;
    }

    .header-img { width: 100%; height: auto; display: block; }
    .header { width: 100%; margin: 0; padding: 0; }
    .content { padding: 10px 40px 30px 40px; }

    .title {
        text-align: center;
        font-weight: bold;
        font-size: 11.5px;
        line-height: 1.25;
        margin: 2px 0 14px;
    }

    /* Office / Learner / Title / Date / Venue */
    table.fields { width: 100%; border-collapse: collapse; }
    table.fields td { padding: 0; height: 16px; vertical-align: bottom; font-size: 10.5px; }
    table.fields td.label { width: 105px; white-space: nowrap; }
    table.fields td.value { border-bottom: 1px solid #000; padding-left: 4px; }

    .category { margin: 12px 0 2px; font-weight: bold; }
    .category .cat-item { margin-left: 18px; }
    .developed { font-weight: bold; margin: 0 0 6px; }

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

    /* Side by side boxes: ang border ay nasa td mismo,
       kaya kusang sumasabay ang taas ng dalawang box at hindi umaapaw */
    table.pair {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 8px;
    }
    table.pair td { vertical-align: top; }
    table.pair td.half {
        width: 49.5%;
        border: 1px solid #000;
        padding: 4px 8px 6px 8px;
    }
    table.pair td.gap { width: 1%; border: 0; padding: 0; }

    .area-title { text-align: center; font-weight: bold; margin-bottom: 3px; }
    .item { margin-bottom: 3px; line-height: 1.2; }

    /* Significant learning */
    table.insight {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }
    table.insight td {
        border: 1px solid #000;
        padding: 4px 8px;
        height: 56px;
        vertical-align: top;
    }
    .insight .label { font-weight: bold; }
    .insight .text { margin-top: 2px; white-space: pre-line; line-height: 1.5; }

    /* Strategies / KPI / Resources / Beneficiaries */
    table.grid { width: 100%; border-collapse: collapse; border: 1px solid #000; }
    table.grid td { width: 50%; vertical-align: top; padding: 4px 8px; border: 1px solid #000; }
    .grid-title { font-weight: bold; margin-bottom: 3px; }

    /* Target date */
    .target { border: 1px solid #000; border-top: 0; padding: 4px 8px 6px; text-align: center; }
    .target .grid-title { text-align: center; }
    .target-list { display: inline-block; text-align: left; }

    /* Signatures */
/* Signatures */
table.sign { width: 100%; border-collapse: collapse; margin-top: 26px; }
table.sign td { padding: 0; text-align: center; vertical-align: bottom; }
table.sign td.lbl { width: 11%; text-align: left; padding-bottom: 1px; }
table.sign td.lbl-r { width: 11%; text-align: right; padding: 0 8px 1px 0; }
table.sign td.cell { width: 24%; }
table.sign td.gap { width: 8.5%; }
table.sign td.line { border-bottom: 1px solid #000; }
.sig-row td { height: 26px; }
.cap-row td { padding-top: 1px; }
.date-row td { height: 30px; }
</style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('assets/image/header.png') }}" alt="" class="header-img">
    </div>

    <div class="content">

        <div class="title">
            Learning Application Plan<br>
            (LAP Form)
        </div>

        <table class="fields">
            <tr><td class="label">Office:</td><td class="value">{{ $office }}</td></tr>
            <tr><td class="label">Learner:</td><td class="value">{{ $learner }}</td></tr>
            <tr><td class="label">Title of Intervention:</td><td class="value">{{ $title }}</td></tr>
            <tr><td class="label">Date Conducted:</td><td class="value">{{ $dateConducted }}</td></tr>
            <tr><td class="label">Venue:</td><td class="value">{{ $venue }}</td></tr>
        </table>

        <div class="category">
            Competency Category:
            @foreach ($categories as $label => $on)
                <span class="cat-item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</span>
            @endforeach
        </div>
        <div class="developed">Competency Developed/Acquired</div>

        {{-- Foundation | Technical --}}
        <table class="pair">
            <tr>
                <td class="half" style="height: 90px;">
                    <div class="area-title">Foundation Competencies</div>
                    @foreach ($foundation as $label => $on)
                        <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
                <td class="gap"></td>
                <td class="half" style="height: 90px;">
                    <div class="area-title">Technical Competencies</div>
                    @foreach ($technical as $label => $on)
                        <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
            </tr>
        </table>

        {{-- Supervisory | Managerial --}}
        <table class="pair">
            <tr>
                <td class="half" style="height: 76px;">
                    <div class="area-title">Supervisory Competencies</div>
                    @foreach ($supervisory as $label => $on)
                        <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
                <td class="gap"></td>
                <td class="half" style="height: 76px;">
                    <div class="area-title">Managerial Competencies</div>
                    @foreach ($managerial as $label => $on)
                        <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
            </tr>
        </table>

        {{-- Significant learning --}}
        <table class="insight">
            <tr>
                <td>
                    <div class="label">Significant Learning/Insight:</div>
                    <div class="text">{{ $insight }}</div>
                </td>
            </tr>
        </table>

        {{-- Strategies | KPI --}}
        <table class="grid">
            <tr>
                <td>
                    <div class="grid-title">Learning Strategies to be implemented</div>
                    @foreach ($strategies as $label => $on)
                        <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
                <td>
                    <div class="grid-title">Key Performance Indicator (based on the IPCR)</div>
                    @foreach ($kpi as $label => $on)
                        <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>
                    <div class="grid-title">Resources to be utilized</div>
                    @foreach ($resources as $label => $on)
                        <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
                <td>
                    <div class="grid-title">Beneficiaries of the Strategies Applied</div>
                    @foreach ($beneficiaries as $label => $on)
                        <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                    @endforeach
                </td>
            </tr>
        </table>

        {{-- Target date --}}
        <div class="target">
            <div class="grid-title">Target Date of Completion</div>
            <div class="target-list">
                @foreach ($targetDates as $label => $on)
                    <div class="item"><span class="cb">{!! $on ? '&#10003;' : '' !!}</span>{{ $label }}</div>
                @endforeach
            </div>
        </div>

        {{-- Signatures --}}

<table class="sign">
    <tr class="sig-row">
        <td class="lbl-r">Signature:</td>
    <td class="cell line"><b>{{ $learner }}</b></td>
        <td class="gap"></td>
        <td class="cell line">&nbsp;</td>
        <td class="gap"></td>
        <td class="cell line">&nbsp;</td>
    </tr>
    <tr class="cap-row">
        <td class="lbl"></td>
        <td class="cell">Learner</td>
        <td class="gap"></td>
        <td class="cell">Supervisor</td>
        <td class="gap"></td>
        <td class="cell">Head of Office</td>
    </tr>
    <tr class="date-row">
        <td class="lbl-r">Date:</td>
        <td class="cell line">&nbsp;</td>
        <td class="gap"></td>
        <td class="cell line">&nbsp;</td>
        <td class="gap"></td>
        <td class="cell line">&nbsp;</td>
    </tr>
</table>
    </div>

    <div class="footer">
        <img src="{{ public_path('assets/image/footer.jpg') }}" alt="">
   
    </div>

</body>
</html>