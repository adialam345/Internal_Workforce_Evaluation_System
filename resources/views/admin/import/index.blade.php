@extends('layouts.app')

@section('title', 'Import Data Tenaga Kerja')
@section('page_title', 'Import Data Tenaga Kerja via Excel')
@section('page_subtitle', 'Unggah berkas spreadsheet (.xlsx, .xls, .csv) untuk memasukkan atau memperbarui data hingga 4.000+ tenaga kerja')

@section('content')
<div class="max-w-3xl space-y-6">

    <!-- Step Guidelines Card -->
    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xs space-y-4">
        <h3 class="text-sm font-bold text-slate-900">Petunjuk & Panduan Import Excel</h3>
        
        <ol class="list-decimal list-inside space-y-2 text-xs text-slate-700 leading-relaxed">
            <li>Gunakan <strong>template resmi</strong> yang telah disediakan agar format kolom sesuai dengan sistem.</li>
            <li>Kolom <strong>NIK</strong> dan <strong>Nama Lengkap</strong> adalah kolom wajib diisi dan NIK harus unik.</li>
            <li>Kolom <strong>Evaluator</strong> dapat diisi dengan <em>Alamat Email</em> atau <em>Nama Lengkap</em> akun Evaluator yang sudah terdaftar di sistem.</li>
            <li>Jika NIK sudah ada di database, data tenaga kerja tersebut akan <strong>diperbarui secara otomatis</strong>.</li>
        </ol>

        <div class="pt-2">
            <a href="{{ route('admin.import.template') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh Template Excel (.xlsx)
            </a>
        </div>
    </div>

    <!-- Import Errors Report if any -->
    @if(session('import_errors'))
        <div class="bg-rose-50 border border-rose-200 rounded-lg p-5">
            <h4 class="text-xs font-bold text-rose-900 mb-2 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Daftar Baris yang Gagal Diimport:
            </h4>
            <div class="max-h-60 overflow-y-auto space-y-1">
                @foreach (session('import_errors') as $err)
                    <div class="text-xs text-rose-700 font-mono bg-white p-2 rounded border border-rose-100">
                        {{ $err }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Upload Form -->
    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xs">
        <form method="POST" action="{{ route('admin.import.process') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="file" class="block text-xs font-semibold text-slate-700 mb-2">
                    Pilih Berkas Excel / CSV <span class="text-rose-500">*</span>
                </label>
                <div class="border-2 border-dashed border-slate-300 hover:border-blue-500 rounded-lg p-6 text-center bg-slate-50 transition-colors">
                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <input type="file" 
                           id="file" 
                           name="file" 
                           accept=".xlsx,.xls,.csv" 
                           required 
                           class="text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                    <p class="text-[11px] text-slate-400 mt-2">Mendukung format .xlsx, .xls, .csv hingga maksimal 10MB</p>
                </div>
            </div>

            <div class="pt-2 flex items-center justify-end">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                    Mulai Proses Import Data
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
