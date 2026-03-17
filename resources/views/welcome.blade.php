<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'NUSAAPP Backend') }}</title>
        <meta
            name="description"
            content="NUSAAPP Backend API untuk operasional garden management, attendance GPS, inventory, task management, payroll, dan invoice."
        >

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,700|dm-sans:400,500,700&display=swap" rel="stylesheet" />

        <style>
            :root {
                --bg: #f3efe6;
                --panel: rgba(255, 255, 255, 0.72);
                --panel-strong: rgba(255, 255, 255, 0.92);
                --text: #16322f;
                --muted: #4f6b66;
                --line: rgba(22, 50, 47, 0.12);
                --green: #0d7a5f;
                --green-deep: #0b4f42;
                --lime: #d3ea63;
                --sand: #f7c98b;
                --shadow: 0 24px 80px rgba(14, 44, 39, 0.16);
            }

            * {
                box-sizing: border-box;
            }

            html {
                scroll-behavior: smooth;
            }

            body {
                margin: 0;
                min-height: 100vh;
                font-family: 'DM Sans', sans-serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top left, rgba(211, 234, 99, 0.75), transparent 28%),
                    radial-gradient(circle at right 10% top 14%, rgba(247, 201, 139, 0.65), transparent 24%),
                    linear-gradient(180deg, #f7f1e5 0%, #efe8db 48%, #ece6da 100%);
            }

            a {
                color: inherit;
                text-decoration: none;
            }

            .page {
                position: relative;
                overflow: hidden;
            }

            .page::before,
            .page::after {
                content: "";
                position: absolute;
                border-radius: 999px;
                pointer-events: none;
                z-index: 0;
            }

            .page::before {
                width: 32rem;
                height: 32rem;
                top: -11rem;
                right: -7rem;
                background: rgba(13, 122, 95, 0.12);
                filter: blur(10px);
            }

            .page::after {
                width: 22rem;
                height: 22rem;
                left: -8rem;
                bottom: 18rem;
                background: rgba(211, 234, 99, 0.22);
                filter: blur(16px);
            }

            .container {
                position: relative;
                z-index: 1;
                width: min(1180px, calc(100% - 32px));
                margin: 0 auto;
            }

            .topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                padding: 24px 0 10px;
            }

            .brand {
                display: inline-flex;
                align-items: center;
                gap: 0.9rem;
            }

            .brand-mark {
                display: grid;
                place-items: center;
                width: 48px;
                height: 48px;
                border-radius: 16px;
                background: linear-gradient(135deg, var(--green-deep), var(--green));
                color: #f4f7ef;
                font-family: 'Space Grotesk', sans-serif;
                font-size: 1.05rem;
                font-weight: 700;
                box-shadow: 0 12px 28px rgba(11, 79, 66, 0.22);
            }

            .brand-copy strong,
            .hero-copy h1,
            .section-title,
            .card-title,
            .step-index,
            .stat strong {
                font-family: 'Space Grotesk', sans-serif;
            }

            .brand-copy {
                display: grid;
                gap: 0.2rem;
            }

            .brand-copy strong {
                font-size: 1rem;
                letter-spacing: 0.02em;
            }

            .brand-copy span,
            .eyebrow,
            .label,
            .mini {
                color: var(--muted);
                font-size: 0.92rem;
            }

            .top-links {
                display: flex;
                flex-wrap: wrap;
                justify-content: flex-end;
                gap: 0.75rem;
            }

            .pill-link,
            .pill {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.8rem 1rem;
                border-radius: 999px;
                border: 1px solid var(--line);
                background: rgba(255, 255, 255, 0.52);
                backdrop-filter: blur(14px);
            }

            .pill-link {
                transition: transform 180ms ease, border-color 180ms ease, background 180ms ease;
            }

            .pill-link:hover,
            .pill-link:focus-visible {
                transform: translateY(-2px);
                background: rgba(255, 255, 255, 0.86);
                border-color: rgba(13, 122, 95, 0.3);
                outline: none;
            }

            .hero {
                display: grid;
                grid-template-columns: minmax(0, 1.2fr) minmax(320px, 0.8fr);
                gap: 1.25rem;
                align-items: stretch;
                padding: 28px 0 20px;
            }

            .hero-card,
            .panel,
            .module-card,
            .step-card,
            .footer-card {
                background: var(--panel);
                border: 1px solid rgba(255, 255, 255, 0.62);
                box-shadow: var(--shadow);
                backdrop-filter: blur(16px);
            }

            .hero-card {
                padding: 2rem;
                border-radius: 32px;
                position: relative;
                overflow: hidden;
            }

            .hero-card::before {
                content: "";
                position: absolute;
                inset: auto -4rem -5rem auto;
                width: 16rem;
                height: 16rem;
                border-radius: 999px;
                background: radial-gradient(circle, rgba(211, 234, 99, 0.54), transparent 68%);
            }

            .eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 0.55rem;
                text-transform: uppercase;
                letter-spacing: 0.14em;
                font-size: 0.78rem;
                font-weight: 700;
                color: var(--green-deep);
            }

            .eyebrow::before {
                content: "";
                width: 0.75rem;
                height: 0.75rem;
                border-radius: 999px;
                background: linear-gradient(135deg, var(--lime), var(--green));
                box-shadow: 0 0 0 6px rgba(13, 122, 95, 0.08);
            }

            .hero-copy h1 {
                margin: 1rem 0 0;
                max-width: 11ch;
                font-size: clamp(2.8rem, 7vw, 5.5rem);
                line-height: 0.94;
                letter-spacing: -0.05em;
            }

            .hero-copy p {
                margin: 1.2rem 0 0;
                max-width: 58ch;
                font-size: 1.05rem;
                line-height: 1.7;
                color: var(--muted);
            }

            .hero-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 0.9rem;
                margin-top: 1.8rem;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.65rem;
                padding: 0.95rem 1.25rem;
                border-radius: 16px;
                font-weight: 700;
                transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
            }

            .button:hover,
            .button:focus-visible {
                transform: translateY(-2px);
                outline: none;
            }

            .button-primary {
                color: #eff8ef;
                background: linear-gradient(135deg, var(--green-deep), var(--green));
                box-shadow: 0 16px 28px rgba(11, 79, 66, 0.18);
            }

            .button-secondary {
                border: 1px solid var(--line);
                background: rgba(255, 255, 255, 0.75);
            }

            .hero-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                margin-top: 1.5rem;
            }

            .hero-side {
                display: grid;
                gap: 1rem;
            }

            .panel {
                border-radius: 28px;
                padding: 1.35rem;
            }

            .panel-strong {
                background: linear-gradient(180deg, rgba(16, 82, 69, 0.96), rgba(10, 56, 47, 0.97));
                color: #eff7ec;
                border: 0;
            }

            .panel-strong .label,
            .panel-strong .mini,
            .panel-strong .endpoint-note {
                color: rgba(239, 247, 236, 0.74);
            }

            .stack {
                display: grid;
                gap: 0.85rem;
            }

            .endpoint-box {
                display: grid;
                gap: 0.5rem;
                padding: 1rem;
                border-radius: 20px;
                background: rgba(255, 255, 255, 0.12);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .endpoint {
                display: inline-flex;
                flex-wrap: wrap;
                gap: 0.65rem;
                align-items: center;
                font-family: 'Space Grotesk', sans-serif;
                font-size: 1.05rem;
            }

            .method {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 62px;
                padding: 0.35rem 0.6rem;
                border-radius: 999px;
                background: rgba(211, 234, 99, 0.16);
                color: var(--lime);
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
            }

            .response {
                margin-top: 0.7rem;
                padding: 1rem;
                border-radius: 22px;
                background: rgba(7, 34, 29, 0.54);
                overflow-x: auto;
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace;
                font-size: 0.86rem;
                line-height: 1.6;
            }

            .section {
                padding: 18px 0;
            }

            .section-header {
                display: flex;
                align-items: end;
                justify-content: space-between;
                gap: 1rem;
                margin-bottom: 1.25rem;
            }

            .section-title {
                margin: 0;
                font-size: clamp(1.8rem, 3vw, 2.8rem);
                letter-spacing: -0.04em;
            }

            .section-copy {
                max-width: 54ch;
                color: var(--muted);
                line-height: 1.75;
            }

            .stats {
                display: grid;
                grid-template-columns: repeat(4, minmax(0, 1fr));
                gap: 1rem;
                margin-top: 1rem;
            }

            .stat {
                padding: 1.2rem;
                border-radius: 24px;
                background: var(--panel-strong);
                border: 1px solid rgba(255, 255, 255, 0.7);
                box-shadow: 0 18px 44px rgba(18, 47, 43, 0.09);
            }

            .stat strong {
                display: block;
                font-size: 2rem;
                line-height: 1;
                margin-bottom: 0.4rem;
            }

            .grid-2,
            .modules,
            .steps,
            .footer-grid {
                display: grid;
                gap: 1rem;
            }

            .grid-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .modules {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .module-card,
            .step-card,
            .footer-card {
                border-radius: 26px;
                padding: 1.35rem;
            }

            .module-card {
                min-height: 220px;
                display: grid;
                align-content: space-between;
                gap: 1rem;
            }

            .module-card:nth-child(1) {
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.9), rgba(239, 250, 235, 0.82));
            }

            .module-card:nth-child(2) {
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.88), rgba(255, 245, 226, 0.86));
            }

            .module-card:nth-child(3) {
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.88), rgba(228, 244, 240, 0.84));
            }

            .module-card:nth-child(4) {
                background: linear-gradient(180deg, rgba(255, 255, 255, 0.9), rgba(244, 236, 224, 0.88));
            }

            .badge {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 42px;
                height: 42px;
                border-radius: 14px;
                background: rgba(13, 122, 95, 0.1);
                color: var(--green-deep);
                font-weight: 700;
            }

            .card-title {
                margin: 0;
                font-size: 1.22rem;
            }

            .card-copy,
            .list,
            .step-card p,
            .footer-card p {
                color: var(--muted);
                line-height: 1.7;
            }

            .list {
                margin: 0;
                padding-left: 1.1rem;
            }

            .list li + li {
                margin-top: 0.45rem;
            }

            .steps {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .step-index {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 2.4rem;
                height: 2.4rem;
                border-radius: 999px;
                background: linear-gradient(135deg, var(--lime), #f4f8cf);
                color: var(--green-deep);
                font-weight: 700;
            }

            .code {
                margin-top: 0.85rem;
                padding: 1rem;
                border-radius: 18px;
                background: rgba(10, 56, 47, 0.06);
                border: 1px solid rgba(22, 50, 47, 0.08);
                overflow-x: auto;
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace;
                font-size: 0.85rem;
                line-height: 1.65;
                color: #24443f;
            }

            .footer-grid {
                grid-template-columns: 1.15fr 0.85fr;
                padding-bottom: 32px;
            }

            .footer-card {
                background: var(--panel-strong);
            }

            .footer-note {
                padding: 0 0 36px;
                text-align: center;
                color: var(--muted);
                font-size: 0.92rem;
            }

            .footer-note strong {
                color: var(--green-deep);
            }

            @media (max-width: 1080px) {
                .hero,
                .footer-grid,
                .modules,
                .steps,
                .grid-2,
                .stats {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 760px) {
                .container {
                    width: min(100% - 20px, 1180px);
                }

                .topbar,
                .section-header {
                    align-items: start;
                    flex-direction: column;
                }

                .top-links {
                    justify-content: flex-start;
                }

                .hero,
                .footer-grid,
                .modules,
                .steps,
                .grid-2,
                .stats {
                    grid-template-columns: 1fr;
                }

                .hero-card,
                .panel,
                .module-card,
                .step-card,
                .footer-card {
                    border-radius: 24px;
                }

                .hero-copy h1 {
                    max-width: none;
                }

                .button,
                .pill-link,
                .pill {
                    width: 100%;
                    justify-content: center;
                }
            }
        </style>
    </head>
    <body>
        <div class="page">
            <div class="container">
                <header class="topbar">
                    <div class="brand">
                        <div class="brand-mark">NA</div>
                        <div class="brand-copy">
                            <strong>NUSAAPP Backend</strong>
                            <span>Garden management API platform</span>
                        </div>
                    </div>

                    <nav class="top-links" aria-label="Quick links">
                        <a class="pill-link" href="#getting-started">Base API</a>
                        <a class="pill-link" href="#modules">Modules</a>
                        <a class="pill-link" href="#documentation">Documentation</a>
                        <a class="pill-link" href="#getting-started">Getting Started</a>
                    </nav>
                </header>

                <section class="hero">
                    <article class="hero-card">
                        <div class="hero-copy">
                            <span class="eyebrow">REST API · Laravel 11 · Sanctum</span>
                            <h1>Operasional taman, tenaga kerja, dan finance dalam satu backend.</h1>
                            <p>
                                NUSAAPP menggabungkan autentikasi berbasis token, attendance validasi GPS,
                                task management, inventory per site, payroll, invoice, dan transaksi dalam satu
                                API yang konsisten untuk kebutuhan aplikasi operasional lapangan.
                            </p>

                            <div class="hero-actions">
                                <a class="button button-primary" href="#getting-started">Lihat Base API</a>
                                <a class="button button-secondary" href="#getting-started">Lihat contoh request</a>
                            </div>

                            <div class="hero-meta">
                                <span class="pill">37 tables</span>
                                <span class="pill">55 permissions</span>
                                <span class="pill">UUID first</span>
                                <span class="pill">GPS geofencing</span>
                            </div>
                        </div>
                    </article>

                    <aside class="hero-side">
                        <section class="panel panel-strong">
                            <div class="stack">
                                <div>
                                    <div class="label">Primary endpoint</div>
                                    <div class="endpoint-box">
                                        <div class="endpoint">
                                            <span class="method">POST</span>
                                            <span>{{ url('/api/auth/login') }}</span>
                                        </div>
                                        <div class="endpoint-note">Gunakan Bearer token untuk semua endpoint terproteksi.</div>
                                    </div>
                                </div>

                                <div>
                                    <div class="label">Standard response</div>
                                    <pre class="response">{
  "success": true,
  "message": "Success",
  "data": { ... }
}</pre>
                                </div>
                            </div>
                        </section>

                        <section class="panel">
                            <div class="label">Platform highlights</div>
                            <div class="stack" style="margin-top: 0.9rem;">
                                <div>
                                    <strong>Attendance dengan validasi lokasi</strong>
                                    <div class="mini">Clock in/out diverifikasi dengan koordinat dan radius site.</div>
                                </div>
                                <div>
                                    <strong>Operasional per site</strong>
                                    <div class="mini">Task, area, site inventory, dan log pekerjaan dikelola terpusat.</div>
                                </div>
                                <div>
                                    <strong>RBAC granular</strong>
                                    <div class="mini">Role dan permission siap untuk Super Admin sampai Staff lapangan.</div>
                                </div>
                            </div>
                        </section>
                    </aside>
                </section>

                <section class="section" aria-labelledby="overview-title">
                    <div class="section-header">
                        <div>
                            <p class="eyebrow">System snapshot</p>
                            <h2 class="section-title" id="overview-title">Ringkasan kemampuan inti</h2>
                        </div>
                        <p class="section-copy">
                            Halaman ini ditujukan sebagai pintu masuk cepat untuk developer, QA, atau integrator
                            yang perlu memahami cakupan backend tanpa membuka template default Laravel.
                        </p>
                    </div>

                    <div class="stats">
                        <div class="stat">
                            <strong>5</strong>
                            <span>Role utama sistem</span>
                        </div>
                        <div class="stat">
                            <strong>20+</strong>
                            <span>Kelompok resource API</span>
                        </div>
                        <div class="stat">
                            <strong>8:00</strong>
                            <span>Batas status hadir default</span>
                        </div>
                        <div class="stat">
                            <strong>v1.0</strong>
                            <span>Versi API saat ini</span>
                        </div>
                    </div>
                </section>

                <section class="section" id="modules" aria-labelledby="modules-title">
                    <div class="section-header">
                        <div>
                            <p class="eyebrow">Core modules</p>
                            <h2 class="section-title" id="modules-title">Area backend yang sudah dicakup</h2>
                        </div>
                        <p class="section-copy">
                            Cakupan modul dibuat untuk operasi harian garden management, mulai dari user access sampai invoicing.
                        </p>
                    </div>

                    <div class="modules">
                        <article class="module-card">
                            <div class="badge">01</div>
                            <div>
                                <h3 class="card-title">Auth & Access</h3>
                                <p class="card-copy">Login, register, profile, role assignment, dan permission-based authorization dengan Sanctum.</p>
                            </div>
                            <ul class="list">
                                <li>Bearer token authentication</li>
                                <li>Spatie permission integration</li>
                                <li>Role matrix untuk 5 level user</li>
                            </ul>
                        </article>

                        <article class="module-card">
                            <div class="badge">02</div>
                            <div>
                                <h3 class="card-title">HR & Workforce</h3>
                                <p class="card-copy">Pengelolaan employee, kontrak kerja, leave request, status karyawan, dan payroll.</p>
                            </div>
                            <ul class="list">
                                <li>Employee lifecycle tracking</li>
                                <li>Approval flow cuti</li>
                                <li>Payroll terhubung data operasional</li>
                            </ul>
                        </article>

                        <article class="module-card">
                            <div class="badge">03</div>
                            <div>
                                <h3 class="card-title">Site Operations</h3>
                                <p class="card-copy">Client, kontrak, site, area, task, task log, dan attendance berbasis GPS untuk tim lapangan.</p>
                            </div>
                            <ul class="list">
                                <li>Task priority dan recurrence</li>
                                <li>Photo-enabled activity logs</li>
                                <li>Geofence validation per site</li>
                            </ul>
                        </article>

                        <article class="module-card">
                            <div class="badge">04</div>
                            <div>
                                <h3 class="card-title">Inventory & Finance</h3>
                                <p class="card-copy">Master item inventory, stok per site, invoice plan, invoice, attachment, dan transaction tracking.</p>
                            </div>
                            <ul class="list">
                                <li>Low stock alert threshold</li>
                                <li>Invoice value tracking</li>
                                <li>Transaction visibility per proses</li>
                            </ul>
                        </article>
                    </div>
                </section>

                <section class="section" id="getting-started" aria-labelledby="getting-started-title">
                    <div class="section-header">
                        <div>
                            <p class="eyebrow">Getting started</p>
                            <h2 class="section-title" id="getting-started-title">Alur integrasi tercepat</h2>
                        </div>
                        <p class="section-copy">
                            Developer baru bisa mulai dari autentikasi, lalu gunakan token untuk resource lain seperti users, employees, sites, tasks, dan attendance.
                        </p>
                    </div>

                    <div class="steps">
                        <article class="step-card">
                            <span class="step-index">1</span>
                            <h3 class="card-title">Login</h3>
                            <p>Autentikasi terlebih dahulu untuk mendapatkan token. Endpoint berikut adalah entry point utama integrasi.</p>
                            <pre class="code">POST {{ url('/api/auth/login') }}
Content-Type: application/json
Accept: application/json</pre>
                        </article>

                        <article class="step-card">
                            <span class="step-index">2</span>
                            <h3 class="card-title">Gunakan Bearer token</h3>
                            <p>Sertakan token pada header request untuk semua endpoint yang membutuhkan autentikasi dan otorisasi.</p>
                            <pre class="code">Authorization: Bearer {token}
Accept: application/json</pre>
                        </article>

                        <article class="step-card">
                            <span class="step-index">3</span>
                            <h3 class="card-title">Akses resource operasional</h3>
                            <p>Setelah login, lanjutkan ke modul seperti users, employees, tasks, attendance, site inventories, invoices, dan transactions.</p>
                            <pre class="code">GET {{ url('/api/users') }}
GET {{ url('/api/tasks') }}
GET {{ url('/api/attendance') }}</pre>
                        </article>
                    </div>
                </section>

                <section class="section" aria-labelledby="conventions-title">
                    <div class="section-header">
                        <div>
                            <p class="eyebrow">API conventions</p>
                            <h2 class="section-title" id="conventions-title">Standar yang perlu diperhatikan</h2>
                        </div>
                    </div>

                    <div class="grid-2">
                        <article class="footer-card">
                            <h3 class="card-title">Format respons</h3>
                            <p>Seluruh endpoint mengembalikan struktur yang konsisten dengan field <strong>success</strong>, <strong>message</strong>, dan <strong>data</strong> atau <strong>errors</strong>.</p>
                            <pre class="code">{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["The email field is required."]
  }
}</pre>
                        </article>

                        <article class="footer-card">
                            <h3 class="card-title">Catatan implementasi</h3>
                            <p>Relasi utama menggunakan UUID, banyak tabel mendukung soft deletes, dan attendance memakai validasi koordinat terhadap site radius dengan pendekatan geofence.</p>
                            <div class="hero-meta">
                                <span class="pill">Laravel 11</span>
                                <span class="pill">PHP 8.3</span>
                                <span class="pill">MySQL 8+</span>
                                <span class="pill">Sanctum</span>
                            </div>
                        </article>
                    </div>
                </section>

                <section class="section" id="documentation" aria-labelledby="documentation-title">
                    <div class="section-header">
                        <div>
                            <p class="eyebrow">Documentation access</p>
                            <h2 class="section-title" id="documentation-title">Dokumentasi yang terhubung ke route web</h2>
                        </div>
                        <p class="section-copy">
                            Dokumentasi tidak lagi hanya berupa file repository. Sekarang tersedia route web untuk membaca API docs langsung dari browser dan membuka koleksi Postman resmi proyek.
                        </p>
                    </div>

                    <div class="grid-2">
                        <article class="footer-card">
                            <h3 class="card-title">API Documentation</h3>
                            <p>Halaman dokumentasi API dirender dari file markdown proyek dan dapat diakses lewat browser untuk kebutuhan developer, QA, atau integrasi cepat.</p>
                            <div class="hero-actions">
                                <a class="button button-primary" href="{{ route('docs.api') }}">Buka /docs/api</a>
                            </div>
                            <pre class="code">GET {{ url('/docs/api') }}</pre>
                        </article>

                        <article class="footer-card">
                            <h3 class="card-title">Postman Collection</h3>
                            <p>Koleksi Postman tersedia melalui route web untuk dibuka langsung sebagai JSON atau diunduh sebagai file koleksi resmi NUSAAPP.</p>
                            <div class="hero-actions">
                                <a class="button button-primary" href="{{ route('docs.postman') }}">Buka JSON</a>
                                <a class="button button-secondary" href="{{ route('docs.postman.download') }}">Download Collection</a>
                            </div>
                            <pre class="code">GET {{ url('/docs/postman') }}
GET {{ url('/docs/postman/download') }}</pre>
                        </article>
                    </div>
                </section>

                <section class="section">
                    <div class="footer-grid">
                        <article class="footer-card">
                            <h3 class="card-title">Untuk tim developer</h3>
                            <p>
                                Jika Anda menjalankan proyek ini secara lokal, base URL API default adalah
                                <strong>{{ url('/api') }}</strong>. Halaman ini hanya sebagai overview cepat; dokumentasi endpoint detail tetap mengikuti file dokumentasi proyek dan koleksi Postman yang ada di repository.
                            </p>
                        </article>

                        <article class="footer-card">
                            <h3 class="card-title">Status runtime</h3>
                            <p>Aplikasi web aktif dan view root berhasil dirender oleh Laravel melalui route <strong>/</strong>.</p>
                            <div class="hero-meta">
                                <span class="pill">App: {{ config('app.name', 'NUSAAPP Backend') }}</span>
                                <span class="pill">Laravel {{ Illuminate\Foundation\Application::VERSION }}</span>
                                <span class="pill">PHP {{ PHP_VERSION }}</span>
                            </div>
                        </article>
                    </div>
                </section>

                <p class="footer-note">
                    <strong>NUSAAPP Backend</strong> · Garden Management REST API · built on Laravel
                </p>
            </div>
        </div>
    </body>
</html>
