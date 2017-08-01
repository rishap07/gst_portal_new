<?php
	$myfile = fopen(date('YmdHis').".txt", "w");
	$txt = json_encode($_POST);
	fwrite($myfile, $txt);
	fclose($myfile);
?>
<html>
	<head>
		<title>E-Billing Solutions Pvt Ltd - Payment Page</title>
		<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
		<script type="text/javascript">
			function submitToPaypal() {
				document.Form2.action='https://www.gstkeeper.com/portal/?page=payment_response';
				document.Form2.submit();
			}
		</script>
	</head>

	<body onload="submitToPaypal();">
		<form name="Form2" id="Form2" action="https://www.gstkeeper.com/portal/?page=payment_response" method="post">
			<div>
			   <table>
				  <tr>
					<td>
						<MARQUEE>
							Please wait while we direct you to GSTKeeper	
						</MARQUEE>
					</td>
				  </tr>
				</table>
			</div>
			<?php foreach( $_REQUEST as $key => $value) { ?>
				<input type='hidden' name='<?php echo $key;?>' value='<?php echo $value;?>'>
			<?php } ?>
		</form>
	</body>
</html>