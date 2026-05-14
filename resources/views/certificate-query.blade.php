<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Akıllı Belge Sorgulama</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
    rel="stylesheet">

  <style>
    body {
      margin: 0;
      background-color: #f8fafc;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      box-sizing: border-box;
      overflow: hidden;
    }

    .certificate-query-wrapper {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* User's CSS */
    .asi-career-page {
      font-family: "Plus Jakarta Sans", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    /* CSS Zoom removed due to calc/max browser compatibility issues. Applied via JS instead. */

    .asi-career-page * {
      box-sizing: border-box;
    }

    .asi-career-layout {
      display: grid;
      grid-template-columns: .9fr 1.05fr 1.05fr;
      gap: 18px;
      width: 100%;
      flex: 1;
      align-items: stretch;
    }

    .asi-career-card,
    .asi-vCard,
    .asi-right-career-card {
      border: 1px solid rgba(15, 17, 21, .10);
      border-radius: 22px;
      box-shadow: 0 14px 30px rgba(15, 17, 21, .05);
      padding: 30px 24px;
      /* Padding'i artırdık ki içerik daha dolgun dursun */
      display: flex;
      flex-direction: column;
      height: 100%;
      justify-content: center;
      /* İçerikleri kartın dikeyde tam ortasına alıyoruz! */
    }

    .asi-career-card {
      background: linear-gradient(135deg, #f5f3ff 0%, #ffffff 70%);
    }

    .asi-vCard {
      background: linear-gradient(180deg, #ffffff 0%, #fafbff 100%);
    }

    .asi-right-career-card {
      background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
    }

    .asi-heroTopBadges,
    .asi-secBadgeRow {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .asi-topBadge,
    .asi-secBadge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      padding: 7px 11px;
      border-radius: 999px;
      border: 1px solid rgba(15, 17, 21, .10);
      background: #fff;
      font-size: 11px;
      font-weight: 700;
    }

    .asi-topBadgePrimary,
    .asi-secBadgePrimary {
      color: #fff;
      border: 0;
      background: linear-gradient(135deg, #7c3aed, #3b82f6);
    }

    .asi-secBadgePrimary {
      background: linear-gradient(135deg, #111827, #7c3aed);
    }

    .asi-topBadgeLogo,
    .asi-secBadgeLogo {
      width: 15px;
      height: 15px;
    }

    .asi-h1 {
      margin: 16px 0 0;
      font-size: 30px;
      line-height: 1.25;
      font-weight: 700;
    }

    .asi-h1-line {
      display: block;
      background: linear-gradient(135deg, #6d28d9, #2563eb);
      -webkit-background-clip: text;
      color: transparent;
    }

    .asi-h1-line-dark {
      background: none;
      color: #111827;
    }

    .asi-heroLead {
      margin-top: 12px;
      font-size: 14px;
      color: rgba(0, 0, 0, .65);
      line-height: 1.7;
    }

    .asi-heroMini {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-top: 14px;
    }

    .asi-miniStat {
      padding: 6px 10px;
      border-radius: 12px;
      border: 1px solid rgba(0, 0, 0, .10);
      background: #fff;
      font-size: 13px;
    }

    .asi-search {
      margin-top: 14px;
      display: grid;
      gap: 8px;
    }

    .asi-search input {
      height: 44px;
      padding: 0 14px;
      border-radius: 12px;
      border: 1px solid rgba(15, 17, 21, .14);
      outline: none;
    }

    .asi-search input:focus,
    .asi-input:focus {
      border-color: rgba(124, 58, 237, .38);
      box-shadow: 0 0 0 4px rgba(124, 58, 237, .10);
    }

    .asi-search-buttons {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px;
    }

    .asi-search-buttons a {
      display: block;
    }

    .asi-search button,
    .asi-btn {
      width: 100%;
      height: 44px;
      border-radius: 12px;
      border: 0;
      color: #fff;
      font-weight: 700;
      cursor: pointer;
      transition: .2s ease;
    }

    .asi-search button {
      background: linear-gradient(135deg, #111827, #7c3aed);
    }

    .asi-btn {
      background: linear-gradient(135deg, #7c3aed, #3b82f6);
    }

    .asi-search button:hover,
    .asi-btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 14px 30px rgba(124, 58, 237, .20);
    }

    .asi-search-buttons {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-top: 14px;
    }

    .asi-search-buttons a,
    .asi-search-buttons button {
      width: 100%;
    }

    .asi-register-trigger {
      background: linear-gradient(135deg, #4f46e5, #7c3aed) !important;
    }

    .asi-logoTicker {
      margin-top: 14px;
      overflow: hidden;
      border-radius: 14px;
      border: 1px solid rgba(0, 0, 0, .08);
      background: #fff;
    }

    .asi-logoTickerTrack {
      display: flex;
      width: max-content;
      gap: 14px;
      padding: 10px 12px;
      animation: asiHeroTicker 24s linear infinite;
    }

    .asi-tickerItem {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 13px;
      font-weight: 700;
      white-space: nowrap;
    }

    .asi-tickerItem img {
      width: 14px;
    }

    .asi-h2 {
      margin: 14px 0 0;
      font-size: 22px;
      font-weight: 700;
    }

    .asi-sub {
      margin-top: 8px;
      color: rgba(15, 17, 21, .60);
      font-size: 13px;
      line-height: 1.6;
    }

    .asi-inlineBox {
      margin-top: 14px;
      border: 1px solid rgba(15, 17, 21, .08);
      border-radius: 18px;
      background: #fff;
      padding: 14px;
    }

    .asi-label {
      font-size: 12px;
      font-weight: 600;
    }

    .asi-formRow {
      margin-top: 10px;
      display: grid;
      grid-template-columns: 1fr 115px;
      gap: 10px;
    }

    .asi-input {
      height: 44px;
      border-radius: 12px;
      border: 1px solid rgba(15, 17, 21, .14);
      padding: 0 13px;
    }

    .asi-miniInfo {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
      margin-top: 10px;
    }

    .asi-miniTag {
      padding: 6px 10px;
      border-radius: 999px;
      background: rgba(15, 17, 21, .04);
      border: 1px solid rgba(15, 17, 21, .08);
      font-size: 11px;
      font-weight: 700;
    }

    .asi-previewMini {
      margin-top: 12px;
      border-radius: 16px;
      background: linear-gradient(135deg, #fcfcff 0%, #f5f3ff 100%);
      border: 1px solid rgba(124, 58, 237, .10);
      padding: 12px;
    }

    .asi-previewTop {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .asi-brand {
      display: flex;
      align-items: center;
      gap: 7px;
      font-size: 12px;
      font-weight: 700;
    }

    .asi-brandLogo {
      width: 18px;
      height: 18px;
    }

    .asi-status {
      padding: 6px 10px;
      border-radius: 999px;
      background: rgba(34, 197, 94, .12);
      border: 1px solid rgba(34, 197, 94, .25);
      color: #15803d;
      font-size: 11px;
      font-weight: 800;
    }

    .asi-previewTitle {
      margin-top: 10px;
      font-size: 15px;
      font-weight: 800;
    }

    .asi-previewGrid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px;
      margin-top: 10px;
    }

    .asi-field {
      border: 1px solid rgba(15, 17, 21, .07);
      background: #fff;
      border-radius: 12px;
      padding: 10px;
    }

    .asi-k {
      font-size: 10px;
      color: rgba(15, 17, 21, .55);
      font-weight: 700;
      text-transform: uppercase;
    }

    .asi-v {
      margin-top: 4px;
      font-size: 12px;
      font-weight: 700;
    }

    .asi-v.ok {
      color: #16a34a;
    }

    .asi-right-top,
    .asi-right-bottom {
      border: 1px solid rgba(15, 17, 21, .07);
      border-radius: 18px;
      background: #fff;
      padding: 14px;
    }

    .asi-right-head h3 {
      margin: 6px 0 0;
      font-size: 18px;
      font-weight: 800;
    }

    .asi-right-head p {
      margin-top: 8px;
      font-size: 12px;
      color: rgba(17, 24, 39, .62);
      line-height: 1.6;
    }

    .asi-right-eyebrow {
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      color: #6d28d9;
    }

    .asi-trainer-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
      margin-top: 14px;
    }

    .asi-trainer-mini-card {
      text-align: center;
      padding: 10px;
      border-radius: 16px;
      background: linear-gradient(180deg, #ffffff 0%, #f7f9ff 100%);
      border: 1px solid rgba(15, 17, 21, .07);
    }

    .asi-trainer-photo-wrap {
      position: relative;
      width: 74px;
      height: 74px;
      margin: 0 auto;
    }

    .asi-trainer-photo {
      width: 74px;
      height: 74px;
      border-radius: 22px;
      object-fit: cover;
    }

    .asi-trainer-badge {
      position: absolute;
      top: -6px;
      right: -6px;
      width: 28px;
      height: 28px;
      border-radius: 999px;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .asi-trainer-badge img {
      width: 20px;
      height: 20px;
    }

    .asi-trainer-name {
      margin-top: 10px;
      font-size: 12px;
      font-weight: 800;
    }

    .asi-job-mini-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 12px;
    }

    .asi-job-mini-title {
      font-size: 16px;
      font-weight: 800;
    }

    .asi-job-mini-sub {
      margin-top: 4px;
      font-size: 11px;
      color: rgba(17, 24, 39, .58);
    }

    .asi-job-mini-list {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
    }

    .asi-job-mini-card {
      border: 1px solid rgba(15, 17, 21, .07);
      border-radius: 14px;
      background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
      padding: 11px 12px;
    }

    .asi-job-mini-company {
      font-size: 11px;
      font-weight: 800;
    }

    .asi-job-mini-role {
      margin-top: 7px;
      font-size: 13px;
      font-weight: 800;
    }

    .asi-job-mini-meta {
      margin-top: 5px;
      font-size: 10px;
      color: rgba(17, 24, 39, .60);
    }

    @keyframes asiHeroTicker {
      0% {
        transform: translateX(0)
      }

      100% {
        transform: translateX(-35%)
      }
    }

    @media(max-width:1200px) {
      .asi-career-layout {
        grid-template-columns: 1fr;
      }

      .asi-formRow {
        grid-template-columns: 1fr;
      }
    }

    @media(max-width:767px) {

      .asi-previewGrid,
      .asi-job-mini-list,
      .asi-search-buttons {
        grid-template-columns: 1fr;
      }

      .asi-trainer-grid {
        grid-template-columns: 1fr 1fr 1fr;
      }
    }

    /* Modal Styles */
    .asi-modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(15, 17, 21, 0.4);
      backdrop-filter: blur(8px);
      z-index: 1000;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .asi-modal {
      background: #fff;
      width: 100%;
      max-width: 480px;
      max-height: 90vh;
      overflow-y: auto;
      border-radius: 28px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
      position: relative;
      animation: modalSlideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      scrollbar-width: thin;
      scrollbar-color: #cbd5e1 transparent;
    }

    .asi-modal::-webkit-scrollbar {
      width: 6px;
    }

    .asi-modal::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }

    @keyframes modalSlideUp {
      from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
      }

      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .asi-modal-header {
      padding: 30px 30px 10px;
      text-align: center;
    }

    .asi-modal-close {
      position: absolute;
      top: 20px;
      right: 20px;
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: none;
      color: #64748b;
      transition: all 0.2s;
    }

    .asi-modal-close:hover {
      background: #e2e8f0;
      color: #0f172a;
    }

    .asi-modal-title {
      font-size: 24px;
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 8px;
    }

    .asi-modal-sub {
      font-size: 14px;
      color: #64748b;
    }

    .asi-modal-body {
      padding: 20px 30px 40px;
    }

    .asi-modal-tabs {
      display: flex;
      padding: 0 30px;
      gap: 20px;
      margin-top: 10px;
      border-bottom: 1px solid #f1f5f9;
    }

    .asi-modal-tab {
      flex: 1;
      padding: 12px 0;
      text-align: center;
      font-size: 14px;
      font-weight: 700;
      color: #94a3b8;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .asi-modal-tab.active {
      color: #0f172a;
      border-bottom-color: #0f172a;
    }

    .asi-tab-content {
      display: none;
      animation: tabFade 0.3s ease-out;
    }

    .asi-tab-content.active {
      display: block;
    }

    @keyframes tabFade {
      from {
        opacity: 0;
        transform: translateY(5px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .asi-form-group {
      margin-bottom: 18px;
    }

    .asi-form-label {
      display: block;
      font-size: 13px;
      font-weight: 700;
      color: #475569;
      margin-bottom: 6px;
      padding-left: 4px;
    }

    .asi-modal-input {
      width: 100%;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 12px 16px;
      font-size: 14px;
      font-family: inherit;
      transition: all 0.2s;
      outline: none;
    }

    .asi-modal-input:focus {
      background: #fff;
      border-color: #6366f1;
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .asi-modal-btn {
      width: 100%;
      background: #0f172a;
      color: #fff;
      border: none;
      border-radius: 14px;
      padding: 14px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
      margin-top: 10px;
    }

    .asi-modal-btn:hover {
      background: #1e293b;
      transform: translateY(-1px);
    }

    .asi-divider {
      display: flex;
      align-items: center;
      text-align: center;
      margin: 24px 0;
      color: #94a3b8;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .asi-divider::before,
    .asi-divider::after {
      content: '';
      flex: 1;
      border-bottom: 1px solid #e2e8f0;
    }

    .asi-divider:not(:empty)::before {
      margin-right: 1.5em;
    }

    .asi-divider:not(:empty)::after {
      margin-left: 1.5em;
    }

    .asi-google-btn {
      width: 100%;
      background: #fff;
      color: #0f172a;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 12px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      transition: all 0.2s;
      text-decoration: none;
    }

    .asi-google-btn:hover {
      background: #f8fafc;
      border-color: #cbd5e1;
      transform: translateY(-1px);
    }

    .asi-google-icon {
      width: 18px;
      height: 18px;
    }
  </style>
</head>

<body>
  <div class="certificate-query-wrapper">
    <section class="asi-career-page" data-asi-career-page>
      <div class="asi-career-layout">

        <div class="asi-vCard">
          <div class="asi-secBadgeRow">
            <span class="asi-secBadge asi-secBadgePrimary">
              <img src="https://australiasportstr.com/wp-content/uploads/2023/09/cropped-1000x1000_logo.png"
                alt="ASI Logo" class="asi-secBadgeLogo">
              Akıllı Belge Doğrulama
            </span>
            <span class="asi-secBadge">QR Destekli</span>
          </div>

          <h2 class="asi-h2">Akıllı Belge Sorgulama</h2>
          <p class="asi-sub">Belgenizi anında sorgulayın ve doğrulayın.</p>

          <div class="asi-inlineBox">
            <div class="asi-label">Ad Soyad veya Sertifika No</div>

            <div class="asi-formRow">
              <input class="asi-input" type="text" placeholder="Örn: Ayşe Yılmaz veya ASI-PLT-2026-00421"
                autocomplete="off">
              <button class="asi-btn" type="button">SORGULA</button>
            </div>

            <div class="asi-miniInfo">
              <span class="asi-miniTag">Resmi Kayıt</span>
              <span class="asi-miniTag">Doğrulanmış</span>
              <span class="asi-miniTag">Anında Sonuç</span>
            </div>

            <div class="asi-previewMini" id="resultCard" style="display: none;">
              <div class="asi-previewTop">
                <div class="asi-brand">
                  <img src="https://australiasportstr.com/wp-content/uploads/2023/09/cropped-1000x1000_logo.png"
                    alt="ASI Logo" class="asi-brandLogo">
                  <span>ASI Sertifika</span>
                </div>
                <div class="asi-status" id="res-status-badge">Aktif</div>
              </div>

              <div class="asi-previewTitle" id="res-title">Örnek Dijital Sertifika</div>

              <div class="asi-previewGrid">
                <div class="asi-field">
                  <div class="asi-k">Ad Soyad</div>
                  <div class="asi-v" id="res-name">-</div>
                </div>
                <div class="asi-field">
                  <div class="asi-k">Branş</div>
                  <div class="asi-v" id="res-branch">-</div>
                </div>
                <div class="asi-field">
                  <div class="asi-k">Kod</div>
                  <div class="asi-v asi-code mono" id="res-code">-</div>
                </div>
                <div class="asi-field">
                  <div class="asi-k">Durum</div>
                  <div class="asi-v" id="res-status-text">Doğrulandı</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="asi-career-card">
          <div class="asi-heroTopBadges">
            <span class="asi-topBadge asi-topBadgePrimary">
              <img src="https://australiasportstr.com/wp-content/uploads/2023/09/cropped-1000x1000_logo.png"
                class="asi-topBadgeLogo" alt="ASI Logo">
              Uluslararası Sertifika
            </span>
            <span class="asi-topBadge">Doğrulanmış Profil</span>
          </div>

          <div class="asi-left">
            <h2 class="asi-h1">
              <span class="asi-h1-line">Sertifikan Başlangıç,</span>
              <span class="asi-h1-line asi-h1-line-dark">Kariyerin Burada Güçlenir</span>
            </h2>

            <p class="asi-heroLead">
              Doğrulanmış sertifika, görünür profil ve kariyer fırsatları tek sistemde birleşir.
            </p>

            <div class="asi-heroMini">
              <span class="asi-miniStat"><strong>+12.000</strong> Mezun</span>
              <span class="asi-miniStat"><strong>%100</strong> Doğrulama</span>
              <span class="asi-miniStat"><strong>7/24</strong> Sorgulama</span>
            </div>

            <form action="{{ route('public.job-listings') }}" method="GET" class="asi-search">
              <input type="text" name="position" placeholder="Pozisyon ara">
              <input type="text" name="city" placeholder="Şehir ara">

              <div class="asi-search-buttons">
                <button type="submit">İŞ BUL</button>
                <button type="button" class="asi-register-trigger" id="openModalBtn">KAYIT OL</button>
              </div>
            </form>

            <div class="asi-logoTicker">
              <div class="asi-logoTickerTrack">
                <span class="asi-tickerItem"><img
                    src="https://australiasportstr.com/wp-content/uploads/2023/09/cropped-1000x1000_logo.png" alt="">
                  Certified</span>
                <span class="asi-tickerItem"><img
                    src="https://australiasportstr.com/wp-content/uploads/2023/09/cropped-1000x1000_logo.png" alt="">
                  Verified</span>
                <span class="asi-tickerItem"><img
                    src="https://australiasportstr.com/wp-content/uploads/2023/09/cropped-1000x1000_logo.png" alt="">
                  Smart System</span>
                <span class="asi-tickerItem"><img
                    src="https://australiasportstr.com/wp-content/uploads/2023/09/cropped-1000x1000_logo.png" alt="">
                  Digital</span>
              </div>
            </div>
          </div>
        </div>

        <div class="asi-right-career-card">
          <div class="asi-right-top">
            <div class="asi-right-head">
              <span class="asi-right-eyebrow">Örnek Mezun Profilleri</span>
              <h3>Sertifikasını Alan Eğitmenler</h3>
              <p>Doğrulanmış profil ve rozetleriyle görünür olan örnek eğitmenler.</p>
            </div>

            <div class="asi-trainer-grid">
              @foreach($featuredTrainers as $trainer)
                <div class="asi-trainer-mini-card">
                  <div class="asi-trainer-photo-wrap">
                    <img
                      src="{{ $trainer->profile_photo_url ?: 'https://ui-avatars.com/api/?name=' . urlencode($trainer->name . ' ' . $trainer->surname) . '&color=FFFFFF&background=7c3aed' }}"
                      alt="{{ $trainer->name }}" class="asi-trainer-photo">
                    <span class="asi-trainer-badge">
                      <img src="https://australiasportstr.com/wp-content/uploads/2023/09/cropped-1000x1000_logo.png"
                        alt="ASI Logo">
                    </span>
                  </div>
                  <div class="asi-trainer-name" style="text-transform: capitalize;">{{ $trainer->name }}
                    {{ $trainer->surname }}
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <div class="asi-right-bottom">
            <div class="asi-job-mini-head">
              <div>
                <div class="asi-job-mini-title">İş İlanları</div>
                <div class="asi-job-mini-sub">Sertifika sonrası örnek pozisyonlar</div>
              </div>
            </div>

            <div class="asi-job-mini-list">
              @forelse($latestJobs as $job)
                <div class="asi-job-mini-card">
                  <div class="asi-job-mini-company">İş Fırsatı</div>
                  <div class="asi-job-mini-role">{{ Str::limit($job->job_title, 25) }}</div>
                  <div class="asi-job-mini-meta">{{ Str::limit($job->job_description, 40) }}</div>
                </div>
              @empty
                <div class="asi-job-mini-card"
                  style="grid-column: span 3; text-align: center; color: rgba(17, 24, 39, .5);">
                  Henüz aktif iş ilanı bulunmuyor.
                </div>
              @endforelse
            </div>
          </div>
        </div>

      </div>
    </section>

    <!-- Register Modal -->
    <div class="asi-modal-overlay" id="modalOverlay">
      <div class="asi-modal">
        <button class="asi-modal-close" id="closeModalBtn">&times;</button>
        <div class="asi-modal-header">
          <div class="asi-modal-title">Üyelik Oluştur</div>
          <div class="asi-modal-sub">Kariyer yolculuğuna bugün başla.</div>
        </div>
        <div class="asi-modal-tabs">
          <div class="asi-modal-tab active" data-tab="login">Giriş Yap</div>
          <div class="asi-modal-tab" data-tab="register">Kayıt Ol</div>
        </div>

        <div class="asi-modal-body">
          <a href="{{ route('google.redirect') }}" class="asi-google-btn">
            <img src="https://www.svgrepo.com/show/355037/google.svg" alt="Google" class="asi-google-icon">
            Google ile devam et
          </a>

          <div class="asi-divider">VEYA</div>

          <!-- Login Tab -->
          <div id="loginTab" class="asi-tab-content active">
            <form id="loginForm" action="{{ route('login') }}" method="POST">
              @csrf
              <div class="asi-form-group">
                <label class="asi-form-label">E-posta</label>
                <input type="email" name="email" class="asi-modal-input" placeholder="ahmet@ornek.com" required>
              </div>
              <div class="asi-form-group">
                <div class="flex justify-between items-center mb-1">
                  <label class="asi-form-label mb-0">Şifre</label>
                  <a href="{{ route('password.request') }}"
                    class="text-[11px] font-bold text-slate-400 hover:text-slate-600 transition-colors">Şifremi
                    Unuttum</a>
                </div>
                <input type="password" name="password" class="asi-modal-input" placeholder="••••••••" required>
              </div>
              <button type="submit" class="asi-modal-btn">Giriş Yap</button>
              <p class="text-center text-xs text-slate-400 mt-6 font-medium">Hesabınız yok mu? <a
                  href="javascript:void(0)" onclick="switchTab('register')"
                  class="text-slate-900 font-bold underline">Kayıt Ol</a></p>
            </form>
          </div>

          <!-- Register Tab -->
          <div id="registerTab" class="asi-tab-content">
            <form id="registerForm" action="{{ route('register') }}" method="POST">
              @csrf
              <div class="asi-form-group">
                <label class="asi-form-label">Ad Soyad</label>
                <input type="text" name="full_name" class="asi-modal-input" placeholder="Örn: Ahmet Yılmaz" required>
              </div>
              <div class="asi-form-group">
                <label class="asi-form-label">E-posta</label>
                <input type="email" name="email" class="asi-modal-input" placeholder="ahmet@ornek.com" required>
              </div>
              <div class="asi-form-group">
                <label class="asi-form-label">Telefon</label>
                <input type="tel" name="gsm" class="asi-modal-input" placeholder="05xx xxx xx xx" required>
              </div>
              <div class="asi-form-group">
                <label class="asi-form-label">Doğum Tarihi</label>
                <input type="date" name="birth_date" class="asi-modal-input" required>
              </div>
              <div class="asi-form-group">
                <label class="asi-form-label">Şifre</label>
                <input type="password" name="password" class="asi-modal-input" placeholder="••••••••" required>
              </div>
              <div class="asi-form-group">
                <label class="asi-form-label">Şifre Tekrar</label>
                <input type="password" name="password_confirmation" class="asi-modal-input" placeholder="••••••••"
                  required>
              </div>
              <button type="submit" class="asi-modal-btn">Kayıt Ol</button>
              <p class="text-center text-xs text-slate-400 mt-6 font-medium">Zaten hesabınız var mı? <a
                  href="javascript:void(0)" onclick="switchTab('login')"
                  class="text-slate-900 font-bold underline">Giriş Yap</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function () {
      const root = document.querySelector('[data-asi-career-page]');
      const wrapper = document.querySelector('.certificate-query-wrapper');

      if (!root) return;

      // Güvenilir Zoom (Büyütme) Mantığı - Tüm tarayıcılarda kusursuz çalışır
      function applyZoom() {
        if (window.innerWidth >= 1200) {
          // Ekran 1400px'miş gibi davranıp aradaki farkı zoom ile kapatıyoruz
          let z = window.innerWidth / 1400;
          if (z < 1) z = 1;
          if (z > 1.45) z = 1.45; // Maksimum 1.45 katına kadar büyüsün
          root.style.zoom = z;
        } else {
          root.style.zoom = 1;
        }
      }

      window.addEventListener('resize', applyZoom);
      applyZoom();
      setTimeout(applyZoom, 100);

      const input = root.querySelector('.asi-input');
      const btn = root.querySelector('.asi-btn');

      async function run() {
        const val = input.value.trim();
        if (!val) return;

        btn.disabled = true;
        btn.textContent = '...';

        try {
          const formData = new FormData();
          // Eğer ASI içeriyorsa veya rakam varsa register_no olarak gönder, yoksa full_name
          if (val.toUpperCase().includes('ASI') || /\d/.test(val)) {
            formData.append('register_no', val);
          } else {
            formData.append('full_name', val);
          }

          const response = await fetch('/student-portal/search', {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
              'Accept': 'application/json'
            },
            body: formData
          });

          const data = await response.json();

          if (data.success) {
            const cert = data.searched_certificate;
            const student = data.student;

            document.getElementById('res-name').textContent = student.name + ' ' + (student.surname || '');
            document.getElementById('res-branch').textContent = cert.certificate ? cert.certificate.certificate_name : '-';
            document.getElementById('res-code').textContent = cert.register_no || cert.certificate_code;
            document.getElementById('res-title').textContent = cert.certificate ? cert.certificate.certificate_name : 'Dijital Sertifika';

            const statusText = document.getElementById('res-status-text');
            statusText.textContent = 'Doğrulandı';
            statusText.style.color = '#16a34a';

            document.getElementById('resultCard').style.display = 'block';
          } else {
            alert(data.message || 'Sertifika bulunamadı.');
            document.getElementById('resultCard').style.display = 'none';
          }
        } catch (err) {
          console.error(err);
          alert('Sorgulama sırasında bir hata oluştu.');
        } finally {
          btn.disabled = false;
          btn.textContent = 'SORGULA';
        }
      }

      btn.addEventListener('click', run);

      input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          run();
        }
      });

      // Modal Logic
      const modalOverlay = document.getElementById('modalOverlay');
      const openModalBtn = document.getElementById('openModalBtn');
      const closeModalBtn = document.getElementById('closeModalBtn');

      if (openModalBtn && modalOverlay) {
        openModalBtn.addEventListener('click', () => {
          modalOverlay.style.display = 'flex';
          document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
      }

      if (closeModalBtn && modalOverlay) {
        closeModalBtn.addEventListener('click', () => {
          modalOverlay.style.display = 'none';
          document.body.style.overflow = ''; // Restore scrolling
        });
      }

      // Close on overlay click
      modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
          modalOverlay.style.display = 'none';
          document.body.style.overflow = '';
        }
      });

      // Close on Escape key
      window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modalOverlay.style.display === 'flex') {
          modalOverlay.style.display = 'none';
          document.body.style.overflow = '';
        }
      });

      // Tab Switching Logic
      const tabs = document.querySelectorAll('.asi-modal-tab');
      const tabContents = document.querySelectorAll('.asi-tab-content');

      function switchTab(targetTab) {
        // Update tabs
        tabs.forEach(t => {
          t.classList.remove('active');
          if (t.getAttribute('data-tab') === targetTab) {
            t.classList.add('active');
          }
        });

        // Update content
        tabContents.forEach(content => {
          content.classList.remove('active');
          if (content.id === targetTab + 'Tab') {
            content.classList.add('active');
          }
        });
      }

      window.switchTab = switchTab; // Global access for inline onclick

      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          switchTab(tab.getAttribute('data-tab'));
        });
      });

      // Auth Forms Helper
      async function handleAuthForm(formId, submitText, loadingText) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', async (e) => {
          e.preventDefault();
          const submitBtn = form.querySelector('button[type="submit"]');

          submitBtn.disabled = true;
          submitBtn.textContent = loadingText;

          try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
              },
              body: formData
            });

            const data = await response.json();

            if (response.ok && (data.success || data.redirect)) {
              window.location.href = data.redirect || '/dashboard';
            } else {
              if (data.errors) {
                const errorMsg = Object.values(data.errors).flat().join('\n');
                alert(errorMsg);
              } else {
                alert(data.message || 'İşlem sırasında bir hata oluştu.');
              }
            }
          } catch (err) {
            console.error(err);
            alert('Sunucuyla iletişim kurulamadı.');
          } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = submitText;
          }
        });
      }

      // Initialize Forms
      handleAuthForm('registerForm', 'Kayıt Ol', 'Kaydediliyor...');
      handleAuthForm('loginForm', 'Giriş Yap', 'Giriş Yapılıyor...');
    })();
  </script>
</body>

</html>