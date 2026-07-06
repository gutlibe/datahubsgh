<div class="min-h-screen pb-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-12">
        <div class="text-center mb-16">
            <h1 class="text-5xl font-black text-white italic tracking-tighter mb-4">Help & Support</h1>
            <p class="text-slate-400 text-lg font-medium">Everything you need to know about our services</p>
            <div class="w-16 h-1 bg-indigo-600 mx-auto mt-8 rounded-full"></div>
        </div>

        <div class="space-y-8">
            <!-- FAQ Section -->
            <div class="bg-[#0f172a] rounded-[40px] border border-white/5 p-10 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-500/5 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
                
                <h2 class="text-2xl font-black text-white italic mb-8 flex items-center gap-4">
                    <span class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20">
                        <i class="fas fa-question text-sm"></i>
                    </span>
                    Common Questions
                </h2>

                <div class="space-y-10">
                    <div>
                        <h4 class="text-white font-black text-lg mb-3 italic">Delivery Time</h4>
                        <p class="text-slate-400 leading-relaxed font-medium">Delivery is lightning fast, but occasionally there might be slight delays due to network congestion.</p>
                    </div>
                    <div>
                        <h4 class="text-white font-black text-lg mb-3 italic">Working Hours</h4>
                        <p class="text-slate-400 leading-relaxed font-medium">We operate <span class="text-indigo-400 font-black">24/7</span>. All orders are processed automatically at any time of the day.</p>
                    </div>
                    <div>
                        <h4 class="text-white font-black text-lg mb-3 italic">Payment & Refunds</h4>
                        <p class="text-slate-400 leading-relaxed font-medium">If a payment is successful but data isn't delivered, our system will automatically flag it for a refund or retry.</p>
                    </div>
                </div>
            </div>

            <!-- Contact Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white/[0.02] border border-white/5 rounded-[32px] p-8 flex flex-col items-center text-center group hover:bg-white/[0.04] transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-green-500/10 flex items-center justify-center text-green-400 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fab fa-whatsapp text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-white italic mb-2">WhatsApp Support</h4>
                    <p class="text-slate-500 text-sm font-medium mb-6">Fastest response for urgent issues.</p>
                    <?php 
                        $waValue = trim($settings['contact_whatsapp'] ?? '');
                        if (preg_match('/^https?:\/\//', $waValue)) {
                            $waLink = $waValue;
                        } elseif (preg_match('/^[0-9+\s]+$/', $waValue)) {
                            $waLink = "https://wa.me/" . preg_replace('/[^0-9]/', '', $waValue);
                        } else {
                            $waLink = "https://" . ltrim($waValue, '/');
                        }
                    ?>
                    <a href="<?php echo htmlspecialchars($waLink); ?>" target="_blank" class="px-8 py-3 rounded-xl bg-green-600 text-white font-black text-xs uppercase tracking-widest hover:bg-green-500 transition-colors shadow-lg shadow-green-600/10">Chat Now</a>
                </div>

                <div class="bg-white/[0.02] border border-white/5 rounded-[32px] p-8 flex flex-col items-center text-center group hover:bg-white/[0.04] transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-400 mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-envelope text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-black text-white italic mb-2">Email Desk</h4>
                    <p class="text-slate-500 text-sm font-medium mb-6">For formal inquiries and refunds.</p>
                    <a href="mailto:<?php echo htmlspecialchars($settings['email_contact'] ?? ''); ?>" class="px-8 py-3 rounded-xl bg-indigo-600 text-white font-black text-xs uppercase tracking-widest hover:bg-indigo-500 transition-colors shadow-lg shadow-indigo-600/10">Send Mail</a>
                </div>
            </div>
        </div>
    </div>
</div>