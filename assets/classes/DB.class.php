<?php

class DB{
    private $dbh;
    function __construct(){
        try{

            $this->dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']};dbname={$_SERVER['DB']}",
                $_SERVER['DB_USER'], $_SERVER['DB_PASSWORD']);

        }catch(PDOException $pe){
            echo $pe->getMessage();
            die("Bad Database");
        }

    }
    public function getUser($username){
        try{
            $data = array();
            $sqlQuery = "SELECT * FROM attendee WHERE name = :username";

            $stmt  = $this->dbh->prepare($sqlQuery);
            $stmt->bindParam(":username",$username);
            $stmt->execute();

            while ($row=$stmt->fetch()){
                $data['idattendee'] = $row['idattendee'];
                $data['name'] = $row['name'];
                $data['password'] = $row['password'];
                $data['role'] = $row['role'];
            }
        }catch(PDOException $e){
                echo $e->getMessage();
                return array();
            }
         return $data;
    }
    public function createUser($username,$password,$role=3){
        try{
     
            $sqlQuery = "insert into attendee 
                            (name,password,role) 
                                values (:name,:password,:role)";

            $stmt = $this->dbh->prepare($sqlQuery);

            $stmt->execute(array("name"=>$username,
                                "password"=>$password,
                                "role"=>$role));

             return $this->dbh->lastInsertId();
            
        }catch(PDOException $e){
            echo $e->getMessage();
            return array();
        }
    }
    public function allEvent(){
        $sqlQuery = "SELECT e.idevent, e.name, e.datestart, e.dateend, e.numberallowed, v.name AS venue 
                        FROM event AS e
                        LEFT JOIN venue AS v
                            ON e.venue = v.idvenue";

        
        $stmt = $this->dbh->prepare($sqlQuery);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $eventData = array();	
          
		while( $event = $stmt->fetch(PDO::FETCH_ASSOC) ) {		
			$eventRows = array();			
			$eventRows[] = $event['idevent'];
			$eventRows[] = ucfirst($event['name']);
			$eventRows[] = $event['datestart'];		
			$eventRows[] = $event['dateend'];	
			$eventRows[] = $event['numberallowed'];
            $eventRows[] = $event['venue'];			
            $eventData[] = $eventRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$eventData
		);
		echo json_encode($output);

    }
    public function eventList($idatt,$role){	
        $sqlQuery;
        if($role == 1){
            $sqlQuery = "SELECT e.idevent, e.name, e.datestart, e.dateend, e.numberallowed, v.name as venue FROM event as e
                            LEFT JOIN venue as v
                                ON e.venue = v.idvenue";
        }else if($role ==2){
            $sqlQuery = "SELECT e.idevent, e.name, e.datestart, e.dateend, e.numberallowed, v.name as venue FROM event as e
                            INNER JOIN manager_event as me
                                ON me.event = e.idevent
                            LEFT JOIN venue as v
                                ON e.venue = v.idvenue
                            WHERE me.manager = ".$idatt;

        }
        $stmt = $this->dbh->prepare($sqlQuery);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $eventData = array();	

		while( $event = $stmt->fetch(PDO::FETCH_ASSOC) ) {		
			$eventRows = array();			
			$eventRows[] = $event['idevent'];
			$eventRows[] = ucfirst($event['name']);
			$eventRows[] = $event['datestart'];		
			$eventRows[] = $event['dateend'];	
			$eventRows[] = $event['numberallowed'];
            $eventRows[] = $event['venue'];	

            $eventRows[] = '<button type="button" name="update" id="'.$event["idevent"].'" class="btn btn-warning btn-xs update">Update</button>';
            $eventRows[] = '<button type="button" name="delete" id="'.$event["idevent"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
           		
            $eventData[] = $eventRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$eventData
		);
		echo json_encode($output);
    }
    public function getEvent(){
        
		if($_POST["eventId"]) {
			$sqlQuery = "
                    SELECT e.idevent, e.name, e.datestart, e.dateend, e.numberallowed, v.idvenue, v.name as venue FROM event as e 
                        LEFT JOIN venue as v
                            ON e.venue = v.idvenue
                        WHERE idevent = '".$_POST["eventId"]."'";
 
            $stmt = $this->dbh->prepare($sqlQuery);
            $stmt->execute();
            $data = array();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data ['idevent']  = $row['idevent'];
                $data ['name']  = $row['name'];
                $data ['datestart']  = $row['datestart'];
                $data ['dateend']  = $row['dateend'];
                $data ['numberallowed']  = $row['numberallowed'];
                $data ['idvenue']  = $row['idvenue'];
                $data ['venue']  = $row['venue'];
            }
            if(empty($data ['idvenue'])){
                $sqlQuery2 = "
                SELECT idvenue, name FROM venue;";
                
            }else{
			$sqlQuery2 = "
                    SELECT idvenue, name FROM venue
                    WHERE idvenue !=" . $data['idvenue'];
            }

            $stmt2 = $this->dbh->prepare($sqlQuery2);
            $stmt2->execute();

            while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){

                $dataRows [] = $row2['idvenue'];
                $dataRows [] = $row2['name'];

                $data['venueOptions'] = $dataRows;
            }
			echo json_encode($data);
		}
    }
    public function getVenueEvent(){
        $sqlQuery = "SELECT idvenue, name FROM venue";

         
        $stmt = $this->dbh->prepare($sqlQuery);
        $stmt->execute();

        $venueEventData = array();	
        
        while($venueEvent = $stmt->fetch(PDO::FETCH_ASSOC)){

            $venueEventRows = array();
            $venueEventRows ['idvenue'] = $venueEvent['idvenue'];
            $venueEventRows ['name'] = $venueEvent['name'];

            $venueEventData[] = $venueEventRows;

        }
        
        echo json_encode($venueEventData);
    
}

    public function addEvent($role, $idatt){
        try{
            $this->dbh->beginTransaction();
            
            
            $sqlQuery = "insert into event 
                            (name,datestart,dateend, numberallowed, venue) 
                                values (:name,:datestart,:dateend,:numberallowed,:venue)";

            $stmt = $this->dbh->prepare($sqlQuery);

            $stmt->execute(array("name"=>$_POST["eventName"],
                                "datestart"=>$_POST["datestart"],
                                "dateend"=>$_POST["dateend"],
                                "numberallowed"=>$_POST["numAllowed"],
                                "venue"=>$_POST["venueEvent"])
                            );
            
            if($role ==2){
            $sqlQuery2 = "insert into manager_event
                            (event,manager)
                                values(:event, :manager)";

            $stmt2 = $this->dbh->prepare($sqlQuery2);

            $stmt2->execute(array("event"=>$this->dbh->lastInsertId(),
                                 "manager"=>$idatt));
            }
            $this->dbh->commit();            
        }catch(PDOException $e){
            echo $e->getMessage();
            return array();
        }
    }
    public function updateEvent(){
        try{
            if($_POST["eventId"]) {
            $updateQuery = "UPDATE event
                            SET name = :name, 
                                datestart = :datestart, 
                                dateend = :dateend, 
                                numberallowed = :numberallowed, 
                                venue = :venue
                            WHERE event.idevent = " .$_POST["eventId"];
                            
            $stmt = $this->dbh->prepare($updateQuery);
        
            $stmt->execute(array("name"=>$_POST["eventName"],
                                "datestart"=>$_POST["datestart"],
                                "dateend"=>$_POST["dateend"],
                                "numberallowed"=>$_POST["numAllowed"],
                                "venue"=>$_POST["venueEvent"])
                            );
                        }
        }catch(PDOException $e){
            echo $e->getMessage();
            return array();
        }	
	}
	public function deleteEvent(){
		if($_POST["eventId"]) {
			$sqlDelete = "
                        DELETE e ,s ,me, att_s, att_e FROM event as e
                        LEFT JOIN session as s
                        ON e.idevent = s.event
                        LEFT JOIN manager_event as me
                        ON e.idevent = me.event
                        LEFT JOIN attendee_session as att_s
                        ON s.idsession = att_s.session
                        LEFT JOIN attendee_event as att_e
                        ON att_e.event = e.idevent
                        WHERE  e.idevent = :idevent";	
                
            $stmt = $this->dbh->prepare($sqlDelete);
            $stmt->execute(array("idevent"=>$_POST["eventId"]));

		}
	}
 

    public function sessionList($role,$idatt){
        $sqlQuery;
        if($role ==1){
            $sqlQuery = "SELECT s.idsession, s.name, s.numberallowed, s.startdate, s.enddate, e.name as event FROM session as s
            INNER JOIN event as e
                ON s.event = e.idevent";
        }else if($role ==2){
            $sqlQuery = "SELECT s.idsession, s.name, s.numberallowed, s.startdate, s.enddate, e.name as event FROM session as s
            INNER JOIN event as e
                ON s.event = e.idevent
            INNER JOIN manager_event as me
                ON e.idevent = me.event
                WHERE me.manager = " .$idatt;
        }


      
        $stmt = $this->dbh->prepare($sqlQuery);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $sessionData = array();	
        
		while( $session = $stmt->fetch(PDO::FETCH_ASSOC) ) {		
			$sessionRows = array();			
			$sessionRows[] = $session['idsession'];
			$sessionRows[] = ucfirst($session['name']);
			$sessionRows[] = $session['numberallowed'];		
			$sessionRows[] = $session['startdate'];	
			$sessionRows[] = $session['enddate'];
            $sessionRows[] = $session['event'];
            $sessionRows[] = '<button type="button" name="updateS" id="'.$session["idsession"].'" class="btn btn-warning btn-xs update">Update</button>';
            $sessionRows[] = '<button type="button" name="deleteS" id="'.$session["idsession"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
			
            $sessionData[] = $sessionRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$sessionData
		);
		echo json_encode($output);
    }
    public function addSession(){
        try{
            $sqlQuery = "insert into session 
                            (name,numberallowed,startdate, enddate, event) 
                                values (:name,:numberallowed,:startdate,:enddate,:event)";

            $stmt = $this->dbh->prepare($sqlQuery);

            $stmt->execute(array("name"=>$_POST["sessionName"],
                                "numberallowed"=>$_POST["s_numAllowed"],
                                "startdate"=>$_POST["s_datestart"],
                                "enddate"=>$_POST["s_dateend"],
                                "event"=>$_POST["session_event"])
                            );
          
        }catch(PDOException $e){
            echo $e->getMessage();
            return array();
        }
	}
    public function getSessionEvent($role,$idatt){
            $sqlQuery;
            if($role == 1){
                $sqlQuery = "SELECT idevent, name FROM event";
            }else if($role ==2){
                $sqlQuery = "SELECT idevent, name FROM event as e
                INNER JOIN manager_event as me
                    ON e.idevent = me.event
                WHERE me.manager = ".$idatt;
            }
			 
            $stmt = $this->dbh->prepare($sqlQuery);
            $stmt->execute();

            $sessionEventData = array();	
            
            while($seesionEvent = $stmt->fetch(PDO::FETCH_ASSOC)){

                $sessionEventRows = array();

                $sessionEventRows['idevent']  = $seesionEvent['idevent'];
                $sessionEventRows['name']  = $seesionEvent['name'];	

                $sessionEventData[] = $sessionEventRows;
            }
			echo json_encode($sessionEventData);
		
    }
    public function getSession(){
        
		if($_POST["sessionId"]) {
			$sqlQuery = "
                    SELECT s.idsession, s.name, s.startdate, s.enddate, s.numberallowed, e.idevent, e.name as eventName FROM session as s
                        INNER JOIN event as e
                            ON s.event = e.idevent
                        WHERE idsession = ".$_POST['sessionId'];
 
            $stmt = $this->dbh->prepare($sqlQuery);
            $stmt->execute();
            $data = array();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data ['idsession']  = $row['idsession'];
                $data ['name']  = $row['name'];
                $data ['startdate']  = $row['startdate'];
                $data ['enddate']  = $row['enddate'];
                $data ['numberallowed']  = $row['numberallowed'];
                $data ['idevent']  = $row['idevent'];
                $data ['eventName']  = $row['eventName'];
            }

            $sqlQuery2 = "
                    SELECT event.idevent, event.name as eventName FROM event
                    INNER JOIN manager_event as me
                        ON me.event = event.idevent
                    WHERE event.idevent !=".$data['idevent'];
                

            $stmt2 = $this->dbh->prepare($sqlQuery2);
            $stmt2->execute();

            while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){

                $dataRows [] = $row2['idevent'];
                $dataRows [] = $row2['eventName'];

                $data['eventOptions'] = $dataRows;
            }
			echo json_encode($data);
		}
    }
    public function updateSession(){
        if($_POST["sessionId"]) {
            $updateQuery = "UPDATE session
                            SET name = :name, 
                                numberallowed = :numberallowed, 
                                event = :event, 
                                startdate = :startdate, 
                                enddate = :enddate
                            WHERE idsession =".$_POST['sessionId'];
                        
            $stmt = $this->dbh->prepare($updateQuery);

            $stmt->execute(array("name"=>$_POST["sessionName"],
                            "numberallowed"=>$_POST["s_numAllowed"],
                            "event"=>$_POST["session_event"],
                            "startdate"=>$_POST["s_datestart"],
                            "enddate"=>$_POST["s_dateend"])
                        );
        }
    }
    public function deleteSession(){
		if($_POST["sessionId"]) {
			$sqlDelete = "
                DELETE s, att_s FROM session as s
                    LEFT JOIN attendee_session as att_s
                        ON s.idsession = att_s.session
                    WHERE s.idsession = :idsession";	
                
            $stmt = $this->dbh->prepare($sqlDelete);
            $stmt->execute(array("idsession"=>$_POST["sessionId"]));
		}
    }
    
    public function attendeeList($role,$idatt){	
        if($role ==1){
            $sqlQuery = "SELECT att.idattendee, att.name, e.name as event, s.name as session FROM attendee as att
            INNER JOIN attendee_session as att_s
                    ON att.idattendee = att_s.attendee
            INNER JOIN session as s
                    ON att_s.session = s.idsession
            INNER JOIN event as e
                    ON s.event = e.idevent";
        }else if($role ==2){
            $sqlQuery = "SELECT att.idattendee, att.name, e.name as event, s.name as session FROM attendee as att
            INNER JOIN attendee_session as att_s
                    ON att.idattendee = att_s.attendee
            INNER JOIN session as s
                    ON att_s.session = s.idsession
            INNER JOIN event as e
                    ON s.event = e.idevent
            INNER JOIN manager_event as me
                    ON me.event = e.idevent
            WHERE me.manager = ".$idatt;
        }


      
        $stmt = $this->dbh->prepare($sqlQuery);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $attendeeData = array();	
        
		while( $attendee = $stmt->fetch(PDO::FETCH_ASSOC) ) {		
            $attendeeRows = array();	
            $attendeeRows[] = $attendee['idattendee'];		
			$attendeeRows[] = ucfirst($attendee['name']);
			$attendeeRows[] = $attendee['event'];		
			$attendeeRows[] = $attendee['session'];	
            $attendeeRows[] = '<button type="button" name="updateA" id="'.$attendee["idattendee"].'" class="btn btn-warning btn-xs update">Update</button>';
            $attendeeRows[] = '<button type="button" name="deleteA" id="'.$attendee["idattendee"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
			
            $attendeeData[] = $attendeeRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$attendeeData
		);
		echo json_encode($output);
    }
    public function getAttendeeInfo($role,$idatt){
        $sqlQuery;
        $sqlQuery2;

        if($role == 1){
            $sqlQuery = "
                    SELECT s.idsession, s.name, e.idevent, e.name as eventName FROM session as s
                    INNER JOIN event as e
                        ON s.event = e.idevent";
            
            $sqlQuery2 = "
                    SELECT att.idattendee, att.name FROM attendee as att
                        INNER JOIN role as r
                            ON att.role = r.idrole
                        WHERE r.name = 'attendee'";
        }else if($role ==2){

            $sqlQuery = "SELECT s.idsession, s.name, e.idevent, e.name as eventName FROM session as s
            INNER JOIN event as e
                ON s.event = e.idevent
            INNER JOIN manager_event as me
                ON e.idevent = me.event
            WHERE me.manager = ".$idatt;

            $sqlQuery2 = "SELECT att.idattendee, att.name FROM attendee as att
                            INNER JOIN role as r
                                ON att.role = r.idrole
                            WHERE r.name = 'attendee'";
        }
         

        $stmt = $this->dbh->prepare($sqlQuery);
        $stmt->execute();

        $sessionData = array();	
        
        while($seesion = $stmt->fetch(PDO::FETCH_ASSOC)){

            $sessionRows = array();

            $sessionRows['idsession']  = $seesion['idsession'];
            $sessionRows['name']  = $seesion['name'];	
            $sessionRows['eventName']  = $seesion['eventName'];
            $sessionRows['idevent']  = $seesion['idevent'];
            $sessionData[] = $sessionRows;
        }

        $stmt2 = $this->dbh->prepare($sqlQuery2);
        $stmt2->execute();

        $attendeeData = array();	
        
        while($attendee = $stmt2->fetch(PDO::FETCH_ASSOC)){

            $attendeeRows = array();

            $attendeeRows['idattendee']  = $attendee['idattendee'];
            $attendeeRows['name']  = $attendee['name'];	

            $attendeeData[] = $attendeeRows;
        }
        echo json_encode(array('result1'=>$sessionData,'result2'=>$attendeeData));

    }
    public function addAttendee(){
        try{
            list($eventId, $sessionId) = explode("-", $_POST["attEvent"]);

            $sqlQuery = "insert into attendee_event 
                            (event,attendee) 
                                values (:event,:attendee)";

            $sqlQuery2 = "insert into attendee_session
                                (session,attendee) 
                                    values (:session,:attendee)";

            $stmt = $this->dbh->prepare($sqlQuery);
            $stmt->execute(array("event"=>$eventId,
                                "attendee"=>$_POST["attendeeName"])
                            );

            $stmt2 = $this->dbh->prepare($sqlQuery2);
            $stmt2->execute(array("session"=>$sessionId,
                                "attendee"=>$_POST["attendeeName"])
                            );

        }catch(PDOException $e){
            echo $e->getMessage();
            return array();
        }

    }
    public function getAttendee($role, $idatt){
        
		if($_POST["attendeeId"]) {
            $sqlQuery = "
                SELECT e.idevent, e.name as eventName, s.idsession, s.name as sessionName, att.idattendee, att.name from event as e
                    INNER JOIN session as s
                        ON e.idevent = s.event
                    INNER JOIN attendee_session as att_s
                        ON s.idsession = att_s.session
                    INNER JOIN attendee_event as att_e
                        ON e.idevent = att_e.event
                    INNER JOIN attendee as att
                        ON att.idattendee = att_e.attendee
                    WHERE att.idattendee =".$_POST["attendeeId"];
 
            $stmt = $this->dbh->prepare($sqlQuery);
            $stmt->execute();
            $data = array();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data ['idattendee']  = $row['idattendee'];
                $data ['attendeeName']  = $row['name'];
                $data ['idevent']  = $row['idevent'];
                $data ['eventName']  = $row['eventName'];
                $data ['idsession']  = $row['idsession'];
                $data ['sessionName']  = $row['sessionName'];
            }


            $sqlQuery2 = "
                    SELECT att.idattendee, att.name FROM attendee as att
                    INNER JOIN role as r
                        ON att.role = r.idrole
                    WHERE r.name = 'attendee' and att.idattendee !=".$_POST["attendeeId"];

            $stmt2 = $this->dbh->prepare($sqlQuery2);
            $stmt2->execute();

            while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){

                $attendeeRows[] = $row2['idattendee'];
                $attendeeRows[] = $row2['name'];

                $data['attendeeOptions'] = $attendeeRows;

            }
            if($role ==1){
                $sqlQuery3 = "
                SELECT s.idsession, s.name as sessionName, e.idevent, e.name as eventName FROM session as s
                INNER JOIN event as e
                    ON s.event = e.idevent
                WHERE s.idsession !=".$data['idsession'];
            }else if($role ==2){
                $sqlQuery3 = "
                SELECT s.idsession, s.name as sessionName, e.idevent, e.name as eventName FROM session as s
                INNER JOIN event as e
                    ON s.event = e.idevent
                INNER JOIN manager_event as me
                    ON e.idevent = me.event
                WHERE me.manager = ".$idatt. " and s.idsession !=".$data['idsession'];
            }


            $stmt3 = $this->dbh->prepare($sqlQuery3);
            $stmt3->execute();

            while($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)){
                $attendeeEventRows[] = $row3['eventName'];
                $attendeeEventRows[] = $row3['idevent'];
                $attendeeEventRows[] = $row3['idsession'];
                $attendeeEventRows[] = $row3['sessionName'];

                $data['dataEventOptions'] = $attendeeEventRows;

            }        
			echo json_encode($data);
		}
    }
    public function updateAttendee(){
        try{
            list($eventId, $sessionId) = explode("-", $_POST["attEvent"]);

            $sqlQuery = "UPDATE attendee_event 
                            SET event = :event,
                                attendee = :attendee
                            WHERE attendee =".$_POST["attendeeId"];

            $sqlQuery2 = "UPDATE attendee_session
                            SET session = :session,
                                attendee = :attendee
                            WHERE attendee =".$_POST["attendeeId"];

            $stmt = $this->dbh->prepare($sqlQuery);
            $stmt->execute(array("event"=>$eventId,
                                "attendee"=>$_POST["attendeeName"])
                            );

            $stmt2 = $this->dbh->prepare($sqlQuery2);
            $stmt2->execute(array("session"=>$sessionId,
                                "attendee"=>$_POST["attendeeName"])
                            );
        }catch(PDOException $e){
            echo $e->getMessage();
            return array();
        }
    }

    public function deleteAttendee(){
		if($_POST["attendeeId"]) {
			$sqlDelete = "
				DELETE FROM attendee_session
                WHERE attendee = :attendee";	

            $sqlDelete2 = "
				DELETE FROM attendee_event
                WHERE attendee = :attendee";	

            $stmt = $this->dbh->prepare($sqlDelete);
            $stmt->execute(array("attendee"=>$_POST["attendeeId"]));

            $stmt2 = $this->dbh->prepare($sqlDelete2);
            $stmt2->execute(array("attendee"=>$_POST["attendeeId"]));
		}
    }

    public function venueList(){	

        $sqlQuery = "SELECT * from venue";

        $stmt = $this->dbh->prepare($sqlQuery);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $venueData = array();	

		while( $venue = $stmt->fetch(PDO::FETCH_ASSOC) ) {		
			$venueRows = array();			
			$venueRows[] = $venue['idvenue'];
			$venueRows[] = ucfirst($venue['name']);
			$venueRows[] = $venue['capacity'];		
            $venueRows[] = '<button type="button" name="update" id="'.$venue["idvenue"].'" class="btn btn-warning btn-xs update">Update</button>';
            $venueRows[] = '<button type="button" name="delete" id="'.$venue["idvenue"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
           		
            $venueData[] = $venueRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$venueData
		);
		echo json_encode($output);
    }
    public function addVenue(){
        try{
            $sqlQuery = "insert into venue 
                            (name,capacity) 
                                values (:name,:capacity)";

            $stmt = $this->dbh->prepare($sqlQuery);

            $stmt->execute(array("name"=>$_POST["venueName"],
                                "capacity"=>$_POST["capacity"])
                            );
          
        }catch(PDOException $e){
            echo $e->getMessage();
            return array();
        }

    }
    public function getVenue(){
        if($_POST["venueId"]) {
			$sqlQuery = "
                    SELECT * FROM venue
                    WHERE idvenue = ".$_POST['venueId'];
 
            $stmt = $this->dbh->prepare($sqlQuery);
            $stmt->execute();
            $data = array();

            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data ['idvenue']  = $row['idvenue'];
                $data ['name']  = $row['name'];
                $data ['capacity']  = $row['capacity'];
            }

     
			echo json_encode($data);
		}

    }

    public function updateVenue(){
        if($_POST["venueId"]) {
            $updateQuery = "UPDATE venue
                            SET name = :name, 
                                capacity = :capacity
                            WHERE idvenue =".$_POST['venueId'];
                        
            $stmt = $this->dbh->prepare($updateQuery);

            $stmt->execute(array("name"=>$_POST["venueName"],
                            "capacity"=>$_POST["capacity"])
                        );
        }

    }
    public function deleteVenue(){
		if($_POST["venueId"]) {
			$sqlDelete = "
				DELETE FROM venue
                WHERE idvenue = :idvenue";	


            $stmt = $this->dbh->prepare($sqlDelete);
            $stmt->execute(array("idvenue"=>$_POST["venueId"]));

		}
    }
    public function userList(){	

        $sqlQuery = "SELECT att.idattendee, att.name, att.password, r.name as role FROM attendee AS att
                        INNER JOIN role AS r
                            ON att.role = r.idrole
                        WHERE r.name != 'admin'";

        $stmt = $this->dbh->prepare($sqlQuery);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $userData = array();	

		while( $user = $stmt->fetch(PDO::FETCH_ASSOC) ) {		
			$userRows = array();			
			$userRows[] = $user['idattendee'];
			$userRows[] = ucfirst($user['name']);
			$userRows[] = $user['password'];		
            $userRows[] = $user['role'];	
            $userRows[] = '<button type="button" name="update" id="'.$user["idattendee"].'" class="btn btn-warning btn-xs update">Update</button>';
            $userRows[] = '<button type="button" name="delete" id="'.$user["idattendee"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
           		
            $userData[] = $userRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$userData
		);
		echo json_encode($output);
    }
    public function addUser(){
        try{
     
            $sqlQuery = "insert into attendee 
                            (name,password,role) 
                                values (:name,:password,:role)";

            $stmt = $this->dbh->prepare($sqlQuery);

            $stmt->execute(array("name"=>$_POST['userName'],
                                "password"=>password_hash($_POST['userPassword'],PASSWORD_DEFAULT),
                                "role"=>$_POST['userRole']));

             return $this->dbh->lastInsertId();
            
        }catch(PDOException $e){
            echo $e->getMessage();
            return array();
        }

    }
    public function getUserRole(){
        try{
            $sqlQuery = "
                        SELECT * FROM role 
                        WHERE name != 'admin'";

            $stmt = $this->dbh->prepare($sqlQuery);
            $stmt->execute();

            $roleData = array();	
            
            while($role = $stmt->fetch(PDO::FETCH_ASSOC)){
    
                $roleRows = array();
                $roleRows ['idrole'] = $role['idrole'];
                $roleRows ['name'] = $role['name'];
    
                $roleData[] = $roleRows;
    
            }
            
            echo json_encode($roleData);
        }catch(PDOException $e){
            echo $e->getMessage();
            return array();
        }
    }
    public function getUserInfo(){
        if($_POST["userId"]) {
			$sqlQuery = "
                    SELECT att.idattendee, att.name, att.password, att.role, r.name as roleName FROM attendee as att
                    INNER JOIN role as r
                      ON att.role = r.idrole
                    WHERE att.idattendee = ".$_POST['userId'];
 
            $stmt = $this->dbh->prepare($sqlQuery);
            $stmt->execute();
            $data = array();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $data ['idattendee']  = $row['idattendee'];
                $data ['name']  = $row['name'];
                $data ['password']  = $row['password'];
                $data ['role']  = $row['role'];
                $data ['roleName']  = $row['roleName'];
            }

            $sqlQuery2 = "
                        SELECT * FROM role
                        WHERE idrole != ".$data['role']." and name !='admin'";
            $stmt2 = $this->dbh->prepare($sqlQuery2);
            $stmt2->execute();

            while($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
  
                $allRole[] = $row['idrole'];
                $allRole[] = $row['name'];

                $data['selectRoleOptions'] = $allRole;
            }
			echo json_encode($data);
		}

    }

    public function updateUser(){
        if($_POST["userId"]) {
            $updateQuery = "UPDATE attendee
                            SET name = :name, 
                                password = :password,
                                role = :role
                            WHERE idattendee =".$_POST['userId'];
                        
            $stmt = $this->dbh->prepare($updateQuery);

            $stmt->execute(array("name"=>$_POST["userName"],
                            "password"=>$_POST['userPassword'],
                            "role"=>$_POST["userRole"])
                        );
        }

    }
    public function deleteUser(){
		if($_POST["userId"]) {
			$sqlDelete = "
				DELETE FROM attendee
                WHERE idattendee = :idattendee";	


            $stmt = $this->dbh->prepare($sqlDelete);
            $stmt->execute(array("idattendee"=>$_POST["userId"]));

		}
    }

    public function registerList($idatt){

        $sqlQuery = "SELECT DISTINCT e.name as eventName, s.idsession, s.name as sessionName, s.startdate, s.enddate, v.name as venue, att.idattendee from event as e
                    INNER JOIN session as s
                    ON e.idevent = s.event
                    INNER JOIN attendee_session as atts
                    ON atts.session = s.idsession
                    INNER JOIN attendee_event as atte
                    ON atte.event = e.idevent
                    LEFT JOIN venue as v
                    ON v.idvenue = e.venue
                    INNER JOIN attendee as att
                    ON att.idattendee = atts.attendee
                    WHERE att.idattendee =" .$idatt;
   
        $stmt = $this->dbh->prepare($sqlQuery);
        $stmt->execute();
        $numRows = $stmt->rowCount();
        $registerData = array();	
        
		while( $register = $stmt->fetch(PDO::FETCH_ASSOC) ) {		
            $registerRows = array();	
            $registerRows[] = $register['idattendee'];		
			$registerRows[] = $register['eventName'];
			$registerRows[] = ucfirst($register['sessionName']);
            $registerRows[] = $register['startdate'];	
            $registerRows[] = $register['enddate'];	
			$registerRows[] = $register['venue'];
            $registerRows[] = '<button type="button" name="deleteS" id="'.$register['idattendee'].'" class="btn btn-danger btn-xs delete" >Cancel</button>';
			
            $registerData[] = $registerRows;
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$registerData
		);
		echo json_encode($output);

    }
    public function getRegistEvent($idatt){

            $sqlQuery = "
                    SELECT s.idsession, s.name, e.idevent, e.name as eventName FROM session as s
                    INNER JOIN event as e
                        ON s.event = e.idevent";
            


        $stmt = $this->dbh->prepare($sqlQuery);
        $stmt->execute();

        $sessionData = array();	
        while($seesion = $stmt->fetch(PDO::FETCH_ASSOC)){

            $sessionRows = array();

            $sessionRows['idsession']  = $seesion['idsession'];
            $sessionRows['name']  = $seesion['name'];	
            $sessionRows['eventName']  = $seesion['eventName'];
            $sessionRows['idevent']  = $seesion['idevent'];
            $sessionRows['registerId']  = $idatt;
            $sessionData[] = $sessionRows;
        }

        

        echo json_encode(array('result1'=>$sessionData));

    }
 
    public function registEvent(){
        try{
            list($eventId, $sessionId) = explode("-", $_POST["registerSessionName"]);

            $sqlQuery = "insert into attendee_event 
                            (event,attendee) 
                                values (:event,:attendee)";

            $sqlQuery2 = "insert into attendee_session
                                (session,attendee) 
                                    values (:session,:attendee)";

            $stmt = $this->dbh->prepare($sqlQuery);
            $stmt->execute(array("event"=>$eventId,
                                "attendee"=>$_POST["registerId"])
                            );

            $stmt2 = $this->dbh->prepare($sqlQuery2);
            $stmt2->execute(array("session"=>$sessionId,
                                "attendee"=>$_POST["registerId"])
                            );

        }catch(PDOException $e){
            echo $e->getMessage();
            return array();
        }

    }
    public function cancelEvent(){
		if($_POST["registerId"]) {
			$sqlDelete = "
				DELETE FROM attendee_session
                WHERE attendee = :attendee";	

                $sqlDelete2 = "
				DELETE FROM attendee_event
                WHERE attendee = :attendee";	

            $stmt = $this->dbh->prepare($sqlDelete);
            $stmt->execute(array("attendee"=>$_POST["registerId"]));

            $stmt2 = $this->dbh->prepare($sqlDelete2);
            $stmt2->execute(array("attendee"=>$_POST["registerId"]));
		}
    }

} //DB

?>