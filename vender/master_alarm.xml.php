<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
//include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/venderlib.php");

header("Cache-Control: no-cache, must-revalidate");
header("Content-Type: text/xml; charset=EUC-KR");

$masterMsg =  "";

/* ��Ź��ǰ ��Ͻ�û�� �ִ� ��� */
$sql = "SELECT productcode FROM tblproduct p inner join rent_product rp ON p.pridx=rp.pridx  ";
$sql.= "WHERE rp.istrust='0' AND rp.trust_vender='".$_GET["vender"]."' AND rp.trust_approve='N'";
$result=mysql_query($sql,get_db_conn());
while($_trust_data=mysql_fetch_object($result)){

	$masterMsg .= "- ��Ź��ǰ ��Ͻ�û�� �ֽ��ϴ�.";
	$masterMsg .= "[<a href=\"javascript:GoPrdinfo('".$_trust_data->productcode."','')\">Ȯ���ϱ�</a>]";

	$masterMsg = $masterMsg!=""? $masterMsg."<br>": "";
}
/* ��Ź��ǰ ��Ͻ�û�� �ִ� ��� */

/* ������ü�� ��� ��Ź��ǰ ��Ͻ�û�� ���ο���Ȯ�� */
$sql = "SELECT productcode,trust_approve FROM tblproduct p inner join rent_product rp ON p.pridx=rp.pridx  ";
$sql.= "WHERE rp.istrust='0' AND p.vender='".$_GET["vender"]."' AND rp.trust_approve<>'N'";

$result=mysql_query($sql,get_db_conn());
while($_pr_data=mysql_fetch_object($result)){

	if($_pr_data->trust_approve=="Y"){
		$masterMsg .= "- ��Ź�Ͻ� ��ǰ�� ��� �Ϸ�ƽ��ϴ�.";
		$masterMsg .= "[<a href=\"javascript:GoPrdinfo('".$_pr_data->productcode."','')\">Ȯ���ϱ�</a>]";
	}else if($_pr_data->trust_approve=="R"){
		$masterMsg .= "- ��Ź�Ͻ� ��ǰ�� ��� �����ƽ��ϴ�.";
		$masterMsg .= "[<a href=\"javascript:GoPrdinfo('".$_pr_data->productcode."','')\">Ȯ���ϱ�</a>]";
	}

	$masterMsg = $masterMsg!=""? $masterMsg."<br>": "";
}
/* ������ü�� ��� ��Ź��ǰ ��Ͻ�û�� ���ο���Ȯ�� */




/* ��Ź��ǰ ������ �����û�� �ִ� ��� */
$sql = "SELECT productcode FROM tbltrustcommission ";
$sql.= "WHERE (trust_vender='".$_GET["vender"]."' OR vender='".$_GET["vender"]."') ";
$sql.= "AND modify_vender<>'".$_GET["vender"]."' ";
$sql.= "AND status='1'";

$result=mysql_query($sql,get_db_conn());
while($_commi_data=mysql_fetch_object($result)){

	$masterMsg .= "- ��Ź�Ͻ� ��ǰ�� �����ᰡ ���� ��Ź ������� �޸� ��ϵƽ��ϴ�.<br>";
	$masterMsg .= "���ǵ� ������ �ƴ϶�� ���� ��Ź��ü�� �����ϼż� ���� ��û�Ͻ� �� �ֽ��ϴ�.";
	$masterMsg .= "[<a href=\"javascript:GoPrdinfo('".$_commi_data->productcode."','')\">Ȯ���ϱ�</a>]";

	$masterMsg = $masterMsg!=""? $masterMsg."<br>": $masterMsg;
}
/* ��Ź��ǰ ������ �����û�� �ִ� ��� */

/* ��Ź��ǰ �������� �� ��Ź���� ���öȸ */
$sql = "SELECT ta.ta_idx,ta.take_vender FROM tbltrustcancel tc LEFT JOIN tbltrustagree ta ON tc.ta_idx=ta.ta_idx ";
$sql.= "WHERE tc.cancel_agree='Y' AND tc.status='3' AND ta.give_vender='".$_GET["vender"]."'";
$result=mysql_query($sql,get_db_conn());
while($_cancel_data=mysql_fetch_object($result)){
	
	$sql_ = "SELECT id FROM tblvenderinfo WHERE vender = '".$_cancel_data->take_vender."'";
	$res2=mysql_query($sql_,get_db_conn());
	if($vdata2=mysql_fetch_object($res2)){
		$masterMsg .= "- [".$vdata2->id."]��ü�� ��Ź����� öȸ�� �Ϸ�ƽ��ϴ�. ��Ź��ǰ�� ������ ���� ���ڿ� �°� ���޵� �����Դϴ�.";
		$masterMsg .= "[<a href=\"trust_view.php?ta_idx=".$_cancel_data->ta_idx."\">Ȯ���ϱ�</a>]";
	}
	$masterMsg = $masterMsg!=""? $masterMsg."<br>": $masterMsg;

}
/* ��Ź��ǰ �������� �� ��Ź���� ���öȸ */


/*��Ź��ü ���*/
$sql = "SELECT tm_idx,approve FROM tbltrustmanage WHERE vender='".$_GET["vender"]."' AND approve<>'N' AND approve_check='N'";
$result=mysql_query($sql,get_db_conn());
while($_tm_data=mysql_fetch_object($result)){

	if($_tm_data->approve=="Y"){
		$masterMsg .= "- ��Ź���� ��ü�� ����� �Ϸ�ƽ��ϴ�.";
		$masterMsg .= "[<a href=\"trust_list.php\">Ȯ���ϱ�</a>]";
	}else if($_tm_data->approve=="R"){
		$masterMsg .= "- ��Ź���� ��ü�� ����� �����ƽ��ϴ�.";
		$masterMsg .= "[<a href=\"trust_list.php\">Ȯ���ϱ�</a>]";
	}

	$masterMsg = $masterMsg!=""? $masterMsg."<br>": $masterMsg;
}
/*��Ź��ü ���*/

/*��Ź����û ���ι� ��ҵ� ���*/
$sql = "SELECT ta_idx,approve FROM tbltrustagree WHERE give_vender='".$_GET["vender"]."' AND approve<>'N' AND approve_check='N'";
$result=mysql_query($sql,get_db_conn());
while($_ta_data=mysql_fetch_object($result)){

	if($_ta_data->approve=="Y"){
		$masterMsg .= "- ���Ͻ� ��Ź��ü�� ������ �Ϸ�ƽ��ϴ�.";
		$masterMsg .= "[<a href=\"trust_view.php?type=give&ta_idx=".$_ta_data->ta_idx."\">Ȯ���ϱ�</a>]";
	}else if($_ta_data->approve=="R"){
		$masterMsg .= "- ��Ź��ü����û�� �����ƽ��ϴ�.";
		$masterMsg .= "[<a href=\"trust_view.php?type=give&ta_idx=".$_ta_data->ta_idx."\">Ȯ���ϱ�</a>]";
	}else if($_ta_data->approve=="C"){
		$masterMsg .= "- ��Ź��ü���� ȸ������ ��ǰ�� ���� ��Ź������ �����߽��ϴ�.";
		$masterMsg .= "[<a href=\"trust_view.php?type=give&ta_idx=".$_ta_data->ta_idx."\">Ȯ���ϱ�</a>]";
	}

	$masterMsg = $masterMsg!=""? $masterMsg."<br>": $masterMsg;
}
/*��Ź����û ���ι� ��ҵ� ���*/
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
