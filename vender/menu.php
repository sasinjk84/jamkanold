<?
include_once($Dir."lib/admin_more.php");

$_dataShopMoreInfo = getShopMoreInfo();
$coupon_use = $_dataShopMoreInfo['coupon_use'];

if ($coupon_use =="1") {
	switch(substr(strrchr(getenv("SCRIPT_NAME"),"/"),1)) {
		case "vender_info.php":
			$menuidx = "shop1"; $idx[0][0] = 'YES'; break;
		case "delivery_info.php":
			$menuidx = "shop1"; $idx[0][1] = 'YES'; break;
		case "product_deliinfo.php":
			$menuidx = "shop1"; $idx[0][2] = 'YES'; break;
		case "trust_list.php":
			$menuidx = "shop1"; $idx[0][3] = 'YES'; break;

		case "minishop_info.php":
			$menuidx = "shop2"; $idx[1][0] = 'YES'; break;
		case "minishop_design.php":
			$menuidx = "shop2"; $idx[1][1] = 'YES'; break;
		case "cust_info.php":
			$menuidx = "shop2"; $idx[1][2] = 'YES'; break;
		case "themecode_manager.php":
			$menuidx = "shop2"; $idx[1][3] = 'YES'; break;
		case "themecode_prdtin.php":
			$menuidx = "shop2"; $idx[1][4] = 'YES'; break;
		case "main_design.php":
			$menuidx = "shop2"; $idx[1][5] = 'YES'; break;
		case "code_design.php":
			$menuidx = "shop2"; $idx[1][6] = 'YES'; break;
		case "main_topdesign.php":
			$menuidx = "shop2"; $idx[1][7] = 'YES'; break;
		case "code_topdesign.php":
			$menuidx = "shop2"; $idx[1][8] = 'YES'; break;
		case "minishop_notice.php":
			$menuidx = "shop2"; $idx[1][9] = 'YES'; break;

		case "product_register.php":
			$menuidx = "shop3"; $idx[2][0] = 'YES'; break;
		case "product_myprd.php":
		case "product_prdmodify.php":
			$menuidx = "shop3"; $idx[2][1] = 'YES'; break;
		/*case "product_imgmultiset.php":
			$menuidx = "shop3"; $idx[2][2] = 'YES'; break;
		case "product_excelupdate.php":
			$menuidx = "shop3"; $idx[2][3] = 'YES'; break;
		case "product_allupdate.php":
			$menuidx = "shop3"; $idx[2][4] = 'YES'; break;*/

		case "order_list.php":
			$menuidx = "shop4"; $idx[3][0] = 'YES'; break;			
			
		/*case "order_csvdelivery.php":
			$menuidx = "shop4"; $idx[3][1] = 'YES'; break;*/
		case "order_qna.php":
		case "order_qnaview.php":
			$menuidx = "shop4"; $idx[3][1] = 'YES'; break;
		case "order_review.php":
			$menuidx = "shop4"; $idx[3][2] = 'YES'; break;
		case "order_cs.php":
			$menuidx = "shop4"; $idx[3][3] = 'YES'; break;
		case "order_cs_view.php":
			$menuidx = "shop4"; $idx[3][4] = 'YES'; break;
		case "order_reservation.php":
			$menuidx = "shop4"; $idx[3][5] = 'YES'; break;
		case "rental_schedule_list.php": // �ű� �߰�
			$menuidx = "shop4"; $idx[3][6] = 'YES'; break;
		case "rental_order_list.php": // �ű� �߰�
			$menuidx = "shop4"; $idx[3][7] = 'YES'; break;

		case "sellstat_list.php":
			$menuidx = "shop5"; $idx[4][0] = 'YES'; break;
		case "sellstat_sale.php":
			$menuidx = "shop5"; $idx[4][1] = 'YES'; break;
		case "sellstat_calendar.php":
			$menuidx = "shop5"; $idx[4][2] = 'YES'; break;

		case "shop_notice.php":
			$menuidx = "shop6"; $idx[5][0] = 'YES'; break;
		case "shop_counsel.php":
			$menuidx = "shop6"; $idx[5][1] = 'YES'; break;

		case "coupon_new.php":
			$menuidx = "shop7"; $idx[6][0] = 'YES'; break;
		case "coupon_supply.php":
			$menuidx = "shop7"; $idx[6][1] = 'YES'; break;
		case "coupon_list.php":
			$menuidx = "shop7"; $idx[6][2] = 'YES'; break;

	}
}else{

	switch(substr(strrchr(getenv("SCRIPT_NAME"),"/"),1)) {
		case "vender_info.php":
			$menuidx = "shop1"; $idx[0][0] = 'YES'; break;
		case "delivery_info.php":
			$menuidx = "shop1"; $idx[0][1] = 'YES'; break;
		case "product_deliinfo.php":
			$menuidx = "shop1"; $idx[0][2] = 'YES'; break;
		case "trust_list.php":
			$menuidx = "shop1"; $idx[0][3] = 'YES'; break;

		case "minishop_info.php":
			$menuidx = "shop2"; $idx[1][0] = 'YES'; break;
		case "minishop_design.php":
			$menuidx = "shop2"; $idx[1][1] = 'YES'; break;
		case "cust_info.php":
			$menuidx = "shop2"; $idx[1][2] = 'YES'; break;
		case "themecode_manager.php":
			$menuidx = "shop2"; $idx[1][3] = 'YES'; break;
		case "themecode_prdtin.php":
			$menuidx = "shop2"; $idx[1][4] = 'YES'; break;
		case "main_design.php":
			$menuidx = "shop2"; $idx[1][5] = 'YES'; break;
		case "code_design.php":
			$menuidx = "shop2"; $idx[1][6] = 'YES'; break;
		case "main_topdesign.php":
			$menuidx = "shop2"; $idx[1][7] = 'YES'; break;
		case "code_topdesign.php":
			$menuidx = "shop2"; $idx[1][8] = 'YES'; break;
		case "minishop_notice.php":
			$menuidx = "shop2"; $idx[1][9] = 'YES'; break;

		case "product_register.php":
			$menuidx = "shop3"; $idx[2][0] = 'YES'; break;
		case "product_myprd.php":
		case "product_prdmodify.php":
			$menuidx = "shop3"; $idx[2][1] = 'YES'; break;
		case "product_imgmultiset.php":
			$menuidx = "shop3"; $idx[2][2] = 'YES'; break;
		/*case "product_excelupdate.php":
			$menuidx = "shop3"; $idx[2][3] = 'YES'; break;
		case "product_allupdate.php":
			$menuidx = "shop3"; $idx[2][4] = 'YES'; break;*/

		case "order_list.php":
			$menuidx = "shop4"; $idx[3][0] = 'YES'; break;
		/*case "order_csvdelivery.php":
			$menuidx = "shop4"; $idx[3][1] = 'YES'; break;*/
		case "order_qna.php":
			$menuidx = "shop4"; $idx[3][1] = 'YES'; break;
		case "order_qnaview.php":
			$menuidx = "shop4"; $idx[3][1] = 'YES'; break;
		case "order_review.php":
			$menuidx = "shop4"; $idx[3][2] = 'YES'; break;
		case "order_cs.php":
			$menuidx = "shop4"; $idx[3][3] = 'YES'; break;
		case "order_cs_view.php":
			$menuidx = "shop4"; $idx[3][4] = 'YES'; break;
		case "order_reservation.php":
			$menuidx = "shop4"; $idx[3][5] = 'YES'; break;
		case "rental_schedule_list.php": // �ű� �߰�
			$menuidx = "shop4"; $idx[3][6] = 'YES'; break;
		case "rental_order_list.php": // �ű� �߰�
			$menuidx = "shop4"; $idx[3][7] = 'YES'; break;
		

		case "sellstat_list.php":
			$menuidx = "shop5"; $idx[4][0] = 'YES'; break;
		case "sellstat_sale.php":
			$menuidx = "shop5"; $idx[4][1] = 'YES'; break;
		case "sellstat_calendar.php":
			$menuidx = "shop5"; $idx[4][2] = 'YES'; break;

		case "shop_notice.php":
			$menuidx = "shop6"; $idx[5][0] = 'YES'; break;
		case "shop_counsel.php":
			$menuidx = "shop6"; $idx[5][1] = 'YES'; break;

	}
}

