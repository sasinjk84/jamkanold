<?
$qry = "WHERE a.ordercode = b.ordercode ";
$qry.= "AND a.ordercode LIKE '".$date_year.$date_month."%' AND b.ordercode LIKE '".$date_year.$date_month."%' ";
$qry.= "AND b.vender='".$_VenderInfo->getVidx()."' ";
if($loc!="ALL") {
	if($loc=="��Ÿ") $qry.= "AND a.loc is NULL ";
	else $qry.= "AND a.loc = '".$loc."' ";
}
if(strlen($prcode)>0) {
	$qry.= "AND b.productcode = '".$prcode."' ";
} else if(strlen($code)>=3) {
	$qry.= "AND b.productcode LIKE '".$likecode."%' ";
}

$sql = "SELECT ";
$sql.= "SUM(IF(b.deli_gbn='Y',b.quantity,NULL)) as Ycnt, ";
$sql.= "SUM(IF(b.deli_gbn='N' OR b.deli_gbn='S',b.quantity,NULL)) as Ncnt, ";
$sql.= "SUM(IF(b.deli_gbn='R',b.quantity,NULL)) as Rcnt, ";
$sql.= "SUM(IF(b.deli_gbn='Y',b.price*b.quantity,NULL)) as Ysum, ";
$sql.= "SUM(IF(b.deli_gbn='N' OR b.deli_gbn='S',b.price*b.quantity,NULL)) as Nsum, ";
$sql.= "SUM(IF(b.deli_gbn='R',b.price*b.quantity,NULL)) as Rsum, ";
$sql.= "substring(a.ordercode,1,6) as month FROM tblorderinfo a, tblorderproduct b ";

if($age2>0 || $sex!="ALL") {
	$sql.= ", tblmember c ".$qry." AND a.id=c.id ";
	if($age1>0) {
		$start_year = (int)date("Y") - (int)$age2 +1;
		$end_year = (int)date("Y") - (int)$age1 +1;
		$s_year = substr((string)$start_year,2,2);
		$e_year = substr((string)$end_year,2,2);

		if ($start_year < 2000 && $end_year < 2000) {
			 $sql.= "AND (LEFT(c.resno,2) BETWEEN '".$s_year."' AND '".$e_year."') AND MID(c.resno,7,1) < '3' ";
		} else if ($start_year < 2000 && $end_year > 1999) {
			 $sql.= "AND (((LEFT(c.resno,2) BETWEEN '".$s_year."' AND '99') AND MID(c.resno,7,1) < '3') ";
			 $sql.= "OR ((LEFT(c.resno,2) BETWEEN '00' AND '".$e_year."') ";
			 $sql.= "AND MID(c.resno,7,1) > '2')) ";
		} else if ($start_year > 1999 && $end_year > 1999) {
			$sql.= "AND (LEFT(c.resno,2) BETWEEN '".$s_year."' AND '".$e_year."') ";
			$sql.= "AND MID(c.resno,7,1) > '2') ";
		}
	}
	if($sex=="M") {
		$sql.= "AND MID(c.resno,7,1)='1' ";
	} else if ($sex=="F") {
		$sql.= "AND MID(c.resno,7,1)='2' ";
	}
} else {
	$sql.= $qry." ";
	if($member=="Y") {
		$sql.= "AND MID(a.ordercode,21,1)!='X' ";
	} else if($member=="N") {
		$sql.= "AND MID(a.ordercode,21,1)='X' ";
	}
}
$sql.= "GROUP BY month ";


$MAX_barsize=420;
$Ysumtot=0;
$Rsumtot=0;
$Nsumtot=0;
$maxsum=0;

$Ycnttot=0;
$Rcnttot=0;
$Ncnttot=0;

