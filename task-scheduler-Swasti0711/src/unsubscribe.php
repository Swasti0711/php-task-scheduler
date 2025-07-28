unsubscribe.php
<?php
require_once 'functions.php';

$message = '';
$status = 'info';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (unsubscribeEmail($email)) {
            $message = 'You have been successfully unsubscribed from task reminders.';
            $status = 'success';
        } else {
            $message = 'Unsubscription failed. You may not be subscribed or there was an error.';
            $status = 'error';
        }
    } else {
        $message = 'Invalid email address.';
        $status = 'error';
    }
} else {
    $message = 'Please provide an email address to unsubscribe.';
    $status = 'error';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Unsubscribe - Task Planner</title>
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
        .info {
            color: #0c5460;
            border: 2px solid #bee5eb;
            background-color: #d1ecf1;
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
        <h2 id="unsubscription-heading">Unsubscribe from Task Updates</h2>
        
        <div class="message <?php echo $status; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        
        <?php if ($status === 'success'): ?>
            <p>You will no longer receive task reminder emails.</p>
            <p>If you change your mind, you can always subscribe again from the Task Planner.</p>
        <?php elseif ($status === 'error' && !isset($_GET['email'])): ?>
            <p>If you received this link in an email, please make sure you clicked the complete link.</p>
        <?php endif; ?>
        
        <p><a href="index.php">← Back to Task Planner</a></p>
    </div>
</body>
</html>