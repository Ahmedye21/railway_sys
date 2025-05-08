<?php
// public/index.php

// Paths and constants
require_once __DIR__ . '/paths.php';

// URLs
define('BASE_URL', '/railway_sys/public'); // Adjust as needed
define('APP_URL', 'http://localhost/railway_sys/public');

// Start session
session_start();

// Load Routes
require_once PUBLIC_PATH . '/routes/web.php';
