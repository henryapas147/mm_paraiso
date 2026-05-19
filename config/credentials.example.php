<?php
// Copy this file to credentials.php and fill in your actual values.
// credentials.php is gitignored — never commit real credentials.

// App settings (set APP_DEBUG=true for local development)
putenv('APP_DEBUG=false');

// Online (production) database credentials
putenv('ONLINE_DB_HOST=localhost');
putenv('ONLINE_DB_NAME=your_production_db_name');
putenv('ONLINE_DB_USER=your_production_db_user');
putenv('ONLINE_DB_PASS=your_production_db_password');

// Local (development) database credentials
putenv('LOCAL_DB_HOST=localhost');
putenv('LOCAL_DB_NAME=booking');
putenv('LOCAL_DB_USER=root');
putenv('LOCAL_DB_PASS=');

// Gmail SMTP Configuration (for sending inquiry replies)
// 1. Enable 2-Step Verification on your Google Account
// 2. Generate an App Password at https://myaccount.google.com/apppasswords
// 3. Use the 16-character app password below (NOT your regular Gmail password)
putenv('GMAIL_USER=paraisomm32@gmail.com');
putenv('GMAIL_APP_PASSWORD=hqpc vszg styk kvae');  // 16-character app password with spaces

// Gmail API Polling — for recording inbound customer replies automatically
// Run this ONE TIME to get your refresh token:
//   1. Visit: http://localhost/admin_booking/build/get_refresh_token.php
//   2. Authorize with the Gmail account used for sending (paraisomm32@gmail.com)
//   3. Copy the refresh token shown and paste it below
putenv('GMAIL_POLLING_REFRESH_TOKEN=your-refresh-token-here');
