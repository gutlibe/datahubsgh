<div class="container mx-auto px-4 py-6">
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-gray-800">AirtelTigo Bundles</h1>
    </div>

    <?php if (empty($products)): ?>
        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
            <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No Products Available</h3>
            <p class="text-gray-500">There are currently no products for AirtelTigo.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            <?php foreach ($products as $product): ?>
                <?php view('components/bundle-card-guest', ['product' => $product]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Include purchase modal component -->
<?php view('components/purchase-modal-guest'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle "Buy Now" button clicks with timeout to ensure modal is loaded
    setTimeout(function() {
        const buyNowButtons = document.querySelectorAll('.buy-now-btn');
        buyNowButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const productName = this.getAttribute('data-product-name');
                const productPrice = this.getAttribute('data-product-price');
                
                if (productId && productName && productPrice && window.showPurchaseModal) {
                    try {
                        window.showPurchaseModal(productId, productName, productPrice);
                    } catch (err) {
                        console.error('Error opening purchase modal:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to process purchase. Please try again or refresh the page.'
                        });
                    }
                }
            });
        });
    }, 100); // Small delay to ensure all DOM elements are loaded
});
</script>
