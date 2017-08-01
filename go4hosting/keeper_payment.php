<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EBS Payment</title>
<script type="text/javascript">
	function submitToPaypal() {
		document.Form2.action='https://secure.ebs.in/pg/ma/payment/request';
		document.Form2.submit();
	}
</script>
<style type="text/css">
marquee{
	margin-top:20%;
	font-family:Arial, Helvetica, sans-serif;
	font-size:18px;
	font-weight:bold;
}
</style>
</head>
<body onload="submitToPaypal();">
	<div>
	   <table>
		  <tr>
			<td>
				<MARQUEE>
					Please wait while we direct you to secure payment page	
				</MARQUEE> 
			</td>
		  </tr>
		</table>
	</div>
<?php 
	ini_set('display_errors',1);
	error_reporting(E_ALL);

	$hashData = "6cf89c6e859c40b24975b263c9fe5ee6";
	ksort($_POST);

	foreach ($_POST as $key => $value){
		if (strlen($value) > 0) {
			$hashData .= '|'.$value;
		}
	}

	if (strlen($hashData) > 0) {
		$secure_hash = strtoupper(hash("sha512",$hashData));
	}
?>
<form action="https://secure.ebs.in/pg/ma/payment/request" name="Form2" id="Form2" method="POST">
	<?php foreach ($_POST as $key => $value) { ?>
		<input type="hidden" value="<?php echo $value;?>" name="<?php echo $key;?>"/>
	<?php } ?>
	<input type="hidden" value="<?php echo $secure_hash; ?>" name="secure_hash"/>
</form>