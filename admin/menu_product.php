<?
switch(substr(strrchr(getenv("SCRIPT_NAME"),"/"),1)) {
	case "product_code.php":
		$menuidx = "shop1"; $idx[0][0] = 'YES'; break;
	case "product_register.php":
		$menuidx = "shop1"; $idx[0][1] = 'YES'; break;
	/*case "product_assemble.php":
		$menuidx = "shop1"; $idx[0][2] = 'YES'; break;*/
	case "product_mainlist.php":
		$menuidx = "shop1"; $idx[0][2] = 'YES'; break;
	case "product_codelist.php":
		$menuidx = "shop1"; $idx[0][3] = 'YES'; break;
	case "product_sort.php":
		$menuidx = "shop1"; $idx[0][4] = 'YES'; break;
	case "product_copy.php":
		$menuidx = "shop1"; $idx[0][5] = 'YES'; break;
	case "product_theme.php":
		$menuidx = "shop1"; $idx[0][6] = 'YES'; break;
	case "product_detaillist.php":
		$menuidx = "shop1"; $idx[0][7] = 'YES'; break;
	case "product_deliinfo.php":
		$menuidx = "shop1"; $idx[0][8] = 'YES'; break;
	case "product_brand.php":
		$menuidx = "shop1"; $idx[0][9] = 'YES'; break;
	case "product_business.php":
		$menuidx = "shop1"; $idx[0][10] = 'YES'; break;
	case "product2_register.php":
		$menuidx = "shop1"; $idx[0][11] = 'YES'; break;
	case "product_latestup.php":
		$menuidx = "shop1"; $idx[0][12] = 'YES'; break;
	case "product_latestsell.php":
		$menuidx = "shop1"; $idx[0][13] = 'YES'; break;
	case "product_listsearch.php":
		$menuidx = "shop1"; $idx[0][14] = 'YES'; break;
	case "product_allcategory.php":		// �߰� �޴�
		$menuidx = "shop1"; $idx[0][15] = 'YES'; break;

/*	case "product_imgmulticonfig.php":
		$menuidx = "shop2"; $idx[1][0] = 'YES'; break;
	case "product_imgmultiset.php":
		$menuidx = "shop2"; $idx[1][1] = 'YES'; break;*/

	case "product_collectionconfig.php":
		$menuidx = "shop3"; $idx[2][0] = 'YES'; break;
	case "product_collectionlist.php":
		$menuidx = "shop3"; $idx[2][1] = 'YES'; break;

	case "product_allupdate.php":
		$menuidx = "shop4"; $idx[3][0] = 'YES'; break;
	case "product_reserve.php":
		$menuidx = "shop4"; $idx[3][1] = 'YES'; break;
	case "product_price.php":
		$menuidx = "shop4"; $idx[3][2] = 'YES'; break;
	case "product_allsoldout.php":
		$menuidx = "shop4"; $idx[3][3] = 'YES'; break;
	case "product_allquantity.php":
		$menuidx = "shop4"; $idx[3][4] = 'YES'; break;
	case "product_excelupload.php":
		$menuidx = "shop4"; $idx[3][5] = 'YES'; break;
	/*case "product_exceldownload.php":
		$menuidx = "shop4"; $idx[3][6] = 'YES'; break;*/

	case "product_giftlist.php":
		$menuidx = "shop5"; $idx[4][0] = 'YES'; break;
	case "product_estimate.php":
		$menuidx = "shop5"; $idx[4][1] = 'YES'; break;
	case "product_review.php":
		$menuidx = "shop5"; $idx[4][2] = 'YES'; break;
	case "product_wishlist.php":
		$menuidx = "shop5"; $idx[4][3] = 'YES'; break;
	case "product_keywordsearch.php":
		$menuidx = "shop5"; $idx[4][4] = 'YES'; break;
	case "product_detailfilter.php":
		$menuidx = "shop5"; $idx[4][5] = 'YES'; break;

	case "product_option.php":
		$menuidx = "shop6"; $idx[5][0] = 'YES'; break;

	case "product_package.php":
		$menuidx = "shop7"; $idx[6][0] = 'YES'; break;
		/*
	case "scheduled_delivery.php":
		$menuidx = "shop8";
		switch($_REQUEST['mode']){
			case 'config':
				$idx[7][2] = 'YES'; break;
			case 'modify':
			case 'new':
				$idx[7][1] = 'YES'; break;
			default:
				$idx[7][0] = 'YES'; break;
		}
		break;
*/
	case "product_rental.booking.php":
		$menuidx = "shop10"; $idx[10][0] = 'YES'; break;
	case "product_rental.product.list.php":
		$menuidx = "shop10"; $idx[10][1] = 'YES'; break;
	case "product_rental.local.php":
		$menuidx = "shop10"; $idx[10][2] = 'YES'; break;
/*
	case "product_rent.Refund.php":
		$menuidx = "shop10"; $idx[10][3] = 'YES'; break;
	case "product_rent.long.php":
		$menuidx = "shop10"; $idx[10][4] = 'YES'; break;*/
	case "product_rent.season.php":
		$menuidx = "shop10"; $idx[10][5] = 'YES'; break;

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
	echo "	<td height=\"19\" style=\"padding-left:33px;\" class=\"".$str_style_class."\"><img src=\"images/icon_leftmenu1.gif\" border=\"0\"><a href=\"".$url."\">".$name."</a></td>\n";
	echo "</tr>\n";
	if($end==2 || $end==3){
		echo "<tr><td height=\"25\" ></td></tr>";
	}
}
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
//layerlist = new Array ('shop1','shop2','shop3','shop4','shop5','shop6','shop8','shop10');
//layerlist = new Array ('shop1','shop3','shop5','shop6','shop10');
layerlist = new Array ('shop1','shop4','shop5','shop6','shop10');
var thisshop="<?=$menuidx?>";
ino=5;

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
	<TD height="68" align="right" valign="top" background="images/product_leftmenu_title.gif" ><a href="javascript:scrollMove(0);"><img src="images/leftmenu_stop.gif" border="0" id="menu_pix"></a><a href="javascript:scrollMove(1);"><img src="images/leftmenu_trans.gif" border="0" hspace="2" id="menu_scroll"></a></TD>
