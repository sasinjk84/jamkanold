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
$change=$_POST["change"];
$mainsort=$_POST["mainsort"];
$shopwidth=(int)$_POST["shopwidth"];
$shopleftmenuwidth=(int)$_POST["shopleftmenuwidth"];
$shopleftmenuwidth = ( $shopleftmenuwidth > 0 ) ? $shopleftmenuwidth : 180;
$maintype=$_POST["maintype"];
$mainused=$_POST["mainused"];
$shopbgtype=$_POST["shopbgtype"];
$shopbgtypemain=$_POST["shopbgtypemain"];
$bgcolor=$_POST["bgcolor"];
$bgclear=$_POST["bgclear"];

$bgimage = $_FILES['bgimage']['tmp_name'];
$bgimage_type = $_FILES['bgimage']['type'];
$bgimage_name = $_FILES['bgimage']['name'];
$bgimage_size = $_FILES['bgimage']['size'];

$bgimagefreeze=$_POST["bgimagefreeze"];
$bgimagelocat=$_POST["bgimagelocat"];

$bgimagerepet=$_POST["bgimagerepet"];
$mousekeyright=$_POST["mousekeyright"];
$mousekeydrag=$_POST["mousekeydrag"];
$mousekeyover=$_POST["mousekeyover"];
$mousekeyboard=$_POST["mousekeyboard"];

$imagepath = $Dir.DataDir."shopimages/etc/";
$image_name="background.gif";

if ($type=="up") {
	if($shopbgtype == "I")
	{
		if (strlen($bgimage_name)>0 && strtolower(substr($bgimage_name,strlen($bgimage_name)-3,3))=="gif" && $bgimage_size<=153600) {
			move_uploaded_file($bgimage,"$imagepath$image_name");
			chmod("$imagepath$image_name",0664);
		} else {
			if (strlen($bgimage_name)>0) $msg="�ø��� �̹����� 150KB ������ gif���ϸ� �˴ϴ�.";
		}
	}
	else
		@unlink("$imagepath$image_name");

	$layoutdata_str="";
	if ($shopwidth>0){
		$layoutdata_str[] = "SHOPWIDTH=".$shopwidth;
	}

	if ($shopleftmenuwidth>0){
		$layoutdata_str[] = "SHOPLEFTMENUWIDTH=".$shopleftmenuwidth;
	}

	$layoutdata_str[]= "MAINTYPE=".$maintype;
	$layoutdata_str[]= "MAINUSED=".@implode("", $mainused);
	$layoutdata_str[]= "MAINSORT=".$mainsort;
	$layoutdata_str[]= "MOUSEKEY=".$mousekeyright.$mousekeydrag.$mousekeyover.$mousekeyboard;
	$layoutdata_str[]= "SHOPBGTYPE=".$shopbgtype.$shopbgtypemain;

	if($shopbgtype == "B")
		$layoutdata_str[]= "BGCOLOR=".$bgclear."#".$bgcolor;
	if($shopbgtype == "I")
		$layoutdata_str[]= "BACKGROUND=".$bgimagefreeze.$bgimagelocat.$bgimagerepet;

	$sql = "UPDATE tblshopinfo SET ";
	$sql.= "layoutdata		= '".implode("", $layoutdata_str)."' ";
	$result = mysql_query($sql,get_db_conn());
	DeleteCache("tblshopinfo.cache");
	$onload="<script> alert('���� ������ �Ϸ�Ǿ����ϴ�. $msg'); </script>";
}

$sql = "SELECT layoutdata FROM tblshopinfo";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
mysql_free_result($result);

if(strlen($row->layoutdata)<1)
	$row->layoutdata="SHOPWIDTH=SHOPLEFTMENUWIDTH=180MAINTYPE=BMAINUSED=INBHSMAINSORT=INBHSGAMOUSEKEY=NNNNSHOPBGTYPE=NNBGCOLOR=N#FFFFFFBACKGROUND=YAA";

unset($layoutdata);
if(strlen($row->layoutdata)>0) {
	$laytemp=explode("",$row->layoutdata);
	$laycnt=count($laytemp);
	for ($layi=0;$layi<$laycnt;$layi++) {
		$laytemp2=explode("=",$laytemp[$layi]);
		if(isset($laytemp2[1])) {
			$layoutdata[$laytemp2[0]]=$laytemp2[1];
		} else {
			$layoutdata[$laytemp2[0]]="";
		}
	}
}

if(strlen($layoutdata["MAINTYPE"])==0)
	$layoutdata["MAINTYPE"] = "B";
if(strlen($layoutdata["MAINUSED"])==0)
	$layoutdata["MAINUSED"] = "INBHS";
if(strlen($layoutdata["MAINSORT"])==0)
	$layoutdata["MAINSORT"] = "INBHSGA";
