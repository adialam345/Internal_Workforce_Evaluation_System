@extends('layouts.auth')

@section('title', 'Masuk ke Sistem')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-xl border border-slate-200 shadow-xs">
    <!-- Header -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-blue-600 text-white font-bold text-lg mb-3 shadow-xs">
            WE
        </div>
        <h1 class="text-xl font-bold text-slate-900 tracking-tight">Sistem Evaluasi Tenaga Kerja</h1>
        <p class="text-xs text-slate-500 mt-1">Silakan masuk menggunakan akun terdaftar Anda</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-xs">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   placeholder="nama@workeval.local" 
                   class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
        </div>

        <div>
            <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   required 
                   placeholder="••••••••" 
                   class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center text-xs text-slate-600 cursor-pointer">
                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                <span class="ml-2">Ingat saya di perangkat ini</span>
            </label>
        </div>

        <button type="submit" 
                class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-md shadow-xs transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
            Masuk ke Akun
        </button>
    </form>

    <!-- Quick Credentials Helper for Development -->
    <div class="mt-6 pt-5 border-t border-slate-100 text-xs">
        <p class="font-semibold text-slate-500 mb-2">Akun Default untuk Pengujian:</p>
        <div class="grid grid-cols-2 gap-2">
            <button type="button" 
                    onclick="document.getElementById('email').value='admin@workeval.local'; document.getElementById('password').value='password';"
                    class="p-2 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-left transition-colors">
                <div class="font-bold text-slate-900">Admin</div>
                <div class="text-[11px] text-slate-500 truncate">admin@workeval.local</div>
            </button>

            <button type="button" 
                    onclick="document.getElementById('email').value='budi@workeval.local'; document.getElementById('password').value='password';"
                    class="p-2 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 text-left transition-colors">
                <div class="font-bold text-slate-900">Evaluator (Budi)</div>
                <div class="text-[11px] text-slate-500 truncate">budi@workeval.local</div>
            </button>
        </div>
        <p class="text-[11px] text-slate-400 mt-2 text-center">Password untuk semua akun: <code class="font-mono text-slate-600">password</code></p>
    </div>
</div>
@endsection
