	<?
	if(substr(getenv("SCRIPT_NAME"),-16)=="/basket_text.php"){
		header("HTTP/1.0 404 Not Found");
		exit;
	}

	$codeA=$_POST["codeA"];
	$codeB=$_POST["codeB"];
	$codeC=$_POST["codeC"];
	$codeD=$_POST["codeD"];
	$likecode=$codeA.$codeB.$codeC.$codeD;

	$one_start = "<form name=form1 method=post action=\"".$_SERVER[PHP_SELF]."\">\n";
	$one_start.= "<input type=hidden name=productcode>\n";
	$one_start.= "<input type=hidden name=quantity>\n";
	$one_start.= "<input type=hidden name=option1>\n";
	$one_start.= "<input type=hidden name=option2>\n";
	$one_start.= "<input type=hidden name=assembleuse>\n";
	$one_start.= "<input type=hidden name=package_num>\n";

	$one_codeA = "<select name=codeA style=\"".$codeA_style."\" onchange=\"SearchChangeCate(this,1);CheckCode();\">\n";
	$one_codeA.= "<option value=\"\">--- 1�� ī�װ� ���� ---</option>\n";
	$one_codeA.= "</select>\n";

	$one_codeB = "<select name=codeB style=\"".$codeB_style."\"  onchange=\"SearchChangeCate(this,2);CheckCode();\">\n";
	$one_codeB.= "<option value=\"\">--- 2�� ī�װ� ���� ---</option>\n";
	$one_codeB.= "</select>\n";

	$one_codeC = "<select name=codeC style=\"".$codeC_style."\"  onchange=\"SearchChangeCate(this,3);CheckCode();\">\n";
	$one_codeC.= "<option value=\"\">--- 3�� ī�װ� ���� ---</option>\n";
	$one_codeC.= "</select>\n";

	$one_codeD = "<select name=codeD style=\"".$codeD_style."\"  onchange=\"CheckCode();\">\n";
	$one_codeD.= "<option value=\"\">--- 4�� ī�װ� ���� ---</option>\n";
	$one_codeD.= "</select>\n";

	$one_prlist = "<select name=tmpprcode style=\"".$prlist_style."\" onchange=\"CheckProduct();\">\n";
	$one_prlist.= "<option value=\"\">��ǰ ����</option>\n";
	if(strlen($likecode)==12) {
		$sql = "SELECT a.productcode,a.productname,a.sellprice,a.tinyimage,a.quantity,a.option1,a.option2, ";
		$sql.= "a.etctype,a.selfcode,a.assembleuse,a.package_num FROM tblproduct AS a ";
		$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
		$sql.= "WHERE a.productcode LIKE '".$likecode."%' AND a.display='Y' ";
		$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
		$sql.= "ORDER BY a.productname ";
		$result=mysql_query($sql,get_db_conn());
		$ii=0;
		$prlistscript="<script>\n";
		while($row=mysql_fetch_object($result)) {
			if(strlen(dickerview($row->etctype,$row->sellprice,1))==0) {
				$miniq = 1;
				if (strlen($row->etctype)>0) {
					$etctemp = explode("",$row->etctype);
					for ($i=0;$i<count($etctemp);$i++) {
						if (substr($etctemp[$i],0,6)=="MINIQ=") $miniq=substr($etctemp[$i],6);  // �ּ��ֹ�����
					}
				}
				$one_prlist.= "<option value=\"".$ii."\">".strip_tags(str_replace("<br>", " ", viewselfcode($row->productname,$row->selfcode)))." - ".number_format($row->sellprice)."��";
				if(strlen($row->quantity)!=0 && $row->quantity<=0) $one_prlist.= " (ǰ��)";
				$one_prlist.= "</option>\n";

				if(strlen($row->quantity)!=0 && $row->quantity<=0) {
					$tmpq=0;
				} else {
					$tmpq=$row->quantity;
					if($row->quantity==NULL) $tmpq=1000;
				}
				$prlistscript.="var plist=new pralllist();\n";
				$prlistscript.="plist.productcode='".$row->productcode."';\n";
				$prlistscript.="plist.tinyimage='".$row->tinyimage."';\n";
				$prlistscript.="plist.option1=1;\n";
				$prlistscript.="plist.option2=1;\n";
				$prlistscript.="plist.quantity=".$tmpq.";\n";
				$prlistscript.="plist.miniq=".$miniq.";\n";
				$prlistscript.="plist.assembleuse='".($row->assembleuse=="Y"?"Y":"N")."';\n";
				$prlistscript.="plist.package_num='".((int)$row->package_num>0?$row->package_num:"")."';\n";
				$prlistscript.="prall[".$ii."]=plist;\n";
				$prlistscript.="plist=null;\n";
				$ii++;
			}
		}
		mysql_free_result($result);
		$prlistscript.="</script>\n";
	}

	$sql = "SELECT * FROM tblproductcode WHERE 1=1 ";
	if(strlen($_ShopInfo->getMemid())==0 || $_ShopInfo->getMemid()=="deleted") {
		$sql.= "AND group_code='' ";
	} else {
		$sql.= "AND (group_code='' OR group_code='ALL' OR group_code='".$_ShopInfo->getMemgroup()."') ";
	}
	$sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') ORDER BY sequence DESC ";
	$i=0;
	$ii=0;
	$iii=0;
	$iiii=0;
	$strcodelist = "";
	$strcodelist.= "<script>\n";
	$result = mysql_query($sql,get_db_conn());
	$selcode_name="";
	while($row=mysql_fetch_object($result)) {
		$strcodelist.= "var clist=new CodeList();\n";
		$strcodelist.= "clist.codeA='".$row->codeA."';\n";
		$strcodelist.= "clist.codeB='".$row->codeB."';\n";
		$strcodelist.= "clist.codeC='".$row->codeC."';\n";
		$strcodelist.= "clist.codeD='".$row->codeD."';\n";
		$strcodelist.= "clist.type='".$row->type."';\n";
		$strcodelist.= "clist.code_name='".$row->code_name."';\n";
		if($row->type=="L" || $row->type=="T" || $row->type=="LX" || $row->type=="TX") {
			$strcodelist.= "lista[".$i."]=clist;\n";
			$i++;
		}
		if($row->type=="LM" || $row->type=="TM" || $row->type=="LMX" || $row->type=="TMX") {
			if ($row->codeC=="000" && $row->codeD=="000") {
				$strcodelist.= "listb[".$ii."]=clist;\n";
				$ii++;
			} else if ($row->codeD=="000") {
				$strcodelist.= "listc[".$iii."]=clist;\n";
				$iii++;
			} else if ($row->codeD!="000") {
				$strcodelist.= "listd[".$iiii."]=clist;\n";
				$iiii++;
			}
		}
		$strcodelist.= "clist=null;\n\n";
	}
	mysql_free_result($result);
	$strcodelist.= "CodeInit();\n";
	$strcodelist.= "</script>\n";

	$one_end = "</form>\n";
	$one_end.= $strcodelist."\n";
	$one_end.= $prlistscript."\n";
	$one_end.= "<script>SearchCodeInit('".$codeA."','".$codeB."','".$codeC."','".$codeD."');</script>";

	$one_primg="<img src=\"".$Dir."images/common/basket/oneshot_primageU.gif\" border=0 width=50 height=50 name=oneshot_primage>";









	/* ��ٱ��� ��ǰ ���� ���� */
	$timgsize = 50;
	foreach($basketItems['vender'] as $vender=>$vendervalue){

		for( $i = 0 ; $i < count($vendervalue['products']) ; $i++ ){

			$formcount++;

			$pattern = array();
			$replace = array();

			$product = $vendervalue['products'][$i];

			$tempbasket.=$mainbasket;

			if(!$couponable && $product['cateAuth']['coupon'] == 'Y'){
				$chkcoupons = array();
				$chkcoupons = getMyCouponList($product['productcode']);
				if(_array($chkcoupons)) {
					$couponable = true;
				}
			}
			if(!$reserveuseable && $product['cateAuth']['reserve'] == 'Y') {
				$reserveuseable = true;
			}



			$arPresent[$formcount] = $product['present_state'];
			$arPester[$formcount] = $product['pester_state'];
			$sellChk = true;
			if($product['sell_startdate'] && $product['sell_enddate']){
				$sellChk = false;
				if($product['sell_startdate']<time() && time()<$product['sell_enddate']){
					$sellChk = true;
				}
			}





			// ���� �׷� ó��
			$groupbasketvalue="";
			//echo $formcount;
			if( count($vendervalue['products']) == ($i+1) ) $groupbasketvalue="[GROUPBASKETVALUE]";

			array_push($pattern,'(\[GROUPBASKETVALUE\])');
			array_push($replace,$groupbasketvalue);




			// ��ٱ��� ��ǰ�� ���� ���� : ��ǰ�� ���� ó���� =======================
			$basket_start = "
				<form name=\"form_".$formcount."\" method=post action=\"".$Dir.FrontDir."basket.php\">
					<input type=hidden name=\"mode\">
					<input type=hidden name=\"code\" value=\"".$code."\">
					<input type=hidden name=\"productcode\" value=\"".$product['productcode']."\">
					<input type=hidden name=\"orgquantity\" value=\"".$product['quantity']."\">
					<input type=hidden name=\"orgoption1\" value=\"".$product['opt1_idx']."\">
					<input type=hidden name=\"orgoption2\" value=\"".$product['opt2_idx']."\">
					<input type=hidden name=\"opts\" value=\"".$product['optidxs']."\">
					<input type=hidden name=\"brandcode\" value=\"".$brandcode."\">
					<input type=hidden name=\"assemble_list\" value=\"".$product['assemble_list']."\">
					<input type=hidden name=\"assemble_idx\" value=\"".$product['assemble_idx']."\">
					<input type=hidden name=\"package_idx\" value=\"".$product['package_idx']."\">
					<input type=hidden name=\"productname\" value=\"".strip_tags($product['productname'])."\">
			";

			array_push($pattern,'(\[FORBASKET\])');
			array_push($replace,$basket_start);



			// ��ٱ��� ��ǰ�� �� ���� : ��ǰ�� ���� ó���� =======================
			array_push($pattern,'(\[FORENDBASKET\])');
			array_push($replace,"</form>");



			// �ɼ� ó��
			$basket_option="";
			$tempoption="";
			if($sellChk){
				//�ɼ� 1
				if (_array($product['option1'])) {
					$tok = $product['option1'];
					$count=count($tok);
					$basket_option.= $tok[0]." ";
					$basket_option.= "<select name=option1 size=1 onchange=\"CheckForm('upd',".$formcount.")\">\n";
					for($o1=1;$o1<$count;$o1++){
						if(strlen($tok[$o1])>0){
							$sel = ($o1==$product['opt1_idx']) ? " selected" : "";
							$basket_option.= "<option value=\"".$o1."\" ".$sel.">".$tok[$o1]."</option>";
						}
					}
					$basket_option.= "</select>";
				}

				// �ɼ� 2
				if (_array($product['option2'])) {
					$tok = $product['option2'];
					$count=count($tok);
					$basket_option.= $tok[0]." ";
					$basket_option.= "<select name=option2 size=1 onchange=\"CheckForm('upd',".$formcount.")\">\n";
					for($o2=1;$o2<$count;$o2++){
						if(strlen($tok[$o2])>0){
							$sel = ($o2==$product['opt2_idx']) ? " selected" : "";
							$basket_option.= "<option value=\"".$o2."\" ".$sel.">".$tok[$o2]."</option>";
						}
					}
					$basket_option.= "</select>";

					$tempoption = $optionbasket;
					$tempoption = ereg_replace("\[BASKET_OPTION\]",$basket_option,$tempoption);
				}
			}

			array_push($pattern,'(\[OPTIONVALUE\])');
			array_push($replace,$tempoption);



			// ��ǰ ���� ���� �� ��Ʈ�� =====================================
			if($sellChk){
				$basket_quantity="<input name=quantity value=\"".$product['quantity']."\" size=3 maxlength=4 class=\"inputQuantity\" onkeyup=\"strnumkeyup(this)\">";
				$basket_qup="\"javascript:change_quantity('up',".($formcount).")\"";
				$basket_qdn="\"javascript:change_quantity('dn',".($formcount).")\"";
				$basket_qupdate="\"javascript:CheckForm('upd',".($formcount).")\"";
			}else{
				$basket_quantity="<input name=quantity value=\"".$product['quantity']."\" size=3 maxlength=4 class=\"inputQuantity\" readonly \">";
				$basket_qup="\"#none\"";
				$basket_qdn="\"#none\"";
				$basket_qupdate="\"javascript:alert('�Ǹ������ǰ�Դϴ�.');\"";
			}

			// ���ż�
			array_push($pattern,'(\[BASKET_QUANTITY\])');
			array_push($replace,$basket_quantity);

			// ���ż� 1 ���ϱ�
			array_push($pattern,'(\[BASKET_QUP\])');
			array_push($replace,$basket_qup);

			// ���ż� 1 ����
			array_push($pattern,'(\[BASKET_QDN\])');
			array_push($replace,$basket_qdn);

			// ���ż� ���� ��ư
			array_push($pattern,'(\[BASKET_QUPDATE\])');
			array_push($replace,$basket_qupdate);






			// ��ǰ ���ϱ� (���ø���Ʈ ����) ======================================
			if($sellChk){
				if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
					$basket_wishlist="javascript:go_wishlist('".($formcount)."')";
				} else {
					$basket_wishlist="javascript:check_login()";
				}
			}else{
				$basket_wishlist ="javascript:alert('�Ǹ������ǰ�Դϴ�.')";
			}

			array_push($pattern,'(\[BASKET_WISHLIST\])');
			array_push($replace,$basket_wishlist);



			// ��ٱ��Ͽ��� ����
			array_push($pattern,'(\[BASKET_DEL\])');
			array_push($replace,"javascript:CheckForm('del',".($formcount).")");



			// ��ǰ ���� ���� ============================================
			array_push($pattern,'(\[BASKET_SELLPRICE\])');
			array_push($replace,number_format($product['sellprice']));



			// ��ǰ ���� ���� ���� ============================================
			array_push($pattern,'(\[BASKET_PRICE\])');
			array_push($replace,number_format($product['realprice']));





			// ������ =================================================
			array_push($pattern,'(\[BASKET_RESERVE\])');
			array_push($replace,number_format($product['reserve']));




			// Ư�� ���� ================================================
			if(strlen($product['addcode'])==0) {
				$basket_addcode1="";
			} else{
				$basket_addcode1="-".$product['addcode'];
			}
			$basket_addcode2=$product['addcode'];

			array_push($pattern,'(\[BASKET_ADDCODE1\])');
			array_push($replace,$basket_addcode1);

			array_push($pattern,'(\[BASKET_ADDCODE2\])');
			array_push($replace,$basket_addcode2);




			// ��ۺ� ���� ���� ��� =======================================
			$deliPrtChk="";
			$deliPrtRowspan = "";
			if($product['deli_price']>0){
				if($product['deli']=="Y"){
					$deliPrt = "������<br>(". $product['deli_price']*$product['quantity'] ."��)";
				}else if($product['deli']=="N") {
					$deliPrt = "������<br />(". number_format($product['deli_price'])."��)";
				}
			}else if($product['deli']=="F" || $product['deli']=="G"){
				$deliPrt = ($product['deli']=="F"?'��������':'����');
			}else{
				if($vender == 0) {
					$deliPrt = "�⺻��ۺ�";
					$productRealPrice += $product['realprice'];
				} else {
					$deliPrt = "������<br />�⺻���";
				}
				$deliPrtChk = $vender."D";
			}


			array_push($pattern,'(\[DELI_STR\])');
			array_push($replace,$deliPrt);




			// ��ٱ��� ��ǰ�� üũ�ڽ� ===================================
			array_push($pattern,'(\[ITEM_CHKBOX\])');
			array_push($replace,"<input type=\"checkbox\" name=\"basket_select_item\" value=\"".$product['basketidx']."\" >");



			// ��ٱ��� �̹��� �̸����� ====================================
			if($product['tinyimage'][$product['tinyimage']['big']] > $timgsize) {
				$imageSize = $product['tinyimage']['big'].'="'.$timgsize.'"';
			} else{
				$imageSize = "";
			}
			$basket_primg="<img src=\"".$product['tinyimage']['src']."\" ".$imageSize." />";

			array_push($pattern,'(\[BASKET_PRIMG\])');
			array_push($replace,$basket_primg);



			// ��ǰ�� ================================================
			$basket_prname = ($sellChk)?"":"<font color=\"#FF0000\">[�Ǹ�����]</font>";
			$basket_prname .= "<a href=\"".$Dir.FrontDir."productdetail.php?productcode=".$product['productcode']."\"><font color=#373737><b>".viewproductname($product['productname'],$product['etctype'],$product['selfcode'],$product['addcode'])."</b></font></a>".$bankonly_html.$setquota_html."";

			array_push($pattern,'(\[BASKET_PRNAME\])');
			array_push($replace,$basket_prname);



			// ���� �� ���� ���� : ������� ������ ǥ�� ===========================
			$chkAuthIcon = array();
			if($product['cateAuth']['coupon'] == 'N') array_push($chkAuthIcon,'<IMG SRC=/images/common/basket/001/basket_spe_icon002x.gif hspace=1 alt=�������� ����Ұ� />');
			if($product['cateAuth']['reserve'] == 'N') array_push($chkAuthIcon,'<IMG SRC=/images/common/basket/001/basket_spe_icon001x.gif alt=������ ���Ұ� />');
			if($product['cateAuth']['gift'] == 'Y' && checkGiftSet()) array_push($chkAuthIcon,'<IMG SRC=/images/common/basket/001/basket_spe_icon004o.gif alt=����ǰ ����Ұ� />');
			if($product['cateAuth']['refund'] == 'N') array_push($chkAuthIcon,'<img src=/images/common/basket/001/basket_spe_icon003x.gif hspace=1 alt=��ȯ/��ǰ �Ұ� />');
			if(_array($chkAuthIcon)){
				$chkAuthIcon = implode(' ',$chkAuthIcon)."<br />";
			}

			array_push($pattern,'(\[CATE_AUTH_ICON\])');
			array_push($replace,$chkAuthIcon);



			// ���� ����Ʈ ==========================================
			$couponList = "";
			$mycoupons = $ablecoupons =array();
			if($_data->coupon_ok=="Y" && $product['cateAuth']['coupon'] == 'Y' && checkGroupUseCoupon()){
				$mycoupons = getMyCouponList($product['productcode']);
				$ablecoupons = ableCouponOnProduct($product['productcode'],$product['vender'],true);
				if(_array($mycoupons)){
					foreach($mycoupons as $abcoupon){
						$couponList .= "<span class=\"couponDownArea\">";
						$couponList .= "<b>��</b>&nbsp;".number_format(intval($abcoupon['sale_money'])).(($abcoupon['sale_type']<'3')?'%':'��').((intval($abcoupon['sale_type'])%2 == 1)?"����":"����");

						if(_array($mycoupon_codes) && in_array($abcoupon['coupon_code'],$mycoupon_codes)){
							$couponList .= "<img src=\"".$Dir."images/common/order/".$_data->design_order."/icon_get.gif\" border=\"0\" style=\"position:relative; top:0.2em;\" alt=\"������\" /><br />";
						}else{
							$couponList .= "<a href=\"javascript:issue_coupon('".$abcoupon['coupon_code']."','".$product['productcode']."');\"><img src=\"".$Dir."images/common/order/".$_data->design_order."/icon_download.gif\" style=\"position:relative; top:0.2em;\" border=\"0\" alt=\"�����ٿ�\" /><br /></a>";
						}
						$couponList .= '</span>';
					}
				}

				if(_array($ablecoupons)){
					foreach($ablecoupons as $abcoupon){
						$couponList .= '<span class="couponDownArea">';
						$couponList .= '<b>��</b>&nbsp;'.number_format(intval($abcoupon['sale_money'])).(($abcoupon['sale_type']<'3')?'%':'��').((intval($abcoupon['sale_type'])%2 == 1)?'����':'����');

						if(_array($mycoupon_codes) && in_array($abcoupon['coupon_code'],$mycoupon_codes)){
							$couponList .= "<img src=\"".$Dir."images/common/order/".$_data->design_order."/icon_get.gif\" border=\"0\" style=\"position:relative; top:0.2em;\" alt=\"������\" /><br />";
						}else{
							$couponList .= "<a href=\"javascript:issue_coupon('".$abcoupon['coupon_code']."','".$product['productcode']."');\"><img src=\"".$Dir."images/common/order/".$_data->design_order."/icon_download.gif\" style=\"position:relative; top:0.2em;\" border=\"0\" alt=\"�����ٿ�\" /><br /></a>";
						}
						$couponList .= '</span>';
					}
				}
			}else{
				$couponList .= "&nbsp;";
			}
			array_push($pattern,'(\[COUPON_LIST\])');
			array_push($replace,$couponList);




			$tempbasket=preg_replace($pattern,$replace,$tempbasket);

		}


		// �����纰 �׷� ���� ��� ���� **************************************************8

		$pattern = array();
		$replace = array();

		// �����纰 ��۷� =========================================
		array_push($pattern,'(\[GROUP_DELIPRICE\])');
		array_push($replace,number_format($vendervalue['deliprice']));


		// �����纰 �հ�ݾ� =========================================
		array_push($pattern,'(\[GROUP_TOTPRICE\])');
		array_push($replace,number_format($vendervalue['sumprice']));


		// �����纰 ?? =========================================
		array_push($pattern,'(\[BASKET_GROUPSTART\])');
		array_push($replace,"");

		// �����纰 ?? =========================================
		array_push($pattern,'(\[BASKET_GROUPEND\])');
		array_push($replace,"");

		$tempgroupbasket=preg_replace($pattern,$replace,$groupbasket);


		// �����纰 ���� ���� ��� ===================================
		$pattern=array("(\[GROUPBASKETVALUE\])");
		$replace=array($tempgroupbasket);




		// ��ۺ� ���� ���� ���� ���
		$venderDeliInfo = "";
		if( !( $vendervalue['conf']['deli_pricetype'] == "Y" AND $vendervalue['deliprice'] == 0 ) ) { // ������ �ƴҰ��
			$venderDeliInfo .= "<br><b>�⺻ ��ۺ� ���� ����</b>". ( $vendervalue['conf']['groupDeli'] > 1 ? "(ȸ�� ��� ��ۺ� ��å ����)" : "" ) ."";
			
			if( $vendervalue['delisumprice'] >= $vendervalue['conf']['deli_mini'] ){
				$venderDeliInfo .= "<font color='#ff4400'><strong>[�����]</strong></font>";
			}

			$venderDeliInfo .= " : �����纰 ���űݾ�(<b>".number_format($vendervalue['delisumprice'])."��</b>, ������ۻ�ǰ ".( $vendervalue['conf']['deli_pricetype'] == "Y" ? "����" : "����" ).")�� <b>".number_format($vendervalue['conf']['deli_mini'])."��</b> �̻��� ���";
		}
		array_push($pattern,'(\[DELI_INFO\])');
		array_push($replace,$venderDeliInfo);







		$tempbasket=preg_replace($pattern,$replace,$tempbasket);

		// �����纰 �׷� ���� ��� �� **************************************************

	}



	// ��ٱ��� ��ü ����
	if($basketItems['sumprice']>0) {

		$originalbasket=$ifbasket;

		$pattern = array();
		$replace = array();

		if($_data->ETCTYPE["VATUSE"]=="Y") {
			$sumpricevat=return_vat($basketItems['sumprice']);
			$basket_productpricevat=($sumpricevat>0?"+ ":"").number_format($sumpricevat);
		} else {
			$sumpricevat=0;
			$basket_productpricevat=0;
		}

		// ��ٱ��� ���� ����Ʈ =======================================
		array_push($pattern,'(\[BASKETVALUE\])');
		array_push($replace,$tempbasket);

		$originalbasket=preg_replace($pattern,$replace,$originalbasket);

	} else {
		// ��ٱ��� �հ� �ݾ��� 0 �ϰ�� ��ٱ��� ����� ������ ����
		$originalbasket=$nobasket;
	}
	/* ��ٱ��� ��ǰ ���� �� */









	$royalvalue="";
	$royal_img="";
	$royal_msg1="";
	$royal_msg2="";
	if(strlen($_ShopInfo->getMemid())>0 && strlen($_ShopInfo->getMemgroup())>0 && substr($_ShopInfo->getMemgroup(),0,1)!="M") {
		$arr_dctype=array("B"=>"����","C"=>"ī��","N"=>"");
		$sql = "SELECT a.name,b.group_code,b.group_name,b.group_payment,b.group_usemoney,b.group_addmoney ";
		$sql.= "FROM tblmember a, tblmembergroup b WHERE a.id='".$_ShopInfo->getMemid()."' AND b.group_code=a.group_code ";
		$sql.= "AND MID(b.group_code,1,1)!='M' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if(file_exists($Dir.DataDir."shopimages/groupimg_".$row->group_code.".gif")) {
				$royal_img="<img src=\"".$Dir.DataDir."shopimages/etc/groupimg_".$row->group_code.".gif\" border=0>";
			} else {
				$royal_img="<img src=\"".$Dir."images/common/group_img.gif\" border=0>\n";
			}
			$royal_msg1="<B>".$row->name."</B>���� <B><FONT COLOR=\"#EE1A02\">[".$row->group_name."]</FONT></B> ȸ���Դϴ�.";
			$royal_msg2 = "<B>".$row->name."</B>���� <FONT COLOR=\"#EE1A02\"><B>".number_format($row->group_usemoney)."��</B></FONT> �̻� ".$arr_dctype[$row->group_payment]."���Ž�,";
			$type=substr($row->group_code,0,2);
			if($type=="RW") $royal_msg2.="�����ݿ� ".number_format($row->group_addmoney)."���� <font color=#EE1A02><B>�߰� ����</B></font>�� �帳�ϴ�.";
			else if($type=="RP") $royal_msg2.="���� �������� ".number_format($row->group_addmoney)."�踦 <font color=#EE1A02><B>����</B></font>�� �帳�ϴ�.";
			else if($type=="SW") $royal_msg2.="���űݾ� ".number_format($row->group_addmoney)."���� <font color=#EE1A02><B>�߰� ����</B></font>�� �帳�ϴ�.";
			else if($type=="SP") $royal_msg2.="���űݾ��� ".number_format($row->group_addmoney)."%�� <font color=#EE1A02><B>�߰� ����</B></font>�� �帳�ϴ�.";

			$pattern=array("(\[ROYAL_IMG\])","(\[ROYAL_MSG1\])","(\[ROYAL_MSG2\])");
			$replace=array($royal_img,$royal_msg1,$royal_msg2);
			$royalvalue=preg_replace($pattern,$replace,$mainroyal);
		}
		mysql_free_result($result);
	}

	if(strlen($code)>0) {
		$basket_shopping=$Dir.FrontDir."productlist.php?code=".substr($code,0,12);
	} else {
		$basket_shopping=$Dir.MainDir."main.php";
	}
	$basket_clear="\"javascript:basket_clear()\"";



	?>