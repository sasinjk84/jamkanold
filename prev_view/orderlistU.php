<?
$menu_myhome="".$Dir.FrontDir."mypage.php";
$menu_myorder="".$Dir.FrontDir."mypage_orderlist.php";
$menu_mypersonal="".$Dir.FrontDir."mypage_personal.php";
$menu_mywish="".$Dir.FrontDir."wishlist.php";
$menu_myreserve="".$Dir.FrontDir."mypage_reserve.php";
$menu_mycoupon="".$Dir.FrontDir."mypage_coupon.php";
$menu_myinfo="".$Dir.FrontDir."mypage_usermodify.php";
$menu_myout="".$Dir.FrontDir."mypage_memberout.php";
if(getVenderUsed()==true) { $menu_mycustsect=$Dir.FrontDir."mypage_custsect.php"; } 

if(strpos($body,"[IFORDER]")!=0) {
	$ifordernum=strpos($body,"[IFORDER]");
	$endordernum=strpos($body,"[IFENDORDER]");
	$elseordernum=strpos($body,"[IFELSEORDER]");

	$orderstartnum=strpos($body,"[FORORDER]");
	$orderstopnum=strpos($body,"[FORENDORDER]");

	$iforder=substr($body,$ifordernum+9,$orderstartnum-($ifordernum+9))."[ORDERVALUE]".substr($body,$orderstopnum+13,$elseordernum-($orderstopnum+13));

	$noorder=substr($body,$elseordernum+13,$endordernum-$elseordernum-13);

	$mainorder=substr($body,$orderstartnum,$orderstopnum-$orderstartnum+13);

	$productstartnum=strpos($mainorder,"[FORPRODUCT]");
	$productstopnum=strpos($mainorder,"[FORENDPRODUCT]");

	$mainproduct=substr($mainorder,$productstartnum,$productstopnum-$productstartnum+15);

	$ifdelisearchnum=strpos($mainproduct,"[IFDELISEARCH]");
	$enddelisearchnum=strpos($mainproduct,"[IFENDDELISEARCH]");
	$elsedelisearchnum=strpos($mainproduct,"[IFELSEDELISEARCH]");

	$ifdelisearch=substr($mainproduct,$ifdelisearchnum+14,$elsedelisearchnum-($ifdelisearchnum+14));
	$nodelisearch=substr($mainproduct,$elsedelisearchnum+18,$enddelisearchnum-$elsedelisearchnum-18);
	$mainproduct=substr($mainproduct,0,$ifdelisearchnum)."[DELISEARCHVALUE]".substr($mainproduct,$enddelisearchnum+17);

	$mainorder=substr($mainorder,0,$productstartnum)."[ORIGINALPRODUCT]".substr($mainorder,$productstopnum+15);

	$body=substr($body,0,$ifordernum)."[ORIGINALORDER]".substr($body,$endordernum+12);
}

