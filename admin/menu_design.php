<?
switch(substr(strrchr(getenv("SCRIPT_NAME"),"/"),1)) {
	case "design_webftp.php":
		$menuidx = "shop1"; $idx[0][0] = 'YES'; break;
	case "design_option.php":
		$menuidx = "shop1"; $idx[0][1] = 'YES'; break;

	case "design_adultintro.php":
		$menuidx = "shop2"; $idx[1][0] = 'YES'; break;
	case "design_main.php":
		$menuidx = "shop2"; $idx[1][1] = 'YES'; break;
	case "design_bottom.php":
		$menuidx = "shop2"; $idx[1][2] = 'YES'; break;
	case "design_plist.php":
		$menuidx = "shop2"; $idx[1][3] = 'YES'; break;
	case "design_pdetail.php":
		$menuidx = "shop2"; $idx[1][4] = 'YES'; break;

	/*
	case "design_eachintropage.php":
		$menuidx = "shop3"; $idx[2][0] = 'YES'; break;
	case "design_eachtitleimage.php":
		$menuidx = "shop3"; $idx[2][1] = 'YES'; break;
	*/
	case "design_eachtopmenu.php":
		$menuidx = "shop3"; $idx[2][0] = 'YES'; break;
	case "design_eachleftmenu.php":
		$menuidx = "shop3"; $idx[2][1] = 'YES'; break;
	case "design_eachmain.php":
		$menuidx = "shop3"; $idx[2][2] = 'YES'; break;
	case "design_eachbottom.php":
		$menuidx = "shop3"; $idx[2][3] = 'YES'; break;
	/*
	case "design_eachloginform.php":
		$menuidx = "shop3"; $idx[2][6] = 'YES'; break;
	*/
	case "design_eachplist.php":
		$menuidx = "shop3"; $idx[2][4] = 'YES'; break;
	/*
	case "design_eachpdetail.php":
		$menuidx = "shop3"; $idx[2][8] = 'YES'; break;
	*/

	case "design_tag.php":
		$menuidx = "shop4"; $idx[3][0] = 'YES'; break;
	case "design_section.php":
		$menuidx = "shop4"; $idx[3][1] = 'YES'; break;
	case "design_search.php":
		$menuidx = "shop4"; $idx[3][2] = 'YES'; break;
	case "design_useinfo.php":
		$menuidx = "shop4"; $idx[3][3] = 'YES'; break;
	case "design_memberjoin.php":
		$menuidx = "shop4"; $idx[3][4] = 'YES'; break;
	case "design_usermodify.php":
		$menuidx = "shop4"; $idx[3][5] = 'YES'; break;
	case "design_login.php":
		$menuidx = "shop4"; $idx[3][6] = 'YES'; break;
	case "design_basket.php":
		$menuidx = "shop4"; $idx[3][7] = 'YES'; break;
	case "design_order.php":
		$menuidx = "shop4"; $idx[3][8] = 'YES'; break;
	case "design_mypage.php":
		$menuidx = "shop4"; $idx[3][9] = 'YES'; break;
	case "design_orderlist.php":
		$menuidx = "shop4"; $idx[3][10] = 'YES'; break;
	case "design_wishlist.php":
		$menuidx = "shop4"; $idx[3][11] = 'YES'; break;
	case "design_mycoupon.php":
		$menuidx = "shop4"; $idx[3][12] = 'YES'; break;
	case "design_myreserve.php":
		$menuidx = "shop4"; $idx[3][13] = 'YES'; break;
	case "design_mypersonal.php":
		$menuidx = "shop4"; $idx[3][14] = 'YES'; break;
	case "design_mycustsect.php":
		if(getVenderUsed()==true) { $menuidx = "shop4"; $idx[3][15] = 'YES'; } break;
	case "design_sendmail.php":
		$menuidx = "shop4"; $idx[3][16] = 'YES'; break;
	case "design_popupnotice.php":
		$menuidx = "shop4"; $idx[3][17] = 'YES'; break;
	case "design_popupinfo.php":
		$menuidx = "shop4"; $idx[3][18] = 'YES'; break;
	case "design_formmail.php":
		$menuidx = "shop4"; $idx[3][19] = 'YES'; break;
	case "design_cardtopimg.php":
		$menuidx = "shop4"; $idx[3][20] = 'YES'; break;
	case "design_blist.php":
		$menuidx = "shop4"; $idx[3][21] = 'YES'; break;
	case "design_bmap.php":
		$menuidx = "shop4"; $idx[3][22] = 'YES'; break;

	case "design_eachtag.php":
		$menuidx = "shop5"; $idx[4][0] = 'YES'; break;
	case "design_eachsection.php":
		$menuidx = "shop5"; $idx[4][1] = 'YES'; break;
	case "design_eachsearch.php":
		$menuidx = "shop5"; $idx[4][2] = 'YES'; break;
	case "design_eachbasket.php":
		$menuidx = "shop5"; $idx[4][3] = 'YES'; break;
	case "design_eachprimageview.php":
		$menuidx = "shop5"; $idx[4][4] = 'YES'; break;
	case "design_eachpopupnotice.php":
		$menuidx = "shop5"; $idx[4][5] = 'YES'; break;
	case "design_eachpopupinfo.php":
		$menuidx = "shop5"; $idx[4][6] = 'YES'; break;
	case "design_eachsendmail.php":
		$menuidx = "shop5"; $idx[4][7] = 'YES'; break;
	case "design_eachformmail.php":
		$menuidx = "shop5"; $idx[4][8] = 'YES'; break;
	case "design_eachboardtop.php":
		$menuidx = "shop5"; $idx[4][9] = 'YES'; break;
	case "design_eachuseinfo.php":
		$menuidx = "shop5"; $idx[4][10] = 'YES'; break;
	case "design_eachagreement.php":
		$menuidx = "shop5"; $idx[4][11] = 'YES'; break;
	case "design_eachjoinagree.php":
		$menuidx = "shop5"; $idx[4][12] = 'YES'; break;
	case "design_eachmemberjoin.php":
		$menuidx = "shop5"; $idx[4][13] = 'YES'; break;
	case "design_eachusermodify.php":
		$menuidx = "shop5"; $idx[4][14] = 'YES'; break;
	case "design_eachiddup.php":
		$menuidx = "shop5"; $idx[4][15] = 'YES'; break;
	case "design_eachfindpwd.php":
		$menuidx = "shop5"; $idx[4][16] = 'YES'; break;
	case "design_eachlogin.php":
		$menuidx = "shop5"; $idx[4][17] = 'YES'; break;
	case "design_eachmemberout.php":
		$menuidx = "shop5"; $idx[4][18] = 'YES'; break;
	case "design_eachmypage.php":
		$menuidx = "shop5"; $idx[4][19] = 'YES'; break;
	case "design_eachorderlist.php":
		$menuidx = "shop5"; $idx[4][20] = 'YES'; break;
	case "design_eachwishlist.php":
		$menuidx = "shop5"; $idx[4][21] = 'YES'; break;
	case "design_eachmycoupon.php":
		$menuidx = "shop5"; $idx[4][22] = 'YES'; break;
	case "design_eachmyreserve.php":
		$menuidx = "shop5"; $idx[4][23] = 'YES'; break;
	case "design_eachmypersonal.php":
		$menuidx = "shop5"; $idx[4][24] = 'YES'; break;
	case "design_eachmycustsect.php":
		if(getVenderUsed()==true) { $menuidx = "shop5"; $idx[4][25] = 'YES'; } break;
	case "design_eachsurveylist.php":
		$menuidx = "shop5"; $idx[4][26] = 'YES'; break;
	case "design_eachsurveyview.php":
		$menuidx = "shop5"; $idx[4][27] = 'YES'; break;
	case "design_eachreviewpopup.php":
		$menuidx = "shop5"; $idx[4][28] = 'YES'; break;
	case "design_eachreviewall.php":
		$menuidx = "shop5"; $idx[4][29] = 'YES'; break;
	case "design_eachrssinfo.php":
		$menuidx = "shop5"; $idx[4][30] = 'YES'; break;
	case "design_eachblist.php":
		$menuidx = "shop5"; $idx[4][31] = 'YES'; break;
	case "design_eachbmap.php":
		$menuidx = "shop5"; $idx[4][32] = 'YES'; break;
	case "design_eachbottomtools.php":
		$menuidx = "shop5"; $idx[4][33] = 'YES'; break;

	case "design_newpage.php":
		$menuidx = "shop6"; $idx[5][0] = 'YES'; break;
	case "design_community.php":
		$menuidx = "shop6"; $idx[5][1] = 'YES'; break;

	case "design_easytop.php":
		$menuidx = "shop7"; $idx[6][0] = 'YES'; break;
	case "design_easyleft.php":
		$menuidx = "shop7"; $idx[6][1] = 'YES'; break;
	case "design_easycss.php":
		$menuidx = "shop7"; $idx[6][2] = 'YES'; break;

	case "design_backup.php":
		$menuidx = "shop8"; $idx[7][0] = 'YES'; break;
	case "design_backup_revert.php":
		$menuidx = "shop8"; $idx[7][1] = 'YES'; break;
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
		echo "<tr><td height=\"25\"></td></tr>";
	}
}
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
layerlist = new Array ('shop1','shop2','shop3','shop4','shop5','shop6','shop7','shop8');
var thisshop="<?=$menuidx?>";
ino=8;

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