if(strlen($layoutdata["MOUSEKEY"])==0)
	$layoutdata["MOUSEKEY"] = "NNNN";
if(strlen($layoutdata["SHOPBGTYPE"])==0)
	$layoutdata["SHOPBGTYPE"] = "NN";
if(strlen($layoutdata["BGCOLOR"])==0)
	$layoutdata["BGCOLOR"] = "N#FFFFFF";
if(strlen($layoutdata["BACKGROUND"])==0)
	$layoutdata["BACKGROUND"] = "NAA";

$mainsort="";
$bgcolor="";

$maintype_checked[$layoutdata["MAINTYPE"]] = "checked";

for($i=1; $i<strlen($layoutdata["MAINUSED"]); $i++)
{
	$mainused_checked[substr($layoutdata["MAINUSED"],$i,1)] = "checked";
}

for($i=1; $i<strlen($layoutdata["MAINSORT"]); $i++)
{
	$mainsort[] = substr($layoutdata["MAINSORT"],$i,1);
}


for($i=0; $i<strlen($layoutdata["MOUSEKEY"]); $i++)
{
	$mousekey_checked[][substr($layoutdata["MOUSEKEY"],$i,1)] = "checked";
}
$shopbgtype = substr($layoutdata["SHOPBGTYPE"],0,1);
$shopbgtype_checked[substr($layoutdata["SHOPBGTYPE"],0,1)] = "checked";
$shopbgtypemain_checked[substr($layoutdata["SHOPBGTYPE"],1,1)] = "checked";

if(strlen($layoutdata["BGCOLOR"]) == 0)
	$layoutdata["BGCOLOR"] = "N#FFFFFF";
if(strlen($layoutdata["BACKGROUND"]) == 0)
	$layoutdata["BACKGROUND"] = "YAA";

$bgclear_checked[@substr($layoutdata["BGCOLOR"],0,1)] = "checked";
$bgcolor = @substr($layoutdata["BGCOLOR"],2);

$bgimagefreeze_checked[@substr($layoutdata["BACKGROUND"],0,1)] = "checked";
$bgimagelocat_seleced[@substr($layoutdata["BACKGROUND"],1,1)] = "selected";
$bgimagerepet_checked[@substr($layoutdata["BACKGROUND"],2,1)] = "checked";
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>
function selcolor(obj){
	if(!document.form1.bgcolor.disabled)
	{
		fontcolor = obj.value.substring(1);
		var newcolor = showModalDialog("color.php?color="+fontcolor, "oldcolor", "resizable: no; help: no; status: no; scroll: no;");
		if(newcolor){
			obj.value=newcolor;
		}
	}
}

function shopbgtype_change(thisForm,thisValue)
{
	if(document.getElementById("idx_bgcolor"))
		bgcolor_obj = document.getElementById("idx_bgcolor");
	if(document.getElementById("idx_bgimage"))
		bgimage_obj = document.getElementById("idx_bgimage");

	if(thisValue == "N")
	{
		thisForm.bgcolor.disabled=true;
		thisForm.bgclear[0].disabled=true;
		thisForm.bgclear[1].disabled=true;
		thisForm.bgimage.disabled=true;
		thisForm.bgimagefreeze[0].disabled=true;
		thisForm.bgimagefreeze[1].disabled=true;
		thisForm.bgimagelocat.disabled=true;
		thisForm.bgimagerepet[0].disabled=true;
		thisForm.bgimagerepet[1].disabled=true;
		thisForm.bgimagerepet[2].disabled=true;
		thisForm.bgimagerepet[3].disabled=true;
		bgcolor_obj.style.backgroundColor="#EAE9E4";
		bgimage_obj.style.backgroundColor="#EAE9E4";
	}
	else
	{
		if(thisValue == "B")
		{
			thisForm.bgcolor.disabled=false;
			thisForm.bgclear[0].disabled=false;
			thisForm.bgclear[1].disabled=false;
			thisForm.bgimage.disabled=true;
			thisForm.bgimagefreeze[0].disabled=true;
			thisForm.bgimagefreeze[1].disabled=true;
			thisForm.bgimagelocat.disabled=true;
			thisForm.bgimagerepet[0].disabled=true;
			thisForm.bgimagerepet[1].disabled=true;
			thisForm.bgimagerepet[2].disabled=true;
			thisForm.bgimagerepet[3].disabled=true;
			bgcolor_obj.style.backgroundColor="#FE8E4B";
			bgimage_obj.style.backgroundColor="#EAE9E4";
		}
		else
		{
			thisForm.bgcolor.disabled=true;
			thisForm.bgclear[0].disabled=true;
			thisForm.bgclear[1].disabled=true;
			thisForm.bgimage.disabled=false;
			thisForm.bgimagefreeze[0].disabled=false;
			thisForm.bgimagefreeze[1].disabled=false;
			thisForm.bgimagelocat.disabled=false;
			thisForm.bgimagerepet[0].disabled=false;
			thisForm.bgimagerepet[1].disabled=false;
			thisForm.bgimagerepet[2].disabled=false;
			thisForm.bgimagerepet[3].disabled=false;
			bgcolor_obj.style.backgroundColor="#EAE9E4";
			bgimage_obj.style.backgroundColor="#FE8E4B";
		}
	}
}

