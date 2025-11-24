<x-guest-layout>

    <h2 class="text-2xl font-bold text-center mb-4">Reset Password</h2>
    <p class="text-center text-sm mb-6">Masukkan password baru untuk akun kamu</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div class="mb-4">
            <x-input-label for="email" value="Email" class="text-white" />
            <x-text-input id="email" type="email" name="email"
                class="block mt-1 w-full bg-white/70"
                :value="old('email', $request->email)" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" value="Password Baru" class="text-white" />
            <x-text-input id="password" type="password" name="password"
                class="block mt-1 w-full bg-white/70"
                required />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" class="text-white" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                class="block mt-1 w-full bg-white/70"
                required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-300" />
        </div>

        <button
            class="w-full py-2 bg-blue-600 hover:bg-blue-700 transition font-semibold rounded-md">
            Reset Password
        </button>
    </form>

</x-guest-layout>
