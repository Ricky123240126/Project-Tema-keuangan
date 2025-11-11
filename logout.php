<?php
session_start();
session_destroy();
header("menu_login.php");
exit();
?>