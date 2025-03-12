<?php
include('../../resources/database/config.php');
include('../bootstrap.php');

if (!isset($_SESSION['ID'])) {
    header("location: ../login.php");
    exit;
}

$account_id = $_SESSION['ID'];

// Check if the user has any completed bookings
$sql = "SELECT * FROM booking WHERE account_id = ? AND book_status = 'completed' LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'i', $account_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_array($result);

if (mysqli_num_rows($result) == 0) {
    echo "<div class='alert alert-danger text-center'>You can only submit feedback if you have a completed booking.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
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

        .feedback-container {
            max-width: 800px;
            margin: 0 auto 3rem;
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
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

        .form-label {
            color: var(--primary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 0.2rem rgba(230, 126, 34, 0.25);
        }

        .rating-group {
            margin-bottom: 1.5rem;
        }

        .submit-btn {
            background: var(--accent);
            color: var(--white);
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            background: #d35400;
            transform: translateY(-2px);
        }

        .form-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .form-section-title {
            color: var(--primary);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .feedback-container {
                margin: 1rem;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include('../view/navbar.php'); ?>

    <div class="main-content">
        <div class="container">
            <div class="feedback-container">
                <h2 class="section-title">Submit Your Feedback</h2>
                <form action="store.php" method="POST">
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-star"></i> Overall Rating
                        </div>
                        <div class="mb-3">
                            <label class="form-label">How would you rate your overall experience? (1-5)</label>
                            <select name="rating" class="form-select" required>
                                <option value="">Select Rating</option>
                                <option value="1">1 - Poor</option>
                                <option value="2">2 - Fair</option>
                                <option value="3">3 - Good</option>
                                <option value="4">4 - Very Good</option>
                                <option value="5">5 - Excellent</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-comment-dots"></i> Detailed Feedback
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Overall Experience:</label>
                            <textarea name="overall_experience" class="form-control" rows="3" 
                                    placeholder="Please share your overall experience with us..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Room Cleanliness:</label>
                            <textarea name="room_cleanliness" class="form-control" rows="3"
                                    placeholder="How was the cleanliness of your room?" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Staff Service:</label>
                            <textarea name="staff_service" class="form-control" rows="3"
                                    placeholder="How was your experience with our staff?" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Facilities:</label>
                            <textarea name="facilities" class="form-control" rows="3"
                                    placeholder="What did you think about our facilities?" required></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-pencil-alt"></i> Additional Comments
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Additional Feedback:</label>
                            <textarea name="comment" class="form-control" rows="4"
                                    placeholder="Please share any additional comments or suggestions..." required></textarea>
                        </div>
                    </div>

                    <input type="hidden" name="book_id" value="<?php echo $booking['book_id'] ?>">
                    <button type="submit" class="submit-btn w-100">
                        <i class="fas fa-paper-plane"></i> Submit Feedback
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php include('../view/footer.php'); ?>
</body>
</html>