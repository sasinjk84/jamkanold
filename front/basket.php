<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/basket_func.php");

/// POST INPUT
$mode=$_POST["mode"];
$code=$_POST["code"];
$ordertype=$_REQUEST["ordertype"];	//�ٷα��� ���� (�ٷα��Ž� => ordernow)
$opts=$_POST["opts"];	//�ɼǱ׷� ���õ� �׸� (��:1,1,2,)
$option1=$_POST["option1"];	//�ɼ�1
$option2=$_POST["option2"];	//�ɼ�2
$quantity=(int)$_REQUEST["quantity"];	//���ż���
if($quantity==0) $quantity=1;
$productcode=$_REQUEST["productcode"];

$orgquantity=$_POST["orgquantity"];
$orgoption1=$_POST["orgoption1"];
$orgoption2=$_POST["orgoption2"];

$assemble_type=$_POST["assemble_type"];
$assemble_list=@str_replace("|","",$_POST["assemble_list"]);
$assembleuse=$_POST["assembleuse"];
$assemble_idx=(int)$_POST["assemble_idx"];

$package_idx=(int)$_POST["package_idx"];

$sell_memid = $_POST["sell_memid"];

$wishCate = (empty($_REQUEST['sfld'])?"A":$_REQUEST['sfld']);

$sels=(array)$_POST["basket_select_item"];

if($ordertype == 'recommand'){ // Ÿȸ�� ��õ ���� ��� ó��
	if(strlen($_ShopInfo->getMemid())==0) {
		Header("Location:".$Dir.FrontDir."login.php?chUrl=".getUrl());
		exit;
	}

	if(!_empty($_REQUEST['rcode'])){
		if(substr($_REQUEST['rcode'],0,5) != 'RECOM'){
			_alert('��ġ �ϴ� ������ �����ϴ�.','/');
			exit;
		}

		$sql = "select * from recommand_request where recomcode='".substr($_REQUEST['rcode'],5)."' limit 1";
		if(false === $res = mysql_query($sql,get_db_conn())) _alert('DB ȣ�� ����','/');
		if(mysql_num_rows($res) < 1) _alert('��ġ�ϴ� ������ ã�� �� �����ϴ�.','/');
		
		$reqinfo = mysql_fetch_assoc($res);
		//if(!_empty($reqinfo['ordercode'])) _alert('�̹� ���� ó�� �� ��õ���Դϴ�.','/');		
		$recomorder = true;
		$recomcode =$reqinfo['recomcode'];

		@mysql_query("delete from tblbasket_recommand where ".$basketWhere,get_db_conn());
		
		//��õ�� ��ǰ �ߺ� �ֹ������ϵ��� ����
		$sql2 = "select * from recommand_basket where recomcode='".$recomcode."' limit 1";
		if(false === $res2 = mysql_query($sql2,get_db_conn())) _alert('DB ȣ�� ����','/');
		if(mysql_num_rows($res2) > 0){
			$reqinfo2 = mysql_fetch_assoc($res2);
			$recom_folder = $reqinfo['reqid']."_".substr($_REQUEST['rcode'],5,8);
			@mysql_query("delete from tblbasket_recommand where basketidx='".$reqinfo2['basketidx']."'",get_db_conn());
		}
//echo substr($_REQUEST['rcode'],5,8);exit;

		//mysql_query("insert into tblbasket_recommand (basketidx,tempkey,productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,sell_memid,ordertype,memid) select basketidx,'".$_ShopInfo->getTempkey()."',productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,'','recommand','".$_ShopInfo->getMemid()."','".$recom_folder."' from recommand_basket where recomcode ='".$recomcode."'");
		
		$sql = "select bfidx from basket_folder where name='".$recom_folder."' and id='".$_ShopInfo->getMemid()."'";
		$res =  mysql_query($sql,get_db_conn());
		if(mysql_num_rows($res)>0){
			$bdata = mysql_fetch_assoc($res);
			$bfidx = $bdata[bfidx];
		}else{
			$sql = "insert into basket_folder set id='".$_ShopInfo->getMemid()."' ,name='".$recom_folder."',type='".$_REQUEST['ordertype']."'";
			if(false === mysql_query($sql,get_db_conn())) _alert('DB ���� ����','-1');
			$bfidx = mysql_insert_id(get_db_conn());	
		}
		//echo "insert into tblbasket_recommand (basketidx,tempkey,productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,sell_memid,ordertype,memid,folder) select basketidx,'".$_ShopInfo->getTempkey()."',productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,'','recommand','".$_ShopInfo->getMemid()."','".$bfidx."' from recommand_basket where recomcode ='".$recomcode."'";exit;

		mysql_query("insert into tblbasket_normal (basketidx,tempkey,productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,sell_memid,ordertype,memid,folder) select basketidx,'".$_ShopInfo->getTempkey()."',productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,memid,'recommand','".$_ShopInfo->getMemid()."','".$bfidx."' from recommand_basket where recomcode ='".$recomcode."'");
		_alert('','/front/basket.php?basket_type=recommand&sfld='.$bfidx);

	}else if(_empty($_ShopInfo->getMemid())){
		_alert('�α��� �Ǿ� ���� �ʽ��ϴ�.','-1');
		exit;
	}else{
	 	@mysql_query("delete from tblbasket_recommand where ".$basketWhere,get_db_conn());
		mysql_query("insert into tblbasket_recommand (basketidx,tempkey,productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,sell_memid,ordertype,memid) select basketidx,'".$_ShopInfo->getTempkey()."',productcode,opt1_idx,opt2_idx,optidxs,quantity,deli_type,date,memid,'recommand',memid from recommand_basket where recomcode is null and memid='".$_ShopInfo->getMemid()."'");
	}
}


