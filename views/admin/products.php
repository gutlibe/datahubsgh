<div class="pb-12">
    <!-- Page Header -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-white italic tracking-tighter">Inventory</h1>
            <p class="text-slate-500 font-bold text-[10px] uppercase tracking-[0.3em] mt-1">Catalog & Volume Control</p>
        </div>
        
        <button id="add-product-btn" class="bg-indigo-600 text-white font-black px-8 py-4 rounded-[22px] shadow-xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all flex items-center gap-3 group">
            <i class="fas fa-plus text-xs group-hover:rotate-90 transition-transform"></i>
            <span class="text-sm">Deploy Product</span>
        </button>
    </div>

    <!-- Interface Controls -->
    <div class="bg-[#0f172a] rounded-[32px] border border-white/5 p-2 mb-8 shadow-2xl flex flex-col md:flex-row items-center gap-2">
        <div class="flex flex-wrap items-center gap-1 p-1 w-full md:w-auto">
            <button class="tab-button px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all active-tab border border-transparent" data-network="mtn">MTN GH</button>
            <button class="tab-button px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border border-transparent text-slate-500 hover:text-white" data-network="at">AirtelTigo</button>
            <button class="tab-button px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all border border-transparent text-slate-500 hover:text-white" data-network="telecel">Telecel</button>
        </div>
        
        <div class="md:ml-auto flex items-center gap-2 p-2 w-full md:w-auto">
            <div id="bulk-controls" class="flex items-center gap-2 bg-white/5 rounded-[20px] px-4 py-2 border border-white/5">
                <select id="bulk-action" class="bg-transparent border-none text-slate-400 text-[10px] font-black uppercase tracking-widest focus:ring-0 cursor-pointer">
                    <option value="" class="bg-[#0f172a]">Bulk Action</option>
                    <option value="enable" class="bg-[#0f172a]">Activate</option>
                    <option value="disable" class="bg-[#0f172a]">Deactivate</option>
                    <option value="delete" class="bg-[#0f172a]">Purge</option>
                </select>
                <button id="bulk-apply-btn" class="text-indigo-400 font-black text-[10px] uppercase tracking-widest hover:text-indigo-300">Run</button>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-[#0f172a] rounded-[40px] border border-white/5 overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="min-w-full" id="products-table">
                <thead>
                    <tr class="bg-white/[0.02]">
                        <th class="px-8 py-5 text-left w-10">
                            <input type="checkbox" id="select-all" class="w-5 h-5 bg-white/5 border-white/10 rounded-lg text-indigo-600 focus:ring-indigo-500/20">
                        </th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Deployment Name</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Node</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Volume</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Rate (Cust)</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Rate (Agent)</th>
                        <th class="px-6 py-5 text-left text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Uptime</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Control</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($products as $product): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors group product-row" data-id="<?php echo $product['id']; ?>" data-network="<?php echo htmlspecialchars($product['network']); ?>">
                            <td class="px-8 py-5">
                                <input type="checkbox" class="select-row w-5 h-5 bg-white/5 border-white/10 rounded-lg text-indigo-600 focus:ring-indigo-500/20">
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="name text-sm font-black text-white italic"><?php echo htmlspecialchars($product['name']); ?></span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="network px-3 py-1 rounded-lg bg-white/5 border border-white/5 text-[9px] font-black text-slate-400 uppercase tracking-widest"><?php echo htmlspecialchars($product['network']); ?></span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="volume text-sm font-bold text-slate-300"><?php echo htmlspecialchars($product['volume']); ?></span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="customer_price text-sm font-black text-emerald-400 italic">GH₵ <?php echo number_format($product['customer_price'], 2); ?></span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="agent_price text-sm font-black text-indigo-400 italic">GH₵ <?php echo number_format($product['agent_price'], 2); ?></span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="validity text-[10px] font-bold text-slate-500 italic"><?php echo htmlspecialchars($product['validity']); ?></span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <div class="is_available hidden"><?php echo $product['is_available'] ? 'Yes' : 'No'; ?></div>
                                    <div class="w-2 h-2 rounded-full <?php echo $product['is_available'] ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.4)]' : 'bg-red-500'; ?>"></div>
                                    <button class="edit-btn p-2.5 rounded-xl bg-white/5 text-slate-400 hover:text-white hover:bg-white/10 transition-all border border-white/5">
                                        <i class="fas fa-pen-nib text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Dark Product Modal -->
<div id="product-modal" class="fixed z-[1050] inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
    <div class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity" aria-hidden="true"></div>
    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
    <div class="inline-block align-middle bg-[#0f172a] rounded-[40px] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full border border-white/10">
      <div class="px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-2xl font-black text-white italic tracking-tight" id="modal-title-text">Product Details</h3>
            <button id="cancel-btn-x" class="p-2 rounded-xl bg-white/5 text-slate-400 hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="product-form" class="space-y-6">
            <input type="hidden" id="product-id" name="id">
            
            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Product Name</label>
                <input type="text" name="name" id="name" class="w-full px-6 py-4 bg-white/[0.03] border border-transparent rounded-2xl focus:bg-white/[0.05] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all font-bold text-white" placeholder="e.g. MTN 10GB" required>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Network</label>
                    <select name="network" id="network" class="w-full px-6 py-4 bg-white/[0.03] border border-transparent rounded-2xl text-white font-bold outline-none focus:ring-4 focus:ring-indigo-500/10">
                        <option value="mtn" class="bg-[#0f172a]">MTN</option>
                        <option value="at" class="bg-[#0f172a]">AirtelTigo</option>
                        <option value="telecel" class="bg-[#0f172a]">Telecel</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Data Volume</label>
                    <input type="text" name="volume" id="volume" class="w-full px-6 py-4 bg-white/[0.03] border border-transparent rounded-2xl font-bold text-white outline-none focus:ring-4 focus:ring-indigo-500/10" placeholder="10GB" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Customer Price</label>
                    <input type="number" step="0.01" name="customer_price" id="customer_price" class="w-full px-6 py-4 bg-white/[0.03] border border-transparent rounded-2xl font-bold text-emerald-400 outline-none focus:ring-4 focus:ring-emerald-500/10" placeholder="0.00" required>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Agent Price</label>
                    <input type="number" step="0.01" name="agent_price" id="agent_price" class="w-full px-6 py-4 bg-white/[0.03] border border-transparent rounded-2xl font-bold text-indigo-400 outline-none focus:ring-4 focus:ring-indigo-500/10" placeholder="0.00" required>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Validity</label>
                    <select name="validity" id="validity" class="w-full px-6 py-4 bg-white/[0.03] border border-transparent rounded-2xl text-white font-bold outline-none focus:ring-4 focus:ring-indigo-500/10">
                        <option value="60 days" class="bg-[#0f172a]">60 Days</option>
                        <option value="Non expiry" class="bg-[#0f172a]">Non-Expiry</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 ml-2">Available</label>
                    <select name="is_available" id="is_available" class="w-full px-6 py-4 bg-white/[0.03] border border-transparent rounded-2xl text-white font-bold outline-none focus:ring-4 focus:ring-indigo-500/10">
                        <option value="1" class="bg-[#0f172a]">Yes</option>
                        <option value="0" class="bg-[#0f172a]">No</option>
                    </select>
                </div>
            </div>

            <div class="pt-4">
                <button type="button" id="save-product-btn" class="w-full bg-indigo-600 text-white font-black py-5 rounded-[24px] shadow-2xl shadow-indigo-600/20 hover:bg-indigo-500 transition-all flex items-center justify-center gap-3 group">
                    <span class="text-lg">Save Product</span>
                    <i class="fas fa-check-circle text-sm group-hover:scale-110 transition-transform"></i>
                </button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
    .active-tab {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.2);
        color: #818cf8;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('product-modal');
    const addProductBtn = document.getElementById('add-product-btn');
    const cancelBtn = document.getElementById('cancel-btn-x');
    const saveProductBtn = document.getElementById('save-product-btn');
    const productForm = document.getElementById('product-form');
    const modalTitle = document.getElementById('modal-title-text');
    const productIdField = document.getElementById('product-id');
    
    function openModal() {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        productForm.reset();
        productIdField.value = '';
    }
    
    addProductBtn.addEventListener('click', () => {
        modalTitle.textContent = 'New Deployment';
        openModal();
    });
    
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            modalTitle.textContent = 'Edit Specs';
            const row = e.target.closest('.product-row');
            const id = row.dataset.id;
            productIdField.value = id;
            document.getElementById('name').value = row.querySelector('.name').textContent;
            
            const networkText = row.querySelector('.network').textContent.toLowerCase();
            document.getElementById('network').value = networkText.includes('mtn') ? 'mtn' : (networkText.includes('airtel') || networkText === 'at' ? 'at' : 'telecel');
            
            document.getElementById('volume').value = row.querySelector('.volume').textContent;
            document.getElementById('customer_price').value = row.querySelector('.customer_price').textContent.replace('GH₵ ', '').replace(',', '');
            document.getElementById('agent_price').value = row.querySelector('.agent_price').textContent.replace('GH₵ ', '').replace(',', '');
            document.getElementById('validity').value = row.querySelector('.validity').textContent;
            document.getElementById('is_available').value = row.querySelector('.is_available').textContent === 'Yes' ? '1' : '0';
            openModal();
        });
    });
    
    cancelBtn.addEventListener('click', closeModal);
    
    saveProductBtn.addEventListener('click', async () => {
        const id = productIdField.value;
        const formData = new FormData(productForm);
        const data = Object.fromEntries(formData.entries());
        
        for (const key in data) {
            if (data[key] === '' && key !== 'id') {
                if(typeof showToast === 'function') showToast('Incomplete', 'Please fill all parameters', 'info');
                return;
            }
        }
        
        const url = id ? `${BASE_URL}/api/product/update/` : `${BASE_URL}/api/product/create/`;
        const body = id ? { id, data } : { data };
        
        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            });
            const result = await res.json();
            if (result.success) {
                if(typeof showToast === 'function') showToast('Success', result.message, 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                if(typeof showToast === 'function') showToast('Error', result.message, 'error');
            }
        } catch (error) {
            if(typeof showToast === 'function') showToast('Critical', 'System sync failed', 'error');
        }
    });
    
    // Tabs & Bulk Actions
    const selectAll = document.getElementById('select-all');
    const selectRows = document.querySelectorAll('.select-row');
    const bulkApplyBtn = document.getElementById('bulk-apply-btn');
    
    selectAll?.addEventListener('change', function () {
        selectRows.forEach(checkbox => checkbox.checked = this.checked);
    });
    
    bulkApplyBtn?.addEventListener('click', async function () {
        const selectedIds = Array.from(selectRows).filter(cb => cb.checked).map(cb => cb.closest('.product-row').dataset.id);
        const action = document.getElementById('bulk-action').value;
        
        if (selectedIds.length === 0 || !action) {
            if(typeof showToast === 'function') showToast('Action Required', 'Select units and operation', 'info');
            return;
        }
        
        const apiUrl = action === 'delete' ? `${BASE_URL}/api/product/bulk-delete/` : `${BASE_URL}/api/product/bulk-update/`;
        const body = action === 'delete' ? { ids: selectedIds } : { ids: selectedIds, action };

        try {
            const res = await fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body),
            });
            const data = await res.json();
            if (data.success) {
                if(typeof showToast === 'function') showToast('Executed', 'Bulk operation successful', 'success');
                setTimeout(() => location.reload(), 800);
            } else {
                if(typeof showToast === 'function') showToast('Execution Failed', data.message, 'error');
            }
        } catch (error) {
            if(typeof showToast === 'function') showToast('Network Error', 'Sync interrupted', 'error');
        }
    });

    const tabButtons = document.querySelectorAll('.tab-button');
    const productRows = document.querySelectorAll('.product-row');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            tabButtons.forEach(btn => btn.classList.remove('active-tab'));
            this.classList.add('active-tab');
            const network = this.dataset.network;
            productRows.forEach(row => {
                row.style.display = (row.dataset.network === network) ? '' : 'none';
            });
        });
    });

    // Initialize with MTN tab active
    document.querySelector('.tab-button[data-network="mtn"]').click();
});
</script>