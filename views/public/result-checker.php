<div class="container mx-auto px-4 py-6 max-w-3xl">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-black text-white italic tracking-tighter mb-2">Result Checker</h1>
        <p class="text-slate-500 text-sm font-medium">WASSCE & BECE checker cards, delivered instantly by SMS.</p>
    </div>

    <?php if (!$isEnabled || empty($types)): ?>
        <div class="bg-[#0f172a] rounded-[40px] border border-white/5 p-12 text-center">
            <div class="mx-auto w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-4">
                <i class="fas fa-file-invoice text-2xl text-slate-500"></i>
            </div>
            <h3 class="text-xl font-black text-white mb-2 italic">Temporarily Unavailable</h3>
            <p class="text-slate-500 text-sm">Result checker purchases are currently unavailable. Please check back later.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <?php foreach ($types as $type):
                $firstTierPrice = !empty($type['tiers']) ? (float)$type['tiers'][0]['unit_price'] : 0;
            ?>
                <div class="bg-[#0f172a] rounded-[32px] border border-white/5 p-8 hover:border-emerald-500/30 transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20 mb-6 text-emerald-400">
                        <i class="fas fa-file-invoice text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white italic mb-1"><?php echo htmlspecialchars($type['name']); ?></h3>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-6">From GH₵<?php echo number_format($firstTierPrice, 2); ?> per card</p>
                    <button type="button" onclick='openRcModal(<?php echo json_encode($type); ?>)' class="w-full bg-emerald-600 text-white font-black py-4 rounded-2xl hover:bg-emerald-500 transition-all shadow-xl shadow-emerald-600/10">
                        Buy Now
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Purchase Modal -->
<div id="rc-modal" class="fixed inset-0 z-[1000] overflow-y-auto hidden" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div id="rc-modal-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div class="inline-block align-middle bg-[#0f172a] rounded-[40px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-white/10">
            <div class="px-8 py-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-white italic tracking-tight" id="rc-modal-title">Checkout</h3>
                    <button id="rc-close-modal" class="p-2 rounded-xl bg-white/5 text-slate-400 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2">Recipient Phone Number</label>
                        <input type="tel" id="rc-msisdn" maxlength="10" placeholder="024 000 0000"
                            class="w-full pl-6 pr-6 py-5 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all font-black text-white text-lg placeholder:font-normal placeholder:text-slate-600">
                        <p id="rc-phone-error" class="text-[10px] font-bold text-red-400 hidden uppercase tracking-wide px-2">Please enter a valid number</p>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2">Quantity</label>
                        <div class="flex items-center justify-center gap-4">
                            <button type="button" onclick="rcChangeQty(-1)" class="h-12 w-12 bg-white/5 rounded-2xl font-black text-white hover:bg-white/10">-</button>
                            <input type="number" id="rc-quantity" value="1" min="1" max="30" oninput="rcUpdatePrice()" class="w-20 text-center bg-white/[0.03] border border-transparent rounded-2xl p-3 text-xl font-black text-white outline-none">
                            <button type="button" onclick="rcChangeQty(1)" class="h-12 w-12 bg-white/5 rounded-2xl font-black text-white hover:bg-white/10">+</button>
                        </div>
                    </div>

                    <div class="p-5 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 text-center">
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Total Price</p>
                        <p class="text-3xl font-black text-white" id="rc-total-price">₵0.00</p>
                    </div>

                    <button id="rc-confirm-btn" class="w-full bg-emerald-600 text-white font-black py-5 rounded-[24px] shadow-2xl shadow-emerald-600/20 hover:bg-emerald-500 transition-all flex items-center justify-center gap-3">
                        <span id="rc-btn-text" class="text-lg">Initialize Payment</span>
                        <i class="fas fa-arrow-right text-sm" id="rc-btn-icon"></i>
                        <svg class="animate-spin h-6 w-6 text-white hidden" id="rc-loading-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let rcSelectedType = null;

function openRcModal(type) {
    rcSelectedType = type;
    document.getElementById('rc-modal-title').innerText = type.name;
    document.getElementById('rc-msisdn').value = '';
    document.getElementById('rc-quantity').value = 1;
    document.getElementById('rc-phone-error').classList.add('hidden');
    rcUpdatePrice();
    document.getElementById('rc-modal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeRcModal() {
    document.getElementById('rc-modal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

function rcChangeQty(delta) {
    const input = document.getElementById('rc-quantity');
    let val = parseInt(input.value || 1) + delta;
    if (val < 1) val = 1;
    if (val > 30) val = 30;
    input.value = val;
    rcUpdatePrice();
}

function rcResolveUnitPrice(qty) {
    for (const tier of rcSelectedType.tiers) {
        const min = parseInt(tier.min_qty);
        const max = tier.max_qty !== null ? parseInt(tier.max_qty) : null;
        if (qty >= min && (max === null || qty <= max)) {
            return parseFloat(tier.unit_price);
        }
    }
    return rcSelectedType.tiers.length ? parseFloat(rcSelectedType.tiers[rcSelectedType.tiers.length - 1].unit_price) : 0;
}

function rcUpdatePrice() {
    const qty = parseInt(document.getElementById('rc-quantity').value || 1);
    const total = rcResolveUnitPrice(qty) * qty;
    document.getElementById('rc-total-price').innerText = `₵${total.toFixed(2)}`;
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('rc-modal-overlay').onclick = closeRcModal;
    document.getElementById('rc-close-modal').onclick = closeRcModal;

    const confirmBtn = document.getElementById('rc-confirm-btn');
    confirmBtn.addEventListener('click', async function() {
        const msisdn = document.getElementById('rc-msisdn').value.trim();
        const phoneError = document.getElementById('rc-phone-error');

        if (!/^0[0-9]{9}$/.test(msisdn)) {
            phoneError.classList.remove('hidden');
            return;
        }
        phoneError.classList.add('hidden');

        const quantity = parseInt(document.getElementById('rc-quantity').value || 1);

        confirmBtn.disabled = true;
        document.getElementById('rc-loading-spinner').classList.remove('hidden');
        document.getElementById('rc-btn-icon').classList.add('hidden');
        document.getElementById('rc-btn-text').textContent = 'Securing...';

        try {
            const response = await fetch(BASE_URL + '/api/order/result-checker-purchase/', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ checker_type: rcSelectedType.checker_type, msisdn, quantity })
            });
            const result = await response.json();

            if (result.success && result.authorization_url) {
                window.location.href = result.authorization_url;
            } else {
                if (typeof showToast === 'function') {
                    showToast('Notice', result.message || 'Service temporarily unavailable.', 'info');
                }
                rcResetBtn();
            }
        } catch (error) {
            if (typeof showToast === 'function') {
                showToast('Connection Error', 'Please check your internet.', 'error');
            }
            rcResetBtn();
        }
    });

    function rcResetBtn() {
        confirmBtn.disabled = false;
        document.getElementById('rc-loading-spinner').classList.add('hidden');
        document.getElementById('rc-btn-icon').classList.remove('hidden');
        document.getElementById('rc-btn-text').textContent = 'Initialize Payment';
    }
});
</script>
