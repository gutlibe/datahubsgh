<!DOCTYPE html>
<html lang="en">
<head>
    <base href="/">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? $appName . ' - Buy Data Bundles Online'; ?></title>
    <meta name="description" content="Buy MTN, AirtelTigo (AT), and Telecel data bundles on the <?php echo $appName; ?> portal. Get affordable data packages delivered to your phone in minutes. Fast, secure, and reliable data purchasing.">
    <link rel="icon" href="<?php echo asset('favicon-data.svg'); ?>" type="image/svg+xml">
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const BASE_URL = '<?php echo BASE_URL; ?>';

        // Lightweight Toast System
        function showToast(title, message, type = 'info') {
            const container = document.getElementById('toast-container');
            if (!container) return;
            
            const toast = document.createElement('div');
            toast.className = 'toast-item';
            
            const types = {
                success: { bg: 'bg-green-500/10', text: 'text-green-400', icon: 'fa-check-circle' },
                error: { bg: 'bg-red-500/10', text: 'text-red-400', icon: 'fa-times-circle' },
                info: { bg: 'bg-indigo-500/10', text: 'text-indigo-400', icon: 'fa-info-circle' }
            };
            const config = types[type] || types.info;

            toast.innerHTML = `
                <div class="toast-icon ${config.bg} ${config.text}">
                    <i class="fas ${config.icon} text-xl"></i>
                </div>
                <div class="flex-grow">
                    <div class="text-sm font-black text-white">${title}</div>
                    <div class="text-[11px] text-slate-400 font-medium">${message}</div>
                </div>
            `;

            container.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 10);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, 4000);
        }
    </script>
    <style>
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }
        .toast-item {
            pointer-events: auto;
            min-width: 280px;
            max-width: 350px;
            background: #1e293b;
            border-radius: 1.25rem;
            padding: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            color: white;
        }
        .toast-item.show { transform: translateX(0); }
        .toast-icon {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Custom Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #020617;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
        
        /* Specialized scrollbar for the product lists */
        .products-container::-webkit-scrollbar {
            width: 4px;
        }
        .products-container::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-[#020617] text-slate-200 selection:bg-indigo-500/30">
    <div id="toast-container" class="toast-container"></div>
    <?php view('components/header'); ?>
    <main class="container mx-auto mt-10">
        <?php require $content; ?>
    </main>
    <script src="<?php echo asset('js/main.js'); ?>"></script>
</body>
</html>
