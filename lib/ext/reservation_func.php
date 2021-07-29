<?



	// ��ۿ���
	$deliStep = array(
		'S'=>"��۴��(�߼��غ�)",
		'X'=>"��ۿ�û",
		'Y'=>"���",
		'D'=>"<font color=blue>��ҿ�û</font>",
		'N'=>"��ó��",
		'E'=>"<font color=red>ȯ�Ҵ��</font>",
		'C'=>"<font color=red>�ֹ����</font>",
		'R'=>"�ݼ�",
		'H'=>"���(<font color=red>���꺸��</font>)",
		'RC'=>"<span style='color:blue'>ȯ�ҿϷ�</span>"
	);

	// ���� ����
	$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��","P"=>"�ſ�ī��(�Ÿź�ȣ)","M"=>"�ڵ���");


	/* ���� �ֹ� ��ǰ ����Ʈ
	* rsvProdList ( �ɼ� );
	*/
	function rsvProdList( $listOption ){

		$vdate = $listOption['vdate']; // ��¥
		$prodeCode = $listOption['productcode']; // ��ǰ�ڵ�
		$vender = $listOption['vender']; // ����
		$page = $listOption['page']; // ������
		$more = $listOption['more']; // ������ ���� ��� ǥ��

		$srchDate = "";
		$returnList = array();

		if( strlen($vdate) > 0 ) {

			$Y =substr($vdate,0,4);
			$M =substr($vdate,4,2);
			$D =substr($vdate,6,2);

			if( $more == "A" ) {
				$srchDate = "P.`reservation` >= '".$Y."-".($M ? $M."-" : "00" ).($D ? $D : "00" )."' ";
			} else{
				$srchDate = "P.`reservation` LIKE '".$Y."-".($M ? $M."-" : "%" ).($D ? $D : "%" )."' ";
			}

			$sql = "
				SELECT
					P.`pridx`, P.`productcode`, P.`productname`, P.`tinyimage`, P.`reservation`,
					IF ( P.`vender`>0 , (SELECT V.`id` FROM `tblvenderinfo` as V WHERE P.`vender` = V.`vender`) , '����' ) as venderName
				FROM
					`tblproduct` as P
				WHERE
					".$srchDate."
			";

			if( $vender > 0 ) {
				$sql .= "
					AND
					P.`vender` = '".$vender."'
				";
			}

			if( strlen($prodeCode) > 0 ) {
				$sql .= "
					AND
					P.`productcode` = '".$prodeCode."'
				";
			}

			 $sql .= "
				ORDER BY P.`reservation` ASC
				;
			";
			$result=mysql_query($sql,get_db_conn());
			while ( $row = mysql_fetch_assoc($result) ){

				// ��ǰ����
				$returnList[$row['productcode']]['product'] = $row;

				//��ǰ�� �ֹ� ����Ʈ
				$rsvOrderArray = srvProdOrderList($row['productcode'], $page);
				$returnList[$row['productcode']]['product']['orderCount'] = $rsvOrderArray['totalNo']; // �ֹ�ī����
				if( strlen($prodeCode) > 0 ) {
					foreach ( $rsvOrderArray as $rsvOrder ) {
						$returnList[$row['productcode']]['order'][$rsvOrder['ordercode']] = $rsvOrder;
					}
					$returnList[$row['productcode']]['order']['pageList'] = $rsvOrderArray['pageList']; // �ֹ�ī����
				}
			}
		}
		return $returnList;
	}


	/* ��ǰ�� �ֹ� ����Ʈ
	* srvProdOrderList(��ǰ�ڵ�);
	*/
	function srvProdOrderList( $productcode, $page ) {
		global $vdate;

		$orderReturnList = array();
		if( $productcode ) {

			$page_list_option="?vdate=".$vdate."&productcode=".$productcode."&";

			$list_item=100;
			$list_page=10;

			if( empty($page)) $page = 1;
			$offset = $list_item*($page-1); //�������� ���۰� ���

			// ������ ����
			$sql = "
				SELECT
					OI.`id`, OI.`sender_name`, OI.`ordercode`, OI.`deli_gbn` as orderDeli, OI.`paymethod`, OI.`pay_flag`, OI.`bank_date`, OI.`pay_admin_proc`, OI.`order_msg`,
					OP.`uid`, OP.`quantity`, OP.`price`, OP.`date`, OP.`status`, OP.`deli_gbn` as orderProdDeli, OP.`opt1_name`, OP.`opt2_name`, OP.`opt3_name`, OP.`opt4_name`
				FROM
					`tblorderinfo` as OI
					INNER JOIN
						`tblorderproduct` as OP
					ON
						OI.`ordercode` = OP.`ordercode`
				WHERE
					OP.`productcode` = '".$productcode."'
				ORDER BY OP.`date` ASC
			";
			// ������ ��

			$result=mysql_query($sql,get_db_conn()) or die (mysql_error());
			$total_no=mysql_num_rows($result); // ���ù� �� ����

			$sql.=" LIMIT ".$offset.", ".$list_item." ; "; // ������ ��� ����

			$total_page=ceil($total_no/$list_item); // ��ü �Խù� ������ ����
			if($total_page==0) $total_page=1; // ��ü �Խù� ������ ���� �ʱ�ȭ
			$cur_num=$total_no-$list_item*($page-1); // ���� �Խù� ������
			$result=mysql_query($sql,get_db_conn()) or die (mysql_error());
			$total_block=ceil($total_page/$list_page); //������ ����Ʈ ������ ��ü ����
			$block=ceil($page/$list_page); //���� ������ ����Ʈ ������
			$first=($block-1)*$list_page;
			$last=$block*$list_page;

			if($block >= $total_block) {
				$last=$total_page;
			}

			//$result=mysql_query($sql,get_db_conn());

			while ( $row = mysql_fetch_assoc($result) ){
				$orderReturnList[$row['uid']] = $row;
			}

			$orderReturnList['totalNo'] = $total_no;
			$orderReturnList['pageList'] = pageList($total_page,$page,$total_block,$block,$first,$last,$page_list_option,0);

		}
		return $orderReturnList;
	}





?>
