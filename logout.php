<?php
require_once 'config.php';
$_SESSION['token']='';
header("Location: ".$base);
/*
session_destroy();
header("Location: $base/login.php");
*/