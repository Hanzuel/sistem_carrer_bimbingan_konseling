<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F6F7F9">
    <title>@yield('title', 'Career Quest') — Bimbingan Karier</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,600;12..96,700&family=Geist:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        /* Design tokens: ubah warna / font di sini saja */
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Geist', 'ui-sans-serif', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
                        display: ['"Bricolage Grotesque"', 'Geist', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        paper: '#F6F7F9',
                        quest: {
                            50:  '#EFF4FF', 100: '#DDE7FF', 200: '#BCCFFF', 300: '#9BB7FF',
                            400: '#6D94FF', 500: '#3F68FF', 600: '#2A4FEF', 700: '#203CC4',
                            800: '#1A2F99', 900: '#17277D',
                        },
                        sun: {
                            50:  '#FFFBEB', 100: '#FEF3C7', 200: '#FDE68A', 300: '#FCD34D',
                            400: '#FBBF24', 500: '#F59E0B', 600: '#D97706', 700: '#B45309',
                        },
                        sage: {
                            50:  '#F0FDFA', 100: '#CCFBF1', 200: '#99F6E4', 300: '#5EEAD4',
                            400: '#2DD4BF', 500: '#14B8A6', 600: '#0D9488', 700: '#0F766E',
                        },
                    },
                    keyframes: {
                        fill:  { from: { transform: 'scaleX(0)' }, to: { transform: 'scaleX(1)' } },
                        'pulse-soft': {
                            '0%, 100%': { opacity: '1' },
                            '50%':      { opacity: '0.5' },
                        },
                        'dash-flow': {
                            from: { strokeDashoffset: '0' },
                            to:   { strokeDashoffset: '-60' },
                        },
                    },
                    animation: {
                        fill:         'fill .5s cubic-bezier(.4,0,.2,1) .15s both',
                        'pulse-soft': 'pulse-soft 3s ease-in-out infinite',
                        'dash-flow':  'dash-flow 6s linear infinite',
                    },
                },
            },
        };
    </script>

    <style type="text/tailwindcss">
        @layer base {
            html { -webkit-text-size-adjust: 100%; }
            @media (prefers-reduced-motion: no-preference) {
                html { scroll-behavior: smooth; }
            }
            h1, h2, h3, h4 {
                font-family: 'Bricolage Grotesque', 'Geist', ui-sans-serif, system-ui, sans-serif;
                letter-spacing: -0.03em;
                text-wrap: balance;
            }
            p { text-wrap: pretty; }
            ::selection { background: #DDE7FF; color: #09090B; }
            :focus-visible { outline: 2px solid #2A4FEF; outline-offset: 2px; border-radius: 6px; }
        }

        @layer components {
            .card {
                @apply rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm shadow-zinc-900/[0.03] sm:p-6;
            }
            .btn-primary {
                @apply inline-flex items-center justify-center gap-2 rounded-lg bg-zinc-950 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:-translate-y-px hover:bg-zinc-800 hover:shadow-md disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0 disabled:hover:shadow-sm;
            }
            .btn-accent {
                @apply inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-quest-500 via-quest-600 to-quest-700 px-4 py-2.5 text-sm font-medium text-white shadow-sm shadow-quest-600/25 transition hover:-translate-y-px hover:shadow-md hover:shadow-quest-600/35 disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0 disabled:hover:shadow-sm;
            }
            .btn-secondary {
                @apply inline-flex items-center justify-center gap-2 rounded-lg border border-zinc-300 bg-white px-4 py-2.5 text-sm font-medium text-zinc-800 transition hover:-translate-y-px hover:border-zinc-400 hover:bg-zinc-50 hover:shadow-sm disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:translate-y-0 disabled:hover:shadow-none;
            }
            .field {
                @apply block w-full rounded-lg border border-zinc-300 bg-white px-3.5 py-2.5 text-sm text-zinc-950 transition placeholder:text-zinc-400 hover:border-zinc-400 focus:border-quest-600 focus:outline-none focus:ring-2 focus:ring-quest-600/20;
            }
        }

        @layer utilities {
            .bg-grain {
                background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-delay: 0ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    @stack('head')
</head>
<body class="flex min-h-screen flex-col bg-paper font-sans text-zinc-900 antialiased">

    {{-- Ikon logo (dipakai di header dan footer) --}}
    <svg width="0" height="0" class="absolute" aria-hidden="true" focusable="false">
        <symbol id="cq-mark" viewBox="0 0 32 32">
            <rect width="32" height="32" rx="8" fill="currentColor"/>
            <path d="M8 22.5 14 15.5l4 3L24 10.5" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="24" cy="10.5" r="3.2" fill="#3F68FF"/>
        </symbol>
    </svg>

    <a href="#konten"
       class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-zinc-950 focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-white">
        Lewati ke konten
    </a>

    {{-- ====================================================================== --}}
    {{-- LATAR GEOMETRIS — semua animasi digerakkan oleh Motion (vanilla)      --}}
    {{-- ====================================================================== --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">

        {{-- Wash gradient: biru di atas, amber tipis di bawah --}}
        <div class="absolute inset-x-0 top-0 h-[42rem] bg-gradient-to-b from-quest-50 via-quest-50/40 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 h-[28rem] bg-gradient-to-t from-sun-50/50 to-transparent"></div>

        {{-- Orb blur besar — drift di Motion --}}
        <div class="absolute -left-40 top-20 h-[32rem] w-[32rem] rounded-full bg-quest-200/35 blur-3xl" data-motion="drift" data-delay="0"></div>
        <div class="absolute -right-44 top-[18%] h-[34rem] w-[34rem] rounded-full bg-sun-200/25 blur-3xl" data-motion="drift-slow" data-delay="2"></div>
        <div class="absolute -bottom-40 left-1/3 h-[28rem] w-[28rem] rounded-full bg-sage-200/25 blur-3xl" data-motion="drift" data-delay="4"></div>

        {{-- Orb tengah --}}
        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
            <div class="h-[40rem] w-[40rem] rounded-full bg-quest-100/30 blur-3xl" data-motion="breathe" data-delay="1"></div>
        </div>

        {{-- Grid titik --}}
        <div class="absolute inset-0 opacity-40 [background-image:radial-gradient(#d4d4d8_1px,transparent_1px)] [background-size:26px_26px] [mask-image:radial-gradient(ellipse_at_center,black_15%,transparent_65%)]"
             data-parallax="30"></div>

        {{-- Grid diagonal --}}
        <div class="absolute inset-0 opacity-[0.035] [background-image:linear-gradient(45deg,#18181b_1px,transparent_1px),linear-gradient(-45deg,#18181b_1px,transparent_1px)] [background-size:90px_90px]"></div>

        {{-- Kurva dekoratif --}}
        <svg class="absolute inset-0 h-full w-full opacity-[0.09]" viewBox="0 0 1000 1000" preserveAspectRatio="none" data-parallax="60">
            <path d="M-100,620 Q220,420 500,560 T1100,500"
                  fill="none" stroke="#2A4FEF" stroke-width="1.4" stroke-dasharray="5 9" class="animate-dash-flow"/>
            <path d="M-100,720 Q300,520 620,660 T1100,600"
                  fill="none" stroke="#F59E0B" stroke-width="1.1" stroke-dasharray="3 8" class="animate-dash-flow"/>
            <path d="M-100,520 Q260,340 560,470 T1100,420"
                  fill="none" stroke="#14B8A6" stroke-width="1" stroke-dasharray="2 7"/>
        </svg>

        {{-- klaster kiri-atas --}}
        <div class="absolute left-[8%] top-[16%]" data-motion="float" data-delay="0.2">
            <div class="h-14 w-14 rotate-12 rounded-2xl border border-quest-300/60"></div>
        </div>
        <div class="absolute left-[5%] top-[27%]" data-motion="pulse" data-delay="0.5">
            <div class="h-2.5 w-2.5 rounded-full bg-sun-400/70"></div>
        </div>
        <div class="absolute left-[13%] top-[10%]" data-motion="orbit" data-delay="0">
            <div class="h-3 w-3 rounded-full border border-sage-400/60"></div>
        </div>

        {{-- klaster kanan-atas --}}
        <div class="absolute right-[9%] top-[13%]" data-motion="float-slow" data-delay="0.4">
            <div class="h-28 w-28 rounded-full border-2 border-quest-300/45"></div>
        </div>
        <div class="absolute right-[19%] top-[8%]" data-motion="pulse" data-delay="1.2">
            <div class="h-3 w-3 rounded-full bg-sun-400/70"></div>
        </div>
        <div class="absolute right-[24%] top-[26%]" data-motion="spin" data-delay="0">
            <svg class="h-10 w-10 text-sun-400/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                <path d="M12 4v16M4 12h16"/>
            </svg>
        </div>
        <div class="absolute right-[15%] top-[30%]" data-motion="sway" data-delay="0.6">
            <div class="h-2 w-12 rounded-full bg-sage-300/60"></div>
        </div>

        {{-- klaster kiri-tengah --}}
        <div class="absolute left-[11%] top-[48%]" data-motion="spin" data-delay="1">
            <svg class="h-16 w-16 text-sage-400/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4">
                <path d="M12 2 22 20H2Z" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="absolute left-[16%] top-[62%]" data-motion="float" data-delay="0.8">
            <div class="h-12 w-24 rounded-t-full border-t-2 border-quest-300/50"></div>
        </div>
        <div class="absolute left-[7%] top-[60%]" data-motion="pulse" data-delay="0.3">
            <div class="h-1.5 w-1.5 rounded-full bg-quest-500/60"></div>
        </div>

        {{-- klaster kanan-tengah --}}
        <div class="absolute right-[12%] top-[44%]" data-motion="float" data-delay="1.4">
            <svg class="h-16 w-16 text-quest-400/55" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3">
                <path d="M12 2 20 7v10l-8 5-8-5V7Z" stroke-linejoin="round"/>
            </svg>
        </div>
        <div class="absolute right-[18%] top-[58%]" data-motion="sway" data-delay="0.4">
            <div class="grid h-20 w-20 place-items-center rounded-full border border-sage-300/55">
                <div class="grid h-14 w-14 place-items-center rounded-full border border-sage-300/55">
                    <div class="h-8 w-8 rounded-full border border-sage-300/55"></div>
                </div>
            </div>
        </div>

        {{-- klaster kiri-bawah --}}
        <div class="absolute left-[14%] bottom-[16%]" data-motion="float-slow" data-delay="0.5">
            <div class="grid h-24 w-24 place-items-center rounded-full border border-sun-300/55">
                <div class="grid h-16 w-16 place-items-center rounded-full border border-sun-300/55">
                    <div class="h-8 w-8 rounded-full border border-sun-300/55"></div>
                </div>
            </div>
        </div>
        <div class="absolute left-[8%] bottom-[32%]" data-motion="orbit" data-delay="0.9">
            <div class="h-2 w-2 rounded-full bg-quest-500/60"></div>
        </div>
        <div class="absolute left-[24%] bottom-[10%]" data-motion="pulse" data-delay="1.1">
            <div class="h-2.5 w-2.5 rounded-full bg-sun-400/70"></div>
        </div>

        {{-- klaster kanan-bawah --}}
        <div class="absolute right-[12%] bottom-[20%]" data-motion="float" data-delay="1.6">
            <div class="h-12 w-12 rotate-45 rounded-lg border border-quest-300/60"></div>
        </div>
        <div class="absolute right-[22%] bottom-[14%]" data-motion="pulse" data-delay="0.7">
            <div class="h-2 w-2 rounded-full bg-sun-500/70"></div>
        </div>
        <div class="absolute right-[6%] bottom-[36%]" data-motion="spin" data-delay="0.4">
            <svg class="h-9 w-9 text-quest-400/50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                <path d="M3 15l4-4 4 4 4-4 4 4"/>
            </svg>
        </div>

        {{-- aksen tengah --}}
        <div class="absolute left-[42%] top-[16%]" data-motion="float-slow" data-delay="0.1">
            <svg class="h-8 w-8 text-sun-400/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                <path d="M12 3v18M3 12h18M6 6l12 12M18 6 6 18"/>
            </svg>
        </div>
        <div class="absolute left-[52%] top-[8%]" data-motion="pulse" data-delay="1.5">
            <div class="h-1.5 w-1.5 rounded-full bg-quest-500/60"></div>
        </div>
        <div class="absolute left-[62%] bottom-[12%]" data-motion="orbit" data-delay="1.3">
            <div class="h-3 w-3 rounded-full border border-sun-400/60"></div>
        </div>
        <div class="absolute left-[38%] bottom-[24%]" data-motion="sway" data-delay="0.8">
            <div class="h-1.5 w-10 rounded-full bg-quest-300/60"></div>
        </div>

        {{-- Grain overlay --}}
        <div class="absolute inset-0 bg-grain opacity-[0.05] mix-blend-multiply"></div>
    </div>

    {{-- ===================== HEADER ===================== --}}
    <header class="relative z-40 border-b border-zinc-200 bg-white/90 backdrop-blur-md
                   after:pointer-events-none after:absolute after:inset-x-0 after:bottom-0 after:h-px
                   after:bg-gradient-to-r after:from-transparent after:via-quest-500/60 after:to-transparent
                   lg:sticky lg:top-0">

        <div class="mx-auto flex h-14 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
            <div class="flex min-w-0 items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <svg class="h-7 w-7 shrink-0 text-zinc-950" aria-hidden="true"><use href="#cq-mark"/></svg>
                    <span class="font-display text-[17px] font-semibold tracking-tight text-zinc-950">Career Quest</span>
                </a>
                <span class="hidden h-4 w-px bg-zinc-200 sm:block" aria-hidden="true"></span>
                <span class="hidden text-sm capitalize text-zinc-500 sm:block">{{ auth()->user()->role ?? 'tamu' }}</span>
            </div>

            @auth
                <div class="flex items-center gap-3">
                    <div class="hidden text-right leading-tight sm:block">
                        <p class="max-w-[14rem] truncate text-sm font-medium text-zinc-950">{{ auth()->user()->name }}</p>
                        <p class="max-w-[14rem] truncate text-xs text-zinc-500">{{ auth()->user()->identifier }}</p>
                    </div>

                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-gradient-to-br from-quest-100 to-quest-200 text-xs font-semibold text-quest-800 ring-1 ring-inset ring-quest-300/60"
                          title="{{ auth()->user()->name }} ({{ auth()->user()->identifier }})">
                        {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-secondary !px-3 !py-1.5">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <path d="m16 17 5-5-5-5"/>
                                <path d="M21 12H9"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        {{-- Jalur perjalanan siswa (7 langkah) --}}
        @auth
            @if (auth()->user()->role === 'siswa')
                @php
                    $langkah = [
                        ['route' => 'siswa.beranda',           'label' => 'Beranda'],
                        ['route' => 'siswa.kenali-diri',       'label' => 'Kenali Diri'],
                        ['route' => 'siswa.kartu-profesi',     'label' => 'Kartu Profesi'],
                        ['route' => 'siswa.misi',              'label' => 'Misi'],
                        ['route' => 'siswa.tantangan-pilihan', 'label' => 'Tantangan Pilihan'],
                        ['route' => 'siswa.refleksi',          'label' => 'Refleksi'],
                        ['route' => 'siswa.penutup',           'label' => 'Penutup'],
                    ];

                    $posisi = collect($langkah)->search(fn ($l) => request()->routeIs($l['route'], $l['route'] . '.*'));
                    $posisi = $posisi === false ? -1 : $posisi;
                    $total  = count($langkah);
                @endphp

                <div class="border-t border-zinc-100">
                    <div class="mx-auto max-w-6xl px-4 sm:px-6">

                        {{-- Layar besar: jalur lengkap --}}
                        <nav aria-label="Langkah Career Quest" class="hidden lg:block">
                            <ol class="grid grid-cols-7 py-4">
                                @foreach ($langkah as $i => $item)
                                    @php
                                        $status = $i < $posisi ? 'lewat' : ($i === $posisi ? 'kini' : 'nanti');

                                        $node = [
                                            'lewat' => 'bg-zinc-950 text-white',
                                            'kini'  => 'bg-quest-600 text-white ring-4 ring-quest-100',
                                            'nanti' => 'border border-zinc-300 bg-white text-zinc-500 group-hover:border-zinc-950 group-hover:text-zinc-950',
                                        ][$status];

                                        $label = [
                                            'lewat' => 'text-zinc-700',
                                            'kini'  => 'font-semibold text-zinc-950',
                                            'nanti' => 'text-zinc-500 group-hover:text-zinc-950',
                                        ][$status];
                                    @endphp

                                    <li class="relative">
                                        @unless ($loop->last)
                                            <span class="absolute left-10 right-2 top-[15px] block h-0.5 overflow-hidden rounded-full bg-zinc-200" aria-hidden="true">
                                                @if ($i < $posisi)
                                                    <span class="absolute inset-0 origin-left bg-gradient-to-r from-zinc-900 to-quest-700 {{ $i === $posisi - 1 ? 'animate-fill' : '' }}"></span>
                                                @endif
                                            </span>
                                        @endunless

                                        <a href="{{ route($item['route']) }}"
                                           @if ($status === 'kini') aria-current="page" @endif
                                           class="group relative z-10 inline-flex flex-col items-start gap-2">
                                            <span class="relative inline-flex">
                                                @if ($status === 'kini')
                                                    <span class="absolute inset-0 rounded-full bg-quest-400/40 blur-md animate-pulse-soft" aria-hidden="true"></span>
                                                @endif
                                                <span class="relative grid h-8 w-8 place-items-center rounded-full text-sm font-semibold transition {{ $node }}">{{ $i + 1 }}</span>
                                            </span>
                                            <span class="text-[13px] leading-tight transition {{ $label }}">{{ $item['label'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </nav>

                        {{-- Layar kecil: ringkas --}}
                        <nav aria-label="Langkah Career Quest" class="py-3 lg:hidden">
                            <div class="flex items-baseline justify-between gap-3">
                                <p class="truncate text-sm font-semibold text-zinc-950">
                                    {{ $posisi >= 0 ? $langkah[$posisi]['label'] : 'Career Quest' }}
                                </p>
                                <p class="shrink-0 text-xs text-zinc-500">
                                    {{ $posisi >= 0 ? 'Langkah ' . ($posisi + 1) . ' dari ' . $total : $total . ' langkah' }}
                                </p>
                            </div>

                            <ol class="mt-1 flex gap-1.5">
                                @foreach ($langkah as $i => $item)
                                    @php
                                        $bar = $i < $posisi
                                            ? 'bg-gradient-to-r from-zinc-900 to-quest-700'
                                            : ($i === $posisi ? 'bg-quest-600' : 'bg-zinc-200');
                                    @endphp
                                    <li class="flex-1">
                                        <a href="{{ route($item['route']) }}"
                                           @if ($i === $posisi) aria-current="page" @endif
                                           aria-label="Langkah {{ $i + 1 }}: {{ $item['label'] }}"
                                           class="block py-2.5">
                                            <span class="block h-1.5 rounded-full {{ $bar }} {{ $i === $posisi ? 'animate-pulse-soft' : '' }}"></span>
                                        </a>
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>
                </div>
            @endif
        @endauth
    </header>

    {{-- ===================== KONTEN ===================== --}}
    <main id="konten" class="relative z-0 mx-auto w-full max-w-6xl flex-1 px-4 py-8 sm:px-6 lg:py-12">
        @if (session('status'))
            <div role="status" class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900 shadow-sm shadow-emerald-900/[0.04]" data-no-animate>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-600" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="m8.5 12.5 2.5 2.5 4.5-5"/>
                </svg>
                <p class="font-medium">{{ session('status') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="mb-6 flex items-start gap-3 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3.5 text-sm text-rose-900 shadow-sm shadow-rose-900/[0.04]" data-no-animate>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 h-4 w-4 shrink-0 text-rose-600" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 8v4.5M12 16h.01"/>
                </svg>
                <div>
                    <p class="font-semibold">Ada isian yang perlu diperbaiki</p>
                    <ul class="mt-1.5 list-disc space-y-1 pl-5 text-rose-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- ===================== FOOTER ===================== --}}
    <footer class="relative z-10 overflow-hidden bg-zinc-950 text-zinc-400">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-quest-500/50 to-transparent" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full bg-quest-600/20 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-16 h-64 w-64 rounded-full bg-sun-500/10 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-6xl px-4 py-12 sm:px-6">
            <div class="grid gap-8 md:grid-cols-2 md:items-start">
                <div>
                    <div class="flex items-center gap-2.5">
                        <svg class="h-7 w-7 shrink-0 rounded-lg text-zinc-800 ring-1 ring-white/10" aria-hidden="true"><use href="#cq-mark"/></svg>
                        <span class="font-display text-lg font-semibold tracking-tight text-white">Career Quest</span>
                    </div>
                    <p class="mt-4 max-w-sm text-sm leading-relaxed">
                        Media eksplorasi karier reflektif sebagai pendukung layanan BK.
                    </p>
                </div>

                <div class="rounded-xl border border-white/10 bg-white/[0.03] p-4">
                    <p class="text-sm font-medium text-white">Perlu diketahui</p>
                    <p class="mt-1 text-sm leading-relaxed">
                        Career Quest bukan tes psikologi dan tidak memberikan diagnosis.
                    </p>
                </div>
            </div>

            <div class="mt-10 border-t border-white/10 pt-5 text-xs text-zinc-500">
                © {{ date('Y') }} Career Quest
            </div>
        </div>
    </footer>

    {{-- ===================== MOTION (Framer Motion vanilla) ===================== --}}
    <script src="https://cdn.jsdelivr.net/npm/motion@11.11.13/dist/motion.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const M = window.Motion;
            if (!M || typeof M.animate !== 'function') return;

            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reduce) return;

            const { animate } = M;

            /* ---------- 1. PRESET LOOP ANIMASI (Array [keyframes, options]) ---------- */
            const presets = {
                float:        (d) => [{ y: [0, -18, 0] },                                  { duration: 9,  ease: 'easeInOut', repeat: Infinity, delay: d }],
                'float-slow': (d) => [{ y: [0, -28, 0] },                                  { duration: 13, ease: 'easeInOut', repeat: Infinity, delay: d }],
                drift:        (d) => [{ x: [0, 40, -30, 0], y: [0, -30, 20, 0], scale: [1, 1.06, 0.96, 1] },
                                                                                            { duration: 22, ease: 'easeInOut', repeat: Infinity, delay: d }],
                'drift-slow': (d) => [{ x: [0, -50, 0], y: [0, 40, 0], scale: [1, 1.1, 1] },
                                                                                            { duration: 28, ease: 'easeInOut', repeat: Infinity, delay: d }],
                orbit:        (d) => [{ x: [0, 14, 0, -14, 0], y: [0, -10, -18, -10, 0] },  { duration: 11, ease: 'easeInOut', repeat: Infinity, delay: d }],
                sway:         (d) => [{ x: [-8, 8, -8], rotate: [-4, 4, -4] },             { duration: 14, ease: 'easeInOut', repeat: Infinity, delay: d }],
                breathe:      (d) => [{ opacity: [0.35, 0.65, 0.35], scale: [1, 1.05, 1] }, { duration: 10, ease: 'easeInOut', repeat: Infinity, delay: d }],
                pulse:        (d) => [{ opacity: [1, 0.5, 1] },                            { duration: 3,  ease: 'easeInOut', repeat: Infinity, delay: d }],
                spin:         (d) => [{ rotate: [0, 360] },                                { duration: 45, ease: 'linear',    repeat: Infinity, delay: d }],
            };

            document.querySelectorAll('[data-motion]').forEach((el) => {
                const preset = presets[el.dataset.motion];
                if (!preset) return;
                const delay = parseFloat(el.dataset.delay || '0');
                const [keyframes, options] = preset(delay);
                animate(el, keyframes, options);
            });

            /* ---------- 2. PARALLAX ---------- */
            const parallaxEls = [...document.querySelectorAll('[data-parallax]')];
            if (parallaxEls.length) {
                const applyParallax = (progress) => {
                    parallaxEls.forEach((el) => {
                        const speed = parseFloat(el.dataset.parallax) || 30;
                        el.style.transform = `translate3d(0, ${-progress * speed}px, 0)`;
                    });
                };

                if (typeof M.scroll === 'function') {
                    M.scroll((info) => {
                        const p = typeof info === 'number'
                            ? info
                            : (info?.y?.progress ?? info?.progress ?? 0);
                        applyParallax(p);
                    });
                } else {
                    let raf = 0;
                    const onScroll = () => {
                        cancelAnimationFrame(raf);
                        raf = requestAnimationFrame(() => {
                            const max = document.documentElement.scrollHeight - window.innerHeight;
                            applyParallax(max > 0 ? window.scrollY / max : 0);
                        });
                    };
                    window.addEventListener('scroll', onScroll, { passive: true });
                    onScroll();
                }
            }

            /* ---------- 3. CARD ENTRANCE (non-destructive, tidak pernah hide permanen) ---------- */
            const cards = [...document.querySelectorAll('.card:not([data-no-animate])')];
            cards.forEach((card, i) => {
                animate(
                    card,
                    { opacity: [0.01, 1], y: [10, 0] },
                    { duration: 0.45, delay: Math.min(i, 4) * 0.05, ease: [0.22, 1, 0.36, 1] }
                );
            });
        });
    </script>

    @stack('scripts')
</body>
</html>