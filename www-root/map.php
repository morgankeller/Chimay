<?php
include_once('security.php');
include_once('config.php');
define('pageName','map');
define('pageAction','mapSetup');
define('pageTitle','Map');
include_once('header.php');
?>
<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA2h2IUlawGFkeg2mXiq3AqLtIvGuSDGoI">
</script>
    <div id="map-canvas"></div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 main">
          
        </div>
      </div>
    </div>
<?php
	//print_r($_COOKIE);
  include_once('footer.php');
?>