<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$_ShopInfo->getPgdata();


define('KCPMOD_REAL',false); // �׽�Ʈ ���� �� ���� �ش� ���� true �� �����Ұ�


$_ShopInfo->getPgdata();

$g_conf_home_dir	= dirname(__FILE__);	// BIN ������ �Է� bin ���� ����
//$g_conf_log_level	= "3";											// ����Ұ�
$g_conf_pa_port		= "8090";										// ��Ʈ��ȣ , ����Ұ�

$pgid_info=GetEscrowType($_data->card_id);
$pg_type=$pgid_info["PG"];


if(KCPMOD_REAL){
	switch(substr($oinfo['paymethod'],0,1)){
		/*
		case "B":
			exit('������ �Ա��� �κ� ��� ����� �ƴմϴ�.');
			break;*/
		case "V":
			$pgid_info=GetEscrowType($_data->trans_id);			
			$pg_type=$pgid_info["PG"];
			$mod_type = 'STPA';
			break;
			/*
		case "O":
			$pgid_info=GetEscrowType($_data->virtual_id);
			$pg_type=$pgid_info["PG"];
			break;
			
		case "Q":
			$pgid_info=GetEscrowType($_data->escrow_id);
			$pg_type=$pgid_info["PG"];
			break;
			*/
		case "C":
		case "P":
			$pgid_info=GetEscrowType($_data->card_id);
			$pg_type=$pgid_info["PG"];		
			$mod_type = 'RN07';			
			break;
			/*
		case "M":
			$pgid_info=GetEscrowType($_data->mobile_id);
			$pg_type=$pgid_info["PG"];
			brak;*/
		default:
			exit('�������� �ʴ� ���� Ÿ�� �Դϴ�.');
	}
	if($pgid_info["PG"] != 'A'){
		exit('�κ� ��Ҵ� ���� KCP��⸸ ���� �մϴ�.');
	}
	
	
	if(empty($pgid_info['ID']) || empty($pgid_info['KEY'])) exit('������ �ùٸ��� �ʽ��ϴ�.');
	
	$g_conf_pa_url		= "paygw.kcp.co.kr";							// real url : paygw.kcp.co.kr , test url : testpaygw.kcp.co.kr
	$g_conf_site_cd  = $pgid_info['ID'];
	$g_conf_site_key = $pgid_info['KEY'];
}else{
	$g_conf_pa_url		= "testpaygw.kcp.co.kr";							// real url : paygw.kcp.co.kr , test url : testpaygw.kcp.co.kr
	$g_conf_site_cd  = "T0000";                     // �� ���� �ݿ��� KCP���� �߱��� site_cd ���
	$g_conf_site_key = "3grptw1.zW0GSo4PQdaGvsF__"; // �� ���� �ݿ��� KCP���� �߱��� site_key ���
	$mod_type = 'RN07';
	$oinfo['tno'] = '20090610121212';
	$mod_desc= '�׽�Ʈ�� �κ� ���';
	//$oinfo['mod_mny'] = '100';
	$oinfo['price'] = '1004';
}


?>