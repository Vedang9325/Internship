<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
|
| This section defines global configuration settings for FinnServ,
| comparable to general application preferences in TallyPrime.
|
*/

// APP_NAME: The brand name of this accounting system.
define('APP_NAME', 'FinnServ');

// APP_VERSION: Follows semantic versioning. Useful for checking feature updates.
define('APP_VERSION', '1.0.0');

// BASE_URL: The base URL path where the application is hosted locally.
// All absolute redirects and assets load relative to this path.
define('BASE_URL', '/Internship/FinnServ/');

// APP_ENV: Environment indicator. 'development' displays detailed DB errors.
define('APP_ENV', 'development');


/*
|--------------------------------------------------------------------------
| Timezone Configuration
|--------------------------------------------------------------------------
|
| Ensures date functions use the correct regional timezone (Asia/Kolkata).
| Accurate timing is critical for financial transaction timestamps (vouchers).
|
*/

date_default_timezone_set('Asia/Kolkata');