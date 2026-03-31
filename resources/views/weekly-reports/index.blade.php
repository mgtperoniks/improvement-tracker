@extends('layouts.app')

@section('title', 'Kaizen Tracker | Daftar Rencana Mingguan')

@section('content')
<div class="p-8 space-y-6">
    <div class="flex items-end justify-between border-b border-slate-100 pb-4">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-inverse-surface">Daftar Rencana Mingguan</h2>
            <p class="text-sm text-on-surface-variant">Pilih minggu untuk melihat detail laporan rencana perbaikan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3">
        @forelse($weeks as $week)
        <a href="{{ route('weekly-reports.show', $week->week_start_date->format('Y-m-d')) }}" 
           class="group flex items-center bg-white border border-slate-200 p-4 hover:border-primary hover:shadow-sm transition-all">
            <div class="flex items-center gap-6 flex-1">
                <div class="w-12 h-12 bg-slate-50 flex flex-col items-center justify-center border border-slate-100 group-hover:bg-primary-container transition-colors">
                    <span class="text-[10px] font-bold text-primary uppercase">{{ $week->week_start_date->format('M') }}</span>
                    <span class="text-xs font-bold text-slate-400 group-hover:text-primary">{{ $week->week_start_date->format('y') }}</span>
                </div>
                
                <div class="flex-1">
                    <h3 class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors">
                        Minggu ke-{{ $week->week_number }} {{ $week->year }}
                    </h3>
                    <p class="text-[11px] text-slate-500 font-medium">
                        {{ $week->week_start_date->format('d M Y') }} - {{ $week->week_end_date->format('d M Y') }} • {{ $week->total_plans }} Rencana
                    </p>
                </div>

                <div class="hidden md:flex flex-col items-end px-8 border-r border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Total Rencana</p>
                    <p class="text-xl font-bold text-slate-900 tracking-tighter">{{ $week->completed_plans }} / {{ $week->total_plans }}</p>
                </div>

                <div class="hidden md:flex flex-col items-end px-8">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Rata-rata Skor</p>
                    <p class="text-xl font-bold {{ $week->avg_score >= 80 ? 'text-green-600' : ($week->avg_score >= 50 ? 'text-yellow-600' : 'text-red-600') }} tracking-tighter">
                        {{ number_format($week->avg_score, 1) }}
                    </p>
                </div>
            </div>
            
            <div class="ml-4 text-slate-300 group-hover:text-primary transition-colors">
                <span class="material-symbols-outlined">chevron_right</span>
            </div>
        </a>
        @empty
        <div class="bg-white border border-slate-200 p-12 text-center">
            <span class="material-symbols-outlined text-4xl text-slate-200 mb-2">calendar_today</span>
            <p class="text-sm text-slate-500 italic">Belum ada data rencana mingguan yang tersedia.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
