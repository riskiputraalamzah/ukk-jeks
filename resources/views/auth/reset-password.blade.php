<x-guest-layout>
    <h2 class="text-2xl font-bold text-center mb-3">Reset Password</h2>
    <p class="text-center text-sm mb-5 text-white/90">Masukkan password baru untuk akun kamu</p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-white font-medium mb-2 text-sm">Email</label>
            <input id="email" type="email" name="email"
                class="w-full px-3 py-2.5 bg-white/80 rounded-lg border border-white/30 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-gray-800 placeholder-gray-500 text-sm"
                value="{{ old('email', $request->email) }}" required autofocus>
            @if($errors->has('email'))
                <p class="mt-1 text-red-300 text-xs">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-white font-medium mb-2 text-sm">Password Baru</label>
            <input id="password" type="password" name="password"
                class="w-full px-3 py-2.5 bg-white/80 rounded-lg border border-white/30 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-gray-800 placeholder-gray-500 text-sm"
                required>
            @if($errors->has('password'))
                <p class="mt-1 text-red-300 text-xs">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="mb-5">
            <label for="password_confirmation" class="block text-white font-medium mb-2 text-sm">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                class="w-full px-3 py-2.5 bg-white/80 rounded-lg border border-white/30 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent text-gray-800 placeholder-gray-500 text-sm"
                required>
            @if($errors->has('password_confirmation'))
                <p class="mt-1 text-red-300 text-xs">{{ $errors->first('password_confirmation') }}</p>
            @endif
        </div>

        <button type="submit"
            class="w-full py-2.5 bg-blue-500 hover:bg-blue-600 transition font-semibold rounded-md text-white text-sm shadow-md">
            Reset Password
        </button>
    </form>
</x-guest-layout>