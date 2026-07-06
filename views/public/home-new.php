<?php
use App\Classes\Product;
use App\Classes\Setting;

$productModel = new Product();
$mtnProducts = $productModel->getByNetwork('mtn');
$atProducts = $productModel->getByNetwork('at');
$telecelProducts = $productModel->getByNetwork('telecel');

$setting = new Setting();
$allSettings = $setting->getAllSettings(); // Fetch all settings

// WhatsApp Link Logic
$waValue = trim($allSettings['contact_whatsapp'] ?? '');
$waLink = '';
if (!empty($waValue)) {
    if (preg_match('/^https?:\/\//', $waValue)) {
        $waLink = $waValue;
    } elseif (preg_match('/^[0-9+\s]+$/', $waValue)) {
        $waLink = "https://wa.me/" . preg_replace('/[^0-9]/', '', $waValue);
    } else {
        $waLink = "https://" . ltrim($waValue, '/');
    }
}
?>

<div class="min-h-screen pb-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
        
        <!-- Page Title Section -->
        <div class="text-center mb-16 relative">
            <div class="absolute inset-0 bg-indigo-500/10 blur-[120px] rounded-full"></div>
            <h1 class="relative text-5xl md:text-7xl font-black text-white mb-4 tracking-tighter italic">
                Get <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-violet-400">Connected.</span>
            </h1>
            <p class="relative text-slate-400 max-w-xl mx-auto text-lg font-medium leading-relaxed">
                Premium data bundles delivered at lightning speed. No accounts, no hassle.
            </p>
        </div>

        <?php if (!empty($service_status_message)): ?>
        <!-- Dynamic Dark Notice -->
        <div class="mb-16 relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-violet-600 rounded-[32px] blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
            <div class="relative bg-[#0f172a] border border-white/5 rounded-[32px] p-8 overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/10 rounded-full -translate-y-32 translate-x-32 blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                    <div class="flex-shrink-0 hidden md:flex">
                        <div class="w-20 h-20 rounded-3xl bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <i class="fas fa-bolt text-white text-3xl"></i>
                        </div>
                    </div>
                    <div class="flex-grow">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                            <div class="text-center md:text-left">
                                <div class="flex items-center gap-3 mb-4 justify-center md:justify-start">
                                    <h3 class="text-2xl font-black text-white italic tracking-tight uppercase">Live Update</h3>
                                    <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                </div>
                                <p class="text-slate-400 text-lg font-medium leading-relaxed max-w-3xl">
                                    <?php echo htmlspecialchars($service_status_message); ?>
                                </p>
                            </div>
                            
                            <?php if (!empty($waLink)): ?>
                            <div class="flex-shrink-0">
                                <a href="<?php echo htmlspecialchars($waLink); ?>" target="_blank" class="group/btn relative inline-flex items-center gap-4 px-8 py-5 rounded-[24px] bg-green-500 text-white font-black uppercase tracking-widest text-xs sm:text-sm transition-all hover:bg-green-600 hover:scale-105 active:scale-95 shadow-2xl shadow-green-500/40">
                                    <i class="fab fa-whatsapp text-2xl"></i>
                                    <span>Join WhatsApp Channel</span>
                                    <i class="fas fa-arrow-right text-xs group-hover/btn:translate-x-1 transition-transform"></i>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Network Selection Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start mb-24">
            
            <!-- MTN Card -->
            <div class="group relative bg-[#0f172a] rounded-[40px] border border-white/5 overflow-hidden transition-all duration-500 hover:border-indigo-500/30 hover:shadow-2xl hover:shadow-indigo-500/10 <?php echo ($configs['enable_mtn_purchase'] ?? '1') === '0' ? 'opacity-50 grayscale' : ''; ?>">
                <div class="absolute top-0 left-0 w-full h-1 bg-amber-400"></div>
                <div class="p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-20 h-20 rounded-3xl bg-amber-400/10 flex items-center justify-center border border-amber-400/20 group-hover:scale-110 transition-transform duration-500">
                            <img src="/images/mtn.jpg" alt="MTN" class="h-12 w-12 object-contain">
                        </div>
                        <?php if (($configs['enable_mtn_purchase'] ?? '1') === '1'): ?>
                        <button class="network-toggle w-12 h-12 flex items-center justify-center rounded-2xl bg-white/5 text-amber-400 hover:bg-amber-400 hover:text-white transition-all" data-network="mtn">
                            <i class="fas fa-chevron-down transform transition-transform duration-300"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-3xl font-black text-white italic mb-3">MTN GH</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-10">Fastest network speeds for heavy users and businesses.</p>
                    
                    <?php if (($configs['enable_mtn_purchase'] ?? '1') === '1'): ?>
                    <button class="main-buy-btn w-full bg-amber-400 text-amber-950 font-black py-5 rounded-[24px] hover:bg-amber-300 transition-all shadow-xl shadow-amber-400/10" data-network="mtn">Buy Now</button>
                    <?php else: ?>
                    <button disabled class="w-full bg-white/5 text-slate-600 font-black py-5 rounded-[24px] cursor-not-allowed">Offline</button>
                    <?php endif; ?>
                </div>

                <div id="mtn-products" class="hidden border-t border-white/5 bg-white/[0.01] p-6 max-h-[500px] overflow-y-auto products-container">
                    <div class="grid grid-cols-1 gap-4">
                        <?php foreach ($mtnProducts as $product): ?>
                            <?php view('components/bundle-card-guest', ['product' => $product, 'network' => 'mtn']); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- AT Card -->
            <div class="group relative bg-[#0f172a] rounded-[40px] border border-white/5 overflow-hidden transition-all duration-500 hover:border-indigo-500/30 hover:shadow-2xl hover:shadow-indigo-500/10 <?php echo ($configs['enable_at_purchase'] ?? '1') === '0' ? 'opacity-50 grayscale' : ''; ?>">
                <div class="absolute top-0 left-0 w-full h-1 bg-blue-500"></div>
                <div class="p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-20 h-20 rounded-3xl bg-blue-500/10 flex items-center justify-center border border-blue-500/20 group-hover:scale-110 transition-transform duration-500">
                            <img src="/images/at.png" alt="AT" class="h-12 w-12 object-contain">
                        </div>
                        <?php if (($configs['enable_at_purchase'] ?? '1') === '1'): ?>
                        <button class="network-toggle w-12 h-12 flex items-center justify-center rounded-2xl bg-white/5 text-blue-400 hover:bg-blue-500 hover:text-white transition-all" data-network="at">
                            <i class="fas fa-chevron-down transform transition-transform duration-300"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-3xl font-black text-white italic mb-3">AirtelTigo</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-10">High value data plans designed for flexibility.</p>
                    
                    <?php if (($configs['enable_at_purchase'] ?? '1') === '1'): ?>
                    <button class="main-buy-btn w-full bg-blue-600 text-white font-black py-5 rounded-[24px] hover:bg-blue-500 transition-all shadow-xl shadow-blue-600/10" data-network="at">Buy Now</button>
                    <?php else: ?>
                    <button disabled class="w-full bg-white/5 text-slate-600 font-black py-5 rounded-[24px] cursor-not-allowed">Offline</button>
                    <?php endif; ?>
                </div>

                <div id="at-products" class="hidden border-t border-white/5 bg-white/[0.01] p-6 max-h-[500px] overflow-y-auto products-container">
                    <div class="grid grid-cols-1 gap-4">
                        <?php foreach ($atProducts as $product): ?>
                            <?php view('components/bundle-card-guest', ['product' => $product, 'network' => 'at']); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Telecel Card -->
            <div class="group relative bg-[#0f172a] rounded-[40px] border border-white/5 overflow-hidden transition-all duration-500 hover:border-indigo-500/30 hover:shadow-2xl hover:shadow-indigo-500/10 <?php echo ($configs['enable_telecel_purchase'] ?? '1') === '0' ? 'opacity-50 grayscale' : ''; ?>">
                <div class="absolute top-0 left-0 w-full h-1 bg-red-600"></div>
                <div class="p-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-20 h-20 rounded-3xl bg-red-600/10 flex items-center justify-center border border-red-600/20 group-hover:scale-110 transition-transform duration-500">
                            <img src="/images/telecel.png" alt="Telecel" class="h-12 w-12 object-contain">
                        </div>
                        <?php if (($configs['enable_telecel_purchase'] ?? '1') === '1'): ?>
                        <button class="network-toggle w-12 h-12 flex items-center justify-center rounded-2xl bg-white/5 text-red-400 hover:bg-red-600 hover:text-white transition-all" data-network="telecel">
                            <i class="fas fa-chevron-down transform transition-transform duration-300"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-3xl font-black text-white italic mb-3">Telecel</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed mb-10">The most flexible data options for the modern user.</p>
                    
                    <?php if (($configs['enable_telecel_purchase'] ?? '1') === '1'): ?>
                    <button class="main-buy-btn w-full bg-red-600 text-white font-black py-5 rounded-[24px] hover:bg-red-500 transition-all shadow-xl shadow-red-600/10" data-network="telecel">Buy Now</button>
                    <?php else: ?>
                    <button disabled class="w-full bg-white/5 text-slate-600 font-black py-5 rounded-[24px] cursor-not-allowed">Offline</button>
                    <?php endif; ?>
                </div>

                <div id="telecel-products" class="hidden border-t border-white/5 bg-white/[0.01] p-6 max-h-[500px] overflow-y-auto products-container">
                    <div class="grid grid-cols-1 gap-4">
                        <?php foreach ($telecelProducts as $product): ?>
                            <?php view('components/bundle-card-guest', ['product' => $product, 'network' => 'telecel']); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Features -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-10 rounded-[40px] bg-white/[0.02] border border-white/5 flex flex-col items-center text-center transition-all duration-300 hover:bg-white/[0.04]">
                <div class="w-16 h-16 bg-indigo-500/10 rounded-2xl flex items-center justify-center mb-6 text-indigo-400">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h4 class="text-xl font-black text-white mb-2 italic">Secure Gateway</h4>
                <p class="text-slate-500 text-sm font-medium">Bank-grade encryption for every transaction you make.</p>
            </div>
            <div class="p-10 rounded-[40px] bg-white/[0.02] border border-white/5 flex flex-col items-center text-center transition-all duration-300 hover:bg-white/[0.04]">
                <div class="w-16 h-16 bg-green-500/10 rounded-2xl flex items-center justify-center mb-6 text-green-400">
                    <i class="fas fa-history text-2xl"></i>
                </div>
                <h4 class="text-xl font-black text-white mb-2 italic">Fast Delivery</h4>
                <p class="text-slate-500 text-sm font-medium">Automated processing ensures your data lands in seconds.</p>
            </div>
            <div class="p-10 rounded-[40px] bg-white/[0.02] border border-white/5 flex flex-col items-center text-center transition-all duration-300 hover:bg-white/[0.04]">
                <div class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center mb-6 text-amber-400">
                    <i class="fas fa-headset text-2xl"></i>
                </div>
                <h4 class="text-xl font-black text-white mb-2 italic">Direct Support</h4>
                <p class="text-slate-500 text-sm font-medium">Real humans available to help you via our support hub.</p>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($waLink)): ?>