$ord1="";
$ord2="";
$ord3="";
$ord4="";
if (preg_match("/\[ORD1([a-zA-Z0-9_?\/\-.]+)\]/",$body,$match)) {
	$ord1_tmp=substr($match[1],1);
	$ord1_val=explode("_",$ord1_tmp);
	$ord1_off=$ord1_val[0];
	$ord1_on=$ord1_val[1];
	if(strlen($ord1_on)==0) $ord1_on=$ord1_off;
	if($ordgbn=="A") {
		$ord1="<A HREF=\"javascript:GoOrdGbn('A')\"><img src=\"".$ord1_on."\" border=0></A>";
	} else {
		$ord1="<A HREF=\"javascript:GoOrdGbn('A')\"><img src=\"".$ord1_off."\" border=0></A>";
	}
}
if (preg_match("/\[ORD2([a-zA-Z0-9_?\/\-.]+)\]/",$body,$match)) {
	$ord2_tmp=substr($match[1],1);
	$ord2_val=explode("_",$ord2_tmp);
	$ord2_off=$ord2_val[0];
	$ord2_on=$ord2_val[1];
	if(strlen($ord2_on)==0) $ord2_on=$ord2_off;
	if($ordgbn=="S") {
		$ord2="<A HREF=\"javascript:GoOrdGbn('S')\"><img src=\"".$ord2_on."\" border=0></A>";
	} else {
		$ord2="<A HREF=\"javascript:GoOrdGbn('S')\"><img src=\"".$ord2_off."\" border=0></A>";
	}
}
if (preg_match("/\[ORD3([a-zA-Z0-9_?\/\-.]+)\]/",$body,$match)) {
	$ord3_tmp=substr($match[1],1);
	$ord3_val=explode("_",$ord3_tmp);
	$ord3_off=$ord3_val[0];
	$ord3_on=$ord3_val[1];
	if(strlen($ord3_on)==0) $ord3_on=$ord3_off;
	if($ordgbn=="C") {
		$ord3="<A HREF=\"javascript:GoOrdGbn('C')\"><img src=\"".$ord3_on."\" border=0></A>";
	} else {
		$ord3="<A HREF=\"javascript:GoOrdGbn('C')\"><img src=\"".$ord3_off."\" border=0></A>";
	}
}
if (preg_match("/\[ORD4([a-zA-Z0-9_?\/\-.]+)\]/",$body,$match)) {
	$ord4_tmp=substr($match[1],1);
	$ord4_val=explode("_",$ord4_tmp);
	$ord4_off=$ord4_val[0];
	$ord4_on=$ord4_val[1];
	if(strlen($ord4_on)==0) $ord4_on=$ord4_off;
	if($ordgbn=="R") {
		$ord4="<A HREF=\"javascript:GoOrdGbn('R')\"><img src=\"".$ord4_on."\" border=0></A>";
	} else {
		$ord4="<A HREF=\"javascript:GoOrdGbn('R')\"><img src=\"".$ord4_off."\" border=0></A>";
	}
}


$search_btn1="\"javascript:GoSearch('TODAY')\"";
$search_btn2="\"javascript:GoSearch('15DAY')\"";
$search_btn3="\"javascript:GoSearch('1MONTH')\"";
$search_btn4="\"javascript:GoSearch('3MONTH')\"";
$search_btn5="\"javascript:GoSearch('6MONTH')\"";

$search_date ="<select name=\"s_year\" onchange=\"ChangeDate('s')\" style=\"font-size:11px\">\n";
for($i=date("Y");$i>=(date("Y")-2);$i--) {
	$search_date.="<option value=\"".$i."\"";
	if($s_year==$i) $search_date.=" selected";
	$search_date.=" style=\"color:#444444\">".$i."</option>\n";
}
$search_date.="</select> ��\n";
$search_date.="<select name=\"s_month\" onchange=\"ChangeDate('s')\" style=\"font-size:11px\">\n";
for($i=1;$i<=12;$i++) {
	$search_date.="<option value=\"".$i."\"";
	if($s_month==$i) $search_date.=" selected";
	$search_date.=" style=\"color:#444444\">".$i."</option>\n";
}
$search_date.="</select> ��\n";
$search_date.="<select name=\"s_day\" style=\"font-size:11px\">\n";
for($i=1;$i<=get_totaldays($s_year,$s_month);$i++) {
	$search_date.="<option value=\"".$i."\"";
	if($s_day==$i) $search_date.=" selected";
	$search_date.=" style=\"color:#444444\">".$i."</option>\n";
}
$search_date.="</select> ��\n";
$search_date.="~ \n";
$search_date.="<select name=\"e_year\" onchange=\"ChangeDate('e')\" style=\"font-size:11px\">\n";
for($i=date("Y");$i>=(date("Y")-2);$i--) {
	$search_date.="<option value=\"".$i."\"";
	if($e_year==$i) $search_date.=" selected";
	$search_date.=" style=\"color:#444444\">".$i."</option>\n";
}
$search_date.="</select> ��\n";
$search_date.="<select name=\"e_month\" onchange=\"ChangeDate('e')\" style=\"font-size:11px\">\n";
for($i=1;$i<=12;$i++) {
	$search_date.="<option value=\"".$i."\"";
	if($e_month==$i) $search_date.=" selected";
	$search_date.=" style=\"color:#444444\">".$i."</option>\n";
}
$search_date.="</select> ��\n";
$search_date.="<select name=\"e_day\" style=\"font-size:11px\">\n";
for($i=1;$i<=get_totaldays($e_year,$e_month);$i++) {
	$search_date.="<option value=\"".$i."\"";
	if($e_day==$i) $search_date.=" selected";
	$search_date.=" style=\"color:#444444\">".$i."</option>\n";
}
$search_date.="</select> ��\n";

