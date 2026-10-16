
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