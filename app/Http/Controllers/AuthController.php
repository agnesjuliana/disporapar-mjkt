<?php

namespace App\Http\Controllers;

use App\Models\EventOrganizer;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

        $user = $request->user();
        if (! $user->is_verified) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $this->sendVerificationOtp($user);

            return redirect()
                ->route('verification.notice', ['email' => $user->email])
                ->with('status', 'Email belum diverifikasi. Kode OTP baru telah dikirim.');
        }

        if ($user->status !== 'ACTIVE') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi admin.'])
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

        $user = User::create([
            'id' => (string) Str::uuid(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password_hash' => $validated['password'],
            'role' => 'MASYARAKAT',
            'status' => 'ACTIVE',
            'is_verified' => false,
        ]);

        $this->sendVerificationOtp($user);

        return redirect()
            ->route('verification.notice', ['email' => $user->email])
            ->with('status', 'Pendaftaran berhasil. Masukkan kode OTP yang dikirim ke email Anda.');
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

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'id' => (string) Str::uuid(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password_hash' => $validated['password'],
                'role' => 'TENANT',
                'status' => 'ACTIVE',
                'is_verified' => false,
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

            return $user;
        });

        $this->sendVerificationOtp($user);

        return redirect()
            ->route('verification.notice', ['email' => $user->email])
            ->with('status', 'Pendaftaran berhasil. Masukkan kode OTP yang dikirim ke email Anda, lalu tunggu verifikasi Admin.');
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

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'id' => (string) Str::uuid(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password_hash' => $validated['password'],
                'role' => 'EVENT_ORGANIZER',
                'status' => 'ACTIVE',
                'is_verified' => false,
            ]);

            EventOrganizer::create([
                'id' => (string) Str::uuid(),
                'user_id' => $user->id,
                'organization_name' => $validated['org_name'],
                'contact_person' => $validated['name'],
                'contact_phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            return $user;
        });

        $this->sendVerificationOtp($user);

        return redirect()
            ->route('verification.notice', ['email' => $user->email])
            ->with('status', 'Pendaftaran event organizer berhasil. Masukkan kode OTP yang dikirim ke email Anda.');
    }

    public function showVerification(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $email = $request->string('email')->toString();
        abort_if($email === '', 404);

        return view('auth.verify-email', [
            'email' => $email,
        ]);
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp' => ['required', 'digits:6'],
        ]);

        $user = User::query()
            ->where('email', $validated['email'])
            ->firstOrFail();

        if ($user->is_verified) {
            return redirect()
                ->route('login')
                ->with('status', 'Email sudah terverifikasi. Silakan masuk.');
        }

        if (
            ! $user->email_verification_otp_hash
            || ! $user->email_verification_otp_expires_at
            || $user->email_verification_otp_expires_at->isPast()
            || ! Hash::check($validated['otp'], $user->email_verification_otp_hash)
        ) {
            return back()
                ->withErrors(['otp' => 'Kode OTP salah atau sudah kedaluwarsa.'])
                ->onlyInput('email');
        }

        $user->update([
            'is_verified' => true,
            'email_verification_otp_hash' => null,
            'email_verification_otp_expires_at' => null,
        ]);

        return redirect()
            ->route('login')
            ->with('status', 'Email berhasil diverifikasi. Silakan masuk.');
    }

    public function resendVerificationOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::query()
            ->where('email', $validated['email'])
            ->firstOrFail();

        if ($user->is_verified) {
            return redirect()
                ->route('login')
                ->with('status', 'Email sudah terverifikasi. Silakan masuk.');
        }

        $this->sendVerificationOtp($user);

        return back()
            ->with('status', 'Kode OTP baru telah dikirim ke email Anda.')
            ->withInput(['email' => $user->email]);
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

    private function sendVerificationOtp(User $user): void
    {
        $otp = (string) random_int(100000, 999999);

        $user->forceFill([
            'email_verification_otp_hash' => Hash::make($otp),
            'email_verification_otp_expires_at' => now()->addMinutes(10),
        ])->save();

        Mail::send('emails.auth.verification-otp', [
            'user' => $user,
            'otp' => $otp,
            'expiresAt' => $user->email_verification_otp_expires_at,
        ], function ($message) use ($user): void {
            $message
                ->to($user->email, $user->name)
                ->subject('Kode Verifikasi Email Disporapar');
        });
    }
}
