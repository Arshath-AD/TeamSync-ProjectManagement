<x-guest-layout>

    {{-- Heading --}}
    <div class="mb-6">
        <h2 class="text-base font-semibold" style="color:var(--text);letter-spacing:-0.02em;">Sign in to your account</h2>
        <p class="text-xs mt-1" style="color:var(--text-2);">Welcome back — enter your credentials below.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <label class="ts-label" for="email">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="ts-input" required autofocus autocomplete="username"
                   placeholder="you@example.com">
            @error('email')
                <p class="mt-1.5 text-xs" style="color:var(--danger)">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label class="ts-label" style="margin-bottom:0;" for="password">Password</label>
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs transition-colors" style="color:var(--text-2);"
                       onmouseover="this.style.color='var(--accent)';" onmouseout="this.style.color='var(--text-2)';">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <input id="password" type="password" name="password"
                       class="ts-input pr-10" required autocomplete="current-password"
                       placeholder="••••••••">
                <button type="button" class="pw-toggle-btn" data-pw-toggle="password" aria-label="Toggle password visibility">
                    <svg class="eye-closed w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                    <svg class="eye-open w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1.5 text-xs" style="color:var(--danger)">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember --}}
        <label class="inline-flex items-center gap-2.5 cursor-pointer">
            <input id="remember_me" type="checkbox" name="remember"
                   class="rounded" style="border-color:var(--border-2);background:var(--surface-2);color:var(--accent);width:14px;height:14px;">
            <span class="text-xs" style="color:var(--text-2);">Remember me for 30 days</span>
        </label>

        {{-- Submit --}}
        <button type="submit" class="ts-btn-primary w-full justify-center mt-2" style="height:38px;">
            Sign In
        </button>

        {{-- Register link --}}
        <p class="text-center text-xs pt-1" style="color:var(--text-2);">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium transition-colors" style="color:var(--accent);"
               onmouseover="this.style.color='var(--accent-hov)';" onmouseout="this.style.color='var(--accent)';">
                Create one free
            </a>
        </p>
    </form>

</x-guest-layout>
