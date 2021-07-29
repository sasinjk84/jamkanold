<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

$mode=$_POST["mode"];
$prcodes=$_POST["prcodes"];
$display=$_POST["display"];
$gubun_vender = $_POST["gubun_vender"];

// 정산 기준 조회 jdy
$shop_more_info = getShopMoreInfo();
$account_rule = $shop_more_info['account_rule'];

$savewideimage = $Dir.DataDir."shopimages/wideimage/";
$vender_more = getVenderMoreInfo($_VenderInfo->getVidx());
$commission_type = $vender_more['commission_type'];

// 정산 기준 조회 jdy

if($mode=="display" && strlen($prcodes)>0 && ($display=="Y" || $display=="N")) {
	if(substr($_venderdata->grant_product,1,1)!="Y") {
		echo "<html></head><body onload=\"alert('상품정보 수정 권한이 없습니다.\\n\\n쇼핑몰에 문의하시기 바랍니다.')\"></body></html>";exit;
	} else if(substr($_venderdata->grant_product,3,1)!="N") {
		echo "<html></head><body onload=\"alert('쇼핑몰 운영자만 상품진열 수정이 가능합니다.\\n\\n쇼핑몰에 문의하시기 바랍니다.')\"></body></html>";exit;
	}

	$prcodes=substr($prcodes,0,-1);
	$prcodelist=ereg_replace(',','\',\'',$prcodes);

	if ($display=="Y") {
		$prcodelist_s = explode( ",", $prcodes);

		$i=0;
		while ($prcodelist_s[$i]){

			//수수료 승인이 나지 않은 상태에서 노출 불가 jdy
			if ($account_rule=="1" || $commission_type=="1") {
				$p_sql = "select first_approval from product_commission where productcode='".$prcodelist_s[$i]."'" ;

				$result=mysql_query($p_sql,get_db_conn());
				$data=mysql_fetch_array($result);

				if ($data[0] != "1") {
					echo "<html></head><body onload=\"alert('아직 수수료가 결정되지 않아 진열할 수 없는상품이 있습니다..')\"></body></html>";exit;
				}
			}
			$i++;
		}
	}

	$sql = "UPDATE tblproduct p left join rent_product r on r.pridx = p.pridx  SET p.display='".$display."' ";
	$sql.= "WHERE p.productcode IN ('".$prcodelist."') and (r.istrust is null || r.istrust ='1')  ";
	
	$sql.= "AND p.vender='".$_VenderInfo->getVidx()."' ";

	if(mysql_query($sql,get_db_conn())) {
		$sql = "SELECT COUNT(*) as prdt_allcnt, COUNT(IF(display='Y',1,NULL)) as prdt_cnt FROM tblproduct ";
		$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		$prdt_allcnt=(int)$row->prdt_allcnt;
		$prdt_cnt=(int)$row->prdt_cnt;
		mysql_free_result($result);

		$sql = "UPDATE tblvenderstorecount SET prdt_allcnt='".$prdt_allcnt."', prdt_cnt='".$prdt_cnt."' ";
		$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
		mysql_query($sql,get_db_conn());

		echo "<html></head><body onload=\"alert('요청하신 작업이 성공하였습니다.-위탁상품은 제외됩니다.');parent.pageForm.submit();\"></body></html>";exit;
	} else {		
		echo "<html></head><body onload=\"alert('요청하신 작업중 오류가 발생하였습니다.')\"></body></html>";exit;
	}

}else if($mode=="copy"){

	if(substr($_venderdata->grant_product,0,1)!="Y") {
		echo "<html></head><body onload=\"alert('상품 등록 권한이 없습니다.\\n\\n쇼핑몰에 문의하시기 바랍니다.');\"></body></html>";exit;
	}

	if (strlen($cproductcode)==18) {
		$copycode = substr($cproductcode,0,12);
		$sql = "SELECT * FROM tblproduct WHERE productcode = '".$cproductcode."'";
		$result = mysql_query($sql,get_db_conn());
		if ($row=mysql_fetch_object($result)) {

			$sql = "SELECT productcode,productnumber FROM tblproduct WHERE productcode LIKE '".$copycode."%' ";
			$sql.= "ORDER BY productcode DESC LIMIT 1 ";
			$result = mysql_query($sql,get_db_conn());
			if ($rows = mysql_fetch_object($result)) {
				$newproductcode = substr($rows->productcode,12)+1;
				$newproductcode = substr("000000".$newproductcode,strlen($newproductcode));

			} else {
				$newproductcode = "000001";

			}
			mysql_free_result($result);
			
			$arr_prdnumber = explode("-",$row->productnumber);
			$vendercode = $arr_prdnumber[0];
			$trust_vendercode = $arr_prdnumber[1];
			

			$sql = "SELECT MAX(RIGHT(productnumber,6)) as maxproductnumber FROM tblproduct ";
			$sql.= "WHERE productnumber LIKE '".$vendercode."-".$trust_vendercode."-"."%' ";
			$result = mysql_query($sql,get_db_conn());
			if ($rows = mysql_fetch_object($result)) {
				if (strlen($rows->maxproductnumber)==6) {
					$productnumber = ((int)$rows->maxproductnumber)+1;
					$productnumber = sprintf("%06d",$productnumber);
				} else if($rows->maxproductnumber==NULL){
					$productnumber = "000001";
				}
				mysql_free_result($result);
			} else {
				$productnumber = "000001";
			}

			//echo $vendercode."/".$trust_vendercode."/".$productnumber;
			//exit;


			$path = $Dir.DataDir."shopimages/product/";
			$widepath = $Dir.DataDir."shopimages/wideimage/";
			if (strlen($row->maximage)>0) {
				$maximage=$copycode.$newproductcode.".".strtolower(substr($row->maximage,strlen($row->maximage)-3,3));
				if (file_exists("$path$row->maximage")==true) {
					if ($mode=="move") rename("$path$row->maximage","$path$maximage");
					else copy("$path$row->maximage","$path$maximage");
				}
			} else $maximage="";
			if (strlen($row->minimage)>0) {
				$minimage=$copycode.$newproductcode."2.".strtolower(substr($row->minimage,strlen($row->minimage)-3,3));
				if (file_exists("$path$row->minimage")==true) {
					if ($mode=="move") rename("$path$row->minimage","$path$minimage");
					else copy("$path$row->minimage","$path$minimage");
				}
			} else $minimage="";
			if (strlen($row->tinyimage)>0) {
				$tinyimage=$copycode.$newproductcode."3.".strtolower(substr($row->tinyimage,strlen($row->tinyimage)-3,3));
				if (file_exists("$path$row->tinyimage")==true) {
					if ($mode=="move") rename("$path$row->tinyimage","$path$tinyimage");
					else copy("$path$row->tinyimage","$path$tinyimage");
				}
			} else $tinyimage="";

			if (strlen($row->wideimage)>0) {
				$wideimage=$copycode.$newproductcode.strtolower(substr($row->wideimage,strlen($row->wideimage)-3,3));
				if (file_exists("$widepath$row->wideimage")==true) {
					if ($mode=="move") rename("$widepath$row->wideimage","$widepath$wideimage");
					else copy("$widepath$row->wideimage","$widepath$wideimage");
				}
			} else $wideimage="";
			if (strlen($row->quantity)==0) $quantity="NULL";
			else $quantity=$row->quantity;

			$productname = mysql_escape_string($row->productname);
			$production = mysql_escape_string($row->production);
			$madein = mysql_escape_string($row->madein);
			$model = mysql_escape_string($row->model);
			$tempkeyword = mysql_escape_string($row->keyword);
			$addcode = mysql_escape_string($row->addcode);
			$userspec = mysql_escape_string($row->userspec);
			$option1 = mysql_escape_string($row->option1);
			$option2 = mysql_escape_string($row->option2);
			$content = mysql_escape_string($row->content);
			$selfcode = mysql_escape_string($row->selfcode);
			$assembleproduct = mysql_escape_string($row->assembleproduct);

			$sql = "INSERT tblproduct SET ";
			$sql.= "productcode		= '".$copycode.$newproductcode."', ";
			$sql.= "productnumber	= '".$vendercode."-".$trust_vendercode."-".$productnumber."', ";
			$sql.= "productname		= '".$productname."', ";
			$sql.= "prmsg			= '".$row->prmsg."', ";
			$sql.= "assembleuse		= '".$row->assembleuse."', ";
			$sql.= "assembleproduct	= '".$row->assembleproduct."', ";
			$sql.= "sellprice		= ".$row->sellprice.", ";
			$sql.= "consumerprice	= ".$row->consumerprice.", ";
			$sql.= "discountRate	= ".$row->discountRate.", ";
			$sql.= "buyprice		= ".$row->buyprice.", ";
			$sql.= "reserve			= '".$row->reserve."', ";
			$sql.= "reservetype		= '".$row->reservetype."', ";
			$sql.= "production		= '".$production."', ";
			$sql.= "madein			= '".$madein."', ";
			$sql.= "model			= '".$model."', ";
			$sql.= "brand			= '".$row->brand."', ";
			$sql.= "opendate		= '".$row->opendate."', ";
			$sql.= "selfcode		= '".$row->selfcode."', ";
			$sql.= "bisinesscode	= '".$row->bisinesscode."', ";
			$sql.= "quantity		= ".$quantity.", ";
			$sql.= "group_check		= '".$row->group_check."', ";
			$sql.= "keyword			= '".$tempkeyword."', ";
			$sql.= "addcode			= '".$addcode."', ";
			$sql.= "userspec		= '".$userspec."', ";
			$sql.= "maximage		= '".$maximage."', ";
			$sql.= "minimage		= '".$minimage."', ";
			$sql.= "tinyimage		= '".$tinyimage."', ";
			$sql.= "wideimage		= '".$wideimage."', ";
			$sql.= "option_price	= '".$row->option_price."', ";
			$sql.= "option_quantity	= '".$row->option_quantity."', ";
			$sql.= "option1			= '".$option1."', ";
			$sql.= "option2			= '".$option2."', ";
			$sql.= "etctype			= '".$row->etctype."', ";
			$sql.= "deli_type		= '".$row->deli_type."', ";
			$sql.= "deli_price		= '".$row->deli_price."', ";
			$sql.= "deli			= '".$row->deli."', ";
			$sql.= "reservation		= '".$row->reservation."', ";
			$sql.= "today_reserve	= '".$row->today_reserve."', ";

			$sql.= "package_num		= '".(int)$row->package_num."', ";
			$sql.= "display			= '".$row->display."', ";
			if ($newtime=="Y")
				$sql.= "date		= '".date("YmdHis")."', ";
			else
				$sql.= "date		= '".$row->date."', ";
			$sql.= "vender			= '".$row->vender."', ";
			$sql.= "rental			= '".$row->rental."', ";
			$sql.= "tax_yn			= '".$row->tax_yn."', ";
			$sql.= "regdate			= now(), ";
			$sql.= "modifydate		= now(), ";
			$sql.= "content			= '".$content."', ";
			$sql.= "etcapply_coupon	= '".$row->etcapply_coupon."', ";
			$sql.= "etcapply_reserve= '".$row->etcapply_reserve."', ";
			$sql.= "etcapply_gift	= '".$row->etcapply_gift."', ";
			$sql.= "etcapply_return	= '".$row->etcapply_return."', ";
			$sql.= "catekeyword		= '".$row->catekeyword."', ";
			$sql.= "booking_confirm = '".$row->booking_confirm."', ";
			$sql.= "reseller_reserve = '".$row->reseller_reserve."' ";

			//$insert = mysql_query($sql,get_db_conn());
			//$insert_pridx = mysql_insert_id();

			if($insert = mysql_query($sql,get_db_conn())) {
				$insert_pridx = mysql_insert_id(get_db_conn());
				
				$rent_sql = "SELECT * FROM rent_product WHERE pridx = '".$row->pridx."'";
				$rent_res = mysql_query($rent_sql,get_db_conn());
				if ($rent_row=mysql_fetch_object($rent_res)) {

					// 렌탈 옵션 처리
					if($row->rental == '2'){
						$ropt_sql = "SELECT * FROM rent_product_option WHERE pridx = '".$row->pridx."'";
						$ropt_res = mysql_query($ropt_sql,get_db_conn());
						$i=0;
						while($ropt_row=mysql_fetch_object($ropt_res)) {
							if($i==0){
								$productoption_grade = $ropt_row->grade;
							}
							$optinsert_sql = "set pridx='".$insert_pridx."',grade='"._escape($ropt_row->grade,false)."',optionName='"._escape($ropt_row->optionName,false)."',custPrice='"._escape($ropt_row->custPrice,false)."',priceDiscP='"._escape($ropt_row->priceDiscP,false)."',nomalPrice='"._escape($ropt_row->nomalPrice,false)."',productTimeover_percent='"._escape($ropt_row->productTimeover_percent,false)."',productTimeover_price='"._escape($ropt_row->productTimeover_price,false)."',productHalfday_percent='"._escape($ropt_row->productHalfday_percent,false)."',productHalfday_price='"._escape($ropt_row->productHalfday_price,false)."',productOverHalfTime_percent='"._escape($ropt_row->productOverHalfTime_percent,false)."',productOverHalfTime_price='"._escape($ropt_row->productOverHalfTime_price,false)."',productOverOneTime_percent='"._escape($ropt_row->productOverOneTime_percent,false)."',productOverOneTime_price='"._escape($ropt_row->productOverOneTime_price,false)."',optionPay='"._escape($ropt_row->optionPay,false)."',deposit='"._escape($ropt_row->deposit,false)."',prepay='"._escape($ropt_row->prepay,false)."',busySeason='"._escape($ropt_row->busySeason,false)."',busyHolidaySeason='"._escape($ropt_row->busyHolidaySeason,false)."',semiBusySeason='"._escape($ropt_row->semiBusySeason,false)."',semiBusyHolidaySeason='"._escape($ropt_row->semiBusyHolidaySeason,false)."',holidaySeason='"._escape($ropt_row->holidaySeason,false)."',productCount='"._escape($ropt_row->productCount,false)."'";

							$optinsert_sql = "insert into rent_product_option ".$optinsert_sql;		
							mysql_query($optinsert_sql,get_db_conn());
							$i++;

						}

						// 대여 상품 저장
						$rentProductValue = array();
						$rentProductValue['pridx'] = $insert_pridx;
						$rentProductValue['istrust'] = $rent_row->istrust;
						$rentProductValue['trustCommi'] = $rent_row->trustCommi;
						$rentProductValue['location'] = $rent_row->location;
						$rentProductValue['goodsType'] = $row->rent;
						$rentProductValue['itemType'] = $rent_row->itemType;			
						$rentProductValue['multiOpt'] = ($rent_row->multiOpt == '1')?'1':'0';
						if($rentProductValue['multiOpt'] == '0') $rentProductValue['tgrade'] = $productoption_grade;

						$rentProductValue['maincommi'] = $rent_row->maincommi;	
						$rentProductValue['trust_vender'] = $rent_row->trust_vender;
						$rentProductValue['trust_approve'] = $rent_row->trust_approve;

						$rentProductResult = rentProductSave( $rentProductValue );						
					}
				}
				
								
				$sql = "UPDATE tblvenderstorecount SET prdt_allcnt=prdt_allcnt+1 ";
				if($display=="Y") {
					$sql.= ",prdt_cnt=prdt_cnt+1 ";
				}
				$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
				mysql_query($sql,get_db_conn());
				$sql = "INSERT tblvendercodedesign SET ";
				$sql.= "vender		= '".$_VenderInfo->getVidx()."', ";
				$sql.= "code		= '".substr($copycode,0,3)."', ";
				$sql.= "tgbn		= '10', ";
				$sql.= "hot_used	= '1', ";
				$sql.= "hot_dispseq	= '118' ";
				@mysql_query($sql,get_db_conn());
				

				$vender_sql = "SELECT * FROM vender_rent WHERE pridx = '".$row->pridx."'";
				$vender_res = mysql_query($vender_sql,get_db_conn());
				if ($vender_row=mysql_fetch_object($vender_res)) {
					$sql2 = "insert vender_rent SET ";
					$sql2.= "vender				= '".$_VenderInfo->getVidx()."', ";
					$sql2.= "pridx				= '".$insert_pridx."', ";
					$sql2.= "rent_stime			= '".$vender_row->rent_stime."', ";
					$sql2.= "rent_etime			= '".$vender_row->rent_etime."', ";
					$sql2.= "pricetype			= '".$vender_row->pricetype."', ";
					$sql2.= "useseason			= '".$vender_row->useseason."', ";
					$sql2.= "base_period		= '".$vender_row->base_period."', ";
					$sql2.= "ownership			= '".$vender_row->ownership."', ";
					$sql2.= "base_time			= '".$vender_row->base_time."', ";
					$sql2.= "base_price			= '".$vender_row->base_price."', ";
					$sql2.= "timeover_price		= '".$vender_row->timeover_price."', ";
					$sql2.= "halfday			= '".$vender_row->halfday."', ";
					$sql2.= "halfday_percent	= '".$vender_row->halfday_percent."', ";
					$sql2.= "oneday_ex			= '".$vender_row->oneday_ex."', ";
					$sql2.= "time_percent		= '".$vender_row->time_percent."', ";
					$sql2.= "checkin_time		= '".$vender_row->checkin_time."', ";
					$sql2.= "checkout_time		= '".$vender_row->checkout_time."', ";
					$sql2.= "cancel_cont		= '".$vender_row->cancel_cont."', ";
					$sql2.= "discount_card		= '".$vender_row->discount_card."' ";
					mysql_query($sql2,get_db_conn());
				}
				
				//장기대여
				$sql_ = "SELECT * FROM vender_longrent WHERE pridx = '".$row->pridx."'";
				$res_ = mysql_query($sql_,get_db_conn());
				while($row_=mysql_fetch_object($res_)) {
					$sql2 = "insert into vender_longrent set vender='".$_VenderInfo->getVidx()."',pridx='".$insert_pridx."',sday='".$row_->sday."',eday='".$row_->eday."',percent='".$row_->percent."'";
					mysql_query($sql2,get_db_conn());
				}

				//환불
				$sql_ = "SELECT * FROM vender_refund WHERE pridx = '".$row->pridx."'";
				$res_ = mysql_query($sql_,get_db_conn());
				while($row_=mysql_fetch_object($res_)) {
					$sql2 = "insert into vender_refund set vender='".$_VenderInfo->getVidx()."',pridx='".$insert_pridx."',day='".$row_->day."',percent='".$row_->percent."'";
					mysql_query($sql2,get_db_conn());
				}
				
				//장기할인
				$sql_ = "SELECT * FROM vender_longdiscount WHERE pridx = '".$row->pridx."'";
				$res_ = mysql_query($sql_,get_db_conn());
				while($row_=mysql_fetch_object($res_)) {
					$sql2 = "insert into vender_longdiscount set vender='".$_VenderInfo->getVidx()."',pridx='".$insert_pridx."',day='".$row_->day."',percent='".$row_->percent."'";
					mysql_query($sql2,get_db_conn());
				}
				//gura

			}

			//jdy 추가 내용 조회 이동
			copyCommission($cproductcode, $copycode.$newproductcode);

			// 복사되는 멀티카테고리 생성
			$multiCateSQL = "
				INSERT
					`tblcategorycode`
				SET
					`categorycode`='".$copycode."' ,
					`productcode` = '".$copycode.$newproductcode."'
			";
			@mysql_query($multiCateSQL,get_db_conn());


			/// 정보 고시 이동 관련 추가 부분
			$sql_gosi = "insert into tblproduct_detail select ".$insert_pridx.",didx,dtitle,dcontent FROM `tblproduct_detail` WHERE pridx ='".$row->pridx."'";
			@mysql_query($sql_gosi,get_db_conn());


			$fromproductcodes.="|".$cproductcode;
			$copyproductcodes.="|".$copycode.$newproductcode;

			if($row->vender>0) {
				$vender_prcodelist[$row->vender]["IN"][]=$copycode.$newproductcode;
			}

			
			if($row->group_check=="Y") {
				$sql = "INSERT INTO tblproductgroupcode SELECT '".$copycode.$newproductcode."', group_code FROM tblproductgroupcode WHERE productcode = '".$cproductcode."' ";
				mysql_query($sql,get_db_conn());
			}
			if($row->assembleuse=="Y") { //코디/조립 상품일 경우
				$sql = "INSERT INTO tblassembleproduct ";
				$sql.= "SELECT '".$copycode.$newproductcode."', assemble_type, assemble_title, assemble_pridx, assemble_list FROM tblassembleproduct ";
				$sql.= "WHERE productcode='".$cproductcode."' ";
				mysql_query($sql,get_db_conn());

				$sql = "SELECT assemble_pridx FROM tblassembleproduct ";
				$sql.= "WHERE productcode = '".$cproductcode."' ";
				$result = mysql_query($sql,get_db_conn());
				if($row = @mysql_fetch_object($result)) {
					if(strlen(str_replace("","",$row->assemble_pridx))>0) {
						$sql = "UPDATE tblproduct SET ";
						$sql.= "assembleproduct = CONCAT(assembleproduct,',".$copycode.$newproductcode."') ";
						$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
						$sql.= "AND assembleuse != 'Y' ";
						mysql_query($sql,get_db_conn());
					}
				}
				mysql_free_result($result);
			} else {
				$sql = "UPDATE tblproduct SET assembleproduct = '' ";
				$sql.= "WHERE productcode='".$copycode.$newproductcode."'";
				mysql_query($sql,get_db_conn());
			}

			$log_content = "## 상품복사입력 ## - 상품코드 ".$cproductcode." => ".$copycode.$newproductcode." - 상품명 : ".$productname;
			ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
		}

		echo "<html></head><body onload=\"alert('요청하신 작업이 성공하였습니다.');parent.pageForm.submit();\"></body></html>";exit;

	}


}else if($mode=="delete" && strlen($prcodes)>0) {

	if(substr($_venderdata->grant_product,2,1)!="Y") {
		echo "<html></head><body onload=\"alert('상품 삭제권한이 없습니다.\\n\\n쇼핑몰에 문의하시기 바랍니다.')\"></body></html>";exit;
	}

	unset($_deldata);
	$prcodes=substr($prcodes,0,-1);
	$prcodelist=ereg_replace(',','\',\'',$prcodes);

	$prcodes="";
	$sql = "SELECT productcode, productname, maximage, minimage, tinyimage, display,pridx,assembleuse,assembleproduct,content FROM tblproduct ";
	$sql.= "WHERE productcode IN ('".$prcodelist."') ";
	$sql.= "AND vender='".$_VenderInfo->getVidx()."' ";
	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		$_deldata[]=$row;
		$prcodes.=$row->productcode.",";
	}
	mysql_free_result($result);

	if(count($_deldata)>0) {
		$prcodes=substr($prcodes,0,-1);
		$prcodelist=ereg_replace(',','\',\'',$prcodes);

		$sql = "DELETE FROM tblproduct WHERE productcode IN ('".$prcodelist."') ";
		$sql.= "AND vender='".$_VenderInfo->getVidx()."' ";
		if(mysql_query($sql,get_db_conn())) {
			//상품 삭제로 인한 관련 데이터 삭제처리

			#태그관련 지우기
			$sql = "DELETE FROM tbltagproduct WHERE productcode IN ('".$prcodelist."') ";
			mysql_query($sql,get_db_conn());

			#리뷰 지우기
			$sql = "DELETE FROM tblproductreview WHERE productcode IN ('".$prcodelist."') ";
			mysql_query($sql,get_db_conn());

			#위시리스트 지우기
			$sql = "DELETE FROM tblwishlist WHERE productcode IN ('".$prcodelist."') ";
			mysql_query($sql,get_db_conn());

			#관련상품 지우기
			$sql = "DELETE FROM tblcollection WHERE productcode IN ('".$prcodelist."') ";
			mysql_query($sql,get_db_conn());

			#테마상품 지우기
			$sql = "DELETE FROM tblproducttheme WHERE productcode IN ('".$prcodelist."') ";
			mysql_query($sql,get_db_conn());

			#상품접근권한 지우기
			$sql = "DELETE FROM tblproductgroupcode WHERE productcode IN ('".$prcodelist."')";
			mysql_query($sql,get_db_conn());

			/* 추가 수수료 테이블 내용 삭제 jdy */
			$sql = "DELETE FROM product_commission WHERE productcode IN ('".$prcodelist."') ";
			mysql_query($sql,get_db_conn());
			/* 추가 수수료 테이블 내용 삭제 jdy */
			
			// 멀티 카테고리 삭제
			$sql = "DELETE tblcategorycode set WHERE productcode IN ('".$prcodelist."')" ;
			@mysql_query($sql,get_db_conn());

			//검색키워드 삭제
			$sql = "DELETE FROM tblkeyword WHERE productcode IN ('".$prcodelist."') ";
			mysql_query($sql,get_db_conn());


			//미니샵 테마코드에 등록된 상품 삭제
			$sql = "DELETE FROM tblvenderthemeproduct WHERE vender='".$_VenderInfo->getVidx()."' ";
			$sql.= "AND productcode IN ('".$prcodelist."') ";
			mysql_query($sql,get_db_conn());

			//미니샵 상품수 업데이트 (진열된 상품만)
			$sql = "SELECT COUNT(*) as prdt_allcnt, COUNT(IF(display='Y',1,NULL)) as prdt_cnt FROM tblproduct ";
			$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			$prdt_allcnt=(int)$row->prdt_allcnt;
			$prdt_cnt=(int)$row->prdt_cnt;
			mysql_free_result($result);

			$sql = "UPDATE tblvenderstorecount SET prdt_allcnt='".$prdt_allcnt."', prdt_cnt='".$prdt_cnt."' ";
			$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
			mysql_query($sql,get_db_conn());

			$tmpcodeA=array();
			$arrprcode=explode(",",$prcodes);
			for($j=0;$j<count($arrprcode);$j++) {
				$tmpcodeA[substr($arrprcode[$j],0,3)]=true;
			}

			if(count($tmpcodeA)>0) {
				$sql = "SELECT SUBSTRING(productcode,1,3) as codeA FROM tblproduct ";
				$sql.= "WHERE ( ";
				$arr_codeA=$tmpcodeA;
				$i=0;
				while(list($key,$val)=each($arr_codeA)) {
					if(strlen($key)==3) {
						if($i>0) $sql.= "OR ";
						$sql.= "productcode LIKE '".$key."%' ";
						$i++;
					}
				}
				$sql.= ") ";
				$sql.= "AND vender='".$_VenderInfo->getVidx()."' ";
				$sql.= "GROUP BY codeA ";
				$result=mysql_query($sql,get_db_conn());
				while($row=mysql_fetch_object($result)) {
					unset($tmpcodeA[$row->codeA]);
				}
				mysql_free_result($result);

				if(count($tmpcodeA)>0) {
					$str_codeA="";
					while(list($key,$val)=each($tmpcodeA)) {
						$str_codeA.=$key.",";

						$imagename=$Dir.DataDir."shopimages/vender/".$_VenderInfo->getVidx()."_CODE10_".$key.".gif";
						@unlink($imagename);
					}
					$str_codeA=substr($str_codeA,0,-1);
					$str_codeA=ereg_replace(',','\',\'',$str_codeA);
					$sql = "DELETE FROM tblvendercodedesign WHERE vender='".$_VenderInfo->getVidx()."' ";
					$sql.= "AND code IN ('".$str_codeA."') AND tgbn='10' ";
					mysql_query($sql,get_db_conn());
				}
			}

			#상품이미지 삭제
			$imagepath=$Dir.DataDir."shopimages/product/";
			$update_ymd = date("YmdH");
			$update_ymd2 = date("is");
			for($i=0;$i<count($_deldata);$i++) {
				/** 에디터 관련 파일 처리 추가 부분 */
				if(preg_match_all('/\/data\/editor\/([a-zA-Z0-9\.]+)/',$_deldata[$i]->content,$edimg)){
					foreach($edimg[1] as $timg){
						@unlink($_SERVER['DOCUMENT_ROOT'].'/data/editor/'.$timg);
					}
				}
				/** #에디터 관련 파일 처리 추가 부분 */

				if(strlen($_deldata[$i]->assembleproduct)>0) {
					$sql = "SELECT productcode, assemble_pridx FROM tblassembleproduct ";
					$sql.= "WHERE productcode IN ('".str_replace(",","','",$_deldata[$i]->assembleproduct)."') ";
					$result = mysql_query($sql,get_db_conn());
					while($row = @mysql_fetch_object($result)) {
						$sql = "SELECT SUM(sellprice) as sumprice FROM tblproduct ";
						$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
						$sql.= "AND display ='Y' ";
						$sql.= "AND assembleuse!='Y' ";
						$result2 = mysql_query($sql,get_db_conn());
						if($row2 = @mysql_fetch_object($result2)) {
							$sql = "UPDATE tblproduct SET sellprice='".$row2->sumprice."' ";
							$sql.= "WHERE productcode = '".$row->productcode."' ";
							$sql.= "AND assembleuse='Y' ";
							mysql_query($sql,get_db_conn());
						}
						mysql_free_result($result2);
					}
				}

				$sql = "UPDATE tblassembleproduct SET ";
				$sql.= "assemble_pridx=REPLACE(assemble_pridx,'".$_deldata[$i]->pridx."',''), ";
				$sql.= "assemble_list=REPLACE(assemble_list,',".$_deldata[$i]->pridx."','') ";
				mysql_query($sql,get_db_conn());

				unset($vimagear);
				$vimagear=array(&$vimage,&$vimage2,&$vimage3);
				$vimage=$_deldata[$i]->maximage;
				$vimage2=$_deldata[$i]->minimage;
				$vimage3=$_deldata[$i]->tinyimage;

				for($y=0;$y<3;$y++){
					if(strlen($vimagear[$y])>0 && file_exists($imagepath.$vimagear[$y]))
						unlink($imagepath.$vimagear[$y]);
				}
				@delProductMultiImg("prdelete","",$_deldata[$i]->productcode);
				deleteNewMultiCont($_deldata[$i]->productcode);
				$wideimage="";
				$wideimage=$savewideimage.$_deldata[$i]->productcode."*";

				@proc_matchfiledel($wideimage);
				$log_content = "## 상품삭제 ## - 상품코드 ".$_deldata[$i]->productcode." - 상품명 : ".urldecode($_deldata[$i]->productname)." ".$_deldata[$i]->display."";
				$_VenderInfo->ShopVenderLog($_VenderInfo->getVidx(),$connect_ip,$log_content,$update_date);
				$update_ymd2++;
			}

			echo "<html></head><body onload=\"alert('요청하신 작업이 성공하였습니다.');parent.pageForm.submit();\"></body></html>";exit;
		} else {
			echo "<html></head><body onload=\"alert('요청하신 작업중 오류가 발생하였습니다.')\"></body></html>";exit;
		}
	} else {
		echo "<html></head><body onload=\"alert('삭제할 상품이 존재하지 않습니다.');parent.pageForm.submit();\"></body></html>";exit;
	}
}

