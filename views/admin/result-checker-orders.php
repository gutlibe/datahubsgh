<div class="pb-12">
    <div class="mb-10 flex items-center gap-4">
        <a href="<?php echo rtrim(APP_URL, '/'); ?>/admin/result-checker" class="h-12 w-12 bg-[#0f172a] border border-white/5 rounded-2xl flex items-center justify-center text-slate-400 hover:text-white transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-4xl font-black text-white italic tracking-tighter">Result Checker Orders</h1>
        </div>
    </div>

    <form method="GET" class="flex flex-col sm:flex-row gap-3 mb-8">
        <input type="text" name="q" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search reference or phone..." class="flex-1 px-5 py-3 bg-[#0f172a] border border-white/5 rounded-2xl text-sm font-bold text-white outline-none">
        <select name="status" class="px-5 py-3 bg-[#0f172a] border border-white/5 rounded-2xl text-sm font-bold text-white outline-none">
            <option value="">All Status</option>
            <?php foreach (['pending', 'processing', 'completed', 'failed'] as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo $status === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl">Filter</button>
    </form>

    <div class="bg-[#0f172a] rounded-[32px] border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/[0.02]">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Reference</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Type / Qty</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Recipient</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Amount</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="6" class="px-6 py-16 text-center text-slate-500 text-xs font-bold uppercase">No orders found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order):
                            $statusColors = [
                                'pending' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                'processing' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                'completed' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                'failed' => 'bg-red-500/10 text-red-400 border-red-500/20'
                            ];
                            $sc = $statusColors[$order['status']] ?? 'bg-white/5 text-slate-400 border-white/5';
                        ?>
                        <tr class="hover:bg-white/[0.02]">
                            <td class="px-6 py-4 font-mono text-xs font-bold text-white"><?php echo htmlspecialchars($order['reference']); ?></td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-white"><?php echo htmlspecialchars($order['checker_type']); ?></span>
                                <span class="text-xs text-slate-500">&times; <?php echo (int)$order['quantity']; ?></span>
                            </td>
                            <td class="px-6 py-4 font-mono text-xs text-slate-400"><?php echo htmlspecialchars($order['msisdn']); ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 rounded text-[9px] font-black uppercase border <?php echo $sc; ?>"><?php echo htmlspecialchars($order['status']); ?></span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-white">GH₵<?php echo number_format($order['amount'], 2); ?></td>
                            <td class="px-6 py-4 text-right text-slate-500 text-xs"><?php echo date('d M, Y H:i', strtotime($order['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="flex items-center justify-between mt-6">
        <span class="text-xs text-slate-500 font-bold uppercase">Page <?php echo $page; ?> of <?php echo $totalPages; ?></span>
        <div class="flex gap-2">
            <?php if ($page > 1): ?><a href="?page=<?php echo $page - 1; ?>&q=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>" class="px-4 py-2 bg-[#0f172a] border border-white/5 rounded-xl text-xs font-black text-white">Previous</a><?php endif; ?>
            <?php if ($page < $totalPages): ?><a href="?page=<?php echo $page + 1; ?>&q=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>" class="px-4 py-2 bg-[#0f172a] border border-white/5 rounded-xl text-xs font-black text-white">Next</a><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