function noselectmenu($name,$url,$idx,$end){
	$str_name = $name;
	if ($idx == "YES") {
		$str_name = "<font color=#FF6000>".$name."</font>";
	}
	echo "<tr>\n";
	echo "	<td width=8><img src=images/icon_dot01.gif border=0 align=absmiddle></td>\n";
	echo "	<td><a href=\"".$url."\">".$str_name."</a></b></td>\n";
	echo "</tr>\n";
	if($end==2 || $end==3){
		echo "<tr><td colspan=2 height=8></td></tr>";
	}
}

?>

<SCRIPT LANGUAGE="JavaScript">
<!--
<? if ($coupon=="1") { ?>
layerlist = new Array ('shop1','shop2','shop3','shop4','shop5','shop6','shop7');
var thisshop="<?=$menuidx?>";
ino=7;
<? }else {?>
layerlist = new Array ('shop1','shop2','shop3','shop4','shop5','shop6');
var thisshop="<?=$menuidx?>";
ino=6;
<? } ?>

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
		if(document.all){
			document.all(shop).style.display="block";
		} else if(document.getElementById){
			document.getElementById(shop).style.display="block";
		} else if(document.layers){
			document.layers[shop].display="block";
		}
	} catch (e) {
/*
		shop = "shop1";
		if(document.all){
			document.all(shop).style.display="block";
		} else if(document.getElementById){
			document.getElementById(shop).style.display="block";
		} else if(document.layers){
			document.layers[shop].display="block";
		}
*/
	}
}
//-->
</SCRIPT>

