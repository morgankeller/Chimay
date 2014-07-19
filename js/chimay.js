INVOICE = {
  common : {
    init     : function(){
    	
    	}
  },
  dashboard : {
    init     : function(){
    	// http://www.chartjs.org/docs/
    	// paid percentage chart
    	/*
    	var paidData = "";
		$.ajax({
			url: "api.php?function=paidData",
			async: false,
			cache: false,
			dataType: 'json'
		}).done(function(returnData,status) {
				paidData = returnData;
			});
    	var ctx = $("#paidChart").get(0).getContext("2d");
		var paidChart = new Chart(ctx);
		var options = [
			{
				animateScale: true
			}		
		];
		paidChart.Doughnut(paidData,options);
		$("#paidChart-legend").append(createLegend(paidData));
		*/

    	},
    dashboardSetup     : function(){
    	// dashboard setup
    	listMessages();
    	listClients();
    	listContacts();
    }
  },
  client : {
    init     : function(){
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
				$('input,textarea').each(function() {
					var inputID = $(this).attr('id');
					$(this).val(data[0][inputID]);
					
					//alert('inputID: '+inputID+', data: '+data[0][inputID]);
				});
				
		});
		
    }
  },
  clientDashboard : {
    init     : function(){
    	// list clients
    	listClients(10000);
		}
  },
  contact : {
    init     : function(){
    	// Populate Client Dropdown
    	clientDropdown();
    	// Update Client information on change to Client selector
    	$(document).on("change","#clientID",function() {
    		var clientID = $(this).val();
    		clientInfo(clientID);
    	});
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
				$('input,textarea').each(function() {
					var inputID = $(this).attr('id');
					$(this).val(data[0][inputID]);
					
					//alert('inputID: '+inputID+', data: '+data[0][inputID]);
				});
				// Select Client from dropdown
				$("#clientID > option[value="+data[0].clientID+"]").attr("selected",true);
				// Pull Client information
				clientInfo(data[0].clientID);
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

function listMessages() {
	$.ajax({
		url: "api.php?function=listMessages",
		cache: false
		}).done(function(data) {
			$.each(data,function() {
				$(".message-table").append('<tr><td>'+this.messageTitle+'</td><td>'+this.userFirstName+'</td><td>'+this.messageBody+'</td></tr>');
			});
	});
}

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
				$(".client-table").append('<tr><td><a href="client.php?clientID='+this.clientID+'">'+this.clientName+'</a></td><td>'+this.clientCity+'</td><td><a href="'+this.clientWebsite+'" target="_blank">'+this.clientWebsite+'</a></td><td><a class="btn btn-primary" role="button" href="client.php?clientID='+this.clientID+'">Edit</a></td></tr>');
			});
	});
}

function listContacts() {
	$.ajax({
		url: "api.php?function=listContacts",
		cache: false
		}).done(function(data) {
			$.each(data,function() {
				$(".contact-table").append('<tr><td><a href="contact.php?contactID='+this.contactID+'">'+this.contactFirstName+' '+this.contactLastName+'</a></td><td><a href="tel:'+this.contactPhone+'">'+this.contactPhone+'</a></td><td><a href="mailto:'+this.contactEmail+'" target="_blank">'+this.contactEmail+'</a></td><td><a class="btn btn-primary" role="button" href="contact.php?contactID='+this.contactID+'">Edit</a></td></tr>');
			});
	});
}

function createLegend(data) {
	var chartData = data;
	var output = '<ul class="legend-list">';
	$.each(chartData, function(key, val) {
		output += '<li><div class="legend-color pull-left" style="background-color:'+val["color"]+'">&nbsp;</div>'+val["name"]+'</li>';
	});
	output += "</ul>";
	//alert(output);
	return output;
}

function formatDate(mysqlDate) {
	var t = mysqlDate.split(/[- :]/);
	var newDate = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
	return newDate.toLocaleDateString();
}

function clientDropdown() {
	$.ajax({
			url: "api.php?function=listClients&limit=10000",
			cache: false
			}).done(function(data) {
				$.each(data,function() {
					$("#clientID").append('<option value="'+this.clientID+'">'+this.clientName+'</option>');
				});
		});
}

function clientInfo(clientID) {
	$.ajax({
			url: "api.php?function=listClients&clientID="+clientID,
			cache: false
			}).done(function(data) {
				if(data[0].clientAddress2 != '') {
					var output = '<h4>'+data[0].clientName+'</h4><p>'+data[0].clientAddress1+'<br />'+data[0].clientAddress2+'<br />'+data[0].clientCity+', '+data[0].clientState+' '+data[0].clientZip+'</p>';
				} else {
					var output = '<h4>'+data[0].clientName+'</h4><p>'+data[0].clientAddress1+'<br />'+data[0].clientCity+', '+data[0].clientState+' '+data[0].clientZip+'</p>';
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
