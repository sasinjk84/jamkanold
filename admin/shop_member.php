<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$type=$_POST["type"];
$text1=$_POST["text1"];
$text2=$_POST["text2"];
$text3=$_POST["text3"];
$up_memberout_type=$_POST["up_memberout_type"];
$up_resno_type=$_POST["up_resno_type"];
$up_resno_type2=$_POST["up_resno_type2"];

if($type=="up"){
	if($up_resno_type=="Y" && $up_resno_type2=="N") $up_resno_type="Y";
	else if($up_resno_type=="Y" && $up_resno_type2=="Y") $up_resno_type="M";
	else if($up_resno_type=="N") $up_resno_type="N";

	$count=0;
	$temparray1 = explode("",$text1);
	$temparray2 = explode("",$text2);
	$temparray3 = explode("",$text3);
	$cnt=count($temparray1);
	for($i=1;$i<=$cnt;$i++){
		$temp1=trim($temparray1[$i]); $temp2=trim($temparray2[$i]); $temp3=trim($temparray3[$i]);
		if(strlen($temp1)>0 && strlen($temp2)>0 && strlen($temp3)>0){
			if($count!=0) $temp.="=";
			$count++;
			$temp.=$temp1."=".$temp2."=".$temp3;
		}
	}
	
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "memberout_type		= '".$up_memberout_type."', ";
	$sql.= "resno_type			= '".$up_resno_type."', ";
	$sql.= "member_addform		= '".$temp."' ";
	
	// ����ȸ�� ���� �߰�
	$sql.= ",wholesalemember='".(($_POST['up_wholesalemember']=='Y')?'Y':'N')."'";
	// # ����ȸ�� ���� �߰�
	$update = mysql_query($sql,get_db_conn());
	
	if(!_empty($_POST['ext_memconf_reqgender'])){
		@mysql_query("insert into extra_conf (type,name,value) VALUES ('memconf','reqgender','".($_POST['ext_memconf_reqgender'])."') ON DUPLICATE KEY UPDATE value=VALUES(value)",get_db_conn());
	}
	
	if(!_empty($_POST['ext_memconf_reqbirth'])){
		@mysql_query("insert into extra_conf (type,name,value) VALUES ('memconf','reqbirth','".($_POST['ext_memconf_reqbirth'])."') ON DUPLICATE KEY UPDATE value=VALUES(value)",get_db_conn());
	}
	
	
	DeleteCache("tblshopinfo.cache");
	
	
	$onload="<script>alert('ȸ������ ���� ������ �Ϸ�Ǿ����ϴ�.');</script>";
}

//$sql = "SELECT memberout_type,resno_type,member_addform FROM tblshopinfo ";
$sql = "SELECT memberout_type,resno_type,member_addform,wholesalemember FROM tblshopinfo ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$memberout_type = $row->memberout_type;
$resno_type = $row->resno_type;
if ($resno_type=="Y") $resno_type2="N";
else if ($resno_type=="M") {
	$resno_type="Y";
	$resno_type2="Y";
} else {
	$resno_type2="N";
}
if (strlen($row->member_addform)!=0) {
	$fieldarray=explode("=",$row->member_addform);
	$num=sizeof($fieldarray)/3;
	for($i=0;$i<$num;$i++){
		$field_length1[$i]=$fieldarray[$i*3+1];
		$max_length1[$i]=$fieldarray[$i*3+2];
		if (substr($fieldarray[$i*3],-1,1)=="^") {
			$field_name1[$i] = substr($fieldarray[$i*3],0,strlen($fieldarray[$i*3])-1);
			$field_check[$i] = "Y";
		} else {
			$field_name1[$i] = $fieldarray[$i*3];
			$field_check[$i] = "N";
		}
	}
}
mysql_free_result($result);

