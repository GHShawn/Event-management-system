<?php

class MyUtils{

	static function html_header($title="Untitled",$styles=""){
		$string = <<<END
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>$title</title>
	<link href="$styles" type="text/css" rel="stylesheet" />


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap.min.js"></script>		
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap.min.css" />
    <script src="assets/js/data.js"></script>
</head>
<body>\n
END;
	return $string;
}

   // This is a nav bar function for re-usability on every user page
	static function navBar($pageName,$pageLink){
		$newBar = '';

		if ($pageLink != 'attendee.php'){
			$newBar = ' <li class="nav-item">
						<a class="nav-link active" href="' .$pageLink .'">Admin</a>
					</li>';
		}
		$string = '<div class="sidenav">
        <div class="login-main-text">
		   <h2>'. $pageName . '</h2>
		   <ul class="nav flex-column">
				'.$newBar.'
                <li class="nav-item">
                    <a class="nav-link active" href="attendee.php">Event</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="registration.php">Registration</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
	</div>';
		return $string;
	}


	// all event datatable element function
	static function allEvent(){

		$string = '<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">   
					<h1>All Event</h1>		
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h3 class="panel-title"></h3>
							</div>
						</div>
					</div>
					<table id="allEvent" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Start_Date</th>					
								<th>End_Date</th>
								<th>Number_Allowed</th>
								<th>Venue</th>									
							</tr>
						</thead> 
					</table>
				</div>';

		return $string;
	}

	// register event table and modal creation
	static function registerEvent(){

		$string = '<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">   
					<h1>Current Registered Event</h1>		
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h3 class="panel-title"></h3>
							</div>
							<div class="col-md-2" align="right">
								<button type="button" name="add" id="registEvent" class="btn btn-success btn-xs">Register New Event</button>
							</div>
						</div>
					</div>
					<table id="registeredList" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Id</th>
								<th>Event</th>
								<th>Session</th>					
								<th>Start_Date</th>
								<th>End_Date</th>
								<th>Venue</th>	
								<th></th>									
							</tr>
						</thead> 
					</table>
				</div>';

		return $string;
	}
	static function registerModal(){
		$string = '<div id="registerModal" class="modal fade">
    	<div class="modal-dialog">
    		<form method="post" id="registerForm">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Regist Event</h4>
    				</div>
    				<div class="modal-body">
						<div class="form-group"
						<label for="registerSessionName" class="control-label">Event Name</label>							
							<select id="registerSessionName" name="registerSessionName" required></select>		
						</div>						
					</div>
					
    				<div class="modal-footer">
    					<input type="hidden" name="registerId" id="registerId" />
    					<input type="hidden" name="action" id="raction" value="" />
    					<input type="submit" name="save" id="rsave" class="btn btn-info" value="Save" />
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    				</div>
    			</div>
    		</form>
    	</div>
    </div>
 
     ';
		return $string;
	}

	// Manager event list data table and modal
	static function manageEvent(){

		$string = '<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">   
					<h1>Event List</h1>		
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h3 class="panel-title"></h3>
							</div>
							<div class="col-md-2" align="right">
								<button type="button" name="add" id="addEvent" class="btn btn-success btn-xs">Add Event</button>
							</div>
						</div>
					</div>
					<table id="eventList" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Start_Date</th>					
								<th>End_Date</th>
								<th>Number_Allowed</th>
								<th>Venue</th>					
								<th></th>
								<th></th>					
							</tr>
						</thead> 
					</table>
				</div>';

		return $string;
	}
	static function eventModal(){
		$string = '<div id="eventModal" class="modal fade">
    	<div class="modal-dialog">
    		<form method="post" id="eventForm">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Edit Event</h4>
    				</div>
    				<div class="modal-body">
						<div class="form-group"
							<label for="eventName" class="control-label">Event Name</label>
							<input type="text" class="form-control" id="eventName" name="eventName" required>			
						</div>
						<div class="form-group">
							<label for="datestart" class="control-label">Start Date</label>							
							<input type="datetime" class="form-control" id="datestart" name="datestart" placeholder="mm/dd/yy 00:00:00" required>							
						</div>	   	
						<div class="form-group">
							<label for="dateend" class="control-label">End Date</label>							
							<input type="datetime" class="form-control"  id="dateend" name="dateend" placeholder="mm/dd/yy 00:00:00" required>							
						</div>	 
						<div class="form-group">
							<label for="numAllowed" class="control-label">Number Allowed</label>							
							<input type="text" class="form-control" id="numAllowed" name="numAllowed" required>			
						</div>
						<div class="form-group">
							<label for="venueEvent" class="control-label">Venue</label>	
							<select id="venueEvent" name="venueEvent"></select>						
							</div>						
    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="eventId" id="eventId" />
    					<input type="hidden" name="action" id="action" value="" />
    					<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    				</div>
    			</div>
    		</form>
    	</div>
    </div>
 
     ';
		return $string;
	}

