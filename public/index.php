<?php
// public/index.php

// Paths and constants
require_once __DIR__ . '/paths.php';

// URLs
define('BASE_URL', '/SE/public'); // Adjust as needed
define('APP_URL', 'http://localhost/rail-connect/public');

// Start session
session_start();

// Load Routes
require_once PUBLIC_PATH . '/routes/web.php';