</TR>
<TR>
	<TD background="images/leftmenu_bg.gif">
	<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
	<col width="16"></col>
	<col></col>
	<col width="16"></col>
	<TR>
		<TD valign="top">
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblashop1">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop1');" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">ī�װ�/��ǰ����</td>
		</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblbshop1" style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34"  style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop1');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">ī�װ�/��ǰ����</td>
		</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<div id="shop1" style="display:none;">
			<table WIDTH="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx && $menuidx != "shop1") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			noselectmenu('ī�װ� ����','product_code.php',$idx[0][0],0);
			//noselectmenu('ī�װ� �������','product_allcategory.php',$idx[0][15],1);
			noselectmenu('��ǰ ���/����/����','product_register.php',$idx[0][1],1);
			//noselectmenu('�ڵ�/���� ��ǰ ����','product_assemble.php',$idx[0][2],1);

			noselectmenu('���λ�ǰ ��������','product_mainlist.php',$idx[0][2],1);
			noselectmenu('ī�װ� ��ǰ ��������','product_codelist.php',$idx[0][3],1);
			noselectmenu('��ǰ �������� ����','product_sort.php',$idx[0][4],1);
			noselectmenu('��ǰ �̵�/����/����','product_copy.php',$idx[0][5],1);
			noselectmenu('���� ī�װ� ��ǰ����','product_theme.php',$idx[0][6],1);
			noselectmenu('��ǰ ���� �������','product_detaillist.php',$idx[0][7],1);
			noselectmenu('��ȯ/���/ȯ������ ����',"product_deliinfo.php",$idx[0][8],1);
			noselectmenu('��ǰ �귣�� ���� ����',"product_brand.php",$idx[0][9],1);
			noselectmenu('��ǰ �ŷ�ó ����',"product_business.php",$idx[0][10],1);
			noselectmenu('��ǰ�� ���/����/����',"product2_register.php",$idx[0][11],1);
			noselectmenu('�ֱٵ�ϻ�ǰ',"product_latestup.php",$idx[0][12],1);
			noselectmenu('�ֱ��ǸŻ�ǰ',"product_latestsell.php",$idx[0][13],1);
			noselectmenu('��ϵ� ��ǰ ��ȸ',"product_listsearch.php",$idx[0][14],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>
		
		<? /*		
		
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblashop8">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop8');" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">�����ۻ�ǰ����</td>
		</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblbshop8" style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34"  style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop8');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">�����ۻ�ǰ����</td>
		</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<div id="shop8" style="display:none;">
			<table WIDTH="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop8") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			noselectmenu('������ ��ǰ���','scheduled_delivery.php',$idx[7][0],0); 
			noselectmenu('������ ��ǰ���','scheduled_delivery.php?mode=new',$idx[7][1],1);
			noselectmenu('������ ����','scheduled_delivery.php?mode=config',$idx[7][2],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>
		
		

		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblashop2">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop2');" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">�����̹��� ����</td>
		</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblbshop2" style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34"  style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop2');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">�����̹��� ����</td>
		</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<div id="shop2" style="display:none;">
			<table WIDTH="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop2") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			noselectmenu('��ǰ �����̹��� ����','product_imgmulticonfig.php',$idx[1][0],0);
			noselectmenu('��ǰ �����̹��� ���/����','product_imgmultiset.php',$idx[1][1],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table> */ ?>

		<!--
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblashop3">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop3');" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">���û�ǰ ����</td>
		</tr>
		</table>-->
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblbshop3" style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34"  style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop3');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">���û�ǰ ����</td>
		</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<div id="shop3" style="display:none;">
			<table WIDTH="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop3") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			//noselectmenu('���û�ǰ ������� ����','product_collectionconfig.php',$idx[2][0],0);
			noselectmenu('���û�ǰ �˻�/���','product_collectionlist.php',$idx[2][1],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>


		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblashop4">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop4');" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">��ǰ �ϰ�����</td>
		</tr>
		</table>
		
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblbshop4" style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34"  style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop4');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">��ǰ �ϰ�����</td>
		</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<div id="shop4" style="display:none;">
			<table WIDTH="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop4") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			//noselectmenu('��ǰ �ϰ� �������','product_allupdate.php',$idx[3][0],0);
			//noselectmenu('��ǰ ������ �ϰ�����','product_reserve.php',$idx[3][1],1);
			//noselectmenu('�ǸŻ�ǰ ���� �ϰ�����','product_price.php',$idx[3][2],1);
			//noselectmenu('ǰ����ǰ �ϰ� ����/����','product_allsoldout.php',$idx[3][3],1);
			//noselectmenu('����ǰ �ϰ�����','product_allquantity.php',$idx[3][4],2);
			noselectmenu('��ǰ���� �ϰ� ���','product_excelupload.php',$idx[3][5],1);
			//noselectmenu('��ǰ ���� �ٿ�ε�','product_exceldownload.php',$idx[3][6],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblashop5">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop5');" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">�������</td>
		</tr>
		</table>
		
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblbshop5" style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34"  style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop5');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">�������</td>
		</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<div id="shop5" style="display:none;">
			<table WIDTH="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop5") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			//noselectmenu('����ǰ ���� ����','product_giftlist.php',$idx[4][0],0);
			//noselectmenu('������ ��ǰ ���/����','product_estimate.php',$idx[4][1],1);
			noselectmenu('��ǰ ���� ����','product_review.php',$idx[4][2],1);
			//noselectmenu('Wishlist ��ǰ ����','product_wishlist.php',$idx[4][3],1);
			//noselectmenu('��ǰ Ű���� �˻�','product_keywordsearch.php',$idx[4][4],1);
			//noselectmenu('��ǰ�󼼳��� �ܾ� ���͸�','product_detailfilter.php',$idx[4][5],2);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>

		<!--
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblashop6">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop6');" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">�ɼǱ׷� ��� ����</td>
		</tr>
		</table>
		-->
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblbshop6" style="display:none">
		<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
		<tr>
			<td height="34"  style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop6');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">�ɼǱ׷� ��� ����</td>
		</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td>
			<div id="shop6" style="display:none;">
			<table WIDTH="100%" cellpadding="0" cellspacing="0">
