<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Career Quest</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
        <div class="mb-6">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Career Quest · Bimbingan Karier</p>
            <h1 class="text-2xl font-semibold mt-1">Masuk ke Aplikasi</h1>
            <p class="text-sm text-slate-600 mt-1">Gunakan kode/ID pengguna yang diberikan untuk memulai sesi.</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            <div>
                <label for="identifier" class="block text-sm font-medium text-slate-700 mb-1">
                    Kode / ID Pengguna
                </label>
                <input
                    id="identifier"
                    name="identifier"
                    type="text"
                    value="{{ old('identifier') }}"
                    placeholder="Contoh: siswa01, bk01, atau admin01"
                    required
                    autofocus
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-slate-900 focus:outline-none"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                    Kata Sandi
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm focus:border-slate-900 focus:outline-none"
                >
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center gap-2 text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300">
                    <span>Ingat sesi</span>
                </label>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-slate-900 text-white py-2.5 text-sm font-medium hover:bg-slate-800 transition"
            >
                Masuk
            </button>
        </form>
    </div>
</body>
</html>