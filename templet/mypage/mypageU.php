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
if($_data->recom_url_ok == "Y" || $_data->sns_ok == "Y"){
	$menu_promote="".$Dir.FrontDir."mypage_promote.php";
}
$menu_gonggu="".$Dir.FrontDir."mypage_gonggu.php";

//�ξ�ȸ�� ����
$royalvalue="";
if(strpos($body,"[IFROYAL]")!=0) {
	$ifroyalnum=strpos($body,"[IFROYAL]");
	$endroyalnum=strpos($body,"[IFENDROYAL]");
	$mainroyal=substr($body,$ifroyalnum+9,$endroyalnum-$ifroyalnum-9);
	$body=substr($body,0,$ifroyalnum)."[ROYALVALUE]".substr($body,$endroyalnum+12);

	$royal_img="";
	$royal_msg1="";
	$royal_msg2="";
	if(strlen($_ShopInfo->getMemid())>0 && strlen($_ShopInfo->getMemgroup())>0) {
		$arr_dctype=array("B"=>"����","C"=>"ī��","N"=>"");
		$sql = "SELECT a.name,b.group_code,b.group_name,b.group_payment,b.group_usemoney,b.group_addmoney ";
		$sql.= "FROM tblmember a, tblmembergroup b WHERE a.id='".$_ShopInfo->getMemid()."' AND b.group_code=a.group_code ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if(file_exists($Dir.DataDir."shopimages/etc/groupimg_".$row->group_code.".gif")) {
				$royal_img="<img src=\"".$Dir.DataDir."shopimages/etc/groupimg_".$row->group_code.".gif\" border=0>";
			} else {
				$royal_img="<img src=\"".$Dir."images/common/group_img.gif\" border=0>\n";
			}
			$royal_msg1="<B>".$row->name."</B>���� ȸ������� <B><FONT COLOR=\"#ff6600\">[".$row->group_name."]</FONT></B> �Դϴ�.";
			if (substr($row->group_code,0,1)!="M") {
				$royal_msg2 = "<B>".$row->name."</B>���� <FONT COLOR=\"#ff6600\"><B>".number_format($row->group_usemoney)."��</B></FONT> �̻� ".$arr_dctype[$row->group_payment]."���Ž�";
				$type=substr($row->group_code,0,2);
				if($type=="RW") $royal_msg2.="�����ݿ� ".number_format($row->group_addmoney)."���� <font color=#EE1A02><B>�߰� ����</B></font>�� �帳�ϴ�.";
				else if($type=="RP") $royal_msg2.="���� �������� ".number_format($row->group_addmoney)."�踦 <font color=#EE1A02><B>����</B></font>�� �帳�ϴ�.";
				else if($type=="SW") $royal_msg2.="���� �ݾ� ".number_format($row->group_addmoney)."���� <font color=#EE1A02><B>�߰� ����</B></font>�� �帳�ϴ�.";
				else if($type=="SP") $royal_msg2.="���� �ݾ��� ".number_format($row->group_addmoney)."%�� <font color=#EE1A02><B>�߰� ����</B></font>�� �帳�ϴ�.";
			} else {
				$royal_msg2="";
			}
			if(strpos($mainroyal,"[IFROYALMSG2]")!=0) {
				if(strlen($royal_msg2)>0) {
					$pattern=array("(\[IFROYALMSG2\])","(\[IFENDROYALMSG2\])");
					$replace=array("","");
					$mainroyal=preg_replace($pattern,$replace,$mainroyal);
				} else {
					$ifmsg2num=strpos($mainroyal,"[IFROYALMSG2]");
					$endmsg2num=strpos($mainroyal,"[IFENDROYALMSG2]")+16;
					$mainroyal=substr($mainroyal,0,$ifmsg2num).substr($mainroyal,$endmsg2num);
				}
			}


			$pattern=array("(\[ROYAL_IMG\])","(\[ROYAL_MSG1\])","(\[ROYAL_MSG2\])");
			$replace=array($royal_img,$royal_msg1,$royal_msg2);
			$royalvalue=preg_replace($pattern,$replace,$mainroyal);
		}
		mysql_free_result($result);
	}
}