// �ֹ�Ÿ�Ժ� ��ٱ��� ���̺�
$basket = basketTable($ordertype);
/*
if(strlen($_ShopInfo->getMemid())==0 or $ordertype=="ordernow") {	//��ȸ��
	$basketWhere = "tempkey='".$_ShopInfo->getTempkey()."' and memid=''";
}else{
	$basketWhere = "memid='".$_ShopInfo->getMemid()."'";
}
*/
if(strlen($_ShopInfo->getMemid())==0){//��ȸ��
	$basketWhere= "1=1 AND tempkey='".$_ShopInfo->getTempkey()."' and memid='' ";
}else{
	if($tblbasket=="tblbasket_ordernow") {
		$basketWhere= "1=1 AND tempkey='".$_ShopInfo->getTempkey()."' ";
	}
	$basketWhere= "1=1 AND memid='".$_ShopInfo->getMemid()."' ";
}

if( $ordertype != "" AND $ordertype != "pester" ){
	$sql = "DELETE FROM ".$basket." WHERE ".$basketWhere;
	//mysql_query($sql,get_db_conn());
}

if($assemble_idx==0) {
	if($assembleuse=="Y") {
		$assemble_idx="99999";
	}
} else {
	$assembleuse="Y";
}


//��ٱ��� ����Ű Ȯ��
if(strlen($_ShopInfo->getTempkey())==0 || $_ShopInfo->getTempkey()=="deleted") {
	$_ShopInfo->setTempkey($_data->ETCTYPE["BASKETTIME"]);	
}

