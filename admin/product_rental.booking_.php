<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

extract($_GET);
//_pr($_GET);
// ���� ���� ����Ʈ
$venderList = venderList("vender,id,com_name");

// �뿩 ����� ���� ����Ʈ
$value = array("display"=>1); //, "type"=>"B"
$localList = rentLocalList( $value );
//_pr($localList);



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
?>

<? INCLUDE "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
	<!--

	// ���콺 ����ٴϴ� ���̾�
	jQuery(document).ready(function(){
		$(document).mousemove(function(e){
			var leftP = ( ( $(document).width() - 300 ) < e.pageX ) ? $(document).width()-330 : e.pageX ;
		   $('#viewInfo').css("left",leftP-13);
		   $('#viewInfo').css("top",e.pageY+15);
		});
	})

	// ���̾� ����ä�� ���̱�
	function viewInfo( idx ) {
		$('#viewInfo').css("display","block");
		$('#viewInfo').html($('#bookingInfo_'+idx).html());
	}

	// ���̾� ������� ������
	function offInfo( idx ) {
		$('#viewInfo').css("display","none");
		$('#viewInfo').html("");
	}
	-->
</script>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
<td valign="top">
<table cellpadding="0" cellspacing="0" width=100%>
<tr>
<td>

<table cellpadding="0" cellspacing="0" width="100%"  background="images/con_bg.gif">
<tr>
<td valign="top"  background="images/leftmenu_bg.gif" width=198>
	<? include ("menu_product.php"); ?>
</td>

<td width=10></td>
<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td height="29" colspan="3">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt;����/�뿩 ��Ȳ&gt; <span class="2depth_select">���� ����</span></td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
	<td background="images/con_t_01_bg.gif"></td>
	<td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
