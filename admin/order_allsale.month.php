<?
function get_totaldays($year,$month) {
	$date = 1;
	while(checkdate($month,$date,$year)) {
		$date++;
	}
	$date--;
	return $date;
}

$qry = "WHERE a.ordercode LIKE '".$date_year.$date_month."%' ";
if($paymethod!="ALL") $qry.= "AND paymethod LIKE '".$paymethod."%' ";
if($loc!="ALL") {
	if($loc=="기타") $qry.= "AND a.loc is NULL ";
	else $qry.= "AND a.loc = '".$loc."' ";
}

$sql = "SELECT ";




// 부분취소 체크
$pChk = "SELECT SUM(p.price) as rcSum FROM tblorderproduct p WHERE p.deli_gbn IN('Y','N','X','S') AND p.status = 'RC' AND p.ordercode=a.ordercode";

// 입금
$Yif = "( a.deli_gbn='Y' || a.deli_gbn='S' || a.deli_gbn='X' || a.deli_gbn='D' || a.deli_gbn='E' || a.deli_gbn='H' ) || ( IF( (".$pChk.") > 0 , TRUE, FALSE) ) ";
$sql.= "COUNT(IF(".$Yif.",1,NULL)) as Ycnt, ";
$sql.= "SUM(IF(".$Yif.",IF((".$pChk.") > 0,( (a.price)-( ".$pChk.") ),a.price),NULL)) as Ysum, ";

// 미처리 (부분취소 제외)
$Nif = "a.deli_gbn='N' && IF( (".$pChk.") > 0 , FALSE, TRUE)";
$sql.= "COUNT(IF(".$Nif.",1,NULL)) as Ncnt, ";
$sql.= "SUM(IF(".$Nif.",a.price,NULL)) as Nsum, ";

// 환불 (부분취소 포함) (취소는 배송전에만 되므로 표시안함)
$Rif = "a.deli_gbn='R' || IF( (".$pChk.") > 0 , TRUE, FALSE)";
$sql.= "COUNT(IF(".$Rif.",1,NULL)) as Rcnt, ";
$sql.= "SUM(IF(".$Rif.",IF((".$pChk.") > 0,(".$pChk."),a.price),NULL)) as Rsum, ";




$sql.= "substring(a.ordercode,1,8) as day FROM tblorderinfo a ";

if($age2>0 || $sex!="ALL") {
	$sql.= ", tblmember b ".$qry." AND a.id=b.id ";
	if($age1>0) {
		$start_year = (int)date("Y") - (int)$age2 +1;
		$end_year = (int)date("Y") - (int)$age1 +1;
		$s_year = substr((string)$start_year,2,2);
		$e_year = substr((string)$end_year,2,2);

		if ($start_year < 2000 && $end_year < 2000) {
			 $sql.= "AND ((LEFT(b.resno,2) BETWEEN '".$s_year."' AND '".$e_year."') AND MID(b.resno,7,1) < '3') ";
		} else if ($start_year < 2000 && $end_year > 1999) {
			 $sql.= "AND (((LEFT(b.resno,2) BETWEEN '".$s_year."' AND '99') AND MID(b.resno,7,1) < '3') ";
			 $sql.= "OR ((LEFT(b.resno,2) BETWEEN '00' AND '".$e_year."') ";
			 $sql.= "AND MID(b.resno,7,1) > '2')) ";
		} else if ($start_year > 1999 && $end_year > 1999) {
			$sql.= "AND ((LEFT(b.resno,2) BETWEEN '".$s_year."' AND '".$e_year."') ";
			$sql.= "AND MID(b.resno,7,1) > '2') ";
		}
	}
	if($sex=="M") {
		$sql.= "AND b.gender='1' ";
	} else if ($sex=="F") {
		$sql.= "AND b.gender='2' ";
	}
} else {
	$sql.= $qry." ";
	if($member=="Y") {
		$sql.= "AND MID(a.ordercode,21,1)!='X' ";
	} else if($member=="N") {
		$sql.= "AND MID(a.ordercode,21,1)='X' ";
	}
}
$sql.= "GROUP BY day ";
//echo $sql;

$MAX_barsize=100;
$Ysumtot=0;
$Rsumtot=0;
$Nsumtot=0;
$maxsum=0;

$Ycnttot=0;
$Rcnttot=0;
$Ncnttot=0;

