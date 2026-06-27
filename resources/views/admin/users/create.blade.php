<x-layouts.app title="Tambah Pengguna" current-page="users" role="ADMIN">
    <x-ui.flash-banner />

    <div class="mb-5">
        <a href="{{ route('users.index') }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Pengguna
        </a>
        <h2 class="page-title mt-2">Tambah Pengguna</h2>
        <p class="page-subtitle">Buat akun pengguna baru untuk sistem.</p>
    </div>

    <div class="card max-w-3xl">
        <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
            @csrf
            @include('admin.users._form', ['user' => new \App\Models\User(['status' => 'ACTIVE'])])
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Pengguna
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>
