document.addEventListener('DOMContentLoaded', () => {
    const saveBtn = document.getElementById('save-status-btn');
    const messageTextarea = document.getElementById('service-status-message');
    const previewDiv = document.getElementById('status-preview');

    if (saveBtn) {
        saveBtn.addEventListener('click', async (event) => {
            event.preventDefault();
            
            const message = messageTextarea.value;
            const originalHtml = saveBtn.innerHTML;
            
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Transmitting...';

            try {
                const response = await fetch(`${BASE_URL}/api/admin/service-status/update/`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();

                if (data.success) {
                    previewDiv.textContent = message || 'NO ACTIVE BROADCAST';
                    if (typeof showToast === 'function') {
                        showToast('Uplink Success', 'Global status has been updated.', 'success');
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast('Transmit Failed', data.message || 'Check server permissions.', 'error');
                    }
                }
            } catch (err) {
                if (typeof showToast === 'function') {
                    showToast('Connection Error', 'Uplink synchronization failed.', 'error');
                }
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalHtml;
            }
        });
    }
});