// ==================== Global Utilities ====================

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function for scroll events
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// ==================== Image Lazy Loading ====================

class LazyLoader {
    constructor() {
        this.images = document.querySelectorAll('img[data-src]');
        this.init();
    }

    init() {
        if ('IntersectionObserver' in window) {
            this.observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadImage(entry.target);
                    }
                });
            }, {
                rootMargin: '50px'
            });

            this.images.forEach(img => this.observer.observe(img));
        } else {
            // Fallback for older browsers
            this.images.forEach(img => this.loadImage(img));
        }
    }

    loadImage(img) {
        const src = img.getAttribute('data-src');
        if (!src) return;

        img.src = src;
        img.removeAttribute('data-src');
        img.classList.add('loaded');

        if (this.observer) {
            this.observer.unobserve(img);
        }
    }
}

// ==================== Form Validation ====================

class FormValidator {
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        if (this.form) {
            this.init();
        }
    }

    init() {
        this.form.addEventListener('submit', (e) => {
            if (!this.validate()) {
                e.preventDefault();
            }
        });

        // Real-time validation
        const inputs = this.form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', debounce(() => this.validateField(input), 500));
        });
    }

    validate() {
        let isValid = true;
        const inputs = this.form.querySelectorAll('[required]');

        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        const type = field.type;
        let isValid = true;
        let message = '';

        // Required check
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            message = 'This field is required';
        }

        // Email validation
        if (type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                message = 'Please enter a valid email address';
            }
        }

        // Phone validation
        if (type === 'tel' && value) {
            const phoneRegex = /^[\d\s\-\+\(\)]+$/;
            if (!phoneRegex.test(value) || value.length < 10) {
                isValid = false;
                message = 'Please enter a valid phone number';
            }
        }

        // Min length
        if (field.hasAttribute('minlength')) {
            const minLength = parseInt(field.getAttribute('minlength'));
            if (value.length < minLength) {
                isValid = false;
                message = `Minimum ${minLength} characters required`;
            }
        }

        // Max length
        if (field.hasAttribute('maxlength')) {
            const maxLength = parseInt(field.getAttribute('maxlength'));
            if (value.length > maxLength) {
                isValid = false;
                message = `Maximum ${maxLength} characters allowed`;
            }
        }

        this.showFieldStatus(field, isValid, message);
        return isValid;
    }

    showFieldStatus(field, isValid, message) {
        // Remove previous error
        const existingError = field.parentElement.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Remove error classes
        field.classList.remove('border-red-500', 'border-green-500');

        if (!isValid && message) {
            // Add error styling
            field.classList.add('border-red-500');

            // Create error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message text-red-500 text-sm mt-1';
            errorDiv.textContent = message;
            field.parentElement.appendChild(errorDiv);
        } else if (isValid && field.value.trim()) {
            field.classList.add('border-green-500');
        }
    }
}

// ==================== Search Functionality ====================

class SearchHandler {
    constructor(inputSelector, resultsSelector) {
        this.input = document.querySelector(inputSelector);
        this.resultsContainer = document.querySelector(resultsSelector);

        if (this.input && this.resultsContainer) {
            this.init();
        }
    }

    init() {
        this.input.addEventListener('input', debounce((e) => {
            this.search(e.target.value);
        }, 300));

        // Close results when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.input.contains(e.target) && !this.resultsContainer.contains(e.target)) {
                this.hideResults();
            }
        });
    }

    async search(query) {
        if (query.length < 2) {
            this.hideResults();
            return;
        }

        this.showLoading();

        try {
            const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            this.displayResults(data);
        } catch (error) {
            console.error('Search error:', error);
            this.showError('Search failed. Please try again.');
        }
    }

    displayResults(results) {
        if (!results || results.length === 0) {
            this.resultsContainer.innerHTML = '<div class="p-4 text-gray-500">No results found</div>';
        } else {
            this.resultsContainer.innerHTML = results.map(item => `
                <a href="${item.url}" class="block p-4 hover:bg-gray-50 transition-colors">
                    <div class="font-semibold">${item.name}</div>
                    <div class="text-sm text-gray-600">${item.type}</div>
                </a>
            `).join('');
        }
        this.showResults();
    }

    showLoading() {
        this.resultsContainer.innerHTML = '<div class="p-4 text-center"><div class="loading-spinner mx-auto"></div></div>';
        this.showResults();
    }

    showError(message) {
        this.resultsContainer.innerHTML = `<div class="p-4 text-red-500">${message}</div>`;
        this.showResults();
    }

    showResults() {
        this.resultsContainer.classList.remove('hidden');
    }

    hideResults() {
        this.resultsContainer.classList.add('hidden');
    }
}