//sns ȫ�� ����üũ
if(strlen($_ShopInfo->getMemid()) > 0){
	$sql ="UPDATE ".$basket." SET sell_memid ='' WHERE ".$basketWhere." AND sell_memid='".$_ShopInfo->getMemid()."'";
	mysql_query($sql,get_db_conn());
}
//echo $_POST['act'];exit;
switch($_POST['act']){
	case 'deleteItem':	 // �ű� ���� ����		
		if(_isInt($_REQUEST['sbasketidx'])){
			$delidxs = array($_REQUEST['sbasketidx']);
		}else if(_array($_REQUEST['basket_select_item'])){
			$delidxs = $_REQUEST['basket_select_item'];
		}
		$sql = "delete r.* from rent_basket_temp r left join tblbasket_normal b on b.basketidx=r.basketidx and r.ordertype=b.ordertype where b.".$basketWhere." and  b.basketidx in ('".implode("','",$delidxs)."')";	

		mysql_query($sql,get_db_conn());	
		$sql = "delete from tblbasket_normal where ".$basketWhere." and  basketidx in ('".implode("','",$delidxs)."')";

		mysql_query($sql,get_db_conn());
		_alert('','/front/basket.php?ordertype='.$_REQUEST['ordertype'].'&sfld='.$_REQUEST['sfld']);	
		exit;	
	case 'upd':
		$ret = updateBasketQuantity($_POST['ordertype'],$_POST['sbasketidx'],$_POST['sbasketquantity']);			
		_alert($ret['err'],'/front/basket.php?ordertype='.$_REQUEST['ordertype'].'&sfld='.$_REQUEST['sfld']);	
		break;
	case 'delichange':
		$ret = updateBasketDelitype($_POST['ordertype'],$_POST['sbasketidx'],$_POST['sbasketdelitype']);			
		_alert($ret['err'],'/front/basket.php?ordertype='.$_REQUEST['ordertype'].'&sfld='.$_REQUEST['sfld']);	
		break;
	case 'delFolder': // ���� ����	
		if(_empty($_ShopInfo->getMemid())) _alert('ȸ�� ���� ����Դϴ�.','-1');
		if(!_isInt($_REQUEST['bfidx'])) _alert('��� ���� ������ ������ �ֽ��ϴ�.','-1');		
		
		$sql = "select bfidx from basket_folder where bfidx='".$_REQUEST['bfidx']."' limit 1";
		if(false === $res =  mysql_query($sql,get_db_conn())) _alert('DB ��ȸ ����','-1');
		//$sql = "update tblbasket set folder=NULL where tempkey='".$_ShopInfo->getTempkey()."' and folder='".$_REQUEST['bfidx']."'"; // ���� ���� �ʴ� ��쿡 ���
	//	$sql = "delete from tblbasket where tempkey='".$_ShopInfo->getTempkey()."' and folder='".$_REQUEST['bfidx']."'"; // ����
			$sql = "delete from tblbasket_normal where ".$basketWhere." and folder='".$_REQUEST['bfidx']."'"; // ����
		@mysql_query($sql,get_db_conn());
		$sql = "delete from basket_folder where id='".$_ShopInfo->getMemid()."' and bfidx='".$_REQUEST['bfidx']."'"; // ����
		@mysql_query($sql,get_db_conn());
		_alert('','/front/basket.php?ordertype='.$_REQUEST['ordertype'].'&sfld='.$bfidx);	
		break;
	case 'modifyFoldername':

		if(_empty($_ShopInfo->getMemid())) _alert('ȸ�� ���� ����Դϴ�.','-1');
		if(!_isInt($_REQUEST['bfidx'])) _alert('��� ���� ������ ������ �ֽ��ϴ�.','-1');		
		if(_empty($_REQUEST['newFoldername'])) _alert('���� �������� ���� ���� �ʾҽ��ϴ�.','-1');
		
		$sql = "select bfidx from basket_folder where bfidx='".$_REQUEST['bfidx']."' limit 1";
		if(false === $res =  mysql_query($sql,get_db_conn())) _alert('DB ��ȸ ����','-1');
		else if(mysql_num_rows($res) <1) _alert('��� ������ ã�� �� �����ϴ�.');	
		$bfidx = mysql_result($res,0,0);

		$sql = "select bfidx from basket_folder where name='".$_REQUEST['newFoldername']."' and id='".$_ShopInfo->getMemid()."'";
		$res =  mysql_query($sql,get_db_conn());
		if(mysql_num_rows($res) >0) _alert('������ �׷���� �����մϴ�.','-1');	
		else{
			$sql = "update basket_folder set name='"._escape($_REQUEST['newFoldername'],false)."' where id='".$_ShopInfo->getMemid()."' and bfidx='".$bfidx."'";
			if(false === mysql_query($sql,get_db_conn())) _alert('DB ó�� ����','-1');
			_alert('','/front/basket.php?ordertype='.$_REQUEST['ordertype'].'&sfld='.$bfidx);
		}
		break;
	case 'insertFolder':
		$sql = "select bfidx from basket_folder where name='".$_REQUEST['newFolder']."' and id='".$_ShopInfo->getMemid()."'";
		$res =  mysql_query($sql,get_db_conn());
		if(mysql_num_rows($res) >0) _alert('������ �׷���� �����մϴ�.','-1');	
		else{
			$sql = "insert into basket_folder set id='".$_ShopInfo->getMemid()."' ,name='"._escape($_REQUEST['newFolder'],false)."',type='".$_REQUEST['ordertype']."'";
			if(false === mysql_query($sql,get_db_conn())) _alert('DB ���� ����','-1');
			$bfidx = mysql_insert_id(get_db_conn());
			_alert('','/front/basket.php?ordertype='.$_REQUEST['ordertype'].'&sfld='.$bfidx);	
		}
		break;
	case 'moveFolder':	
		
		if(_empty($_ShopInfo->getMemid())) _alert('ȸ�� ���� ����Դϴ�.','-1');
		if(!_array($_REQUEST['basket_select_item'])) _alert('��� ��ǰ�� ���� ���� �ʾҽ��ϴ�.','-1');	
		if(_isInt($_REQUEST['moveFolder'])){
			$sql = "select bfidx from basket_folder where bfidx='".$_REQUEST['moveFolder']."' limit 1";
			if(false === $res = mysql_query($sql,get_db_conn())) _alert('DB ��ȸ ����','-1');
			else if(mysql_num_rows($res) <1) _alert('��� ������ ã�� �� �����ϴ�.');
			$bfidx = mysql_result($res,0,0);
		}else if(!_empty($_REQUEST['newFolder'])){
			$sql = "insert into basket_folder set id='".$_ShopInfo->getMemid()."' ,name='"._escape($_REQUEST['newFolder'],false)."',type='".$_REQUEST['ordertype']."'";
			if(false === mysql_query($sql,get_db_conn())) _alert('DB ���� ����','-1');
			$bfidx = mysql_insert_id(get_db_conn());
		}
		
		if($bfidx!=0 && !_isInt($bfidx)) _alert('��� ������ �ùٸ��� �ʽ��ϴ�.','-1');

	//	$sql = "update tblbasket set folder='".$bfidx."' where ".$basketWhere." and basketidx in ('".implode("','",$_REQUEST['basket_select_item'])."')";
		$sql = "update tblbasket_normal set folder='".$bfidx."' where ".$basketWhere." and basketidx in ('".implode("','",$_REQUEST['basket_select_item'])."')";
		mysql_query($sql,get_db_conn());
		_alert('','/front/basket.php?ordertype='.$_REQUEST['ordertype'].'&sfld='.$bfidx);	
		break;
	default:
		break;
	
}