//ȫ��URL �� SNSINFO
//if($_data->recom_url_ok == "Y" && strlen($_ShopInfo->getMemid())>0){
if(strlen($_ShopInfo->getMemid())>0){
	if(strpos($body,"[IFHONGBO]")!=0) {
		$ifhongbonum=strpos($body,"[IFHONGBO]");
		$endhongbonum=strpos($body,"[IFENDHONGBO]");
		$mainhongbo=substr($body,$ifhongbonum+10,$endhongbonum-$ifhongbonum-10);
		$body=substr($body,0,$ifhongbonum)."[HONGBOVALUE]".substr($body,$endhongbonum+13);

		$arRecomType = explode("", $_data->recom_memreserve_type);
		$sAddRecom = "";
		if($arRecomType[0] == "A"){
			$sAddRecom = "�� <font color=\"#CC0000\">URL�ּ�</font>�� ���� �ű�ȸ�� ���Խ� ȸ���Բ� <u>".$_data->recom_memreserve."���� ������ ����</u>";
		}else if($arRecomType[0] == "B"){
			$sAddRecom = "�� <font color=\"#CC0000\">URL�ּ�</font>�� ���� ������ ȸ���� ù ���Ű� �̷������ <u>";
			if($arRecomType[1] == "A"){
				if($arRecomType[2] == "N"){
					$sAddRecom .= $_data->recom_memreserve."����";
				}else if($arRecomType[2] == "Y"){
					$sAddRecom .= "���űݾ��� ".$_data->recom_memreserve."%��";
				}
			}else if($arRecomType[1] == "B"){
				$sAddRecom .= "���űݾ׿� ����";
			}
			$sAddRecom .= " ������</u>�� ȸ���Բ� ����";
		}
		$sql = "SELECT COUNT(*) as cnt FROM tblmember WHERE rec_id='".$_ShopInfo->getMemid()."'";
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);
		$recom_cnt = $row->cnt;
		mysql_free_result($result);
		$memreccnt = $recom_cnt;
		$memhongbourl = "http://".$_ShopInfo->getShopurl()."?token=".$url_id;
		$hongbopopup = "javascript:win_hongboUrl()";
		$memaddreserve = $sAddRecom;

		$pattern=array("(\[NAME\])","(\[MEMBERCNT\])","(\[MEMHONGBOURL\])","(\[HONGBOPOPUP\])","(\[MEMADDRESERVE\])");
		$replace=array($_ShopInfo->getMemname(),$memreccnt,$memhongbourl,$hongbopopup,$memaddreserve);
		$hongbovalue=preg_replace($pattern,$replace,$mainhongbo);
	}

}
if($_data->sns_ok == "Y" && strlen($_ShopInfo->getMemid())>0) {
	$memsnsinfo =
		((TWITTER_ID !="TWITTER_ID")? "<a href=\"javascript:changeSnsInfo('t');\"><IMG SRC=\"../images/design/icon_twitter_off.gif\" WIDTH=\"25\" HEIGHT=\"25\" ALT=\"\" border=\"0\" id=\"twLoginBtn\"></a>":"").
		((FACEBOOK_ID !="FACEBOOK_ID")? "<a href=\"javascript:changeSnsInfo('f');\"><IMG SRC=\"../images/design/icon_facebook_off.gif\" WIDTH=\"25\" HEIGHT=\"25\" ALT=\"\" hspace=\"4\" border=\"0\" id=\"fbLoginBtn\"></a>":"").
		((ME2DAY_ID !="ME2DAY_ID")? "<a href=\"javascript:changeSnsInfo('m');\"><IMG SRC=\"../images/design/icon_me2day_off.gif\" WIDTH=\"25\" HEIGHT=\"25\" ALT=\"\" border=\"0\"id=\"meLoginBtn\"></a>":"");
}

