<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "ma-2";
$MenuCode = "market";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$quickmenu = array("U","001","002","003","004");

$type=$_POST["type"];
$checkadd=$_POST["checkadd"];
$x_to=$_POST["x_to"];
$y_to=$_POST["y_to"];
$scroll_auto=$_POST["scroll_auto"];
$prdt_cnt=$_POST["prdt_cnt"];
$design=$_POST["design"];
$content=$_POST["content"];

if($type=="insert") {
	if($checkadd=="Y") {
		$sql = "SELECT * FROM tbldesignnewpage WHERE type='r_banner' ";
		$result = mysql_query($sql,get_db_conn());
		$rows=mysql_num_rows($result);
		mysql_free_result($result);
		if($rows<=0) {
			if(strlen($x_to)==0) $x_to=0;
			if(strlen($y_to)==0) $y_to=0;
			if(strlen($scroll_auto)==0) $scroll_auto="Y";
			if(strlen($prdt_cnt)==0) $prdt_cnt=5;
			if($design!="U") $content="";

			$subject=$x_to."".$y_to;
			$filename=$design."AA";

			$sql = "INSERT tbldesignnewpage SET ";
			$sql.= "type		= 'r_banner', ";
			$sql.= "subject		= '".$subject."', ";
			$sql.= "filename	= '".$filename."', ";
			$sql.= "leftmenu	= '".$scroll_auto."', ";
			$sql.= "body		= '".$content."', ";
			$sql.= "code		= '".$prdt_cnt."' ";
			mysql_query($sql,get_db_conn());
			$onload="<script>alert(\"������ �Ϸ�Ǿ����ϴ�.\");</script>";
		}
	} else {
		$onload="<script>alert(\"������ �Ϸ�Ǿ����ϴ�.\");</script>";
	}
} else if($type=="modify") {
	if($checkadd=="Y") {
		if(strlen($x_to)==0) $x_to=0;
		if(strlen($y_to)==0) $y_to=0;
		if(strlen($scroll_auto)==0) $scroll_auto="Y";
		if(strlen($prdt_cnt)==0) $prdt_cnt=5;
		if($design!="U") $content="";

		$subject=$x_to."".$y_to;
		$filename=$design."AA";

		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "subject		= '".$subject."', ";
		$sql.= "filename	= '".$filename."', ";
		$sql.= "leftmenu	= '".$scroll_auto."', ";
		$sql.= "body		= '".$content."', ";
		$sql.= "code		= '".$prdt_cnt."' ";
		$sql.= "WHERE type='r_banner' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert(\"������ �Ϸ�Ǿ����ϴ�.\");</script>";
	} else {	//����
		$sql = "DELETE FROM tbldesignnewpage WHERE type='r_banner' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert(\"������ �Ϸ�Ǿ����ϴ�.\");</script>";
	}
}

$sql = "SELECT * FROM tbldesignnewpage WHERE type='r_banner' ";
$result = mysql_query($sql,get_db_conn());
if ($row=mysql_fetch_object($result)) {
	$tmp=explode("",$row->subject);
	$x_to=$tmp[0];	//������ġ
	$y_to=$tmp[1];	//������ġ

	unset($tmp);
	$tmp=explode("",$row->filename);
	$design=$tmp[0];	//������
	$prdt_type=$tmp[1];	//��ǰŸ��(AA:�̹���,AB:�̹���+��ǰ��)

	$scroll_auto=$row->leftmenu;	//��ũ�� Ÿ��
	$content=$row->body;			//���������� ����
	$prdt_cnt=$row->code;			//��ǰ���� (1~9)

	$checkadd="Y";
	$type="modify";
} else {
	$scroll_auto="Y";
	$prdt_cnt=5;
	$checkadd="N";
	$type="insert";
}
mysql_free_result($result);

?>

<? INCLUDE "header.php"; ?>

<!-- �����Ϳ� ���� ȣ�� -->
<script type="text/javascript" src="/gmeditor/js/jquery.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.event.drag-2.0.min.js"></script>
<script type="text/javascript" src="/gmeditor/js/jquery.resizable.js"></script>
<script type="text/javascript" src="/gmeditor/js/ajax_upload.3.6.js"></script>
<script type="text/javascript" src="/gmeditor/js/ej.h2xhtml.js"></script>
<script type="text/javascript" src="/gmeditor/editor.js"></script>
<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="/js/jquery.autocomplete.css" />
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	ejEditor();
});
</script>
<style type="text/css">
@import url("/gmeditor/common.css");
</style>
<!-- # �����Ϳ� ���� ȣ�� -->

<script type="text/javascript" src="lib.js.php"></script>
<script language="Javascript1.2" src="htmlarea/editor.js"></script>
<script language="JavaScript">
_editor_url = "htmlarea/";
var quickcnt = <?=count($quickmenu)?>;
function ChangeEditer(mode,obj){
	if (mode==form1.htmlmode.value) {
		return;
	} else {
		obj.checked=true;
		editor_setmode('content',mode);
	}
	form1.htmlmode.value=mode;
}

