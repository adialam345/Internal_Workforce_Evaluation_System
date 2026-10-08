@extends('layouts.app')

@section('title', 'Edit Akun Evaluator')
@section('page_title', 'Edit Akun Evaluator')
@section('page_subtitle', 'Perbarui profil, kata sandi, atau departemen evaluator')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xs">
        <form method="POST" action="{{ route('admin.evaluators.update', $evaluator->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nama Lengkap Evaluator <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $evaluator->name) }}" 
                           required 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                        Alamat Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $evaluator->email) }}" 
                           required 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                        Kata Sandi Baru <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin mengubah kata sandi)</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Minimal 6 karakter..." 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="department" class="block text-xs font-semibold text-slate-700 mb-1">Departemen / Divisi</label>
                        <input type="text" 
                               id="department" 
                               name="department" 
                               value="{{ old('department', $evaluator->department) }}" 
                               class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $evaluator->phone) }}" 
                               class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div>
                    <label for="is_active" class="block text-xs font-semibold text-slate-700 mb-1">Status Akun</label>
                    <select id="is_active" name="is_active" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="1" {{ old('is_active', $evaluator->is_active) ? 'selected' : '' }}>Aktif (Dapat Login & Menilai)</option>
                        <option value="0" {{ !old('is_active', $evaluator->is_active) ? 'selected' : '' }}>Nonaktif (Login Diblokir)</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.evaluators.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-md border border-slate-300 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
