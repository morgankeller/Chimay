<?php
include_once('security.php');
include_once('config.php');
define('pageName','clientDashboard');
if(isset($_GET['clientID'])) {
  define('pageAction','showClient');
  define('pageTitle','Client Information');
} else {
  define('pageAction','allClients');
  define('pageTitle','Clients!');
}
include_once('header.php');
?>
<div class="container-fluid">
	<div class="row">
    <div class="col-sm-12 main">
      <h1 class="page-header"><?php echo pageTitle; ?></h1>
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
  <div class="row">
    <div class="col-sm-6 main">
      <div class="client-info">

      </div>
    </div>
    <div class="col-sm-6 main">
      <div class="contacts">
        <h2>Contacts</h2>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 main">
      <div class="client-map">
        <h2>Map</h2>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-5 main">
      <div class="add-client-notes">
        <h2>Add Note</h2>
        <form class="form-horizontal note-form" role="form">
          <div class="row">
            <div class="form-group">
              <div class="col-xs-12">
                <input type="text" class="form-control" id="noteTitle" name="noteTitle" placeholder="title">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group">
              <div class="col-xs-12">
                <textarea class="form-control" rows="5" id="noteBody" name="noteBody" placeholder="message"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- save note -->
            <input type="hidden" class="form-control" id="clientID" name="clientID" <?php if(isset($_GET['clientID'])){ echo('value='.$_GET['clientID']);} ?>>
            <button type="submit" class="btn btn-success save-button" data-action="saveNote">Save</button>
          </div>
        </form>
      </div>
    </div>
    <div class="col-sm-7 main">
      <h2>Notes</h2>
      <div class="notes">
      </div>
    </div>
  </div>
</div>
<?php
	include_once('footer.php');
?>