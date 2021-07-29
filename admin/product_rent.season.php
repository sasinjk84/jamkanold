<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-10-21
 * Time: 오후 5:50
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### 페이지 접근권한 check ###############
$PageCode = "pr-1";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################


//extract($_REQUEST);


if( $_GET['vdate'] ) {
	$chan_Y =substr($_GET['vdate'],0,4);
	$chan_M =substr($_GET['vdate'],4,2);
	$chan_D =substr($_GET['vdate'],6,2);
	$vdate = $_GET['vdate'];
} else {
	$chan_Y=date("Y");
	$chan_Y=date("Y");
	$chan_M=date("m");
	$chan_D=date("d");
	$vdate = $chan_Y.$chan_M.$chan_D;
}

$chkDate = $chan_Y."년 ".($chan_M?$chan_M."월 ":"").($chan_D?$chan_D."일":"");

$t=mktime(0,0,0,$chan_M,1,$chan_Y);
$week=date("w",$t);
$lastday=date("t",$t);
$day=1;


?>

<? INCLUDE "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script language="JavaScript">
	<!--
	// 캘린더 년/월 바꿈
	function dateChg ( date ) {
		location.href="?vdate="+date;
	}

	// 주말 요금 적용 (모든 토,일요일)
	function changeDayPrice ( day, chk, vdate ) {
		location.href="?vdate="+vdate+"&day="+day+"&chk="+chk;
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
														<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 상품 &gt; 예약/렌탈 관리 &gt; <span class="2depth_select">시즌(성수기) 관리</span></td>
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
																	<TD><IMG SRC="images/product_season_title.gif" ALT="시즌(성수기) 관리"></TD>
																</tr>
																<tr>
																	<TD width="100%" background="images/title_bg.gif" height="21"></TD>
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
																	<TD width="100%" class="notice_blue">성수기/준성수기, 주말/공휴일 요금을 관리하실 수 있습니다.</TD>
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

													<tr><td height="20"></td></tr>
													<tr>
														<td>
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD><IMG SRC="images/product_season_stitle1.gif" ALT="성수기/주말요금 등록" /></TD>
																	<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
																	<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr><td height="3"></td></tr>
													<tr>
														<td>
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD><IMG SRC="images/distribute_01.gif"></TD>
																	<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																	<TD><IMG SRC="images/distribute_03.gif"></TD>
																</TR>
																<TR>
																	<TD background="images/distribute_04.gif"></TD>
																	<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																	<TD width="100%" class="notice_blue">
																		1) 주말요금(토/일요일) 일괄적용시 해당 월의 모든 주말요금이 적용됩니다.<br />
																		2) 주말요금을 별도로 적용하실 경우 공휴일/주말요금 등록에서 주말 일자를 별도로 입력하시면 됩니다.<br />
																		3) 요금적용 순서는 <strong>주말(공휴일)요금 > 성수기 > 준성수기 > 비수기 순서</strong>로 적용됩니다.
																	</TD>
																	<TD background="images/distribute_07.gif"></TD>
																</TR>
																<TR>
																	<TD><IMG SRC="images/distribute_08.gif"></TD>
																	<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																	<TD><IMG SRC="images/distribute_10.gif"></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr><td height="5"></td></tr>

													<tr>
														<td>
															<!-- 전체 설정 -->
															<table cellpadding="0" cellspacing="0" width="100%" style="border:0px solid #CDDDE0; margin-bottom:10px;">
																<colgroup>
																	<col width="145" />
																	<col width="" />
																</colgroup>
																<tr><td background="images/table_top_line.gif" colSpan="2" style="height:1px" /></td></tr>
																<? /*
																<tr>
																	<td class="table_cell"><img width="8" height="11" src="images/icon_point2.gif" border="0"/> 성수기/준성수기 등록</td>
																	<td class="td_con1">
																		<input type="button" value="성수기/준성수기 등록하기" onclick="window.open( 'product_seasonpop.php', 'busySeasonPop', 'width=800,height=600' );">
																	</td>
																</tr>
																
																<tr><td background="images/table_con_line.gif" colSpan="2" /></tr>
																*/ ?>
																<tr>
																	<td class="table_cell"><img width="8" height="11" src="images/icon_point2.gif" border="0"/>등록/관리</td>
																	<td class="td_con1">
																		<input type="button" value="휴일 및 주말 요금적용 관리하기" onclick="window.open( 'product_holiday.php', 'holidayPop', 'width=800,height=600' );">
																	</td>
																</tr>
																<tr><td background="images/table_top_line.gif" colSpan="2" style="height:1px" /></td></tr>
															</table>
															<!-- 전체 설정 끝 -->


															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<tr><td height="20"></td></tr>
																<TR>
																	<TD><IMG SRC="images/product_season_stitle2.gif" ALT="성수기/주말요금 현황보기" /></TD>
																	<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
																	<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																</TR>
															</TABLE>
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD><IMG SRC="images/distribute_01.gif"></TD>
																	<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																	<TD><IMG SRC="images/distribute_03.gif"></TD>
																</TR>
																<TR>
																	<TD background="images/distribute_04.gif"></TD>
																	<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																	<TD width="100%" class="notice_blue">
																		1) 성수기와 주말요금이 겹칠 경우 주말요금이 우선 적용됩니다.<br />
																		2) 공휴일 요금은 주말요금과 동일하게 적용됩니다.
																	</TD>
																	<TD background="images/distribute_07.gif"></TD>
																</TR>
																<TR>
																	<TD><IMG SRC="images/distribute_08.gif"></TD>
																	<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
																	<TD><IMG SRC="images/distribute_10.gif"></TD>
																</TR>
																<tr><td height="5"></td></tr>
															</TABLE>

															<!-- 달력 시작 -->
															<!-- 년월 달력 -->
															<table cellpadding="0" cellspacing="0" width="100%" style="border:0px solid #CDDDE0; margin-bottom:15px;">
																<colgroup>
																	<col width="145" />
																	<col width="100" />
																	<col width="" />
																</colgroup>
																<tr><td background="images/table_top_line.gif" colSpan="3" /></td></tr>
																<tr>
																	<td class="table_cell"><img width="8" height="11" src="images/icon_point2.gif" border="0"/> 기간선택</td>
																	<td class="td_con1" align="center">
																		<select onchange="dateChg( this.value);">
																			<?
																			for($Yi=2012;$Yi<=date("Y")+2;$Yi++){
																				$sel = "";
																				if( $Yi.$chan_M==$chan_Y.$chan_M ) $sel = "selected";
																				echo "<option value=".$Yi.$chan_M." ".$sel.">".$Yi."</option>";
																			}
																			?>
																		</select>
																		년
																	</td>
																	<td class="td_con1" id='calendarMonth' height="100%" style="padding:0px;border:0px;">
																		<table cellpadding="0" cellspacing="0" border="0" height="100%">
																			<?
																			for($Mi=1;$Mi<=12;$Mi++){
																				$sel = ($Mi==$chan_M)?"background:#ff3300;color:#ffffff;font-weight:700;":"";
																				echo "<td class=\"td_con1\" onclick=\"dateChg('".$chan_Y.str_pad($Mi, 2, "0", STR_PAD_LEFT)."');\" align='center' style=\"padding:0px 10px;".$sel."cursor:pointer;\">".str_pad($Mi, 2, "0", STR_PAD_LEFT)."월</td>";
																			}
																			?>
																		</table>
																	</td>
																</tr>
																<tr><td background="images/table_top_line.gif" colSpan="3" /></td></tr>
															</table>
															<!-- 년월 달력 끝 -->
															<!-- 달력 테이블 시작 -->
															<?
															if( $chan_M > 0 ) {
																$chan_M=str_pad($chan_M, 2, "0", STR_PAD_LEFT);
																$hdays = rentHolidayMonth($chan_Y.$chan_M,$_REQUEST['code']);
																$season = rentBusySeasonRange($_REQUEST['code'],$chan_Y.$chan_M);
																?>
																<table width="100%" cellpadding="0" cellspacing="0" align="center" class="tableBase">
																	<Tr height="25" bgcolor="#F3F3F3" ALIGN="CENTER">
																		<Td width='15%' style='font-family:verdana; color:#FF000A;'>SUN</td>
																		<Td width='14%' style='font-family:verdana; color:#000000;'>MON</td>
																		<Td width='14%' style='font-family:verdana; color:#000000;'>TUE</td>
																		<Td width='14%' style='font-family:verdana; color:#000000;'>WED</td>
																		<Td width='14%' style='font-family:verdana; color:#000000;'>THU</td>
																		<Td width='14%' style='font-family:verdana; color:#000000;'>FRI</td>
																		<Td width='15%' style='font-family:verdana; color:#5635FF;'>SAT</td>
																	</tr>
																	<?
																	$statrstamp = strtotime($chan_Y.'-'.$chan_M.'-01');
																	$loop = ceil((date('t',$statrstamp)+ date('w',$statrstamp))/7);
																	for($i=1; $i<=$loop; $i++){
																		echo"<Tr>";
																		for($j=0; $j<=6; $j++){
																			$Cday=str_pad($day, 2, "0", STR_PAD_LEFT);
																			$DATE_KEY = $chan_Y.$chan_M.$Cday;

																			//체크데이트 (토,일, 오늘 선택일)
																			if( $DATE_KEY==$vdate ) {
																				$today_print_content="#F6F6C2";
																			} else {
																				$today_print_content="#FFFFFF";
																			}
																			if($week==$j || $day>1){
																				if($day <= $lastday){
																					if($j==0){
																						echo "<Td class=\"firstTd\"height=50 valign='top' align='center' bgcolor='".$today_print_content."'>";
																					}else{
																						echo "<Td height=50 valign='top' align='center' bgcolor='".$today_print_content."'>";
																					}
																					echo "<DIV style='width:100%; height:100%;' onclick=\"dateChg( ".$DATE_KEY.", moreAll.value);\" >";
																					echo "<p style=\"margin:0px;padding:0px 8px;text-align:left;\">";
																					if($j==0) echo "<span style=\"color:#FF356D;\">";
																					if($j==6) echo "<span style=\"color:#5635FF;\">";
																					echo $day;
																					echo "</span>";
																					echo "</p>";

																					/** 요금제 적용 정보
																					 * 순위 : 공유일지정주말요금제 적용 > 주말요금적용 > 시즌요금제
																					 */
																					$dayPriceChk = false;

																					// 주말요금제 적용
																					//$holiday = rentHolidayInfo($DATE_KEY);

																					if( ( $j==6 AND $seasonSet['sat'] ) OR ( $j==0 AND $seasonSet['sun'] ) AND ( $dayPriceChk == false ) ) {
																						echo "<div>주말요금적용</div>";
																						$dayPriceChk = true;
																					}

																					//if ( ( $holiday['idx'] > 0 ) AND ( $dayPriceChk == false ) ) {
																					if ( (isset($hdays['days'][$Cday])) AND ( $dayPriceChk == false ) ) {
																						echo "<div>[".$hdays['days'][$Cday]."] 주말요금적용</div>";
																						$dayPriceChk = true;
																					}
																					/*
																					// 성수기
																					$busySeason = rentBusySeasonInfo("busy",$DATE_KEY);
																					if( ( $busySeason['idx'] > 0 ) AND ( $dayPriceChk == false ) ) {
																						echo "<div>성수기요금적용</div>";
																						$dayPriceChk = true;
																					}

																					// 준성수기
																					$busySeason = rentBusySeasonInfo("semi",$DATE_KEY);
																					if( ( $busySeason['idx'] > 0 ) AND ( $dayPriceChk == false ) ) {
																						echo "<div>준성수기요금적용</div>";
																						$dayPriceChk = true;
																					}
																					*/
																					if($dayPriceChk == false){
																						if(in_array($Cday,$season['busy'])) echo "<div>성수기요금적용</div>";
																						else if(in_array($Cday,$season['semi'])) echo "<div>준성수기요금적용</div>";									
																						$dayPriceChk = true;
																					}
																					echo "</DIV>";
																					$day++;
																				}else{
																					echo "<Td valign='top' align='center' bgcolor='#CCCCCC'>";
																				}
																			}else{
																				echo "<Td valign='top' align='center' bgcolor='#CCCCCC'>";
																			}
																			echo "</td>\n";
																		}
																		echo"</tr>\n";
																	}
																	?>
																</table>
															<?
															}
															?>
															<!-- 달력 테이블 끝 -->
															<!-- 달력 끝 -->


														</td>
													</tr>


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
																				<td ><span class="font_dotline">설명</span></td>
																			</tr>
																			<tr>
																				<td width="20" align="right">&nbsp;</td>
																				<td  class="space_top">- 설명내용</td>
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
													<tr>
														<td height="50"></td>
													</tr>
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
		</td>
	</tr>
</table>


<?
INCLUDE "copyright.php";
?>