//�ֱ� �ֹ����� ����
$ordervalue="";
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


	if(strpos($body,"[IFGIFTCARD]")!=0) {
		if($type != 3){
			$ifgiftcard = "<!-- ";
			$ifelsegiftcard = " -->";
			$ifendgiftcard = "";
		}else{
			$ifgiftcard = "";
			$ifelsegiftcard = "<!-- ";
			$ifendgiftcard = " -->";
		}
	}

	$ordtab1="";
	$ordtab2="";
	$ordtab3="";
	if (preg_match("/\[ORDTAB1([a-zA-Z0-9_?\/\-.]+)\]/",$body,$match)) {
		$ordtab1_tmp=substr($match[1],1);
		$ordtab1_val=explode("_",$ordtab1_tmp);
		$ordtab1_off=$ordtab1_val[0];
		$ordtab1_on=$ordtab1_val[1];
		if(strlen($ordtab1_on)==0) $ordtab1_on=$ordtab1_off;
		if($type=="") {
			$ordtab1="<A HREF=\"".$Dir.FrontDir."mypage.php\"><img src=\"".$ordtab1_on."\" border=0></A>";
		} else {
			$ordtab1="<A HREF=\"".$Dir.FrontDir."mypage.php\"><img src=\"".$ordtab1_off."\" border=0></A>";
		}
	}
	if (preg_match("/\[ORDTAB2([a-zA-Z0-9_?\/\-.]+)\]/",$body,$match)) {
		$ordtab2_tmp=substr($match[1],1);
		$ordtab2_val=explode("_",$ordtab2_tmp);
		$ordtab2_off=$ordtab2_val[0];
		$ordtab2_on=$ordtab2_val[1];
		if(strlen($ordtab2_on)==0) $ordtab2_on=$ordtab2_off;
		if($type=="2") {
			$ordtab2="<A HREF=\"".$Dir.FrontDir."mypage.php?type=2\"><img src=\"".$ordtab2_on."\" border=0></A>";
		} else {
			$ordtab2="<A HREF=\"".$Dir.FrontDir."mypage.php?type=2\"><img src=\"".$ordtab2_off."\" border=0></A>";
		}
	}
	if (preg_match("/\[ORDTAB3([a-zA-Z0-9_?\/\-.]+)\]/",$body,$match)) {
		$ordtab3_tmp=substr($match[1],1);
		$ordtab3_val=explode("_",$ordtab3_tmp);
		$ordtab3_off=$ordtab3_val[0];
		$ordtab3_on=$ordtab3_val[1];
		if(strlen($ordtab3_on)==0) $ordtab3_on=$ordtab3_off;
		if($type=="3") {
			$ordtab3="<A HREF=\"".$Dir.FrontDir."mypage.php?type=3\"><img src=\"".$ordtab3_on."\" border=0></A>";
		} else {
			$ordtab3="<A HREF=\"".$Dir.FrontDir."mypage.php?type=3\"><img src=\"".$ordtab3_off."\" border=0></A>";
		}
	}

	$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
	$result=mysql_query($sql,get_db_conn());
	$delicomlist=array();
	while($row=mysql_fetch_object($result)) {
		$delicomlist[$row->code]=$row;
	}
	mysql_free_result($result);

	$curdate=date("Ymd",mktime(0,0,0,(int)date("m")-1,(int)date("d"),date("Y")));
	$sql = "SELECT ordercode, price, paymethod, pay_admin_proc, pay_flag, bank_date, deli_gbn, gift ";
	$sql.= "FROM tblorderinfo WHERE id='".$_ShopInfo->getMemid()."' ";
	$sql.= "AND ordercode >= '".$curdate."' AND (del_gbn='N' OR del_gbn='A') ";
	if(!$type) $sql .= "AND gift='0' ";
	else if($type == 2) $sql.= "AND gift='3' ";
	else if($type == 3) $sql.= "AND gift in('1','2') ";
	$sql.= "ORDER BY ordercode DESC LIMIT 10 ";
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

		//$sql = "SELECT * FROM tblorderproduct WHERE ordercode='".$row->ordercode."' ";
		//$sql.= "AND NOT (productcode LIKE 'COU%' OR productcode LIKE '999999%') ";

		$sql = "SELECT op.*,op.quantity*op.price as sumprice,p.tinyimage,p.minimage FROM tblorderproduct op left join tblproduct p on (op.productcode = p.productcode) WHERE op.ordercode='".$row->ordercode."' ";
		$sql.= "AND NOT (op.productcode LIKE 'COU%' OR op.productcode LIKE '999999%') ";

		$result2=mysql_query($sql,get_db_conn());
		$jj=0;
		$originalproduct="";
		while($row2=mysql_fetch_object($result2)) {
			$tempproduct=$mainproduct;

			if($row2->tinyimage){
				$pr_image="<A HREF=\"javascript:OrderDetailProduct('".$row->ordercode."','".$row2->productcode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row2->tinyimage)."\" border=\"0\" width=\"50\" style=\"float:left;margin-right:5px;\"/></a>";
			}else{
				$pr_image="<A HREF=\"javascript:OrderDetailProduct('".$row->ordercode."','".$row2->productcode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"".$Dir."images/no_img.gif\" border=\"0\" width=\"50\" style=\"float:left;margin-right:5px;\"/></a>";
			}

			//echo $pr_image;

			$order_name=$pr_image."<A HREF=\"javascript:OrderDetailProduct('".$row->ordercode."','".$row2->productcode."')\" onmouseover=\"window.status='�ֹ�������ȸ';return true;\" onmouseout=\"window.status='';return true;\">".$row2->productname."</a>";
			$order_delistat="";
			if ($row2->deli_gbn=="C") $order_delistat="�ֹ����";
			else if ($row2->deli_gbn=="D") $order_delistat="��ҿ�û";
			else if ($row2->deli_gbn=="E") $order_delistat="ȯ�Ҵ��";
			else if ($row2->deli_gbn=="X") {
				if($row->gift=='1') {
					$sql3 = "SELECT * FROM tblgift_info WHERE ordercode='{$row->ordercode}'";
					$result3=mysql_query($sql3,get_db_conn());
					$row3 = mysql_fetch_array($result3);
					mysql_free_result($result3);
					$order_delistat="������ȣ�߼�";
				}
				else $order_delistat="�߼��غ�";
			}
			else if ($row2->deli_gbn=="Y") {
				if($row->gift=='1') {
					$sql3 = "SELECT * FROM tblgift_info WHERE ordercode='{$row->ordercode}'";
					$result3=mysql_query($sql3,get_db_conn());
					$row3 = mysql_fetch_array($result3);
					mysql_free_result($result3);
					$order_delistat="�����������Ϸ�";
				}
				else if($row->gift=='2') $order_delistat="�����Ϸ�";
				else  $order_delistat="�߼ۿϷ�";
			}
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

			if($row2->deli_gbn=="Y" && $_data->review_type !="N")  {
				$review_write="<A HREF=\"javascript:OrderReview('".$row->ordercode."','".$row2->productcode."')\" onmouseover=\"window.status='��ǰ��';return true;\" onmouseout=\"window.status='';return true;\"><img src=\"/images/common/mypage/001/mypage_order_icon04.gif\" alt=\"��ǰ���ۼ�\" /></a>";
			}else{
				$review_write = "<img src=\"/images/common/mypage/001/mypage_order_icon04_off.gif\" alt=\"��ǰ���ۼ�\" />";
			}

			$pattern=array("(\[ORDER_NAME\])","(\[ORDER_DELISTAT\])","(\[ORDER_DETAIL\])","(\[REVIEW_WRITE\])","(\[DELISEARCHVALUE\])","(\[FORPRODUCT\])","(\[FORENDPRODUCT\])");
			$replace=array($order_name,$order_delistat,$order_detail,$review_write,$delisearchval,"","");
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
}

