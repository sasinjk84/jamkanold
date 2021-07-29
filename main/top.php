<?
if(strlen($Dir)==0) {
	$Dir="../";
}
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");



if ((strlen($_REQUEST["id"])>0 && strlen($_REQUEST["passwd"])>0) || $_REQUEST["type"]=="logout" || $_REQUEST["type"]=="exit") {
	include($Dir."lib/loginprocess.php");
	exit;
}

?>

<html>
	<head>
	</head>
<body onload="<? echo $onload ?>">
<?if($_data->align_type=="Y") echo "<center>";?>
</body>
</html>