document.onkeydown = CheckKeyPress;
var all_list = new Array();
var selnum="";
var ProductInfoStop="";

function CheckKeyPress(updownValue) {
	prevobj=null;
	selobj=null;

	if(updownValue)
		ekey = updownValue;
	else
		ekey = event.keyCode;

	if(selnum!="" && (ekey==38 || ekey==40 || ekey=="up" || ekey=="down")) {
		var j=all_list.length;
		var h=0;
		for(i=0;i<all_list.length;i++) {
			j--;
			if(ekey==38 || ekey == "up") {			//���� �̵�
				h=i;
				kk=h;
				kk--;
			} else {	//�Ʒ��� �̵�
				h=j;
				kk=h;
				kk++;
			}

			if(selnum==all_list[h].num) {
				if(prevobj!=null) {
					selobj=all_list[h];

					t1=prevobj.sort;
					prevobj.sort=selobj.sort;
					selobj.sort=t1;

					o1=prevobj.no;
					prevobj.no=selobj.no;
					selobj.no=o1;

					all_list[h]=prevobj;
					all_list[kk]=selobj;

					takeChange(prevobj);
					takeChange(selobj);

					all_list[kk].selected=false;
					selnum="";
					document.form1.change.value="Y";
					ChangeList(all_list[kk].num);
				}
				break;
			} else {
				prevobj=all_list[h];
			}
		}
	}
}

function takeChange(argObj)
{
	var innerHtmlStr = "";
	document.all["idx_inner_"+argObj.sort].innerHTML=argObj.mainused_html+argObj.mainused_check+argObj.mainused_html2;
}

function move_save()
{
	val="";
	for(i=0;i<all_list.length;i++)
	{
		val+=all_list[i].mainused;
	}
	document.form1.mainsort.value="I"+val;
}

function updown_click(num,updownValue)
{
	if(selnum != num)
		ChangeList(num);

	CheckKeyPress(updownValue);
}

function ChangeList(num) {
	if(ProductInfoStop)
		ProductInfoStop = "";
	else
	{
		for(i=0;i<all_list.length;i++) {
			if(all_list[i].num==num) {
				if(all_list[i].selected==true) {
					selnum="";
					all_list[i].selected=false;
					document.all["idx_inner_"+all_list[i].sort].style.backgroundColor="#FFFFFF";
				} else {
					selnum=num;
					all_list[i].selected=true;
					document.all["idx_inner_"+all_list[i].sort].style.backgroundColor="#efefef";
				}
			} else {
				all_list[i].selected=false;
				document.all["idx_inner_"+all_list[i].sort].style.backgroundColor="#FFFFFF";
			}
		}
	}
}

function ObjList() {
	var argv = ObjList.arguments;
	var argc = ObjList.arguments.length;

	//Property ����
	this.classname		= "ObjList";
	this.debug			= false;
	this.num			= new String((argc > 0) ? argv[0] : "0");
	this.mainused		= new String((argc > 1) ? argv[1] : "");
	this.mainused_html	= new String((argc > 2) ? argv[2] : "");
	this.mainused_html2	= new String((argc > 3) ? argv[2] : "");
	this.mainused_check	= new String((argc > 4) ? argv[4] : "");
	this.no				= new String((argc > 5) ? argv[5] : "");
	this.sort			= new String((argc > 6) ? argv[6] : "");
	this.selected		= new Boolean((argc > 7) ? argv[7] : false );
}

function checkChange(checkedValue,num)
{
	ProductInfoStop = "1";

	for(i=0;i<all_list.length;i++) {
		if(all_list[i].num==num) {
			if(checkedValue)
				all_list[i].mainused_check = "checked";
			else
				all_list[i].mainused_check = "";
		}
	}
}

