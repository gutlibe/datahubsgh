<div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background Glows -->
    <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-indigo-500/10 blur-[120px] rounded-full"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-violet-500/10 blur-[120px] rounded-full"></div>

    <div class="relative text-center max-w-lg">
        <h1 class="text-[150px] md:text-[200px] font-black leading-none text-transparent bg-clip-text bg-gradient-to-b from-indigo-500 to-transparent opacity-20 italic">404</h1>
        
        <div class="-mt-16 md:-mt-24 space-y-6">
            <h2 class="text-4xl font-black text-white italic tracking-tighter">Path Not Found.</h2>
            <p class="text-slate-500 text-lg font-medium leading-relaxed">
                The terminal coordinate you requested does not exist or has been relocated within the network.
            </p>
            
            <div class="pt-8">
                <a href="<?php echo rtrim(APP_URL, '/'); ?>" class="inline-flex items-center gap-3 bg-indigo-600 text-white font-black px-10 py-5 rounded-[24px] shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all group uppercase text-xs tracking-[0.2em]">
                    <i class="fas fa-home text-sm group-hover:-translate-y-0.5 transition-transform"></i>
                    Return Home
                </a>
            </div>
        </div>
    </div>
</div>