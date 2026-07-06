<div class="pb-12 max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-10">
        <h1 class="text-4xl font-black text-white italic tracking-tighter">Status Uplink</h1>
        <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.3em] mt-1">Global Broadcast System</p>
    </div>

    <div class="space-y-8">
        <!-- Input Area -->
        <div class="bg-[#0f172a] rounded-[40px] border border-white/5 p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-indigo-500/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                        <i class="fas fa-edit text-xs"></i>
                    </div>
                    <h3 class="text-xl font-black text-white italic tracking-tight">Compose Broadcast</h3>
                </div>

                <div class="space-y-2">
                    <label for="service-status-message" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2">Public Message</label>
                    <textarea id="service-status-message" name="service_status_message" rows="5" 
                        class="w-full px-6 py-5 bg-white/[0.03] border border-transparent rounded-[32px] focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white text-base placeholder:font-normal placeholder:text-slate-600 leading-relaxed"
                        placeholder="State current system status..."><?php echo htmlspecialchars($service_status_message ?? ''); ?></textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" id="save-status-btn" class="bg-indigo-600 text-white font-black px-10 py-4 rounded-2xl shadow-xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all flex items-center gap-3 group">
                        <span class="text-sm uppercase tracking-widest">Transmit Update</span>
                        <i class="fas fa-paper-plane text-xs group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Preview Area -->
        <div class="space-y-4">
            <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] ml-4 flex items-center gap-3">
                <span class="w-8 h-px bg-white/10"></span>
                Uplink Preview
            </h3>
            <div class="bg-white/[0.02] border border-dashed border-white/10 rounded-[32px] p-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-indigo-500/5 to-transparent"></div>
                <div id="status-preview" class="relative z-10 text-xl font-black text-white italic text-center leading-relaxed">
                    <?php echo htmlspecialchars($service_status_message ?? 'NO ACTIVE BROADCAST'); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo asset('js/admin/service-status.js'); ?>"></script>