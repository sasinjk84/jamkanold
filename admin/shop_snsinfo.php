<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "sh-3";
$MenuCode = "shop";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$imagepath = $Dir.DataDir."shopimages/etc/";

$type=$_POST["type"];
$up_sns_ok=$_POST["up_sns_ok"];
$up_sns_reserve_type		=$_POST["up_sns_reserve_type"];
$up_sns_recomreserve		=$_POST["up_sns_recomreserve"];
$up_sns_recomreserve_type	=$_POST["up_sns_recomreserve_type"];
$up_sns_recomreserve_give_type	=$_POST["up_sns_recomreserve_give_type"];
$up_sns_memreserve			=$_POST["up_sns_memreserve"];
$up_sns_memreserve_type		=$_POST["up_sns_memreserve_type"];
$up_sns_memreserve_give_type		=$_POST["up_sns_memreserve_give_type"];
$snsBoardReserve = ($_POST["snsBoardReserve"]>0?$_POST["snsBoardReserve"]:0);



if($up_sns_reserve_type != "A")
{
	$up_sns_recomreserve_type="";
	$up_sns_memreserve_type="";
	$up_sns_recomreserve=0;
	$up_sns_memreserve=0;
	$up_sns_recomreserve_give_type="";
	$up_sns_memreserve_give_type="";
}
$up_sns_reserve_type = $up_sns_reserve_type."".$up_sns_recomreserve_type."".$up_sns_memreserve_type."".$up_sns_recomreserve_give_type."".$up_sns_memreserve_give_type;

if ($type=="up") {
	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "sns_ok				= '".$up_sns_ok."', ";
	$sql.= "sns_reserve_type	= '".$up_sns_reserve_type."', ";
	$sql.= "sns_recomreserve	= '".$up_sns_recomreserve."', ";
	$sql.= "sns_memreserve		= '".$up_sns_memreserve."' ";
	//echo $sql;

	mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");

	// �Խ��� ȫ�� ������
	$extra_conf_sql = "SELECT `value` FROM `extra_conf` WHERE `type` = 'tblshopinfo' AND `name` = 'snsBoardReserve' ";
	$extra_conf_res = mysql_query($extra_conf_sql,get_db_conn());
	if( mysql_num_rows($extra_conf_res) ) {
		$extra_conf_up_sql = "UPDATE `extra_conf` SET `value`='".$snsBoardReserve."' WHERE `type` = 'tblshopinfo' AND `name` = 'snsBoardReserve' ";
	} else{
		$extra_conf_up_sql = "INSERT `extra_conf` SET `type` = 'tblshopinfo', `name` = 'snsBoardReserve', `value`='".$snsBoardReserve."' ";
	}
	$extra_conf_up_res = mysql_query($extra_conf_up_sql,get_db_conn());
	mysql_free_result($extra_conf_res);
	mysql_free_result($extra_conf_up_res);

	$onload="<script>alert('SNS ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
}else if($type =="snsinfo"){
	$arSnsType = array("f","t","m");
	for($i=0;$i<sizeof($arSnsType);$i++){
		$type = $arSnsType[$i];
		$state = $_POST[$type."_state"];
		$appid = $_POST[$type."_appid"];
		$secret = $_POST[$type."_secret"];
		$icon = $_FILES[$type."_icon"];

		$sql = "SELECT COUNT(*) as cnt, icon_img FROM tblshopsnsinfo ";
		$sql.= "WHERE type = '{$type}'";
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		mysql_free_result($result);
		$cnt=$row->cnt;
		$old_icon = $row->icon_img;

		if ($icon[name] && (strtolower(substr($icon[name],strlen($icon[name])-3,3))=="gif" || strtolower(substr($icon[name],strlen($icon[name])-3,3))=="jpg")) {
			if ($icon[size]<153600) {
				$icon[name]="icon_".$type.substr($icon[name],-4);
				if(strlen($old_icon)>0 && file_exists($imagepath.$old_icon)) {
					unlink($imagepath.$old_icon);
				}
				move_uploaded_file($up_image[tmp_name],$imagepath.$up_image[name]);
				chmod($imagepath.$up_image[name],0606);
			} else {
				$up_image[name] = $old_icon;
			}
		}


		if ($cnt==1) {
			$sql="UPDATE tblshopsnsinfo SET ";
			$sql.= "appid		= '".$appid."', ";
			$sql.= "secret		= '".$secret."', ";
			$sql.= "icon_img	= '".$up_image[name]."', ";
			$sql.= "state		= '".$state."' ";
			$sql.= "WHERE type = '".$type."'";
			$onload="<script>alert('sns ä������ ������ �Ϸ�Ǿ����ϴ�.');</script>";
		} else {
			$sql="INSERT INTO tblshopsnsinfo (type,appid,secret,icon_img,state) VALUES ('".$type."','".$appid."','".$secret."','".$up_image[name]."','".$state."')";
			$onload="<script>alert('sns ä������ ����� �Ϸ�Ǿ����ϴ�.');</script>";
		}
		mysql_query($sql,get_db_conn());
	}
}

