<!-- <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Letterhead - City of Tagum</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --green: #0e7a12;
    --ink: #000;
  }
  * { box-sizing: border-box; }
  body {
    margin: 0;
    padding: 24px;
    background: #f2f2f2;
    font-family: 'Poppins', 'Century Gothic', 'Segoe UI', Arial, sans-serif;
  }

  .letterhead {
    position: relative;
    width: 800px;
    max-width: 100%;
    height: 145px;
    margin: 0 auto;
    background: #fff;
    /* border-top: 1px solid #ccc; */
    overflow: hidden;
  }

  /* Green bar sa kaliwa (nasa likod ng seal) */
  .bar-left {
    position: absolute;
    left: 0;
    top: 98px;
    width: 70px;
    height: 24px;
    background: var(--green);
    z-index: 1;
  }

  /* Seal (nasa ibabaw ng green bar) */
  .seal {
    position: absolute;
    left: 76px;
    top: 24px;
    width: 116px;
    height: 104px;
    z-index: 2;
  }
  .seal img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }

  /* Government lines */
  .gov {
    position: absolute;
    left: 222px;
    top: 50px;
    color: var(--ink);
    line-height: 1.2;
    z-index: 2;
  }
  .gov .small {
    font-size: 8px;
    text-transform: uppercase;
  }
  .gov .city {
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;
    line-height: 1.25;
  }

  /* Green bar with white office name */
  .office {
    position: absolute;
    left: 207px;
    right: 0;
    top: 98px;
    height: 24px;
    padding-left: 13px;
    background: var(--green);
    color: #fff;
    font-size: 15.5px;
    font-weight: 600;
    line-height: 24px;
    text-transform: uppercase;
    white-space: nowrap;
    letter-spacing: .2px;
    z-index: 1;
  }

  @media print {
    body { background: #fff; padding: 0; }
    .letterhead { border-top: none; }
    .bar-left, .office { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
  }
</style>
</head>
<body>

<header class="letterhead">
  <div class="bar-left"></div>

  <div class="seal">
    <img src="{{ asset('assets/image/logo.png') }}" alt="City of Tagum Seal">
  </div>

  <div class="gov">
    <div class="small">Republic of the Philippines</div>
    <div class="small">Province of Davao del Norte</div>
    <div class="city">City of Tagum</div>
  </div>

  <div class="office">Change this to the name of your respective office</div>
</header>

</body>
</html> -->
{{-- resources/views/header.blade.php --}}
@php
    $officeName = $officeName ?? 'Change this to the name of your respective office';

    $logoPath = public_path('assets/image/logo.png');
    $logo = file_exists($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;
@endphp

<style>
  .letterhead { position: relative; width: 800px; height: 145px; background: #fff; }
  .letterhead .bar-left {
    position: absolute; left: 0; top: 98px;
    width: 70px; height: 29px; background: #0e7a12;
  }
  .letterhead .seal {
    position: absolute; left: 76px; top: 24px;
    width: 116px; height: 104px;
  }
  .letterhead .seal img { width: 104px; height: 104px; }
  .letterhead .gov {
    position: absolute; left: 222px; top: 50px;
    color: #000; line-height: 1.2;
  }
  .letterhead .gov .small { font-size: 8px; text-transform: uppercase; }
  .letterhead .gov .city { font-size: 16px; font-weight: bold; text-transform: uppercase; }
  .letterhead .office {
    position: absolute; left: 207px; top: 98px;
    width:100%; height: 29px; padding-left: 13px;
    background: #0e7a12; color: #fff;
    font-size: 14px; font-weight: bold; line-height: 20px;
    text-transform: uppercase; white-space: nowrap;
  }
</style>

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