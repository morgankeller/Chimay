<?php
require_once('config.php');
class Chimay {
	
	var $link;
	
	function __construct() {
		$this->link = mysqli_connect(db_host, db_user, db_pass, db_name, db_port);
	}
	
	/////////////
	/*  Notes  */
	/////////////
	
	/* Save Note */
	public function saveNote($data) {
		$note = $this->processGET($data);
		if(isset($note['clientID'])){
			$sql = "INSERT INTO `notes` (`noteTitle`,`noteBody`,`userID`,`clientID`) VALUES ('".$note['noteTitle']."','".$note['noteBody']."','".$_COOKIE['userID']."','".$note['clientID']."')";
		} else if(isset($note['contactID'])) {
			$sql = "INSERT INTO `notes` (`noteTitle`,`noteBody`,`userID`,`contactID`) VALUES ('".$note['noteTitle']."','".$note['noteBody']."','".$_COOKIE['userID']."','".$note['contactID']."')";
		}
		$res = mysqli_query($this->link,$sql);
		$note['noteID'] = mysqli_insert_id($this->link);
		$note['noteStatus'] = "success";
		return $note;
	}

	/* List Notes */
	public function listNotes($type=null,$ID=null) {
		$messages = array();
		if($type != null && $type == 'client' && $ID != null) {
			$sql = "SELECT * FROM `notes`,`users` WHERE `notes`.userID = `users`.userID AND `notes`.clientID = '".$ID."' ORDER BY `notes`.noteCreated DESC";
		} else if($type != null && $type == 'contact' && $ID != null) {
			$sql = "SELECT * FROM `notes`,`users` WHERE `notes`.userID = `users`.userID AND `notes`.contactID = '".$ID."' ORDER BY `notes`.noteCreated DESC";
		} else {
			$sql = "SELECT * FROM `notes`,`users` WHERE `notes`.userID = `users`.userID ORDER BY `notes`.noteCreated DESC";
		}
		$res = mysqli_query($this->link,$sql);
		$fields = mysqli_fetch_fields($res);
		$i=0;
		while($row = mysqli_fetch_array($res)) {
			foreach($fields as $f) {
				$messages[$i][$f->name] = $row[$f->name];
			}
			$i++;
		}
		return $messages;
	}

	/////////////////
	/*  Utilities  */
	/////////////////
	
	/* Process $_GET data into array */
	private function processGET($data) {
		$cleanData = array();
		foreach($data as $key => $val) {
			if(is_array($val)) {
				foreach($val as $v) {
					$cleanData[$key][] = addslashes($v);
				}
			} else {
				$cleanData[$key] = addslashes($val);
			}
		}
		return $cleanData;
	}

	//////////////
	/* Clients */
	//////////////
	
	/* List Clients */
	public function listClients($limit=5,$clientID=null) {
		$clients = array();
		if($clientID != null) {
			$sql = "SELECT * FROM `clients` WHERE clientID =".$clientID;
		} else {
			$sql = "SELECT * FROM `clients` ORDER BY `clients`.`clientUpdated` DESC LIMIT ".$limit;
		}
		$res = mysqli_query($this->link,$sql);
		$fields = mysqli_fetch_fields($res);
		$i=0;
		while($row = mysqli_fetch_array($res)) {
			foreach($fields as $f) {
				$clients[$i][$f->name] = $row[$f->name];
			}
			$i++;
		}
		return $clients;
	}
	
	/* Save Client */
	public function saveClient($data) {
		$client = $this->processGET($data);
		$sql = "INSERT INTO `clients` (`clientName`,`clientAddress1`,`clientAddress2`,`clientCity`,`clientState`,`clientZip`,`clientWebsite`,`userID`,`contextID`) VALUES ('".$client['clientName']."','".$client['clientAddress1']."','".$client['clientAddress2']."','".$client['clientCity']."','".$client['clientState']."','".$client['clientZip']."','".$client['clientWebsite']."','".$_COOKIE['userID']."','".$client['contextID']."')";
		$res = mysqli_query($this->link,$sql);
		$client['clientID'] = mysqli_insert_id($this->link);
		$latLong = $this->getLatLong($client['clientID']);
		$client['clientStatus'] = "success";
		return $client;
	}
	
