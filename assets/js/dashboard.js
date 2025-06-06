// Utility functions
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

// Main initialization function that can be called both on load and after AJAX
function initializeDashboard() {
    // Theme initialization
    if (localStorage.getItem('woodash-theme') === 'light') document.body.classList.add('light');

    // Initialize UI with debounced functions
    const debouncedInitializeUI = debounce(initializeUI, 250);
    debouncedInitializeUI();

    // Initialize slideshow
    initializeSlideshow();

    // Initialize date range picker if it exists
    if (document.getElementById('date-range')) {
        flatpickr("#date-range", {
            mode: "range",
            dateFormat: "Y-m-d"
        });
    }

    // Initialize all event listeners
    initializeEventListeners();
    
    // Initialize charts
    initializeCharts();
    
    // Initialize tooltips
    initializeTooltips();
    
    // Initialize background animation
    initializeBackgroundAnimation();
    
    // Initialize orbs
    initializeOrbs();

    // Fetch initial dashboard data
    fetchDashboardData();
}

// Function to initialize all event listeners
function initializeEventListeners() {
    // Light/Dark mode toggle
    const toggleMode = document.getElementById('toggle-mode');
    if (toggleMode) {
        function updateModeButton() {
            const isLight = document.body.classList.contains('light');
            document.getElementById('toggle-mode-icon').textContent = isLight ? '☀️' : '🌙';
            document.getElementById('toggle-mode-text').textContent = isLight ? 'Light Mode' : 'Dark Mode';
        }

        toggleMode.addEventListener('click', function() {
            document.body.classList.toggle('light');
            localStorage.setItem('woodash-theme', document.body.classList.contains('light') ? 'light' : 'dark');
            updateModeButton();
        });
        updateModeButton();
    }

    // CSV Export
    const exportCsv = document.getElementById('export-csv');
    if (exportCsv) {
        exportCsv.addEventListener('click', function() {
            let dateRange = document.getElementById('date-range').value.split(' to ');
            this.disabled = true;
            this.textContent = 'Exporting...';
            window.location = woodash_ajax.ajax_url +
                '?action=woodash_export_csv&nonce=' + woodash_ajax.nonce +
                '&date_from=' + (dateRange[0] || '') +
                '&date_to=' + (dateRange[1] || '');
            setTimeout(() => {
                this.disabled = false;
                this.textContent = 'Export CSV';
            }, 2000);
        });
    }

    // Date filter
    const applyDate = document.getElementById('apply-date');
    if (applyDate) {
        applyDate.addEventListener('click', function() {
            fetchDashboardData();
        });
    }

    // Chart range toggle
    Array.from(document.getElementsByClassName('woodash-range-btn')).forEach(btn => {
        btn.addEventListener('click', function() {
            currentGranularity = this.getAttribute('data-range');
            Array.from(document.getElementsByClassName('woodash-range-btn')).forEach(b => b.classList.remove('bg-blue-700'));
            this.classList.add('bg-blue-700');
            fetchDashboardData();
        });
    });

    // Export buttons
    const exportProductsCsv = document.getElementById('export-products-csv');
    if (exportProductsCsv) {
        exportProductsCsv.addEventListener('click', handleExportClick);
    }

    const exportCustomersCsv = document.getElementById('export-customers-csv');
    if (exportCustomersCsv) {
        exportCustomersCsv.addEventListener('click', handleExportClick);
    }
}

// Handle export button clicks
function handleExportClick() {
    let dateRange = document.getElementById('date-range').value.split(' to ');
    let granularity = currentGranularity;
    this.disabled = true;
    this.textContent = 'Exporting...';
    window.location = woodash_ajax.ajax_url +
        '?action=' + (this.id === 'export-products-csv' ? 'woodash_export_products_csv' : 'woodash_export_customers_csv') +
        '&nonce=' + woodash_ajax.nonce +
        '&date_from=' + (dateRange[0] || '') +
        '&date_to=' + (dateRange[1] || '') +
        '&granularity=' + granularity;
    setTimeout(() => {
        this.disabled = false;
        this.textContent = 'Export CSV';
    }, 2000);
}

// Initialize on DOM content loaded
document.addEventListener('DOMContentLoaded', initializeDashboard);

// Listen for AJAX content updates
document.addEventListener('woodashContentUpdated', initializeDashboard);

