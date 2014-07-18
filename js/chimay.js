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
  invoice : {
    init     : function(){
    	// Populate Client Dropdown
    	clientDropdown();
    	// Update Client information on change to Client selector
    	$(document).on("change","#clientID",function() {
    		var clientID = $(this).val();
    		clientInfo(clientID);
    	});
    	// Calculate totals
    	calculateSubtotals();
    	calculateTotal();
    	// Date/Time pickers
    	$("#invoiceDatePicker").datetimepicker();
    	$("#invoiceDuePicker").datetimepicker();
    	// Add Row
    	$(".add-row-button").click(function() {
    		$(".row-item:first").clone().appendTo(".invoice-rows");
    		$(".row-item:last").find("input").val('');
    	});
   		// Update Numbers
   		$(document).on("keyup",".update-total",function() {
    		//update sub totals
    		calculateSubtotals();
    		//update master total
    		calculateTotal();
    	});
    	// Delete Row
    	$(document).on("click",".delete-row",function() {
    		if($(".delete-row").length > 1) {
	    		$(this).parent().parent().parent().parent().remove();
	    		calculateSubtotals();
	    		calculateTotal();
    		}
    	});
    	// Form Submission
    	$(".save-button").on("click",function(event) {
			event.preventDefault();
			var dataAction = $(this).attr('data-action');
			//console.log($(this).serialize());
			$.ajax({
				url: "api.php?function="+dataAction+"&"+$(".invoice-form").serialize(),
				cache: false
				}).done(function(data) {
					// insert some error checking here
					
					// success, go to Invoices Page
					window.location.replace('index.php?invoiceID='+data.invoiceID);
			});
		});
		// Alerts
		//$(".alert").alert();
    	},
    addEstimate	: function() {

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

function listClients() {
	$.ajax({
		url: "api.php?function=listClients",
		cache: false
		}).done(function(data) {
			$.each(data,function() {
				$(".client-table").append('<tr><td><a href="client.php?clientID='+this.clientID+'">'+this.clientName+'</a></td><td>'+this.clientCity+'</td><td><a href="'+this.clientWebsite+'" target="_blank">'+this.clientWebsite+'</a></td><td><a class="btn btn-primary" role="button" href="client.php?clientID='+this.clientID+'">Edit</a></td></tr>');
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
			url: "api.php?function=listClients",
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
					var output = '<h4>'+data[0].clientName+'</h4><p>Attn: '+data[0].clientContact+'<br />'+data[0].clientAddress1+'<br />'+data[0].clientAddress2+'<br />'+data[0].clientCity+', '+data[0].clientState+' '+data[0].clientZip+'</p>';
				} else {
					var output = '<h4>'+data[0].clientName+'</h4><p>Attn: '+data[0].clientContact+'<br />'+data[0].clientAddress1+'<br />'+data[0].clientCity+', '+data[0].clientState+' '+data[0].clientZip+'</p>';
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
