<div class="min-h-screen py-12 px-4 sm:px-6">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8 p-6 rounded-[32px] bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="relative z-10 flex items-center">
                <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-black italic">Payment Successful!</h3>
                    <p class="mt-1 text-sm opacity-90"><?php echo (int)$order['quantity']; ?> &times; <?php echo htmlspecialchars($order['display_name']); ?> — GH₵<?php echo number_format($order['amount'], 2); ?></p>
                </div>
            </div>
        </div>

        <div class="bg-[#0f172a] rounded-[32px] border border-white/5 p-6 mb-8">
            <h3 class="text-xl font-black text-white mb-4 text-center italic">Order Status</h3>

            <?php if ($order['status'] === 'completed'): ?>
                <div class="mb-4 text-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 uppercase tracking-widest">Completed</span>
                    <p class="text-slate-500 text-xs mt-2">Cards have also been sent by SMS to <?php echo htmlspecialchars($order['msisdn']); ?>.</p>
                </div>
                <div class="space-y-3">
                    <?php foreach (json_decode($order['cards'] ?? '[]', true) as $i => $card): ?>
                        <div class="bg-white/[0.03] rounded-2xl p-5 border border-white/5">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Card <?php echo $i + 1; ?></p>
                            <p class="font-mono text-sm text-white font-bold">Serial: <?php echo htmlspecialchars($card['serial'] ?? '-'); ?></p>
                            <p class="font-mono text-sm text-white font-bold">PIN: <?php echo htmlspecialchars($card['pin'] ?? '-'); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php elseif ($order['status'] === 'failed'): ?>
                <div class="text-center">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-black bg-red-500/10 text-red-400 border border-red-500/20 uppercase tracking-widest">Failed</span>
                    <p class="text-slate-500 text-sm mt-4">This purchase could not be completed. Please contact support with your reference number below.</p>
                </div>
            <?php else: ?>
                <div id="rc-status-pending" class="text-center">
                    <div class="w-8 h-8 border-4 border-white/10 border-t-emerald-500 rounded-full animate-spin mx-auto mb-4"></div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-black bg-blue-500/10 text-blue-400 border border-blue-500/20 uppercase tracking-widest">Verifying Payment...</span>
                </div>
            <?php endif; ?>
        </div>

        <div class="bg-[#0f172a] rounded-[32px] border border-white/5 p-6 mb-8">
            <h3 class="text-xl font-black text-white mb-4 text-center italic">Reference Number</h3>
            <div class="flex items-center justify-center">
                <div class="flex-1 max-w-md bg-white/[0.03] rounded-xl p-3 border border-white/5 flex items-center justify-between">
                    <input type="text" id="reference-input" value="<?php echo htmlspecialchars($reference); ?>" class="w-full bg-transparent text-white font-bold text-center focus:outline-none" readonly>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo rtrim($_ENV['APP_URL'], '/'); ?>/home" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-700 text-white font-bold rounded-xl hover:from-indigo-700 hover:to-purple-800 transition-all text-center shadow-lg">Continue Shopping</a>
        </div>
    </div>
</div>

<?php if ($order['status'] === 'pending'): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const reference = "<?php echo $reference; ?>";
    const isLocal = <?php echo (($_ENV['APP_ENV'] ?? 'production') === 'local') ? 'true' : 'false'; ?>;

    function pollStatus() {
        <?php if (($_ENV['APP_ENV'] ?? 'production') === 'local'): ?>
        fetch('/api/order/verify-payment/', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ reference: reference })
        }).then(r => r.json()).then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                setTimeout(pollStatus, 3000);
            }
        }).catch(() => setTimeout(pollStatus, 3000));
        <?php else: ?>
        setTimeout(() => window.location.reload(), 4000);
        <?php endif; ?>
    }

    setTimeout(pollStatus, 2000);
});
</script>
<?php endif; ?>