<?
			if($menuidx != "shop6") {
				echo "<tr><td height=\"1\"></td></tr>";
			}
			noselectmenu('�ɼǱ׷� ��� ����','product_option.php',$idx[5][0],3);
?>
			</table>
			</div>
			</td>
		</tr>
		</table>


		<? /* �뿩 ���� */ ?>
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblashop10">
			<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
			<tr>
				<td height="34" style="padding-left:20px;cursor:hand;" onClick="ChangeMenu('shop10');" class="depth1_noselect"><img src="images/icon_leftmenu.gif" border="0" align="absmiddle"  style="margin-right:4px;margin-bottom:2px">����/��Ż ����</td>
			</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0" id="tblbshop10" style="display:none">
			<tr><td height="3" background="images/leftmenu_line.gif"></td></tr>
			<tr>
				<td height="34"  style="padding-left:20px;cursor:hand;" class="depth1_select" onClick="ChangeMenu('shop10');"><img src="images/icon_leftmenu_select.gif"  align="absmiddle" style="margin-right:4px;margin-bottom:2px">����/��Ż ����</td>
			</tr>
		</table>
		<table WIDTH="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<div id="shop10" style="display:none;">
						<table WIDTH="100%" cellpadding="0" cellspacing="0">
							<?
							if($menuidx != "shop10") {
								echo "<tr><td height=\"1\"></td></tr>";
							}
							noselectmenu('����/��Ż ��Ȳ','product_rental.booking.php',$idx[10][0],0);
							noselectmenu('����/��Ż �˻�','product_rental.product.list.php',$idx[10][1],1);
							noselectmenu('��Ż���/����� ����','product_rental.local.php',$idx[10][2],1);
							/*
							noselectmenu('���� ����','product_rental.booking.list.php',$idx[10][2],1);
							noselectmenu('ȯ�� ��å','product_rent.Refund.php',$idx[10][3],1);
							noselectmenu('��ⷻŻ ������å','product_rent.long.php',$idx[10][4],1);
							*/
							noselectmenu('���ϰ���','product_rent.season.php',$idx[10][5],2);
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

<script>InitMenu('<?=$menuidx?>');</script>
<script type="text/javascript" src="move_menu.js.php"></script>