<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$email=urldecode($_POST["email"]);

if (trim($email)=='') {
	echo "110";
} else if(!preg_match("/([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/",$email)){
	echo "120";
} else {
	$sql = "SELECT id FROM tblmember WHERE email='".$email."' ";
	$result = mysql_query($sql,get_db_conn());

	if ($row=mysql_fetch_object($result)) {
		$sql2 = "SELECT id FROM tblmember WHERE id='".$email."' and member_out ='N' AND loginType = '' AND cert_key = '' ";
		$result3 = mysql_query($sql2,get_db_conn());
		if($row3=mysql_fetch_object($result3)) {
			echo "001";
		}else{
			echo "130";
		}
	} else {
		$sql = "SELECT id FROM tblmemberout WHERE id='".$email."' ";
		$result2 = mysql_query($sql,get_db_conn());
		if($row2=mysql_fetch_object($result2)) {
			echo "130";
		} else {
			echo "000";
		}
		mysql_free_result($result2);
	}
	mysql_free_result($result);
}

?>