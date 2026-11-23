<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | ClassIQ</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <!-- favicon -->
  <link rel="shortcut icon" href="{{ asset('images/Fevicon.png') }}">
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <div class="bg-white border border-slate-200 rounded-2xl shadow-lg px-8 py-8">

            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('images/Fevicon.png') }}"
                     alt="ClassIQ logo"
                     class="h-16 w-16 mb-2">
                <p class="text-[2.2rem] font-extrabold uppercase leading-none">
                    <span class="text-[#c9942a]">CLASS</span><span class="text-[#45c06b] text-[2.5rem]">I</span><span class="text-[#c9942a]">Q</span>
                </p>
                <p class="text-xs text-slate-400">
                    Admin console
                </p>
            </div>

            <h1 class="text-xl font-semibold text-slate-900 text-center mb-1">
                Admin sign in
            </h1>
            <p class="text-xs text-slate-500 text-center mb-6">
                Sign in with your admin credentials.
            </p>

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-3 py-2 text-xs text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Important: token-based action --}}
            <form method="POST"
                  action="{{ route('admin.login.token.submit', $token) }}"
                  class="space-y-4">
                @csrf

                <div>
                    <label for="username" class="block text-xs font-medium text-slate-700 mb-1">
                        Username
                    </label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900
                               placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="admin_username"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-medium text-slate-700 mb-1">
                        Password
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900
                               placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center justify-between text-xs text-slate-500">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="remember"
                               class="h-3.5 w-3.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span>Remember this device</span>
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center rounded-lg bg-black text-white text-sm font-medium
                           py-2.5 mt-2 shadow-sm hover:bg-zinc-900 transition-colors focus:outline-none
                           focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-100"
                >
                    Log in as admin
                </button>
            </form>

        </div>

        <p class="mt-4 text-[10px] text-center text-slate-400">
            Protected area. Only authorized ClassIQ administrators may log in.
        </p>
    </div>

</body>
</html>
