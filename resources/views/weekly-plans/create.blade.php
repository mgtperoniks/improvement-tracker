@extends('layouts.app')

@section('title', 'Kaizen Tracker | New Weekly Plan')

@section('content')
<!-- Main Content -->
<div class="flex-1 flex justify-center py-8 px-6 overflow-y-auto">
    <div class="w-full max-w-3xl">
        <!-- Form Header -->
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-inverse-surface">New Weekly Plan</h1>
                <p class="text-sm text-on-surface-variant">Define objectives for Week {{ now()->format('W') }}</p>
            </div>
            <div class="text-[11px] font-medium text-primary flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">lightbulb</span>
                2–3 plans per week recommended
            </div>
        </div>

        <form action="{{ route('api.weekly-plans.store') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Row 1: SPV & Title -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-4">
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">Supervisor (SPV)</label>
                    <select name="user_id" required class="w-full bg-surface-container-lowest border-0 border-b border-outline-variant focus:border-primary focus:ring-0 py-2 text-on-surface text-sm transition-all appearance-none cursor-pointer">
                        <option value="">Select SPV...</option>
                        @foreach($supervisors ?? [] as $spv)
                        <option value="{{ $spv->id }}">{{ $spv->name }} ({{ $spv->department_name }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-8">
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">Plan Title</label>
                    <input name="title" required type="text" class="w-full bg-surface-container-lowest border-0 border-b border-outline-variant focus:border-primary focus:ring-0 px-0 py-2 text-on-surface text-sm transition-all placeholder:text-surface-dim" placeholder="e.g., Reduction of Cycle Time"/>
                </div>
            </div>

            <!-- Row 2: Description -->
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">Description (Optional)</label>
                <textarea name="description" class="w-full bg-surface-container-lowest border-0 border-b border-outline-variant focus:border-primary focus:ring-0 px-0 py-1 text-on-surface text-sm transition-all placeholder:text-surface-dim resize-none" placeholder="Context/rationale..." rows="1"></textarea>
            </div>

            <!-- Row 3: Expected Output (Highlighted) -->
            <div class="bg-primary-container/10 p-3 rounded-sm border-l-4 border-primary">
                <div class="flex items-center gap-2 mb-1">
                    <label class="block text-[10px] uppercase tracking-widest text-primary font-bold">Expected Output</label>
                    <span class="text-[9px] bg-primary text-on-primary px-1 rounded-full uppercase">Required</span>
                </div>
                <input name="expected_output" required minlength="10" class="w-full bg-transparent border-0 border-b border-primary/20 focus:border-primary focus:ring-0 px-0 py-1 text-on-surface font-medium text-sm transition-all placeholder:text-slate-400" placeholder="e.g., 15% decrease in idle time" type="text"/>
            </div>

            <!-- Row 4: Category & Impact -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-2">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-2">Category</label>
                    <div class="flex flex-wrap gap-1">
                        <label class="cursor-pointer">
                            <input checked type="radio" name="category" value="improvement" class="hidden peer"/>
                            <span class="px-3 py-1.5 text-[10px] font-bold border border-outline-variant text-on-surface-variant peer-checked:bg-inverse-surface peer-checked:text-surface peer-checked:border-inverse-surface transition-all inline-block rounded-sm">Improvement</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="category" value="problem" class="hidden peer"/>
                            <span class="px-3 py-1.5 text-[10px] font-bold border border-outline-variant text-on-surface-variant peer-checked:bg-inverse-surface peer-checked:text-surface peer-checked:border-inverse-surface transition-all inline-block rounded-sm">Problem Solving</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="category" value="maintenance" class="hidden peer"/>
                            <span class="px-3 py-1.5 text-[10px] font-bold border border-outline-variant text-on-surface-variant peer-checked:bg-inverse-surface peer-checked:text-surface peer-checked:border-inverse-surface transition-all inline-block rounded-sm">Maintenance</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-2">Impact Level</label>
                    <div class="flex gap-1">
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="impact_level" value="low" class="hidden peer"/>
                            <span class="w-full text-center px-2 py-1.5 text-[10px] font-bold border border-outline-variant text-on-surface-variant peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-700 transition-all inline-block rounded-sm">Low</span>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input checked type="radio" name="impact_level" value="medium" class="hidden peer"/>
                            <span class="w-full text-center px-2 py-1.5 text-[10px] font-bold border border-outline-variant text-on-surface-variant peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-700 transition-all inline-block rounded-sm">Med</span>
                        </label>
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="impact_level" value="high" class="hidden peer"/>
                            <span class="w-full text-center px-2 py-1.5 text-[10px] font-bold border border-outline-variant text-on-surface-variant peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-700 transition-all inline-block rounded-sm">High</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Row 5: Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">Start Date</label>
                    <input name="week_start_date" required value="{{ now()->startOfWeek()->format('Y-m-d') }}" type="date" class="w-full bg-surface-container-lowest border-0 border-b border-outline-variant focus:border-primary focus:ring-0 px-0 py-1 text-on-surface text-sm transition-all"/>
                </div>
                <div>
                    <label class="block text-[10px] uppercase tracking-widest text-on-surface-variant font-bold mb-1">End Date</label>
                    <input name="week_end_date" required value="{{ now()->endOfWeek()->format('Y-m-d') }}" type="date" class="w-full bg-surface-container-lowest border-0 border-b border-outline-variant focus:border-primary focus:ring-0 px-0 py-1 text-on-surface text-sm transition-all"/>
                </div>
            </div>

            <!-- Row 6: Actions -->
            <div class="pt-6 mt-2 border-t border-slate-200/50 flex flex-col sm:flex-row gap-3">
                <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-primary to-primary-dim text-on-primary font-bold uppercase tracking-[0.1em] text-xs rounded-sm hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    Submit Plan
                    <span class="material-symbols-outlined text-sm">send</span>
                </button>
            </div>
            <p class="text-center text-on-surface-variant text-[9px] font-medium opacity-60">
                Plans tracked via Global Kaizen System.
            </p>
        </form>
    </div>
</div>
@endsection
