<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

$tm_idx=$_POST["tm_idx"];
$mode=$_POST["mode"];
$take_vender=$_POST["take_vender"];
$give_vender=$_POST["give_vender"];


if($mode == "insert") {
	
	$sql = "INSERT tbltrustagree SET ";
	$sql.= "take_vender		= '".$take_vender."', ";
	$sql.= "give_vender		= '".$give_vender."', ";
	$sql.= "approve			= 'N', ";
	$sql.= "regdate		= now() ";

	if($insert = mysql_query($sql,get_db_conn())){
		
		$sql_ = "SELECT LAST_INSERT_ID() ";
		$res = mysql_fetch_row(mysql_query($sql_,get_db_conn()));
		$ta_idx = $res[0];
		
		$arrItemPhoto = explode(",",$_POST["arrPhoto"]);
		$start = 0;
		for($i=0;$i<sizeof($_POST["codeA"]);$i++){

			if($i==0){
				$start = $i;
				$end = $_POST["photo_cnt"][$i];
			}else{
				$start = $end;
				$end = $start + $_POST["photo_cnt"][$i];
			}
			for($j=$start;$j<$end;$j++){
				$itemPhoto[$i] .= $arrItemPhoto[$j]." | ";
			}

			$sql2 = "INSERT tbltrustitem SET ";
			$sql2.= "ta_idx			= '".$ta_idx."', ";
			$sql2.= "codeA			= '".$_POST["codeA"][$i]."', ";
			$sql2.= "item_amount	= '".$_POST["item_amount"][$i]."', ";
			$sql2.= "item_desc		= '".$_POST["item_desc"][$i]."', ";
			$sql2.= "item_photo		= '".$itemPhoto[$i]."' ";
			mysql_query($sql2,get_db_conn());
		}

		echo "<script>alert(\"위탁관리 신청이 완료되었습니다. 위탁관리 업체로 신청정보가 전달되며, 2주 내 승인이 없을 경우 위탁 신청이 취소되며 신청정보는 파기됩니다.\");window.close();</script>";
		exit;
	}
}//insert end




$sql = "SELECT * FROM tbltrustmanage tm ";
$sql.= "left join tblvenderinfo v on tm.vender=v.vender ";
$sql.= "left join vender_more_info vi on tm.vender=vi.vender ";
$sql.= "WHERE tm_idx='".$tm_idx."' ";
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


if($data->com_image){
	$comImg = "<img src=".$com_image_url.$data->com_image." width=\"125\" height=\"125\" alt=\"\">";
}else{
	$comImg = "<img src=\"../images/no_img.gif\" width=\"125\" height=\"125\" >";
}

$sql = "SELECT * FROM tbltrustagree WHERE give_vender='".$_VenderInfo->getVidx()."' AND take_vender='".$data->vender."' AND approve<>'R'";
$res=mysql_query($sql,get_db_conn());
$agreeCnt = mysql_num_rows($res);

if($agreeCnt>0){
	echo "<script>alert('이미 신청중이거나 계약중인 업체입니다.');window.close();</script>";
	exit;
}
?>
<html>
<head>
<title>관리자 페이지</title>
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

<script language="JavaScript">
var htmlView = "";
htmlView += "<div class=\"itemInfo\">";
htmlView += "				<ul class=\"desc1\">";
htmlView += "					<li>";
htmlView += "						<span class=\"sub\">품목</span>";
htmlView += "						<span class=\"unit_small2\">";
htmlView += "							<select name=\"codeA[]\">";
<?
$sql_ = "SELECT codeA,code_name,type FROM tblproductcode ";
$sql_.= "WHERE codeB='000' AND codeC='000' ";
$sql_.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
$res_=mysql_query($sql_,get_db_conn());
while($row_=mysql_fetch_object($res_)) {
	$opList = "<option value='".$row_->codeA."'>".$row_->code_name."</option>";	
	echo "htmlView += \"".$opList."\";\n";
}
mysql_free_result($res_);
?>
htmlView += "							</select>";
htmlView += "						</span>";
htmlView += "					</li>	";
htmlView += "					<li class=\"second\">";
htmlView += "						<span class=\"sub\">수량</span>";
htmlView += "						<span class=\"\"><input type=\"text\" name=\"item_amount[]\"></span>";
htmlView += "					</li>";
htmlView += "				</ul>";
htmlView += "				<div class=\"clear\"></div>";
htmlView += "				<ul class=\"desc2\">";
htmlView += "					<li>";
htmlView += "						<span class=\"sub\">특징</span>";
htmlView += "						<span class=\"\"><textarea name=\"item_desc[]\" id=\"\"></textarea></span>";
htmlView += "					</li>";
htmlView += "					<li>";
htmlView += "						<span class=\"sub\">사진</span>";
htmlView += "						<span>";



htmlView2 = "						</span>";
htmlView2 += "					</li>";
htmlView2 += "				</ul>";
htmlView2 += "			</div>";

