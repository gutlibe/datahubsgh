<?php
$statuses = ['accepted', 'processing', 'delivered', 'failed'];
$providersList = ['hubnet', 'ckgodsway'];
$networksList = ['mtn', 'at', 'telecel'];
$modesList = ['auto', 'manual'];
?>

<div class="pb-12">
    <!-- Page Header -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-white italic tracking-tighter">Order Log</h1>
            <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.3em] mt-1">Transaction stream monitor</p>
        </div>
        
        <!-- Unified Controls -->
        <div class="flex flex-wrap items-center gap-3">
            <!-- Bulk Actions (Floating) -->
            <div id="bulk-actions" class="hidden animate-fade-in flex items-center bg-indigo-600 rounded-2xl px-4 py-2 shadow-lg shadow-indigo-500/20 border border-indigo-400/20">
                <span class="text-[10px] font-black text-white uppercase tracking-widest mr-4"><span id="selected-count">0</span> Selected</span>
                <select id="bulk-status" class="bg-white/10 border-none text-white text-xs font-bold rounded-lg focus:ring-0 cursor-pointer mr-2 py-1">
                    <option value="" class="bg-[#0f172a]">Action...</option>
                    <?php foreach ($statuses as $s): ?>
                    <option value="<?= $s ?>" class="bg-[#0f172a]"><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
                <button onclick="applyBulkAction()" class="bg-white text-indigo-600 text-[10px] font-black px-4 py-1.5 rounded-lg uppercase tracking-widest hover:bg-indigo-50 transition-colors">
                    Execute
                </button>
            </div>

            <button onclick="document.getElementById('filter-form').classList.toggle('hidden')" class="p-4 rounded-2xl bg-white/5 border border-white/5 text-slate-400 hover:text-white hover:bg-white/10 transition-all">
                <i class="fas fa-filter"></i>
            </button>
        </div>
    </div>

    <!-- Unified Search & Filter Bar -->
    <div id="filter-form" class="bg-[#0f172a] rounded-[32px] border border-white/5 p-6 mb-8 shadow-2xl">
        <form action="" method="get" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 items-end">
            <div class="lg:col-span-2">
                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-2">Universal Search</label>
                <div class="relative group">
                    <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Ref, Phone or ID..." class="w-full pl-12 pr-4 py-4 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white text-sm placeholder:font-normal">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-600 group-focus-within:text-indigo-400 transition-colors"></i>
                </div>
            </div>

            <div>
                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-2">Status</label>
                <select name="status" class="w-full px-4 py-4 bg-white/[0.03] border border-transparent rounded-2xl text-white font-bold text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all cursor-pointer">
                    <option value="">All Status</option>
                    <?php foreach ($statuses as $s) : ?>
                        <option value="<?= $s ?>" <?= ($status === $s) ? 'selected' : '' ?> class="bg-[#0f172a]"><?= ucfirst($s) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-2">Network</label>
                <select name="network" class="w-full px-4 py-4 bg-white/[0.03] border border-transparent rounded-2xl text-white font-bold text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all cursor-pointer">
                    <option value="">All Nets</option>
                    <?php foreach ($networksList as $n) : ?>
                        <option value="<?= $n ?>" <?= ($network === $n) ? 'selected' : '' ?> class="bg-[#0f172a]"><?= strtoupper($n) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-2">Gateway</label>
                <select name="provider" class="w-full px-4 py-4 bg-white/[0.03] border border-transparent rounded-2xl text-white font-bold text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all cursor-pointer">
                    <option value="">All Providers</option>
                    <?php foreach ($providersList as $p) : ?>
                        <option value="<?= $p ?>" <?= ($provider === $p) ? 'selected' : '' ?> class="bg-[#0f172a]"><?= ucfirst($p) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-2">Execution</label>
                <select name="mode" class="w-full px-4 py-4 bg-white/[0.03] border border-transparent rounded-2xl text-white font-bold text-sm outline-none focus:ring-4 focus:ring-indigo-500/10 transition-all cursor-pointer">
                    <option value="">All Modes</option>
                    <?php foreach ($modesList as $m) : ?>
                        <option value="<?= $m ?>" <?= ($mode === $m) ? 'selected' : '' ?> class="bg-[#0f172a]"><?= ucfirst($m) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="bg-indigo-600 text-white font-black py-4 rounded-2xl hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-600/10 uppercase text-xs tracking-widest">
                Apply
            </button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-[#0f172a] rounded-[40px] border border-white/5 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-white/[0.02]">
                        <th class="px-8 py-5 text-left w-10">
                            <input type="checkbox" id="select-all" class="w-5 h-5 bg-white/5 border-white/10 rounded-lg text-indigo-600 focus:ring-indigo-500/20">
                        </th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Reference</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Package</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Terminal</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Volume</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Timestamp</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($orders)) : ?>
                        <tr>
                            <td colspan="8" class="px-8 py-20 text-center">
                                <i class="fas fa-inbox text-4xl text-slate-700 mb-4 block"></i>
                                <p class="text-slate-500 font-bold italic text-lg">No records matching criteria</p>
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($orders as $order) : ?>
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-8 py-5">
                                    <input type="checkbox" name="order_ids[]" value="<?= $order['id'] ?>" class="order-checkbox w-5 h-5 bg-white/5 border-white/10 rounded-lg text-indigo-600 focus:ring-indigo-500/20">
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-black text-indigo-400 border-b border-indigo-400/20"><?= substr($order['reference'], 0, 10) ?></span>
                                        <button onclick="copyTo('<?= $order['reference'] ?>')" class="text-slate-600 hover:text-white transition-colors">
                                            <i class="far fa-copy text-[10px]"></i>
                                        </button>
                                    </div>
                                    <div class="text-[9px] font-black text-slate-600 mt-1">ID: #<?= $order['id'] ?></div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-black text-white italic"><?= htmlspecialchars($order['product_name']) ?></div>
                                    <div class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-0.5"><?= htmlspecialchars($order['network']) ?></div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-bold text-slate-300"><?= $order['msisdn'] ?></div>
                                    <div class="text-[9px] font-black text-slate-600 uppercase mt-0.5"><?= $order['source'] ?? 'Direct' ?></div>
                                </td>
                                <td class="px-6 py-5 text-sm font-black text-emerald-400 italic">
                                    GH₵ <?= number_format($order['amount'], 2) ?>
                                </td>
                                <td class="px-6 py-5">
                                    <?php 
                                        $statusStyle = match($order['status']) {
                                            'accepted' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                            'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            'processing' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                            'failed' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                            default => 'bg-white/5 text-slate-400 border-white/5'
                                        };
                                    ?>
                                    <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg border <?php echo $statusStyle; ?>">
                                        <?= $order['status'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-[10px] font-bold text-slate-500">
                                    <?= date('M d, H:i', strtotime($order['created_at'])) ?>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex flex-col gap-1">
                                        <select onchange="handleStatusChange(<?= (int)$order['id'] ?>, this.value)" class="bg-white/5 border border-white/5 text-white text-[10px] font-black rounded-xl focus:ring-indigo-500/20 block w-full p-2.5 cursor-pointer uppercase tracking-widest">
                                            <option value="" class="bg-[#0f172a]">MANAGE</option>
                                            <?php foreach ($statuses as $s) : ?>
                                                <?php if ($s !== $order['status']) : ?>
                                                    <option value="<?= $s ?>" class="bg-[#0f172a]"><?= strtoupper($s) ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                        
                                        <div class="flex gap-1 mt-1">
                                            <?php if ($order['status'] === 'pending'): ?>
                                            <button 
                                                id="verify-btn-<?= $order['id'] ?>" 
                                                onclick="verifyPayment('<?= $order['reference'] ?>', <?= $order['id'] ?>)" 
                                                class="flex-1 bg-amber-500/10 hover:bg-amber-500/20 text-amber-500 text-[8px] font-black py-1.5 rounded-lg border border-amber-500/20 uppercase tracking-tighter"
                                            >
                                                Verify Payment
                                            </button>
                                            <?php endif; ?>
                                            
                                            <?php if (in_array($order['status'], ['accepted', 'failed', 'processing']) && $order['mode'] === 'manual'): ?>
                                            <button 
                                                id="fulfill-btn-<?= $order['id'] ?>" 
                                                onclick="triggerFulfillment(<?= (int)$order['id'] ?>)" 
                                                class="flex-1 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 text-[8px] font-black py-1.5 rounded-lg border border-indigo-500/20 uppercase tracking-tighter"
                                            >
                                                Fulfill Order
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (!empty($orders) && $totalPages > 1): ?>
        <div class="px-8 py-6 border-t border-white/5 flex items-center justify-between bg-white/[0.01]">
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Page <?= $currentPage ?> of <?= $totalPages ?></span>
            <div class="flex gap-2">
                <?php 
                $currentPath = rtrim(APP_URL, '/') . '/' . ($_GET['url'] ?? 'admin/history');
                $baseUrl = $currentPath . "?search=" . urlencode($searchTerm) . "&status=" . urlencode($status) . "&network=" . urlencode($network) . "&provider=" . urlencode($provider) . "&mode=" . urlencode($mode);
                ?>
                <?php if ($currentPage > 1): ?>
                    <a href="<?= $baseUrl ?>&page=<?= $currentPage - 1 ?>" class="p-3 rounded-xl bg-white/5 border border-white/5 text-white hover:bg-indigo-600 transition-all"><i class="fas fa-chevron-left"></i></a>
                <?php endif; ?>
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= $baseUrl ?>&page=<?= $currentPage + 1 ?>" class="p-3 rounded-xl bg-white/5 border border-white/5 text-white hover:bg-indigo-600 transition-all"><i class="fas fa-chevron-right"></i></a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function copyTo(text) {
        navigator.clipboard.writeText(text);
        if (typeof showToast === 'function') showToast('Copied', 'Reference stored to clipboard', 'success');
    }

    async function verifyPayment(reference, orderId) {
        if (!confirm('Verify this payment on Paystack?')) return;
        
        try {
            const response = await fetch(`${BASE_URL}/api/order/verify-paystack/`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ reference: reference })
            });
            const data = await response.json();
            if (data.success) {
                if (typeof showToast === 'function') showToast('Verified', data.message, 'success');
                // If payment verified and order accepted, change button to "Fulfill Order"
                const verifyBtn = document.getElementById(`verify-btn-${orderId}`);
                if (verifyBtn) {
                    verifyBtn.outerHTML = `
                        <button 
                            id="fulfill-btn-${orderId}" 
                            onclick="triggerFulfillment(${orderId})" 
                            class="flex-1 bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-400 text-[8px] font-black py-1.5 rounded-lg border border-indigo-500/20 uppercase tracking-tighter"
                        >
                            Fulfill Order
                        </button>
                    `;
                }
                // Optionally, update the status display in the table row without full reload
                const statusSpan = document.querySelector(`#order-${orderId} .status-span`); // Assuming a status span with class status-span
                if (statusSpan) {
                    statusSpan.textContent = 'ACCEPTED';
                    statusSpan.className = 'px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg border bg-green-500/10 text-green-400 border-green-500/20';
                }
                setTimeout(() => location.reload(), 1500); // Reload to ensure consistent state
            } else {
                if (typeof showToast === 'function') showToast('Failed', data.message, 'error');
            }
        } catch (err) {
            if (typeof showToast === 'function') showToast('Error', 'Network communication failed', 'error');
        }
    }

    async function triggerFulfillment(orderId) {
        if (!confirm('Manually trigger fulfillment for this order?')) return;
        
        try {
            const response = await fetch(`${BASE_URL}/api/order/manual-fulfill/`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId })
            });
            const data = await response.json();
            if (data.success) {
                if (typeof showToast === 'function') showToast('Fulfilling', data.message, 'success');
                setTimeout(() => location.reload(), 2000);
            } else {
                if (typeof showToast === 'function') showToast('Failed', data.message, 'error');
            }
        } catch (err) {
            if (typeof showToast === 'function') showToast('Error', 'Network communication failed', 'error');
        }
    }

    async function handleStatusChange(orderId, newStatus) {
        if (!newStatus) return;
        
        try {
            const response = await fetch(`${BASE_URL}/api/order/update-status/`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ order_id: orderId, status: newStatus })
            });
            const data = await response.json();
            if (data.success) {
                if (typeof showToast === 'function') showToast('Success', 'Status updated successfully', 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                if (typeof showToast === 'function') showToast('Failed', data.message, 'error');
            }
        } catch (err) {
            if (typeof showToast === 'function') showToast('Error', 'Network communication failed', 'error');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.order-checkbox');
        const bulkActions = document.getElementById('bulk-actions');
        const selectedCount = document.getElementById('selected-count');

        function updateBulkUI() {
            const checked = document.querySelectorAll('.order-checkbox:checked');
            selectedCount.innerText = checked.length;
            bulkActions.classList.toggle('hidden', checked.length === 0);
        }

        selectAll?.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkUI();
        });

        checkboxes.forEach(cb => cb.addEventListener('change', updateBulkUI));

        window.applyBulkAction = async function() {
            const status = document.getElementById('bulk-status').value;
            const checked = document.querySelectorAll('.order-checkbox:checked');
            const orderIds = Array.from(checked).map(cb => cb.value);

            if (!status || orderIds.length === 0) return;

            try {
                const res = await fetch(`${BASE_URL}/api/order/bulk-update/`, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({order_ids: orderIds, status: status})
                });
                const data = await res.json();
                if (data.success) {
                    if (typeof showToast === 'function') showToast('Success', data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    if (typeof showToast === 'function') showToast('Error', data.message, 'error');
                }
            } catch (err) {
                if (typeof showToast === 'function') showToast('Error', 'Bulk update failed', 'error');
            }
        }
    });
</script>