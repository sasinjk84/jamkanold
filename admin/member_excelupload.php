<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "me-1";
$MenuCode = "member";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

@set_time_limit(300);

setlocale(LC_CTYPE, 'ko_KR.eucKR');

if(!function_exists('fputcsv')) {
	function fputcsv(&$handle, $fields = array(), $delimiter = ',', $enclosure = '"') {
		$str = '';
		$escape_char = '\\';
		foreach ($fields as $value) {
			if (strpos($value, $delimiter) !== false ||
			strpos($value, $enclosure) !== false ||
			strpos($value, "\n") !== false ||
			strpos($value, "\r") !== false ||
			strpos($value, "\t") !== false ||
			strpos($value, ' ') !== false) {
				$str2 = $enclosure;
				$escaped = 0;
				$len = strlen($value);
				for ($i=0;$i<$len;$i++) {
					if ($value[$i] == $escape_char) {
						$escaped = 1;
					} else if (!$escaped && $value[$i] == $enclosure) {
						$str2 .= $enclosure;
					} else {
						$escaped = 0;
					}
					$str2 .= $value[$i];
				}
				$str2 .= $enclosure;
				$str .= $str2.$delimiter;
			} else {
				$str .= $value.$delimiter;
			}
		}
		$str = substr($str,0,-1);
		$str .= "\n";
		return fwrite($handle, $str);
	}
}

$imagepath=$Dir.DataDir."shopimages/etc/";
$filename="memexcelupfile.csv";
$filepath2=$imagepath."member_error.csv";
@unlink($imagepath.$filename);

$mode=$_POST["mode"];
$group_code=$_POST["group_code"];
$upfile=$_FILES["upfile"];

$reg_group=$_shopdata->group_code;

$group_list=array();
$sql = "SELECT group_code,group_name FROM tblmembergroup ";
$result = mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)){
	if(strlen($group_code)>0) {
		if($row->group_code==$group_code) {
			$reg_group=$row->group_code;
		}
	}
	$group_list[]=$row;
}


