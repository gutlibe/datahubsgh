document.addEventListener('DOMContentLoaded', function() {
    const closePanelBtn = document.getElementById('close-panel-btn');
    const panel = document.getElementById('purchase-panel');
    const overlay = document.getElementById('panel-overlay');
    const completePurchaseBtn = document.getElementById('complete-purchase-btn');
    const msisdnInput = document.getElementById('msisdn-input');
    const panelProductDetails = document.getElementById('panel-product-details');
    let currentProductId = null;

    document.querySelectorAll('.buy-now-btn').forEach(button => {
        button.addEventListener('click', function() {
            currentProductId = this.dataset.productId;
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
    
    if (closePanelBtn) {
        closePanelBtn.addEventListener('click', function() {
            panel.classList.add('translate-x-full');
            overlay.classList.add('hidden');
            overlay.classList.remove('opacity-100');
        });
    }
    
    overlay.addEventListener('click', function() {
        panel.classList.add('translate-x-full');
        overlay.classList.add('hidden');
        overlay.classList.remove('opacity-100');
    });

    completePurchaseBtn.addEventListener('click', async function() {
        const msisdn = msisdnInput.value;
        if (!msisdn || !currentProductId) {
            Swal.fire({
                icon: 'warning',
                title: 'Required Information',
                text: 'Please enter a phone number and select a product.'
            });
            return;
        }

        this.disabled = true;
        this.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;

        try {
            const apiEndpoint = '/api/order/direct-purchase/';
            const response = await fetch(apiEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: currentProductId,
                    msisdn: msisdn
                })
            });

            const result = await response.json();

            if (result.success && result.authorization_url) {
                window.location.href = result.authorization_url;
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Purchase Unavailable',
                    text: result.message || 'Direct purchases are temporarily unavailable. Please try again later.'
                });
                this.disabled = false;
                this.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Complete Purchase
                `;
            }
        } catch (error) {
            console.error('Purchase Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Unexpected Error',
                text: 'Something went wrong while processing your request. Please try again later.'
            });
            this.disabled = false;
            this.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Complete Purchase
            `;
        }
    });
});
