@extends('layouts.app')

@section('title', 'Kaizen Tracker | Manage Categories')

@section('content')
<div class="p-8 space-y-6">
    <div class="flex items-end justify-between border-b border-slate-100 pb-4">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-inverse-surface">Setting Categories</h2>
            <p class="text-sm text-on-surface-variant">Manage improvement plan categories.</p>
        </div>
        <button onclick="openModal('addModal')" class="bg-primary text-white px-4 py-2 text-[11px] font-bold uppercase tracking-widest hover:bg-primary-dim transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-base">add_circle</span> Tambah Category
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 text-sm font-bold flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <div class="max-w-2xl bg-white border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Nama Category</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Slug</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($categories as $category)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-bold text-slate-800">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-xs text-slate-500 font-mono">{{ $category->slug }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button 
                                onclick="editCategory(this)" 
                                data-category="{{ json_encode($category) }}"
                                data-url="{{ route('admin.categories.update', $category->id) }}"
                                class="p-1.5 text-slate-400 hover:text-primary transition-colors border border-slate-100">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </button>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 transition-colors border border-slate-100">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center text-on-surface-variant italic text-sm">Belum ada category.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('addModal')"></div>
        <div class="relative bg-white w-full max-w-sm shadow-2xl p-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 uppercase tracking-tight">Tambah Category</h3>
                <button onclick="closeModal('addModal')" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Nama Category</label>
                    <input type="text" name="name" required class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary" placeholder="Contoh: Improvement">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white py-3 font-bold text-xs uppercase tracking-widest hover:bg-primary-dim transition-all">Simpan Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
        <div class="relative bg-white w-full max-w-sm shadow-2xl p-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 uppercase tracking-tight">Edit Category</h3>
                <button onclick="closeModal('editModal')" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="space-y-1">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Nama Category</label>
                    <input type="text" name="name" id="edit_name" required class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white py-3 font-bold text-xs uppercase tracking-widest hover:bg-primary-dim transition-all">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function editCategory(el) {
        const category = JSON.parse(el.getAttribute('data-category'));
        const url = el.getAttribute('data-url');
        const form = document.getElementById('editForm');
        
        form.action = url;
        document.getElementById('edit_name').value = category.name;
        openModal('editModal');
    }
</script>
@endsection
