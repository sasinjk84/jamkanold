<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-14
 * Time: ���� 3:49
 */
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");

	extract($_GET);

	if( $pridx > 0 ) {

		// ���� ��¥
		$vdate = $_GET[vdate];
		$selT =  ( empty($vdate) ? time() :strtotime( $vdate."01") );
		$prv = date("Ym",strtotime("-1 month",$selT));
		$nxt = date("Ym",strtotime("+1 month",$selT));
		$selY = date("Y",$selT);
		$selM = date("m",$selT);
		$curY = date("Y");
		$curM = date("m");
		$monthDays = date("t",$selT);

		// ��Ż �ֹ� ����Ʈ
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
		<title>��Ż/���� ��Ȳ����</title>
		<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
		<style>
			BODY,DIV,form,TEXTAREA,center,option,pre,blockquote,table{font-family:"����","����";color:#4B4B4B;font-size:12px;line-height:120%;}
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

			// ���콺 ����ٴϴ� ���̾�
			jQuery(document).ready(function(){
				$j(document).mousemove(function(e){
					var leftP = ( ( $j(document).width() - 600 ) < e.pageX ) ? $j(document).width()-625 : e.pageX ;
					$j('#viewInfo').css("left",leftP-20);
					$j('#viewInfo').css("top",e.pageY+15);
				});
			})

			// ���̾� ����ä�� ���̱�
			function viewInfo( idx ) {
				$j('#viewInfo').css("display","block");
				$j('#viewInfo').html($j('#bookingInfo_'+idx).html());
			}

			// ���̾� ������� ������
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
				<div style="float:left;"><img src="/data/design/img/sub/tit_rentalchart.gif" alt="����/��Ż ��Ȳ����" /></div>
				<div style="float:right;margin-top:14px;margin-right:14px;"><a href="javascript:self.close();"><img src="/data/design/img/sub/btn_pop_close.gif" border="0" alt="" /></a><!--<input type="button" value="�ݱ�" onclick="self.close();">--></div>
				<div style="clear:both;"></div>
			</h2>
			<!--<strong>������Ȳ</strong>-->

		<p style="width:96%;margin:10 auto;text-align:left;">* <strong class="font_orange"><?=$selY?>�� <?=$selM?>��</strong> ���� �� ��Ż ��Ȳ�Դϴ�. [<a href="?vdate=<?=$prv?>&pridx=<?=$pridx?>">������</a>] [<a href="?vdate=<?=$nxt?>&pridx=<?=$pridx?>">������</a>] [<a href="?vdate=<?=$curY.$curM?>&pridx=<?=$pridx?>">���� ����(�̹���)</a>] [<a href="javascript:document.location.reload();">���ΰ�ħ</a>]</p>
		<div style="height:300px; overflow:scroll; text-align:left">
		<?=_pr($pinfo)?>
		</div>
		<div id="viewInfo" style="display:none;position:absolute;top:100px;left:100px;width:600;padding:10px;background:#ffffff;border:2px solid #999999;z-index:999;overflow:hidden;"></div>
		<table border="0" cellspacing="0" cellpadding="0" align="center" width="96%" class="tableBase">
			<tr>
				<th class="firstTh">��ǰ��</th>
				<th>�ɼ�</th>
				<?
				// ����
				for( $dd = 1 ; $dd <= $monthDays ; $dd++) {
					$dayOfWeek = date("w",strtotime($selY."-".$selM."-".$dd)); //����
					// ���� �� �޹��� �÷� �޹���>�Ͽ���>����� (�޹���:(�Ͽ���:�����))
					$dayOfWeekColor = (strlen($dayOffs[0])==0?($dayOfWeek==0?"#FF8888":($dayOfWeek==6?"8888FF":"")):"#FF6600");
					echo "<th><span style='color:".$dayOfWeekColor.";'>". str_pad($dd, 2, "0", STR_PAD_LEFT) ."</span></th>";
				}
				?>
			</tr>
			<?
			// ��ǰ ����Ʈ
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


							// �ɼ�����
							$productOptionInfo= rentProductOptionInfo($productOptionKey);

							if( _array($schdROW) ) {

								$bookingInfodetail = "
									<div id=\"bookingInfo_".$selY.$selM.$dd.$productOption['optionIdx']."\" style=\"display:none;\">
										<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FFFFFF\" border=\"0\" class=\"tableBaseSe\">
								";
								$bookingInfodetail .= "
											<tr>
												<th>�����ڵ�</th>
												<th>����</th>
												<!--<th>������</th>
												<th>����ó</th>-->
												<th>������</th>
												<th class=\"lastTh\">�������</th>
											</tr>
								";

								$optCntSum = 0;
								foreach ( $schdROW as $scdValue ) {
									//_pr($scdValue);
									//�� ����
									$optCntSum += $scdValue['opt'][$productOptionKey]['orderCnt'];
									$bookingStart = strtotime($scdValue['bookingStartDate'] . " 00:00:00");
									$bookingEnd = strtotime($scdValue['bookingEndDate'] . " 23:59:59");
									if (_array($scdValue['opt'][$productOptionKey])) {
										$bookingInfodetail .= "
											<tr>
												<td>" . $scdValue['idx'] . "</td>
												<td>" . $scdValue['opt'][$productOptionKey]['orderCnt'] . "��</td>
												<!--<td>" . $scdValue['onnerName'] . "</td>
												<td>" . $scdValue['onnerTel'] . "</td>-->
												<td>" . date("Y.m.d", $bookingStart) . " ~ " . date("Y.m.d", $bookingEnd) . "</td>
												<td class=\"lastTd\"><!--[" . $scdValue[status] . "]-->" . $bookingStatus[$scdValue[status]] . "</td>
											</tr>
										";
									}
								}
								$moreCnt = $productOptionInfo['productCount'] - $optCntSum;
								$bookingInfodetail .= "<caption><div style=\"float:left;font-weight:700;\">" . $selDate . " ����/�뿩 ����</div><div style=\"float:right;\">�� ".$productOptionInfo['productCount']."�� /  �ܿ� ".$moreCnt."��</div></caption>";
								$bookingInfodetail .= "</table></div>";

								// �޷¿� ���
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
				echo "<tr><td colspan='".($monthDays+2)."' align='center' class=\"firstTd\">����/��Ż ��Ȳ�� �����ϴ�.</td></tr>";
			}
			?>
		</table>

	<? // } ?>
		</div>
	</body>
</html>