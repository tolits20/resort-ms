<?php
include('../../resources/database/config.php');
include('../bootstrap.php'); // Ensure correct path

// Prepare and execute the SQL query securely for all feedback
$sql_all_feedback = "SELECT f.*, a.username, b.account_id, r.room_type, r.room_code 
                     FROM feedback f 
                     JOIN booking b ON f.book_id = b.book_id 
                     JOIN account a ON b.account_id = a.account_id 
                     JOIN room r ON b.room_id = r.room_id";
$stmt_all_feedback = $conn->prepare($sql_all_feedback);

if (!$stmt_all_feedback) {
    die("Query preparation failed: " . $conn->error); // Debugging: Show SQL error
}

$stmt_all_feedback->execute();
$result_all_feedback = $stmt_all_feedback->get_result(); // Fetch result from prepared statement
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #7f8c8d;
            --accent: #e67e22;
            --white: #ffffff;
            --dark: #1a1a1a;
        }

        body {
            background: #f8f9fa;
            padding-top: 80px; /* Accommodate fixed navbar */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
            padding: 2rem 0;
        }

        .section-title {
            color: var(--primary);
            font-size: 2rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: var(--accent);
            border-radius: 2px;
        }

        .feedback-container {
            max-width: 1000px;
            margin: 0 auto 3rem;
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .feedback-card {
            background: var(--white);
            border: 1px solid #eee;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feedback-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .feedback-header h5 {
            color: var(--primary);
            font-weight: 600;
            margin: 0;
        }

        .btn-action {
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-add {
            background: var(--accent);
            color: var(--white);
            border: none;
            padding: 0.75rem 2rem;
            margin-bottom: 2rem;
        }

        .btn-add:hover {
            background: #d35400;
            transform: translateY(-2px);
        }

        .btn-edit {
            background: #3498db;
            color: var(--white);
            border: none;
        }

        .btn-edit:hover {
            background: #2980b9;
        }

        .feedback-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .feedback-item {
            padding: 0.5rem;
        }

        .feedback-item strong {
            color: var(--primary);
            display: block;
            margin-bottom: 0.25rem;
        }

        .feedback-comment {
            grid-column: 1 / -1;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .rating-stars {
            color: #f1c40f;
            font-size: 1.1rem;
        }

        .date-info {
            color: var(--secondary);
            font-size: 0.9rem;
            text-align: right;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .feedback-container {
                padding: 1rem;
                margin: 1rem;
            }

            .feedback-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include('guest_navbar.php'); ?>

    <div class="main-content">
        <div class="container">
            <div class="feedback-container">
                <h2 class="section-title">All Feedbacks</h2>
                <?php if ($result_all_feedback->num_rows > 0): ?>
                    <?php while ($row = $result_all_feedback->fetch_assoc()): ?>
                        <div class="feedback-card">
                            <div class="feedback-header">
                                <h5>
                                    <i class="fas fa-user"></i>
                                    <?php echo htmlspecialchars($row['username']); ?>
                                </h5>
                                <div class="rating-stars">
                                    <?php 
                                    $rating = intval($row['rating']);
                                    for($i = 0; $i < $rating; $i++) echo '★';
                                    for($i = $rating; $i < 5; $i++) echo '☆';
                                    ?>
                                </div>
                            </div>
                            <div class="feedback-details">
                                <div class="feedback-item">
                                    <strong>Room Code</strong>
                                    <?php echo htmlspecialchars($row['room_code']); ?>
                                </div>
                                <div class="feedback-item">
                                    <strong>Room Type</strong>
                                    <?php echo htmlspecialchars($row['room_type']); ?>
                                </div>
                                <div class="feedback-item">
                                    <strong>Overall Experience</strong>
                                    <?php echo htmlspecialchars($row['overall_experience']); ?>
                                </div>
                                <div class="feedback-item">
                                    <strong>Room Cleanliness</strong>
                                    <?php echo htmlspecialchars($row['room_cleanliness']); ?>
                                </div>
                                <div class="feedback-item">
                                    <strong>Staff Service</strong>
                                    <?php echo htmlspecialchars($row['staff_service']); ?>
                                </div>
                                <div class="feedback-item">
                                    <strong>Facilities</strong>
                                    <?php echo htmlspecialchars($row['facilities']); ?>
                                </div>
                                <div class="feedback-comment">
                                    <strong>Comment</strong>
                                    <?php echo htmlspecialchars($row['comment']); ?>
                                </div>
                            </div>
                            <div class="date-info">
                                Submitted on <?php echo date('F j, Y', strtotime($row['created_at'])); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="alert alert-info text-center">No feedback available yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include('guest_footer.php'); ?>
</body>
</html>