$code=$_POST["code"];
$disptype=$_POST["disptype"];
$rentaltype=$_POST["rentaltype"];
$soldout=$_POST["soldout"];
$s_check=$_POST["s_check"];
if(strlen($s_check)==0) $s_check="name";
$search=ltrim($_POST["search"]);
$sort=$_POST["sort"];
if($sort!="order by productname asc" && $sort!="order by productname desc" && $sort!="order by productcode asc" && $sort!="order by productcode desc" && $sort!="order by sellprice asc" && $sort!="order by sellprice desc" && $sort!="order by regdate asc" && $sort!="order by regdate desc") {
	$sort="order by regdate desc";
}


$qry = "WHERE 1=1 ";
if(strlen($code)>=3) {
	$qry.= "AND p.productcode LIKE '".$code."%' ";
}
$qry.= "AND (p.vender='".$_VenderInfo->getVidx()."' ";

//보낸위탁,받은위탁상품 가져오기
if($gubun=="me"){
	$qry.= "AND r.istrust='1') ";
}else if($gubun=="take"){//받은위탁
	$qry.= "OR r.trust_vender='".$_VenderInfo->getVidx()."')  and r.istrust ='0' ";	
}else if($gubun=="give"){//보낸위탁
	$qry.= "AND r.trust_vender<>'".$_VenderInfo->getVidx()."')  and r.istrust ='0' ";
}else{
	$qry.= "OR r.trust_vender='".$_VenderInfo->getVidx()."') ";
}