function CheckForm(){
	if (confirm("���θ� ���̾ƿ� ������ ������Ʈ �ϰڽ��ϱ�?")) {
		move_save();
		form1.type.value="up";
		form1.submit();
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ���θ� ȯ�� ���� &gt; <span class="2depth_select">���θ� ���̾ƿ� ����</span></td>
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





			<!-- ���� ���� -->

			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_layout_title.gif" border="0"></TD>
				</TR>
				<TR>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
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
					<TD width="100%" class="notice_blue">���θ� ��ü������ ���� �� ������� ����� ������ �� �ֽ��ϴ�.</TD>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
			<input type=hidden name="type">
			<input type=hidden name="change">
			<input type=hidden name="mainsort">
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_layout_stitle1.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" border="0"></TD>
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
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">���θ� ��ü/�����޴� ����(Width) ����� �����Ͻ� �� �ֽ��ϴ�.</TD>
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
				<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
				<TR>
					<TD bgcolor="#B9B9B9" height="1"></TD>
				</TR>
				<TR>
					<TD>
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="table_cell"><img src="images/shop_layout_img1.gif" border="0"></td>
						<td class="td_con1" width="100%" style="padding:10px;">
							<span class="font_orange"><b>���θ� ��ü ����(Width) ������ : </b></span><input type=text name="shopwidth" size="6" maxlength="6" value="<?=$layoutdata["SHOPWIDTH"]?>" class="input"> �ȼ�(Pixel)<br />
							<span style="color:#444444;"><b>���θ� ���� ����(Width) ������ : </b></span><input type=text name="shopleftmenuwidth" size="6" maxlength="6" value="<?=$layoutdata["SHOPLEFTMENUWIDTH"]?>" class="input"> �ȼ�(Pixel)<br /><br />
							<span class="space_top">* ���Է½� ���ø� ��ü ����� ���� ��� �˴ϴ�.<br />
							* ���θ� ��ü ����(Width) �ּ� ������� <b>900�ȼ�</b>������ �����մϴ�.<br />
							* ���θ� ���� ����(Width) �⺻ ������� <b>180�ȼ�</b>�Դϴ�.<br />
							* ���θ� ��ü �� ���� ����(Width) ������� ���ڸ� �Է� �����մϴ�.</span>
						</td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD bgcolor="#B9B9B9" height="1"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_layout_stitle2.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" border="0"></TD>
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
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) ���θ� ���� �߾� ���̾ƿ� A,B,C Ÿ���� �����Ͽ� ����� �� �ֽ��ϴ�.<br>
					2) ���θ� ���� ������ �� �޴���� ���� �� ��ġ�� ������ �� �ֽ��ϴ�.</TD>
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
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#EDEDED" style="padding:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<TR>
						<TD align="center" height="30" background="images/blueline_bg.gif"><b><font color="#555555">���θ� ����</span></b></TD>
					</TR>
					<TR>
						<TD bgcolor="#eeeeee" height="1"></TD>
					</TR>
					<tr>
						<td width="70%" bgcolor="#FFFFFF">
						<TABLE cellSpacing="0" cellPadding="0" width="100%" border="0">
						<col width=""></col>
						<col width="180"></col>
						<TR>
							<TD align="center" height="30" background="images/blueline_bg.gif"><b><font color="#555555">�߾� ���̾ƿ� ����</span></b></TD>
							<TD align="center" height="30" background="images/blueline_bg.gif" style="border-left:#EDEDED 1px solid;"><b><font color="#555555">���� �޴� �� ��ġ ����</span></b></TD>
						</TR>
						<TR>
							<TD colspan="2" bgcolor="#eeeeee" height="1"></TD>
						</TR>
						<TR>
							<TD style="padding:20pt;padding-left:10pt;padding-right:10pt;">
							<table cellpadding="0" cellspacing="0" width="100%">
							<tr align="center">
								<!--<td><IMG src="images/shop_layout_img2.gif" border="0" class="imgline"></td>
								<td><IMG src="images/shop_layout_img3.gif" border="0" class="imgline"></td>-->
								<td><IMG src="images/shop_layout_img4.gif" border="0" class="imgline"></td>
							</tr>
							<tr>
								<td height="5" colspan="3"></td>
							</tr>
							<tr align="center">
								<!--<td><INPUT type=radio name="maintype" value="A" id="idx_maintype1" <?=$maintype_checked["A"]?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_maintype1"><b>A Ÿ��</b></label></td>
								<td><INPUT type=radio name="maintype" value="B" id="idx_maintype2" <?=$maintype_checked["B"]?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_maintype2"><b>B Ÿ��</b></label></td>-->
								<td><INPUT type=radio name="maintype" value="C" id="idx_maintype3" <?=$maintype_checked["C"]?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_maintype3"><b>A Ÿ��</b></label></td>
							</tr>
							</table>
							</TD>
							<TD style="padding:10pt;" style="border-left:#EDEDED 1px solid;" valign="top" height="100%">
							<table cellpadding="0" cellspacing="0" width="100%" height="100%">
							<tr>
								<td align="center" valign="top" height="100%">
								<table cellpadding="0" cellspacing="0" width="100%" height="100%">
								<input type="hidden" name="mainused[]" value="I">
								<tr>
									<td height="30" background="images/blueline_bg.gif" align="center" style="border:#EDEDED 1px solid;"><b>���μҰ���</b></td>
								</tr>
								<TR>
									<TD height="5"></TD>
								</TR>
								<tr>
									<td height="100%">
									<table cellpadding="0" cellspacing="0" width="100%" height="100%" style="border:#EDEDED 1px solid;border-top:none;">