$sql = "SELECT sns_ok,sns_reserve_type,sns_recomreserve,sns_memreserve ";
$sql.= "FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$sns_ok=$row->sns_ok;
	$sns_reserve_type=$row->sns_reserve_type;
	$sns_recomreserve=$row->sns_recomreserve;
	$sns_memreserve=$row->sns_memreserve;
	$arSnsType = explode("",$sns_reserve_type);
}

mysql_free_result($result);

$extra_conf_sql = "SELECT `value` FROM `extra_conf` WHERE `type` = 'tblshopinfo' AND `name` = 'snsBoardReserve' ";
$extra_conf_res = mysql_query($extra_conf_sql,get_db_conn());
if ($extra_conf_row=mysql_fetch_object($extra_conf_res)) {
	$snsBoardReserve=$extra_conf_row->value;
}
mysql_free_result($extra_conf_res);
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	document.form1.type.value="up";
	document.form1.submit();
}
function CheckForm2() {
	document.form2.type.value="snsinfo";
	document.form2.submit();
}
function rsvType(val){
	if(val =="A"){
		document.getElementById("snsTypeWrap").style.display = "block";
	}else{
		document.getElementById("snsTypeWrap").style.display = "none";
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
			<? include ("menu_shop.php"); ?>
			</td>

			<td></td>
			<td valign="top">

<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� � ���� &gt; <span class="2depth_select">SNS �� ȫ�������� ����</span></td>
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
					<TD><IMG SRC="images/shop_sns_title.gif"></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
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
							<TD width="100%" class="notice_blue"><p>SNS�� ���� ��ǰ ȫ���� ���� ������ �ο��� �� �ֽ��ϴ�. Ÿ ���θ��� ����ȭ�Ǵ� SNS ȫ�������� Ȱ���� ������.</p></TD>
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
							<TD><IMG SRC="images/shop_sns_stitle1.gif" HEIGHT=31 ALT=""></TD>
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
							<TD width="100%" class="notice_blue">1) SNS ���� ��ǰ ����������
							SNS�˸��� ������ ��Ÿ���ϴ�.</TD>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method="post">
			<input type="hidden" name="type" />
			<tr>
				<td>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">SNS ���뿩�� ����</TD>
							<TD class="td_con1"><input type=radio id="idx_sns_ok1" name=up_sns_ok value="Y" <?=($sns_ok =="Y")? "checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sns_ok1>SNS ���</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=radio id="idx_sns_ok2" name=up_sns_ok value="N" <?=($sns_ok !="Y")? "checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_sns_ok2>SNS ���Ұ�</label></TD>
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
							<TD><IMG SRC="images/shop_sns_stitle2.gif" HEIGHT=31 ALT=""></TD>
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
							<TD width="100%" class="notice_blue">
								1) My page > �����ݿ��� �߰��� ������ Ȯ�� �����մϴ�.
								<br>2) <font style="color:#f00"><b>�̸���, sms, sns</b></font>�� ���� ȸ���� ���� ��ǰȫ�� url�� ���� ���θ����� ��ǰ �ֹ��� ��ۿϷ�Ǹ� �������� �߰� �����˴ϴ�.
								<br>3) �������� ��� ���� �����÷��� 0������ ǥ�� �Ͻø� �˴ϴ�.
								<br>4) �������� ���� ��� ó���� ���� �˴ϴ�.
								<br>5) �Խ��� ȫ�� �������� ȫ��URL�� ���� ���� ���� 1ȸ���� �˴ϴ�.
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
			<tr><td height=3></td></tr>
			<tr>
				<td>
					<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰȫ�� ������ ����</TD>
							<TD class="td_con1" >
							<input type="radio" name="up_sns_reserve_type" id="up_sns_reserve_typeN" value="N" onclick="rsvType('N');" <?=($arSnsType[0] =="" || $arSnsType[0] == "N")? "checked":""?>><label for="up_sns_reserve_typeN">������ ������</label>
							<input type="radio" name="up_sns_reserve_type" id="up_sns_reserve_typeA" value="A" onclick="rsvType('A');" <?=($arSnsType[0] == "A")? "checked":""?>><label for="up_sns_reserve_typeA">��ü��ǰ �ϰ�����</label>
							<!-- <input type="radio" name="up_sns_reserve_type" id="up_sns_reserve_typeB" value="B" onclick="rsvType('B');" <?=($arSnsType[0] == "B")? "checked":""?>><label for="up_sns_reserve_typeB">�� ��ǰ�� �����ݼ��������� ����</label> -->
							</TD>
						</TR>
						<TR id="snsTypeWrap">
							<TD colspan="2">
								<table WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
									<col width="139"></col>
									<col width=""></col>
									<TR>
										<TD colspan="2"  background="images/table_con_line.gif"></TD>
									</TR>
									<TR>
										<TD class="table_cell" width="139px"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ ȫ���� ������</TD>
										<TD class="td_con1" >
											<input name="up_sns_recomreserve" value="<?=$sns_recomreserve?>" size=10 maxlength=6 class="input">
											�� <input type="hidden" name="up_sns_recomreserve_type" value="N">
											<? /*
											<select name="up_sns_recomreserve_type" class="select">
												<option value="N"<?=($arSnsType[1]!="Y")?" selected":""?>>������(��)</option>
												<option value="Y"<?=($arSnsType[1]!="Y")?"":" selected"?>>������(%)</option>
											</select>
											*/?>
											<select name="up_sns_recomreserve_give_type" class="select">
												<option value="O"<?=($arSnsType[3]!="A")?" selected":""?>>1ȸ����</option>
												<option value="A"<?=($arSnsType[3]!="A")?"":" selected"?>>��������</option>
											</select>
										</TD>
									</TR>
									<TR>
										<TD colspan="2"  background="images/table_con_line.gif"></TD>
									</TR>
									<TR>
										<TD class="table_cell" width="139px"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ ������ ������</TD>
										<TD class="td_con1">
											<input name="up_sns_memreserve" value="<?=$sns_memreserve?>" size=10 maxlength=6 class="input">
											�� <input type="hidden" name="up_sns_memreserve_type" value="N">
											<? /*
											<select name="up_sns_memreserve_type" class="select">
												<option value="N"<?=($arSnsType[2]!="Y")?" selected":""?>>������(��)</option>
												<option value="Y"<?=($arSnsType[2]!="Y")?"":" selected"?>>������(%)</option>
											</select>
											*/ ?>
											<select name="up_sns_memreserve_give_type" class="select">
												<option value="O"<?=($arSnsType[4]!="A")?" selected":""?>>1ȸ����</option>
												<option value="A"<?=($arSnsType[4]!="A")?"":" selected"?>>��������</option>
											</select>
										</TD>
									</TR>
									<TR>
										<TD colspan=2 background="images/table_top_line.gif"></TD>
									</TR>
									<TR>
										<TD class="table_cell" width="139px"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� ȫ���� ������</TD>
										<TD class="td_con1">
											<input name="snsBoardReserve" value="<?=$snsBoardReserve?>" size=10 maxlength=6 class="input"> �� (���� 1ȸ ����)
										</TD>
									</TR>
								</table>
							</TD>
						</TR>
						<TR>
							<TD colspan=2 background="images/table_top_line.gif"></TD>
						</TR>
					</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			<tr><td height="30"></td></tr>
			</form>
			</table>

			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
						<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
							<TR>
								<TD><IMG SRC="images/shop_bitly_stitle3.gif"  HEIGHT=31 ALT="���� ������ ���� ����"></TD>
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
								<TD COLSPAN=1 background="images/distribute_02.gif"></TD>
								<TD><IMG SRC="images/distribute_03.gif"></TD>
							</TR>
							<TR>
								<TD background="images/distribute_04.gif"></TD>
								<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
								<TD width="100%" class="notice_blue">
									1) https://bitly.com ���񽺸� ���� http://bit.ly/xxxxxxx ������ ��URL�� �����մϴ�.<br />
									2) <a href="https://bitly.com/" target="_blank">https://bitly.com/</a>�� ���� �� <a href="https://bitly.com/a/oauth_apps" target="_blank">https://bitly.com/a/oauth_apps</a>�� Generic Access Token�� ����/�Է��� �ֽø� �˴ϴ�.
								</TD>
								<TD background="images/distribute_07.gif"></TD>
							</TR>
							<TR>
								<TD><IMG SRC="images/distribute_08.gif"></TD>
								<TD COLSPAN=1 background="images/distribute_09.gif"></TD>
								<TD><IMG SRC="images/distribute_10.gif"></TD>
							</TR>
						</TABLE>
					</td>
				</tr>
				<tr><td height="5"></td></tr>
				<tr>
					<td>
						<?
							if( $type == "bitlyUpdate" ) {
								$extra_conf_sql = "SELECT `value` FROM `extra_conf` WHERE `type` = 'bitlyShortUrl' AND `name` = 'accessToken' ";
								$extra_conf_res = mysql_query($extra_conf_sql,get_db_conn());
								if( mysql_num_rows($extra_conf_res) ) {
									$extra_conf_up_sql = "UPDATE `extra_conf` SET `value`='".$accessToken."' WHERE `type` = 'bitlyShortUrl' AND `name` = 'accessToken' ";
								} else{
									$extra_conf_up_sql = "INSERT `extra_conf` SET `type` = 'bitlyShortUrl', `name` = 'accessToken', `value`='".$accessToken."' ";
								}
								$extra_conf_up_res = mysql_query($extra_conf_up_sql,get_db_conn());
								mysql_free_result($extra_conf_res);
								mysql_free_result($extra_conf_up_res);
								$onload="<script>alert('Bitly's API Access Token ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
							}
							$extra_conf_sql = "SELECT `value` FROM `extra_conf` WHERE `type` = 'bitlyShortUrl' AND `name` = 'accessToken' ";
							$extra_conf_res = mysql_query($extra_conf_sql,get_db_conn());
							if ($extra_conf_row=mysql_fetch_object($extra_conf_res)) {
								$accessToken=$extra_conf_row->value;
							}
							mysql_free_result($extra_conf_res);
						?>
						<form name="bitlyForm" action="<?=$_SERVER[PHP_SELF]?>" method="POST">
						<input type=hidden name=type value="bitlyUpdate">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD colspan=3 background="images/table_top_line.gif"></TD>
							</TR>
							<TR>
								<TD class="table_cell" width="140"><img src="images/icon_point2.gif" width="8" height="11" border="0">Bitly's API</TD>
								<TD class="td_con1" width="200">Generic Access Token</TD>
								<TD class="td_con1">
									<input type="text" name="accessToken" value="<?=$accessToken?>" style="width:80%;">
								</TD>
							</TR>
							<TR>
								<TD colspan="3" background="images/table_top_line.gif"></TD>
							</TR>
						</TABLE>
						</form>
					</td>
				</tr>
				<tr>
					<td align="center"><img src="images/botteon_save.gif" width="113" height="38" border="0" onclick="bitlyForm.submit();" style="cursor:pointer;"></td>
				</tr>
			</table>


