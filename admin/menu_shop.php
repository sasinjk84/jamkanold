<?
switch(substr(strrchr(getenv("SCRIPT_NAME"),"/"),1)) {
	case "shop_basicinfo.php":
		$menuidx = "shop1"; $idx[0][0] = 'YES'; break;
	case "shop_keyword.php":
		$menuidx = "shop1"; $idx[0][1] = 'YES'; break;
	/*
	case "shop_mainintro.php":
		$menuidx = "shop1"; $idx[0][2] = 'YES'; break;
	case "shop_companyintro.php":
		$menuidx = "shop1"; $idx[0][3] = 'YES'; break;
	*/
	case "shop_agreement.php":
		$menuidx = "shop1"; $idx[0][2] = 'YES'; break;
	case "shop_privercyinfo.php":
		$menuidx = "shop1"; $idx[0][3] = 'YES'; break;

	/*
	case "shop_openmethod.php":
		$menuidx = "shop2"; $idx[1][0] = 'YES'; break;
	case "shop_displaytype.php":
		$menuidx = "shop2"; $idx[1][1] = 'YES'; break;
	case "shop_layout.php":
		$menuidx = "shop2"; $idx[1][2] = 'YES'; break;
	case "shop_mainproduct.php":
		$menuidx = "shop2"; $idx[1][3] = 'YES'; break;
	case "shop_productshow.php":
		$menuidx = "shop2"; $idx[1][4] = 'YES'; break;
	case "shop_mainleftinform.php":
		$menuidx = "shop2"; $idx[1][5] = 'YES'; break;
	*/
	case "shop_logobanner.php":
		$menuidx = "shop2"; $idx[1][0] = 'YES'; break;
	/*
	case "shop_orderform.php":
		$menuidx = "shop2"; $idx[1][7] = 'YES'; break;
	case "shop_ssl.php":
		$menuidx = "shop2"; $idx[1][8] = 'YES'; break;
	case "shop_bizsiren.php":
		$menuidx = "shop2"; $idx[1][9] = 'YES'; break;
	*/

	case "shop_tag.php":
		$menuidx = "shop3"; $idx[2][0] = 'YES'; break;
	case "shop_search.php":
		$menuidx = "shop3"; $idx[2][1] = 'YES'; break;
	case "shop_reserve.php":
		$menuidx = "shop3"; $idx[2][2] = 'YES'; break;
	case "shop_recommand.php":
		$menuidx = "shop3"; $idx[2][3] = 'YES'; break;
	case "shop_member.php":
		$menuidx = "shop3"; $idx[2][4] = 'YES'; break;
	case "shop_deli.php":
		$menuidx = "shop3"; $idx[2][5] = 'YES'; break;
	case "shop_payment.php":
		$menuidx = "shop3"; $idx[2][6] = 'YES'; break;
	case "shop_escrow.php":
		$menuidx = "shop3"; $idx[2][7] = 'YES'; break;
	case "shop_return.php":
		$menuidx = "shop3"; $idx[2][8] = 'YES'; break;
	case "shop_basket.php":
		$menuidx = "shop3"; $idx[2][9] = 'YES'; break;
	case "shop_review.php":
		$menuidx = "shop3"; $idx[2][10] = 'YES'; break;
	case "shop_estimate.php":
		$menuidx = "shop3"; $idx[2][11] = 'YES'; break;
	case "shop_snsinfo.php":
		$menuidx = "shop3"; $idx[2][12] = 'YES'; break;

	case "shop_rolelist.php":
		$menuidx = "shop4"; $idx[3][0] = 'YES'; break;
	case "shop_adminlist.php":
		$menuidx = "shop4"; $idx[3][1] = 'YES'; break;
	case "shop_iplist.php":
		$menuidx = "shop4"; $idx[3][2] = 'YES'; break;
	case "shop_changeadminpasswd.php":
		$menuidx = "shop4"; $idx[3][3] = 'YES'; break;
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
	echo "	<td height=\"19\"  style=\"padding-left:33px;\" class=\"".$str_style_class."\"><img src=\"images/icon_leftmenu1.gif\" border=\"0\"><a href=\"".$url."\">".$name."</a></td>\n";
	echo "</tr>\n";
	if($end==2 || $end==3){
		echo "<tr><td height=\"25\" ></td></tr>";
	}
}
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
layerlist = new Array ('shop1','shop2','shop3','shop4');
var thisshop="<?=$menuidx?>";
ino=2;

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
		shop = "shop1";
		tblashop = "tblashop1";
		tblbshop = "tblbshop1";
		document.all(shop).style.display="block";
		document.all(tblashop).style.display="none";
		document.all(tblbshop).style.display="block";
		num=shop.substring(4,5)-1;
	}
}
//-->
</SCRIPT>

<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
<TR>
	<TD height="68" align="right" valign="top" background="images/shop_leftmenu_title.gif"><a href="javascript:scrollMove(0);"><img src="images/leftmenu_stop.gif" border="0" id="menu_pix"></a><a href="javascript:scrollMove(1);"><img src="images/leftmenu_trans.gif" border="0"" id="menu_scroll"></a></TD>
