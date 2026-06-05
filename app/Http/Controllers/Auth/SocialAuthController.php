<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\Profile;
use App\Models\CounselorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Supported social providers.
     *
     * @var array
     */
    protected $supportedProviders = ['google', 'github', 'facebook', 'linkedin', 'microsoft', 'apple'];

    /**
     * Redirect the user to the provider's official authentication page.
     *
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redirectToProvider($provider)
    {
        if (!in_array($provider, $this->supportedProviders)) {
            return redirect()->route('login')->withErrors([
                'email' => "Le fournisseur d'authentification '" . ucfirst($provider) . "' n'est pas supporté."
            ]);
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            Log::error("Socialite redirect error for {$provider}: " . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => "Impossible de rediriger vers " . ucfirst($provider) . ". Vérifiez la configuration de vos clés d'API et de vos redirections dans le fichier .env."
            ]);
        }
    }

    /**
     * Obtain the user information from the provider's callback.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        if (!in_array($provider, $this->supportedProviders)) {
            return redirect()->route('login')->withErrors([
                'email' => "Le fournisseur d'authentification '" . ucfirst($provider) . "' n'est pas supporté."
            ]);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            Log::error("Socialite callback error for provider {$provider}: " . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => "L'authentification auprès de " . ucfirst($provider) . " a échoué ou a été annulée."
            ]);
        }

        $email = $socialUser->getEmail();
        if (empty($email)) {
            return redirect()->route('login')->withErrors([
                'email' => "Impossible de récupérer votre adresse e-mail depuis votre compte " . ucfirst($provider) . ". Veuillez vous assurer que votre e-mail est configuré comme public chez ce fournisseur."
            ]);
        }

        // Search for existing SocialAccount relation
        $socialAccount = SocialAccount::where('provider_name', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            // Update token/avatar if changed
            $socialAccount->update([
                'token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken ?? $socialAccount->refresh_token,
                'avatar' => $socialUser->getAvatar(),
            ]);

            $user = $socialAccount->user;
        } else {
            // Check if User already exists with the same email
            $user = User::where('email', $email)->first();

            if ($user) {
                // Associate the social account to the existing user
                SocialAccount::create([
                    'user_id' => $user->id,
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'token' => $socialUser->token,
                    'refresh_token' => $socialUser->refreshToken,
                    'avatar' => $socialUser->getAvatar(),
                ]);
            } else {
                // INSTEAD of creating user immediately with random password,
                // redirect user to Complete Registration form to enter password & select role
                session([
                    'social_register' => [
                        'provider' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'name' => $socialUser->getName() ?? explode('@', $email)[0],
                        'email' => $email,
                        'token' => $socialUser->token,
                        'refresh_token' => $socialUser->refreshToken ?? null,
                        'avatar' => $socialUser->getAvatar(),
                    ]
                ]);

                return redirect()->route('auth.social.complete');
            }
        }

        // Check if user account is blocked
        if ($user->is_blocked) {
            return redirect()->route('login')->withErrors([
                'email' => "Votre compte a été suspendu. Veuillez contacter l'administrateur.",
            ]);
        }

        // Log the user in
        Auth::login($user, true);

        // Redirect to appropriate dashboard based on role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isCounselor()) {
            return redirect()->route($user->status === User::STATUS_APPROVED ? 'counselor.dashboard' : 'counselor.pending');
        }

        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('student.dashboard');
    }

    /**
     * Show form to complete social registration.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showCompleteForm()
    {
        $socialData = session('social_register');

        if (!$socialData) {
            return redirect()->route('login')->withErrors([
                'email' => "Session expirée ou invalide pour l'inscription sociale."
            ]);
        }

        return view('auth.social-complete', compact('socialData'));
    }

    /**
     * Complete social registration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeSocialRegister(Request $request)
    {
        $socialData = session('social_register');

        if (!$socialData) {
            return redirect()->route('login')->withErrors([
                'email' => "Session expirée ou invalide pour l'inscription sociale."
            ]);
        }

        $roleInput = $request->input('role', User::ROLE_STUDENT);

        $rules = [
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['nullable', 'in:student,counselor'],
        ];

        if ($roleInput === 'counselor') {
            $rules['phone'] = ['required', 'string', 'max:20'];
            $rules['specialty'] = ['required', 'string', 'max:255'];
            $rules['experience_years'] = ['required', 'integer', 'min:0', 'max:50'];
            $rules['bio'] = ['required', 'string', 'max:2000'];
            $rules['cv'] = ['nullable', 'file', 'mimes:pdf', 'max:4096'];
        }

        $request->validate($rules);

        $role = User::ROLE_STUDENT;
        $status = User::STATUS_APPROVED;

        if ($roleInput === 'counselor') {
            $role = User::ROLE_COUNSELOR_PENDING;
            $status = User::STATUS_PENDING_APPROVAL;
        }

        // Create user
        $user = User::create([
            'name' => $socialData['name'],
            'email' => $socialData['email'],
            'password' => Hash::make($request->password),
            'role' => $role,
            'status' => $status,
            'avatar' => $socialData['avatar'],
        ]);

        // Create profile
        if ($roleInput === 'student') {
            Profile::firstOrCreate(['user_id' => $user->id]);
        } else {
            $cvPath = null;
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
            }

            CounselorProfile::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'specialty' => $request->specialty,
                'experience_years' => $request->experience_years,
                'bio' => $request->bio,
                'cv_path' => $cvPath,
            ]);
        }

        // Associate the social account
        SocialAccount::create([
            'user_id' => $user->id,
            'provider_name' => $socialData['provider'],
            'provider_id' => $socialData['provider_id'],
            'token' => $socialData['token'],
            'refresh_token' => $socialData['refresh_token'] ?? null,
            'avatar' => $socialData['avatar'],
        ]);

        // Clean session
        session()->forget('social_register');

        // Log the user in
        Auth::login($user, true);

        // Redirect based on role
        if ($user->role === User::ROLE_COUNSELOR_PENDING) {
            return redirect()->route('counselor.pending');
        }

        return redirect()->route('student.dashboard');
    }
}