//���� ������ ���� ���
if($_REQUEST['mode']=="coupon" && strlen($_REQUEST['coupon_code'])==8){
	$onload = '';
	if(strlen($_ShopInfo->getMemid())==0) {	//��ȸ��
		echo "<html></head><body onload=\"alert('�α��� �� ���� �ٿ�ε尡 �����մϴ�.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."';\"></body></html>";exit;
	}else{
		$sql = "SELECT * FROM tblcouponinfo where coupon_code = '".$_REQUEST['coupon_code']."'";


		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			if($row->issue_tot_no>0 && $row->issue_tot_no<$row->issue_no+1) {
				$onload="<script>alert(\"��� ������ �߱޵Ǿ����ϴ�.\");</script>";
			} else {
				$date=date("YmdHis");
				if($row->date_start>0) {
					$date_start=$row->date_start;
					$date_end=$row->date_end;
				} else {
					$date_start = substr($date,0,10);
					$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($row->date_start),substr($date,0,4)))."23";
				}
				$sql = "INSERT tblcouponissue SET coupon_code	= '".$_REQUEST['coupon_code']."',id			= '".$_ShopInfo->getMemid()."',date_start	= '".$date_start."',date_end	= '".$date_end."', date		= '".$date."' ";
				mysql_query($sql,get_db_conn());
				if(!mysql_errno()) {
					$sql = "UPDATE tblcouponinfo SET issue_no = issue_no+1 WHERE coupon_code = '".$_REQUEST['coupon_code']."'";
					mysql_query($sql,get_db_conn());
					$onload="<script>alert(\"�ش� ���� �߱��� �Ϸ�Ǿ����ϴ�.\\n\\n��ǰ �ֹ��� �ش� ������ ����Ͻ� �� �ֽ��ϴ�.\");</script>";
				} else {
					if($row->repeat_id=="Y") {	//������ ��߱��� �����ϴٸ�,,,,
						$sql = "UPDATE tblcouponissue SET ";
						if($row->date_start<=0) {
							$sql.= "date_start	= '".$date_start."', ";
							$sql.= "date_end	= '".$date_end."', ";
						}
						$sql.= "used		= 'N' ";
						$sql.= "WHERE coupon_code='".$_REQUEST['coupon_code']."' ";
						$sql.= "AND id='".$_ShopInfo->getMemid()."' ";
						mysql_query($sql,get_db_conn()) or die(mysql_error());
						$onload="<script>alert(\"�ش� ���� �߱��� �Ϸ�Ǿ����ϴ�.\\n\\n��ǰ �ֹ��� �ش� ������ ����Ͻ� �� �ֽ��ϴ�.\");</script>";

					} else {
						$onload="<script>alert(\"�̹� ������ �߱޹����̽��ϴ�.\\n\\n�ش� ������ ��߱��� �Ұ����մϴ�.\");</script>";
					}
				}
			}
		}
		mysql_free_result($result);

	}

	if(!_empty($onload)){
		echo $onload;
	}
	?>
	<script language="javascript" type="text/javascript">
		document.location.replace('/front/basket.php');
	</script>
	<?
	exit;

}




