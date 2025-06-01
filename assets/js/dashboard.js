document.addEventListener('DOMContentLoaded', function() {
    // Theme initialization
    if (localStorage.getItem('woodash-theme') === 'light') document.body.classList.add('light');

    // Flatpickr date range picker
    flatpickr("#date-range", {
        mode: "range",
        dateFormat: "Y-m-d"
    });

    // Light/Dark mode toggle
    function updateModeButton() {
        const isLight = document.body.classList.contains('light');
        document.getElementById('toggle-mode-icon').textContent = isLight ? '☀️' : '��';
        document.getElementById('toggle-mode-text').textContent = isLight ? 'Light Mode' : 'Dark Mode';
    }

    document.getElementById('toggle-mode').addEventListener('click', function() {
        document.body.classList.toggle('light');
        localStorage.setItem('woodash-theme', document.body.classList.contains('light') ? 'light' : 'dark');
        updateModeButton();
    });
    updateModeButton();

    // CSV Export
    document.getElementById('export-csv').addEventListener('click', function() {
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

    // Date filter
    document.getElementById('apply-date').addEventListener('click', function() {
        fetchDashboardData();
    });

    // Loading spinner
    function showLoading(show) {
        let spinner = document.getElementById('woodash-loading');
        if (spinner) spinner.style.display = show ? 'flex' : 'none';
    }

    // Chart.js setup
    let salesChart = new Chart(document.getElementById('sales-chart').getContext('2d'), {
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

    let currentGranularity = 'daily';

    // Chart range toggle
    Array.from(document.getElementsByClassName('woodash-range-btn')).forEach(btn => {
        btn.addEventListener('click', function() {
            currentGranularity = this.getAttribute('data-range');
            Array.from(document.getElementsByClassName('woodash-range-btn')).forEach(b => b.classList.remove('bg-blue-700'));
            this.classList.add('bg-blue-700');
            fetchDashboardData();
        });
    });

    // Fetch dashboard data
    function fetchDashboardData() {
        showLoading(true);
        let dateRange = document.getElementById('date-range').value.split(' to ');
        jQuery.post(woodash_ajax.ajax_url, {
            action: 'woodash_get_data',
            nonce: woodash_ajax.nonce,
            date_from: dateRange[0] || '',
            date_to: dateRange[1] || '',
            granularity: currentGranularity
        }, function(response) {
            showLoading(false);
            if (!response || response.error) {
                alert('Failed to load dashboard data.');
                return;
            }
            document.getElementById('total-sales').textContent = '$' + response.total_sales;
            document.getElementById('total-orders').textContent = response.total_orders;
            document.getElementById('aov').textContent = '$' + response.aov;

            // Top products
            let productsHtml = '';
            response.top_products.forEach(p => {
                productsHtml += `<tr><td>${p.name}</td><td>${p.sales}</td></tr>`;
            });
            document.getElementById('top-products').innerHTML = productsHtml;

            // Top customers
            let customersHtml = '';
            response.top_customers.forEach(c => {
                customersHtml += `<tr><td>${c.name}</td><td>${c.orders}</td></tr>`;
            });
            document.getElementById('top-customers').innerHTML = customersHtml;

            // Sales chart
            salesChart.data.labels = response.sales_overview.labels;
            salesChart.data.datasets[0].data = response.sales_overview.data;
            salesChart.update();
        }).fail(function() {
            showLoading(false);
            alert('Error: Could not fetch dashboard data.');
        });
    }

    // Initial load
    fetchDashboardData();

    // Export Top Products CSV
    document.getElementById('export-products-csv').addEventListener('click', function() {
        let dateRange = document.getElementById('date-range').value.split(' to ');
        let granularity = currentGranularity;
        this.disabled = true;
        this.textContent = 'Exporting...';
        window.location = woodash_ajax.ajax_url +
            '?action=woodash_export_products_csv&nonce=' + woodash_ajax.nonce +
            '&date_from=' + (dateRange[0] || '') +
            '&date_to=' + (dateRange[1] || '') +
            '&granularity=' + granularity;
        setTimeout(() => {
            this.disabled = false;
            this.textContent = 'Export CSV';
        }, 2000);
    });

    // Export Top Customers CSV
    document.getElementById('export-customers-csv').addEventListener('click', function() {
        let dateRange = document.getElementById('date-range').value.split(' to ');
        let granularity = currentGranularity;
        this.disabled = true;
        this.textContent = 'Exporting...';
        window.location = woodash_ajax.ajax_url +
            '?action=woodash_export_customers_csv&nonce=' + woodash_ajax.nonce +
            '&date_from=' + (dateRange[0] || '') +
            '&date_to=' + (dateRange[1] || '') +
            '&granularity=' + granularity;
        setTimeout(() => {
            this.disabled = false;
            this.textContent = 'Export CSV';
        }, 2000);
    });
});
