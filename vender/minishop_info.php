<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$mode=$_POST["mode"];
if($mode=="update") {
	$up_brand_name=$_POST["up_brand_name"];
	$up_description=$_POST["up_description"];
	$upfile=$_FILES["upfile"];

	if(strlen($up_brand_name)==0) {
		echo "<html></head><body onload=\"alert('�̴ϼ����� �Է��ϼ���.');\"></body></html>";exit;
	}
	$sql = "SELECT COUNT(*) as cnt FROM tblvenderstore ";
	$sql.= "WHERE vender!='".$_VenderInfo->getVidx()."' ";
	$sql.= "AND brand_name='".$up_brand_name."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$cnt=$row->cnt;
	mysql_free_result($result);
	if($cnt>0) {
		echo "<html></head><body onload=\"alert('�̴ϼ����� �ߺ��Ǿ����ϴ�.\\n\\n�̴ϼ��� �ߺ�Ȯ���� �ϼ���.');\"></body></html>";exit;
	}

	if(strlen($upfile["name"])>0 && $upfile["size"]>0 && file_exists($upfile["tmp_name"])) {
		$ext = strtolower(substr($upfile["name"],strlen($upfile["name"])-3,3));
		if($ext=="gif" || $ext=="jpg"){
			$imagenameorg="logo_".$_VenderInfo->getVidx().".gif";
			move_uploaded_file($upfile["tmp_name"],$Dir.DataDir."shopimages/vender/".$imagenameorg);
			chmod($Dir.DataDir."shopimages/vender/".$imagenameorg,0664);
		} else {
			echo "<html></head><body onload=\"alert('�̹��� ����� gif, jpg ���ϸ� ��� �����մϴ�.\\n\\nȮ�� �� �ٽ� ����Ͻñ� �ٶ��ϴ�.')\"></body></html>";exit;
		}
	}
	$sql = "UPDATE tblvenderstore SET ";
	$sql.= "brand_name			= '".$up_brand_name."', ";
	$sql.= "brand_description	= '".$up_description."' ";
	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
	if(mysql_query($sql,get_db_conn())) {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� �����Ͽ����ϴ�.');parent.location.reload()\"></body></html>";exit;
	} else {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.')\"></body></html>";exit;
	}
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function formSubmit(proc_type) {
	if(iForm.up_brand_name.value.length==0) {
		alert("�̴ϼ����� �Է��ϼ���.");
		document.iForm.up_brand_name.style.background="#EEFFB6";
		document.iForm.up_brand_name.focus();
		return;
	}
	if(iForm.upfile.value.length>0){
		var local_img = iForm.upfile.value;
		if (!ValidImageFile(iForm.upfile)){
			alert("�ùٸ� �ΰ� �̹����� �����ϼ���.");
			iForm.upfile.focus();
			return;     
		}
		iForm.image_path.value=iForm.upfile.value;
	}

	if (proc_type=="preview") {
		mallWin = windowOpenScroll("", "MinishopPreview", 920, 500);
		mallWin.focus();

		iForm.target = "MinishopPreview";
		iForm.action = "preview.minishop.php";
		iForm.submit();
		iForm.action = "";
	} else {
		if (confirm("�����Ͻ� ������ �����Ͻðڽ��ϱ�?")) {
			iForm.mode.value="update";
			iForm.target = "processFrame";
			iForm.submit();
		}
	}
}

function confirmMinishopName(name) {
	if(name.length==0) {
		alert("�̴ϼ����� �Է��ϼ���.");
		document.iForm.up_brand_name.style.background="#EEFFB6";
		document.iForm.up_brand_name.focus();
		return;
	}
	mallWin = windowOpenScroll("confirm_minishopname.php?brand_name="+name, "confirmMinishopName", 620, 400);
	mallWin.focus();
}
</script>
<table border=0 cellpadding=0 cellspacing=0 width=100% height="100%" style="table-layout:fixed">
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=5></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">

	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td><img src="images/minishop_info_title.gif"></td>
				</tr>
				<tr>
					<td height=5 background="images/minishop_titlebg.gif">
				</tr>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td colspan=3 >


						<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
							<tr>
								<td  bgcolor="#F5F5F9" style="padding:20px">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�̴ϼ� �⺻������ �����Ͻ� �� �ֽ��ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�̴ϼ� �⺻���� �Է½� �̴ϼ� ���������� ��ܿ� ����˴ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�̴ϼ� �ΰ� �̹����� 500K�̸� ���ѵ˴ϴ�.</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>



					</td>
				</tr>
				</table>
				</td>
			</tr>

			<!-- ó���� ���� ��ġ ���� -->
			<tr><td height=40></td></tr>
			<tr>
				<td >





				
				<table border=0 cellpadding=0 cellspacing=0 width=100%>

				<form name=iForm method=post action="<?=$_SERVER[PHP_SELF]?>" enctype=multipart/form-data>
				<input type=hidden name=preview_type value="info">
				<input type=hidden name=image_path>
				<input type=hidden name=mode>

				<tr>
					<td><img src="images/minishop_info_stitle01.gif" border=0 align=absmiddle alt="������ü �⺻����"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="notice_blue">(�̴ϼ� �⺻ ������ �����Ͻø� �̴ϼ� ��ܿ� ���õǴ� ������ ����˴ϴ�. ['*'ǥ�ô� �ʼ��Է�])</font></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				</table>
				<table border=0 cellpadding=7 cellspacing=1 bgcolor=#dddddd width=100% style="table-layout:fixed">
				<col width=140></col>
				<col width=></col>
				<tr>
					<td bgcolor=#f0f0f0 style="padding-left:10"><B><font color=red>*</font> �̴ϼ���</B></td>
					<td bgcolor=#ffffff style="padding-left:10">
					<input class=input type=text name=up_brand_name value="<?=$_venderdata->brand_name?>" size=20 maxlength=30 onKeyDown="chkFieldMaxLen(30)">
					<img src="images/btn_confirm10.gif" border=0 align=absmiddle style="cursor:hand;" onClick="confirmMinishopName(document.iForm.up_brand_name.value)">
					&nbsp;&nbsp;
					<font color=#2A97A7>* �ѱ� 15��, ���� 30�� �̳�, ���� ����</font>
					</td>
				</tr>
				<tr>
					<td bgcolor=#f0f0f0 style="padding-left:10"><B><font color=red>*</font> �̴ϼ� �ּ�</B></td>
					<td bgcolor=#ffffff style="padding-left:10">
					<B>http://<?=$minishopurl?></B>
					</td>
				</tr>
				<tr>
					<td bgcolor=#f0f0f0 style="padding-left:10"> <B>�̴ϼ� ����</B></td>
					<td bgcolor=#ffffff style="padding-left:10">
					<textarea name=up_description style="width:100%;height:80px"  class=input onKeyDown="chkFieldMaxLen(200)"><?=$_venderdata->brand_description?></textarea>
					</td>
				</tr>
				<tr>
					<td bgcolor=#f0f0f0 style="padding-left:10"> <B>�̴ϼ� �ΰ�</B></td>
					<td bgcolor=#ffffff style="padding-left:10">
					<input type=file name=upfile size=20 class=button>
					&nbsp;&nbsp;
					<font color=#2A97A7>* �̵�Ͻ� �Ʒ� �̹����� �ΰ�� ��ϵ˴ϴ�.</font>
					<BR><img width=0 height=3><BR>
					<font color=#2A97A7>500K �̸��� JPG, GIF�� ���� / ũ��:165 * 80</font>
					<br>
<?
					if(file_exists($Dir.DataDir."shopimages/vender/logo_".$_VenderInfo->getVidx().".gif")) {
						echo "<img src=\"".$Dir.DataDir."shopimages/vender/logo_".$_VenderInfo->getVidx().".gif\" border=0>\n";
					} else {
						echo "<img src=\"".$Dir."images/minishop/logo.gif\" border=0>\n";
					}
?>
					</td>
				</tr>
				</table>

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td align=center>
					<A HREF="javascript:formSubmit('preview')"><img src="images/btn_preview01.gif" border=0></A>
					&nbsp;
					<A HREF="javascript:formSubmit('')"><img src="images/btn_save01.gif" border=0></A>
					</td>
				</tr>

				</form>

				</table>

				<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

				</td>
			</tr>
			<!-- ó���� ���� ��ġ �� -->

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