if($mode=="clear") {	//��ٱ��� ����
	clearBasket($basket);
} else if($mode == 'seldel'){
	_alert('����� ������� ��ȸ ó�� ���� �ʾҽ��ϴ�.','-1');
}else if(strlen($productcode)==18) {
	if(strlen($code)==0) {
		$code=substr($productcode,0,12);
	}
	$codeA=substr($code,0,3);
	$codeB=substr($code,3,3);
	$codeC=substr($code,6,3);
	$codeD=substr($code,9,3);
	if(strlen($codeA)!=3) $codeA="000";
	if(strlen($codeB)!=3) $codeB="000";
	if(strlen($codeC)!=3) $codeC="000";
	if(strlen($codeD)!=3) $codeD="000";

	$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeB='".$codeB."' AND codeC='".$codeC."' AND codeD='".$codeD."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		if($row->group_code=="NO") {	//���� �з�
			echo "<html></head><body onload=\"alert('�ǸŰ� ����� ��ǰ�Դϴ�.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
		} else if($row->group_code=="ALL" && strlen($_ShopInfo->getMemid())==0) {	//ȸ���� ���ٰ���
			echo "<html></head><body onload=\"alert('�α��� �ϼž� ��ٱ��Ͽ� ������ �� �ֽ��ϴ�.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
		} else if(strlen($row->group_code)>0 && $row->group_code!="ALL" && $row->group_code!=$_ShopInfo->getMemgroup()) {	//�׷�ȸ���� ����
			echo "<html></head><body onload=\"alert('�ش� �з��� ���� ������ �����ϴ�.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
		}

		//Wishlist ���
		if($mode=="wishlist") {
			if(strlen($_ShopInfo->getMemid())==0) {	//��ȸ��
				echo "<html></head><body onload=\"alert('�α����� �ϼž� �� ���񽺸� �̿��Ͻ� �� �ֽ��ϴ�.');location.href='".$Dir.FrontDir."login.php?chUrl=".getUrl()."';\"></body></html>";exit;
			}
			$sql = "SELECT COUNT(*) as totcnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' ";
			$result2=mysql_query($sql,get_db_conn());
			$row2=mysql_fetch_object($result2);
			$totcnt=$row2->totcnt;
			mysql_free_result($result2);
			$maxcnt=20;
			if($totcnt>=$maxcnt) {
				$sql = "SELECT b.productcode FROM tblwishlist a, tblproduct b ";
				$sql.= "LEFT OUTER JOIN tblproductgroupcode c ON b.productcode=c.productcode ";
				$sql.= "WHERE a.id='".$_ShopInfo->getMemid()."' AND a.productcode=b.productcode ";
				$sql.= "AND b.display='Y' ";
				$sql.= "AND (b.group_check='N' OR c.group_code='".$_ShopInfo->getMemgroup()."') ";
				$sql.= "GROUP BY b.productcode ";
				$result2=mysql_query($sql,get_db_conn());
				$i=0;
				$wishprcode="";
				while($row2=mysql_fetch_object($result2)) {
					$wishprcode.="'".$row2->productcode."',";
					$i++;
				}
				mysql_free_result($result2);
				$totcnt=$i;
				$wishprcode=substr($wishprcode,0,-1);
				if(strlen($wishprcode)>0) {
					$sql = "DELETE FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' AND productcode NOT IN (".$wishprcode.") ";
					mysql_query($sql,get_db_conn());
				}
			}
			if($totcnt<$maxcnt) {
				$sql = "SELECT COUNT(*) as cnt FROM tblwishlist WHERE id='".$_ShopInfo->getMemid()."' AND productcode='".$productcode."' ";
				$result2=mysql_query($sql,get_db_conn());
				$row2=mysql_fetch_object($result2);
				$cnt=$row2->cnt;
				mysql_free_result($result2);
				if($cnt>0) {
					$sql = "UPDATE tblwishlist SET date='".date("YmdHis")."' ";
					$sql.= "WHERE id='".$_ShopInfo->getMemid()."' ";
					$sql.= "AND productcode='".$productcode."' ";
					$sql.= "AND opt1_idx='".$option1."' AND opt2_idx='".$option2."' AND optidxs='".$opts."' ";
					mysql_query($sql,get_db_conn());

					echo "<html></head><body onload=\"alert('WishList�� �̹� ��ϵ� ��ǰ�Դϴ�.');history.go(-1);\"></body></html>";exit;
				} else {
					$sql = "INSERT tblwishlist SET ";
					$sql.= "id			= '".$_ShopInfo->getMemid()."', ";
					$sql.= "productcode	= '".$productcode."' ";
					mysql_query($sql,get_db_conn());
					echo "<html></head><body onload=\"alert('WishList�� �ش� ��ǰ�� ����Ͽ����ϴ�.');history.go(-1);\"></body></html>";exit;
				}
			} else {
				echo "<html></head><body onload=\"alert('WishList���� ".$maxcnt."�� ������ ����� �����մϴ�.\\n\\nWishList���� �ٸ� ��ǰ�� �����Ͻ� �� ����Ͻñ� �ٶ��ϴ�.');history.go(-1);\"></body></html>";exit;
			}
		}
	} else {
		echo "<html></head><body onload=\"alert('�ش� �з��� �������� �ʽ��ϴ�.');location.href='".$Dir.FrontDir."basket.php';\"></body></html>";exit;
	}
	mysql_free_result($result);
}


$errmsg="";



if($mode!="clear" && $mode!="seldel" && $mode!="wishlist" && strlen($productcode)==18) {	
	//�ش��ǰ����, ��ٱ��ϴ��, �ٷα���, ���� ������Ʈ, �������Žÿ�...
	if($mode=="del") {
		_alert('���� ȣ�� �ʿ�','-1');
	} else if ($mode=="upd") {
		_alert('���� ȣ�� �ʿ�','-1');
	} else if(preg_match('/^[0-9]{18}$/',$productcode)) {		
		$return = addBasket($_POST);
		if($_POST['ordertype'] == 'recommandnow') exit;
		if(_empty($return['msg'])){ ?>
				<script type="text/javascript">
					if(!confirm("��ٱ��Ͽ� �ش� ��ǰ�� ����Ͽ����ϴ�.\r\n\r\n��ٱ��� �������� �̵� �ϰڽ��ϱ�?")){
						<? if($return_url=="list"){ ?>
							window.history.go(-1);
						<? }else{ ?>
							document.location.replace('/front/productdetail.php?productcode=<?=$productcode?>');
						<? } ?>
					}else{
						document.location.replace('<?=$Dir.FrontDir?>basket.php'); 
					}					
				</script><?
		}else{
			_alert($return['msg'],'-1');
		}
//		_pr($return);
		exit;
	}
}

