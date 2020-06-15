
<?php
    function __autoload($class_name){
      require_once "./assets/classes/$class_name.class.php";
  }

  echo MyUtils::html_header("Login Page","assets/css/style.css");
  
?>

<!-- Login form -->
   <div class="sidenav">
		<div class="login-main-text">
		   <h2>Application<br> Login Page</h2>
		   <p>Login or register from here to access.</p>
		</div>
	 </div>
	 <div class="main">
		<div class="col-md-6 col-sm-12">
		   <div class="login-form">
			  <form action = "/~sxw9470/ISTE341/Project1/login.php" method="post">
				 <div class="form-group">
					<label>User Name</label>
					<input type="text" name="username" class="form-control" placeholder="User Name">
				 </div>
				 <div class="form-group">
					<label>Password</label>
					<input type="password" name="password" class="form-control" placeholder="Password">
				 </div>
				 <button type="submit" name="login" class="btn btn-black">Login</button>
				 <button type="submit" name="register" class="btn btn-secondary">Register</button>
			  </form>
		   </div>
		</div>
	 </div>

<?php

if(isset($_POST['login'])) 
{ 
   #validate and sanitize username and password if is exist
   if(Validator::sanitize($_POST['username']) && Validator::sanitize($_POST['password'])){
         $username = strip_tags($_POST['username']);
         $password = strip_tags($_POST['password']);

         #Validate credentials
         $db = new DB();
         $data = $db->getUser($username);

         if(!empty($data)){
            // if($data['password'] == $password){
            if(password_verify($password, $data['password'])){
               session_name('attendee');
               session_start();
               // Store data in session variables
               $_SESSION["loggedin"] = true;
               $_SESSION["idattendee"] = $data['idattendee'];
               $_SESSION["role"] = $data['role'];
               header("Location: ".Validator::role($_SESSION["role"]));
            }
         }else{
            Validator::alertBox('You had entered wrong user name or password');
         }
   }else{
         Validator::alertBox('Please enter your user name and password');
   }
}

if(isset($_POST['register'])){

   if(Validator::sanitize($_POST['username']) && Validator::sanitize($_POST['password'])){
      $username = strip_tags($_POST['username']);
      $password = strip_tags($_POST['password']);

      #Validate credentials
      $db = new DB();
      if(empty($db->getUser($username))){
         $db->createUser($username,password_hash($password,PASSWORD_DEFAULT));
         Validator::alertBox('Congraduation! You had created an account');

      }else{
         Validator::alertBox('Sorry, user name already exist');
      }
   }else{
      Validator::alertBox('Please enter user name and password to create an account');
   }

}
?>
      
<?php
   echo MyUtils::html_footer();
?>

