<script>
    let charts = {};
    let currentData = {
        timeData: [],

        customerData: [],
        productData: []
    };

    // Initialize charts
    function initializeCharts() {
        updateTimeChart();
    }

    // Fetch data from server
    async function fetchData(endpoint, params = {}) {
        try {
            const url = new URL(`/admin/data/${endpoint}`, window.location.origin);
            Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

            const response = await fetch(url);
            if (!response.ok) throw new Error('Network response was not ok');
            return await response.json();
        } catch (error) {
            console.error('Error fetching data:', error);
            return [];
        }
    }

    // Time chart
    async function updateTimeChart() {
        const displayType = document.getElementById('timeDisplayType').value;
        const fromDate = document.getElementById('timeFromDate').value;
        const toDate = document.getElementById('timeToDate').value;

        // Show loading
        document.getElementById('timeLoading').style.display = 'block';
        document.querySelector('#time-content .chart-container').style.display = 'none';
        document.getElementById('timeDetailTable').style.display = 'none';

        // Fetch data
        currentData.timeData = await fetchData('time', { from_date: fromDate, to_date: toDate });

        // Hide loading
        document.getElementById('timeLoading').style.display = 'none';

        const ctx = document.getElementById('timeChart').getContext('2d');

        if (charts.timeChart) {
            charts.timeChart.destroy();
        }

        if (displayType === 'detail') {
            document.getElementById('timeDetailTable').style.display = 'block';

            const tbody = document.getElementById('timeTableBody');
            tbody.innerHTML = '';
            currentData.timeData.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.date}</td>
                        <td>${item.invoices}</td>
                        <td>${parseFloat(item.revenue).toLocaleString('vi-VN')} VNĐ</td>
                    </tr>
                `;
            });
        } else {
            document.querySelector('#time-content .chart-container').style.display = 'block';

            charts.timeChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: currentData.timeData.map(item => item.date),
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: currentData.timeData.map(item => parseFloat(item.revenue)),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + ' VNĐ';
                                }
                            }
                        }
                    }
                }
            });
        }
    }



    // Customer chart
    async function updateCustomerChart() {
        const displayType = document.getElementById('customerDisplayType').value;

        // Show loading
        document.getElementById('customerLoading').style.display = 'block';
        document.querySelector('#customer-content .chart-container').style.display = 'none';
        document.getElementById('customerDetailTable').style.display = 'none';

        // Fetch data
        currentData.customerData = await fetchData('customer');

        // Hide loading
        document.getElementById('customerLoading').style.display = 'none';

        const ctx = document.getElementById('customerChart').getContext('2d');

        if (charts.customerChart) {
            charts.customerChart.destroy();
        }

        if (displayType === 'detail') {
            document.getElementById('customerDetailTable').style.display = 'block';

            const tbody = document.getElementById('customerTableBody');
            tbody.innerHTML = '';
            currentData.customerData.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td>${item.orders}</td>
                        <td>${parseFloat(item.total).toLocaleString('vi-VN')} VNĐ</td>
                    </tr>
                `;
            });
        } else {
            document.querySelector('#customer-content .chart-container').style.display = 'block';

            charts.customerChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: currentData.customerData.map(item => item.name),
                    datasets: [{
                        data: currentData.customerData.map(item => parseFloat(item.total)),
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                            '#4BC0C0', '#FF6384'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.parsed.toLocaleString('vi-VN') + ' VNĐ';
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Product chart
    async function updateProductChart() {
        const displayType = document.getElementById('productDisplayType').value;

        // Show loading
        document.getElementById('productLoading').style.display = 'block';
        document.querySelector('#product-content .chart-container').style.display = 'none';
        document.getElementById('productDetailTable').style.display = 'none';

        // Fetch data
        currentData.productData = await fetchData('product');

        // Hide loading
        document.getElementById('productLoading').style.display = 'none';

        const ctx = document.getElementById('productChart').getContext('2d');

        if (charts.productChart) {
            charts.productChart.destroy();
        }

        if (displayType === 'detail' || displayType === 'sales') {
            document.getElementById('productDetailTable').style.display = 'block';

            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '';
            currentData.productData.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>${parseFloat(item.revenue).toLocaleString('vi-VN')} VNĐ</td>
                    </tr>
                `;
            });
        } else {
            document.querySelector('#product-content .chart-container').style.display = 'block';

            charts.productChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: currentData.productData.map(item => item.name.length > 20 ? item.name.substring(0, 20) + '...' : item.name),
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: currentData.productData.map(item => parseFloat(item.revenue)),

                        backgroundColor: 'rgba(255, 206, 86, 0.8)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString('vi-VN') + ' VNĐ';
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });
        }
    }

    // Export to Excel
    function exportToExcel(type) {
        let data = [];
        let headers = [];
        let filename = '';

        switch(type) {
            case 'time':
                data = currentData.timeData;
                headers = ['Ngày', 'Số hóa đơn', 'Doanh thu'];
                filename = 'thong-ke-theo-thoi-gian.xlsx';
                break;

            case 'customer':
                data = currentData.customerData;
                headers = ['Khách hàng', 'Số đơn hàng', 'Tổng tiền'];
                filename = 'thong-ke-theo-khach-hang.xlsx';
                break;
            case 'product':
                data = currentData.productData;
                headers = ['Sản phẩm', 'Số lượng bán', 'Doanh thu'];
                filename = 'thong-ke-theo-san-pham.xlsx';
                break;
        }

        if (data.length === 0) {
            alert('Không có dữ liệu để xuất!');
            return;
        }

        // Create CSV content
        let csvContent = headers.join(',') + '\n';

        data.forEach(item => {
            let row = [];
            switch(type) {
                case 'time':
                    row = [item.date, item.invoices, item.revenue];
                    break;

                case 'customer':
                    row = [item.name, item.orders, item.total];
                    break;
                case 'product':
                    row = [item.name, item.quantity, item.revenue];
                    break;
            }
            csvContent += row.join(',') + '\n';
        });

        // Create and download file
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');

        if (link.download !== undefined) {
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', filename.replace('.xlsx', '.csv'));
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    // Tab change handlers
    document.getElementById('reportTabs').addEventListener('shown.bs.tab', function (event) {
        const target = event.target.getAttribute('data-bs-target');

        switch(target) {

            case '#customer-content':
                if (currentData.customerData.length === 0) {
                    updateCustomerChart();
                }
                break;
            case '#product-content':
                if (currentData.productData.length === 0) {
                    updateProductChart();
                }
                break;
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
    });
</script>