$mycoupon_codes = getMyCouponList('',true);


//��ٱ��� ���̺� ����
$tblbasket = $basket;

if(!_empty($_ShopInfo->getMemid())){ 
	$folders = array();
	if(false !== $fres = mysql_query("select * from basket_folder where id='".$_ShopInfo->getMemid()."' order by bfidx desc",get_db_conn())){		
		while($frow = mysql_fetch_assoc($fres)){			
			if(!_empty($frow['name'])) $folders[$frow['bfidx']] = $frow['name'];
		}
	}	
}

//�⺻������ ���
if($_REQUEST['sfld']=="0"){
	$_REQUEST['sfld'] = $_REQUEST['sfld'];
}else{
	if(!_empty($_REQUEST['sfld']) && !isset($folders[$_REQUEST['sfld']])) $_REQUEST['sfld'] = '';
}

// ��ٱ��� ������ (Array) ==================================================
$basketItems = getBasketByArray($basket,NULL,$_REQUEST['sfld']);
if($basketItems['errcnt'] > 0){
	_alert('�Ϻ� ������ �׸�� ���� ȸ�� �׸� ���� �ڵ� ���� ó�� �Ǿ����ϴ�.','/front/basket.php');
}

/*
ȸ�� ��� ���� �޼��� ============
	RW : �ݾ� �߰� ����
	RP  : % �߰� ����
	SW : �ݾ� �߰� ����
	SP  : % �߰� ����
*/
$groupMemberSale = "";
if( $basketItems['groupMemberSale'] ) {
	$groupMemberSale .= "
		<font style=\"letter-spacing:0px;\"><b>ȸ���������</b> : ".$basketItems['groupMemberSale']['name']."</font>��(".$basketItems['groupMemberSale']['group'].")��
		<font color=\"#ee0a02\" style=\"letter-spacing:0px;\">".number_format($basketItems['groupMemberSale']['useMoney'])."��</font> �̻�
		<font  color=\"#ee0a02\">".$basketItems['groupMemberSale']['payType']."</font> ������<br />
	";
	if($basketItems['groupMemberSale']['groupCode']=="RW") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>".number_format($basketItems['groupMemberSale']['addMoney'])."</b>��</font>�� �������� �߰��� ������ �帳�ϴ�.";
	} else if($basketItems['groupMemberSale']['groupCode']=="RP") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>���űݾ��� ".number_format($basketItems['groupMemberSale']['addMoney'])."%</b></font>�� ������ �帳�ϴ�.";
	} else if($basketItems['groupMemberSale']['groupCode']=="SW") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>���űݾ� ".number_format($basketItems['groupMemberSale']['addMoney'])."��</b></font>�� �߰��� ������ �帳�ϴ�.";
	} else if($basketItems['groupMemberSale']['groupCode']=="SP") {
		$groupMemberSale .= "<font color=#ee0a02 style=letter-spacing:0px;><b>���űݾ��� ".number_format($basketItems['groupMemberSale']['addMoney'])."%</b></font>�� �߰��� ������ �帳�ϴ�.";
	}
}


?>

