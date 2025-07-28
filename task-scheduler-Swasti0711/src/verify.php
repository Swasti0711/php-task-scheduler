<?php
require_once 'functions.php';

$message = '';
$status = 'error';

if (isset($_GET['email']) && isset($_GET['code'])) {
    $email = $_GET['email'];
    $code = $_GET['code'];
    
    if (verifySubscription($email, $code)) {
        $message = 'Your subscription has been verified successfully! You will now receive task reminders.';
        $status = 'success';
    } else {
        $message = 'Verification failed. The link may be invalid or expired.';
    }
} else {
    $message = 'Invalid verification link.';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Subscription Verification - Task Planner</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success {
            color: #28a745;
            border: 2px solid #28a745;
            background-color: #d4edda;
        }
        .error {
            color: #dc3545;
            border: 2px solid #dc3545;
            background-color: #f8d7da;
        }
        .message {
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        a {
            color: #007cba;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Do not modify the ID of the heading -->
        <h2 id="verification-heading">Subscription Verification</h2>
        
        <div class="message <?php echo $status; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        
        <?php if ($status === 'success'): ?>
            <p>You can now close this window and return to the Task Planner.</p>
        <?php else: ?>
            <p>Please try subscribing again or contact support if the problem persists.</p>
        <?php endif; ?>
        
        <p><a href="index.php">← Back to Task Planner</a></p>
    </div>
</body>
</html>