<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}

if($_data->reserve_maxuse<0) {
	echo "<html><head><title></title></head><body onload=\"alert('�� ���θ������� ������ ����� �������� �ʽ��ϴ�.');location.href='".$Dir.FrontDir."mypage.php'\"></body></html>";exit;
}

$id = $name = $reserve = "";
$memberSQL = "SELECT id, name, reserve FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
if(false !== $memberRes = mysql_query($memberSQL, get_db_conn())){
	$memberrowcount = mysql_num_rows($memberRes);
	if($memberrowcount >0){
		$id = mysql_result($memberRes,0,0);
		$name = mysql_result($memberRes,0,1);
		$reserve = mysql_result($memberRes,0,2);
	}else{
		echo '<script>alert("ȸ�������� �������� �ʽ��ϴ�.");location.replace("'.$_SERVER['PHP_SELF'].'?type=logout");</script>';exit;
	}
	mysql_free_result($memberRes);
}else{
	echo '<script>alert("�������� ������ �ƴմϴ�.");location.replace("/main/main.php");</script>';exit;
}
$mode = !_empty($_POST['type'])? trim($_POST['type']):"";//
if(strlen($mode)>0 && $mode =="auth" && strlen($id)>0){
	$authcode = !_empty($_POST['authcode'])? trim($_POST['authcode']):"";
	$x = !_empty($_POST['x'])? trim($_POST['x']):""; // ���̴°�??
	$x = !_empty($_POST['y'])? trim($_POST['y']):""; // ���̴°�??
	$tempautchcode = explode('-',$authcode);
	$authcode1=$authcode2="";
	$authcode1 = trim($tempautchcode[0]);
	$authcode2 = trim($tempautchcode[1]);

	if((strlen($authcode1)<=0) && strlen($authcode2)<=0){
		echo '<script>alert("������ �Ѿ���� ���߽��ϴ�.");location.replace("'.$_SERVER['PHP_SELF'].'");</script>';exit;
	}
	$authSQL="SELECT * FROM tblgift_info WHERE authcode1 = '".$authcode1."' AND authcode2 ='".$authcode2."' " ;
	if(false !== $authRes = mysql_query($authSQL,get_db_conn())){
		$authrowcount = mysql_num_rows($authRes);
		$usetoid=$giftprice=$ordercode=$uid=$gifttoid="";
		if($authrowcount>0){
			$authrow = mysql_fetch_assoc($authRes);
			$usetoid = trim($authrow['use_id']);
			$giftprice = abs(trim($authrow['price']));
			$uid = trim($authrow['uid']);
			$gifttoid=trim($authrow['send_id']);
			$ordercode = trim($authrow['ordercode']);

			if(strlen($usetoid)>0){
				echo '<script>alert("�̹� ���� ��ǰ���Դϴ�.");location.replace("'.$_SERVER['PHP_SELF'].'");</script>';exit;
			}else{

				$givereserveSQL = "UPDATE tblmember SET reserve = reserve + ".$giftprice." ";
				$givereserveSQL.= "WHERE id='".$id."' ";
				if(false !== mysql_query($givereserveSQL,get_db_conn())){
					$givegiftSQL = "UPDATE tblgift_info SET use_id = '".$id."', use_date='".time()."', status='B' ";
					$givegiftSQL.= "WHERE uid='".$uid."' AND authcode1 = '".$authcode1."' AND authcode2='".$authcode2."' AND send_id = '".$gifttoid."' ";
					if(false !== mysql_query($givegiftSQL,get_db_conn())){
						$reservecontent = "������ǰ�� ������ ��ȯ(".$authcode1."-".$authcode2.")";
						$reseveSQL = "INSERT tblreserve SET ";
						$reseveSQL.= "id			= '".$id."', ";
						$reseveSQL.= "reserve		= '".$giftprice."' ,";
						$reseveSQL.= "reserve_yn	= 'Y', ";
						$reseveSQL.= "content		= '".$reservecontent."', ";
						//$reseveSQL.= "orderdata	= '".$row2->ordercode."=".$row2->price."', ";
						$reseveSQL.= "date		= '".date("YmdHis")."' ";
						@mysql_query($reseveSQL,get_db_conn());

						$ordercodeSQL="UPDATE tblorderinfo SET deli_gbn='Y' WHERE ordercode='".$ordercode."' ";
						@mysql_query($orderSQL,get_db_conn());
						$orderproductSQL="UPDATE tblorderproduct SET deli_gbn='Y' WHERE ordercode='".$ordercode."' ";
						@mysql_query($orderproductSQL,get_db_conn());

						echo '<script>alert("��ǰ���� ���� �����Ǿ� ���������� ��ȯ �Ǿ����ϴ�.");self.close();</script>';exit;
					}
				}
			}
		}else{
			echo '<script>alert("��ȿ���� ���� ��ǰ���Դϴ�.");location.replace("'.$_SERVER['PHP_SELF'].'");</script>';exit;
		}
	}
}
?>
<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?> - ��ǰ�� ���</TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>
</HEAD>
<SCRIPT LANGUAGE="JavaScript">
<!--
function ckForm(){
	f = document.form1;
	if(!f.authcode.value) {
		alert('������ȣ�� �Է� �Ͻñ� �ٶ��ϴ�');
		f.authcode.focus();
		return false;
	}
	f.submit();
}
//-->
</SCRIPT>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

	<form name="form1" method="post" action="<?=$_SERVER[PHP_SELF]?>" onsubmit="return ckForm()">
	<input type="hidden" name="type" value="auth">
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td colspan="2"><img src="/images/common/giftcard_title.gif" alt="��ǰ�� ���" /></td>
		</tr>
		<tr>
			<td align="right" style="height:28px;"><img src="/images/common/giftcard_text.gif" alt="��ǰ�� ��ȣ :" /></td>
			<td><input type="text" name="authcode" size="20"></td>
		</tr>
		<tr>
			<td colspan="2" height="40" style="text-align:center;">
				<input type="image" src="/images/common/insert_icon.gif" />
				<a href="javascript:close();"><img src="/images/common/bigview_btnclose.gif" border="0"></a>
			</td>
		</tr>
	</table>
	</form>

<?=$onload?>

</BODY>
</HTML>