<?php

require __DIR__ . '/includes/header.php';

if(!isset($_SESSION['auth'])) {
    header('Location: login.php');
    exit();
}

session_destroy();

header('Location: login.php');
exit();