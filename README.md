# Task Scheduler  
**Author:** Swasti Ranjan  

A PHP-based Task Scheduler that allows users to manage tasks, subscribe via email with verification, and receive automated reminders using CRON jobs. Built with pure PHP and JSON file storage, without any external libraries.  

---

## Features  

### Task Management  
- Add new tasks via a simple form  
- Prevents duplicate task entries  
- Stores tasks in `tasks.txt` using valid JSON format  
- Mark tasks as complete/incomplete  
- Delete tasks with a single click  
- All task items rendered dynamically inside the task list section  

### Email Subscription System  
- Email subscription with verification  
- Stores verified emails in `subscribers.txt`  
- Stores pending verifications in `pending_subscriptions.txt`  
- Generates a 6-digit random verification code  
- Sends verification emails in HTML format using PHP `mail()`  
- Prevents re-subscription of already verified emails  

### Reminder System with CRON Integration  
- Sends reminder emails only to verified subscribers  
- Reminder email includes pending tasks in HTML format  
- Includes unsubscribe link handled via `unsubscribe.php`  
- Removes email from subscribers list upon unsubscribe  
- Manual CRON simulation supported (`php src/cron.php`)  
- Linux-compatible CRON job setup via `setup_cron.sh`  

---

## Folder Structure  
src/
├── index.php
├── functions.php
├── verify.php
├── unsubscribe.php
├── cron.php
├── setup_cron.sh
├── tasks.txt
├── subscribers.txt
└── pending_subscriptions.txt

 How to Run the Project  
1. Clone the Repository  
git clone https://github.com/Swasti0711/php-task-scheduler.git
cd php-task-scheduler

2. Start PHP Server
php -S localhost:8000 -t src/
Open http://localhost:8000/index.php in your browser.

3. Email Testing with Mailpit (Optional)
Download and run Mailpit, then open:

http://localhost:8025
Use this to check verification and reminder emails.

4. Simulate CRON Reminders (Windows)

php src/cron.php
5. Configure CRON Job (Linux/Mac)

chmod +x src/setup_cron.sh
./src/setup_cron.sh
Testing Summary
Feature	Status
Add tasks	Implemented
Prevent duplicate tasks	Implemented
Complete/Delete tasks	Implemented
Email subscription form	Implemented
Email verification	Working
Reminder email format	Verified
Unsubscribe functionality	Functional
JSON data storage	Verified
