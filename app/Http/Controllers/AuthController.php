<?php

namespace App\Http\Controllers;

use App\Models\EventOrganizer;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function chooseRole(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.choose-role');
    }

    public function showMasyarakatRegistration(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.masyarakat-registration');
    }

    public function registerMasyarakat(Request $request): RedirectResponse
    {
        $validated = $this->validateUserRegistration($request);

        User::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password_hash' => $validated['password'],
            'role' => 'MASYARAKAT',
            'status' => 'MASYARAKAT',
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Pendaftaran berhasil. Silakan masuk menggunakan akun Anda.');
    }

    public function showTenantRegistration(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.tenant-registration');
    }

    public function registerTenant(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:255'],
            'org_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'id' => (string) Str::uuid(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password_hash' => $validated['password'],
                'role' => 'TENANT',
                'status' => 'TENANT',
            ]);

            Tenant::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'organization_name' => $validated['org_name'],
                'contact_person' => $validated['name'],
                'contact_phone' => $validated['phone'],
                'address' => $validated['address'],
                'registration_status' => 'PENDING',
                'registered_at' => now(),
            ]);
        });

        return redirect()
            ->route('login')
            ->with('status', 'Pendaftaran berhasil. Akun Anda sedang menunggu verifikasi Admin.');
    }

    public function showEventOrganizerRegistration(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.event-organizer-registration');
    }

    public function registerEventOrganizer(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:255'],
            'org_name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'id' => (string) Str::uuid(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password_hash' => $validated['password'],
                'role' => 'EVENT_ORGANIZER',
                'status' => 'EVENT_ORGANIZER',
            ]);

            EventOrganizer::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'organization_name' => $validated['org_name'],
                'contact_person' => $validated['name'],
                'contact_phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);
        });

        return redirect()
            ->route('login')
            ->with('status', 'Pendaftaran event organizer berhasil. Silakan masuk menggunakan akun Anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda berhasil keluar.');
    }

    /**
     * @return array{name: string, email: string, password: string}
     */
    private function validateUserRegistration(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
    }
}
