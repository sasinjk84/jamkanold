<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$ordercode=$_GET["ordercode"];

if(strlen($_ShopInfo->getId())==0 || strlen($ordercode)==0){
	echo "<script>window.close();</script>";
	exit;
}

$query="";

$sql = "SELECT tax_cnum,tax_mid,tax_tid ";
$sql.= "FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
mysql_free_result($result);
$tax_no=$row->tax_cnum;
$kcp_mid=$row->tax_mid;
$kcp_tid=$row->tax_tid;
if(strlen($tax_no)==0) {
	echo "<script>window.close();</script>";
	exit;
}

$query ="cashtype=QURY";
$query.="&midbykcp=".$kcp_mid;
$query.="&termid=".$kcp_tid;
$query.="&cashipaddress1=203.238.36.160";
$query.="&cashportno1=9981";
$query.="&cashipaddress2=203.238.36.161";
$query.="&cashportno2=9981";
$query.="&tax_no=".$tax_no;

$sql = "SELECT * FROM tbltaxsavelist WHERE ordercode='".$ordercode."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	if($row->type!="Y" && $row->type!="C") {
		echo "<script>window.close();</script>";
		exit;
	}

	$id_info=$row->id_info;
	$authno=$row->authno;

	$query.="&tsdtime=".substr($row->tsdtime,2);
	$query.="&id_info=".$row->id_info;
	$query.="&authno=".$row->authno;
	$query.="&mtrsno=".$row->mtrsno;
} else {
	echo "<script>window.close();</script>";
	exit;
}
mysql_free_result($result);

//cgi ȣ��
$host_url=getenv("HTTP_HOST");
$host_cgi="/".RootPath.CashcgiDir."bin/cgiway.cgi";

$resdata=SendSocketPost($host_url,$host_cgi,$query);
$_taxdata=getParse($resdata);

if(count($_taxdata)>0 && strlen($_taxdata["mrspc"])>0) {
	if($_taxdata["mrspc"]!="00") {
		$msg="���ݿ����� ��ȸ�� �����Ͽ����ϴ�.\\n\\n--------------------���л���--------------------\\n\\n".$_taxdata["resp_msg"];
		echo "<script>alert('".$msg."');window.close();</script>";
		exit;
	}
} else {
	echo "<script>alert('���ݿ����� ���� ������ �����Ͽ����ϴ�.');window.close();</script>";
	exit;
}

