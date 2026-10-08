@extends('layouts.app')

@section('title', 'Edit Tenaga Kerja')
@section('page_title', 'Edit Data Tenaga Kerja')
@section('page_subtitle', 'Perbarui detail data tenaga kerja atau ubah penugasan evaluator')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-xs">
        <form method="POST" action="{{ route('admin.employees.update', $employee->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- NIK -->
                <div>
                    <label for="employee_code" class="block text-xs font-semibold text-slate-700 mb-1">
                        NIK / Kode Tenaga Kerja <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="employee_code" 
                           name="employee_code" 
                           value="{{ old('employee_code', $employee->employee_code) }}" 
                           required 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama', $employee->nama) }}" 
                           required 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Jabatan -->
                <div>
                    <label for="jabatan" class="block text-xs font-semibold text-slate-700 mb-1">Jabatan / Posisi</label>
                    <input type="text" 
                           id="jabatan" 
                           name="jabatan" 
                           value="{{ old('jabatan', $employee->jabatan) }}" 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Divisi -->
                <div>
                    <label for="divisi" class="block text-xs font-semibold text-slate-700 mb-1">Divisi</label>
                    <input type="text" 
                           id="divisi" 
                           name="divisi" 
                           value="{{ old('divisi', $employee->divisi) }}" 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Departemen -->
                <div>
                    <label for="department" class="block text-xs font-semibold text-slate-700 mb-1">Departemen</label>
                    <input type="text" 
                           id="department" 
                           name="department" 
                           value="{{ old('department', $employee->department) }}" 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Unit Kerja -->
                <div>
                    <label for="unit" class="block text-xs font-semibold text-slate-700 mb-1">Unit Kerja / Shift</label>
                    <input type="text" 
                           id="unit" 
                           name="unit" 
                           value="{{ old('unit', $employee->unit) }}" 
                           class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                </div>

                <!-- Penugasan Evaluator -->
                <div>
                    <label for="evaluator_id" class="block text-xs font-semibold text-slate-700 mb-1">Penugasan Evaluator</label>
                    <select id="evaluator_id" name="evaluator_id" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Belum Ditugaskan --</option>
                        @foreach ($evaluators as $ev)
                            <option value="{{ $ev->id }}" {{ old('evaluator_id', $employee->evaluator_id) == $ev->id ? 'selected' : '' }}>
                                {{ $ev->name }} ({{ $ev->department ?? 'General' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Kepegawaian -->
                <div>
                    <label for="status" class="block text-xs font-semibold text-slate-700 mb-1">Status Kepegawaian</label>
                    <select id="status" name="status" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="active" {{ old('status', $employee->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ old('status', $employee->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end gap-3">
                <a href="{{ route('admin.employees.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-md border border-slate-300 transition-colors">
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
