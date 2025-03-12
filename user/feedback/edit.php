<?php
include('../../resources/database/config.php');
include("../../admin/includes/system_update.php");

if (!isset($_SESSION['ID'])) {
    header("location: ../../login.php");
    exit;
}

$account_id = $_SESSION['ID'];
$feedback_id = $_GET['feedback_id'] ?? 0;

// Check if the feedback exists and belongs to the user
$sql = "SELECT f.*, b.book_id 
        FROM feedback f 
        JOIN booking b ON f.book_id = b.book_id 
        WHERE f.feedback_id = ? AND b.account_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $feedback_id, $account_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Feedback not found or you are not authorized.");
}

$feedback = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Feedback</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #7f8c8d;
            --accent: #e67e22;
            --white: #ffffff;
            --dark: #1a1a1a;
            --success: #27ae60;
            --danger: #c0392b;
        }

        body {
            background: #f8f9fa;
            padding-top: 80px;
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

        .btn-update {
            background: var(--success);
            color: var(--white);
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-update:hover {
            background: #219a52;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: var(--secondary);
            color: var(--white);
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #6c7a89;
            transform: translateY(-2px);
        }

        .button-group {
            display: grid;
            gap: 1rem;
            margin-top: 2rem;
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
                <h2 class="section-title">Edit Your Feedback</h2>
                <form action="update.php" method="POST">
                    <input type="hidden" name="feedback_id" value="<?php echo $feedback['feedback_id']; ?>">
                    <input type="hidden" name="book_id" value="<?php echo $feedback['book_id']; ?>">

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-star"></i> Overall Rating
                        </div>
                        <div class="mb-3">
                            <label class="form-label">How would you rate your overall experience? (1-5)</label>
                            <select name="rating" class="form-select" required>
                                <option value="1" <?php if ($feedback['rating'] == 1) echo "selected"; ?>>1 - Poor</option>
                                <option value="2" <?php if ($feedback['rating'] == 2) echo "selected"; ?>>2 - Fair</option>
                                <option value="3" <?php if ($feedback['rating'] == 3) echo "selected"; ?>>3 - Good</option>
                                <option value="4" <?php if ($feedback['rating'] == 4) echo "selected"; ?>>4 - Very Good</option>
                                <option value="5" <?php if ($feedback['rating'] == 5) echo "selected"; ?>>5 - Excellent</option>
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
                                    placeholder="Please share your overall experience with us..." 
                                    required><?php echo htmlspecialchars($feedback['overall_experience']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Room Cleanliness:</label>
                            <textarea name="room_cleanliness" class="form-control" rows="3"
                                    placeholder="How was the cleanliness of your room?" 
                                    required><?php echo htmlspecialchars($feedback['room_cleanliness']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Staff Service:</label>
                            <textarea name="staff_service" class="form-control" rows="3"
                                    placeholder="How was your experience with our staff?" 
                                    required><?php echo htmlspecialchars($feedback['staff_service']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Facilities:</label>
                            <textarea name="facilities" class="form-control" rows="3"
                                    placeholder="What did you think about our facilities?" 
                                    required><?php echo htmlspecialchars($feedback['facilities']); ?></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-pencil-alt"></i> Additional Comments
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Additional Feedback:</label>
                            <textarea name="comment" class="form-control" rows="4"
                                    placeholder="Please share any additional comments or suggestions..." 
                                    required><?php echo htmlspecialchars($feedback['comment']); ?></textarea>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-update w-100">
                            <i class="fas fa-save"></i> Update Feedback
                        </button>
                        <a href="index.php" class="btn btn-cancel w-100">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('../view/footer.php'); ?>
</body>
</html>