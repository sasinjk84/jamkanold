<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/basket_func.php");
include_once($Dir."lib/ext/order_func.php");

include_once($Dir."lib/ext/rent.php");

try{
	if(_empty($_ShopInfo->getMemid())) throw new ErrorException('�α��� �Ǿ� ���� �ʽ��ϴ�.');	
	//@mysql_query("DELETE FROM tblbasket_recommand WHERE tempkey='".$_ShopInfo->getTempkey()."' ");
	@mysql_query("DELETE FROM tblbasket_recommand WHERE memid='".$_ShopInfo->getMemid()."' ");
	
	$sql = "select * from recommand_basket where memid='".$_ShopInfo->getMemid()."' and recomcode is null";
	
	if(false === $res = mysql_query($sql,get_db_conn())) throw new ErrorException('DB ȣ�� ����');
	//throw new ErrorException($sql."/".mysql_num_rows($res));
	if(mysql_num_rows($res) < 1) throw new ErrorException('['.$_ShopInfo->getMemid().']��ٱ��Ͽ� ��ϵ� ��ǰ�� �����ϴ�.');
	$sql = "select count(recomcode) from recommand_request";
	if(false === $res = mysql_query($sql,get_db_conn()))  throw new ErrorException('DB ȣ�� ����');
	$cnt = mysql_result($res,0,0)+1;
	
	do{
		$recomcode = date('Ymd').$cnt.'R'.rand(1111,9999);
		$sql = "select * from recommand_request where recomcode='".$recomcode."'";
		if(false === $res = mysql_query($sql,get_db_conn()))  throw new ErrorException('DB ȣ�� ����2');
	}while(mysql_result($res) > 0);
	
	$sms = implode('-',$_REQUEST['sms']);
	$_REQUEST['smsmsg'] = "��õ���� ��ǰ�� �ֽ��ϴ�.\r\n".$_REQUEST['email']." �� Ȯ���ϼ���.";

	//$sql = "insert into recommand_request set recomcode='".$recomcode."',name="._escape($_REQUEST['name']).",memid="._escape($_REQUEST['memid']).",sms="._escape($sms).",email="._escape($_REQUEST['email']).",smsmsg="._escape($_REQUEST['smsmsg']).",emailmsg="._escape($_REQUEST['emailmsg']).",reqid='".$_ShopInfo->getMemid()."'";

	$sql = "insert into recommand_request set recomcode='".$recomcode."',name="._escape($_REQUEST['name']).",memid="._escape($_REQUEST['memid']).",sms="._escape($sms).",email="._escape($_REQUEST['email']).",smsmsg="._escape($_REQUEST['smsmsg']).",emailmsg="._escape($_REQUEST['emailmsg']).",reqid="._escape($_REQUEST['reseller_id']);

	
	if(false === $res = mysql_query($sql,get_db_conn()))  throw new ErrorException('DB ��� ����');
	
	$sql = "update recommand_basket set recomcode='".$recomcode."'  where recomcode is null and  memid='".$_ShopInfo->getMemid()."'";
	//$sql = "update recommand_basket set recomcode='".$recomcode."'  where recomcode is null and  memid="._escape($_REQUEST['reseller_id']);
	mysql_query($sql,get_db_conn());
	
	if(!_empty($sms) && !_empty($_REQUEST['smsmsg'])){ // SMS������
		$sqlsms = "SELECT * FROM tblsmsinfo LIMIT 1 ; ";
		$resultsms= mysql_query($sqlsms,get_db_conn());
		if($rowsms=mysql_fetch_object($resultsms)){			
			$sms_id=$rowsms->id;
			$sms_authkey=$rowsms->authkey;
			
			if(strlen($rowsms->subadmin1_tel)>8) $totellist.=",".$rowsms->subadmin1_tel;
			if(strlen($rowsms->subadmin2_tel)>8) $totellist.=",".$rowsms->subadmin2_tel;
			if(strlen($rowsms->subadmin3_tel)>8) $totellist.=",".$rowsms->subadmin3_tel;
			$fromtel=$rowsms->return_tel;
			if(!_empty($sms_id) && !_empty($sms_authkey)){
				$temp=SendSMS($sms_id, $sms_authkey, $sms, "", $fromtel,'0',$_REQUEST['smsmsg'], $etcmsg);
			}
		}
	}
	
	//if(!_empty($_REQUEST['emailmsg'])){ // E-mail ������
	//SendRecommandMail($_data->shopname, $_data->shopurl, $_REQUEST['emailmsg'], $_data->info_email, $_ShopInfo->getMemname(), $_REQUEST['email'], $_REQUEST['name'],'RECOM'.$recomcode);
	SendRecommandMail($_data->shopname, $_data->shopurl, $_REQUEST['emailmsg'], $_data->info_email,$_ShopInfo->getMemid(), $_REQUEST['reseller_id'], $_REQUEST['email'], $_REQUEST['name'],'RECOM'.$recomcode);
//	}
	_alert('��õ ������ �߼� �߽��ϴ�.','/');

}catch(ErrorException $e){
//	echo $e->getMessage();
	_alert($e->getMessage(),'-1');	
}catch(Exception $e){
	
}