function CheckForm(type) {
	if(document.form1.x_to.value.length==0 || document.form1.y_to.value.length==0) {
		alert("�ֱ� �� ��ǰ�� ��ġ ������ �ϼ���.");
		document.form1.x_to.focus();
		return;
	}
	if(!IsNumeric(document.form1.x_to.value)) {
		alert("�ֱ� �� ��ǰ�� ��ġ �������� ���ڸ� �Է� �����մϴ�.");
		document.form1.x_to.focus();
		return;
	}
	if(!IsNumeric(document.form1.y_to.value)) {
		alert("�ֱ� �� ��ǰ�� ��ġ �������� ���ڸ� �Է� �����մϴ�.");
		document.form1.y_to.focus();
		return;
	}
	if(document.form1.scroll_auto[0].checked==false && document.form1.scroll_auto[1].checked==false) {
		alert("��ũ�� Ÿ���� �����ϼ���.");
		document.form1.scroll_auto[0].focus();
		return;
	}
	design=false;
	designval="";
	for(i=quickcnt;i<document.form1.design.length;i++) {
		if(document.form1.design[i].checked==true) {
			design=true;
			designval=document.form1.design[i].value;
			break;
		}
	}
	if(!design) {
		alert("�ֱ� �� ��ǰ�� ���ø��� �����ϼ���.");
		return;
	}

	if(designval=="U" && document.form1.content.value.length==0) {
		alert("�ֱ� �� ��ǰ�� ���������� �Է��ϼ���.");
		return;
	}
	if(type=="modify") {
		if(!confirm("�ֱ� �� ��ǰ�� ��ɼ����� �Ͻðڽ��ϱ�?")) {
			return;
		}
	}
	document.form1.type.value=type;
	document.form1.submit();
}

