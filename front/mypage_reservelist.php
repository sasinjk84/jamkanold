<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/base_func.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/ext/order_func.php");
include_once($Dir."lib/class/pages.php");

if(strlen($_ShopInfo->getMemid())==0) {
	Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
	exit;
}

$sql = "SELECT * FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	if($row->member_out=="Y") {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		echo "<html><head><title></title></head><body onload=\"alert('회원 아이디가 존재하지 않습니다.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	}

	if($row->authidkey!=$_ShopInfo->getAuthidkey()) {
		$_ShopInfo->SetMemNULL();
		$_ShopInfo->Save();
		echo "<html><head><title></title></head><body onload=\"alert('처음부터 다시 시작하시기 바랍니다.');location.href='".$Dir.FrontDir."login.php';\"></body></html>";exit;
	}
}
mysql_free_result($result);

//리스트 세팅
$setup[page_num] = 10;
$setup[list_num] = 10;
$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

?>

<HTML>
	<HEAD>
		<TITLE><?=$_data->shoptitle?> - 예약리스트</TITLE>
		<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
		<META http-equiv="X-UA-Compatible" content="IE=Edge" />

		<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
		<META name="keywords" content="<?=$_data->shopkeyword?>">
		<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
		<?include($Dir."lib/style.php")?>
	</HEAD>

	<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

		<?
			include ($Dir.MainDir.$_data->menu_type.".php");
			include_once("./mypage_groupinfo.php");

			$param = array('id'=>$_ShopInfo->getMemid());
			switch($_REQUEST['s']){
				case 'BANK_READY':
					$tit = '-예약 입금대기';
					$param['status']=array('BRA');
					break;
				case 'BANK_NC':
					$tit = '-예약취소 임박';
					$param['status'] = array('BRB');
					break;
				case 'RENT_OK':
					$tit = '-예약확정';
					$param['status'] = array('BO');
					break;
				case 'RENT_CAN':
					$tit = '-예약취소';
					$param['status'] = array('BRC');
					break;
				case 'RENT_LAST_CAN':
					$tit = '-지난 취소예약';
					$param['status'] = array('BRD');
					break;
				default:
					$tit = '';
					$param['status']=array('BR','BC','BO');
					break;
			}

			$rentCount = rentProduct::getCount($_ShopInfo->getMemid());
		?>

		<!-- 마이페이지-주문내역 상단 메뉴 -->
		<div class="currentTitle">
			<div class="titleimage">예약리스트 <?=$tit?></div>
		</div>
		<!-- 마이페이지-예약 및 취소 상단 메뉴 -->

		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<?
				$leftmenu="Y";
				if($_data->design_orderlist=="U") {
					$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='orderlist'";
					$result=mysql_query($sql,get_db_conn());
					if($row=mysql_fetch_object($result)) {
						$body=$row->body;
						$body=str_replace("[DIR]",$Dir,$body);
						$leftmenu=$row->leftmenu;
						$newdesign="Y";
					}
					mysql_free_result($result);
				}

				if ($leftmenu!="N") {
					echo "<tr>\n";
					if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/orderlist_title.gif")) {
						echo "<td><img src=\"".$Dir.DataDir."design/orderlist_title.gif\" border=\"0\" alt=\"주문내역\"></td>\n";
					} else {
						echo "<td></td>\n";
					}
					echo "</tr>\n";
				}
			?>
			<tr>
				<td align="center" style="text-align:left;">
					<!-- START -->
					<div class="orderStateWrap">
						<!-- 주문현황 -->
						<table cellpadding="0" cellspacing="0" border="0" width="55%" class="myOrderTbl" style="margin:0 auto;">
							<tr>
								<td>
									<p>예약 입금대기</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=BANK_READY"><strong><?=number_format($rentCount['booking_ready'])?></strong></a>
								</td>
								<td>
									<p>예약취소 임박</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=BANK_NC"><strong><?=number_format($rentCount['booking_close_near'])?></strong></a>
								</td>
								<td>
									<p>예약확정</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=RENT_OK"><strong><?=number_format($rentCount['booking_comp'])?></strong></a>
								</td>

								<td style="border-left:1px solid #e5e5e5;">
									<p>예약취소</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=RENT_CAN"><strong><?=number_format($rentCount['booking_cancle_cur'])?></strong></a>
								</td>
								<?php /* // 지난 예약은 렌탈 종료나 반납 완료 일 텐데 여기 있을 필요가 있을까??
								<td>
									<p>지난 예약</p>
									<a href="javascript:goOrderType('R');"><strong><?=number_format($rentCount['rental_end'])?></strong></a>
								</td>
								*/ ?>
								<td>
									<p>지난 취소예약</p>
									<a href="<?=$_SERVER['PHP_SELF']?>?s=RENT_LAST_CAN"><strong><?=number_format($rentCount['booking_cancle_old'])?></strong></a>
								</td>
							</tr>
						</table>
					</div>
					<p style="padding:40px 0px 10px;">결재 및 입금 확인이 된 경우, 자동으로 예약 확정이 되며, 그 외 예약 확정을 원하는 경우 판매자에게 문의하시기 바랍니다.</p>

					<table cellpadding="0" cellspacing="0" width="100%" border="0" class="orderlistTbl">
						<colgroup>
						<col width="180"></col>
						<col width="*"></col>
						<col width="120"></col>
						<col width="120"></col>
						<col width="120"></col>
						<col width="120"></col>
						</colgroup>
						<tr>
							<th>주문일자/결제번호</th>
							<th>상품정보</th>
							<th>기간</th>
							<th>주문금액(수량)</th>
							<th>판매자</th>
							<th>진행상태</th>
						</tr>

						<?
						// $productSchdListData['memId'] = $_ShopInfo->getMemid();
						// $schdROW = productScheduleList( $productSchdListData );

						$info = rentProduct::searchOrder($param);

						if(!_array($info)) {
						?>
						<tr>
							<td align="center" colspan="6" style="padding:30px;">등록된 정보가 없습니다.</td>
						</tr>
						<?
						} else {
							//_pr($info);
							foreach ( $info as $k => $v ) {
								$pinfo = rentProduct::read($v['pridx']);
								//_pr($pinfo);
								if(!_array($pinfo)) continue;
								$optionName = "";
								foreach($v['opt'] as $ov) {
									$optionName .= $ov['optionName']." : ".$ov['orderCnt']."개<br>";
								}
								
								$rangetext = '';
								$timetext = '';
								if($pinfo['solvprice']['codeinfo']['pricetype'] == 'time') $rangetext = date('Y-m-d H',strtotime($v['start'])).'<br>'.date('Y-m-d H',strtotime($v['end'])+1);
								else $rangetext = date('Y-m-d',strtotime($v['start'])).'<br>'.date('Y-m-d',strtotime($v['end'])+1);

								$datediff = datediff_rent($v['end'],$v['start']);
								if($datediff['day'] > 0) $timetext =$datediff['day'].'일 ';
								if($datediff['hour'] > 0) $timetext .=$datediff['hour'].'시간';
								if(!_empty($timetext)) $rangetext .= '<br>'.$timetext;

								//  남은시간
								$edatediff = datediff_rent($v['end'],date('Y-m-d H:i:s'));		
								$timetext = '';						
								if($edatediff['day'] > 0) $timetext =$edatediff['day'].'일 ';
								if($edatediff['hour'] > 0) $timetext .=$edatediff['hour'].'시간';

								?>
								<tr>
									<td align="center"><?=$v['regDate']?><br/>(<?=$v['ordercode']?>)</td>
									<td align="left"><?=$v['productname']?><br/><?=$optionName?></td>
									<td align="center"><?=$rangetext?></td>
									<td align="center"><?=number_format($v['price'])?>원<br/>(<?=number_format($v['quantity'])?>개)</td>
									<td align="center"><?=$v['com_name'].'<br/>'.$v['com_tel']?></td>
									<td align="center"><?=rentProduct::_bookingStatus($v['status'])?><br><?=$timetext?></td>
								</tr>
					<?	
							}
						}
					?>


					</table>
					<!-- END -->
				</td>
			</tr>
		</table>
		<? include ($Dir."lib/bottom.php") ?>
		<?=$onload?>
	</BODY>
</HTML>