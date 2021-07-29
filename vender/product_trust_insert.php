<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$mode=$_POST["mode"];
$prcodes=$_POST["prcodes"];
$code=$_POST["code"];
$disptype=$_POST["disptype"];
$rentaltype=$_POST["rentaltype"];
$soldout=$_POST["soldout"];
$s_check=$_POST["s_check"];
if(strlen($s_check)==0) $s_check="name";
$search=ltrim($_POST["search"]);


$qry = "WHERE 1=1 ";
if(strlen($code)>=3) {
	$qry.= "AND p.productcode LIKE '".$code."%' ";
}
$qry.= "AND (p.vender='".$_VenderInfo->getVidx()."' ";

//보낸위탁,받은위탁상품 가져오기
if($gubun=="me"){
	$qry.= "AND r.istrust='1') ";
}else if($gubun=="take"){//받은위탁
	$qry.= "OR r.trust_vender='".$_VenderInfo->getVidx()."') ";	
}else if($gubun=="give"){//보낸위탁
	$qry.= "AND r.trust_vender<>'".$_VenderInfo->getVidx()."') ";
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
		$qry.= "AND r.istrust='1'";
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


if(strlen($search)>0) {
	if($s_check=="name") $qry.= "AND p.productname LIKE '%".$search."%' ";
	else if($s_check=="code") $qry.= "AND p.productcode='".$search."' ";
}

if($insert_mode=="trust" && strlen($prcodes)>0) {
	$prcodes=substr($prcodes,0,-1);
	$prcodelist=ereg_replace(',','\',\'',$prcodes);

	$sql = "SELECT * FROM tblproduct WHERE productcode IN ('".$prcodelist."') AND vender='".$_VenderInfo->getVidx()."' ";
	$sql.= "ORDER BY productcode";
	$result = mysql_query($sql,get_db_conn());
	
	while ($row=mysql_fetch_object($result)) {
		
		if($row->rental=="2"){

			$code=substr($row->productcode,0,12);
			$codeA=substr($row->productcode,0,3);
			$commi = rentCommitionByCategory($code,$_VenderInfo->getVidx());	
			$rentProductValue = array();
			$rentProductValue['pridx'] = $row->pridx;
			$rentProductValue['istrust'] = "0";
			//$rentProductValue['maincommi'] = $commi['main'];
			$rentProductValue['maincommi'] = "0";
			$rentProductValue['trust_vender'] = $_POST["trust_vender"];	

			//위탁업체 수수료가져오기
			$sql = "SELECT ta.ta_idx,tm.product_commi FROM tbltrustagree ta ";
			$sql.= "left join tbltrustmanage tm on tm.vender=ta.take_vender ";
			$sql.= "WHERE (ta.take_vender='".$_VenderInfo->getVidx()."' OR ta.give_vender='".$_VenderInfo->getVidx()."') ";
			$sql.= "AND (ta.take_vender='".$_POST["trust_vender"]."' OR ta.give_vender='".$_POST["trust_vender"]."') ";
			$sql.= "AND (ta.approve='Y' OR ta.approve='N')";
			
			$res=mysql_query($sql,get_db_conn());
			$rw=mysql_fetch_object($res);

			$arrPr_commi = explode("//",$rw->product_commi);
			for($i=0;$i<sizeof($arrPr_commi);$i++){
				$arrCommi[$i] = explode(":",$arrPr_commi[$i]);
				
				if($codeA==$arrCommi[$i][0]){
					$rentProductValue['maincommi'] = $arrCommi[$i][1];
				}
			}
			
			if($rentProductValue['maincommi'] == "0"){
				echo "<script>alert(\"위탁신청한 상품의 카테고리와 위탁 카테고리가 일치하지 않는 상품이 있습니다.\");history.back();</script>";
				exit;
			}else{
				$rentProductResult = rentProductSave( $rentProductValue );
			}
		}
	}
	mysql_free_result($result);

	echo "<script>alert(\"관리업체로 상품 위탁이 정상적으로 신청됐습니다.\\n\\n관리업체의 승인 시 위탁이 완료됩니다.\");opener.pageForm.submit();window.close();</script>";
	exit;
}

if($insert_mode=="trustall") {

	$sql = "SELECT p.pridx FROM tblproduct p left join rent_product r on p.pridx=r.pridx ".$qry;
	$sql.= " ORDER BY productcode";


	$result = mysql_query($sql,get_db_conn());

	while ($row=mysql_fetch_object($result)) {
		
		if($row->rental=="2"){
			$code=substr($row->productcode,0,12);
			$commi = rentCommitionByCategory($code,$_VenderInfo->getVidx());
			$rentProductValue = array();
			$rentProductValue['pridx'] = $row->pridx;
			$rentProductValue['istrust'] = "0";
			$rentProductValue['maincommi'] = $commi['main'];

			$rentProductValue['trust_vender'] = $_POST["trust_vender"];	

			$rentProductResult = rentProductSave( $rentProductValue );
		}
	}
	mysql_free_result($result);

	echo "<script>alert(\"관리업체로 상품 위탁이 정상적으로 신청됐습니다.\\n\\n관리업체의 승인 시 위탁이 완료됩니다.\");opener.pageForm.submit();window.close();</script>";
	exit;
}
?>

<html>
<head>
<title>위탁관리 업체선택</title>
<!--META content="IE=5" http-equiv="X-UA-Compatible"-->
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index,nofollow">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<!--[if lt lE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js">
</script>
<![endif]-->
<link rel="stylesheet" href="/admin/style.css">
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="lib.js.php"></script>
<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {    

	$('#trust_applyBtn').click(function(){
		if($("select[name='trust_vender']").val()==null){
			alert("입점업체를 선택하세요");
			$("select[name='trust_vender']").focus();
			return false;
		}else{
			document.cancelForm.action = "<?=$PHP_SELF?>";
		//	document.cancelForm.submit();
		}
	});

});
</script>
</head>

