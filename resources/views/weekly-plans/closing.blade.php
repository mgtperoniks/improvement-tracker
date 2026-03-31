@extends('layouts.app')

@section('title', 'Kaizen Tracker | Weekly Closing')

@section('content')
<section class="p-8 max-w-7xl mx-auto w-full space-y-6">
    <!-- Breadcrumbs & Heading -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">
                <span>Tracker</span>
                <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                <span class="text-primary">Weekly Closing</span>
            </nav>
            <h2 class="text-2xl font-bold tracking-tight text-inverse-surface">Pending Validations</h2>
        </div>
        <div class="flex gap-2">
            <button class="bg-primary text-white px-8 py-3 rounded-sm text-sm font-bold uppercase tracking-widest hover:brightness-110 shadow-lg shadow-primary/30 transition-all active:scale-95">
                Refresh List
            </button>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="flex gap-4 bg-surface-container-low p-3 rounded-sm border border-outline-variant/20 items-center overflow-x-auto">
        <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant whitespace-nowrap">Filter:</span>
        <select class="bg-white border border-outline-variant/30 rounded-sm text-xs py-1 px-3 focus:ring-1 focus:ring-primary">
            <option>All Supervisors</option>
        </select>
        <button class="ml-auto text-[10px] font-bold uppercase tracking-widest text-on-surface-variant hover:text-primary underline">Clear All</button>
    </div>

    <!-- Compact Stats Bar -->
    <div class="grid grid-cols-4 gap-4">
        <div class="bg-surface-container-lowest p-3 rounded border border-outline-variant/10 flex justify-between items-center">
            <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Plans</span>
            <span class="text-xl font-bold">{{ $stats->total ?? 0 }}</span>
        </div>
        <div class="bg-surface-container-lowest p-3 rounded border border-outline-variant/10 flex justify-between items-center border-l-4 border-l-primary">
            <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Completed</span>
            <span class="text-xl font-bold text-primary">{{ $stats->completed ?? 0 }}</span>
        </div>
        <div class="bg-surface-container-lowest p-3 rounded border border-outline-variant/10 flex justify-between items-center border-l-4 border-l-error">
            <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Pending</span>
            <span class="text-xl font-bold text-error">{{ $stats->pending ?? 0 }}</span>
        </div>
        <div class="bg-surface-container-lowest p-3 rounded border border-outline-variant/10 flex justify-between items-center border-l-4 border-l-secondary">
            <span class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Extended</span>
            <span class="text-xl font-bold text-secondary">{{ $stats->extended ?? 0 }}</span>
        </div>
    </div>

    <!-- Fast Validation List Layout -->
    <div class="bg-white border border-outline-variant/20 rounded-sm divide-y divide-outline-variant/10">
        <!-- Header -->
        <div class="grid grid-cols-12 px-4 py-2 bg-surface-container-low text-[10px] font-bold uppercase tracking-widest text-on-surface-variant items-center">
            <div class="col-span-1">ID</div>
            <div class="col-span-5">Kaizen Plan / Objective</div>
            <div class="col-span-2 text-center">SPV</div>
            <div class="col-span-4 text-center">Validation Action</div>
        </div>

        @forelse($plans ?? [] as $plan)
        <div class="grid grid-cols-12 px-4 py-3 items-center hover:bg-slate-50 transition-colors {{ $plan->status !== 'planned' ? 'opacity-70' : '' }}">
            <div class="col-span-1 text-xs font-mono text-on-surface-variant">#{{ $plan->id }}</div>
            <div class="col-span-5 pr-4">
                <p class="text-sm font-semibold text-inverse-surface truncate">{{ $plan->title }}</p>
                <p class="text-[10px] text-on-surface-variant uppercase font-medium">{{ $plan->category }}</p>
            </div>
            <div class="col-span-2 text-center text-xs text-on-surface-variant">
                {{ $plan->user->name }}
            </div>
            <div class="col-span-4 flex justify-end items-center gap-1">
                @if($plan->status === 'planned')
                <button onclick="openValidationModal({{ $plan->id }}, 'completed')" class="px-3 py-1.5 rounded-sm bg-primary text-white text-[10px] font-bold uppercase tracking-wider shadow-sm">Completed</button>
                <button onclick="openValidationModal({{ $plan->id }}, 'completed_no_impact')" class="px-3 py-1.5 rounded-sm bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wider hover:bg-tertiary hover:text-white transition-all">No Impact</button>
                <button onclick="updateStatus({{ $plan->id }}, 'not_completed')" class="px-3 py-1.5 rounded-sm bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wider hover:bg-error hover:text-white transition-all">Failed</button>
                <button onclick="updateStatus({{ $plan->id }}, 'extended')" class="px-3 py-1.5 rounded-sm bg-surface-container-high text-on-surface-variant text-[10px] font-bold uppercase tracking-wider hover:bg-secondary hover:text-white transition-all">Extend</button>
                @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                    <span class="material-symbols-outlined text-xs mr-1">check_circle</span> {{ str_replace('_', ' ', $plan->status) }}
                </span>
                <button onclick="openValidationModal({{ $plan->id }}, '{{ $plan->status }}')" class="text-[10px] font-bold text-primary uppercase ml-3 hover:underline">Edit</button>
                @endif
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-on-surface-variant italic text-sm">No pending plans found. All caught up!</div>
        @endforelse
    </div>