<!-- Floating WhatsApp Support -->
<a href="<?php echo htmlspecialchars($waLink); ?>" target="_blank" class="fixed bottom-8 right-8 z-[100] group">
    <div class="absolute -inset-4 bg-green-500/20 rounded-full blur-xl group-hover:bg-green-500/40 transition duration-500 animate-pulse"></div>
    <div class="relative w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-green-500/20 group-hover:scale-110 group-hover:-rotate-12 transition-all duration-500 border border-white/10">
        <i class="fab fa-whatsapp text-white text-3xl"></i>
        <div class="absolute -top-2 -right-2 w-5 h-5 bg-indigo-500 rounded-full border-2 border-[#020617] flex items-center justify-center">
            <div class="w-1.5 h-1.5 bg-white rounded-full animate-ping"></div>
        </div>
    </div>
    
    <!-- Tooltip -->
    <div class="absolute right-full mr-4 top-1/2 -translate-y-1/2 px-4 py-2 bg-[#0f172a] border border-white/5 rounded-xl text-white text-xs font-black italic whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
        Chat with Support
    </div>
</a>
<?php endif; ?>

<?php view('components/purchase-modal-guest'); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function toggleNetwork(network, button) {
        const container = document.getElementById(`${network}-products`);
        const toggleBtn = document.querySelector(`.network-toggle[data-network="${network}"]`);
        const icon = toggleBtn.querySelector('i');
        
        container.classList.toggle('hidden');
        if (!container.classList.contains('hidden')) {
            icon.style.transform = 'rotate(180deg)';
            container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            icon.style.transform = 'rotate(0deg)';
        }
    }

    document.querySelectorAll('.network-toggle').forEach(button => {
        button.addEventListener('click', function() {
            toggleNetwork(this.getAttribute('data-network'), this);
        });
    });

    document.querySelectorAll('.main-buy-btn').forEach(button => {
        button.addEventListener('click', function() {
            toggleNetwork(this.getAttribute('data-network'), this);
        });
    });

    document.body.addEventListener('click', function(e) {
        const buyBtn = e.target.closest('.buy-now-btn');
        if (buyBtn) {
            const productId = buyBtn.getAttribute('data-product-id');
            const productName = buyBtn.getAttribute('data-product-name');
            const productPrice = buyBtn.getAttribute('data-product-price');
            const productNetwork = buyBtn.getAttribute('data-product-network');
            if (window.showPurchaseModal) {
                window.showPurchaseModal(productId, productName, productPrice, productNetwork);
            }
        }
    });
});
</script>