if($gubun_vender){
	$trustArr = explode("::",$gubun_vender);

	if($trustArr[1]=="take"){//받은위탁인 경우 
		$qry.= "AND p.vender='".$trustArr[0]."'";
	}else if($trustArr[1]=="give"){
		$qry.= "AND r.trust_vender='".$trustArr[0]."'";
	}else{
		$qry.= "AND (r.istrust='1' OR r.istrust is NULL)";
	}
}

//진열,대기
for($i=0;$i<strlen($disptype);$i++){
	if(strlen($disptype[$i])>0){
		$disptypeArr .= "'".$disptype[$i]."',";
	}
}
if($disptypeArr){
	$disptypeArr = substr($disptypeArr,0,strlen($disptypeArr) - 1);
	$qry.= " AND p.display in (".$disptypeArr.")";
}

//대여,판매
for($i=0;$i<strlen($rentaltype);$i++){
	if(strlen($rentaltype[$i])>0){
		$rentaltypeArr .= "'".$rentaltype[$i]."',";
	}
}
if($rentaltypeArr){
	$rentaltypeArr = substr($rentaltypeArr,0,strlen($rentaltypeArr) - 1);
	$qry.= " AND p.rental in (".$rentaltypeArr.")";
}

if($soldout=="Y") $qry.= "AND p.quantity<=0 ";


