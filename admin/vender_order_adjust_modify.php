<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/admin_more.php");

	if(strlen($_ShopInfo->getId())==0){
		echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
		exit;
	}


	$mode=$_POST["mode"];
	$search_date=$_POST["search_date"];

	$ordercode = $_REQUEST["ordercode"];
	$vender=$_REQUEST["vender"];

	$up_adjust = $_REQUEST["up_adjust"];
	$memo = $_REQUEST["memo"];

	if ($mode=="insert") {
		
		if (!empty($up_adjust)) {
			//���泻�� �Է�
			 addOrderAdjustDetail($ordercode, $vender, $up_adjust, $memo);

		}

		echo "<script>alert('����� ó���� �Ϸ�Ǿ����ϴ�.');opener.location.reload();window.close();</script>";
		exit;

	}


	if(strlen($ordercode)==0) {
		echo "<html><head></head><body onload=\"alert('�ֹ������� �ùٸ��� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
	}

	if(strlen($vender)==0) {
		echo "<html><head></head><body onload=\"alert('�ش� ������ü�� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
	}

	//����� ������ȸ

	$data = selectOrderAdjustDetail($ordercode, $vender);

	$adjust = $data['sumadjust'];

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>������ ����/����</title>
<link rel="stylesheet" href="style.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--

function CheckForm() {

	document.form1.up_adjust.value = str_replace(",","",document.form1.up_adjust.value);
	if(document.form1.up_adjust.value.length==0 || isNaN(document.form1.up_adjust.value)){
		alert('����ݾ��� �Է����� �����̰ų� ���ڰ� �ƴմϴ�.\n �ٽ� Ȯ���Ͻð� �Է¹ٶ��ϴ�.');
		document.form1.up_adjust.focus();
		return;
	}
	
	btnok = document.getElementById('btnok');
	btnok.style.display='none';

	document.form1.mode.value = "insert";
	document.form1.submit();

}


function str_replace ( search, replace, subject ) {
    // Replace all occurrences of the search string with the replacement string
    //
    // +    discuss at: http://kevin.vanzonneveld.net/techblog/article/javascript_equivalent_for_phps_str_replace/
    // +       version: 801.3120
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'

    var result = "";
    var prev_i = 0;
    for (i = subject.indexOf(search); i > -1; i = subject.indexOf(search, i)) {
        result += subject.substring(prev_i, i);
        result += replace;
        i += search.length;
        prev_i = i;
    }

    return result + subject.substring(prev_i, subject.length);
}


function number_format(num) {
	var num = num.toString();
	cks = '';
	num = num.replace(/,/g, "");
	var result = '';

	if(num.indexOf('-')!=-1) {
		cks = '-';
		num = num.replace(/-/g, "");
	}

	for(var i=0; i<num.length; i++) {
		var tmp = num.length-(i+1);
		if(i%3==0 && i!=0) result = ',' + result;
		result = num.charAt(tmp) + result;
	}

	if(cks=='-') return '-' + result;
	else return result;
}

function cnum(obj){
	vls = str_replace(",","",obj.value);
	obj.value = number_format(vls);
}

//-->
</SCRIPT>
</head>

<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;overflow-y:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
<TABLE width="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id=table_body>
	<TR>
		<TD height="31" background="images/member_mailallsend_imgbg.gif">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="25"><p>&nbsp;</td>
					<td><p><b><font color="white">���� �������� �̷�</b></font></td>
				</tr>
			</table>
		</TD>
	</TR>
</table>

<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
<input type=hidden name=mode>
<input type=hidden name=ordercode value="<?=$ordercode?>">
<input type=hidden name=vender value="<?=$vender?>">
<input type=hidden name=commission_result />

<TABLE WIDTH="96%" align="center" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id=table_body>
	<TR>
		<TD align=left><b>* �ֹ���</b></TD>
	</TR>
	<TR>
	<TD style="padding:5px 0px;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<tr>
			<td width="100%">

			<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=135></col> <!-- �����/�ֹ��ڵ� -->
				<col width=70></col> <!-- �ֹ��� -->
				<col width=></col> <!-- ��ǰ�� -->

				<col width=60></col> <!-- �� ��۷� -->
				<col width=70></col> <!-- �������� -->
				<col width=70></col> <!-- �����ݾ� -->
				<col width=100></col> <!-- ����ݾ� -->
				<TR>
					<TD background="images/table_top_line.gif" colspan="13"></TD>
				</TR>
				<TR height="32">
					<TD class="table_cell5" align="center">���԰�����/�ֹ��ڵ�</TD>
					<TD class="table_cell6" align="center">�ֹ�����</TD>
					<td align="center" colspan="5">

					<table border=0 cellpadding=0 cellspacing=0 width="100%" height="100%">
						<col width=></col>
						<col width=30></col>
						<col width=65></col>
						<col width=106></col>
						<col width=61></col>
						<tr height="32">
						<TD class="table_cell6" align="center">��ǰ��</TD>
						<TD class="table_cell6" align="center">����</TD>
						<TD class="table_cell6" align="center">�Ǹűݾ�</TD>
						<TD class="table_cell6" align="center">������</TD>
						<TD class="table_cell6" align="center">������</TD>
						</tr>
					</table>
					</td>
					<TD class="table_cell6" align="center">��۷�</TD>
					<TD class="table_cell6" align="center">��������</TD>
					<TD class="table_cell6" align="center">�����ݾ�</TD>
					<TD class="table_cell6" align="center">����ݾ�</TD>
				</TR>
				<TR>
					<TD colspan="13" background="images/table_con_line.gif"></TD>
				</TR>