//�ֱ� ���ǳ���
if(strpos($body,"[IFPERSONAL]")!=0) {
	$ifpersonalnum=strpos($body,"[IFPERSONAL]");
	$endpersonalnum=strpos($body,"[IFENDPERSONAL]");
	$elsepersonalnum=strpos($body,"[IFELSEPERSONAL]");

	$personalstartnum=strpos($body,"[FORPERSONAL]");
	$personalstopnum=strpos($body,"[FORENDPERSONAL]");

	$ifpersonal=substr($body,$ifpersonalnum+12,$personalstartnum-($ifpersonalnum+12))."[PERSONALVALUE]".substr($body,$personalstopnum+16,$elsepersonalnum-($personalstopnum+16));

	$nopersonal=substr($body,$elsepersonalnum+16,$endpersonalnum-$elsepersonalnum-16);

	$mainpersonal=substr($body,$personalstartnum,$personalstopnum-$personalstartnum+16);

	$body=substr($body,0,$ifpersonalnum)."[ORIGINALPERSONAL]".substr($body,$endpersonalnum+15);

	$sql = "SELECT idx,subject,date,re_date FROM tblpersonal ";
	$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
	$sql.= "ORDER BY idx DESC LIMIT 5 ";
	$result = mysql_query($sql,get_db_conn());
	$cnt=0;
	while($row=mysql_fetch_object($result)) {
		$temppersonal.=$mainpersonal;

		$personal_date = substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2)." (".substr($row->date,8,2).":".substr($row->date,10,2).")";
		$personal_redate="-";
		if(strlen($row->re_date)==14) {
			$personal_redate = substr($row->re_date,0,4)."/".substr($row->re_date,4,2)."/".substr($row->re_date,6,2)." (".substr($row->re_date,8,2).":".substr($row->re_date,10,2).")";
		}
		$personal_subject="<A HREF=\"javascript:ViewPersonal('".$row->idx."')\"><FONT COLOR=\"#000000\">".strip_tags($row->subject)."</FONT></A>";
		if(strlen($row->re_date)==14) {
			$personal_reply="<img src=\"".$Dir."images/common/mypersonal_skin_icon1.gif\" border=0 align=absmiddle>";
		} else {
			$personal_reply="<img src=\"".$Dir."images/common/mypersonal_skin_icon2.gif\" border=0 align=absmiddle>";
		}
		$cnt++;
		$pattern=array("(\[PERSONAL_DATE\])","(\[PERSONAL_SUBJECT\])","(\[PERSONAL_REPLY\])","(\[PERSONAL_REDATE\])","(\[FORPERSONAL\])","(\[FORENDPERSONAL\])");
		$replace=array($personal_date,$personal_subject,$personal_reply,$personal_redate,"","");

		$temppersonal=preg_replace($pattern,$replace,$temppersonal);
	}
	mysql_free_result($result);
	if($cnt>0) {
		$originalpersonal=$ifpersonal;
		$pattern=array("(\[PERSONALVALUE\])");
		$replace=array($temppersonal);
		$originalpersonal=preg_replace($pattern,$replace,$originalpersonal);
	} else {
		$originalpersonal=$nopersonal;
	}
}