<table width=100%  border="0" cellpadding="0" cellspacing="0" >
<tr>
	<td height=100%>
	<table width=100%  border="0" cellpadding="0" cellspacing="0" >
	<tr>
		<td valign=top background="images/minishop_leftbg.gif" height=100%>
		<table border=0 cellpadding=0 cellspacing=0 width=100%>
		<tr>
			<td><img src="images/menu_topa.gif" border=0></td>
		</tr>
		<tr>
			<td><A HREF="main.php"><img src="images/menu_top.gif" border=0></A></td>
		</tr>
		</table>

		<table width=100% border=0 cellspacing=0 cellpadding=0  align="center">
		<tr height=30 style="padding-left:8px">
			<td style="padding-left:20px"><a href="javascript:ChangeMenu('shop1');"><img src=images/icon_cross01.gif border=0 align=absmiddle> <?=(isset($idx[0])==true?"<font color=#FF6000>":"")?><B>��ü���� ����</B></font></a></b></td>
		</tr>
		<tr id=shop1 style='display:none'>
			<td valign=top style=background-repeat:no-repeat;padding-top:10;padding-left:20;padding-bottom:10px >
			<table border=0 cellspacing=0 cellpadding=0 class=font_gray1>
<?
			noselectmenu('��ü���� ����','vender_info.php',$idx[0][0],0);
			noselectmenu('��۰��� ��ɼ���','delivery_info.php',$idx[0][1],1);
			noselectmenu('���/��ȯ/ȯ������ ����','product_deliinfo.php',$idx[0][2],1);
			noselectmenu('��Ź��� ����','trust_list.php',$idx[0][3],1);
?>
			</table>
			</td>
		</tr>
		</table>

		<table width=100% border=0 cellspacing=0 cellpadding=0  align="center">
		<tr><td height=2 background=images/icon_line01.gif></td></tr>
		<tr height=30 style="padding-left:8px">
			<td style="padding-left:20px"><a href="javascript:ChangeMenu('shop2');"><img src=images/icon_cross01.gif border=0 align=absmiddle> <?=(isset($idx[1])==true?"<font color=#FF6000>":"")?><B>�̴ϼ� � ����</B></font></a></b></td>
		</tr>
		<tr id=shop2 style='display:none'>
			<td valign=top style=background-repeat:no-repeat;padding-top:10;padding-left:20;padding-bottom:10px >
			<table border=0 cellspacing=0 cellpadding=0 class=font_gray1>
