<!-- views/components/bundle-card.php -->
<div class="bundle-card-container bg-white rounded-xl shadow-md hover:shadow-lg overflow-hidden transform hover:-translate-y-1 transition-all duration-300 flex flex-col h-full border border-gray-100">
    <div class="p-5 flex-grow">
        <div class="flex items-start justify-between">
            <h3 class="text-lg md:text-xl font-bold text-gray-800 truncate mr-2" data-product-name><?php echo htmlspecialchars($product['name']); ?></h3>
            <div class="flex-shrink-0">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <?php echo htmlspecialchars($product['network']); ?>
                </span>
            </div>
        </div>
        <p class="text-sm text-gray-500 mt-1 mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <?php echo htmlspecialchars($product['validity']); ?>
        </p>
        <div class="flex items-baseline">
            <p class="text-2xl md:text-3xl font-extrabold text-indigo-600" data-product-price><?php echo format_currency($product['customer_price']); ?></p>
            <span class="ml-2 text-xs text-gray-500 line-through">
                <?php echo format_currency($product['customer_price'] * 1.1); ?>
            </span>
        </div>
        <div class="mt-2">
            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                <?php echo htmlspecialchars($product['volume']); ?>
            </span>
        </div>
    </div>
    <div class="p-4 pt-0">
        <button class="buy-now-btn w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-3 px-4 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm hover:shadow-md flex items-center justify-center" 
                data-product-id="<?php echo htmlspecialchars($product['id']); ?>"
                data-is-logged-in="<?php echo isLoggedIn() ? 'true' : 'false'; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Buy Now
        </button>
    </div>
</div>