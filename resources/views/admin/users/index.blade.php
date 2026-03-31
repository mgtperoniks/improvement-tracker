@extends('layouts.app')

@section('title', 'Kaizen Tracker | Manage Users')

@section('content')
<div class="p-8 space-y-6">
    <div class="flex items-end justify-between border-b border-slate-100 pb-4">
        <div>
            <h2 class="text-xl font-bold tracking-tight text-inverse-surface">Management User</h2>
            <p class="text-sm text-on-surface-variant">Tambah atau ubah data User (Manajer, Kabag, SPV, dll), departemen, dan jabatan.</p>
        </div>
        <button onclick="openModal('addModal')" class="bg-primary text-white px-4 py-2 text-[11px] font-bold uppercase tracking-widest hover:bg-primary-dim transition-all flex items-center gap-2">
            <span class="material-symbols-outlined text-base">person_add</span> Tambah User
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 text-sm font-bold flex items-center gap-2">
        <span class="material-symbols-outlined">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-100 text-red-700 px-4 py-3 text-sm font-bold space-y-1">
        <div class="flex items-center gap-2 mb-1">
            <span class="material-symbols-outlined">error</span>
            <span>Terdapat kesalahan input:</span>
        </div>
        <ul class="list-disc list-inside text-xs font-normal opacity-80">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Kode</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Nama</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant">Departemen</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant text-center">Jabatan</th>
                    <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest text-on-surface-variant text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-bold text-primary">{{ $user->employee_id }}</td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                        <p class="text-[10px] text-slate-400 italic">
                            {{ $user->email ?? 'No email provided' }}
                        </p>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $user->department_name }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 bg-slate-100 text-[10px] font-bold text-slate-600 uppercase border border-slate-200">
                            {{ $user->position }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button 
                                onclick="editUser(this)" 
                                data-user="{{ json_encode($user) }}"
                                data-url="{{ route('admin.users.update', $user->id) }}"
                                class="p-1.5 text-slate-400 hover:text-primary transition-colors border border-slate-100">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </button>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
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
                    <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant italic text-sm">Belum ada data User.</td>
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
        <div class="relative bg-white w-full max-w-lg shadow-2xl p-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 uppercase tracking-tight">Tambah User Baru</h3>
                <button onclick="closeModal('addModal')" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Kode (Employee ID)</label>
                        <input type="text" name="employee_id" required value="{{ old('employee_id') }}" class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary" placeholder="S.942">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Jabatan</label>
                        <select name="position" required class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                            <option value="Manajer" {{ old('position') == 'Manajer' ? 'selected' : '' }}>Manajer</option>
                            <option value="Kabag" {{ old('position') == 'Kabag' ? 'selected' : '' }}>Kabag</option>
                            <option value="SPV" {{ old('position') == 'SPV' || !old('position') ? 'selected' : '' }}>SPV</option>
                            <option value="Wakil SPV" {{ old('position') == 'Wakil SPV' ? 'selected' : '' }}>Wakil SPV</option>
                            <option value="Staff" {{ old('position') == 'Staff' ? 'selected' : '' }}>Staff</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Nama Lengkap</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Departemen</label>
                    <input type="text" name="department_name" required value="{{ old('department_name') }}" class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary" placeholder="PPIC">
                </div>
                <div class="pt-4 border-t border-slate-50 mt-4">
                    <p class="text-[10px] text-slate-400 italic mb-3">Opsional: Isi jika user akan diberikan akses login.</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Email (Opsional)</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Password (Opsional)</label>
                            <input type="password" name="password" class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white py-3 font-bold text-xs uppercase tracking-widest hover:bg-primary-dim transition-all">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
        <div class="relative bg-white w-full max-w-lg shadow-2xl p-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 uppercase tracking-tight">Edit Data User</h3>
                <button onclick="closeModal('editModal')" class="text-slate-400 hover:text-slate-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Kode (Employee ID)</label>
                        <input type="text" name="employee_id" id="edit_employee_id" required class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Jabatan</label>
                        <select name="position" id="edit_position" required class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                            <option value="Manajer">Manajer</option>
                            <option value="Kabag">Kabag</option>
                            <option value="SPV">SPV</option>
                            <option value="Wakil SPV">Wakil SPV</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_name" required class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Departemen</label>
                    <input type="text" name="department_name" id="edit_dept" required class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                </div>
                <div class="pt-4 border-t border-slate-50 mt-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Email (Opsional)</label>
                            <input type="email" name="email" id="edit_email" class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Password (Kosongkan jika tidak diubah)</label>
                            <input type="password" name="password" class="w-full border-slate-200 text-sm focus:border-primary focus:ring-primary">
                        </div>
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary text-white py-3 font-bold text-xs uppercase tracking-widest hover:bg-primary-dim transition-all">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function editUser(el) {
        const user = JSON.parse(el.getAttribute('data-user'));
        const url = el.getAttribute('data-url');
        const form = document.getElementById('editForm');
        
        form.action = url;
        document.getElementById('edit_employee_id').value = user.employee_id;
        document.getElementById('edit_position').value = user.position;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email || '';
        document.getElementById('edit_dept').value = user.department_name;
        openModal('editModal');
    }

    // Handle initial modal display if validation errors exist
    @if($errors->any())
        // Determine which modal to reopen based on context or just reopen Add Modal if desired
        // For simplicity, we can just allow the user to reopen it manually or try to guess.
        // Usually, if it's a 'store' error, it's addModal.
    @endif
</script>
@endsection
