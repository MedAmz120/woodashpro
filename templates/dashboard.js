// Debounce function (add at the very top)
function debounce(func, wait) {
  let timeout;
  return function (...args) {
    const later = () => {
      clearTimeout(timeout);
      func.apply(this, args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Format date as YYYY-MM-DD in local timezone
function formatDateLocal(d) {
  const pad = (n) => String(n).padStart(2, '0');
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
}

// Build ISO-8601 range in site/local time
function buildIsoRange(range) {
  const to = new Date();
  let from = new Date();
  if (range === 'today') {
    // from = to
  } else if (range === 'last7days') {
    from.setDate(from.getDate() - 6);
  } else if (typeof range === 'object' && range.from && range.to) {
    from = new Date(range.from);
    from.setHours(0, 0, 0, 0);
    const t = new Date(range.to);
    t.setHours(23, 59, 59, 999);
    return {
      after: `${formatDateLocal(from)}T00:00:00`,
      before: `${formatDateLocal(t)}T23:59:59`,
    };
  }
  from.setHours(0, 0, 0, 0);
  to.setHours(23, 59, 59, 999);
  return {
    after: `${formatDateLocal(from)}T00:00:00`,
    before: `${formatDateLocal(to)}T23:59:59`,
  };
}

// Send client debug to backend log
function woodashDebug(label, payload) {
  try {
    const params = new URLSearchParams();
    params.append('action', 'woodash_debug');
    params.append('nonce', woodashData.nonce);
    params.append('label', label);
    params.append('data', JSON.stringify(payload || {}));
    fetch(woodashData.ajaxurl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
      body: params.toString(),
    });
  } catch (_) {}
}

// Fetch NEW customers count within window
async function fetchNewCustomers(after, before) {
  const root = (window.wpApiSettings && window.wpApiSettings.root) || '/wp-json/';
  const nonce = (window.wpApiSettings && window.wpApiSettings.nonce) || '';
  const baseHeaders = { 'X-WP-Nonce': nonce };

  // Try direct filter first
  try {
    const p1 = new URLSearchParams({ after, before, interval: 'day', timezone: 'site', customer_type: 'new' });
    const r1 = await fetch(`${root}wc-analytics/reports/customers/stats?${p1}`, { headers: baseHeaders });
    const j1 = await r1.json();
    const c1 = Number(j1?.totals?.customers_count || 0);
    if (c1 > 0) return c1;
  } catch (_) {}

  // Fallback: segmented by customer_type
  try {
    const p2 = new URLSearchParams({ after, before, interval: 'day', timezone: 'site', segmentby: 'customer_type' });
    const r2 = await fetch(`${root}wc-analytics/reports/customers/stats?${p2}`, { headers: baseHeaders });
    const j2 = await r2.json();
    const seg = Array.isArray(j2?.segments) ? j2.segments.find((s) => s.segment_id === 'new' || s?.label?.toLowerCase() === 'new') : null;
    const c2 = Number(seg?.totals?.customers_count || 0);
    return c2;
  } catch (_) {}

  return 0;
}

// Fetch processing orders count via admin-ajax
async function fetchProcessingCount(after, before) {
  const params = new URLSearchParams();
  params.append('action', 'woodash_get_processing_count');
  params.append('nonce', woodashData.nonce);
  params.append('after', after);
  params.append('before', before);
  const res = await fetch(woodashData.ajaxurl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
    body: params.toString(),
  });
  const json = await res.json();
  if (json && json.success && json.data && typeof json.data.processing === 'number') {
    return json.data.processing;
  }
  return 0;
}

// Fetch from WooCommerce Analytics REST
async function fetchAnalytics(range) {
  const { after, before } = buildIsoRange(range);
  const root = (window.wpApiSettings && window.wpApiSettings.root) || '/wp-json/';
  const nonce = (window.wpApiSettings && window.wpApiSettings.nonce) || '';
  const params = new URLSearchParams({ after, before, interval: 'day', timezone: 'site' });
  const headers = { 'X-WP-Nonce': nonce };

  const [revenueRes, ordersRes] = await Promise.all([
    fetch(`${root}wc-analytics/reports/revenue/stats?${params}`, { headers }),
    fetch(`${root}wc-analytics/reports/orders/stats?${params}`, { headers }),
  ]);
  const revenue = await revenueRes.json();
  const orders = await ordersRes.json();
  return { revenue, orders, after, before };
}

// Update sales overview chart
let salesChartInstance = null;
function updateSalesOverview(data) {
  const ctx = document.getElementById('sales-chart');
  if (!ctx) return;
  
  // Aggressive cleanup: destroy ALL charts on this canvas
  try {
    // Method 1: Try Chart.js built-in method
    if (typeof Chart.getChart === 'function') {
      const existingChart = Chart.getChart(ctx);
      if (existingChart) {
        existingChart.destroy();
      }
    }
  } catch (e) {
    console.warn('Error with Chart.getChart:', e);
  }
  
  // Method 2: Destroy our tracked instance
  if (salesChartInstance) {
    try {
      salesChartInstance.destroy();
    } catch (e) {
      console.warn('Error destroying tracked chart:', e);
    }
    salesChartInstance = null;
  }
  
  // Method 3: Force cleanup of Chart.js registry
  try {
    if (typeof Chart.instances !== 'undefined') {
      Object.keys(Chart.instances).forEach(key => {
        try {
          const chart = Chart.instances[key];
          if (chart && chart.canvas === ctx) {
            chart.destroy();
          }
        } catch (e) {
          console.warn('Error destroying chart from registry:', e);
        }
      });
    }
  } catch (e) {
    console.warn('Error with Chart.instances cleanup:', e);
  }
  
  // Method 4: Clear the canvas manually
  try {
    const context = ctx.getContext('2d');
    if (context) {
      context.clearRect(0, 0, ctx.width, ctx.height);
    }
  } catch (e) {
    console.warn('Error clearing canvas:', e);
  }
  
  // Format dates for X-axis based on data
  const formatDateLabel = (dateString) => {
    const date = new Date(dateString);
    const now = new Date();
    const isToday = date.toDateString() === now.toDateString();
    const isThisYear = date.getFullYear() === now.getFullYear();
    
    if (isToday) {
      return 'Today';
    } else if (isThisYear) {
      return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    } else {
      return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: '2-digit' });
    }
  };
  
  // Calculate Y-axis scaling based on data range
  const values = data.data.filter(v => v > 0); // Filter out zero values for better scaling
  const minValue = values.length > 0 ? Math.min(...values) : 0;
  const maxValue = values.length > 0 ? Math.max(...values) : 100;
  
  // Create Y-axis ticks with proper scaling
  const createYTicks = (min, max) => {
    if (min === max) {
      return [min, min + (min * 0.1)];
    }
    
    const range = max - min;
    const step = range / 4; // 5 ticks total
    const ticks = [];
    
    for (let i = 0; i <= 4; i++) {
      const value = min + (step * i);
      ticks.push(Math.round(value));
    }
    
    return ticks;
  };
  
  const yTicks = createYTicks(minValue, maxValue);
  
  // Small delay to ensure everything is cleaned up
  setTimeout(() => {
    try {
      salesChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels.map(formatDateLabel),
          datasets: [
            {
              label: 'Sales',
              data: data.data,
              borderColor: '#00CC61',
              borderWidth: 3,
              tension: 0.4,
              fill: true,
              backgroundColor: 'rgba(0, 204, 97, 0.1)',
              pointBackgroundColor: '#00CC61',
              pointBorderColor: '#ffffff',
              pointBorderWidth: 2,
              pointRadius: 4,
              pointHoverRadius: 6,
              pointHoverBorderWidth: 3,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: {
            intersect: false,
            mode: 'index',
          },
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.9)',
              titleColor: '#ffffff',
              bodyColor: '#ffffff',
              padding: 12,
              titleFont: { family: 'Inter', size: 14, weight: '600' },
              bodyFont: { family: 'Inter', size: 13 },
              cornerRadius: 8,
              displayColors: false,
              callbacks: {
                title: function(context) {
                  return context[0].label;
                },
                label: function(context) {
                  return '$' + context.parsed.y.toLocaleString(undefined, { 
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 2 
                  });
                }
              }
            },
          },
          scales: {
            y: { 
              beginAtZero: false,
              min: minValue > 0 ? minValue * 0.9 : 0,
              max: maxValue * 1.1,
              ticks: { 
                font: { family: 'Inter', size: 12 },
                color: '#6B7280',
                callback: function(value) {
                  return '$' + value.toLocaleString();
                }
              },
              grid: { 
                color: 'rgba(0, 0, 0, 0.05)',
                drawBorder: false
              },
              border: {
                display: false
              }
            },
            x: { 
              grid: { display: false },
              ticks: { 
                font: { family: 'Inter', size: 12 },
                color: '#6B7280',
                maxRotation: 45,
                minRotation: 0
              },
              border: {
                display: false
              }
            },
          },
          animation: { 
            duration: 800, 
            easing: 'easeOutQuart' 
          },
          elements: {
            point: {
              hoverBackgroundColor: '#00CC61',
              hoverBorderColor: '#ffffff',
            }
          }
        },
      });
    } catch (e) {
      console.error('Error creating chart:', e);
    }
  }, 100);
}