<?
			/*
			noselectmenu('�̴ϼ� �⺻���� ����','minishop_info.php',$idx[1][0],0);
			noselectmenu('�̴ϼ� �����ΰ���','minishop_design.php',$idx[1][1],1);
			noselectmenu('������ ��������','cust_info.php',$idx[1][2],1);
			noselectmenu('�׸� ī�װ� ����','themecode_manager.php',$idx[1][3],1);
			noselectmenu('�׸� ī�װ� ��ǰ����','themecode_prdtin.php',$idx[1][4],1);
			noselectmenu('���� ȭ�� ����','main_design.php',$idx[1][5],1);
			noselectmenu('��з� ȭ�� ����','code_design.php',$idx[1][6],1);
			noselectmenu('���� ���/�̺�Ʈ ����','main_topdesign.php',$idx[1][7],1);
			noselectmenu('��з� ���/�̺�Ʈ ����','code_topdesign.php',$idx[1][8],1);
			*/
			noselectmenu('�̴ϼ� �������� ����','minishop_notice.php',$idx[1][9],0);
			noselectmenu('�̴ϼ� �����ΰ���','minishop_design.php',$idx[1][1],1);
			/* noselectmenu('���� ȭ�� ����','main_design.php',$idx[1][5],1); */
?>
			</table>
			</td>
		</tr>
		</table>

		<table width=100% border=0 cellspacing=0 cellpadding=0  align="center">
		<tr><td height=2 background=images/icon_line01.gif></td></tr>
		<tr height=30 style="padding-left:8px">
			<td style="padding-left:20px"><a href="javascript:ChangeMenu('shop3');"><img src=images/icon_cross01.gif border=0 align=absmiddle> <?=(isset($idx[2])==true?"<font color=#FF6000>":"")?><B>�ǸŻ�ǰ ����</B></font></a></b></td>
		</tr>
		<tr id=shop3 style='display:none'>
			<td valign=top style=background-repeat:no-repeat;padding-top:10;padding-left:20;padding-bottom:10px >
			<table border=0 cellspacing=0 cellpadding=0 class=font_gray1>
<?
			noselectmenu('��ǰ �űԵ��','product_register.php',$idx[2][0],0);
			noselectmenu('�� ��ǰ ����','product_myprd.php',$idx[2][1],1);
			/*noselectmenu('�����̹��� ���/����','product_imgmultiset.php',$idx[2][2],1);
			noselectmenu('��ǰ �ϰ� ���','product_excelupdate.php',$idx[2][3],1);
			noselectmenu('��ǰ �ϰ� �������','product_allupdate.php',$idx[2][4],2);*/
?>
			</table>
			</td>
		</tr>
		</table>

		<table width=100% border=0 cellspacing=0 cellpadding=0  align="center">
		<tr><td height=2 background=images/icon_line01.gif></td></tr>
		<tr height=30 style="padding-left:8px">
			<td style="padding-left:20px"><a href="javascript:ChangeMenu('shop4');"><img src=images/icon_cross01.gif border=0 align=absmiddle> <?=(isset($idx[3])==true?"<font color=#FF6000>":"")?><B>�ֹ�/��� ����</B></font></a></b></td>
		</tr>
		<tr id=shop4 style='display:none'>
			<td valign=top style="background-repeat:no-repeat;padding-top:10;padding-left:20;padding-bottom:10px" >
			<table border=0 cellspacing=0 cellpadding=0 class=font_gray1>
