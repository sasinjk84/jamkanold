<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$vender=$_POST["vender"];
if(strlen($vender)==0) {
	echo "<html><head></head><body onload=\"alert('�ش� ������ü�� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}
$sql = "SELECT a.*, b.brand_name,b.brand_description,c.prdt_allcnt,c.prdt_cnt,c.cust_cnt,c.count_total ";
$sql.= "FROM tblvenderinfo a, tblvenderstore b, tblvenderstorecount c ";
$sql.= "WHERE a.vender='".$vender."' AND a.delflag='N' AND a.vender=b.vender AND b.vender=c.vender ";
$result=mysql_query($sql,get_db_conn());
if(!$row=mysql_fetch_object($result)) {
	echo "<html><head></head><body onload=\"alert('�ش� ������ü�� �������� �ʽ��ϴ�.');window.close();\"></body></html>";exit;
}
mysql_free_result($result);
$_vdata=$row;

$bank_account="";
if(strlen($_vdata->bank_account)>0) {
	$tmp=explode("=",$_vdata->bank_account);
	$bank_account=$tmp[0]." ".$tmp[1]." (".$tmp[2].")";
}

$deli_price = $_vdata->deli_price;
if($deli_price==-9) $delivery="Y";
else if($deli_price==0) $delivery="F";
else $delivery="M";
if($deli_price<0) $deli_price=0;
$deli_mini = $_vdata->deli_mini;

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>������ü ������</title>
<link rel="stylesheet" href="style.css" type="text/css">
<SCRIPT LANGUAGE="JavaScript">
<!--
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	//var oHeight = document.all.table_body.clientHeight + 55;
	var oHeight = 500;

	window.resizeTo(oWidth,oHeight);
}
//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">