<!-- //sns �⺻���� -->
			<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
					<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
						<TR>
							<TD><IMG SRC="images/shop_sns_stitle3.gif"  HEIGHT=31 ALT=""></TD>
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
							<TD width="100%" class="notice_blue">
								1) sns �α��� ������ ���� �� SNS���� ��ID�� �� ��ũ���ڵ带 �߱޹޾� ������ּ���.<br />
								2) Facebook : <a href="https://www.facebook.com/developers/createapp.php" target="_blank">https://www.facebook.com/developers/createapp.php</a><br />
								3) Twitter : <a href="https://dev.twitter.com/apps/new" target="_blank">https://dev.twitter.com/apps/new</a>
								<!--<br>4) Me2day : <a href="http://me2day.net/me2/app" target="_blank">http://me2day.net/me2/app</a> �α��� �� �� Ű �߱޹ޱ�-->
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
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<?
					$sql = "SELECT * FROM tblshopsnsinfo ";
					$result=@mysql_query($sql,get_db_conn());
					while($row=@mysql_fetch_object($result)) {
						$type=$row->type;
						$appid=$row->appid;
						$secret=$row->secret;
						$icon_img=$row->icon_img;
						$state=$row->state;
						$arSnsinfo["{$type}"][]=$appid;
						$arSnsinfo["{$type}"][]=$secret;
						$arSnsinfo["{$type}"][]=$icon_img;
						$arSnsinfo["{$type}"][]=$state;
					}
					mysql_free_result($result);
				?>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
					<TR>
						<TD colspan=3 background="images/table_top_line.gif"></TD>
					</TR>
					<TR>
						<TD class="table_cell" width="140" rowspan="3"><img src="images/icon_point2.gif" width="8" height="11" border="0">���̽���</TD>
						<TD class="td_con1" width="100"style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;">��뼳��</TD>
						<TD class="td_con1" style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;">
							<input type=radio id="f_state1" name="f_state" value="Y" <?=($arSnsinfo['f'][3] =="Y")? "checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=f_state1>���</label><input type=radio id="f_state2" name=f_state value="N" <?=($arSnsinfo['f'][3] !="Y")? "checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=f_state2>������</label>
						</TD>
					</TR>
					<TR>
						<TD class="td_con1" width="100"style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;">�� ID</TD>
						<TD class="td_con1" style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;"><input type="text" name="f_appid" value="<?=$arSnsinfo['f'][0]?>" size=50 maxlength=40 class="input"></TD>
					</TR>
					<TR>
						<TD class="td_con1" width="100">�� Secret</TD>
						<TD class="td_con1" ><input type="text" name="f_secret" value="<?=$arSnsinfo['f'][1]?>" size=50 maxlength=80 class="input"></TD>
					</TR>
					<!--
					<TR>
						<TD class="td_con1" width="100">�� ������</TD>
						<TD class="td_con1" ><?=($arSnsinfo['f'][2])? "<img src=\"".$imagepath.$arSnsinfo['f'][2]."\" align=\"absmiddle\" width=\"23\">":"<img src=\"/images/design/icon_facebook_on.gif\" align=\"absmiddle\" width=\"23\">"?> <input type="file" name="f_icon"  size=50  class="input"></TD>
					</TR>
					-->
					<TR>
						<TD colspan="3"  background="images/table_con_line.gif"></TD>
					</TR>
				</TABLE>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD class="table_cell" width="140" rowspan="3"><img src="images/icon_point2.gif" width="8" height="11" border="0">Ʈ����</TD>
					<TD class="td_con1" width="100"style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;">��뼳��</TD>
					<TD class="td_con1" style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;">
						<input type=radio id="t_state1" name="t_state" value="Y" <?=($arSnsinfo['t'][3] =="Y")? "checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=t_state1>���</label><input type=radio id="t_state2" name=t_state value="N" <?=($arSnsinfo['t'][3] !="Y")? "checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=t_state2>������</label>
					</TD>
				</TR>
				<TR>
					<TD class="td_con1" width="100"style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;">�� ID</TD>
					<TD class="td_con1" style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;"><input type="text" name="t_appid" value="<?=$arSnsinfo['t'][0]?>" size=50 maxlength=40 class="input"></TD>
				</TR>
				<TR>
					<TD class="td_con1" width="100">�� Secret</TD>
					<TD class="td_con1" ><input type="text" name="t_secret" value="<?=$arSnsinfo['t'][1]?>" size=50 maxlength=80 class="input"></TD>
				</TR>
				<!-- <TR>
					<TD class="td_con1" width="100">�� ������</TD>
					<TD class="td_con1" ><?=($arSnsinfo['t'][2])? "<img src=\"".$imagepath.$arSnsinfo['t'][2]."\" align=\"absmiddle\" width=\"23\">":"<img src=\"/images/design/icon_twitter_on.gif\" align=\"absmiddle\" width=\"23\">"?> <input type="file" name="t_icon"  size=50  class="input"></TD>
				</TR> -->

					<TR>
						<TD colspan="3"  background="images/table_top_line.gif"></TD>
					</TR>
				</TABLE>

				<!--
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD class="table_cell" width="140" rowspan="3"><img src="images/icon_point2.gif" width="8" height="11" border="0">��������</TD>
					<TD class="td_con1" width="100" style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;">��뼳��</TD>
					<TD class="td_con1" style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;">
						<input type=radio id="m_state1" name="m_state" value="Y" <?=($arSnsinfo['m'][3] =="Y")? "checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=m_state1>���</label><input type=radio id="m_state2" name=m_state value="N" <?=($arSnsinfo['m'][3] !="Y")? "checked":""?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=m_state2>������</label>
					</TD>
				</TR>
				<TR>
					<TD class="td_con1" width="100"style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;">�� ID</TD>
					<TD class="td_con1" style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;"><input type="text" name="m_appid" value="<?=$arSnsinfo['m'][0]?>" size=50 maxlength=40 class="input"></TD>
				</TR>
				<TR>
					<TD class="td_con1" width="100">�� Secret</TD>
					<TD class="td_con1" style="border-bottom-width:1px; border-bottom-color:#E3E3E3; border-bottom-style:solid;"><input type="text" name="m_secret" value="<?=$arSnsinfo['m'][1]?>" size=50 maxlength=80 class="input"></TD>
				</TR>
				<!-- <TR>
					<TD class="td_con1" width="100">�� ������</TD>
					<TD class="td_con1" ><?=($arSnsinfo['m'][2])? "<img src=\"".$imagepath.$arSnsinfo['m'][2]."\" align=\"absmiddle\" width=\"23\">":"<img src=\"/images/design/icon_me2day_on.gif\" align=\"absmiddle\" width=\"23\">"?> <input type="file" name="m_icon"  size=50  class="input"></TD>
				</TR> --//>

				<TR>
					<TD colspan=3 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				-->

				</td>
			</tr>
			<tr><td height="30"></td></tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm2();"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			</form>
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
<script type="text/javascript">
rsvType('<?=$arSnsType[0]?>');
</script>

<? INCLUDE "copyright.php"; ?>