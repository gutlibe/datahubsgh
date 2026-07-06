<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 px-4 sm:px-6">
    <div class="max-w-2xl mx-auto">
        <!-- Success Message Card -->
        <div class="mb-8 p-6 rounded-2xl bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-xl transform transition-all duration-300 relative overflow-hidden">
            <!-- Decorative Background Element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-white bg-opacity-10 rounded-full -translate-y-16 translate-x-16"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                    <div class="flex flex-col md:flex-row items-start md:items-center mb-4 md:mb-0">
                        <div class="flex items-center mb-3 md:mb-0 md:mr-6">
                            <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Payment Successful!</h3>
                                <p class="mt-1 text-sm opacity-90">Thank you for your purchase</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Order Details</h3>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Product:</span>
                    <span class="font-medium text-gray-900"><?php echo htmlspecialchars($order['product_name']); ?></span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Network:</span>
                    <span class="font-medium text-gray-900"><?php echo htmlspecialchars($order['network']); ?></span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Recipient:</span>
                    <span class="font-medium text-gray-900"><?php echo htmlspecialchars($order['msisdn']); ?></span>
                </div>
                
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-gray-600">Amount Paid:</span>
                    <span class="font-medium text-gray-900">GH₵ <?php echo number_format($order['amount'], 2); ?></span>
                </div>
                
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-600">Status:</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <?php echo ucfirst($order['status']); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Reference Card with Copy Functionality -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Reference Number</h3>
            
            <div class="flex items-center justify-center">
                <div class="flex items-center space-x-3 w-full max-w-md">
                    <div class="flex-1 bg-gray-50 rounded-lg p-3 border border-gray-200 flex items-center justify-between">
                        <input 
                            type="text" 
                            id="reference-input" 
                            value="<?php echo htmlspecialchars($reference); ?>" 
                            class="w-full bg-transparent text-gray-900 font-bold text-center focus:outline-none"
                            readonly
                        >
                        <button 
                            id="copy-reference-btn"
                            class="ml-2 p-2 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700"
                            title="Copy to clipboard"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <div id="copy-feedback" class="mt-3 text-center text-sm text-green-600 hidden">
                Reference copied to clipboard!
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Your Order Process</h3>
            
            <div class="space-y-6">
                <!-- Step 1 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-500 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-base font-medium text-gray-900">Payment Completed</h4>
                        <p class="text-sm text-gray-500">Your payment has been processed successfully</p>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white">
                            <span class="text-sm font-bold">2</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-base font-medium text-gray-900">Order Processing</h4>
                        <p class="text-sm text-gray-500">We're processing your data bundle request</p>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-500 text-white">
                            <span class="text-sm font-bold">3</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-base font-medium text-gray-900">Data Delivery</h4>
                        <p class="text-sm text-gray-500">Data bundle will be sent to your phone number</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4 text-center">Next Steps</h3>
            
            <ul class="space-y-3">
                <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-700">Keep your reference number for any inquiries</span>
                </li>
                <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-700">You will receive your data bundle shortly</span>
                </li>
                <li class="flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-gray-700">Check your phone for a confirmation message</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a 
                href="<?php echo rtrim($_ENV['APP_URL'], '/'); ?>/home" 
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-700 text-white font-bold rounded-lg hover:from-indigo-700 hover:to-purple-800 transition-all duration-300 text-center shadow-md hover:shadow-lg"
            >
                Continue Shopping
            </a>
            <a 
                href="<?php echo rtrim($_ENV['APP_URL'], '/'); ?>/check-status?ref=<?php echo urlencode($reference); ?>" 
                class="px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-bold rounded-lg hover:from-gray-700 hover:to-gray-800 transition-all duration-300 text-center shadow-md hover:shadow-lg"
            >
                Track Order
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const copyBtn = document.getElementById('copy-reference-btn');
        const referenceInput = document.getElementById('reference-input');
        const copyFeedback = document.getElementById('copy-feedback');
        
        copyBtn.addEventListener('click', function() {
            referenceInput.select();
            referenceInput.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    copyFeedback.classList.remove('hidden');
                    
                    // Hide feedback after 3 seconds
                    setTimeout(function() {
                        copyFeedback.classList.add('hidden');
                    }, 3000);
                    
                    // Add temporary visual feedback to button
                    const originalHTML = copyBtn.innerHTML;
                    copyBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    `;
                    
                    setTimeout(function() {
                        copyBtn.innerHTML = originalHTML;
                    }, 2000);
                } else {
                    alert('Failed to copy reference. Please try again.');
                }
            } catch (err) {
                // Fallback for browsers that don't support execCommand
                if (navigator.clipboard && window.isSecureContext) {
                    navigator.clipboard.writeText(referenceInput.value).then(function() {
                        copyFeedback.classList.remove('hidden');
                        
                        // Hide feedback after 3 seconds
                        setTimeout(function() {
                            copyFeedback.classList.add('hidden');
                        }, 3000);
                        
                        // Add temporary visual feedback to button
                        const originalHTML = copyBtn.innerHTML;
                        copyBtn.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        `;
                        
                        setTimeout(function() {
                            copyBtn.innerHTML = originalHTML;
                        }, 2000);
                    }).catch(function(err) {
                        console.error('Failed to copy: ', err);
                        alert('Failed to copy reference. Please try again.');
                    });
                } else {
                    // Fallback for older browsers
                    referenceInput.select();
                    alert('Copy function: Please press Ctrl+C to copy the reference number');
                }
            }
        });
    });

    // Auto-verify payment for pending orders (Only in Local Environment)
    <?php if (($order['status'] === 'pending') && ($_ENV['APP_ENV'] ?? 'production') === 'local'): ?>
    document.addEventListener('DOMContentLoaded', function() {
        const reference = "<?php echo $reference; ?>";
        console.log('Local Dev: Attempting auto-verification for', reference);
        
        fetch('/api/order/verify-payment/', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ reference: reference })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Verification result:', data);
            if (data.success) {
                // Reload to show updated status
                window.location.reload();
            } else {
                console.log('Verification check failed:', data.message);
                // Optional: Alert the user if it's explicitly failed
                 Swal.fire({
                    icon: 'warning',
                    title: 'Verification Pending',
                    text: 'Status: ' + data.message
                });
            }
        })
        .catch(error => {
            console.error('Error verifying payment:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to verify payment: ' + error
            });
        });
    });
    <?php endif; ?>
</script>