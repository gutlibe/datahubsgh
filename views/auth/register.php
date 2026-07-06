<div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Background Glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-indigo-500/10 blur-[120px] rounded-full"></div>

    <div class="relative bg-[#0f172a] p-10 rounded-[40px] shadow-2xl w-full max-w-lg border border-white/5">
        <div class="text-center mb-10">
            <div class="mx-auto bg-indigo-600/10 border border-indigo-500/20 rounded-3xl w-20 h-20 flex items-center justify-center mb-6">
                <i class="fas fa-user-plus text-indigo-400 text-3xl"></i>
            </div>
            <h2 class="text-3xl font-black text-white italic tracking-tight">Create Account</h2>
            <p class="text-slate-500 mt-2 font-medium">Join our premium data network</p>
        </div>

        <form id="registerForm" class="space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="first_name" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">First Name</label>
                    <div class="relative group">
                        <input type="text" id="first_name" name="first_name" class="w-full px-6 py-4 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white placeholder:font-normal placeholder:text-slate-600" placeholder="John" required>
                    </div>
                </div>
                <div>
                    <label for="last_name" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Last Name</label>
                    <div class="relative group">
                        <input type="text" id="last_name" name="last_name" class="w-full px-6 py-4 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white placeholder:font-normal placeholder:text-slate-600" placeholder="Doe" required>
                    </div>
                </div>
            </div>

            <div>
                <label for="email" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Email Address</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-envelope text-slate-600 group-focus-within:text-indigo-400 transition-colors"></i>
                    </div>
                    <input type="email" id="email" name="email" class="w-full pl-12 pr-5 py-4 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white placeholder:font-normal placeholder:text-slate-600" placeholder="john@example.com" required>
                </div>
            </div>

            <div>
                <label for="phone_number" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Phone Number</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-slate-600 group-focus-within:text-indigo-400 transition-colors"></i>
                    </div>
                    <input type="text" id="phone_number" name="phone_number" class="w-full pl-12 pr-5 py-4 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white placeholder:font-normal placeholder:text-slate-600" placeholder="024 000 0000" required>
                </div>
            </div>

            <div>
                <label for="password" class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="fas fa-key text-slate-600 group-focus-within:text-indigo-400 transition-colors"></i>
                    </div>
                    <input type="password" id="password" name="password" class="w-full pl-12 pr-5 py-4 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white placeholder:font-normal placeholder:text-slate-600" placeholder="••••••••" required>
                </div>
            </div>

            <div class="flex items-center ml-2">
                <input id="terms" type="checkbox" class="w-5 h-5 bg-white/5 border-white/10 rounded-lg text-indigo-600 focus:ring-indigo-500/20 focus:ring-offset-0" required>
                <label for="terms" class="ml-3 block text-sm text-slate-400 font-medium">I agree to the <a href="javascript:void(0);" class="text-indigo-400 font-bold hover:underline">Terms & Conditions</a></label>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-black py-5 rounded-[24px] shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all flex items-center justify-center gap-3 group">
                <span>Create My Account</span>
                <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
            </button>
        </form>

        <div class="mt-10 text-center">
            <p class="text-slate-500 text-sm font-medium">
                Already member? 
                <a href="<?php echo rtrim(APP_URL, '/'); ?>/login" class="text-indigo-400 font-black hover:text-indigo-300 transition-colors">Access Portal</a>
            </p>
        </div>

        <div id="message" class="mt-6 text-center text-sm font-bold"></div>
    </div>
</div>
<script src="<?php echo asset('js/auth/register.js'); ?>"></script>