<?
switch(substr(strrchr(getenv("SCRIPT_NAME"),"/"),1)) {
	case "counter_index.php":
		$menuidx = "shop1"; $idx[0][0] = 'YES'; break;

	case "counter_timevisit.php":
		$menuidx = "shop2"; $idx[1][0] = 'YES'; break;
	case "counter_dayvisit.php":
		$menuidx = "shop2"; $idx[1][1] = 'YES'; break;
	case "counter_timepageview.php":
		$menuidx = "shop2"; $idx[1][2] = 'YES'; break;
	case "counter_daypageview.php":
		$menuidx = "shop2"; $idx[1][3] = 'YES'; break;
	case "counter_timeorder.php":
		$menuidx = "shop2"; $idx[1][4] = 'YES'; break;
	case "counter_dayorder.php":
		$menuidx = "shop2"; $idx[1][5] = 'YES'; break;

	case "counter_prcodeprefer.php":
		$menuidx = "shop3"; $idx[2][0] = 'YES'; break;
	case "counter_productprefer.php":
		$menuidx = "shop3"; $idx[2][1] = 'YES'; break;
	case "counter_prsearchprefer.php":
		$menuidx = "shop3"; $idx[2][2] = 'YES'; break;
	case "counter_sitepageprefer.php":
		$menuidx = "shop3"; $idx[2][3] = 'YES'; break;

	case "counter_domainrank.php":
		$menuidx = "shop4"; $idx[3][0] = 'YES'; break;
	case "counter_searchenginerank.php":
		$menuidx = "shop4"; $idx[3][1] = 'YES'; break;
	case "counter_searchkeywordrank.php":
		$menuidx = "shop4"; $idx[3][2] = 'YES'; break;

	case "counter_timetotal.php":
		$menuidx = "shop5"; $idx[4][0] = 'YES'; break;
	case "counter_daytotal.php":
		$menuidx = "shop5"; $idx[4][1] = 'YES'; break;

	case "counter_count.php":
		$menuidx = "shop6"; $idx[5][0] = 'YES'; break;
	case "counter_NotAuthIP.php":
		$menuidx = "shop6"; $idx[5][1] = 'YES'; break;
}

function noselectmenu($name,$url,$idx,$end){
	if($end==0 || $end==3){
		echo "<tr><td  height=\"8\"></td></tr>";
	}
	$str_style_class="depth2_default";
	if ($idx == "YES") {
		$str_style_class = "depth2_select";
	}
	echo "<tr>\n";
	echo "	<td  style=\"padding-left:33pt;\" class=\"".$str_style_class."\" height=\"19\"><img src=\"images/icon_leftmenu1.gif\" width=\"8\" height=\"10\" border=\"0\"><a href=\"".$url."\">".$name."</a></td>\n";
	echo "</tr>\n";
	if($end==2 || $end==3){
		echo "<tr><td height=\"25\" ></td></tr>";
	}
}
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
layerlist = new Array ('shop1','shop2','shop3','shop4','shop5','shop6');
var thisshop="<?=$menuidx?>";
ino=6;

function Change(){
	if(document.all){
		for(i=0;i<ino;i++) {
			document.all(layerlist[i]).style.display="none";
		}
		stobj="document.all(shop).style";
	} else if(document.getElementById){
		for(i=0;i<ino;i++) {
			document.getElementById(layerlist[i]).style.display="none";
		}
		stobj="document.getElementById(shop).style";
	} else if(document.layers){
		for(i=0;i<ino;i++) {
			document.layers[layerlist[i]].display=none;
		}
		stobj="document.layers[shop]";
	}
}

function ChangeMenu(shop){
	if ( thisshop !== shop){
		Change();
		eval(stobj).display="block";
		thisshop=shop;
	} else{
		Change();
		//eval(stobj).display="block";
		thisshop=stobj;
	}
}

function InitMenu(shop) {
	try {
		tblashop = "tbla".concat(shop);
		tblbshop = "tblb".concat(shop);
		document.all(shop).style.display="block";
		document.all(tblashop).style.display="none";
		document.all(tblbshop).style.display="block";
		num=shop.substring(4,5)-1;
	} catch (e) {
		thisshop = "shop1";
		tblashop = "tblashop1";
		tblbshop = "tblbshop1";
		document.all(thisshop).style.display="block";
		document.all(tblashop).style.display="none";
		document.all(tblbshop).style.display="block";
		num=thisshop.substring(4,5)-1;
	}
}
//-->
</SCRIPT>

<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
	<TD height="68" valign="top" background="images/counter_leftmenu_title.gif" style="padding-top:10pt; padding-right:5pt;"><p align="right"><a href="javascript:scrollMove(0);"><img src="images/leftmenu_stop.gif" width="27" height="15" border="0" id="menu_pix"></a><a href="javascript:scrollMove(1);"><img src="images/leftmenu_trans.gif" width="28" height="15" border="0" hspace="2" id="menu_scroll"></a></TD>
