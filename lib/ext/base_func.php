<?php
include_once dirname(__FILE__).'/func.php';
// �ù�� ������ �ڵ� �� Ű�� ������ ���� �迭�� ��ȯ
// ���� �Ķ���� $itemisArray �� true �� ��� �� �迭 ��Ҵ� �迭�� ��ȯ�ǰ� ���̿��� ���� ��ü�� ��ȯ
function getDeliCompany($itemisArray=false){	
	$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
	$result=mysql_query($sql,get_db_conn());
	$delicomlist=array();
	if(mysql_num_rows($result) > 0){
		if($itemisArray === true){
			while($row=mysql_fetch_array($result)) $delicomlist[$row->code]=$row;
		}else{
			while($row=mysql_fetch_object($result)) $delicomlist[$row->code]=$row;
		}
	}
	return $delicomlist;
}
?>