if($mode=="upload" && strlen($upfile[name])>0 && $upfile[size]>0) {
	########################### TEST ���θ� Ȯ�� ##########################
	DemoShopCheck("������������� �׽�Ʈ�� �Ұ��� �մϴ�.", $_SERVER[PHP_SELF]);
	#######################################################################

	$ext = strtolower(substr($upfile[name],strlen($upfile[name])-3,3));
	if($ext=="csv") {
		copy($upfile[tmp_name],$imagepath.$filename);
		chmod($imagepath.$filename,0664);
	} else {
		echo "<html><head></head><body onload=\"alert('���������� �߸��Ǿ� ���ε尡 �����Ͽ����ϴ�.\\n\\n��� ������ ������ ����(CSV) ���ϸ� ��� �����մϴ�.');location='".$_SERVER["PHP_SELF"]."'\"></body></html>";exit;
	}

	########################################################################################################
	# 0=>���̵�, 1=>��й�ȣ, 2=>�̸�, 3=>�ֹι�ȣ, 4=>�̸���, 5=>�޴���, 6=>�̸��ϼ��ſ���, 7=>SMS���ſ���
	# 8=>����ȭ, 9=>�������ȣ, 10=>���ּ�(��/��/�� �̻�), 11=>���ּ�(���� �̸�), 12=>ȸ����ȭ
	# 13=>ȸ������ȣ, 14=>ȸ���ּ�(��/��/�� �̻�), 15=>ȸ���ּ�(���� �̸�), 16=>������, 17=>������
	########################################################################################################

	$query="INSERT INTO tblmember (id,passwd,name,resno,email,mobile,news_yn,gender,home_post,home_addr,home_tel,office_post,office_addr,office_tel,reserve,joinip,date,group_code) VALUES ";

	$error_list=array();
	$memcnt=0;
	$filepath=$imagepath.$filename;
	$fp=fopen($filepath,"r");
	$yy=0;
	while($field=@fgetcsv($fp, 4096, ",")) {
		if($yy++==0) continue;

		$id=trim($field[0]);
		$passwd=trim($field[1]);
		$name=trim($field[2]);
		$resno=trim($field[3]);
		$email=trim($field[4]);
		$mobile=trim($field[5]);
		$news_mail_yn=trim($field[6]);
		$news_sms_yn=trim($field[7]);
		$home_tel=trim($field[8]);
		$home_post=trim($field[9]);
		$home_post=@str_replace("-","",$home_post);
		$home_addr1=trim($field[10]);
		$home_addr2=trim($field[11]);
		$office_tel=trim($field[12]);
		$office_post=trim($field[13]);
		$office_post=@str_replace("-","",$office_post);
		$office_addr1=trim($field[14]);
		$office_addr2=trim($field[15]);
		$reserve=(int)trim($field[16]);

		$date=trim(@str_replace("/","",$field[17]));
		$date=@str_replace("-","",$date);
		if(strlen($date)!=8) $date=date("Ymd");
		$date.="000000";

		if(!preg_match("/^(Y|N)$/",$news_mail_yn)) {
			$news_mail_yn="Y";
		}
		if(!preg_match("/^(Y|N)$/",$news_sms_yn)) {
			$news_sms_yn="Y";
		}
		if($news_mail_yn=="Y" && $news_sms_yn=="Y") {
			$news_yn="Y";
		} else if($news_mail_yn=="Y") {
			$news_yn="M";
		} else if($news_sms_yn=="Y") {
			$news_yn="S";
		} else {
			$news_yn="N";
		}

		$home_addr="";
		if(strlen($home_post)==6) $home_addr=$home_addr1."=".$home_addr2;
		$home_addr = str_replace("'","\'",$home_addr);

		$office_addr="";
		if(strlen($office_post)==6) $office_addr=$office_addr1."=".$office_addr2;
		$office_addr = str_replace("'","\'",$office_addr);

		$resno=str_replace("-","",$resno);
		if(strlen($resno)==13) {
			if(!chkResNo($resno)) $resno="";
		} else if(strlen($resno)==41) {

			//7011031[670b14728ad9902aecba32e22fa4f6bd]
		} else {
			$resno="";
		}

		$joinip="127.0.0.1";

		if(strlen($id)==0 || strlen($passwd)==0 || strlen($name)==0 || strlen($email)==0) {
			$error_list[]=$field;
			continue;
		} else if($_shopdata->resno_type!="N" && strlen($resno)==0) {
			$error_list[]=$field;
			continue;
		} else if(!IsAlphaNumeric($id)) {
			$error_list[]=$field;
			continue;
		} else if(!ismail($email)) {
			$error_list[]=$field;
			continue;
		} else if($passwd=="����ȸ��" || substr($resno,0,7)=="9999999") {
			$error_list[]=$field;
			continue;
		}

		//���̵� �ߺ� üũ
		$sql = "SELECT COUNT(*) as cnt FROM tblmember WHERE id='".$id."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);
		if($row->cnt>=1) {
			$error_list[]=$field;
			continue;
		}

		$gender="";
		if(strlen($resno)>0) {		//�ֹι�ȣ �ߺ�üũ
			$sql = "SELECT COUNT(*) as cnt FROM tblmember WHERE resno='".$resno."' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			mysql_free_result($result);
			if($row->cnt>=1) {
				$error_list[]=$field;
				continue;
			}
			$gender=substr($resno,6,1);
		}

		//�̸��� �ߺ� üũ
		$sql = "SELECT COUNT(*) as cnt FROM tblmember WHERE email='".$email."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);
		if($row->cnt>=1) {
			$error_list[]=$field;
			continue;
		}

		//��й�ȣ ������
		if(strlen($passwd)<16) {
			$passwd=md5($passwd);
		}

		$memcnt++;
		$query.= "('".$id."','".$passwd."','".$name."','".$resno."','".$email."','".$mobile."','".$news_yn."','".$gender."','".$home_post."','".$home_addr."','".$home_tel."','".$office_post."','".$office_addr."','".$office_tel."','".$reserve."','".$joinip."','".$date."','".$reg_group."'),";

		if($memcnt==1000) {
			$query=substr($query,0,-1);
			mysql_query($query,get_db_conn());
			$memcnt=0;
			$query="INSERT INTO tblmember (id,passwd,name,resno,email,mobile,news_yn,gender,home_post,home_addr,home_tel,office_post,office_addr,office_tel,reserve,joinip,date,group_code) VALUES ";
		}
	}
	@fclose($fp);
	@unlink($filepath);

	if($memcnt>0) {
		$query=substr($query,0,-1);
		mysql_query($query,get_db_conn());
	}

	@unlink($filepath2);
	if(count($error_list)>0) {
		$fp2=fopen($filepath2,"a");
		for($i=0;$i<count($error_list);$i++) {
			if(strlen($error_list[$i])>0) {
				fputcsv($fp2,$error_list[$i]);
			}
		}
		@fclose($fp2);
	}

	echo "<html><head></head><body onload=\"alert('ȸ������ ����� �Ϸ�Ǿ����ϴ�.');location.href='".$_SERVER["PHP_SELF"]."';\"></body></html>";exit;
} else if($mode=="error_del") {
	@unlink($filepath2);
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
var isupload=false;
function CheckForm() {
	if(isupload==true) {
		alert("######### ���� ȸ�������� ������Դϴ�. #########");
		return;
	}

	if(document.form1.group_code.value=="") {
		if(!confirm("ȸ������� ���������ʰ� ����Ͻðڽ��ϱ�?")) {
			return;
		}
	} else {
		temp=document.form1.group_code.options[document.form1.group_code.selectedIndex].text;
		if(!confirm("\""+temp+"\" ȸ��������� ����Ͻðڽ��ϱ�?")) {
			return;
		}
	}

	isupload=true;
	document.all.uploadButton.style.filter = "Alpha(Opacity=60) Gray";
	document.form1.mode.value="upload";
	document.form1.submit();
}

function delete_errfile() {
	if(isupload==true) {
		alert("######### ���� ȸ�������� ������Դϴ�. #########");
		return;
	}
	if(confirm("��� ������ ȸ������ ���������� �������� �����Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="error_del";
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
			<? include ("menu_member.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ȸ������ &gt; ȸ���������� &gt; <span class="2depth_select">ȸ������ �ϰ� ���</span></td>
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
					<TD><IMG SRC="images/member_excelupload_title.gif" border=0></TD>
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
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">�ټ��� ȸ�������� �������Ϸ� ����� �ϰ� ����� �ϴ� ����Դϴ�.</TD>
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
			<tr><td height=20></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/member_excelupload_stitle1.gif" board=0></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name=mode>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<TR>
					<TD background="images/table_top_line.gif" colspan=2></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ��Ͼ�� �ٿ�ε�</TD>
					<TD class="td_con1" ><A HREF="images/sample/member.csv"><img src="images/btn_down1.gif" border=0 align=absmiddle></A> <span class="font_orange">������(CSV)������ �������� �� ������ ���� �ۼ��մϴ�.</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ����� ����</TD>
					<TD class="td_con1" >
					<select name=group_code>
						<option value="">ȸ������� �����ϼ���.</option>
<?
						for($i=0;$i<count($group_list);$i++) {
							echo "<option value=\"".$group_list[$i]->group_code."\">".$group_list[$i]->group_name."</option>\n";
						}
?>
					</select>
					<span class="font_orange">����޼����� <B>"ȸ������ -> ȸ����� ����"</B>���� �Ͻø� �˴ϴ�.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0">��������(CSV) ���</TD>
					<TD class="td_con1" ><input type=file name=upfile style="width:54%" class="input"> <span class="font_orange">������(CSV) ���ϸ� ��� �����մϴ�.</span></TD>
				</TR>

				<?if(file_exists($filepath2)==true){?>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell" width="139"><img src="images/icon_point2.gif" width="8" height="11" border="0"><font color=red>��Ͻ��� ���� ����</font></TD>
					<TD class="td_con1" ><A HREF="<?=$filepath2?>"><B>[�ٿ�ε�]</B></A> <img width=10 height=0> <A HREF="javascript:delete_errfile()"><B>[�����ϱ�]</B></A> &nbsp;&nbsp;&nbsp; <span class="font_orange">����� ������ �����͸� ����(CSV)���Ϸ� �ٿ�/���� �Ͻ� �� �ֽ��ϴ�.</span></TD>
				</TR>
				<?}?>

				<TR>
					<TD background="images/table_top_line.gif" colspan=2></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td align="center" height=10></td>
			</tr>
			<tr>
				<td align="center"><img src="images/btn_fileup.gif" id="uploadButton" width="113" height="38" border="0" style="cursor:hand" onclick="CheckForm(document.form1);"></td>
			</tr>
			</form>
			<tr>
				<td height=20></td>
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
					<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">ȸ������ �ϰ� ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">
						- ȸ�������� �ϰ� ��� �ϰų�, Ÿ ���θ� �̿� �� ������ �����ϴµ� �����ϰ� ���˴ϴ�.
						<br>
						<span class="font_orange" style="padding-left:0px"><B>- ȸ�������͸� ������ ��� ȸ���� ���ǰ� �� �ʿ��Ͽ��� ȸ�� ���� �� �����Ͻñ� �ٶ��ϴ�.</B></span>
						</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">����(CSV)���� �ۼ� ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">
						- �������� �ۼ��� �ι� ° ���κ��� �����͸� �Է��Ͻñ� �ٶ��ϴ�. (ù ������ �ʵ� ����κ�)<br>
						- �Ʒ� ���Ĵ�� <FONT class=font_orange><B>�������� �ۼ� -> �ٸ��̸����� ���� -> CSV(��ǥ�� �и�)</B></font> ������ �����Ͻø� �˴ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">ȸ������ �ϰ���� ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �� �Ʒ��� ������ ����� ȸ������ ���������� �ۼ��մϴ�.<br>
						<span class="font_orange" style="padding-left:10px">----------------------------------------------------- ��ǰ���� ���� ���� -----------------------------------------------------</span><br>
						<span class="font_blue" style="padding-left:25px">���̵�, ��й�ȣ, �̸�, �ֹι�ȣ, �̸���, �޴���, �̸��ϼ���, SMS����, ����ȭ, �������ȣ, ���ּ�(��/��/�� �̻�), </span>
						<br>
						<span class="font_blue" style="padding-left:25px">���ּ�(���� �̸�), ȸ����ȭ, ȸ������ȣ, ȸ���ּ�(��/��/�� �̻�), ȸ���ּ�(���� �̸�), ������, ������<span><br>
						<span class="font_orange" style="padding-left:10px">------------------------------------------------------------------------------------------------------------------------------</span><br>

						<div style="padding-left:30">
						<table border=0 cellpadding=0 cellspacing=0 width=600>
						<col width=145></col>
						<col width=></col>
						<tr>
							<td colspan=2 align=center style="padding-bottom:5">
							<B>ȸ������ ���� �ۼ� ��)</B>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���̵�<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							getmall <img width=20 height=0><FONT class=font_orange>(����/���� 4~12��) <B>- ���̵� �ߺ��� ����� �ʵ˴ϴ�</B></font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">��й�ȣ<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							1234 <img width=20 height=0><FONT class=font_orange>(��ȣȭ�� ��й�ȣ�� ��� ����)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�̸�<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							ȫ�浿
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�ֹι�ȣ

							<?if($_shopdata->resno_type!="N") {?>
							<FONT class=font_orange>(*)</font>
							<?}?>

							</td>
							<td class=td_con1 style="padding-left:5;">
							701103-1000000 <FONT class=font_orange>=>�Ϲ����� �ֹι�ȣ</font>
							<br>701103-1[670b14728ad9902aecba32e22fa4f6bd] <FONT class=font_orange>=> �ֹι�ȣ ��6�ڸ� ��ȣȭ</font>
							<br>
							<FONT class=font_orange><B>(�ֹι�ȣ �ߺ��� ����� �ʵ˴ϴ�.)</B></font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�̸���<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							master@getmall.co.kr <img width=20 height=0><FONT class=font_orange><B>(�̸��� �ߺ��� ����� �ʵ˴ϴ�.)</B></font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�޴���</td>
							<td class=td_con1 style="padding-left:5;">
							010-000-0000
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�̸��� ���ſ���<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							Y
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">SMS ���ſ���<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							Y
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">����ȭ<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							02-00-0000
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">�� �����ȣ<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							137-070
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���ּ� (��/��/�� �̻�)<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							����� ���ʱ� ���ʵ�
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">���ּ� (���� �̸�)<FONT class=font_orange>(*)</font></td>
							<td class=td_con1 style="padding-left:5;">
							1358-18���� XX���� 8��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">ȸ����ȭ</td>
							<td class=td_con1 style="padding-left:5;">
							02-111-1111
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">ȸ�� �����ȣ</td>
							<td class=td_con1 style="padding-left:5;">
							137-073
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">ȸ���ּ� (��/��/�� �̻�)</td>
							<td class=td_con1 style="padding-left:5;">
							����� ���ʱ� ��赿
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">ȸ���ּ� (���� �̸�)</td>
							<td class=td_con1 style="padding-left:5;">
							18-18���� XX���� 3��
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">������</td>
							<td class=td_con1 style="padding-left:5;">
							0
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">������</td>
							<td class=td_con1 style="padding-left:5;">
							2007/05/10 <img width=20 height=0><FONT class=font_orange>(���� ��¥�� ��Ͻ� ����)</font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						</table>
						</div>

						<span class="font_orange" style="padding-left:10px">------------------------------------------------------------------------------------------------------------------------------</span>
						</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �� ����(CSV)������ �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �� [���ϵ��] ��ư�� �̿��Ͽ� ���ε� �Ϸ� �ϸ� ȸ�������� ��ϵ˴ϴ�.</td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"><IMG SRC="images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
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