</TR>
<TR>
	<TD  background="images/leftmenu_bg.gif">
	<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
	<col width="16"></col>
	<col></col>
	<col width="16"></col>
	<TR>

		<TD valign="top">
		<table cellpadding="0" cellspacing="0"  id=tblashop1 width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop1');"       class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">������� HOME</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblbshop1 style="display:none" width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34"  class="depth1_select" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop1');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">������� HOME</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0" >
		<tr>
			<td >
			<div id=shop1 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table cellpadding="0" cellspacing="0" >
<?
			if($menuidx && $menuidx != "shop1") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			noselectmenu('������� HOME','counter_index.php',$idx[0][0],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblashop2 width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop2');"       class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">Ʈ���� �м�</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblbshop2 style="display:none" width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" class="depth1_select"  style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop2');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">Ʈ���� �м�</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td >
			<div id=shop2 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table cellpadding="0" cellspacing="0"  width="100%">
<?
			if($menuidx != "shop2") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			noselectmenu('�ð��� �� �湮��','counter_timevisit.php',$idx[1][0],0);
			noselectmenu('���ں� �� �湮��','counter_dayvisit.php',$idx[1][1],1);
			noselectmenu('�ð��� ��������','counter_timepageview.php',$idx[1][2],1);
			noselectmenu('���ں� ��������','counter_daypageview.php',$idx[1][3],1);
			noselectmenu('�ð��� �ֹ��õ��Ǽ�','counter_timeorder.php',$idx[1][4],1);
			noselectmenu('���ں� �ֹ��õ��Ǽ�','counter_dayorder.php',$idx[1][5],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<table cellpadding="0" cellspacing="0"  id=tblashop3 width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop3');"       class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">�� ��ȣ�� �м�</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblbshop3 style="display:none" width="100%">
<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" class="depth1_select"  style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop3');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">�� ��ȣ�� �м�</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  width="100%">
		<tr>
			<td >
			<div id=shop3 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table cellpadding="0" cellspacing="0"  width="100%">
<?
			if($menuidx != "shop3") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			noselectmenu('�з��� ��ȣ��','counter_prcodeprefer.php',$idx[2][0],0);
			noselectmenu('��ǰ ��ȣ��','counter_productprefer.php',$idx[2][1],1);
			noselectmenu('��ǰ �˻� ��ȣ��','counter_prsearchprefer.php',$idx[2][2],1);
			noselectmenu('Site ������� ��ȣ��','counter_sitepageprefer.php',$idx[2][3],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<table cellpadding="0" cellspacing="0"  id=tblashop4 width="100%">
<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop4');"       class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">�ܺ� ���� ��� �м�</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblbshop4 style="display:none" width="100%">
<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" class="depth1_select"  style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop4');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">�ܺ� ���� ��� �м�</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  width="100%">
		<tr>
			<td >
			<div id=shop4 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table cellpadding="0" cellspacing="0"  width="100%">
<?
			if($menuidx != "shop4") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			noselectmenu('�����κ� ���ټ���','counter_domainrank.php',$idx[3][0],0);
			noselectmenu('�˻������� ���ټ���','counter_searchenginerank.php',$idx[3][1],1);
			noselectmenu('�˻����� �˻��� ����','counter_searchkeywordrank.php',$idx[3][2],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<table cellpadding="0" cellspacing="0"  id=tblashop5 width="100%">
<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop5');"       class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">�׷����� ���� ���м�</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblbshop5 style="display:none" width="100%">
<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" class="depth1_select"  style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop5');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">�׷����� ���� ���м�</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  width="100%">
		<tr>
			<td >
			<div id=shop5 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table cellpadding="0" cellspacing="0"  width="100%">
<?
			if($menuidx != "shop5") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			noselectmenu('�ð��� ��ü �������','counter_timetotal.php',$idx[4][0],0);
			noselectmenu('���ں� ��ü �������','counter_daytotal.php',$idx[4][1],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<table cellpadding="0" cellspacing="0"  id=tblashop6 width="100%">
<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop6');"       class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">���� ����</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  style="display:none" id=tblbshop6 width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34"  class="depth1_select" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop6');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">���� ����</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0" >
		<tr>
			<td >
			<div id=shop6 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table cellpadding="0" cellspacing="0" >
<?
			if($menuidx != "shop6") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			noselectmenu('���� IP ����','counter_count.php',$idx[5][0],0);
			noselectmenu('���� IP ����','counter_NotAuthIP.php',$idx[5][1],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		</TD>

	</TR>
	</TABLE>
	</TD>
</TR>

</TABLE>

<script>
InitMenu('<?=$menuidx?>');
</script>
<script type="text/javascript" src="move_menu.js.php"></script>