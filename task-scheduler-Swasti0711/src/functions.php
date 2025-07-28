
<?php
ini_set('SMTP', 'localhost');
ini_set('smtp_port', '1025');
ini_set('sendmail_from', 'no-reply@example.com');

/**
 * Adds a new task to the task list
 * 
 * @param string $task_name The name of the task to add.
 * @return bool True on success, false on failure.
 */
function addTask( string $task_name ): bool {
	$file  = __DIR__ . '/tasks.txt';
	
	// Get existing tasks
	$tasks = getAllTasks();
	
	// Check for duplicates
	foreach ($tasks as $task) {
		if (strtolower(trim($task['name'])) === strtolower(trim($task_name))) {
			return false; // Duplicate task
		}
	}
	
	// Generate unique ID
	$task_id = uniqid('task_');
	
	// Add new task
	$new_task = [
		'id' => $task_id,
		'name' => trim($task_name),
		'completed' => false
	];
	
	$tasks[] = $new_task;
	
	// Save to file
	return file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT)) !== false;
}

/**
 * Retrieves all tasks from the tasks.txt file
 * 
 * @return array Array of tasks. -- Format [ id, name, completed ]
 */
function getAllTasks(): array {
	$file = __DIR__ . '/tasks.txt';
	
	if (!file_exists($file)) {
		file_put_contents($file, '[]');
		return [];
	}
	
	$content = file_get_contents($file);
	$tasks = json_decode($content, true);
	
	return is_array($tasks) ? $tasks : [];
}

/**
 * Marks a task as completed or uncompleted
 * 
 * @param string  $task_id The ID of the task to mark.
 * @param bool $is_completed True to mark as completed, false to mark as uncompleted.
 * @return bool True on success, false on failure
 */
function markTaskAsCompleted( string $task_id, bool $is_completed ): bool {
	$file  = __DIR__ . '/tasks.txt';
	
	$tasks = getAllTasks();
	$found = false;
	
	foreach ($tasks as &$task) {
		if ($task['id'] === $task_id) {
			$task['completed'] = $is_completed;
			$found = true;
			break;
		}
	}
	
	if (!$found) {
		return false;
	}
	
	return file_put_contents($file, json_encode($tasks, JSON_PRETTY_PRINT)) !== false;
}

/**
 * Deletes a task from the task list
 * 
 * @param string $task_id The ID of the task to delete.
 * @return bool True on success, false on failure.
 */
function deleteTask( string $task_id ): bool {
	$file  = __DIR__ . '/tasks.txt';
	
	$tasks = getAllTasks();
	$filtered_tasks = [];
	$found = false;
	
	foreach ($tasks as $task) {
		if ($task['id'] !== $task_id) {
			$filtered_tasks[] = $task;
		} else {
			$found = true;
		}
	}
	
	if (!$found) {
		return false;
	}
	
	return file_put_contents($file, json_encode($filtered_tasks, JSON_PRETTY_PRINT)) !== false;
}

/**
 * Generates a 6-digit verification code
 * 
 * @return string The generated verification code.
 */
function generateVerificationCode(): string {
	return str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
}

/**
 * Subscribe an email address to task notifications.
 *
 * Generates a verification code, stores the pending subscription,
 * and sends a verification email to the subscriber.
 *
 * @param string $email The email address to subscribe.
 * @return bool True if verification email sent successfully, false otherwise.
 */
function subscribeEmail( string $email ): bool {
	$file = __DIR__ . '/pending_subscriptions.txt';
	
	// Check if already subscribed
	$subscribers_file = __DIR__ . '/subscribers.txt';
	$subscribers = [];
	if (file_exists($subscribers_file)) {
		$content = file_get_contents($subscribers_file);
		$subscribers = json_decode($content, true) ?: [];
	}
	
	if (in_array($email, $subscribers)) {
		return false; // Already subscribed
	}
	
	// Get pending subscriptions
	$pending = [];
	if (file_exists($file)) {
		$content = file_get_contents($file);
		$pending = json_decode($content, true) ?: [];
	}
	
	// Generate verification code
	$code = generateVerificationCode();
	
	// Store pending subscription
	$pending[$email] = [
		'code' => $code,
		'timestamp' => time()
	];
	
	// Save pending subscriptions
	if (file_put_contents($file, json_encode($pending, JSON_PRETTY_PRINT)) === false) {
		return false;
	}
	
	// Send verification email
$base_url = "http://localhost:8000";
$verification_link = $base_url . "/verify.php?email=" . urlencode($email) . "&code=" . $code;

	
	$subject = "Verify subscription to Task Planner";
	$body = '<p>Click the link below to verify your subscription to Task Planner:</p>';
	$body .= '<p><a id="verification-link" href="' . $verification_link . '">Verify Subscription</a></p>';
	
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=UTF-8\r\n";
	$headers .= "From: no-reply@example.com\r\n";
	
	return mail($email, $subject, $body, $headers);
}

