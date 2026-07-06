document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const ref = urlParams.get('ref');
    if (ref) {
        Swal.fire({
            title: 'Payment Successful!',
            text: `Your transaction with reference ${ref} was successful.`,
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            // Remove the ref from the URL
            window.history.replaceState({}, document.title, "/" + window.location.pathname.split("/")[1]);
        });
    }
});