<HTML>
<HEAD>
	<TITLE><?=$_data->shoptitle?> - ��ٱ���</TITLE>
	<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
	<META http-equiv="X-UA-Compatible" content="IE=Edge" />
	<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
	<META name="keywords" content="<?=$_data->shopkeyword?>">
	<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
	<script type="text/javascript" src="<?=$Dir?>lib/DropDown.js.php"></script>
	<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
	<script language="javascript" type="text/javascript">
		var $j =jQuery.noConflict();
	</script>
	<? include($Dir."lib/style.php")?>
	<script language="javascript" type="text/javascript">
		<!--
		function checkSelect(){
			var f = document.basketForm;
			var selitems = $j(f).find('input[name^=basket_select_item]:checked');
			return selitems.length;
		}

		function CheckForm(mode,idx) {
			var f = document.basketForm;
			if(mode=="del") {
				if(idx=='sel'){
					f.sbasketidx.value = '';
					var selitems = $j(f).find('input[name^=basket_select_item]:checked');
					
					if(selitems.length <1){
						alert('���õ� �׸��� �����ϴ�.');
					}else if(!confirm("���õ� �׸��� ��ٱ��Ͽ��� �����Ͻðڽ��ϱ�?")){
						return;
					}
				}else if(confirm("�ش� ��ǰ�� ��ٱ��Ͽ��� �����Ͻðڽ��ϱ�?")) {
					f.sbasketidx.value = idx;
				}
				mode = 'deleteItem';
			} else if(mode=="upd"){
				var el = $j(f).find('input[name=quantity\\['+idx+'\\]]');
				if(!el){
					alert('��� ���� ���� ã���� �����ϴ�.');
				}else{
					quantity = parseInt($j(el).val());
					if(isNaN(quantity)){
						alert('������ ���ڸ� �Է��ϼ���');
					}else if(quantity.length < 1 || quantity < 1){
						alert('������ 0 �̻� �Է��ϼž� �մϴ�.');
					}
					
					f.sbasketidx.value = idx;
					f.sbasketquantity.value = quantity;
				}
			}else if(mode=="delichange"){
				var el = $j(f).find('select[name=deli_type\\['+idx+'\\]]');
				f.sbasketidx.value = idx;
				f.sbasketdelitype.value = $j(el).val();
			}
			f.act.value=mode;
			f.submit();
		}

		function change_quantity(gbn,idx) {
			tmp=document["form_"+idx].quantity.value;
			if(gbn=="up") {
				tmp++;
			} else if(gbn=="dn") {
				if(tmp>1) tmp--;
			}
			document["form_"+idx].quantity.value=tmp;
		}

		function go_wishlist(idx) {
			document.wishform.productcode.value=document["form_"+idx].productcode.value;
			document.wishform.opts.value=document["form_"+idx].opts.value;
			document.wishform.option1.value=document["form_"+idx].orgoption1.value;
			document.wishform.option2.value=document["form_"+idx].orgoption2.value;
			window.open("about:blank","confirmwishlist","width=500,height=300,scrollbars=no");
			document.wishform.submit();
		}

		function basket_clear() {
			if(confirm("��ٱ��ϸ� ���ðڽ��ϱ�?")) {
				document.delform.mode.value="clear";
				document.delform.submit();
			}
		}

		function check_login() {
			if(confirm("�α����� �ʿ��� �����Դϴ�. �α����� �Ͻðڽ��ϱ�?")) {
				document.location.href="<?=$Dir.FrontDir?>login.php?chUrl=<?=getUrl()?>";
			}
		}

		function setPackageShow(packageid) {
			if(packageid.length>0 && document.getElementById(packageid)) {
				if(document.getElementById(packageid).style.display=="none") {
					document.getElementById(packageid).style.display="";
				} else {
					document.getElementById(packageid).style.display="none";
				}
			}
		}

		// ��ٱ��� ��Ż ��ǰ �ɼ� ����
		function rentOptionCHG ( pridx, ordertype ) {
			window.open("/front/rentBasketOptionCHG_pop.php?pridx="+pridx+"&ordertype="+ordertype,"rentBasketOptionCHG_pop","width=500,height=800");
		}

		function CheckBoxAll() {
			var sa = true;
			var form = document.basketform;

			if(form.flag.value==1){
				sa = false;
			}

			for (var i=0;i<form.elements.length;i++) {
				var e = form.elements[i];
				if(e.type.toUpperCase()=="CHECKBOX" && e.name=="basket_select_item[]") {
					if(sa)
						e.checked = false;
					else
						e.checked = true;
				}
			}

			if(form.flag.value == 1) {
				form.flag.value = 0;
			} else{
				form.flag.value = 1;
			}
		}

		function GoDelete() {
			var form = document.basketform1;
			var issel=false;
			for (var i=0;i<form.elements.length;i++) {
				var e = form.elements[i];
				if(e.type.toUpperCase()=="CHECKBOX" && e.name=="basket_select_item[]") {
					if(e.checked==true) {
						issel=true;
						break;
					}
				}
			}
			if(!issel) {
				alert("������ ��ǰ�� �����ϼ���.");
				return;
			}
			if(confirm("�����Ͻðڽ��ϱ�?")) {
				form.mode.value="delete";
				form.submit();
			}
		}
		//-->
	</SCRIPT>
	<link type="text/css" rel="stylesheet" href="/css/newUI.css" />
<style type="text/css"> 
<!-- 
option.disabled { color: lightgrey; } 
--> 
</style> 
</HEAD>

<body<?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<? 
	@include_once $Dir.'_NaverCheckout/naverCheckout.class.php';
	@include_once $Dir.'_NaverCheckout/callback.js.php';

?>

<? include ($Dir.MainDir.$_data->menu_type.".php"); ?>

<form name="selItemForm" id="selItemForm" method="post" action="<?=$Dir.FrontDir."basket.php"?>">
	<input type=hidden name="mode" value="">
	<input type=hidden name="basketidxs" value=""/>
	<input type=hidden name="ordertype" value="<?=$ordertype?>"/>
