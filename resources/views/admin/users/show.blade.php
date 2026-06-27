<x-layouts.app :title="$user->name" current-page="users" role="ADMIN">
    <x-ui.flash-banner />

    @php
        $roleLabels = ['ADMIN' => 'Admin', 'EVENT_ORGANIZER' => 'Event Organizer', 'TENANT' => 'Tenant', 'MASYARAKAT' => 'Masyarakat'];
        $roleColors = ['ADMIN' => 'indigo', 'EVENT_ORGANIZER' => 'emerald', 'TENANT' => 'orange', 'MASYARAKAT' => 'blue'];
        $profile = $user->role === 'EVENT_ORGANIZER' ? $user->eventOrganizer : $user->tenant;
    @endphp

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <a href="{{ route('users.index') }}" class="text-sm text-slate-400 hover:text-indigo-500 flex items-center gap-1 w-fit">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                Kembali ke Pengguna
            </a>
            <h2 class="page-title mt-2">{{ $user->name }}</h2>
            <p class="page-subtitle">{{ $user->email }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                Edit
            </a>
            @if ($user->id !== auth()->id())
                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="card">
            <div class="w-16 h-16 rounded-2xl bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-300 flex items-center justify-center font-bold text-2xl mb-4">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h3 class="font-semibold mb-3">Status Akun</h3>
            <div class="space-y-3">
                <div class="flex justify-between gap-3 text-sm">
                    <span class="text-slate-400">Role</span>
                    <span class="badge badge-{{ $roleColors[$user->role] ?? 'gray' }}">{{ $roleLabels[$user->role] ?? $user->role }}</span>
                </div>
                <div class="flex justify-between gap-3 text-sm">
                    <span class="text-slate-400">Status</span>
                    <x-ui.status-badge :status="$user->status" />
                </div>
                <div class="flex justify-between gap-3 text-sm">
                    <span class="text-slate-400">Email</span>
                    <span class="badge {{ $user->is_verified ? 'badge-green' : 'badge-yellow' }}">{{ $user->is_verified ? 'Verified' : 'Belum Verified' }}</span>
                </div>
                <div class="flex justify-between gap-3 text-sm">
                    <span class="text-slate-400">Terdaftar</span>
                    <span class="font-medium">{{ $user->created_at?->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-4">
            <div class="card">
                <h3 class="font-semibold mb-3">Informasi Pengguna</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ([
                        ['icon' => 'user', 'label' => 'Nama', 'value' => $user->name],
                        ['icon' => 'mail', 'label' => 'Email', 'value' => $user->email],
                        ['icon' => 'phone', 'label' => 'Telepon', 'value' => $profile?->contact_phone],
                        ['icon' => 'map-pin', 'label' => 'Alamat', 'value' => $profile?->address],
                    ] as $item)
                        <div class="flex items-start gap-3">
                            <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                            <div>
                                <p class="text-xs text-slate-400">{{ $item['label'] }}</p>
                                <p class="text-sm font-medium">{{ $item['value'] ?: '-' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($profile)
                <div class="card">
                    <h3 class="font-semibold mb-3">Profil {{ $user->role === 'TENANT' ? 'Tenant' : 'Event Organizer' }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-slate-400">Organisasi</p>
                            <p class="text-sm font-medium">{{ $profile->organization_name }}</p>
                        </div>
                        @if ($user->role === 'TENANT')
                            <div>
                                <p class="text-xs text-slate-400">Status Registrasi Tenant</p>
                                <x-ui.status-badge :status="$profile->registration_status" />
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
