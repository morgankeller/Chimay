<?php
include_once('security.php');
include_once('config.php');
define('pageName','dashboard');
define('pageAction','dashboardSetup');
define('pageTitle','Dashboard');
include_once('header.php');
?>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 main">
          <h1 class="page-header">Dashboard!</h1>
          <h2 class="sub-header">New Clients <a href="client-dashboard.php" class="btn btn-success" role="button">View All</a></h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <tbody class="client-table">
                <!--
                -->
              </tbody>
            </table>
          </div>
          <h2 class="sub-header">Contacts</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <tbody class="contact-table">
                <!--
                -->
              </tbody>
            </table>
          </div>
          <h2 class="sub-header">Messages *coming soon*</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <tbody class="message-table">
                <!--
                -->
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
<?php
	//print_r($_COOKIE);
  include_once('footer.php');
?>