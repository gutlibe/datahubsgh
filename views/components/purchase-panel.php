<!-- views/components/purchase-panel.php -->
<div id="purchase-panel" class="fixed top-0 right-0 h-full w-full sm:w-4/5 md:w-1/3 bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-[1010] rounded-l-2xl overflow-hidden">
    <div class="h-full flex flex-col bg-gradient-to-b from-gray-50 to-white">
        <div class="p-6 bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-bold">Confirm Purchase</h2>
                <button id="close-panel-btn" class="p-2 rounded-full hover:bg-white/20 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-6 flex-grow overflow-y-auto">
            <div class="mb-6 p-5 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100 shadow-sm">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Your Balance</p>
                        <p id="user-balance" class="text-2xl font-bold text-blue-900 mt-1" data-balance="<?php $user = getAuthUser(); echo $user ? $user['balance'] : '0'; ?>">
                            <?php echo $user ? format_currency($user['balance']) : 'GH₵0.00'; ?>
                        </p>
                    </div>
                    <div class="p-3 rounded-lg bg-white shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div id="panel-product-details" class="mb-6">
                <!-- Product details will be injected here by JavaScript -->
            </div>

            <div class="mb-6">
                <label for="msisdn-input" class="block text-gray-700 text-sm font-bold mb-2">Phone Number</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="msisdn-input" 
                        class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition duration-200 shadow-sm" 
                        placeholder="e.g., 0241234567"
                    >
                </div>
                <p class="mt-2 text-sm text-gray-500">Enter the phone number to receive the data bundle</p>
            </div>
        </div>

        <div class="p-6 pt-0 border-t border-gray-200 bg-white">
            <?php if (isLoggedIn()): ?>
                <button id="complete-purchase-btn" class="w-full bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white font-bold py-4 px-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Complete Purchase
                </button>
            <?php else: ?>
                <a href="<?php echo rtrim(APP_URL, '/') . '/login'; ?>" class="w-full bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-4 px-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 text-center block flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Login to Purchase
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="panel-overlay" class="fixed top-0 left-0 w-full h-full bg-black/70 z-[1005] hidden opacity-0 transition-opacity duration-300"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const closePanelBtn = document.getElementById('close-panel-btn');
    const panel = document.getElementById('purchase-panel');
    const overlay = document.getElementById('panel-overlay');
    
    if (closePanelBtn) {
        closePanelBtn.addEventListener('click', function() {
            panel.classList.add('translate-x-full');
            overlay.classList.add('hidden');
            overlay.classList.remove('opacity-100');
        });
    }
    
    // Close panel when clicking on overlay
    overlay.addEventListener('click', function() {
        panel.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        overlay.classList.remove('opacity-100');
    });
});
</script>