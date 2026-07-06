<div class="pb-12 max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-black text-white italic tracking-tighter">Settings</h1>
        <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.3em] mt-1">Configure your website basics</p>
        <div class="w-16 h-1 bg-indigo-600 mx-auto mt-6 rounded-full shadow-[0_0_10px_rgba(79,70,229,0.4)]"></div>
    </div>

    <!-- Messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>document.addEventListener('DOMContentLoaded', () => { if(typeof showToast === 'function') showToast('Saved', '<?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>', 'success'); });</script>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <script>document.addEventListener('DOMContentLoaded', () => { if(typeof showToast === 'function') showToast('Error', '<?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>', 'error'); });</script>
    <?php endif; ?>

    <div class="bg-[#0f172a] rounded-[40px] border border-white/5 shadow-2xl overflow-hidden">
        <div class="px-8 py-8 border-b border-white/5 bg-white/[0.01]">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                    <i class="fas fa-sliders-h"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-white italic">General Settings</h2>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Basic app information</p>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-10">
            <form action="<?php echo rtrim(APP_URL, '/'); ?>/admin/settings" method="POST" class="space-y-10">
                
                <!-- Section: Website Info -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.3em] flex items-center gap-3">
                        <span class="w-8 h-px bg-indigo-500/30"></span>
                        Website Details
                    </h3>
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-3 ml-2">App Name (Website Title)</label>
                        <div class="relative">
                            <input type="text" name="app_name" value="<?php echo htmlspecialchars($settings['app_name'] ?? ''); ?>" class="w-full px-6 py-5 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white text-lg" placeholder="Data Portal">
                        </div>
                    </div>
                </div>

                <!-- Section: Payment Keys -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-purple-400 uppercase tracking-[0.3em] flex items-center gap-3">
                        <span class="w-8 h-px bg-purple-500/30"></span>
                        Payment API Keys
                    </h3>
                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-4">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2">Paystack API Keys</label>
                            <input type="text" name="paystack_public_key" value="<?php echo htmlspecialchars($settings['paystack_public_key'] ?? ''); ?>" class="w-full px-6 py-4 bg-white/[0.02] border border-white/5 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 outline-none transition-all font-bold text-white text-sm" placeholder="Paystack Public Key">
                            <input type="password" name="paystack_secret_key" value="<?php echo htmlspecialchars($settings['paystack_secret_key'] ?? ''); ?>" class="w-full px-6 py-4 bg-white/[0.02] border border-white/5 rounded-2xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 outline-none transition-all font-bold text-white text-sm" placeholder="Paystack Secret Key">
                        </div>
                    </div>
                </div>

                <!-- Section: Telegram -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-teal-400 uppercase tracking-[0.3em] flex items-center gap-3">
                        <span class="w-8 h-px bg-teal-500/30"></span>
                        Telegram Bot Tokens
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Chat ID (Group ID)</label>
                            <input type="text" name="telegram_chat_id" value="<?php echo htmlspecialchars($settings['telegram_chat_id'] ?? ''); ?>" class="w-full px-6 py-4 bg-white/[0.03] border border-transparent rounded-2xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all font-bold text-white text-sm" placeholder="Enter Chat ID">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php
                            $bot_tokens = [
                                'manual_alert_bot_token' => 'Manual Bot Token',
                                'auto_alert_bot_token' => 'Auto Bot Token',
                                'processing_alert_bot_token' => 'Processing Bot Token',
                                'delivered_alert_bot_token' => 'Delivery Bot Token',
                                'failed_alert_bot_token' => 'Error Bot Token',
                            ];
                            foreach ($bot_tokens as $key => $label):
                            ?>
                            <div>
                                <label class="block text-[9px] font-black text-slate-600 uppercase tracking-widest mb-2 ml-2"><?php echo $label; ?></label>
                                <input type="password" name="<?php echo $key; ?>" value="<?php echo htmlspecialchars($settings[$key] ?? ''); ?>" class="w-full px-5 py-3 bg-white/[0.02] border border-white/5 rounded-xl focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 outline-none transition-all font-bold text-white text-xs" placeholder="API Token">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Section: Contact -->
                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.3em] flex items-center gap-3">
                        <span class="w-8 h-px bg-emerald-500/30"></span>
                        Support Contacts
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" name="contact_phone" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>" class="w-full px-5 py-4 bg-white/[0.02] border border-white/5 rounded-2xl text-white font-bold text-xs" placeholder="Phone Number">
                        <input type="text" name="contact_whatsapp" value="<?php echo htmlspecialchars($settings['contact_whatsapp'] ?? ''); ?>" class="w-full px-5 py-4 bg-white/[0.02] border border-white/5 rounded-2xl text-white font-bold text-xs" placeholder="WhatsApp Link/Number">
                        <input type="email" name="email_contact" value="<?php echo htmlspecialchars($settings['email_contact'] ?? ''); ?>" class="w-full px-5 py-4 bg-white/[0.02] border border-white/5 rounded-2xl text-white font-bold text-xs" placeholder="Email Address">
                    </div>
                </div>

                <div class="pt-10 flex justify-center border-t border-white/5">
                    <button type="submit" class="bg-indigo-600 text-white font-black px-12 py-5 rounded-[24px] shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all flex items-center gap-3 group">
                        <span class="text-lg">Save All Settings</span>
                        <i class="fas fa-save text-sm group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>