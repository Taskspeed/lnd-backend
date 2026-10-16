{{-- resources/views/forms/agency-learning-development.blade.php --}}
{{-- Ginagamit ng App\Exports\AgencyLearningDevelopmentExport (FromView).
     Structure lang ito; ang styles (font, kulay, border, merge) ay nasa AfterSheet ng Export class. --}}
@php
    $monthHeaders = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEPT', 'OCT', 'NOV', 'DEC'];

    // Legend (NO. | Experiential | Social | Formal)
    $legend = [
        [1, 'ODP - Observation/Demonstration and Practice', 'JS - Job Shadowing', 'RD - Reading'],
        [2, 'DE - Delegation', 'CO - Coaching', 'EL - eLearning'],
        [3, 'JE - Job Enrichment', 'MT - Mentoring', 'CE - Continuing Education'],
        [4, 'JR - Job Rotation', 'CS - Counseling', 'TC - Training Courses'],
        [5, 'RA - Reassignment', 'CFT - Cross-Functional Teams', 'S/W - Seminars/Workshops'],
        [6, 'BM - Benchmarking', 'NT - Networking', '*'],
        [7, 'AL - Action Learning/ Evaluation & Planning Activity', '*', '*'],
    ];

    $blank = fn (int $n) => str_repeat('<td></td>', $n);   // pang-pad hanggang 16 columns (A-P)
@endphp
<table>
    {{-- Row 1: logo + banner (nilalagay ng WithDrawings) --}}
    <tr>{!! $blank(16) !!}</tr>

    {{-- Row 2: Title --}}
    <tr>
        <td>AGENCY LEARNING AND DEVELOPMENT MONITORING REPORT {{ $year }}</td>{!! $blank(15) !!}
    </tr>

    {{-- Row 3: Legend header --}}
    <tr>
        <td>NO.</td>
        <td>Experiential Learning (70%) – Learning by Doing</td>
        <td>Social Learning (20%) – Learning Through Others</td>
        <td>Formal Learning (10%) – Structured Learning Programs</td>{!! $blank(12) !!}
    </tr>

    {{-- Rows 4-10: Legend --}}
    @foreach ($legend as $l)
        <tr>
            <td>{{ $l[0] }}</td>
            <td>{{ $l[1] }}</td>
            <td>{{ $l[2] }}</td>
            <td>{{ $l[3] }}</td>{!! $blank(12) !!}
        </tr>
    @endforeach

    {{-- Rows 11-12: Table header --}}
    <tr>
        <td>NO.</td>
        <td>NAME</td>
        <td>POSITION</td>
        <td>TRAININGS ATTENDED IN {{ $year }}</td>{!! $blank(11) !!}
        <td>TOTAL</td>
    </tr>
    <tr>
        {!! $blank(3) !!}
        @foreach ($monthHeaders as $m)
            <td>{{ $m }}</td>
        @endforeach
        <td></td>
    </tr>

    {{-- Row 13+: Office > Status > Employees --}}
    @foreach ($layout as $item)
        @if ($item['type'] === 'office')
            <tr><td>{{ $item['label'] }}</td>{!! $blank(15) !!}</tr>

        @elseif ($item['type'] === 'status')
            <tr><td>{{ $item['label'] }}</td>{!! $blank(15) !!}</tr>

        @elseif ($item['type'] === 'employee')
            <tr>
                <td>{{ $item['no'] }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['position'] }}</td>
                @foreach ($item['months'] as $count)
                    <td>{{ $count > 0 ? $count : '' }}</td>
                @endforeach
                <td>=SUM(D{{ $item['row'] }}:O{{ $item['row'] }})</td>
            </tr>

        @elseif ($item['type'] === 'total')
            {{-- Ang formulas ng TOTAL ay nilalagay ng AfterSheet --}}
            <tr><td>TOTAL</td>{!! $blank(15) !!}</tr>
        @endif
    @endforeach
</table>