//���ø���Ʈ ����
$wish_list="";
$match=array();
$default_wish=array("5","N","N");
if (preg_match("/\[WISH_LIST([0-9NY]{0,3})\]/",$body,$match)) {
	$match_array=explode("_",$match[1]);
	for ($i=0;$i<strlen($match_array[0]);$i++) {
		$default_wish[$i]=$match_array[0][$i];
	}
	$wish_cols=(int)$default_wish[0];
	$wish_price=$default_wish[1];		// �Һ��ڰ� ǥ�ÿ���
	$wish_reserve=$default_wish[2];		// ������ ǥ�ÿ���

	if($wish_cols==0 || $wish_cols==9) $wish_cols=5;

	$wish_colnum=$wish_cols;
	$wish_product_num=$wish_cols;
	if($wish_cols==6)		$wish_imgsize=$_data->primg_minisize-5;
	else if($wish_cols==7)	$wish_imgsize=$_data->primg_minisize-10;
	else if($wish_cols==8)	$wish_imgsize=$_data->primg_minisize-20;
	else					$wish_imgsize=$_data->primg_minisize;

	$wish_list.="<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
	for($j=0;$j<$wish_cols;$j++) {

		$wish_list.="<col width=".floor(100/$wish_cols)."%></col>\n";
	}
	$wish_list.="<tr>\n";

	$sql = "SELECT b.productcode,b.productname,b.sellprice,b.quantity,b.reserve,b.reservetype,b.tinyimage, ";
	$sql.= "b.consumerprice,b.option_price,b.option_quantity,b.selfcode,b.etctype FROM tblwishlist a, tblproduct b ";
	$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
	$sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
	$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
	$sql.= "AND b.display='Y' LIMIT ".$wish_product_num." ";
	$result=mysql_query($sql,get_db_conn());
	$cnt=0;
	while($row=mysql_fetch_object($result)) {

		$wish_list.="<td align=center valign=top>\n";
		$wish_list.="<table border=0 cellpadding=0 cellspacing=0 width=100% id=\"W".$row->productcode."\" onmouseover=\"quickfun_show(this,'W".$row->productcode."','')\" onmouseout=\"quickfun_show(this,'W".$row->productcode."','none')\">\n";
		$wish_list.="<tr>\n";
		$wish_list.="	<td align=center style=\"padding-left:5px;padding-right:5px;\">";
		$wish_list.="<A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\">";
		if (strlen($row->tinyimage)>0 && file_exists($Dir.DataDir."shopimages/product/".$row->tinyimage)==true) {
			$wish_list.="<img src=\"".$Dir.DataDir."shopimages/product/".urlencode($row->tinyimage)."\" border=0 ";
			$width = getimagesize($Dir.DataDir."shopimages/product/".$row->tinyimage);
			if($_data->ETCTYPE["IMGSERO"]=="Y") {
				if ($width[1]>$width[0] && $width[1]>$_data->primg_minisize2) $wish_list.="height=".$_data->primg_minisize2." ";
				else if (($width[1]>=$width[0] && $width[0]>=$wish_imgsize) || $width[0]>=$wish_imgsize) $wish_list.="width=".$wish_imgsize." ";
			} else {
				if ($width[0]>=$width[1] && $width[0]>=$wish_imgsize) $wish_list.="width=".$wish_imgsize." ";
				else if ($width[1]>=$wish_imgsize) $wish_list.="height=".$wish_imgsize." ";
			}
		} else {
			$wish_list.="<img src=\"".$Dir."images/no_img.gif\" border=0 width=".$wish_imgsize." align=center";
		}
		$wish_list.="	></A></td>\n";
		$wish_list.="</tr>\n";
		$wish_list.="<tr><td height=\"3\" style=\"position:relative;\">".($_data->ETCTYPE["QUICKTOOLS"]!="Y"?"<script>quickfun_write('".$Dir."','W','".$row->productcode."','".($row->quantity=="0"?"":"1")."')</script>":"")."</td></tr>\n";
		$wish_list.="<tr>\n";
		$wish_list.="	<td align=center valign=top style=\"padding-left:5px;padding-right:5px;word-break:break-all;\"><A HREF=\"".$Dir.FrontDir."productdetail.php?productcode=".$row->productcode.$add_query."\" onmouseover=\"window.status='��ǰ����ȸ';return true;\" onmouseout=\"window.status='';return true;\"><FONT class=\"prname\">".viewproductname($row->productname,$row->etctype,$row->selfcode)."</FONT></A></td>\n";
		$wish_list.="</tr>\n";
		if($wish_price=="Y") {	//�Һ��ڰ�
			$wish_list.="<tr>\n";
			$wish_list.="	<td align=center valign=top style=\"padding-left:5px;padding-right:5px;\" class=\"prconsumerprice\"><img src=\"".$Dir."images/common/won_icon2.gif\" border=0 align=absmiddle><s>".number_format($row->consumerprice)."��</s>";
			$wish_list.="	</td>\n";
			$wish_list.="</tr>\n";
		}
		$wish_list.="<tr>\n";
		$wish_list.="	<td align=center valign=top style=\"padding-left:5px;padding-right:5px;\" class=\"prprice\">";
		if($dicker=dickerview($row->etctype,number_format($row->sellprice)."��",1)) {
			$wish_list.=$dicker;
		} else if(strlen($_data->proption_price)==0) {
			$wish_list.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ".number_format($row->sellprice)."��";
			if (strlen($row->option_price)!=0) $wish_list.="<FONT color=red>(�ɼǺ���)</FONT>";
		} else {
			$wish_list.="<img src=\"".$Dir."images/common/won_icon.gif\" border=0 align=absmiddle> ";
			if (strlen($row->option_price)==0) $wish_list.=number_format($row->sellprice)."��";
			else $wish_list.=ereg_replace("\[PRICE\]",number_format($row->sellprice),$_data->proption_price);
		}
		if ($row->quantity=="0") $wish_list.=soldout(1);
		$wish_list.="	</td>\n";
		$wish_list.="</tr>\n";
		$reserveconv=getReserveConversion($row->reserve,$row->reservetype,$row->sellprice,"Y");
		if($wish_reserve=="Y" && $reserveconv>0) {	//������
			$wish_list.="<tr>\n";
			$wish_list.="	<td align=center valign=top style=\"padding-left:5px;padding-right:5px;\" class=\"prreserve\"><img src=\"".$Dir."images/common/reserve_icon.gif\" border=0 align=absmiddle> ".number_format($reserveconv)."��";
			$wish_list.="	</td>\n";
			$wish_list.="</tr>\n";
		}
		$wish_list.="</table>\n";
		$wish_list.="</td>";

		$cnt++;
	}
	if($cnt>0 && $cnt<$wish_cols) {
		for($k=0; $k<($wish_cols-$cnt); $k++) {
			$wish_list.="<td></td>\n<td></td>\n";
		}
	}
	mysql_free_result($result);
	if ($cnt==0) {
		$wish_list.="<td height=40 colspan=".$wish_colnum." align=center>WishList�� ��� ��ǰ�� �����ϴ�.</td>";
	}

	$wish_list.="	</tr>\n";
	$wish_list.="	</table>\n";
}





//�ֹ���Ȳ ����
$orderid = isset($_ShopInfo->memid)?trim($_ShopInfo->memid):"";