	// Session List table and modal
	static function sessionTable(){

		$string = '<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">   	
					<h1>Session List</h1>
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h3 class="panel-title"></h3>
							</div>
							<div class="col-md-2" align="right">
								<button type="button" name="addSession" id="addSession" class="btn btn-success btn-xs">Add Session</button>
							</div>
						</div>
					</div>
					<table id="sessionList" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Number_Allowed</th>
								<th>Start_Date</th>					
								<th>End_Date</th>
								<th>Event</th>				
								<th></th>
								<th></th>			
							</tr>
						</thead> 
					</table>
				</div>';

		return $string;
	}
	static function sessionModal(){
		$string = '<div id="sessionModal" class="modal fade">
				<div class="modal-dialog">
					<form method="post" id="sessionForm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title"><i class="fa fa-plus"></i> Edit Session</h4>
							</div>
							<div class="modal-body">
								<div class="form-group"
									<label for="sessionName" class="control-label">Session Name</label>
									<input type="text" class="form-control" id="sessionName" name="sessionName" required>			
								</div>
								<div class="form-group">
								<label for="numAllowed" class="control-label">Number_Allowed</label>							
								<input type="text" class="form-control" id="s_numAllowed" name="s_numAllowed" required>			
								</div>
								<div class="form-group">
									<label for="datestart" class="control-label">Start_Date</label>							
									<input type="datetime" class="form-control" id="s_datestart" name="s_datestart" placeholder="yyyy-mm-dd 00:00:00" required>							
								</div>	   	
								<div class="form-group">
									<label for="dateend" class="control-label">End_Date</label>							
									<input type="datetime" class="form-control"  id="s_dateend" name="s_dateend" placeholder="yyyy-mm-dd 00:00:00" required>							
								</div>	 
								<div class="form-group">
									<label for="session_event" class="control-label">Event</label>	
									<select id="session_event" name="session_event"></select>						
								</div>	 
							</div>
							<div class="modal-footer">
								<input type="hidden" name="sessionId" id="sessionId" />
								<input type="hidden" name="action" id="actionSession" value="" />
								<input type="submit" name="save" id="saveSession" class="btn btn-info" value="Save" />
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			
     		';
		return $string;
	}


	// attendee table and modal function 
	static function attendeeTable(){

		$string = '<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">   	
					<h1>Attendee List</h1>
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h3 class="panel-title"></h3>
							</div>
							<div class="col-md-2" align="right">
								<button type="button" name="addAttendee" id="addAttendee" class="btn btn-success btn-xs">Add Attendee</button>
							</div>
						</div>
					</div>
					<table id="attendeeList" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Event</th>		
								<th>Session</th>
								<th></th>
								<th></th>					
							</tr>
						</thead> 
					</table>
				</div>';

		return $string;
	}
	static function attendeeModal(){
		$string = '<div id="attendeeModal" class="modal fade">
				<div class="modal-dialog">
					<form method="post" id="attendeeForm">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title"><i class="fa fa-plus"></i> Edit Attendee</h4>
							</div>
							<div class="modal-body">
								<div class="form-group"
									<label for="attendeeName" class="control-label">Attendee Name</label>
									<select id="attendeeName" name="attendeeName" required></select>						
								</div>
								<div class="form-group">
									<label for="attEvent" class="control-label">Event</label>		
									<select id="attEvent" name="attEvent" required></select>											
								</div>	

							</div>
							<div class="modal-footer">
								<input type="hidden" name="attendeeId" id="attendeeId" />
								<input type="hidden" name="action" id="actionAttendee" value="" />
								<input type="submit" name="save" id="saveAttendee" class="btn btn-info" value="Save" />
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
						</div>
					</form>
				</div>
			</div>
			
     		';
		return $string;
	}

