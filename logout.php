<?php
// Initialize the session
session_name('attendee');
session_start();
 
// Unset all of the session variables
session_unset(); 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: login.php");
exit;
?>