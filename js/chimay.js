INVOICE = {
  common : {
    init     : function(){
    	
    	}
  },
  dashboard : {
    init     : function(){
    	
    	},
    dashboardSetup     : function(){
    	// dashboard setup
    	listNotes(null,null);
    	listClients();
    	listContacts();
    }
  },
  client : {
    init     : function(){
    	// client context radio buttons
    	contextRadios();
    	// Form Submission
    	$(".save-button").on("click",function(event) {
			event.preventDefault();
			var dataAction = $(this).attr('data-action');
			//console.log($(this).serialize());
			$.ajax({
				url: "api.php?function="+dataAction+"&"+$(".client-form").serialize(),
				cache: false
				}).done(function(data) {
					// insert some error checking here
					
					// success, go Home
					window.location.replace('index.php?clientID='+data.clientID);
			});
		});
    },
    editClient	:  function() {
    	var clientID = getParameterByName('clientID');
    	// update save action
    	$(".save-button").attr('data-action','editClient');
    	// pull in data from invoice
    	$.ajax({
			url: "api.php?function=listClients&clientID="+clientID,
			cache: false
			}).done(function(data) {
				// populate form
				$("input[type!='radio'],textarea").each(function() {
					var inputID = $(this).attr('id');
					$(this).val(data[0][inputID]);
				});
				// Select radio button of client context
				$("#contextID"+data[0].contextID).attr("checked",true);
		});
		
    }
  },
  clientDashboard : {
    init     : function(){
    	
	},
	allClients : function() {
		// list clients
    	listClients(10000);
	},
	showClient : function() {
		var clientID = getParameterByName('clientID');
		clientInfo(clientID);
		listClientContacts(clientID);
		listNotes('client',clientID);
		$(".save-button").on("click",function(event) {
			event.preventDefault();
			var dataAction = $(this).attr('data-action');
			//console.log($(this).serialize());
			$.ajax({
				url: "api.php?function="+dataAction+"&"+$(".note-form").serialize(),
				cache: false
				}).done(function(data) {
					// insert some error checking here
					
					// success, refresh notes
					$('.note-form').find("input[type=text], textarea").val("");
					$('.notes').empty();
					listNotes('client',clientID);
			});
		});
	}
  },
  contact : {
    init     : function(){
    	// Populate Client Select
    	clientSelect();
    	// Form Submission
    	$(".save-button").on("click",function(event) {
			event.preventDefault();
			var dataAction = $(this).attr('data-action');
			//console.log($(this).serialize());
			$.ajax({
				url: "api.php?function="+dataAction+"&"+$(".contact-form").serialize(),
				cache: false
				}).done(function(data) {
					// insert some error checking here
					
					// success, go Home
					window.location.replace('index.php?contact='+data.contactID);
			});
		});
    },
    editContact	:  function() {
    	var contactID = getParameterByName('contactID');
    	// update save action
    	$(".save-button").attr('data-action','editContact');
    	// pull in data from invoice
    	$.ajax({
			url: "api.php?function=listContacts&contactID="+contactID,
			cache: false
			}).done(function(data) {
				// populate form
				$("input[type!='checkbox'],textarea").each(function() {
					var inputID = $(this).attr('id');
					$(this).val(data[0][inputID]);
					//alert('inputID: '+inputID+', data: '+data[0][inputID]);
				});
				// Select Client from dropdown
				var i;
				var clientIDs = [];
				for(i=0; i<data[0].clientContacts.length; i++) {
					clientIDs[i] = data[0].clientContacts[i].clientID;
				};
				//alert(clientIDs);
				$("input[type='checkbox']").each(function() {
					if($.inArray(this.value, clientIDs) != -1) {
						$(this).attr("checked",true);
					}
				});
				//$("input[type='checkbox',value="+data[0].clientID+"]").attr("checked",true);
				// Pull Client information
				//clientInfo(data[0].clientID);
		});

		
    }
  },
  map : {
    init     : function(){
    	//https://maps.googleapis.com/maps/api/js?key=AIzaSyA2h2IUlawGFkeg2mXiq3AqLtIvGuSDGoI
    },
    mapSetup	:  function() {
		function initialize() {
	        var mapOptions = {
	          center: new google.maps.LatLng(34.0500, -118.2500),
	          zoom: 10
	        };
	        var map = new google.maps.Map(document.getElementById("map-canvas"),mapOptions);
	        var marker;
			$.ajax({
				url: "api.php?function=mapPoints",
				cache: false
				}).done(function(data) {
					$.each(data,function() {
						marker = new google.maps.Marker({
					        position: new google.maps.LatLng(this.clientLat, this.clientLng),
					        map: map,
					        title: " "+this.clientName+" "
					      });
					});
			});
	    }
    	google.maps.event.addDomListener(window, 'load', initialize);

    }
  }
}
/*
function listNotes() {
	$.ajax({
		url: "api.php?function=listNotes",
		cache: false
		}).done(function(data) {
			$.each(data,function() {
				$(".note-table").append('<tr><td>'+this.noteTitle+'</td><td>'+this.userFirstName+'</td><td>'+this.noteBody+'</td></tr>');
			});
	});
}
*/

