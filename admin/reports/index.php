<?php 
include('../../resources/database/config.php');
include ('../includes/template.html');
include("../includes/system_update.php");
?>

<!-- Add Google Fonts and our custom stylesheet -->
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<link href="css/dashboard-styles.css" rel="stylesheet">

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
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize default values for demonstration
    document.getElementById('total-bookings').textContent = '156';
    document.getElementById('total-earnings').textContent = '$24,850';
    document.getElementById('total-discounts').textContent = '$3,200';
    document.getElementById('pending-payments').textContent = '$1,850';

    let chart;
    function updateMetrics() {
        const startDate = document.getElementById("start-date").value;
        const endDate = document.getElementById("end-date").value;
        console.log("Updating metrics for date range:", startDate, "to", endDate);
        
        // Simulating data update for demonstration
        // In a real application, this would fetch data from the server
        setTimeout(() => {
            document.getElementById('total-bookings').textContent = Math.floor(Math.random() * 200 + 100);
            document.getElementById('total-earnings').textContent = '$' + Math.floor(Math.random() * 30000 + 10000).toLocaleString();
            document.getElementById('total-discounts').textContent = '$' + Math.floor(Math.random() * 5000 + 1000).toLocaleString();
            document.getElementById('pending-payments').textContent = '$' + Math.floor(Math.random() * 3000 + 500).toLocaleString();
            
            // Update chart with new date range
            updateChart('Total Bookings');
        }, 500);
    }

    function previewPDF() {
        alert("Preview PDF functionality to be implemented.");
    }

    function downloadPDF() {
        alert("Download PDF functionality to be implemented.");
    }

    function updateChart(metric) {
        // Sample data - in a real application, this would be fetched from the server
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
        const datasets = {
            'Total Bookings': {
                data: [42, 58, 37, 45, 52, 48, 61],
                backgroundColor: 'rgba(52, 152, 219, 0.7)',
                borderColor: 'rgba(52, 152, 219, 1)'
            },
            'Total Earnings': {
                data: [3500, 4200, 3100, 3750, 4500, 4000, 5200],
                backgroundColor: 'rgba(46, 204, 113, 0.7)',
                borderColor: 'rgba(46, 204, 113, 1)'
            },
            'Total Discounts': {
                data: [450, 580, 390, 520, 630, 550, 710],
                backgroundColor: 'rgba(243, 156, 18, 0.7)',
                borderColor: 'rgba(243, 156, 18, 1)'
            },
            'Pending Payments': {
                data: [320, 410, 280, 350, 420, 380, 450],
                backgroundColor: 'rgba(231, 76, 60, 0.7)',
                borderColor: 'rgba(231, 76, 60, 1)'
            }
        };
        
        if (chart) {
            chart.destroy();
        }
        
        const ctx = document.getElementById('bookingChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: metric,
                    data: datasets[metric].data,
                    backgroundColor: datasets[metric].backgroundColor,
                    borderColor: datasets[metric].borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: metric + ' by Month',
                        font: {
                            size: 16
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        // Make the chart container taller for better visibility
        document.getElementById('bookingChart').style.height = '400px';
    }

    // Initialize chart with default data
    window.onload = function() {
        updateChart('Total Bookings');
    };
</script>
<style>
    /* Dashboard Custom Styles */
:root {
  --primary-color: #3498db;
  --success-color: #2ecc71;
  --warning-color: #f39c12;
  --danger-color: #e74c3c;
  --light-bg: #f8f9fa;
  --dark-text: #2c3e50;
  --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

body {
  font-family: 'Open Sans', sans-serif;
  background-color: #f5f7fa;
  color: var(--dark-text);
}

.content {
  padding: 20px 0;
}

.dashboard-header {
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: var(--card-shadow);
}

.dashboard-header h2 {
  color: var(--dark-text);
  font-weight: 600;
  margin-bottom: 0;
}

.date-range-container {
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: var(--card-shadow);
}

.metric-card {
  border-radius: 8px;
  padding: 15px;
  height: 100%;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
  box-shadow: var(--card-shadow);
  margin-bottom: 20px;
}

.metric-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
}

.metric-card .card-title {
  font-size: 1rem;
  font-weight: 600;
}

.metric-card .card-text {
  font-size: 1.8rem;
  font-weight: 700;
  margin-top: 10px;
  margin-bottom: 0;
}

.primary-card {
  background: linear-gradient(135deg, #3498db, #2980b9);
}

.success-card {
  background: linear-gradient(135deg, #2ecc71, #27ae60);
}

.warning-card {
  background: linear-gradient(135deg, #f39c12, #e67e22);
}

.danger-card {
  background: linear-gradient(135deg, #e74c3c, #c0392b);
}

.chart-container {
  background-color: white;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  box-shadow: var(--card-shadow);
}

.action-buttons {
  background-color: white;
  border-radius: 8px;
  padding: 15px;
  box-shadow: var(--card-shadow);
}

.btn-custom-primary {
  background-color: var(--primary-color);
  border: none;
  border-radius: 4px;
  padding: 8px 20px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-custom-primary:hover {
  background-color: #2980b9;
  transform: translateY(-2px);
}

.btn-custom-secondary {
  background-color: #95a5a6;
  border: none;
  border-radius: 4px;
  padding: 8px 20px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-custom-secondary:hover {
  background-color: #7f8c8d;
  transform: translateY(-2px);
}

.btn-custom-success {
  background-color: var(--success-color);
  border: none;
  border-radius: 4px;
  padding: 8px 20px;
  font-weight: 600;
  transition: all 0.3s ease;
}

.btn-custom-success:hover {
  background-color: #27ae60;
  transform: translateY(-2px);
}

@media (max-width: 768px) {
  .metric-card .card-text {
    font-size: 1.5rem;
  }
}
</style>