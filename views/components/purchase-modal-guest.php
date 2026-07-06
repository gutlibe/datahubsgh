<div id="purchase-modal" class="fixed inset-0 z-[1000] overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <!-- Darker Backdrop -->
        <div id="modal-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-middle bg-[#0f172a] rounded-[40px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-md sm:w-full border border-white/10">
            <div class="px-8 py-8">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-white italic tracking-tight" id="modal-title">Checkout</h3>
                    <button id="close-modal-x" class="p-2 rounded-xl bg-white/5 text-slate-400 hover:text-white transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="space-y-6">
                    <!-- Product Info Area -->
                    <div id="modal-product-details" class="p-5 rounded-3xl bg-white/[0.03] border border-white/5 relative overflow-hidden">
                        <!-- Content injected by JS -->
                    </div>

                    <!-- Number Entry -->
                    <div class="space-y-3">
                        <label for="phone-number-input" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2">
                            Recipient Phone Number
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="fas fa-phone-alt text-slate-600 group-focus-within:text-indigo-400 transition-colors text-sm"></i>
                            </div>
                            <input
                                type="tel"
                                id="phone-number-input"
                                class="w-full pl-12 pr-6 py-5 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-black text-white text-lg placeholder:font-normal placeholder:text-slate-600"
                                placeholder="024 000 0000"
                                maxlength="10"
                            >
                        </div>
                        <p id="phone-error" class="text-[10px] font-bold text-red-400 hidden uppercase tracking-wide px-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Please enter a valid number
                        </p>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2">
                        <button
                            id="confirm-purchase-btn"
                            class="w-full bg-indigo-600 text-white font-black py-5 rounded-[24px] shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all flex items-center justify-center gap-3 group"
                        >
                            <span id="btn-text" class="text-lg">Initialize Payment</span>
                            <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform" id="btn-icon"></i>
                            <svg class="animate-spin h-6 w-6 text-white hidden" id="loading-spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <button id="close-modal-btn" class="w-full text-center text-xs font-black text-slate-500 hover:text-slate-300 transition-colors uppercase tracking-[0.2em]">
                        Cancel Transaction
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentProductId = null;
    window.showPurchaseModal = function(productId, productName, productPrice, productNetwork) {
        currentProductId = productId;
        const modal = document.getElementById('purchase-modal');
        const modalProductDetails = document.getElementById('modal-product-details');
        const phoneNumberInput = document.getElementById('phone-number-input');
        const phoneError = document.getElementById('phone-error');
        
        const networkColors = {
            'mtn': 'text-amber-400 bg-amber-400/10 border-amber-400/20',
            'at': 'text-blue-400 bg-blue-400/10 border-blue-400/20',
            'telecel': 'text-red-400 bg-red-400/10 border-red-400/20'
        };
        const colorClass = networkColors[productNetwork?.toLowerCase()] || 'text-indigo-400 bg-indigo-400/10 border-indigo-400/20';

        modalProductDetails.innerHTML = `
            <div class="flex justify-between items-center">
                <div class="space-y-2">
                    <span class="inline-flex px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest border ${colorClass}">
                        ${productNetwork || 'Data'}
                    </span>
                    <h4 class="text-xl font-black text-white italic leading-tight">${productName}</h4>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-black text-indigo-400">${productPrice}</div>
                    <div class="text-[10px] font-black text-slate-500 uppercase tracking-tighter">Total Due</div>
                </div>
            </div>
        `;
        
        phoneNumberInput.value = '';
        phoneError.classList.add('hidden');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('purchase-modal');
        const modalOverlay = document.getElementById('modal-overlay');
        const closeX = document.getElementById('close-modal-x');
        const closeModalBtn = document.getElementById('close-modal-btn');
        const confirmPurchaseBtn = document.getElementById('confirm-purchase-btn');
        const phoneNumberInput = document.getElementById('phone-number-input');
        const phoneError = document.getElementById('phone-error');
        const loadingSpinner = document.getElementById('loading-spinner');
        const btnText = document.getElementById('btn-text');
        const btnIcon = document.getElementById('btn-icon');
        
        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            currentProductId = null;
        }

        modalOverlay.onclick = closeModal;
        closeX.onclick = closeModal;
        closeModalBtn.onclick = closeModal;
        
        confirmPurchaseBtn.addEventListener('click', async function() {
            const phoneNumber = phoneNumberInput.value.trim();
            if (!currentProductId) return;
            if (!/^0[0-9]{9}$/.test(phoneNumber)) {
                phoneError.classList.remove('hidden');
                phoneNumberInput.focus();
                return;
            }
            
            phoneError.classList.add('hidden');
            confirmPurchaseBtn.disabled = true;
            loadingSpinner.classList.remove('hidden');
            btnIcon.classList.add('hidden');
            btnText.textContent = 'Securing...';
            
            try {
                const response = await fetch(BASE_URL + '/api/order/direct-purchase/', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ product_id: currentProductId, msisdn: phoneNumber })
                });
                
                const result = await response.json();
                if (result.success && result.authorization_url) {
                    window.location.href = result.authorization_url;
                } else {
                    if (typeof showToast === 'function') {
                        showToast('Notice', result.message || 'Service temporarily unavailable.', 'info');
                    }
                    resetBtn();
                }
            } catch (error) {
                if (typeof showToast === 'function') {
                    showToast('Connection Error', 'Please check your internet.', 'error');
                }
                resetBtn();
            }
        });

        function resetBtn() {
            confirmPurchaseBtn.disabled = false;
            loadingSpinner.classList.add('hidden');
            btnIcon.classList.remove('hidden');
            btnText.textContent = 'Initialize Payment';
        }
    });
</script>