</tr>
<tr>
	<td width="16" background="images/con_t_04_bg1.gif"></td>
	<td bgcolor="#ffffff" style="padding:10px">


		<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/product_rental_title.gif" ALT="���� ��Ȳ"></TD>
						</tr>
						<tr>
							<TD width="100%" background="images/title_bg.gif" height=21></TD>
						</TR>
					</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/distribute_01.gif"></TD>
							<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
							<TD><IMG SRC="images/distribute_03.gif"></TD>
						</TR>
						<TR>
							<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" ></TD>
							<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
							<TD width="100%" class="notice_blue">���� ���¸� Ȯ���ϰ� �����մϴ�.</TD>
							<TD background="images/distribute_07.gif"><IMG SRC="images/distribute_07.gif" ></TD>
						</TR>
						<TR>
							<TD><IMG SRC="images/distribute_08.gif"></TD>
							<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
							<TD><IMG SRC="images/distribute_10.gif"></TD>
						</TR>
					</TABLE>
				</td>
			</tr>
			<tr>
				<td>
					<br><strong>������Ȳ</strong>
					<br>
					[��] [��] [��]
					<hr>
					[<a href="javascript:document.location.reload();">���ΰ�ħ</a>]
					[<a href="?vdate=<?=$prv?>">������</a>]
					<?=$selY?>.<?=$selM?>
					[<a href="?vdate=<?=$nxt?>">������</a>]
					[<a href="?vdate=<?=$curY.$curM?>">���� ����(�̹���)</a>]
					<br>
					<hr>

					<div id="viewInfo" style="display:none;position:absolute; left:100; top:100; width:300; height:0; z-index:999;background:#ffffff;"></div>
					<table border="0" cellspacing="0" cellpadding="0" class="tableBase">
						<tr>
							<th class="firstTh">��ǰ��/��������</th>
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

							//foreach ( $localList as $v ) {

						/*
							$productSQL = "
									SELECT
										ps.*, p.productname, p.quantity
									FROM
										rent_product_schedule as ps
										INNER JOIN tblproduct as p ON p.`pridx` = ps.`pridx`
									WHERE
										ps.bookingStartDate LIKE '".$selY."-".$selM."%'
										OR
										ps.bookingEndDate LIKE '".$selY."-".$selM."%'
									GROUP BY ps.pridx
							";
							$productRes = mysql_query( $productSQL,get_db_conn());
							//while ( $productRow = mysql_fetch_assoc( $productRes ) ) {
						*/

						$bookingProductList = bookingProductList('M',$selY.$selM);

						$productOptionList = array();
						//_pr($bookingProductList);
						foreach ( $bookingProductList as $k=>$v) {
							$productOptionList[$v['pridx']]['productname'] = $v['productname'];
							foreach ( $v['rentOpt'] as $vv ) {
								$productOptionList[$v['pridx']]['option'][$vv['idx']]['optionIdx'] = $vv['idx'];
								$productOptionList[$v['pridx']]['option'][$vv['idx']]['optionName'] = $vv['optionName'];
								$productOptionList[$v['pridx']]['option'][$vv['idx']]['orderCnt'] = $vv['orderCnt'];
							}
						}

						//_pr($productOptionList);

						foreach ( $productOptionList as $pridx => $product ) {
							$optinCnt = count($product['option']);
							$optinCntSet = 1;
							foreach ( $product['option'] as $productOptionKey => $productOption ) {

								?>
								<tr>
									<? if( $optinCntSet == 1 ) { ?><td class="firstTd" align="left" rowspan="<?=$optinCnt?>"><?=$product['productname']?></td><? $optinCntSet++; } ?>
									<td align="left"><?=$productOption['optionName']?></td>
									<?
									for( $dd = 1 ; $dd <= $monthDays ; $dd++) {

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

										$bookingInfo = array();
										$bookingInfo[idx] = $schdROW[idx];
										$bookingInfo[local] = $schdROW[location];
										$bookingInfo[sdate] = $schdROW[bookingStartDate];
										$bookingInfo[edate] = $schdROW[bookingEndDate];
										$bookingInfo[status] = $schdROW[status];
										$bookingInfo[cnt] = $schdROW[cnt];
										$bookingInfo[name] = "ȫ�浿";
										$bookingInfo[tel] = "010-1234-5678";

										// ��¥ ���
										/*
										$selDay = strtotime($selDate);
										$bookingStart = strtotime($bookingInfo[sdate]." 00:00:00");
										$bookingEnd = strtotime($bookingInfo[edate]." 23:59:59");
										$selMonthStartDay = strtotime($selY."-".$selM."-01 00:00:00");
										$selMonthEndDay = strtotime($selY."-".$selM."-".$monthDays." 23:59:59");

										// ������ ���ʿ� ���� �������
										$bookingDisplayDays = ceil(($bookingEnd - $bookingStart) / 86400);
										$monthEndMore = "";
										$monthStartMore = "";
										if ( $bookingEnd > $selMonthEndDay ) {
											$bookingDisplayDays = ceil($bookingDisplayDays - ( ( $bookingEnd - $selMonthEndDay ) / 86400 ));
											$monthEndMore = "��";
										}
										if ( $bookingStart < $selMonthStartDay ) {
											$bookingDisplayDays = ceil($bookingDisplayDays - ( ( $selMonthStartDay - $bookingStart ) / 86400 ));
											$monthStartMore = "��";
										}
										*/

										//if( $bookingInfo[local] == $v[location] AND $bookingStart <= $selDay AND $selDay < $bookingEnd ) {
										if( _array($schdROW) ) {

											//_pr($schdROW);
											//echo $schdROW." / ";



											$bookingInfodetail = "<div id=\"bookingInfo_".$selY.$selM.$dd.$productOption['optionIdx']."\" style=\"display:none;\"><table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#FFFFFF\" border=\"0\" class=\"tableBaseSe\" style=\"border:1px solid #dddddd;\">";

											$optCntSum = 0;
											foreach ( $schdROW as $scdValue ) {
												//�� ����
												$optCntSum += $scdValue['optCnt'];
												$bookingStart = strtotime($scdValue[bookingStartDate]." 00:00:00");
												$bookingEnd = strtotime($scdValue[bookingEndDate]." 23:59:59");
												$bookingInfodetail .= "
												<tr><th><img src=\"images/icon_point2.gif\" align=\"absmiddle\" border=\"0\" />�����ڵ�</th><td>" . $scdValue[idx] . "</td></tr>
												<tr><th><img src=\"images/icon_point2.gif\" align=\"absmiddle\" border=\"0\" />������</th><td>" . $scdValue[name] . "</td></tr>
												<tr><th><img src=\"images/icon_point2.gif\" align=\"absmiddle\" border=\"0\" />����ó</th><td>" . $scdValue[tel] . "</td></tr>
												<tr><th><img src=\"images/icon_point2.gif\" align=\"absmiddle\" border=\"0\" />������</th><td>" . date("Y.m.d", $bookingStart) . " ~ " . date("Y.m.d", $bookingEnd) . "</td></tr>
												<tr><th class=\"lastTh\"><img src=\"images/icon_point2.gif\" align=\"absmiddle\" border=\"0\" />�������</th><td class=\"lastTd\">[" . $scdValue[status] . "]" . $bookingStatus[$scdValue[status]] . "</td></tr>
											";
											}
											$moreCnt = $productOptionInfo['productCount'] - $optCntSum;
											$bookingInfodetail .= "<caption><div style=\"float:left;font-weight:700;\">" . $selDate . " �뿩 ����</div><div style=\"float:right;\">�� ".$productOptionInfo['productCount']."�� /  �ܿ� ".$moreCnt."��</div></caption>";
											$bookingInfodetail .= "</table></div>";

											// �޷¿� ���
											//echo "<td colspan=\"".$bookingDisplayDays."\" onmouseover=\"viewInfo('".$bookingInfo[idx]."');\" onmouseout=\"offInfo('".$bookingInfo[idx]."');\">".$monthStartMore."[".$bookingInfo[status]."]".$monthEndMore.$bookingInfodetail."</td>";
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
						?>
					</table>



					<hr>



					<table border="1" cellspacing="0" cellpadding="5" width="100%">
						<tr align="center">
							<td>��Ī</td>
							<td>�뿩 �Ⱓ</td>
							<td>�뿩�ڸ�</td>
							<td>����ó</td>
							<td>�ݾ�</td>
							<td>����</td>
							<td>�����</td>
						</tr>
						<?
						/*
						$sqlWhere = array();
						array_push($sqlWhere, " ( ( s.`bookingStartDate` >= ".$bookingStartDate." AND s.`bookingStartDate` <= ".$bookingEndDate." ) OR ( s.`bookingEndDate` >= ".$bookingStartDate." AND s.`bookingEndDate` <= ".$bookingEndDate." ) ) ");


						// ����� �˻�
						if( $srchLocal > 0 ){
							array_push($sqlWhere, " s.`location` = ".$srchLocal." ");
						}

						// Ű���� �˻�
						if ( strlen($srchTxt) > 0 ) {
							array_push($sqlWhere, " ( o.`id` LIKE '%".$srchTxt."%' OR o.`sender_name` LIKE '%".$srchTxt."%' OR o.`sender_tel` LIKE '%".$srchTxt."%' ) ");
						}

						$sqlWhere = " WHERE `type` = 'B' ".implode(" AND ", $sqlWhere);

						$sqlJoin = "INNER JOIN `tblorderinfo` as o ON o.`ordercode` = s.`ordercode` ";
						$prtJoin = ", o.`id`, o.`price`, o.`sender_name`, o.`sender_tel` ";

						$schdSQL = "SELECT s.*".$prtJoin." FROM `rent_product_schedule` as s ".$sqlJoin.$sqlWhere;

						$schdRES=mysql_query($schdSQL,get_db_conn());

						// ����¡����
						$total = mysql_result($schdRES,0,0);
						$page = _isInt($_REQUEST['page'])?intval($_REQUEST['page']):1;
						$perpage = _isInt($_REQUEST['perpage'])?intval($_REQUEST['perpage']):10;
						$total_page = max(1,@ceil($total/$perpage));
						$page = min($page,$total_page);
						$schdSQL .= " limit ".($page-1)*$perpage.','.$perpage;

						$schdRES=mysql_query($schdSQL,get_db_conn());
						while ( $schdROW =mysql_fetch_assoc($schdRES) ) {
						*/

						$selDate = $selY."-".$selM."-";
						$productSchdListData = array();
						$productSchdListData['dateStart'] = $selDate.date("t",$selDate);
						$productSchdListData['dateEnd'] = $selDate."01";
						$schdROW = productScheduleList( $productSchdListData );

						foreach ( $schdROW as $scdValue ) {
							echo "
								<tr align=\"center\">
									<td>".$scdValue['location']."</td>
									<td>".$scdValue['bookingStartDate']." ~ ".$scdValue['bookingEndDate']."</td>
									<td>".$scdValue['sender_name']."</td>
									<td>".$scdValue['sender_tel']."</td>
									<td>".number_format($scdValue['price'])."</td>
									<td>".$bookingStatus[$scdValue['status']]."</td>
									<td>".$scdValue['regDate']."</td>
								</tr>
							";
						}
						?>
						<tr align=\"center\">
							<td colspan="7" align="center">
								<?
								//$pages = new pages(array('total_page'=>$total_page,'page'=>$page,'pageblocks'=>10,'links'=>"/admin/product_rental.booking.list.php?page=%u"));
								//echo $pages->_solv()->_result('fulltext');
								?>
							</td>
						</tr>
					</table>



				</td>
			</tr>

			<tr><td height="20"></td></tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
							<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
							<TD width="100%" background="images/manual_bg.gif"></TD>
							<TD background="images/manual_bg.gif"></TD>
							<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
						</TR>
						<TR>
							<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
							<TD COLSPAN=3 width="100%" valign="top" bgcolor="#FFFFFF" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
								<table cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
										<td ><span class="font_dotline">����</span></td>
									</tr>
									<tr>
										<td width="20" align="right">&nbsp;</td>
										<td class="space_top">
											- ���� ��û �� �Ϸ�� ��ǰ ������ ������ ���� �ľ��� �� �ֽ��ϴ�.<br />
											- �� ��ǰ�� �������ڿ� ���콺�� �ø��ø� ������� ���������� Ȯ���� �� �ֽ��ϴ�.
										</td>
									</tr>
									<tr>
										<td colspan="2" height="20"></td>
									</tr>
								</table>
							</TD>
							<TD background="images/manual_right1.gif"><IMG SRC="images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
						</TR>
						<TR>
							<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
							<TD COLSPAN=3 background="images/manual_down.gif"><IMG SRC="images/manual_down.gif" WIDTH="4" HEIGHT=8 ALT=""></TD>
							<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
						</TR>
					</TABLE>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
		</table>
	</td>
	<td width="16" background="images/con_t_02_bg.gif"></td>
</tr>
<tr>
	<td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
	<td background="images/con_t_04_bg.gif"></td>
	<td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
</tr>
<tr><td height="20"></td></tr>
</table>


</td>
</tr>
</table>
</td>
</tr>
</table>


<?
INCLUDE "copyright.php";
?>