// DOMContentLoaded
document.addEventListener('DOMContentLoaded', function () {
  if (typeof woodashData === 'undefined') return;

  // Initial fetch using WooCommerce Analytics
  (function initialLoad() {
    fetchAnalytics('today').then(async ({ revenue, orders, after, before }) => {
      // Cards (use net revenue & API AOV)
      const gross = Number(revenue?.totals?.gross_revenue || 0);
      const net = Number(revenue?.totals?.net_revenue || 0);
      const numOrders = Number(orders?.totals?.orders_count || 0);
      const aovApi = Number(revenue?.totals?.average_order_value || 0);
      const aov = aovApi || (numOrders > 0 ? net / numOrders : 0);

      woodashDebug('cards-initial', { after, before, gross, net, numOrders, aovApi, aov });

      const elSales = document.getElementById('total-sales');
      if (elSales) elSales.textContent = '$' + net.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
      const elOrders = document.getElementById('total-orders');
      if (elOrders) elOrders.textContent = numOrders.toLocaleString();
      const elAov = document.getElementById('aov');
      if (elAov) elAov.textContent = '$' + aov.toFixed(2);

      // New customers
      try {
        const newCustomers = await fetchNewCustomers(after, before);
        const elNew = document.getElementById('new-customers');
        if (elNew) elNew.textContent = newCustomers.toLocaleString();
        woodashDebug('customers-initial', { newCustomers, after, before });
      } catch (e) {
        woodashDebug('customers-initial-error', { error: String(e) });
      }

      // Processing badge
      const processing = await fetchProcessingCount(after, before);
      const elPending = document.getElementById('pending-orders');
      if (elPending) elPending.textContent = `Processing : ${processing.toLocaleString()}`;

      // Overview net revenue
      const intervals = revenue?.intervals || [];
      const labels = intervals.map((i) => i.date_start);
      const series = intervals.map((i) => Number(i.subtotals?.net_revenue || 0));
      woodashDebug('overview-initial', { labelsSample: labels.slice(0, 6), seriesSample: series.slice(0, 6) });
      updateSalesOverview({ labels, data: series });
    });
  })();

  // Date filter change
  const dateFilter = document.getElementById('date-filter');
  if (dateFilter)
    dateFilter.addEventListener('change', function () {
      const range = this.value;
      if (range === 'custom') {
        document.getElementById('custom-date-from').classList.remove('hidden');
        document.getElementById('custom-date-to').classList.remove('hidden');
        return;
      }
      document.getElementById('custom-date-from').classList.add('hidden');
      document.getElementById('custom-date-to').classList.add('hidden');

      fetchAnalytics(range).then(async ({ revenue, orders, after, before }) => {
        const gross = Number(revenue?.totals?.gross_revenue || 0);
        const net = Number(revenue?.totals?.net_revenue || 0);
        const numOrders = Number(orders?.totals?.orders_count || 0);
        const aovApi = Number(revenue?.totals?.average_order_value || 0);
        const aov = aovApi || (numOrders > 0 ? net / numOrders : 0);
        woodashDebug('cards-range', { range, after, before, gross, net, numOrders, aovApi, aov });
        const elSales = document.getElementById('total-sales');
        if (elSales) elSales.textContent = '$' + net.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const elOrders = document.getElementById('total-orders');
        if (elOrders) elOrders.textContent = numOrders.toLocaleString();
        const elAov = document.getElementById('aov');
        if (elAov) elAov.textContent = '$' + aov.toFixed(2);

        try {
          const newCustomers = await fetchNewCustomers(after, before);
          const elNew = document.getElementById('new-customers');
          if (elNew) elNew.textContent = newCustomers.toLocaleString();
          woodashDebug('customers-range', { range, newCustomers, after, before });
        } catch (e) {
          woodashDebug('customers-range-error', { range, error: String(e) });
        }

        const processing = await fetchProcessingCount(after, before);
        const elPending = document.getElementById('pending-orders');
        if (elPending) elPending.textContent = `Processing : ${processing.toLocaleString()}`;

        const intervals = revenue?.intervals || [];
        const labels = intervals.map((i) => i.date_start);
        const series = intervals.map((i) => Number(i.subtotals?.net_revenue || 0));
        woodashDebug('overview-range', { range, labelsSample: labels.slice(0, 6), seriesSample: series.slice(0, 6) });
        updateSalesOverview({ labels, data: series });
      });
    });

  // Apply custom date
  const applyCustomDateButton = document.getElementById('apply-custom-date');
  if (applyCustomDateButton)
    applyCustomDateButton.addEventListener('click', function () {
      const customDateFrom = document.getElementById('custom-date-from');
      const customDateTo = document.getElementById('custom-date-to');
      const dateFrom = customDateFrom.value;
      const dateTo = customDateTo.value;
      if (dateFrom && dateTo) {
        fetchAnalytics({ from: dateFrom, to: dateTo }).then(async ({ revenue, orders, after, before }) => {
          const gross = Number(revenue?.totals?.gross_revenue || 0);
          const net = Number(revenue?.totals?.net_revenue || 0);
          const numOrders = Number(orders?.totals?.orders_count || 0);
          const aovApi = Number(revenue?.totals?.average_order_value || 0);
          const aov = aovApi || (numOrders > 0 ? net / numOrders : 0);
          woodashDebug('cards-custom', { from: dateFrom, to: dateTo, after, before, gross, net, numOrders, aovApi, aov });
          const elSales = document.getElementById('total-sales');
          if (elSales) elSales.textContent = '$' + net.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
          const elOrders = document.getElementById('total-orders');
          if (elOrders) elOrders.textContent = numOrders.toLocaleString();
          const elAov = document.getElementById('aov');
          if (elAov) elAov.textContent = '$' + aov.toFixed(2);

          try {
            const newCustomers = await fetchNewCustomers(after, before);
            const elNew = document.getElementById('new-customers');
            if (elNew) elNew.textContent = newCustomers.toLocaleString();
            woodashDebug('customers-custom', { from: dateFrom, to: dateTo, newCustomers, after, before });
          } catch (e) {
            woodashDebug('customers-custom-error', { from: dateFrom, to: dateTo, error: String(e) });
          }

          const processing = await fetchProcessingCount(after, before);
          const elPending = document.getElementById('pending-orders');
          if (elPending) elPending.textContent = `Processing : ${processing.toLocaleString()}`;

          const intervals = revenue?.intervals || [];
          const labels = intervals.map((i) => i.date_start);
          const series = intervals.map((i) => Number(i.subtotals?.net_revenue || 0));
          woodashDebug('overview-custom', { from: dateFrom, to: dateTo, labelsSample: labels.slice(0, 6), seriesSample: series.slice(0, 6) });
          updateSalesOverview({ labels, data: series });
        });
      }
    });

  // Logout functionality
  const logoutBtn = document.querySelector('.woodash-logout-btn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function(e) {
      e.preventDefault();
      
      // Show loading state
      const originalText = this.textContent;
      this.textContent = 'Logging out...';
      this.style.pointerEvents = 'none';
      
      // Call logout AJAX action
      const params = new URLSearchParams();
      params.append('action', 'woodash_logout');
      params.append('nonce', woodashData.nonce);
      
      fetch(woodashData.ajaxurl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
        body: params.toString(),
      })
      .then(response => response.json())
      .then(data => {
        if (data.success && data.data && data.data.redirect_url) {
          // Redirect to activation page
          window.location.href = data.data.redirect_url;
        } else {
          // Fallback: redirect to activation page manually
          window.location.href = 'admin.php?page=woodash-pro-activate';
        }
      })
      .catch(error => {
        console.error('Logout error:', error);
        // Fallback: redirect to activation page manually
        window.location.href = 'admin.php?page=woodash-pro-activate';
      })
      .finally(() => {
        // Reset button state
        this.textContent = originalText;
        this.style.pointerEvents = 'auto';
      });
    });
  }
});



