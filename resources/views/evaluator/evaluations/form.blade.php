@extends('layouts.app')

@section('title', 'Form Evaluasi: ' . $employee->nama)
@section('page_title', 'Formulir Evaluasi Tenaga Kerja')
@section('page_subtitle', 'Penilaian berkala kinerja tenaga kerja: ' . $employee->nama . ' (' . $employee->employee_code . ')')

@section('content')
<div class="max-w-4xl space-y-6" x-data="{
    scores: {{ json_encode(array_map(fn($item) => $item['score'] ?? null, $existingAnswers)) }},
    calculateAverage() {
        let values = Object.values(this.scores).filter(v => v !== null && v !== '');
        if (values.length === 0) return '0.00';
        let sum = values.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
        return (sum / values.length).toFixed(2);
    }
}">

    <!-- Employee Summary Banner -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-lg">
                {{ strtoupper(substr($employee->nama, 0, 2)) }}
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-900">{{ $employee->nama }}</h3>
                <p class="text-xs text-slate-500 font-mono">NIK: {{ $employee->employee_code }} &bull; {{ $employee->jabatan ?? '-' }} &bull; {{ $employee->divisi ?? '-' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3 bg-slate-50 px-4 py-2 rounded-lg border border-slate-200 shrink-0">
            <div class="text-right">
                <span class="text-[10px] uppercase font-bold text-slate-500">Estimasi Rata-rata Skor</span>
                <div class="text-xl font-bold text-blue-700 leading-none mt-0.5">
                    <span x-text="calculateAverage()">0.00</span>
                    <span class="text-xs font-normal text-slate-400">/ 5.00</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Evaluation Form -->
    <form method="POST" action="{{ route('evaluator.evaluations.save', $employee->id) }}" class="space-y-6">
        @csrf

        <!-- Questions Section -->
        <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 sm:px-6 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Indikator & Kriteria Penilaian</h4>
                    <p class="text-xs text-slate-500 mt-0.5">Berikan penilaian objektif pada skala 1 (Sangat Kurang) hingga 5 (Sangat Baik)</p>
                </div>
            </div>

            <div class="divide-y divide-slate-200">
                @foreach ($questions as $idx => $q)
                    @php
                        $currentScore = $existingAnswers[$q->id]['score'] ?? old("scores.{$q->id}");
                        $currentNote = $existingAnswers[$q->id]['notes'] ?? old("notes.{$q->id}");
                    @endphp
                    <div class="p-4 sm:p-6 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div class="space-y-1 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                        {{ $q->category }}
                                    </span>
                                    @if($q->is_required)
                                        <span class="text-[10px] font-bold text-rose-600 uppercase tracking-wider">*Wajib</span>
                                    @endif
                                </div>
                                <h5 class="text-sm font-semibold text-slate-900 leading-snug">
                                    {{ $idx + 1 }}. {{ $q->question }}
                                </h5>
                                @if($q->description)
                                    <p class="text-xs text-slate-500">{{ $q->description }}</p>
                                @endif
                            </div>

                            <!-- Rating Score Selector (1 to 5) -->
                            <div class="shrink-0">
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1 sm:text-right">Skor (1 - 5):</label>
                                <div class="inline-flex items-center gap-1.5 p-1 bg-slate-100 rounded-lg border border-slate-200">
                                    @for ($s = $q->min_score; $s <= $q->max_score; $s++)
                                        <label class="cursor-pointer">
                                            <input type="radio" 
                                                   name="scores[{{ $q->id }}]" 
                                                   value="{{ $s }}" 
                                                   x-model="scores[{{ $q->id }}]"
                                                   {{ $currentScore == $s ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-8 h-8 rounded-md flex items-center justify-center text-xs font-bold transition-all peer-checked:bg-blue-600 peer-checked:text-white text-slate-700 hover:bg-slate-200">
                                                {{ $s }}
                                            </div>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- Optional Specific Note per Question -->
                        <div class="pt-1">
                            <input type="text" 
                                   name="notes[{{ $q->id }}]" 
                                   value="{{ $currentNote }}" 
                                   placeholder="Catatan tambahan untuk indikator ini (opsional)..." 
                                   class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-md focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600 text-slate-700">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Qualitative Evaluation Sections -->
        <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xs space-y-4">
            <h4 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3">Catatan Kualitatif & Rekomendasi</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kelebihan -->
                <div>
                    <label for="strengths" class="block text-xs font-semibold text-slate-700 mb-1">
                        Kelebihan / Kekuatan Kerja <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="strengths" 
                              name="strengths" 
                              rows="3" 
                              placeholder="Uraikan hal positif dan keunggulan kinerja selama periode evaluasi..." 
                              class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">{{ old('strengths', $evaluation->strengths ?? '') }}</textarea>
                </div>

                <!-- Area Pengembangan -->
                <div>
                    <label for="areas_for_improvement" class="block text-xs font-semibold text-slate-700 mb-1">
                        Area Pengembangan / Hal yang Perlu Ditingkatkan <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="areas_for_improvement" 
                              name="areas_for_improvement" 
                              rows="3" 
                              placeholder="Uraikan hal-hal yang perlu diperbaiki atau ditingkatkan..." 
                              class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">{{ old('areas_for_improvement', $evaluation->areas_for_improvement ?? '') }}</textarea>
                </div>

                <!-- Rekomendasi -->
                <div>
                    <label for="recommendations" class="block text-xs font-semibold text-slate-700 mb-1">
                        Rekomendasi Tindak Lanjut
                    </label>
                    <textarea id="recommendations" 
                              name="recommendations" 
                              rows="3" 
                              placeholder="Contoh: Diperpanjang kontrak, diikutsertakan pelatihan teknis, promosi..." 
                              class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">{{ old('recommendations', $evaluation->recommendations ?? '') }}</textarea>
                </div>

                <!-- Catatan Umum -->
                <div>
                    <label for="evaluator_notes" class="block text-xs font-semibold text-slate-700 mb-1">
                        Catatan Umum Tambahan
                    </label>
                    <textarea id="evaluator_notes" 
                              name="evaluator_notes" 
                              rows="3" 
                              placeholder="Catatan penutup evaluasi..." 
                              class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">{{ old('evaluator_notes', $evaluation->evaluator_notes ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <a href="{{ route('evaluator.employees.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-md border border-slate-300 transition-colors text-center">
                Kembali ke Daftar
            </a>

            <div class="flex items-center justify-end gap-3">
                <button type="submit" 
                        name="action" 
                        value="draft" 
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                    Simpan Draft Sementara
                </button>

                <button type="submit" 
                        name="action" 
                        value="submit" 
                        onclick="return confirm('PERHATIAN: Setelah disubmit secara resmi, evaluasi tidak dapat diubah lagi tanpa izin Administrator. Apakah Anda yakin ingin mengirim evaluasi ini sekarang?')"
                        class="px-5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
                    Submit Evaluasi Resmi
                </button>
            </div>
        </div>
    </form>

</div>
@endsection
