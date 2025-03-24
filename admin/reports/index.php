<?php 
include('../../resources/database/config.php');
include('../includes/page_authentication.php');
include('../includes/template.php');
include("../includes/system_update.php");

$sql = "SELECT a.username, COUNT(ta.task_id) AS completed_tasks
        FROM task_assignees ta
        JOIN account a ON ta.staff_id = a.account_id
        WHERE ta.assignee_task = 'Complete'
        GROUP BY a.username
        ORDER BY completed_tasks DESC";
$result = mysqli_query($conn,$sql);

$sql2= "SELECT 
            r.room_type, 
            COUNT(b.book_id) AS total_bookings,
            ROUND((COUNT(b.book_id) / (SELECT COUNT(*) FROM booking WHERE book_status = 'completed')) * 100, 2) AS occupancy_rate
        FROM booking b
        JOIN room r ON b.room_id = r.room_id
        WHERE b.book_status = 'completed'
        GROUP BY r.room_type";
$result2 = mysqli_query($conn,$sql2);

$sql3 = "SELECT 
            t.title,
            SUM(CASE WHEN ta.assignee_task = 'Complete' THEN 1 ELSE 0 END) AS completed,
            SUM(CASE WHEN ta.assignee_task = 'Overdue' THEN 1 ELSE 0 END) AS overdue,
            SUM(CASE WHEN ta.assignee_task = 'Pending' THEN 1 ELSE 0 END) AS pending
        FROM tasks t
        LEFT JOIN task_assignees ta ON t.id = ta.task_id
        GROUP BY t.title";
$result3 = $conn->query($sql3);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Management Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/dashboard-styles.css" rel="stylesheet">
</head>
<body>

