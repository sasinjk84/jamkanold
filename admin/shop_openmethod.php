<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-2";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$up_adult_type=$_POST["up_adult_type"];
$up_adult_type2=$_POST["up_adult_type2"];
$up_member_baro=$_POST["up_member_baro"];
$up_member_buygrant=$_POST["up_member_buygrant"];

if ($type=="up") {
	if (strlen($up_member_baro)==0) {
		$up_member_baro="N";
	}
	if ($up_adult_type=="B"){
		$up_adult_type="B";
		$up_member_baro="Y";
		$up_member_buygrant="Y";
	} else if ($up_adult_type=="Y") {
		$up_adult_type=$up_adult_type2;
	} else {
		$up_adult_type="N";
	}

	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "adult_type		= '".$up_adult_type."', ";
	$sql.= "member_baro		= '".$up_member_baro."', ";
	$sql.= "member_buygrant	= '".$up_member_buygrant."' ";
	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload = "<script> alert('����� ���� ������ �Ϸ�Ǿ����ϴ�.'); </script>";
}

$sql = "SELECT adult_type, member_baro, member_buygrant FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());

if ($row = mysql_fetch_object($result)) {
	$adult_type = $row->adult_type;
	$member_baro = $row->member_baro;
	$member_buygrant = $row->member_buygrant;
}
mysql_free_result($result);

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function CheckForm(){
	var form = document.form1;
	if (form.up_adult_type[1].checked==true && form.up_adult_type2[0].checked==false && form.up_adult_type2[1].checked==false) {
		alert("���� ���θ� ���ý� [��Ʈ�� ���ø� ����]�� �����ϼž� �մϴ�.");
		return;
	}
	if (form.up_adult_type[1].checked==true && form.up_adult_type2[1].checked==true && form.up_member_baro[1].checked==false) {
		alert("���� ���θ� ���ý� [���ű���]������ [������ ������ ����]�� �����Ͻô°��� �����մϴ�.");
	}
	if (form.up_member_baro[1].checked==true && form.up_member_buygrant[0].checked==true) {
		alert("[������ ������ ����]�� �����ϼ����Ƿ�, ȸ�������ο� [ȸ������]���� �����ϼž� �մϴ�.");
		return;
	}
	if(form.up_adult_type[2].checked==true){
		alert("B2B ���θ��� �����ϼ����Ƿ� [ȸ������]�� [������ ������ ����]�� ���õ˴ϴ�.");
	}
	form.type.value="up";
	form.submit();
}

function ChoiceValue(type,no) {
	var form = document.form1;
	if (type=="adult" && no!=1) {
		form.up_adult_type2[0].disabled=true;
		form.up_adult_type2[1].disabled=true;
	} else if (type=="adult" && no==1) {
		form.up_adult_type2[0].disabled=false;
		form.up_adult_type2[1].disabled=false;
	}

	if (type=="adult" && no==2) {
		form.up_member_baro[0].disabled=true;
		form.up_member_buygrant[0].disabled=true;
		form.up_member_buygrant[1].checked=true;
		form.up_member_baro[1].disabled=false;
		form.up_member_baro[1].checked=true;
	} else if (type=="adult" && no!=2) {
		form.up_member_baro[0].disabled=false;
		form.up_member_buygrant[0].disabled=false;
	}

	if (type=="member" && no==0) {
		form.up_member_baro[0].disabled=true;
		form.up_member_baro[1].disabled=true;
		form.up_member_baro[0].checked=true;
	} else if (type=="member" && no==1) {
		form.up_member_baro[0].disabled=false;
		form.up_member_baro[1].disabled=false;
	}

	if (type=="intro" && no==1) {
		form.up_member_buygrant[0].disabled=true;
		form.up_member_buygrant[1].checked=true;
	} else if(type=="intro" && no!=1){
		form.up_member_buygrant[0].disabled=false;
	}
}