<?
							
							if(@count($mainsort)==0)
								$mainsort = array("N", "B", "H", "S","G", "A");
							$mainsort_count = count($mainsort);

							$mainsort_name = array("N"=>"�Ż�ǰ ���", "B"=>"��õ��ǰ ���", "H"=>"�α��ǰ ���", "S"=>"Ư����ǰ ���","G"=>"����ǥ�� ���", "A"=>"���ǥ�� ���");

							$j=1;
							$strlist="<script>\n";
							$jj=$mainsort_count;

							for($ii=0; $ii<$mainsort_count; $ii++)
							{
								$strlist.= "var objlist=new ObjList();\n";
								$strlist.= "objlist.num=\"".$j."\";\n";
								$strlist.= "objlist.mainused=\"".$mainsort[$ii]."\";\n";
								$strlist.= "objlist.mainused_html=\"<table cellpadding=\\\"0\\\" cellspacing=\\\"0\\\" width=\\\"100%\\\" onclick=\\\"ChangeList('".$j."');\\\"><tr><TD style=\\\"padding-left:20px;\\\"><a href=\\\"javascript:updown_click('".$j."','up')\\\"><img src=\\\"images/btn_plus.gif\\\" border=\\\"0\\\" style=\\\"margin-bottom:3px;\\\"></a><br><a href=\\\"javascript:updown_click('".$j."','down')\\\"><img src=\\\"images/btn_minus.gif\\\" border=\\\"0\\\" style=\\\"margin-top:3px;\\\"></a></td><td width=\\\"100%\\\" style=\\\"padding-left:10px;\\\"><INPUT type=checkbox name=\\\"mainused[]\\\" value=\\\"".$mainsort[$ii]."\\\" id=\\\"idx_mainused".$ii."\\\" onclick=\\\"checkChange(this.checked,'".$j."');\\\"\";\n";
								$strlist.= "objlist.mainused_html2=\"><label style=\\\"cursor:hand;\\\" onmouseover=\\\"style.textDecoration='underline'\\\" onmouseout=\\\"style.textDecoration='none'\\\" for=\\\"idx_mainused".$ii."\\\" onclick=\\\"checkChange(this.checked,'".$j."');\\\"><b>".$mainsort_name[$mainsort[$ii]]."</b></label></td></tr></table>\";\n";
								$strlist.= "objlist.mainused_check=\"".$mainused_checked[$mainsort[$ii]]."\";\n";
								$strlist.= "objlist.no=\"".$jj--."\";\n";
								$strlist.= "objlist.sort=\"".$ii."\";\n";
								$strlist.= "objlist.selected=false;\n";
								$strlist.= "all_list[".$ii."]=objlist;\n";
								$strlist.= "objlist=null;\n";
?>
									<tr>
										<td height="1" bgcolor="#EDEDED"></td>
									</tr>
									<tr>
										<td id="idx_inner_<?=$ii?>" onmouseover="if(this.style.backgroundColor != '#efefef')this.style.backgroundColor='#F4F7FC';" onmouseout="if(this.style.backgroundColor != '#efefef')this.style.backgroundColor='#FFFFFF';" style="background-Color:'#FFFFFF';cursor:hand;">
										<table cellpadding="0" cellspacing="0" width="100%" onclick="ChangeList('<?=$j?>');">
										<tr>
											<td style="padding-left:20px;"><a href="javascript:updown_click('<?=$j?>','up')"><img src="images/btn_plus.gif" border="0" style="margin-bottom:3px;"></a><br><a href="javascript:updown_click('<?=$j?>','down')"><img src="images/btn_minus.gif" border="0" style="margin-top:3px;"></a></td>
											<td width="100%" style="padding-left:10px;"><INPUT type=checkbox name="mainused[]" value="<?=$mainsort[$ii]?>" id="idx_mainused<?=$ii?>" <?=$mainused_checked[$mainsort[$ii]]?> onclick="checkChange(this.checked,'<?=$j?>');"><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_mainused<?=$ii?>" onclick="checkChange(this.checked,'<?=$j?>');"><b><?=$mainsort_name[$mainsort[$ii]]?></b></label></td>
										</tr>
										</table>
										</td>
									</tr>
<?
								$j++;
							}

							$strlist.="</script>\n";
							echo $strlist;