if(strlen($orderid) > 0){
	$oiSQL = "SELECT ";
	$oiSQL .= "COUNT(oi.ordercode) AS ordercount, "; // �ֹ���Ȳ ��
	$oiSQL .= "SUM(IF((oi.pay_admin_proc = 'Y' OR oi.pay_admin_proc = 'N') AND oi.deli_gbn = 'N',1,0)) AS delireadycount, "; // �߼��غ� ��
	$oiSQL .= "SUM(IF(oi.deli_gbn = 'Y', 1,0)) AS delicomplatecount, "; // �߼ۿϷ� ��
	$oiSQL .= "SUM(IF(op.status = 'RA',1,0)) AS refundcount, "; //ȯ�ҿ�û��
	$oiSQL .= "SUM(IF(op.status = 'RC',1,0)) AS repaymentcount "; //ȯ�ҿϷ��
	$oiSQL .= "FROM tblorderinfo AS oi LEFT OUTER JOIN tblorderproduct AS op ON(oi.ordercode = op.ordercode) ";
	$oiSQL .= "WHERE oi.id = '".$orderid."' ";

	$ordercount = "";
	$deliready = "";
	$delicomplate = "";
	$refund = "";
	$repayment = "";

	if(false !== $oiRes = mysql_query($oiSQL, get_db_conn())){
		$oiNumRow = mysql_num_rows($oiRes);
		if($oiNumRow > 0){
			$oiRow = mysql_fetch_assoc($oiRes);

			// �ֹ���Ȳ,�߼��غ�,�߼ۿϷ�� ��ü�ֹ����� ī���� ����
			// ȯ�ҽ�û,ȯ�ҿϷ�� ��ǰ������ ī���� ����
			$ordercount = $oiRow['ordercount']; // �ֹ���Ȳ ��
			$deliready = _empty($oiRow['delireadycount'])?0:$oiRow['delireadycount']; // �߼��غ� ��
			$delicomplate = _empty($oiRow['delicomplatecount'])?0:$oiRow['delicomplatecount']; // �߼ۿϷ� ��
			$refund = _empty($oiRow['refundcount'])?0:$oiRow['refundcount']; // ȯ�ҿ�û��
			$repayment = _empty($oiRow['repaymentcount'])?0:$oiRow['repaymentcount']; // ȯ�ҿ�û��

			/*
			$delicomplate = $oiRow['delicomplatecount']; //�߼ۿϷ� ��
			$refund = $oiRow['refundcount']; //ȯ�ҿ�û��
			$repayment = $oiRow['repaymentcount']; //ȯ�ҿ�û��*/
		}

		mysql_free_result($oiRes);
	}
}
//�ֹ���Ȳ ��


//�̸��� & SMS ���ŵ��� ��� j.bum
if($_mdata->news_yn=="Y") {
	$news_mail_yn="Y";
	$news_sms_yn="Y";
} else if($_mdata->news_yn=="M") {
	$news_mail_yn="Y";
	$news_sms_yn="N";
} else if($_mdata->news_yn=="S") {
	$news_mail_yn="N";
	$news_sms_yn="Y";
} else if($_mdata->news_yn=="N") {
	$news_mail_yn="N";
	$news_sms_yn="N";
}

//���� �α��� ���� Ȯ��
$loginYear = substr($_mdata->logindate,0,4);
$loginMonth = substr($_mdata->logindate,4,2);
$loginDay = substr($_mdata->logindate,6,2);
$loginHour = substr($_mdata->logindate,8,2);
$loginMinute = substr($_mdata->logindate,10,2);
$loginSec = substr($_mdata->logindate,12,2);

if($news_mail_yn=="Y"){
	$revivemail = "���ŵ���";
}else{
	$revivemail = "���Űź�";
}
if($news_sms_yn=="Y"){
	$recivesms = "���ŵ���";
}else{
	$recivesms = "���Űź�";
}

$lastlogin = $loginYear."/".$loginMonth."/".$loginDay." ".$loginHour.":".$loginMinute.":".$loginSec;



$id=$_mdata->id;
$name=$_mdata->name;
$email=$_mdata->email;
$address=explode("=",$_mdata->home_addr);
$address1=$address[0];
$address2=$address[1];
$tel=$_mdata->home_tel;
$mobile=$_mdata->mobile;
$reserve=number_format($_mdata->reserve);
$reserve_more="".$Dir.FrontDir."mypage_reserve.php";
$coupon=number_format($coupon_cnt);
$coupon_more="".$Dir.FrontDir."mypage_coupon.php";
$gift_auth = "".$Dir.FrontDir."mypage_auth.php";

$order_more="".$Dir.FrontDir."mypage_orderlist.php";
$personal_more="".$Dir.FrontDir."mypage_personal.php";
$wish_more="".$Dir.FrontDir."wishlist.php";

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
	"(\[MENU_PROMOTE\])",
	"(\[MENU_GONGGU\])",

	"(\[ID\])",
	"(\[NAME\])",
	"(\[EMAIL\])",
	"(\[ADDRESS1\])",
	"(\[ADDRESS2\])",
	"(\[TEL\])",
	"(\[MOBILE\])",
	"(\[RESERVE\])",
	"(\[RESERVE_MORE\])",
	"(\[COUPON\])",
	"(\[COUPON_MORE\])",
	"(\[ROYALVALUE\])",
	"(\[ORDER_MORE\])",
	"(\[ORIGINALORDER\])",
	"(\[PERSONAL_MORE\])",
	"(\[ORIGINALPERSONAL\])",
	"(\[WISH_MORE\])",
	"(\[WISH_LIST([0-9NY]{0,3})\])",
	"(\[GIFT_AUTH\])",
	"(\[HONGBOVALUE\])",
	"(\[MEMSNSINFO\])",

	"(\[ORDTAB1([a-zA-Z0-9_?\/\-.]+)\])",
	"(\[ORDTAB2([a-zA-Z0-9_?\/\-.]+)\])",
	"(\[ORDTAB3([a-zA-Z0-9_?\/\-.]+)\])",

	"(\[IFGIFTCARD\])",
	"(\[IFELSEGIFTCARD\])",
	"(\[IFENDGIFTCARD\])",

	"(\[ORDERCOUNT\])",
	"(\[DELIREADY\])",
	"(\[DELICOMPLATE\])",
	"(\[REFUND\])",
	"(\[REPAYMENT\])",

	"(\[RECIVEMAIL\])",
	"(\[RECIVESMS\])",
	"(\[LASTLOGIN\])"

);
$replace=array($menu_myhome,$menu_myorder,$menu_mypersonal,$menu_mywish,$menu_myreserve,$menu_mycoupon,$menu_myinfo,$menu_myout,$menu_mycustsect,$menu_promote,$menu_gonggu,$id,$name,$email,$address1,$address2,$tel,$mobile,$reserve,$reserve_more,$coupon,$coupon_more,$royalvalue,$order_more,$originalorder,$personal_more,$originalpersonal,$wish_more,$wish_list,$gift_auth,$hongbovalue,$memsnsinfo,$ordtab1,$ordtab2,$ordtab3,$ifgiftcard,$ifelsegiftcard,$ifendgiftcard, $ordercount,$deliready,$delicomplate,$refund,$repayment, $revivemail,$recivesms,$lastlogin);