function SendRecommandMail($shopname, $shopurl,$msg,$info_email,$memid,$sendername,$email,$name,$recomcode) {
	$subject = $shopname." ���� ".$sendername." ���� ��õ �Ͻ� ��ǰ �Դϴ�.";
	
	$curdate = date("Y�� m�� d��");
	$pattern = array ("(\[SHOP\])","(\[NAME\])","(\[ID\])","(\[PASSWORD\])","(\[URL\])","(\[CURDATE\])");
	$replace = array ($shopname,$name,$id,$passwd,$shopurl,$curdate);
	
	$body	 = nl2br(preg_replace($pattern,$replace,$msg));
//	$body .= "<br>��ǰ ���� ��õ ������ȣ�� (".$recomcode.") �Դϴ�.";
	$body .= "<br><br><b style='color:#ff0000'>������</b> - ".$name."�Բ��� ��õ���� ��ǰ";
	$body .= "<br><a href='http://".$shopurl.((substr($shopurl,-1,1)=='/')?'':'/')."front/basket.php?ordertype=recommand&rcode=".$recomcode."'>http://".$shopurl.((substr($shopurl,-1,1)=='/')?'':'/')."front/basket.php?ordertype=recommand&rcode=".$recomcode."</a>";
	//$body .= "<br><a href='http://".$shopurl.((substr($shopurl,-1,1)=='/')?'':'/')."front/order.php?ordertype=recommand&rcode=".$recomcode."'>http://".$shopurl.((substr($shopurl,-1,1)=='/')?'':'/')."front/order.php?ordertype=recommand&rcode=".$recomcode."</a>";
	

	$mailtop.="		<style type=\"text/css\">";
	$mailtop.="		body, td {font-family:����;font-size:12px;color:#666666;line-height:18px;}";
	$mailtop.="		img {margin:0; border:0;}";
	$mailtop.="		</style>";

	$mailtop.="		<table cellpadding=0 cellspacing=0 width=100% style='table-layout:fixed'>\n";
	$mailtop.="		<col width=80></col>\n";
	$mailtop.="		<col></col>\n";
	$mailtop.="		<col width=100></col>\n";
	$mailtop.="		<col width=150></col>\n";
	$mailtop.="		<col width=70></col>\n";
	$mailtop.="		<col width=100></col>\n";
	$mailtop.="		<col width=100></col>\n";
	$mailtop.="		<col width=100></col>\n";
	$mailtop.="		<col width=100></col>\n";
	$mailtop.="		<tr>\n";
	$mailtop.="			<td height=2 colspan=9 bgcolor=#969696></td>\n";
	$mailtop.="		</tr>\n";
	$mailtop.="		<tr height=30 align=center bgcolor=#f9f9f9>\n";
	$mailtop.="			<td colspan=2><font color=#474747><b>��ǰ����</b></font></td>\n";
	$mailtop.="			<td><font color=#474747><b>��ǰ�ݾ�</b></font></td>\n";
	$mailtop.="			<td><font color=#474747><b>�Ⱓ</b></font></td>\n";
	$mailtop.="			<td><font color=#474747><b>����</b></font></td>\n";
	$mailtop.="			<td><font color=#474747><b>����</b></font></td>\n";
	$mailtop.="			<td><font color=#474747><b>�ֹ��ݾ�</b></font></td>\n";
	$mailtop.="			<td><font color=#474747><b>��ۺ�</b></font></td>\n";
	$mailtop.="			<td><font color=#474747><b>�Ǹ���</b></font></td>\n";
	$mailtop.="		</tr>\n";
	$mailtop.="		<tr>\n";
	$mailtop.="			<td height=1 colspan=9 bgcolor=#e4e4e4></td>\n";
	$mailtop.="		</tr>\n";

	$recomcode = substr($recomcode,5);
	$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,a.deli_type,b.vender,b.productcode,b.productname,b.sellprice, b.reserve,b.reservetype,b.addcode,b.tinyimage,b.option_price,b.option_quantity,b.option1,b.option2, b.brand, b.etctype,b.deli_price, b.deli,b.sellprice*a.quantity as realprice, b.selfcode,a.basketidx, b.sns_state,b.present_state,b.pester_state,b.sns_reserve2,b.sns_reserve2_type,b.rental, b.pridx, b.date, b.tax_yn,r.*,rp.trust_vender,rp.istrust FROM recommand_basket a inner join rent_basket_temp r on r.basketidx = a.basketidx left join tblproduct b ON a.productcode=b.productcode left join rent_product rp on b.pridx=rp.pridx WHERE recomcode='".$recomcode."' AND memid='".$memid."' and ordertype='recommand' ORDER BY rental asc,deli ASC, deli_price ASC ";

	$result=mysql_query($sql,get_db_conn());
	
	unset($tempmaildata);
	//while($row = mysql_fetch_object($result)) {
	while($product = mysql_fetch_assoc($result)){

		//if (substr($product[productcode],0,3)=="999" || substr($product[productcode],0,3)=="COU") {
		if (substr($product[productcode],0,3)=="COU") {
			$etcdata[]=$product;
			continue;
		}
		if($product[reserve]>0) $reserve+=$product[reserve]*$product[quantity];


		//����������������
		if($product[istrust]=="0"){
			$sql2 = "SELECT com_name FROM tblvenderinfo WHERE vender='".$product[trust_vender]."'";
			$p_vender = $product[trust_vender];
		}else{
			$sql2 = "SELECT com_name FROM tblvenderinfo WHERE vender='".$product[vender]."'";
			$p_vender = $product[vender];
		}
		$res2=mysql_query($sql2,get_db_conn());
		$venderinfo = mysql_fetch_object($res2);

		if ($product['deli_type'] == "�ù�") {
			// ��۷�
			$deliPrtChk="";
			$deliPrtRowspan = "";
			if($product[deli_price]>0){
				if($product[deli]=="Y"){
					$deliprice = $product[deli_price]*$product[quantity];
				}else if($product[deli]=="N") {
					$deliprice = $product[deli_price];
				}

				$delimsg = "����";
				if ($deliprice > 0) {
					$totaldeliprice += $deliprice;
					$delimsg = number_format($deliprice)."��";
				}
				$deliPrt = "������<br>(".$delimsg.")";
			}else if($product[deli]=="F" || $product[deli]=="G"){
				$deliPrt = ($product[deli]=="F"?'��������':'����');
			}else{
				$deliPrt  = "�⺻��ۺ�<br/>(";
				if ($venderinfo->deli_price > 0) {
					if ($vender == 0 && $venderDeliPrintCHK == true) {
						$deliPrt .= "����";
					} else {
						$totaldeliprice += $venderinfo->deli_price;
						$deliPrt .= number_format($venderinfo->deli_price)."��";
					}
				} else {
					$deliPrt .= "����";
				}
				$deliPrt .= ")";
				$deliPrtChk = $vender."D";
			}

			// ��ۺ� ���̺� ����
			if( strlen($deliPrtChk) > 0 ) {
				$deliPrtArr[$deliPrtChk]++;
				if( $deliPrtArr[$deliPrtChk] > 1 ) {
					$deliPrt = "";
				} else{
					$deliCount = $deliCount[$product[deli]][($product[deli_price]>0?"1":"0")];

					if( $deliCount > 1 ) {
						$deliPrtRowspan = " rowspan = '".$deliCount."'";
					}
				}
			}
		}else{
			$deliPrt = $product['deli_type'];
		}
		
		if($product['rental'] != '2'){ // �Ϲ� ��ǰ 
			$producttotalprice+= $product['sellprice']*$product['quantity'];

			//������
			$mem_reserve = getProductReserve($product['productcode']);
			$reserve_total += $producttotalprice*$mem_reserve;



			$sellprice = $product[sellprice]*$product[quantity];

			$diff = datediff_rent($product[end],$product[start]);

			// ��� ���� ��ȸ
			$longdiscountP = venderLongDiscount($p_vender,$product['pridx'],abs($diff['day']));
			if($longdiscountP < 0){
				$longdiscountP = rentLongDiscount($product['pridx'],abs($diff['day']));	
			}

			if($longdiscountP > 0){
				$discprice = -1*floor($sellprice*($longdiscountP/100));
			}
			
			$totaldiscprice += $discprice;
			//$totaldiscprice = number_format(abs($discprice+$sellprice*$member_discount)).'��';
		}else{
			$bitem = array();
			$bitem[$product['optidx']] = $product['quantity'];
			$basketitems['solvprice'] = rentProduct::solvPrice($product['pridx'],$bitem,$product['start'],$product['end'],$product['vender']);
			$product['realprice'] += $basketitems['solvprice']['totalprice'] - abs($basketitems['solvprice']['discprice']);
			$product['sellprice'] = $product['realprice']/$basketitems['quantity'];
			$product['longdiscount'] =$basketitems['solvprice']['discprice'];
			$product['rentinfo'] = $basketitems;

			$tmpPinfo = rentProduct::read($product['pridx']);
			$rentItem = $product['rentinfo'];
			$roptinfo = &$tmpPinfo['options'][$rentItem['optidx']];
			$sellprice = $rentItem['solvprice']['totalprice'];
			$discprice = -1*abs($rentItem['solvprice']['discprice']);


			//������
			$mem_reseller_reserve = getProductReseller_Reserve($product['productcode']);
			$reserve_total += $product['realprice']*$mem_reseller_reserve;


		}

		$tempmaildata.="<tr>\n";
		$tempmaildata.="	<td style='padding:10px 0 10px 10px; vertical-align:text-top;'>";
		if(strlen($product[tinyimage])!=0 && file_exists(DirPath.DataDir."shopimages/product/".$product[tinyimage])){
			$tempmaildata.="<img src=\"http://".$shopurl.DataDir."shopimages/product/".$product[tinyimage]."\" ";
		} else {
			$tempmaildata.="<img src=\"http://".$shopurl."images/no_img.gif\" ";
		}
		$tempmaildata.=" width=\"54\" height=\"54\" style='border:1px solid #d0d0d0;'></td>";
		$tempmaildata.="	<td style='padding-left:10px;word-break:break-all;color:#ff6c00;'>";
		$tempmaildata.="<B>".$product[productname]."</B>";
		$tempmaildata.="	</td>\n";
		if($product['rental'] != '2'){ // �Ϲ� ��ǰ 
			$tempmaildata.="	<td align=center>".number_format($sellprice)."��"."</td>\n";
		}else{
			$tempmaildata.="	<td align=center>".number_format($rentItem['solvprice']['prdrealprice'])."��"."</td>\n";
		}
		$tempmaildata.="	<td align=center>".$product[start].'<br>'.$product[end]."</td>\n";
		$tempmaildata.="	<td align=center>".$product[quantity]."</td>\n";
		
		if($product['rental'] != '2'){ // �Ϲ� ��ǰ 
			$tempmaildata.="	<td align=center>".number_format($discprice)."��"."</td>\n";
		//	$tempmaildata.="	<td align=center><font color=#ff6c00><b>".number_format($sellprice+$discprice)."��</b></font></td>\n";
		}else{
			$tempmaildata.="	<td align=center>".number_format(abs($rentItem['solvprice']['discprice']))."��"."</td>\n";
		//	$tempmaildata.="	<td align=center><font color=#ff6c00><b>".number_format($rentItem['solvprice']['totalprice']+$discprice)."��</b></font></td>\n";
		}
		$tempmaildata.="	<td align=center><font color=#ff6c00><b>".number_format($sellprice+$discprice)."��</b></font></td>\n";
		$tempmaildata.="	<td align=center ".$deliPrtRowspan.">".number_format($product[deli_price])."��</td>\n";
		$tempmaildata.="	<td align=center ".$deliPrtRowspan.">".$venderinfo->com_name."</td>\n";
		$tempmaildata.="</tr>\n";

		$tempmaildata.="<tr><td colspan=9 height=1 bgcolor=#e4e4e4></td></tr>\n";

		$cnt++;

		$producttotalprice += $sellprice;
		$sumprice+=$sellprice+$discprice;
	}
		
	$maildata[0]=$mailtop.$maildata[0];
	if(strlen($tempmaildata)>0) {
		$maildata[0].=$tempmaildata;
	}

	if ($totaldeliprice > 0) {
		$disp_sumprice = number_format($totaldeliprice + $sumprice).'��';
		$disp_deliprice = '(+)'.number_format($totaldeliprice);
	} else {
		$disp_sumprice = number_format($sumprice).'��';

		if ($deliPrt) {
			$disp_deliprice = '����';
		} else {
			$disp_deliprice = 0;
		}
	}


	$maildata[0].="<tr>\n";
	$maildata[0].="	<td height=45></td>\n";
	$maildata[0].="</tr>\n";
	$maildata[0].="<tr>\n";
	$maildata[0].="	<td colspan=9>\n";
	$maildata[0].="	<table cellpadding=0 cellspacing=0 width=100%>\n";
	$maildata[0].="	<tr>\n";
	$maildata[0].="		<td height=2 bgcolor=#969696></td>\n";
	$maildata[0].="	</tr>\n";
	$maildata[0].="	<tr height=30 bgcolor=#f9f9f9>\n";
	$maildata[0].="		<td align=right style='padding-right:10px;'><font color=#474747><B>�� ��ǰ�ݾ�&nbsp;:&nbsp;</b></font><font color=#ff6c00 style='font-size:12pt;'><b>".number_format($producttotalprice)."��</b></font></td>\n";
	$maildata[0].="	</tr>\n";
	$maildata[0].="	<tr height=30 bgcolor=#f9f9f9>\n";
	$maildata[0].="		<td align=right style='padding-right:10px;'><font color=#474747><B>��ǰ����&nbsp;:&nbsp;</b></font><font color=#ff6c00 style='font-size:12pt;'><b>".number_format($totaldiscprice)."��</b></font></td>\n";
	$maildata[0].="	</tr>\n";
	$maildata[0].="	<tr height=30 bgcolor=#f9f9f9>\n";
	$maildata[0].="		<td align=right style='padding-right:10px;'><font color=#474747><B>��ۺ�&nbsp;:&nbsp;</b></font><font color=#ff6c00 style='font-size:12pt;'><b>".$disp_deliprice."</b></font></td>\n";
	$maildata[0].="	</tr>\n";
	$maildata[0].="	<tr height=30 bgcolor=#f9f9f9>\n";
	$maildata[0].="		<td align=right style='padding-right:10px;'><font color=#474747><B>�� �����ݾ�&nbsp;:&nbsp;</b></font><font color=#ff6c00 style='font-size:12pt;'><b>".$disp_sumprice."</b></font></td>\n";
	$maildata[0].="	</tr>\n";
	$maildata[0].="	<tr>\n";
	$maildata[0].="		<td height=1 bgcolor=#e4e4e4></td>\n";
	$maildata[0].="	</tr>\n";
	$maildata[0].="	</table>\n";
	$maildata[0].="	</td>\n";
	$maildata[0].="</tr>\n";
	$maildata[0].="<tr>\n";
	$maildata[0].="	<td height=45>";
	$maildata[0].="		<a href=\"http://".$shopurl."\" target='_blank'><button type=button style=\"width:200px;height:40px;padding:5px;background:#efefef\">���θ��� �̵�</button></a>";
	$maildata[0].="	</td>\n";
	$maildata[0].="</tr>\n";
	$maildata[0].="</table>\n";


	$body .= $maildata[0];

	if(strlen($shopname)>0) $mailshopname = "=?ks_c_5601-1987?B?".base64_encode($shopname)."?=";
	$header=getMailHeader($mailshopname,$info_email);
	if(ismail($email)) {
		sendmail($email, $subject, $body, $header);	
	}	
}
exit;
?>