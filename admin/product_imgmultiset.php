<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-2";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$searchtype=0;
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="codeinit.js.php"></script>
<script language="JavaScript">
var code="<?=$code?>";
function CodeProcessFun(_code) {
	if(_code=="out" || _code.length==0 || _code=="000000000000") {
		document.all["code_top"].style.background="#dddddd";
		selcode="";
		seltype="";

		if(_code!="out") {
			BodyInit('');
		} else {
			_code="";
		}
	} else {
		document.all["code_top"].style.background="#ffffff";
		BodyInit(_code);
	}

	if(selcode.length==12 && selcode!="000000000000" && seltype.indexOf("X")!=-1) {
		document.form2.mode.value="";
		document.form2.code.value=selcode;
		document.form2.target="ListFrame";
		document.form2.action="product_imgmultiset.list.php";
		if(document.ListFrame.form1)
			document.form2.Scrolltype.value = document.ListFrame.form1.Scrolltype.value;
		document.form2.submit();
	} else {
		document.form2.mode.value="";
		document.form2.code.value="";
		document.form2.target="ListFrame";
		document.form2.action="product_imgmultiset.list.php";
		if(document.ListFrame.form1)
			document.form2.Scrolltype.value = document.ListFrame.form1.Scrolltype.value;
		document.form2.submit();
	}
}

var allopen=false;
function AllOpen() {
	display="show";
	open1="open";
	if(allopen) {
		display="none";
		open1="close";
		allopen=false;
	} else {
		allopen=true;
	}
	for(i=0;i<all_list.length;i++) {
		if(display=="none" && all_list[i].codeA==selcode.substring(0,3)) {
			all_list[i].selected=true;
			selcode=all_list[i].codeA+all_list[i].codeB+all_list[i].codeC+all_list[i].codeD;
			seltype=all_list[i].type;
		}
		all_list[i].display=display;
		all_list[i].open=open1;
		for(ii=0;ii<all_list[i].ArrCodeB.length;ii++) {
			if(display=="none") {
				all_list[i].ArrCodeB[ii].selected=false;
			}
			all_list[i].ArrCodeB[ii].display=display;
			all_list[i].ArrCodeB[ii].open=open1;
			for(iii=0;iii<all_list[i].ArrCodeB[ii].ArrCodeC.length;iii++) {
				if(display=="none") {
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].selected=false;
				}
				all_list[i].ArrCodeB[ii].ArrCodeC[iii].display=display;
				all_list[i].ArrCodeB[ii].ArrCodeC[iii].open=open1;
				for(iiii=0;iiii<all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD.length;iiii++) {
					if(display=="none") {
						all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].selected=false;
					}
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].display=display;
					all_list[i].ArrCodeB[ii].ArrCodeC[iii].ArrCodeD[iiii].open=open1;
				}
			}
		}
	}
	BodyInit('');
}

function ViewLayer(layer) {
	if(layer=="layer2") {
		document.all["contentDiv"].disabled=true;
		document.all["hide_code_div"].style.display="none";
		document.all["hide_code_div2"].style.display="";
		document.all["layer2"].style.display="";
	} else {
		document.all["contentDiv"].disabled=false;
		document.all["hide_code_div"].style.display="";
		document.all["hide_code_div2"].style.display="none";
		document.all["layer2"].style.display="none";
	}
}

function CheckSearch() {
	document.form1.mode.value = "";
	document.form1.code.value = "";
	if (document.form1.keyword.value.length<2) {
		if(document.form1.keyword.value.length==0) alert("�˻�� �Է��ϼ���.");
		else alert("�˻���� 2���� �̻� �Է��ϼž� �մϴ�.");
		document.form1.keyword.focus();
		return;
	} else {
		document.form1.target="ListFrame";
		document.form1.action="product_imgmultiset.list.php";
		document.form1.Scrolltype.value = document.ListFrame.form1.Scrolltype.value;
		document.form1.submit();
	}
}

function CheckKeyPress(){
	ekey=event.keyCode;
	if (ekey==13) {
		CheckSearch();
	}
}

var divLeft=0;
var defaultLeft=0;
var timeOffset=0;
var setTObj;
var divName="";
var zValue=0;

