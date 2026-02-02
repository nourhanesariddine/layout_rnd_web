import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Initialize toasts on page load with modern animations
document.addEventListener('DOMContentLoaded', function() {
    // Show success toast
    const successToast = document.getElementById('successToast');
    if (successToast) {
        const toast = new bootstrap.Toast(successToast, {
            animation: true,
            autohide: true,
            delay: 4000
        });
        toast.show();
    }

    // Show warning toast
    const warningToast = document.getElementById('warningToast');
    if (warningToast) {
        const toast = new bootstrap.Toast(warningToast, {
            animation: true,
            autohide: true,
            delay: 6000
        });
        toast.show();
    }

    // Show error toast
    const errorToast = document.getElementById('errorToast');
    if (errorToast) {
        const toast = new bootstrap.Toast(errorToast, {
            animation: true,
            autohide: true,
            delay: 6000
        });
        toast.show();
    }
    
    // Remove toast element after it's hidden for cleanup
    const toastElements = document.querySelectorAll('.modern-toast');
    toastElements.forEach(element => {
        element.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    });
});