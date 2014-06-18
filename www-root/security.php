<?php
	session_start();
	include_once('config.php');

	function checkCreds($userName,$userPassword) {
		// Update this with the root of the site
		$url = siteRoot.'api.php?function=checkCreds&userName='.$userName.'&userPassword='.$userPassword;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
	
	if (isset($_GET['logout'])) {
		// remove all cookies
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		header('Location: login.php?logout=yup');
	} else if (isset($_POST['userName']) && isset($_POST['userPassword'])) {	
		//check credentials
		$userName = $_POST['userName'];
		$userPassword = $_POST['userPassword'];
		//$userPassword = md5('zomg'.$_POST['userPassword'].'h4h4h4');
		$data = checkCreds($userName,$userPassword);
		$data = json_decode($data);
		if (isset($data->{'text'}) || empty($data)) {	// credentials are bad
			header('Location: login.php?login=nope&msg='.$data->{'text'});
		} else {	//credentials are good
			//continue
			setcookie('userName',$data->{'userName'},time()+2592000);
			setcookie('userFirstName',$data->{'userFirstName'},time()+2592000);
			setcookie('userEmail',$data->{'userEmail'},time()+2592000);
			setcookie('userPassword',$data->{'userPassword'},time()+2592000);
			// keep going
			header('Location: index.php');
		}
	} else if(isset($_COOKIE['userName']) && isset($_COOKIE['userPassword'])) {
		//check credentials
		$data = checkCreds($_COOKIE['userName'],$_COOKIE['userPassword']);
		$data = json_decode($data);
		if (isset($data->{'text'}) || empty($data)) {	// credentials are bad
			header('Location: login.php?login=nope');
		} else {
			//credentials are good, keep going
		}
	} else {	// no cookies or post vars set, show login page
		include_once('login.php');
		exit();
	}



?>