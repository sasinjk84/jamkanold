<?php
/***************************************************************************
���� ���� Ȯ�ο� ó�� Ŭ���� (����ſ�������)

���� : �ش� ��� ����� �ݵ�� bizsiren.com �� �α����ؼ� ����Ȯ���� ������ �� ��û ������ ���� ���� ������ �ش� ���� ������ srvList �� �������־�߸� �մϴ�.
**************************************************************************/

class sci_ipin{
	var $seedFileName = '/SciSecuX'; // ���̺귯�� ���� ��θ� �������� �����ϵ� ��ȣȭ ������ ��ġ ���
	var $seedFile = ''; // ��� ��� ��������� Ŭ���� �ν��Ͻ�ȭ�� �ڵ� ��� ���� �ؼ� ��� �� ( �������� ����)
	var $id = 'OBJ001'; // bizsiren.com �� ��ϵ� �������� Ȯ�� ��ü id
	// [�ʼ�&����] bizsiren.com �� ������ ������ ��ϵ� ��û ��� ������ ��� ������ ���� �迭 ȭ�Ͽ� ����Ͽ��� Ȯ���ϵ��� ��.
	var $srvList = array(
						'yeanchon.objet.co.kr'=>array(
											'/sci/ipin/request.php'=>'004001'
											),
						'yeanchon.com'=>array(
											'/sci/ipin/request.php'=>'005001'
											),
						'www.yeanchon.com'=>array(
											'/sci/ipin/request.php'=>'006001'
											)
						);

	function sci_ipin(){
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
	function _reqInfo(){
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

		/************************************************************************************/
		/* reqNum ���� ���� ����� ��ȣȭ�� ���� SecuKey�� Ȱ�� �ǹǷ� �߿��մϴ�.			*/
		/* reqNum �� ���� Ȯ�� ��û�� �׻� ���ο� ������ �ߺ� ���� �ʰ� ���� �ؾ� �մϴ�.	*/
		/* ��Ű �Ǵ� Session�� ��Ÿ ����� ����ؼ� reqNum ����								*/
		/* ipin_result_seed.php���� ���� �� �� �ֵ��� �ؾ� ��.								*/
		/* ������ ���ؼ� ��Ű�� ����� ���̹Ƿ� ���� �Ͻñ� �ٶ��ϴ�.						*/
		/************************************************************************************/
		//01. reqNum ��Ű ����
		setcookie("REQNUM", $reqNum, time()+600);

		$exVar       = "0000000000000000";        // Ȯ���ӽ� �ʵ��Դϴ�. �������� ������..

		//02. ��ȣȭ �Ķ���� ����
		$enc_reqInfo = $reqNum . "/" . $id . "/" . $srvNo . "/" . $exVar;

		//03. ����Ȯ�� ��û���� 1����ȣȭ
		$enc_reqInfo = exec($this->seedFile." SEED 1 1 $enc_reqInfo ");

		//04. ��û���� ������������ ����
		$hash_reqInfo = exec($this->seedFile." HMAC 1 1 $enc_reqInfo ");    // ��û���� ������������ ����

		//05. ��û���� 2����ȣȭ
		//������ ���� ��Ģ : "��û���� 1�� ��ȣȭ/������������/�Ϻ�ȭ Ȯ�� ����"
		$enc_reqInfo = $enc_reqInfo. "/" .$hash_reqInfo. "/" ."00000000";
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
		$totInfo = split("/", $dec_retInfo);
		$encPara  = $totInfo[0];		//��ȣȭ�� ���� �Ķ����
		$encMsg   = $totInfo[1];		//��ȣȭ�� ���� �Ķ������ Hash��

		//03. ������������ ����
		$hmac_str = exec($this->seedFile." HMAC 1 0 $encPara ");
		if($hmac_str != $encMsg){
			$return['err'] = "���������� �����Դϴ�.!!";
			return $return;
		}
		//04. ����Ȯ��1����ȣȭ�� ��ȣȭ
		$decPara = exec($this->seedFile." SEED 2 0 $iv $encPara ");

		//05. �Ķ���� �и�
		$split_dec_retInfo = split("/", $decPara);
		$return['reqNum'] = $split_dec_retInfo[0];   //��û��ȣ
		$return['vDiscrNo'] = $split_dec_retInfo[1];   //�����ɹ�ȣ
		$return['name'] = $split_dec_retInfo[2];   //����
		$return['result'] = $split_dec_retInfo[3];   //�������
		$return['age'] = $split_dec_retInfo[4];   //���ɴ�
		$return['sex'] = $split_dec_retInfo[5];   //����
		$return['ip'] = $split_dec_retInfo[6];   //Client IP
		$return['authInfo'] = $split_dec_retInfo[7];   //�߱޼�������
		$return['birth'] = $split_dec_retInfo[8];   //�������
		$return['fgn'] = $split_dec_retInfo[9];   //��/�ܱ��α���
		$return['discrHash'] = $split_dec_retInfo[10];  //�ߺ�����Ȯ������
		$return['ciVersion'] = $split_dec_retInfo[11];  //�������� ����
		$return['ciscrHash'] = $split_dec_retInfo[12];  //��������


		$return['discrHash'] = exec($this->seedFile." SEED 2 0 $iv ".$return['discrHash']);    //�ߺ�����Ȯ�������� �������� "/"�� ���ü� �����Ƿ� �ѹ��� ��ȣȭ
		$return['ciscrHash'] = exec($this->seedFile." SEED 2 0 $iv ".$return['ciscrHash']);    //���������� �������� "/"�� ���ü� �����Ƿ� �ѹ��� ��ȣȭ

		return $return;
	}

	function _getSave($reqNum){
		$return = array('err'=>'');
		if(!preg_match('/^[0-9a-zA-Z]{10,}$/',$reqNum)){
			$return['err'] = '�ĺ� ��ȣ ����';
		}else{
			$sql = "select * from sci_ipin_log where reqNum='".$reqNum."' limit 1";

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