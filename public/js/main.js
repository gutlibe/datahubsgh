
document.addEventListener('DOMContentLoaded', () => {
 
    const tabs = document.querySelectorAll('[data-tabs-target]');
    const tabContents = document.querySelectorAll('[role="tabpanel"]');

    if (tabs.length > 0) {
    
        tabs[0].setAttribute('aria-selected', 'true');
        tabs[0].classList.add('border-indigo-500', 'text-indigo-600');
        const firstTabContent = document.querySelector(tabs[0].dataset.tabsTarget);
        if (firstTabContent) {
            firstTabContent.classList.remove('hidden');
        }

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
               
                tabs.forEach(t => {
                    t.setAttribute('aria-selected', 'false');
                    t.classList.remove('border-indigo-500', 'text-indigo-600');
                    t.classList.add('hover:text-gray-600', 'hover:border-gray-300');
                });

              
                tabContents.forEach(content => {
                    content.classList.add('hidden');
                });

            
                this.setAttribute('aria-selected', 'true');
                this.classList.add('border-indigo-500', 'text-indigo-600');
                this.classList.remove('hover:text-gray-600', 'hover:border-gray-300');

            
                const target = document.querySelector(this.dataset.tabsTarget);
                if (target) {
                    target.classList.remove('hidden');
                }
            });
        });
    }


    const adminMenuIcon = document.getElementById('admin-menu-icon');
    const adminSidebarPanel = document.getElementById('admin-sidebar-panel');
    const adminSidebarCloseBtn = document.getElementById('admin-sidebar-close-btn');
    const adminSidebarOverlay = document.getElementById('admin-sidebar-overlay');

    if (adminMenuIcon && adminSidebarPanel && adminSidebarCloseBtn && adminSidebarOverlay) {
        adminMenuIcon.addEventListener('click', () => {
            adminSidebarPanel.classList.remove('-translate-x-full');
            adminSidebarOverlay.classList.remove('hidden');
        });

        adminSidebarCloseBtn.addEventListener('click', () => {
            adminSidebarPanel.classList.add('-translate-x-full');
            adminSidebarOverlay.classList.add('hidden');
        });

        adminSidebarOverlay.addEventListener('click', () => {
            adminSidebarPanel.classList.add('-translate-x-full');
            adminSidebarOverlay.classList.add('hidden');
        });
    }


    const purchasePanel = document.getElementById('purchase-panel');
    const panelOverlay = document.getElementById('panel-overlay');

    if (purchasePanel && panelOverlay) {
        const productDetailsContainer = document.getElementById('panel-product-details');
        const completePurchaseBtn = document.getElementById('complete-purchase-btn');
        const userBalanceEl = document.getElementById('user-balance');
        let currentProductId = null;

        const openPanel = (product) => {
            currentProductId = product.id;
            
            const userBalance = parseFloat(userBalanceEl.dataset.balance);
            const productPrice = parseFloat(product.customer_price);

            if (userBalance < productPrice) {
                completePurchaseBtn.disabled = true;
                completePurchaseBtn.innerText = 'Insufficient Balance';
            } else {
                completePurchaseBtn.disabled = false;
                completePurchaseBtn.innerText = 'Complete Purchase';
            }

            productDetailsContainer.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Network</p>
                        <p class="text-lg font-semibold">${product.network.toUpperCase()}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Product</p>
                        <p id="panel-product-name" class="text-lg font-semibold">${product.name}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Price</p>
                        <p class="text-lg font-semibold">GH₵${productPrice.toFixed(2)}</p>
                    </div>
                </div>
            `;
            purchasePanel.classList.remove('translate-x-full');
            panelOverlay.classList.remove('hidden');
        };

        const closePanel = () => {
            purchasePanel.classList.add('translate-x-full');
            panelOverlay.classList.add('hidden');
        };

        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.addEventListener('click', async (event) => {
                if (event.target.classList.contains('buy-now-btn')) {
                    const button = event.target;
                    const productCard = button.closest('.bundle-card-container');
                    const name = productCard.querySelector('[data-product-name]').innerText;
                    const price = productCard.querySelector('[data-product-price]').innerText.replace('GH₵', '');
                    const network = tab.id;

                    const product = {
                        id: button.dataset.productId,
                        name: name,
                        network: network,
                        customer_price: price
                    };
                    
                    openPanel(product);
                }
            });
        });

        if (panelOverlay) {
            panelOverlay.addEventListener('click', closePanel);
        }

        if (completePurchaseBtn) {
            completePurchaseBtn.addEventListener('click', async () => {
                if (!currentProductId || completePurchaseBtn.disabled) return;

                const msisdnInput = document.getElementById('msisdn-input');
                const msisdn = msisdnInput ? msisdnInput.value : '';

                if (!msisdn) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please enter a phone number (MSISDN) to proceed.',
                    });
                    return;
                }

           
                const productNameEl = document.getElementById('panel-product-name');
                const productName = productNameEl ? productNameEl.innerText : 'this product';

                const reference = `WEB-${Date.now()}-${Math.floor(Math.random() * 1000)}`;

                Swal.fire({
                    title: 'Confirm Purchase',
                    text: `Are you sure you want to buy ${productName} for ${msisdn}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, purchase it!'
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Processing...',
                            text: 'Please wait while we complete your purchase.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        const response = await fetch(`${BASE_URL}/api/order/place/`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ product_id: currentProductId, reference: reference, msisdn: msisdn })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Purchase Accepted!',
                                text: data.message,
                            }).then(() => {
                       
                                location.reload(); 
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Purchase Failed',
                                text: data.message || 'An unknown error occurred.',
                            });
                        }
                    }
                });
            });
        }
    }


    document.querySelectorAll('.change-status-btn').forEach(button => {
        button.addEventListener('click', async (event) => {
            const orderId = event.target.dataset.orderId;
            const currentStatus = event.target.dataset.currentStatus;

            const { value: newStatus } = await Swal.fire({
                title: 'Select new status',
                input: 'select',
                inputOptions: {
                    'pending': 'Pending',
                    'completed': 'Completed',
                    'failed': 'Failed'
                },
                inputPlaceholder: 'Select a status',
                showCancelButton: true,
                inputValue: currentStatus
            });

            if (newStatus) {
                Swal.fire({
                    title: 'Updating...',
                    text: 'Please wait while we update the order status.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const response = await fetch(`${BASE_URL}/api/order/update-status/`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_id: orderId, status: newStatus })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        text: data.message,
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: data.message || 'An unknown error occurred.',
                    });
                }
            }
        });
    });
});
