<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

logoutUser();

// Clear remember cookie
setcookie('kw_remember', '', time() - 3600, '/', '', APP_ENV === 'production', true);

redirect('portal/login');