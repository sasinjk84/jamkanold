<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

//SNS �α���
@include_once($Dir."lib/sns_init.php");

// ���̹� API ���� ����
$state	= $naver->getConnectState();

// ���̹� �α��� API ���� ���� ���� ���.
$result	= $naver->getUserProfile();
$result	= json_decode($result);
$info	= $result->response;

$id= $naver->getSocialId().$info->id;
// ���̹� �α��� API ���� ����� ���� ������ �ַ�� ȸ�� �������� ��.
//$sql	= sprintf("select * from tblmember where member_out ='N' AND loginType = 'naver' AND id = '%s'", $id);
$sql="select * from tblmember where member_out ='N' AND loginType='naver' AND id LIKE '".$id."%'";
$result=mysql_query($sql,get_db_conn());
$cnt=mysql_num_rows($result);

// �˾��̳� �ƴϳ� ����. 160331 ������ ������ ����.
$opener	= "opener.";
$close	= "self.close();";

if ($state && $cnt) {
	if($row = mysql_fetch_object($result)) {
		$memid		 = $row->id;
		$memname	 = !_empty($row->name) ? $row->name : $row->nickname;
		$mememail	 = $row->email;
		$memgroup	 = $row->group_code;
		$memreserve	 = $row->reserve;

		$authidkey = md5(uniqid(""));

		$_ShopInfo->setMemid($memid);
		$_ShopInfo->setAuthidkey($authidkey);
		$_ShopInfo->setMemgroup($memgroup);
		$_ShopInfo->setMemname($memname);
		$_ShopInfo->setMemreserve($memreserve);
		$_ShopInfo->setMememail($mememail);
		$_ShopInfo->Save();

		$sql = "UPDATE tblmember SET ";
		$sql.= "authidkey		= '".$authidkey."', ";
		$sql.= "ip				= '".getenv("REMOTE_ADDR")."', ";
		$sql.= "logindate		= '".date("YmdHis")."', ";
		$sql.= "logincnt		= logincnt+1 ";
		$sql.= "WHERE id = '".$_ShopInfo->getMemid()."'";
		mysql_query($sql,get_db_conn());

		$loginday = date("Ymd");
		$sql = "SELECT id_list FROM tblshopcountday ";
		$sql.= "WHERE date='".$loginday."'";
		$result = mysql_query($sql,get_db_conn());
		if($row3 = mysql_fetch_object($result)){
			if(!strpos(" ".$row3->id_list,"".$_ShopInfo->getMemid()."")){
				$id_list=$row3->id_list.$_ShopInfo->getMemid()."";
				$sql = "UPDATE tblshopcountday SET id_list='".$id_list."',login_cnt=login_cnt+1 ";
				$sql.= "WHERE date='".$loginday."'";
				mysql_query($sql,get_db_conn());
			}
		} else {
			$id_list="".$_ShopInfo->getMemid()."";
			$sql = "INSERT INTO tblshopcountday (date,count,login_cnt,id_list) VALUES ('".$loginday."',1,1,'".$id_list."')";
			mysql_query($sql,get_db_conn());
		}

		//echo "<script>alert('".$_GET['reffer']."');</script>";
		//exit;

		if (!empty($chUrl)) {
			echo "<script>".$opener."location.href = '{$chUrl}';".$close."</script>";
		} else {
			if($_GET['reffer']=='join'){
				//echo "<script>alert('�̹� ���� �α��ο� ���Ե� ȸ���Դϴ�.');".$opener."location.href = '/';".$close."</script>";
				echo "<script>".$opener."location.href = '/';".$close."</script>";
			}else{
				echo "<script>".$opener."location.href = '/';".$close."</script>";
			}
		}
	}
} else {
	echo '<script>'.$opener.'location.href = "/front/member_join.php?loginType=naver";'.$close.'</script>';
}