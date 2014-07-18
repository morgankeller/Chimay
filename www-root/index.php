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
          <h2 class="sub-header">Clients</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <tbody class="client-table">
                <!--
                <tr>
                  <td>1,008</td>
                  <td>Fusce</td>
                  <td>nec</td>
                  <td>tellus</td>
                  <td>sed</td>
                </tr>
                -->
              </tbody>
            </table>
          </div>
          <h2 class="sub-header">Messages *coming soon*</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <tbody class="message-table">
                <!--
                <tr>
                  <td>1,008</td>
                  <td>Fusce</td>
                  <td>nec</td>
                  <td>tellus</td>
                  <td>sed</td>
                </tr>
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