<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Admin Control'; ?></title>
    <link rel="icon" href="<?php echo asset('favicon-data.svg'); ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';
    </script>
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }

        body { background-color: #020617; color: #cbd5e1; overflow-x: hidden; }
        
        /* Sidebar Base Styles */
        .admin-sidebar {
            width: 280px;
            z-index: 1010;
            background-color: #0f172a;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .admin-content {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Desktop States */
        @media (min-width: 1024px) {
            .sidebar-open .admin-sidebar { width: 280px; transform: translateX(0); }
            .sidebar-open .admin-content { margin-left: 280px; }
            
            .sidebar-closed .admin-sidebar { width: 88px; transform: translateX(0); }
            .sidebar-closed .admin-content { margin-left: 88px; }

            /* Hide text in closed state */
            .sidebar-closed .sidebar-label,
            .sidebar-closed .sidebar-header-text,
            .sidebar-closed .sidebar-footer-text {
                opacity: 0;
                visibility: hidden;
                width: 0;
                display: none;
            }
            
            .sidebar-closed .nav-item-content {
                justify-content: center;
                padding-left: 0;
                padding-right: 0;
            }
            
            .sidebar-closed .nav-icon {
                margin: 0 !important;
                font-size: 1.25rem;
            }
        }

        /* Mobile States */
        @media (max-width: 1023px) {
            .admin-sidebar {
                transform: translateX(-100%);
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 280px !important;
            }
            .sidebar-open .admin-sidebar {
                transform: translateX(0);
            }
            .admin-content {
                margin-left: 0 !important;
            }
        }

        /* Smooth Label Transitions */
        .sidebar-label {
            transition: opacity 0.2s ease;
            white-space: nowrap;
        }
    </style>
    <script>
        // Apply sidebar state immediately to prevent flicker
        (function() {
            const state = localStorage.getItem('admin_sidebar_state') || 'open';
            if (window.innerWidth >= 1024) {
                document.documentElement.classList.add('sidebar-' + state);
                // Also add to body once it exists
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.classList.add('sidebar-' + state);
                });
            } else {
                document.documentElement.classList.add('sidebar-closed');
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.classList.add('sidebar-closed');
                });
            }
        })();
    </script>
</head>
<body class="antialiased">
    <!-- Admin Sidebar -->
    <aside id="admin-sidebar" class="admin-sidebar fixed top-0 left-0 h-screen flex flex-col overflow-hidden">
        <div class="p-6 border-b border-white/5 h-20 flex items-center shrink-0">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex-shrink-0 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                    <span class="text-white font-black italic text-lg"><?php echo htmlspecialchars(strtoupper($appName[0] ?? 'A')); ?></span>
                </div>
                <div class="sidebar-header-text">
                    <h2 class="text-white font-black italic tracking-tighter text-xl whitespace-nowrap">Admin</h2>
                    <p class="text-[9px] text-slate-500 uppercase tracking-[0.3em] font-bold">Control</p>
                </div>
            </div>
        </div>

        <nav class="flex-grow overflow-y-auto overflow-x-hidden py-6">
            <?php view('components/admin-sidebar'); ?>
        </nav>

        <div class="p-4 border-t border-white/5 shrink-0">
            <a href="/logout" class="flex items-center gap-4 p-4 rounded-2xl text-slate-400 hover:text-red-400 hover:bg-red-500/5 transition-all group overflow-hidden nav-item-content">
                <i class="fas fa-power-off flex-shrink-0 w-6 text-center nav-icon group-hover:scale-110 transition-transform"></i>
                <span class="sidebar-label sidebar-footer-text font-bold text-sm whitespace-nowrap">Terminate</span>
            </a>
        </div>
    </aside>

    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[1005] hidden"></div>

    <!-- Main Wrapper -->
    <div class="admin-content">
        <!-- Top Nav -->
        <header class="h-20 border-b border-white/5 flex items-center justify-between px-6 md:px-10 bg-[#020617]/80 backdrop-blur-md sticky top-0 z-40">
            <div class="flex items-center gap-4">
                <button id="toggle-sidebar" class="p-3 rounded-xl bg-white/5 text-slate-400 hover:text-white hover:bg-white/10 transition-all border border-white/5">
                    <i class="fas fa-bars-staggered"></i>
                </button>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden md:flex flex-col items-end mr-2 text-right">
                    <span class="text-sm font-black text-white italic">Administrator</span>
                    <span class="text-[10px] text-green-500 font-bold uppercase tracking-widest flex items-center gap-1.5 justify-end">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        Live System
                    </span>
                </div>
                <div class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 md:p-8">
            <div class="max-w-7xl mx-auto">
                <?php require $content; ?>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleBtn = document.getElementById('toggle-sidebar');
            const body = document.body;

            function updateSidebarState(newState) {
                if (newState === 'open') {
                    body.classList.add('sidebar-open');
                    body.classList.remove('sidebar-closed');
                } else {
                    body.classList.add('sidebar-closed');
                    body.classList.remove('sidebar-open');
                }
                
                if (window.innerWidth >= 1024) {
                    localStorage.setItem('admin_sidebar_state', newState);
                }
            }

            toggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const isNowOpen = body.classList.contains('sidebar-open');
                updateSidebarState(isNowOpen ? 'closed' : 'open');
                
                if (window.innerWidth < 1024) {
                    if (body.classList.contains('sidebar-open')) {
                        overlay.classList.remove('hidden');
                        body.style.overflow = 'hidden';
                    } else {
                        overlay.classList.add('hidden');
                        body.style.overflow = '';
                    }
                }
            });

            overlay.addEventListener('click', function() {
                updateSidebarState('closed');
                overlay.classList.add('hidden');
                body.style.overflow = '';
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    overlay.classList.add('hidden');
                    body.style.overflow = '';
                    const savedState = localStorage.getItem('admin_sidebar_state') || 'open';
                    updateSidebarState(savedState);
                } else {
                    updateSidebarState('closed');
                }
            });
        });
    </script>
</body>
</html>