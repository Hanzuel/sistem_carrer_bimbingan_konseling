<header class="sticky top-0 z-20 bg-white border-b border-slate-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 min-h-16 py-2 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-4">
            <a href="{{ route('home') }}" class="font-bold text-base text-slate-900">
                Career Quest
            </a>
            <nav class="flex flex-wrap items-center gap-1.5 sm:gap-2 text-xs sm:text-sm">
                <a href="{{ route('siswa.beranda') }}"
                   class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('siswa.beranda') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                    Beranda
                </a>
                <a href="{{ route('siswa.misi-1') }}"
                   class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('siswa.misi-1') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                    Misi 1
                </a>
                <a href="{{ route('siswa.misi-2') }}"
                   class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('siswa.misi-2') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                    Misi 2
                </a>
                <a href="{{ route('siswa.misi-3') }}"
                   class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('siswa.misi-3') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                    Misi 3
                </a>
                <a href="{{ route('siswa.misi-4') }}"
                   class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('siswa.misi-4') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                    Misi 4
                </a>
                <a href="{{ route('siswa.misi-5') }}"
                   class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('siswa.misi-5') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                    Misi 5
                </a>
                <a href="{{ route('siswa.pusat-materi') }}"
                   class="px-2.5 py-1.5 rounded-lg {{ request()->routeIs('siswa.pusat-materi') ? 'bg-indigo-600 text-white font-medium' : 'text-slate-600 hover:bg-slate-100' }}">
                    Pusat Materi
                </a>
            </nav>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-100">
                Keluar
            </button>
        </form>
    </div>
</header>