<?
				$sql ="SELECT SUM(IF((productcode!='99999999990X' AND NOT (productcode LIKE 'COU%')), price,NULL)) as sumprice, ";
				$sql.= "SUM(reserve) as sumreserve, ";
				$sql.= "SUM(deli_price) as sumdeliprice, ";
				$sql.= "SUM(cou_price) as sumcouprice, ";
				$sql.= "ordercode, deli_date, vender, sum(adjust) as sumadjust FROM `order_adjust_detail` ";
				$sql.=" WHERE vender='".$vender."' AND ordercode='".$ordercode."' ";
				
				$result=mysql_query($sql,get_db_conn());

				$colspan= 13;
					
			while($row=mysql_fetch_object($result)) {

				$date = substr($row->deli_date,0,4)."/".substr($row->deli_date,4,2)."/".substr($row->deli_date,6,2)." (".substr($row->deli_date,8,2).":".substr($row->deli_date,10,2).")";
				$orderdate = substr($row->ordercode,0,4)."/".substr($row->ordercode,4,2)."/".substr($row->ordercode,6,2)." (".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).")";
				
				echo "<tr>\n";

				echo "	<td class=\"td_con5\" align=center style=\"font-size:8pt;line-height:12pt\"><A HREF=\"javascript:OrderDetailView('".$row->ordercode."',".$row->vender.")\">".$date."<br>".$row->ordercode."</A></td>\n";
				echo "	<td class=\"td_con6\" align=center style=\"font-size:8pt;line-height:12pt\">".$orderdate."</td>";
				echo "	<td class=\"td_con6\" colspan=\"5\">\n";
				
				echo "	<table border=0 cellpadding=0 cellspacing=0 width='100%'>\n";
				echo "	<col width=></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=30></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=59></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=100></col>\n";
				echo "	<col width=1></col>\n";
				echo "	<col width=55></col>\n";

				$sql = "SELECT o.*,
						a.account_rule, a.rate, a.cost, a.status,
						a.relay, a.rate_price, a.surtax
						FROM tblorderproduct o left join order_adjust_detail a
						on o.ordercode=a.ordercode and o.productcode=a.productcode
						WHERE o.vender='".$row->vender."' AND o.ordercode='".$row->ordercode."' ";
				$sql.=  getVenderOrderAdjustListGoods($row->vender, $search_date);
				$sql.= "AND NOT (o.productcode LIKE 'COU%' OR o.productcode LIKE '999999%') ";
				
				$status_chk=0;

				$result2=mysql_query($sql,get_db_conn());
				$jj=0;
				while($row2=mysql_fetch_object($result2)) {

					
					$a_rule = $row2->account_rule;
					$rate = $row2->rate;
					$cost = $row2->cost;

					$relay = $row2->relay;
					$rate_price = $row2->rate_price;
					$surtax = $row2->surtax;

					$rate_val = 0;


					if ($a_rule =='1') {
						$rate_val = $row2->price*$row2->quantity - $cost." ��";
					}else{
						$rate_val = $rate." % ->".$rate_price." ��";
					}

					if ($relay == "1" && $surtax>0) {
						$rate_val .= "<br/>";
						$rate_val .= "( -".$surtax."��)";
					}

					$s_value ="";

					if ($row2->status != 1) {
						$status_chk++;
					}


					if($jj>0) echo "<tr><td colspan=9 height=1 bgcolor=#E7E7E7></tr>";
					echo "<tr>\n";
					echo "	<td style=\"font-size:8pt;padding:3;line-height:11pt\"><a href=\"/front/productdetail.php?productcode=".$row2->productcode."\" target=\"_blank\">".$row2->productname."</a></td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=center style=\"font-size:8pt\">".$row2->quantity."</td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=right style=\"font-size:8pt;padding:3\">".number_format($row2->price*$row2->quantity)."&nbsp;</td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=right style=\"font-size:8pt;padding:3\">".$rate_val."&nbsp;</td>\n";
					echo "	<td bgcolor=#E7E7E7></td>\n";
					echo "	<td align=right style=\"font-size:8pt;padding:3\">".($row2->reserve>0?"-":"").number_format($row2->reserve*$row2->quantity)."&nbsp;</td>\n";
					echo "</tr>\n";
					$jj++;
				}
				mysql_free_result($result2);

				echo "	</table>\n";
				echo "	</td>\n";
				echo "	<td class=\"td_con6\" align=right style=\"font-size:8pt;padding:3\">".($row->sumdeliprice>0?"+":"").number_format($row->sumdeliprice)."&nbsp;</td>\n";
				echo "	<td class=\"td_con6\" align=right style=\"font-size:8pt;padding:3\">".number_format($row->sumcouprice)."&nbsp;</td>\n";
				echo "	<td class=\"td_con6\" align=right style=\"font-size:8pt;padding:3\"><B>".number_format($row->sumprice+$row->sumdeliprice-($row->sumreserve-$row->sumcouprice))."</B>&nbsp;</td>\n";
				echo "	<td class=\"td_con6\" align=right style=\"font-size:8pt;padding:3\"><B>".number_format($row->sumadjust)."</B>&nbsp;</td>\n";
				
			
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<TD height=1 background=\"images/table_con_line.gif\" colspan=\"".($colspan-2)."\"></TD>\n";
				echo "</tr>\n";

				$i++;
			}
			mysql_free_result($result);
