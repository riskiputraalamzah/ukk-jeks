<div class="card shadow-sm border-0 rounded-4 p-4 text-center" 
     style="max-width: 380px; margin:auto;">

    <!-- Foto Profil -->
    <div class="mb-3">
        <img src="{{ Auth::user()->avatar 
            ? asset('storage/' . Auth::user()->avatar) 
            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->username) }}"
            class="rounded-circle shadow-sm"
            width="110" height="110" style="object-fit: cover;">
    </div>

    <!-- Nama -->
    <h5 class="fw-bold mb-1">
        {{ Auth::user()->username ?? 'Administrator' }}
    </h5>

    <!-- Email -->
    <p class="mb-1 text-muted">
        <i class="bi bi-envelope"></i>
        {{ Auth::user()->email }}
    </p>

    <!-- No HP -->
    <p class="mb-3 text-muted">
        <i class="bi bi-telephone"></i>
        {{ Auth::user()->no_hp ?? '-' }}
    </p>

    <hr>

    <!-- Tombol Edit -->
    <a href="{{ route('profile.edit') }}" 
       class="d-flex align-items-center gap-2 p-2 rounded-3 text-decoration-none"
       style="color:#0d6efd; font-weight:500;">
        <i class="bi bi-gear"></i> Edit Profil
    </a>

    <hr>

    <!-- Tombol Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" 
                class="d-flex align-items-center gap-2 btn w-100 text-start p-2 rounded-3"
                style="color:#dc3545; font-weight:500;">
            <i class="bi bi-box-arrow-right"></i> Logout
        </button>
    </form>

</div>