$search_ok="\"javascript:CheckForm()\"";


$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
$result=mysql_query($sql,get_db_conn());
$delicomlist=array();
while($row=mysql_fetch_object($result)) {
	$delicomlist[$row->code]=$row;
}
mysql_free_result($result);

$s_curtime=mktime(0,0,0,$s_month,$s_day,$s_year);
$s_curdate=date("Ymd",$s_curtime);
$e_curtime=mktime(0,0,0,$e_month,$e_day,$e_year);
$e_curdate=date("Ymd",$e_curtime)."999999999999";


$sql = "SELECT COUNT(*) as t_count FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$t_count = (int)$row->t_count;
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

$sql = "SELECT ordercode, price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn ";
$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
$sql.= "AND ordercode >= '".$s_curdate."' AND ordercode <= '".$e_curdate."' ";
if($ordgbn=="S") $sql.= "AND deli_gbn IN ('S','Y','N','X') ";
else if($ordgbn=="C") $sql.= "AND deli_gbn IN ('C','D') ";
else if($ordgbn=="R") $sql.= "AND deli_gbn IN ('R','E') ";
$sql.= "AND (del_gbn='N' OR del_gbn='A') ";
$sql.= "ORDER BY ordercode DESC ";
$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
$result=mysql_query($sql,get_db_conn());
$cnt=0;
while($row=mysql_fetch_object($result)) {
	$temporder.=$mainorder;

	$order_date=substr($row->ordercode,0,4).".".substr($row->ordercode,4,2).".".substr($row->ordercode,6,2);
	if (preg_match("/^(B){1}/",$row->paymethod)) $order_method="������ �Ա�";
	else if (preg_match("/^(V){1}/",$row->paymethod)) $order_method="�ǽð�������ü";
	else if (preg_match("/^(O){1}/",$row->paymethod)) $order_method="�������";
	else if (preg_match("/^(Q){1}/",$row->paymethod)) $order_method="�������-<FONT COLOR=\"red\">�Ÿź�ȣ</FONT>";
	else if (preg_match("/^(C){1}/",$row->paymethod)) $order_method="�ſ�ī��";
	else if (preg_match("/^(P){1}/",$row->paymethod)) $order_method="�ſ�ī��-<FONT COLOR=\"red\">�Ÿź�ȣ</FONT>";
	else if (preg_match("/^(M){1}/",$row->paymethod)) $order_method="�޴���";
	else $order_method="";

	$order_price=number_format($row->price);
	$order_detail="\"javascript:OrderDetailPop('".$row->ordercode."')\"";

	$sql = "SELECT * FROM tblorderproduct WHERE ordercode='".$row->ordercode."' ";
	$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";
	$result2=mysql_query($sql,get_db_conn());
	$jj=0;
	$originalproduct="";
	while($row2=mysql_fetch_object($result2)) {
		$tempproduct=$mainproduct;

		$order_name=$row2->productname;
		$order_delistat="";
		if ($row2->deli_gbn=="C") $order_delistat="�ֹ����";
		else if ($row2->deli_gbn=="D") $order_delistat="��ҿ�û";
		else if ($row2->deli_gbn=="E") $order_delistat="ȯ�Ҵ��";
		else if ($row2->deli_gbn=="X") $order_delistat="�߼��غ�";
		else if ($row2->deli_gbn=="Y") $order_delistat="�߼ۿϷ�";
		else if ($row2->deli_gbn=="N") {
			if (strlen($row->bank_date)<12 && preg_match("/^(B|O|Q){1}/", $row->paymethod)) $order_delistat="�Ա�Ȯ����";
			else if ($row->pay_admin_proc=="C" && $row->pay_flag=="0000") $order_delistat="�������";
			else if (strlen($row->bank_date)>=12 || $row->pay_flag=="0000") $order_delistat="�߼��غ�";
			else $order_delistat="����Ȯ����";
		} else if ($row2->deli_gbn=="S") {
			$order_delistat="�߼��غ�";
		} else if ($row2->deli_gbn=="R") {
			$order_delistat="�ݼ�ó��";
		} else if ($row2->deli_gbn=="H") {
			$order_delistat="�߼ۿϷ� [���꺸��]";
		}

		$order_delicom="";
		$order_delisearch="";

		$deli_url="";
		$trans_num="";
		$company_name="";
		if($row2->deli_gbn=="Y") {
			if($row2->deli_com>0 && $delicomlist[$row2->deli_com]) {
				$deli_url=$delicomlist[$row2->deli_com]->deli_url;
				$trans_num=$delicomlist[$row2->deli_com]->trans_num;
				$company_name=$delicomlist[$row2->deli_com]->company_name;

				$order_delicom=$company_name;

				if(strlen($row2->deli_num)>0 && strlen($deli_url)>0) {
					if(strlen($trans_num)>0) {
						$arrtransnum=explode(",",$trans_num);
						$pattern=array("(\[1\])","(\[2\])","(\[3\])","(\[4\])");
						$replace=array(substr($row2->deli_num,0,$arrtransnum[0]),substr($row2->deli_num,$arrtransnum[0],$arrtransnum[1]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1],$arrtransnum[2]),substr($row2->deli_num,$arrtransnum[0]+$arrtransnum[1]+$arrtransnum[2],$arrtransnum[3]));
						$deli_url=preg_replace($pattern,$replace,$deli_url);
					} else {
						$deli_url.=$row2->deli_num;
					}
					$order_delisearch="javascript:DeliSearch('".$deli_url."')";
				}
				$pattern=array("(\[ORDER_DELICOM\])","(\[ORDER_DELISEARCH\])");
				$replace=array($order_delicom,$order_delisearch);

				$delisearchval=preg_replace($pattern,$replace,$ifdelisearch);
			} else {
				$delisearchval=$nodelisearch;
			}
		} else {
			$delisearchval=$nodelisearch;
		}

		$pattern=array("(\[ORDER_NAME\])","(\[ORDER_DELISTAT\])","(\[ORDER_DETAIL\])","(\[DELISEARCHVALUE\])","(\[FORPRODUCT\])","(\[FORENDPRODUCT\])");
		$replace=array($order_name,$order_delistat,$order_detail,$delisearchval,"","");
		$originalproduct.=preg_replace($pattern,$replace,$tempproduct);
	}
	mysql_free_result($result2);

	$cnt++;

	$pattern=array("(\[ORDER_DATE\])","(\[ORDER_METHOD\])","(\[ORDER_PRICE\])","(\[ORDER_DETAIL\])","(\[ORIGINALPRODUCT\])","(\[FORORDER\])","(\[FORENDORDER\])");
	$replace=array($order_date,$order_method,$order_price,$order_detail,$originalproduct,"","");

	$temporder=preg_replace($pattern,$replace,$temporder);
}
mysql_free_result($result);

