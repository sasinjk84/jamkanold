<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
//INCLUDE ("access.php");

if($_GET['vender']) $vender = $_GET['vender'];

$rootlength=strlen($Dir)-1;	### /design/ 전 까지의 string length
$max=11;		### 전체경로의 디렉토리 "/" 갯수
$dirmax=20;		### 생성 가능한 디렉토리 갯수

$type=$_POST["type"];
$dir=$_POST["dir"];

$path="";

if(strlen($vender) > 0 ) {
	$originalpath = $Dir.DataDir."vender/".$vender."/";
	if (!is_dir($originalpath)) {
		mkdir($originalpath, 0707, true);
		chmod($originalpath, 0707);
	}

}else{
	$originalpath = $Dir.DataDir."design/";
}
//$originalpath=ereg_replace("\.\.","",$originalpath);
$originallength=strlen($originalpath);

if(strlen($dir)==0)
	$path=$originalpath;
else {
	$dir=ereg_replace("\.\.","",$dir); // 상위디렉토리로 이동 금지
	$dir=ereg_replace(" ","",$dir);  // 공백 제거
	$path=$originalpath.$dir."/";
	$dir="";
}
if(strlen($path)<strlen($originalpath)) $path=$originalpath;

$subpath=substr($path,$rootlength);
$subpath3=substr($path,$rootlength,strlen($originalpath)-$rootlength);
$subpath2=substr($path,strlen($originalpath));

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>파일다운로드</title>
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="../lib/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="./uploadify/swfobject.js"></script>
<script type="text/javascript" src="./uploadify/jquery.uploadify.js"></script>
<script type="text/javascript">
<!--
$j(document).ready(function() {
	$j('#file_upload').uploadify({
		'uploader'  : '../admin/uploadify/uploadify.swf',
		'script'    : '../admin/uploadify/uploadify.php',
		'cancelImg' : '../admin/uploadify/cancel.png',
		'folder'    : '<?=$originalpath?>',
		'multi'     : true,
		'auto'      : true,
		'buttonText' : 'UPLOAD',
		'fileExt'   : '*.jpg;*.gif;*.png',
		'fileDesc'  : 'Image Files',
		'onComplete' : function(e,ID,fObj,res,data) {
			alert('저장되었습니다.');
			window.location.reload();
		}
	});
});

function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 65;

	window.resizeTo(oWidth,oHeight);
}

function htmledit() {
	if (document.form1.filelist.selectedIndex==-1) {
		alert("파일목록에서 HTM(htm)파일을 선택하세요.");
		return;
	}
	filename = document.form1.filelist.options[document.form1.filelist.selectedIndex].value;
	ext = filename.substring(filename.length-3,filename.length);
	ext = ext.toLowerCase();
	if (ext!="htm" && ext!="css") {
		alert("htm,css파일만 편집하실 수 있습니다.");
		return;
	}
	window.open("about:blank","webftpetcpop","height=10,width=10");
	document.form3.action="design_webftp.edit.php";
	document.form3.val.value=filename;
	document.form3.submit();
}

function imageview() {
	if(document.form1.filelist.selectedIndex==-1) {
		alert("파일목록에서 이미지 파일을 선택하세요.");
		return;
	}
	filename = document.form1.filelist.options[document.form1.filelist.selectedIndex].value;
	ext = filename.substring(filename.length-3,filename.length);
	ext = ext.toLowerCase();
	if (ext!="gif" && ext!="jpg") {
		alert("GIF와 JPG파일만 보실 수 있습니다.");return;
	}
	window.open("about:blank","webftpetcpop","height=10,width=10");
	document.form3.action="design_webftp.imgview.php";
	document.form3.val.value=filename;
	document.form3.submit();
}

function select_file(filepath) {
	if(filepath.length>0) {
		document.all["fileurlidx"].innerHTML="/<?=RootPath.substr($subpath3,1)?>"+filepath;
	}
}

