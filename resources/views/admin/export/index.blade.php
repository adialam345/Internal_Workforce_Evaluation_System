@extends('layouts.app')

@section('title', 'Export Laporan Evaluasi')
@section('page_title', 'Export Data & Laporan Evaluasi ke Excel')
@section('page_subtitle', 'Unduh rekapitulasi evaluasi seluruh tenaga kerja lengkap dengan skor dan catatan')

@section('content')
<div class="max-w-2xl space-y-6">

    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xs">
        <form method="POST" action="{{ route('admin.export.process') }}" class="space-y-4">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="evaluator_id" class="block text-xs font-semibold text-slate-700 mb-1">
                        Filter Evaluator
                    </label>
                    <select id="evaluator_id" name="evaluator_id" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Semua Evaluator --</option>
                        @foreach ($evaluators as $ev)
                            <option value="{{ $ev->id }}">{{ $ev->name }} ({{ $ev->department ?? 'General' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">
                        Filter Status Evaluasi
                    </label>
                    <select id="status" name="status" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Semua Status Evaluasi --</option>
                        <option value="submitted">Hanya yang Selesai (Submitted)</option>
                        <option value="draft">Hanya yang Masih Draft</option>
                        <option value="un-evaluated">Hanya yang Belum Dievaluasi</option>
                    </select>
                </div>

                <div>
                    <label for="divisi" class="block text-xs font-semibold text-slate-700 mb-1">
                        Filter Divisi
                    </label>
                    <select id="divisi" name="divisi" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Semua Divisi --</option>
                        @foreach ($divisions as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Download Berkas Excel (.xlsx)
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
