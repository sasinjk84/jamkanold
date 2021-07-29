<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/admin_more.php");

if(strlen($_ShopInfo->getId())==0){
	echo "<script>alert('정상적인 경로로 접근하시기 바랍니다.');window.close();</script>";
	exit;
}

$vender=$_REQUEST["vender"];
//$type="give";
if(strlen($vender)==0) {
	echo "<html><head></head><body onload=\"alert('해당 입점업체가 존재하지 않습니다.');window.close();\"></body></html>";exit;
}

$mode=$_POST["mode"];

$arr_sel_vender = explode("::",$sel_vender);
$take_vender = $arr_sel_vender[0];


//위탁계약 승인여부
if($mode=="change"){
	
	$sql = "SELECT p.pridx FROM tblproduct p ";
	$sql.= "left join rent_product rp on p.pridx=rp.pridx ";

	if($type=="take"){//받은위탁인 경우 
		$sql.= "WHERE rp.trust_vender='".$vender."' ";
		$sql.= "AND p.vender='".$take_vender."'";
	}else{
		$sql.= "WHERE rp.trust_vender='".$take_vender."' ";
	}

	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)){
		$sql = "UPDATE tblproduct SET ";
		$sql.= "display	= 'N' ";
		$sql.= "WHERE pridx='".$row->pridx."'";
		mysql_query($sql,get_db_conn());
	}
	//echo "<script>alert(\"위탁상품의 노출이 중지되었습니다.\");parent.location.reload();</script>";
	echo "<script>alert(\"위탁상품의 노출이 중지되었습니다.\");comChange('".$sel_vender."');</script>";
//	exit;
}



//위탁계약 취소
if($mode=="cancel"){
	
	$sql = "SELECT p.pridx FROM tblproduct p ";
	$sql.= "left join rent_product rp on p.pridx=rp.pridx ";

	if($type=="take"){//받은위탁인 경우 
		$sql.= "WHERE rp.trust_vender='".$vender."' ";
		$sql.= "AND p.vender='".$take_vender."'";
	}else{
		$sql.= "WHERE rp.trust_vender='".$take_vender."' ";
	}


	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)){
		$sql = "UPDATE tblproduct SET ";
		$sql.= "display	= 'N' ";
		$sql.= "WHERE pridx='".$row->pridx."'";
		mysql_query($sql,get_db_conn());
/*
		$sql = "UPDATE rent_product SET ";
		$sql.= "istrust	= '1' ";
		$sql.= "WHERE pridx='".$row->pridx."'";
*/
		mysql_query($sql,get_db_conn());
	}
	
	//계약철회
	$sql = "UPDATE tbltrustagree SET ";
	$sql.= "approve	= 'C' ";
	$sql.= "WHERE ta_idx='".$ta_idx."'";
	mysql_query($sql,get_db_conn());

	$sql = "UPDATE tbltrustcancel SET ";
	$sql.= "cancel_agree	= 'Y', ";
	$sql.= "status			= '3' ";
	$sql.= "WHERE ta_idx='".$ta_idx."'";

	if($update = mysql_query($sql,get_db_conn())){
		//echo "<script>alert(\"위탁계약이 종료되었으며, 등록된 위탁상품의 노출이 중지되었습니다.\");parent.location.reload();</script>";
		echo "<script>alert(\"위탁계약이 종료되었으며, 등록된 위탁상품의 노출이 중지되었습니다.\");comChange('".$sel_vender."');</script>";
		//exit;
	}

}else{
	
	/*
	if($ta_idx){
		//$where="ta.ta_idx='".$ta_idx."' ";
	}else{
		//$where = "(ta.give_vender=tm.vender OR ta.take_vender=tm.vender) ";
		if($type=="give"){	//보낸위탁 : 받은위탁업체정보가져오기
			$where = "ta.take_vender=tm.vender ";
		}else{ //받은위탁
			$where = "ta.give_vender=tm.vender ";
		}
	}
	*/
	
	
	
	if($sel_vender){
		$arr_sel_vender = explode("::",$sel_vender);
		if($arr_sel_vender[1]=="give"){	//보낸위탁 : 받은위탁업체정보가져오기
			$where2 = "AND ta.take_vender=".$arr_sel_vender[0];
			$type = "give";
			$vender_sql = $arr_sel_vender[0];
		}else{ //받은위탁
			$where2 = "AND ta.give_vender=".$arr_sel_vender[0];
			$type = "take";
			$vender_sql = $vender;
		}		
	
		$where = "(ta.give_vender=tm.vender OR ta.take_vender=tm.vender) ";

		$sql = "SELECT tm.product_commi,ta.ta_idx,ta.take_vender,ta.regdate as rDate FROM tbltrustmanage tm ";
		$sql.= "left join tbltrustagree ta on ".$where;
		$sql.= "WHERE tm.vender='".$vender_sql."' ";
		$sql.= "AND ta.approve<>'R'";
		$sql.= $where2;

		$result=mysql_query($sql,get_db_conn());
		$data=mysql_fetch_object($result);


		//$take_vender = $data->take_vender;
	}
}
?>
<!DOCTYPE html>

