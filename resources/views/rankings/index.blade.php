@extends('layouts.app')

@section('title', 'Kaizen Tracker | Monthly Ranking')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header Section -->
    <section class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-inverse-surface">Performance Scoreboard</h2>
            <p class="text-on-surface-variant text-sm font-medium">{{ now()->format('F Y') }} Fiscal Period</p>
        </div>
        <div class="flex gap-2">
            <div class="px-4 py-2 bg-white border border-outline-variant/20 shadow-sm rounded">
                <p class="text-[9px] uppercase tracking-widest text-on-surface-variant font-bold">AVG EFFICIENCY</p>
                <p class="text-xl font-black text-primary">{{ $avgEfficiency ?? 0 }}%</p>
            </div>
            <div class="px-4 py-2 bg-white border border-outline-variant/20 shadow-sm rounded">
                <p class="text-[9px] uppercase tracking-widest text-on-surface-variant font-bold">TOTAL LOGS</p>
                <p class="text-xl font-black text-secondary">{{ number_format($totalLogs ?? 0) }}</p>
            </div>
        </div>
    </section>

    <!-- Ranking Table Section -->
    <section class="space-y-3">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider flex items-center gap-2 text-on-surface-variant">
                <span class="material-symbols-outlined text-primary text-lg">leaderboard</span>
                Supervisor Rankings
            </h3>
        </div>
        <div class="bg-white overflow-hidden rounded border border-outline-variant/20 shadow-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-outline-variant/15">
                    <tr>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">#</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">SPV Name</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Dept</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Score</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Plans</th>
                        <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Completion</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10 text-sm">
                    @forelse($rankings ?? [] as $index => $rank)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-on-surface">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                @if($index < 3)
                                <span class="material-symbols-outlined {{ $index == 0 ? 'text-[#FFD700]' : ($index == 1 ? 'text-[#C0C0C0]' : 'text-[#CD7F32]') }} text-lg" style="font-variation-settings: 'FILL' 1;">workspace_premium</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 font-semibold">{{ $rank->user->name }}</td>
                        <td class="px-6 py-4 text-on-surface-variant text-xs uppercase">{{ $rank->user->department_name }}</td>
                        <td class="px-6 py-4 tabular-nums font-bold">{{ number_format($rank->total_score, 1) }}</td>
                        <td class="px-6 py-4 tabular-nums">{{ $rank->total_plans }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="font-bold w-10">{{ $rank->rate }}%</span>
                                <div class="flex-1 max-w-[100px] h-1 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary" style="width: {{ $rank->rate }}%"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-on-surface-variant italic text-sm">No ranking data available for this period.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Bottom Section: Category Distribution -->
    <section class="grid grid-cols-1 gap-6">
        <div class="bg-white p-6 rounded border border-outline-variant/20 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-6 flex items-center justify-between">
                Category Distribution
                <span class="text-[10px] font-normal font-mono normal-case italic">By Total Effort</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories ?? [] as $category => $percent)
                <div class="space-y-1">
                    <div class="flex justify-between text-[10px] font-bold uppercase tracking-tighter">
                        <span>{{ ucwords($category) }}</span>
                        <span>{{ $percent }}%</span>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
