<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "de-1";
$MenuCode = "design";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$rootlength=strlen($Dir)-1;	### design/ �� ������ string length
$max=7;		### ��ü����� ���丮 "/" ����
$dirmax=20;		### ���� ������ ���丮 ����

$type=$_POST["type"];
$dir=$_POST["dir"];
$delfile=$_POST["delfile"];
$selectdir=$_POST["selectdir"];
$newdir=$_POST["newdir"];

$path="";

$upfile1=$_FILES[upfile1];
$upfile2=$_FILES[upfile2];
$upfile3=$_FILES[upfile3];
$upfile4=$_FILES[upfile4];
$upfile5=$_FILES[upfile5];
$upfile6=$_FILES[upfile6];
$upfile7=$_FILES[upfile7];
$upfile8=$_FILES[upfile8];
$upfile9=$_FILES[upfile9];
$upfile10=$_FILES[upfile10];

$originalpath = $Dir.DataDir."design/";
//$originalpath=ereg_replace("\.\.","",$originalpath);

$originallength=strlen($originalpath);

$total=0;
getDirList(substr($originalpath,0,-1));
@sort($dirlist);
$temp=$dirlist;
$number = sizeof($temp);
for($i=0;$i<$number;$i++) {
	$tempdir=$temp[$i];
	if(strlen($tempdir)>$originallength && is_dir($tempdir)) {
		$total++;
	}
}

if(strlen($dir)==0)
	$path=$originalpath;
else {
	$dir=ereg_replace("\.\.","",$dir); // �������丮�� �̵� ����
	$dir=ereg_replace(" ","",$dir);  // ���� ����
	$path=$originalpath.$dir."/";
	$dir="";
}
if(strlen($path)<strlen($originalpath)) $path=$originalpath;

$countslash = explode("/", $path);

if($type=="ins") $max=$max-1;
if(count($countslash)>$max || ($type=="ins" && $total>=$dirmax)) {
	if($type=="ins" && $total>=$dirmax) {
		echo "<html><head><title></title></head><body onload=\"alert('���丮�� �� ".$dirmax."���� ��ϵ˴ϴ�. ���̻� ����� �Ұ����մϴ�.');\"></body></html>";
	} else {
		echo "<html><head><title></title></head><body onload=\"alert('���̻� ���� ���丮 ����̵��� �ʽ��ϴ�.');\"></body></html>";
	}
	$type="";
	$temppath=substr($path,0,-1);
	$path = substr($temppath,0,strrpos($temppath,"/"))."/";
}
if($type=="del") {
	$temppath=substr($path,0,-1);
	proc_rmdir($temppath);
	$path = substr($temppath,0,strrpos($temppath,"/"))."/";
} else if($type=="mv") {
	$temppath=substr($path,0,-1);
	$path2 = substr($temppath,0,strrpos($temppath,"/"))."/";
	$path2 = $path2.$selectdir."/";

	if(is_dir($path2)) {
		echo "<html><head><title></title></head><body onload=\"alert('���� �̸��� ���丮�� �ֽ��ϴ�.');\"></body></html>";
	} else {
		rename($path,$path2);
		$path=$path2;
		$dir=substr($path,strlen($originalpath));
		$dir=substr($dir,0,-1);
	}
}

if(!is_dir($path) && $total<$dirmax) {
	mkdir($path);
	chmod($path, 0755);
} else if($type=="ins" && $total<$dirmax) {
	echo "<html><head><title></title></head><body onload=\"alert('��ϵ� ���丮�Դϴ�.');\"></body></html>";
} else if(!is_dir($path)) {
	echo "<html><head><title></title></head><body onload=\"alert('���丮�� �� ".$dirmax."���� ��ϵ˴ϴ�. ���̻� ����� �Ұ����մϴ�.');history.go(-1);\"></body></html>";
	exit;
}

if ($type=="filedelete" && $delfile) {
	$delfile=substr($delfile,1);
	$ardelfile = explode("|",$delfile);
	$arnum = count($ardelfile);
	for($i=0;$i<$arnum;$i++)
		if(file_exists($originalpath.$ardelfile[$i])) unlink($originalpath.$ardelfile[$i]);
}

