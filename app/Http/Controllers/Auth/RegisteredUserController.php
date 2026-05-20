<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $roleInput = $request->input('role', User::ROLE_STUDENT);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'status' => $status,
        ]);

        if ($roleInput === 'counselor') {
            $cvPath = null;
            if ($request->hasFile('cv')) {
                $cvPath = $request->file('cv')->store('cvs', 'public');
            }

            \App\Models\CounselorProfile::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'specialty' => $request->specialty,
                'experience_years' => $request->experience_years,
                'bio' => $request->bio,
                'cv_path' => $cvPath,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($user->role === User::ROLE_COUNSELOR_PENDING) {
            return redirect()->route('counselor.pending');
        }

        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        }

        if ($user->isCounselor()) {
            return redirect()->route('counselor.dashboard');
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
