<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

header("Cache-Control: no-cache, must-revalidate");
header("Content-Type: text/xml; charset=EUC-KR");

$masterMsg = "";

/* ������ �����û�� �ִ� ��� */
$sql = "SELECT vender FROM vender_more_info WHERE commission_status='1'";
$result1=mysql_query($sql,get_db_conn());
while($_vmdata=mysql_fetch_object($result1)){
	$sql_ = "SELECT id FROM tblvenderinfo WHERE vender = '".$_vmdata->vender."'";
	$res1=mysql_query($sql_,get_db_conn());
	if($vdata1=mysql_fetch_object($res1)){
		$masterMsg .= "- [".$vdata1->id."]���� ������ �����û�� �ֽ��ϴ�.";
		$masterMsg .= "[<a href=\"javascript:viewHistory('".$_vmdata->vender."')\">Ȯ���ϱ�</a>]";
	}
	$masterMsg = $masterMsg!=""? $masterMsg."<br>": "";
}
/* ������ �����û�� �ִ� ��� */

/* ������ü ���Խ��ǿ� ���ǰ� �ִ� ��� */
$sql = "SELECT vender FROM tblvenderadminqna WHERE re_date is NULL ";
$result2=mysql_query($sql,get_db_conn());
while($_qnadata=mysql_fetch_object($result2)){
	$sql_ = "SELECT id FROM tblvenderinfo WHERE vender = '".$_qnadata->vender."'";
	$res2=mysql_query($sql_,get_db_conn());
	if($vdata2=mysql_fetch_object($res2)){
		$masterMsg .= "- [".$vdata2->id."]���� ������ü ���Խ��ǿ� ���ǰ� �ֽ��ϴ�.";
		$masterMsg .= "[<a href=\"vender_counsel.php\">Ȯ���ϱ�</a>]";
	}
	$masterMsg = $masterMsg!=""? $masterMsg."<br>": "";
}
/* ������ü ���Խ��ǿ� ���ǰ� �ִ� ��� */

/* ȸ�� ��޺� ���κ����û�� �ִ� ��� */
$sql = "SELECT req.productcode FROM tblproduct a inner join discount_chgrequest req on req.productcode=a.productcode WHERE a.productcode is not null group by req.productcode";
$result3=mysql_query($sql,get_db_conn());
while($_dcdata=mysql_fetch_object($result3)){
	$masterMsg .= "- [".$_dcdata->productcode."] ��ǰ�� ȸ�� ��޺� ���κ����û�� �ֽ��ϴ�.";
	$masterMsg .= "[<A HREF=\"JavaScript:ProductInfo('".substr($_dcdata->productcode,0,12)."','".$_dcdata->productcode."','YES','0')\">Ȯ���ϱ�</a>]";
	$masterMsg = $masterMsg!=""? $masterMsg."<br>": "";
}
/* ȸ�� ��޺� ���κ����û�� �ִ� ��� */

/* ��õ�� ���������û�� �ִ� ��� */
$sql = "SELECT req.productcode FROM tblproduct a inner join req_chgresellerreserv req on req.productcode=a.productcode WHERE a.productcode is not null group by req.productcode";
$result4=mysql_query($sql,get_db_conn());
while($_revdata=mysql_fetch_object($result4)){
	$masterMsg .= "- [".$_revdata->productcode."] ��ǰ�� ��õ�� ���������û�� �ֽ��ϴ�.";
	$masterMsg .= "[<A HREF=\"JavaScript:ProductInfo('".substr($_revdata->productcode,0,12)."','".$_revdata->productcode."','YES','0')\">Ȯ���ϱ�</a>]";
	$masterMsg = $masterMsg!=""? $masterMsg."<br>": "";
}
/* ��õ�� ���������û�� �ִ� ��� */


/* ȸ�� ��޺� ���������û�� �ִ� ��� */
$sql = "SELECT req.productcode FROM tblproduct a inner join reserve_chgrequest req on req.productcode=a.productcode WHERE a.productcode is not null group by req.productcode";
$result5=mysql_query($sql,get_db_conn());
while($_rcdata=mysql_fetch_object($result5)){
	$masterMsg .= "- [".$_rcdata->productcode."] ��ǰ�� ȸ�� ��޺� ���������û�� �ֽ��ϴ�.";
	$masterMsg .= "[<A HREF=\"JavaScript:ProductInfo('".substr($_rcdata->productcode,0,12)."','".$_rcdata->productcode."','YES','0')\">Ȯ���ϱ�</a>]";
	$masterMsg = $masterMsg!=""? $masterMsg."<br>": "";
}
/* ȸ�� ��޺� ���������û�� �ִ� ��� */



/* ��õ�� ��޺� ���������û�� �ִ� ��� */
$sql = "SELECT req.productcode FROM tblproduct a inner join reseller_reserve_chgrequest req on req.productcode=a.productcode WHERE a.productcode is not null group by req.productcode";
$result6=mysql_query($sql,get_db_conn());
while($_rrcdata=mysql_fetch_object($result6)){
	$masterMsg .= "- [".$_rrcdata->productcode."] ��ǰ�� ��õ�� ��޺� ���������û�� �ֽ��ϴ�.";
	$masterMsg .= "[<A HREF=\"JavaScript:ProductInfo('".substr($_rrcdata->productcode,0,12)."','".$_rrcdata->productcode."','YES','0')\">Ȯ���ϱ�</a>]";
	$masterMsg = $masterMsg!=""? $masterMsg."<br>": "";
}
/* ��õ�� ��޺� ���������û�� �ִ� ��� */

?>

<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
	<td>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr>
		<td id=layerbox-top style="cursor:move; float:left;">
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
		<col width=10></col>
		<col width=></col>
		<col width=10></col>
		<tr>
			<td style="width:10px;height:25px;background: url(/<?=RootPath?>images/common/layeropenbg_top_left.gif) no-repeat 0 0;"></td>
			<td style="height:25px;background: url(/<?=RootPath?>images/common/layeropenbg_top_center.gif)">
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
			<col width=></col>
			<col width=50></col>
			<tr>
				<td style="padding:5,0,0,0; font-size:11px;color:#FEEACB;"><B>�˸� �޼���</B></td>
				<td align=right style="padding-top:2;"><a style="cursor:hand" onclick="MasterAlarm.openwinClose()"><FONT style="font-size:11px;color:#FEEACB;">close</FONT> <img src="/<?=RootPath?>images/common/layeropen_btn_close.gif" border=0 align=absmiddle></a></td>
			</tr>
			</table>
			</td>
			<td style="width:10px;height:25px;background: url(/<?=RootPath?>images/common/layeropenbg_top_right.gif) no-repeat 0 0;"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td id=layerbox-content>
		<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
		<col width=10></col>
		<col width=></col>
		<col width=10></col>
		<tr>
			<td style="width:10px;background: url(/<?=RootPath?>images/common/layeropenbg_middle_left.gif) repeat-y;"></td>
			<td style="background: url(/<?=RootPath?>images/common/layeropenbg_middle_center.gif);">
			<div style="margin: 15px 0 0 3px;overflow:scroll;height:280px">
			<!-- ���� ���� -->

				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td id=layerbox-top style="cursor:move; float:left;">
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<col width=10></col>
						<col width=></col>
						<col width=10></col>
						<tr>
							<td></td>
							<td>
								<?=$masterMsg?>
							</td>
							<td></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
			</div>
			</td>
		</tr>
		</table>
		</td>
	</tr>
</table>

<form name="form1" method="post">
<input type=hidden name='vender'>
</form>


<script>
Drag.init($("layerbox-top"),$("create_openwin"));
</script>
