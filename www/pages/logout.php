<?php

session_start();

unset($_SESSION['username']);
header("Location: /pages/login.php")

?>