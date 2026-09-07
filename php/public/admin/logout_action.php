<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

removeAuthCookie();
session_start();
session_destroy();

redirect(BASE_URL . '/admin/login.php');
