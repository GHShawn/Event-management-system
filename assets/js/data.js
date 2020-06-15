
// Use Ajax to manage all the event function
$(document).ready(function(){	

	//Event data table
	var eventData = $('#eventList').DataTable({
		"lengthChange": false,
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"action.php",
			type:"POST",
			data:{action:'listEvent'},
			dataType:"json"
		},
		"columnDefs":[
			{
				"targets":[0, 1, 2, 3, 4, 5, 6, 7],
				"orderable":false,
			},
		],
		"pageLength": 10 
	});		

		//Event session table
		var sessionData = $('#sessionList').DataTable({
			"lengthChange": false,
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				url:"action.php",
				type:"POST",
				data:{action:'listSession'},
				dataType:"json"
			},
			"columnDefs":[
				{
					"targets":[0, 1, 2, 3, 4, 5, 6, 7],
					"orderable":false,
				},
			],
			"pageLength": 10 
		});		


		//Event attendee table
		var attendeeData = $('#attendeeList').DataTable({
			"lengthChange": false,
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				url:"action.php",
				type:"POST",
				data:{action:'listAttendee'},
				dataType:"json"
			},
			"columnDefs":[
				{
					"targets":[0, 1, 2, 3, 4, 5],
					"orderable":false,
				},
			],
			"pageLength": 10 
		});	

		// All event data table
		var allEventData = $('#allEvent').DataTable({
			"lengthChange": false,
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				url:"action.php",
				type:"POST",
				data:{action:'allEvent'},
				dataType:"json"
			},
			"columnDefs":[
				{
					"targets":[0,1,2,3,4,5],
					"orderable":false,
				},
			],
			"pageLength": 10 
		});	

		//Register Event session table
		var registerData = $('#registeredList').DataTable({
			"lengthChange": false,
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				url:"action.php",
				type:"POST",
				data:{action:'listRegister'},
				dataType:"json"
			},
			"columnDefs":[
				{
					"targets":[0, 1, 2, 3, 4, 5],
					"orderable":false,
				},
			],
			"pageLength": 10 
		});		

		//User table
		var allUserData = $('#userList').DataTable({
			"lengthChange": false,
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				url:"action.php",
				type:"POST",
				data:{action:'allUser'},
				dataType:"json"
			},
			"columnDefs":[
				{
					"targets":[0,1,2,3],
					"orderable":false,
				},
			],
			"pageLength": 10 
		});	

		// Venue data table
		var allVenueData = $('#venueList').DataTable({
			"lengthChange": false,
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				url:"action.php",
				type:"POST",
				data:{action:'allVenue'},
				dataType:"json"
			},
			"columnDefs":[
				{
					"targets":[0,1,2],
					"orderable":false,
				},
			],
			"pageLength": 10 
		});	


	
	// Add event function	
	$('#addEvent').click(function(){
		var action = 'getVenueEvent';
		var options = '<option value="">-- select an option --</option>';
		$.ajax({
			url:'action.php',
			method:"POST",
			data:{action:action},
			dataType:"json",
			success:function(data){
				$('#eventModal').modal('show');
				$('#eventForm')[0].reset();
				for(let i=0; i<data.length; i++){
					options += '<option value="' + data[i]['idvenue'] + '">' + data[i]['name'] + '</option>';
				}
				$("#venueEvent").html(options);
							
				$('.modal-title').html("<i class='fa fa-plus'></i> Add Session");
				$('#action').val('addEvent');
				$('#save').val('Add');
			}
		})
	});		

	// update event function
	$("#eventList").on('click', '.update', function(){
		var eventId = $(this).attr("id");
		var action = 'getEvent';
		$.ajax({
			url:'action.php',
			method:"POST",
			data:{eventId:eventId, action:action},
			dataType:"json",
			success:function(data){
				var options = '<option value="' + data.idvenue+ '">' + data.venue + '</option>';
				$('#eventModal').modal('show');
				$('#eventId').val(data.idevent);
				$('#eventName').val(data.name);
				$('#datestart').val(data.datestart);
				$('#dateend').val(data.dateend);				
				$('#numAllowed').val(data.numberallowed);

				for(let i=0; i<data.venueOptions.length; i+=2){
					options += '<option value="' + data.venueOptions[i]+ '">' + data.venueOptions[i+1] + '</option>';
				}
				$('#venueEvent').html(options);	
				$('.modal-title').html("<i class='fa fa-plus'></i> Edit Event");
				
				$('#action').val('updateEvent');
				$('#save').val('Save');
			}
		})
	});

	// submit event function
	$("#eventModal").on('submit','#eventForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#eventForm')[0].reset();
				$('#eventModal').modal('hide');				
				$('#save').attr('disabled', false);
				eventData.ajax.reload();
				allEventData.ajax.reload();
				sessionData.ajax.reload();
				attendeeData.ajax.reload();
			}
		})
	});		

	// delete event function
	$("#eventList").on('click', '.delete', function(){
		var eventId = $(this).attr("id");		
		var action = "eventDelete";
		if(confirm("Are you sure you want to delete this event?")) {
			$.ajax({
				url:"action.php",
				method:"POST",
				data:{eventId:eventId, action:action},
				success:function(data) {					
					eventData.ajax.reload();
					sessionData.ajax.reload();
					attendeeData.ajax.reload();
				}
			})
		} else {
			return false;
		}
	});	

	// add session function
	$('#addSession').click(function(){
		var action = 'getSessionEvent';
		var options = '<option value="">-- select an option --</option>';
		$.ajax({
			url:'action.php',
			method:"POST",
			data:{action:action},
			dataType:"json",
			success:function(data){
				$('#sessionModal').modal('show');

				for(let i=0; i<data.length; i++){
					options += '<option value="' + data[i]['idevent'] + '">' + data[i]['name'] + '</option>';
				}
				$("#session_event").html(options);
							
				$('.modal-title').html("<i class='fa fa-plus'></i> Add Session");
				$('#actionSession').val('addSession');
				$('#saveSession').val('Add');
			}
		})
	});		

	// update session function
	$("#sessionList").on('click', '.update', function(){
		var sessionId = $(this).attr("id");
		var action = 'getSession';
		$.ajax({
			url:'action.php',
			method:"POST",
			data:{sessionId:sessionId, action:action},
			dataType:"json",
			success:function(data){
				var options = '<option value="' + data.idevent+ '">' + data.eventName + '</option>';
				$('#sessionModal').modal('show');
				$('#sessionId').val(data.idsession);
				$('#sessionName').val(data.name);
				$('#s_numAllowed').val(data.numberallowed);
				$('#s_datestart').val(data.startdate);
				$('#s_dateend').val(data.enddate);	

				if(data.eventOptions != null){
					for(let i=0; i<data.eventOptions.length; i+=2){
						options += '<option value="' + data.eventOptions[i]+ '">' + data.eventOptions[i+1] + '</option>';
					}
				}
				$('#session_event').html(options);	
				$('.modal-title').html("<i class='fa fa-plus'></i> Edit Session");
				
				 $('#actionSession').val('updateSession');
				 $('#saveSession').val('Save');
			}
		})
	});

	// submit session function
	$("#sessionModal").on('submit','#sessionForm', function(event){
		event.preventDefault();
		$('#saveSession').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#sessionForm')[0].reset();
				$('#sessionModal').modal('hide');				
				$('#saveSession').attr('disabled', false);
				sessionData.ajax.reload();
				attendeeData.ajax.reload();
			}
		})
	});		

	//delete session function
		$("#sessionList").on('click', '.delete', function(){
			var sessionId = $(this).attr("id");		
			var action = "sessionDelete";
			if(confirm("Are you sure you want to delete this session?")) {
				$.ajax({
					url:"action.php",
					method:"POST",
					data:{sessionId:sessionId, action:action},
					success:function(data) {					
						sessionData.ajax.reload();
						attendeeData.ajax.reload();
					}
				})
			} else {
				return false;
			}
		});	


	
		$('#addAttendee').click(function(){
			var action = 'getAttendeeInfo';
			var attendeeOptions = '<option value="">-- select an option --</option>';
			var eventOptions = '<option value="">-- select an option --</option>';

			$.ajax({
				url:'action.php',
				method:"POST",
				data:{action:action},
				dataType:"json",
				success:function(data){
					$('#attendeeModal').modal('show');
					$('#attendeeForm')[0].reset();
				
					for(let i=0; i<data.result1.length; i++){
						eventOptions += '<optgroup label="' + data.result1[i]['eventName'] + '"><option value="' + data.result1[i]['idevent'] + '-' + data.result1[i]['idsession'] +'">' + data.result1[i]['name'] + '</option></optgroup>';
					}

					for(let i=0; i<data.result2.length; i++){
						attendeeOptions += '<option value="' + data.result2[i]['idattendee'] + '">' + data.result2[i]['name'] + '</option>';
					}
					$("#attEvent").html(eventOptions);
					$("#attendeeName").html(attendeeOptions);
					
					$('.modal-title').html("<i class='fa fa-plus'></i> Add Attendee");
					$('#actionAttendee').val('addAttendee');
					$('#saveAttendee').val('Add');
				}
			})
		});		

		$("#attendeeList").on('click', '.update', function(){
			var attendeeId = $(this).attr("id");
			var action = 'getAttendee';
			$.ajax({
				url:'action.php',
				method:"POST",
				data:{attendeeId:attendeeId, action:action},
				dataType:"json",
				success:function(data){
					$('#attendeeModal').modal('show');

					var options = '<option value="' + data.idattendee+ '">' + data.attendeeName + '</option>';
					var eventOptions = '<optgroup label="' + data.eventName + '"><option value="' + data.idevent + '-' + data.idsession +'">' + data.sessionName + '</option></optgroup>';
					if(data.attendeeOptions != null){
						for(let i=0; i<data.attendeeOptions.length; i+=2){
							options += '<option value="' + data.attendeeOptions[i]+ '">' + data.attendeeOptions[i+1] + '</option>';
						}
					}

					if(data.dataEventOptions != null){
						for(let i=0; i<data.dataEventOptions.length; i+=4){
							eventOptions += '<optgroup label="' + data.dataEventOptions[i] + '"><option value="' + data.dataEventOptions[i+1] + '-' + data.dataEventOptions[i+2] +'">' + data.dataEventOptions[i+3] + '</option></optgroup>';
						}
					}
					$('#attendeeName').html(options);

					$('#attEvent').html(eventOptions);
					$('#attendeeId').val(data.idattendee);

					$('.modal-title').html("<i class='fa fa-plus'></i> Edit Attendee");
					$('#actionAttendee').val('updateAttendee');
					$('#saveAttendee').val('Save');
				}
			})
		});
		$("#attendeeModal").on('submit','#attendeeForm', function(event){
			event.preventDefault();
			$('#save').attr('disabled','disabled');
			var formData = $(this).serialize();
			$.ajax({
				url:"action.php",
				method:"POST",
				data:formData,
				success:function(data){				
					$('#attendeeForm')[0].reset();
					$('#attendeeModal').modal('hide');				
					$('#save').attr('disabled', false);
					attendeeData.ajax.reload();
				}
			})
		});		
		$("#attendeeList").on('click', '.delete', function(){
			var attendeeId = $(this).attr("id");		
			var action = "deleteAttendee";
			if(confirm("Are you sure you want to delete this attendee?")) {
				$.ajax({
					url:"action.php",
					method:"POST",
					data:{attendeeId:attendeeId, action:action},
					success:function(data) {					
						attendeeData.ajax.reload();
					}
				})
			} else {
				return false;
			}
		});	


		$('#addVenue').click(function(){

			$('#venueModal').modal('show');
			$('#venueForm')[0].reset();
			$('.modal-title').html("<i class='fa fa-plus'></i> Add Venue");
		
			$('#actionVenue').val('addVenue');
			$('#saveVenue').val('Add Venue');
				

		});		
		$("#venueList").on('click', '.update', function(){
			var venueId = $(this).attr("id");
			var action = 'getVenue';
			$.ajax({
				url:'action.php',
				method:"POST",
				data:{venueId:venueId, action:action},
				dataType:"json",
				success:function(data){
					$('#venueModal').modal('show');
					$('#venueId').val(data.idvenue);
					$('#venueName').val(data.name);
					$('#capacity').val(data.capacity);

					$('.modal-title').html("<i class='fa fa-plus'></i> Edit Venue");
					$('#actionVenue').val('updateVenue');
					$('#saveVenue').val('Save');
				}
			})
		});

		$("#venueModal").on('submit','#venueForm', function(event){
			event.preventDefault();
			$('#saveVenue').attr('disabled','disabled');
			var formData = $(this).serialize();
			$.ajax({
				url:"action.php",
				method:"POST",
				data:formData,
				success:function(data){				
					$('#venueForm')[0].reset();
					$('#venueModal').modal('hide');				
					$('#saveVenue').attr('disabled', false);
					allVenueData.ajax.reload();
					eventData.ajax.reload();
					allEventData.ajax.reload();
				}
			})
		});	
		$("#venueList").on('click', '.delete', function(){
			var venueId = $(this).attr("id");		
			var action = "deleteVenue";
			if(confirm("Are you sure you want to delete this venue?")) {
				$.ajax({
					url:"action.php",
					method:"POST",
					data:{venueId:venueId, action:action},
					success:function(data) {					
						allVenueData.ajax.reload();
						eventData.ajax.reload();
						allEventData.ajax.reload();
					}
				})
			} else {
				return false;
			}
		});	
		
		$('#addUser').click(function(){
			var action = 'getUserRole';
			var options = '<option value="">-- select an option --</option>';
			$.ajax({
				url:'action.php',
				method:"POST",
				data:{action:action},
				dataType:"json",
				success:function(data){
					$('#userModal').modal('show');
					$('#userForm')[0].reset();
					for(let i=0; i<data.length; i++){
						options += '<option value="' + data[i]['idrole'] + '">' + data[i]['name'] + '</option>';
					}
					$("#userRole").html(options);
								
					$('.modal-title').html("<i class='fa fa-plus'></i> Add User");
					$('#uaction').val('addUser');
					$('#usave').val('Add User');
				}
			})
		});		

		$("#userList").on('click', '.update', function(){
			var userId = $(this).attr("id");
			var action = 'getUserInfo';
			$.ajax({
				url:'action.php',
				method:"POST",
				data:{userId:userId, action:action},
				dataType:"json",
				success:function(data){
					var roleOptions = '<option value="' + data.role + '">' + data.roleName + '</option>';
					$('#userModal').modal('show');
					$('#userId').val(data.idattendee);
					$('#userName').val(data.name);
					$('#userPassword').val(data.password);
					if(data.selectRoleOptions != null){
						for(let i=0; i<data.selectRoleOptions.length; i+=2){
							roleOptions += '<option value="' + data.selectRoleOptions[i]+ '">' + data.selectRoleOptions[i+1]+ '</option>';
					 	}
					 }
					$('#userRole').html(roleOptions);
					$('.modal-title').html("<i class='fa fa-plus'></i> Edit User");
					$('#uaction').val('updateUser');
					$('#usave').val('Save');
				}
			})
		});

		$("#userModal").on('submit','#userForm', function(event){
			event.preventDefault();
			$('#usave').attr('disabled','disabled');
			var formData = $(this).serialize();
			$.ajax({
				url:"action.php",
				method:"POST",
				data:formData,
				success:function(data){				
					$('#userForm')[0].reset();
					$('#userModal').modal('hide');				
					$('#usave').attr('disabled', false);
					allUserData.ajax.reload();
					attendeeData.ajax.reload();
				}
			})
		});	
		$("#userList").on('click', '.delete', function(){
			var userId = $(this).attr("id");		
			var action = "deleteUser";
			if(confirm("Are you sure you want to delete this user?")) {
				$.ajax({
					url:"action.php",
					method:"POST",
					data:{userId:userId, action:action},
					success:function(data) {					
						allUserData.ajax.reload();
						attendeeData.ajax.reload();
					}
				})
			} else {
				return false;
			}
		});	

		//$('#registEvent').click(function(){


			// var action = 'getAttendeeInfo';
			// var eventOptions = '<option value="">-- select an option --</option>';

			// $.ajax({
			// 	url:'action.php',
			// 	method:"POST",
			// 	data:{action:action},
			// 	dataType:"json",
			// 	success:function(data){
			// 		$('#registerModal').modal('show');
			// 		$('#registerForm')[0].reset();
				
			// 		for(let i=0; i<data.result1.length; i++){
			// 			eventOptions += '<optgroup label="' + data.result1[i]['eventName'] + '"><option value="' + data.result1[i]['idevent'] + '-' + data.result1[i]['idsession'] +'">' + data.result1[i]['name'] + '</option></optgroup>';
			// 		}


			// 		$("#registerSessionName").html(eventOptions);
					
			// 		$('.modal-title').html("<i class='fa fa-plus'></i> Regist Event");
			// 		$('#raction').val('registerEvent');
			// 		$('#rsave').val('Add');
			// 	}
			// })
		//});	

		$('#registEvent').click(function(){
			var action = 'getRegistEvent';
			var eventOptions = '<option value="">-- select an option --</option>';
			$.ajax({
				url:'action.php',
				method:"POST",
				data:{action:action},
				dataType:"json",
				success:function(data){
					$('#registerModal').modal('show');
					$('#registerForm')[0].reset();

					$("#registerId").val(data.result1[0]['registerId']);			
					for(let i=0; i<data.result1.length; i++){
						eventOptions += '<optgroup label="' + data.result1[i]['eventName'] + '"><option value="' + data.result1[i]['idevent'] + '-' + data.result1[i]['idsession'] +'">' + data.result1[i]['name'] + '</option></optgroup>';
					}
					$("#registerSessionName").html(eventOptions);
					
					$('.modal-title').html("<i class='fa fa-plus'></i> Regist New Event");
					$('#raction').val('registEvent');
					$('#rsave').val('Regist');
				}
			})
		});	
		$("#registerModal").on('submit','#registerForm', function(event){
			event.preventDefault();
			$('#rsave').attr('disabled','disabled');
			var formData = $(this).serialize();
			$.ajax({
				url:"action.php",
				method:"POST",
				data:formData,
				success:function(data){				
					$('#registerForm')[0].reset();
					$('#registerModal').modal('hide');				
					$('#rsave').attr('disabled', false);
					registerData.ajax.reload();
					attendeeData.ajax.reload();
				}
			})
		});	
		$("#registeredList").on('click', '.delete', function(){
			var registerId = $(this).attr("id");		
			var action = "cancelEvent";
			if(confirm("Are you sure you want to cancel this event?")) {
				$.ajax({
					url:"action.php",
					method:"POST",
					data:{registerId:registerId, action:action},
					success:function(data) {					
						registerData.ajax.reload();
						attendeeData.ajax.reload();
					}
				})
			} else {
				return false;
			}
		});	
});