$dir2=substr($path,0,-1);
$dir2=substr($dir2,strrpos($dir2,"/")+1);

if($type=="fileupload") {
	$filearray = array (&$upfile1,&$upfile2,&$upfile3,&$upfile4,&$upfile5,&$upfile6,&$upfile7,&$upfile8,&$upfile9,&$upfile10);
	$filesize=(int)$filearray[0]["size"]+(int)$filearray[1]["size"]+(int)$filearray[2]["size"]+(int)$filearray[3]["size"]+(int)$filearray[4]["size"]+(int)$filearray[5]["size"]+(int)$filearray[6]["size"]+(int)$filearray[7]["size"]+(int)$filearray[8]["size"]+(int)$filearray[9]["size"];
	if($filesize>2097152) {
		echo "<html><head><title></title></head><body onload=\"alert('���Ͽ뷮�� 300KByte�� �ʰ��Ǿ����ϴ�.');\"></body></html>";
	} else {
		$cnt= count($filearray);
		for($i=0;$i<$cnt;$i++){
			if (strlen($filearray[$i]["name"])>0 && file_exists($filearray[$i]["tmp_name"])==true) {
				$filearray[$i]["name"]=ereg_replace(" ","",$filearray[$i]["name"]);
				$ext = substr($filearray[$i]["name"],-4);
				if($ext =="html") {
					$filearray[$i]["name"] = substr($filearray[$i]["name"],0,(strlen($filearray[$i]["name"]))-1);
				}
				else if (substr($ext,-3)=="php") $filearray[$i]["name"]=$filearray[$i]["name"]."s";

				if($filearray[$i]["size"] > 2097152) {
					echo "<html><head><title></title></head><body onload=\"alert('���Ͽ뷮�� 2MByte�� �ʰ��Ǿ����ϴ�.');\"></body></html>";
				} else {
					if(move_uploaded_file($filearray[$i]["tmp_name"],$path.$filearray[$i]["name"])) {
						chmod($path.$filearray[$i]["name"],0604);
					}
				}
			}
		}
	}
}

$subpath=substr($path,$rootlength);
$subpath3=substr($path,$rootlength,strlen($originalpath)-$rootlength);
$subpath2=substr($path,strlen($originalpath));
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function webftp_popup() {
	window.open("design_webftp.popup.php","webftppopup","height=10,width=10");
}
function htmledit() {
	if (document.form1.filelist.selectedIndex==-1) {
		alert("���ϸ�Ͽ��� HTM(htm)������ �����ϼ���.");
		return;
	}
	filename = document.form1.filelist.options[document.form1.filelist.selectedIndex].value;
	ext = filename.substring(filename.length-3,filename.length);
	ext = ext.toLowerCase();
	if (ext!="htm" && ext!="css") {
		alert("htm,css���ϸ� �����Ͻ� �� �ֽ��ϴ�.");
		return;
	}
	window.open("about:blank","webftpetcpop","height=10,width=10");
	document.form3.action="design_webftp.edit.php";
	document.form3.val.value=filename;
	document.form3.submit();
}

function imageview() {
	if(document.form1.filelist.selectedIndex==-1) {
		alert("���ϸ�Ͽ��� �̹��� ������ �����ϼ���.");
		return;
	}
	filename = document.form1.filelist.options[document.form1.filelist.selectedIndex].value;
	ext = filename.substring(filename.length-3,filename.length);
	ext = ext.toLowerCase();
	if (ext!="gif" && ext!="jpg") {
		alert("GIF�� JPG���ϸ� ���� �� �ֽ��ϴ�.");return;
	}
	window.open("about:blank","webftpetcpop","height=10,width=10,scrollbars=1");
	document.form3.action="design_webftp.imgview.php";
	document.form3.val.value=filename;
	document.form3.submit();
}

function delete_file() {
	val="";
	if(document.form1.filelist.selectedIndex==-1) {
		alert("������ ������ �����ϼ���.");
		return;
	}
	for(i=0;i<document.form1.filelist.options.length;i++) {
		if(document.form1.filelist.options[i].selected==true) {
			if(document.form1.filelist.options[i].value.length>0) {
				val+="|"+document.form1.filelist.options[i].value;
			}
		}
	}
	if(val.length==0) {
		alert("�����Ͻ� ������ ��ϵ� ������ �����ϴ�.");
		return;
	}
	if(confirm("���õ� ���� �����Ͻðڽ��ϱ�?")) {
		document.form2.type.value="filedelete";
		document.form2.delfile.value=val;
		document.form2.submit();
	}
}

