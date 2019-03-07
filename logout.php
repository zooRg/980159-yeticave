<?php
require_once('functions.php');
require_once('init.php');

if ($_SESSION['user'] && $is_auth) {
    unset($_SESSION['user']);
    header("Location: /index.php");
} else {
    header("Location: /index.php");
}

?>