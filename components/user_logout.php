<?php
ob_start();
include 'connect.php';
session_start();
session_unset();
session_destroy();
header('location:../home.php');
?>
<?php ob_end_flush(); ?>