//-->
</SCRIPT>
<style type="text/css">
.uploadifyButton {
	background-color: #505050;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	color: #FFF;
	font: 12px Arial, Helvetica, sans-serif;
	padding: 8px 0;
	text-align: center;
	width: 100%;
}
.uploadify:hover .uploadifyButton {
	background-color: #808080;
}
.uploadifyQueueItem {
	background-color: #F5F5F5;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	font: 11px Verdana, Geneva, sans-serif;
	margin-top: 5px;
	max-width: 350px;
	padding: 10px;
}
.uploadifyError {
	background-color: #FDE5DD !important;
}
.uploadifyQueueItem .cancel {
	float: right;
}
.uploadifyQueue .completed {
	background-color: #E5E5E5;
}
.uploadifyProgress {
	background-color: #E5E5E5;
	margin-top: 10px;
	width: 100%;
}
.uploadifyProgressBar {
	background-color: #0099FF;
	height: 3px;
	width: 1px;
}
</style>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 style="overflow-x:hidden;overflow-y:hidden;" onLoad="PageResize();">
<TABLE WIDTH="750" BORDER=0 CELLPADDING=0 CELLSPACING=0 id=table_body>
<tr>
	<td>		
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><img src="images/design_webftp_wintitle.gif" border="0"></td>
		<td background="images/member_mailallsend_imgbg.gif" width=100%></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td style="padding:20">

	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
	<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
	<col width=310></col>
	<col width=50></col>
	<col width=></col>
	<tr><td colspan=3 height=1 background="images/table_top_line.gif"></td></tr>
	<tr>
		<td class="table_cell" align="center"><FONT color=#3d3d3d><B>디렉토리</B></FONT></td>
		<td class="table_cell1" align="center">&nbsp;</td>
		<td class="table_cell1" align="center" background="images/blueline_bg.gif"><b><font color="333333">파일목록</font></b></td>
	</tr>
	<TR>
		<TD colspan="3" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
	</TR>
	<tr>
		<td align=center valign=top style="padding:5">
		<!-- 디렉토리 목록 시작 -->
		<table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr>
			<td>
			<table cellpadding="8" cellspacing="0" width="100%" bgcolor="#EBEBEB">
			<tr>
				<td align=center>
				<IFRAME style="WIDTH:100%;HEIGHT:262px" src="design_webftp.directory.php?dir=<?=substr($subpath2,0,-1)?>&popup=ok&vender=<?=$vender?>" scrolling=yes size="6" marginwidth=5 marginheight=5></IFRAME>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr><td height=5></td></tr>
		<tr>
			<td><hr size="1" color="#EBEBEB"></td>
		</tr>
		<tr>
			<td>* <FONT color=#0054a6>현재 경로 : /<?=RootPath.substr($subpath,1)?></FONT></td>
		</tr>
		<tr>
			<td><hr size="1" color="#EBEBEB"></td>
		</tr>
		</table>
		<!-- 디렉토리 목록 끝 -->
		</td>

		<!-- -->
		<TD class="td_con1" align="center" valign=top style="padding-top:130"><img src="images/icon_nero.gif" border="0"></TD>

		<td class="td_con1" align=center valign=top style="padding:5">
		<!-- 파일목록 시작 -->
		<table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#ededed">
			<tr>
				<td style="padding:8,8,0,8">
				<SELECT style="width:100%;" name=filelist size=16 multiple onchange="select_file(options.value)" class="font_size1">
<?
				$temp=getFileList(substr($path,0,-1));
				@sort($temp);
				for ($i=0;$i<sizeof($temp);$i++) {
					$filename=ereg_replace("\*","",$temp[$i]);
					echo "<option value=\"$subpath2$filename\">$filename\n";
					$tok = strtok("\n");
				}
				if ($i==0) echo "<option value=\"\">등록된 파일이 없습니다.";
?>
				</SELECT>
				</td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="6" align=center>
				<tr>
					<td align=center><a href="javascript:htmledit()"><IMG SRC="images/design_webftp_icon1.gif" border="0"></a></td>
					<td align=center><a href="javascript:imageview();"><IMG SRC="images/design_webftp_icon2.gif" border="0"></a></td>
				</tr>
				<tr>
					<td colspan=2><input id="file_upload" type="file" name="file_upload" /></td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td style="padding-top:5">
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<col width=90></col>
			<col width=></col>
			<tr>
				<td>&nbsp;* 이미지경로 : </td>
				<td id=fileurlidx>선택안됨</td>
			</tr>
			</table>
			</td>
		</tr>
		
		</table>
		<!-- 파일목록 끝 -->
		</td>
	</tr>
	</form>
	<tr><td colspan=3 height=1 background="images/table_top_line.gif"></td></tr>
	</table>

	</td>
</tr>
<TR>
	<TD align=center>
		<a href="javascript:window.close()"><img src="images/btn_close.gif" border="0" border=0></a>	
	</TD>
</TR>
<tr><td height=10></td></tr>
</TABLE>

<form name=form3 method=post target="webftpetcpop">
<input type=hidden name=val>
<input type=hidden name=vender value="<?=$vender?>">
</form>

</body>
</html>