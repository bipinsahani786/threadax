<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin — ThreadAx Control Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex items-center justify-center bg-brand-light">

    <div class="w-full max-w-md p-4">
        <div class="bg-white border border-brand-border rounded-xl p-8 shadow-sm">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center gap-0.5 font-heading text-xl font-extrabold mb-2">
                    <span>THREAD</span><span class="threadax-logo-box">AX</span>
                </div>
                <p class="text-xs font-bold uppercase tracking-widest text-brand-muted">Control Panel</p>
            </div>

            {{-- Errors --}}
            @if ($errors->any())
                <div class="mb-5 p-3 rounded-lg bg-red-50 border border-red-100 text-red-600 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-brand-muted mb-1.5">Email</label>
                    <input type="email" name="email" id="email" required autofocus value="{{ old('email') }}"
                        class="input-field"
                        placeholder="admin@threadax.com">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-brand-muted mb-1.5">Password</label>
                    <input type="password" name="password" id="password" required
                        class="input-field"
                        placeholder="••••••••">
                </div>

                <div class="flex items-center gap-2">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-brand-border text-brand-text focus:ring-brand-text accent-brand-text">
                    <label for="remember" class="text-sm text-brand-muted">Remember me</label>
                </div>

                <button type="submit" class="w-full bg-brand-text text-white font-semibold rounded-md px-4 py-3 text-sm hover:bg-brand-dark/80 transition-colors">
                    Sign In
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-brand-muted mt-4">&copy; {{ date('Y') }} ThreadAx. Admin access only.</p>
    </div>

</body>
</html>