/*
if($disptype=="Y") $qry.= "AND p.display='Y' ";
else if($disptype=="N") $qry.= "AND p.display='N' ";

if($rentaltype=="1") $qry.= "AND p.rental='1' ";
else if($rentaltype=="2") $qry.= "AND p.rental='2' ";
*/

if(strlen($search)>0) {
	if($s_check=="name") $qry.= "AND p.productname LIKE '%".$search."%' ";
	else if($s_check=="code") $qry.= "AND p.productcode='".$search."' ";
}


$setup[page_num] = 10;
$setup[list_num] = $_POST["list_num"]? $_POST["list_num"] : 20;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];
if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

$t_count=0;
$sql = "SELECT COUNT(*) as t_count FROM tblproduct as p left join rent_product r on p.pridx=r.pridx ".$qry." ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$t_count = $row->t_count;
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

//내 상품수
$allCnt=0;
$sql = "SELECT COUNT(*) as cnt FROM tblproduct WHERE vender='".$_VenderInfo->getVidx()."'";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$allCnt = $row->cnt;

//받은 위탁상품수
$sql = "SELECT COUNT(*) as cnt FROM tblproduct p left join rent_product r on r.pridx = p.pridx ";
$sql.= "WHERE r.trust_vender='".$_VenderInfo->getVidx()."' and r.istrust ='0' ";
$sql.= "AND p.vender<>'".$_VenderInfo->getVidx()."' ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$takeCnt = $row->cnt;

//보낸 위탁상품수
$sql = "SELECT COUNT(*) as cnt FROM tblproduct p left join rent_product r on r.pridx = p.pridx ";
$sql.= "WHERE r.trust_vender<>'".$_VenderInfo->getVidx()."' and r.istrust ='0' ";
$sql.= "AND p.vender='".$_VenderInfo->getVidx()."' ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$giveCnt = $row->cnt;