$result = mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	$day=(int)substr($row->day,6,2);
	$Ysum[$day]=$row->Ysum;
	$Rsum[$day]=$row->Rsum;
	$Nsum[$day]=$row->Nsum;
	if($row->Ysum>$maxsum) $maxsum=$row->Ysum;
	if($row->Rsum>$maxsum) $maxsum=$row->Rsum;
	if($row->Nsum>$maxsum) $maxsum=$row->Nsum;

	$Ycnt[$day]=$row->Ycnt;
	$Rcnt[$day]=$row->Rcnt;
	$Ncnt[$day]=$row->Ncnt;

	$Ysumtot+=$row->Ysum;
	$Rsumtot+=$row->Rsum;
	$Nsumtot+=$row->Nsum;

	$Ycnttot+=$row->Ycnt;
	$Rcnttot+=$row->Rcnt;
	$Ncnttot+=$row->Ncnt;
}
mysql_free_result($result);
?>
<TABLE cellSpacing=0 cellPadding=0 width="760" border=0>
	<TR>
		<TD background="images/table_top_line.gif" width="80"><img src="images/table_top_line.gif"></TD>
		<TD background="images/table_top_line.gif" width="680" colspan="2"></TD>
		<TD background="images/table_top_line.gif"></TD>
		<TD background="images/table_top_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell" width="60"><p align="center">날짜</TD>
		<TD class="table_cell1" width="131"><p align="center">매출현황</p></TD>
		<TD class="table_cell1" width="519"><p align="center">해당건수 ( <b><font color="#00ACD9">입금■</font></b> &nbsp;<b><font color="#58BE0E">반송■</font></b> &nbsp;&nbsp;<font color="#FFA800"><b>미입금■ </b></font>)</p></TD>
		<td></td>
		<TD class="table_cell1" width="200"><p align="center">배송완료상품통계</p></TD>
	</TR>
	<TR>
		<TD colspan="5" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></TD>
	</TR>
<?
$totaldays = get_totaldays($date_year,$date_month);
for($i=1;$i<=$totaldays;$i++) {
	$nowWeek = date("w", mktime(0,0,0,$date_month,$i,$date_year));

	if(($date_year.$date_month.$i)==date("Ymj")) {
		$tdclass="td_con_orange1";
		$tdclass2="td_con_orange";
	} else if($nowWeek==0) {
		$tdclass="td_con_red1";
		$tdclass2="td_con_red";
	} else if($nowWeek==6) {
		$tdclass="td_con_blue1";
		$tdclass2="td_con_blue";
	}  else {
		$tdclass="td_con2";
		$tdclass2="td_con1a";
	}

	echo "<tr>\n";
	echo "	<TD class=\"$tdclass\" width=\"73\"><p align=\"center\">".$i."일</td>\n";
	echo "	<TD class=\"$tdclass2\" width=\"139\">\n";
	echo "	<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"54\"><p style='font-size:11px;color:#00ACD9'>입금</p></td>\n";
	echo "		<td width=\"111\"><p align=\"right\">".number_format($Ysum[$i])."원</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"34\"><p style='font-size:11px;color:#58BE0E'>반송</p></td>\n";
	echo "		<td width=\"111\"><p align=\"right\">".number_format($Rsum[$i])."원</td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"34\"><p style='font-size:11px;color:#FFA800'>미입금</p></td>\n";
	echo "		<td width=\"111\"><p align=\"right\">".number_format($Nsum[$i])."원</td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "	</td>\n";
	echo "	<TD class=\"$tdclass2\" width=\"527\">\n";
	echo "	<table cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">\n";
	echo "	<tr>\n";
	echo "		<td width=\"479\">\n";
	echo "		<div align=\"right\"><table cellpadding=\"0\" cellspacing=\"0\" width=\"".(@round(($Ysum[$i] / $maxsum)*$MAX_barsize)>0?@round(($Ysum[$i] / $maxsum)*$MAX_barsize):"1")."%\">\n";
	echo "		<tr>\n";
	echo "			<td width=\"516\" height=\"15\" background=\"images/line_bg1.gif\"></td>\n";
	echo "		</tr>\n";
	echo "		</table></div>\n";
	echo "		</td>\n";
	echo "		<td width=\"80\"><p align=\"right\">(".number_format($Ycnt[$i])."건)</p></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"479\">\n";
	echo "		<div align=\"right\"><table cellpadding=\"0\" cellspacing=\"0\" width=\"".(@round(($Rsum[$i] / $maxsum)*$MAX_barsize)>0?@round(($Rsum[$i] / $maxsum)*$MAX_barsize):"1")."%\">\n";
	echo "		<tr>\n";
	echo "			<td width=\"516\" height=\"15\" background=\"images/line_bg2.gif\"></td>\n";
	echo "		</tr>\n";
	echo "		</table></div>\n";
	echo "		</td>\n";
	echo "		<td width=\"80\"><p align=\"right\">(".number_format($Rcnt[$i])."건)</p></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td width=\"479\">\n";
	echo "		<div align=\"right\"><table cellpadding=\"0\" cellspacing=\"0\" width=\"".(@round(($Nsum[$i] / $maxsum)*$MAX_barsize)>0?@round(($Nsum[$i] / $maxsum)*$MAX_barsize):"1")."%\">\n";
	echo "		<tr>\n";
	echo "			<td width=\"516\" height=\"15\" background=\"images/line_bg3.gif\"></td>\n";
	echo "		</tr>\n";
	echo "		</table></div>\n";
	echo "		</td>\n";
	echo "		<td width=\"80\"><p align=\"right\">(".number_format($Ncnt[$i])."건)</p></td>\n";
	echo "	</tr>\n";
	echo "	</table>\n";
	echo "	</td>\n";
	echo "	<td></td>\n";
	echo "	<td align=\"center\"><img src=\"./images/btn_productaverage.gif\" style=\"cursor:pointer;\" onclick=\"allsaleProduct('".$date_month."','".$i."');\"></td>\n";
	echo "</tr>\n";

	if($i != $totaldays) {
		echo "<tr>\n";
		echo "	<TD colspan=\"5\" width=\"760\" background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>\n";
		echo "</tr>\n";
	}
}
?>
<TR>
	<TD background="images/table_con_line1.gif" width="80"><img src="images/table_con_line1.gif" width="4" height="1" border="0"></TD>
	<TD background="images/table_con_line1.gif" width="680" colspan="4"></TD>
	<td></td>
