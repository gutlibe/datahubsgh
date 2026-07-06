<div class="pb-12">
    <!-- Page Header -->
    <div class="mb-10">
        <h1 class="text-4xl font-black text-white italic tracking-tighter">Gateways</h1>
        <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.3em] mt-1">Manage API links and routing</p>
    </div>

    <div class="space-y-10">
        <!-- Network Routing -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                    <i class="fas fa-network-wired"></i>
                </div>
                <h3 class="text-xl font-black text-white italic tracking-tight">Active API Links</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php 
                $networks = ['mtn', 'at', 'telecel'];
                $networkConfig = [
                    'mtn' => ['label' => 'MTN GH', 'color' => 'amber'],
                    'at' => ['label' => 'AirtelTigo', 'color' => 'blue'],
                    'telecel' => ['label' => 'Telecel', 'color' => 'red']
                ];
                
                foreach ($networks as $network): 
                    $currentProvider = $routeMap[$network] ?? 'hubnet';
                    $cfg = $networkConfig[$network];
                ?>
                <div class="bg-[#0f172a] rounded-[32px] p-8 border border-white/5 shadow-2xl relative overflow-hidden group hover:border-indigo-500/20 transition-all duration-500">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-<?php echo $cfg['color']; ?>-500/5 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-lg font-black text-white italic"><?php echo $cfg['label']; ?></h4>
                            <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/5 text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                <?php echo $currentProvider === 'ckgodsway' ? 'CKLink' : 'HUB'; ?>
                            </span>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest ml-1">Current Active Link</label>
                            <select onchange="updateRoute('<?php echo $network; ?>', this.value)" class="w-full px-4 py-4 bg-white/[0.03] border border-transparent rounded-2xl text-white font-black text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all cursor-pointer">
                                <option value="hubnet" <?php echo $currentProvider === 'hubnet' ? 'selected' : ''; ?> class="bg-[#0f172a]">Hubnet Link</option>
                                <option value="ckgodsway" <?php echo $currentProvider === 'ckgodsway' ? 'selected' : ''; ?> class="bg-[#0f172a]">CKGodsway Link</option>
                            </select>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- API Configuration -->
        <section>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 border border-emerald-500/20">
                    <i class="fas fa-key text-sm"></i>
                </div>
                <h3 class="text-xl font-black text-white italic tracking-tight">API Key Config</h3>
            </div>

            <div class="bg-[#0f172a] rounded-[40px] border border-white/5 p-8 shadow-2xl relative overflow-hidden">
                <div class="absolute -left-10 -bottom-10 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
                
                <form id="keysForm" class="relative z-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-2">Hubnet API Key</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <i class="fas fa-shield-alt text-slate-600 group-focus-within:text-indigo-400 transition-colors"></i>
                                </div>
                                <input type="text" name="hubnet_key" value="<?php echo htmlspecialchars($hubnetKey ?? ''); ?>" class="w-full pl-12 pr-5 py-5 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white text-sm placeholder:font-normal placeholder:text-slate-600" placeholder="Paste Key Here">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-2">CKGodsway API Key</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                    <i class="fas fa-fingerprint text-slate-600 group-focus-within:text-emerald-400 transition-colors"></i>
                                </div>
                                <input type="text" name="ckgodsway_key" value="<?php echo htmlspecialchars($ckgodswayKey ?? ''); ?>" class="w-full pl-12 pr-5 py-5 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white text-sm placeholder:font-normal placeholder:text-slate-600" placeholder="Paste Key Here">
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white font-black px-10 py-5 rounded-[22px] shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all flex items-center gap-3 group">
                            <span>Save API Keys</span>
                            <i class="fas fa-sync-alt text-xs group-hover:rotate-180 transition-transform duration-700"></i>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<script>
    async function updateRoute(network, providerSlug) {
        try {
            const res = await fetch(`${BASE_URL}/api/admin/providers/switch/`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({network, provider_slug: providerSlug})
            });
            const result = await res.json();
            if (result.success) {
                if (typeof showToast === 'function') showToast('Updated', 'Link changed successfully', 'success');
            } else {
                if (typeof showToast === 'function') showToast('Error', result.message, 'error');
            }
        } catch (err) {
            if (typeof showToast === 'function') showToast('Critical', 'Update failed', 'error');
        }
    }

    document.getElementById('keysForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const originalHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData.entries());

        try {
            const res = await fetch(`${BASE_URL}/api/admin/providers/save-keys/`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            });
            const result = await res.json();
            
            if (result.success) {
                if (typeof showToast === 'function') showToast('Saved', result.message, 'success');
            } else {
                if (typeof showToast === 'function') showToast('Error', result.message, 'error');
            }
        } catch (err) {
            if (typeof showToast === 'function') showToast('Error', 'Save failed', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        }
    });
</script>