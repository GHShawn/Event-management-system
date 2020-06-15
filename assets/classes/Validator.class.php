<?php
    #validation function for check if the data exist
    class Validator{
        static function sanitize($data){
            if(!empty($data)){
                return true;
            }else{
                return false;
            }
        }
        // identify the user role
        static function role($role){
            $string;
            if($role ==1){
                $string = "admin.php";
            }else if($role ==2){
                $string = "manager.php";
            }else{
                $string = "attendee.php";
            }
            return $string;
        }
        // identify the user role page title
        static function roleName($role){
            $string;
            if($role ==1){
                $string = "Admin Page";
            }else if($role ==2){
                $string = "Manager Page";
            }else{
                $string = "Attendee Page";
            }
            return $string;
        }
        static function logginValidate(){
            // Initialize the session
            session_name('attendee');
            session_start();
            
            // Check if the user is logged in, if not then redirect to login page
            if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
                header("location: login.php");
                exit;
            }
         
        }

        // function to alert user error message
        static function alertBox($message){
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    }
