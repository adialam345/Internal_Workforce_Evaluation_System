@extends('layouts.app')

@section('title', 'Tambah Akun Evaluator')
@section('page_title', 'Tambah Akun Evaluator / Verifikator')
@section('page_subtitle', 'Buat kredensial login baru untuk evaluator departemen')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xs">
        <form method="POST" action="{{ route('admin.evaluators.store') }}" class="space-y-4">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nama Lengkap Evaluator <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           placeholder="Contoh: Budi Santoso" 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                        Alamat Email (Digunakan untuk Login) <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           placeholder="budi@workeval.local" 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                        Kata Sandi Akun <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           placeholder="Minimal 6 karakter" 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="department" class="block text-xs font-semibold text-slate-700 mb-1">Departemen / Divisi</label>
                        <input type="text" 
                               id="department" 
                               name="department" 
                               value="{{ old('department') }}" 
                               placeholder="Contoh: Produksi" 
                               class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}" 
                               placeholder="08123456789" 
                               class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div>
                    <label for="is_active" class="block text-xs font-semibold text-slate-700 mb-1">Status Akun</label>
                    <select id="is_active" name="is_active" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif (Dapat Login & Menilai)</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif (Login Diblokir)</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.evaluators.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-md border border-slate-300 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                    Buat Akun Evaluator
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
