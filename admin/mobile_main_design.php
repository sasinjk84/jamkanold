<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "mo-1";
$MenuCode = "mobile";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################
if($_POST[type]=="update"){
	mysql_query("update tblmobileconfig  set main_item_sort = '".$_POST[item_sort]."'",get_db_conn());
}

$query = "SELECT main_item_sort FROM tblmobileconfig";
$result = mysql_query($query,get_db_conn());
$row = mysql_fetch_array($result);

//str_checked1 str_checked2 ...
if($row[main_item_sort]){
	${"str_checked{$row[main_item_sort]}"} = "checked";
}
?>
<? INCLUDE "header.php"; ?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	if(confirm("�����Ͻ� ���������� �����Ͻðڽ��ϱ�?")) {
		document.form1.type.value="update";
		document.form1.submit();
	}
}
</script>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
							<col width=198></col>
							<col width=10></col>
							<col width=></col>
							<tr>
								<td valign="top"  background="images/leftmenu_bg.gif">
									<? include ("menu_mobile.php"); ?>
								</td>
								<td></td>
								<td valign="top">
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td height="29" colspan="3">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ����ϼ� &gt; <span class="2depth_select">���α��� �����ġ����</span></td>
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
																	<TD><IMG SRC="images/mobile_main_design_title.gif"  ALT=""></TD>
																</tr>
																<tr>
																	<TD width="100%" background="images/title_bg.gif" height="21"></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr>
														<td height="3"></td>
													</tr>
													<tr>
														<td style="padding-bottom:3pt;">
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD><IMG SRC="images/distribute_01.gif"></TD>
																	<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
																	<TD><IMG SRC="images/distribute_03.gif"></TD>
																</TR>
																<TR>
																	<TD background="images/distribute_04.gif"></TD>
																	<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																	<TD width="100%" class="notice_blue">����ϼ��θ� ����ȭ�� ��������� ��¼����� �����ϽǼ� �ֽ��ϴ�.</TD>
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
													<tr>
														<td height="20"></td>
													</tr>

													<tr>
														<td>
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD><IMG SRC="images/mobile_main_design_stitle.gif"  ALT="���� ������� ��¼���"></TD>
																	<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
																	<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr>
														<td height="3"></td>
													</tr>
													<form name="form1" action="<?=$_SERVER["PHP_SELF"]?>" method="post">
													<input type="hidden" name="type">
													<tr>
														<td>
															<table cellpadding="0" cellspacing="0" width="100%">
																<tr>
																	<td width="100%" bgcolor="#ededed" style="padding:4pt;">
																		<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
																			<tr>
																				<td width="100%">
																					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																						<TR>
																							<TD width="100%" height="30" background="images/blueline_bg.gif"><p align="center"><b><font color="#333333">���α������ ��¼��� �����ϱ�</font></b></TD>
																						</TR>
																						<TR>
																							<TD width="100%" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
																						</TR>
																						<tr>
																							<td height="3"></td>
																						</tr>
																						<TR>
																							<TD width="100%" style="padding:30pt;">
																								<table cellpadding="0" cellspacing="0" border="0" width="510"  align="center">
																									<tr>
																										<td style="padding-right:30px">
																											<table cellpadding="0" cellspacing="0" width="" border="0">
																												<tr>
																													<td>
																														<img src="http://www.getmall.co.kr/images/mobile_main/main01.jpg" border=0 width="150" height="180" style='border:1 dotted #FFFFFF'  class="imgline1">
																													</td>
																												</tr>
																												<tr>
																													<td align="center"><input type="radio" name="item_sort" value="1" <?=$str_checked1?> /> ����Ÿ��#1</td>
																												</tr>
																											</table>
																										</td>

																										<td style="padding-right:30px">
																											<table cellpadding="0" cellspacing="0" width="" border="0">
																												<tr>
																													<td>
																														<img src="http://www.getmall.co.kr/images/mobile_main/main02.jpg" border=0 width="150" height="180" style='border:1 dotted #FFFFFF'  class="imgline1">
																													</td>
																												</tr>
																												<tr>
																													<td align="center"><input type="radio" name="item_sort" value="2" <?=$str_checked2?> /> ����Ÿ��#2</td>
																												</tr>
																											</table>
																										</td>

																										<td>
																											<table cellpadding="0" cellspacing="0" width="" border="0">
																												<tr>
																													<td>
																														<img src="http://www.getmall.co.kr/images/mobile_main/main03.jpg" border=0 width="150" height="180" style='border:1 dotted #FFFFFF'  class="imgline1">
																													</td>
																												</tr>
																												<tr>
																													<td align="center"><input type="radio" name="item_sort" value="3" <?=$str_checked3?> /> ����Ÿ��#3</td>
																												</tr>
																											</table>
																										</td>
																									</tr>
																									<tr>
																										<td height="30" colspan="3" align="center"></td>
																									</tr>
																									<tr>
																										<td style="padding-right:30px">
																											<table cellpadding="0" cellspacing="0" width="" border="0">
																												<tr>
																													<td>
																														<img src="http://www.getmall.co.kr/images/mobile_main/main04.jpg" border=0 width="150" height="180" style='border:1 dotted #FFFFFF'  class="imgline1">
																													</td>
																												</tr>
																												<tr>
																													<td align="center"><input type="radio" name="item_sort" value="4" <?=$str_checked4?> /> ����Ÿ��#4</td>
																												</tr>
																											</table>
																										</td>

																										<td style="padding-right:30px">
																											<table cellpadding="0" cellspacing="0" width="" border="0">
																												<tr>
																													<td>
																														<img src="http://www.getmall.co.kr/images/mobile_main/main05.jpg" border=0 width="150" height="180" style='border:1 dotted #FFFFFF'  class="imgline1">
																													</td>
																												</tr>
																												<tr>
																													<td align="center"><input type="radio" name="item_sort" value="5" <?=$str_checked5?> /> ����Ÿ��#5</td>
																												</tr>
																											</table>
																										</td>

																										<td>
																											<table cellpadding="0" cellspacing="0" width="" border="0">
																												<tr>
																													<td>
																														<img src="http://www.getmall.co.kr/images/mobile_main/main06.jpg" border=0 width="150" height="180" style='border:1 dotted #FFFFFF'  class="imgline1">
																													</td>
																												</tr>
																												<tr>
																													<td align="center"><input type="radio" name="item_sort" value="6" <?=$str_checked6?> /> ����Ÿ��#6</td>
																												</tr>
																											</table>
																										</td>
																									</tr>
																									<tr>
																										<td width="100%" colspan="3" height="25"><hr size="1" noshade color="#EBEBEB"></td>
																									</tr>

																								</table>
																							</TD>
																						</TR>
																					</TABLE>
																				</td>
																			</tr>
																		</table>
																	</TD>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td height=10></td>
													</tr>
													<tr>
														<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
													</tr>
													</form>

													<tr>
														<td height=20></td>
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
																	<TD background="images/manual_left1.gif"></TD>
																	<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
																		<table cellpadding="0" cellspacing="0" width="100%">

																			<tr>
																				<td height="20" colspan="2"></td>
																			</tr>
																			<tr>
																				<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																				<td width="100%"><span class="font_dotline">���α��� �����ġ ����</span></td>
																			</tr>
																			<tr>
																				<td width="20" align="right">&nbsp;</td>
																				<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">
																				- ����ϼ� ���α����� �����Ͻ� �� �ֽ��ϴ�.<br/>
																				- �ٷΰ��� �޴��� ��� "�ٷΰ���޴� ����" ���� �������� ������ ��� ���ο��� �ش� �޴��� ������� �ʽ��ϴ�.<br/>
																				- ���ϴ� ���α��� ������ "�����ϱ�"��ư�� Ŭ���Ͻø� ����˴ϴ�.
																				</td>
																			</tr>
																			<tr>
																				<td height="20" colspan="2"></td>
																			</tr>


																		</table>
																	</TD>
																	<TD background="images/manual_right1.gif"></TD>
																</TR>
																<TR>
																	<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
																	<TD COLSPAN=3 background="images/manual_down.gif"></TD>
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
<? INCLUDE "copyright.php"; ?>