function ChangeDesign(tmp) {
	tmp0=tmp;
	tmp=tmp + quickcnt;
	document.form1["design"][tmp].checked=true;
	if(tmp0==0) {
		document.all["layer1"].style.display="block";
		document.all["layer2"].style.display="block";
	} else {
		document.all["layer1"].style.display="none";
		document.all["layer2"].style.display="none";
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
			<? include ("menu_market.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ���������� &gt; �̺�Ʈ/����ǰ ��� ���� &gt; <span class="2depth_select">�ֱ� �� ��ǰ ����</span></td>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=type value="<?=$type?>">
			<input type=hidden name=htmlmode value='wysiwyg'>
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_newproductview_title.gif" ALT=""></TD>
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
					<TD width="100%" class="notice_blue">������ ��ǰ�� ���θ� ������ ����������� ����ٴϸ鼭 �����ִ� ��� �Դϴ�.<BR>���θ��� ������ �����̴� Quick�޴� â�� ���Ͽ� �ֱ� �������� �ִ� 9���� ��ǰ�� ����˴ϴ�.</TD>
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
				<td><IMG SRC="images/market_quickmenu_stitle1.gif" border="0"></td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" width="100%">
								<col width="160"></col>
								<col width=""></col>
									<TR>
										<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>�ֱٺ� ��ǰ ��� ���� ����</TD>
										<TD class="td_con1"><INPUT id=idx_checkadd0 type=radio value=Y <?if($checkadd=="Y")echo"checked";?> name=checkadd><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_checkadd0>�����ϰڽ��ϴ�.</LABEL>  &nbsp;&nbsp;&nbsp;
					<INPUT id=idx_checkadd1 type=radio value=N <?if($checkadd=="N")echo"checked";?> name=checkadd><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_checkadd1>�������� �ʰڽ��ϴ�.</LABEL>
										</TD>
									</TR>
								</table>
							</td>
						</tr>
						<TR>
							<TD bgcolor="#B9B9B9" height="1"></TD>
						</TR>
					</table>


				</td>
			</tr>
			<tr><td height="20"></td></tr>







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
					<TD width="100%" class="notice_blue">Quick�޴� �������� <b>&quot;�ֱ� ������ ��ǰ�� ������&quot; </b> ���ýÿ��� �ֱ� �� ��ǰ�� ��µ��� �ʽ��ϴ�.<br>
					<a href="javascript:parent.topframe.GoMenu(7,'market_quickmenu.php');"><span class="font_blue">������ ���� > �̺�Ʈ/����ǰ ��� ���� > Quick�޴� ����</span></a></TD>
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
			<tr><td height="30"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/market_newproductview_stit2.gif" WIDTH="187" HEIGHT=31 ALT=""></TD>
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
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ֱ� �� ��ǰ ��ġ ����</TD>
					<TD class="td_con1">���ʿ��� <INPUT onkeyup="return strnumkeyup(this);" style="PADDING-LEFT: 5px" size=5 name=x_to value="<?=$x_to?>" class="input"> �ȼ� �̵� ��, ���ʿ��� <INPUT onkeyup="return strnumkeyup(this);" style="PADDING-LEFT: 5px" size=5 name=y_to value="<?=$y_to?>" class="input"> �ȼ� �Ʒ��� �̵��մϴ�.</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ũ�� ����</TD>
					<TD class="td_con1">
					<INPUT id=idx_scroll_auto1 type=radio value=Y <?if($scroll_auto=="Y")echo"checked";?> name=scroll_auto><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for=idx_scroll_auto1>ȭ�� ��ũ�ѿ� ���߾� �ڵ� ��ũ��</LABEL>  &nbsp;&nbsp;&nbsp;
					<INPUT id=idx_scroll_auto2 type=radio value=N <?if($scroll_auto=="N")echo"checked";?> name=scroll_auto><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_scroll_auto2>��ġ ����</LABEL>&nbsp;
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ ���� ����</TD>
					<TD class="td_con1">
					<SELECT name=prdt_cnt style=width:50px size="1" class="select">
					<option value=1 <?if($prdt_cnt==1)echo"selected";?>>1��</option>
					<option value=2 <?if($prdt_cnt==2)echo"selected";?>>2��</option>
					<option value=3 <?if($prdt_cnt==3)echo"selected";?>>3��</option>
					<option value=4 <?if($prdt_cnt==4)echo"selected";?>>4��</option>
					<option value=5 <?if($prdt_cnt==5)echo"selected";?>>5��</option>
					<option value=6 <?if($prdt_cnt==6)echo"selected";?>>6��</option>
					<option value=7 <?if($prdt_cnt==7)echo"selected";?>>7��</option>
					<option value=8 <?if($prdt_cnt==8)echo"selected";?>>8��</option>
					<option value=9 <?if($prdt_cnt==9)echo"selected";?>>9��</option>
					</SELECT>
					<span class="font_orange">������ Ŭ���� �ֱ� ��ǰǰ�� ������ ������ŭ �����ְ� �˴ϴ�.</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td bgcolor="#ededed" style="padding:4pt;">
						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
						<tr>
							<td>
							<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
							<TR>
								<TD width="100%" height="30" background="images/blueline_bg.gif"><p align="center"><b><font color="#555555">���ø� ����</font><span class="font_orange">(�������� 80px ����)</b></span></TD>
							</TR>
							<TR>
								<TD width="100%" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
							</TR>
							<TR>
								<TD width="100%" style="padding:0pt;">
								<TABLE cellSpacing=0 cellPadding="5" width="100%" border=0>
								<TR>
									<TD width="24" height="160" valign="middle"><p align="right"><img src="images/btn_back.gif" onMouseover='moveright()' onMouseout='clearTimeout(righttime)' style="cursor:hand;" width="31" height="31" border="0"></TD>
									<TD width="720" height="160">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tr height=170>
										<td id=temp style="visibility:hidden;position:absolute;top:0;left:0">
				<?
										echo "<script>";
										$jj=0;
										$menucontents = "";
										$menucontents .= "<table border=0 cellpadding=0 cellspacing=0><tr>";
										for($i=0;$i<count($quickmenu);$i++) {
											echo "thisSel = 'dotted #FFFFFF';";
											$menucontents .= "<td width='173'><p align='center'><input type=radio name='design' value='".$quickmenu[$i]."'";
											if($design==$quickmenu[$i]) $menucontents .= " checked";
											$menucontents .= " onclick='ChangeDesign(".$i.");'><img src='images/sample/newproductview".$quickmenu[$i].".gif' border=0 width=150 height=125 hspace='5' style='border-width:1pt; border-color:#FFFFFF; border-style:solid;' onMouseOver='changeMouseOver(this);' onMouseOut='changeMouseOut(this,thisSel);' style='cursor:hand;' onclick='ChangeDesign(".$i.");'></td>";
											$jj++;
										}
										$menucontents .= "</tr></table>";
										echo "</script>";
				?>

										<script language="JavaScript1.2">
										<!--
										function changeMouseOver(img) {
											 img.style.border='1 dotted #999999';
										}
										function changeMouseOut(img,dot) {
											 img.style.border="1 "+dot;
										}

										var menuwidth=650
										var menuheight=170
										var scrollspeed=10
										var menucontents="<nobr><?=$menucontents?></nobr>";

										var iedom=document.all||document.getElementById
										if (iedom)
											document.write(menucontents)
										var actualwidth=''
										var cross_scroll, ns_scroll
										var loadedyes=0
										function fillup(){
											if (iedom){
												cross_scroll=document.getElementById? document.getElementById("test2") : document.all.test2
												cross_scroll.innerHTML=menucontents
												actualwidth=document.all? cross_scroll.offsetWidth : document.getElementById("temp").offsetWidth
											}
											else if (document.layers){
												ns_scroll=document.ns_scrollmenu.document.ns_scrollmenu2
												ns_scroll.document.write(menucontents)
												ns_scroll.document.close()
												actualwidth=ns_scroll.document.width
											}
											loadedyes=1
										}
										window.onload=fillup

										function moveleft(){
											if (loadedyes){
												if (iedom&&parseInt(cross_scroll.style.left)>(menuwidth-actualwidth)){
													cross_scroll.style.left=parseInt(cross_scroll.style.left)-scrollspeed
												}
												else if (document.layers&&ns_scroll.left>(menuwidth-actualwidth))
													ns_scroll.left-=scrollspeed
											}
											lefttime=setTimeout("moveleft()",50)
										}

										function moveright(){
											if (loadedyes){
												if (iedom&&parseInt(cross_scroll.style.left)<0)
													cross_scroll.style.left=parseInt(cross_scroll.style.left)+scrollspeed
												else if (document.layers&&ns_scroll.left<0)
													ns_scroll.left+=scrollspeed
											}
											righttime=setTimeout("moveright()",50)
										}

										if (iedom||document.layers){
											with (document){
												write('<td valign=top>')
												if (iedom){
													write('<div style="position:relative;width:'+menuwidth+';">');
													write('<div style="position:absolute;width:'+menuwidth+';height:'+menuheight+';overflow:hidden;">');
													write('<div id="test2" style="position:absolute;left:0">');
													write('</div></div></div>');
												}
												else if (document.layers){
													write('<ilayer width='+menuwidth+' height='+menuheight+' name="ns_scrollmenu">')
													write('<layer name="ns_scrollmenu2" left=0 top=0></layer></ilayer>')
												}
												write('</td>')
											}
										}
										//-->
										</script>
										</td>
									</tr>
									</table>
									</TD>
									<TD width="27" height="160"><p align="left"><img src="images/btn_next.gif" onMouseover='moveleft()' onMouseout='clearTimeout(lefttime)' style="cursor:hand;" width="31" height="31" border="0"></TD>
								</TR>
								</TABLE>
								</TD>
							</TR>
							</TABLE>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD colspan="2">
					<div id=layer1 style="margin-left:0;display:hide; display:<?=($design=="U"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<!-- <tr>
						<td width="100%" height="35">
						<INPUT type=radio id=idx_chk_webedit1 name=chk_webedit CHECKED onclick="JavaScript:ChangeEditer('wysiwyg',this)"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_chk_webedit1>��������� �Է��ϱ�</LABEL>  &nbsp;&nbsp;&nbsp;
						<INPUT type=radio id=idx_chk_webedit2 name=chk_webedit onclick="JavaScript:ChangeEditer('textedit',this);"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_chk_webedit2>���� HTML�� �Է��ϱ�</LABEL> <BR>
						</td>
					</tr> -->
					<tr>
						<td width="100%"><TEXTAREA style="DISPLAY: yes; WIDTH: 100%" name=content rows="17" wrap=off class="textarea" lang="ej-editor1"><?=$content?></TEXTAREA></td>
					</tr>
					</table>
					</div>
					</TD>
				</tr>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
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
						<td><span class="font_dotline">�ֱ� �� ��ǰ ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ֱ� ���� �� ��ǰ ������ �ִ� 30��, ����� 9���� ��ǰ���� ����� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ֱ� ���� �� ��ǰ�� [ȭ�� ��ũ�ѿ� ���߾� �ڵ� ��ũ��] �Ǵ� [��ġ����] ������ �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �پ��� ���ø��� �⺻���� �����Ǹ� ���� ���������ε� �����մϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
						<div id=layer2 style="margin-left:0;display:hide; display:<?=($design=="U"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<tr>
							<td colspan="2" height="20"></td>
						</tr>
						<tr>
							<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
							<td width="701"><span class="font_orange"><b>�Է� ����� �Ʒ��� �����ϼ���.</b></span></td>
						</tr>
						</table>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<col width=150></col>
						<col width=></col>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[NEW]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֱ� �� ��ǰ ����Ʈ
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						<tr>
							<td class=table_cell align=right style="padding-right:15">[UP]/[DOWN]</td>
							<td class=td_con1 style="padding-left:5;">
							�ֱ� �� ��ǰ ��/�Ʒ� �̵�<br><FONT class=font_blue>��:&lt;a href=[UP]>UP&lt;/a>, &lt;a href=[DOWN]>DOWN&lt;/a></font>
							</td>
						</tr>
						<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
						</table>
						</div>
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
<script>
editor_generate('content');
</script>
<?=$onload?>
<? INCLUDE "copyright.php"; ?>