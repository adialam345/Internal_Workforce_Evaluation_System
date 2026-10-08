@extends('layouts.app')

@section('title', 'Kriteria & Pertanyaan Evaluasi')
@section('page_title', 'Bank Kriteria & Pertanyaan Evaluasi')
@section('page_subtitle', 'Konfigurasi pertanyaan, kategori penilaian, skala skor (1-5), dan status aktif kriteria')

@section('content')
<div class="space-y-4" x-data="{ 
    createModal: false, 
    editModal: false,
    editData: { id: '', category: '', question: '', description: '', type: 'rating', min_score: 1, max_score: 5, order: 0, is_required: 1, is_active: 1 },
    openEdit(q) {
        this.editData = { ...q };
        this.editModal = true;
    }
}">

    <!-- Top Action Bar -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-xs flex items-center justify-between">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Daftar Indikator Penilaian</h3>
            <p class="text-xs text-slate-500 mt-0.5">Semua kriteria aktif akan muncul pada formulir penilaian evaluator</p>
        </div>
        <button type="button" @click="createModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kriteria Baru
        </button>
    </div>

    <!-- Questions Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4 w-12 text-center">Urutan</th>
                        <th class="py-3 px-4">Kategori & Pertanyaan</th>
                        <th class="py-3 px-4 text-center">Tipe & Skala</th>
                        <th class="py-3 px-4 text-center">Wajib Diisi</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($questions as $q)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-700 text-xs">
                                {{ $q->order }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="text-[10px] font-bold text-blue-700 uppercase tracking-wider bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200">
                                    {{ $q->category }}
                                </span>
                                <div class="font-semibold text-slate-900 mt-1">{{ $q->question }}</div>
                                @if($q->description)
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $q->description }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-700">
                                <span class="font-mono">{{ strtoupper($q->type) }}</span> ({{ $q->min_score }}-{{ $q->max_score }})
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($q->is_required)
                                    <span class="text-xs font-semibold text-rose-600">Ya</span>
                                @else
                                    <span class="text-xs text-slate-400">Opsional</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($q->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button type="button" @click="openEdit({{ json_encode($q) }})" class="p-1 text-slate-600 hover:text-blue-600" title="Edit Kriteria">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.questions.toggle', $q->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1 text-xs font-semibold {{ $q->is_active ? 'text-amber-600 hover:text-amber-800' : 'text-emerald-600 hover:text-emerald-800' }}" title="{{ $q->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            @if($q->is_active)
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                    @if($q->answers_count == 0)
                                        <form method="POST" action="{{ route('admin.questions.destroy', $q->id) }}" class="inline" onsubmit="return confirm('Hapus kriteria penilaian ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-slate-400 hover:text-rose-600" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500 text-sm">
                                Belum ada kriteria pertanyaan evaluasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Question Modal -->
    <div x-show="createModal" 
         x-transition
         @keydown.escape.window="createModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" 
         style="display: none;">
        
        <div @click.away="createModal = false" class="bg-white rounded-lg border border-slate-200 shadow-xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Tambah Kriteria Evaluasi Baru</h3>
                <button @click="createModal = false" class="text-slate-400 hover:text-slate-700">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.questions.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Penilaian <span class="text-rose-500">*</span></label>
                    <input type="text" name="category" required placeholder="Contoh: Kedisiplinan & Integritas" class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pertanyaan / Indikator <span class="text-rose-500">*</span></label>
                    <textarea name="question" required rows="2" placeholder="Pertanyaan atau indikator evaluasi..." class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi / Petunjuk Pengisian</label>
                    <input type="text" name="description" placeholder="Panduan singkat bagi evaluator..." class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tipe Input</label>
                        <select name="type" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:ring-2 focus:ring-blue-600 focus:outline-none">
                            <option value="rating">Rating (Skala)</option>
                            <option value="textarea">Textarea (Uraian)</option>
                            <option value="text">Text (Singkat)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Skor Min / Max</label>
                        <div class="flex items-center gap-1">
                            <input type="number" name="min_score" value="1" min="1" class="w-full px-2 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md text-center">
                            <span class="text-xs text-slate-400">-</span>
                            <input type="number" name="max_score" value="5" max="10" class="w-full px-2 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md text-center">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Urut</label>
                        <input type="number" name="order" value="1" min="0" class="w-full px-2 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md text-center">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Wajib Diisi</label>
                        <select name="is_required" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md">
                            <option value="1">Ya, Wajib</option>
                            <option value="0">Opsional</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status Kriteria</label>
                        <select name="is_active" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="createModal = false" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-md border border-slate-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs">
                        Simpan Kriteria
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Question Modal -->
    <div x-show="editModal" 
         x-transition
         @keydown.escape.window="editModal = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs" 
         style="display: none;">
        
        <div @click.away="editModal = false" class="bg-white rounded-lg border border-slate-200 shadow-xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Edit Kriteria Evaluasi</h3>
                <button @click="editModal = false" class="text-slate-400 hover:text-slate-700">&times;</button>
            </div>

            <form method="POST" :action="'{{ url('admin/questions') }}/' + editData.id" class="space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Penilaian <span class="text-rose-500">*</span></label>
                    <input type="text" name="category" x-model="editData.category" required class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pertanyaan / Indikator <span class="text-rose-500">*</span></label>
                    <textarea name="question" x-model="editData.question" required rows="2" class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi / Petunjuk Pengisian</label>
                    <input type="text" name="description" x-model="editData.description" class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tipe Input</label>
                        <select name="type" x-model="editData.type" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md">
                            <option value="rating">Rating (Skala)</option>
                            <option value="textarea">Textarea (Uraian)</option>
                            <option value="text">Text (Singkat)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Skor Min / Max</label>
                        <div class="flex items-center gap-1">
                            <input type="number" name="min_score" x-model="editData.min_score" min="1" class="w-full px-2 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md text-center">
                            <span class="text-xs text-slate-400">-</span>
                            <input type="number" name="max_score" x-model="editData.max_score" max="10" class="w-full px-2 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md text-center">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Urut</label>
                        <input type="number" name="order" x-model="editData.order" min="0" class="w-full px-2 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md text-center">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Wajib Diisi</label>
                        <select name="is_required" x-model="editData.is_required" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md">
                            <option :value="1">Ya, Wajib</option>
                            <option :value="0">Opsional</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Status Kriteria</label>
                        <select name="is_active" x-model="editData.is_active" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md">
                            <option :value="1">Aktif</option>
                            <option :value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                    <button type="button" @click="editModal = false" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-md border border-slate-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-md shadow-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
