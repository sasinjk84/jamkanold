<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "co-1";
$MenuCode = "community";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 15;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];
$scheck=$_REQUEST["scheck"];
$search=$_REQUEST["search"];

if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

$mode=$_POST["mode"];
$up_personal=$_POST["up_personal"];
$idxs=$_POST["idxs"];

if($mode=="update" && strlen($up_personal)>0) {
	$sql = "UPDATE tblshopinfo SET personal_ok='".$up_personal."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script>alert(\"1:1 �� �Խ��� ��뿩�� ������ ����Ǿ����ϴ�.\");</script>";
} else if($mode=="delete" && strlen($idxs)>0) {
	$sql = "DELETE FROM tblpersonal WHERE idx IN (".$idxs.") ";
	mysql_query($sql,get_db_conn());
	$onload="<script>alert(\"�����Ͻ� �Խù��� �����Ͽ����ϴ�.\");</script>";
}

$sql = "SELECT personal_ok FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
mysql_free_result($result);

$personal_ok=$row->personal_ok;

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function Search(form) {
	if(form.search.value.length==0) {
		alert("�˻�� �Է��ϼ���.");
		form.search.focus();
		return;
	}
	form.submit();
}

function search_default(){
	form2.scheck.value = "";
	form2.search.value = "";
	form2.submit();
}

function CheckAll(){
	chkval=document.form2.allcheck.checked;
	try {
		cnt=document.form2.delcheck.length;
		for(i=1;i<=cnt;i++){
			document.form2.delcheck[i].checked=chkval;
		}
	} catch(e) {}
}

function CheckDelete(form) {
	try {
		idxs="";
		for(i=1;i<form.delcheck.length;i++) {
			if(form.delcheck[i].checked==true) {
				idxs+=","+form.delcheck[i].value;
			}
		}
		if(idxs.length==0) {
			alert("������ �Խù��� �����ϼ���.");
			return;
		}
		if(confirm("�����Ͻ� �Խù��� �����Ͻðڽ��ϱ�?")) {
			idxs=idxs.substring(1,idxs.length);
			form.mode.value="delete";
			form.idxs.value=idxs;
			form.submit();
		}
	} catch(e){}
}

function update_submit(form) {
	form.mode.value='update';
	form.submit();
}

function Search_id(id) {
	document.form2.scheck.selectedIndex=0;
	document.form2.search.value=id;
	document.form2.submit();
}

function ViewPersonal(idx) {
	window.open("about:blank","personal_pop","width=600,height=550,scrollbars=yes");
	document.form3.idx.value=idx;
	document.form3.submit();
}

function smsSetting(){
	var _form = document.smsForm;
	_form.submit();
	
}
//-->
</SCRIPT>
<style>
	form{border:0px;padding:0px;margin:0px;}
