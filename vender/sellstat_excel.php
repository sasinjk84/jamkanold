<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$ordercodes=substr($_POST["ordercodes"],0,-1);
$paystate=$_POST["paystate"];
$deli_gbn=$_POST["deli_gbn"];
$s_check=$_POST["s_check"];
$search=$_POST["search"];

$CurrentTime = time();

$search_start=$_POST["search_start"];
$search_end=$_POST["search_end"];
$search_s=$search_start?str_replace("-","",$search_start."000000"):str_replace("-","",$period[0]."000000");
$search_e=$search_end?str_replace("-","",$search_end."235959"):date("Ymd",$CurrentTime)."235959";

$tempstart = explode("-",$search_start);
$tempend = explode("-",$search_end);
$termday = (mktime(0,0,0,$tempend[1],$tempend[2],$tempend[0])-mktime(0,0,0,$tempstart[1],$tempstart[2],$tempstart[0]))/86400;
if ($termday>31) {
//	echo "<script>alert('주문서 EXCEL 다운로드 기간은 1달을 초과할 수 없습니다.');</script>";
//	exit;
}

/*
Header("Content-Type: application/octet-stream"); 
Header("Content-Disposition: attachment; filename=sellstat_excel_".date("Ymd",$CurrentTime).".csv"); 
Header("Pragma: no-cache"); 
Header("Expires: 0"); 
*/


header( "Content-type: application/vnd.ms-excel; charset=euc-kr" ); 
header( "Content-Disposition: attachment; filename=sellstat_excel_".date("Ymd",$CurrentTime).".xls"); 
header( "Content-Description: PHP4 Generated Data" ); 
print("<meta http-equiv=\"Content-Type\" content=\"application/vnd.ms-excel; charset=euc-kr\">");



/*
$excelnum=",0,1,2,3,4,5,6,7,8,9,10,11,";

$excelval=array(
	array("결제일"					,&$date),				#0		-
	array("정산일"					,&$comDate),		#1		-
	array("주문코드"				,&$ordercode),			#2		-
	array("상품명"					,&$productname),		#3		-
	array("수량"					,&$quantity),				#4		-
	array("판매"					,&$pay),				#5		-
	array("수수료"					,&$rate_val),		#6		-
	array("적립금"					,&$reserve),		#7		-
	array("배송료"					,&$sumdeliprice),		#8		-
	array("쿠폰할인"				,&$sumcouprice),				#9		-
	array("정산"					,&$sumadjust),				#10		-
	array("상태"					,&$status_value),			#11		-
);
*/
$today = date("Y-m-d",$CurrentTime);

$orderby=$_POST["orderby"];
if($orderby!="deli_date" && $orderby!="ordercode") $orderby="deli_date";

$vender = $_VenderInfo->getVidx();
$s_check=$_POST["s_check"];
$search_date=$_POST["search_date"];

$after_chk = "";
if ($search_date>$today) {
	$after_chk = "1";
}

//$arr_excel = explode(",",$excelnum);
//$cnt = count($arr_excel);

if(strlen($ordercodes)>0) $ordercodes="'".str_replace(",","','",$ordercodes)."'";

$sql = "SELECT SUM(IF((productcode!='99999999990X' AND NOT (productcode LIKE 'COU%')), price,NULL)) as sumprice, ";
$sql.= "SUM(reserve) as sumreserve, ";
$sql.= "SUM(deli_price) as sumdeliprice, ";
$sql.= "SUM(cou_price) as sumcouprice, ";
$sql.= "ordercode, deli_date, com_date,vender, sum(adjust) as sumadjust FROM `order_adjust_detail` a ";

$sql.= " WHERE vender='".$vender."' and a.deli_date between '".$search_s."' and '".$search_e."' ";

if(strlen($ordercodes)>0)
	$sql.= "AND a.ordercode IN (".$ordercodes.") ";
else
	$sql.= "AND a.ordercode >= '".$search_s."' AND a.ordercode <= '".$search_e."' ";

for($i=0;$i<strlen($s_check);$i++){
	if(strlen($s_check[$i])>0){
		
		$search_s_check .= "'".$s_check[$i]."',";
		if($s_check[$i]=="4"){
			$qry2_1= " AND (a.rate_price<0";
		}else{
			$s_checkArr .= "'".$s_check[$i]."',";
		}
	}
}

if($s_checkArr){
	$s_checkArr = substr($s_checkArr,0,strlen($s_checkArr) - 1);
	$qry2_2.= " a.status in (".$s_checkArr.")";

	if($qry2_1){
		$qry2 = $qry2_1." or ".$qry2_2.") ";
	}else{
		$qry2 = " AND ".$qry2_2." ";
	}
}else{
	$qry2 = $qry2_1.") ";
}



$sql.= $qry2;
$sql.= "GROUP BY ordercode, vender ORDER BY ".$orderby." DESC ";

