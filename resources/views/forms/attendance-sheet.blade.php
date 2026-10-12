{{-- resources/views/forms/attendance-sheet.blade.php --}}
@php
// ===== Galing sa PDFController::nominatedEmployee() =====
$officeName = $officeName ?? 'City Human Resource Management Office';
$title = $training_title ?? '';
$activity = $training_activity ?? '';
$date = $training_date ?? '';

$venue = $training_venue ?? ''; // optional (hindi pa pinapasa ng controller)

$morning_in = $morning_in ?? '';
$morning_out = $morning_out ?? '';
$afternoon_in = $afternoon_in ?? '';
$afternoon_out = $afternoon_out ?? '';

$rows = collect($participants ?? [])->values();
$minRows = 30;
$totalRows = max($minRows, $rows->count());

// Logo as base64 (safe sa DomPDF)
$logoPath = public_path('assets/image/logo.png');
$logo = file_exists($logoPath)
? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
: null;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Attendance Sheet</title>
  <style>
    @page {
      margin: 8mm;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Helvetica', 'Arial', sans-serif;
      color: #000;
    }

    /* ===== Letterhead (landscape: ~1060px usable width) ===== */
    .letterhead {
      position: relative;
      width: 100%;
      height: 125px;
    }

    .letterhead .bar-left {
      position: absolute;
      left: 0;
      top: 82px;
      width: 70px;
      height: 29px;
      background: #0e7a12;
    }

    .letterhead .seal {
      position: absolute;
      left: 76px;
      top: 8px;
      width: 116px;
      height: 104px;
    }

    .letterhead .seal img {
      width: 104px;
      height: 104px;
    }

    .letterhead .gov {
      position: absolute;
      left: 222px;
      top: 34px;
      line-height: 1.2;
      color: #0e7a12;
    }

    .letterhead .gov .small {
      font-size: 8px;
      text-transform: uppercase;
    }

    .letterhead .gov .city {
      font-size: 18px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .letterhead .office {
      position: absolute;
      left: 207px;
      top: 82px;
      width: 850px;
      height: 29px;
      padding-left: 13px;
      background: #0e7a12;
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      line-height: 29px;
      text-transform: uppercase;
      white-space: nowrap;
    }

    /* ===== Title / meta ===== */
    .title {
      text-align: center;
      font-size: 20px;
      font-weight: bold;
      margin: 6px 0 12px;
    }

    .meta {
      font-size: 12px;
      line-height: 1.4;
      margin: 0 0 8px 0;
    }

    .meta b {
      font-size: 13px;
    }

    /* ===== Table ===== */
    table.attendance {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
    }

    table.attendance thead {
      display: table-header-group;
    }

    /* ulitin ang header sa bawat page */
    table.attendance tr {
      page-break-inside: avoid;
    }

    table.attendance th,
    table.attendance td {
      border: 1px solid #000;
      padding: 3px 5px;
    }

    table.attendance thead th {
      text-align: center;
      vertical-align: middle;
      font-size: 11px;
      font-weight: bold;
      text-transform: uppercase;
    }

    table.attendance th.consent-text {
      font-size: 7px;
      text-align: left;
      text-transform: none;
      line-height: 1.15;
      padding: 4px 5px;
    }

    table.attendance th.yn {
      font-size: 10px;
      height: 20px;
    }

    table.attendance tbody td {
      height: 26px;
      vertical-align: middle;
    }

    table.attendance td.center {
      text-align: center;
    }

    table.attendance td.check {
      text-align: center;
      font-family: 'DejaVu Sans', sans-serif;
    }
  </style>
</head>

<body>

  {{-- ===== Letterhead ===== --}}
  <div class="letterhead">
    <div class="bar-left"></div>

    <div class="seal">
      @if($logo)
      <img src="{{ $logo }}" alt="City of Tagum Seal">
      @endif
    </div>

    <div class="gov">
      <div class="small">Republic of the Philippines</div>
      <div class="small">Province of Davao del Norte</div>
      <div class="city">City of Tagum</div>
    </div>

    <div class="office">{{ $officeName }}</div>
  </div>

  <div class="title">ATTENDANCE SHEET</div>

  <div class="meta">
    <div><b>ACTIVITY/ PROGRAM:</b> {{ $activity }} | {{ $title }}</div>
    <div><b>DATE/TIME/VENUE OF ACTIVITY:</b> {{ $date }} | Morning: {{ $morning_in }} - {{ $morning_out }} | Afternoon: {{ $afternoon_in }} - {{ $afternoon_out }} | {{ $venue }}</div>
  </div>

  <table class="attendance">
    <thead>
      <tr>
        <th rowspan="2" style="width:4%">No.</th>
        <th rowspan="2" style="width:20%">Name</th>
        <th rowspan="2" style="width:15%">Designation</th>
        <th rowspan="2" style="width:9%">Employment Status</th>
        <th rowspan="2" style="width:4%">Age</th>
        <th rowspan="2" style="width:5%">Sex (M/F)</th>
        <th rowspan="2" style="width:10%">Office</th>
        <th colspan="2" class="consent-text" style="width:20%">
          <b>I hereby give my consent to LGU-Tagum City and City Human Resource Management Office to collect, use and process my personal information.<br>
            I understand that my consent does not preclude the existence of other criteria for lawful processing of personal data, and does not waive any of my rights under the Data Privacy Act of 2012 and other applicable laws.</b>
        </th>
        <th rowspan="2" style="width:13%">Signature</th>
      </tr>
      <tr>
        <th class="yn" style="width:10%">YES</th>
        <th class="yn" style="width:10%">NO</th>
      </tr>
    </thead>
    <tbody>
      @for ($i = 0; $i < $totalRows; $i++)
        @php $p=$rows->get($i); @endphp
        <tr>
          <td class="center">{{ $i + 1 }}</td>
          <td>{{ $p->full_name   ?? '' }}</td>
          <td>{{ $p->designation ?? '' }}</td>
          <td>{{ $p->status      ?? '' }}</td>
          <td class="center">{{ $p->age ?? '' }}</td>
          <td class="center">{{ $p->sex ?? '' }}</td>
          <td>{{ $p->office_abbr ?? $p->office ?? '' }}</td>
          <td class="check"></td>
          <td class="check"></td>
          <td></td>
        </tr>
        @endfor
    </tbody>
  </table>

</body>

</html>