</form>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<?
	$leftmenu="Y";
	if($_data->design_basket=="U") {
		$sql="SELECT body,leftmenu FROM ".$designnewpageTables." WHERE type='basket'";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$body=$row->body;
			$body=str_replace("[DIR]",$Dir,$body);
			$leftmenu=$row->leftmenu;
			$newdesign="Y";
		}
		mysql_free_result($result);
	}

	if ($leftmenu!="N") {
		echo "<tr>\n";
		if ($_data->title_type=="Y" && file_exists($Dir.DataDir."design/basket_title.gif")) {
			echo "<td><img src=\"".$Dir.DataDir."design/basket_title.gif\" border=\"0\" alt=\"��ٱ���\"></td>\n";
		} else {
			echo "<td>\n";
			/*
			echo "<TABLE WIDTH=100% BORDER=0 CELLPADDING=0 CELLSPACING=0>\n";
			echo "<TR>\n";
			echo "	<TD><IMG SRC=".$Dir."images/".$_data->icon_type."/basket_title_head.gif ALT=></TD>\n";
			echo "	<TD width=100% valign=top background=".$Dir."images/".$_data->icon_type."/basket_title_bg.gif></TD>\n";
			echo "	<TD width=40><IMG SRC=".$Dir."images/".$_data->icon_type."/basket_title_tail.gif ALT=></TD>\n";
			echo "</TR>\n";
			echo "</TABLE>\n";
			*/
			echo "</td>\n";
		}
		echo "</tr>\n";
	}

	echo "<tr>\n";
	echo "	<td align=\"center\">\n";
	//echo $Dir.TempletDir."basket/basket".$_data->design_basket.".php";
	include ($Dir.TempletDir."basket/basket".$_data->design_basket.".php");
	echo "	</td>\n";
	echo "</tr>\n";

	if($ordertype=="ordernow") {	//�ٷα���
		if($sumprice>=$_data->bank_miniprice) {
			//echo "<script>location.href='".$Dir.FrontDir."login.php?chUrl=".urlencode($Dir.FrontDir."order.php")."';</script>";
			exit;
		} else {
			//$onload="<script>alert('".number_format($_data->bank_miniprice)."�� �̻� ���Ű� �����մϴ�.(code:071)');</script>";
		}
	}

	?>
	<script type="text/javascript">
		var arPresent = new Array(<?for($i=0;$i<sizeof($arPresent);$i++) { if ($i!=0) { echo ",";} echo "'".$arPresent[$i]."'"; } ?>);
		var arPester = new Array(<?for($i=0;$i<sizeof($arPester);$i++) { if ($i!=0) { echo ",";} echo "'".$arPester[$i]."'"; } ?>);
		var pname ="";
		function chkPresent(){
			pname ="";
			for(i=0;i<arPresent.length;i++)
			{
				if(arPresent[i] == "N"){
					obj = document.forms["form_"+i];
					pname = pname + obj.productname.value +"\n";
				}
			}
			if(pname){
				alert(pname + " \n�����ϱ� �Ұ��� ��ǰ�Դϴ�.\n�ش� ��ǰ�� �����Ͻð� �ٽ��ѹ� �õ����ּ���");
			}else{
				location.href ='<?=$Dir.FrontDir?>login.php?chUrl=<?=urlencode($Dir.FrontDir."order.php?ordertype=present")?>';
			}
		}
		function chkPester(){
			pname ="";
			for(i=0;i<arPester.length;i++)
			{
				if(arPester[i] == "N"){
					obj = document.forms["form_"+i];
					pname = pname + obj.productname.value +"\n";
				}
			}
			if(pname){
				alert(pname + " \n������ �Ұ��� ��ǰ�Դϴ�.\n�ش� ��ǰ�� �����Ͻð� �ٽ��ѹ� �õ����ּ���");
			}else{
				location.href ='<?=$Dir.FrontDir?>login.php?chUrl=<?=urlencode($Dir.FrontDir."order.php?ordertype=pester")?>';
			}
		}


		<?if($_data->coupon_ok=="Y") {?>
		function issue_coupon(coupon_code,productcode){
			document.couponissueform.mode.value="coupon";
			document.couponissueform.coupon_code.value=coupon_code;
			document.couponissueform.productcode.value=productcode;
			document.couponissueform.submit();
		}
		<?}?>

	</script>

	<form name=couponissueform method=get action="<?=$_SERVER[PHP_SELF]?>">
		<input type=hidden name=mode value="">
		<input type=hidden name=coupon_code value="">
		<input type=hidden name=productcode value="">
	</form>

	<form name=wishform method=post action="<?=$Dir.FrontDir?>confirm_wishlist.php" target="confirmwishlist">
		<input type=hidden name=productcode>
		<input type=hidden name=opts>
		<input type=hidden name=option1>
		<input type=hidden name=option2>
	</form>
	<form name=delform method=post action="<?=$_SERVER[PHP_SELF]?>">
		<input type=hidden name=mode>
		<input type=hidden name=code value="<?=$code?>">
		<input type=hidden name=productcode>
		<input type=hidden name=ordertype value="<?=$ordertype?>">
	</form>
</table>

<? include ($Dir."lib/bottom.php") ?>

<?=$onload?>

</BODY>
</HTML>