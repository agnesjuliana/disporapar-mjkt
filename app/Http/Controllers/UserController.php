<?php

namespace App\Http\Controllers;

use App\Models\EventOrganizer;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $role = $request->string('role')->toString();
        $status = $request->string('status')->toString();
        $verified = $request->string('verified')->toString();

        $users = User::query()
            ->when($search, function (Builder $query) use ($search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, fn (Builder $query) => $query->where('role', $role))
            ->when($status, fn (Builder $query) => $query->where('status', $status))
            ->when($verified !== '', fn (Builder $query) => $query->where('is_verified', $verified === '1'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => User::query()->count(),
            'admin' => User::query()->where('role', 'ADMIN')->count(),
            'eo' => User::query()->where('role', 'EVENT_ORGANIZER')->count(),
            'tenant' => User::query()->where('role', 'TENANT')->count(),
            'masyarakat' => User::query()->where('role', 'MASYARAKAT')->count(),
            'unverified' => User::query()->where('is_verified', false)->count(),
        ];

        return view('admin.users.index', [
            'users' => $users,
            'stats' => $stats,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'verified' => $verified,
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateUser($request);

        DB::transaction(function () use ($validated): void {
            $user = User::create([
                'id' => (string) Str::uuid(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password_hash' => $validated['password'],
                'role' => $validated['role'],
                'status' => $validated['status'],
                'is_verified' => (bool) ($validated['is_verified'] ?? false),
            ]);

            $this->syncRoleProfile($user, $validated);
        });

        return to_route('users.index')->with('status', 'Pengguna berhasil dibuat.');
    }

    public function show(User $user): View
    {
        $user->load(['tenant', 'eventOrganizer', 'participantRegistrations.event']);

        return view('admin.users.show', [
            'user' => $user,
        ]);
    }

    public function edit(User $user): View
    {
        $user->load(['tenant', 'eventOrganizer']);

        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $this->validateUser($request, $user);

        DB::transaction(function () use ($user, $validated): void {
            $payload = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'status' => $validated['status'],
                'is_verified' => (bool) ($validated['is_verified'] ?? false),
            ];

            if (! empty($validated['password'])) {
                $payload['password_hash'] = $validated['password'];
            }

            $user->update($payload);
            $this->syncRoleProfile($user, $validated);
        });

        return to_route('users.show', $user)->with('status', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return to_route('users.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return to_route('users.index')->with('status', 'Pengguna berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateUser(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'role' => [
                $user ? 'sometimes' : 'required',
                Rule::in(['ADMIN', 'EVENT_ORGANIZER', 'TENANT', 'MASYARAKAT']),
            ],
            'status' => ['required', Rule::in(['ACTIVE', 'INACTIVE', 'SUSPENDED'])],
            'is_verified' => ['nullable', 'boolean'],
            'password' => [$user ? 'nullable' : 'required', 'confirmed', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:255'],
            'org_name' => ['nullable', 'required_if:role,EVENT_ORGANIZER,TENANT', 'string', 'max:255'],
            'address' => ['nullable', 'required_if:role,EVENT_ORGANIZER,TENANT', 'string', 'max:255'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function syncRoleProfile(User $user, array $validated): void
    {
        if ($user->role === 'EVENT_ORGANIZER') {
            EventOrganizer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'id' => $user->eventOrganizer?->id ?? (string) Str::uuid(),
                    'organization_name' => $validated['org_name'] ?? $user->eventOrganizer?->organization_name ?? $user->name,
                    'contact_person' => $user->name,
                    'contact_phone' => $validated['phone'] ?? $user->eventOrganizer?->contact_phone ?? '',
                    'address' => $validated['address'] ?? $user->eventOrganizer?->address ?? '-',
                ],
            );
        }

        if ($user->role === 'TENANT') {
            Tenant::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'id' => $user->tenant?->id ?? (string) Str::uuid(),
                    'organization_name' => $validated['org_name'] ?? $user->tenant?->organization_name ?? $user->name,
                    'contact_person' => $user->name,
                    'contact_phone' => $validated['phone'] ?? $user->tenant?->contact_phone ?? '',
                    'address' => $validated['address'] ?? $user->tenant?->address ?? '-',
                    'registration_status' => $user->tenant?->registration_status ?? 'APPROVED',
                    'registered_at' => $user->tenant?->registered_at ?? now(),
                    'approved_by' => $user->tenant?->approved_by,
                    'approved_at' => $user->tenant?->approved_at,
                ],
            );
        }
    }
}