	/* Edit Client */
	public function editClient($clientID,$data) {
		$client = $this->processGET($data);
		$sql = "UPDATE `clients` SET `clientName` = '".$client['clientName']."' , `clientAddress1` = '".$client['clientAddress1']."' , `clientAddress2` = '".$client['clientAddress2']."' , `clientCity` = '".$client['clientCity']."' , `clientState` = '".$client['clientState']."' , `clientZip` = '".$client['clientZip']."', `clientWebsite` = '".$client['clientWebsite']."', `userID` = '".$_COOKIE['userID']."',`contextID` = '".$client['contextID']."' WHERE clientID = ".$clientID;
		$res = mysqli_query($this->link,$sql);
		$client['sql'] = $sql;
		$client['clientStatus'] = "success";
		$latLong = $this->getLatLong($clientID);
		return $client;
	}

	/* List Contexts */
	public function listContexts() {
		$contexts = array();
		$sql = "SELECT * FROM `contexts`";
		$res = mysqli_query($this->link,$sql);
		$fields = mysqli_fetch_fields($res);
		$i=0;
		while($row = mysqli_fetch_array($res)) {
			foreach($fields as $f) {
				$contexts[$i][$f->name] = $row[$f->name];
			}
			$i++;
		}
		return $contexts;
	}
	
	//////////////
	/* Contacts */
	//////////////

	/* List Contacts */
	public function listContacts($contactID=null) {
		$contacts = array();
		if($contactID != null) {
			$sql = "SELECT * FROM `contacts` WHERE contactID =".$contactID;
		} else {
			$sql = "SELECT * FROM `contacts` ORDER BY `contactUpdated` DESC";
		}
		$res = mysqli_query($this->link,$sql);
		$fields = mysqli_fetch_fields($res);
		$i=0;
		while($row = mysqli_fetch_array($res)) {
			foreach($fields as $f) {
				$contacts[$i][$f->name] = $row[$f->name];
				
			}
			$contacts[$i]['clientContacts'] = $this->getContactClients($row['contactID']);
			$i++;
		}
		return $contacts;
	}
	
	/* Save Contacts */
	public function saveContact($data) {
		$contact = $this->processGET($data);
		$contactSql = "INSERT INTO `contacts` (`contactFirstName`,`contactLastName`,`contactTitle`,`contactEmail`,`contactPhone`,`userID`) VALUES ('".$contact['contactFirstName']."','".$contact['contactLastName']."','".$contact['contactTitle']."','".$contact['contactEmail']."','".$contact['contactPhone']."','".$_COOKIE['userID']."')";
		$res = mysqli_query($this->link,$contactSql);
		$contact['contactSql'] = $contactSql;
		$contact['contactID'] = mysqli_insert_id($this->link);
		$contact['contactStatus'] = "success";
		if(isset($contact['clientID'])) {
			foreach($contact['clientID'] as $c) {
				$this->saveClientContact($c,$contact['contactID']);
			}
		}
		return $contact;
	}
	
	/* Edit Contacts */
	public function editContact($contactID,$data) {
		$contact = $this->processGET($data);
		$sql = "UPDATE `contacts` SET `contactFirstName` = '".$contact['contactFirstName']."' , `contactLastName` = '".$contact['contactLastName']."' ,`contactTitle` = '".$contact['contactTitle']."' , `contactEmail` = '".$contact['contactEmail']."' , `contactPhone` = '".$contact['contactPhone']."' ,`userID` = '".$_COOKIE['userID']."' WHERE contactID = ".$contactID;
		$res = mysqli_query($this->link,$sql);
		$contact['updateSQL'] = $sql;
		//remove old client contact
		$contact['removeSQL'] = $this->removeClientContact($contactID);
		if(isset($contact['clientID']) && is_array($contact['clientID'])){
			foreach($contact['clientID'] as $c) {
				$contact['addClientContactSQL'][] = $this->saveClientContact($c,$contactID);
			}
		} elseif (isset($contact['clientID'])) {
			$contact['addClientContactSQL'] = $this->saveClientContact($contact['clientID'],$contactID);
		}
		$contact['contactStatus'] = "success";
		return $contact;
	}

	/////////////////////
	/* Client Contacts */
	/////////////////////

	/* Get Client Contacts */
	public function getClientContacts($clientID) {
		$contactIDs = array();
		$contacts = array();
		$sql = "SELECT `contactID` FROM `clientContacts` WHERE clientID = ".$clientID;
		$res = mysqli_query($this->link,$sql);
		$fields = mysqli_fetch_fields($res);
		$i=0;
		while($row = mysqli_fetch_array($res)) {
			$contactIDs[$i] = $row['contactID'];
			$i++;
		}
		foreach($contactIDs as $c) {
			$contacts = array_merge($contacts,$this->listContacts($c));
		}
		return $contacts;
	}