${"check_resno_type".$resno_type} = "checked";
${"check_resno_type2".$resno_type2} = "checked";
${"check_memberout_type".$memberout_type} = "checked";

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	var form = document.form1;
	var isMax=false;
	form.text1.value="";
	form.text2.value="";
	form.text3.value="";
	for(i=0;i<form.field_name.length;i++){
		if(isNaN(form.field_length[i].value)){
			alert("�ʵ� ���̴� ���ڸ� �Է� �����մϴ�.");
			form.field_length[i].focus();
			return;
		}
		if(isNaN(form.max_length[i].value)){
			alert("�ʵ� �ִ���̴� ���ڸ� �Է� �����մϴ�.");
			form.max_length[i].focus();
			return;
		}
		if(form.field_name[i].value.indexOf('')>=0){
			alert("'' ���ڴ� �Է��Ͻ� �� �����ϴ�.");
			form.field_name[i].focus();
			return;
		}
		if(form.field_name[i].value.indexOf('^')>=0){
			alert("'^' ���ڴ� �Է��Ͻ� �� �����ϴ�.");
			form.field_name[i].focus();
			return;
		}
		if(form.field_length[i].value>40){
			alert("�ʵ���̴� �ִ� 40���� �����մϴ�.");
			form.field_length[i].focus();
			return;
		}
		if((form.field_name[i].value.length!=0 && form.field_length[i].value.length==0) || (form.field_name[i].value.length==0 && form.field_length[i].value.length!=0)){
			alert("�߰��Է��� �Է��� �߸��Ǿ����ϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.");
			if(form.field_length[i].value.length==0) form.field_length[i].focus();
			else form.field_name[i].focus();
			return;
		}
		if(form.field_name[i].value.length!=0 && form.field_length[i].value.length!=0 && form.max_length[i].value.length==0){
			isMax=true;
			form.max_length[i].value=form.field_length[i].value;
		}
		if(form.field_name[i].value.length!=0 && form.field_length[i].value.length!=0 && form.max_length[
		i].value.length!=0){
			if (form.field_check[i].checked==true) {
				chk_val = '^';
			} else {
				chk_val = '';
			}
			form.text1.value=form.text1.value+""+form.field_name[i].value+chk_val;
			form.text2.value=form.text2.value+""+form.field_length[i].value;
			form.text3.value=form.text3.value+""+form.max_length[i].value;
		}
	}
	if (isMax==true) {
		if (!confirm("�ִ� ���̸� �Է����� �����ø� �ʵ���̿� ���� ������ �Էµ˴ϴ�.")) {
			return;
		}
	}
	form.type.value="up";
	form.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� � ���� &gt; <span class="2depth_select">ȸ������ ����</span></td>
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
					<TD><IMG SRC="images/shop_member_title.gif"  ALT=""></TD>
					</tr>
<tr>
<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
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
					<TD width="100%" class="notice_blue">�⺻ ȸ������ �Է��� + �߰� �Է��� , �ֹι�ȣ ��� �� Ż������ �� �� �ֽ��ϴ�.</TD>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<input type=hidden name=text1>
			<input type=hidden name=text2>
			<input type=hidden name=text3>
			<!-- ������ ȸ�� ���� �߰� �κ�  // ����� form �� hidden input ��ġ�� ����� -->
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_member_stitle_new1.gif" ALT="����ȸ�� ��� ��� ����"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
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
					<TD width="100%" class="notice_blue">1) ����ȸ���� ��ǰ ������ ���Ű��� ���� ��� �ش� �������� ǥ�� �� �ֹ� �˴ϴ�.<br>2) ����ȸ���� ������ �������� ������ �ʿ�� �մϴ�. <br />3) ����ȸ���� ������ �� ���� ������ �Ұ� �մϴ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ȸ�� ��뿩��</TD>
					<TD class="td_con1"><input type=radio name='up_wholesalemember' id="idx_wholesalemember1" value="Y"  <? if($row->wholesalemember == 'Y') echo 'checked'; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for='idx_wholesalemember1'>����ȸ�� ���</label>  &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_wholesalemember2" name='up_wholesalemember' value="N"  <? if($row->wholesalemember != 'Y') echo 'checked'; ?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for='idx_wholesalemember2'>����ȸ�� ������� ����</label></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<!-- #������ ȸ�� ��� ���� �� -->
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_member_stitle1.gif" WIDTH="188" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
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
					<TD width="100%" class="notice_blue">1) �ִ���̸� �Է����� ������ �ʵ���̿� �����ϰ� ��ϵ˴ϴ�.<br>2) �ʵ��� �ִ� ���̴� 250�Դϴ�.<br>3) �߰��Է����� �⺻�Է��� �ϴܿ� ǥ��˴ϴ�.</TD>
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
			<tr><td height=3></td></tr>			
			<tr>
				<td>	
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" colspan=5></TD>
				</TR>
				<TR bgColor=#f0f0f0 height=26>
					<TD class="table_cell" align=middle width="28">����</TD>
					<TD class="table_cell1" align=middle >�ʵ��</TD>
					<TD class="table_cell1" align=middle width="156">�ʵ����</TD>
					<TD class="table_cell1" align=middle width="152">�Է��ִ����</TD>
					<TD class="table_cell1" align=middle width="38">�ʼ�</TD>
				</TR>
				<TR>
					<TD colspan="5" background="images/table_con_line.gif"></TD>
				</TR>