<?
			noselectmenu('�ֹ���ȸ/���','order_list.php',$idx[3][0],0);
			noselectmenu('������Ȳ','rental_schedule_list.php',$idx[3][6],1);

			/*
			if($_SERVER['REMOTE_ADDR'] == '218.233.160.229'){
				noselectmenu('�����ֹ�����','rental_order_list.php',$idx[3][7],1);
			}
			noselectmenu('�ֹ�����Ʈ�ϰ���� ����','order_csvdelivery.php',$idx[3][1],1);
			*/

			noselectmenu('��ǰ Q&A ����','order_qna.php',$idx[3][1],1);
			noselectmenu('��ǰ ����ı� ����','order_review.php',$idx[3][2],1);
			noselectmenu('��ǰ CS ����','order_cs.php',$idx[3][3],2);
			
	//		noselectmenu('���� �Ǹ� �ֹ� ����','order_reservation.php',$idx[3][5],2);
?>
			</table>
			</td>
		</tr>
		</table>

		<table width=100% border=0 cellspacing=0 cellpadding=0  align="center">
		<tr><td height=2 background=images/icon_line01.gif></td></tr>
		<tr height=30 style="padding-left:8px">
			<td style="padding-left:20px"><a href="javascript:ChangeMenu('shop5');"><img src=images/icon_cross01.gif border=0 align=absmiddle> <?=(isset($idx[4])==true?"<font color=#FF6000>":"")?><B>����/���� ����</B></font></a></b></td>
		</tr>
		<tr id=shop5 style='display:none'>
			<td valign=top style=background-repeat:no-repeat;padding-top:10;padding-left:20;padding-bottom:10px >
			<table border=0 cellspacing=0 cellpadding=0 class=font_gray1>
<?
			noselectmenu('�ǸŻ�ǰ ������ȸ','sellstat_list.php',$idx[4][0],0);
			noselectmenu('������ǰ ����м�','sellstat_sale.php',$idx[4][1],1);
			noselectmenu('���� Ķ����','sellstat_calendar.php',$idx[4][2],2);
?>
			</table>
			</td>
		</tr>
		</table>

		<table width=100% border=0 cellspacing=0 cellpadding=0  align="center">
		<tr><td height=2 background=images/icon_line01.gif></td></tr>
		<tr height=30 style="padding-left:8px">
			<td style="padding-left:20px"><a href="javascript:ChangeMenu('shop6');"><img src=images/icon_cross01.gif border=0 align=absmiddle> <?=(isset($idx[6])==true?"<font color=#FF6000>":"")?><B>���� Ŀ�´�Ƽ</B></font></a></b></td>
		</tr>
		<tr id=shop6 style='display:none'>
			<td valign=top style=background-repeat:no-repeat;padding-top:10;padding-left:20;padding-bottom:10px >
			<table border=0 cellspacing=0 cellpadding=0 class=font_gray1>
<?
			noselectmenu('���� ��������','shop_notice.php',$idx[5][0],0);
			noselectmenu('���� ���Խ���','shop_counsel.php',$idx[5][1],2);
?>
			</table>
			</td>
		</tr>
		</table>

<? /* ������ü ���� ��� ���� jdy */?>
<? if (/*$coupon_use=="1"*/false) { ?>
		<table width=100% border=0 cellspacing=0 cellpadding=0  align="center">
		<tr><td height=2 background=images/icon_line01.gif></td></tr>
		<tr height=30 style="padding-left:8px">
			<td style="padding-left:20px"><a href="javascript:ChangeMenu('shop7');"><img src=images/icon_cross01.gif border=0 align=absmiddle> <?=(isset($idx[5])==true?"<font color=#FF6000>":"")?><B>�������� ����</B></font></a></b></td>
		</tr>
		<tr id=shop7 style='display:none'>
			<td valign=top style=background-repeat:no-repeat;padding-top:10;padding-left:20;padding-bottom:10px >
			<table border=0 cellspacing=0 cellpadding=0 class=font_gray1>
<?
			noselectmenu('���� �����ϱ�','coupon_new.php',$idx[5][0],0);
			noselectmenu('������ ���� �����߱�','coupon_supply.php',$idx[5][1],1);
			noselectmenu('�߱޵� �������� ����','coupon_list.php',$idx[5][2],2);
?>
			</table>
			</td>
		</tr>
		</table>
<? } ?>


		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<script>InitMenu('<?=$menuidx?>');</script>