$(function(){
	$('#agree_y').click(function() {
		$('#trust_applyBtn').removeAttr("disabled");
	});
	$('#agree_n').click(function() {
		$('#trust_applyBtn').attr("disabled", "disabled");
	});

	$('#itemPlus').click(function() {
		var NowCntNodes=eval($(".preview:last").attr('id').replace("preview_",""));
		if(NowCntNodes<1){NowCntNodes=0;}
		var AddCntNodes=NowCntNodes+1;
		
		htmlView_ = "			<button type=\"button\" onclick=\"fileUploadAction('"+AddCntNodes+"');\">파일 올리기</button>";
		htmlView_ += "			<input type=\"file\" name=\"upload\" id=\"upload_"+AddCntNodes+"\" style=\"display:none\">";
		htmlView_ += "			*최대 200MB	이내, 위탁품목의 주요특징이 포함된 고화질 이미지 권장<br>";
		htmlView_ += "			<div id=\"preview_"+AddCntNodes+"\" class=\"preview\"></div>";
		htmlView_ += "			<input type=\"hidden\" name=\"photo_cnt[]\" id=\"photo_cnt_"+AddCntNodes+"\" value=\"0\">";

		$("#itemPlus").before(htmlView+htmlView_+htmlView2);
		
	});
})

function removeMultiimage(el,img){
	$(el).remove();
	document.itemForm.arrPhoto.value = document.itemForm.arrPhoto.value.replace(img,"");
	document.itemForm.arrPhoto.value = document.itemForm.arrPhoto.value.replace(",,",",");
}
</script>


</head>

<body topmargin=0 leftmargin=0  marginheight=0 marginwidth=0>
	
<section>
	<article class="trustInfo">
		<div class="figure">
			<div class="irap"><?=$comImg?></div>
			<div class="figureCaption">
				<ul class="markup">
					<li><?=$data->com_name." | ".$data->id?></li>
					<li>위치:<?=$data->store_addr?></li>
					<li>주품목:<?=$mainCodeNm?></li>
					<li>위탁수수료:<?=$mainCommi?></li>
					<li>결산일:<?=$account_date?></li>
					<li>정산일:<?=(strlen($data->close_date)>0?"정산일 ".$data->close_date." 일전":"")?></li>
				</ul>
			</div>
			<div class="clear"></div>
			<h4>업체소개글</h4>
			<span class="info"><?=nl2br($data->comp_info)?></span>
		</div>
		<div class="figure2">
			<ul class="">
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
					<span class="sub">창고 소재지</span>
					<span class="unit"><?=$data->store_addr?></span>
				</li>
				<li>
					<span class="sub">창고규모</span>
					<span class="unit"><?=$data->store_area?>평</span>
				</li>
				<li>
					<span class="sub">운영 쇼핑몰</span>
					<span class="unit"><?=$data->homepage?></span>
				</li>
				<li>
					<span class="sub">보조 관리품목</span>
					<span class="unit"><?=substr($codeNm,0,-1)?></span>
				</li>
				<li>
					<span class="sub">물품출고시간</span>
					<span class="unit"><?=$data->release_time?></span>
				</li>
				<li>
					<span class="sub">위탁수수료</span>
					<span class="unit"><?=substr($pr_commi,0,-1)?></span>
				</li>
			</ul>
		</div>
	</article>
	<article class="prDesc">
		<h4 class="txt">위탁품목 정보</h4>
		<form name="itemForm" id="itemForm" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="insert">
		<input type="hidden" name="give_vender" value="<?=$_VenderInfo->getVidx()?>">
		<input type="hidden" name="take_vender" value="<?=$data->vender?>">
		<fieldset>
			<legend>위탁관리 업체등록 신청하기</legend>
			<div class="greyBox">
				<div class="itemInfo">
					<ul class="desc1">
						<li>
							<span class="sub">품목</span>
							<span class="unit_small2">
								<select name="codeA[]">
								<?
								$sql = "SELECT codeA,code_name,type FROM tblproductcode ";
								$sql.= "WHERE codeB='000' AND codeC='000' ";
								$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
								$result=mysql_query($sql,get_db_conn());
								while($row=mysql_fetch_object($result)) {
									echo "<option value=\"".$row->codeA."\">".$row->code_name."</option>\n";
								}
								mysql_free_result($result);
								?>
								</select>
							</span>
						</li>	
						<li class="second">
							<span class="sub">수량</span>
							<span class=""><input type="text" name="item_amount[]"></span>
						</li>
					</ul>
					<div class="clear"></div>
					<ul class="desc2">
						<li>
							<span class="sub">특징</span>
							<span class=""><textarea name="item_desc[]" id=""></textarea></span>
						</li>
						<li>
							<span class="sub">사진</span>
							<span>
								<button type="button" onclick="fileUploadAction('1');">파일 올리기</button>
								<input type="file" name="upload" id="upload_1" style="display:none">
								*최대 200MB	이내, 위탁품목의 주요특징이 포함된 고화질 이미지 권장<br>
								<div id="preview_1" class="preview"></div>
								<input type="hidden" name="photo_cnt[]" id="photo_cnt_1" value="0">
							</span>
						</li>
					</ul>
				</div>
				<button type="button" class="btn100" id="itemPlus">+위탁품목 추가하기+</button>
			</div>

			<ul class="txtlist">
				<li>선택하신 위탁업체로 내 정보(업체명,연락처,주소,아이디,사업자등록번호,계좌 등)가 제공됩니다.</li>
				<li>제공 정보는 위탁관리 기간동안 배송 및 계약의 이행을 위해 사용되며 기간 종료 후 6개월 이내 파기됩니다.</li>
				<li>위탁하신 품목의 결산 및 정산일은 위탁사의 설정에 따라 적용됩니다.</li>
				<li>위탁 신청 후 2주일 내 위탁업체의 위탁 승인이 없을 경우 위탁관리 신청이 취소됩니다.</li>
				<li>위탁 승인 후 배송된 물품의 상태에 따라 모든 상품 혹은 일부 상품의 위탁이 거절될 수 있습니다.</li>
				<li>
					<span>위탁 후 1년내 거래가 되지 않을 경우, 해당 물품은 반품 혹은 파기 또는 수수료가 조정될 수 있습니다.</span>
					<span><input type="radio" name="agree" id="agree_y" value="Y">동의 <input type="radio" name="agree" id="agree_n" value="N" checked="checked">거절</span>
				</li>
			</ul>
			<div id="demo"></div>
			<input type="hidden" name="arrPhoto">
			<div class="applyBtn">
				<button type="button" class="okBtn" id="trust_applyBtn" disabled="disabled" onclick="javascript:submitAction()">위탁관리 신청하기</button>
			</div>
		</fieldset>
		</form>
	</article>

