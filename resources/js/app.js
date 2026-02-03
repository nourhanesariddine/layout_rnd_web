import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;


document.addEventListener('DOMContentLoaded', function() {
    const successToast = document.getElementById('successToast');
    if (successToast) {
        const toast = new bootstrap.Toast(successToast, {
            animation: true,
            autohide: true,
            delay: 4000
        });
        toast.show();
    }

    const warningToast = document.getElementById('warningToast');
    if (warningToast) {
        const toast = new bootstrap.Toast(warningToast, {
            animation: true,
            autohide: true,
            delay: 6000
        });
        toast.show();
    }

    const errorToast = document.getElementById('errorToast');
    if (errorToast) {
        const toast = new bootstrap.Toast(errorToast, {
            animation: true,
            autohide: true,
            delay: 6000
        });
        toast.show();
    }
    
    const toastElements = document.querySelectorAll('.modern-toast');
    toastElements.forEach(element => {
        element.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    });

    const searchForm = document.getElementById('searchForm');
    if (searchForm && searchForm.dataset.searchRoute && searchForm.dataset.perPage) {
        if (typeof initSearch === 'function') {
            initSearch(searchForm.dataset.searchRoute, parseInt(searchForm.dataset.perPage));
        }
    }

    initializeTooltips();
    initializeDeleteModals();
    initializeCSVFileHandler();
});


window.initSearch = function(searchRoute, perPage) {
    const searchForm = document.getElementById('searchForm');
    const resultsContainer = document.getElementById('resultsContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const clearBtn = document.getElementById('clearBtn');
    
    if (!searchForm || !resultsContainer) {
        return;
    }
    
    let searchTimeout;

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        performSearch(1);
    });

    const nameInput = document.getElementById('name');
    const phoneInput = document.getElementById('phone');
    const departmentSelect = document.getElementById('department_id');

    if (nameInput && phoneInput && departmentSelect) {
        [nameInput, phoneInput, departmentSelect].forEach(element => {
            element.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    if (nameInput.value || phoneInput.value || departmentSelect.value) {
                        performSearch(1);
                    } else {
                        clearResults();
                    }
                }, 500);
            });
        });

        departmentSelect.addEventListener('change', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                if (nameInput.value || phoneInput.value || departmentSelect.value) {
                    performSearch(1);
                } else {
                    clearResults();
                }
            }, 300);
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            searchForm.reset();
            clearResults();
        });
    }

    function performSearch(page = 1) {
        const formData = new FormData(searchForm);
        formData.append('page', page);
        formData.append('per_page', perPage);
        
        loadingIndicator.style.display = 'block';
        resultsContainer.style.display = 'none';

        fetch(searchRoute, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingIndicator.style.display = 'none';
            resultsContainer.style.display = 'block';
            
            if (data.success) {
                resultsContainer.innerHTML = data.html;
                attachPaginationListeners();
                initializeTooltips();
            } else {
                resultsContainer.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>Error performing search. Please try again.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loadingIndicator.style.display = 'none';
            resultsContainer.style.display = 'block';
            resultsContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>An error occurred. Please try again.
                </div>
            `;
        });
    }

    function attachPaginationListeners() {
        const paginationLinks = document.querySelectorAll('.pagination-link');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = this.getAttribute('data-page');
                performSearch(page);
            });
        });
    }

    function initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    function clearResults() {
        resultsContainer.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-search display-1 text-muted d-block mb-3"></i>
                <h5 class="text-muted">Start Searching</h5>
                <p class="text-muted">Use the filters above to search for contacts.</p>
            </div>
        `;
    }
};

function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function initializeDeleteModals() {
    const deleteModals = document.querySelectorAll('#deleteModal, [id*="deleteModal"]');
    
    deleteModals.forEach(deleteModal => {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            let id, name, nameSelector, formSelector;

            if (button.hasAttribute('data-contact-id')) {
                id = button.getAttribute('data-contact-id');
                name = button.getAttribute('data-contact-name');
                nameSelector = '#contactNameToDelete';
                formSelector = '#deleteContactForm';
            } else if (button.hasAttribute('data-user-id')) {
                id = button.getAttribute('data-user-id');
                name = button.getAttribute('data-user-name');
                nameSelector = '#userNameToDelete';
                formSelector = '#deleteUserForm';
            } else if (button.hasAttribute('data-department-id')) {
                id = button.getAttribute('data-department-id');
                name = button.getAttribute('data-department-name');
                nameSelector = '#departmentNameToDelete';
                formSelector = '#deleteDepartmentForm';
            } else {
                id = button.getAttribute('data-id');
                name = button.getAttribute('data-name');
                nameSelector = '[id$="NameToDelete"]';
                formSelector = deleteModal.querySelector('form');
            }

            if (name) {
                const nameSpan = deleteModal.querySelector(nameSelector);
                if (nameSpan) {
                    nameSpan.textContent = name;
                }
            }

            if (id) {
                const form = formSelector ? (typeof formSelector === 'string' ? deleteModal.querySelector(formSelector) : formSelector) : deleteModal.querySelector('form');
                if (form) {
                    const baseAction = form.getAttribute('data-base-action');
                    if (baseAction) {
                        form.action = baseAction.replace(':id', id);
                    }
                }
            }
        });
    });
}

function initializeCSVFileHandler() {
    const csvFileInput = document.getElementById('csv_file');
    if (csvFileInput) {
        csvFileInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
            }
        });
    }
}