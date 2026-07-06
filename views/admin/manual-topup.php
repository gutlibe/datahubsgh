<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 px-4 sm:px-6">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Manual Top-Up</h1>
            <p class="text-gray-600">Credit user accounts manually</p>
            <div class="w-16 h-1 bg-indigo-600 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center">
                    <div class="p-2 rounded-lg bg-indigo-100 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Top-Up User Account</h2>
                        <p class="text-sm text-gray-600">Search for a user to credit their balance</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form action="<?php echo BASE_URL; ?>/admin/manual-topup" method="POST" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700 mb-2">User Email</label>
                        <div class="flex">
                            <div class="relative flex-grow">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="pl-10 block w-full px-4 py-3 border border-gray-300 rounded-l-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition duration-200" 
                                    placeholder="Enter user email" 
                                    required
                                >
                            </div>
                            <button 
                                type="submit" 
                                name="search_user" 
                                class="px-6 py-3 border border-transparent shadow-sm text-sm font-bold rounded-r-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 transform hover:-translate-y-0.5"
                            >
                                Search User
                            </button>
                        </div>
                    </div>
                </form>

                <?php if ($user_found && $user): ?>
                    <div class="mt-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100 shadow-sm">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            User Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</p>
                                <p class="text-sm text-gray-900 mt-1"><?php echo htmlspecialchars($user['email']); ?></p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone Number</p>
                                <p class="text-sm text-gray-900 mt-1"><?php echo htmlspecialchars($user['phone_number']); ?></p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Current Balance</p>
                                <p class="text-lg font-bold text-green-600 mt-1">GH₵ <?php echo htmlspecialchars(number_format($user['balance'], 2)); ?></p>
                            </div>
                        </div>

                        <form action="<?php echo BASE_URL; ?>/admin/manual-topup" method="POST" class="mt-6 space-y-4">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="hidden" name="old_balance" value="<?php echo $user['balance']; ?>">
                            <input type="hidden" name="user_email" value="<?php echo $user['email']; ?>">
                            <div>
                                <label for="topup_amount" class="block text-sm font-bold text-gray-700 mb-2">Amount to Top-Up</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">GH₵</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        step="0.01" 
                                        id="topup_amount" 
                                        name="topup_amount" 
                                        class="pl-12 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:outline-none transition duration-200" 
                                        placeholder="0.00" 
                                        required
                                    >
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button 
                                    type="button" 
                                    id="topup-button" 
                                    class="inline-flex justify-center py-3 px-6 border border-transparent shadow-md text-sm font-bold rounded-lg text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300 transform hover:-translate-y-0.5"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Credit User
                                </button>
                            </div>
                        </form>
                    </div>
                <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_user'])): ?>
                    <div class="mt-8 p-4 bg-red-50 rounded-lg border border-red-200">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-red-700 font-medium">User not found. Please check the email address and try again.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <div id="receipt-container" class="mt-8 p-6 border border-gray-200 rounded-xl bg-gray-50 hidden">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Transaction Receipt
                    </h3>
                    <textarea 
                        id="receipt" 
                        rows="8" 
                        class="w-full p-4 border border-gray-300 rounded-lg bg-white font-mono text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        readonly
                    ></textarea>
                    <div class="flex justify-end mt-4">
                        <button 
                            id="copy-receipt" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-200"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Copy Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const topupButton = document.getElementById('topup-button');
    
    if (topupButton) {
        topupButton.addEventListener('click', function () {
            const form = this.closest('form');
            const amount = form.querySelector('#topup_amount').value;
            const amountValue = parseFloat(amount);

            if (!amount || isNaN(amountValue) || amountValue <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Amount',
                    text: 'Please enter a valid amount to top up.',
                    confirmButtonColor: '#6366F1'
                });
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to credit this user with GH₵ ${amountValue.toFixed(2)}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#EF4444',
                confirmButtonText: 'Yes, credit user!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        const receiptContainer = document.getElementById('receipt-container');
        const receiptTextarea = document.getElementById('receipt');
        const userEmail = urlParams.get('email');
        const oldBalance = parseFloat(urlParams.get('old_balance')).toFixed(2);
        const newBalance = parseFloat(urlParams.get('new_balance')).toFixed(2);
        const amount = (newBalance - oldBalance).toFixed(2);

        const receiptContent = `
Transaction Receipt
===================
User Email: ${userEmail}
Amount Credited: GH₵ ${amount}
Old Balance: GH₵ ${oldBalance}
New Balance: GH₵ ${newBalance}
===================
Date: ${new Date().toLocaleString()}
        `;
        receiptTextarea.value = receiptContent.trim();
        receiptContainer.classList.remove('hidden');

        Swal.fire({
            icon: 'success',
            title: 'Top-Up Successful!',
            text: 'User has been credited successfully!',
            confirmButtonColor: '#6366F1'
        });
    }

    const copyButton = document.getElementById('copy-receipt');
    if (copyButton) {
        copyButton.addEventListener('click', function () {
            const receiptTextarea = document.getElementById('receipt');
            receiptTextarea.select();
            document.execCommand('copy');
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Receipt copied to clipboard.',
                confirmButtonColor: '#6366F1'
            });
        });
    }
});
</script>
