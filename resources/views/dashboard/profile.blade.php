<x-layouts.app title="Profil" current-page="profile" :role="$menu['role']">
    @php
        $roleProfile = $user->role === 'EVENT_ORGANIZER' ? $user->eventOrganizer : ($user->role === 'TENANT' ? $user->tenant : null);
    @endphp

    <div class="space-y-6">
        <div class="page-header">
            <div>
                <p class="page-subtitle">Informasi akun pengguna</p>
                <h2 class="page-title">Profil</h2>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali ke Home
            </a>
        </div>

        <section class="grid grid-cols-1 xl:grid-cols-3 gap-4">
            <div class="card xl:col-span-1">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-2xl {{ $menu['color'] }} flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ strtoupper(substr($user->name ?? $menu['fallbackInitial'], 0, 1)) }}
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900 dark:text-white">{{ $user->name }}</h3>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                    <div class="mt-4 flex flex-wrap justify-center gap-2">
                        <span class="badge badge-indigo">{{ $menu['label'] }}</span>
                        <x-ui.status-badge :status="$user->status" />
                    </div>
                </div>
            </div>

            <div class="card xl:col-span-2">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-4">Detail Akun</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nama</dt>
                        <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</dt>
                        <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Role</dt>
                        <dd class="mt-1"><span class="badge badge-indigo">{{ $menu['label'] }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status</dt>
                        <dd class="mt-1"><x-ui.status-badge :status="$user->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Verifikasi Email</dt>
                        <dd class="mt-1">
                            @if ($user->is_verified)
                                <span class="badge badge-green">Terverifikasi</span>
                            @else
                                <span class="badge badge-yellow">Belum Terverifikasi</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Terdaftar Sejak</dt>
                        <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">
                            {{ $user->created_at?->translatedFormat('d F Y H:i') ?? '-' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </section>

        @if ($roleProfile)
            <section class="card">
                <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-4">
                    {{ $user->role === 'EVENT_ORGANIZER' ? 'Detail Event Organizer' : 'Detail Tenant' }}
                </h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Nama Organisasi</dt>
                        <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $roleProfile->organization_name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Kontak Person</dt>
                        <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $roleProfile->contact_person ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">No. Telepon</dt>
                        <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $roleProfile->contact_phone ?? '-' }}</dd>
                    </div>
                    @if ($user->role === 'TENANT')
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Status Registrasi Tenant</dt>
                            <dd class="mt-1"><x-ui.status-badge :status="$roleProfile->registration_status" /></dd>
                        </div>
                    @endif
                    <div class="md:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Alamat</dt>
                        <dd class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $roleProfile->address ?? '-' }}</dd>
                    </div>
                </dl>
            </section>
        @endif
    </div>
</x-layouts.app>
