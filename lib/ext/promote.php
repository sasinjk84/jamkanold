<?


	/**
		SNS ȫ�� ������
	*/


	// �Խ��� SNS ȫ�� URL ���� ������ ó�� ======================================================
	function snsPromoteBoard( $bcmt ){

		$reteun = array();

		$sql = "SELECT board, num, id, chk FROM tblsnsboard WHERE code='".$bcmt."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$board=$row->board;
			$num=$row->num;
			$id=$row->id;
			$chk=$row->chk;
			$sql = "SELECT title, content FROM tblboard WHERE num='".$num."' ";
			$result2=mysql_query($sql,get_db_conn());
			if($row2=mysql_fetch_object($result2)) {
				$return['fbName'] = strip_tags($row2->title);
				$return['fbDesc'] = substr(strip_tags($row2->content),0, 300);
				$return['mainurl'] = BoardDir."board.php?pagetype=view&num=".$num."&board=".$board;

				// ������ ó��
				if( ( $id != NULL OR $id != "" ) AND $chk == 0 ) {
					$extra_conf_sql = "SELECT `value` FROM `extra_conf` WHERE `type` = 'tblshopinfo' AND `name` = 'snsBoardReserve' ";
					$extra_conf_res = mysql_query($extra_conf_sql,get_db_conn());
					if ($extra_conf_row=mysql_fetch_object($extra_conf_res)) {
						$snsBoardReserve=$extra_conf_row->value;
						SetReserve($id,$snsBoardReserve,"�Խ��� SNS ȫ�������� [".$bcmt."]");
						mysql_query("UPDATE `tblsnsboard` SET `chk`=1 WHERE code='".$bcmt."' ",get_db_conn());
					}
					mysql_free_result($extra_conf_res);
				}

				$return['err'] = "no";

			}else{
				$return['err'] = "noBoard";
			}
		}else{
			$return['err'] = "noHonbo";
		}

		return $return;

	}



	// ��ǰ SNS ȫ�� URL �� ���ٽ� ó�� =========================================================


	// ȫ�� ������ ���� ���
	function snsPromoteAccess ( $pk ) {

		global $_ShopInfo;

		$return['access'] = false;
		$return['pcode'] = "";
		$pkInfo = snsPromote_pkInfo($pk);

		if( $pkInfo['cnt'] > 0 ) { // ȫ�������� �ִٸ�

			$return['access'] = true;
			$return['pcode'] = $pkInfo['pcode'];

			// ����Ű (���� �������ִ� ����ڴ� ����Ű ��� )
			if( strlen($_ShopInfo->authkey) == 0 ){
				$_ShopInfo->authkey = md5(uniqid(""));
				$_ShopInfo->Save();
			}

			$acsInfo = snsPromote_acsInfo( $_ShopInfo->authkey );

			$logSQL = "";
			if( empty($acsInfo['idx']) ){
				$logSQL = "INSERT `tblsnsproductLog` SET `code` = '".$pkInfo['code']."', `authkey` = '".$_ShopInfo->authkey."', `accessTime` = NOW() ; ";
			} else{
				if( strlen($_ShopInfo->getMemid()) > 0 ){
					$logSQL = "UPDATE `tblsnsproductLog` SET `memid` = '".$_ShopInfo->getMemid()."' WHERE `code` = '".$pkInfo['code']."' AND `authkey` = '".$_ShopInfo->authkey."' LIMIT 1 ; ";
				}
			}
			mysql_query($logSQL, get_db_conn());

			// �α��ε� ȸ�� ���� ������Ʈ
			snsPromoteAccessLogin( $_ShopInfo->authkey, $_ShopInfo->getMemid() );

			//���� ī��Ʈ (ȫ��URLŬ����)
			mysql_query( "UPDATE tblsnsproduct SET count=count+1 WHERE code='".$pk."' LIMIT 1;" ,get_db_conn());
		}

		return $return;
	}





	// PKŰ ����
	function snsPromote_pkInfo ( $pk ) {
		$sql = "SELECT *, count(*) as cnt FROM `tblsnsproduct` WHERE `code` = '".$pk."' LIMIT 1 ; ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_assoc($result);
		return $row;
	}





	// ȫ�� URL ���� ����
	function snsPromote_acsInfo ( $authkey ) {
		$sql = "SELECT *, count(*) as cnt FROM `tblsnsproductLog` WHERE `authkey` = '".$authkey."' ; ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_assoc($result);
		return $row;
	}






	// ȫ�� URL ���� �� �α���
	function snsPromoteAccessLogin( $authkey, $id = '' ){
		if( strlen($authkey) > 0 AND strlen($id) > 0 ) {
			$acsInfo = snsPromote_acsInfo( $authkey );
			$pkInfo = snsPromote_pkInfo($acsInfo['code']);
			$logSQL = "";
			if( $acsInfo['cnt'] > 0 AND $pkInfo['cnt'] > 0 AND strlen($id) > 0 ){
				if( $id == $pkInfo['id'] ){
					$logSQL = "DELETE FROM `tblsnsproductLog` WHERE `authkey` = '".$authkey."' ; ";
				} else{
					$logSQL = "UPDATE `tblsnsproductLog` SET `memid` = '".$id."' WHERE `authkey` = '".$authkey."' LIMIT 1 ; ";
				}
			}
			mysql_query($logSQL, get_db_conn());
		}
	}





	// ȫ�� URL ���� �ֹ�
	function snsPromoteAccessOrder( $authkey, $ordercode ){
		$acsInfo = snsPromote_acsInfo( $authkey );
		if( !empty($acsInfo['idx']) AND strlen($ordercode) > 0 ){
			$logSQL = "UPDATE `tblsnsproductLog` SET `ordercode` = '".$ordercode."', `orderTime` = NOW() WHERE `authkey` = '".$authkey."' AND `orderTime` = '0000-00-00 00:00:00' LIMIT 1 ; ";
			mysql_query($logSQL, get_db_conn());
		}
	}




	// ȫ�� URL ���� �ֹ� ���� �� Ȯ��
	function snsPromoteOrderInfo( $ordercode ){
		$sql = "SELECT *, count(*) as cnt FROM `tblsnsproductLog` WHERE `ordercode` = '".$ordercode."' ; ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_assoc($result);
		return $row;
	}





	// ȫ�� URL ���� �ֹ� �Ϸ� (ȸ������ó�� ����)
	function snsPromoteAccessOrderOK( $ordercode ){

		$orderInfo = snsPromoteOrderOkInfo ( $ordercode );

		// ȫ���� ����
		if( $orderInfo['rsvA'] ){
			SetReserve($orderInfo['pkId'],$orderInfo['pkRsv'],$orderInfo['memId']."���� SNSȫ���� ���� ��ǰ��������.");
		}

		// ������ ����
		if( $orderInfo['rsvB'] ){
			SetReserve($orderInfo['memId'],$orderInfo['memRsv'],$orderInfo['pkId']."���� SNSȫ���� ���� ����������.");
		}

		// �Ϸ� ó��
		$logSQL = "UPDATE `tblsnsproductLog` SET `orderOkTime` = NOW() WHERE `ordercode` = '".$ordercode."' AND `orderOkTime` = '0000-00-00 00:00:00' LIMIT 1 ; ";
		mysql_query($logSQL, get_db_conn());

	}






	// ȫ�� URL ���� ���� ����
	function snsPromoteOrderOkInfo ( $ordercode ){

		$return = array();
		$return['rsvA'] = false;
		$return['rsvB'] = false;

		if( strlen($ordercode) > 0 ){

			$arSns = snsReserveInfo();

			// ȫ�� ��ǰ �ֹ� ����
			$promot = snsPromoteOrderInfo( $ordercode );
			$pkInfo = snsPromote_pkInfo($promot['code']);
			if( $arSns[7] == "Y" AND $arSns[0] != "N" AND $pkInfo['cnt'] > 0 ) {

				$return['pkId'] = $pkInfo['id']; //  ��ǰ ȫ����
				$return['memId'] = $promot['memid']; // ��ǰ ������

				$return['pkRsv'] = $arSns[5]; // ��ǰ ȫ���� ������
				$return['memRsv'] = $arSns[6]; // ��ǰ ������ ������

				// ȫ���� ����
				if( strlen($pkInfo['id']) > 0 AND $arSns[5] > 0 ) {
					if( $arSns[3] == "O" ){ // 1ȸ ����
						$return['rsvA'] = ( $promot['cnt'] == 1 ? true : false ) ;
					} elseif($arSns[3] == "A"){ // ��������
						$return['rsvA'] = true;
					}
				}

				// ������ ����
				if( strlen($promot['memid']) > 0 AND $arSns[6] > 0 ) {
					if( $arSns[4] == "O" ){ // 1ȸ ����
						$return['rsvB'] = ( $promot['cnt'] == 1 ? true : false ) ;
					} elseif($arSns[4] == "A"){ // ��������
						$return['rsvB'] = true;
					}
				}
			}
		}

		return $return;

	}



	// ������ ���� ����
	function snsReserveInfo () {
		global $_data;
		/*
			sns_reserve_type
			$arSnsType[n]
			n : ����
			0 : [N]������ ������ [A]��ü��ǰ �ϰ����� [B]�� ��ǰ�� �����ݼ��������� ����
			1 : ��ǰ ȫ���� ���� Ÿ�� [N]������(��) [Y]������(%)
			2 : ��ǰ ������ ���� Ÿ�� [N]������(��) [Y]������(%)
			3 : ��ǰ ȫ���� ���� ���� Ÿ�� [O]1ȸ���� [A]��������
			4 : ��ǰ ������ ���� ���� Ÿ�� [O]1ȸ���� [A]��������
			5 : ��ǰ ȫ���� ������
			6 : ��ǰ ������ ������
		*/
		$arSnsType = explode("",$_data->sns_reserve_type);
		$arSnsType[5]=$_data->sns_recomreserve; // ��ǰ ȫ���� ������
		$arSnsType[6]=$_data->sns_memreserve; // ��ǰ ������ ������
		$arSnsType[7]=$_data->sns_ok; // SNS ��� ����

		return $arSnsType;
	}





























	/**
		��õ�� ������
	*/

	// ������ - ��õ�� ���� ���� ����
	// $arr �迭�� ��� ���� ���̸� ��� ����
	function recommandSetting( $arr = '' ){

		if( is_array($arr) ) {

			// ����
			$orgMemRecommandReserve=trim($arr["orgMemRecommandReserve"]);
				$orgMemRecommandReserve=( strlen($orgMemRecommandReserve) > 0 ) ? $orgMemRecommandReserve : 0;
			$orgMemRecommandType = ( $arr["orgMemRecommandReserveType1"] == "join" ) ? $arr["orgMemRecommandReserveType1"] : $arr["orgMemRecommandReserveType2"];
			$newMemRecommandReserve=trim($arr["newMemRecommandReserve"]);
				$newMemRecommandReserve=( strlen($newMemRecommandReserve) > 0 ) ? $newMemRecommandReserve : 0;

			// ���� ��õ�� ȸ�� ������
			recommandSettingDb( 'orgMemRecommandReserve', $orgMemRecommandReserve );

			// ���� ��õ�� ȸ�� ���� ���� Ÿ��
			recommandSettingDb( 'orgMemRecommandType', $orgMemRecommandType );

			// ��õ�޾� ������ ȸ�� ������
			recommandSettingDb( '', $newMemRecommandReserve );

		} else{

			// ���
			$reteun = array();
			$extra_conf_res = mysql_query("SELECT `value`, `name` FROM `extra_conf` WHERE `type` = 'recommandReserve' ",get_db_conn());
			while ($extra_conf_row=mysql_fetch_object($extra_conf_res)) {
				$reteun[$extra_conf_row->name] = $extra_conf_row->value;
			}
			mysql_free_result($extra_conf_res);
			return $reteun;

		}

	}

	// DB�� ������ ���� - extra_conf
	function recommandSettingDb ( $k, $v ){
		if( !empty($k) AND !empty($v) ) {
			${$k} = $v;
			$extra_conf_res = mysql_query("SELECT `value` FROM `extra_conf` WHERE `type` = 'recommandReserve' AND `name` = '".$k."' ",get_db_conn());
			if( mysql_num_rows($extra_conf_res) ) {
				$extra_conf_up_sql = "UPDATE `extra_conf` SET `value`='".${$k}."' WHERE `type` = 'recommandReserve' AND `name` = '".$k."' ";
			} else{
				$extra_conf_up_sql = "INSERT `extra_conf` SET `type` = 'recommandReserve', `name` = '".$k."', `value`='".${$k}."' ";
			}
			$extra_conf_up_res = mysql_query($extra_conf_up_sql,get_db_conn());
			mysql_free_result($extra_conf_res);
			mysql_free_result($extra_conf_up_res);
		}
	}



	// ȸ�� ���Խ� ó�� ����
	function recommandJoin( $arr = "" ){

		if( is_array($arr) ) {

			$date=date("YmdHis");

			$get = recommandSetting(); // ����ȣ��


			// ��õ�� ȸ�� ����
			$orgMemRecommandReserveResult = 0;
			if( $get['orgMemRecommandType'] == "join" AND $get['orgMemRecommandReserve'] > 0 ) {

				SetReserve($arr['recomMem'],$get['orgMemRecommandReserve'],"ȫ��URL�� ���� ".$arr['newMeme']."���� ���� ȫ��������.");

				$orgMemRecommandReserveResult = $get['orgMemRecommandReserve'];

				// �α�
				recomenLog( $arr['recomMem'], "recomMem", $orgMemRecommandReserveResult );
			}


			// ��õ �޾� ������ ȸ�� ��� ����
			$newMemRecommandReserveResult = 0;
			if( $get['newMemRecommandReserve'] > 0 ) {

				SetReserve($arr['newMeme'],$get['newMemRecommandReserve'],$arr['recomMem']."���� ��õ���� ���� �߰�������.");

				$newMemRecommandReserveResult = $get['newMemRecommandReserve'];

				// ��õ�� ��õ���� ������ ī��Ʈ
				$rec_cnt=0;
				$rec_result = mysql_query( "SELECT rec_cnt FROM tblrecommendmanager WHERE rec_id='".$arr['recomMem']."'" ,get_db_conn());
				if($rec_row = mysql_fetch_object($rec_result)) {
					$rec_cnt = (int)$rec_row->rec_cnt;
				}
				mysql_free_result($rec_result);
				if($rec_cnt>0) {	//update
					$sql2 = "UPDATE tblrecommendmanager SET rec_cnt=rec_cnt+1 WHERE rec_id='".$arr['recomMem']."' ";
				} else {			//insert
					$sql2 = "INSERT tblrecommendmanager SET rec_id		= '".$arr['recomMem']."', rec_cnt	= '1', date		= '".$date."' ";
				}
				mysql_query($sql2,get_db_conn());


				// (����)������ ī��Ʈ �̰ɷ� �ϴµ�...������ �ȵɵ�...
				$sql2 = "INSERT tblrecomendlist SET ";
				$sql2.= "rec_id			= '".$arr['recomMem']."', ";
				$sql2.= "id				= '".$arr['newMeme']."', ";
				$sql2.= "rec_id_reserve	= '".$orgMemRecommandReserveResult."', ";
				$sql2.= "id_reserve		= '".$newMemRecommandReserveResult."', ";
				$sql2.= "date			= '".$date."' ";
				mysql_query($sql2,get_db_conn());

				// �α�
				recomenLog( $arr['newMeme'], "joinMem", $newMemRecommandReserveResul );

			}

		}

	}


	// ��õ�� ȸ�� ���� (��ǰ ���� �Ϸ��)
	function recommandMemReserve ( $memid ){

		$get = recommandSetting(); // ����ȣ��

		// ȸ���� ��õ��
		$getMemResult = mysql_query( "SELECT rec_id FROM tblmember WHERE id = '".$memid."' ; ",get_db_conn());
		$getMemRow = mysql_fetch_assoc($getMemResult);

		//_pr($getMemRow);
		if( strlen($getMemRow['rec_id']) ) {

			$log = recomenLog($getMemRow['rec_id']);

			//_pr($get);
			//_pr($log);

			$reserveOK = false;

			if ( $get[orgMemRecommandType] == "orderA" ) { // ���� 1ȸ��
				if( $log['result']['cnt'] == 0  ) { $reserveOK = true; }
			} else if ( $get[orgMemRecommandType] == "orderB" ) { // ���Ž� ���� (��������)
				$reserveOK = true;
			}
			if( $reserveOK ) {
				// ����
				SetReserve( $getMemRow['rec_id'], $get['orgMemRecommandReserve'], "[ȫ������]".$memid."���� ��ǰ���ſϷ�." );
				// �α�
				$chk = recomenLog( $getMemRow['rec_id'], "orderEnd", $get['orgMemRecommandReserve'] );
				//_pr($chk);
			}

		}
		//exit;
	}


	// ��õ ���� �α� �ۼ�
	function recomenLog ( $memId, $type = '', $reserve = 0 ) {

		$return = array();

		if ( empty($memId) ){
			$return['error'] = "ȸ�����̵� ����";
		} else {
			if( strlen($type) > 0 ) {
				// �α� ����
				if ( $reserve > 0 ) {
					mysql_query("INSERT tblrecomenLog SET memId = '".$memId."', reserve = '".$reserve."', type = '".$type."'; ", get_db_conn());
					$return['result'] = "����Ϸ�";
				} else {
					$return['error'] = "������ ����(0��)";
				}
			} else {
				// �α� ȣ��
				$getLogSQL = "SELECT * FROM  tblrecomenLog WHERE memId = '".$memId."' ; ";
				$getLogResult = mysql_query($getLogSQL,get_db_conn());
				$return['result']['cnt'] = mysql_num_rows($getLogResult);
				while ( $getLogRow = mysql_fetch_assoc( $getLogResult ) ) {
					$return['result'][$getLogRow['idx']]['id'] = $getLogRow['memId'];
					$return['result'][$getLogRow['idx']]['reserve'] = $getLogRow['reserve'];
					$return['result'][$getLogRow['idx']]['type'] = $getLogRow['type'];
				}
			}
		}
		return $return;
	}

?>