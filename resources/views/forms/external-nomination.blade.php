{{-- resources/views/nomination-form.blade.php --}}
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
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root { --green: #0e7a12; --green-soft: #eaf5ea; --line: #222; --muted: #555; }
  * { box-sizing: border-box; }

  @page { size: A4; margin: 10mm; }

  body {
    margin: 0;
    padding: 24px 0;
    background: #f2f2f2;
    font-family: 'Poppins', 'Century Gothic', 'Segoe UI', Arial, sans-serif;
    color: #111;
    font-size: 13px;
  }

  .page {
    width: 800px;
    max-width: 100%;
    margin: 0 auto;
    background: #fff;
    padding-bottom: 40px;
  }
  .content { padding: 18px 40px 0 40px; }

  .date { font-weight: 600; font-size: 13px; margin-bottom: 6px; }

  .title {
    text-align: center;
    font-size: 22px;
    font-weight: 700;
    letter-spacing: 1.5px;
    margin: 8px 0 22px;
    text-transform: uppercase;
  }
  .title::after {
    content: '';
    display: block;
    width: 90px;
    height: 3px;
    /* background: var(--green); */
    margin: 8px auto 0;
  }

  /* ===== Info fields ===== */
  table.fields { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
  table.fields td { padding: 6px 0; vertical-align: bottom; }
  table.fields td.label { width: 1%; white-space: nowrap; font-weight: 600; padding-right: 8px; }
  table.fields td.value { border-bottom: 1px solid var(--line); }

  .intro { margin: 22px 0 8px; color: var(--muted); }

  /* ===== Participants table ===== */
  table.participants {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
  }
  table.participants th,
  table.participants td { border: 1px solid var(--line); padding: 5px 6px; }
  table.participants thead th {
    background: var(--green-soft);
    color: #0a4d0d;
    font-weight: 600;
    text-transform: uppercase;
    text-align: center;
    vertical-align: middle;
    font-size: 10px;
    letter-spacing: .3px;
  }
  table.participants tbody td { height: 26px; }
  table.participants tbody td.no { text-align: center; font-weight: 600; width: 34px; }
  table.participants tbody tr:nth-child(even) td { background: #fafafa; }

  /* ===== Signature ===== */
  .signature { margin-top: 80px; text-align: center; }
  .signature .line {
    width: 280px;
    margin: 0 auto;
    border-top: 1px solid var(--line);
    padding-top: 4px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: .5px;
  }
  .signature .sub { font-size: 11px; color: var(--muted); }

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