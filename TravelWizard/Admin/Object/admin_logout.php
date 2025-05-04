<?php
//start session
session_start();
// Unset all session variables
session_unset();  
// Destroy the session
session_destroy(); 

header("Location: ../adminLogin.php");
exit();
