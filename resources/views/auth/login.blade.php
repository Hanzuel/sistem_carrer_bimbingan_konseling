@extends('layouts.app')

@section('title', 'Login Administrator')

@section('content')
@php
    $loginAction = Route::has('login.post') ? route('login.post') : route('login');
@endphp

<div class="mx-auto max-w-md space-y-6 py-4">
    <div class="card space-y-5">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-quest-600">Akses Terbatas</p>
            <h1 class="mt-1 text-2xl font-bold text-zinc-950">Login Administrator</h1>
            <p class="mt-1 text-xs leading-relaxed text-zinc-600">
                Halaman masuk khusus pengelolaan kelas, akun pengguna, dan administrasi sistem Career Quest.
            </p>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-sage-200 bg-sage-50 px-3.5 py-2.5 text-xs text-sage-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-3.5 py-2.5 text-xs text-red-700">
                <ul class="list-disc space-y-0.5 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $loginAction }}" class="space-y-4">
            @csrf

            <div>
                <label for="identifier" class="mb-1 block text-xs font-semibold text-zinc-700">
                    ID / Kode Administrator
                </label>
                <input
                    id="identifier"
                    type="text"
                    name="identifier"
                    value="{{ old('identifier') }}"
                    required
                    autofocus
                    placeholder="Contoh: admin01"
                    class="field"
                >
            </div>

            <div>
                <label for="password" class="mb-1 block text-xs font-semibold text-zinc-700">
                    Kata Sandi
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    placeholder="Masukkan kata sandi admin"
                    class="field"
                >
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="inline-flex cursor-pointer items-center gap-2 text-zinc-600">
                    <input type="checkbox" name="remember" class="rounded border-zinc-300 text-quest-600 focus:ring-quest-600">
                    <span>Ingat sesi saya</span>
                </label>
            </div>

            <button type="submit" class="btn-accent w-full">
                Masuk ke Panel Admin
            </button>
        </form>

        <div class="border-t border-zinc-100 pt-4 space-y-3">
            <button type="button" onclick="fillAdminDemo('admin01', 'password123')"
                    class="w-full rounded-lg border border-zinc-200 bg-paper px-3 py-2 text-xs font-medium text-zinc-800 transition hover:border-quest-400 hover:bg-quest-50">
                Isi Cepat Akun Uji Coba Admin <span class="text-zinc-500">(admin01)</span>
            </button>

            <div class="text-center">
                <a href="{{ route('home') }}" class="text-xs font-medium text-zinc-600 underline hover:text-zinc-950">
                    Kembali ke Halaman Utama (Siswa & Guru BK)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function fillAdminDemo(identifier, password) {
        const idInput = document.getElementById('identifier');
        const pwInput = document.getElementById('password');
        if (idInput) idInput.value = identifier;
        if (pwInput) pwInput.value = password;
        idInput?.focus();
    }
</script>
@endpush