// ȸ����� �� �߰�����/�����ȳ� ------------------------------------------------------ start
if(strlen($_ShopInfo->getMemid())>0) {

	$arr_dctype=array("B"=>"����","C"=>"ī��","N"=>"");

	if( strlen($_ShopInfo->getMemgroup())>0 ) {
		$memberInfo_sql = "SELECT a.name,b.group_code,b.group_name,b.group_payment,b.group_usemoney,b.group_addmoney,b.group_order_price,b.group_order_cnt FROM tblmember a, tblmembergroup b WHERE a.id='".$_ShopInfo->getMemid()."' AND b.group_code=a.group_code ";
	} else {
		$memberInfo_sql = "SELECT name FROM tblmember WHERE id='".$_ShopInfo->getMemid()."' ";
	}
	$memberInfo_result=mysql_query($memberInfo_sql,get_db_conn());
	if($memberInfo_row=mysql_fetch_object($memberInfo_result)) {

		//��� �߰����� ------------- start
		if(!_empty($memberInfo_row->group_name)){
			// ȸ�� ��� ���� ó��
			$fsql = "select * from tblmembergroup where group_order_price > '".$memberInfo_row->group_order_price."' and group_order_cnt >= '".$memberInfo_row->group_order_price."' order by group_order_price asc,group_order_cnt asc limit 1";

			$nginfo = false;
			if(false !== $fres = mysql_query($fsql,get_db_conn())){
				if(mysql_num_rows($fres)){
					$nginfo = mysql_fetch_assoc($fres);
				}else{
					$fsql = "select * from tblmembergroup order by group_order_price desc,group_order_cnt desc limit 1";
					if(false !== $fres = mysql_query($fsql,get_db_conn())){
						if(mysql_num_rows($fres)){
							$nginfo = mysql_fetch_assoc($fres);
						}
					}
				}
			}

			$consql = "select * from extra_conf where type='autogroup'";
			$gconfig = array();
			if($nginfo !== false && false !== $conres = mysql_query($consql,get_db_conn())){
				while($crow = mysql_fetch_assoc($conres)){
					$gconfig[$crow['name']]= $crow['value'];
				}
				$lastday = intval(date('t'));
				$currday = intval(date('d'));
			//	$gconfig['rangestart'] = 29;
				$gap = $gconfig['rangestart'] - $currday;

				$gconfig['check']['end'] =strtotime(date('Y-m-'.$gconfig['rangestart']));

				if($gap < 0){
					$gconfig['check']['end'] = strtotime('+1 month',$gconfig['check']['end']);
				}
				$gconfig['check']['start'] = strtotime('-'.($gconfig['rangemonth']).' month',$gconfig['check']['end']);
				$gconfig['check']['rprice'] = $nginfo['group_order_price'];
				$gconfig['check']['rcnt'] = $nginfo['group_order_cnt'];
				$gconfig['check']['price'] = $gconfig['check']['cnt'] = 0;

				$orderinfoSQL = "SELECT SUM(price) as sumprice, COUNT(price) AS sumcount FROM tblorderinfo WHERE id = '".$_ShopInfo->getMemid()."' AND deli_gbn = 'Y' AND ordercode >= '".date('Ymd',$gconfig['checkRange']['start'])."000000' AND ordercode <= '".date('Ymd',$gconfig['checkRange']['end'])."235959' group by id ";
				if(false !== $ores = mysql_query($orderinfoSQL,get_db_conn())){
					if(mysql_num_rows($ores)){
						$gconfig['check']['price'] = mysql_result($ores,0,0);
						$gconfig['check']['rprice'] -= $gconfig['check']['price'];
						$gconfig['check']['cnt'] -= mysql_result($ores,0,1);
						$gconfig['check']['rcnt'] -= $gconfig['check']['cnt'];
					}
				}

				if($gconfig['check']['rprice'] < 0) $gconfig['check']['rprice']=0;
				if($gconfig['check']['rcnt'] < 0) $gconfig['check']['rcnt']=0;
			}
		}
		//��� �߰����� ------------- End

		$nextGroupInfo_groupName = $nextGroupInfo_rprice = $nextGroupInfo_rcnt = $nextGroupInfo_dateStart = $nextGroupInfo_dateEnd = $nextGroupInfo_cnt = $nextGroupInfo_keepclass = $nextGroupInfo_price = "";
		if($nginfo !== false){
			$nextGroupInfo_groupName = $nginfo['group_name']; //  �����׷��
			$nextGroupInfo_rprice = number_format($gconfig['check']['rprice']); //�������űݾ�
			$nextGroupInfo_rcnt = number_format($gconfig['check']['rcnt']); //���� ���ŰǼ�
			$nextGroupInfo_dateStart = date('Y.m.d',$gconfig['check']['start']); //���űⰣ(�������űݾ� �����Ⱓ) ����
			$nextGroupInfo_dateEnd = date('Y.m.d',$gconfig['check']['end']); //���űⰣ(�������űݾ� �����Ⱓ) ��
			$nextGroupInfo_cnt = number_format($gconfig['check']['cnt']); //���ŰǼ�
			$nextGroupInfo_keepclass = $gconfig['keepclass']; //��������Ⱓ
			$nextGroupInfo_price = number_format($gconfig['check']['price']); //���űݾ�
		}


		// ȸ���̹���
		if(file_exists($Dir.DataDir."shopimages/etc/groupimg_".$memberInfo_row->group_code.".gif")){
			$memberInfoImage = "<img src=\"".$Dir.DataDir."shopimages/etc/groupimg_".$memberInfo_row->group_code.".gif\" border=0>";
		}else{
			$memberInfoImage = "<img src=\"".$Dir."images/common/mypage/".$_data->design_mypage."/mypage_mem_icon01.gif\">";
		}

		// ȸ���̸�
		$memberInfoName = $memberInfo_row->name;

		// ȸ���׷�
		$memberInfoGroup = $memberInfo_row->group_name;

		// ȸ����� ���� ����
		if (substr($memberInfo_row->group_code,0,1)!="M") {
			$memberGroupInfo = $memberInfo_row->name."���� <b><font color='#ff6600'>".number_format($memberInfo_row->group_usemoney)."��</font></b> �̻� ".$arr_dctype[$memberInfo_row->group_payment]."���Ž�";

			$type=substr($memberInfo_row->group_code,0,2);
			if($type=="RW") {
				$memberGroupInfo .= "<b><font color=#3f77ca>".number_format($memberInfo_row->group_addmoney)."</font></b>���� <b>�߰�����</b>�� �帳�ϴ�.";
			} else if($type=="RP") {
				$memberGroupInfo .= "���� �������� ".number_format($memberInfo_row->group_addmoney)."�踦 ������ �帳�ϴ�.";
			} else if($type=="SW") {
				$memberGroupInfo .= "���űݾ��� ".number_format($memberInfo_row->group_addmoney)."���� <b>�߰�����</b>�� �帳�ϴ�.";
			} else if($type=="SP") {
				$memberGroupInfo .= "���űݾ��� ".number_format($memberInfo_row->group_addmoney)."%�� <b>�߰�����</b>�� �帳�ϴ�.";
			}
		}


	}
}

