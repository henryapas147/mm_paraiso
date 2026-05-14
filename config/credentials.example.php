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
