document.addEventListener('DOMContentLoaded', function() {
    const CouponAnalytics = {
        charts: {},
        state: {
            currentPeriod: 'month',
            currentFilter: 'usage',
            chartViews: {
                discountType: 'doughnut'
            }
        },

        // Sample data
        sampleData: {
            coupons: [
                { code: 'SUMMER20', description: 'Summer Sale 20% Off', usage_count: 245, revenue: 12500, discount: 2500, created_at: '2024-03-01', expires_at: '2024-08-31' },
                { code: 'WELCOME10', description: 'Welcome Discount', usage_count: 180, revenue: 9000, discount: 900, created_at: '2024-01-01', expires_at: '2024-12-31' },
                { code: 'FLASH25', description: 'Flash Sale', usage_count: 150, revenue: 7500, discount: 1875, created_at: '2024-03-15', expires_at: '2024-03-20' },
                { code: 'HOLIDAY15', description: 'Holiday Special', usage_count: 120, revenue: 6000, discount: 900, created_at: '2024-02-01', expires_at: '2024-04-30' },
                { code: 'NEWUSER', description: 'New User Discount', usage_count: 100, revenue: 5000, discount: 500, created_at: '2024-01-01', expires_at: '2024-12-31' },
                { code: 'BULK10', description: 'Bulk Purchase Discount', usage_count: 85, revenue: 8500, discount: 850, created_at: '2024-02-15', expires_at: '2024-05-15' },
                { code: 'LOYALTY20', description: 'Loyalty Program', usage_count: 75, revenue: 3750, discount: 750, created_at: '2024-01-15', expires_at: '2024-12-31' },
                { code: 'CLEARANCE', description: 'Clearance Sale', usage_count: 65, revenue: 3250, discount: 650, created_at: '2024-03-10', expires_at: '2024-03-25' }
            ],
            activities: [
                { type: 'usage', code: 'SUMMER20', user: 'John Doe', amount: 125.50, time: '2 minutes ago' },
                { type: 'create', code: 'FLASH25', user: 'Admin', time: '1 hour ago' },
                { type: 'expire', code: 'WELCOME10', time: '3 hours ago' },
                { type: 'usage', code: 'HOLIDAY15', user: 'Jane Smith', amount: 89.99, time: '5 hours ago' },
                { type: 'usage', code: 'NEWUSER', user: 'Mike Johnson', amount: 149.99, time: '6 hours ago' },
                { type: 'modify', code: 'BULK10', user: 'Admin', changes: 'Updated discount amount', time: '8 hours ago' },
                { type: 'usage', code: 'LOYALTY20', user: 'Sarah Wilson', amount: 199.99, time: '10 hours ago' }
            ],
            customerSegments: {
                new: 45,
                returning: 30,
                vip: 15,
                firstTime: 10
            },
            geographic: {
                country: [
                    { name: 'North America', value: 42 },
                    { name: 'Europe', value: 28 },
                    { name: 'Asia', value: 18 },
                    { name: 'South America', value: 8 },
                    { name: 'Oceania', value: 4 }
                ],
                state: [
                    { name: 'California', value: 25 },
                    { name: 'New York', value: 18 },
                    { name: 'Texas', value: 15 },
                    { name: 'Florida', value: 12 },
                    { name: 'Illinois', value: 10 }
                ],
                city: [
                    { name: 'New York', value: 15 },
                    { name: 'Los Angeles', value: 12 },
                    { name: 'Chicago', value: 10 },
                    { name: 'Houston', value: 8 },
                    { name: 'Phoenix', value: 6 }
                ]
            },
            metrics: {
                conversionRate: [2.5, 3.1, 2.8, 3.5, 4.2, 4.8],
                revenue: [15000, 18000, 16500, 21000, 24000, 28000],
                orderValue: {
                    withCoupon: [85, 88, 92, 95, 98, 102],
                    withoutCoupon: [75, 77, 78, 79, 80, 81]
                },
                performance: {
                    peakUsage: 245,
                    avgDailyUsage: 42,
                    growthRate: 15.8,
                    totalRevenue: 56500,
                    avgDiscount: 25.50,
                    redemptionRate: 68.5,
                    customerRetention: 72.3,
                    revenuePerCoupon: 7062.50
                },
                timeMetrics: {
                    mostActiveHour: '14:00',
                    mostActiveDay: 'Friday',
                    avgResponseTime: '2.5 hours',
                    peakUsageTime: '15:00-17:00'
                },
                customerMetrics: {
                    newCustomers: 450,
                    returningCustomers: 300,
                    vipCustomers: 150,
                    firstTimeUsers: 100,
                    avgCustomerValue: 125.75,
                    customerSatisfaction: 4.5
                }
            }
        },

        init() {
            this.initCharts();
            this.initEventListeners();
            this.updateOverviewStats();
            this.updateTopCoupons();
            this.updateRecentActivity();
            this.updateTimeMetrics();
            this.updateCustomerMetrics();
            this.updatePerformanceMetrics();
        },

        initCharts() {
            this.initUsageChart();
            this.initDiscountTypeChart();
            this.initUsageByDayChart();
            this.initConversionRateChart();
            this.initRevenueImpactChart();
            this.initCustomerSegmentsChart();
            this.initGeographicChart();
            this.initOrderValueChart();
        },

        initUsageChart() {
            const ctx = document.getElementById('couponUsageChart');
            if (!ctx) return;

            this.charts.usage = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Coupon Usage',
                        data: [],
                        borderColor: '#DC2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        tension: 0.4,
                        fill: true
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 14 },
                            bodyFont: { family: 'Inter', size: 13 }
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

            this.updateChartData(this.state.currentPeriod);
        },

        initDiscountTypeChart() {
            const ctx = document.getElementById('discountTypeChart');
            if (!ctx) return;

            this.charts.discountType = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Percentage', 'Fixed Amount', 'Free Shipping'],
                    datasets: [{
                        data: [65, 25, 10],
                        backgroundColor: [
                            'rgba(220, 38, 38, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)'
                        ],
                        borderColor: [
                            'rgba(220, 38, 38, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: 'Inter' }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 14 },
                            bodyFont: { family: 'Inter', size: 13 }
                        }
                    }
                }
            });
        },

        initUsageByDayChart() {
            const ctx = document.getElementById('usageByDayChart');
            if (!ctx) return;

            this.charts.usageByDay = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Current Period',
                        data: [45, 38, 42, 35, 55, 65, 40],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 14 },
                            bodyFont: { family: 'Inter', size: 13 }
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
        },

        initConversionRateChart() {
            const ctx = document.getElementById('conversionRateChart');
            if (!ctx) return;

            this.charts.conversionRate = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Conversion Rate',
                        data: [2.5, 3.1, 2.8, 3.5, 4.2, 4.8],
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 14 },
                            bodyFont: { family: 'Inter', size: 13 }
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
        },

        initRevenueImpactChart() {
            const ctx = document.getElementById('revenueImpactChart');
            if (!ctx) return;

            this.charts.revenueImpact = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [15000, 18000, 16500, 21000, 24000, 28000],
                        backgroundColor: 'rgba(139, 92, 246, 0.8)',
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 14 },
                            bodyFont: { family: 'Inter', size: 13 }
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
        },

        initCustomerSegmentsChart() {
            const ctx = document.getElementById('customerSegmentsChart');
            if (!ctx) return;

            this.charts.customerSegments = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['New Customers', 'Returning Customers', 'VIP Customers', 'First-time Users'],
                    datasets: [{
                        data: [45, 30, 15, 10],
                        backgroundColor: [
                            'rgba(220, 38, 38, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderColor: [
                            'rgba(220, 38, 38, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(16, 185, 129, 1)',
                            'rgba(139, 92, 246, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: 'Inter' }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 14 },
                            bodyFont: { family: 'Inter', size: 13 }
                        }
                    }
                }
            });
        },

        initGeographicChart() {
            const ctx = document.getElementById('geographicChart');
            if (!ctx) return;

            this.charts.geographic = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['North America', 'Europe', 'Asia', 'South America', 'Oceania'],
                    datasets: [{
                        label: 'Usage Distribution',
                        data: [42, 28, 18, 8, 4],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
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
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 14 },
                            bodyFont: { family: 'Inter', size: 13 }
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
        },

        initOrderValueChart() {
            const ctx = document.getElementById('orderValueChart');
            if (!ctx) return;

            this.charts.orderValue = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [
                        {
                            label: 'With Coupon',
                            data: [85, 88, 92, 95, 98, 102],
                            borderColor: '#DC2626',
                            backgroundColor: 'rgba(220, 38, 38, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Without Coupon',
                            data: [75, 77, 78, 79, 80, 81],
                            borderColor: '#9CA3AF',
                            backgroundColor: 'rgba(156, 163, 175, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: 'Inter' }
                            }
                        },
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
        },

        initEventListeners() {
            // Period buttons
            document.querySelectorAll('#coupon-trend-period-buttons [data-period]').forEach(button => {
                button.addEventListener('click', (e) => {
                    const period = e.target.dataset.period;
                    this.updateChartData(period);
                    this.updatePeriodButtons(period);
                });
            });

            // Top coupons filter
            const filterSelect = document.getElementById('top-coupons-filter');
            if (filterSelect) {
                filterSelect.addEventListener('change', (e) => {
                    this.state.currentFilter = e.target.value;
                    this.updateTopCoupons();
                });
            }

            // Export button
            const exportBtn = document.getElementById('export-top-coupons');
            if (exportBtn) {
                exportBtn.addEventListener('click', () => this.exportTopCoupons());
            }

            // Chart view toggles
            document.querySelectorAll('[data-chart-view]').forEach(button => {
                button.addEventListener('click', (e) => {
                    const view = e.target.closest('button').dataset.chartView;
                    this.toggleChartView('discountType', view);
                });
            });

            // Usage comparison
            const comparisonSelect = document.getElementById('usage-comparison');
            if (comparisonSelect) {
                comparisonSelect.addEventListener('change', (e) => {
                    this.updateUsageComparison(e.target.value);
                });
            }

            // Refresh activity
            const refreshBtn = document.getElementById('refresh-activity');
            if (refreshBtn) {
                refreshBtn.addEventListener('click', () => this.updateRecentActivity());
            }

            // View all activity
            const viewAllBtn = document.getElementById('view-all-activity');
            if (viewAllBtn) {
                viewAllBtn.addEventListener('click', () => this.viewAllActivity());
            }

            // Region filter
            const regionFilter = document.getElementById('region-filter');
            if (regionFilter) {
                regionFilter.addEventListener('change', (e) => {
                    this.updateGeographicData(e.target.value);
                });
            }
        },

        updatePeriodButtons(activePeriod) {
            document.querySelectorAll('#coupon-trend-period-buttons [data-period]').forEach(button => {
                if (button.dataset.period === activePeriod) {
                    button.classList.remove('woodash-btn-secondary');
                    button.classList.add('bg-[#DC2626]', 'text-white', 'woodash-btn-primary');
                } else {
                    button.classList.remove('bg-[#DC2626]', 'text-white', 'woodash-btn-primary');
                    button.classList.add('woodash-btn-secondary');
                }
            });
        },

        generateChartData(period) {
            const now = new Date();
            const data = [];
            const labels = [];
            let count;

            switch(period) {
                case 'week':
                    count = 7;
                    for (let i = 0; i < count; i++) {
                        const date = new Date(now);
                        date.setDate(date.getDate() - (count - 1 - i));
                        labels.push(date.toLocaleDateString('en-US', { weekday: 'short' }));
                        data.push(Math.floor(Math.random() * 50) + 10);
                    }
                    break;
                case 'month':
                    count = 12;
                    for (let i = 0; i < count; i++) {
                        const date = new Date(now);
                        date.setMonth(date.getMonth() - (count - 1 - i));
                        labels.push(date.toLocaleDateString('en-US', { month: 'short' }));
                        data.push(Math.floor(Math.random() * 100) + 20);
                    }
                    break;
                case 'year':
                    count = 5;
                    for (let i = 0; i < count; i++) {
                        const date = new Date(now);
                        date.setFullYear(date.getFullYear() - (count - 1 - i));
                        labels.push(date.getFullYear().toString());
                        data.push(Math.floor(Math.random() * 500) + 100);
                    }
                    break;
            }

            return { labels, data };
        },

        updateChartData(period) {
            if (!this.charts.usage) return;

            const { labels, data } = this.generateChartData(period);
            this.charts.usage.data.labels = labels;
            this.charts.usage.data.datasets[0].data = data;
            this.charts.usage.update();
            this.state.currentPeriod = period;
            this.updateOverviewStats();
            this.updateUsageMetrics(data);
        },

        updateUsageMetrics(data) {
            const peakUsage = Math.max(...data);
            const avgDailyUsage = Math.round(data.reduce((a, b) => a + b, 0) / data.length);
            const growthRate = ((data[data.length - 1] - data[0]) / data[0] * 100).toFixed(1);

            document.getElementById('peak-usage').textContent = peakUsage;
            document.getElementById('avg-daily-usage').textContent = avgDailyUsage;
            document.getElementById('growth-rate').textContent = `${growthRate > 0 ? '+' : ''}${growthRate}%`;
        },

        updateOverviewStats() {
            const totalCoupons = this.sampleData.coupons.length;
            const activeCoupons = this.sampleData.coupons.filter(c => new Date(c.expires_at) > new Date()).length;
            const totalUsage = this.sampleData.coupons.reduce((sum, c) => sum + c.usage_count, 0);
            const totalRevenue = this.sampleData.coupons.reduce((sum, c) => sum + c.revenue, 0);
            const avgDiscount = (this.sampleData.coupons.reduce((sum, c) => sum + c.discount, 0) / totalUsage).toFixed(2);
            const performance = this.sampleData.metrics.performance;

            // Update overview cards
            document.getElementById('total-coupons').textContent = totalCoupons;
            document.getElementById('active-coupons').textContent = activeCoupons;
            document.getElementById('total-coupon-usage').textContent = totalUsage.toLocaleString();
            document.getElementById('avg-discount').textContent = `$${avgDiscount}`;

            // Update performance metrics
            document.getElementById('peak-usage').textContent = performance.peakUsage;
            document.getElementById('avg-daily-usage').textContent = performance.avgDailyUsage;
            document.getElementById('growth-rate').textContent = `${performance.growthRate > 0 ? '+' : ''}${performance.growthRate}%`;

            // Update additional metrics
            const metricsContainer = document.querySelector('.performance-metrics');
            if (metricsContainer) {
                metricsContainer.innerHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">Redemption Rate</p>
                            <p class="text-lg font-semibold text-gray-900">${performance.redemptionRate}%</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">Customer Retention</p>
                            <p class="text-lg font-semibold text-gray-900">${performance.customerRetention}%</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">Revenue per Coupon</p>
                            <p class="text-lg font-semibold text-gray-900">$${performance.revenuePerCoupon}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-500">Total Revenue</p>
                            <p class="text-lg font-semibold text-gray-900">$${performance.totalRevenue.toLocaleString()}</p>
                        </div>
                    </div>
                `;
            }
        },

        toggleChartView(chartId, view) {
            if (!this.charts[chartId]) return;

            const chart = this.charts[chartId];
            const data = chart.data;
            
            // Destroy current chart
            chart.destroy();

            // Create new chart with different type
            const ctx = document.getElementById(chartId + 'Chart');
            this.charts[chartId] = new Chart(ctx, {
                type: view,
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: view === 'doughnut' ? 'bottom' : 'top',
                            labels: {
                                font: { family: 'Inter' }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { family: 'Inter', size: 14 },
                            bodyFont: { family: 'Inter', size: 13 }
                        }
                    }
                }
            });

            // Update button states
            document.querySelectorAll('[data-chart-view]').forEach(button => {
                if (button.dataset.chartView === view) {
                    button.classList.remove('woodash-btn-secondary');
                    button.classList.add('bg-[#DC2626]', 'text-white', 'woodash-btn-primary');
                } else {
                    button.classList.remove('bg-[#DC2626]', 'text-white', 'woodash-btn-primary');
                    button.classList.add('woodash-btn-secondary');
                }
            });
        },

        updateUsageComparison(period) {
            if (!this.charts.usageByDay) return;

            const currentData = this.charts.usageByDay.data.datasets[0].data;
            let comparisonData;

            switch(period) {
                case 'last-week':
                    comparisonData = currentData.map(value => Math.round(value * 0.8 + Math.random() * 10));
                    break;
                case 'last-month':
                    comparisonData = currentData.map(value => Math.round(value * 0.7 + Math.random() * 15));
                    break;
                default:
                    this.charts.usageByDay.data.datasets = [{
                        label: 'Current Period',
                        data: currentData,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderRadius: 4
                    }];
                    this.charts.usageByDay.update();
                    return;
            }

            this.charts.usageByDay.data.datasets = [
                {
                    label: 'Current Period',
                    data: currentData,
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderRadius: 4
                },
                {
                    label: 'Previous Period',
                    data: comparisonData,
                    backgroundColor: 'rgba(156, 163, 175, 0.8)',
                    borderRadius: 4
                }
            ];

            this.charts.usageByDay.update();
        },

        updateTopCoupons() {
            const topCouponsList = document.getElementById('top-coupons-list');
            if (!topCouponsList) return;

            // Sort based on current filter
            const sortedCoupons = this.sampleData.coupons.sort((a, b) => b[this.state.currentFilter] - a[this.state.currentFilter]);

            topCouponsList.innerHTML = '';
            sortedCoupons.forEach(coupon => {
                const couponElement = document.createElement('div');
                couponElement.className = 'flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors';
                couponElement.innerHTML = `
                    <div>
                        <div class="font-medium text-gray-900">${coupon.code}</div>
                        <div class="text-sm text-gray-500">${coupon.description}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-gray-900">${this.formatValue(coupon[this.state.currentFilter])}</div>
                        <div class="text-sm text-gray-500">${this.getFilterLabel(this.state.currentFilter)}</div>
                    </div>
                `;
                topCouponsList.appendChild(couponElement);
            });
        },

        formatValue(value) {
            if (this.state.currentFilter === 'revenue' || this.state.currentFilter === 'discount') {
                return '$' + value.toLocaleString();
            }
            return value.toLocaleString();
        },

        getFilterLabel(filter) {
            switch(filter) {
                case 'usage': return 'uses';
                case 'revenue': return 'revenue';
                case 'discount': return 'discount';
                default: return '';
            }
        },

        exportTopCoupons() {
            const csv = [
                ['Code', 'Description', 'Usage Count', 'Revenue', 'Discount'],
                ...this.sampleData.coupons.map(coupon => [
                    coupon.code,
                    coupon.description,
                    coupon.usage_count,
                    coupon.revenue,
                    coupon.discount
                ])
            ].map(row => row.join(',')).join('\n');

            const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'top-coupons.csv';
            link.click();
        },

        updateRecentActivity() {
            const activityList = document.getElementById('recent-activity-list');
            if (!activityList) return;

            activityList.innerHTML = '';
            this.sampleData.activities.forEach(activity => {
                const activityElement = document.createElement('div');
                activityElement.className = 'flex items-start space-x-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors';
                
                let icon, color, text;
                switch(activity.type) {
                    case 'usage':
                        icon = 'fa-ticket-alt';
                        color = 'text-blue-500';
                        text = `${activity.user} used coupon ${activity.code} ($${activity.amount})`;
                        break;
                    case 'create':
                        icon = 'fa-plus-circle';
                        color = 'text-green-500';
                        text = `New coupon ${activity.code} created`;
                        break;
                    case 'expire':
                        icon = 'fa-clock';
                        color = 'text-red-500';
                        text = `Coupon ${activity.code} expired`;
                        break;
                    case 'modify':
                        icon = 'fa-edit';
                        color = 'text-yellow-500';
                        text = `${activity.user} modified ${activity.code}: ${activity.changes}`;
                        break;
                }

                activityElement.innerHTML = `
                    <div class="flex-shrink-0">
                        <i class="fas ${icon} ${color} text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">${text}</p>
                        <p class="text-sm text-gray-500">${activity.time}</p>
                    </div>
                `;
                activityList.appendChild(activityElement);
            });
        },

        viewAllActivity() {
            // Implement view all activity functionality
            console.log('View all activity clicked');
        },

        updateGeographicData(level) {
            if (!this.charts.geographic) return;

            const data = {
                country: [
                    { name: 'North America', value: 42 },
                    { name: 'Europe', value: 28 },
                    { name: 'Asia', value: 18 },
                    { name: 'South America', value: 8 },
                    { name: 'Oceania', value: 4 }
                ],
                state: [
                    { name: 'California', value: 25 },
                    { name: 'New York', value: 18 },
                    { name: 'Texas', value: 15 },
                    { name: 'Florida', value: 12 },
                    { name: 'Illinois', value: 10 }
                ],
                city: [
                    { name: 'New York', value: 15 },
                    { name: 'Los Angeles', value: 12 },
                    { name: 'Chicago', value: 10 },
                    { name: 'Houston', value: 8 },
                    { name: 'Phoenix', value: 6 }
                ]
            };

            const selectedData = data[level];
            const labels = selectedData.map(d => d.name);
            const values = selectedData.map(d => d.value);

            this.charts.geographic.data.labels = labels;
            this.charts.geographic.data.datasets[0].data = values;
            this.charts.geographic.update();
        },

        updateTimeMetrics() {
            const timeMetricsContainer = document.querySelector('.time-metrics');
            if (!timeMetricsContainer) return;

            const metrics = [
                {
                    title: 'Most Active Hour',
                    value: '2:00 PM',
                    change: '+15%',
                    trend: 'up',
                    icon: 'fa-clock'
                },
                {
                    title: 'Most Active Day',
                    value: 'Friday',
                    change: '+8%',
                    trend: 'up',
                    icon: 'fa-calendar'
                },
                {
                    title: 'Average Response Time',
                    value: '2.5 hours',
                    change: '-12%',
                    trend: 'down',
                    icon: 'fa-stopwatch'
                },
                {
                    title: 'Peak Usage Time',
                    value: '3:00 PM - 5:00 PM',
                    change: '+5%',
                    trend: 'up',
                    icon: 'fa-chart-line'
                }
            ];

            timeMetricsContainer.innerHTML = metrics.map(metric => `
                <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-50 rounded-lg mr-3">
                            <i class="fas ${metric.icon} text-red-600"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-600">${metric.title}</h4>
                            <p class="text-lg font-semibold text-gray-900">${metric.value}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm ${metric.trend === 'up' ? 'text-green-600' : 'text-red-600'}">
                            ${metric.change}
                        </span>
                        <p class="text-xs text-gray-500">vs last month</p>
                    </div>
                </div>
            `).join('');
        },

        updateCustomerMetrics() {
            const customerMetricsContainer = document.querySelector('.customer-metrics');
            if (!customerMetricsContainer) return;

            const metrics = [
                {
                    title: 'New Customers',
                    value: '1,234',
                    change: '+23%',
                    trend: 'up',
                    icon: 'fa-user-plus'
                },
                {
                    title: 'Returning Customers',
                    value: '856',
                    change: '+12%',
                    trend: 'up',
                    icon: 'fa-users'
                },
                {
                    title: 'VIP Customers',
                    value: '245',
                    change: '+8%',
                    trend: 'up',
                    icon: 'fa-crown'
                },
                {
                    title: 'Customer Satisfaction',
                    value: '4.8/5',
                    change: '+0.2',
                    trend: 'up',
                    icon: 'fa-star'
                }
            ];

            customerMetricsContainer.innerHTML = metrics.map(metric => `
                <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-center">
                        <div class="p-2 bg-red-50 rounded-lg mr-3">
                            <i class="fas ${metric.icon} text-red-600"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-600">${metric.title}</h4>
                            <p class="text-lg font-semibold text-gray-900">${metric.value}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm ${metric.trend === 'up' ? 'text-green-600' : 'text-red-600'}">
                            ${metric.change}
                        </span>
                        <p class="text-xs text-gray-500">vs last month</p>
                    </div>
                </div>
            `).join('');
        },

        updatePerformanceMetrics() {
            const performanceMetricsContainer = document.querySelector('.performance-metrics');
            if (!performanceMetricsContainer) return;

            const metrics = [
                {
                    title: 'Conversion Rate',
                    value: '12.5%',
                    change: '+2.3%',
                    trend: 'up',
                    icon: 'fa-chart-pie'
                },
                {
                    title: 'Revenue Impact',
                    value: '$45,678',
                    change: '+15%',
                    trend: 'up',
                    icon: 'fa-dollar-sign'
                },
                {
                    title: 'Average Order Value',
                    value: '$89.45',
                    change: '+8.2%',
                    trend: 'up',
                    icon: 'fa-shopping-cart'
                },
                {
                    title: 'Redemption Rate',
                    value: '68%',
                    change: '+5.1%',
                    trend: 'up',
                    icon: 'fa-ticket-alt'
                }
            ];

            performanceMetricsContainer.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    ${metrics.map(metric => `
                        <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                            <div class="flex items-center">
                                <div class="p-2 bg-red-50 rounded-lg mr-3">
                                    <i class="fas ${metric.icon} text-red-600"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-600">${metric.title}</h4>
                                    <p class="text-lg font-semibold text-gray-900">${metric.value}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm ${metric.trend === 'up' ? 'text-green-600' : 'text-red-600'}">
                                    ${metric.change}
                                </span>
                                <p class="text-xs text-gray-500">vs last month</p>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }
    };

    // Initialize analytics
    CouponAnalytics.init();
}); 