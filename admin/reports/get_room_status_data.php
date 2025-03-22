It looks like your code is almost complete, but there are a few issues that need to be addressed to ensure everything works smoothly. Below, I'll provide the **final corrected and complete code** for your dashboard, including fixes for the PDF generation and chart initialization.

---

### **Final Corrected Code**

```php
<?php
// Include necessary files
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include('../includes/template.php');
include('../includes/system_update.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Management Dashboard</title>
    <!-- Add Google Fonts and our custom stylesheet -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="css/dashboard-styles.css" rel="stylesheet">
    <style>
        /* Custom CSS for chart containers */
        .chart-wrapper {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            height: 350px; /* Fixed height for the chart wrapper */
        }

        .chart-wrapper canvas {
            height: 300px !important; /* Fixed height for the chart */
            width: 100% !important; /* Responsive width */
        }

        .chart-title {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="container">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <h2>Resort Management Dashboard</h2>
            </div>
            
            <!-- Date Range Selection -->
            <div class="date-range-container">
                <div class="row">
                    <div class="col-md-4">
                        <label for="start-date" class="form-label">Start Date:</label>
                        <input type="date" id="start-date" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="end-date" class="form-label">End Date:</label>
                        <input type="date" id="end-date" class="form-control">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-custom-primary w-100" onclick="updateMetrics()">Apply Filter</button>
                    </div>
                </div>
            </div>

            <!-- Metric Cards -->
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="metric-card primary-card text-white" onclick="updateChart('Total Bookings')">
                        <h5 class="card-title">Total Bookings</h5>
                        <p class="card-text" id="total-bookings">0</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="metric-card success-card text-white" onclick="updateChart('Total Earnings')">
                        <h5 class="card-title">Total Earnings</h5>
                        <p class="card-text" id="total-earnings">$0</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="metric-card warning-card text-white" onclick="updateChart('Total Discounts')">
                        <h5 class="card-title">Total Discounts</h5>
                        <p class="card-text" id="total-discounts">$0</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="metric-card danger-card text-white" onclick="updateChart('Pending Payments')">
                        <h5 class="card-title">Pending Payments</h5>
                        <p class="card-text" id="pending-payments">$0</p>
                    </div>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="chart-container">
                <canvas id="bookingChart"></canvas>
            </div>

            <!-- PDF Buttons -->
            <div class="action-buttons d-flex justify-content-end">
                <button class="btn btn-custom-secondary me-2" onclick="previewPDF()">Preview Report</button>
                <button class="btn btn-custom-success" onclick="downloadPDF()">Download Report</button>
            </div>

            <!-- Room Status Analytics -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="chart-container">
                        <h4 class="chart-title">Room Status Analytics</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="chart-wrapper">
                                    <h5>Most Selected Rooms</h5>
                                    <canvas id="roomPopularityChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="chart-wrapper">
                                    <h5>New Users Registration</h5>
                                    <canvas id="newUsersChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="chart-wrapper">
                                    <h5>Room Occupancy Rate</h5>
                                    <canvas id="occupancyRateChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="chart-wrapper">
                                    <h5>Revenue by Room Type</h5>
                                    <canvas id="revenueByRoomChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize default values for demonstration
        document.getElementById('total-bookings').textContent = '156';
        document.getElementById('total-earnings').textContent = '$24,850';
        document.getElementById('total-discounts').textContent = '$3,200';
        document.getElementById('pending-payments').textContent = '$1,850';

        let chart;
        let roomPopularityChart, newUsersChart, occupancyRateChart, revenueByRoomChart;

        function updateMetrics() {
            const startDate = document.getElementById("start-date").value;
            const endDate = document.getElementById("end-date").value;
            
            if (!startDate || !endDate) {
                alert("Please select both start and end dates");
                return;
            }
            
            document.getElementById('total-bookings').innerHTML = '<small>Loading...</small>';
            document.getElementById('total-earnings').innerHTML = '<small>Loading...</small>';
            document.getElementById('total-discounts').innerHTML = '<small>Loading...</small>';
            document.getElementById('pending-payments').innerHTML = '<small>Loading...</small>';
            
            fetch('get_booking_data.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `start_date=${startDate}&end_date=${endDate}`
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) throw new Error('Failed to fetch data from server.');

                document.getElementById('total-bookings').textContent = data.data.total_bookings;
                document.getElementById('total-earnings').textContent = '₱' + parseFloat(data.data.total_earnings).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                document.getElementById('total-discounts').textContent = '₱' + parseFloat(data.data.total_discounts).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                document.getElementById('pending-payments').textContent = '₱' + parseFloat(data.data.pending_payments).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                
                window.chartData = data.data.chart_data;
                updateChart('Total Bookings', window.chartData);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                alert('Failed to fetch data. Please try again later.');
            });

            // Update room status charts
            updateRoomStatusCharts(startDate, endDate);
        }

        function updateChart(metric, chartData = null) {
            if (!chartData) chartData = window.chartData || defaultChartData;
            renderChart(metric, chartData);
        }

        function renderChart(metric, chartData) {
            const colorMap = {
                'Total Bookings': { backgroundColor: 'rgba(52, 152, 219, 0.7)', borderColor: 'rgba(52, 152, 219, 1)' },
                'Total Earnings': { backgroundColor: 'rgba(46, 204, 113, 0.7)', borderColor: 'rgba(46, 204, 113, 1)' },
                'Total Discounts': { backgroundColor: 'rgba(243, 156, 18, 0.7)', borderColor: 'rgba(243, 156, 18, 1)' },
                'Pending Payments': { backgroundColor: 'rgba(231, 76, 60, 0.7)', borderColor: 'rgba(231, 76, 60, 1)' }
            };

            if (chart) chart.destroy();

            const ctx = document.getElementById('bookingChart').getContext('2d');

            // Find max value for the selected metric and increase by 30%
            const maxValue = Math.max(...chartData.data[metric]) || 10; // Default to 10 if data is empty
            const suggestedMax = Math.ceil(maxValue * 1.3); // Increase max value by 30%

            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: metric,
                        data: chartData.data[metric],
                        backgroundColor: colorMap[metric].backgroundColor,
                        borderColor: colorMap[metric].borderColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        title: { display: true, text: metric + ' by ' + chartData.groupBy, font: { size: 16 } }
                    },
                    scales: {
                        x: { type: 'category', labels: chartData.labels },
                        y: { 
                            beginAtZero: true,
                            suggestedMax: suggestedMax, // Ensures scaling
                            ticks: {
                                stepSize: Math.ceil(suggestedMax / 5), // Dynamic step size
                            }
                        }
                    }
                }
            });

            document.getElementById('bookingChart').style.height = '400px';
        }

        function initRoomStatusCharts() {
            // Room Popularity Chart (Horizontal Bar Chart)
            const roomPopCtx = document.getElementById('roomPopularityChart').getContext('2d');
            roomPopularityChart = new Chart(roomPopCtx, {
                type: 'bar',
                data: {
                    labels: ['Deluxe Suite', 'Executive Room', 'Standard Room', 'Family Room', 'Presidential Suite'],
                    datasets: [{
                        label: 'Number of Bookings',
                        data: [0, 0, 0, 0, 0], // Will be populated with actual data
                        backgroundColor: 'rgba(52, 152, 219, 0.7)',
                        borderColor: 'rgba(52, 152, 219, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y', // Horizontal bar chart
                    responsive: true,
                    maintainAspectRatio: false, // Allow custom height
                    plugins: {
                        legend: { position: 'top' }
                    }
                }
            });
            
            // New Users Chart (Line Chart)
            const newUsersCtx = document.getElementById('newUsersChart').getContext('2d');
            newUsersChart = new Chart(newUsersCtx, {
                type: 'line',
                data: {
                    labels: [], // Will be populated with date labels
                    datasets: [{
                        label: 'New Registrations',
                        data: [], // Will be populated with actual data
                        borderColor: 'rgba(46, 204, 113, 1)',
                        backgroundColor: 'rgba(46, 204, 113, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            
            // Occupancy Rate Chart (Doughnut Chart)
            const occupancyCtx = document.getElementById('occupancyRateChart').getContext('2d');
            occupancyRateChart = new Chart(occupancyCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Occupied', 'Available', 'Maintenance', 'Reserved'],
                    datasets: [{
                        data: [0, 0, 0, 0], // Will be populated with actual data
                        backgroundColor: [
                            'rgba(231, 76, 60, 0.7)',  // Red - Occupied
                            'rgba(46, 204, 113, 0.7)', // Green - Available
                            'rgba(243, 156, 18, 0.7)', // Yellow - Maintenance
                            'rgba(52, 152, 219, 0.7)'  // Blue - Reserved
                        ],
                        borderColor: [
                            'rgba(231, 76, 60, 1)',
                            'rgba(46, 204, 113, 1)',
                            'rgba(243, 156, 18, 1)',
                            'rgba(52, 152, 219, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
            
            // Revenue by Room Type Chart (Bar Chart)
            const revenueByRoomCtx = document.getElementById('revenueByRoomChart').getContext('2d');
            revenueByRoomChart = new Chart(revenueByRoomCtx, {
                type: 'bar',
                data: {
                    labels: ['Deluxe Suite', 'Executive Room', 'Standard Room', 'Family Room', 'Presidential Suite'],
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: [0, 0, 0, 0, 0], // Will be populated with actual data
                        backgroundColor: 'rgba(155, 89, 182, 0.7)',
                        borderColor: 'rgba(155, 89, 182, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function updateRoomStatusCharts(startDate, endDate) {
            fetch('get_room_status_data.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `start_date=${startDate}&end_date=${endDate}`
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) throw new Error('Failed to fetch room status data from server.');
                
                // Update Most Selected Rooms chart
                roomPopularityChart.data.labels = data.room_popularity.labels;
                roomPopularityChart.data.datasets[0].data = data.room_popularity.data;
                roomPopularityChart.update();
                
                // Update New Users chart
                newUsersChart.data.labels = data.new_users.labels;
                newUsersChart.data.datasets[0].data = data.new_users.data;
                newUsersChart.update();
                
                // Update Occupancy Rate chart
                occupancyRateChart.data.datasets[0].data = [
                    data.occupancy.occupied,
                    data.occupancy.available,
                    data.occupancy.maintenance,
                    data.occupancy.reserved
                ];
                occupancyRateChart.update();
                
                // Update Revenue by Room Type chart
                revenueByRoomChart.data.labels = data.revenue_by_room.labels;
                revenueByRoomChart.data.datasets[0].data = data.revenue_by_room.data;
                revenueByRoomChart.update();
            })
            .catch(error => {
                console.error('Error fetching room status data:', error);
                alert('Failed to fetch room status data. Please try again later.');
            });
        }

        window.onload = function() {
            let currentDate = new Date();
            let startDate = new Date(currentDate.getFullYear(), 0, 1); // January 1st of the current year

            console.log("Default Start Date:", startDate.toISOString().split("T")[0]);
            console.log("Default End Date:", currentDate.toISOString().split("T")[0]);

            // Set values to date inputs
            document.getElementById('start-date').value = startDate.toISOString().split("T")[0];
            document.getElementById('end-date').value = currentDate.toISOString().split("T")[0];

            // Initialize the room status charts
            initRoomStatusCharts();

            // Load data based on these dates
            updateMetrics();
        };

        // Add functionality for PDF generation
        function previewPDF() {
            const startDate = document.getElementById("start-date").value || 'All Time';
            const endDate = document.getElementById("end-date").value || 'All Time';
            
            fetch('generate_pdf.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `start_date=${startDate}&end_date=${endDate}&action=preview`
            })
            .then(response => response.blob())
            .then(blob => {
                // Create a blob URL for the PDF
                const url = window.URL.createObjectURL(blob);
                
                // Open the PDF in a new tab
                window.open(url, '_blank');
                
                // Clean up the blob URL after the PDF is opened
                window.URL.revokeObjectURL(url);
            })
            .catch(error => {
                console.error('Error generating PDF:', error);
                alert('Failed to generate PDF preview. Please try again later.');
            });
        }

        function downloadPDF() {
            const startDate = document.getElementById("start-date").value || 'All Time';
            const endDate = document.getElementById("end-date").value || 'All Time';
            const filename = `Resort_Report_${startDate}_to_${endDate}.pdf`.replace(/[\/\\]/g, '-');
            
            fetch('generate_pdf.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `start_date=${startDate}&end_date=${endDate}&action=download`
            })
            .then(response => response.blob())
            .then(blob => {
                // Create a blob URL for the PDF
                const url = window.URL.createObjectURL(blob);
                
                // Create a download link
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                
                // Clean up the blob URL and remove the link
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            })
            .catch(error => {
                console.error('Error downloading PDF:', error);
                alert('Failed to download PDF. Please try again later.');
            });
        }

        // Function to switch between data metrics in the chart
        function switchMetric(metric) {
            // Update active state in the UI
            document.querySelectorAll('.metric-switcher button').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`.metric-switcher button[data-metric="${metric}"]`).classList.add('active');
            
            // Update the chart
            updateChart(metric);
        }

        // Function to refresh all data (can be called from a button)
        function refreshData() {
            updateMetrics();
        }
    </script>
</body>
</html>