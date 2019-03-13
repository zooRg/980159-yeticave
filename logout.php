<?php
require_once 'init.php';

if (isset($_SESSION['user'], $is_auth)) {
    unset($_SESSION['user']);
}

header('Location: /index.php');