// ȸ���̹���
array_push($pattern,'(\[MEMBER_INFO_IMG\])');
array_push($replace,$memberInfoImage);

// ȸ���̸�
array_push($pattern,'(\[MEMBER_INFO_NAME\])');
array_push($replace,$memberInfoName);

// ȸ���׷�
array_push($pattern,'(\[MEMBER_INFO_GROUP\])');
array_push($replace,$memberInfoGroup);

// ȸ����� ���� ����
array_push($pattern,'(\[MEMBER_GROUP_INFO\])');
array_push($replace,$memberGroupInfo);


//��� �߰����� -------------
	//�����׷��
	array_push($pattern,'(\[NEXT_GRP_NAME\])');
	array_push($replace,$nextGroupInfo_groupName);
	//�������űݾ�
	array_push($pattern,'(\[NEXT_GRP_R_PRICE\])');
	array_push($replace,$nextGroupInfo_rprice);
	//���� ���ŰǼ�
	array_push($pattern,'(\[NEXT_GRP_R_CNT\])');
	array_push($replace,$nextGroupInfo_rcnt);
	//���űⰣ(�������űݾ� �����Ⱓ) ����
	array_push($pattern,'(\[NEXT_GRP_DATE_S\])');
	array_push($replace,$nextGroupInfo_dateStart);
	//���űⰣ(�������űݾ� �����Ⱓ) ��
	array_push($pattern,'(\[NEXT_GRP_DATE_E\])');
	array_push($replace,$nextGroupInfo_dateEnd);
	//���ŰǼ�
	array_push($pattern,'(\[NEXT_GRP_CNT\])');
	array_push($replace,$nextGroupInfo_cnt);
	//��������Ⱓ
	array_push($pattern,'(\[NEXT_GRP_KEEP\])');
	array_push($replace,$nextGroupInfo_keepclass);
	//���űݾ�
	array_push($pattern,'(\[NEXT_GRP_PRICE\])');
	array_push($replace,$nextGroupInfo_price);

	// ȸ�������� ����
	if(strpos($body,"[IF_MEMBER_S]")!=0) {
		$memeberInfoNumS=strpos($body,"[IF_MEMBER_S]");
		$memeberInfoNumE=strpos($body,"[IF_MEMBER_E]");
		if(strlen($nextGroupInfo_groupName)>0) {
			$memeberInfo=substr($body,$memeberInfoNumS+13,$memeberInfoNumE-$memeberInfoNumS-13);
		} else{
			$memeberInfo="";
		}
		$body=substr($body,0,$memeberInfoNumS).$memeberInfo.substr($body,$memeberInfoNumE+13);
	}

// ȸ����� �� �߰�����/�����ȳ� ------------------------------------------------------ end










$body=preg_replace($pattern,$replace,$body);

echo $body;

?>