<?php
/***************************************************************************
���� ���� Ȯ�ο� ó�� Ŭ���� (����ſ�������)

���� : �ش� ��� ����� �ݵ�� bizsiren.com �� �α����ؼ� ����Ȯ���� ������ �� ��û ������ ���� ���� ������ �ش� ���� ������ srvList �� �������־�߸� �մϴ�.
��� ���� ���� ������  /sci/pcc/result.php ���� ��û ������  �Ǵ� �Ķ���ͺ�(addVar �� ����) ó���� �ۼ����ּž� �մϴ�.
**************************************************************************/

class sci_pcc{
	var $seedFileName = '/SciSecuX'; // ���̺귯�� ���� ��θ� �������� �����ϵ� ��ȣȭ ������ ��ġ ���
	var $seedFile = ''; // ��� ��� ��������� Ŭ���� �ν��Ͻ�ȭ�� �ڵ� ��� ���� �ؼ� ��� �� ( �������� ����)
	var $id = 'SOBJ001'; // bizsiren.com �� ��ϵ� �������� Ȯ�� ��ü id
	// [�ʼ�&����] bizsiren.com �� ������ ������ ��ϵ� ��û ��� ������ ��� ������ ���� �迭 ȭ�Ͽ� ����Ͽ��� Ȯ���ϵ��� ��.
	var $srvList = array(
						'yeanchon.objet.co.kr'=>array(
							'/front/member_new/member_agree.php'=>'004001',
							'/sci/pcc/request.php'=>'004002'
						),
						'yeanchon.com'=>array(
							'/front/member_new/member_agree.php'=>'005001',
							'/sci/pcc/request.php'=>'005002'
						),
						'www.yeanchon.com'=>array(
							'/front/member_new/member_agree.php'=>'006001',
							'/sci/pcc/request.php'=>'006002'
						)
	);

	function sci_pcc(){
		$this->__construct();
	}

	function __construct(){
		$this->seedFile = dirname(__FILE__).$this->seedFileName;
	}


	function _srvNo(){
		$srvNo = '';
		$listarr = $this->srvList[$_SERVER['HTTP_HOST']];
		if(is_array($listarr) && count($listarr) >0 && isset($listarr[$_SERVER['PHP_SELF']]) && !empty($listarr[$_SERVER['PHP_SELF']])) $srvNo = $listarr[$_SERVER['PHP_SELF']];

		return $srvNo;
	}

	// ����Ȯ�� ��û�� ������ ��ȣȭ �� ��ȯ
	// �߰� �Ķ���Ͱ� ���� ��� addVar �� �Է°����� ����
	function _reqInfo($addVar=''){
		$CurTime = date('YmdHis');  //���� �ð� ���ϱ�
		//6�ڸ� ������ ����
		$RandNo = rand(100000, 999999);

		$id = $this->id;
		$srvNo = $this->_srvNo();
		if(!preg_match('/^[0-9]{6}$/',$srvNo)){ // ���� ��ȣ�� ���Ŀ� ���� �ʤ��� ��� ���� ó��
			//echo '���� Ȯ�ο� ��û ���� ��ȣ�� �ùٸ��� �ʽ��ϴ�. ���α׷����� ������ ���� �Ͻñ� �ٶ��ϴ�.'.$srvNo;
			return '';
		}

		$reqNum = $CurTime.$RandNo;
		$certDate = $CurTime;
		$certGb = 'H'; // �޴���
		//$addVar = ''; // �߰� �Ķ����


		/************************************************************************************/
		/* reqNum ���� ���� ����� ��ȣȭ�� ���� SecuKey�� Ȱ�� �ǹǷ� �߿��մϴ�.			*/
		/* reqNum �� ���� Ȯ�� ��û�� �׻� ���ο� ������ �ߺ� ���� �ʰ� ���� �ؾ� �մϴ�.	*/
		/* ��Ű �Ǵ� Session�� ��Ÿ ����� ����ؼ� reqNum ����								*/
		/* vname_result_seed.php���� ���� �� �� �ֵ��� �ؾ� ��.								*/
		/* ������ ���ؼ� ��Ű�� ����� ���̹Ƿ� ���� �Ͻñ� �ٶ��ϴ�.						*/
		/************************************************************************************/
		//01. reqNum ��Ű ����
		setcookie("REQNUM", $reqNum, time()+600);

		$exVar       = "0000000000000000";        // Ȯ���ӽ� �ʵ��Դϴ�. �������� ������..

		//02. ��ȣȭ �Ķ���� ����
		$reqInfo = $id . "^" . $srvNo . "^" . $reqNum . "^" . $certDate . "^" . $certGb . "^" . $addVar . "^" . $exVar;
		//03. ����Ȯ�� ��û���� 1����ȣȭ
		$iv = "";
		$enc_reqInfo = exec($this->seedFile." SEED 1 1 $reqInfo ");
		//04. ��û���� ������������ ����
		$hmac_str = exec($this->seedFile." HMAC 1 1 $enc_reqInfo ");

		//05. ��û���� 2����ȣȭ
		//������ ���� ��Ģ : "��û���� 1�� ��ȣȭ^������������^�Ϻ�ȭ Ȯ�� ����"
		$enc_reqInfo = $enc_reqInfo. "^" .$hmac_str. "^" ."0000000000000000";
		$enc_reqInfo = exec($this->seedFile." SEED 1 1 $enc_reqInfo ");
		return $enc_reqInfo;
	}

