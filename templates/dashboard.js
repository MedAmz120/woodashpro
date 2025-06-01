// Debounce function (add at the very top)
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        const later = () => {
            clearTimeout(timeout);
            func.apply(this, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof woodashData === 'undefined') return;

    // Function to fetch data with date parameters
    function fetchData(dateFrom, dateTo, granularity) {
        var params = new URLSearchParams({
            action: 'woodash_get_data',
            nonce: woodashData.nonce
        });
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        if (granularity) params.append('granularity', granularity);

        fetch(woodashData.ajaxurl + '?' + params.toString())
            .then(response => response.json())
            .then(data => {
                if (data.total_sales !== undefined) {
                    var el = document.getElementById('total-sales');
                    if (el) el.textContent = '$' + Number(data.total_sales).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
                if (data.aov !== undefined) {
                    var el = document.getElementById('aov');
                    if (el) el.textContent = '$' + Number(data.aov).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }
                if (data.sales_overview) {
                    updateSalesOverview(data.sales_overview);
                }
                // Add more updates for other metrics as needed
            });
    }

    // Function to update the sales overview chart
    function updateSalesOverview(data) {
        var ctx = document.getElementById('sales-chart');
        if (!ctx) return;
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Sales',
                    data: data.data,
                    borderColor: '#00CC61',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(0, 204, 97, 0.1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { family: 'Inter', size: 14 },
                        bodyFont: { family: 'Inter', size: 13 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { font: { family: 'Inter' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter' } }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                }
            }
        });
    }

    // Initial fetch
    fetchData();

    // Example: Add event listeners for date filter dropdown
    var dateFilter = document.getElementById('date-filter');
    dateFilter.addEventListener('change', function() {
        var range = this.value;
        var dateFrom, dateTo;
        var today = new Date();
        if (range === 'today') {
            dateFrom = today.toISOString().split('T')[0];
            dateTo = today.toISOString().split('T')[0];
        } else if (range === 'last7days') {
            dateFrom = new Date(today.setDate(today.getDate() - 7)).toISOString().split('T')[0];
            dateTo = new Date().toISOString().split('T')[0];
        } else if (range === 'custom') {
            var customDateFrom = document.getElementById('custom-date-from');
            var customDateTo = document.getElementById('custom-date-to');
            customDateFrom.classList.remove('hidden');
            customDateTo.classList.remove('hidden');
            return;
        }
        // Hide date pickers if not custom
        document.getElementById('custom-date-from').classList.add('hidden');
        document.getElementById('custom-date-to').classList.add('hidden');
        fetchData(dateFrom, dateTo, range);
    });

    // Handle custom date application
    var applyCustomDateButton = document.getElementById('apply-custom-date');
    applyCustomDateButton.addEventListener('click', function() {
        var customDateFrom = document.getElementById('custom-date-from');
        var customDateTo = document.getElementById('custom-date-to');
        var dateFrom = customDateFrom.value;
        var dateTo = customDateTo.value;
        if (dateFrom && dateTo) {
            fetchData(dateFrom, dateTo, 'custom');
        }
    });
}); 