	// User table and modal
	static function allUser(){

		$string = '<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">   
					<h1>All User</h1>		
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h3 class="panel-title"></h3>
							</div>
							<div class="col-md-2" align="right">
								<button type="button" name="addUser" id="addUser" class="btn btn-success btn-xs">Add User</button>
							</div>
						</div>
					</div>
					<table id="userList" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Password</th>					
								<th>Role</th>			
								<th></th>
								<th></th>					
							</tr>
						</thead> 
					</table>
				</div>';

		return $string;
	}
	static function userModal(){
		$string = '<div id="userModal" class="modal fade">
    	<div class="modal-dialog">
    		<form method="post" id="userForm">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Edit User</h4>
    				</div>
    				<div class="modal-body">
						<div class="form-group"
							<label for="userName" class="control-label">User Name</label>
							<input type="text" class="form-control" id="userName" name="userName" required>			
						</div>
						<div class="form-group">
							<label for="userPassword" class="control-label">Password</label>							
							<input type="text" class="form-control" id="userPassword" name="userPassword" required>							
						</div>	   	
						<div class="form-group">
							<label for="userRole" class="control-label">Role</label>	
							<select id="userRole" name="userRole"></select>								
						</div>	 					
    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="userId" id="userId" />
    					<input type="hidden" name="action" id="uaction" value="" />
    					<input type="submit" name="save" id="usave" class="btn btn-info" value="Save" />
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    				</div>
    			</div>
    		</form>
    	</div>
    </div>
 
     ';
		return $string;
	}


	// venue table and modal
	static function allVenue(){

		$string = '<div class="col-lg-10 col-md-10 col-sm-9 col-xs-12">   
					<h1>All Venue</h1>		
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-10">
								<h3 class="panel-title"></h3>
							</div>
							<div class="col-md-2" align="right">
								<button type="button" name="addVenue" id="addVenue" class="btn btn-success btn-xs">Add Venue</button>
							</div>
						</div>
					</div>
					<table id="venueList" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Capacity</th>					
								<th></th>
								<th></th>					
							</tr>
						</thead> 
					</table>
				</div>';

		return $string;
	}
	static function venueModal(){
		$string = '<div id="venueModal" class="modal fade">
    	<div class="modal-dialog">
    		<form method="post" id="venueForm">
    			<div class="modal-content">
    				<div class="modal-header">
    					<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"><i class="fa fa-plus"></i> Edit Venue</h4>
    				</div>
    				<div class="modal-body">
						<div class="form-group"
							<label for="venueName" class="control-label">Venue Name</label>
							<input type="text" class="form-control" id="venueName" name="venueName" required>			
						</div>
						<div class="form-group">
							<label for="capacity" class="control-label">Capacity</label>							
							<input type="text" class="form-control" id="capacity" name="capacity" required>							
						</div>	   						
    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="venueId" id="venueId" />
    					<input type="hidden" name="action" id="actionVenue" value="" />
    					<input type="submit" name="save" id="saveVenue" class="btn btn-info" value="Save" />
    					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    				</div>
    			</div>
    		</form>
    	</div>
    </div>
 
     ';
		return $string;
	}

	// footer 
	static function html_footer($text=""){
		$string ="\n$text\n</body>\n</html>";
		return $string;
	}

} // end class


?>