<?php
include('assets/classes/DB.class.php');

SESSION_NAME('attendee');
SESSION_START();



$db = new DB();

// action for all event
if(!empty($_POST['action']) && $_POST['action'] == 'allEvent') {
	$db->allEvent();
}

// All action for event list
if(!empty($_POST['action']) && $_POST['action'] == 'listEvent') {
	$db->eventList($_SESSION['idattendee'],$_SESSION['role']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'addEvent') {
	$db->addEvent($_SESSION['role'], $_SESSION['idattendee']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'getVenueEvent') {
	$db->getVenueEvent();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getEvent') {
	$db->getEvent();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateEvent') {
	$db->updateEvent();
}
if(!empty($_POST['action']) && $_POST['action'] == 'eventDelete') {
	$db->deleteEvent();
}

// All action for session list 
if(!empty($_POST['action']) && $_POST['action'] == 'listSession') {
	$db->sessionList($_SESSION['role'],$_SESSION['idattendee']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'addSession') {
	$db->addSession();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getSessionEvent') {
	$db->getSessionEvent($_SESSION['role'],$_SESSION['idattendee']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'getSession') {
	$db->getSession();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateSession') {
	$db->updateSession();
}
if(!empty($_POST['action']) && $_POST['action'] == 'sessionDelete') {
	$db->deleteSession();
}

// All action for attendee list 
if(!empty($_POST['action']) && $_POST['action'] == 'listAttendee') {
	$db->attendeeList($_SESSION['role'],$_SESSION['idattendee']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'addAttendee') {
	$db->addAttendee($_SESSION['idattendee']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'getAttendeeInfo') {
	$db->getAttendeeInfo($_SESSION['role'],$_SESSION['idattendee']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'getAttendee') {
	$db->getAttendee($_SESSION['role'],$_SESSION['idattendee']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateAttendee') {
	$db->updateAttendee();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteAttendee') {
	$db->deleteAttendee();
}


// All action for user list 
if(!empty($_POST['action']) && $_POST['action'] == 'allUser') {
	$db->userList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addUser') {
	$db->addUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getUserRole') {
	$db->getUserRole();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getUserInfo') {
	$db->getUserInfo();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateUser') {
	$db->updateUser();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteUser') {
	$db->deleteUser();
}


// All action for venue list 
if(!empty($_POST['action']) && $_POST['action'] == 'allVenue') {
	$db->venueList();
}
if(!empty($_POST['action']) && $_POST['action'] == 'addVenue') {
	$db->addVenue();
}
if(!empty($_POST['action']) && $_POST['action'] == 'getVenue') {
	$db->getVenue();
}
if(!empty($_POST['action']) && $_POST['action'] == 'updateVenue') {
	$db->updateVenue();
}
if(!empty($_POST['action']) && $_POST['action'] == 'deleteVenue') {
	$db->deleteVenue();
}

// All action for registration list 
if(!empty($_POST['action']) && $_POST['action'] == 'listRegister') {
	$db->registerList($_SESSION['idattendee']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'getRegistEvent') {
	$db->getRegistEvent($_SESSION['idattendee']);
}
if(!empty($_POST['action']) && $_POST['action'] == 'registEvent') {
	$db->registEvent();
}
if(!empty($_POST['action']) && $_POST['action'] == 'cancelEvent') {
	$db->cancelEvent();
}
?> 