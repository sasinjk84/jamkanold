<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

$mode=$_POST["mode"];
$itemfileURL = "/data/trust_item/";

//위탁계약 승인여부
if($mode=="change"){
	$sql = "UPDATE tbltrustagree SET ";
	$sql.= "approve	= '".$approve."' ";
	if($approve=="Y"){
		$sql.= ",contract_date	= now() ";
	}
	$sql.= "WHERE ta_idx='".$ta_idx."' ";
	if($update = mysql_query($sql,get_db_conn())){
		if($approve=="Y"){ //승인
			//sms 보내기$data->p_mobile
			
			//이메일 보내기$data->p_email

			$message = "위탁계약신청을 승인하였습니다.";
		}else{
			$message = "위탁계약신청을 거절하였습니다.";
		}

		echo "<script>alert(\"".$message."\");document.location.href='trust_list.php';</script>";
		exit;
	}
}

//계약체결전 취소시 삭제
if($mode=="delete"){

	//업로드된 파일 삭제
	$sql = "SELECT item_photo FROM tbltrustitem ";
	$sql.= "WHERE ta_idx='".$ta_idx."' ";
	$sql.= "ORDER BY ti_idx ASC ";
	$result=mysql_query($sql,get_db_conn());
	while($row=mysql_fetch_object($result)) {
		$row->item_photo = substr($row->item_photo,0,-3);
		$arrItemPhoto = explode(" | ",$row->item_photo);
		for($i=0;$i<sizeof($arrItemPhoto);$i++){
			@unlink($itemfileURL.$arrItemPhoto[$i]);
		}
	}

	$sql = "DELETE FROM tbltrustitem WHERE ta_idx='".$ta_idx."' ";
	mysql_query($sql,get_db_conn());

	$sql = "DELETE FROM tbltrustcancel WHERE ta_idx='".$ta_idx."' ";
	mysql_query($sql,get_db_conn());
	
	$sql = "DELETE FROM tbltrustagree WHERE ta_idx='".$ta_idx."' ";
	mysql_query($sql,get_db_conn());
	echo "<script>alert(\"위탁계약신청이 취소/삭제되었습니다.\");document.location.href='trust_list.php';</script>";
	exit;

}

if($mode=="cancel_agree"){
	$sql = "UPDATE tbltrustcancel SET ";
	$sql.= "cancel_agree	= '".$cancel_agree."' ";
	$sql.= "WHERE ta_idx='".$ta_idx."'";
	mysql_query($sql,get_db_conn());
	
	if($cancel_agree=="Y"){
		$agree = "동의";
	}else{
		$agree = "거절";
	}
	echo "<script>alert(\"위탁계약철회신청에 ".$agree."되었습니다.\");document.location.href='trust_view.php?type=".$type."&ta_idx=".$ta_idx."';</script>";
	exit;
}

//위탁계약체결후 취소시 취소사유작성
if($mode=="cancel"){

	$approve        = "N";
	$cancel_reason  = $_POST["cancel_reason"];
	
	#첨부파일
	if(!_empty($_FILES['upload']['name'])){
		$cancelfileURL = $_SERVER['DOCUMENT_ROOT']."/data/trust_cancel/";

		$exte = explode(".",$_FILES['upload']['name']);
		$exte = $exte[ count($exte)-1 ];
		$cencelfile_name = "cancel_".date("YmdHis").".".$exte;
		
		move_uploaded_file($_FILES['upload']['tmp_name'],$cancelfileURL.$cencelfile_name);
	}

	$sql = "INSERT tbltrustcancel SET ";
	$sql.= "ta_idx			= '".$ta_idx."', ";
	$sql.= "cancel_reason	= '".$cancel_reason."', ";
	if(strlen($cencelfile_name)>0){
		$sql.= "cancel_doc		= '".$cencelfile_name."', ";
	}
	$sql.= "cancel_date		= now(), ";
	$sql.= "status			= '0' ";

	if($insert = mysql_query($sql,get_db_conn())){
		echo "<script>alert(\"위탁계약 철회신청이 완료되었습니다.\");location.href='trust_list.php';</script>";
		exit;
	}

}else{

	if($type=="give"){	//보낸위탁 : 받은위탁업체정보가져오기
		$title = "보낸위탁";

		$sql = "SELECT tm.product_commi,vi.adjust_lastday,ta.ta_idx,ta.approve,ta.approve_check,ta.regdate as rDate,v.*,tm.store_addr ";
		$sql.= "FROM tbltrustagree ta ";
		$sql.= "left join tbltrustmanage tm on ta.take_vender=tm.vender ";
		$sql.= "left join tblvenderinfo v on v.vender=ta.take_vender ";
		$sql.= "left join vender_more_info vi on vi.vender=ta.take_vender ";
		$sql.= "WHERE ta_idx='".$ta_idx."' ";
	}else{ //받은위탁
		$title = "받은위탁";

		$sql = "SELECT tm.product_commi,vi.adjust_lastday,ta.ta_idx,ta.approve,ta.approve_check,ta.regdate as rDate,v.*,tm.store_addr ";
		$sql.= "FROM tbltrustagree ta ";
		$sql.= "left join tbltrustmanage tm on ta.take_vender=tm.vender ";
		$sql.= "left join tblvenderinfo v on v.vender=ta.give_vender ";
		$sql.= "left join vender_more_info vi on vi.vender=ta.take_vender ";
		$sql.= "WHERE ta_idx='".$ta_idx."' ";
	}

	$result=mysql_query($sql,get_db_conn());

	$data=mysql_fetch_object($result);

	switch($data->adjust_lastday) {
		case 0 : $account_date = "매월 ".$data->account_date."일";
			break;
		case 1 : $account_date = "매월 마지막일";
			break;
		case 2 : $account_date = "매월 15일과 마지막일";
			break;
	}

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

			$pr_commi .= $cRow->code_name."(".$arrCommi[$i][1]."%),";
		}
		mysql_free_result($cRow);
	}

	//승인여부 확인체크하기
	if($type=="give" && $data->approve_check=="N"){
		$sql = "UPDATE tbltrustagree SET ";
		$sql.= "approve_check	= 'Y' ";
		$sql.= "WHERE ta_idx='".$ta_idx."' AND approve<>'N' ";
		mysql_query($sql,get_db_conn());
	}

}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>

