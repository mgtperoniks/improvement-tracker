@extends('layouts.app')

@section('title', 'Kaizen Tracker | Detail Rencana Mingguan')

@section('content')
<div class="p-8 space-y-8">
    <div class="flex items-end justify-between border-b border-slate-100 pb-4">
        <div>
            <div class="flex items-center gap-2 text-slate-400 mb-1">
                <a href="{{ route('weekly-reports.index') }}" class="hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-base">arrow_back</span> Kembali
                </a>
            </div>
            <h2 class="text-xl font-bold tracking-tight text-inverse-surface">Detail Rencana Mingguan</h2>
            <p class="text-sm text-on-surface-variant italic uppercase font-bold tracking-widest mt-1">
                Minggu ke-{{ $stats->week_number }} {{ $stats->year }} ({{ $stats->range }})
            </p>
        </div>
        <div class="flex gap-2">
            <button class="bg-primary text-white px-4 py-2 text-[11px] font-bold uppercase tracking-widest hover:bg-primary-dim transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-base">download</span> Export Excel
            </button>
            <button class="bg-red-600 text-white px-4 py-2 text-[11px] font-bold uppercase tracking-widest hover:bg-red-700 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-base">picture_as_pdf</span> Export PDF
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 text-sm font-bold flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 border border-slate-100">
            <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant block mb-1">Total Rencana</span>
            <span class="text-3xl font-bold tracking-tighter text-inverse-surface">{{ $stats->total }}</span>
        </div>
        <div class="bg-white p-5 border border-slate-100">
            <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant block mb-1">Selesai</span>
            <span class="text-3xl font-bold tracking-tighter text-inverse-surface">{{ $stats->completed }}</span>
        </div>
        <div class="bg-white p-5 border border-slate-100 border-l-4 border-l-primary">
            <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant block mb-1">Rata-rata Skor</span>
            <span class="text-3xl font-bold tracking-tighter text-inverse-surface">{{ number_format($stats->avg_score, 1) }}</span>
        </div>
    </div>

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-[11px] font-bold uppercase tracking-widest text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-base">table_rows</span>
                Detail Rencana Supervisor
            </h3>
        </div>
        <div class="bg-white border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Supervisor</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Rencana Improvement</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant border-x border-slate-100">Hasil</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant text-center w-32 border-r border-slate-100">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant text-center w-32 border-r border-slate-100">Proof of Work</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant text-right w-24 border-r border-slate-100">Score</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($plans as $plan)
                        <tr class="row-interactive transition-colors align-top">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-bold text-inverse-surface">{{ $plan->user->name }}</p>
                                <p class="text-[10px] text-on-surface-variant uppercase">{{ $plan->user->department_name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-800">{{ $plan->title }}</p>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $plan->expected_output }}</p>
                                <div class="flex gap-2 mt-2">
                                    <span class="px-1.5 py-0.5 bg-slate-100 text-[9px] font-bold text-slate-500 uppercase border border-slate-200">
                                        {{ $plan->category }}
                                    </span>
                                    <span class="px-1.5 py-0.5 bg-blue-50 text-[9px] font-bold text-blue-600 uppercase border border-blue-100">
                                        {{ $plan->impact_level }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 border-x border-slate-100 min-w-[200px]">
                                @if($plan->notes)
                                    <p class="text-xs text-slate-700 leading-relaxed italic">{{ $plan->notes }}</p>
                                @else
                                    <span class="text-[10px] text-slate-300 italic">Belum ada hasil</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center border-r border-slate-100">
                                <span class="inline-flex items-center px-2 py-1 text-[10px] font-bold uppercase tracking-tight
                                    @if($plan->status == 'completed') text-green-700 bg-green-50 border border-green-100 
                                    @elseif($plan->status == 'extended') text-yellow-700 bg-yellow-50 border border-yellow-100
                                    @else text-slate-500 bg-slate-50 border border-slate-100 @endif">
                                    {{ $plan->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center border-r border-slate-100">
                                <div class="flex flex-wrap gap-1 justify-center">
                                    @if($plan->proofs->count() > 0)
                                        @foreach($plan->proofs as $proof)
                                            <a href="{{ Storage::disk('public')->url($proof->file_path) }}" target="_blank" class="p-1 bg-slate-50 text-slate-400 hover:text-primary transition-colors border border-slate-200" title="View Proof">
                                                <span class="material-symbols-outlined text-[18px]">attachment</span>
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="text-[10px] text-slate-300 italic">No proof</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right border-r border-slate-100">
                                <span class="text-sm font-bold {{ $plan->score ? 'text-primary' : 'text-slate-300' }}">
                                    {{ $plan->score ? number_format($plan->score->final_score, 1) : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-1">
                                    <a href="{{ route('weekly-reports.plans.edit', $plan->id) }}" class="p-1 text-slate-400 hover:text-primary transition-colors border border-slate-100" title="Edit Plan">
                                        <span class="material-symbols-outlined text-lg">edit</span>
                                    </a>
                                    <form action="{{ route('weekly-reports.plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Hapus rencana ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-slate-400 hover:text-red-600 transition-colors border border-slate-100" title="Delete Plan">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant italic text-sm bg-slate-50/20">
                                <span class="material-symbols-outlined text-4xl mb-2 opacity-20">inventory_2</span>
                                <p>Tidak ada rencana yang ditemukan untuk minggu ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