</section>

<!-- Validation Modal (Simplified for Logic) -->
<div id="validation-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-lg rounded-sm shadow-2xl overflow-hidden">
        <form id="validation-form" onsubmit="event.preventDefault(); submitValidation();" enctype="multipart/form-data">
            <input type="hidden" name="plan_id" id="modal-plan-id">
            <input type="hidden" name="status" id="modal-status">
            <div class="p-6 space-y-4">
                <h3 class="text-xl font-bold text-inverse-surface">Validate Plan Implementation</h3>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-on-surface-variant mb-1">Closing Notes</label>
                    <textarea name="notes" class="w-full bg-white border border-outline-variant/30 rounded-sm text-sm p-2 h-24 resize-none focus:ring-primary" placeholder="Lessons learned..."></textarea>
                </div>
                <div id="proof-container">
                    <div class="text-[10px] font-bold text-error uppercase mb-2 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span> Proof Required
                    </div>
                    <input type="file" name="proofs[]" multiple class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                </div>
            </div>
            <div class="flex border-t border-outline-variant/15">
                <button type="button" onclick="closeValidationModal()" class="flex-1 px-4 py-4 text-xs font-bold uppercase tracking-widest text-on-surface-variant hover:bg-slate-50 transition-colors border-r">Cancel</button>
                <button type="submit" class="flex-1 bg-primary text-white px-4 py-4 text-xs font-bold uppercase tracking-widest hover:brightness-110">Confirm & Save</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openValidationModal(id, status) {
        document.getElementById('modal-plan-id').value = id;
        document.getElementById('modal-status').value = status;
        document.getElementById('validation-modal').classList.remove('hidden');
    }

    function closeValidationModal() {
        document.getElementById('validation-modal').classList.add('hidden');
    }

    function submitValidation() {
        const form = document.getElementById('validation-form');
        const formData = new FormData(form);
        const id = formData.get('plan_id');

        fetch(`./../api/weekly-plans/${id}/status`, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'PATCH'
            }
        }).then(async res => {
            if (!res.ok) {
                const errorData = await res.json();
                throw new Error(errorData.message || 'Validation failed');
            }
            return res.json();
        }).then(data => {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Plan status updated successfully.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }).catch(err => {
            Swal.fire({
                title: 'Gagal!',
                text: err.message,
                icon: 'error',
                confirmButtonColor: '#0061e0',
            });
        });
    }

    function updateStatus(id, status) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin memperbarui status ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0061e0',
            cancelButtonColor: '#ff5c5c',
            confirmButtonText: 'Ya, Perbarui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('status', status);

                fetch(`./../api/weekly-plans/${id}/status`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-HTTP-Method-Override': 'PATCH'
                    }
                }).then(async res => {
                    if (!res.ok) {
                        const errorData = await res.json();
                        throw new Error(errorData.message || 'Update failed');
                    }
                    return res.json();
                }).then(data => {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Status updated.',
                        icon: 'success',
                        timer: 1000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }).catch(err => {
                    Swal.fire({
                        title: 'Gagal!',
                        text: err.message,
                        icon: 'error'
                    });
                });
            }
        });
    }
</script>
@endsection
