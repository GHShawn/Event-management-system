<?php
    function __autoload($class_name){
      require_once "./assets/classes/$class_name.class.php";
  }
  echo MyUtils::html_header("Attendee Page","assets/css/style.css");
  echo Validator::logginValidate();
  echo MyUtils::navBar(Validator::roleName($_SESSION['role']),Validator::role($_SESSION['role']));


?>

<div class="main">
	
	<?php echo MyUtils::allEvent();?>
	
</div>

<?php
  echo MyUtils::html_footer();
?>