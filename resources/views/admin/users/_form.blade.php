@php
    $isEdit = $user->exists;
    $profile = $user->role === 'EVENT_ORGANIZER' ? $user->eventOrganizer : $user->tenant;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label class="form-label" for="name">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" class="form-input" required value="{{ old('name', $user->name) }}">
    </div>
    <div>
        <label class="form-label" for="email">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" id="email" class="form-input" required value="{{ old('email', $user->email) }}">
    </div>
    <div>
        <label class="form-label" for="phone">No. Telepon</label>
        <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone', $profile?->contact_phone) }}">
    </div>
    <div>
        <label class="form-label" for="role">Role <span class="text-red-500">*</span></label>
        <select name="role" id="role" class="form-select" required @disabled($isEdit) onchange="toggleRoleFields(this.value)">
            @foreach (['' => '-- Pilih Role --', 'ADMIN' => 'Admin', 'EVENT_ORGANIZER' => 'Event Organizer', 'TENANT' => 'Tenant', 'MASYARAKAT' => 'Masyarakat'] as $value => $label)
                <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @if ($isEdit)
            <input type="hidden" name="role" value="{{ $user->role }}">
        @endif
    </div>
    <div>
        <label class="form-label" for="status">Status Akun</label>
        <select name="status" id="status" class="form-select">
            @foreach (['ACTIVE' => 'Aktif', 'INACTIVE' => 'Tidak Aktif', 'SUSPENDED' => 'Dibekukan'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $user->status ?: 'ACTIVE') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div id="org-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
        <div>
            <label class="form-label" for="org_name">Nama Organisasi</label>
            <input type="text" name="org_name" id="org_name" class="form-input" value="{{ old('org_name', $profile?->organization_name) }}">
        </div>
        <div>
            <label class="form-label" for="address">Alamat</label>
            <input type="text" name="address" id="address" class="form-input" value="{{ old('address', $profile?->address) }}">
        </div>
    </div>

    <div>
        <label class="form-label" for="password">Password {{ $isEdit ? '' : '*' }}</label>
        <input type="password" name="password" id="password" class="form-input" @required(! $isEdit)>
        <p class="text-xs text-slate-400 mt-1">{{ $isEdit ? 'Kosongkan jika tidak ingin mengubah password.' : 'Minimal 8 karakter.' }}</p>
    </div>
    <div>
        <label class="form-label" for="password_confirmation">Konfirmasi Password {{ $isEdit ? '' : '*' }}</label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" @required(! $isEdit)>
    </div>

    <label class="md:col-span-2 flex items-center gap-3 p-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
        <input type="checkbox" name="is_verified" value="1" class="rounded border-slate-300 text-indigo-600" @checked(old('is_verified', $user->exists ? $user->is_verified : true))>
        <span class="text-sm text-slate-600 dark:text-slate-300">Tandai email sebagai verified</span>
    </label>
</div>

@push('scripts')
    <script>
        function toggleRoleFields(role) {
            const wrapper = document.getElementById('org-fields');
            const enabled = role === 'EVENT_ORGANIZER' || role === 'TENANT';
            wrapper.classList.toggle('hidden', !enabled);
            wrapper.querySelectorAll('input').forEach((input) => {
                input.required = enabled;
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            toggleRoleFields(document.getElementById('role').value);
        });
    </script>
@endpush
