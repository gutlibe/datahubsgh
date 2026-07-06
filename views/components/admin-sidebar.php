<ul class="space-y-2 px-6">
    <?php
    $navItems = [
        ['url' => 'admin/dashboard', 'icon' => 'fas fa-th-large', 'label' => 'Overview'],
        ['url' => 'admin/orders', 'icon' => 'fas fa-shopping-cart', 'label' => 'Order Log'],
        ['url' => 'admin/products', 'icon' => 'fas fa-box', 'label' => 'Inventory'],
        ['url' => 'admin/users', 'icon' => 'fas fa-users', 'label' => 'User Hub'],
        ['url' => 'admin/providers', 'icon' => 'fas fa-network-wired', 'label' => 'Gateways'],
        ['url' => 'admin/settings', 'icon' => 'fas fa-sliders-h', 'label' => 'General'],
        ['url' => 'admin/configurations', 'icon' => 'fas fa-cog', 'label' => 'System Config'],
        ['url' => 'admin/service-status', 'icon' => 'fas fa-signal', 'label' => 'Uptime'],
    ];

    // Get current path more reliably
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $currentPath = ltrim($currentPath, '/');
    
    foreach ($navItems as $item):
        // Robust active check: either exact match or starts with the URL
        $isActive = ($currentPath === $item['url'] || strpos($currentPath, $item['url'] . '/') === 0);
    ?>
    <li class="relative">
        <a href="<?php echo rtrim(APP_URL, '/'); ?>/<?php echo $item['url']; ?>" 
           class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-300 group nav-item-content relative overflow-hidden <?php echo $isActive ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/40' : 'text-slate-400 hover:text-white hover:bg-white/5'; ?>">
            
            <?php if ($isActive): ?>
            <!-- Active Pill -->
            <div class="absolute left-0 top-0 w-1 h-full bg-white/40"></div>
            <?php endif; ?>

            <i class="<?php echo $item['icon']; ?> w-6 text-center nav-icon <?php echo $isActive ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400'; ?> transition-colors"></i>
            <span class="font-bold text-sm tracking-tight sidebar-label whitespace-nowrap"><?php echo $item['label']; ?></span>
            
            <?php if ($isActive): ?>
            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-white animate-pulse sidebar-label"></div>
            <?php endif; ?>
        </a>
    </li>
    <?php endforeach; ?>
</ul>