<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$prcode=$_POST["prcode"];
if(strlen($prcode)!=18) {
	exit;
}

if(substr($_venderdata->grant_product,1,1)!="Y") {
	exit;
}

$sql = "SELECT productcode FROM tblproduct WHERE productcode='".$prcode."' AND vender='".$_VenderInfo->getVidx()."' ";
$result=mysql_query($sql,get_db_conn());
$rows=mysql_num_rows($result);
mysql_free_result($result);
if($rows<=0) {
	exit;
}
?>

<html>
<head>
<title></title>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>LH.add("parent_resizeIframe('PrdtImgIfrm')");</script>
<link rel=stylesheet href="style.css" type=text/css>
<script language='JavaScript'>
function formSubmit(mode) {
	checked=false;
	for(i=1;i<=10;i++) {
		gbn=i;
		if(gbn<10)gbn="0"+gbn;
		if(document.form2["mulimg"+gbn].value.length>0) {
			checked=true;
			break;
		}
	}

	if(mode!="delete" && checked==false){
		alert('����Ͻ� �̹����� �����ϼ���.');
		document.form2.mulimg01.focus();
		return;
	}
	if(mode!="delete" || confirm("�̹����� �����Ͻðڽ��ϱ�?")){
		document.form2.type.value=mode;
		document.form2.target="processFrame";
		document.form2.submit();
	}
}

function mulimgdel(no) {
	if(confirm("�ش� �̹����� �����Ͻðڽ��ϱ�?")){
		document.form2.type.value="delete";
		document.form2.mulimgno.value=no;
		document.form2.target="processFrame";
		document.form2.submit();
	}
}
</script>

</head>
<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>
<table width=100% border=0 cellspacing=0 cellpadding=0 bgcolor=FFFFFF>
<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=prcode value="<?=$prcode?>">
</form>
<?
$sql = "SELECT * FROM tblmultiimages ";
$sql.= "WHERE productcode = '".$prcode."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)){
	//$mulimg_name = array ("01"=>&$row->primg01,"02"=>&$row->primg02,"03"=>&$row->primg03,"04"=>&$row->primg04,"05"=>&$row->primg05,"06"=>&$row->primg06,"07"=>&$row->primg07,"08"=>&$row->primg08,"09"=>&$row->primg09,"10"=>&$row->primg10);
	$mulimg_name = array ();
	for( $i=1;$i<=MultiImgCnt;$i++ ){
		$k = str_pad($i,2,'0',STR_PAD_LEFT);
		$mulimg_name[$k] = &$row->{"primg".$k};
	}
	$type="update";
} else {
	$type="insert";
}
?>
<tr>
	<td>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<form name=form2 action="product_imgmultiset.process.php" method=post enctype="multipart/form-data">
<?
	$urlpath=$Dir.DataDir."shopimages/multi/";
	for($i=1;$i<=MultiImgCnt;$i+=2){
		$gbn1=substr("0".$i,-2);
		$gbn2=substr("0".($i+1),-2);
?>
	<tr bgcolor=#FFFFFF>
		<td class="lineleft" width=50% align=center style="padding:5">
		<input type=file name=mulimg<?=$gbn1?> style="width:100%" class=button>
		<input type=hidden name=oldimg<?=$gbn1?> value="<?=$mulimg_name[$gbn1]?>">
		</td>
		<td class="line" width=50% align=center style="padding:5">
		<input type=file name=mulimg<?=$gbn2?> style="width:100%" class=button>
		<input type=hidden name=oldimg<?=$gbn2?> value="<?=$mulimg_name[$gbn2]?>">
		</td>
	</tr>
	<?if(strlen($mulimg_name[$gbn1])>0 || strlen($mulimg_name[$gbn2])>0){?>
	<tr>
		<td class="lineleft" width=50% align=center style="padding:5;line-height:125%">
		<?if(strlen($mulimg_name[$gbn1])>0){?>
		<img src="<?=$urlpath."s".$mulimg_name[$gbn1]?>" border=1>
		<br>
		<A HREF="javascript:mulimgdel('<?=$gbn1?>');"><img src="images/btn_mulsdel.gif" border=0 align=absmiddle></A>
		<?}else{echo"&nbsp;";}?>
		</td>
		<td class="line" width=50% align=center style="padding:5;line-height:125%">
		<?if(strlen($mulimg_name[$gbn2])>0){?>
		<img src="<?=$urlpath."s".$mulimg_name[$gbn2]?>" border=1>
		<br>
		<A HREF="javascript:mulimgdel('<?=$gbn2?>');"><img src="images/btn_mulsdel.gif" border=0 align=absmiddle></A>
		<?}else{echo"&nbsp;";}?>
		</td>
	</tr>
	<?}?>
<?
	}
?>
	<tr><td height=20 colspan=2></td></tr>
	<tr>
		<td align=center colspan=2>
		<?if($type=="update"){?>
		<A HREF="javascript:formSubmit('<?=$type?>')"><img src=images/btn_save01.gif border=0></A>
		<?}else{?>
		<A HREF="javascript:formSubmit('<?=$type?>')"><img src=images/btn_regist01.gif border=0></A>
		<?}?>
		&nbsp;
		<A HREF="javascript:<?=($type=="insert"?"alert('��ϵ� �����̹����� �����ϴ�.');":"formSubmit('delete');")?>"><img src=images/btn_delete01.gif border=0></A>
		</td>
	</tr>

	<input type=hidden name=type>
	<input type=hidden name=mulimgno>
	<input type=hidden name=productcode value="<?=$prcode?>">
	</form>

	</table>
	</td>
</tr>
</table>
<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>
</body>
</html>