if(strlen($disptype)==0){
	$disptypeArr = "'Y','N'";
}

if(strlen($rentaltype)==0){
	$rentaltypeArr = "'2'";
}

if($gubun=="me"){
	$t_count = $allCnt;
}else if($gubun=="take"){//받은위탁
	$t_count = $takeCnt;
}else if($gubun=="give"){//보낸위탁
	$t_count = $giveCnt;
}else{
	$t_count = $t_count;
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>

<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>

<script language="JavaScript">
$(document).ready(function() {

	$('.search_all').click(function() {
		if( $(this).is(":checked") ) {
			$('.search_status').prop("checked",true);
		}
		else {
			$('.search_status').prop("checked",false);
		}
	})

	$('.search_status').on("change",function() {
		if($('.search_status:not(:checked)').length==0){
             $('.search_all').prop("checked",true);
		}
		else {
             $('.search_all').prop("checked",false);
		}
	})

	$('#default').click(function(){
		$('input[name=search_all]').prop("checked",false);
		$('input[name=disptype\\[\\]]').prop("checked",true);
		$('input[name=soldout]').prop("checked",false);
		$('#rentaltype_2').prop("checked",true);
		$('#rentaltype_1').prop("checked",false);

		$('select[name=code1]').val("").attr("selected","selected");
		ACodeSendIt("");
	})


})

function ACodeSendIt(code) {
	document.sForm.code.value=code;
	murl = "product_myprd.ctgr.php?code="+code+"&depth=2";
	surl = "product_myprd.ctgr.php?depth=3";
	durl = "product_myprd.ctgr.php?depth=4";
	BCodeCtgr.location.href = murl;
	CCodeCtgr.location.href = surl;
	DCodeCtgr.location.href = durl;
}

//엑셀파일 다운로드
function excelDown() {
	document.etcform.prcodes.value="";
	for(i=1;i<document.form2.chkprcode.length;i++) {
		if(document.form2.chkprcode[i].checked==true) {
			document.etcform.prcodes.value+=document.form2.chkprcode[i].value+",";
		}
	}
	if(document.etcform.prcodes.value.length==0) {
		alert("선택하신 상품이 없습니다.");
		return;
	}
	if(confirm("선택하신 상품의 정보를 엑셀다운로드 하시겠습니까?")) {
		document.etcform.mode.value="excel";
		document.etcform.display.value="";
		document.etcform.action="product_myprd.exceldown.php";
		document.etcform.target="processFrame";
		document.etcform.submit();
	}
}

//엑셀파일 전체다운로드
function excelAllDown() {
	if(confirm("전체 상품의 정보를 엑셀다운로드 하시겠습니까?")) {
		document.etcform.mode.value="excelall";
		document.etcform.display.value="";
		document.etcform.action="product_myprd.exceldown.php";
		document.etcform.target="processFrame";
		document.etcform.submit();
	}
}

<?if(substr($_venderdata->grant_product,1,1)=="Y" && substr($_venderdata->grant_product,3,1)=="N") {?>
function setPrdDisplaytype(prcode,display) {
	if(display!="Y" && display!="N") {
		alert("ON/OFF 설정이 잘못되었습니다.");
		return;
	}
	document.etcform.prcodes.value="";
	if(prcode.length==18) {
		document.etcform.prcodes.value+=prcode+",";
	} else {
		for(i=1;i<document.form2.chkprcode.length;i++) {
			if(document.form2.chkprcode[i].checked==true) {
				document.etcform.prcodes.value+=document.form2.chkprcode[i].value+",";
			}
		}
	}
	if(document.etcform.prcodes.value.length==0) {
		alert("선택하신 상품이 없습니다.");
		return;
	}
	if(confirm("선택하신 상품의 상품진열을 ["+(display=="Y"?"ON":"OFF")+"] 하시겠습니까?")) {
		document.etcform.mode.value="display";
		document.etcform.display.value=display;
		document.etcform.action="<?=$_SERVER[PHP_SELF]?>";
		document.etcform.target="processFrame";
		document.etcform.submit();
	}
}
<?}?>

<?if(substr($_venderdata->grant_product,2,1)=="Y") {?>
function DeletePrd(prcode) {
	document.etcform.prcodes.value="";
	if(prcode.length==18) {
		document.etcform.prcodes.value+=prcode+",";
	} else {
		for(i=1;i<document.form2.chkprcode.length;i++) {
			if(document.form2.chkprcode[i].checked==true) {
				document.etcform.prcodes.value+=document.form2.chkprcode[i].value+",";
			}
		}
	}
	if(document.etcform.prcodes.value.length==0) {
		alert("선택하신 상품이 없습니다.");
		return;
	}
	if(confirm("선택하신 상품을 삭제할 경우 복구가 불가능합니다.\n\선택하신 상품을 완전히 삭제하시겠습니까?")) {
		document.etcform.mode.value="delete";
		document.etcform.display.value="";
		document.etcform.action="<?=$_SERVER[PHP_SELF]?>";
		document.etcform.target="processFrame";
		document.etcform.submit();
	}
}
<?}?>

function trustOpen(mode) {
	if(mode=="trust"){
		document.sForm.prcodes.value="";
		for(i=1;i<document.form2.chkprcode.length;i++) {
			if(document.form2.chkprcode[i].checked==true) {
				document.sForm.prcodes.value+=document.form2.chkprcode[i].value+",";
			}
		}
		if(document.sForm.prcodes.value.length==0) {
			alert("선택하신 상품이 없습니다.");
			return;
		}
	}

	window.open("","trustOpen","width=300,height=330,scrollbars=no");
	document.sForm.mode.value = mode;
	document.sForm.action="product_trust_insert.php";
	document.sForm.target="trustOpen";
	document.sForm.submit();
	document.sForm.target="";
}


function SearchPrd() {
	document.sForm.submit();
}

function SearchPrd2(val) {
	document.sForm.list_num.value=val;
	document.sForm.submit();
}

function SearchPrd3(val) {
	document.sForm.gubun_vender.value=val;
	document.sForm.submit();
}

function GoPage(block,gotopage) {
	document.pageForm.block.value=block;
	document.pageForm.gotopage.value=gotopage;
	document.pageForm.submit();
}

function OrderSort(sort) {
	document.pageForm.block.value="";
	document.pageForm.gotopage.value="";
	document.pageForm.sort.value=sort;
	document.pageForm.submit();
}

function GoPrdinfo(prcode,target) {
	document.form3.target="";
	document.form3.prcode.value=prcode;
	if(target.length>0) {
		document.form3.target=target;
	}
	document.form3.submit();
}

function CheckAll(){
   chkval=document.form2.allcheck.checked;
   cnt=document.form2.tot.value;
   for(i=1;i<=cnt;i++){
      document.form2.chkprcode[i].checked=chkval;
   }
}

function Copy(pcode) {
	if (confirm("이 상품을 복사해서 상품을 등록하시겠습니까?")) {
		document.etcform.cproductcode.value = pcode;
		document.etcform.mode.value="copy";
		document.etcform.target="processFrame";
		document.etcform.submit();
	}
}

function viewHistory(productcode) {
	window.open("vender_prdtcom_histoy_pop.php?productcode="+productcode,"history","height=400,width=550,toolbar=no,menubar=no,scrollbars=yes,status=no");
}

function confirmAdminProduct(){
	alert("상품등록, 수정 권한이 제한된 상태입니다.\n관리자에게 문의바랍니다.");
	return;
}

</script>
<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed"  height="100%" >
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=20></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">

	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td><img src="images/product_myprd_title.gif"></td>
				</tr>
				<tr>
					<td height=5 background="images/minishop_titlebg.gif">
				</tr>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td colspan=3 >


						<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
							<tr>
								<td  bgcolor="#F5F5F9" style="padding:20px">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">카테고리 분류/상품명 검색으로 등록된 상품을 관리합니다.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">상품명 클릭시 해당 상품 열람/수정이 가능합니다.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">상품 체크 후 ON/OFF 상태를 일괄 변경할 수 있습니다.</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>



					</td>
				</tr>
				</table>
				</td>
			</tr>
			
			<tr><td height=10></td></tr>
			<tr>
				<td>
					<table cellpadding="10" cellspacing="7" width="100%" bgcolor="#EFEFF2">
						<tr>
							<td bgcolor="#ffffff">
								<ul class="orderSearchTop">
									<li><a href="?gubun=">전체 <font class="<?=$gubun==""? "skyblue":"orderNum";?>"><?=$allCnt+$takeCnt?></font>건</a></li>
									<li>① <a href="?gubun=me">내 상품목록 <font class="<?=$gubun=="me"? "skyblue":"orderNum";?>"><?=$allCnt-$giveCnt?></font>건</a></li>
									<li>② <a href="?gubun=take">받은위탁 상품목록 <font class="<?=$gubun=="take"? "skyblue":"orderNum";?>"><?=$takeCnt?></font>건</a></li>
									<li>③ <a href="?gubun=give">보낸위탁 상품목록 <font class="<?=$gubun=="give"? "skyblue":"orderNum";?>"><?=$giveCnt?></font>건</a></li>
								</ul>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<!-- 처리할 본문 위치 시작 -->
			<tr><td height=10></td></tr>
			<tr>
				<td>
					<form name="sForm" method="post">
					<input type=hidden name=prcodes>
					<input type=hidden name=mode>
					<input type="hidden" name="code" value="<?=$code?>">
					<input type="hidden" name="list_num" value="<?=$setup[list_num]?>">
					<input type="hidden" name="gubun" value="<?=$gubun?>">
					<input type="hidden" name="gubun_vender" value="<?=$gubun_vender?>">
					<div class="searchTab">
						<div class="searchTab1">
							<span class="searchTab1_1">
							상품상태
							</span>
							<span class="searchTab1_2">
								<input type="checkbox" class="search_all" name="search_all" value="all" <?=($search_all=="all")?"checked":"";?> >전체
								<input type="checkbox" class="search_status" name="disptype[]" value="Y" <?=strpos($disptypeArr,"Y")?"checked":"";?>>진열
								<input type="checkbox" class="search_status" name="disptype[]" value="N" <?=strpos($disptypeArr,"N")?"checked":"";?>>대기
								<input type="checkbox" class="search_status" name="soldout" value="Y" <?if($soldout=="Y")echo"checked";?>>품절
								<input type="checkbox" class="search_status" id="rentaltype_2" name="rentaltype[]" value="2" <?=strpos($rentaltypeArr,"2")?"checked":"";?>>대여상품
								<input type="checkbox" class="search_status" id="rentaltype_1" name="rentaltype[]" value="1" <?=strpos($rentaltypeArr,"1")?"checked":"";?>>판매상품
								<button type="button" class="btn_day" id="default">초기화</button>
							</span>
						</div>

						<div class="searchTab2">
							<div class="searchTab6">
								<div class="searchTab6_1">
								카테고리
								</div>
								<div class="searchTab6_2">
									<ul>
										<li>
											<select name="code1" style=width:155 onchange="ACodeSendIt(this.options[this.selectedIndex].value)">
												<option value="">------ 대 분 류 ------</option>
												<?
												$sql = "SELECT SUBSTRING(productcode,1,3) as prcode FROM tblproduct ";
												$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
												$sql.= "GROUP BY prcode ";
												$result=mysql_query($sql,get_db_conn());
												$codes="";
												while($row=mysql_fetch_object($result)) {
													$codes.=$row->prcode.",";
												}
												mysql_free_result($result);
												if(strlen($codes)>0) {
													$codes=substr($codes,0,-1);
													$prcodelist=ereg_replace(',','\',\'',$codes);
												}
												if(strlen($prcodelist)>0) {
													$sql = "SELECT codeA,codeB,codeC,codeD,code_name FROM tblproductcode ";
													$sql.= "WHERE codeA IN ('".$prcodelist."') AND codeB='000' AND codeC='000' ";
													$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
													$result=mysql_query($sql,get_db_conn());
													while($row=mysql_fetch_object($result)) {
														echo "<option value=\"".$row->codeA."\"";
														if($row->codeA==substr($code,0,3)) echo " selected";
														echo ">".$row->code_name."</option>\n";
													}
													mysql_free_result($result);
												}
												?>
											</select>
										</li>
										<li>
											<iframe name="BCodeCtgr" src="product_myprd.ctgr.php?code=<?=substr($code,0,3)?>&select_code=<?=$code?>&depth=2" width="155" height="21" scrolling=no frameborder=no></iframe>
										</li>
										<li>
											<iframe name="CCodeCtgr" src="product_myprd.ctgr.php?code=<?=substr($code,0,6)?>&select_code=<?=$code?>&depth=3" width="155" height="21" scrolling=no frameborder=no></iframe>
										</li>
										<li>
											<iframe name="DCodeCtgr" src="product_myprd.ctgr.php?code=<?=substr($code,0,9)?>&select_code=<?=$code?>&depth=4" width="155" height="21" scrolling=no frameborder=no></iframe>
										</li>
									</ul>
								</div>
							</div>
						

							<div class="searchTab7">
								
								<div class="searchTab7_1">
									<input type="radio" name="s_check" value="name" <?if($s_check=="name")echo"checked";?>>상품명
									<input type="radio" name="s_check" value="code" <?if($s_check=="code")echo"checked";?>>상품코드
								</div>
								<div class="searchTab7_2">
									<input type="text" name="search" id="search" value="<?=$search?>" placeholder="입력하지 않고 검색하면 전체검색됩니다.">
								</div>
							</div>

						</div>

						<div class="searchTab8">
							<button type="submit" class="searchBtn" onclick="javascript:SearchPrd()">검색</button>
						</div>
						
						<div class="clear"></div>
					</div>
					</form>






<!--
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td valign=top bgcolor=D4D4D4 style=padding:1>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td valign=top bgcolor=F0F0F0 style=padding:10>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<form name="sForm" method="post">
						<input type="hidden" name="code" value="<?=$code?>">
						<tr>
							<td>
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<tr>
								<td>
								<select name="code1" style=width:155 onchange="ACodeSendIt(this.options[this.selectedIndex].value)">
								<option value="">------ 대 분 류 ------</option>
<?
								$sql = "SELECT SUBSTRING(productcode,1,3) as prcode FROM tblproduct ";
								$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
								$sql.= "GROUP BY prcode ";
								$result=mysql_query($sql,get_db_conn());
								$codes="";
								while($row=mysql_fetch_object($result)) {
									$codes.=$row->prcode.",";
								}
								mysql_free_result($result);
								if(strlen($codes)>0) {
									$codes=substr($codes,0,-1);
									$prcodelist=ereg_replace(',','\',\'',$codes);
								}
								if(strlen($prcodelist)>0) {
									$sql = "SELECT codeA,codeB,codeC,codeD,code_name FROM tblproductcode ";
									$sql.= "WHERE codeA IN ('".$prcodelist."') AND codeB='000' AND codeC='000' ";
									$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
									$result=mysql_query($sql,get_db_conn());
									while($row=mysql_fetch_object($result)) {
										echo "<option value=\"".$row->codeA."\"";
										if($row->codeA==substr($code,0,3)) echo " selected";
										echo ">".$row->code_name."</option>\n";
									}
									mysql_free_result($result);
								}
?>
								</select>
								</td>
								<td></td>
								<td>
								<iframe name="BCodeCtgr" src="product_myprd.ctgr.php?code=<?=substr($code,0,3)?>&select_code=<?=$code?>&depth=2" width="155" height="21" scrolling=no frameborder=no></iframe>
								</td>
								<td></td>
								<td><iframe name="CCodeCtgr" src="product_myprd.ctgr.php?code=<?=substr($code,0,6)?>&select_code=<?=$code?>&depth=3" width="155" height="21" scrolling=no frameborder=no></iframe></td>
								<td></td>
								<td><iframe name="DCodeCtgr" src="product_myprd.ctgr.php?code=<?=substr($code,0,9)?>&select_code=<?=$code?>&depth=4" width="155" height="21" scrolling=no frameborder=no></iframe></td>
							</tr>
							</table>
							</td>
						</tr>
						<tr><td height=5></td></tr>
						<tr>
							<td>
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<tr>
								<td colspan=4>
								<select name=disptype style="width:30%">
								<option value="">진열/대기상품 전체</option>
								<option value="Y" <?if($disptype=="Y")echo"selected";?>>진열상품만 검색</option>
								<option value="N" <?if($disptype=="N")echo"selected";?>>대기상품만 검색</option>
								</select>

								
								
								<select name=rentaltype style="width:30%">
								<option value="">대여/판매상품 전체</option>
								<option value="2" <?if($rentaltype=="2")echo"selected";?>>대여상품만 검색</option>
								<option value="1" <?if($rentaltype=="1")echo"selected";?>>판매상품만 검색</option>
								</select>
							
								<select name="s_check" style="width:30%">
								<option value="name" <?if($s_check=="name")echo"selected";?>>상품명으로 검색</option>
								<option value="code" <?if($s_check=="code")echo"selected";?>>상품코드로 검색</option>
								</select>
								</td>


								<td><input type=text name=search value="<?=$search?>" style="width:100%"></td>

								<td></td>

								<td><A HREF="javascript:SearchPrd()"><img src=images/btn_inquery03.gif border=0></A></td>
							</tr>
							</table>
							</td>
						</tr>

						</form>

						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
-->
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr>
					<td>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
							<tr>
								<td height="30">검색결과(총 <font class="skyblue"><?=$t_count?></font> 건)</td>
								<td align="right">
									<select name="list_num" style="width:150px" onchange="javascript:SearchPrd2(this.options[this.selectedIndex].value);">
										<option value="20" <?=$setup[list_num]==20? "selected":"";?>>20개씩 보기</option>
										<option value="30" <?=$setup[list_num]==30? "selected":"";?>>30개씩 보기</option>
										<option value="50" <?=$setup[list_num]==50? "selected":"";?>>50개씩 보기</option>
										<option value="100" <?=$setup[list_num]==100? "selected":"";?>>100개씩 보기</option>
										<option value="200" <?=$setup[list_num]==200? "selected":"";?>>200개씩 보기</option>
									</select>
								</td>
							</tr>
						</table>
					</td>
				<tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<col width=150></col>
					<col width=></col>
					<tr>
						<td>
							<div class="tableTop">
								<button type="text" onclick="javascript:excelAllDown()">전체다운로드</button> 
								<button type="text" onclick="javascript:excelDown()">선택다운로드</button>
							</div>
						</td>
						<td align=right>
						<?if(substr($_venderdata->grant_product,1,1)=="Y" && substr($_venderdata->grant_product,3,1)=="N") {?>
						<img src=images/btn_prddispon.gif border=0 style="cursor:hand" onclick="setPrdDisplaytype('','Y')">
						<img src=images/btn_prddispoff.gif border=0 style="cursor:hand" onclick="setPrdDisplaytype('','N')">
						<?}?>
						<?if(substr($_venderdata->grant_product,2,1)=="Y") {?>
						<img src=images/btn_prddel.gif border=0 style="cursor:hand" onclick="DeletePrd('')">
						<?}?>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height=3></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				<tr>
					<td bgcolor=E7E7E7>
					<table width=100% border=0 cellspacing=1 cellpadding=0 style="table-layout:fixed">
					<col width=30></col>
					<col width=40></col>
					<col width=120></col>
					<col width=120></col>
					<col width=></col>
					<? /** 수수료 관련 jdy **/ ?>
					<? if ($account_rule==1 || $commission_type==1) { ?>
					<col width=120></col>
					<? } ?>
					<? /** 수수료 관련 jdy **/ ?>
					<col width=60></col>
					<col width=70></col>
					<col width=60></col>
					<col width=60></col>
					<col width=60></col>

					<form name=form2 method=post>
					<input type=hidden name=chkprcode>

					<tr height=35 align=center bgcolor=F5F5F5>
						<td align=center><input type=checkbox name=allcheck onclick="CheckAll()"></td>
						<td align=center><B>번호</B></td>
						<td align=center>
							<select name="gubun_vender" onchange="javascript:SearchPrd3(this.options[this.selectedIndex].value);">
								<option value="">구분</option>
								<option value="<?=$_VenderInfo->getVidx()?>::me" <?=$_VenderInfo->getVidx()."::me"==$gubun_vender? "selected":"";?>>내상품</option>
								<?
								//$sql2 = "SELECT r.trust_vender,p.vender FROM tblproduct p left join rent_product r on p.pridx=r.pridx ";
								//$sql2.= "WHERE (p.vender='".$_VenderInfo->getVidx()."' and (r.trust_vender is NULL or r.trust_vender='0')) ";
								//$sql2.= "OR (r.trust_vender='".$_VenderInfo->getVidx()."' AND r.trust_vender<>a.vender AND r.trust_approve='Y') ";
								//$sql2.= "GROUP BY r.trust_vender ";
		
		
		
								//$sql2.= "WHERE (p.vender='".$_VenderInfo->getVidx()."' or r.trust_vender is not null) AND r.istrust='0' AND r.trust_approve='Y' GROUP BY r.trust_vender";

								$sql2 = "SELECT ta.ta_idx,ta.give_vender,ta.take_vender FROM tbltrustagree ta ";
								$sql2.= "WHERE (ta.give_vender='".$_VenderInfo->getVidx()."' OR ta.take_vender='".$_VenderInfo->getVidx()."') ";
								$sql2.= "AND ta.approve='Y' ";


								$res2=mysql_query($sql2,get_db_conn());
								while($row2=mysql_fetch_object($res2)){
									//if($_VenderInfo->getVidx()==$row2->vender) $search_vender = $row2->trust_vender."::give";	//받은위탁
									//else $search_vender = $row2->vender."::take";	//보낸위탁

									if($_VenderInfo->getVidx()==$row2->take_vender){//보낸위탁	
										$search_vender = $row2->give_vender."::take";
										$vener_idx = $row2->give_vender;
									}else{	//받은위탁
										$search_vender = $row2->take_vender."::give";
										$vener_idx = $row2->take_vender;
									}


									$sql2_ = "SELECT com_name FROM tblvenderinfo WHERE vender='".$vener_idx."'";
									$res2_=mysql_query($sql2_,get_db_conn());
									$data2_=mysql_fetch_object($res2_);
								?>
								<option value="<?=$search_vender?>" <?=$search_vender==$gubun_vender? "selected":"";?>><?=$data2_->com_name?></option>
								<?
								}
								?>
							</select>
						</td>
						<td align=center><a href="javascript:OrderSort('<?=($sort=="order by productcode asc"?"order by productcode desc":"order by productcode asc")?>')"; onMouseover="self.status=''; return true; "><B>상품코드</B></a></td>
						<td align=center><a href="javascript:OrderSort('<?=($sort=="order by productname asc"?"order by productname desc":"order by productname asc")?>')"; onMouseover="self.status=''; return true; "><B>상품명</B></a></td>

						<? /** 수수료 관련 jdy **/ ?>
						<? if ($account_rule==1 || $commission_type==1) {

							if ($account_rule==1) { ?>
							<td align=center><B>공급가</B></a></td>
						<?
							}else { ?>
							<td align=center><B>수수료</B></a></td>
						<?	}
						}?>
						<? /** 수수료 관련 jdy **/ ?>

						<td align=center><a href="javascript:OrderSort('<?=($sort=="order by sellprice asc"?"order by sellprice desc":"order by sellprice asc")?>')"; onMouseover="self.status=''; return true; "><B>가격</B></a></td>
						<td align=center><a href="javascript:OrderSort('<?=($sort=="order by regdate asc"?"order by regdate desc":"order by regdate asc")?>')"; onMouseover="self.status=''; return true; "><B>등록일</B></a></td>
						<td align=center><a href="javascript:OrderSort('<?=($sort=="order by quantity asc"?"order by quantity desc":"order by quantity asc")?>')"; onMouseover="self.status=''; return true; "><B>재고</B></a></td>
						<td align=center><B>복사</B></td>
						<td align=center><B>상태</B></td>
					</tr>
<?
					$colspan=9;
					$cnt=0;
					if($t_count>0) {
						/*
						$sql = "SELECT productcode,productname,sellprice,regdate,display,selfcode FROM tblproduct ".$qry." ".$sort." ";
						*/

						/* 개별 수수료 관련 jdy */
						$sql = "SELECT r.trust_vender,p.vender,p.rental,r.istrust,p.pridx,p.productcode,productname,sellprice,regdate,display,selfcode,p.quantity,c.rq_com, c.cf_com, c.rq_cost, c.cf_cost, c.status, c.first_approval, reservation FROM tblproduct p left join product_commission c on p.productcode=c.productcode left join rent_product r on p.pridx=r.pridx ".$qry." ".$sort." ";
						/* 개별 수수료 관련 jdy */

						$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];

						$result=mysql_query($sql,get_db_conn());
						$i=0;$tr_disabled="";
						while($row=mysql_fetch_object($result)) {
							$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
							
							if($row->trust_vender==$_VenderInfo->getVidx()) {//받은위탁
								$sql2 = "SELECT ta.ta_idx,ta.give_vender,ta.take_vender,ta.approve FROM tbltrustagree ta ";
								$sql2.= "WHERE (ta.take_vender='".$_VenderInfo->getVidx()."' AND give_vender='".$row->vender."') ";
								$res2=mysql_query($sql2,get_db_conn());
								$rw=mysql_fetch_object($res2);

								if($rw->approve!="Y"){
									$tr_disabled = "disabled='true'";
								}else{
									$tr_disabled = "";
								}
							}else{
								$tr_disabled = "";
							}

							echo "<tr height=30 bgcolor=#FFFFFF ".$tr_disabled.">\n";
							echo "	<td align=center><input type=checkbox name=chkprcode value=\"".$row->productcode."\"></td>\n";
							echo "	<td align=center style=\"font-size:8pt\">".$number."</td>\n";
							
							//구분(내상품,보낸위탁,받은위탁)
							echo "	<td align=center style=\"font-size:8pt\">";
								if($row->trust_vender==$_VenderInfo->getVidx()) $search_vender = $row->vender;	//받은위탁
								else $search_vender = $row->trust_vender;	//보낸위탁
								$sql2 = "SELECT com_name FROM tblvenderinfo WHERE vender='".$search_vender."'";
								$res2=mysql_query($sql2,get_db_conn());
								$data2=mysql_fetch_object($res2);
								if($row->rental==1 || $row->istrust==1) echo "①내상품";
								else if($row->istrust==0 && $row->trust_vender==$_VenderInfo->getVidx()) echo "②(받은위탁)<br>".$data2->com_name;
								else if($row->istrust==0 && $row->trust_vender<>$_VenderInfo->getVidx()) echo "③(보낸위탁)<br>".$data2->com_name;
							echo "	</td>\n";
							
							echo "	<td align=center style=\"font-size:8pt\">".$row->productcode."</td>\n";
							$reservation = "";
							if( $row->reservation != "0000-00-00" ) {
								$reservation = "<font color=red>[예약배송:".$row->reservation."]</font><br>";
							}

							if($tr_disabled!=""){
								echo "	<td style='font-size:8pt;line-height:11pt;padding-left:5;padding-right:5'>".$reservation.titleCut(45,$row->productname.($row->selfcode?"-".$row->selfcode:""))."<img src=images/newwindow.gif border=0 align=absmiddle></A></td>\n";
							}else{
								echo "	<td style='font-size:8pt;line-height:11pt;padding-left:5;padding-right:5'>".$reservation."<A HREF=\"javascript:GoPrdinfo('".$row->productcode."','')\">".titleCut(45,$row->productname.($row->selfcode?"-".$row->selfcode:""))."</A> <A HREF=\"javascript:GoPrdinfo('".$row->productcode."','_blank')\"><img src=images/newwindow.gif border=0 align=absmiddle></A></td>\n";
							}

							 /** 수수료 관련 jdy **/
							if ($account_rule==1 || $commission_type==1) {
								/* 개별 수수료 관련 jdy */
								$history_html = "<button style='color:ffffff;background-color:1F497D;padding:0px 4px;border:0;' onclick=\"viewHistory('".$row->productcode."')\">H</button>";
								if ($account_rule==1) {

									if ($row->status == "") {
										$com_value = "공급가를 지정해주세요.";
									}else if ($row->status == "1") {

										if ($row->first_approval == "1") {
											$com_value = $history_html." ".$row->cf_cost."원 [".$row->rq_cost."원 요청]";
										}else{
											$com_value = $history_html." [".$row->rq_cost."원 요청]";
										}
									}else if ($row->status == "2") {
										$com_value = $history_html." ".$row->cf_cost."원";
									}else if ($row->status == "3") {

										if ($row->first_approval == "1") {
											$com_value = $history_html." ".$row->cf_cost."원 [".$row->rq_cost."원 요청거부]";
										}else{
											$com_value = $history_html." [".$row->rq_cost."원 요청거부]";
										}

									}
								}else{

									if ($commission_type =="1") {

										if ($row->status == "") {
											$com_value = "수수료를 지정해주세요.";
										}else if ($row->status == "1") {
											if ($row->first_approval == "1") {
												$com_value = $history_html." ".$row->cf_com."% [".$row->rq_com."% 요청]";
											}else{
												$com_value = $history_html." [".$row->rq_com."% 요청]";
											}


										}else if ($row->status == "2") {
											$com_value = $history_html." ".$row->cf_com."%";
										}else if ($row->status == "3") {
											if ($row->first_approval == "1") {
												$com_value = $history_html." ".$row->cf_com."% [".$row->rq_com."% 요청거부]";
											}else{
												$com_value = $history_html." [".$row->rq_com."% 요청거부]";
											}

										}
									}
								}

								/* 개별 수수료 관련 jdy */

								echo "	<td align=center style=font-size:8pt;padding-right:5>".$com_value."</td>\n";
							}

							echo "	<td align=right style=font-size:8pt;padding-right:5>".number_format($row->sellprice)."</td>\n";
							echo "	<td align=center style=\"font-size:8pt\">".substr($row->regdate,0,10)."</td>\n";
							echo "	<td align=center style=\"font-size:8pt\">";
								if($row->rental!=2){
									if (strlen($row->quantity)==0) echo "무제한";
									else if($row->quantity<=0) echo "품절";
									else echo $row->quantity;
								}else{
									$sql = "SELECT MAX(productCount) as prCount FROM rent_product_option WHERE pridx='".$row->pridx."'";
									$pRes=mysql_query($sql,get_db_conn());
									if(mysql_num_rows($pRes)>0) {
										$pCnt = mysql_fetch_object($pRes);
										echo $pCnt->prCount;
									}
								}
							echo "	</td>\n";
							echo "	<td align=center style=\"font-size:8pt\"><a href=\"javascript:Copy('".$row->productcode."')\">복사</a></td>\n";
							echo "	<td align=center>";
							if(substr($_venderdata->grant_product,1,1)=="Y" && substr($_venderdata->grant_product,3,1)=="N") {
								if($row->display=="Y") {
									echo "<img src=images/icon_on.gif border=0 style=\"cursor:hand\" onclick=\"setPrdDisplaytype('".$row->productcode."','N')\">";
								} else {
									if($tr_disabled!=""){
										echo "<img src=images/icon_off.gif border=0>";
									}else{
										echo "<img src=images/icon_off.gif border=0 style=\"cursor:hand\" onclick=\"setPrdDisplaytype('".$row->productcode."','Y')\">";
									}
								}
							} else {
								if($row->display=="Y") {
									echo "<img src=images/icon_on.gif border=0 onclick=\"confirmAdminProduct()\">";
								} else {
									if($tr_disabled!=""){
										echo "<img src=images/icon_off.gif border=0>";
									}else{
										echo "<img src=images/icon_off.gif border=0 onclick=\"confirmAdminProduct()\">";
									}
								}
							}
							echo "	</td>\n";
							echo "</tr>\n";
							$i++;
						}
						mysql_free_result($result);
						$cnt=$i;

						if($i>0) {
							$total_block = intval($pagecount / $setup[page_num]);
							if (($pagecount % $setup[page_num]) > 0) {
								$total_block = $total_block + 1;
							}
							$total_block = $total_block - 1;
							if (ceil($t_count/$setup[list_num]) > 0) {
								// 이전	x개 출력하는 부분-시작
								$a_first_block = "";
								if ($nowblock > 0) {
									$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='첫 페이지';return true\"><img src=".$Dir."images/minishop/btn_miniprev_end.gif border=0 align=absmiddle></a> ";
									$prev_page_exists = true;
								}
								$a_prev_page = "";
								if ($nowblock > 0) {
									$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='이전 ".$setup[page_num]." 페이지';return true\"><img src=".$Dir."images/minishop/btn_miniprev.gif border=0 align=absmiddle></a> ";

									$a_prev_page = $a_first_block.$a_prev_page;
								}
								if (intval($total_block) <> intval($nowblock)) {
									$print_page = "";
									for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
										if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
											$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></font> ";
										} else {
											$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='페이지 : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
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
											$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></FONT> ";
										} else {
											$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='페이지 : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
										}
									}
								}
								$a_last_block = "";
								if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
									$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
									$last_gotopage = ceil($t_count/$setup[list_num]);
									$a_last_block .= " <a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='마지막 페이지';return true\"><img src=".$Dir."images/minishop/btn_mininext_end.gif border=0 align=absmiddle></a>";
									$next_page_exists = true;
								}
								$a_next_page = "";
								if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
									$a_next_page .= " <a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='다음 ".$setup[page_num]." 페이지';return true\"><img src=".$Dir."images/minishop/btn_mininext.gif border=0 align=absmiddle></a>";
									$a_next_page = $a_next_page.$a_last_block;
								}
							} else {
								$print_page = "<B>1</B>";
							}
							$pageing=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
						}
					} else {
						echo "<tr height=28 bgcolor=#FFFFFF><td colspan=".$colspan." align=center>조회된 내용이 없습니다.</td></tr>\n";
					}
