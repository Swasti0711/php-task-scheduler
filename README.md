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
<img width="1920" height="1080" alt="Screenshot (216)" src="https://github.com/user-attachments/assets/68277e38-4d9e-4768-b8f4-a6c4ba97f969" />

Open http://localhost:8000/index.php in your browser.

<img width="1920" height="1080" alt="Screenshot (217)" src="https://github.com/user-attachments/assets/0203264c-253f-4f26-bb97-38c45556f566" />
<img width="1920" height="1080" alt="Screenshot (218)" src="https://github.com/user-attachments/assets/e1da3f61-6167-4026-a922-d0004abb5970" />

3. Email Testing with Mailpit (Optional)
Download and run Mailpit, then open:
<img width="1920" height="1080" alt="Screenshot (220)" src="https://github.com/user-attachments/assets/3e201f4b-10c9-4607-9987-97f6ee6c60fe" />

http://localhost:8025
Use this to check verification and reminder emails.
<img width="1920" height="1080" alt="Screenshot (219)" src="https://github.com/user-attachments/assets/6931970c-1944-4ffa-9a9e-78cd86bf5080" />
<img width="1920" height="1080" alt="Screenshot (223)" src="https://github.com/user-attachments/assets/e3bb04d2-1c25-40e2-aa7f-37427b0bc341" />
<img width="1920" height="1080" alt="Screenshot (224)" src="https://github.com/user-attachments/assets/88e13dd7-80fc-400a-bdbb-dfaea3aa587c" />

4. Simulate CRON Reminders (Windows)
<img width="1920" height="1080" alt="Screenshot (225)" src="https://github.com/user-attachments/assets/603f5574-f6c3-4f31-b041-296b697d6c8f" />
<img width="1920" height="1080" alt="Screenshot (226)" src="https://github.com/user-attachments/assets/63ca617d-62d2-4745-94b8-48618b238905" />
<img width="1920" height="1080" alt="Screenshot (227)" src="https://github.com/user-attachments/assets/d8ac05de-86fb-43a0-848d-c65f846dabaf" />

php src/cron.php
5. Configure CRON Job (Linux/Mac)

chmod +x src/setup_cron.sh
./src/setup_cron.sh
Testing Summary
Feature	Status
Add tasks                   	Implemented
Prevent duplicate tasks     	Implemented
Complete/Delete tasks	       Implemented
Email subscription form	     Implemented
Email verification          	Working
Reminder email format	       Verified
Unsubscribe functionality	   Functional
JSON data storage	           Verified

https://github.com/user-attachments/assets/df683047-9807-407a-bfc4-eb9ed7056b7f

