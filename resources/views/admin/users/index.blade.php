<x-layouts.app title="Manajemen Pengguna" current-page="users" role="ADMIN">
    <x-ui.flash-banner />

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="page-title">Manajemen Pengguna</h2>
            <p class="page-subtitle">Kelola akun admin, event organizer, tenant, dan masyarakat.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Tambah Pengguna
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4 mb-5">
        @foreach ([
            ['label' => 'Total', 'value' => $stats['total'], 'icon' => 'users', 'color' => 'indigo'],
            ['label' => 'Admin', 'value' => $stats['admin'], 'icon' => 'shield', 'color' => 'blue'],
            ['label' => 'EO', 'value' => $stats['eo'], 'icon' => 'calendar-range', 'color' => 'emerald'],
            ['label' => 'Tenant', 'value' => $stats['tenant'], 'icon' => 'store', 'color' => 'orange'],
            ['label' => 'Masyarakat', 'value' => $stats['masyarakat'], 'icon' => 'users', 'color' => 'slate'],
            ['label' => 'Belum Verified', 'value' => $stats['unverified'], 'icon' => 'mail-warning', 'color' => 'amber'],
        ] as $stat)
            <div class="card p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/30 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-300 flex items-center justify-center shrink-0">
                    <i data-lucide="{{ $stat['icon'] }}" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500">{{ $stat['label'] }}</p>
                    <p class="text-2xl font-bold leading-tight">{{ $stat['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card mb-4 p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-52">
                <label class="form-label text-xs" for="search">Cari</label>
                <div class="relative">
                    <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" id="search" value="{{ $search }}" class="form-input pl-8 text-sm py-1.5" placeholder="Nama atau email">
                </div>
            </div>
            <div>
                <label class="form-label text-xs" for="role">Role</label>
                <select name="role" id="role" class="form-select text-sm py-1.5">
                    @foreach (['' => 'Semua', 'ADMIN' => 'Admin', 'EVENT_ORGANIZER' => 'Event Organizer', 'TENANT' => 'Tenant', 'MASYARAKAT' => 'Masyarakat'] as $value => $label)
                        <option value="{{ $value }}" @selected($role === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs" for="status">Status</label>
                <select name="status" id="status" class="form-select text-sm py-1.5">
                    @foreach (['' => 'Semua', 'ACTIVE' => 'Aktif', 'INACTIVE' => 'Tidak Aktif', 'SUSPENDED' => 'Dibekukan'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs" for="verified">Email</label>
                <select name="verified" id="verified" class="form-select text-sm py-1.5">
                    @foreach (['' => 'Semua', '1' => 'Verified', '0' => 'Belum Verified'] as $value => $label)
                        <option value="{{ $value }}" @selected($verified === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="filter" class="w-4 h-4"></i>
                Filter
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        @if ($users->isEmpty())
            <div class="text-center py-16 text-slate-500">
                <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                <p class="font-medium">Tidak ada pengguna ditemukan</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Email</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @php
                                $roleColors = ['ADMIN' => 'indigo', 'EVENT_ORGANIZER' => 'emerald', 'TENANT' => 'orange', 'MASYARAKAT' => 'blue'];
                                $roleLabels = ['ADMIN' => 'Admin', 'EVENT_ORGANIZER' => 'Event Organizer', 'TENANT' => 'Tenant', 'MASYARAKAT' => 'Masyarakat'];
                            @endphp
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $users->firstItem() + $loop->index }}</td>
                                <td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 font-bold text-xs flex items-center justify-center">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-{{ $roleColors[$user->role] ?? 'gray' }}">{{ $roleLabels[$user->role] ?? $user->role }}</span></td>
                                <td><x-ui.status-badge :status="$user->status" /></td>
                                <td>
                                    <span class="badge {{ $user->is_verified ? 'badge-green' : 'badge-yellow' }}">
                                        {{ $user->is_verified ? 'Verified' : 'Belum Verified' }}
                                    </span>
                                </td>
                                <td class="text-xs text-slate-400 whitespace-nowrap">{{ $user->created_at?->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="flex gap-1">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-secondary btn-sm btn-icon" title="Detail">
                                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-ghost btn-sm btn-icon" title="Edit">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-ghost btn-sm btn-icon text-red-500" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