function upfile_plus() {
	for(i=4;i<=10;i++) {
		if(document.all) {
			if(document.all["hideupfile"+i].style.display=="none") {
				document.all["hideupfile"+i].style.display="block";
				break;
			}
		} else if(document.getElementById) {
			if(document.getElementById("hideupfile"+i).style.display=="none") {
				document.getElementById("hideupfile"+i).style.display="block";
				break;
			}
		}
	}
}

function select_file(filepath) {
	if(filepath.length>0) {
		document.all["fileurlidx"].innerHTML="/<?=RootPath.substr($subpath3,1)?>"+filepath;
	}
}

function dir_delete() {
	if(document.form1.dir.value.length==0) {
		alert('�⺻ ������ �����ϽǼ� �����ϴ�.');
		return;
	}
	if(!confirm("������ ���丮�� �����Ͻðڽ��ϱ�?")) return;
	if(document.form1.count.value>0) {
		if(!confirm("������ ���丮�� �����̳� ���丮�� �����մϴ�.\n��� �����Ͻðڽ��ϱ�?")) return;
	}
	document.form1.type.value="del";
	document.form1.submit();
}

function dir_modify() {
	if(document.form1.dir.value.length==0) {
		alert('�⺻ ������ ������ �Ұ����մϴ�.');
		return;
	}
	temp=document.form1.selectdir.value;
	count=0;
	for(i=0;i<temp.length;i++) {
		temp2=temp.substr(i,1);
		if((temp2>="0" && temp2<="9") || (temp2>="a" && temp2<="z") || (temp2>="A" && temp2<="Z") || temp2=="_" || temp2 =="-") {
			count++;
		} else {
			alert('���丮�� �ü� ���� �̸��Դϴ�. �ٽ� �Է��ϼ���');
			document.form1.selectdir.focus();
			return;
		}
	}
	if (count>0) {
		if (!confirm("������ ���丮���� �����Ͻðڽ��ϱ�?")) return;
		document.form1.type.value="mv";
		document.form1.submit();
	}
}

function dir_new() {
<?
$countslash2 = explode("/", $subpath);
if(count($countslash2)>$max) {
?>
	alert('���� ������ ����ϽǼ��� �����ϴ�.');
	return;
<?}else{?>
	temp=document.form1.newdir.value;
	count=0;
	for(i=0;i<temp.length;i++) {
		temp2=temp.substr(i,1);
		if((temp2>="0" && temp2<="9") || (temp2>="a" && temp2<="z") || (temp2>="A" && temp2<="Z") || temp2=="_" || temp2=="-") {
			count++;
		} else{
			alert('���丮�� �ü� ���� �̸��Դϴ�. �ٽ� �Է��ϼ���');
			document.form1.newdir.focus();
			return;
		}
	}
	if (count>0) {
		if(!confirm("���丮�� ����Ͻðڽ��ϰ�?")) return;
	}
	if(document.form1.dir.value.length==0) document.form1.dir.value=temp;
	else document.form1.dir.value=document.form1.dir.value+"/"+temp;
	document.form1.type.value="ins";
	document.form1.submit();
<?}?>
}

function upload_file() {
	if(confirm("������ ���ε� �Ͻðڽ��ϱ�?")) {
		document.form1.type.value="fileupload";
		document.form1.submit();
	}
}