function getParse($temp) {
	$val = array();
	$list = explode("<br>\n",$temp);
	for ($i=0;$i<count($list); $i++) {
		$data = explode("=",$list[$i]);
		$val[$data[0]] = $data[1];
	}
	return $val;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title>���ݿ�����</title>
<style type="text/css">
body {margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px;font-family: "����", "����ü";font-size: 8pt;color: #303030;}
.total {font-family: "����", "����ü";font-size: 8pt;font-weight: bold;color: #FF5400;}
.gray {font-family: "����", "����ü";font-size: 8pt;color: #7F7F7F;}
</style>
<script language="javascript">
    function cleanPrinting()
    {
        for( i = 1; i <= 2; i++ ) document.getElementById( "bottomImg" + i ).style.display = "none";
        alert( "��½� ����̹����� ������ ���� ���,\n\n[����]�޴� - [���ͳ� �ɼ�] - [���]�� - �μ� �׸��� Ȯ�����ֽñ� �ٶ��ϴ�." );
        window.print();
        document.getElementById( "bottomImg2" ).style.display = "inline";
    }

	function PageResize() {
		var oWidth = document.all.table_body.clientWidth + 10;
		var oHeight = document.all.table_body.clientHeight + 80;

		window.resizeTo(oWidth,oHeight);
	}

</script>
</head>
<body style="overflow-x:hidden;overflow-y:hidden;" oncontextmenu="return false" onLoad="PageResize();">

<?if($_taxdata["msg_type"]=="7102"){?>
<div id="Layer1" style="position:absolute; left:250px; top:390px; width:116px; height:114px; z-index:1; visibility: visible"><img src="images/taxsaveviewpop_mark.gif" width="104" height="106"></div>
<?}?>

<table width="360" border="0" cellspacing="0" cellpadding="0" id=table_body>
<tr>
	<td style="padding-top:20px; padding-left:20px; padding-right:20px; padding-bottom:5px">
	<table border=0 cellpadding=10 cellspacing=1 bgcolor=#eeeeee>
	<tr>
		<td bgcolor=#FFFFFF>
		<table border=0 cellpadding=0 cellspacing=0>
		<tr>
			<td>
			<!--�ڰŷ����� ����-->
			<table width="305" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="2" colspan="2" bgcolor="#FACAB7"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EE9B6A"></td>
				</tr>
				<tr>
					<td height="17" bgcolor="#F6F5ED"><img src="images/taxsaveviewpop_bul01.gif" width="13" height="10" align="absmiddle">
						<strong>��ȭ��ȣ/�ֹε�Ϲ�ȣ</strong>
					</td>
					<td bgcolor="#F6F5ED"><img src="images/taxsaveviewpop_bul01.gif" width="13" height="10" align="absmiddle"><strong>�ŷ�����(���뵵)</strong></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#FACAB7"></td>
				</tr>
				<tr>
					<td height="17"><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle">
					<?=str_pad(substr($_taxdata["id_info"],0,6),strlen($_taxdata["id_info"]),"*")?>
					</td>
					<td><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle">
					���ݰ���<?=($_taxdata["msg_type"]=="7102"?"���":"")?>(�ҵ����)                
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#FACAB7"></td>
				</tr>
				<tr>
					<td height="17" bgcolor="#F6F5ED"><img src="images/taxsaveviewpop_bul01.gif" width="13" height="10" align="absmiddle">
					<strong>�̸�</strong>                
					</td>
					<td bgcolor="#F6F5ED"><img src="images/taxsaveviewpop_bul01.gif" width="13" height="10" align="absmiddle"><strong>�ŷ��Ͻ�</strong></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#FACAB7"></td>
				</tr>
				<tr>
					<td height="17"><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle"><?=$_taxdata["cons_name"]?></td>
					<td><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle">
					<?=($_taxdata["msg_type"]=="7100"?"20".substr($_taxdata["tsdtime"],0,2)."-".substr($_taxdata["tsdtime"],2,2)."-".substr($_taxdata["tsdtime"],4,2)." ".substr($_taxdata["tsdtime"],6,2).":".substr($_taxdata["tsdtime"],8,2).":".substr($_taxdata["tsdtime"],10,2):"")?>
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#FACAB7"></td>
				</tr>
				<tr>
					<td height="17" bgcolor="#F6F5ED"><img src="images/taxsaveviewpop_bul01.gif" width="13" height="10" align="absmiddle"><strong>���ι�ȣ</strong></td>
					<td bgcolor="#F6F5ED"><img src="images/taxsaveviewpop_bul01.gif" width="13" height="10" align="absmiddle"><strong>�ŷ�����Ͻ�</strong></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#FACAB7"></td>
				</tr>
				<tr>
					<td height="17"><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle">
					<?=$authno?>                
					</td>
					<td><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle">
					<?=($_taxdata["msg_type"]=="7102"?"20".substr($_taxdata["tsdtime"],0,2)."-".substr($_taxdata["tsdtime"],2,2)."-".substr($_taxdata["tsdtime"],4,2)." ".substr($_taxdata["tsdtime"],6,2).":".substr($_taxdata["tsdtime"],8,2).":".substr($_taxdata["tsdtime"],10,2):"")?>
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#FACAB7"></td>
				</tr>
				<tr>
					<td height="17" bgcolor="#F6F5ED"><img src="images/taxsaveviewpop_bul01.gif" width="13" height="10" align="absmiddle"><strong>�ֹ���ȣ</strong></td>
					<td bgcolor="#F6F5ED"><img src="images/taxsaveviewpop_bul01.gif" width="13" height="10" align="absmiddle"><strong>��ǰ��</strong></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#FACAB7"></td>
				</tr>
				<tr>
					<td height="17"><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle"><?=$_taxdata["orderid"]?></td>
					<td><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle"><?=titleCut(20,$_taxdata["prod_name"])?></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#EE9B6A"></td>
				</tr>
				<tr>
					<td height="2" colspan="2" bgcolor="#FACAB7"></td>
				</tr>
			</table>
			<!--�ڰŷ����� ��-->
			</td>
		</tr>
		<tr>
			<td height="10"></td>
		</tr>
		<tr>
			<td>
			<!--�ڰ����ݾ� ����-->
			<table width="305" border="0" cellspacing="0" cellpadding="3">
				<tr>
					<td height="108" align="right" background="images/taxsaveviewpop_table.gif">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="23" height="17">&nbsp;</td>
								<td width="23">&nbsp;</td>
								<td width="23">&nbsp;</td>
								<td width="23">&nbsp;</td>
								<td width="23">&nbsp;</td>
								<td width="23">&nbsp;</td>
								<td width="23">&nbsp;</td>
								<td width="23">&nbsp;</td>
								<td width="23">&nbsp;</td>
							</tr>
							<!--�ݾ�-->
							<tr>
							<?
							for($i=0;$i<9;$i++) {
								$tempnum="";
								if((9-$i)<=strlen($_taxdata["amt2"])) {
									$jj=strlen($_taxdata["amt2"])-(9-$i);
									$tempnum=substr($_taxdata["amt2"],$jj,1);
								}
								if(strlen($tempnum)==0) {
									echo "<td height='17' align='center'>&nbsp;</td>";
								} else {
									echo "<td height='17' align='center'><strong>".$tempnum."</strong></td>";
								}
							}
							?>
							</tr>
							<tr>
								<td height="17">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
							<!--VAT-->
							<?
							for($i=0;$i<9;$i++) {
								$tempnum="";
								if((9-$i)<=strlen($_taxdata["amt4"])) {
									$jj=strlen($_taxdata["amt4"])-(9-$i);
									$tempnum=substr($_taxdata["amt4"],$jj,1);
								}
								if(strlen($tempnum)==0) {
									echo "<td height='17' align='center'>&nbsp;</td>";
								} else {
									echo "<td height='17' align='center'><strong>".$tempnum."</strong></td>";
								}
							}
							?>
							</tr>
							<tr>
								<td height="17">&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<!--�հ�-->
							<tr>
							<?
							for($i=0;$i<9;$i++) {
								$tempnum="";
								if((9-$i)<=strlen($_taxdata["amt1"])) {
									$jj=strlen($_taxdata["amt1"])-(9-$i);
									$tempnum=substr($_taxdata["amt1"],$jj,1);
								}
								if(strlen($tempnum)==0) {
									echo "<td height='17' align='center' class='total'>&nbsp;</td>";
								} else {
									echo "<td height='17' align='center' class='total'><strong>".$tempnum."</strong></td>";
								}
							}
							?>
							</tr>
						</table>
						</td>
					</tr>
				</table>
				<!--�ڰ����ݾ� ��-->
				</td>
			</tr>
			<tr>
				<td>
				<!--�����ݿ����� ��������� ����-->
				<table width="305" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="17" colspan="2" bgcolor="#F4F4F4"><img src="images/taxsaveviewpop_tt.gif" width="131" height="16"></td>
				</tr>
				<tr>
					<td height="2" colspan="2" bgcolor="#D6D6D6"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#AFAFAF"></td>
				</tr>
				<tr>
					<td height="17" colspan="2" bgcolor="#F4F4F4"><img src="images/taxsaveviewpop_bul02.gif" width="13" height="10" align="absmiddle"><strong>��������</strong></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#D6D6D6"></td>
				</tr>
				<tr>
					<td height="17" colspan="2"><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle"><?=$_taxdata["mcht_name"]?></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#D6D6D6"></td>
				</tr>
				<tr>
					<td height="17" bgcolor="#F4F4F4"><img src="images/taxsaveviewpop_bul02.gif" width="13" height="10" align="absmiddle"><strong>��ǥ�ڸ�</strong></td>
					<td bgcolor="#F4F4F4"><img src="images/taxsaveviewpop_bul02.gif" width="13" height="10" align="absmiddle"><strong>��ȭ��ȣ</strong></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#D6D6D6"></td>
				</tr>
				<tr>
					<td height="17"><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle"><?=$_taxdata["sell_name"]?></td>
					<td><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle">
					<?=$_taxdata["sell_tel"]?>
					</td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#D6D6D6"></td>
				</tr>
				<tr>
					<td height="17" colspan="2" bgcolor="#F4F4F4"><img src="images/taxsaveviewpop_bul02.gif" width="13" height="10" align="absmiddle"><strong>����ڹ�ȣ</strong></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#D6D6D6"></td>
				</tr>
				<tr>
					<td height="17" colspan="2"><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle"><?=substr($_taxdata["tax_no"],0,3)."-".substr($_taxdata["tax_no"],3,2)."-".substr($_taxdata["tax_no"],5,5)?></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#D6D6D6"></td>
				</tr>
				<tr>
					<td height="17" colspan="2" bgcolor="#F4F4F4"><img src="images/taxsaveviewpop_bul02.gif" width="13" height="10" align="absmiddle"><strong>�ּ�</strong></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#D6D6D6"></td>
				</tr>
				<tr>
					<td height="17" colspan="2"><img src="images/taxsaveviewpop_pad.gif" width="13" height="10" align="absmiddle"><?=$_taxdata["sell_addr"]?></td>
				</tr>
				<tr>
					<td height="1" colspan="2" bgcolor="#AFAFAF"></td>
				</tr>
				<tr>
					<td height="2" colspan="2" bgcolor="#D6D6D6"></td>
				</tr>
				</table>
				<!--�����ݿ����� ��������� ��-->
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td height="25" class="gray">������ : 1544-2020 | http://���ݿ�����/kr </td>
			</tr>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td align=center>
	<table border=0>
        <tr>
            <td height="55" align="center" valign="top">
            <img src="images/taxsaveviewpop_print.gif" width="97" height="36" border="0" onclick="JavaScript:cleanPrinting();" style="cursor:pointer;" id="bottomImg1">
            <img src="images/taxsaveviewpop_close.gif" width="66" height="36" border="0" onclick="JavaScript:self.close();" style="cursor:pointer;" id="bottomImg2">
            </td>
        </tr>
	</table>
	</td>
</tr>
</table>
</body>
</html>
