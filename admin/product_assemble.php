<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "product";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

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

	if(selcode.length==12 && selcode!="000000000000") {
		document.form2.mode.value="";
		document.form2.code.value=selcode;
		document.form2.target="ListFrame";
		document.form2.action="product_assemble.list.php";
		if(document.ListFrame.form1)
			document.form2.Scrolltype.value = document.ListFrame.form1.Scrolltype.value
		document.form2.submit();
	} else {
		document.form2.mode.value="";
		document.form2.code.value="";
		document.form2.target="ListFrame";
		document.form2.action="product_assemble.list.php";
		if(document.ListFrame.form1)
			document.form2.Scrolltype.value = document.ListFrame.form1.Scrolltype.value
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

function assembleFormSubmit() {
	form = document.form1;

	if(form.assemble_title.selectedIndex<0) {
		document.assembleForm.assemble_title_code.value = "";
		document.assembleForm.assemble_title_list.value = "";
		document.assembleForm.assemble_basic_code.value = "";
	} else {
		document.assembleForm.assemble_title_code.value = form.assemble_title.selectedIndex;
		assemble_array_num = form.assemble_title.options[form.assemble_title.selectedIndex].value.split('');
		document.assembleForm.assemble_title_list.value = assemble_array_num[2];
		document.assembleForm.assemble_basic_code.value = assemble_array_num[3];
	}
	document.assembleForm.submit();
}

function assembleuse_change() {
	form = document.form1;
	if(document.getElementById("assembleidx")) {
		if(form.assembleuse[0].checked) {
			document.getElementById("assembleidx").style.display="none";
		} else if(form.assembleuse[1].checked) {
			document.getElementById("assembleidx").style.display="";
		}
	}
}

function assemble_title_change(assembleFormtype) {
	form = document.form1;
	form.assemble_update_type.value="";
	form.assemble_title_name.value="";
	form.assemble_type.checked=true;
	form.assemble_modify_list.value="";
	form.assemble_basic_code.value="";
	if(document.getElementById("btnidx")){
		document.getElementById("btnidx").src = "images/btn_input.gif";
	}

	if(assembleFormtype=="Y") {
		assembleFormSubmit();
	}
}

function assemble_title_move(movetype) {
	form = document.form1;
	if(form) {
		if(form.assemble_title.selectedIndex<0) {
			alert("�̵��� ���� ��ǰ Ÿ��Ʋ�� ������ �ּ���.");
			form.assemble_title.focus();
		} else {
			var moveobj_value = form.assemble_title.options[form.assemble_title.selectedIndex].value;
			var moveobj_text = form.assemble_title.options[form.assemble_title.selectedIndex].text;

			if(movetype=="up") {
				if(form.assemble_title.selectedIndex>0 && form.assemble_title.options[form.assemble_title.selectedIndex-1]) {
					var movego_value = form.assemble_title.options[form.assemble_title.selectedIndex-1].value;
					var movego_text = form.assemble_title.options[form.assemble_title.selectedIndex-1].text;
					form.assemble_title.options[form.assemble_title.selectedIndex-1].value=moveobj_value;
					form.assemble_title.options[form.assemble_title.selectedIndex-1].text=moveobj_text;
					form.assemble_title.options[form.assemble_title.selectedIndex].value=movego_value;
					form.assemble_title.options[form.assemble_title.selectedIndex].text=movego_text;
					form.assemble_title.options[form.assemble_title.selectedIndex].selected=false;
					form.assemble_title.options[form.assemble_title.selectedIndex-1].selected=true;
				}
			} else {
				if(form.assemble_title.options[form.assemble_title.selectedIndex+1]) {
					var movego_value = form.assemble_title.options[form.assemble_title.selectedIndex+1].value;
					var movego_text = form.assemble_title.options[form.assemble_title.selectedIndex+1].text;
					form.assemble_title.options[form.assemble_title.selectedIndex+1].value=moveobj_value;
					form.assemble_title.options[form.assemble_title.selectedIndex+1].text=moveobj_text;
					form.assemble_title.options[form.assemble_title.selectedIndex].value=movego_value;
					form.assemble_title.options[form.assemble_title.selectedIndex].text=movego_text;
					form.assemble_title.options[form.assemble_title.selectedIndex].selected=false;
					form.assemble_title.options[form.assemble_title.selectedIndex+1].selected=true;
				}
			}
			assemble_title_change('');
		}
	}
}

function assemble_title_delete() {
	form = document.form1;
	if(form) {
		if(form.assemble_title.selectedIndex<0) {
			alert("������ ���� ��ǰ Ÿ��Ʋ�� ������ �ּ���.");
			form.assemble_title.focus();
		} else {
			if(confirm("���õ� ���� ��ǰ Ÿ��Ʋ�� ���� �ϰڽ��ϱ�?")) {
				form.assemble_title.options[form.assemble_title.selectedIndex]=null;
				assemble_title_change('Y');
			}
		}
	}
}

function assemble_title_modify() {
	form = document.form1;
	if(form) {
		if(form.assemble_title.selectedIndex<0) {
			alert("������ ���� ��ǰ Ÿ��Ʋ�� ������ �ּ���.");
			form.assemble_title.focus();
		}
		else {
			form.assemble_update_type.value="modify";
			assemble_array_num=form.assemble_title.options[form.assemble_title.selectedIndex].value.split('');

			form.assemble_type.checked=(assemble_array_num[0]=="Y"?true:false);
			form.assemble_title_name.value=assemble_array_num[1];
			form.assemble_modify_list.value=assemble_array_num[2];
			form.assemble_basic_code.value=assemble_array_num[3];

			if(document.getElementById("btnidx")){
				document.getElementById("btnidx").src = "images/btn_modify.gif";
			}
		}
	} else {
		alert("���� ��ǰ Ÿ��Ʋ �߰� �� ������ �߻��ƽ��ϴ�.");
		return;
	}
}

function assemble_title_update() {
	form = document.form1;
	if(form) {
		if(form.assemble_title_name.value.length>0) {
			if(form.assemble_update_type.value=="modify") {
				form.assemble_title.options[form.assemble_title.selectedIndex].value=(form.assemble_type.checked?"Y":"N")+""+form.assemble_title_name.value+""+form.assemble_modify_list.value+""+form.assemble_basic_code.value;
				form.assemble_title.options[form.assemble_title.selectedIndex].text=form.assemble_title_name.value+(form.assemble_type.checked?" : �ʼ�(O)":" : �ʼ�(X)");
			} else {
				form.assemble_title.options[form.assemble_title.length] = new Option(form.assemble_title_name.value+(form.assemble_type.checked?" : �ʼ�(O)":" : �ʼ�(X)"),(form.assemble_type.checked?"Y":"N")+""+form.assemble_title_name.value+""+"", false, false);
			}
			assemble_title_change('');
		} else {
			alert("���� ��ǰ Ÿ��Ʋ�� �Է��� �ּ���.");
			form.assemble_title_name.focus();
			return;
		}
	} else {
		alert("���� ��ǰ Ÿ��Ʋ �߰� �� ������ �߻��ƽ��ϴ�.");
		return;
	}
}

function assemble_list_update(assemblelistproduct,assemblebasicproduct) {
	form = document.form1;
	if(form.assemble_title.selectedIndex<0) {
		alert('���� ��ǰ Ÿ��Ʋ�� ���� �ȵ� �ֽ��ϴ�.');
		form.assemble_title.focus();
		return false;
	} else {
		form.assemble_update_type.value="modify";
		assemble_array_num=form.assemble_title.options[form.assemble_title.selectedIndex].value.split('');
		form.assemble_title.options[form.assemble_title.selectedIndex].value=(assemble_array_num[0]=="Y"?"Y":"N")+""+assemble_array_num[1]+""+assemblelistproduct+""+assemblebasicproduct;
		form.assemble_title.options[form.assemble_title.selectedIndex].text=assemble_array_num[1]+(assemble_array_num[0]=="Y"?" : �ʼ�(O)":" : �ʼ�(X)");
		assemble_title_change('');
		return true;
	}
}
</script>

<STYLE type=text/css>
	#menuBar {}
	#contentDiv {WIDTH: 200;HEIGHT: 320;}
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
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ��ǰ���� &gt; ī�װ�/��ǰ���� &gt; <span class="2depth_select">�ڵ�/���� ��ǰ ����</span></td>
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
					<TD><IMG SRC="images/product_assemble_titl.gif" border="0"></TD>
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
					<TD width="100%" class="notice_blue">�ڵ�/���� ��ǰ���� ��ϵ� ��ǰ�� ���� ��ǰ ���/����/���� �� �� �ֽ��ϴ�.<br>���� ��ǰ���� ������ ��ǰ���� ������ : ��ǰ��, �ǸŰ���, ���, ��ǰ��������</TD>
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
				<table cellpadding="0" cellspacing="0" width="100%" height="1090">
				<tr>
					<td valign="top">
					<DIV onmouseover="document.getElementById('cateidx').style.zIndex=2;" id="cateidx" style="position:absolute;z-index:0;width:242px;bgcolor:#FFFFFF;">
					<table cellpadding="0" cellspacing="0" width="100%" height="1050">
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
							<button title="��ü Ʈ��Ȯ��" id="btn_treeall" class="btn" onmouseover="if(this.className=='btn'){this.className='btnOver'}" onmouseout="if(this.className=='btnOver'){this.className='btn'}" unselectable="on" onclick="AllOpen();"><IMG SRC="images/category_btn1.gif" WIDTH=22 HEIGHT=23 border="0"></button>
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
							<DIV class=MsgrScroller id=contentDiv style="width=99%;height:100%;OVERFLOW-x: auto; OVERFLOW-y: auto;" oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
							<DIV id=bodyList>
							<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%" bgcolor=FFFFFF>
							<tr>
								<td height=18><IMG SRC="images/directory_root.gif" border=0 align=absmiddle> <span id="code_top" style="cursor:default;" onmouseover="this.className='link_over'" onmouseout="this.className='link_out'" onclick="ChangeSelect('');">�ֻ��� ī�װ�</span></td>
							</tr>
							<tr>
								<!-- ��ǰī�װ� ��� -->
								<td id="code_list" nowrap valign=top></td>
								<!-- ��ǰī�װ� ��� �� -->
							</tr>
							</table>
							</DIV>
							</DIV>
							</td>
						</tr>
						</table>
						</td>
					</tr>
					<tr>
						<td><IMG SRC="images/category_boxdown.gif" border="0"></td>
					</tr>
					</table>
					</div>
					</td>
					<td style="padding-left:84px;"></td>
					<td width="100%" valign="top" height="100%" onmouseover="document.getElementById('cateidx').style.zIndex=0;"><DIV style="position:relative;z-index:1;width:100%;height:100%;bgcolor:#FFFFFF;"><IFRAME name="ListFrame" src="product_assemble.list.php" width=100% height=1000 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME></div></td>
				</tr>
				</table>
				</td>
			</tr>
			<IFRAME name="HiddenFrame" src="/blank.php" width=0 height=0 frameborder=0 align=TOP scrolling="no" marginheight="0" marginwidth="0"></IFRAME>
			</form>
			<form name=form2 action="" method=post>
			<input type=hidden name=mode>
			<input type=hidden name=code>
			<input type=hidden name=Scrolltype>
			</form>
			<tr>
				<td height="30"></td>
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
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;"  class="menual_bg">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�ڵ�/������ǰ �˻�/��� ���ǻ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ڵ�/������ �������� ������ ��ǰ���� �����ϴ� ���� ��ǰ��, �ǸŰ���, ���, ��ǰ�������θ� �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ڵ�/������ ��ǰ���� ������ ��ǰ���/���� �������� �ǸŰ��ݿ��� ���� ����� �ּ���.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ڵ�/���� ��ǰ�� ������ǰ �̵�Ͻ� ���Ű� �Ұ��� �մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ڵ�/���� ��ǰ�� ������ǰ ������ ������ ��ǰ�� ����� ���� �մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ڵ�/������ǰ�� �����ϴ� ��ǰ�� ���/����/������ �����մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ڵ�/���� ������ǰ ���ý� �ʼ� ���� �Ǵ� ���ʼ� ������ ������ �� �ֽ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top">- �ڵ�/���� �⺻ ������ǰ�̶� �ڵ�/������ ������ǰ �� �ϳ��� �⺻���� ���õǾ� �Ǹ� ���ݿ� ����˴ϴ�.</td>
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
			<tr>
				<td height="50"></td>
			</tr>
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
include ("codeinit.php");
?>

<? INCLUDE "copyright.php"; ?>