<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
if(strlen($_ShopInfo->getId())==0){
	echo "<script>window.close();</script>";
	exit;
}

if(strlen($vender) > 0 ) {
	$rootlength=strlen($Dir)-1;
	$popup=$_REQUEST["popup"];
	$originalpath = $Dir.DataDir."vender/".$vender."/";

}else{
	$rootlength=strlen($Dir)-1;
	$dir=$_REQUEST["dir"];
	$popup=$_REQUEST["popup"];
	$dir=ereg_replace("\.\.","",$dir);
	$originalpath = $Dir.DataDir."design/";
}

$rootlength=strlen($Dir)-1;


//$originalpath=ereg_replace("\.\.","",$originalpath);
$originallength=strlen($originalpath);

if(strlen($dir)==0)
	$path=$originalpath;
else {
	$dir=ereg_replace("\.\.","",$dir); // �������丮�� �̵� ����
	$dir=ereg_replace(" ","",$dir);  // ���� ����
	$path=$originalpath.$dir."/";
}
if(strlen($path)<strlen($originalpath)) $path=$originalpath;

$count=0;
getDirList(substr($originalpath,0,-1));
@sort($dirlist);
$temp=$dirlist;
$number = sizeof($temp);
for($i=0;$i<$number;$i++) {
	$tempdir=$temp[$i];
	if(strlen($tempdir)>$originallength && is_dir($tempdir)){
		$tempdirectory[$count++] = substr($tempdir,$originallength);
	}
}

$back=0;
for($i=$count-1;$i>=0;$i--) {
	$num = strpos($tempdirectory[$i+1],"/");
	if($num>0) $len=$num;
	else $len=strlen($tempdirectory[$i+1]);

	$num2 = strpos($tempdirectory[$i-1],"/");
	if($num2>0) $len2=$num2;
	else $len2=strlen($tempdirectory[$i-1]);

	$filenum[$i]=1; 
	$filefolder[$i]=count(explode("/",$tempdirectory[$i]));
	if($filefolder[$i]==1) { //1,2
		if($i==($count-1) || $back==2) {
			$filenum[$i]=1;
			$back=1;
		} else $filenum[$i]=1;
	} else if($filefolder[$i]==2) { //3,4,5,6
		if(strcmp(substr($tempdirectory[$i+1],0,$len),substr($tempdirectory[$i],0,$len))==0) {
			if($i==$count-1) $filenum[$i]=4;
			else if((strcmp(substr($tempdirectory[$i-1],0,$len2),substr($tempdirectory[$i],0,$len2))==0 && $filefolder[$i]==$filefolder[$i-1]) || (strcmp(substr($tempdirectory[$count-1],0,$len2),substr($tempdirectory[$i],0,$len2))==0)) { 
				$filenum[$i]=4;
			} else $filenum[$i]=4;
		} else if($filefolder[$i]!=$filefolder[$i-1]) $filenum[$i]=4;
	}
   if($back==0 && $back<$filefolder[$i]) $back=$filefolder[$i];

   if(strpos($tempdirectory[$i],"/")!=0) $directory[$i]=substr($tempdirectory[$i],strrpos($tempdirectory[$i],"/")+1);
   else $directory[$i]=$tempdirectory[$i];
}

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>���丮 ���</title>
<link rel="stylesheet" href="style.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(dir) {
	document.form1.dir.value=dir;
	document.form1.submit();
}
//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<form name=form1 method=post action="design_webftp<?if($popup=="ok")echo".popup";?>.php" target="_parent">
<tr>
	<td class="font_size1" style="padding:3">
	<img src="images/directory_root.gif"> <a href="javascript:document.form1.submit()"><?if($originalpath==$path)echo"<font color=#FF4C00>"; echo "/".RootPath.substr(substr($originalpath,$rootlength),1)."</font>";?></a>
	</td>
</tr>
<?
for($i=0;$i<$count;$i++) {
	echo "<tr>\n";
	echo "	<td class=\"font_size1\" style=\"padding:0,3,0,".(3+($filenum[$i]-1)*6)."\">\n";
	echo "	<img src=\"images/directory_folder".(strcmp($path,$originalpath.$tempdirectory[$i]."/")==0?"2":"1").".gif\" align=absmiddle> <a href=\"javascript:CheckForm('".$tempdirectory[$i]."')\">".(strcmp($path,$originalpath.$tempdirectory[$i]."/")==0?"<font color=#FF4C00>":"").$directory[$i]."</font></a>\n";
	echo "	</td>\n";
	echo "</tr>\n";
}
?>
<input type=hidden name=dir>
</form>
</table>
</body>
</html>