<div class="container mx-auto px-4 py-6">
    <div class="sticky top-[64px] z-40 bg-white/90 backdrop-blur-sm shadow-lg rounded-xl mb-8 p-3 border border-gray-100">
        <div class="flex flex-wrap justify-center gap-2" id="tabs-container">
            <?php 
                $networks = array_keys($productsByNetwork);
                foreach ($networks as $index => $network): 
            ?>
                <button class="tab-link flex-1 min-w-[100px] py-3 px-4 text-center font-bold rounded-lg transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 whitespace-nowrap <?php echo $index === 0 ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'; ?>" 
                        onclick="openNetwork(event, '<?php echo $network; ?>')">
                    <?php echo strtoupper($network); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <?php foreach ($productsByNetwork as $network => $products): ?>
        <div id="<?php echo $network; ?>" class="tab-content" style="<?php echo $network === $networks[0] ? 'display: block;' : 'display: none;'; ?>">
            <?php if (empty($products)): ?>
                <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                    <div class="mx-auto w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No Products Available</h3>
                    <p class="text-gray-500">There are currently no products for <?php echo strtoupper($network); ?>.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                    <?php foreach ($products as $product): ?>
                        <?php view('components/bundle-card', ['product' => $product]); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php view('components/purchase-panel'); ?>

<div id="toast-notification" class="fixed top-5 right-5 bg-gradient-to-r from-red-500 to-orange-500 text-white py-3 px-5 rounded-xl shadow-lg hidden animate__animated animate__fadeInRight z-50 border border-red-400">
    <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        <span>Please log in to purchase an item.</span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buyNowButtons = document.querySelectorAll('.buy-now-btn');
    const toast = document.getElementById('toast-notification');

    buyNowButtons.forEach(button => {
        button.addEventListener('click', function() {
            const isLoggedIn = this.getAttribute('data-is-logged-in') === 'true';
            if (!isLoggedIn) {
                toast.classList.remove('hidden');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3000);
            }
        });
    });
});

function openNetwork(evt, networkName) {
    // Hide all tab content
    const tabcontent = document.getElementsByClassName("tab-content");
    for (let i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Deactivate all tab links
    const tablinks = document.getElementsByClassName("tab-link");
    for (let i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove('bg-gradient-to-r', 'from-indigo-600', 'to-purple-600', 'text-white', 'shadow-md');
        tablinks[i].classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(networkName).style.display = "block";
    evt.currentTarget.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
    evt.currentTarget.classList.add('bg-gradient-to-r', 'from-indigo-600', 'to-purple-600', 'text-white', 'shadow-md');
}
</script>