<script language="JavaScript">
$(function(){
	$('#agree_y').click(function() {
		$('#cancelSubmit').removeAttr("disabled");
	});
	$('#agree_n').click(function() {
		$('#cancelSubmit').attr("disabled", "disabled");
	});

	$('#trustCancel').click(function(){ 
		<? if($data->approve=="N"){ ?>
			if(confirm("위탁계약신청을 취소하시겠습니까?")){
				document.appForm.mode.value="delete";
				document.appForm.submit();
			}
		<? }else{ ?>
			$('.txt').hide();
			$('.cancelForm').show();
		<? } ?>
	});
	
	$('#cancelSubmit').click(function(){
		if($('#cancel_reason').val()==""){
			alert("취소사유를 입력해주세요");
			$('#cancel_reason').focus();
			return false;
		}else if($('#upload').val()==""){
			alert("증빙서류를 업로드해주세요");
			return false;
		}else{
			if(confirm("위탁계약취소 및 철회하시겠습니까?")){
				document.cancelForm.submit();
			}
		}
	});

	$('#cancelBack').click(function(){ 
		$('.txt').show();
		$('.cancelForm').hide();
	});

	$('#approve').click(function(){ 
		$('input[name=approve]').val('Y');
		$('#appForm').submit();
	});

	$('#refuse').click(function(){ 
		$('input[name=approve]').val('R');
		$('#appForm').submit();
	});

	$('#delete').click(function(){ 
		if(confirm("철회된 위탁계약을 삭제하시겠습니까?")){
			document.appForm.mode.value="delete";
			document.appForm.submit();
		}
	});


	$('#cancel_agree_y').click(function(){
		if(confirm("위탁계약철회요청에 동의하시겠습니까?")){
			document.cancelForm.mode.value = "cancel_agree";
			document.cancelForm.cancel_agree.value = "Y";
			document.cancelForm.submit();
		}
	});

	$('#cancel_agree_n').click(function(){
		if(confirm("위탁계약철회요청에 거절하시겠습니까?")){
			document.cancelForm.mode.value = "cancel_agree";
			document.cancelForm.cancel_agree.value = "N";
			document.cancelForm.submit();
		}
	});

})
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
	
		<section>
			<article class="vTitle">
				<h2>위탁계약 관리</h2>
				<div class="vTitle_bgimg"></div>
				<ul>
					<li class="notice_gray"><img src="images/icon_dot02.gif" border=0 hspace="4">잠깐닷컴의 위탁계약을 관리할 수 있는 페이지입니다.</li>
					<li class="notice_gray"><img src="images/icon_dot02.gif" border=0 hspace="4">보유상품의 직접관리가 어려울 경우 품목별 전문 위탁업체를 선택하여 관리 받을 수 있습니다.</li>
				</ul>
			</article>
			<article class="trList">
				<h4>위탁계약 업체정보</h4>
				<table>
					<tr>
						<th scope="col"><?=$title?><br><?=str_replace("-",".",$data->rDate)?></th>
						<td class="txtBold"><?=$data->com_name?></td>
						<td><?=$mainCodeNm?></td>
						<td><?=$mainCommi?></td>
						<td><?=$account_date?></td>
						<td class="addr"><?=$data->store_addr?></td>
						<td><button type="button" onclick="document.location.href='trust_list.php'">돌아가기</button></td>
					</tr>
				</table>
				<div class="trDesc">
					<ul>
						<li>
							<span class="sub">대표자명</span>
							<span class="unit"><?=$data->com_owner?></span>
						</li>	
						<li>
							<span class="sub">상호(회사명)</span>
							<span class="unit"><?=$data->com_name?></span>
						</li>	
						<li>
							<span class="sub">사업자등록번호</span>
							<span class="unit"><?=$data->com_num?></span>
						</li>
						<li>
							<span class="sub">사업장 소재지</span>
							<span class="unit"><?=$data->com_addr?></span>
						</li>
						<li>
							<span class="sub">전화번호</span>
							<span class="unit"><?=$data->com_tel?></span>
						</li>
						<li>
							<span class="sub">팩스번호</span>
							<span class="unit"><?=$data->com_fax?></span>
						</li>
					</ul>
					<ul>
						<li>
							<span class="sub">담당자 이름</span>
							<span class="unit"><?=$data->p_name?></span>
						</li>	
						<li>
							<span class="sub">담당자 연락처</span>
							<span class="unit"><?=$data->p_mobile?></span>
						</li>	
						<li>
							<span class="sub">이메일 주소</span>
							<span class="unit"><?=$data->p_email?></span>
						</li>
						<li>
							<span class="sub">부서명</span>
							<span class="unit"><?=$data->p_buseo?></span>
						</li>
						<li>
							<span class="sub">직위</span>
							<span class="unit"><?=$data->p_level?></span>
						</li>
					</ul>
				</div>
				<div class="trDesc2">
					<h4>위탁관리 계약사항</h4>
					<ul>
						<li><?=$pr_commi?></li>
					</ul>
				</div>
			</article>
			<article class="prDesc">
				<h4>위탁품목 상세정보</h4>
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
				<ul class="desc1">
					<li>
						<span class="sub">품목</span>
						<span class="unit_small2 txtBox"><?=$row2->code_name?></span>
					</li>	
					<li class="second">
						<span class="sub">수량</span>
						<span class="unit_small txtBox"><?=$row->item_amount?></span>
					</li>
				</ul>
				<div class="clear"></div>
				<ul class="desc2">
					<li>
						<span class="sub">특징</span>
						<span class="unit_big txtBox"><?=nl2br($row->item_desc)?></span>
					</li>
					<li>
						<span class="sub">사진</span>
						<span class="unit">
							<b>첨부파일</b> 
							<?
							for($i=0;$i<sizeof($arrItemPhoto);$i++){
								echo "<a href='trust_download.php?dir=".$itemfileURL."&file_name=".$arrItemPhoto[$i]."'>".$arrItemPhoto[$i]."</a>";
								if($i<>sizeof($arrItemPhoto)-1){
									echo " | ";
								}
							}
							?>
							<dl class="imgList">
								<?								
								for($i=0;$i<sizeof($arrItemPhoto);$i++){
								?>
								<dd><img src="<?=$itemfileURL.$arrItemPhoto[$i]?>" width="300" height="50"></dd>
								<?
								}
								?>
							</dl>
						</span>
					</li>
				</ul>
				<?
				}
				?>
				<div class="applyBtn">
					<? if($type=="take"){ //받은 위탁 ?>
						<? if($data->approve=="N"){?>
						<button type="button" class="okBtn" id="approve">위탁계약승인하기</button>
						<button type="button" class="noBtn" id="refuse">위탁계약거절하기</button>
						<? }?>
					<? }else{ ?>
						<button type="button" class="noBtn" id="trustCancel">위탁계약취소/철회</button>
					<? } ?>
				</div>
				<div class="txt">
					<? if($type=="take"){ //받은 위탁 ?>
						<? if($data->approve=="N"){?>
						※ 위탁계약을 2주일 간 승인하지 않으면 관리 신청이 자동으로 취소됩니다.
						<? }?>
					<? }else{ ?>
						※ 위탁신청의 취소 혹은 위탁관리계약의 철회를 신청할 수 있습니다. 위탁계약 취소접수 후 계약업체의 동의 시 위탁상품의 노출은 중지됩니다.<br> 취소접수 후 위탁보낸 상품을 돌려받고 잠깐닷컴 운영자에게 신고하면 매출 수수료를 정산받고 위탁관리 계약이 완전히 종료됩니다.
					<? } ?>
				</div>

				<?
				$sql = "SELECT * FROM tbltrustcancel WHERE ta_idx= '".$ta_idx."' ";
				$cRes=mysql_query($sql,get_db_conn());
				$cancelCnt = mysql_num_rows($cRes);
				$cData=mysql_fetch_object($cRes);
				
				if($cancelCnt>0){
					echo "<script>
					$(function(){
						$('.cancelForm').show();
					})
					</script>";
				}
				?>
				<form name="cancelForm" id="cancelForm" method="post" action="<?=$PHP_SELF?>" enctype="multipart/form-data">
				<input type="hidden" name="mode" value="cancel">
				<input type="hidden" name="type" value="<?=$type?>">
				<input type="hidden" name="ta_idx" value="<?=$ta_idx?>">
				<input type="hidden" name="cancel_agree" value="Y">
				<fieldset>
					<legend>위탁계약취소/철회</legend>
					<div class="cancelForm">
						<ul>
							<li>
								<span class="sub">사유</span> 
								<span><textarea name="cancel_reason" id="cancel_reason"><?=$cData->cancel_reason?></textarea></span>
							</li>
							<li>
								<span class="sub">첨부</span> 
								<span>
									<button type="button" onclick="fileUploadAction();">파일 올리기</button>
									*취소 및 철회 사유와 관련된 증빙서류 업로드
									<input type="file" name="upload" id="upload" style="display:none">
									<div id="preview" class="preview"></div>
									<?=$cData->cancel_doc?>
								</span>
							</li>
						</ul>
						<?
						//철회신청하기 전인 경우
						if($cancelCnt==0){
						?>
						<ul>
							<li>위탁신청의 취소 혹은 위탁관리계약의 철회를 신청할 수 있습니다.</li>
							<li>위탁계약 취소접수 후 계약업체의 동의 시 위탁상품의 노출은 중지됩니다.</li>
							<li>취소접수 후 위탁보낸 상품을 돌려받고 잠깐닷컴 운영자에게 신고하세요.</li>
							<li>
								<span>운영자 접수 완료 후 지급 기일에 맞춰 미정산 수수료를 지급받고 위탁관리 계약이 완전히 종료됩니다.</span>
								<span><input type="radio" name="agree" id="agree_y" value="Y">동의 <input type="radio" name="agree" id="agree_n" value="N" checked="checked">거절</span>
							</li>
						</ul>
						<div class="applyBtn">
							<button type="button" class="okBtn" id="cancelSubmit" disabled="disabled">신청하기</button>
							<button type="button" class="okBtn" id="cancelBack">돌아가기</button>
						</div>
						<? 
						}else{	
						?>
						<div class="applyBtn">
							<?
							if($type=="take" && $cData->status==0){
								if($cData->cancel_agree==""){
							?>
								위탁계약 업체가 위탁계약철회요청을 하셨습니다.<br>
								동의하시겠습니까?<br>
								<button type="button" class="okBtn" id="cancel_agree_y">동의하기</button>
								<button type="button" class="okBtn" id="cancel_agree_n">거절하기</button>
							<?
								}else if($cData->cancel_agree=="Y"){
									echo "위탁계약 업체의 위탁계약철회요청에 동의하셨습니다.";
								}else if($cData->cancel_agree=="N"){
									echo "위탁계약 업체의 위탁계약철회요청에 거절하셨습니다.";
								}
							}else{
								//처리상태(신청중 0,상품회수중 1,정산지급완료 2,철회완료 3)
								if($cData->status==0) $cancelMsg = "계약철회신청중입니다.";
								if($cData->status==1) $cancelMsg = "상품회수중입니다.";
								if($cData->status==2) $cancelMsg = "정산지급완료되었습니다.";
								if($cData->status==3) $cancelMsg = "계약철회완료되었습니다.";

								if($cData->status==0 && $cData->cancel_agree=="N"){
									echo "위탁계약 업체가 위탁계약철회를 거절했습니다.<br>계약업체와 협의 후 다시 진행해주세요.<br>계약업체의 불합리한 거절에 대해서는 이의신청 접수할 수 있습니다.<br><a href='./shop_counsel.php'>이의신청하러 가기▶<a>";
								}else{
							?>
									<button type="button" class="okBtn"><?=$cancelMsg?></button>
							<?
								}
							}
							?>
							<? if($cData->status==3){ ?>
							<button type="button" class="noBtn" id="delete">계약내용삭제하기</button>
							※ 계약내용을 삭제하셔야 재계약신청 가능합니다.
							<? } ?>
						</div>
						<?
						}
						?>
					</div>
					<div class="clear"></div>
				</fieldset>
				</form>
			</article>

		</section>

	</td>
	<td>



<form name="appForm" id="appForm" method="post" action="<?=$_SERVER[PHP_SELF]?>">
<input type="hidden" name="mode" value="change">
<input type="hidden" name="ta_idx" value="<?=$ta_idx?>">
<input type="hidden" name="approve">
</form>



<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>
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