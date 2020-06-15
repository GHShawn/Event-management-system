<?php
    function __autoload($class_name){
      require_once "./assets/classes/$class_name.class.php";
  }
  echo MyUtils::html_header("Admin Page","assets/css/style.css");
  echo Validator::logginValidate();
  echo MyUtils::navBar(Validator::roleName($_SESSION['role']),Validator::role($_SESSION['role']));
?>

<div class="main">
    
    <?php 
        echo MyUtils::allUser();
        echo MyUtils::userModal();
        echo MyUtils::allVenue(); 
        echo MyUtils::venueModal();
        echo MyUtils::manageEvent();
        echo MyUtils::eventModal(); 
        echo MyUtils::sessionTable();
        echo Myutils::sessionModal();
        echo MyUtils::attendeeTable();
        echo Myutils::attendeeModal();
    ?>
    
</div>

<?php echo MyUtils::html_footer();?>