function InitValue(){
	var form = document.form1;
<?if($adult_type != "Y" && $adult_type!="M" ){?>
	form.up_adult_type2[0].disabled=true;
	form.up_adult_type2[1].disabled=true;
<?}?>
<?if($adult_type=="M" ){?>
	form.up_member_buygrant[0].disabled=true;
<?}?>
<?if($adult_type=="B"){?>
	form.up_member_baro[0].disabled=true;
	form.up_member_buygrant[0].disabled=true;
<?}?>
<?if($member_buygrant != "Y" ){?>
	form.up_member_baro[0].disabled=true;
	form.up_member_baro[1].disabled=true;
<?}?>
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
			<? include ("menu_shop.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� ȯ�� ���� &gt; <span class="2depth_select"> ������ ���� ����</span></td>
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
					<TD><IMG SRC="images/shop_openmethod_title.gif"  ALT=""></TD>
				</TR>
				<TR>
					<TD width="100%" background="images/title_bg.gif" HEIGHT=21></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=20></td></tr>
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
					<TD width="100%" class="notice_blue">1) Ư�� ���θ��� ���� ȸ��/��ȸ��, ���θ����� ���θ� ������ �� �ֽ��ϴ�.<br>2) �ֹι�ȣ������ �ֹε�Ϲ�ȣ ���� �˻��̸�, �Ǹ������� �ƴմϴ�.<br>3) <b>�Ǹ������� <span class=font_orange>����Ǹ����� ����</span>�� �����ϼž� ��� �����մϴ�.</b></TD>
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
				<td height=10></td>
			</tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td valign="top">
					<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_openmethod_box1.gif"></TD>
					</TR>
					<TR>
						<TD background="images/shop_openmethod_box_bg.gif" style="padding-top:6pt; padding-bottom:6pt; padding-left:6pt;" height="75"><input type=radio id="idx_adult_type1" name=up_adult_type value="N" <? if($adult_type == "N") echo "checked ";?> onclick="ChoiceValue('adult',0)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_adult_type1>�Ϲ� ���θ�</label><BR>
						<input type=radio id="idx_adult_type2" name=up_adult_type value="Y" <? if($adult_type == "Y" || $adult_type=="M") echo "checked ";?> onclick="ChoiceValue('adult',1)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_adult_type2>���� ���θ�</label><img src="images/icon_19.gif" width="19" height="19" border="0" align=absmiddle><br>
						<input type=radio id="idx_adult_type3" name=up_adult_type value="B" <? if($adult_type=="B") echo "checked ";?> onclick="ChoiceValue('adult',2)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_adult_type3>B2B ���θ�</label></TD>
					</TR>
					<TR>
						<TD><IMG SRC="images/shop_openmethod_box_down.gif" WIDTH="165" HEIGHT=9 ALT=""></TD>
					</TR>
					</TABLE>
					</td>
					<td  align="center"><img src="images/icon_nero.gif" width="23" height="45" border="0"></td>
					<td valign="top">
					<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_openmethod_box2.gif"></TD>
					</TR>
					<TR>
						<TD background="images/shop_openmethod_box_bg.gif" style="padding-top:6pt; padding-left:6pt;" height="75">
						<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><input type=radio id="idx_adult_type21" name=up_adult_type2 value="Y" <? if($adult_type == "Y") echo "checked ";?> onclick="ChoiceValue('intro',0)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_adult_type21>�ֹι�ȣ ������ �̿�</label><BR>
							<input type=radio id="idx_adult_type22" name=up_adult_type2 value="M" <? if($adult_type == "M") echo "checked ";?> onclick="ChoiceValue('intro',1)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_adult_type22>ȸ������ �� �̿�</label></TD>
						</TR>
						<TR>
							<TD style="padding-left:7pt;"><span class=font_orange>* ���μ��θ��� ���</span><img src="images/icon_19.gif" width="19" height="19" border="0" align=absmiddle></TD>
						</TR>
						</TABLE>
						</TD>
					</TR>
					<TR>
						<TD><IMG SRC="images/shop_openmethod_box_down.gif" WIDTH="165" HEIGHT=9 ALT=""></TD>
					</TR>
					</TABLE>
					</td>
					<td  align="center"><img src="images/icon_nero.gif" width="23" height="45" border="0"></td>
					<td valign="top">
					<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_openmethod_box3.gif"></TD>
					</TR>
					<TR>
						<TD background="images/shop_openmethod_box_bg.gif" style="padding-top:6pt; padding-bottom:6pt; padding-left:6pt;" height="75">
						<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><input type=radio id="idx_member_buygrant1" name=up_member_buygrant value="U" <? if($member_buygrant == "U") echo "checked ";?> onclick="ChoiceValue('member',0)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_member_buygrant1>ȸ��/��ȸ��</label><BR>
							<input type=radio id="idx_member_buygrant2" name=up_member_buygrant value="Y" <? if($member_buygrant == "Y") echo "checked ";?> onclick="ChoiceValue('member',1)"><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_member_buygrant2>ȸ������</label></TD>
						</TR>
						<TR>
							<TD style="padding-left:7pt;"><span class=font_orange>* ��� ���θ� ����</span></TD>
						</TR>
						</TABLE>
						</TD>
					</TR>
					<TR>
						<TD><IMG SRC="images/shop_openmethod_box_down.gif" WIDTH="165" HEIGHT=9 ALT=""></TD>
					</TR>
					</TABLE>
					</td>
					<td  align="center"><img src="images/icon_nero.gif" width="23" height="45" border="0"></td>
					<td valign="top">
					<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>
					<TR>
						<TD><IMG SRC="images/shop_openmethod_box4.gif"></TD>
					</TR>
					<TR>
						<TD background="images/shop_openmethod_box_bg.gif" style="padding-top:6pt; padding-left:6pt;" height="75">
						<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><input type=radio id="idx_member_baro1" name=up_member_baro value="N" <? if($member_baro == "N") echo "checked ";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_member_baro1>ȸ�����Խ� �ٷ� ����</label><br>
							<input type=radio id="idx_member_baro2" name=up_member_baro value="Y" <? if($member_baro == "Y") echo "checked ";?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_member_baro2>������ ������ ����</label></TD>
						</TR>
						<TR>
							<TD  style="padding-left:7pt;"><span class=font_orange>* ȸ������ ���θ��� ���</span></TD>
						</TR>
						</TABLE>
						</TD>
					</TR>
					<TR>
						<TD><IMG SRC="images/shop_openmethod_box_down.gif" WIDTH="165" HEIGHT=9 ALT=""></TD>
					</TR>
					</TABLE>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
			<tr><td height=20></td></tr>
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
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">���θ� ������ ���� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- ȸ�� �� ��ȸ���� �����Ӱ� �̿� ������ ���θ�: �Ϲݼ��θ�+ȸ��/��ȸ��<br>
						- B2B ������ ���θ� : �Ϲݼ��θ�-ȸ������+������ ���� �� ����, B2B ���θ�-ȸ������+������ ���� �� ����<br>
						</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td class="space_top">&nbsp; </td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">��Ʈ�� ȭ�� ������ ���� �޴�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- <a href="javascript:parent.topframe.GoMenu(2,'design_adultintro.php');"><span class="font_blue">�����ΰ��� > ���ø� - ���� �� ī�װ� > ���θ� ��Ʈ�� ���ø�</span></a><br>
						- <a href="javascript:parent.topframe.GoMenu(2,'design_eachintropage.php');"><span class="font_blue">�����ΰ��� > ���������� - ���� �� ���ϴ� > ��Ʈ�� ȭ�� �ٹ̱�</span></a>
						</td>
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
<script>InitValue();</script>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>