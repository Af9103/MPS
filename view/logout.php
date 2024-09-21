<?php
session_start();

$_SESSION['id']='';
$_SESSION['npk']='';
$_SESSION['password']='';
$_SESSION['name']='';
$_SESSION['level']='';

unset($_SESSION['id']);
unset($_SESSION['npk']);
unset($_SESSION['password']);
unset($_SESSION['name']);
unset($_SESSION['level']);

session_unset();
session_destroy();
header('Location:login.php');

?>