?>
			</td>

		</tr>
	</tr>
	</table>
	</TD>
</TR>
<? if ($status_chk==0 && $search_date<=date("Y-m-d")) { ?>
<TR>
	<TD height="30"></TD>
</TR>
<TR>
	<TD align=left><b>* ����� ����</b></TD>
</TR>
<TR>
	<TD style="padding:5px 0px;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<col width=118></col>
		<col width=></col>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�߰�/������ �Է�</TD>
			<TD class="td_con1"><input type=text name=up_adjust maxlength=10 style="width:80;text-align:right" class="input" onFocus="cnum(this);" onKeyUp="cnum(this);" onKeyDown="cnum(this);">��</TD>
		</TR>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�߰�/���� ����</TD>
			<TD class="td_con1"><input type="text" size="50" name="memo"  class="input"/></TD>
		</TR>
		<TR>
			<TD colspan="2"></TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td class="font_blue" style="padding-top:5pt; padding-bottom:5pt; padding-left:8pt;">* <b>��)�߰��� 500�Է�, ������ -500�Է�</b><br>* �߰�/���� ������ ó�� ���ݿ� �°� �Է��Ͻñ� �ٶ��ϴ�.</td>
	</tr>
	<TR>
		<TD background="images/table_top_line.gif"></TD>
	</TR>
	</table>
	</TD>
</TR>
<TR>
	<TD align=center><a href="javascript:CheckForm();"><img src="images/btn_ok.gif" width="36" height="18" border="0" vspace="0" border=0 id="btnok"></a>&nbsp;&nbsp;<a href="javascript:window.close();"><img src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" border=0 hspace="2"></a></TD>
</TR>
<? }else{

	if ($search_date>date("Y-m-d")) {	?>
	
		<script type="text/javascript">
		<!--
			alert("������������ ���� ����ݿ� ���� ������ �������� �Ұ����մϴ�.");
	   //-->
		</script>
	<? }

	if ($status_chk!=0) {	?>
	
		<script type="text/javascript">
		<!--
			alert("���� ó���� ���� ����ݿ� ���� ������ �������� �Ұ����մϴ�.");
	   //-->
		</script>
	<? }

}?>
<tr>
	<td height="30"></td>
</tr>
<TR>
	<TD align=left><b>* ���� ����</b></TD>
</TR>
<TR>
	<TD style="padding:5px 0px;">
		<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
		<TR><TD background="images/table_top_line.gif" colspan="5" height=1></TD></TR>
		<TR align=center>
			<TD class="table_cell">��¥</TD>
			<TD class="table_cell1">����ݾ�</TD>
			<TD class="table_cell1">������ �ݾ�</TD>
			<TD class="table_cell1">������ �ݾ�</TD>
			<TD class="table_cell1">�޸�</TD>
		</TR>
			<TR><TD background="images/table_con_line.gif" colspan="5" height=1></TD></TR>
<? 

	$result = listOrderAdjustUpdateHistory($ordercode, $vender);
	
	$data_lows = mysql_num_rows($result);


if ($data_lows) { ?>

			<? while($data= mysql_fetch_array($result)) { 
				
					$reg_date = substr($data['reg_date'], 0, 10);
				?>
				
				<tr>
					<td align=center class="td_con2"><?= $reg_date ?></td>
					<td align=center class="td_con1"><?= $data['move_adjust'] ?> ��</td>
					<td align=center class="td_con1"><?= $data['old_adjust'] ?> ��</td>
					<td align=center class="td_con1"><?= $data['result_adjust'] ?> ��</td>
					<td align=center class="td_con1"><?= $data['memo'] ?></td>
				</tr>				
				<TR><TD background="images/table_con_line.gif" colspan="5" height=1></TD></TR>
			<? } ?>
<? }else{ ?>
	<tr>
		<td align="center" colspan="5" height="30">
			<b>������ ������ �����ϴ�.</b>
		</td>
	</tr>
<? }?>

			</table>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<TR>
		<TD align=center><a href="javascript:window.close();"><img src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" border=0 hspace="2"></a></TD>
	</TR>
</form>
</TABLE>
</body>
</html>