// Slideshow initialization
function initializeSlideshow() {
    const slideshow = document.querySelector('.woodash-slideshow');
    if (!slideshow) return;

    const slides = slideshow.querySelectorAll('.woodash-slide');
    const dots = document.querySelectorAll('.woodash-slide-dot');
    const prevBtn = document.querySelector('.woodash-slide-prev');
    const nextBtn = document.querySelector('.woodash-slide-next');
    
    let currentSlide = 0;
    let slideInterval;
    const slideDuration = 5000; // 5 seconds per slide

    // Function to show a specific slide
    function showSlide(index) {
        // Hide all slides
        slides.forEach(slide => {
            slide.style.opacity = '0';
            slide.style.transform = 'translateX(100%)';
            slide.style.zIndex = '0';
        });

        // Show the current slide
        slides[index].style.opacity = '1';
        slides[index].style.transform = 'translateX(0)';
        slides[index].style.zIndex = '1';

        // Update dots
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-[#00CC61]', i === index);
            dot.classList.toggle('bg-gray-300', i !== index);
        });

        currentSlide = index;
    }

    // Function to show next slide
    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }

    // Function to show previous slide
    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }

    // Add click event listeners to dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            clearInterval(slideInterval);
            showSlide(index);
            startSlideshow();
        });
    });

    // Add click event listeners to navigation buttons
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            clearInterval(slideInterval);
            prevSlide();
            startSlideshow();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            clearInterval(slideInterval);
            nextSlide();
            startSlideshow();
        });
    }

    // Function to start the slideshow
    function startSlideshow() {
        slideInterval = setInterval(nextSlide, slideDuration);
    }

    // Add hover pause functionality
    slideshow.addEventListener('mouseenter', () => {
        clearInterval(slideInterval);
    });

    slideshow.addEventListener('mouseleave', () => {
        startSlideshow();
    });

    // Add touch swipe functionality
    let touchStartX = 0;
    let touchEndX = 0;

    slideshow.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    });

    slideshow.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe left
                nextSlide();
            } else {
                // Swipe right
                prevSlide();
            }
        }
    }

    // Add CSS transitions
    slides.forEach(slide => {
        slide.style.transition = 'all 0.5s ease-in-out';
        slide.style.position = 'absolute';
        slide.style.width = '100%';
        slide.style.height = '100%';
    });

    // Show first slide and start slideshow
    showSlide(0);
    startSlideshow();
}

// Chart initialization
function initializeCharts() {
    // Sales Chart
    const salesCtx = document.getElementById('sales-chart');
    if (salesCtx) {
        new Chart(salesCtx.getContext('2d'), {
            type: 'line',
            data: { labels: [], datasets: [{ label: 'Sales', data: [], borderColor: '#38A169', backgroundColor: 'rgba(56,161,105,0.1)' }] },
            options: {
                elements: {
                    line: { borderColor: '#38A169', backgroundColor: 'rgba(56,161,105,0.1)' }
                },
                responsive: true,
                plugins: { legend: { display: false } }
            }
        });
    }

    // Orders Chart
    const ordersCtx = document.getElementById('orders-chart');
    if (ordersCtx) {
        new Chart(ordersCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Orders',
                    data: [150, 230, 180, 290, 200, 250],
                    backgroundColor: '#00CC61',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.9)',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
}

// Tooltip initialization
function initializeTooltips() {
    const tooltipTriggers = document.querySelectorAll('.woodash-tooltip-trigger');
    
    tooltipTriggers.forEach(trigger => {
        const tooltip = trigger.querySelector('.woodash-tooltip');
        if (!tooltip) return;

        trigger.addEventListener('mouseenter', () => {
            tooltip.style.visibility = 'visible';
            tooltip.style.opacity = '1';
        });

        trigger.addEventListener('mouseleave', () => {
            tooltip.style.visibility = 'hidden';
            tooltip.style.opacity = '0';
        });
    });
}

// Background animation initialization
function initializeBackgroundAnimation() {
    const bgAnimation = document.querySelector('.woodash-bg-animation');
    if (!bgAnimation) return;

    bgAnimation.addEventListener('mousemove', (e) => {
        const rect = bgAnimation.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        
        bgAnimation.style.setProperty('--mouse-x', `${x}%`);
        bgAnimation.style.setProperty('--mouse-y', `${y}%`);
    });
}

// Orbs initialization
function initializeOrbs() {
    const orbs = document.querySelectorAll('.woodash-orb');
    
    orbs.forEach(orb => {
        // Random position
        const x = Math.random() * 100;
        const y = Math.random() * 100;
        
        orb.style.left = `${x}%`;
        orb.style.top = `${y}%`;
        
        // Random size
        const size = Math.random() * 100 + 50;
        orb.style.width = `${size}px`;
        orb.style.height = `${size}px`;
        
        // Random color
        const hue = Math.random() * 360;
        orb.style.backgroundColor = `hsla(${hue}, 100%, 50%, 0.1)`;
        
        // Random animation delay
        const delay = Math.random() * 5;
        orb.style.animationDelay = `${delay}s`;
    });
}

// Notification system
const woodashNotifications = {
    show: function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `woodash-notification woodash-notification-${type} woodash-animate-in`;
        notification.innerHTML = message;
        
        document.body.appendChild(notification);
        
        // Remove after 5 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 5000);
    }
};

// Export for use in other files
window.woodashNotifications = woodashNotifications;
