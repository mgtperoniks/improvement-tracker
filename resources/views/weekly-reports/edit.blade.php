@extends('layouts.app')

@section('title', 'Kaizen Tracker | Edit Weekly Plan')

@section('content')
<div class="flex-1 flex justify-center py-8 px-6 overflow-y-auto">
    <div class="w-full max-w-3xl">
        <!-- Form Header -->
        <div class="mb-6 flex justify-between items-end border-b border-slate-100 pb-4">
            <div>
                <div class="flex items-center gap-2 text-slate-400 mb-1">
                    <a href="{{ route('weekly-reports.show', Carbon\Carbon::parse($plan->week_start_date)->format('Y-m-d')) }}" class="hover:text-primary transition-colors flex items-center gap-1 text-[10px] font-bold uppercase tracking-widest">
                        <span class="material-symbols-outlined text-base">arrow_back</span> Kembali ke Report
                    </a>
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-inverse-surface">Edit Weekly Plan</h1>
                <p class="text-sm text-on-surface-variant">Update plan details for Supervisor</p>
            </div>
            <div class="text-[11px] font-medium text-red-600 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">warning</span>
                Internal Correction Mode
            </div>
        </div>

        <form action="{{ route('weekly-reports.plans.update', $plan->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            
            <!-- Row 1: SPV & Title -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-4">
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">Supervisor (SPV)</label>
                    <select name="user_id" required class="w-full bg-surface-container-lowest border-0 border-b border-outline-variant focus:border-primary focus:ring-0 py-2 text-on-surface text-sm transition-all appearance-none cursor-pointer">
                        @foreach($supervisors ?? [] as $spv)
                        <option value="{{ $spv->id }}" {{ $plan->user_id == $spv->id ? 'selected' : '' }}>
                            {{ $spv->name }} ({{ $spv->department_name }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-8">
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">Plan Title</label>
                    <input name="title" required type="text" value="{{ $plan->title }}" class="w-full bg-surface-container-lowest border-0 border-b border-outline-variant focus:border-primary focus:ring-0 px-0 py-2 text-on-surface text-sm transition-all placeholder:text-surface-dim"/>
                </div>
            </div>

            <!-- Row 2: Expected Output -->
            <div class="bg-primary-container/10 p-3 rounded-sm border-l-4 border-primary">
                <div class="flex items-center gap-2 mb-1">
                    <label class="block text-[10px] uppercase tracking-widest text-primary font-bold">Expected Output</label>
                </div>
                <input name="expected_output" required minlength="10" value="{{ $plan->expected_output }}" class="w-full bg-transparent border-0 border-b border-primary/20 focus:border-primary focus:ring-0 px-0 py-1 text-on-surface font-medium text-sm transition-all placeholder:text-slate-400" type="text"/>
            </div>

            <!-- Row 3: Category & Impact -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-2">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-2">Category</label>
                    <div class="flex flex-wrap gap-1">
                        @foreach($categories ?? [] as $cat)
                        <label class="cursor-pointer">
                            <input type="radio" name="category" value="{{ $cat->slug }}" {{ $plan->category == $cat->slug ? 'checked' : '' }} class="hidden peer"/>
                            <span class="px-3 py-1.5 text-[10px] font-bold border border-outline-variant text-on-surface-variant peer-checked:bg-inverse-surface peer-checked:text-surface peer-checked:border-inverse-surface transition-all inline-block rounded-sm">{{ $cat->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-2">Impact Level</label>
                    <div class="flex gap-1">
                        @foreach(['low', 'medium', 'high'] as $level)
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="impact_level" value="{{ $level }}" {{ $plan->impact_level == $level ? 'checked' : '' }} class="hidden peer"/>
                            <span class="w-full text-center px-2 py-1.5 text-[10px] font-bold border border-outline-variant text-on-surface-variant peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-700 transition-all inline-block rounded-sm uppercase">{{ $level }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Row 4: Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">Start Date</label>
                    <input name="week_start_date" required value="{{ Carbon\Carbon::parse($plan->week_start_date)->format('Y-m-d') }}" type="date" class="w-full bg-surface-container-lowest border-0 border-b border-outline-variant focus:border-primary focus:ring-0 px-0 py-1 text-on-surface text-sm transition-all"/>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">End Date</label>
                    <input name="week_end_date" required value="{{ Carbon\Carbon::parse($plan->week_end_date)->format('Y-m-d') }}" type="date" class="w-full bg-surface-container-lowest border-0 border-b border-outline-variant focus:border-primary focus:ring-0 px-0 py-1 text-on-surface text-sm transition-all"/>
                </div>
            </div>

            <!-- Row 5: Hasil (Notes) -->
            <div class="pt-2">
                <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">Hasil (Closing Notes / Lesson Learned)</label>
                <textarea name="notes" rows="3" class="w-full bg-surface-container-lowest border border-outline-variant/30 focus:border-primary focus:ring-0 p-3 text-on-surface text-sm transition-all placeholder:text-surface-dim italic">{{ $plan->notes }}</textarea>
                <p class="text-[9px] text-on-surface-variant mt-1 italic">This was filled during validation. You can correct it here if needed.</p>
            </div>

            <!-- Row 5: Actions -->
            <div class="pt-6 mt-2 border-t border-slate-200/50 flex flex-col sm:flex-row gap-3">
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-primary to-primary-dim text-on-primary font-bold uppercase tracking-[0.1em] text-xs rounded-sm hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    Update Plan Details
                    <span class="material-symbols-outlined text-sm">save</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
