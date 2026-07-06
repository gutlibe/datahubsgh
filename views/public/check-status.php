<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 px-4 sm:px-6">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Track Your Order</h1>
            <p class="text-gray-600">Enter your order reference to track the status of your order</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100 mb-8">
            <form method="POST" action="" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-grow">
                    <input 
                        type="text" 
                        name="reference" 
                        value="<?php echo htmlspecialchars($reference ?? ''); ?>" 
                        placeholder="Enter your order reference (e.g., REF123456789)" 
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none transition duration-200"
                        required
                    />
                </div>
                <div>
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold py-3 px-6 rounded-lg hover:from-blue-700 hover:to-indigo-800 transition-all duration-300 shadow-md hover:shadow-lg"
                    >
                        Check Status
                    </button>
                </div>
            </form>
        </div>

        <?php if (!empty($reference) && isset($orders) && !empty($orders)): ?>
        <!-- Display Order if found -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Order Details for Reference: <?php echo htmlspecialchars($reference); ?></h2>
            
            <div class="space-y-4">
                <?php foreach ($orders as $order): ?>
                <div class="p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex flex-col md:flex-row md:items-center justify-between">
                        <div class="mb-2 md:mb-0">
                            <div class="font-bold text-gray-900"><?php echo htmlspecialchars($order['product_name']); ?></div>
                            <div class="text-sm text-gray-600">Ref: <?php echo htmlspecialchars($order['reference']); ?></div>
                        </div>
                        <div class="flex flex-col items-end">
                            <div class="text-sm font-medium">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    <?php 
                                    switch (strtolower($order['status'])) {
                                        case 'delivered':
                                            echo 'bg-green-100 text-green-800';
                                            break;
                                        case 'accepted':
                                        case 'processing':
                                            echo 'bg-blue-100 text-blue-800';
                                            break;
                                        case 'pending':
                                            echo 'bg-yellow-100 text-yellow-800';
                                            break;
                                        case 'failed':
                                            echo 'bg-red-100 text-red-800';
                                            break;
                                        default:
                                            echo 'bg-gray-100 text-gray-800';
                                    }
                                    ?>">
                                    <?php echo ucfirst(htmlspecialchars($order['status'])); ?>
                                </span>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?php echo date('M j, Y g:i A', strtotime($order['created_at'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php elseif (!empty($reference) && isset($orders) && empty($orders)): ?>
        <!-- No order found for the reference -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="text-center py-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No Order Found</h3>
                <p class="text-gray-600">No order was found with reference <?php echo htmlspecialchars($reference); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>