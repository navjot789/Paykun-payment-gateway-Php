<?php 
ob_start();
session_start();
unset($_SESSION["cart_item"]);
unset($_SESSION['total_elements']);
header('location:index.php');
exit();
?>