if($cnt>0) {
	$originalorder=$iforder;
	$pattern=array("(\[ORDERVALUE\])");
	$replace=array($temporder);
	$originalorder=preg_replace($pattern,$replace,$originalorder);
} else {
	$originalorder=$noorder;
}

$total_block = intval($pagecount / $setup[page_num]);

if (($pagecount % $setup[page_num]) > 0) {
	$total_block = $total_block + 1;
}

$total_block = $total_block - 1;

if (ceil($t_count/$setup[list_num]) > 0) {
	// ����	x�� ����ϴ� �κ�-����
	$a_first_block = "";
	if ($nowblock > 0) {
		$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><FONT class=\"prlist\">[1...]</FONT></a>&nbsp;&nbsp;";

		$prev_page_exists = true;
	}

	$a_prev_page = "";
	if ($nowblock > 0) {
		$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><FONT class=\"prlist\">[prev]</FONT></a>&nbsp;&nbsp;";

		$a_prev_page = $a_first_block.$a_prev_page;
	}

	// �Ϲ� �������� ������ ǥ�úκ�-����

	if (intval($total_block) <> intval($nowblock)) {
		$print_page = "";
		for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
			if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
				$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$setup[page_num]) + $gopage)."</font> ";
			} else {
				$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</FONT></a> ";
			}
		}
	} else {
		if (($pagecount % $setup[page_num]) == 0) {
			$lastpage = $setup[page_num];
		} else {
			$lastpage = $pagecount % $setup[page_num];
		}

		for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
			if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
				$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$setup[page_num]) + $gopage)."</FONT> ";
			} else {
				$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</FONT></a> ";
			}
		}
	}		// ������ �������� ǥ�úκ�-��


	$a_last_block = "";
	if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
		$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
		$last_gotopage = ceil($t_count/$setup[list_num]);

		$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><FONT class=\"prlist\">[...".$last_gotopage."]</FONT></a>";

		$next_page_exists = true;
	}

	// ���� 10�� ó���κ�...

	$a_next_page = "";
	if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
		$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><FONT class=\"prlist\">[next]</FONT></a>";

		$a_next_page = $a_next_page.$a_last_block;
	}
} else {
	$print_page = "<FONT class=\"prlist\">1</FONT>";
}