?>
									</table>
									</td>
								</tr>
								</table>
								</td>
							</tr>
							</table>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td><IMG SRC="images/shop_layout_stitle3.gif" border="0"></td>
			</tr>
			<tr>
				<td height="6"></td>
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
						<col width="150"></col>
						<col width=""></col>
						<TR>
							<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���θ� ��� ����</TD>
							<TD class="td_con1"><input type=radio name="shopbgtype" value="N" id="idx_shopbgtype1" onclick="shopbgtype_change(this.form,this.value);" <?=$shopbgtype_checked["N"]?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_shopbgtype1">��� ��� ����</label><input type=radio name="shopbgtype" value="B" id="idx_shopbgtype2" onclick="shopbgtype_change(this.form,this.value);" <?=$shopbgtype_checked["B"]?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_shopbgtype2">��� �������� ����</label><input type=radio name="shopbgtype" value="I" id="idx_shopbgtype3" onclick="shopbgtype_change(this.form,this.value);" <?=$shopbgtype_checked["I"]?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_shopbgtype3">��� �̹����� ����</label>
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
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td><IMG SRC="images/shop_layout_stitle4.gif" border="0"></td>
			</tr>
			<tr>
				<td height="6"></td>
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
						<col width="150"></col>
						<col width=""></col>
						<TR>
							<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���� �߾� ��� ����</TD>
							<TD class="td_con1"><input type=radio name="shopbgtypemain" value="N" id="idx_shopbgtypemain1" <?=$shopbgtypemain_checked["N"]?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_shopbgtypemain1">���� �߾ӿ��� �������(������� �ڵ� ����, <font color="#000000">����</font>)</label><input type=radio name="shopbgtypemain" value="Y" id="idx_shopbgtypemain2" <?=$shopbgtypemain_checked["Y"]?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_shopbgtypemain2">���� �߾ӿ��� ������</label>
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
			<tr>
				<td height="40"></td>
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
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">1) ���θ� ����/�ܺ� ������ ���� �Ǵ� �̹����� ������ �� �ֽ��ϴ�.<br>
					2) ���� ������ �����ڵ��ȣ�� �Է��Ͽ� ����� �� ������, ����ǥ�� �̿�� �ڵ��ȣ�� Ȯ���� �� �ֽ��ϴ�.<br>
					3) ��� �̹����� ����Ȯ���� gif �� �����ϸ� �뷮�� �ִ� 150KByte ������ �����մϴ�.<br>
					4) �������� Ÿ������ �̿�� ��ܿ��� ���� �� ����̹��� ������� �ʽ��ϴ�.<br>
					5) ����� ������ ��ġ�� �����Ͻ÷��� ���� ������ üũ�� �����ϼž� �մϴ�. üũ�Ͻ� ��� ����� ��ũ���� ���� �����Դϴ�.<br>
					6) ���� ���̾ƿ� �߾ӿ� ���� ��� ����/������ ���ýÿ� ���ʸ޴��� ���ؼ��� ���� ����/������ �� �� �ֽ��ϴ�.</TD>
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
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td id="idx_bgcolor" style="padding:4pt;background-Color:<?=($shopbgtype=="B"?"#FE8E4B":"#EAE9E4")?>;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD height="30" background="images/blueline_bg.gif" align="center"><b><font color="#555555">��� �������� ����</span></b></TD>
						</TR>
						<TR>
							<TD bgcolor="#EDEDED"></TD>
						</TR>
						<tr>
							<td>
							<table cellpadding="0" cellspacing="0" width="100%">
							<col width="150"></col>
							<col width=""></col>
							<TR>
								<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���� ����</TD>
								<TD class="td_con1" valign="bottom">
								<table cellpadding="0" cellspacing="0">
								<tr>
									<td style="padding-left:5px;">#</td>
									<td style="padding-left:3px;"><input type=text name="bgcolor" value="<?=$bgcolor?>" size="8" maxlength="6" class="input" <?=($shopbgtype=="N" || $shopbgtype=="I"?"disabled":"")?>></td>
									<td style="padding-left:5px;"><font color="<?=$bgcolor?>"><span style="font-size:20pt;">��</span></font></td>
									<td style="padding-left:5px;"><a href="javascript:selcolor(document.form1.bgcolor)"><IMG src="images/icon_color.gif" border="0" align="absmiddle"></a></td>
								</tr>
								</table>
								</td>
							</TR>
							<TR>
								<TD colspan="2" bgcolor="#EDEDED"></TD>
							</TR>
							<TR>
								<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>����� ��뿩��</TD>
								<TD class="td_con1" valign="bottom"><input type=radio name="bgclear" value="N" id="idx_bgclear1" <?=$bgclear_checked["N"]?> <?=($shopbgtype=="N" || $shopbgtype=="I"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgclear1">����� ������</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<input type=radio name="bgclear" value="Y" id="idx_bgclear2" <?=$bgclear_checked["Y"]?> <?=($shopbgtype=="N" || $shopbgtype=="I"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgclear2">����� �����</label></td>
							</TR>
							</table>
							</td>
						</tr>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="5"></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td id="idx_bgimage" style="padding:4pt;background-Color:<?=($shopbgtype=="I"?"#FE8E4B":"#EAE9E4")?>;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="#FFFFFF">
					<tr>
						<td>
						<table cellpadding="0" cellspacing="0" width="100%">
						<TR>
							<TD height="30" background="images/blueline_bg.gif" align="center"><b><font color="#555555">��� �̹����� ����</span></b></TD>
						</TR>
						<TR>
							<TD bgcolor="#EDEDED"></TD>
						</TR>
						<tr>
							<td>
							<table cellpadding="0" cellspacing="0" width="100%">
							<col width="150"></col>
							<col width=""></col>
							<TR>
								<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>��� �̹���</TD>
								<TD class="td_con1" style="padding-left:8px;"><input type=file name="bgimage" style="WIDTH: 98%" class="input" <?=($shopbgtype=="N" || $shopbgtype=="B"?"disabled":"")?>><br>
								* ��� ������ �̹����� ���� Ȯ���� <span class="font_orange">GIF(gif)</span> �� �����ϸ� �뷮�� <span class="font_orange">�ִ� 150KB</span> ���� �����մϴ�.
								<?
									if(file_exists($imagepath.$image_name))
									{
?>
										<table cellpadding="0" cellspacing="0" width="98%" border="0" style="table-layout:fixed">
										<tr>
											<td height="5"></td>
										</tr>
										<tr>
											<td height="100" style="border:#EDEDED 1px solid;"><img src="<?=$imagepath.$image_name?>" border="0"></td>
										</tr>
										<tr>
											<td height="5"></td>
										</tr>
										</table>
<?
									}
								?>
								</TD>
							</TR>
							<TR>
								<TD colspan="2" bgcolor="#EDEDED"></TD>
							</TR>
							<TR>
								<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>��� ���� ����</TD>
								<TD class="td_con1"><input type=radio name="bgimagefreeze" value="Y" id="idx_bgimagefreeze1" <?=$bgimagefreeze_checked["Y"]?> <?=($shopbgtype=="N" || $shopbgtype=="B"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgimagefreeze1">��� ���� ����(��� �̹��� ����ٴմϴ�.)</label>
								<input type=radio name="bgimagefreeze" value="N" id="idx_bgimagefreeze2" <?=$bgimagefreeze_checked["N"]?> <?=($shopbgtype=="N" || $shopbgtype=="B"?"disabled":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgimagefreeze2">��� ������(��� �̹��� ����)</label>
								</TD>
							</TR>
							<TR>
								<TD colspan="2" bgcolor="#EDEDED"></TD>
							</TR>
							<TR>
								<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>��� ��� ���� ��ġ</TD>
								<TD class="td_con1" style="padding-left:8px;"><select name="bgimagelocat" class="select" <?=($shopbgtype=="N" || $shopbgtype=="B"?"disabled":"")?>>
									<option value="A" <?=$bgimagelocat_seleced["A"]?>>���� - ���� </option>
									<option value="B" <?=$bgimagelocat_seleced["B"]?>>���� - �߾�</option>
									<option value="C" <?=$bgimagelocat_seleced["C"]?>>���� - ����</option>
									<option value="D" <?=$bgimagelocat_seleced["D"]?>>��� - ����</option>
									<option value="E" <?=$bgimagelocat_seleced["E"]?>>��� - �߾�</option>
									<option value="F" <?=$bgimagelocat_seleced["F"]?>>��� - ����</option>
									<option value="G" <?=$bgimagelocat_seleced["G"]?>>�ǾƷ� - ����</option>
									<option value="H" <?=$bgimagelocat_seleced["H"]?>>�ǾƷ� - �߾�</option>
									<option value="I" <?=$bgimagelocat_seleced["I"]?>>�ǾƷ� - ����</option>
									</select></TD>
							</TR>
							<TR>
								<TD colspan="2" bgcolor="#EDEDED"></TD>
							</TR>
							<TR>
								<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>��� �ݺ� ����</TD>
								<TD class="td_con1"><input type=radio name="bgimagerepet" value="A" id="idx_bgimagerepet1" <?=$bgimagerepet_checked["A"]?> <?=($shopbgtype=="N" || $shopbgtype=="B"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgimagerepet1">��ü�ݺ�</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<input type=radio name="bgimagerepet" value="B" id="idx_bgimagerepet2" <?=$bgimagerepet_checked["B"]?> <?=($shopbgtype=="N" || $shopbgtype=="B"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgimagerepet2">����ݺ�</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<input type=radio name="bgimagerepet" value="C" id="idx_bgimagerepet3" <?=$bgimagerepet_checked["C"]?> <?=($shopbgtype=="N" || $shopbgtype=="B"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgimagerepet3">�����ݺ�</label>&nbsp;&nbsp;&nbsp;&nbsp;
								<input type=radio name="bgimagerepet" value="D" id="idx_bgimagerepet4" <?=$bgimagerepet_checked["D"]?> <?=($shopbgtype=="N" || $shopbgtype=="B"?"disabled":"")?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_bgimagerepet4">�ݺ�����</label>
								</TD>
							</TR>
							</table>
							</td>
						</tr>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/shop_layout_stitle5.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" border="0"></TD>
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
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">�������� Ÿ������ ���ý� ��ܿ��� ������� ����� �������� �ʽ��ϴ�.</TD>
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
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<TR>
					<TD bgcolor="#B9B9B9" height="1"></TD>
				</TR>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width="200"></col>
					<col width=""></col>
					<TR>
						<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���콺 ������ ��ư ��뿩��</TD>
						<TD class="td_con1"><input type=radio name="mousekeyright" value="N" id="idx_mousekeyright1" <?=$mousekey_checked[0]["N"]?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_mousekeyright1">��� ������</label>
						<input type=radio name="mousekeyright" value="Y" id="idx_mousekeyright2" <?=$mousekey_checked[0]["Y"]?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_mousekeyright2">��� �Ұ���</label>
						</TD>
					</TR>
					<TR>
						<TD colspan="2" bgcolor="#EDEDED"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���콺 �巡�� ��뿩��</TD>
						<TD class="td_con1"><input type=radio name="mousekeydrag" value="N" id="idx_mousekeydrag1" <?=$mousekey_checked[1]["N"]?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_mousekeydrag1">��� ������</label>
						<input type=radio name="mousekeydrag" value="Y" id="idx_mousekeydrag2" <?=$mousekey_checked[1]["Y"]?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_mousekeydrag2">��� �Ұ���</label>
						</TD>
					</TR>
					<TR>
						<TD colspan="2" bgcolor="#EDEDED"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>���콺 �̹��� �� ����â ��¿���</TD>
						<TD class="td_con1"><input type=radio name="mousekeyover" value="N" id="idx_mousekeyover1" <?=$mousekey_checked[2]["N"]?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_mousekeyover1">��� ������</label>
						<input type=radio name="mousekeyover" value="Y" id="idx_mousekeyover2" <?=$mousekey_checked[2]["Y"]?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_mousekeyover2">��� �Ұ���</label>
						</TD>
					</TR>
					<TR>
						<TD colspan="2" bgcolor="#EDEDED"></TD>
					</TR>
					<TR>
						<TD class="table_cell"><b><img src="images/icon_point2.gif" border="0"></b>Ű���� ��뿩��(Ctrl, ���Ű)</TD>
						<TD class="td_con1"><input type=radio name="mousekeyboard" value="N" id="idx_mousekeyboard1" <?=$mousekey_checked[3]["N"]?>><label style="cursor:hand;" onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_mousekeyboard1">��� ������</label>
						<input type=radio name="mousekeyboard" value="Y" id="idx_mousekeyboard2" <?=$mousekey_checked[3]["Y"]?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for="idx_mousekeyboard2">��� �Ұ���</label>
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
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td align="center"><a href="javascript:CheckForm('up');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			</form>
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
						<td ><span class="font_dotline">���θ��� ���̾ƿ� �� ��Ÿ��� ����</span></td>
					</tr>
					<tr>
						<td width="20" align="right">&nbsp;</td>
						<td  class="space_top">
						- ���θ� ��ü ���λ���� ������ �� ������, ���Է½� ���ø��� ����� ������� ��µ˴ϴ�.<br>
						- ���� �޴� ��� �� ��ġ������ ��� �̿��Ͽ� ������ �� �ֽ��ϴ�. �� üũ���� ���� �޴��� ������ ��µ��� �ʽ��ϴ�.<br>
						- ���θ� ��� ������ �����ڵ� �� �̹��� �����Ͽ� ����� �� �ֽ��ϴ�.<br>
						<b>&nbsp;&nbsp;</b>�̹��� �뷮�� �ִ� 150KByte���� �����ϸ�, Ȯ���ڴ� gif�� �����մϴ�.<br>
						- ���� ��������� ��ǰ���� �Ǵ� �ҽ����縦 ���ܽ� �̿��� �� �ֽ��ϴ�.<br>
						<b>&nbsp;&nbsp;</b>��, �����Ӽ������� �������� Ÿ�� ���� ��ܿ��� ������� ����� �۵����� �ʽ��ϴ�.<br>
						- �������ӿ��� ������ �� �� ������������ ����� ��� �����¿� ������ ��Ȯ�� ��ġ���� ���� �� �ֽ��ϴ�.<br>
						- �¿������� �����ϸ� ���� �����ο� ��ȭ�� ���� �� �ֽ��ϴ�.<br>
						- ��ǰ�� Ư���̳� ���θ��� ��ȭ�� �� �� �¿����� �� �������� �����ϸ鼭 ����Ͻ� �� �ֽ��ϴ�.
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




			<!-- ���� ���� -->
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