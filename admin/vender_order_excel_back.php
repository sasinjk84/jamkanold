<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

$CurrentTime = time();
$period[0] = date("Y-m-d",$CurrentTime);
$period[1] = date("Y-m-d",$CurrentTime-(60*60*24*7));
$period[2] = date("Y-m-d",$CurrentTime-(60*60*24*14));
$period[3] = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d"),date("Y")));

$orderby=$_POST["orderby"];
if($orderby!="deli_date" && $orderby!="ordercode") $orderby="deli_date";

$vender=$_POST["vender"];
$s_check=$_POST["s_check"];
$search=$_POST["search"];
$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$vperiod=(int)$_POST["vperiod"];

$search_start=$search_start?$search_start:$period[1];
$search_end=$search_end?$search_end:date("Y-m-d",$CurrentTime);
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[1]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

${"check_vperiod".$vperiod} = "checked";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>366) {
	echo "<script>alert('�˻��Ⱓ�� 1���� �ʰ��� �� �����ϴ�.');location='".$_SERVER[PHP_SELF]."';</script>";
	exit;
}


$output_file_name = $search_start."_".$search_end."_������ü�����������";
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename={$output_file_name}.xls" );
header( "Content-Description: PHP4 Generated Data");


$qry.= "WHERE a.ordercode=b.ordercode ";
if(strlen($vender)>0) {
	$qry.= "AND b.vender='".$vender."' ";
} else {
	$qry.= "AND b.vender>0 ";
}
$qry.= "AND NOT (b.productcode LIKE '999999%' AND b.productcode!='99999999990X') ";
$qry.= "AND a.deli_gbn='Y' ";
$qry.= "AND ((MID(a.paymethod,1,1) IN ('B','O','Q') AND LENGTH(a.bank_date)=14) OR (MID(a.paymethod,1,1) IN ('C','P','M','V') AND a.pay_admin_proc!='C' AND a.pay_flag='0000')) ";
if(substr($search_s,0,8)==substr($search_e,0,8)) {
	$qry.= "AND a.deli_date LIKE '".substr($search_s,0,8)."%' ";
} else {
	$qry.= "AND a.deli_date>='".$search_s."' AND a.deli_date <='".$search_e."' ";
}
if(strlen($search)>0) {
	if($s_check=="cd") $qry.= "AND a.ordercode='".$search."' ";
	else if($s_check=="mn") $qry.= "AND a.sender_name='".$search."' ";
	else if($s_check=="mi") $qry.= "AND a.id='".$search."' ";
	else if($s_check=="cn") $qry.= "AND a.id='".$search."X' ";
}

$t_count=0;
$sumprice=0;
$sumreserve=0;
$sumdeliprice=0;
$sumcouprice=0;
$sql = "SELECT COUNT(DISTINCT(a.ordercode)) as t_count, (select rate from tblvenderinfo where vender = b.vender) as rate,";
$sql.= "SUM(IF((b.productcode!='99999999990X' AND NOT (b.productcode LIKE 'COU%')), b.price*b.quantity,NULL)) as sumprice, ";
$sql.= "SUM(IF(b.productcode LIKE 'COU%', b.price,NULL)) as sumcouprice, ";
$sql.= "SUM(b.reserve*b.quantity) as sumreserve, SUM(IF(b.productcode='99999999990X', b.price,NULL)) as sumdeliprice ";
$sql.= "FROM tblorderinfo a, tblorderproduct b ".$qry." GROUP BY a.ordercode,b.vender ";
$result = mysql_query($sql,get_db_conn());
while($row = mysql_fetch_object($result)) {
	$t_count+=$row->t_count;
	$sumprice+=(int)$row->sumprice;
	$sumreserve+=(int)$row->sumreserve;
	$sumdeliprice+=(int)$row->sumdeliprice;
	$sumcouprice+=(int)$row->sumcouprice;
	$sum+=(int)(($row->sumprice+$row->sumdeliprice-$row->sumreserve-$row->sumcouprice)*(100-$row->rate)/100);
}
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

$venderlist=array();
$sql = "SELECT vender,id,com_name,delflag FROM tblvenderinfo ORDER BY id ASC ";
$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	$venderlist[$row->vender]=$row;
}
mysql_free_result($result);

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=EUC-KR">
	<title></title>
	<script type="text/javascript" src="lib.js.php"></script>
	<script type="text/javascript" src="calendar.js.php"></script>
