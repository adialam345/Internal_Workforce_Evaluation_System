@extends('layouts.app')

@section('title', 'Detail Evaluasi - ' . $evaluation->employee->nama)
@section('page_title', 'Lembar Detail Evaluasi')
@section('page_subtitle', 'Tinjauan hasil penilaian kinerja tenaga kerja dan kontrol status berkas')

@section('content')
<div class="max-w-4xl space-y-6" x-data="{ unlockModal: false }">

    <!-- Top Action Bar -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.evaluations.index') }}" class="p-1.5 text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-md border border-slate-300">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <span class="text-xs font-semibold text-slate-700">Kembali ke Daftar Evaluasi</span>
        </div>

        <div class="flex items-center gap-2">
            @if($evaluation->isSubmitted())
                <a href="{{ route('evaluations.print', $evaluation->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Lembar Resmi
                </a>

                <button type="button" @click="unlockModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold rounded-md border border-rose-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                    Buka Kembali (Unlock Evaluasi)
                </button>
            @else
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                    Status: Draft Sementara (Belum Disubmit oleh Evaluator)
                </span>
            @endif
        </div>
    </div>

    @if($evaluation->unlocked_at)
        <div class="p-4 rounded-lg bg-amber-50 border border-amber-200 text-xs text-amber-800 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div>
                <strong>Catatan Otorisasi:</strong> Evaluasi ini pernah dibuka kembali (unlocked) oleh Administrator ({{ $evaluation->unlockedByUser->name ?? 'Admin' }}) pada {{ $evaluation->unlocked_at->format('d/m/Y H:i') }}.
            </div>
        </div>
    @endif

    <!-- Employee & Evaluator Information Card -->
    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xs space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Tenaga Kerja</span>
                <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $evaluation->employee->nama }}</div>
                <div class="font-mono text-slate-500">NIK: {{ $evaluation->employee->employee_code }}</div>
            </div>

            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Jabatan & Divisi</span>
                <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $evaluation->employee->jabatan ?? '-' }}</div>
                <div class="text-slate-500">{{ $evaluation->employee->divisi ?? '-' }} &bull; {{ $evaluation->employee->unit ?? '-' }}</div>
            </div>

            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Evaluator Penilai</span>
                <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $evaluation->evaluator->name ?? '-' }}</div>
                <div class="text-slate-500">{{ $evaluation->evaluator->department ?? '-' }}</div>
            </div>

            <div>
                <span class="text-slate-500 uppercase font-semibold text-[10px]">Skor Rata-Rata</span>
                <div class="text-2xl font-bold text-blue-700 mt-0.5">
                    {{ $evaluation->total_score !== null ? number_format((float)$evaluation->total_score, 2) : '-' }}
                    <span class="text-xs text-slate-400 font-normal">/ 5.00</span>
                </div>
                <div class="text-[11px] text-slate-500">
                    {{ $evaluation->submitted_at ? 'Disubmit: ' . $evaluation->submitted_at->format('d/m/Y H:i') : 'Draft' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Questions Breakdown Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200">
            <h3 class="text-sm font-bold text-slate-900">Rincian Penilaian per Kriteria</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Kriteria & Indikator Penilaian</th>
                        <th class="py-3 px-4 text-center w-24">Skor (1-5)</th>
                        <th class="py-3 px-4">Catatan Evaluator</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($evaluation->answers as $idx => $ans)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-500 text-xs">
                                {{ $idx + 1 }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-[11px] font-semibold text-blue-700 uppercase tracking-wider block">
                                    {{ $ans->question->category ?? 'Umum' }}
                                </span>
                                <div class="font-medium text-slate-900 mt-0.5">{{ $ans->question->question ?? '-' }}</div>
                                @if($ans->question && $ans->question->description)
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $ans->question->description }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($ans->score !== null)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-sm bg-blue-50 text-blue-700 border border-blue-200">
                                        {{ number_format((float)$ans->score, 0) }}
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-xs text-slate-700">
                                {{ $ans->notes ?? $ans->answer ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-500 text-sm">
                                Belum ada rincian jawaban evaluasi yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Qualitative Notes & Recommendations -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Kelebihan -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <h4 class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Kelebihan / Kekuatan Kerja
            </h4>
            <p class="text-xs text-slate-700 whitespace-pre-line leading-relaxed">
                {{ $evaluation->strengths ?? 'Tidak ada catatan khusus.' }}
            </p>
        </div>

        <!-- Area Perbaikan -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Area Pengembangan / Perbaikan
            </h4>
            <p class="text-xs text-slate-700 whitespace-pre-line leading-relaxed">
                {{ $evaluation->areas_for_improvement ?? 'Tidak ada catatan khusus.' }}
            </p>
        </div>

        <!-- Rekomendasi -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <h4 class="text-xs font-bold text-blue-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Rekomendasi Tindak Lanjut
            </h4>
            <p class="text-xs text-slate-700 whitespace-pre-line leading-relaxed">
                {{ $evaluation->recommendations ?? 'Tidak ada rekomendasi khusus.' }}
            </p>
        </div>

        <!-- Catatan Umum -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                Catatan Umum Evaluator
            </h4>
            <p class="text-xs text-slate-700 whitespace-pre-line leading-relaxed">
                {{ $evaluation->evaluator_notes ?? 'Tidak ada catatan umum.' }}
            </p>
        </div>
    </div>

    <!-- Unlock Confirmation Modal Dialog -->
    <div x-show="unlockModal" 
         x-transition:enter="transition-opacity ease-linear duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @keydown.escape.window="unlockModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" 
         style="display: none;">
        
        <div @click.away="unlockModal = false" class="bg-white rounded-lg border border-slate-200 shadow-xl max-w-md w-full p-6 space-y-4">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-rose-50 text-rose-600 rounded-md shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Buka Kembali (Unlock) Evaluasi?</h3>
                    <p class="text-xs text-slate-500 mt-1">Status evaluasi akan dikembalikan menjadi <strong>Draft</strong> sehingga Evaluator terkait dapat mengubah dan mensubmit ulang nilai.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.evaluations.unlock', $evaluation->id) }}" class="space-y-3">
                @csrf
                <div>
                    <label for="reason" class="block text-xs font-semibold text-slate-700 mb-1">Alasan Pembukaan Kembali <span class="text-rose-500">*</span></label>
                    <textarea id="reason" 
                              name="reason" 
                              required 
                              rows="3" 
                              placeholder="Masukkan alasan atau instruksi revisi untuk dicatat pada audit log..." 
                              class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                    <button type="button" @click="unlockModal = false" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-md border border-slate-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                        Konfirmasi Unlock
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
