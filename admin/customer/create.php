<?php 
if(!empty($_GET['create'])) {
    include('../../resources/database/config.php');
    include('../includes/system_update.php');
    include('../includes/template.php');
}

?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #f5f7fa;
            --text-color: #2c3e50;
            --border-radius: 12px;
        }

        .content {
            background-color: var(--secondary-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 15px;
            line-height: 1.6;
        }

        .account-creation-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            padding: 40px;
            transition: all 0.3s ease;
        }

        .form-progress {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .progress-step {
            flex: 1;
            text-align: center;
            padding: 10px;
            position: relative;
            color: #b0b7c3;
            font-weight: 500;
        }

        .progress-step::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e0e7f3;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .progress-step.active::before {
            background-color: var(--primary-color);
            color: white;
        }

        .progress-step.active {
            color: var(--text-color);
            font-weight: 600;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .form-label {
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
        }

        .form-control, .form-select {
            padding: 12px 15px;
            border: 1px solid #e0e7f3;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-navigation {
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-next {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-prev {
            background-color: #f0f3f7;
            color: var(--text-color);
            border: 1px solid #e0e7f3;
        }

        .btn-submit {
            background-color: #2ecc71;
            color: white;
            border: none;
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .form-section.active {
                grid-template-columns: 1fr;
            }

            .account-creation-container {
                padding: 20px;
            }
        }

        /* Validation Styles */
        .form-control.is-invalid {
            border-color: #e74c3c;
        }

        .invalid-feedback {
            color: #e74c3c;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<div class="content">
    <div class="account-creation-container">
        <div class="form-progress">
            <div class="progress-step active">Personal Info</div>
            <div class="progress-step">Account Details</div>
            <div class="progress-step">Confirmation</div>
        </div>

        <form id="account-creation-form" action="store.php" method="post" enctype="multipart/form-data">
            <!-- Personal Info Section -->
            <div class="form-section active" data-step="1">
                <div class="form-group">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" id="fname" name="fname" class="form-control" required>
                    <div class="invalid-feedback">Please enter your first name</div>
                </div>

                <div class="form-group">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" id="lname" name="lname" class="form-control" required>
                    <div class="invalid-feedback">Please enter your last name</div>
                </div>

                <div class="form-group">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" id="age" name="age" class="form-control" min="18" max="100" required>
                    <div class="invalid-feedback">Please enter a valid age (18-100)</div>
                </div>

                <div class="form-group">
                    <label for="gender" class="form-label">Gender</label>
                    <select id="gender" name="gender" class="form-select" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="contact" class="form-label">Contact Number</label>
                    <input type="tel" id="contact" name="contact" class="form-control" pattern="[0-9]{10}" required>
                    <div class="invalid-feedback">Please enter a valid 10-digit phone number</div>
                </div>
            </div>

            <!-- Account Details Section -->
            <div class="form-section" data-step="2">
                <div class="form-group">
                    <label for="username" class="form-label">Email Address</label>
                    <input type="email" id="username" name="username" class="form-control" placeholder="user@example.com" required>
                    <div class="invalid-feedback">Please enter a valid email address</div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" minlength="8" required>
                    <div class="invalid-feedback">Password must be at least 8 characters long</div>
                </div>

                <div class="form-group">
                    <label for="confirm-password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm-password" class="form-control" required>
                    <div class="invalid-feedback">Passwords do not match</div>
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Account Type</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="">Select Account Type</option>
                        <option value="user">Regular User</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="file" class="form-label">Profile Picture (Optional)</label>
                    <input type="file" id="file" name="file" class="form-control" accept="image/*">
                </div>
            </div>

            <!-- Confirmation Section -->
            <div class="form-section" data-step="3">
                <div class="col-12">
                    <h3>Review Your Information</h3>
                    <p>Please review the details before submitting your account creation request.</p>
                    <ul id="review-list" class="list-group"></ul>
                </div>
            </div>

            <div class="form-navigation">
                <button type="button" class="btn btn-navigation btn-prev" style="display:none;">Previous</button>
                <button type="button" class="btn btn-navigation btn-next">Next</button>
            </div>

            <button type="submit" class="btn btn-submit" style="display:none;" name="create">Create Account</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('account-creation-form');
            const sections = form.querySelectorAll('.form-section');
            const prevBtn = form.querySelector('.btn-prev');
            const nextBtn = form.querySelector('.btn-next');
            const submitBtn = form.querySelector('.btn-submit');
            const progressSteps = document.querySelectorAll('.progress-step');
            const reviewList = document.getElementById('review-list');

            let currentStep = 0;

            function validateStep(step) {
                const currentSection = sections[step];
                const inputs = currentSection.querySelectorAll('input, select');
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                // Additional custom validations
                if (step === 1) {
                    const password = form.querySelector('#password');
                    const confirmPassword = form.querySelector('#confirm-password');
                    if (password.value !== confirmPassword.value) {
                        confirmPassword.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        confirmPassword.classList.remove('is-invalid');
                    }
                }

                return isValid;
            }

            function updateReviewSection() {
                reviewList.innerHTML = '';
                const personalInfo = sections[0];
                const accountDetails = sections[1];

                const infoToReview = [
                    { section: personalInfo, labels: ['fname', 'lname', 'age', 'gender', 'contact'] },
                    { section: accountDetails, labels: ['username', 'role'] }
                ];

                infoToReview.forEach(group => {
                    group.labels.forEach(label => {
                        const input = group.section.querySelector(`#${label}`);
                        if (input) {
                            const listItem = document.createElement('li');
                            listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between');
                            listItem.innerHTML = `
                                <strong>${input.closest('.form-group').querySelector('.form-label').textContent}:</strong>
                                <span>${input.value}</span>
                            `;
                            reviewList.appendChild(listItem);
                        }
                    });
                });
            }

            nextBtn.addEventListener('click', function() {
                if (validateStep(currentStep)) {
                    sections[currentStep].classList.remove('active');
                    progressSteps[currentStep].classList.remove('active');
                    currentStep++;

                    if (currentStep === sections.length - 1) {
                        updateReviewSection();
                        nextBtn.style.display = 'none';
                        submitBtn.style.display = 'block';
                    }

                    sections[currentStep].classList.add('active');
                    progressSteps[currentStep].classList.add('active');
                    prevBtn.style.display = 'block';
                }
            });

            prevBtn.addEventListener('click', function() {
                sections[currentStep].classList.remove('active');
                progressSteps[currentStep].classList.remove('active');
                currentStep--;

                sections[currentStep].classList.add('active');
                progressSteps[currentStep].classList.add('active');

                if (currentStep === 0) {
                    prevBtn.style.display = 'none';
                }

                nextBtn.style.display = 'block';
                submitBtn.style.display = 'none';
            });
        });
    </script>
</div>