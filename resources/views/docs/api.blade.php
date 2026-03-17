<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>API Documentation | {{ config('app.name', 'NUSAAPP Backend') }}</title>

        <style>
            :root {
                --bg: #f4efe5;
                --paper: #fffdf8;
                --panel: rgba(255, 255, 255, 0.82);
                --text: #173430;
                --muted: #5b746f;
                --line: rgba(23, 52, 48, 0.12);
                --accent: #0c7c61;
                --accent-deep: #0a5848;
                --accent-soft: #dbef8f;
                --code: #112622;
                --shadow: 0 28px 80px rgba(20, 48, 43, 0.12);
            }

            * {
                box-sizing: border-box;
            }

            html {
                scroll-behavior: smooth;
            }

            body {
                margin: 0;
                font-family: Georgia, 'Times New Roman', serif;
                color: var(--text);
                background:
                    radial-gradient(circle at top right, rgba(219, 239, 143, 0.45), transparent 24%),
                    linear-gradient(180deg, #f8f3ea 0%, #f0e8db 100%);
            }

            a {
                color: var(--accent-deep);
            }

            .shell {
                width: min(1240px, calc(100% - 24px));
                margin: 0 auto;
                padding: 24px 0 40px;
            }

            .topbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                margin-bottom: 20px;
            }

            .brand {
                display: grid;
                gap: 0.3rem;
            }

            .brand strong {
                font-size: 1.1rem;
                letter-spacing: 0.02em;
            }

            .brand span,
            .meta,
            .aside p,
            .aside li {
                color: var(--muted);
                font-size: 0.95rem;
                line-height: 1.7;
            }

            .actions {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0.9rem 1.15rem;
                border-radius: 999px;
                border: 1px solid var(--line);
                background: rgba(255, 255, 255, 0.7);
                color: var(--text);
                text-decoration: none;
                transition: transform 180ms ease, background 180ms ease;
            }

            .button:hover,
            .button:focus-visible {
                transform: translateY(-2px);
                background: white;
                outline: none;
            }

            .button-primary {
                background: linear-gradient(135deg, var(--accent-deep), var(--accent));
                color: #f5fbf7;
                border-color: transparent;
            }

            .layout {
                display: grid;
                grid-template-columns: minmax(0, 300px) minmax(0, 1fr);
                gap: 1rem;
                align-items: stretch;
            }

            .aside,
            .document {
                background: var(--panel);
                border: 1px solid rgba(255, 255, 255, 0.7);
                box-shadow: var(--shadow);
                backdrop-filter: blur(16px);
            }

            .aside {
                align-self: start;
                position: sticky;
                top: 20px;
                height: calc(100vh - 40px);
                padding: 1.2rem;
                border-radius: 28px;
                display: flex;
                flex-direction: column;
                gap: 0.8rem;
            }

            .aside h2,
            .document h1,
            .document h2,
            .document h3,
            .document h4 {
                font-family: Arial, sans-serif;
            }

            .aside ul {
                padding-left: 1.1rem;
                margin: 0.75rem 0 0;
            }

            .document-intro {
                margin-bottom: 1.5rem;
                padding: 1.15rem 1.2rem;
                border: 1px solid var(--line);
                border-radius: 22px;
                background: rgba(12, 124, 97, 0.04);
            }

            .document-intro h2 {
                margin: 0 0 0.6rem;
                padding: 0;
                border: 0;
                font-size: 1.15rem;
            }

            .document-intro p,
            .document-intro li {
                margin: 0;
                color: var(--muted);
            }

            .document-intro ul {
                margin: 0.9rem 0 0;
                padding-left: 1.1rem;
            }

            .document-intro li + li {
                margin-top: 0.35rem;
            }

            .toc {
                display: grid;
                gap: 0.35rem;
                margin-top: 1rem;
                padding-right: 0.2rem;
                overflow-y: auto;
                min-height: 0;
                flex: 1 1 auto;
            }

            .toc-link {
                display: block;
                padding: 0.45rem 0.65rem;
                border-radius: 12px;
                color: #2b4a44;
                text-decoration: none;
                line-height: 1.45;
                transition: background 160ms ease, transform 160ms ease;
            }

            .toc-link:hover,
            .toc-link:focus-visible {
                background: rgba(12, 124, 97, 0.08);
                transform: translateX(2px);
                outline: none;
            }

            .toc-link.is-active {
                background: linear-gradient(135deg, rgba(12, 124, 97, 0.14), rgba(219, 239, 143, 0.28));
                color: #0a5848;
                font-weight: 700;
                box-shadow: inset 3px 0 0 var(--accent);
            }

            .aside::-webkit-scrollbar {
                width: 10px;
            }

            .aside::-webkit-scrollbar-track {
                background: rgba(12, 124, 97, 0.05);
                border-radius: 999px;
            }

            .aside::-webkit-scrollbar-thumb {
                background: rgba(12, 124, 97, 0.24);
                border-radius: 999px;
            }

            .toc-link.level-1 {
                font-weight: 700;
            }

            .toc-link.level-2 {
                padding-left: 0.9rem;
            }

            .toc-link.level-3 {
                padding-left: 1.35rem;
                font-size: 0.92rem;
                color: var(--muted);
            }

            .toc-empty {
                margin-top: 1rem;
                color: var(--muted);
                flex: 1 1 auto;
            }

            .document {
                padding: 2rem;
                border-radius: 34px;
                background: var(--paper);
            }

            .document > :first-child {
                margin-top: 0;
            }

            .document h1,
            .document h2,
            .document h3,
            .document h4 {
                color: #102d29;
                line-height: 1.2;
                margin: 1.7em 0 0.6em;
            }

            .document h1 {
                font-size: clamp(2rem, 4vw, 3rem);
                margin-top: 0;
            }

            .document h2 {
                font-size: 1.7rem;
                border-bottom: 1px solid var(--line);
                padding-bottom: 0.4rem;
                scroll-margin-top: 24px;
            }

            .document h1,
            .document h3,
            .document h4 {
                scroll-margin-top: 24px;
            }

            .document p,
            .document li {
                font-size: 1rem;
                line-height: 1.8;
                color: #2e4b46;
            }

            .document ul,
            .document ol {
                padding-left: 1.3rem;
            }

            .document blockquote {
                margin: 1.25rem 0;
                padding: 0.9rem 1rem;
                border-left: 4px solid var(--accent);
                background: rgba(12, 124, 97, 0.05);
                color: #294641;
            }

            .document pre {
                overflow-x: auto;
                padding: 1rem 1.1rem;
                border-radius: 20px;
                background: var(--code);
                color: #eef5f2;
                font-size: 0.9rem;
                line-height: 1.7;
            }

            .document code {
                font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace;
                font-size: 0.92em;
            }

            .document :not(pre) > code {
                padding: 0.15rem 0.4rem;
                border-radius: 8px;
                background: rgba(12, 124, 97, 0.08);
                color: #0e5344;
            }

            .document table {
                width: 100%;
                border-collapse: collapse;
                margin: 1.2rem 0;
            }

            .document th,
            .document td {
                padding: 0.75rem;
                border: 1px solid var(--line);
                text-align: left;
                vertical-align: top;
            }

            .document hr {
                border: 0;
                border-top: 1px solid var(--line);
                margin: 2rem 0;
            }

            .back-to-top {
                position: fixed;
                right: 24px;
                bottom: 24px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 52px;
                height: 52px;
                border: 0;
                border-radius: 999px;
                background: linear-gradient(135deg, var(--accent-deep), var(--accent));
                color: #f5fbf7;
                box-shadow: 0 18px 38px rgba(10, 88, 72, 0.24);
                cursor: pointer;
                opacity: 0;
                visibility: hidden;
                transform: translateY(10px);
                transition: opacity 180ms ease, transform 180ms ease, visibility 180ms ease;
                z-index: 30;
            }

            .back-to-top.is-visible {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .back-to-top:hover,
            .back-to-top:focus-visible {
                transform: translateY(-2px);
                outline: none;
            }

            @media (max-width: 900px) {
                .layout {
                    grid-template-columns: 1fr;
                }

                .aside {
                    position: static;
                    height: auto;
                }
            }

            @media (max-width: 640px) {
                .topbar {
                    flex-direction: column;
                    align-items: start;
                }

                .actions,
                .button {
                    width: 100%;
                }

                .document,
                .aside {
                    border-radius: 24px;
                }

                .back-to-top {
                    right: 16px;
                    bottom: 16px;
                }
            }
        </style>
    </head>
    <body>
        <div class="shell">
            <header class="topbar">
                <div class="brand">
                    <strong>API Documentation</strong>
                    <span>{{ config('app.name', 'NUSAAPP Backend') }} · rendered from API_DOCUMENTATION.md</span>
                </div>

                <div class="actions">
                    <a class="button" href="{{ route('docs.postman') }}">Buka Postman JSON</a>
                    <a class="button" href="{{ route('docs.postman.download') }}">Download Postman</a>
                    <a class="button button-primary" href="{{ url('/') }}">Kembali ke Welcome</a>
                </div>
            </header>

            <div class="layout">
                <aside class="aside">
                    <h2>Table of Contents</h2>

                    @if (! empty($toc))
                        <nav class="toc" aria-label="Table of contents">
                            @foreach ($toc as $item)
                                <a class="toc-link level-{{ $item['level'] }}" href="#{{ $item['id'] }}">
                                    {{ $item['text'] }}
                                </a>
                            @endforeach
                        </nav>
                    @else
                        <p class="toc-empty">Heading tidak ditemukan di markdown.</p>
                    @endif
                </aside>

                <main class="document">
                    <section class="document-intro" aria-label="Document info">
                        <h2>Dokumen ini</h2>
                        <p>Halaman ini dirender langsung dari file markdown project supaya dokumentasi bisa diakses lewat route web tanpa membuka file repository secara manual.</p>
                        <p class="meta" style="margin-top: 0.75rem;">Last rendered: {{ $lastUpdated }}</p>

                        <ul>
                            <li>Source: API_DOCUMENTATION.md</li>
                            <li>Route docs: /docs/api</li>
                            <li>Route Postman: /docs/postman</li>
                            <li>Download Postman: /docs/postman/download</li>
                        </ul>
                    </section>

                    {!! $content !!}
                </main>
            </div>
        </div>

        <button class="back-to-top" id="back-to-top" type="button" aria-label="Back to top">
            ↑
        </button>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tocLinks = Array.from(document.querySelectorAll('.toc-link'));
                const backToTopButton = document.getElementById('back-to-top');

                const updateBackToTopVisibility = () => {
                    if (! backToTopButton) {
                        return;
                    }

                    backToTopButton.classList.toggle('is-visible', window.scrollY > 320);
                };

                backToTopButton?.addEventListener('click', () => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });

                updateBackToTopVisibility();
                window.addEventListener('scroll', updateBackToTopVisibility, { passive: true });

                if (tocLinks.length === 0) {
                    return;
                }

                const sections = tocLinks
                    .map((link) => {
                        const targetId = link.getAttribute('href')?.slice(1);

                        if (! targetId) {
                            return null;
                        }

                        const section = document.getElementById(targetId);

                        if (! section) {
                            return null;
                        }

                        return { link, section };
                    })
                    .filter(Boolean);

                if (sections.length === 0) {
                    return;
                }

                const setActiveLink = (activeId) => {
                    sections.forEach(({ link, section }) => {
                        const isActive = section.id === activeId;

                        link.classList.toggle('is-active', isActive);

                        if (isActive) {
                            link.setAttribute('aria-current', 'location');
                        } else {
                            link.removeAttribute('aria-current');
                        }
                    });

                    const activeLink = sections.find(({ section }) => section.id === activeId)?.link;

                    activeLink?.scrollIntoView({
                        block: 'nearest',
                        inline: 'nearest',
                    });
                };

                const findClosestSection = () => {
                    const offset = window.innerHeight * 0.22;
                    let activeSection = sections[0].section;

                    sections.forEach(({ section }) => {
                        if (section.getBoundingClientRect().top - offset <= 0) {
                            activeSection = section;
                        }
                    });

                    setActiveLink(activeSection.id);
                };

                const observer = new IntersectionObserver(
                    (entries) => {
                        const visibleEntries = entries
                            .filter((entry) => entry.isIntersecting)
                            .sort((left, right) => left.boundingClientRect.top - right.boundingClientRect.top);

                        if (visibleEntries.length > 0) {
                            setActiveLink(visibleEntries[0].target.id);
                            return;
                        }

                        findClosestSection();
                    },
                    {
                        rootMargin: '-18% 0px -62% 0px',
                        threshold: [0, 0.1, 0.25, 0.5],
                    }
                );

                sections.forEach(({ section }) => observer.observe(section));

                tocLinks.forEach((link) => {
                    link.addEventListener('click', () => {
                        const targetId = link.getAttribute('href')?.slice(1);

                        if (targetId) {
                            setActiveLink(targetId);
                        }
                    });
                });

                findClosestSection();
                window.addEventListener('resize', findClosestSection, { passive: true });
            });
        </script>
    </body>
</html>