</TR>
<tr>
	<TD class="td_con2" width="73"><p align="center">합계</td>
	<TD class="td_con1a" width="139">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="34"><p><img src="images/icon_trans.gif" width="41" height="15" border="0"></p></td>
		<td width="111"><p align="right"><?=number_format($Ysumtot)?>원</td>
	</tr>
	<tr>
		<td width="34"><p><img src="images/icon_back.gif" width="41" height="15" border="0"></p></td>
		<td width="111"><p align="right"><?=number_format($Rsumtot)?>원</td>
	</tr>
	<tr>
		<td width="34"><p><img src="images/icon_not.gif" width="41" height="15" border="0"></p></td>
		<td width="111"><p align="right"><?=number_format($Nsumtot)?>원</td>
	</tr>
	</table>
	</td>
	<TD class="td_con1a" width="527">
	<table cellpadding="1" cellspacing="0" width="100%">
	<tr>
		<td width="479">
		<div align="right"><table cellpadding="0" cellspacing="0" width="<?=(@round(($Ysumtot / ($Ysumtot+$Rsumtot+$Nsumtot))*$MAX_barsize)>0?@round(($Ysumtot / ($Ysumtot+$Rsumtot+$Nsumtot))*$MAX_barsize):"1")?>%">
		<tr>
			<td width="516" height="15" background="images/line_bg1.gif"></td>
		</tr>
		</table></div>
		</td>
		<td width="80"><p align="right">(<?=number_format($Ycnttot)?>건)</p></td>
	</tr>
	<tr>
		<td width="479">
		<div align="right"><table cellpadding="0" cellspacing="0" width="<?=(@round(($Rsumtot / ($Ysumtot+$Rsumtot+$Nsumtot))*$MAX_barsize)>0?@round(($Rsumtot / ($Ysumtot+$Rsumtot+$Nsumtot))*$MAX_barsize):"1")?>%">
		<tr>
			<td width="516" height="15" background="images/line_bg2.gif"></td>
		</tr>
		</table></div>
		</td>
		<td width="80"><p align="right">(<?=number_format($Rcnttot)?>건)</p></td>
	</tr>
	<tr>
		<td width="479">
		<div align="right"><table cellpadding="0" cellspacing="0" width="<?=(@round(($Nsumtot / ($Ysumtot+$Rsumtot+$Nsumtot))*$MAX_barsize)>0?@round(($Nsumtot / ($Ysumtot+$Rsumtot+$Nsumtot))*$MAX_barsize):"1")?>%">
		<tr>
			<td width="516" height="15" background="images/line_bg3.gif"></td>
		</tr>
		</table></div>
		</td>
		<td width="80"><p align="right">(<?=number_format($Ncnttot)?>건)</p></td>
	</tr>
	</table>
	</td>
	<td></td>
	<td align="center"><img src="./images/btn_productaverage.gif" style="cursor:pointer;" onclick="allsaleProduct('<?=$date_month?>','all');"></td>
</tr>
<TR>
	<TD background="images/table_con_line1.gif" width="80"><img src="images/table_con_line1.gif" width="4" height="1" border="0"></TD>
	<TD background="images/table_con_line1.gif" width="680" colspan="4"></TD>
	<td></td>
</TR>
</table>