$result = mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	$month=(int)substr($row->month,4,2);
	$Ysum[$month]=$row->Ysum;
	$Rsum[$month]=$row->Rsum;
	$Nsum[$month]=$row->Nsum;
	if($row->Ysum>$maxsum) $maxsum=$row->Ysum;
	if($row->Rsum>$maxsum) $maxsum=$row->Rsum;
	if($row->Nsum>$maxsum) $maxsum=$row->Nsum;

	$Ycnt[$month]=$row->Ycnt;
	$Rcnt[$month]=$row->Rcnt;
	$Ncnt[$month]=$row->Ncnt;

	$Ysumtot+=$row->Ysum;
	$Rsumtot+=$row->Rsum;
	$Nsumtot+=$row->Nsum;

	$Ycnttot+=$row->Ycnt;
	$Rcnttot+=$row->Rcnt;
	$Ncnttot+=$row->Ncnt;
}
mysql_free_result($result);
?>

<table border=0 cellpadding=5 cellspacing=1 width=100% bgcolor="#D4D4D4">
<tr>
	<td width=60 nowrap></td>
	<td width=130 nowrap></td>
	<td width=100%></td>
</tr>
<tr height=35 bgcolor=#F0F0F0>
	<td colspan=3 align=right style="padding-right:5">
	<img src="images/bar_delivery.jpg" width=25 height=15 align=absmiddle> ���
	<img width=10 height=0>
	<img src="images/bar_return.jpg" width=25 height=15 align=absmiddle> �ݼ�
	<img width=10 height=0>
	<img src="images/bar_delay.jpg" width=25 height=15 align=absmiddle> ��ó��
	</td>
</tr>
<?
for($i=1;$i<=12;$i++) {
	$trbgcolor="#FFFFFF";
	if(($date_year.$i)==date("Yn")) $trbgcolor="#FEF7F1";
	echo "<tr bgcolor=".$trbgcolor.">\n";
	echo "	<td align=center nowrap>".$i."��</td>\n";
	echo "	<td align=right style=\"line-height:12pt\">";
	echo "	".number_format($Ysum[$i])."��";
	echo"	<br>";
	echo "	".number_format($Rsum[$i])."��";
	echo "	<br>";
	echo "	".number_format($Nsum[$i])."��";
	echo "	</td>\n";
	echo "	<td style=\"font-size:8pt;color:#868686;line-height:1pt\">";
	echo "	<img src=\"images/bar_delivery.jpg\" width=".@round(($Ysum[$i] / $maxsum)*$MAX_barsize)." height=11 align=absmiddle> (".number_format($Ycnt[$i])."��)";
	echo "	<br><br><br>";
	echo "	<img src=\"images/bar_return.jpg\" width=".@round(($Rsum[$i] / $maxsum)*$MAX_barsize)." height=11 align=absmiddle> (".number_format($Rcnt[$i])."��)";
	echo "	<br><br><br>";
	echo "	<img src=\"images/bar_delay.jpg\" width=".@round(($Nsum[$i] / $maxsum)*$MAX_barsize)." height=11 align=absmiddle> (".number_format($Ncnt[$i])."��)";
	echo "	</td>\n";
	echo "</tr>\n";
}
?>
<tr bgcolor=#FFFFFF>
	<td align=center nowrap><B>�հ�</B></td>
	<td align=right style="font-weight:bold;line-height:12pt">
	<?=number_format($Ysumtot)?>��
	<br>
	<?=number_format($Rsumtot)?>��
	<br>
	<?=number_format($Nsumtot)?>��
	</td>
	<td style="font-size:8pt;color:#868686;font-weight:bold;line-height:1pt">
	<img src="images/bar_delivery.jpg" width="<?=@round(($Ysumtot / ($Ysumtot+$Rsumtot+$Nsumtot))*$MAX_barsize)?>" height=11 align=absmiddle> (<?=number_format($Ycnttot)?>��)
	<br><br><br>
	<img src="images/bar_return.jpg" width="<?=@round(($Rsumtot / ($Ysumtot+$Rsumtot+$Nsumtot))*$MAX_barsize)?>" height=11 align=absmiddle> (<?=number_format($Rcnttot)?>��)
	<br><br><br>
	<img src="images/bar_delay.jpg" width="<?=@round(($Nsumtot / ($Ysumtot+$Rsumtot+$Nsumtot))*$MAX_barsize)?>" height=11 align=absmiddle> (<?=number_format($Ncnttot)?>��)
	</td>
</tr>
</table>