	/* Get Contact Clients */
	public function getContactClients($contactID) {
		$clientIDs = array();
		$clients = array();
		$sql = "SELECT `clientID` FROM `clientContacts` WHERE contactID = ".$contactID;
		$res = mysqli_query($this->link,$sql);
		$fields = mysqli_fetch_fields($res);
		$i=0;
		while($row = mysqli_fetch_array($res)) {
			$clientIDs[$i] = $row['clientID'];
			$i++;
		}
		foreach($clientIDs as $c) {
			$clients = array_merge($clients,$this->listClients(null,$c));
		}
		return $clients;
	}

	/* Save Client Contacts */
	public function saveClientContact($clientID,$contactID) {
		$clientContactSql = "INSERT INTO `clientContacts` (`clientID`,`contactID`) VALUES ('".$clientID."','".$contactID."')";
		$res = mysqli_query($this->link,$clientContactSql);
		return $clientContactSql;
	}

	/* Remove Client Contact */
	public function removeClientContact($contactID) {
		$sql = "DELETE FROM `clientContacts` WHERE `contactID`=".$contactID;
		$res = mysqli_query($this->link,$sql);
		return $sql;
	}

	/////////////
	/*   Map   */
	/////////////
	/* Get latitude and longitude for address */
	public function getLatLong($clientID) {
		$clientData = $this->listClients(null,$clientID);
		$addressString = $clientData[0]['clientAddress1'].', '.$clientData[0]['clientCity'].', '.$clientData[0]['clientState'].', '.$clientData[0]['clientZip'];
		$addressString = str_replace(' ','+',$addressString);
		$apiURL = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$addressString.'&key=AIzaSyA2h2IUlawGFkeg2mXiq3AqLtIvGuSDGoI';
		// pull data from Google
		// https://developers.google.com/maps/documentation/geocoding/
		$apiData = file_get_contents($apiURL);
		$apiData = json_decode($apiData);
		if($apiData->status == 'OK') {
			$lat = $apiData->results[0]->geometry->location->lat;
			$lng = $apiData->results[0]->geometry->location->lng;
			$sql = "UPDATE `clients` SET `clientLat` = '".$lat."', `clientLng` = '".$lng."' WHERE clientID = ".$clientID;
			$res = mysqli_query($this->link,$sql);
			$result['sql'] = $sql;
			$result['status'] = $apiData->status;
			return $result;
		} else {
			return $apiData->status;
		}
	}

	/* Get map points to build map */
	public function mapPoints($clientID=null) {
		$points = array();
		if($clientID != null) {
			$sql = "SELECT `clientLat`, `clientLng`,`clientName`,`contextID` FROM `clients` WHERE `clientID` = ".$clientID;
		} else {
			$sql = "SELECT `clientLat`, `clientLng`,`clientName`,`contextID` FROM `clients` WHERE `clientLat` != '0.0000000'";
		}
		$res = mysqli_query($this->link,$sql);
		$fields = mysqli_fetch_fields($res);
		$i=0;
		while($row = mysqli_fetch_array($res)) {
			foreach($fields as $f) {
				$points[$i][$f->name] = $row[$f->name];
			}
			$i++;
		}
		return $points;
	}
	
	///////////
	/* Users */
	///////////
	
	/* Get User Name */
	public function getUserName($userID) {
		$userName = '';
		$sql = "SELECT userName FROM `users` WHERE userID=".$userID;
		$res = mysqli_query($this->link,$sql);
		while($row = mysqli_fetch_array($res)) {
			$userName = $row['userName'];
			}
		return $userName;
	}

	/* List Users */
	public function listUsers($userID=null) {
		$users = array();
		if($userID != null) {
			$sql = "SELECT * FROM `users` WHERE userID=".$userID;
		} else {
			$sql = "SELECT * FROM `users` ORDER BY `userID` ASC";
		}
		$res = mysqli_query($this->link,$sql);
		$fields = mysqli_fetch_fields($res);
		$i=0;
		while($row = mysqli_fetch_array($res)) {
			foreach($fields as $f) {
				$users[$i][$f->name] = $row[$f->name];
			}
			$i++;
		}
		return $users;
	}
	
	/* Check User Credentials */
	public function checkCreds($userName,$userPassword) {
		$sql = "SELECT * FROM users WHERE userName='".$userName."' AND userPassword='".$userPassword."'";
		$res = mysqli_query($this->link,$sql);
		if(mysqli_num_rows($res) == 1) {
			$user = array();
			$fields = mysqli_fetch_fields($res);
			while($row = mysqli_fetch_array($res)) {
				foreach($fields as $f) {
					$user[$f->name] = $row[$f->name];
				}
			}
			return $user;
		} else {
			$msg['text'] = 'no dice - try again';
			return $msg;
		}
	}
	
}