<html>
<head>
<title>위탁계약관리</title>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index,nofollow">
<meta http-equiv="X-UA-Compatible" content="IE=edge">



<style type="text/css">
body,input {font-family: "Nanum Gothic", sans-serif; font-size: 12px;}

section {margin-right:20px;margin-left:20px;font-family:돋음;color:333333;line-height:14pt;}
article {width:100%;margin-top:20px;}

.top1_1 {float:left;width:150px;}
.top1_1 select {width:120px;font-size:12px}
.top1_2 {float:left;width:300px;vertical-align:top;}
.top1_2 h4 {text-align:center}
.top1_3 {float:left;width:250px;}
.top1_3 ul li {height:30px;}
.top1_3 span {display: inline-block; }

.top1_3 .sub1 {width:100px;font-weight:bold}
.top1_3 .sub2 {width:60px;text-align:center;}

.grayBox {background:#ffffff; border:1px solid #d9d9d9; color:#999999}
.clear {clear:both;}

.prDesc span {display: inline-block; vertical-align:top}
.prDesc ul {padding-left:20px;}
.prDesc ul li {float:right;margin-bottom:5px;list-style:none;}
.prDesc .desc1 li:first-child {float:left;}
.prDesc .sub {width:60px;}
.prDesc .unit {width:90%;}
.prDesc .unit_big {width:80%;height:70px}
.prDesc .unit_small {width:60px;}
.prDesc .unit_small2 {width:90px;}
.prDesc .unit_small2 select {width:90px}
.prDesc .imgList dd {padding-bottom:5px;}

.unit_big { width:300px;height:170px;background:#efefef; border:1px solid #d9d9d9;display: inline-block;}


.txtBox {padding-left:5px;background:#efefef; border:1px solid #d9d9d9;display: inline-block;}
.prDesc .second {float:right; width:70%; top:0px;}
.prDesc .txt {margin:10px;color:#ff0000}

.cancelForm {width:90%;margin-left:50px;margin-bottom:5px;margin-top:30px;padding:5px;border:1px solid #666666;}
.cancelForm .sub {width:60px;vertical-align:top;}
.cancelForm h4 {text-align:center;margin-top:-20px}
.cancelTitle {text-align:center;line-height:30px;width:200px;height:30px;background:#efefef;border:1px solid #666666;color:#666666}

.applyBtn {text-align:center;padding-top:5px;}
.applyBtn .redBtn {width:300px; margin-bottom:10px; padding:5px;color:#ff0000; font-weight:bold; border:1px solid #ff0000}
.applyBtn .okBtn {width:250px; margin-bottom:10px; padding:5px;}

</style>

<script type="text/javascript" src="lib.js.php"></script>
<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script language="JavaScript">
$(function(){

	$('#trustStop').click(function(){
		if(confirm("위탁계약으로 등록된 위탁상품의노출이 일괄 중지됩니다. 중지하시겠습니까?")){
			document.cancelForm.submit();
		}
	});

	$('#trustCancel').click(function(){
		if(confirm("위탁계약이 철회되어 이후부터는 위탁상품등록이 불가능하며 이미 등록된 위탁상품의 노출도 일괄 중지됩니다. 철회하시겠습니까?")){
			document.cancelForm.mode.value="cancel";
			document.cancelForm.submit();
		}
	});

})

function comChange(val){
	document.cancelForm.mode.value = "";
	//document.cancelForm.ta_idx.value = val;
	document.cancelForm.sel_vender.value = val;
	document.cancelForm.submit();
}
</script>
</head>
<!--
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" style="overflow-x:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false" onLoad="PageResize();">
-->
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0>

<section>
	<article class="top">
		<div class="top1_1">
			<?
			$sql2 = "SELECT ta.ta_idx,ta.give_vender,ta.take_vender,ta.approve FROM tbltrustagree ta ";
			$sql2.= "WHERE (ta.give_vender='".$vender."' OR ta.take_vender='".$vender."') ";
			$sql2.= "AND ta.approve='Y' ";

			$res2=mysql_query($sql2,get_db_conn());
			?>
			<select name="sel_vender" onchange="javascript:comChange(this.options[this.selectedIndex].value)">
			<option value="">위탁계약업체선택</option>
			<?
			while($row2=mysql_fetch_object($res2)){
				if($vender==$row2->take_vender){//받은위탁,보낸위탁업체 정보가져오기 give_vender
					$vender_gubun = "(받은위탁) ";
					$vener_idx = $row2->give_vender;
					$search_vender = $row2->give_vender."::take";
				}else{	//보낸위탁,받은위탁업체 정보가져오기 take_vender
					$vender_gubun = "(보낸위탁) ";
					$vener_idx = $row2->take_vender;
					$search_vender = $row2->take_vender."::give";
				}

				$sql2_ = "SELECT com_name FROM tblvenderinfo WHERE vender='".$vener_idx."'";
				$res2_=mysql_query($sql2_,get_db_conn());
				$data2_=mysql_fetch_object($res2_);
			?>
			<option value="<?=$search_vender?>" <?=$search_vender==$sel_vender? "selected":"";?>><?=$vender_gubun.$data2_->com_name?></option>
			<?
			}
			?>
			</select>

		</div>
		<? if($sel_vender){ ?>
		<div class="top1_2">
			<h4>위탁관리 수수료</h4>
			<span>
				위탁계약 수수료는 업체간의 계약임으로 가급적 수정하지 않아야 합니다.
				수수료를 수정하더라도 기존에 등록된 위탁상품에는 영향을 주지 않습니다.
			</span>
		</div>
		<? } ?>
		<div class="top1_3">
			<ul>
				<?
				$arrPr_commi = explode("//",$data->product_commi);
				for($i=0;$i<sizeof($arrPr_commi);$i++){
					$arrCommi[$i] = explode(":",$arrPr_commi[$i]);

					$sql_ = "SELECT code_name FROM tblproductcode ";
					$sql_.= "WHERE codeA='".$arrCommi[$i][0]."' ";
					$sql_.= "AND codeB='000' AND codeC='000' ";
					$sql_.= "AND codeD='000' AND type LIKE 'L%'";
					$cRes=mysql_query($sql_,get_db_conn());
					while($cRow=mysql_fetch_object($cRes)) {
						if($i==0){
							$mainCodeNm = $cRow->code_name;
							$mainCommi = $arrCommi[$i][1]."%";
						}else{
							$codeNm .= $cRow->code_name.",";
						}
						
						if($arrCommi[$i][1]){
							$pr_commi .= "<li><span class='sub1'>".$cRow->code_name."</span> <span class='sub2 grayBox'>".$arrCommi[$i][1]."%</span></li>";
						}
					}
					mysql_free_result($cRow);
				}

				?>
				<?=$pr_commi?>
			</ul>
		</div>
		<div class="clear"></div>
	</article>
	<article class="prDesc">
		<?
		$sql = "SELECT * FROM tbltrustitem ";
		$sql.= "WHERE ta_idx='".$data->ta_idx."' ";
		$sql.= "ORDER BY ti_idx ASC ";
		$result=mysql_query($sql,get_db_conn());
		while($row=mysql_fetch_object($result)) {
			//품목이름 가져오기
			$sql2 = "SELECT code_name FROM tblproductcode ";
			$sql2.= "WHERE codeA='".$row->codeA."' ";
			$result2=mysql_query($sql2,get_db_conn());
			$row2=mysql_fetch_object($result2);

			$row->item_photo = substr($row->item_photo,0,-3);
			$arrItemPhoto = explode(" | ",$row->item_photo);
		?>
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td></td>
				<td></td>
			</tr>
		</table>
		<ul class="desc1">
			<li style="float:left;">
				<span class="sub" style="width:60px;display: inline-block; vertical-align:top">품목</span>
				<span class="unit_small2 txtBox" style="width:80px;height:23px;padding-left:5px;background:#efefef; border:1px solid #d9d9d9;display: inline-block; vertical-align:top"><?=$row2->code_name?></span>
			</li>	
			<li class="second" style="float:right;margin-bottom:5px; width:70%; top:0px;">
				<span class="sub" style="width:60px;display: inline-block; vertical-align:top">수량</span>
				<span class="unit_small txtBox" style="width:90px;height:23px;padding-left:5px;background:#efefef; border:1px solid #d9d9d9;display: inline-block;"><?=$row->item_amount?></span>
			</li>
		</ul>
		<div style="clear:both;"></div>
		<ul class="desc2">
			<li>
				<span class="sub" style="width:60px;display: inline-block; vertical-align:top">특징</span>
				<span class="unit_big txtBox"  style="width:600px;height:80px;background:#efefef; border:1px solid #d9d9d9;display: inline-block;"><?=nl2br($row->item_desc)?></span>
			</li>
			<li>
				<span class="sub" style="width:60px;display: inline-block; vertical-align:top">사진</span>
				<span class="unit">
					<b>첨부파일</b> 
					<?
					for($i=0;$i<sizeof($arrItemPhoto);$i++){
						echo "<a href='../vender/trust_download.php?dir=/data/trust_item/&file_name=".$arrItemPhoto[$i]."'>".$arrItemPhoto[$i]."</a>";
						if($i<>sizeof($arrItemPhoto)-1){
							echo " | ";
						}
					}
					?>
				</span>
			</li>
		</ul>
		<?
		}

		$sql = "SELECT * FROM tbltrustcancel WHERE ta_idx= '".$data->ta_idx."' ";
		$cRes=mysql_query($sql,get_db_conn());
		$cancelCnt = mysql_num_rows($cRes);
		if($cancelCnt>0){
			$cData=mysql_fetch_object($cRes);
		?>
		<div class="cancelForm">
			<h4><span class="cancelTitle">위탁계약 철회신청 내용</span></h4>
			<ul>
				<li>
					<span class="sub">사유</span> 
					<span class="unit_big txtBox"><?=nl2br($cData->cancel_reason)?></span>
				</li>
				<li>
					<span class="sub">첨부</span> 
					<span>
						<a href="../vender/trust_download.php?dir=/data/trust_cancel/&file_name=<?=$cData->cancel_doc?>"><?=$cData->cancel_doc?></a>
					</span>
				</li>
			</ul>
			<? if($type=="take"){ //받은 위탁인 경우: 보낸위탁업체에서 위탁계약철회 요청이 있는경우 ?>
			<div class="applyBtn">
				<button type="button" class="okBtn" id="trustStop">위탁상품 노출중지</button>
				<button type="button" class="redBtn" id="trustCancel">위탁관리 계약철회</button>
				<div class="txt">
					본사의 위탁관리 계약철회는 되돌릴 수 없으니, 위탁상품 회수를 확인한 후 신중하게 진행하세요.
				</div>
			</div>
			<? } ?>
		</div>
		<div class="clear"></div>
		<?
		}
		?>
	</article>

</section>


<form name="cancelForm" id="cancelForm" method="post" action="<?=$_SERVER[PHP_SELF]?>">
<input type="hidden" name="mode" value="change">
<input type="hidden" name="ta_idx" value="<?=$data->ta_idx?>">
<input type="hidden" name="vender" value="<?=$vender?>">
<input type="hidden" name="sel_vender" value="<?=$sel_vender?>">
<input type="hidden" name="type" value="<?=$type?>">
<input type="hidden" name="approve">
</form>

</body>
</html>
<script language="JavaScript">
var upload = document.querySelectorAll("input[name='upload']");
var sel_files = [];
	
$(document).on("change","input[name='upload']",function(e){
	var get_file = e.target.files;

	if(get_file[0].size > 5 * 1024 * 1024){
		alert('5MB 이상의 파일은 업로드할 수 없습니다.');
		return;
	}

	$("#preview").append(get_file[0].name);
})


function fileUploadAction() {
	console.log("fileUploadAction");
	$("#upload").trigger('click');
}

</script>