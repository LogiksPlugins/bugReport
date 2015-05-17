<?php
if(!defined("ROOT")) exit("Direct Access To This Script Not Allowed");

if(isset($_REQUEST['action'])) {
	$cfg=loadFeature("bugReport");
	$token=$cfg['public_token'];
	$pass=$cfg['private_token'];

	switch ($_REQUEST['action']) {
		case 'debug':
			$lc=new LogiksEncryption($pass);
			$encoded=array("server","session","php","userid","privilegeid","url");
			foreach($_POST as $key => $value) {
				if(in_array($key,$encoded)) {
					$_POST[$key]=base64_encode($lc->decode($value));
				}
			}
			printArray($_POST);
			echo "<script>top.lgksAlert('Thank You.<br/>Bug will be looked into as soon as possible.');</script>";
			break;
		case 'feedback':
			printArray($_POST);
			break;
		default:
			# code...
			break;
	}
}
?>