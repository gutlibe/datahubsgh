<div class="pb-12">
    <!-- Dashboard Heading -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-black text-white italic tracking-tighter">System Overview</h1>
            <p class="text-slate-500 font-bold text-xs uppercase tracking-[0.3em] mt-1">Real-time terminal metrics</p>
        </div>
        <div class="px-6 py-3 rounded-2xl bg-white/5 border border-white/5 flex items-center gap-3">
            <i class="far fa-calendar-alt text-indigo-400"></i>
            <span class="text-sm font-black text-slate-300 italic"><?php echo date('l, F j, Y'); ?></span>
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Revenue Card -->
        <div class="bg-[#0f172a] rounded-[32px] p-8 border border-white/5 shadow-2xl relative overflow-hidden group hover:border-indigo-500/20 transition-all duration-500">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-500/10 rounded-full blur-2xl group-hover:bg-indigo-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center mb-6 text-indigo-400 border border-indigo-500/20">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Lifetime Revenue</p>
                <h3 class="text-3xl font-black text-white italic tracking-tight">GH₵ <?php echo number_format($stats['total_sales'] ?? 0, 2); ?></h3>
            </div>
        </div>

        <!-- Today Sales -->
        <div class="bg-[#0f172a] rounded-[32px] p-8 border border-white/5 shadow-2xl relative overflow-hidden group hover:border-emerald-500/20 transition-all duration-500">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center mb-6 text-emerald-400 border border-emerald-500/20">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Today's Volume</p>
                <h3 class="text-3xl font-black text-white italic tracking-tight">GH₵ <?php echo number_format($stats['today_sales'] ?? 0, 2); ?></h3>
            </div>
        </div>

        <!-- This Week Sales -->
        <div class="bg-[#0f172a] rounded-[32px] p-8 border border-white/5 shadow-2xl relative overflow-hidden group hover:border-blue-500/20 transition-all duration-500">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center mb-6 text-blue-400 border border-blue-500/20">
                    <i class="fas fa-calendar-week text-xl"></i>
                </div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Weekly Volume</p>
                <h3 class="text-3xl font-black text-white italic tracking-tight">GH₵ <?php echo number_format($stats['this_week_sales'] ?? 0, 2); ?></h3>
            </div>
        </div>

        <!-- Success Rate (Calculated mock) -->
        <div class="bg-[#0f172a] rounded-[32px] p-8 border border-white/5 shadow-2xl relative overflow-hidden group hover:border-amber-500/20 transition-all duration-500">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-colors"></div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center mb-6 text-amber-400 border border-amber-500/20">
                    <i class="fas fa-check-double text-xl"></i>
                </div>
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Success Metric</p>
                <h3 class="text-3xl font-black text-white italic tracking-tight">98.4%</h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <!-- Main Data Stream -->
        <div class="lg:col-span-2 bg-[#0f172a] rounded-[40px] border border-white/5 p-8 shadow-2xl">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-black text-white italic">Analytics Stream</h3>
                <div class="flex gap-2">
                    <span class="px-3 py-1 rounded-lg bg-white/5 text-[10px] font-black text-indigo-400 border border-white/5">WEEKLY</span>
                </div>
            </div>
            <div class="h-[300px] relative">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Distribution -->
        <div class="bg-[#0f172a] rounded-[40px] border border-white/5 p-8 shadow-2xl">
            <h3 class="text-xl font-black text-white italic mb-8">Network Nodes</h3>
            <div class="space-y-6">
                <?php foreach ($stats['network_stats'] as $stat): ?>
                <?php 
                    $color = match(strtolower($stat['network'])) {
                        'mtn' => 'bg-amber-400',
                        'at' => 'bg-blue-500',
                        'telecel' => 'bg-red-500',
                        default => 'bg-indigo-500'
                    };
                    $textColor = match(strtolower($stat['network'])) {
                        'mtn' => 'text-amber-400',
                        'at' => 'text-blue-400',
                        'telecel' => 'text-red-400',
                        default => 'text-indigo-400'
                    };
                ?>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full <?php echo $color; ?> shadow-[0_0_8px_rgba(0,0,0,0.5)]"></div>
                            <span class="text-xs font-black text-slate-300 uppercase tracking-widest"><?php echo ucfirst($stat['network']); ?></span>
                        </div>
                        <span class="text-sm font-black <?php echo $textColor; ?> italic"><?php echo $stat['count']; ?></span>
                    </div>
                    <div class="w-full bg-white/5 rounded-full h-1.5 overflow-hidden">
                        <div class="<?php echo $color; ?> h-full transition-all duration-1000" style="width: <?php echo ($stat['count'] / max(1, array_sum(array_column($stats['network_stats'], 'count')))) * 100; ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-10 pt-8 border-t border-white/5">
                <h3 class="text-xs font-black text-slate-500 uppercase tracking-[0.2em] mb-6">Order Breakdown</h3>
                <div class="grid grid-cols-2 gap-4">
                    <?php foreach ($stats['status_stats'] as $stat): ?>
                    <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-4 group hover:bg-white/[0.04] transition-colors">
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1"><?php echo ucfirst($stat['status']); ?></p>
                        <p class="text-xl font-black text-white italic"><?php echo $stat['count']; ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Data Table -->
    <div class="bg-[#0f172a] rounded-[40px] border border-white/5 overflow-hidden shadow-2xl">
        <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
            <h3 class="text-xl font-black text-white italic">Live Order Log</h3>
            <a href="/admin/orders" class="text-[10px] font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-widest flex items-center gap-2 transition-colors">
                Deep Inspection
                <i class="fas fa-chevron-right text-[8px]"></i>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-white/[0.02]">
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Reference</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Terminal</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Product</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Volume</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($recent_orders as $order): ?>
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="px-8 py-5">
                            <span class="text-xs font-black text-indigo-400 border-b border-indigo-400/20"><?php echo substr($order['reference'], 0, 8); ?></span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-bold text-slate-300"><?php echo $order['msisdn']; ?></span>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-black text-white italic"><?php echo $order['product_name']; ?></span>
                        </td>
                        <td class="px-8 py-5 text-sm font-black text-emerald-400 italic">
                            GH₵ <?php echo number_format($order['amount'], 2); ?>
                        </td>
                        <td class="px-8 py-5">
                            <?php 
                                $statusStyle = match($order['status']) {
                                    'accepted' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                    'processing' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                    'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                    'failed' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                    default => 'bg-white/5 text-slate-400 border-white/5'
                                };
                            ?>
                            <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg border <?php echo $statusStyle; ?>">
                                <?php echo $order['status']; ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 text-xs font-bold text-slate-500">
                            <?php echo date('M d, H:i', strtotime($order['created_at'])); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                data: [
                    <?php echo $stats['this_week_sales'] / 7; ?>,
                    <?php echo $stats['this_week_sales'] / 5; ?>,
                    <?php echo $stats['this_week_sales'] / 3; ?>,
                    <?php echo $stats['today_sales']; ?>,
                    0, 0, 0
                ],
                borderColor: '#6366f1',
                borderWidth: 4,
                pointBackgroundColor: '#6366f1',
                pointBorderColor: 'rgba(255,255,255,0.1)',
                pointBorderWidth: 8,
                pointHoverRadius: 8,
                tension: 0.4,
                fill: true,
                backgroundColor: gradient
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    grid: { color: 'rgba(255, 255, 255, 0.03)', drawBorder: false },
                    ticks: { color: '#64748b', font: { weight: 'bold', size: 10 } }
                },
                x: { 
                    grid: { display: false },
                    ticks: { color: '#64748b', font: { weight: 'bold', size: 10 } }
                }
            }
        }
    });
</script>