#!/bin/bash

# Get the absolute path of the current directory
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CRON_PHP_PATH="$SCRIPT_DIR/cron.php"

# Create the cron job entry
CRON_JOB="0 * * * * /usr/bin/php $CRON_PHP_PATH"

# Check if the cron job already exists
if crontab -l 2>/dev/null | grep -q "$CRON_PHP_PATH"; then
    echo "Cron job already exists for $CRON_PHP_PATH"
    echo "Current crontab:"
    crontab -l 2>/dev/null | grep "$CRON_PHP_PATH"
else
    # Add the cron job to the current user's crontab
    (crontab -l 2>/dev/null; echo "$CRON_JOB") | crontab -
    
    if [ $? -eq 0 ]; then
        echo "Cron job successfully added!"
        echo "Job: $CRON_JOB"
        echo ""
        echo "This will run cron.php every hour at minute 0."
        echo "To verify, run: crontab -l"
    else
        echo "Failed to add cron job. Please check permissions."
        exit 1
    fi
fi

# Make the script executable
chmod +x "$0"

echo ""
echo "Setup complete! The cron job will send task reminders every hour."
echo "Make sure your system's mail function is configured properly for email delivery."