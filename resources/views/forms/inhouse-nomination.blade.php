{{-- resources/views/inhouse-nomination-form.blade.php --}}
@php
    $date         = $date         ?? now()->format('F d, Y');
    $officeName   = $officeName   ?? 'Change this to the name of your respective office';
    $participants = $participants ?? [];
    $minRows      = 5;
    $rowCount     = max($minRows, count($participants));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Nomination Form</title>
<!-- <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"> -->
<style>
  :root { --green: #0e7a12; --green-soft: #eaf5ea; --line: #222; --muted: #555; }
  * { box-sizing: border-box; }

@page { margin: 10mm; }
body { margin: 0; font-family: 'DejaVu Sans', sans-serif; color: #111; font-size: 11px; }
.content { padding: 10px 20px 0; }
.date { font-weight: bold; margin-bottom: 6px; }
.title { text-align: center; font-size: 18px; font-weight: bold; letter-spacing: 1.5px; margin: 8px 0 18px; text-transform: uppercase; }

table.fields { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
table.fields td { padding: 5px 0; vertical-align: bottom; }
table.fields td.label { width: 110px; font-weight: bold; white-space: nowrap; }
table.fields td.value { border-bottom: 1px solid #222; }

.intro { margin: 18px 0 8px; color: #555; }

table.participants { width: 100%; border-collapse: collapse; font-size: 9px; }
table.participants th, table.participants td { border: 1px solid #222; padding: 4px 5px; }
table.participants thead th { background: #eaf5ea; color: #0a4d0d; text-transform: uppercase; text-align: center; font-size: 8px; }
table.participants tbody td { height: 24px; }
table.participants tbody td.no { text-align: center; font-weight: bold; width: 28px; }

.signature { margin-top: 60px; text-align: center; }
.signature .line { width: 260px; margin: 0 auto; border-top: 1px solid #222; padding-top: 4px; font-weight: bold; text-transform: uppercase; font-size: 11px; }
.signature .sub { font-size: 10px; color: #555; }
  @media print {
    body { background: #fff; padding: 0; }
    .page { width: 100%; }
    table.participants thead th,
    table.participants tbody tr:nth-child(even) td,
    .title::after {
      -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
  }
</style>
</head>
<body>

<div class="page">

  {{-- Header (galing sa header.blade.php) --}}
  @include('header', ['officeName' => $officeName])

  <div class="content">

    <div class="date">{{ $date }}</div>

    <div class="title">Nomination Form</div>

    <table class="fields">
      <tr>
        <td class="label">Name of Office:</td>
        <td class="value">{{ $office ?? '' }}</td>
      </tr>
      <tr>
        <td class="label">Title of Training:</td>
        <td class="value">{{ $training_title ?? '' }}</td>
      </tr>
      <tr>
        <td class="label">Date of Training:</td>
        <td class="value">{{ $training_date ?? '' }}</td>
      </tr>
    </table>

    <p class="intro">We will send the following participants:</p>

    <table class="participants">
      <thead>
        <tr>
          <th rowspan="2">No.</th>
          <th colspan="3">Name</th>
          <th>Sex</th>
          <th rowspan="2">Position / Designation</th>
          <th rowspan="2">Relevance of the training to the nature of work</th>
          <th rowspan="2">Employment Status</th>
          <th rowspan="2">Contact Number</th>
        </tr>
        <tr>
          <th>Surname</th>
          <th>First Name</th>
          <th>M.I</th>
          <th>M/F</th>
        </tr>
      </thead>
      <tbody>
        @for ($i = 0; $i < $rowCount; $i++)
          @php $p = $participants[$i] ?? null; @endphp
          <tr>
            <td class="no">{{ $i + 1 }}.</td>
            <td>{{ $p['surname']    ?? '' }}</td>
            <td>{{ $p['first_name'] ?? '' }}</td>
            <td>{{ $p['mi']         ?? '' }}</td>
            <td style="text-align:center">{{ $p['sex'] ?? '' }}</td>
            <td>{{ $p['position']   ?? '' }}</td>
            <td>{{ $p['relevance']  ?? '' }}</td>
            <td>{{ $p['status']     ?? '' }}</td>
            <td>{{ $p['contact']    ?? '' }}</td>
          </tr>
        @endfor
      </tbody>
    </table>

    <div class="signature">
      <div class="line">{{ $office_head ?? '' }}</div>
      <div class="sub">Office Head</div>
    </div>

  </div>
</div>

</body>
</html>