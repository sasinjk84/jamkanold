<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-1";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 20;

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

$type=$_POST["type"];
$mode=$_POST["mode"];
$survey_code=$_POST["survey_code"];
$up_survey_content=$_POST["up_survey_content"];
$up_survey_select1=$_POST["up_survey_select1"];
$up_survey_select2=$_POST["up_survey_select2"];
$up_survey_select3=$_POST["up_survey_select3"];
$up_survey_select4=$_POST["up_survey_select4"];
$up_survey_select5=$_POST["up_survey_select5"];
$up_ip_yn=$_POST["up_ip_yn"];
$up_grant_write=$_POST["up_grant_write"];
$up_grant_comment=$_POST["up_grant_comment"];
$currentdate=date("YmdHis");

if($type=="insert" && strlen($up_survey_content)>0) {
	$grant_type = $up_grant_write.$up_grant_comment;

	$sql = "UPDATE tblsurveymain SET display = 'N' WHERE display = 'N' ";
	mysql_query($sql,get_db_conn());

	$sql = "INSERT tblsurveymain SET ";
	$sql.= "survey_code		= '".$currentdate."', ";
	$sql.= "time_start		= '".time()."', ";
	$sql.= "time_end		= '0', ";
	$sql.= "display			= 'Y', ";
	$sql.= "ip_yn			= '".$up_ip_yn."', ";
	$sql.= "grant_type		= '".$grant_type."', ";
	$sql.= "survey_content	= '".$up_survey_content."', ";
	$sql.= "survey_select1	= '".$up_survey_select1."', ";
	$sql.= "survey_select2	= '".$up_survey_select2."', ";
	$sql.= "survey_select3	= '".$up_survey_select3."', ";
	$sql.= "survey_select4	= '".$up_survey_select4."', ";
	$sql.= "survey_select5	= '".$up_survey_select5."' ";
	$insert=mysql_query($sql,get_db_conn());
	$onload="<script>alert('�¶�����ǥ ����� �Ϸ�Ǿ����ϴ�.');</script>\n";
} else if ($type=="modify" && strlen($survey_code)>0) {
	if ($mode=="result") {
		$grant_type = $up_grant_write.$up_grant_comment;
		if ($up_display=="Y") {
			$sql = "UPDATE tblsurveymain SET display = 'N' WHERE display = 'N' ";
			mysql_query($sql,get_db_conn());
		}
		$sql = "UPDATE tblsurveymain SET ";
		$sql.= "display			= '".$up_display."', ";
		$sql.= "ip_yn			= '".$up_ip_yn."', ";
		$sql.= "grant_type		= '".$grant_type."', ";
		$sql.= "survey_content	= '".$up_survey_content."', ";
		$sql.= "survey_select1	= '".$up_survey_select1."', ";
		$sql.= "survey_select2	= '".$up_survey_select2."', ";
		$sql.= "survey_select3	= '".$up_survey_select3."', ";
		$sql.= "survey_select4	= '".$up_survey_select4."', ";
		$sql.= "survey_select5	= '".$up_survey_select5."' ";
		$sql.= "WHERE survey_code = '".$survey_code."' ";
		$update=mysql_query($sql,get_db_conn());
		$onload="<script>alert('�¶�����ǥ ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
		unset($type);
		unset($mode);
		unset($survey_code);
	} else {
		$sql = "SELECT * FROM tblsurveymain WHERE survey_code='".$survey_code."'";
		$result = mysql_query($sql,get_db_conn());
		$data = mysql_fetch_object($result);
		mysql_free_result($result);
		if (!$data) {
			$onload="<script>alert('�����Ϸ��� ��ǥ�� �������� �ʽ��ϴ�.');<script>";
			unset($type);
			unset($survey_code);
		} else {
			$grant_write=substr($data->grant_type,0,1);
			$grant_comment=substr($data->grant_type,1,1);
		}
	}
} else if ($type=="delete" && strlen($survey_code)>0) {
	$sql = "DELETE FROM tblsurveymain WHERE survey_code = '".$survey_code."' ";
	mysql_query($sql,get_db_conn());
	$sql = "DELETE FROM tblsurveyresult WHERE survey_code = '".$survey_code."' ";
	mysql_query($sql,get_db_conn());
	$onload="<script> alert('�ش� �¶�����ǥ ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
	unset($type);
	unset($survey_code);
}

