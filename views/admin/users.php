<div class="pb-12 max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-white italic tracking-tighter">User Hub</h1>
            <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.3em] mt-1">Authorized Terminal Operators</p>
        </div>
        
        <div class="flex flex-wrap gap-4">
            <div class="px-6 py-3 rounded-2xl bg-indigo-600/10 border border-indigo-500/20 flex items-center gap-3">
                <i class="fas fa-users text-indigo-400"></i>
                <span class="text-sm font-black text-slate-300 italic"><?php echo count($users); ?> active nodes</span>
            </div>
            <?php 
                $totalBalance = array_sum(array_column($users, 'balance'));
            ?>
            <div class="px-6 py-3 rounded-2xl bg-emerald-600/10 border border-emerald-500/20 flex items-center gap-3">
                <i class="fas fa-wallet text-emerald-400"></i>
                <span class="text-sm font-black text-slate-300 italic">GH₵ <?php echo number_format($totalBalance, 2); ?> Pool</span>
            </div>
        </div>
    </div>

    <!-- Unified Search & Filter Bar -->
    <div class="bg-[#0f172a] rounded-[32px] border border-white/5 p-6 mb-8 shadow-2xl">
        <form action="<?php echo BASE_URL; ?>/admin/users" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-grow w-full">
                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-2">Account Search</label>
                <div class="relative group">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Search by name, email or phone..." class="w-full pl-12 pr-4 py-4 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white text-sm placeholder:font-normal">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-600 group-focus-within:text-indigo-400 transition-colors"></i>
                </div>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-2">Field</label>
                <select name="filter" class="w-full px-4 py-4 bg-white/[0.03] border border-transparent rounded-2xl text-white font-bold text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all cursor-pointer">
                    <option value="email" <?php echo (($_GET['filter'] ?? '') === 'email') ? 'selected' : ''; ?> class="bg-[#0f172a]">Email Address</option>
                    <option value="phone_number" <?php echo (($_GET['filter'] ?? '') === 'phone_number') ? 'selected' : ''; ?> class="bg-[#0f172a]">Phone Number</option>
                </select>
            </div>

            <button type="submit" class="w-full md:w-auto bg-indigo-600 text-white font-black px-10 py-4 rounded-2xl hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-600/10 uppercase text-xs tracking-widest">
                Search
            </button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-[#0f172a] rounded-[40px] border border-white/5 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-white/[0.02]">
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Operator</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Email Link</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Permissions</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Credit Node</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($users)) : ?>
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <i class="fas fa-user-slash text-4xl text-slate-700 mb-4 block"></i>
                                <p class="text-slate-500 font-bold italic text-lg">No matching operator records</p>
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($users as $user) : ?>
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-600/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 font-black italic">
                                            <?php echo strtoupper(substr($user['name'] ?? '?', 0, 1)); ?>
                                        </div>
                                        <span class="text-sm font-black text-white italic"><?php echo htmlspecialchars($user['name'] ?? 'Anonymous'); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm font-bold text-slate-400"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-6 py-5">
                                    <?php 
                                        $role = strtolower($user['role'] ?? 'user');
                                        $roleStyle = $role === 'admin' ? 'bg-purple-500/10 text-purple-400 border-purple-500/20' : 'bg-blue-500/10 text-blue-400 border-blue-500/20';
                                    ?>
                                    <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg border <?php echo $roleStyle; ?>">
                                        <?php echo $role; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="text-sm font-black text-emerald-400 italic">GH₵ <?php echo number_format($user['balance'] ?? 0, 2); ?></span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user)); ?>)" class="p-2.5 rounded-xl bg-white/5 text-slate-400 hover:text-white hover:bg-white/10 transition-all border border-white/5">
                                        <i class="fas fa-user-edit text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit User Modal (Dark) -->
<div id="editUserModal" class="fixed z-[1050] inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-middle bg-[#0f172a] rounded-[40px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-white/10">
            <div class="px-8 py-8">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-white italic tracking-tight">Operator Config</h3>
                    <button onclick="closeEditModal()" class="p-2 rounded-xl bg-white/5 text-slate-400 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form id="editUserForm" class="space-y-6">
                    <input type="hidden" id="editUserId" name="userId">
                    
                    <div>
                        <label for="editUserBalance" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Node Balance (GH₵)</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-emerald-400 font-black italic">GH₵</div>
                            <input type="number" name="balance" id="editUserBalance" step="0.01" min="0" class="w-full pl-16 pr-5 py-5 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-black text-white text-lg" placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label for="editUserRole" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Access Level</label>
                        <select id="editUserRole" name="role" class="w-full px-6 py-5 bg-white/[0.03] border border-transparent rounded-2xl text-white font-black outline-none focus:ring-4 focus:ring-indigo-500/10 cursor-pointer">
                            <option value="user" class="bg-[#0f172a]">Terminal User</option>
                            <option value="admin" class="bg-[#0f172a]">System Admin</option>
                        </select>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-indigo-600 text-white font-black py-5 rounded-[24px] shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all flex items-center justify-center gap-3 group">
                            <span>Update Operator</span>
                            <i class="fas fa-check-circle text-sm group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openEditModal(user) {
        document.getElementById('editUserId').value = user.id;
        document.getElementById('editUserBalance').value = user.balance;
        document.getElementById('editUserRole').value = user.role;
        document.getElementById('editUserModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editUserModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.getElementById('editUserForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing...';

        try {
            const formData = new FormData(this);
            const response = await fetch(`${BASE_URL}/api/user/update/`, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            if (data.success) {
                if(typeof showToast === 'function') showToast('Updated', 'User profile synced successfully', 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                if(typeof showToast === 'function') showToast('Sync Failed', data.message, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalHtml;
            }
        } catch (err) {
            if(typeof showToast === 'function') showToast('Critical', 'System connection interrupted', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        }
    });
</script>