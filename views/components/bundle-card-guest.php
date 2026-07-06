<?php 
// Define network-specific colors for dark mode
$network_colors = [
    'mtn' => [
        'bg' => 'bg-amber-400',
        'hover_bg' => 'hover:bg-amber-500',
        'text' => 'text-amber-950',
        'price_text' => 'text-amber-400',
        'border' => 'border-amber-400/10'
    ],
    'at' => [
        'bg' => 'bg-blue-600',
        'hover_bg' => 'hover:bg-blue-700',
        'text' => 'text-white',
        'price_text' => 'text-blue-400',
        'border' => 'border-blue-400/10'
    ],
    'telecel' => [
        'bg' => 'bg-red-600',
        'hover_bg' => 'hover:bg-red-700',
        'text' => 'text-white',
        'price_text' => 'text-red-400',
        'border' => 'border-red-400/10'
    ]
];

$network = strtolower($network ?? $product['network'] ?? 'default');
$colors = $network_colors[$network] ?? [
    'bg' => 'bg-indigo-600',
    'hover_bg' => 'hover:bg-indigo-700',
    'text' => 'text-white',
    'price_text' => 'text-indigo-400',
    'border' => 'border-indigo-400/10'
];
?>
<div class="bundle-card-container group bg-white/[0.03] rounded-2xl p-4 border border-white/5 hover:border-white/10 transition-all duration-300">
    <div class="flex items-center justify-between gap-4">
        <div class="flex-grow">
            <h3 class="text-base font-black text-white leading-tight mb-1 italic">
                <?php echo htmlspecialchars($product['name']); ?>
            </h3>
            <div class="flex items-center gap-2">
                <span class="text-lg font-black <?php echo $colors['price_text']; ?>">
                    <?php echo format_currency($product['customer_price']); ?>
                </span>
                <span class="text-[10px] text-slate-600 line-through font-bold">
                    <?php echo format_currency($product['customer_price'] * 1.15); ?>
                </span>
            </div>
        </div>

        <div class="flex-shrink-0">
            <button class="buy-now-btn <?php echo $colors['bg']; ?> <?php echo $colors['text']; ?> font-black py-2.5 px-6 rounded-xl text-sm transition-all duration-300 shadow-lg shadow-black/20 hover:scale-105 active:scale-95 flex items-center gap-2" 
                    data-product-id="<?php echo htmlspecialchars($product['id']); ?>"
                    data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                    data-product-price="<?php echo format_currency($product['customer_price']); ?>"
                    data-product-network="<?php echo htmlspecialchars($product['network']); ?>">
                <span>Buy</span>
                <i class="fas fa-bolt text-[10px]"></i>
            </button>
        </div>
    </div>
</div>