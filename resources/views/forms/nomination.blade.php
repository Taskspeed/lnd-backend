{{-- resources/views/forms/nomination-form.blade.php --}}
@php
// ===== Galing sa controller: $event (Event model na may schedule.office) =====
$schedule = $event->schedule->first();

$officeName = 'City Human Resource Management Office';
$title = 'NOMINATION FOR THE ' . strtoupper($event->title_name ?? '');
$date = $schedule->schedule_date_range ?? '';
$venue = $schedule->venue_name ?? '';
$columnLabel = 'EMPLOYEES';

// Static muna (palitan kapag may source na)
$signatory = 'JANYLENE A. PALERMO, MM';
$signatoryTitle = 'City Human Resource Mgt. Officer';

$footerAddress = '2nd Floor, City Hall of Tagum, JV Ayala Ave., Brgy. Apokon';
$footerPhone = '(084) 645 3300 / Local 216-220, 252';
$footerEmail = 'tagumcityhumanresource@gmail.com';

// Offices na may slot lang (number_of_attendees > 0)
$offices = collect($schedule->office ?? [])
    ->filter(fn ($o) => (int) $o->number_of_attendees > 0)
    ->sortBy('office_name')
    ->values()
    ->map(fn ($o) => ['name' => $o->office_name, 'count' => (int) $o->number_of_attendees]);

$total = $offices->sum('count');

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
  <title>{{ $title }}</title>
  <style>
    @page {
      margin: 8mm 12mm 8mm 12mm;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Helvetica', 'Arial', sans-serif;
      color: #000;
    }

    /* ===== Letterhead (portrait: ~700px usable width) ===== */
    .letterhead {
      position: relative;
      width: 100%;
      height: 100px;
    }

    .letterhead .bar-left {
      position: absolute;
      left: 0;
      top: 66px;
      width: 45px;
      height: 24px;
      background: #0e7a12;
    }

    .letterhead .seal {
      position: absolute;
      left: 50px;
      top: 4px;
      width: 90px;
      height: 90px;
    }

    .letterhead .seal img {
      width: 88px;
      height: 88px;
    }

    .letterhead .gov {
      position: absolute;
      left: 160px;
      top: 22px;
      line-height: 1.2;
      color: #0e7a12;
    }

    .letterhead .gov .small {
      font-size: 7px;
      text-transform: uppercase;
    }

    .letterhead .gov .city {
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .letterhead .office {
      position: absolute;
      left: 150px;
      right: 0;
      top: 66px;
      height: 24px;
      padding-left: 10px;
      background: #0e7a12;
      color: #fff;
      font-size: 14px;
      font-weight: bold;
      line-height: 24px;
      text-transform: uppercase;
      white-space: nowrap;
    }

    /* ===== Title ===== */
    .title {
      text-align: center;
      font-size: 14px;
      font-weight: bold;
      margin: 14px 0 0;
      text-transform: uppercase;
    }

    .subtitle {
      text-align: center;
      font-size: 14px;
      margin: 2px 0 12px;
    }

    /* ===== Table ===== */
    table.nomination {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
    }

    table.nomination tr {
      page-break-inside: avoid;
    }

    table.nomination th,
    table.nomination td {
      border: 1px solid #000;
      padding: 3px 6px;
    }

    table.nomination thead th {
      text-align: center;
      vertical-align: middle;
      font-size: 12px;
      font-weight: bold;
      text-transform: uppercase;
    }

    table.nomination thead th.col-count {
      font-size: 10.5px;
      line-height: 1.25;
    }

    table.nomination td.center {
      text-align: center;
    }

    table.nomination td.name {
      text-transform: uppercase;
    }

    table.nomination tr.total td {
      font-weight: bold;
      font-size: 12px;
    }

    table.nomination tr.total td.label {
      text-align: right;
    }

    /* ===== Signatory ===== */
    .signatory {
      margin-top: 50px;
      text-align: center;
      width: 100%;
    }

    .signatory .name {
      font-size: 13px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .signatory .position {
      font-size: 12px;
    }

    /* ===== Footer ===== */
    .footer {
      position: fixed;
      left: 0;
      right: 0;
      bottom: 0;
      border-top: 0;
      font-size: 8px;
      color: #222;
    }

    .footer table {
      width: 100%;
      border-collapse: collapse;
    }

    .footer td {
      border: 0;
      padding: 0 6px;
      vertical-align: middle;
    }

    .footer .dot {
      display: inline-block;
      width: 8px;
      height: 8px;
      border-radius: 4px;
      background: #0e7a12;
      margin-right: 4px;
    }
  </style>
</head>

<body>

  {{-- ===== Footer (nasa bawat page) ===== --}}
  <div class="footer">
    <table>
      <tr>
        <td style="width:34%"><span class="dot"></span>{{ $footerAddress }}</td>
        <td style="width:28%"><span class="dot"></span>{{ $footerPhone }}</td>
        <td style="width:38%"><span class="dot"></span>{{ $footerEmail }}</td>
      </tr>
    </table>
  </div>

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

  <div class="title">{{ $title }}</div>
  <div class="subtitle">{{ $date }} | {{ $venue }}</div>

  <table class="nomination">
    <thead>
      <tr>
        <th style="width:8%">No.</th>
        <th style="width:67%">Name of Office</th>
        <th class="col-count" style="width:25%">{{ $columnLabel }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($offices as $i => $o)
      <tr>
        <td class="center">{{ $i + 1 }}</td>
        <td class="name">{{ $o['name'] }}</td>
        <td class="center">{{ $o['count'] }}</td>
      </tr>
      @endforeach
      <tr class="total">
        <td colspan="2" class="label">TOTAL</td>
        <td class="center">{{ $total }}</td>
      </tr>
    </tbody>
  </table>

  <div class="signatory">
    <div class="name">{{ $signatory }}</div>
    <div class="position">{{ $signatoryTitle }}</div>
  </div>

</body>

</html>