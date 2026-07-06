<?php
$currentPage = $_GET['url'] ?? 'home';
$hideTrackButton = ($currentPage === 'check-status' || strpos($currentPage, 'admin') === 0);
?>
<header class="bg-[#020617]/80 backdrop-blur-xl border-b border-white/5 sticky top-0 z-50 py-4">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-12">
            <!-- Logo Section -->
            <a href="<?php echo rtrim(APP_URL, '/'); ?>/" class="flex items-center space-x-3 group">
                <div class="flex-shrink-0">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:shadow-indigo-500/40 transition-all duration-300">
                        <span class="text-white font-black text-xl italic"><?php echo htmlspecialchars(strtoupper($appName[0])); ?></span>
                    </div>
                </div>
                <div>
                    <h1 class="text-xl font-black text-white tracking-tighter italic">
                        <?php echo htmlspecialchars($appName); ?>
                    </h1>
                </div>
            </a>
            
            <div class="flex items-center gap-6">
                <?php if (!$hideTrackButton): ?>
                <button id="track-order-btn" class="p-3 rounded-2xl bg-white/5 text-slate-400 hover:bg-white/10 hover:text-white transition-all duration-200 border border-white/5" title="Track Order">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<!-- Track Order Modal (Dark Version) -->
<div id="track-modal" class="fixed inset-0 z-[1000] overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div id="track-modal-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-middle bg-[#0f172a] rounded-[32px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-white/5">
            <div class="px-8 pt-8 pb-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-white italic tracking-tight" id="modal-title">Track Order</h3>
                    <button id="close-track-modal" class="p-2 rounded-xl bg-white/5 text-slate-400 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div class="relative group">
                        <input type="text" id="track-reference" class="w-full px-5 py-4 bg-white/5 border border-white/5 rounded-2xl focus:bg-white/10 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-black text-white placeholder:font-normal placeholder:text-slate-500" placeholder="Order Reference">
                    </div>
                    <button id="submit-track" class="w-full bg-indigo-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-600/10 hover:bg-indigo-500 transition-all flex items-center justify-center gap-2 group">
                        <span>Locate Order</span>
                        <i class="fas fa-search text-xs group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>
                
                <div id="track-result" class="mt-6 hidden space-y-3"></div>
            </div>
            <div class="bg-white/[0.02] px-8 py-5 flex justify-center">
                <button id="close-track-btn" class="text-xs font-black text-slate-500 hover:text-slate-300 uppercase tracking-widest">Close Window</button>
            </div>
        </div>
    </div>
</div>

<script>
function safeToast(title, message, type) {
    if (typeof showToast === 'function') {
        showToast(title, message, type);
    } else {
        console.log(`[${type}] ${title}: ${message}`);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const trackBtn = document.getElementById('track-order-btn');
    const modal = document.getElementById('track-modal');
    const overlay = document.getElementById('track-modal-overlay');
    const closeBtns = [document.getElementById('close-track-modal'), document.getElementById('close-track-btn')];
    const submitBtn = document.getElementById('submit-track');
    const referenceInput = document.getElementById('track-reference');
    const resultDiv = document.getElementById('track-result');

    function openModal() {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        resultDiv.innerHTML = '';
        resultDiv.classList.add('hidden');
        referenceInput.value = '';
    }

    trackBtn?.addEventListener('click', openModal);
    overlay?.addEventListener('click', closeModal);
    closeBtns.forEach(btn => btn?.addEventListener('click', closeModal));

    submitBtn?.addEventListener('click', async function() {
        const ref = referenceInput.value.trim();
        if (!ref) {
            safeToast('Empty Reference', 'Please enter your reference code.', 'info');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Locating...';

        try {
            const response = await fetch(BASE_URL + '/api/order/check-status/?reference=' + ref);
            const data = await response.json();

            if (data.success && data.orders && data.orders.length > 0) {
                resultDiv.classList.remove('hidden');
                let html = '<div class="text-[9px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-2">Order found</div>';
                data.orders.forEach(order => {
                    const statusColors = {
                        'delivered': 'bg-green-500/10 text-green-400 border-green-500/20',
                        'processing': 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                        'accepted': 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                        'pending': 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                        'failed': 'bg-red-500/10 text-red-400 border-red-500/20'
                    };
                    const color = statusColors[order.status.toLowerCase()] || 'bg-white/5 text-slate-400 border-white/5';
                    
                    html += `
                        <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5">
                            <div class="flex justify-between items-center mb-1">
                                <span class="font-black text-white italic text-sm">${order.product_name}</span>
                                <span class="px-2 py-0.5 rounded-lg text-[9px] font-black uppercase border ${color}">${order.status}</span>
                            </div>
                            <div class="text-[10px] text-slate-500 font-bold">Reference: ${order.reference}</div>
                        </div>
                    `;
                });
                resultDiv.innerHTML = html;
            } else {
                safeToast('Not Found', 'We could not find that order.', 'error');
                resultDiv.classList.add('hidden');
            }
        } catch (error) {
            safeToast('Error', 'Network error. Try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Locate Order</span><i class="fas fa-search text-xs"></i>';
        }
    });
});
</script>