	// ����Ȯ�� ��� �� ���ڵ� �ؼ� ���� �迭�� ��ȯ
	function _retInfo($retInfo){
		$return = array(); // ��� ��ȯ�� ���� �迭 ó�� - ���� ���� ���� err �÷��� �޽��� �־ ��ȯ
		/************************************************************************************/
		/* - sample ���������� ��û �� ��Ű�� ������ Reqnum���� �����ͼ� IV���� ����   	    */
		/* - ��Ű ����ð� ��� �� ���ó�� ����										    */
		/************************************************************************************/
		//01. ��Ű�� Ȯ��
		$iv = "";
		if(strlen(trim($retInfo)) <1){
			$return['err'] = "��� ���� �����ϴ�.";
			return $return;
		}

		if (isset($_COOKIE["REQNUM"])) {
			$iv = $_COOKIE["REQNUM"];
			setcookie("REQNUM", "", time()-600); //��Ű ����
		}else{
			$return['err'] = "������ ����Ǿ����ϴ�.!!";
			return $return;
		}
		//02. ��û��� ��ȣȭ
		$dec_retInfo = exec($this->seedFile." SEED 2 0 $iv $retInfo ");
		//������ ���� : "����Ȯ��1����ȣȭ��/������������/�Ϻ�ȭȮ�庯��"
		$totInfo = split("\\^", $dec_retInfo);
		$encPara  = $totInfo[0];		//����Ȯ��1����ȣȭ��
		$encMsg   = $totInfo[1];		//��ȣȭ�� ���� �Ķ������ ������������

		//03. ������������ ����
		$hmac_str = exec($this->seedFile." HMAC 1 0 $encPara ");
		if($hmac_str != $encMsg){
			$return['err'] = "���������� �����Դϴ�.!!";
			return $return;
		}
		//04. ����Ȯ��1����ȣȭ�� ��ȣȭ
		$decPara = exec($this->seedFile." SEED 2 0 $iv $encPara ");
		//05. �Ķ���� �и�
		$split_dec_retInfo = split("\\^", $decPara);

		$return['name']	= $split_dec_retInfo[0];		//����
		$return['birYMD'] = $split_dec_retInfo[1];		//����
		$return['sex'] = $split_dec_retInfo[2];		//�������
		$return['fgnGbn'] = $split_dec_retInfo[3];		//���ܱ��� ���а�
		$return['di'] = $split_dec_retInfo[4];		//DI
		$return['ci1'] = $split_dec_retInfo[5];		//CI1
		$return['ci2'] = $split_dec_retInfo[6];		//CI2
		$return['civersion'] = $split_dec_retInfo[7];		//CI Version
		$return['reqNum'] = $split_dec_retInfo[8];		//��û��ȣ
		$return['result'] = $split_dec_retInfo[9];		//����Ȯ�� ��� (Y/N)
		$return['certGb'] = $split_dec_retInfo[10];		//��������
		$return['cellNo'] = $split_dec_retInfo[11];		//�ڵ��� ��ȣ
		$return['cellCorp'] = $split_dec_retInfo[12];		//�̵���Ż�
		$return['certDate'] = $split_dec_retInfo[13];		//�����ð�
		$return['addVar'] = $split_dec_retInfo[14];		//�߰� �Ķ����

		//���� �ʵ�
		$return['ext1'] = $split_dec_retInfo[15];
		$return['ext2'] = $split_dec_retInfo[16];
		$return['ext3'] = $split_dec_retInfo[17];
		$return['ext4'] = $split_dec_retInfo[18];
		$return['ext5'] = $split_dec_retInfo[19];
		$return['err'] = '';
		return $return;
	}

	function _getSave($reqNum){
		$return = array('err'=>'');
		if(!preg_match('/^[0-9a-zA-Z]{10,}$/',$reqNum)){
			$return['err'] = '�ĺ� ��ȣ ����';
		}else{
			$sql = "select * from sci_pcc_log where reqNum='".$reqNum."' limit 1";

			if(false === $res = mysql_query($sql,get_db_conn())) $return['err'] = mysql_error();
			else{
				if(mysql_num_rows($res) < 1) $return['err'] = '��û ������ �ش��ϴ� ��� ���� �����ϴ�.';
				else{
					$return = mysql_fetch_assoc($res);
				}
			}
		}
		return $return;
	}
}

?>