</TR>
<TR>
	<TD  background="images/leftmenu_bg.gif">
	<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
	<col width="16"></col>
	<col></col>
	<col width="16"></col>
	<TR>
		<TD valign="top">
		<table width="100%" cellpadding="0" cellspacing="0" id="tblashop1">
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop1');"  class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">���� �⺻���� ����</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" id="tblbshop1" style="display:none">
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop1');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">���� �⺻���� ����</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<div id="shop1" style="display:none;">
			<table cellpadding="0" cellspacing="0" width="100%">

<?
			if($menuidx && $menuidx != "shop1") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			noselectmenu('���� �⺻���� ����','shop_basicinfo.php',$idx[0][0],0);
			noselectmenu('������ Ÿ��Ʋ/Ű����','shop_keyword.php',$idx[0][1],1);
			//noselectmenu('���� Ÿ��Ʋ�̹��� ������','shop_mainintro.php',$idx[0][2],1);
			//noselectmenu('ȸ�� �Ұ�/�൵','shop_companyintro.php',$idx[0][3],1);
			noselectmenu('���θ� �̿���','shop_agreement.php',$idx[0][2],1);
			noselectmenu('���θ� ����������޹�ħ','shop_privercyinfo.php',$idx[0][3],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<table width="100%" cellpadding="0" cellspacing="0" id="tblashop2">
			<tr><td height="3" background="images/leftmenu_line.gif"></td>
			</tr>
			<tr>
				<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop2');"  class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">���θ� ȯ�� ����</td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" id="tblbshop2" style="display:none">
			<tr><td height="3" background="images/leftmenu_line.gif"></td>
			<tr>
				<td height="34" style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop2');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">���θ� ȯ�� ����</td>
			</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<div id="shop2" style="display:none;">
			<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop2") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			//noselectmenu('������ ���� ����','shop_openmethod.php',$idx[1][0],0);
			//noselectmenu('������/���� ����','shop_displaytype.php',$idx[1][1],1);
			//noselectmenu('���θ� ���̾ƿ� ����','shop_layout.php',$idx[1][2],1);
			//noselectmenu('��ǰ ������/ȭ�� ����','shop_mainproduct.php',$idx[1][3],1);
			//noselectmenu('��ǰ ���� ��Ÿ ����','shop_productshow.php',$idx[1][4],1);
			//noselectmenu('���� �� �˸� ������','shop_mainleftinform.php',$idx[1][5],1);
			noselectmenu('�ΰ�/��� ����','shop_logobanner.php',$idx[1][0],2);
			//noselectmenu('ȸ������/�ֹ� �ȳ�����','shop_orderform.php',$idx[1][7],1);
			//noselectmenu('SSL(���ȼ���) ��� ����','shop_ssl.php',$idx[1][8],1);
			//noselectmenu('�Ǹ����� ���� ����','shop_bizsiren.php',$idx[1][9],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

<? if (strpos($_ShopInfo->getId(), "getmall") !== false) {?>
		<table width="100%" cellpadding="0" cellspacing="0" id="tblashop3">
		<tr><td height="3" background="images/leftmenu_line.gif"></td>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop3');"  class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">���θ� � ����</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" id="tblbshop3" style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop3');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">���θ� � ����</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<div id="shop3" style="display:none;">
			<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop3") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			noselectmenu('��ǰ�±� ���� ��ɼ���','shop_tag.php',$idx[2][0],0);
			noselectmenu('��ǰ�˻� ���� ��ɼ���','shop_search.php',$idx[2][1],1);
			noselectmenu('������/���� ��ɼ���','shop_reserve.php',$idx[2][2],1);
			noselectmenu('��õ�� ���� ����','shop_recommand.php',$idx[2][3],1);
			noselectmenu('ȸ������ ���� ����','shop_member.php',$idx[2][4],1);
			noselectmenu('��ǰ ��۰��� ��ɼ���','shop_deli.php',$idx[2][5],1);
			noselectmenu('��ǰ �������� ��ɼ���','shop_payment.php',$idx[2][6],1);
			noselectmenu('����ũ�� �������� ����','shop_escrow.php',$idx[2][7],1);
			noselectmenu('��ǰ ��ǰ/ȯ�� ��ɼ���','shop_return.php',$idx[2][8],1);
			noselectmenu('��ٱ��� ���� ��ɼ���','shop_basket.php',$idx[2][9],1);
			noselectmenu('��ǰ����(�ı�) ����','shop_review.php',$idx[2][10],1);
			noselectmenu('��ǰ ������ ��ɼ���','shop_estimate.php',$idx[2][11],1);
			noselectmenu('SNS �� ȫ�������� ����','shop_snsinfo.php',$idx[2][12],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>
<? }else{?>
<?}?>

<? if (strpos($_ShopInfo->getId(), "getmall") !== false) {?>
		<table width="100%" cellpadding="0" cellspacing="0" id="tblashop4">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop4');"  class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">���� ����</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0" id="tblbshop4" style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop4');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">���� ����</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<div id="shop4" style="display:none;">
						<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop4") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			noselectmenu('�׷� �� ���� ����','shop_rolelist.php',$idx[3][0],0);
			noselectmenu('���/�ο�� ����','shop_adminlist.php',$idx[3][1],1);
			noselectmenu('����IP ����','shop_iplist.php',$idx[3][2],1);
			noselectmenu('�н����� ����','shop_changeadminpasswd.php',$idx[3][3],2);
?>
						</table>
					</div>
				</td>
			</tr>
		</table>
<? }else{?>
<?}?>

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