if (strlen($type)==0) $type="insert";
if (strlen($grant_write)==0) $grant_write="Y";
if (strlen($grant_comment)==0) $grant_comment="Y";
if (strlen($data->ip_yn)==0) $data->ip_yn="N";
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm(type) {
	if(document.form1.up_survey_content.value.length==0) {
		document.form1.up_subject.focus();
		alert("��ǥ ������ �Է��ϼ���");
		return;
	}
	if(type=="modify") {
		if(!confirm("�ش� ��ǥ�� �����Ͻðڽ��ϱ�?")) {
			return;
		}
		document.form1.mode.value="result";
	} else if (type=="insert") {
		if(!confirm("�¶�����ǥ�� ����Ͻðڽ��ϱ�?")) {
			return;
		}
	}
	document.form1.type.value=type;
	document.form1.submit();
}
function SurveySend(type,code) {
	if(type=="delete") {
		if(!confirm("�ش� ��ǥ�� �����Ͻðڽ��ϱ�?")) return;
	}
	document.form1.type.value=type;
	document.form1.survey_code.value=code;
	document.form1.submit();
}
function ViewSurvey(code) {
	var url;
	url="<?=$Dir.FrontDir?>survey.php?type=view&survey_code="+code;
	window.open (url,"survey","width=450,height=400,scrollbars=yes");
}
function GoPage(block,gotopage) {
	document.form2.block.value = block;
	document.form2.gotopage.value = gotopage;
	document.form2.submit();
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
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; ���������� &gt; <span class="2depth_select">�¶�����ǥ ����</span></td>
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






			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=mode>
			<input type=hidden name=survey_code value="<?=$survey_code?>">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_survey_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">�¶�����ǥ �����޴�  ���/����/���� �Ͻ� �� �ֽ��ϴ�.</TD>
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
				<td>																	
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_survey_stitle1.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>																													
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=130></col>
				<col width=></col>
				<col width=50></col>
				<col width=60></col>
				<col width=60></col>
				<col width=60></col>
				<TR>
					<TD colspan=6 background="images/table_top_line.gif"></TD>
				</TR>
				<TR align=center>
					<TD class="table_cell">�������</TD>
					<TD class="table_cell1">��ǥ����</TD>
					<TD class="table_cell1">��ǥ��</TD>
					<TD class="table_cell1">���࿩��</TD>
					<TD class="table_cell1">����</TD>
					<TD class="table_cell1">����</TD>
				</TR>
				<TR>
					<TD colspan="6" background="images/table_con_line.gif"></TD>
				</TR>
<?
				$colspan=6;
				$sql = "SELECT COUNT(*) as t_count FROM tblsurveymain ";
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				$t_count = $row->t_count;
				mysql_free_result($result);
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT * FROM tblsurveymain ORDER BY survey_code DESC ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result = mysql_query($sql,get_db_conn());
				$cnt=0;
				while($row=mysql_fetch_object($result)) {
					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$cnt);
					$str_date = substr($row->survey_code,0,4)."/".substr($row->survey_code,4,2)."/".substr($row->survey_code,6,2)." ".substr($row->survey_code,8,2).":".substr($row->survey_code,10,2).":".substr($row->survey_code,12,2);
					$sel_tot=$row->survey_cnt1+$row->survey_cnt2+$row->survey_cnt3+$row->survey_cnt4+$row->survey_cnt5;
					if ($row->display=="Y") $display="<span class=\"font_orange\"><b>������</b></span>";
					else $display="����";
					echo "<TR>\n";
					echo "	<TD align=center class=\"td_con2\">".$str_date."</TD>\n";
					echo "	<TD class=\"td_con1\"><A HREF=\"javascript:ViewSurvey('".$row->survey_code."');\">".$row->survey_content."</A></TD>\n";
					echo "	<TD align=center class=\"td_con1\">".$sel_tot."</TD>\n";
					echo "	<TD align=center class=\"td_con1\">".$display."</TD>\n";
					echo "	<TD align=center class=\"td_con1\"><a href=\"javascript:SurveySend('modify','".$row->survey_code."');\"><img src=\"images/btn_edit.gif\" width=\"50\" height=\"22\" border=\"0\"></a></TD>\n";
					echo "	<TD align=center class=\"td_con1\"><a href=\"javascript:SurveySend('delete','".$row->survey_code."');\"><img src=\"images/btn_del.gif\" width=\"50\" height=\"22\" border=\"0\"></a></TD>\n";
					echo "</TR>\n";
					echo "<TR>\n";
					echo "	<TD colspan=".$colspan." background=\"images/table_con_line.gif\"></TD>\n";
					echo "</TR>\n";
					$cnt++;
				}
				mysql_free_result($result);

				if ($cnt==0) {
					echo "<tr><td class=td_con2 colspan=".$colspan." align=center>��ϵ� �¶�����ǥ�� �������� �ʽ��ϴ�..</td></tr>";
				}
