<?php

require_once('functions.php');
$db = require_once('init.php');

unset($_SESSION['user']);

header("Location: /index.php");

?>