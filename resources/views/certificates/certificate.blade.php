<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certificate</title>
<style>
  /*
   | =========================================================
   | FONT REGISTRATION
   | =========================================================
   | DomPDF hindi kayang kumuha ng Google Fonts CDN link sa render time.
   | I-download mo ang .ttf files (Playfair Display, Work Sans) papunta sa
   | resources/fonts/, tapos i-register sa config/dompdf.php:
   |
   |   'font_dir' => storage_path('fonts/'),
   |   'font_cache' => storage_path('fonts/'),
   |
   | at i-run: php artisan dompdf:autoload-fonts (kung available sa package version mo)
   | o manual na i-copy sa storage/fonts/ folder.
   |
   | Samantala, ginamit dito ang mga built-in na font ng DomPDF
   | (DejaVu Serif / DejaVu Sans) bilang safe fallback — gumagana agad
   | kahit hindi mo pa na-rerehistro ang custom fonts.
   */

  :root {
    --deep-green: #14532d;
    --accent-green: #2fa86b;
    --mint-line: #cfe9d9;
    --ink: #1c2b22;
    --muted: #5b6f62;
  }

  * { box-sizing: border-box; }

  body {
    margin: 0;
    padding: 0;
    font-family: "DejaVu Sans", sans-serif;
    color: var(--ink);
  }

  /* =========================================================
     FRAME — simpleng double border, hindi na rotated squares
     (hindi sinusuportahan ni DomPDF ang transform: rotate())
  ========================================================= */

  .certificate {
    width: 100%;
    height: 100%;
    background: #ffffff;
    border: 10px solid #14532d;
    padding: 8px;
  }

  .certificate-inner {
    border: 1.5px solid var(--mint-line);
    padding: 40px 70px;
    text-align: center;
  }

  .top-bar {
    height: 6px;
    width: 100%;
    background: var(--accent-green);
    margin: -40px -70px 34px -70px;
  }

  /* =========================================================
     SEAL — plain circle, safe sa dompdf (may border-radius support)
  ========================================================= */

  .seal {
    width: 84px;
    height: 84px;
    border-radius: 50%;
    background: var(--deep-green);
    color: #ffffff;
    font-family: "DejaVu Sans", sans-serif;
    font-weight: bold;
    font-size: 10px;
    letter-spacing: 0.5px;
    text-align: center;
    /* vertical centering na paraan na kayang i-render ni dompdf (walang flexbox support) */
    line-height: 84px;
    margin: 0 auto;
  }

  .heading {
    margin-top: 22px;
    color: var(--deep-green);
    font-family: "DejaVu Sans", sans-serif;
    font-weight: bold;
    font-size: 22px;
    letter-spacing: 1px;
  }

  .script-line {
    margin-top: 16px;
    color: var(--accent-green);
    font-family: "DejaVu Serif", serif;
    font-style: italic;
    font-size: 32px;
  }

  .recipient {
    margin-top: 18px;
    color: var(--deep-green);
    font-family: "DejaVu Serif", serif;
    font-weight: bold;
    font-size: 32px;
  }

  .rule {
    margin: 20px auto 0;
    width: 280px;
    height: 1px;
    background: var(--mint-line);
  }

  .body-text {
    margin: 16px auto 0;
    max-width: 480px;
    color: var(--muted);
    font-size: 12px;
    line-height: 1.7;
  }

  .body-text .fill {
    color: var(--ink);
    font-weight: bold;
  }

  /* =========================================================
     FOOTER — table layout dahil hindi supported ang flexbox/grid
     sa dompdf; table ang pinaka-reliable para sa side-by-side blocks
  ========================================================= */

  .footer {
    width: 100%;
    margin-top: 60px;
  }

  .footer td {
    width: 50%;
    font-size: 11px;
    vertical-align: bottom;
  }

  .footer .right { text-align: right; }

  .footer-line {
    border-top: 1px solid var(--mint-line);
    padding-top: 6px;
  }

  .footer-name {
    color: var(--deep-green);
    font-weight: bold;
    font-size: 13px;
  }

  .footer-label {
    margin-top: 2px;
    color: var(--muted);
    font-size: 9.5px;
  }
</style>
</head>
<body>

  <div class="certificate">
    <div class="certificate-inner">
      <div class="top-bar"></div>

      <div class="seal">YOUR<br>LOGO</div>

      <div class="heading">CERTIFICATE OF TRAINING COMPLETION</div>

      <div class="script-line">Congratulations!</div>

      <div class="recipient">{{ $recipientName }}</div>

      <div class="rule"></div>

      <div class="body-text">
        This Training Completion Certificate is presented to
        <span class="fill">{{ $recipientName }}</span> for successfully completing
        <span class="fill">{{ $trainingTitle }}</span>, demonstrating a strong
        commitment to professional growth and development.
      </div>

      <table class="footer">
        <tr>
          <td>
            <div class="footer-line">
              <div class="footer-name">{{ $signatoryName }}</div>
              <div class="footer-label">{{ $signatoryTitle }}</div>
            </div>
          </td>
          <td class="right">
            <div class="footer-line">
              <div class="footer-name">{{ $dateIssued }}</div>
              <div class="footer-label">Date</div>
            </div>
          </td>
        </tr>
      </table>
    </div>
  </div>

</body>
</html>