// ==================== Filter System ====================

class FilterManager {
    constructor(filterSelector, itemSelector) {
        this.filters = document.querySelectorAll(filterSelector);
        this.items = document.querySelectorAll(itemSelector);

        if (this.filters.length && this.items.length) {
            this.init();
        }
    }

    init() {
        this.filters.forEach(filter => {
            filter.addEventListener('click', (e) => {
                e.preventDefault();
                const category = filter.dataset.category;
                this.filter(category);
                this.updateActiveFilter(filter);
            });
        });
    }

    filter(category) {
        this.items.forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = '';
                item.classList.add('animate-fadeIn');
            } else {
                item.style.display = 'none';
            }
        });
    }

    updateActiveFilter(activeFilter) {
        this.filters.forEach(filter => {
            filter.classList.remove('active', 'bg-[#F46A06]', 'text-white');
            filter.classList.add('bg-gray-100', 'text-gray-700');
        });

        activeFilter.classList.remove('bg-gray-100', 'text-gray-700');
        activeFilter.classList.add('active', 'bg-[#F46A06]', 'text-white');
    }
}

// ==================== Smooth Scroll ====================

function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            e.preventDefault();
            const target = document.querySelector(href);

            if (target) {
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// ==================== Loading State Manager ====================

class LoadingManager {
    static show(element, message = 'Loading...') {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }

        if (!element) return;

        const loadingHTML = `
            <div class="loading-overlay absolute inset-0 bg-white/90 flex items-center justify-center z-50">
                <div class="text-center">
                    <div class="loading-spinner mx-auto mb-4"></div>
                    <p class="text-gray-600">${message}</p>
                </div>
            </div>
        `;

        element.style.position = 'relative';
        element.insertAdjacentHTML('beforeend', loadingHTML);
    }

    static hide(element) {
        if (typeof element === 'string') {
            element = document.querySelector(element);
        }

        if (!element) return;

        const overlay = element.querySelector('.loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    }
}

// ==================== Toast Notifications (Alternative) ====================

class Toast {
    static show(message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-8 right-8 px-6 py-4 rounded-xl shadow-2xl z-[9999] transform translate-y-full transition-all duration-300 ${this.getTypeClass(type)}`;
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <i class="fas ${this.getIcon(type)} text-xl"></i>
                <span class="font-semibold">${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.remove('translate-y-full');
            toast.classList.add('translate-y-0');
        }, 10);

        setTimeout(() => {
            toast.classList.remove('translate-y-0');
            toast.classList.add('translate-y-full');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    static getTypeClass(type) {
        const classes = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            warning: 'bg-yellow-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        return classes[type] || classes.info;
    }

    static getIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    }
}

// ==================== Local Storage Helper ====================

class StorageHelper {
    static set(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch (e) {
            console.error('Storage set error:', e);
            return false;
        }
    }

    static get(key, defaultValue = null) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (e) {
            console.error('Storage get error:', e);
            return defaultValue;
        }
    }

    static remove(key) {
        try {
            localStorage.removeItem(key);
            return true;
        } catch (e) {
            console.error('Storage remove error:', e);
            return false;
        }
    }

    static clear() {
        try {
            localStorage.clear();
            return true;
        } catch (e) {
            console.error('Storage clear error:', e);
            return false;
        }
    }
}

// ==================== Initialize on DOM Ready ====================

document.addEventListener('DOMContentLoaded', () => {
    // Initialize lazy loading
    if (document.querySelectorAll('img[data-src]').length) {
        new LazyLoader();
    }

    // Initialize smooth scroll
    initSmoothScroll();

    // Initialize forms with validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        new FormValidator(`#${form.id}`);
    });

    // Initialize search if search box exists
    if (document.querySelector('#searchInput')) {
        new SearchHandler('#searchInput', '#searchResults');
    }

    // Initialize filters
    if (document.querySelectorAll('[data-filter]').length) {
        new FilterManager('[data-filter]', '[data-category]');
    }

    // Add hover effects to cards
    document.querySelectorAll('.hover-lift').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '';
        });
    });

    // Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });

    console.log('HFfinder initialized successfully! 🎉');
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        debounce,
        throttle,
        LazyLoader,
        FormValidator,
        SearchHandler,
        FilterManager,
        LoadingManager,
        Toast,
        StorageHelper
    };
}