</style>
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
									<? include ("menu_community.php"); ?>
								</td>
								<td></td>
								<td valign="top">
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td height="29" colspan="3">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : Ŀ�´�Ƽ &gt; Ŀ�´�Ƽ ����  &gt; <span class="2depth_select">1:1�� �Խ��� ����</span></td>
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
												<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
													<input type=hidden name=mode>
													<table cellpadding="0" cellspacing="0" width="100%">
														<tr><td height="8"></td></tr>
														<tr>
															<td>
																<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																	<TR>
																		<TD><IMG SRC="images/community_personal_title.gif"  ALT=""></TD>
																		</tr><tr>
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
																		<TD background="images/distribute_04.gif"></TD>
																		<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																		<TD width="100%" class="notice_blue">1:1 ������ �Խ��� ���� �� ���ǿ� ���� �亯 ������ �Ͻ� �� �ֽ��ϴ�.</TD>
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
														<tr><td height="20"></td></tr>
														<tr>
															<td><IMG SRC="images/community_personal_stitle1.gif"  ALT=""></td>
														</tr>
														<tr>
															<td height="5"></td>
														</tr>
														<tr>
															<td>
																<table cellpadding="0" cellspacing="0" width="100%">
																	<TR>
																		<TD bgcolor="#B9B9B9" height="1"></TD>
																	</TR>
																	<tr>
																		<td>
																			<table cellpadding="0" cellspacing="0" width="100%">
																			<col width="170"></col>
																			<col width=""></col>
																				<TR>
																					<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>1:1������ �Խ��� ��� ����</TD>
																					<TD class="td_con1"><INPUT id=idx_personal0 type=radio value=Y name=up_personal <?if($personal_ok=="Y")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_personal0>1:1 �� �Խ��� ���</LABEL>&nbsp;&nbsp;
																<INPUT id=idx_personal1 type=radio value=N name=up_personal <?if($personal_ok=="N")echo"checked";?> style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_personal1>1:1 �� �Խ��� ������</LABEL>
																					</TD>
																				</TR>
																			</table>
																		</td>
																	</tr>
																	<TR>
																		<TD bgcolor="#B9B9B9" height="1"></TD>
																	</TR>
																</table>
															</td>
														</tr>
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
																		<TD width="100%" class="notice_blue">1) <b><span class="font_orange">1:1 �� �Խ���</span></b>�� ������ <b>���� ���θ� ��ڸ� �� �� �ִ� �Խ���</b>�Դϴ�. <br>2) 1:1 �� �Խ����� ȸ������ ��Ǹ�, MY���������� Ȯ�� �����մϴ�.</TD>
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
														<tr><td height=10></td></tr>
														<tr>
															<td align=center><a href="javascript:update_submit(document.form1);"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
														</tr>
													</table>
												</form>
												<!-- SMS -->
												<?
														$smsSQL = "SELECT type, smsused, leavenumber FROM personalboard_admin LIMIT 0, 1";
														$smstype = $smsused = $smsnumberlist = "";
														$querymode = "ins";
														if(false !== $smsRes = mysql_query($smsSQL, get_db_conn())){
															$smsRowcount = mysql_num_rows($smsRes);
															if($smsRowcount > 0){
																$smstype = mysql_result($smsRes,0,0);
																$smsused = mysql_result($smsRes,0,1);
																$smsnumberlist = mysql_result($smsRes,0,2);
																$querymode = "upd";
															}
														}
														switch($smsused){
															case "Y":
																$smschecked = " checked";
																$smsunchecked ="";
															break;
															default:
																$smschecked = "";
																$smsunchecked =" checked";
															break;
														}
												?>
												
												<form name="smsForm" action="./community_personal_sms_proc.php" method="post">
													<!-- <input type="hidden" name="mode" value="<?=$querymode?>"/> -->
													<table cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td><IMG SRC="images/community_personal_stitle_sms.gif"  ALT="1:1�� �Խ��� SMS �˸� ����"></td>
														</tr>
														<tr>
															<td height="5"></td>
														</tr>
														<tr>
															<td style="border-bottom:1px solid #BBB;border-top:1px solid #BBB;">
																<table cellpadding="0" cellspacing="0" width="100%">
																	<col width="190"></col>
																	<col width=""></col>
																	<TR>
																		<TD class="table_cell" style="border-bottom:1px solid #E3E3E3;border-right:1px solid #E3E3E3;"><img src="images/icon_point2.gif" border="0"><b>1:1�Խ��� ������ �˸� ��뿩��</b></TD>
																		<TD class="td_con1" style="border:0px;border-bottom:1px solid #E3E3E3">
																			<input type="radio" id="checked" name="checksms" value="Y"<?=$smschecked?>><label for="checked">�����</label>
																			<input type="radio" id="unchecked" name="checksms" value="N"<?=$smsunchecked?>><label for="unchecked">������</label>
																			</TD>
																	</TR>
																	<TR>
																		<TD class="table_cell" style="border-right:1px solid #E3E3E3"><img src="images/icon_point2.gif" border="0"><b>1:1�Խ��� ������ ����ó</b></TD>
																		<TD class="td_con1" style="border:0px;">
																			<input type="text" name="numberlist" value="<?=$smsnumberlist?>" style="width:90%;"/>
																		</TD>
																	</TR>
																</table>
															</td>
														</tr>
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
																		1) <b><span class="font_orange">1:1 �� �Խ��� SMS �˸� ����</span></b>�� ���� ������ ��ϵ� ����ó�� �˷� �ִ� ����Դϴ�. <br>
																		2) ����ó ������ ��� ������(-)���� �޸�(,) �� �����Ͽ� �Է��մϴ�. (ex : 00011112222,00022221111)
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
													</table>
												</form>
												<div style="margin-top:5px;text-align:center">
													<a href="javascript:smsSetting();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>
												</div>
												<!-- //SMS -->
												<?
															$colspan=6;
															$sql = "SELECT COUNT(*) as t_count FROM tblpersonal ";
															if(strlen($scheck)>0 && strlen($search)>0) $sql.= "WHERE ".$scheck." LIKE '%".$search."%' ";
															$result = mysql_query($sql,get_db_conn());
															$row = mysql_fetch_object($result);
															mysql_free_result($result);
															$t_count = $row->t_count;
															$pagecount = (($t_count - 1) / $setup[list_num]) + 1;
												?>
												<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
													<input type=hidden name=mode>
													<input type=hidden name=idxs>
													<input type=hidden name=delcheck>
													<table cellpadding="0" cellspacing="0" width="100%">
														<tr>
															<td>&nbsp;</td>
														</tr>
														<tr>
															<td>
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
															<TR>
																<TD><IMG SRC="images/community_personal_stitle2.gif" WIDTH="210" HEIGHT=31 ALT=""></TD>
																<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
																<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
															</TR>
															</TABLE>
															</td>
														</tr>
														<tr>
															<td align="right" style="padding-right:10px;">
															<img src="images/icon_8a.gif" width="13" height="13" border="0">�� �Խù� : <B><?=number_format($t_count)?></B>��, &nbsp; <img src="images/icon_8a.gif" width="13" height="13" border="0">���� <B><?=$gotopage?>/<?=ceil($t_count/$setup[list_num])?></B> ������
															</td>
														</tr>
														<tr><td height=2></td></tr>
														<tr>
															<td>
																<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
																	<col width=20></col>
																	<col width=40></col>
																	<col width=></col>
																	<col width=80></col>
																	<col width=100></col>
																	<col width=85></col>
																	<TR>
																		<TD background="images/table_top_line.gif" colspan="<?=$colspan?>" height=1></TD>
																	</TR>
																	<TR align=center>
																		<TD class="table_cell"><INPUT onclick=CheckAll() type=checkbox name=allcheck></TD>
																		<TD class="table_cell1">NO</TD>
																		<TD class="table_cell1">����</TD>
																		<TD class="table_cell1">ȸ����</TD>
																		<TD class="table_cell1">��¥</TD>
																		<TD class="table_cell1">�亯����</TD>
																	</TR>
																	
																	<TR>
																		<TD colspan="<?=$colspan?>" background="images/table_con_line.gif"></TD>
																	</TR>
																	<?
																					$sql = "SELECT idx,id,name,email,ip,subject,date,re_date FROM tblpersonal ";
																					if(strlen($scheck)>0 && strlen($search)>0) $sql.= "WHERE ".$scheck." LIKE '%".$search."%' ";
																					$sql.= "ORDER BY idx DESC ";
																					$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
																					$result = mysql_query($sql,get_db_conn());
																					$cnt=0;
																					while($row=mysql_fetch_object($result)) {
																						$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
																						$date = substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)."(".substr($row->date,8,2).":".substr($row->date,10,2).")";
																						echo "<TR align=center>\n";
																						echo "	<TD class=\"td_con2\"><input type=checkbox name=delcheck value=\"".$row->idx."\"></TD>\n";
																						echo "	<TD class=\"td_con1\">".$number."</TD>\n";
																						echo "	<TD align=left class=\"td_con1\">&nbsp;<A HREF=\"javascript:ViewPersonal('".$row->idx."');\">".strip_tags($row->subject)."</A></TD>\n";
																						echo "	<TD class=\"td_con1\"><A HREF=\"javascript:Search_id('".$row->id."');\">".$row->name."</A></TD>\n";
																						echo "	<TD class=\"td_con1\">".$date."</TD>\n";
																						echo "	<TD class=\"td_con1\">";
																						if(strlen($row->re_date)==14) {
																							echo "<img src=\"images/icon_finish.gif\" width=\"74\" height=\"25\" border=\"0\">";
																						} else {
																							echo "<img src=\"images/icon_nofinish.gif\" width=\"74\" height=\"25\" border=\"0\">";
																						}
																						echo "	</TD>\n";
																						echo "</TR>\n";
																						echo "<TR>\n";
																						echo "	<TD colspan=\"6\" background=\"images/table_con_line.gif\"></TD>\n";
																						echo "</TR>\n";
																						$cnt++;
																					}
																					mysql_free_result($result);
																					if ($cnt==0) {
																						echo "<tr><td colspan=".$colspan." align=center height=30>�˻��� ������ �������� �ʽ��ϴ�.</td></tr>";
																					}
																	?>
																	<TR>
																		<TD background="images/table_top_line.gif" colspan="<?=$colspan?>" height=1></TD>
																	</TR>
																</TABLE>
															</td>
														</tr>
														<tr>
															<td><a href="javascript:CheckDelete(document.form2);"><img src="images/btn_del2.gif" width="76" height="18" border="0" vspace="3" hspace="3"></a></td>
														</tr>
														<?
																	$total_block = intval($pagecount / $setup[page_num]);

																	if (($pagecount % $setup[page_num]) > 0) {
																		$total_block = $total_block + 1;
																	}

																	$total_block = $total_block - 1;

																	if (ceil($t_count/$setup[list_num]) > 0) {
																		// ����	x�� ����ϴ� �κ�-����
																		$a_first_block = "";
																		if ($nowblock > 0) {
																			$a_first_block .= "<a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=0&gotopage=1' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0  align=\"absmiddle\" width=\"17\" height=\"14\"></a> ";

																			$prev_page_exists = true;
																		}

																		$a_prev_page = "";
																		if ($nowblock > 0) {
																			$a_prev_page .= "<a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=".($nowblock-1)."&gotopage=".($setup[page_num]*($block-1)+$setup[page_num])."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[���� ".$setup[page_num]."��]</a> ";

																			$a_prev_page = $a_first_block.$a_prev_page;
																		}

																		// �Ϲ� �������� ������ ǥ�úκ�-����

																		if (intval($total_block) <> intval($nowblock)) {
																			$print_page = "";
																			for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
																				if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
																					$print_page .= "<B><span class=font_orange2>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</span></B> ";
																				} else {
																					$print_page .= "<a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=".$nowblock."&gotopage=". (intval($nowblock*$setup[page_num]) + $gopage)."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
																				}
																			}
																		} else {
																			if (($pagecount % $setup[page_num]) == 0) {
																				$lastpage = $setup[page_num];
																			} else {
																				$lastpage = $pagecount % $setup[page_num];
																			}

																			for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
																				if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
																					$print_page .= "<B><span class=font_orange2>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</span></B> ";
																				} else {
																					$print_page .= "<a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=".$nowblock."&gotopage=".(intval($nowblock*$setup[page_num]) + $gopage)."' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
																				}
																			}
																		}		// ������ �������� ǥ�úκ�-��


																		$a_last_block = "";
																		if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
																			$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
																			$last_gotopage = ceil($t_count/$setup[list_num]);

																			$a_last_block .= " <a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=".$last_block."&gotopage=".$last_gotopage."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0  align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

																			$next_page_exists = true;
																		}

																		// ���� 10�� ó���κ�...

																		$a_next_page = "";
																		if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
																			$a_next_page .= " <a href='".$_SERVER[PHP_SELF]."?scheck=".$scheck."&search=".$search."&block=".($nowblock+1)."&gotopage=".($setup[page_num]*($nowblock+1)+1)."' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[���� ".$setup[page_num]."��]</a>";

																			$a_next_page = $a_next_page.$a_last_block;
																		}
																	} else {
																		$print_page = "<B><span class=font_orange2>[1]</span></B>";
																	}
																	echo "<tr>\n";
																	echo "	<td colspan=".$colspan." align=center style='font-size:11px;'>\n";
																	echo "		".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
																	echo "	</td>\n";
																	echo "</tr>\n";
														?>
														<tr>
															<td>
																<table cellpadding="0" cellspacing="0" width="100%">
																	<tr>
																		<td width="100%" class="main_sfont_non">&nbsp;</td>
																	</tr>
																	<tr>
																		<td width="100%" class="main_sfont_non">
																			<table cellpadding="10" cellspacing="1" bgcolor="#DBDBDB" width="100%">
																				<tr>
																					<td align=center bgcolor="white">
																						<SELECT name=scheck class="select">
																						<OPTION value=id <?if($scheck=="id")echo"selected";?>>���̵�</OPTION>
																						<OPTION value=name <?if($scheck=="name")echo"selected";?>>�� ��</OPTION>
																						</SELECT>
																						<INPUT type="text" name=search value="<?=$search?>" class="input">
																						<A href="javascript:Search(document.form2);"><img src="images/icon_search.gif" alt=�˻� align=absMiddle border=0></a>
																						<A href="javascript:search_default();"><IMG src="images/icon_search_clear.gif" align=absMiddle border=0 width="68" height="25" hspace="2"></A>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</form>
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td>&nbsp;</td>
													</tr>
													<tr>
														<td>
														<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
														<TR>
															<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
															<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
															<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
															<TD background="images/manual_bg.gif">&nbsp;</TD>
															<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
														</TR>
														<TR>
															<TD background="images/manual_left1.gif"></TD>
															<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
															<table cellpadding="0" cellspacing="0" width="100%">
															<col width=20></col>
															<col width=></col>
															<tr>
																<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																<td><span class="font_dotline">1:1 �� �Խ��� ����</span></td>
															</tr>
															<tr>
																<td align="right">&nbsp;</td>
																<td class="space_top" style="letter-spacing:-0.5pt;">- ���� Ŭ���� ������ ���� �亯�� �� �� ������, �亯 ó���� �׸��� �亯�Ϸ�� ǥ��˴ϴ�.</td>
															</tr>
															<tr>
																<td align="right">&nbsp;</td>
																<td class="space_top" style="letter-spacing:-0.5pt;">- �亯���� ������� �����ϸ�, ������� ������� �������� �亯��¥�� ���ŵ˴ϴ�.</td>
															</tr>
															<tr>
																<td align="right">&nbsp;</td>
																<td class="space_top" style="letter-spacing:-0.5pt;">- ȸ������ Ŭ���ϸ� �ش� ȸ���� ���̵�� �˻��˴ϴ�.</td>
															</tr>
															<tr>
																<td align="right">&nbsp;</td>
																<td class="space_top" style="letter-spacing:-0.5pt;">- 1:1 �� �Խ��� �̻��� ���� ���Ǳ� ����� ���� ������, ���� ����� �� ����˴ϴ�.(�̻�� üũ�ÿ��� ���� ��� ������ �����˴ϴ�.)</td>
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
<form name=form3 action="community_personal_pop.php" method=post target="personal_pop">
<input type=hidden name=idx>
</form>
<?=$onload?>
<? INCLUDE "copyright.php"; ?>