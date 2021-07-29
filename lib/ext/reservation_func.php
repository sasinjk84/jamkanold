<?



	// 배송여부
	$deliStep = array(
		'S'=>"배송대기(발송준비)",
		'X'=>"배송요청",
		'Y'=>"배송",
		'D'=>"<font color=blue>취소요청</font>",
		'N'=>"미처리",
		'E'=>"<font color=red>환불대기</font>",
		'C'=>"<font color=red>주문취소</font>",
		'R'=>"반송",
		'H'=>"배송(<font color=red>정산보류</font>)",
		'RC'=>"<span style='color:blue'>환불완료</span>"
	);

	// 결제 상태
	$arpm=array("B"=>"무통장","V"=>"계좌이체","O"=>"가상계좌","Q"=>"가상계좌(매매보호)","C"=>"신용카드","P"=>"신용카드(매매보호)","M"=>"핸드폰");


	/* 예약 주문 상품 리스트
	* rsvProdList ( 옵션 );
	*/
	function rsvProdList( $listOption ){

		$vdate = $listOption['vdate']; // 날짜
		$prodeCode = $listOption['productcode']; // 상품코드
		$vender = $listOption['vender']; // 벤더
		$page = $listOption['page']; // 페이지
		$more = $listOption['more']; // 선택일 이후 모두 표시

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
					IF ( P.`vender`>0 , (SELECT V.`id` FROM `tblvenderinfo` as V WHERE P.`vender` = V.`vender`) , '본사' ) as venderName
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

				// 상품정보
				$returnList[$row['productcode']]['product'] = $row;

				//상품별 주문 리스트
				$rsvOrderArray = srvProdOrderList($row['productcode'], $page);
				$returnList[$row['productcode']]['product']['orderCount'] = $rsvOrderArray['totalNo']; // 주문카운터
				if( strlen($prodeCode) > 0 ) {
					foreach ( $rsvOrderArray as $rsvOrder ) {
						$returnList[$row['productcode']]['order'][$rsvOrder['ordercode']] = $rsvOrder;
					}
					$returnList[$row['productcode']]['order']['pageList'] = $rsvOrderArray['pageList']; // 주문카운터
				}
			}
		}
		return $returnList;
	}


	/* 상품별 주문 리스트
	* srvProdOrderList(상품코드);
	*/
	function srvProdOrderList( $productcode, $page ) {
		global $vdate;

		$orderReturnList = array();
		if( $productcode ) {

			$page_list_option="?vdate=".$vdate."&productcode=".$productcode."&";

			$list_item=100;
			$list_page=10;

			if( empty($page)) $page = 1;
			$offset = $list_item*($page-1); //페이지별 시작값 계산

			// 쿼리문 시작
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
			// 쿼리문 끝

			$result=mysql_query($sql,get_db_conn()) or die (mysql_error());
			$total_no=mysql_num_rows($result); // 개시물 총 개수

			$sql.=" LIMIT ".$offset.", ".$list_item." ; "; // 쿼리문 출력 조건

			$total_page=ceil($total_no/$list_item); // 전체 게시물 페이지 개수
			if($total_page==0) $total_page=1; // 전체 게시물 페이지 개수 초기화
			$cur_num=$total_no-$list_item*($page-1); // 현제 게시물 페이지
			$result=mysql_query($sql,get_db_conn()) or die (mysql_error());
			$total_block=ceil($total_page/$list_page); //페이지 리스트 페이지 전체 개수
			$block=ceil($page/$list_page); //현재 페이지 리스트 페이지
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
