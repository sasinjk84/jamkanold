<?
// �α����� ȸ���� ��� ȸ���� �Ǹŵ��ȸ������ ����
// �Ǹŵ���� ���  Y ��ȯ
include_once dirname(__FILE__).'/func.php';
// ���� ȸ�� ���� �Ǻ�
function isSeller(){
	global $_ShopInfo;
	$result = '';
	if(strlen(trim($_ShopInfo->getMemid()))>0 AND isWholesale() == "Y" ){
		//$sql = "SELECT g.group_seller from tblmembergroup g left join tblmember m on m.group_code = g.group_code WHERE m.id='".$_ShopInfo->getMemid()."' limit 1";
		$sql = "SELECT comp_num from tblmember WHERE id='".$_ShopInfo->getMemid()."' AND wholesaletype='Y' limit 1";
		$res = mysql_query($sql,get_db_conn());
		if($res && mysql_num_rows($res)){
			//if(mysql_result($res,0,0) == 'Y') $result = 'Y';
			$comp_num = mysql_result($res,0,0);
			if(strlen(trim($comp_num)) > 9) $result = 'Y';
		}
	}
	return $result;
}

// �׷캰 ī�� ������
function getGroupCardCommi(){
	global $_ShopInfo;
	$result = 0;
	if(strlen(trim($_ShopInfo->getMemid()))>0 AND isSeller() != 'Y' ){
		$sql = "SELECT group_card_commi FROM tblmembergroup g left join tblmember m on m.group_code = g.group_code WHERE m.id='".$_ShopInfo->getMemid()."' limit 1";

		$res = mysql_query($sql,get_db_conn());
		if($res){
			if(mysql_num_rows($res)) $result = mysql_result($res,0,0);
		}
	}
	return $result;
}
?>