function download_file(path){
	window.open("about:blank","webftpetcpop","height=10,width=10");
	document.form3.action="design_webftp.down.php";
	document.form3.val.value=path;
	document.form3.submit();
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
			<? include ("menu_design.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" width="16" height="12" border="0" valign=absmiddle>������ġ : �����ΰ��� &gt; ��FTP, ������ �ɼ� ����  &gt; <span class="2depth_select">��FTP/��FTP�˾�</span></td>
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
			<tr>
				<td height="8"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/design_webftp_title.gif"		 ALT=""></TD>
					</TR>
					<TR>
					<TD width="100%" background="images/title_bg.gif" HEIGHT="21"></TD>
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
					<TD background="images/distribute_04.gif"><IMG SRC="images/distribute_04.gif" ></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">���θ��� ���� ���ϵ��� ���󿡼� ���� �����Ͻ� �� �ֽ��ϴ�.</TD>
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
			<tr>
				<td height="10"></td>
			</tr>


			<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>" enctype="multipart/form-data">
			<input type=hidden name=type>
			<input type=hidden name=dir value="<?=substr($subpath2,0,-1)?>">
			<input type=hidden name=count value="<?=$count2?>">
			<input type=hidden name=filesize>
			<tr>
				<td align=right style="padding:0,2,5,0">
				<A HREF="javascript:webftp_popup()"><IMG src="images/webftp_button.gif" align=absmiddle border=0></A>
				</td>
			</tr>
			<tr>
				<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<col width=310></col>
				<col width=50></col>
				<col width=></col>
				<tr><td colspan=3 height=1 background="images/table_top_line.gif"></td></tr>
				<tr>
					<td class="table_cell" align="center"><FONT color=#3d3d3d><B>���丮</B></FONT></td>
					<td class="table_cell1" align="center">&nbsp;</td>
					<td class="table_cell1" align="center" background="images/blueline_bg.gif"><b><font color="#555555">���ϸ��</font></b></td>
				</tr>
				<TR>
					<TD colspan="3" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
				</TR>
				<tr>
					<td align=center valign=top style="padding:4">
					<!-- ���丮 ��� ���� -->
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td>
						<table cellpadding="8" cellspacing="0" width="100%" bgcolor="#EBEBEB">
						<tr>
							<td align=center>
							<IFRAME style="WIDTH:100%;HEIGHT:262px" src="design_webftp.directory.php?dir=<?=substr($subpath2,0,-1)?>" scrolling=yes size="6" marginwidth=5 marginheight=5></IFRAME>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height=10></td></tr>
					<tr>
						<td>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=65></col>
						<col width=></col>
						<col width=38></col>
						<col width=38></col>
						<tr>
							<td>
								<FONT color=#3d3d3d><img src="images/design_webftp_text01.gif" border="0"></FONT>
							</td>
							<td>
								<FONT color=#3d3d3d><INPUT style="width:99%" 
								size=32 name=selectdir class="input" 
								<?
								if($originalpath!=$path) {
									echo "value=\"$dir2\"";
								} else {
									echo "disabled style=\"background='silver'\"";
								}
								?>
								></FONT>
							</td>
							<td>
								<FONT color=#3d3d3d><A href="javascript:dir_modify()"><IMG 
								src="images/icon_edit2.gif" align=absmiddle border=0></A> </FONT>
							</td>
							<td>
								<FONT color=#3d3d3d><A 
								href="javascript:dir_delete()"><IMG src="images/icon_del1.gif" align=absmiddle 
								border=0></A></FONT>
							</td>
						</tr>
						<tr>
							<td>
								<FONT color=#3d3d3d><img src="images/design_webftp_text02.gif" border="0"></FONT>
							</td>
							<td>
								<FONT color=#3d3d3d><INPUT size=32 name=newdir class="input" style=width:98%></FONT>
							</td>
							<td colspan=2>
								<FONT color=#3d3d3d><A 
								href="javascript:dir_new()"><IMG src="images/icon_newfolder.gif" align=absmiddle 
								border=0></A></FONT>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr><td height=1></td></tr>
					<tr>
						<td><hr size="1" color="#EBEBEB"></td>
					</tr>
					<tr>
						<td>* <FONT color=#0054a6>���� ��� : /<?=RootPath.substr($subpath,1)?></FONT></td>
					</tr>
					<tr>
						<td><hr size="1" color="#EBEBEB"></td>
					</tr>
					</table>
					<!-- ���丮 ��� �� -->
					</td>

					<!-- -->
					<TD class="td_con1" align="center" valign=top style="padding-top:130"><img src="images/icon_nero.gif" border="0"></TD>

					<td class="td_con1" align=center valign=top style="padding:5">
					<!-- ���ϸ�� ���� -->
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#EDEDED">
						<tr>
							<td style="padding:8,8,0,8">
							<SELECT style="width:100%;" name=filelist size=16 multiple onchange="select_file(options.value)" class="font_size1">
<?
							unset($temp);
							$temp=getFileList(substr($path,0,-1));
							sort($temp);
							for ($i=0;$i<sizeof($temp);$i++) {
								$filename=ereg_replace("\*","",$temp[$i]);
								echo "<option value=\"$subpath2$filename\">$filename\n";
								$tok = strtok("\n");
							}
							if ($i==0) echo "<option value=\"\">��ϵ� ������ �����ϴ�.";
?>
							</SELECT>
							</td>
						</tr>
						<tr>
							<td align=center>
							<table cellpadding="0" cellspacing="0">

							<tr>
								<td align=center>
									<a href="javascript:htmledit()"><IMG SRC="images/design_webftp_icon1.gif" border="0"></a>
								</td>
								<td align=center>
									<a href="javascript:imageview();"><IMG SRC="images/design_webftp_icon2.gif" border="0"></a>
								</td>
								<td align=center>
									<a href="javascript:delete_file()"><IMG SRC="images/design_webftp_icon3.gif" border="0"></a>
								</td>
							</tr>
							</table>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td height="30">
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=90></col>
						<col width=></col>
						<tr>
							<td>&nbsp;* �̹������ : </td>
							<td id=fileurlidx>���þȵ�</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td style="padding-top:2pt; padding-bottom:2pt;">
						<table cellpadding="1" cellspacing="0" align="center" width="150">
						<tr>
							<td><A HREF="javascript:upload_file()"><img src="images/btn_upload.gif" border="0"></A></td>
							<td><a HREF="javascript:download_file('<?=substr($subpath2,0,-1)?>')"><img src="images/btn_download.gif" border="0"></td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td align=center>

<table cellpadding="20" cellspacing="4" width="100%" bgcolor="#EDEDED">
    <tr>
        <td  bgcolor="white"height="60">



							<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td><a href="javascript:upfile_plus()"><img src="images/design_webftp_btnfileup.gif" width="242" height="22" border="0"></a></td>
							</tr>
							<tr><td height=3></td></tr>
							<tr>
								<td>
								<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
								<tr>
									<TD>
										<INPUT class="input" type=file name=upfile1 style=width:100%>
									</td>
								</tr>
								<tr>
									<TD>
										<INPUT class="input" type=file name=upfile2 style=width:100%>
									</td>
								</tr>
								<tr>
									<TD>
										<INPUT class="input" type=file name=upfile3 style=width:100%>
									</td>
								</tr>
<?
								for($i=4;$i<=10;$i++) {
									echo "<tr id=\"hideupfile".$i."\" style=\"display:none\">\n";
									echo "	<td>\n";
									echo "		<INPUT class=\"input\" type=file name=upfile".$i." style=width:100%>\n";
									echo "	</td>\n";
									echo "</tr>\n";
								}
?>
								<tr>
									<td><img src="images/design_webftp_btntext.gif" width="281" height="14" border="0" vspace="2"></td>
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
					</table>
					<!-- ���ϸ�� �� -->
					</td>
				</tr>
				<tr><td colspan=3 height=1 background="images/table_top_line.gif"></td></tr>
				</table>
				</td>
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
					<TD background="images/manual_left1.gif"><IMG SRC="images/manual_left1.gif" WIDTH=15 HEIGHT="5" ALT=""></TD>
					<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td width="100%"><span class="font_dotline">��FTP/��FTP�˾�</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- ���� ����, �̹��� ���� ���� �����ϰ� ���󿡼� ���ε� �� ������ �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td width="100%" class="space_top" style="letter-spacing:-0.5pt;">- html, css ������ ��FTP�� ������ �����մϴ�.</td>
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
			<tr><td height=40></td></tr>
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

<form name=form2 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=type>
<input type=hidden name=dir value="<?=substr($subpath2,0,-1)?>">
<input type=hidden name=delfile>
</form>

<form name=form3 method=post target="webftpetcpop">
<input type=hidden name=val>
</form>

</table>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>