function divMove()
{
	divLeft+=timeOffset;

	if(divLeft >= defaultLeft)
	{
		divLeft=defaultLeft;
		divName.style.left=divLeft;
		divName.style.zIndex = zValue;
		clearTimeout(setTObj);
		setTObj="";
	}
	else
	{
		timeOffset+=20;
		divName.style.left=divLeft;
		setTObj=setTimeout('divMove();',5);
	}
}

function divAction(arg1,arg2)
{
	if(zValue != arg2 && !setTObj)
	{
		defaultLeft = arg1.offsetLeft;
		divLeft = defaultLeft;
		zValue = arg2;
		divName = arg1;
		if(defaultLeft>0)
			timeOffset = -70;
		divMove();
	}
}
</script>

<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 200;HEIGHT: 315;}
</STYLE>
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
			<? include ("menu_product.php"); ?>
			</td>

			<td></td>
			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt;�����̹��� ���� &gt; <span class="2depth_select">��ǰ �����̹��� ���/����</span></td>
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
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/product_imgmultiset_title.gif" ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
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
					<TD width="100%" class="notice_blue">��ǰ��Ͻ� ��/��/�� �̹��� �� 10������ �̹����� �������� �� ������ �� �ֽ��ϴ�.</TD>
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
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
			<input type=hidden name=mode value="<?=$mode?>">
			<input type=hidden name=code>
			<input type=hidden name=Scrolltype>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td bgcolor="#ededed" style="padding:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<TABLE cellSpacing=0 cellPadding="0" width="100%" border=0>
						<TR>
							<TD class="table_cell" width="214"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b>��ǰ���� ����</b></TD>
							<TD class="td_con1"><input type=radio id="idx_searchtype1" name=searchtype value="0" onclick="ViewLayer('layer1')" <?if($searchtype=="0") echo "checked";?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype1>ī�װ��� ��ǰ ����</label>&nbsp;&nbsp;&nbsp;<input type=radio id="idx_searchtype2" name=searchtype value="1" onclick="ViewLayer('layer2')" <?if($searchtype=="1") echo "checked";?>><label style='cursor:hand; TEXT-DECORATION: none' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_searchtype2>�˻����� ��ǰ ����</label></TD>
						</TR>
						</table>
						<div id=layer2 style="margin-left:0;display:hide; display:<?=($searchtype=="1"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<TABLE cellSpacing=0 cellPadding="0" width="100%" border=0>
						<TR>
							<TD colspan="2" background="images/table_con_line.gif"></TD>
						</TR>
						<TR>
							<TD class="table_cell" width="214"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b>��ǰ�� �Է�</b></TD>
							<TD class="td_con1">
							<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="99%"><input type=text name=keyword size=50 value="<?=$keyword?>" onKeyDown="CheckKeyPress()" class="input" style=width:100%></td>
								<td width="1%"><p align="right"><a href="javascript:CheckSearch();"><img src="images/btn_search2.gif" width="50" height="25" border="0" align=absmiddle hspace="2"></a></td>
							</tr>
							</table>
							</TD>
						</TR>
						</TABLE>
						</div>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%" height="648">
				<tr>
					<td valign="top">
					<DIV onmouseover="document.getElementById('cateidx').style.zIndex=2;" id="cateidx" style="position:absolute;z-index:0;width:242px;bgcolor:#FFFFFF;">
					<table cellpadding="0" cellspacing="0" width="100%" height="608">
					<tr>
						<td width="232" height="100%" valign="top" background="images/category_boxbg.gif">
						<table cellpadding="0" cellspacing="0" width="242" height="100%">
						<tr>
							<td bgcolor="#FFFFFF"><IMG SRC="images/product_totoacategory_title.gif" border="0"></td>
						</tr>
						<tr>
							<td><IMG SRC="images/category_box1.gif" border="0"></td>
						</tr>
						<tr>
							<td bgcolor="#0F8FCB" style="padding:2;padding-left:4">
							<button title="��ü Ʈ��Ȯ��" id="btn_treeall" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="AllOpen();"><IMG SRC="images/category_btn1.gif" border="0"></button>
							</td>
						</tr>
						<tr>
							<td bgcolor="#0F8FCB" style="padding-top:4pt; padding-bottom:6pt;"></td>
						</tr>
						<tr>
							<td><IMG SRC="images/category_box2.gif" border="0"></td>
						</tr>
						<tr>
							<td width="100%" height="100%" align=center valign=top style="padding-left:5px;padding-right:5px;">
							<div id=hide_code_div style="width=99%;height:100%;">
							<DIV class=MsgrScroller id=contentDiv style="width=99%;height:100%;OVERFLOW-x: auto; OVERFLOW-y: auto; z-index:1" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
							<DIV id=bodyList>
							<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor="#FFFFFF">
							<tr>
								<td height=18><IMG SRC="images/directory_root.gif" border=0 align=absmiddle> <span id="code_top" style="cursor:default;" onmouseover="this.className='link_over'" onmouseout="this.className='link_out'" onclick="ChangeSelect('out');">�ֻ��� ī�װ�</span></td>
							</tr>
							<tr>
								<!-- ��ǰī�װ� ��� -->
								<td id="code_list" nowrap valign=top></td>
								<!-- ��ǰī�װ� ��� �� -->
							</tr>
							</table>
							</DIV>
							</DIV>
							</div>
							<div id=hide_code_div2 style="width=99%;height:100%;display:none;">
							<DIV class=MsgrScroller id=contentDiv2 style="width=99%;height:100%;OVERFLOW-x: auto; OVERFLOW-y: auto; background:#f4f4f4" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" disabled>
							<DIV id=bodyList2>
							<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor="#FFFFFF">
							<tr>
								<td nowrap align=center>ī�װ��� ��ǰ���⿡����<br>ī�װ������� ���� �� �ֽ��ϴ�.</td>
							</tr>
							</table>
							</DIV>
							</DIV>
							</div>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><IMG SRC="images/category_boxdown.gif" border="0"></td>
					</tr>
					</table>
					</td>
					<td style="padding-left:84px;"></td>
					<td width="100%" valign="top" height="100%" onmouseover="document.getElementById('cateidx').style.zIndex=0;"><DIV style="position:relative;z-index:1;width:100%;height:100%;bgcolor:#FFFFFF;"><IFRAME name="ListFrame" src="product_imgmultiset.list.php" width=100% height=648 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME></div></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height="30"></td>
			</tr>
			<IFRAME name="HiddenFrame" src="<?=$Dir?>blank.php" width=0 height=0 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME>
			</form>
			<form name=form2 action="" method=post>
			<input type=hidden name=mode>
			<input type=hidden name=code>
			<input type=hidden name=Scrolltype>
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
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��ǰ���� ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ī�װ��� ��ǰ ���� : ��ǰ�� ��ϵ� ������ ī�װ��� ������ ��� ��ǰ�� ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �˻����� ��ǰ ���� : ��ǰ������ �˻� �� �˻��� ��ǰ�� ���� ��� ��ǰ�� ��µ˴ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">��ǰ �����̹��� ���/���� ���ǻ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �����̹��� ��Ͻ� ��,�� �̹����� ����� ���� �̹����� ��ü�Ǿ� ��µ˴ϴ�.<br>
						<b>&nbsp;&nbsp;</b>��,�� �̹��� �ʿ�� ���� �̹����� �߰� ����� �ּ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- ������ư Ŭ���� �ش� ��ǰ���� ��ϵ� �����̹��� ��� �ϰ��� �����˴ϴ�.</td>
					</tr>
					<tr>
						<td height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�����̹��� ��Ϲ��</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">�� ��ǰ���� ����(ī�װ��� ��ǰ ����, �˻����� ��ǰ ����)�� ���� ��ǰ ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">�� �����̹����� �߰��� ��ǰ�� ���� �� �ִ� 10������ �Է��� �� �ֽ��ϴ�.</td>
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
<?
$sql = "SELECT * FROM tblproductcode WHERE type not in('T','TX','TM','TMX','S','SX','SM','SMX') ";
$sql.= "ORDER BY sequence DESC ";
include ("codeinit.php")
?>

<? INCLUDE "copyright.php"; ?>