?>
				<TR>
					<TD colspan=6 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align=center class="font_size">
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
							$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

							$prev_page_exists = true;
						}

						$a_prev_page = "";
						if ($nowblock > 0) {
							$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[prev]</a>&nbsp;&nbsp;";

							$a_prev_page = $a_first_block.$a_prev_page;
						}

						// �Ϲ� �������� ������ ǥ�úκ�-����

						if (intval($total_block) <> intval($nowblock)) {
							$print_page = "";
							for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
								if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
									$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
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
									$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
								}
							}
						}		// ������ �������� ǥ�úκ�-��


						$a_last_block = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
							$last_gotopage = ceil($t_count/$setup[list_num]);

							$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

							$next_page_exists = true;
						}

						// ���� 10�� ó���κ�...

						$a_next_page = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\">[next]</a>";

							$a_next_page = $a_next_page.$a_last_block;
						}
					} else {
						$print_page = "<B>[1]</B>";
					}
?>
					<?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_survey_stitle2.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=140></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǥ����</TD>
					<TD class="td_con1"><INPUT style="WIDTH:60%" name=up_survey_content class="input" value="<?=$data->survey_content?>"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����1</TD>
					<TD class="td_con1"><INPUT style="WIDTH:40%" name=up_survey_select1 class="input" value="<?=$data->survey_select1?>"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����2</TD>
					<TD class="td_con1"><INPUT style="WIDTH:40%" name=up_survey_select2 class="input" value="<?=$data->survey_select2?>"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����3</TD>
					<TD class="td_con1"><INPUT style="WIDTH:40%" name=up_survey_select3 class="input" value="<?=$data->survey_select3?>"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����4</TD>
					<TD class="td_con1"><INPUT style="WIDTH:40%" name=up_survey_select4 class="input" value="<?=$data->survey_select4?>"></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����5</TD>
					<TD class="td_con1"><INPUT style="WIDTH:40%" name=up_survey_select5 class="input" value="<?=$data->survey_select5?>"></TD>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">IP ��������</TD>
					<TD class="td_con1">
					<INPUT type=radio value=Y name=up_ip_yn <? if($data->ip_yn=="Y") echo "checked" ?>>�ڸ�Ʈ �ۼ��� IP ����
					&nbsp;&nbsp;&nbsp;&nbsp;
					<INPUT type=radio value=N name=up_ip_yn <? if($data->ip_yn=="N") echo "checked" ?>>�ڸ�Ʈ �ۼ��� IP ����
					</TD>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǥ ���ٱ���</TD>
					<TD class="td_con1">
					�����ۼ� : 
					<SELECT name=up_grant_write class="select">
					<OPTION value=Y <? if($grant_write=="Y") echo "selected"?>>������ ����</OPTION>
					<OPTION value=N <? if($grant_write=="N") echo "selected"?>>ȸ���� ����</OPTION>
					</SELECT>
					&nbsp;&nbsp;&nbsp; �ڸ�Ʈ�ۼ� : 
					<SELECT name=up_grant_comment class="select">
					<OPTION value=Y <? if($grant_comment=="Y") echo "selected"?>>������ ����</OPTION>
					<OPTION value=N <? if($grant_comment=="N") echo "selected"?>>ȸ���� ����</OPTION>
					</SELECT>
					
					</TD>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif"></TD>
				</TR>
				<?if($type=="modify"){?>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǥ ���࿩��</TD>
					<TD class="td_con1">
					<INPUT type=radio value=Y name=up_display <? if($data->display=="Y") echo "checked" ?>>��ǥ�� �����մϴ�.
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<INPUT type=radio value=N name=up_display <? if ($data->display=="N") echo "checked" ?>>��ǥ ������ �ߴ��մϴ�.
					</TD>
				</tr>
				<?}?>
				<TR>
					<TD colspan=2 background="images/table_con_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=10></td>
			</tr>
			<tr>
				<td align=center><a href="javascript:CheckForm('<?=$type?>');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
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
					<TD background="images/manual_bg.gif"></TD>
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
						<td><span class="font_dotline">�¶�����ǥ ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �¶��� ��ǥ�� ����ȭ�� ���ø����� ���� ������ �⺻���� ��µǰ� ������ �ֽ��ϴ�.<br>
						<b>&nbsp;&nbsp;</b><a href="javascript:parent.topframe.GoMenu(2,'design_main.php');"><span class="font_blue">�����ΰ��� > ���ø�-���� �� ī�װ� > ����ȭ�� ���ø�</span></a></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �¶��� ��ǥ �ߴ��� �ش� ��ǥ�� ������忡�� ��ǥ ���࿩�θ� �����ϸ� �˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ������� �ʴ� �¶��� ��ǥ�� �ǵ��� ���� �ϼ���.</td>
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
			</form>

			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=block value="<?=$block?>">
			<input type=hidden name=gotopage value="<?=$gotopage?>">
			</form>
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
<?=$onload?>
<? INCLUDE "copyright.php"; ?>