function listClients(limit) {
	var clientURL = "api.php?function=listClients";
	if(typeof(limit)!=='undefined') {
		clientURL += "&limit="+limit;
	}
	$.ajax({
		//url: "api.php?function=listClients",
		url: clientURL,
		cache: false
		}).done(function(data) {
			$.each(data,function() {
				$(".client-table").append('<tr><td><a href="client-dashboard.php?clientID='+this.clientID+'">'+this.clientName+'</a></td><td>'+this.clientCity+'</td><td><a href="'+this.clientWebsite+'" target="_blank">'+this.clientWebsite+'</a></td><td><a class="btn btn-primary" role="button" href="client.php?clientID='+this.clientID+'">Edit</a></td></tr>');
			});
	});
}

function listClientContacts(clientID) {
	$.ajax({
		url: "api.php?function=getClientContacts&clientID="+clientID,
		cache: false
		}).done(function(data) {
			$.each(data,function() {
				$(".contacts").append('<p>'+this.contactFirstName+' '+this.contactLastName+'<br /><a href="tel:'+this.contactPhone+'">'+this.contactPhone+'</a><br /><a href="mailto:'+this.contactEmail+'" target="_blank">'+this.contactEmail+'</a><br /><a class="btn btn-primary" role="button" href="contact.php?contactID='+this.contactID+'">Edit</a></p>');
			});
	});
}

function listContacts() {
	$.ajax({
		url: "api.php?function=listContacts",
		cache: false
		}).done(function(data) {
			$.each(data,function() {
				$(".contact-table").append('<tr><td>'+this.contactFirstName+' '+this.contactLastName+'</td><td><a href="tel:'+this.contactPhone+'">'+this.contactPhone+'</a></td><td><a href="mailto:'+this.contactEmail+'" target="_blank">'+this.contactEmail+'</a></td><td><a class="btn btn-primary" role="button" href="contact.php?contactID='+this.contactID+'">Edit</a></td></tr>');
			});
	});
}

function listNotes(type,ID) {
	$.ajax({
		url: "api.php?function=listNotes&type="+type+"&ID="+ID,
		cache: false
		}).done(function(data) {
			$.each(data,function() {
				$(".notes").append('<div class="row"><div class="col-xs-12"><h4>'+this.noteTitle+' <small>Added by '+this.userFirstName+' on '+formatDate(this.noteCreated)+'</small></h4></div></div><div class="row"><div class="col-xs-12">'+this.noteBody+'</div></div>');
			});
	});
}

function formatDate(mysqlDate) {
	var t = mysqlDate.split(/[- :]/);
	var newDate = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
	return newDate.toLocaleDateString();
}

// Populate client context
function contextRadios() {
	$.ajax({
			url: "api.php?function=listContexts",
			cache: false
			}).done(function(data) {
				$.each(data,function() {
					$("#contextIDContainer").append('<div class="radio"><label><input type="radio" name="contextID" id="contextID'+this.contextID+'" value="'+this.contextID+'">'+this.contextName+'</label></div>');
				});
		});
}

// Populate client dropdown
function clientSelect() {
	$.ajax({
			url: "api.php?function=listClients&limit=10000",
			cache: false
			}).done(function(data) {
				$.each(data,function() {
					$("#clientID").append('<label><input type="checkbox" name="clientID[]" value="'+this.clientID+'">'+this.clientName+'</label><br />');
				});
		});
}

function clientInfo(clientID) {
	$.ajax({
			url: "api.php?function=listClients&clientID="+clientID,
			cache: false
			}).done(function(data) {
				if(data[0].clientAddress2 != '') {
					var output = '<address><strong>'+data[0].clientName+'</strong><br />'+data[0].clientAddress1+'<br />'+data[0].clientAddress2+'<br />'+data[0].clientCity+', '+data[0].clientState+' '+data[0].clientZip+'</address>';
				} else {
					var output = '<address><strong>'+data[0].clientName+'</strong><br />'+data[0].clientAddress1+'<br />'+data[0].clientCity+', '+data[0].clientState+' '+data[0].clientZip+'</address>';
				}
				$(".client-info").html(output);
		});
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

// Page JS Routing (based on DOM events): http://viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution
UTIL = {
  exec: function( controller, action ) {
    var ns = INVOICE,
        action = ( action === undefined ) ? "init" : action;
 
    if ( controller !== "" && ns[controller] && typeof ns[controller][action] == "function" ) {
      ns[controller][action]();
    }
  },
 
  init: function() {
    var body = document.body,
        controller = body.getAttribute( "data-controller" ),
        action = body.getAttribute( "data-action" );
 
    UTIL.exec( "common" );
    UTIL.exec( controller );
    UTIL.exec( controller, action );
  }
};
 
$( document ).ready( UTIL.init );
