<section>
    <div style="display:none;">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="avatar" :value="__('Photo de Profil')" />
            <div style="display:flex; align-items:center; gap:1.5rem; margin-top:0.5rem;">
                <div class="avatar-preview" id="avatarPreviewWrap" style="width:64px; height:64px; border-radius:50%; background:linear-gradient(145deg,#0A2540 0%,#1a4f6e 40%,#d4622a 100%); display:flex; align-items:center; justify-content:center; overflow:hidden; box-shadow:0 4px 16px rgba(10,37,64,.2),0 0 0 2px rgba(212,98,42,.12); transition:transform .3s ease;">
                    @php $avatarUrl = auth()->user()->getAvatarUrl(); @endphp
                    @if($avatarUrl)
                        <img id="avatarPreviewImg" src="{{ $avatarUrl }}" onerror="this.style.display='none'; document.getElementById('avatarPreviewDefault').style.display='flex';" style="width:100%; height:100%; object-fit:cover;" alt="{{ auth()->user()->name }}">
                        <div id="avatarPreviewDefault" style="position:relative;width:100%;height:100%;display:none;align-items:center;justify-content:center;">
                            <svg style="position:absolute;bottom:-3px;left:50%;transform:translateX(-50%);width:65%;height:65%;opacity:.15;" viewBox="0 0 24 24" fill="white"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            <span style="position:relative;z-index:2;font-weight:600; font-size:1.5rem;color:#fff;text-shadow:0 1px 4px rgba(0,0,0,.15);">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                    @else
                        <div id="avatarPreviewDefault" style="position:relative;width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                            <svg style="position:absolute;bottom:-3px;left:50%;transform:translateX(-50%);width:65%;height:65%;opacity:.15;" viewBox="0 0 24 24" fill="white"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            <span style="position:relative;z-index:2;font-weight:600; font-size:1.5rem;color:#fff;text-shadow:0 1px 4px rgba(0,0,0,.15);">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                        <img id="avatarPreviewImg" src="" style="width:100%; height:100%; object-fit:cover; display:none;" alt="Preview">
                    @endif
                </div>
                <div>
                    <input id="avatar" name="avatar" type="file" style="font-size:0.8rem;" accept="image/*" onchange="previewAvatar(this)" />
                    <div style="font-size:.7rem;color:var(--ink30,#999);margin-top:.35rem;">PNG, JPG ou WEBP • Max 2 Mo</div>
                </div>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            <script>
            function previewAvatar(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const wrap = document.getElementById('avatarPreviewWrap');
                        const img = document.getElementById('avatarPreviewImg');
                        const def = document.getElementById('avatarPreviewDefault');
                        if (def) def.style.display = 'none';
                        img.src = e.target.result;
                        img.style.display = 'block';
                        wrap.style.transform = 'scale(1.05)';
                        setTimeout(() => wrap.style.transform = 'scale(1)', 300);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }
            </script>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm" style="color: var(--accent3); font-weight: 600;"
                >{{ __('Enregistré.') }}</p>
            @endif
        </div>
    </form>
</section>
