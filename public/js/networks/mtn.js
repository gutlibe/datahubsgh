document.addEventListener('DOMContentLoaded', function() {
    const buyNowButtons = document.querySelectorAll('.buy-now-btn');
    const panel = document.getElementById('purchase-panel');
    const overlay = document.getElementById('panel-overlay');
    const panelProductDetails = document.getElementById('panel-product-details');

    buyNowButtons.forEach(button => {
        button.addEventListener('click', function() {
            const card = this.closest('.bundle-card-container');
            const productName = card.querySelector('[data-product-name]').textContent;
            const productPrice = card.querySelector('[data-product-price]').textContent;

            panelProductDetails.innerHTML = `
                <div class="p-4 bg-gray-100 rounded-lg">
                    <h4 class="font-bold text-lg">${productName}</h4>
                    <p class="text-2xl font-bold text-indigo-600">${productPrice}</p>
                </div>
            `;

            panel.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
            overlay.classList.add('opacity-100');
        });
    });
});