/**
 * Verifies an email subscription
 * 
 * @param string $email The email address to verify.
 * @param string $code The verification code.
 * @return bool True on success, false on failure.
 */
function verifySubscription( string $email, string $code ): bool {
	$pending_file     = __DIR__ . '/pending_subscriptions.txt';
	$subscribers_file = __DIR__ . '/subscribers.txt';
	
	// Get pending subscriptions
	if (!file_exists($pending_file)) {
		return false;
	}
	
	$content = file_get_contents($pending_file);
	$pending = json_decode($content, true) ?: [];
	
	// Check if email and code match
	if (!isset($pending[$email]) || $pending[$email]['code'] !== $code) {
		return false;
	}
	
	// Get current subscribers
	$subscribers = [];
	if (file_exists($subscribers_file)) {
		$content = file_get_contents($subscribers_file);
		$subscribers = json_decode($content, true) ?: [];
	}
	
	// Add to subscribers if not already there
	if (!in_array($email, $subscribers)) {
		$subscribers[] = $email;
	}
	
	// Remove from pending
	unset($pending[$email]);
	
	// Save both files
	$success1 = file_put_contents($subscribers_file, json_encode($subscribers, JSON_PRETTY_PRINT)) !== false;
	$success2 = file_put_contents($pending_file, json_encode($pending, JSON_PRETTY_PRINT)) !== false;
	
	return $success1 && $success2;
}

/**
 * Unsubscribes an email from the subscribers list
 * 
 * @param string $email The email address to unsubscribe.
 * @return bool True on success, false on failure.
 */
function unsubscribeEmail( string $email ): bool {
	$subscribers_file = __DIR__ . '/subscribers.txt';
	
	if (!file_exists($subscribers_file)) {
		return false;
	}
	
	$content = file_get_contents($subscribers_file);
	$subscribers = json_decode($content, true) ?: [];
	
	// Remove email from subscribers
	$filtered_subscribers = array_filter($subscribers, function($subscriber) use ($email) {
		return $subscriber !== $email;
	});
	
	// Re-index array
	$filtered_subscribers = array_values($filtered_subscribers);
	
	return file_put_contents($subscribers_file, json_encode($filtered_subscribers, JSON_PRETTY_PRINT)) !== false;
}

/**
 * Sends task reminders to all subscribers
 * Internally calls  sendTaskEmail() for each subscriber
 */
function sendTaskReminders(): void {
	$subscribers_file = __DIR__ . '/subscribers.txt';
	
	if (!file_exists($subscribers_file)) {
		return;
	}
	
	$content = file_get_contents($subscribers_file);
	$subscribers = json_decode($content, true) ?: [];
	
	// Get pending tasks
	$all_tasks = getAllTasks();
	$pending_tasks = array_filter($all_tasks, function($task) {
		return !$task['completed'];
	});
	
	// Send email to each subscriber if there are pending tasks
	if (!empty($pending_tasks)) {
		foreach ($subscribers as $email) {
			sendTaskEmail($email, $pending_tasks);
		}
	}
}

/**
 * Sends a task reminder email to a subscriber with pending tasks.
 *
 * @param string $email The email address of the subscriber.
 * @param array $pending_tasks Array of pending tasks to include in the email.
 * @return bool True if email was sent successfully, false otherwise.
 */
function sendTaskEmail( string $email, array $pending_tasks ): bool {
	$subject = 'Task Planner - Pending Tasks Reminder';
	
	$body = '<h2>Pending Tasks Reminder</h2>';
	$body .= '<p>Here are the current pending tasks:</p>';
	$body .= '<ul>';
	
	foreach ($pending_tasks as $task) {
		$body .= '<li>' . htmlspecialchars($task['name']) . '</li>';
	}
	
	$body .= '</ul>';
	
	$base_url = "http://localhost:8000";
$unsubscribe_link = $base_url . "/unsubscribe.php?email=" . urlencode($email);

	$body .= '<p><a id="unsubscribe-link" href="' . $unsubscribe_link . '">Unsubscribe from notifications</a></p>';
	
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=UTF-8\r\n";
	$headers .= "From: no-reply@example.com\r\n";
	
	return mail($email, $subject, $body, $headers);
}