<?php
include_once('config.php');
// if there was an error logging in, set the $msg variable
if (isset($_GET['login']) && $_GET['login'] == 'nope') {
	$msg = 'try again';
} else if(isset($_GET['logout']) && $_GET['logout'] == 'yup') {
	$msg = 'you have been logged out';
}
define('pageName','login');
define('pageTitle','Login');
define('pageAction','loginAction');
include_once('header.php');
?>
			<div class="container">
				<div class="row">
					<?php
						if(isset($msg)) {
							echo('<span class="help-inline">'.$msg.'</span>');
						}
					?>
					<form action="security.php" class="form-horizontal" method="POST">
						<fieldset>
							<div class="control-group">
								<input type="text" placeholder="username" id="userName" name="userName">
							</div>
							<div class="control-group">
								<input type="password" placeholder="password" id="userPassword" name="userPassword">
							</div>
							<button type="submit" class="btn">Log me in</button>
						</fieldset>
					</form>
				</div>
<?php
include_once('footer.php');
?>