//error_reporting(-1);
//header("Content-type: text/plain; charset=utf-8");
if(isset($_GET['function'])) {
	$chimay = new Chimay;
	switch($_GET['function']) {
		case 'listUsers':
			header('Content-Type: application/json');
			if(isset($_GET['userIDs'])) {
				echo(json_encode($chimay->listUsers($_GET['userIDs'])));
			} else {
				echo(json_encode($chimay->listUsers()));
			}
			break;
		case 'listNotes':
			header('Content-Type: application/json');
			if(isset($_GET['type']) && isset($_GET['ID'])) {
				echo(json_encode($chimay->listNotes($_GET['type'],$_GET['ID'])));
			} else {
				echo(json_encode($chimay->listNotes()));
			}
			break;
		case 'listClients':
			header('Content-Type: application/json');
			if(isset($_GET['clientID']) && is_numeric($_GET['clientID'])) {
				echo(json_encode($chimay->listClients(null,$_GET['clientID'])));
			} else if (isset($_GET['clientID']) && $_GET['clientID'] != 'undefined') {
				echo(json_encode($chimay->listClients()));
			} else if(isset($_GET['limit']) && is_numeric($_GET['limit'])){
				echo(json_encode($chimay->listClients($_GET['limit'])));
			} else {
				echo(json_encode($chimay->listClients()));
			}
			break;
		case 'listContacts':
			header('Content-Type: application/json');
			if(isset($_GET['contactID']) && is_numeric($_GET['contactID'])) {
				echo(json_encode($chimay->listContacts($_GET['contactID'])));
			} else if (isset($_GET['contactID']) && $_GET['contactID'] != 'undefined') {
				echo(json_encode($chimay->listContacts()));
			} else {
				echo(json_encode($chimay->listContacts()));
			}
			break;
		case 'saveClient':
			header('Content-Type: application/json');
			if(isset($_GET)) {
				echo(json_encode($chimay->saveClient($_GET)));
			}
			break;
		case 'editClient':
			header('Content-Type: application/json');
			if(isset($_GET)) {
				echo(json_encode($chimay->editClient($_GET['clientID'],$_GET)));
			}
			break;
		case 'saveContact':
			header('Content-Type: application/json');
			if(isset($_GET)) {
				echo(json_encode($chimay->saveContact($_GET)));
			}
			break;
		case 'editContact':
			header('Content-Type: application/json');
			if(isset($_GET)) {
				echo(json_encode($chimay->editContact($_GET['contactID'],$_GET)));
			}
			break;
		case 'checkCreds':
			if(isset($_COOKIE['userName']) && isset($_COOKIE['userPassword'])) {
				$userName = $_COOKIE['userName'];
				$userPassword = $_COOKIE['userPassword'];
			} else if(isset($_GET['userName']) && isset($_GET['userPassword'])) {
				$userName = $_GET['userName'];
				$userPassword = $_GET['userPassword'];
			} else {
				$userName = '';
				$userPassword = '';
			}
			header('Content-Type: application/json');
			echo(json_encode($chimay->checkCreds($userName,$userPassword)));
			break;
		case 'getLatLong':
			header('Content-Type: application/json');
			if(isset($_GET['clientID']) && is_numeric($_GET['clientID'])) {
				echo(json_encode($chimay->getLatLong($_GET['clientID'])));
			}
			break;
		case 'mapPoints':
			header('Content-Type: application/json');
			echo(json_encode($chimay->mapPoints()));
			break;
		case 'listContexts':
			header('Content-Type: application/json');
			echo(json_encode($chimay->listContexts()));
			break;
		case 'getClientContacts':
			header('Content-Type: application/json');
			if(isset($_GET['clientID']) && is_numeric($_GET['clientID'])) {
				echo(json_encode($chimay->getClientContacts($_GET['clientID'])));
			}
			break;
		case 'getContactClients':
			header('Content-Type: application/json');
			if(isset($_GET['contactID']) && is_numeric($_GET['contactID'])) {
				echo(json_encode($chimay->getContactClients($_GET['contactID'])));
			}
			break;
		case 'saveNote':
			header('Content-Type: application/json');
			if(isset($_GET)) {
				echo(json_encode($chimay->saveNote($_GET)));
			}
			break;
		default:
			// do nothing
			echo('shitballs, something went wrong');
			break;
	}
}

?>