$page=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;


$pattern=array(
	"(\[MENU_MYHOME\])",
	"(\[MENU_MYORDER\])",
	"(\[MENU_MYPERSONAL\])",
	"(\[MENU_MYWISH\])",
	"(\[MENU_MYRESERVE\])",
	"(\[MENU_MYCOUPON\])",
	"(\[MENU_MYINFO\])",
	"(\[MENU_MYOUT\])",
	"(\[MENU_MYCUSTSECT\])",

	"(\[SEARCH_BTN1\])",
	"(\[SEARCH_BTN2\])",
	"(\[SEARCH_BTN3\])",
	"(\[SEARCH_BTN4\])",
	"(\[SEARCH_BTN5\])",
	"(\[SEARCH_DATE\])",
	"(\[SEARCH_OK\])",

	"(\[ORD1([a-zA-Z0-9_?\/\-.]+)\])",
	"(\[ORD2([a-zA-Z0-9_?\/\-.]+)\])",
	"(\[ORD3([a-zA-Z0-9_?\/\-.]+)\])",
	"(\[ORD4([a-zA-Z0-9_?\/\-.]+)\])",

	"(\[ORIGINALORDER\])",
	"(\[PAGE\])"
);

$replace=array($menu_myhome,$menu_myorder,$menu_mypersonal,$menu_mywish,$menu_myreserve,$menu_mycoupon,$menu_myinfo,$menu_myout,$menu_mycustsect,$search_btn1,$search_btn2,$search_btn3,$search_btn4,$search_btn5,$search_date,$search_ok,$ord1,$ord2,$ord3,$ord4,$originalorder,$page);

$body=preg_replace($pattern,$replace,$body);

echo $body;
?>