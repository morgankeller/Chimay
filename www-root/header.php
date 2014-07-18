<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <title><?php echo pageTitle; ?> - <?php echo siteName; ?></title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
  </head>
  <body data-controller="<?php echo pageName; ?>" data-action="<?php echo pageAction; ?>">
  <?php
  if(isset($_COOKIE['userName']) && isset($_COOKIE['userPassword'])) {
  	?>
  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container-fluid">
	  <div class="navbar-header">
	    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	      <span class="sr-only">Toggle navigation</span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	      <span class="icon-bar"></span>
	    </button>
	    <a class="navbar-brand" href="<?php echo siteRoot; ?>"><img src="<?php echo headerLogo; ?>" alt="<?php echo siteName; ?>" class="img-responsive" /></a>
	  </div>
	  <div class="navbar-collapse collapse">
	    <ul class="nav navbar-nav navbar-right">
	      <li><div><a href="client.php" class="btn btn-info navbar-btn">Add Client</a></div></li>
        <li><div><a href="contact.php" class="btn btn-info navbar-btn">Add Contact</a></div></li>
	      <li><a href="#">Messages</a></li>
	      <li><a href="#">Profile</a></li>
	      <li><a href="security.php?logout=yup">Log out</a></li>
	    </ul>
	  </div>
	</div>
</div>
<?php
  } 
 ?>
