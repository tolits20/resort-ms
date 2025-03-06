<?php 

include('resources/database/config.php');
include('admin/includes/system_update.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .container-box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="content">
        <div class="container-box">
            <h3 class="text-center">Password Recovery</h3>
            <form id="recovery-form" action="recover.php" method="post">
                <div id="step-1" class="step">
                    <label>Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                    <button type="submit" name="remail" value="next" class="btn btn-primary w-100 mt-3">Next</button>
                </div>
                </form> 
                <form id="recovery-form" action="recover.php" method="post">
                <div id="step-2" class="step d-none">
                    <label>Enter OTP</label>
                    <input type="text" id="otp" name="otp" class="form-control" required>
                    <button type="submit" name="otp_confirm" class="btn btn-primary w-100 mt-3" onclick="nextStep(3)">Verify</button>
                </div>
                </form>
                <form id="recovery-form" action="recover.php" method="post">
                <div id="step-3" class="step d-none">
                    <label>New Password</label>
                    <input type="password" id="password" name="npassword" class="form-control" required>
                    <br>
                    <label>Confirm Password</label>
                    <input type="password" id="password" name="cpassword" class="form-control" required>
                    <button type="submit" name="password_set" class="btn btn-success w-100 mt-3">Reset Password</button>
                </div>
            </form>
               
        </div>
    </div>

    <script>
        function getQueryParam(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }
        
        function nextStep(step) {
            let url = `?step=${step}`;
            
            if (step === 2 && email) url += `&email=true`;
            if (step === 3 && otp) url += `&otp=true`;
            
            window.location.href = url;
        }
        
        function showStep() {
            const step = getQueryParam('step') || 1;
            document.querySelectorAll('.step').forEach(el => el.classList.add('d-none'));
            document.getElementById(`step-${step}`).classList.remove('d-none');
            
            if (step === '2') document.getElementById('email').value = getQueryParam('email') || '';
            if (step === '3') document.getElementById('otp').value = getQueryParam('otp') || '';
        }
        
        document.addEventListener('DOMContentLoaded', showStep);
    </script>
</body>
</html>