<?
	for($i=0;$i<10;$i++){
		if ($i == 9) {
			$line_bottom = "bottom";
		}
?>
				<tr bgcolor=#ffffff height=25>
					<TD class="table_cell" align=middle bgColor=#f0f0f0 width="28"><?=$i+1?></TD>
					<TD class="td_con1" style="PADDING-LEFT: 10px" align=left ><input type=text name=field_name value="<?=$field_name1[$i]?>" maxlength=45 style="width:97%" class="input"></TD>
					<TD class="td_con1" align=middle width="156"><input type=text name=field_length value="<?=$field_length1[$i]?>" maxlength=3 style="width:95%" class="input"></TD>
					<TD class="td_con1" align=middle width="152"><input type=text name=max_length value="<?=$max_length1[$i]?>" maxlength=3 style="width:95%" class="input"></TD>
					<TD class="td_con1" align=middle width="38"><input type=checkbox name=field_check value="Y" <? if ($field_check[$i]=="Y") echo "checked"; ?>></TD>
				</TR>
				<TR>
					<TD colspan="5" background="images/table_con_line.gif"></TD>
				</TR>
<?
	}
?>
				<TR>
					<TD background="images/table_top_line.gif" colspan=5></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<?
			$ext_cont = array();
			$esql = "select * from extra_conf where type='memconf'";
			if(false !== $eres = mysql_query($esql,get_db_conn())){
				while($erow = mysql_fetch_assoc($eres)){
					$ext_cont[$erow['name']] = $erow['value'];
				}
			}
			?>
			
			<tr>
				<td style="padding-bottom:3px;"><IMG SRC="images/shop_member_stitle_req.gif" HEIGHT=31 ALT="ȸ�� ���� �ʼ� �Է� ����"></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="margin-bottom:30px;">
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
					<TD class="td_con1">
						<input type="radio" name="ext_memconf_reqgender" id="ext_memconf_reqgender1" value="Y" <?=($ext_cont['reqgender']=='Y')?'checked="checked"':''?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="ext_memconf_reqgender1">�ʼ� �Է�</label>  
						<input type="radio" name="ext_memconf_reqgender" id="ext_memconf_reqgender2" value="N" <?=($ext_cont['reqgender']=='N')?'checked="checked"':''?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="ext_memconf_reqgender2">������ �Է�</label>
						<input type="radio" name="ext_memconf_reqgender" id="ext_memconf_reqgender3" value="H" <?=($ext_cont['reqgender']=='H')?'checked="checked"':''?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="ext_memconf_reqgender3">���� ����</label>
						
						</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������</TD>
					<TD class="td_con1">
						<input type="radio" name="ext_memconf_reqbirth" id="ext_memconf_reqbirth1" value="Y" <?=($ext_cont['reqbirth']=='Y')?'checked="checked"':''?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="ext_memconf_reqbirth1">�ʼ� �Է�</label>  
						<input type="radio" name="ext_memconf_reqbirth" id="ext_memconf_reqbirth2" value="N" <?=($ext_cont['reqbirth']=='N')?'checked="checked"':''?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="ext_memconf_reqbirth2">������ �Է�</label>
						<input type="radio" name="ext_memconf_reqbirth" id="ext_memconf_reqbirth3" value="H" <?=($ext_cont['reqbirth']=='H')?'checked="checked"':''?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="ext_memconf_reqbirth3">���� ����</label>
						</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			
			
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_member_stitle2.gif" WIDTH="188" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
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
					<TD width="100%" class="notice_blue">1) <b>14�� �̸�</b>�� ���� ��ȣ���� ���ǰ� �ʿ��մϴ�.<br>2) <b>�ֹι�ȣ ���Է�</b>���� ���� ��� ���� å���� ���θ��� �ֽ��ϴ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ֹι�ȣ �Է¿��� ����</TD>
					<TD class="td_con1"><input type=radio id="idx_resno_type1" name=up_resno_type value="Y" <?=$check_resno_typeY?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_resno_type1>ȸ�� ���Խ� �ֹε�Ϲ�ȣ �Է�</label>  &nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_resno_type2" name=up_resno_type value="N" <?=$check_resno_typeN?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_resno_type2>ȸ�� ���Խ� �ֹε�Ϲ�ȣ ���Է�</label></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_member_stitle3.gif" WIDTH="188" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height=3></td>
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
					<TD width="100%" class="notice_blue">ȸ������ �� ȸ������ �������� �ֹι�ȣ�� ���氡�� ������ ������ �� �ֽ��ϴ�.</TD>
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
				<td height=3></td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ����</TD>
					<TD class="td_con1"><input type=radio id="idx_resno_type21" name=up_resno_type2 value="Y" <?=$check_resno_type2Y?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_resno_type21>ȸ���� �ֹε�Ϲ�ȣ ��������</label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_resno_type22" name=up_resno_type2 value="N" <?=$check_resno_type2N?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_resno_type22>�Էµ� �ֹε�Ϲ�ȣ �����Ұ���</label></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_member_stitle4.gif" WIDTH="188" HEIGHT=31 ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
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
					<TD width="100%" class="notice_blue">1) ȸ���� ������ ȸ��Ż�� �� �� �ִ� �޴��� <B>[ȸ����������]</B> ȭ�鿡 �߰��� �� �ֽ��ϴ�.<br>
					2) Ż������ ����� ��� [ȸ����������] ȭ�鿡 ǥ�õ˴ϴ�.<br>
					3) ���������νÿ��� ����, ���, �α����� �������� �ش� ��ũ �߰��� �����մϴ�.</TD>
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
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ��Ż�� ��� ����</TD>
					<TD class="td_con1"><input type=radio id="idx_memberout_type1" name=up_memberout_type value="Y" <?=$check_memberout_typeY?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_memberout_type1>������������ ȸ��Ż��</label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_memberout_type2" name=up_memberout_type value="O" <?=$check_memberout_typeO?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_memberout_type2>�ڵ� ȸ�� Ż��</label>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_memberout_type3" name=up_memberout_type value="N" <?=$check_memberout_typeN?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_memberout_type3>ȸ�� Ż��޴� ������</label></TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
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
						<td ><span class="font_dotline">�ֹι�ȣ �̻�� ���� Ư��</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �ֹι�ȣ �̻��� <b>[���̵�/�����ȣã��]</b> �� �̸��� ���Ը����ּҷ� ���� �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �Ǹ��������񽺸� �ް� �ִ� ���� �Ǹ������� ������� �ʽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �Ǹ��������񽺸� �޴� ���� ��� �Ǹ������� ���� �ʰų� �Ǹ����� ��� �������� �ʰ��Ǿ� �߰���� �߻���<br><b>&nbsp;&nbsp;</b>�ֹι�ȣ �̻������ �����ϸ� �Ǹ��������� ���� ���� �� �ֽ��ϴ�. </td>
					</tr>
					<tr>
						<td height="20" colspan="2"></td>
					</tr>
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td ><span class="font_dotline">ȸ��Ż�� ó�� Ư��</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ���������� �� Ż���� ��� �����ڰ� Ż�� ������ �ϸ� ȸ������Ʈ���� ��� �����˴ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �ڵ� Ż���� ���� ȸ������Ʈ���� ��� �����˴ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- ������ ȸ�������� �������� �ʽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- Ż��� ȸ���� �ֹ�����Ʈ�� ���� �������ּ���.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">- �ŷ����� Ȯ���� ���� ȸ��Ż�� �� �ŷ��������� ���� ���� �Ⱓ�� ȸ������ �Ǵ� ����������ȣ��å�� ǥ���� �����մϴ�.<br>
						<b>&nbsp;&nbsp;</b>��) ��� �� ���ù����� ������ ���Ͽ� ������ ���� �ŷ� ���� �Ǹ� �ǹ� ������ Ȯ�� ���� ������ �����Ⱓ �����մϴ�.<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* ��� �Ǵ� û��öȸ � ���� ��� : 5��<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* ��ݰ��� �� ��ȭ���� ���޿� ���� ��� : 5��<br>
						<b>&nbsp;&nbsp;</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* �Һ����� �Ҹ� �Ǵ� ����ó���� ���� ��� : 3��
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
<?=$onload?>

<? INCLUDE "copyright.php"; ?>