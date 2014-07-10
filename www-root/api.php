<?php
require_once('config.php');
class Chimay {
	
	var $link;
	
	function __construct() {
		$this->link = mysqli_connect(db_host, db_user, db_pass, db_name, db_port);
	}
	
	////////////
	/* Users */
	////////////
	
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
	
	//////////////
	/* Messages */
	//////////////
	
	/* List Invoices */
	public function listMessages() {
		$messages = array();
		$sql = "SELECT * FROM `messages`,`users` WHERE `messages`.userID = `users`.userID ORDER BY `messages`.messageCreated ASC";
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
	
	/* List Invoice Detail, including row data */
	public function listInvoiceDetail($invoiceID) {
		$invoice = array();
		$sql = "SELECT * FROM `invoices` WHERE `invoices`.invoiceID = ".$invoiceID;
		$res = mysqli_query($this->link,$sql);
		$fields = mysqli_fetch_fields($res);
		$i=0;
		while($row = mysqli_fetch_array($res)) {
			foreach($fields as $f) {
				$invoice[$f->name] = $row[$f->name];
			}
			$i++;
		}
		$rowsql = "SELECT * FROM `invoiceRows` WHERE invoiceID = ".$invoiceID;
		$rowres = mysqli_query($this->link,$rowsql);
		$rowfields = mysqli_fetch_fields($rowres);
		$l=0;
		while($rowrow = mysqli_fetch_array($rowres)) {
			foreach($rowfields as $rf) {
				// keep invoiceID out of adding to the array
				$val = isset($invoice[$rf->name]) ? $invoice[$rf->name] : '';
				if($val != $invoiceID) {
					$invoice[$rf->name][$l] = $rowrow[$rf->name];
				}
			}
			// count number of line breaks in the description
			$breaks = substr_count($invoice['invoiceRowDescription'][$l],"\n") + 1;
			$invoice['invoiceRowDescriptionRows'][$l] = $breaks;
			$l++;
		}
		return $invoice;
	}
	
	/* Process $_GET data into array */
	private function processGET($data) {
		$invoice = array();
		foreach($data as $key => $val) {
			$invoice[$key] = addslashes($val);
		}
		return $invoice;
	}

	/* Save Invoice */
	public function saveInvoice($data) {
		$invoice = $this->processGET($data);
		$userID = '1';
		$sql = "INSERT INTO `invoices` (`userID`,`clientID`,`invoiceDate`,`invoiceDue`,`invoiceTotal`,`invoiceNotes`,`invoiceEstimate`) VALUES ('".$userID."','".$invoice['clientID']."','".$invoice['invoiceDate']."','".$invoice['invoiceDue']."','".$invoice['invoiceTotal']."','".$invoice['invoiceNotes']."','".$invoice['invoiceEstimate']."')";
		$res = mysqli_query($this->link,$sql);
		//$invoice['sql'] = $sql;
		$invoice['invoiceID'] = mysqli_insert_id($this->link);
		$invoice['invoiceStatus'] = "success";
		$i=0;
		$numRows = count($invoice['invoiceRowItem']);
		while($i < $numRows) {
			$rowsql = "INSERT INTO `invoiceROWS` (`invoiceID`,`invoiceRowItem`,`invoiceRowDescription`,`invoiceRowQuantity`,`invoiceRowRate`,`invoiceRowTotal`) VALUES ('".$invoice['invoiceID']."','".$invoice['invoiceRowItem'][$i]."','".$invoice['invoiceRowDescription'][$i]."','".$invoice['invoiceRowQuantity'][$i]."','".$invoice['invoiceRowRate'][$i]."','".$invoice['invoiceRowTotal'][$i]."')";
			$res = mysqli_query($this->link,$rowsql);
			$i++;
		}
		$invoice['invoiceRowsAdded'] = $i;
		return $invoice;
	}
	
	/* "Edit" invoice by deleting old one and adding new one */
	public function editInvoice($invoiceID,$data) {
		$sql = "DELETE FROM `invoiceRows` WHERE invoiceID = ".$invoiceID;
		$res = mysqli_query($this->link,$sql);
		$sql = "DELETE FROM `invoices` WHERE invoiceID = ".$invoiceID;
		$res = mysqli_query($this->link,$sql);
		$editData = $this->saveInvoice($data);
		return $editData;
	}
	
	/* Mark invoice as paid */
	public function invoicePaid($invoiceID) {
		$data = array();
		$sql = "UPDATE `invoices` SET invoicePaid = 1 WHERE invoiceID = ".$invoiceID;
		$res = mysqli_query($this->link,$sql);
		$data['result'] = 'success';
		return $data;
	}

	/* Output invoice as a PDF */
	public function pdfInvoice($invoiceID) {
		require_once('api-pdf.php');
		// http://www.fpdf.org/
		$invoiceData = $this->listInvoiceDetail($invoiceID);
		$clientData = $this->listClients($invoiceData['clientID']);
		//print_r($invoiceData);

		$pdf = new PDF('P','pt');
		$pdf->SetAuthor('After Hours Agency');
		$invoiceTitle = 'I'.$invoiceID.'-'.$clientData['clientShortName'];
		$pdf->SetTitle($invoiceTitle);
		$pdf->SetDrawColor(80,80,80);
		$pdf->SetTextColor(0,0,0);
		$pdf->SetMargins(50,30);
		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial','',12);

		// Address
		$pdf->Cell(300,0,$pdf->WriteHTML(billingAddress),0,0);

		// Invoice Number
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(0,0,'Invoice #'.$invoiceData['invoiceID'],0,1,'R');
		$pdf->Ln(24);

		// Client Information
		$pdf->SetFont('Arial','B',14);
		$clientData = $this->listClients($invoiceData['clientID']);
		$pdf->Cell(0,24,$clientData[0]['clientName'],'T',1);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,12,'ATTN: '.$clientData[0]['clientContact'],0,1);
		$pdf->Cell(0,12,$clientData[0]['clientAddress1'],0,1);
		if($clientData[0]['clientAddress2'] != '') {
			$pdf->Cell(0,12,$clientData[0]['clientAddress2'],0,1);
		}
		$pdf->Cell(0,12,$clientData[0]['clientCity'].', '.$clientData[0]['clientState'].' '.$clientData[0]['clientZip'],0,1);
		$pdf->Ln(12);

		// Dates
		$pdf->Cell(0,12,'Invoice Date: '.date('n/j/Y',strtotime($invoiceData['invoiceDate'])),0,1);
		$pdf->Cell(0,12,'Due: '.date('n/j/Y',strtotime($invoiceData['invoiceDue'])),0,1);
		$pdf->Ln(24);
		// Line: right margin, from top, width, from top
		//$pdf->Line(40,180,570,180);

		// Row Headers
		$pdf->SetFont('Arial','B',12);
		$pdf->SetFillColor(0,0,0);
		$pdf->SetTextColor(255,255,255);
		$rowHeaders = array('Task','Description','Hours','Rate','Subtotal');
		$rowWidths = array(100,200,50,50,100);
		$i=0;
		foreach($rowHeaders as $r) {
			$pdf->Cell($rowWidths[$i],24,$r,0,0,'L',true);
			$i++;
		}
		$pdf->Ln(30);

		// Invoice Rows
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0,0,0);
		$i=0;
		$numInvoiceRows = count($invoiceData['invoiceRowID']);
		while ($i<$numInvoiceRows) {
			$rowWidthCounter = 0;
			$pdf->Cell($rowWidths[$rowWidthCounter],12,$invoiceData['invoiceRowItem'][$i],0,0);
			$rowWidthCounter++;
			// store Y position so we can put the next row back at the right place
			$currentY = $pdf->GetY();
			$pdf->MultiCell($rowWidths[$rowWidthCounter],12,$invoiceData['invoiceRowDescription'][$i],0,'L');
			// store the Y position at the bottom of the description so we can offset the next row
			$bottomY = $pdf->GetY();
			$pdf->SetLeftMargin(350);
			$pdf->SetY($currentY);
			$rowWidthCounter++;
			$pdf->Cell($rowWidths[$rowWidthCounter],12,$invoiceData['invoiceRowQuantity'][$i],0,0);
			$rowWidthCounter++;
			$pdf->Cell($rowWidths[$rowWidthCounter],12,'$'.$invoiceData['invoiceRowRate'][$i],0,0);
			$rowWidthCounter++;
			$pdf->Cell($rowWidths[$rowWidthCounter],12,'$'.$invoiceData['invoiceRowTotal'][$i],0,0);
			$rowWidthCounter++;
			$pdf->SetY($bottomY);
			$pdf->SetLeftMargin(50);
			$pdf->Ln(12);
			$i++;
		}

