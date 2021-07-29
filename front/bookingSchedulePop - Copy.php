<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-14
 * Time: 오후 3:49
 */
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");

	extract($_GET);

	if( $pridx > 0 ) {

		// 오늘 날짜
		$vdate = $_GET[vdate];
		$selT =  ( empty($vdate) ? time() :strtotime( $vdate."01") );
		$prv = date("Ym",strtotime("-1 month",$selT));
		$nxt = date("Ym",strtotime("+1 month",$selT));
		$selY = date("Y",$selT);
		$selM = date("m",$selT);
		$curY = date("Y");
		$curM = date("m");
		$monthDays = date("t",$selT);

		// 렌탈 주문 리스트
	//	$bookingProductList = bookingProductList('M',$selY.$selM);
		
		$pinfo = &rentProduct::read($pridx);
		rentProduct::schedule($pridx,date('Ym01',$selT),date('Ymt',$selT));
		$schedulebyday = array();
	//	_pr($pinfo);
	/*
		foreach($pinfo['schedule'] as $date=>$schedule){					
			$dkey = substr($date,0,10);		
			if(!isset($schedulebyday[$dkey]))  $schedulebyday[$dkey] = array();
			if(!isset($schedulebyday[$dkey][$pinfo['pridx']]))  $schedulebyday[$dkey][$pinfo['pridx']] = array();
			foreach($schedule as $optidx=>$rentcnt){
				if(!isset($schedulebyday[$dkey][$pinfo['pridx']][$optidx]))  $schedulebyday[$dkey][$pinfo['pridx']][$optidx] = 0;
				$schedulebyday[$dkey][$pinfo['pridx']][$optidx]+=$rentcnt;
				echo '<br>'.$schedulebyday[$dkey][$pinfo['pridx']][$optidx];
			}			
		}*/
	//	_pr($pinfo);
?>

<html>
	<head>
		<title>렌탈/예약 현황보기</title>
		<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
		<style>
			BODY,DIV,form,TEXTAREA,center,option,pre,blockquote,table{font-family:"굴림","돋움";color:#4B4B4B;font-size:12px;line-height:120%;}
			h2{background:url('/data/design/img/sub/tit_pop_bg.gif') repeat-x;}

			A:link    {color:#635C5A;text-decoration:none;}
			A:visited {color:#545454;text-decoration:none;}
			A:active  {color:#5A595A;text-decoration:none;}
			A:hover  {color:#545454;text-decoration:underline;}

			.tableBase{border-top:1px solid #b9b9b9;font-size:12px;}
			.tableBase th{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;background:#f8f8f8;}
			.tableBase .firstTh{border-left:none;background:#f8f8f8;}
			.tableBase td{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;}
			.tableBase .firstTd{border-left:none;}

			.tableBaseSe{font-size:12px;}
			.tableBaseSe caption{padding:8px;background:#ededed;border-bottom:1px solid #d9d9d9;}
			.tableBaseSe th{padding:8px 0px;border-right:1px solid #ededed;border-bottom:1px solid #ededed;background:#f5f5f5;text-align:center;}
			.tableBaseSe .lastTh{border-right:none;}
			.tableBaseSe td{padding:8px 0px;border-right:1px solid #ededed;border-bottom:1px solid #ededed;text-align:center;}
			.tableBaseSe .lastTd{border-right:none;}
		</style>

		<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
		<script type="text/javascript">
			<!--
			var $j = jQuery.noConflict();

			// 마우스 따라다니는 레이어
			jQuery(document).ready(function(){
				$j(document).mousemove(function(e){
					var leftP = ( ( $j(document).width() - 600 ) < e.pageX ) ? $j(document).width()-625 : e.pageX ;
					$j('#viewInfo').css("left",leftP-20);
					$j('#viewInfo').css("top",e.pageY+15);
				});
			})

			// 레이어 내용채워 보이기
			function viewInfo( idx ) {
				$j('#viewInfo').css("display","block");
				$j('#viewInfo').html($j('#bookingInfo_'+idx).html());
			}

			// 레이어 내용비우고 가리기
			function offInfo( idx ) {
				$j('#viewInfo').css("display","none");
				$j('#viewInfo').html("");
			}
			-->
		</script>
	</head>
	
	<body topmargin="0" leftmargin="0" rightmargin="0">
		<div style="text-align:center;">
			<h2>
				<div style="float:left;"><img src="/data/design/img/sub/tit_rentalchart.gif" alt="예약/렌탈 현황보기" /></div>
				<div style="float:right;margin-top:14px;margin-right:14px;"><a href="javascript:self.close();"><img src="/data/design/img/sub/btn_pop_close.gif" border="0" alt="" /></a><!--<input type="button" value="닫기" onclick="self.close();">--></div>
				<div style="clear:both;"></div>
			</h2>
			<!--<strong>예약현황</strong>-->

		<p style="width:96%;margin:10 auto;text-align:left;">* <strong class="font_orange"><?=$selY?>년 <?=$selM?>월</strong> 예약 및 렌탈 현황입니다. [<a href="?vdate=<?=$prv?>&pridx=<?=$pridx?>">이전달</a>] [<a href="?vdate=<?=$nxt?>&pridx=<?=$pridx?>">다음달</a>] [<a href="?vdate=<?=$curY.$curM?>&pridx=<?=$pridx?>">오늘 보기(이번달)</a>] [<a href="javascript:document.location.reload();">새로고침</a>]</p>
		<div style="height:300px; overflow:scroll; text-align:left">
		<?=_pr($pinfo)?>
		</div>
		<div id="viewInfo" style="display:none;position:absolute;top:100px;left:100px;width:600;padding:10px;background:#ffffff;border:2px solid #999999;z-index:999;overflow:hidden;"></div>
		<table border="0" cellspacing="0" cellpadding="0" align="center" width="96%" class="tableBase">
			<tr>
				<th class="firstTh">상품명</th>
				<th>옵션</th>
				<?
				// 일자
				for( $dd = 1 ; $dd <= $monthDays ; $dd++) {
					$dayOfWeek = date("w",strtotime($selY."-".$selM."-".$dd)); //요일
					// 요일 및 휴무일 컬러 휴무일>일요일>토요일 (휴무일:(일요일:토요일))
					$dayOfWeekColor = (strlen($dayOffs[0])==0?($dayOfWeek==0?"#FF8888":($dayOfWeek==6?"8888FF":"")):"#FF6600");
					echo "<th><span style='color:".$dayOfWeekColor.";'>". str_pad($dd, 2, "0", STR_PAD_LEFT) ."</span></th>";
				}
				?>
			</tr>
			<?
			// 상품 리스트
			$isfirst = true;
			$datekey = 'Y-m-d '.($pinfo['codeinfo']['pricetype'] == 'time')?' H':'';
			foreach($pinfo['options'] as $opt){
			?>
					<tr>
						<? if($isfirst){ ?><td class="firstTd" align="left" rowspan="<?=count($pinfo['options'])?>"><?=$pinfo['productname']?></td><? $isfirstf= false; } ?>
						<td align="left"><?=$opt['optionName']?></td>
						<?
						for($dd = 1;$dd<=$monthDays;$dd++){

							$selDate = $selY."-".$selM."-".$dd;
							$productSchdListData = array();
							//$productSchdListData['location'] = $v['location'];

							$productSchdListData['pridx'] = $pridx;
							$productSchdListData['dateStart'] = $selDate;
							$productSchdListData['dateEnd'] = $selDate;
							$schdROW = productScheduleList( $productSchdListData );
							//if ( _array($schdROW) ) _pr($schdROW);


							// 옵션정보
							$productOptionInfo= rentProductOptionInfo($productOptionKey);

							if( _array($schdROW) ) {

								$bookingInfodetail = "
									<div id=\"bookingInfo_".$selY.$selM.$dd.$productOption['optionIdx']."\" style=\"display:none;\">
										<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FFFFFF\" border=\"0\" class=\"tableBaseSe\">
								";
								$bookingInfodetail .= "
											<tr>
												<th>예약코드</th>
												<th>수량</th>
												<!--<th>예약자</th>
												<th>연락처</th>-->
												<th>예약일</th>
												<th class=\"lastTh\">예약상태</th>
											</tr>
								";

								$optCntSum = 0;
								foreach ( $schdROW as $scdValue ) {
									//_pr($scdValue);
									//상세 내용
									$optCntSum += $scdValue['opt'][$productOptionKey]['orderCnt'];
									$bookingStart = strtotime($scdValue['bookingStartDate'] . " 00:00:00");
									$bookingEnd = strtotime($scdValue['bookingEndDate'] . " 23:59:59");
									if (_array($scdValue['opt'][$productOptionKey])) {
										$bookingInfodetail .= "
											<tr>
												<td>" . $scdValue['idx'] . "</td>
												<td>" . $scdValue['opt'][$productOptionKey]['orderCnt'] . "개</td>
												<!--<td>" . $scdValue['onnerName'] . "</td>
												<td>" . $scdValue['onnerTel'] . "</td>-->
												<td>" . date("Y.m.d", $bookingStart) . " ~ " . date("Y.m.d", $bookingEnd) . "</td>
												<td class=\"lastTd\"><!--[" . $scdValue[status] . "]-->" . $bookingStatus[$scdValue[status]] . "</td>
											</tr>
										";
									}
								}
								$moreCnt = $productOptionInfo['productCount'] - $optCntSum;
								$bookingInfodetail .= "<caption><div style=\"float:left;font-weight:700;\">" . $selDate . " 예약/대여 정보</div><div style=\"float:right;\">총 ".$productOptionInfo['productCount']."개 /  잔여 ".$moreCnt."개</div></caption>";
								$bookingInfodetail .= "</table></div>";

								// 달력에 출력
								echo "<td width=\"40\" align=\"center\" bgcolor='#A9E2F3' onmouseover=\"viewInfo('".$selY.$selM.$dd.$productOption['optionIdx']."');\" onmouseout=\"offInfo('".$selY.$selM.$dd.$productOption['optionIdx']."');\"><b>".$moreCnt."</b>".$bookingInfodetail."</td>";
								//$dd += $bookingDisplayDays-1;
							}else{
								echo "<td width=\"40\" align=\"center\">&nbsp;</td>";
							}

						}
						?>
					</tr>
				<?
				}
			}

			if ( count($productOptionList) == 0 ) {
				echo "<tr><td colspan='".($monthDays+2)."' align='center' class=\"firstTd\">예약/렌탈 현황이 없습니다.</td></tr>";
			}
			?>
		</table>

	<? // } ?>
		</div>
	</body>
</html>