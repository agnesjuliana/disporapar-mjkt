<x-layouts.app :title="'Edit - '.$user->name" current-page="users" role="ADMIN">
    <x-ui.flash-banner />

    <div class="mb-5">
        <a href="{{ route('users.show', $user) }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            Kembali ke Detail Pengguna
        </a>
        <h2 class="page-title mt-2">Edit Pengguna</h2>
        <p class="page-subtitle">{{ $user->name }}</p>
    </div>

    <div class="card max-w-3xl">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')
            @include('admin.users._form', ['user' => $user])
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-layouts.app>