<table border=0 cellpadding=0 cellspacing=0 width=500 style="table-layout:fixed;" id=table_body>
<tr>
	<td width=100% align=center>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><img src="images/vender_detail_pop_t.gif" border="0" width="130" height="31"></td>
		<td width="100%" background="images/member_find_titlebg.gif"><FONT COLOR="#ffffff"><B>[<?=$_vdata->id?>]</B></FONT></td>
		<td align=right><img src="images/member_find_titleimg.gif" width="20" height="31" border="0"></td>
	</tr>
	</table>
	<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
	<tr><td height=10></td></tr>
	<tr>
		<td align=center>
		<table border=0 cellpadding=0 cellspacing=0 width=95% style="table-layout:fixed">
		<tr><td height=10></td></tr>
		<tr>
			<td style="padding-bottom:3px"><IMG height=9 src="images/icon_9.gif" width=13 border=0><B>��ü ȸ������</B>
			</td>
		</tr>
		</table>
		<table border=0 cellpadding=0 cellspacing=0 width=95% style="table-layout:fixed">
		<col width=120></col>
		<col width=></col>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���̵�</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->id?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ���</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->com_name?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ڵ�Ϲ�ȣ</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->com_num?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǥ�� ����</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->com_owner?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǥ ��ȭ/�ѽ�</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->com_tel?> / <?=$_vdata->com_fax?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ�� �ּ�</td>
			<td class="td_con1"><img width="0"><B>[<?=$_vdata->com_post?>] <?=$_vdata->com_addr?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� ����</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->com_biz?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� ����</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->com_item?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ�� Ȩ������</td>
			<td class="td_con1"><img width="0"><B><?=(strlen($_vdata->com_homepage)>0?"<a href=\"http://".$_vdata->com_homepage."\" target=\"_blank\">http://".$_vdata->com_homepage."</a>":"")?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		</table>

		<table border=0 cellpadding=0 cellspacing=0 width=95% style="table-layout:fixed">
		<tr><td height=20></td></tr>
		<tr>
			<td style="padding-bottom:3px"><IMG height=9 src="images/icon_9.gif" width=13 border=0><B>��ü ����� ����</B>
			</td>
		</tr>
		</table>
		<table border=0 cellpadding=0 cellspacing=0 width=95% style="table-layout:fixed">
		<col width=120></col>
		<col width=></col>
		<TR>
			<TD colspan==2 background="images/table_top_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� ����</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->p_name?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� �ڵ���</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->p_mobile?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� �̸���</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->p_email?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� �μ���</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->p_buseo?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� ����</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->p_level?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		</table>

		<table border=0 cellpadding=0 cellspacing=0 width=95% style="table-layout:fixed">
		<tr><td height=20></td></tr>
		<tr>
			<td style="padding-bottom:3px"><IMG height=9 src="images/icon_9.gif" width=13 border=0><B>��ü ��������</B>
			</td>
		</tr>
		</table>
		<table border=0 cellpadding=0 cellspacing=0 width=95% style="table-layout:fixed">
		<col width=120></col>
		<col width=></col>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ ó�� ����</td>
			<td class="td_con1">
			<input type=checkbox name=chk_prdt1 value="Y" <?if(substr($_vdata->grant_product,0,1)=="Y")echo"checked";?> disabled><B>���</B>
			<img width=5 height=0>
			<input type=checkbox name=chk_prdt2 value="Y" <?if(substr($_vdata->grant_product,1,1)=="Y")echo"checked";?> disabled><B>����</B>
			<img width=5 height=0>
			<input type=checkbox name=chk_prdt3 value="Y" <?if(substr($_vdata->grant_product,2,1)=="Y")echo"checked";?> disabled><B>����</B>
			<img width=10 height=0>
			<input type=checkbox name=chk_prdt4 value="Y" <?if(substr($_vdata->grant_product,3,1)=="Y")echo"checked";?> disabled><B>���/������, ������ ����</B>
			</td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ��ǰ��</td>
			<td class="td_con1"><img width="0"><B><?=($_vdata->product_max==0?"������ ��� ����":$_vdata->product_max."�� ���� ��ǰ��� ����")?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Ǹ� ������</td>
			<td class="td_con1"><img width="0"><B><?=(int)$_vdata->rate?> %</B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���� ��������</td>
			<td class="td_con1"><img width="0"><B><?=$bank_account?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������</td>
			<td class="td_con1"><img width="0"><B>�ſ� <?=(strlen($_vdata->account_date)>0?$_vdata->account_date."��":"")?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		</table>

		<table border=0 cellpadding=0 cellspacing=0 width=95% style="table-layout:fixed">
		<tr><td height=20></td></tr>
		<tr>
			<td style="padding-bottom:3px"><IMG height=9 src="images/icon_9.gif" width=13 border=0><B>�̴ϼ� ����</B>
			</td>
		</tr>
		</table>
		<table border=0 cellpadding=0 cellspacing=0 width=95% style="table-layout:fixed">
		<col width=120></col>
		<col width=></col>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�̴ϼ���</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->brand_name?></B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�̴ϼ� ����</td>
			<td class="td_con1"><img width="0"><?=$_vdata->brand_description?></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��� ��ǰ</td>
			<td class="td_con1"><img width="0"><B>������/�������� : <?=$_vdata->prdt_cnt?>�� / <?=$_vdata->prdt_allcnt?>��</B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ܰ� ���</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->cust_cnt?>��</B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�� �湮�ڼ�</td>
			<td class="td_con1"><img width="0"><B><?=$_vdata->count_total?>��</B></td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<tr>
			<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��۷�</td>
			<td class="td_con1">
<?
			if($delivery=="F") {
				echo "<B>����</B>";
			} else if($delivery=="Y") {
				echo "<B>����</B>";
			} else if($delivery=="M") {
				echo "�ֹ��ݾ��� <B>".number_format($deli_mini)."��</B> ���� ������ ��۷� <B>".number_format($deli_price)."��</B> �߰�";
			}
?>
			</td>
		</tr>
		<TR>
			<TD colspan="2" background="images/table_con_line.gif"></TD>
		</TR>
		<TR>
			<TD colspan=2 background="images/table_top_line.gif"></TD>
		</TR>
		</table>
		</td>
	</tr>
	<tr><td height=10></td></tr>
	<tr>
		<td align=center><input type="image" src="images/btn_close.gif" width="36" height="18" border="0" vspace="0" hspace="2" onclick="window.close();">
		</td>
	</tr>
	<tr><td height=10></td></tr>
	</table>

	</td>
</tr>
</table>

</body>
</html>