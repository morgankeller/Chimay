<?php
include_once('security.php');
include_once('config.php');
define('pageName','client');

if(isset($_GET['clientID'])) {
	define('pageAction','editClient');
	define('pageTitle','Edit Client');
} else {
	define('pageAction','');
	define('pageTitle','Add Client');
}
include_once('header.php');
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 main">
			<h1 class="page-header hidden-print"><?php echo pageTitle; ?></h1>
		</div>
	</div>
	<form class="form-horizontal client-form" role="form">
	<!-- Client name -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="clientName" class="control-label">Client Name</label>
			</div>
			<div class="col-xs-10">
				<input type="text" class="form-control" id="clientName" name="clientName" placeholder="name">
			</div>
		</div>
	</div>
	<!-- Address -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="clientAddress1" class="control-label">Address</label>
			</div>
			<div class="col-xs-10">
				<input type="text" class="form-control" id="clientAddress1" name="clientAddress1" placeholder="address">
			</div>
			<div class="col-xs-offset-2 col-xs-10">
				<input type="text" class="form-control" id="clientAddress2" name="clientAddress2" placeholder="">
			</div>
		</div>
	</div>
	<!-- City + State + Zip -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="clientCity" class="control-label">City + State + Zip</label>
			</div>
			<div class="col-xs-5">
				<input type="text" class="form-control" id="clientCity" name="clientCity" placeholder="city">
			</div>
			<div class="col-xs-2">
				<input type="text" class="form-control" id="clientState" name="clientState" placeholder="state" value="CA">
			</div>
			<div class="col-xs-3">
				<input type="text" class="form-control" id="clientZip" name="clientZip" placeholder="zip">
			</div>
		</div>
	</div>
	<!-- Website -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="clientWebsite" class="control-label">Website</label>
			</div>
			<div class="col-xs-10">
				<input type="text" class="form-control" id="clientWebsite" name="clientWebsite" placeholder="website">
			</div>
		</div>
	</div>
	<!-- Context -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="contextID" class="control-label">Context</label>
			</div>
			<div class="col-xs-10" id="contextIDContainer">
				
			</div>
		</div>
	</div>
	<!-- Save -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-offset-2 col-xs-10">
				<input type="hidden" class="form-control" id="clientID" name="clientID" <?php if(isset($_GET['clientID'])){ echo('value='.$_GET['clientID']);} ?>>
				<button type="submit" class="btn btn-default save-button" data-action="saveClient">Save</button>
			</div>
		</div>
	</div>
				
	</form>
</div>
<?php
	include_once('footer.php');
?>