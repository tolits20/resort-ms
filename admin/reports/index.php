<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include ('../includes/template.php');
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
        document.getElementById('total-earnings').textContent = '$' + parseFloat(data.data.total_earnings).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('total-discounts').textContent = '$' + parseFloat(data.data.total_discounts).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        document.getElementById('pending-payments').textContent = '$' + parseFloat(data.data.pending_payments).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        
        window.chartData = data.data.chart_data;
        updateChart('Total Bookings', window.chartData);
    })
    .catch(error => {
        console.error('Error fetching data:', error);
        alert('Failed to fetch data. Please try again later.');
    });
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



window.onload = function() {
    let currentDate = new Date();
    let startDate = new Date(currentDate.getFullYear(), 0, 1); // January 1st of the current year

    console.log("Default Start Date:", startDate.toISOString().split("T")[0]);
    console.log("Default End Date:", currentDate.toISOString().split("T")[0]);

    // Set values to date inputs
    document.getElementById('start-date').value = startDate.toISOString().split("T")[0];
    document.getElementById('end-date').value = currentDate.toISOString().split("T")[0];

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
            
            // Clean up
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


  .content {
    padding: 20px 0;
    background-color: white;

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