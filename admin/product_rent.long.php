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

extract($_POST);

// DB
if( $mode == "save") {
	foreach ( $_POST as $k => $v ) {
		$f = explode("_",$k);
		if( !empty($f[1]) AND $f[1]>0 ) {
			mysql_query("UPDATE rent_long_discount SET rate = '".${"day_".$f[1]}."' WHERE days = '".$f[1]."' ",get_db_conn());
		}
	}
}
?>

<? INCLUDE "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
	// 저장
	function longDisLate( f ){
		f.mode.value = "save";
		f.method = "POST";
		f.submit()
	}
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
														<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">현재위치 : 상품관리 &gt;예약/대여 현황&gt; <span class="2depth_select">환불 정책</span></td>
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
																	<TD><IMG SRC="images/product_longrental_title.gif" ALT="장기렌탈 할인정책" /></TD>
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
																	<TD width="100%" class="notice_blue">장기렌탈시 적용되는 할인 정책을 관리할 수 있습니다.</TD>
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
													<tr><td height="15"></td></tr>
													<tr>
														<td>
															<form name="longdisRateForm">
																<h6 style="margin:0px;padding-bottom:6px;"><img src="images/product_longrental_stitle1.gif" alt="" /></h6>
																<table border="0" cellpadding="0" cellspacing="0" width="100%" class="tableBase">
																	<colgroup>
																		<col width="180">
																		</col>
																	</colgroup>
																	<?
																		$rentalLongDiscountList = rentalLongDiscount();
																		foreach ( $rentalLongDiscountList as $k => $v ) {
																			echo "<tr><th class=\"firstTh\" style=\"padding-left:10px;text-align:left;\"><img width=\"8\" height=\"11\" src=\"images/icon_point2.gif\" border=\"0\" />".$k."일 이상</th>";
																			echo "<td style=\"padding-left:10px;\"><input type=\"text\" value=\"".$v."\" name=\"day_".$k."\" size=\"10\" class=\"input\" /> % 할인</td></tr>";
																		}
																	?>
																</table>
																<input type="hidden" name="mode" value="" />
																<div style="margin-top:20px;text-align:center;"><input type="image" src="images/botteon_save.gif" onclick="longDisLate(this.form);" /></div>
															</form>
														</td>
													</tr>
													<tr><td height="40"></td></tr>
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
																			<tr><td colspan="2" height="20"></td></tr>
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
		</td>
	</tr>
</table>

<?
INCLUDE "copyright.php";
?>