$result = mysql_query($sql,get_db_conn());
/*
for($i=0;$i<$cnt;$i++) {
	if($i!=0) echo ",";
	echo $excelval[$arr_excel[$i]][0];
}
echo "\n";

$pattern = array("(\r\n)","(\")","(,)","(;)");
$replacement = array(" ","",".","");

$temp = "";
*/
?>
<table border=1 cellpadding=0 cellspacing=0 width=100%>
<col width=80></col> <!-- 결제일 -->
<col width=80></col> <!-- 정산일 -->
<col width=135></col> <!-- 주문코드 -->
<col width=></col> <!-- 상품명 -->
<col width=30></col> <!-- 수량 -->
<col width=60></col> <!-- 판매금액 -->
<col width=80></col> <!-- 수수료 -->
<col width=55></col> <!-- 적립금 -->
<col width=55></col> <!-- 총 배송료 -->
<col width=60></col> <!-- 쿠폰 사용액 -->
<!-- <col width=70></col> 결제금액 -->
<col width=100></col> <!-- 정산금액 -->
<col width=100></col> <!-- 정산상태 -->
<tr height=32 align=center bgcolor=F5F5F5>
	<td align=center ><B>결제일</B></td>
	<td align=center ><B>정산일</B></td>
	<td align=center ><B>주문코드</B></td>
	<td align=center ><B>상품명</B></td>
	<td align=center ><B>수량</B></td>
	<td align=center ><B>판매</B></td>
	<td align=center ><B>수수료 
	<? if($shop_relay=="1") {?>
	<br/>(수수료의 부가세)
	<? } ?>
	</B></td>
	<td align=center ><B>적립금</B></td>
	<td align=center ><B>배송료</B></td>
	<td align=center ><B>쿠폰할인</B></td>
	<!--td align=center ><B>결제금액</B></td-->
	<TD align=center ><B>정산</B></TD>
	<td align=center ><B>상태</B></td>
</tr>
<?
while ($row=mysql_fetch_object($result)) {
	if(strlen($temp)!=0) {
		for($i=0;$i<$cnt;$i++) {
			if($i!=0) echo ",";
			echo '"' . doubleQuote($excelval[$arr_excel[$i]][1]) . '"';
		}
		echo "\n";
	}

	$date = substr($row->deli_date,0,4)."/".substr($row->deli_date,4,2)."/".substr($row->deli_date,6,2)." (".substr($row->deli_date,8,2).":".substr($row->deli_date,10,2).")";
	
	$comDate = $row->com_date!=""? substr($row->com_date,0,4)."/".substr($row->com_date,4,2)."/".substr($row->com_date,6,2) : "";

	$ordercode = $row->ordercode;
	

	echo "<tr>\n";
	echo "	<td align=center>".$date."</td>\n";
	echo "	<td align=center>".$comDate."</td>\n";
	echo "	<td align=center><A HREF=\"javascript:OrderDetailView('".$row->ordercode."')\">".$row->ordercode."</A></td>\n";
	echo "	<td colspan=5>\n";
	echo "	<table border=1 cellpadding=0 cellspacing=0 width=100%>\n";
	echo "	<col width=></col>\n";
	echo "	<col width=30></col>\n";
	echo "	<col width=60></col>\n";
	echo "	<col width=80></col>\n";
	echo "	<col width=55></col>\n";


	$sql = "SELECT o.*,
			a.account_rule, a.rate, a.cost, a.status,
			a.relay, a.rate_price, a.surtax
			FROM tblorderproduct o left join order_adjust_detail a
			on o.ordercode=a.ordercode and o.productcode=a.productcode
			WHERE o.vender='".$row->vender."' AND o.ordercode='".$row->ordercode."' ";
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

		
		
		$productname = $row2->productname;
		$quantity = $row2->quantity;
		$pay = number_format($row2->price*$row2->quantity);

		$rate_val = 0;

		if ($a_rule =='1') {
			$rate_val = $row2->price*$row2->quantity - $cost." 원";
		}else{
			$rate_val = $rate_price." 원<br>".$rate." %";
		}

		if ($relay == "1") {
			$rate_val .= "<br/>";
			$rate_val .= "(".$surtax."원)";
		}

		$s_value ="";

		if ($row2->status != 1) {
			$status_chk++;
		}

//		if($jj>0) echo "<tr><td colspan=9 height=1 bgcolor=#E7E7E7></tr>";
		echo "<tr>\n";
		echo "	<td>".$row2->productname."</td>\n";
		echo "	<td align=center>".$row2->quantity."</td>\n";
		echo "	<td align=right>".number_format($row2->price*$row2->quantity)."&nbsp;</td>\n";
		echo "	<td align=right>".$rate_val."&nbsp;</td>\n";
		echo "	<td align=right>".($row2->reserve>0?"-":"").number_format($row2->reserve*$row2->quantity)."&nbsp;</td>\n";
		echo "</tr>\n";


		//$reserve = ($row2->reserve>0?"-":"").number_format($row2->reserve*$row2->quantity);
		$jj++;
	}
	mysql_free_result($result2);


	$sumdeliprice = ($row->sumdeliprice>0?"+":"").number_format($row->sumdeliprice);
	$sumcouprice = number_format($row->sumcouprice);
	$sumadjust = number_format($row->sumadjust);

	if ($status_chk==0) {
		if ($after_chk=="1") {
			$status_value = "정산진행중";
		}else{
			$status_value = "정산대기중";
		}

	}else{
		$status_value = "정산처리";
	}
	
	echo "	</table>\n";
	echo "	</td>\n";
	echo "	<td align=right>".($row->sumdeliprice>0?"+":"").number_format($row->sumdeliprice)."&nbsp;</td>\n";
	echo "	<td align=right>".number_format($row->sumcouprice)."&nbsp;</td>\n";
	//echo "	<td align=right><B>".number_format($row->sumprice+$row->sumdeliprice-($row->sumreserve-$row->sumcouprice))."</B>&nbsp;</td>\n";
	echo "	<td align=right><B>".number_format($row->sumadjust)."</B>&nbsp;</td>\n";
	echo "	<td align=center><B>".$status_value."</B>&nbsp;</td>\n";
	echo "</tr>\n";

	$i++;

}
mysql_free_result($result);
/*
for($i=0;$i<$cnt;$i++){
	if($i!=0) echo ",";
	echo '"' . doubleQuote($excelval[$arr_excel[$i]][1]) . '"';
}
echo "\n";

function &doubleQuote($str) {
	return str_replace('"', '""', $str);
}
*/
?>