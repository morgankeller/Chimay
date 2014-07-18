<?php
include_once('security.php');
include_once('config.php');
define('pageName','contact');

if(isset($_GET['contactID'])) {
	define('pageAction','editContact');
	define('pageTitle','Edit Contact');
} else {
	define('pageAction','');
	define('pageTitle','Add Contact');
}
include_once('header.php');
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-12 main">
			<h1 class="page-header hidden-print"><?php echo pageTitle; ?></h1>
		</div>
	</div>
	<form class="form-horizontal contact-form" role="form">
	<!-- Contact first name -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="contactFirstName" class="control-label">First Name</label>
			</div>
			<div class="col-xs-10">
				<input type="text" class="form-control" id="contactFirstName" name="contactFirstName" placeholder="first name">
			</div>
		</div>
	</div>
	<!-- Contact last name -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="contactLastName" class="control-label">Last Name</label>
			</div>
			<div class="col-xs-10">
				<input type="text" class="form-control" id="contactLastName" name="contactLastName" placeholder="last name">
			</div>
		</div>
	</div>
	<!-- Contact title -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="contactTitle" class="control-label">Title</label>
			</div>
			<div class="col-xs-10">
				<input type="text" class="form-control" id="contactTitle" name="contactTitle" placeholder="title">
			</div>
		</div>
	</div>
	<!-- Contact email -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="contactEmail" class="control-label">Email Address</label>
			</div>
			<div class="col-xs-10">
				<input type="text" class="form-control" id="contactEmail" name="contactEmail" placeholder="email">
			</div>
		</div>
	</div>
	<!-- Contact phone number -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="contactPhone" class="control-label">Phone Number</label>
			</div>
			<div class="col-xs-10">
				<input type="text" class="form-control" id="contactPhone" name="contactPhone" placeholder="phone number">
			</div>
		</div>
	</div>
	<!-- Connect to Client -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-2">
				<label for="clientID" class="control-label">Client</label>
			</div>
			<div class="col-xs-4">
				<select class="form-control" id="clientID" name="clientID"><option value="0"></option></select>
			</div>
			<div class="col-xs-5 col-xs-offset-1 client-info">
				<!-- client contact information -->
			</div>
		</div>
	</div>
	<!-- Save -->
	<div class="row">
		<div class="form-group">
			<div class="col-xs-offset-2 col-xs-10">
				<input type="hidden" class="form-control" id="contactID" name="contactID" <?php if(isset($_GET['contactID'])){ echo('value='.$_GET['contactID']);} ?>>
				<button type="submit" class="btn btn-default save-button" data-action="saveContact">Save</button>
			</div>
		</div>
	</div>
				
	</form>
</div>
<?php
	include_once('footer.php');
?>