<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
<TR>
	<TD height="68" align="right" valign="top" background="images/design_leftmenu_title.gif"><a href="javascript:scrollMove(0);"><img src="images/leftmenu_stop.gif" border="0" id="menu_pix"></a><a href="javascript:scrollMove(1);"><img src="images/leftmenu_trans.gif" border="0"" id="menu_scroll"></a></TD>
</TR>
<TR>
	<TD  background="images/leftmenu_bg.gif">
	<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<col width="16"></col>
	<col></col>
	<col width="16"></col>
	<TR>
		<TD  valign="top" >
		<table cellpadding="0" cellspacing="0"  id=tblashop1>
		<tr><td height="3" background="images/leftmenu_line.gif"></td>
		<tr>
			<td height="34" onClick="ChangeMenu('shop1');" style="padding-left:20px;cursor:hand;"class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">��FTP, �������� ����</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblbshop1 style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td>
		<tr>
			<td  height="34"  class="depth1_select" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop1');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">��FTP, �������� ����</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td >
			<div id=shop1 style="display:none;">
			<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx && $menuidx != "shop1") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			noselectmenu('��FTP/��FTP�˾�','design_webftp.php',$idx[0][0],0);
			noselectmenu('���������� ���뼱��','design_option.php',$idx[0][1],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<!--
		<table width="100%" cellpadding="0" cellspacing="0"  id=tblashop2>
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34" onClick="ChangeMenu('shop2');" style="padding-left:20px;cursor:hand;" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">���ø�-����, ī�װ�</td>
		</tr>
		</table>
		-->
		<table width="100%" cellpadding="0" cellspacing="0"  id=tblbshop2 style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" class="depth1_select" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop2');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">���ø�-����, ī�װ�</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td >
			<div id=shop2 style="display:none;">
			<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop2") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			noselectmenu('���θ� ��Ʈ�� ���ø�','design_adultintro.php',$idx[1][0],0);
			noselectmenu('����ȭ�� ���ø�','design_main.php',$idx[1][1],1);
			noselectmenu('���θ� �ϴ� ���ø�','design_bottom.php',$idx[1][2],1);
			noselectmenu('��ǰ ī�װ� ���ø�','design_plist.php',$idx[1][3],1);
			noselectmenu('��ǰ ��ȭ�� ���ø�','design_pdetail.php',$idx[1][4],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblashop3 width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" onClick="ChangeMenu('shop3');" style="padding-left:20px;cursor:hand;" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">����������-����, ī�װ�</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblbshop3 style="display:none" width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" class="depth1_select" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop3');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">����������-����, ī�װ�</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td >
			<div id=shop3 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop3") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			//noselectmenu('��Ʈ�� ȭ�� �ٹ̱�','design_eachintropage.php',$idx[2][0],0);
			//noselectmenu('Ÿ��Ʋ �̹��� ����','design_eachtitleimage.php',$idx[2][1],1);
			noselectmenu('��ܸ޴� �ٹ̱�','design_eachtopmenu.php',$idx[2][0],1);
			noselectmenu('���ʸ޴� �ٹ̱�','design_eachleftmenu.php',$idx[2][1],1);
			noselectmenu('���� ���� �ٹ̱�','design_eachmain.php',$idx[2][2],1);
			noselectmenu('�ϴ�ȭ�� �ٹ̱�','design_eachbottom.php',$idx[2][3],1);
			//noselectmenu('�α����� �ٹ̱�','design_eachloginform.php',$idx[2][6],1);
			noselectmenu('��ǰ ī�װ� �ٹ̱�',"design_eachplist.php",$idx[2][4],2);
			//noselectmenu('��ǰ�� ȭ�� �ٹ̱�',"design_eachpdetail.php",$idx[2][8],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<!--
		<table cellpadding="0" cellspacing="0"  id=tblashop4 width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" onClick="ChangeMenu('shop4');" style="padding-left:20px;cursor:hand;" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">���ø�-������ ����</td>
		</tr>
		</table>
		-->
		<table cellpadding="0" cellspacing="0"  id=tblbshop4 style="display:none" width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34"  class="depth1_select" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop4');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">���ø�-������ ����</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td >
			<div id=shop4 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop4") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			noselectmenu('�±� ȭ�� ���ø�','design_tag.php',$idx[3][0],0);
			noselectmenu('��ǰ���� ȭ�� ���ø�','design_section.php',$idx[3][1],1);
			noselectmenu('��ǰ�˻� ���ȭ�� ���ø�','design_search.php',$idx[3][2],1);
			noselectmenu('�̿�ȳ� ȭ�� ���ø�','design_useinfo.php',$idx[3][3],1);
			noselectmenu('ȸ������ ȭ�� ���ø�','design_memberjoin.php',$idx[3][4],1);
			noselectmenu('ȸ������ ȭ�� ���ø�','design_usermodify.php',$idx[3][5],1);
			noselectmenu('�α��� ���� ȭ�� ���ø�','design_login.php',$idx[3][6],1);
			noselectmenu('��ٱ��� ȭ�� ���ø�','design_basket.php',$idx[3][7],1);
			noselectmenu('�ֹ��� ȭ�� ���ø�','design_order.php',$idx[3][8],1);
			noselectmenu('MYPAGE ȭ�� ���ø�','design_mypage.php',$idx[3][9],1);
			noselectmenu('�ֹ�����Ʈ ȭ�� ���ø�','design_orderlist.php',$idx[3][10],1);
			noselectmenu('WishList ȭ�� ���ø�','design_wishlist.php',$idx[3][11],1);
			noselectmenu('���� ȭ�� ���ø�','design_mycoupon.php',$idx[3][12],1);
			noselectmenu('������ ȭ�� ���ø�','design_myreserve.php',$idx[3][13],1);
			noselectmenu('1:1������ ȭ�� ���ø�','design_mypersonal.php',$idx[3][14],1);
			if(getVenderUsed()==true) { noselectmenu('�ܰ���� ȭ�� ���ø�','design_mycustsect.php',$idx[3][15],1); }
			//noselectmenu('���ϰ��� ȭ�� ���ø�','design_sendmail.php',$idx[3][16],1);
			noselectmenu('�������� �˾�â ���ø�','design_popupnotice.php',$idx[3][17],1);
			noselectmenu('���� �˾�â ���ø�','design_popupinfo.php',$idx[3][18],1);
			noselectmenu('������ ���ø�','design_formmail.php',$idx[3][19],1);
			noselectmenu('ī�����â �ΰ�','design_cardtopimg.php',$idx[3][20],1);
			noselectmenu('��ǰ�귣�� ȭ�� ���ø�','design_blist.php',$idx[3][21],1);
			noselectmenu('�귣��� ȭ�� ���ø�','design_bmap.php',$idx[3][22],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<!--
		<table cellpadding="0" cellspacing="0"  id=tblashop5 width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" onClick="ChangeMenu('shop5');" style="padding-left:20px;cursor:hand;" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">����������-������ ����</td>
		</tr>
		</table>
		-->
		<table cellpadding="0" cellspacing="0"  id=tblbshop5 style="display:none" idth="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34"  class="depth1_select" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop5');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">����������-������ ����</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td >
			<div id=shop5 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop5") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			noselectmenu('�ϴ� ���θ޴� ȭ�� �ٹ̱�',"design_eachbottomtools.php",$idx[4][33],0);
			noselectmenu('�±� ȭ�� �ٹ̱�',"design_eachtag.php",$idx[4][0],1);
			noselectmenu('��ǰ���� ȭ�� �ٹ̱�',"design_eachsection.php",$idx[4][1],1);
			noselectmenu('��ǰ�˻� ���ȭ�� �ٹ̱�',"design_eachsearch.php",$idx[4][2],1);
			noselectmenu('��ٱ��� ȭ�� �ٹ̱�',"design_eachbasket.php",$idx[4][3],1);
			//noselectmenu('��ǰ�̹��� Ȯ��â �ٹ̱�',"design_eachprimageview.php",$idx[4][4],1);
			//noselectmenu('�������� �˾�â �ٹ̱�','design_eachpopupnotice.php',$idx[4][5],1);
			//noselectmenu('���� �˾�â �ٹ̱�','design_eachpopupinfo.php',$idx[4][6],1);
			noselectmenu('���� ȭ�� �ٹ̱�','design_eachsendmail.php',$idx[4][7],1);
			noselectmenu('������ ȭ�� �ٹ̱�','design_eachformmail.php',$idx[4][8],1);
			noselectmenu('�Խ��� ��� ȭ�� �ٹ̱�','design_eachboardtop.php',$idx[4][9],1);
			noselectmenu('�̿�ȳ� ȭ�� �ٹ̱�','design_eachuseinfo.php',$idx[4][10],1);
			noselectmenu('�̿��� ȭ�� �ٹ̱�','design_eachagreement.php',$idx[4][11],1);
			noselectmenu('ȸ������ ���ȭ�� �ٹ̱�','design_eachjoinagree.php',$idx[4][12],1);
			noselectmenu('ȸ������ �Է��� �ٹ̱�','design_eachmemberjoin.php',$idx[4][13],1);
			noselectmenu('ȸ������ȭ�� �ٹ̱�','design_eachusermodify.php',$idx[4][14],1);
			noselectmenu('ȸ��IDüũ ȭ�� �ٹ̱�','design_eachiddup.php',$idx[4][15],1);
			noselectmenu('�н����� �н� ȭ�� �ٹ̱�','design_eachfindpwd.php',$idx[4][16],1);
			noselectmenu('�α��� ȭ�� �ٹ̱�','design_eachlogin.php',$idx[4][17],1);
			noselectmenu('ȸ��Ż�� ȭ�� �ٹ̱�','design_eachmemberout.php',$idx[4][18],1);
			noselectmenu('MYPAGE ȭ�� �ٹ̱�',"design_eachmypage.php",$idx[4][19],1);
			noselectmenu('�ֹ�����Ʈ ȭ�� �ٹ̱�',"design_eachorderlist.php",$idx[4][20],1);
			noselectmenu('WishList ȭ�� �ٹ̱�',"design_eachwishlist.php",$idx[4][21],1);
			noselectmenu('���� ȭ�� �ٹ̱�',"design_eachmycoupon.php",$idx[4][22],1);
			noselectmenu('������ ȭ�� �ٹ̱�',"design_eachmyreserve.php",$idx[4][23],1);
			noselectmenu('1:1������ ȭ�� �ٹ̱�',"design_eachmypersonal.php",$idx[4][24],1);
			if(getVenderUsed()==true) { noselectmenu('�ܰ���� ȭ�� �ٹ̱�',"design_eachmycustsect.php",$idx[4][25],1); }
			noselectmenu('��ǥ����Ʈ ȭ�� �ٹ̱�','design_eachsurveylist.php',$idx[4][26],1);
			noselectmenu('��ǥ��� ȭ�� �ٹ̱�','design_eachsurveyview.php',$idx[4][27],1);
			//noselectmenu('��ǰ���� ����â �ٹ̱�','design_eachreviewpopup.php',$idx[4][28],1);
			noselectmenu('������� ȭ�� �ٹ̱�','design_eachreviewall.php',$idx[4][29],1);
			noselectmenu('RSS ȭ�� �ٹ̱�','design_eachrssinfo.php',$idx[4][30],1);
			noselectmenu('��ǰ�귣�� ȭ�� �ٹ̱�',"design_eachblist.php",$idx[4][31],1);
			noselectmenu('�귣��� ȭ�� �ٹ̱�',"design_eachbmap.php",$idx[4][32],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblashop6 width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" onClick="ChangeMenu('shop6');" style="padding-left:20px;cursor:hand;"class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">���� �߰������� ����</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0"  id=tblbshop6 style="display:none"width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34"  class="depth1_select" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop6');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">���� �߰������� ����</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td >
			<div id=shop6 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop6") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			noselectmenu('�Ϲ������� �ٹ̱�','design_newpage.php',$idx[5][0],0);
			noselectmenu('Ŀ�´�Ƽ ������ �ٹ̱�','design_community.php',$idx[5][1],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<!--
		<table cellpadding="0" cellspacing="0"  id=tblashop7 width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" onClick="ChangeMenu('shop7');" style="padding-left:20px;cursor:hand;"class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">Easy������ ����</td>
		</tr>
		</table>
		-->

		<table cellpadding="0" cellspacing="0"  id=tblbshop7 style="display:none"width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34"  class="depth1_select" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop7');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">Easy������ ����</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td >
			<div id=shop7 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop7") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			noselectmenu('Easy ��� �޴� ����','design_easytop.php',$idx[6][0],0);
			noselectmenu('Easy ���� �޴� ����','design_easyleft.php',$idx[6][1],1);
			noselectmenu('Easy �ؽ�Ʈ �Ӽ� ����','design_easycss.php',$idx[6][0],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<!--
		<table cellpadding="0" cellspacing="0"  id=tblashop8 width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34" onClick="ChangeMenu('shop8');" style="padding-left:20px;cursor:hand;"class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">������ ��� ����</td>
		</tr>
		</table>
		-->
		<table cellpadding="0" cellspacing="0"  id=tblbshop8 style="display:none"width="100%">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td  height="34"  class="depth1_select" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop8');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">������ ��� ����</td>
		</tr>
		</table>
		<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td >
			<div id=shop8 style="margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;padding:0;">
			<table width="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop8") {
				echo "<tr><td height=\"1\" ></td></tr>";
			}
			noselectmenu('����ϱ�','design_backup.php',$idx[7][0],0);
			noselectmenu('�����ϱ�','design_backup_revert.php',$idx[7][1],1);
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
</DIV>
</div>
<script>
InitMenu('<?=$menuidx?>');
</script>
<script type="text/javascript" src="move_menu.js.php"></script>