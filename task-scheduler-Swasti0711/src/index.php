
<?php
ini_set('SMTP', 'localhost');
ini_set('smtp_port', 1025);

require_once 'functions.php';

$message = '';

// Handle task addition
if ($_POST && isset($_POST['task-name']) && !empty(trim($_POST['task-name']))) {
    if (addTask($_POST['task-name'])) {
        $message = 'Task added successfully!';
    } else {
        $message = 'Failed to add task or task already exists.';
    }
}

// Handle email subscription
if ($_POST && isset($_POST['email']) && !empty(trim($_POST['email']))) {
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        if (subscribeEmail($_POST['email'])) {
            $message = 'Verification email sent! Please check your inbox.';
        } else {
            $message = 'Failed to subscribe or email already subscribed.';
        }
    } else {
        $message = 'Please enter a valid email address.';
    }
}

// Handle task status changes
if ($_POST && isset($_POST['task_id']) && isset($_POST['action'])) {
    if ($_POST['action'] === 'toggle') {
        $is_completed = isset($_POST['completed']) && $_POST['completed'] === '1';
        markTaskAsCompleted($_POST['task_id'], $is_completed);
    } elseif ($_POST['action'] === 'delete') {
        deleteTask($_POST['task_id']);
    }
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$tasks = getAllTasks();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Task Planner</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        h1, h2 {
            color: #333;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="email"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 200px;
            margin-right: 10px;
        }
        button {
            padding: 10px 20px;
            background: #007cba;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #005a87;
        }
        .delete-task {
            background: #dc3545;
            padding: 5px 10px;
            font-size: 12px;
        }
        .delete-task:hover {
            background: #c82333;
        }
        #tasks-list {
            list-style: none;
            padding: 0;
        }
        .task-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .task-item.completed {
            text-decoration: line-through;
            opacity: 0.7;
            background: #d4edda;
        }
        .task-content {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }
        .task-status {
            margin-right: 10px;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Task Planner</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <!-- Add Task Form -->
        <h2>Add New Task</h2>
        <form method="POST" action="">
            <input type="text" name="task-name" id="task-name" placeholder="Enter new task" required>
            <button type="submit" id="add-task">Add Task</button>
        </form>
    </div>

    <div class="container">
        <!-- Tasks List -->
        <h2>Tasks</h2>
        <ul id="tasks-list">
            <?php if (empty($tasks)): ?>
                <li>No tasks yet. Add your first task above!</li>
            <?php else: ?>
                <?php foreach ($tasks as $task): ?>
                    <li class="task-item <?php echo $task['completed'] ? 'completed' : ''; ?>">
                        <div class="task-content">
                            <form method="POST" action="" style="display: inline; margin: 0;">
                                <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="completed" value="<?php echo $task['completed'] ? '0' : '1'; ?>">
                                <input type="checkbox" class="task-status" 
                                       <?php echo $task['completed'] ? 'checked' : ''; ?>
                                       onchange="this.form.submit()">
                            </form>
                            <span><?php echo htmlspecialchars($task['name']); ?></span>
                        </div>
                        <form method="POST" action="" style="display: inline; margin: 0;">
                            <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task['id']); ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="delete-task" onclick="return confirm('Are you sure you want to delete this task?')">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>

    <div class="container">
        <!-- Subscription Form -->
        <h2>Subscribe to Task Reminders</h2>
        <p>Get hourly email reminders for your pending tasks.</p>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Enter your email" required>
            <button type="submit" id="submit-email">Subscribe</button>
        </form>
    </div>
</body>
</html>