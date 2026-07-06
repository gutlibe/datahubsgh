<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Enhanced Sunday Closure Notice -->
        <div class="mb-8 p-6 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-xl transform transition-all duration-300 border-l-4 border-yellow-400 relative overflow-hidden">
            <!-- Decorative Background Element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div class="flex flex-col md:flex-row items-start md:items-center mb-4 md:mb-0">
                        <div class="flex items-center mb-3 md:mb-0 md:mr-6">
                            <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Important Notice</h3>
                                <p class="mt-1 text-sm opacity-90">Our automated systems operate <span class="text-yellow-400 font-bold">24/7</span>. All orders are processed instantly at any time of the day.</p>
                            </div>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-400 text-gray-900">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Next Business Day
                            </span>
                        </div>
                    </div>
                    <div class="mt-2 md:mt-0">
                        <a href="<?php echo rtrim(APP_URL, '/'); ?>/help" class="text-sm font-bold underline hover:text-yellow-300 transition-colors duration-200 flex items-center">
                            Need help?
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($_GET['purchase'])): ?>
            <div class="mb-8 p-5 rounded-xl shadow-sm <?php echo $_GET['purchase'] === 'success' ? 'bg-green-50 border border-green-200 text-green-800' : 'bg-red-50 border border-red-200 text-red-800'; ?>">
                <div class="flex items-center">
                    <?php if ($_GET['purchase'] === 'success'): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-bold">Purchase Successful!</p>
                            <p>Your purchase is being processed. You will receive your data bundle shortly.</p>
                        </div>
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="font-bold">Purchase Failed</p>
                            <p>Your purchase failed. Please try again.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Buy Data Bundles</h1>
            <p class="text-gray-600 max-w-2xl mx-auto">Choose your network provider and get fast data bundles delivered to your phone</p>
            <div class="w-20 h-1 bg-indigo-600 mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- MTN - Enhanced with better yellow -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 border border-amber-100 hover:shadow-xl hover:border-amber-300 group relative <?php echo ($configs['enable_mtn_purchase'] ?? '1') === '0' ? 'opacity-75 grayscale-[0.5]' : ''; ?>">
                <div class="absolute top-0 right-0 bg-amber-400 text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-2xl z-10">
                    MOST POPULAR
                </div>
                <div class="p-8 flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full bg-amber-50 flex items-center justify-center mb-6 shadow-md border-2 border-amber-200 group-hover:border-amber-400 transition-colors duration-300">
                        <img src="<?php echo rtrim(APP_URL, '/'); ?>/images/mtn.jpg" alt="MTN Logo" class="h-16 object-contain">
                    </div>
                    <h5 class="text-2xl font-bold text-gray-800 mb-2">MTN</h5>
                    <p class="text-gray-600 text-center mb-6"><?php echo ($configs['enable_mtn_purchase'] ?? '1') === '1' ? 'Get fast and reliable data bundles' : 'MTN purchases are temporarily unavailable.'; ?></p>
                    <?php if (($configs['enable_mtn_purchase'] ?? '1') === '1'): ?>
                    <a href="<?php echo rtrim(APP_URL, '/'); ?>/networks/mtn" class="mt-2 bg-gradient-to-r from-amber-400 to-amber-500 text-white font-bold py-3 px-6 rounded-full hover:from-amber-500 hover:to-amber-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center group-hover:shadow-amber-200">
                        <span>Buy Data</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <?php else: ?>
                    <button disabled class="mt-2 bg-gray-300 text-gray-500 font-bold py-3 px-6 rounded-full cursor-not-allowed">
                        Unavailable
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <!-- AirtelTigo - Enhanced with Blue -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 border border-blue-100 hover:shadow-xl hover:border-blue-300 group <?php echo ($configs['enable_at_purchase'] ?? '1') === '0' ? 'opacity-75 grayscale-[0.5]' : ''; ?>">
                <div class="p-8 flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full bg-blue-50 flex items-center justify-center mb-6 shadow-md border-2 border-blue-200 group-hover:border-blue-400 transition-colors duration-300">
                        <img src="<?php echo rtrim(APP_URL, '/'); ?>/images/at.png" alt="AirtelTigo Logo" class="h-16 object-contain">
                    </div>
                    <h5 class="text-2xl font-bold text-gray-800 mb-2">AirtelTigo</h5>
                    <p class="text-gray-600 text-center mb-6"><?php echo ($configs['enable_at_purchase'] ?? '1') === '1' ? 'Affordable data plans for everyone' : 'AT purchases are temporarily unavailable.'; ?></p>
                    <?php if (($configs['enable_at_purchase'] ?? '1') === '1'): ?>
                    <a href="<?php echo rtrim(APP_URL, '/'); ?>/networks/at" class="mt-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-3 px-6 rounded-full hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center group-hover:shadow-blue-200">
                        <span>Buy Data</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <?php else: ?>
                    <button disabled class="mt-2 bg-gray-300 text-gray-500 font-bold py-3 px-6 rounded-full cursor-not-allowed">
                        Unavailable
                    </button>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Telecel - Enhanced with Red -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 border border-red-100 hover:shadow-xl hover:border-red-300 group <?php echo ($configs['enable_telecel_purchase'] ?? '1') === '0' ? 'opacity-75 grayscale-[0.5]' : ''; ?>">
                <div class="p-8 flex flex-col items-center">
                    <div class="w-24 h-24 rounded-full bg-red-50 flex items-center justify-center mb-6 shadow-md border-2 border-red-200 group-hover:border-red-400 transition-colors duration-300">
                        <img src="<?php echo rtrim(APP_URL, '/'); ?>/images/telecel.png" alt="Telecel Logo" class="h-16 object-contain">
                    </div>
                    <h5 class="text-2xl font-bold text-gray-800 mb-2">Telecel</h5>
                    <p class="text-gray-600 text-center mb-6"><?php echo ($configs['enable_telecel_purchase'] ?? '1') === '1' ? 'Stay connected with flexible data options' : 'Telecel purchases are temporarily unavailable.'; ?></p>
                    <?php if (($configs['enable_telecel_purchase'] ?? '1') === '1'): ?>
                    <a href="<?php echo rtrim(APP_URL, '/'); ?>/networks/telecel" class="mt-2 bg-gradient-to-r from-red-500 to-red-600 text-white font-bold py-3 px-6 rounded-full hover:from-red-600 hover:to-red-700 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center group-hover:shadow-red-200">
                        <span>Buy Data</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <?php else: ?>
                    <button disabled class="mt-2 bg-gray-300 text-gray-500 font-bold py-3 px-6 rounded-full cursor-not-allowed">
                        Unavailable
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Additional Info Section - Enhanced Interactivity -->
        <div class="mt-16 bg-white rounded-2xl shadow-lg p-6 border border-gray-100 hover:shadow-xl transition-shadow duration-300">
            <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Why Choose Us?</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col items-center text-center p-4 rounded-xl transition-all duration-300 hover:bg-indigo-50 group">
                    <div class="bg-indigo-100 p-4 rounded-full mb-4 group-hover:bg-indigo-200 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 group-hover:text-indigo-700 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg mb-2">Quick Processing</h4>
                    <p class="text-gray-600">Orders processed within minutes during working hours</p>
                </div>
                <div class="flex flex-col items-center text-center p-4 rounded-xl transition-all duration-300 hover:bg-green-50 group">
                    <div class="bg-green-100 p-4 rounded-full mb-4 group-hover:bg-green-200 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600 group-hover:text-green-700 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg mb-2">Secure Payments</h4>
                    <p class="text-gray-600">All transactions are encrypted and secure</p>
                </div>
                <div class="flex flex-col items-center text-center p-4 rounded-xl transition-all duration-300 hover:bg-blue-50 group">
                    <div class="bg-blue-100 p-4 rounded-full mb-4 group-hover:bg-blue-200 transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 group-hover:text-blue-700 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 text-lg mb-2">24/7 Support</h4>
                    <p class="text-gray-600">Customer support available for all inquiries</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Scripts remain unchanged -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/public/dist/home.js"></script>