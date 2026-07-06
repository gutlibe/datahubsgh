<div class="pb-12 max-w-5xl mx-auto">
    <!-- Page Header -->
    <div class="mb-10">
        <h1 class="text-4xl font-black text-white italic tracking-tighter">System Config</h1>
        <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.3em] mt-1">Operational feature toggles</p>
    </div>

    <!-- Messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>document.addEventListener('DOMContentLoaded', () => { if(typeof showToast === 'function') showToast('Updated', '<?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>', 'success'); });</script>
    <?php endif; ?>

    <form action="<?php echo rtrim(APP_URL, '/'); ?>/admin/configurations" method="POST" class="space-y-10">
        <!-- Section: Routing Preview -->
        <div class="bg-[#0f172a] rounded-[40px] border border-white/5 p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                            <i class="fas fa-network-wired"></i>
                        </div>
                        <h3 class="text-xl font-black text-white italic tracking-tight">Active Links</h3>
                    </div>
                    <a href="/admin/providers" class="text-[10px] font-black text-indigo-400 uppercase tracking-widest hover:text-indigo-300">Change Routing &rarr;</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php
                    $provider_configs = array_filter($configs, fn($k) => str_ends_with($k, '_provider'), ARRAY_FILTER_USE_KEY);
                    foreach ($provider_configs as $name => $value): 
                    ?>
                    <div class="bg-white/[0.02] p-5 rounded-[24px] border border-white/5 flex justify-between items-center group hover:bg-white/[0.04] transition-colors">
                        <div>
                            <h4 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1"><?php echo str_replace('_provider', '', $name); ?></h4>
                            <p class="text-sm font-black text-white italic"><?php echo $value === 'ckgodsway' ? 'CKLink' : 'HUB'; ?></p>
                        </div>
                        <div class="w-2 h-2 rounded-full <?php echo $value === 'ckgodsway' ? 'bg-purple-500 shadow-[0_0_8px_rgba(168,85,247,0.4)]' : 'bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.4)]'; ?>"></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php
        $descriptions = [
            'auto_processing' => 'Automatically send orders to data providers upon payment confirmation.',
            'enable_direct_purchase' => 'Allow guest users to purchase data bundles without creating an account.',
            'enable_mtn_purchase' => 'Allow users to purchase MTN data bundles.',
            'enable_at_purchase' => 'Allow users to purchase AT data bundles.',
            'enable_telecel_purchase' => 'Allow users to purchase Telecel data bundles.',
            'auto_mtn_processing' => 'Enable automated fulfillment for MTN orders.',
            'auto_at_processing' => 'Enable automated fulfillment for AT orders.',
            'auto_telecel_processing' => 'Enable automated fulfillment for Telecel orders.',
            'verify_phone_before_payment' => 'Check the recipient number via CKGodsway before any balance is debited or a payment session is created. Only applies to networks whose active link is CKGodsway.',
        ];

        $general_configs = array_filter($configs, function($key) {
            $allowed_enable_keys = ['enable_direct_purchase', 'enable_mtn_purchase', 'enable_at_purchase', 'enable_telecel_purchase'];
            if (in_array($key, $allowed_enable_keys)) return true;
            return !str_starts_with($key, 'enable_') && !str_ends_with($key, '_provider');
        }, ARRAY_FILTER_USE_KEY);

        $telegram_configs = array_filter($configs, fn($k) => str_starts_with($k, 'enable_') && str_contains($k, '_alert'), ARRAY_FILTER_USE_KEY);
        ?>

        <!-- Feature Toggles -->
        <div class="space-y-6">
            <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] flex items-center gap-3 ml-2">
                <span class="w-8 h-px bg-indigo-500/30"></span>
                Operational Toggles
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($general_configs as $name => $value): ?>
                <div class="bg-[#0f172a] p-6 rounded-[28px] border border-white/5 hover:border-white/10 transition-all group">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-grow">
                            <h4 class="text-sm font-black text-white italic mb-1 uppercase tracking-tight"><?php echo str_replace('_', ' ', $name); ?></h4>
                            <p class="text-[11px] text-slate-500 font-medium leading-relaxed"><?php echo $descriptions[$name] ?? 'Toggle this system feature.'; ?></p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-1">
                            <input type="hidden" name="configs[<?php echo $name; ?>]" value="0">
                            <input type="checkbox" name="configs[<?php echo $name; ?>]" value="1" class="sr-only peer" <?php echo $value ? 'checked' : ''; ?>>
                            <div class="w-11 h-6 bg-white/5 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 peer-checked:after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 border border-white/5"></div>
                        </label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Alert Systems -->
        <div class="space-y-6">
            <h3 class="text-[10px] font-black text-teal-400 uppercase tracking-[0.3em] flex items-center gap-3 ml-2">
                <span class="w-8 h-px bg-teal-500/30"></span>
                Telegram Relay Controls
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($telegram_configs as $name => $value): ?>
                <div class="bg-[#0f172a] p-6 rounded-[28px] border border-white/5 hover:border-white/10 transition-all group">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-grow">
                            <h4 class="text-sm font-black text-white italic mb-1 uppercase tracking-tight"><?php echo str_replace('_', ' ', $name); ?></h4>
                            <p class="text-[11px] text-slate-500 font-medium leading-relaxed">Broadcast event notification to Telegram nodes.</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-1">
                            <input type="hidden" name="configs[<?php echo $name; ?>]" value="0">
                            <input type="checkbox" name="configs[<?php echo $name; ?>]" value="1" class="sr-only peer" <?php echo $value ? 'checked' : ''; ?>>
                            <div class="w-11 h-6 bg-white/5 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 peer-checked:after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 border border-white/5"></div>
                        </label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="pt-10 flex justify-center border-t border-white/5">
            <button type="submit" class="bg-indigo-600 text-white font-black px-12 py-5 rounded-[24px] shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all flex items-center gap-3 group">
                <span class="text-lg uppercase italic tracking-tight">Sync Configuration</span>
                <i class="fas fa-save text-sm group-hover:scale-110 transition-transform"></i>
            </button>
        </div>
    </form>
</div>