<body topmargin=0 leftmargin=0  marginheight=0 marginwidth=0>

	
<section>
	<article class="applyForm">
		<h4 class="txt">위탁관리 업체선택</h4>
		<div>※ 선택하신 상품을 위탁할 업체를 선택하세요.</div>
		<form name="applyForm" id="applyForm" method="post">
		<input type="hidden" name="insert_mode" value="<?=$mode?>">
		<input type="hidden" name="vender" value="<?=$_VenderInfo->getVidx()?>">
		
		<input type="hidden" name="prcodes" value="<?=$prcodes?>">
		<input type="hidden" name="gubun" value="<?=$gubun?>">
		<input type="hidden" name="gubun_vender" value="<?=$gubun_vender?>">
		<input type="hidden" name="disptype" value="<?=$disptypeArr?>">
		<input type="hidden" name="rentaltype" value="<?=$rentaltypeArr?>">
		<input type="hidden" name="soldout" value="<?=$soldout?>">
		<input type="hidden" name="search" value="<?=$search?>">
		<fieldset>
			<legend>위탁관리 업체선택</legend>
			
			<select name="trust_vender"  multiple style="width:100%;height:200px">
			<?
			$sql = "SELECT * FROM tbltrustagree WHERE (take_vender='".$_VenderInfo->getVidx()."' OR give_vender='".$_VenderInfo->getVidx()."') AND approve='Y'";
			$result=mysql_query($sql,get_db_conn());
			while($row=mysql_fetch_object($result)) {

				if($_VenderInfo->getVidx()==$row->take_vender){//해당업체가 받은업체인 경우
					$trust_vender = $row->give_vender;
					$selected = $_data->vender==$trust_vender? "selected":"";
				}else{
					$trust_vender = $row->take_vender;
					$selected = $rentProduct['trust_vender']==$trust_vender? "selected":"";
				}

				$sql = "SELECT * FROM tblvenderinfo WHERE vender='".$trust_vender."' ";
				$tRes=mysql_query($sql,get_db_conn());
				$tData=mysql_fetch_object($tRes);
			?>
			<option value="<?=$trust_vender?>"><?=$tData->com_name?></option>
			<?
			}
			?>
			</select>
			<div class="applyBtn">
				<button type="text" class="okBtn" id="trust_applyBtn">위탁관리 업체선택</button>
			</div>
		</fieldset>
		</form>
	</article>

</section>

</body>
</html>