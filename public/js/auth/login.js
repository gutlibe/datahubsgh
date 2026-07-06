document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitButton = loginForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authorizing...';
            submitButton.disabled = true;

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch(`${BASE_URL}/api/auth/login/`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password }),
                });

                const data = await response.json();

                if (data.success) {
                    if (typeof showToast === 'function') {
                        showToast('Access Granted', 'Redirecting to your dashboard...', 'success');
                    }
                    setTimeout(() => {
                        if (data.role === 'admin') {
                            window.location.href = `${BASE_URL}/admin/dashboard`;
                        } else {
                            window.location.href = `${BASE_URL}/dashboard`;
                        }
                    }, 1000);
                } else {
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                    if (typeof showToast === 'function') {
                        showToast('Access Denied', data.message || 'Invalid credentials.', 'error');
                    }
                }
            } catch (err) {
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
                if (typeof showToast === 'function') {
                    showToast('System Error', 'Unable to reach authentication server.', 'error');
                }
            }
        });
    }
});