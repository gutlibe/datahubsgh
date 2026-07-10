<div class="pb-12 max-w-5xl mx-auto">
    <div class="mb-10 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-black text-white italic tracking-tighter">Result Checker</h1>
            <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.3em] mt-1">WASSCE & BECE pricing and availability</p>
        </div>
        <a href="<?php echo rtrim(APP_URL, '/'); ?>/admin/result-checker/orders" class="px-6 py-3 bg-white/5 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-white/10 transition">View Orders</a>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <script>document.addEventListener('DOMContentLoaded', () => { if(typeof showToast === 'function') showToast('Updated', '<?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>', 'success'); });</script>
    <?php endif; ?>

    <form action="<?php echo rtrim(APP_URL, '/'); ?>/admin/result-checker" method="POST" class="space-y-8">
        <div class="bg-[#0f172a] rounded-[40px] border border-white/5 p-8 flex items-center justify-between">
            <div>
                <h4 class="text-sm font-black text-white uppercase tracking-widest mb-1">Enable Result Checker Sales</h4>
                <p class="text-[10px] font-bold text-slate-500">Master switch. When off, the feature is hidden everywhere on the site.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                <input type="checkbox" name="enable_result_checker" value="1" class="sr-only peer" <?php echo $globalEnabled ? 'checked' : ''; ?>>
                <div class="w-11 h-6 bg-white/5 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 peer-checked:after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 border border-white/5"></div>
            </label>
        </div>

        <?php foreach ($types as $type): $key = $type['type_key']; ?>
        <div class="bg-[#0f172a] rounded-[40px] border border-white/5 overflow-hidden">
            <div class="p-8 border-b border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-white italic"><?php echo htmlspecialchars($type['display_name']); ?></h3>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Type Key: <?php echo htmlspecialchars($key); ?></p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-4">
                    <input type="checkbox" name="enabled_<?php echo $key; ?>" value="1" class="sr-only peer" <?php echo $type['enabled'] == 1 ? 'checked' : ''; ?>>
                    <div class="w-11 h-6 bg-white/5 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-slate-400 peer-checked:after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 border border-white/5"></div>
                </label>
            </div>

            <div class="p-8 space-y-4">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Quantity-based pricing — leave "Max Qty" empty for "and above"</p>
                <div id="tiers-<?php echo $key; ?>" class="space-y-3">
                    <?php foreach ($type['tiers'] as $tier): ?>
                    <div class="grid grid-cols-4 gap-3 items-center tier-row">
                        <input type="number" name="tier_min_<?php echo $key; ?>[]" value="<?php echo (int)$tier['min_qty']; ?>" placeholder="Min qty" class="px-4 py-3 bg-white/[0.03] border border-white/5 rounded-xl text-sm font-bold text-white outline-none">
                        <input type="number" name="tier_max_<?php echo $key; ?>[]" value="<?php echo $tier['max_qty'] !== null ? (int)$tier['max_qty'] : ''; ?>" placeholder="Max qty (blank = ∞)" class="px-4 py-3 bg-white/[0.03] border border-white/5 rounded-xl text-sm font-bold text-white outline-none">
                        <input type="number" step="0.01" name="tier_price_<?php echo $key; ?>[]" value="<?php echo number_format($tier['unit_price'], 2, '.', ''); ?>" placeholder="Unit price" class="px-4 py-3 bg-white/[0.03] border border-white/5 rounded-xl text-sm font-bold text-white outline-none">
                        <button type="button" onclick="this.closest('.tier-row').remove()" class="text-red-400 hover:text-red-300 text-[10px] font-black uppercase">Remove</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" onclick="addRcTierRow('<?php echo $key; ?>')" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition">
                    + Add Tier
                </button>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="flex justify-end">
            <button type="submit" class="px-12 py-5 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 transition active:scale-95">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
function addRcTierRow(key) {
    const container = document.getElementById('tiers-' + key);
    const row = document.createElement('div');
    row.className = 'grid grid-cols-4 gap-3 items-center tier-row';
    row.innerHTML = `
        <input type="number" name="tier_min_${key}[]" placeholder="Min qty" class="px-4 py-3 bg-white/[0.03] border border-white/5 rounded-xl text-sm font-bold text-white outline-none">
        <input type="number" name="tier_max_${key}[]" placeholder="Max qty (blank = ∞)" class="px-4 py-3 bg-white/[0.03] border border-white/5 rounded-xl text-sm font-bold text-white outline-none">
        <input type="number" step="0.01" name="tier_price_${key}[]" placeholder="Unit price" class="px-4 py-3 bg-white/[0.03] border border-white/5 rounded-xl text-sm font-bold text-white outline-none">
        <button type="button" onclick="this.closest('.tier-row').remove()" class="text-red-400 hover:text-red-300 text-[10px] font-black uppercase">Remove</button>
    `;
    container.appendChild(row);
}
</script>