</section>

</body>
<script language="JavaScript">
var upload = document.querySelectorAll("input[name='upload']");
var sel_files = [];

for(i=0;i<upload.length;i++){
	
	$(document).on("change","input[name='upload']",function(e){

		var index = eval($(this).attr('id').replace("upload_",""));
		
		var get_file = e.target.files;

		var image = document.createElement('img');
		
		if(get_file[0].size > 5 * 1024 * 1024){
			alert('5MB 이상의 파일은 업로드할 수 없습니다.');
			return;
		}
		else if(get_file[0].type.indexOf('image') < 0){
			alert('이미지 파일만 선택하세요.');
			return;
		}

		//sel_files = [];
		sel_files.push(get_file[0]);

		/* FileReader 객체 생성 */
		var reader = new FileReader();

		/* reader 시작시 함수 구현 */
		reader.onload = (function (aImg) {
			console.log(1);

			return function (e) {
				console.log(3);
				/* base64 인코딩 된 스트링 데이터 */
				aImg.src = e.target.result;
				
				image = "<img src='"+aImg.src+"' style='margin:5px;width:50px;height:50px;border:1px solid grey;cursor:pointer' onclick='removeMultiimage(this)'>";
				$("#preview_"+index).append(image);
				/*
				image.style.padding = "5px";
				image.style.width = "50px";
				image.style.height = "50px";
				*/
				$("#photo_cnt_"+index).val(eval($("#photo_cnt_"+index).val())+1);
				
			}
		})(image)

		if(get_file){
			/* 
				get_file[0] 을 읽어서 read 행위가 종료되면 loadend 이벤트가 트리거 되고 
				onload 에 설정했던 return 으로 넘어간다.
				이와 함게 base64 인코딩 된 스트링 데이터가 result 속성에 담겨진다.
			*/
			reader.readAsDataURL(get_file[0]);
			console.log(2);
		}
	})

}//end for

function fileUploadAction(index) {
	console.log("fileUploadAction");
	$("#upload_"+index).trigger('click');
}

function submitAction(){
	var data = new FormData();

	for(var i=0,len=sel_files.length;i<len;i++){
		var name = "image_"+i;
		data.append(name,sel_files[i]);
	}
	data.append("image_count",sel_files.length);
	
	var xhr = new XMLHttpRequest();
	xhr.open("POST","trust_apply_insert.php");
	xhr.onload = function(e){
		if(this.status == 200){
			//console.log("Result : "+e.currentTarget.responseText);
			//document.getElementById("demo").innerHTML = e.currentTarget.responseText;
			//$("input[name=arrPhoto]").val(e.currentTarget.responseText);

			document.itemForm.arrPhoto.value = e.currentTarget.responseText;
			document.itemForm.action = "trust_apply_pop.php";
			document.itemForm.submit();

		}
	}
	xhr.send(data);

	
}
</script>
</html>