</head>

			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="8"></td>
			</tr>
			<tr>
				<td>
					<table border=1 cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td width="372" align="left"><B> * �Ⱓ �� �հ�</B></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=1>
				<col width=></col>
				<col width=></col>
				<col width=></col>
				<col width=></col>
				<col width=></col>
				<col width=></col>
				<TR>
					<TD align="center"><b>�� ��ǰ �Ǹž�</b></TD>
					<TD align="center"><b>�� ��۷�</b></TD>
					<TD align="center"><b>�� ���� ������</b></TD>
					<TD align="center"><b>�� ���� ���ξ�</b></TD>
					<TD align="center"><b>�� �ݾ�</b></TD>
					<TD align="center"><b>�� ���� �ݾ�</b></TD>
				</TR>
				<TR>
					<TD align="center"><?=number_format($sumprice)?>��</TD>
					<TD align="center"><?=($sumdeliprice>0?"+":"").number_format($sumdeliprice)?>��</TD>
					<TD align="center"><?=($sumreserve>0?"-":"").number_format($sumreserve)?>��</TD>
					<TD align="center"><?=number_format($sumcouprice)?>��</TD>
					<TD align="center"><?=number_format($sumprice+$sumdeliprice-($sumreserve-$sumcouprice))?>��</TD>
					<TD align="center"><?=number_format($sum)?>��</TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td width="100%">
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=1 >
				<col width=135></col> <!-- �����/�ֹ��ڵ� -->
				<col width=100></col> <!-- �ֹ��� -->
				<col width=70></col> <!-- ������ü -->
				<col width=300></col> <!-- ��ǰ�� -->
				<col width=30></col> <!-- ���� -->
				<col width=60></col> <!-- �Ǹűݾ� -->
				<col width=55></col> <!-- ������ -->
				<col width=55></col> <!-- �� ��۷� -->
				<col width=60></col> <!-- �������� -->
				<col width=70></col> <!-- �� �ݾ� -->
				<col width=70></col> <!-- �� �ݾ� -->
				<col width=70></col> <!-- �� �ݾ� -->
				<tr>
					<td  align="right" colspan="12">�� �ֹ��� : <B><?=number_format($t_count)?></B>��</td>
				</tr>
				<TR height="32">
					<TD  align="center"><b>���԰����� / �ֹ��ڵ�</b></TD>
					<TD  align="center"><b>�ֹ�����</b></TD>
					<TD  align="center"><b>������ü</b></TD>
					<TD  align="center"><b>��ǰ��</b></TD>
					<TD  align="center"><b>����</b></TD>
					<TD  align="center"><b>�Ǹűݾ�</b></TD>
					<TD  align="center"><b>������</b></TD>
					<TD  align="center"><b>��۷�</b></TD>
					<TD  align="center"><b>��������</b></TD>
					<TD  align="center"><b>�� �ݾ�</b></TD>
					<TD  align="center"><b>������</b></TD>
					<TD  align="center"><b>����ݾ�</b></TD>
				</TR>
<?
		$colspan=12;
		if($t_count>0) {
			$sql ="SELECT SUM(IF((b.productcode!='99999999990X' AND NOT (b.productcode LIKE 'COU%')), b.price*b.quantity,NULL)) as sumprice, (select round(rate, 2) from tblvenderinfo where vender = b.vender) as rate, ";
			$sql.= "SUM(b.reserve*b.quantity) as sumreserve, ";
			$sql.= "SUM(IF(b.productcode='99999999990X', b.price,NULL)) as sumdeliprice, ";
			$sql.= "SUM(IF(b.productcode LIKE 'COU%', b.price,NULL)) as sumcouprice, ";
			$sql.= "a.ordercode,a.deli_date, b.vender FROM tblorderinfo a, tblorderproduct b ".$qry." ";
			$sql.="GROUP BY a.ordercode,b.vender ORDER BY a.".$orderby." DESC ";
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			$thisordcd="";
			$thiscolor="#FFFFFF";
			while($row=mysql_fetch_object($result)) {
				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
				$date = substr($row->deli_date,0,4)."/".substr($row->deli_date,4,2)."/".substr($row->deli_date,6,2)." (".substr($row->deli_date,8,2).":".substr($row->deli_date,10,2).")";
				$orderdate = substr($row->ordercode,0,4)."/".substr($row->ordercode,4,2)."/".substr($row->ordercode,6,2)." (".substr($row->ordercode,8,2).":".substr($row->ordercode,10,2).")";
				
				if($thisordcd!=$row->ordercode) {
					$thisordcd=$row->ordercode;
					if($thiscolor=="#FFFFFF") {
						$thiscolor="#FEF8ED";
					} else {
						$thiscolor="#FFFFFF";
					}
				}
				
				$sql = "SELECT * FROM tblorderproduct WHERE vender='".$row->vender."' AND ordercode='".$row->ordercode."' ";
				$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
				$result2=mysql_query($sql,get_db_conn());
				$jj=0;

				echo "<tr bgcolor=".$thiscolor.">\n";
				echo "	<td align=center>".$date." / ".$row->ordercode."</td>\n";
				echo "	<td  align=center>".$orderdate."</td>";
				echo "	<td  align=center>".(strlen($venderlist[$row->vender]->vender)>0?"<B>".$venderlist[$row->vender]->id."</B>":"-")."</td>\n";
				echo "	<td  colspan=\"4\">\n";
				echo "	<table border=1 cellpadding=0 cellspacing=0 width='100%'>\n";
				echo "	<col width=300></col>\n";
				echo "	<col width=30></col>\n";
				echo "	<col width=60></col>\n";
				echo "	<col width=55></col>\n";
				while($row2=mysql_fetch_object($result2)) {
					echo "<tr>\n";
					echo "	<td>".$row2->productname."</td>\n";
					echo "	<td align=center>".$row2->quantity."</td>\n";
					echo "	<td align=right>".number_format($row2->price*$row2->quantity)."&nbsp;</td>\n";
					echo "	<td align=right>".($row2->reserve>0?"-":"").number_format($row2->reserve*$row2->quantity)."&nbsp;</td>\n";
					echo "</tr>\n";
					$jj++;
				}
				mysql_free_result($result2);
				echo "	</table>\n";
				echo "	</td>\n";
				echo "	<td  align=right >".($row->sumdeliprice>0?"+":"").number_format($row->sumdeliprice)."&nbsp;</td>\n";
				echo "	<td  align=right >".number_format($row->sumcouprice)."&nbsp;</td>\n";
				echo "	<td  align=right ><B>".number_format($row->sumprice+$row->sumdeliprice-($row->sumreserve-$row->sumcouprice))."</B>&nbsp;</td>\n";
				echo "	<td  align=right ><B>".$row->rate."%</B>&nbsp;</td>\n";
				echo "	<td  align=right ><B>".number_format(($row->sumprice+$row->sumdeliprice-($row->sumreserve-$row->sumcouprice))*(100-$row->rate) / 100)."</B>&nbsp;</td>\n";
				echo "</tr>\n";
				$i++;
			}
			mysql_free_result($result);
		}
?>
</table>
</body>
</html>