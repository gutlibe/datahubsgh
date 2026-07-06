document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitButton = registerForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            submitButton.disabled = true;

            const first_name = document.getElementById('first_name').value;
            const last_name = document.getElementById('last_name').value;
            const phone_number = document.getElementById('phone_number').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch(`${BASE_URL}/api/auth/register/`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ first_name, last_name, phone_number, email, password }),
                });

                const data = await response.json();

                if (data.success) {
                    if (typeof showToast === 'function') {
                        showToast('Welcome!', 'Account created successfully. Redirecting...', 'success');
                    }
                    setTimeout(() => {
                        window.location.href = `${BASE_URL}/login`;
                    }, 1500);
                } else {
                    submitButton.innerHTML = originalButtonText;
                    submitButton.disabled = false;
                    if (typeof showToast === 'function') {
                        showToast('Registration Failed', data.message || 'Please check your details.', 'error');
                    }
                }
            } catch (err) {
                submitButton.innerHTML = originalButtonText;
                submitButton.disabled = false;
                if (typeof showToast === 'function') {
                    showToast('Connection Error', 'Unable to reach the server.', 'error');
                }
            }
        });
    }
});