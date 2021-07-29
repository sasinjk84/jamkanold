<?
	header ("Cache-Control : no-cache");
	header ("Cache-Control : post-check=0 pre-check=0");
	header ("Pragma:no-cache");

	//$enc_retInfo = $_REQUEST["retInfo"];
	$enc_retInfo = $_GET["retInfo"];

	if($enc_retInfo == ""){
		$enc_retInfo = $_POST["retInfo"];
	}
	//echo $_COOKIE["REQNUM"];exit;

	$param = "?retInfo=$enc_retInfo";

	echo "<br />request : <br />";
	print_r($_REQUEST);

	echo "<br />post : <br />";
	print_r($_POST);

	echo "<br />get : <br />";
	print_r($_GET);
?>

<html>
<head>
<script language="JavaScript">
	function end(){
		location.href = 'http://beta.jamkan.com/Siren24/pcc_V3_result_seed.php' + '<?=$param?>';
	}
</script>

</head>
<body onload="javascript:end()">
</body>
</html>