		// Total
		$pdf->SetFillColor(0,0,0);
		$pdf->SetTextColor(230,131,1);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(0,24,'Total: $'.$invoiceData['invoiceTotal'],0,0,'R',true);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0,0,0);
		$pdf->Ln(24);

		// Invoice Notes
		$pdf->Ln(24);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(0,12,'Invoice Notes','B',1);
		$pdf->Ln(12);
		$pdf->SetFont('Arial','',9);
		$pdf->MultiCell(0,12,$invoiceData['invoiceNotes'],0,'L');
		
		// Output to Browser
		$pdf->Output();
	}
	
	//////////////
	/* Clients */
	//////////////
	
	/* List Clients */
	public function listClients($clientID=null) {
		$clients = array();
		if($clientID != null) {
			$sql = "SELECT * FROM `clients` WHERE clientID =".$clientID;
		} else {
			$sql = "SELECT * FROM `clients`";
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
		$sql = "INSERT INTO `clients` (`clientName`,`clientAddress1`,`clientAddress2`,`clientCity`,`clientState`,`clientZip`) VALUES ('".$client['clientName']."','".$client['clientAddress1']."','".$client['clientAddress2']."','".$client['clientCity']."','".$client['clientState']."','".$client['clientZip']."')";
		//$sql = mysqli_real_escape_string($this->link,$sql);
		$res = mysqli_query($this->link,$sql);
		$client['clientID'] = mysqli_insert_id($this->link);
		$client['clientStatus'] = "success";
		return $client;
	}
	
	/* Edit Client */
	public function editClient($clientID,$data) {
		$client = $this->processGET($data);
		$sql = "UPDATE `clients` SET `clientName` = '".$client['clientName']."' , `clientAddress1` = '".$client['clientAddress1']."' , `clientAddress2` = '".$client['clientAddress2']."' , `clientCity` = '".$client['clientCity']."' , `clientState` = '".$client['clientState']."' , `clientZip` = '".$client['clientZip']."' WHERE clientID = ".$clientID;
		//$sql = mysqli_real_escape_string($this->link,$sql);
		$res = mysqli_query($this->link,$sql);
		$client['sql'] = $sql;
		$client['clientStatus'] = "success";
		return $client;
	}
	
	/////////////
	/* Reports */
	/////////////
	
	/* Get percentages of paid and unpaid */
	public function paidData() {
		$data = array();
		$sqlPaid[] = "SELECT SUM(`invoiceTotal`) as `totalRow` FROM `invoices` WHERE invoicePaid = 1 AND `invoiceEstimate` = 0";
		$sqlPaid[] = "SELECT SUM(`invoiceTotal`) as `totalRow` FROM `invoices` WHERE invoicePaid = 0 AND `invoiceEstimate` = 0";
		
		$names = array('Paid','Unpaid');

		// setup colors and randomize
		$colors = unserialize(chartColors);
		shuffle($colors);

		for($i=0;$i<2;$i++) {
			$res[$i] = mysqli_query($this->link,$sqlPaid[$i]);
			$row = mysqli_fetch_array($res[$i]);
			$data[$i]['name'] = $names[$i];
			$data[$i]['value'] = intval($row['totalRow']);
			$data[$i]['color'] = $colors[$i];
		}
		
		return $data;
	}

	/* Get ratio of clients based on payments */
	public function clientPercentage() {
		$data = array();
		$clientSql = "SELECT `clientID`, `clientName` FROM `clients`";
		$clientRes = mysqli_query($this->link,$clientSql);
		
		// setup colors and randomize
		$colors = unserialize(chartColors);
		shuffle($colors);

		$i=0;
		while ($row = mysqli_fetch_assoc($clientRes)) {
			$invoiceSql = "SELECT SUM(`invoiceTotal`) as `totalRow` FROM `invoices` WHERE clientID = ".$row['clientID']." AND `invoiceEstimate` = 0";
			$invoiceRes = mysqli_query($this->link,$invoiceSql);
			$invoiceRow = mysqli_fetch_array($invoiceRes);
			$data[$i]['name'] = $row['clientName'];
			$data[$i]['value'] = intval($invoiceRow['totalRow']);
			$data[$i]['color'] = $colors[$i];
			mysqli_free_result($invoiceRes);
			$i++;
		}
		
		return $data;
	}

	/* Get Estimated vs. Invoiced dollar amount */
	public function estimateAndInvoice() {
		$data = array();
		$sql[] = "SELECT SUM(`invoiceTotal`) as `totalRow` FROM `invoices` WHERE `invoiceEstimate` = 0";
		$sql[] = "SELECT SUM(`invoiceTotal`) as `totalRow` FROM `invoices` WHERE `invoiceEstimate` = 1";

		$names = array('Invoiced','Estimated');

		// setup colors and randomize
		$colors = unserialize(chartColors);
		shuffle($colors);

		for($i=0;$i<2;$i++) {
			$res[$i] = mysqli_query($this->link,$sql[$i]);
			$row = mysqli_fetch_array($res[$i]);
			$data[$i]['name'] = $names[$i];
			$data[$i]['value'] = intval($row['totalRow']);
			$data[$i]['color'] = $colors[$i];
		}

		return $data;
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
		case 'listMessages':
			header('Content-Type: application/json');
			echo(json_encode($chimay->listMessages()));
			break;
		case 'listClients':
			header('Content-Type: application/json');
			if(isset($_GET['clientID']) && is_numeric($_GET['clientID'])) {
				echo(json_encode($chimay->listClients($_GET['clientID'])));
			} else if (isset($_GET['clientID']) && $_GET['clientID'] != 'undefined') {
				echo(json_encode($chimay->listClients()));
			} else {
				echo(json_encode($chimay->listClients()));
			}
			break;
		case 'saveInvoice':
			header('Content-Type: application/json');
			if(isset($_GET)) {
				echo(json_encode($chimay->saveInvoice($_GET)));
			}
			break;
		case 'editInvoice':
			header('Content-Type: application/json');
			if(isset($_GET)) {
				echo(json_encode($chimay->editInvoice($_GET['invoiceID'],$_GET)));
			}
			break;
		case 'listInvoiceDetail':
			if(isset($_GET['invoiceID']) && is_numeric($_GET['invoiceID'])) {
				header('Content-Type: application/json');
				echo(json_encode($chimay->listInvoiceDetail($_GET['invoiceID'])));
			}
			break;
		
		case 'invoicePaid':
			header("Content-type: text/plain; charset=utf-8");
			if(isset($_GET['invoiceID']) && is_numeric($_GET['invoiceID'])) {
				echo(json_encode($chimay->invoicePaid($_GET['invoiceID'])));
			}
			break;
		case 'pdfInvoice':
			if(isset($_GET['invoiceID']) && is_numeric($_GET['invoiceID'])) {
				$chimay->pdfInvoice($_GET['invoiceID']);
			}
		case 'paidData':
			header('Content-Type: application/json');
			echo(json_encode($chimay->paidData()));
			break;
		case 'clientPercentage':
			header('Content-Type: application/json');
			echo(json_encode($chimay->clientPercentage()));
			break;
		case 'estimateAndInvoice':
			header('Content-Type: application/json');
			echo(json_encode($chimay->estimateAndInvoice()));
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
		/*
		case 'search':
			if(isset($_GET['string']) && $_GET['string'] != '') {
				header('Content-Type: application/json');
				echo(json_encode($chard->listSongs($_GET['string'])));
			}
			break;
		*/
		default:
			// do nothing
			echo('shitballs, something went wrong');
			break;
	}
}

/* Usage
	$chard = new Chardonnay;
	print_r($chard->getSong('506'));
	print_r($chard->listAlbums());
*/

?>