?>
					<input type=hidden name=tot value="<?=$cnt?>">
					</form>

					</table>
					</td>
				</tr>
				<tr>
					<td>
						<div class="tableTop">
							<button type="text" onclick="javascript:trustOpen('trustall')">전체위탁하기</button> 
							<button type="text" onclick="javascript:trustOpen('trust')">선택위탁하기</button>
						</div>
					</td>
				</tr>
				<tr><td height=10></td></tr>
				<tr>
					<td align=center>
					<form name="pageForm" method="post">
					<input type=hidden name='code' value='<?=$code?>'>
					<input type=hidden name='disptype' value='<?=$disptype?>'>
					<input type=hidden name='s_check' value='<?=$s_check?>'>
					<input type=hidden name='search' value='<?=$search?>'>
					<input type=hidden name='sort' value='<?=$sort?>'>
					<input type=hidden name='block' value='<?=$block?>'>
					<input type=hidden name='gotopage' value='<?=$gotopage?>'>
					<input type="hidden" name="list_num" value="<?=$setup[list_num]?>">
					</form>

					<?=$pageing?>

					</td>
				</tr>
				</table>

				</td>
			</tr>
			<!-- 처리할 본문 위치 끝 -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>

<form name=etcform method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode>
<input type=hidden name=prcodes>
<input type=hidden name=display>
<input type=hidden name=cproductcode>
</form>

<form name=form3 method=post action="product_prdmodify.php">
<input type=hidden name=prcode>
</form>

</table>

<iframe name="processFrame" src="about:blank" width="500" height="500" scrolling=no frameborder=no></iframe>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>
