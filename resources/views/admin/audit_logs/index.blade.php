@extends('layouts.app')

@section('title', 'Audit Log Sistem')
@section('page_title', 'Audit & Activity Log Sistem')
@section('page_subtitle', 'Catatan riwayat seluruh aktivitas penting sistem, login, penugasan, submit evaluasi, dan otorisasi unlock')

@section('content')
<div class="space-y-4">

    <!-- Search & Filters -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('admin.audit_logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Cari Deskripsi / IP</label>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari kata kunci..." 
                       class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Aktivitas (Action)</label>
                <select name="action" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Aktivitas</option>
                    @foreach ($actions as $act)
                        <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Pengguna (User)</label>
                <select name="user_id" class="w-full px-2.5 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-md focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="">Semua Pengguna</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" {{ $userId == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->role }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="w-full px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-md shadow-xs transition-colors">
                    Filter Log
                </button>
                @if($search || $action || $userId)
                    <a href="{{ route('admin.audit_logs.index') }}" class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-md border border-slate-300 shrink-0">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-200 flex items-center justify-between">
            <span class="text-xs text-slate-600">Menampilkan <strong>{{ $logs->firstItem() ?? 0 }} - {{ $logs->lastItem() ?? 0 }}</strong> dari total <strong>{{ number_format($logs->total()) }}</strong> catatan audit log</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Aktivitas (Action)</th>
                        <th class="py-3 px-4">Deskripsi</th>
                        <th class="py-3 px-4">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50 transition-colors text-xs">
                            <td class="py-3 px-4 font-mono text-slate-500 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3 px-4">
                                @if($log->user)
                                    <div class="font-semibold text-slate-900">{{ $log->user->name }}</div>
                                    <div class="text-[10px] text-slate-500">{{ $log->user->role }}</div>
                                @else
                                    <span class="text-slate-400 italic">Sistem / Tamu</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold 
                                    @if(str_contains($log->action, 'UNLOCK')) bg-rose-50 text-rose-700 border border-rose-200
                                    @elseif(str_contains($log->action, 'SUBMIT') || str_contains($log->action, 'CREATE')) bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif(str_contains($log->action, 'LOGIN')) bg-blue-50 text-blue-700 border border-blue-200
                                    @else bg-slate-100 text-slate-700 border border-slate-200 @endif">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-800 leading-relaxed">
                                {{ $log->description }}
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-500">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-500 text-sm">
                                Belum ada catatan aktivitas sistem yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
