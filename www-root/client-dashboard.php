<?php
include_once('security.php');
include_once('config.php');
define('pageName','clientDashboard');
define('pageAction','');
define('pageTitle','Clients');
include_once('header.php');
?>
<div class="container-fluid">
	<div class="row">
        <div class="col-sm-12 main">
          <h1 class="page-header">Clients!</h1>
          <div class="table-responsive">
            <table class="table table-striped">
              <tbody class="client-table">
                <!--
                -->
              </tbody>
            </table>
          </div>

        </div>
      </div>
</div>
<?php
	include_once('footer.php');
?>