<div class="content">
    <div class="container">
        <!-- Dashboard Header with DateTime and User Info -->
        <div class="dashboard-header">
            <div class="header-info">
                <h2>Resort Management Dashboard</h2>
                <div class="user-datetime">
                    <span class="datetime">UTC: 2025-03-24 08:42:23</span>
                    <span class="user">User: S1ngularty</span>
                </div>
            </div>
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
        <div class="row metric-cards">
            <div class="col-md-3 col-sm-6">
                <div class="metric-card primary-card" onclick="updateChart('Total Bookings')">
                    <h5 class="card-title">Total Bookings</h5>
                    <p class="card-text" id="total-bookings">0</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="metric-card success-card" onclick="updateChart('Total Earnings')">
                    <h5 class="card-title">Total Earnings</h5>
                    <p class="card-text" id="total-earnings">$0</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="metric-card warning-card" onclick="updateChart('Total Discounts')">
                    <h5 class="card-title">Total Discounts</h5>
                    <p class="card-text" id="total-discounts">$0</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="metric-card danger-card" onclick="updateChart('Pending Payments')">
                    <h5 class="card-title">Pending Payments</h5>
                    <p class="card-text" id="pending-payments">$0</p>
                </div>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="chart-container">
            <canvas id="bookingChart"></canvas>
        </div>

        <div class="dashboard-grid">
    <!-- Staff Rankings Section -->
    <div class="table-section staff-ranking-table">
        <h3>
            Top Staff Members
            <span class="badge bg-primary">By Task Completion</span>
        </h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="rank-column">Rank</th>
                        <th>Staff Name</th>
                        <th>Completed Tasks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rank = 1;
                    while($row = $result->fetch_assoc()) {
                        $rankClass = ($rank <= 3) ? "rank-$rank" : "rank-other";
                        echo "<tr>
                                <td class='rank-column' data-label='Rank'>
                                    <span class='rank-badge $rankClass'>$rank</span>
                                </td>
                                <td data-label='Staff Name'>" . htmlspecialchars($row["username"]) . "</td>
                                <td data-label='Completed Tasks'>" . htmlspecialchars($row["completed_tasks"]) . "</td>
                              </tr>";
                        $rank++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Room Occupancy Section -->
    <div class="table-section room-occupancy-table">
        <h3>
            Room Occupancy
            <span class="badge bg-success">Current Status</span>
        </h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th>Total Bookings</th>
                        <th>Occupancy Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($row1 = $result2->fetch_assoc()) {
                        $rate = floatval($row1["occupancy_rate"]);
                        echo "<tr>
                                <td data-label='Room Type'>" . htmlspecialchars($row1["room_type"]) . "</td>
                                <td data-label='Total Bookings'>" . htmlspecialchars($row1["total_bookings"]) . "</td>
                                <td data-label='Occupancy Rate'>
                                    <div class='progress-bar-container'>
                                        <div class='progress-bar' style='width: " . min(100, $rate) . "%'></div>
                                    </div>
                                    <div class='mt-1'>" . htmlspecialchars($row1["occupancy_rate"]) . "%</div>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Task Performance Section -->
    <div class="table-section task-performance-table" style="grid-column: 1 / -1;">
        <h3>
            Task Performance
            <span class="badge bg-warning">Status Overview</span>
        </h3>
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Completed</th>
                        <th>Overdue</th>
                        <th>Pending</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($row3 = $result3->fetch_assoc()) {
                        echo "<tr>
                                <td data-label='Task Title'>" . htmlspecialchars($row3["title"]) . "</td>
                                <td data-label='Completed'>
                                    <div class='status-cell'>
                                        <span class='status-indicator status-completed'></span>
                                        " . htmlspecialchars($row3["completed"]) . "
                                    </div>
                                </td>
                                <td data-label='Overdue'>
                                    <div class='status-cell'>
                                        <span class='status-indicator status-overdue'></span>
                                        " . htmlspecialchars($row3["overdue"]) . "
                                    </div>
                                </td>
                                <td data-label='Pending'>
                                    <div class='status-cell'>
                                        <span class='status-indicator status-pending'></span>
                                        " . htmlspecialchars($row3["pending"]) . "
                                    </div>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


        <!-- Action Buttons -->
        <div class="action-buttons">
            <button class="btn btn-custom-secondary" onclick="previewPDF()">Preview Report</button>
            <button class="btn btn-custom-success" onclick="downloadPDF()">Download Report</button>
        </div>
    </div>
</div>
<style>
:root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --danger-color: #e63946;
            --light-bg: #f5f7fa;
            --text-color: #2b2d42;
            --card-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

      

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 20px;
    margin-top: 25px;
    margin-bottom: 25px;
}

.table-section {
    background-color: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

.table-section:hover {
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
}

.table-section h3 {
    color: #2b2d42;
    font-size: 1.2rem;
    font-weight: 600;
    margin-top: 0;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.table-section .badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 4px 10px;
    border-radius: 20px;
}

/* Table Styling */
.data-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.data-table thead th {
    background-color: rgba(67, 97, 238, 0.1);
    color: #4361ee;
    font-weight: 600;
    text-align: left;
    padding: 12px 15px;
    font-size: 0.9rem;
    border-bottom: 2px solid rgba(67, 97, 238, 0.2);
}

.data-table tbody tr {
    transition: all 0.3s ease;
}

.data-table tbody tr:hover {
    background-color: rgba(67, 97, 238, 0.05);
}

.data-table tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    font-size: 0.95rem;
}

/* Staff Ranking Table */
.rank-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    color: white;
    font-weight: 600;
    font-size: 0.8rem;
}

.rank-1 {
    background: linear-gradient(135deg, #ffd700, #ffa500);
}

.rank-2 {
    background: linear-gradient(135deg, #c0c0c0, #a9a9a9);
}

.rank-3 {
    background: linear-gradient(135deg, #cd7f32, #a0522d);
}

.rank-other {
    background: linear-gradient(135deg, #6c757d, #495057);
}

/* Responsive Layout */
@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    
    .task-performance-table {
        grid-column: auto !important;
    }
    
    .data-table thead {
        display: none;
    }
    
    .data-table, .data-table tbody, .data-table tr, .data-table td {
        display: block;
        width: 100%;
    }
    
    .data-table tbody td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }
    
    .data-table tbody td:before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        font-weight: 600;
        text-align: left;
    }
    
    .data-table tr {
        margin-bottom: 15px;
        border: 1px solid #eee;
        border-radius: 8px;
    }
}

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
  color: white; /* Add this line to set text color */
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
    height: 450px;
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize default values
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
    
    // Show loading state
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
    const maxValue = Math.max(...chartData.data[metric]) || 10;
    const suggestedMax = Math.ceil(maxValue * 1.3);

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
                    suggestedMax: suggestedMax,
                    ticks: {
                        stepSize: Math.ceil(suggestedMax / 5),
                    }
                }
            }
        }
    });
}

// PDF functions
function previewPDF() {
    const startDate = document.getElementById("start-date").value || 'All Time';
    const endDate = document.getElementById("end-date").value || 'All Time';
    
    fetch('generate_pdf.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `start_date=${startDate}&end_date=${endDate}&action=preview`
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
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
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `start_date=${startDate}&end_date=${endDate}&action=download`
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    })
    .catch(error => {
        console.error('Error downloading PDF:', error);
        alert('Failed to download PDF. Please try again later.');
    });
}

// Initialize on page load
window.onload = function() {
    let currentDate = new Date();
    let startDate = new Date(currentDate.getFullYear(), 0, 1);

    document.getElementById('start-date').value = startDate.toISOString().split("T")[0];
    document.getElementById('end-date').value = currentDate.toISOString().split("T")[0];

    updateMetrics();
};
</script>

</body>
</html>