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

		echo "<script>alert(\"��Ź���� ��û�� �Ϸ�Ǿ����ϴ�. ��Ź���� ��ü�� ��û������ ���޵Ǹ�, 2�� �� ������ ���� ��� ��Ź ��û�� ��ҵǸ� ��û������ �ı�˴ϴ�.\");window.close();</script>";
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
	case 0 : $account_date = "�ſ� ".$data->account_date."��";
		break;
	case 1 : $account_date = "�ſ� ��������";
		break;
	case 2 : $account_date = "�ſ� 15�ϰ� ��������";
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
	echo "<script>alert('�̹� ��û���̰ų� ������� ��ü�Դϴ�.');window.close();</script>";
	exit;
}
?>
<html>
<head>
<title>������ ������</title>
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
htmlView += "						<span class=\"sub\">ǰ��</span>";
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
htmlView += "						<span class=\"sub\">����</span>";
htmlView += "						<span class=\"\"><input type=\"text\" name=\"item_amount[]\"></span>";
htmlView += "					</li>";
htmlView += "				</ul>";
htmlView += "				<div class=\"clear\"></div>";
htmlView += "				<ul class=\"desc2\">";
htmlView += "					<li>";
htmlView += "						<span class=\"sub\">Ư¡</span>";
htmlView += "						<span class=\"\"><textarea name=\"item_desc[]\" id=\"\"></textarea></span>";
htmlView += "					</li>";
htmlView += "					<li>";
htmlView += "						<span class=\"sub\">����</span>";
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
		
		htmlView_ = "			<button type=\"button\" onclick=\"fileUploadAction('"+AddCntNodes+"');\">���� �ø���</button>";
		htmlView_ += "			<input type=\"file\" name=\"upload\" id=\"upload_"+AddCntNodes+"\" style=\"display:none\">";
		htmlView_ += "			*�ִ� 200MB	�̳�, ��Źǰ���� �ֿ�Ư¡�� ���Ե� ��ȭ�� �̹��� ����<br>";
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
					<li>��ġ:<?=$data->store_addr?></li>
					<li>��ǰ��:<?=$mainCodeNm?></li>
					<li>��Ź������:<?=$mainCommi?></li>
					<li>�����:<?=$account_date?></li>
					<li>������:<?=(strlen($data->close_date)>0?"������ ".$data->close_date." ����":"")?></li>
				</ul>
			</div>
			<div class="clear"></div>
			<h4>��ü�Ұ���</h4>
			<span class="info"><?=nl2br($data->comp_info)?></span>
		</div>
		<div class="figure2">
			<ul class="">
				<li>
					<span class="sub">��ǥ�ڸ�</span>
					<span class="unit"><?=$data->com_owner?></span>
				</li>	
				<li>
					<span class="sub">��ȣ(ȸ���)</span>
					<span class="unit"><?=$data->com_name?></span>
				</li>	
				<li>
					<span class="sub">����ڵ�Ϲ�ȣ</span>
					<span class="unit"><?=$data->com_num?></span>
				</li>
				<li>
					<span class="sub">����� ������</span>
					<span class="unit"><?=$data->com_addr?></span>
				</li>
				<li>
					<span class="sub">��ȭ��ȣ</span>
					<span class="unit"><?=$data->com_tel?></span>
				</li>
				<li>
					<span class="sub">�ѽ���ȣ</span>
					<span class="unit"><?=$data->com_fax?></span>
				</li>
			</ul>
			<ul>
				<li>
					<span class="sub">����� �̸�</span>
					<span class="unit"><?=$data->p_name?></span>
				</li>	
				<li>
					<span class="sub">����� ����ó</span>
					<span class="unit"><?=$data->p_mobile?></span>
				</li>	
				<li>
					<span class="sub">�̸��� �ּ�</span>
					<span class="unit"><?=$data->p_email?></span>
				</li>
				<li>
					<span class="sub">â�� ������</span>
					<span class="unit"><?=$data->store_addr?></span>
				</li>
				<li>
					<span class="sub">â��Ը�</span>
					<span class="unit"><?=$data->store_area?>��</span>
				</li>
				<li>
					<span class="sub">� ���θ�</span>
					<span class="unit"><?=$data->homepage?></span>
				</li>
				<li>
					<span class="sub">���� ����ǰ��</span>
					<span class="unit"><?=substr($codeNm,0,-1)?></span>
				</li>
				<li>
					<span class="sub">��ǰ���ð�</span>
					<span class="unit"><?=$data->release_time?></span>
				</li>
				<li>
					<span class="sub">��Ź������</span>
					<span class="unit"><?=substr($pr_commi,0,-1)?></span>
				</li>
			</ul>
		</div>
	</article>
	<article class="prDesc">
		<h4 class="txt">��Źǰ�� ����</h4>
		<form name="itemForm" id="itemForm" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="insert">
		<input type="hidden" name="give_vender" value="<?=$_VenderInfo->getVidx()?>">
		<input type="hidden" name="take_vender" value="<?=$data->vender?>">
		<fieldset>
			<legend>��Ź���� ��ü��� ��û�ϱ�</legend>
			<div class="greyBox">
				<div class="itemInfo">
					<ul class="desc1">
						<li>
							<span class="sub">ǰ��</span>
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
							<span class="sub">����</span>
							<span class=""><input type="text" name="item_amount[]"></span>
						</li>
					</ul>
					<div class="clear"></div>
					<ul class="desc2">
						<li>
							<span class="sub">Ư¡</span>
							<span class=""><textarea name="item_desc[]" id=""></textarea></span>
						</li>
						<li>
							<span class="sub">����</span>
							<span>
								<button type="button" onclick="fileUploadAction('1');">���� �ø���</button>
								<input type="file" name="upload" id="upload_1" style="display:none">
								*�ִ� 200MB	�̳�, ��Źǰ���� �ֿ�Ư¡�� ���Ե� ��ȭ�� �̹��� ����<br>
								<div id="preview_1" class="preview"></div>
								<input type="hidden" name="photo_cnt[]" id="photo_cnt_1" value="0">
							</span>
						</li>
					</ul>
				</div>
				<button type="button" class="btn100" id="itemPlus">+��Źǰ�� �߰��ϱ�+</button>
			</div>

			<ul class="txtlist">
				<li>�����Ͻ� ��Ź��ü�� �� ����(��ü��,����ó,�ּ�,���̵�,����ڵ�Ϲ�ȣ,���� ��)�� �����˴ϴ�.</li>
				<li>���� ������ ��Ź���� �Ⱓ���� ��� �� ����� ������ ���� ���Ǹ� �Ⱓ ���� �� 6���� �̳� �ı�˴ϴ�.</li>
				<li>��Ź�Ͻ� ǰ���� ��� �� �������� ��Ź���� ������ ���� ����˴ϴ�.</li>
				<li>��Ź ��û �� 2���� �� ��Ź��ü�� ��Ź ������ ���� ��� ��Ź���� ��û�� ��ҵ˴ϴ�.</li>
				<li>��Ź ���� �� ��۵� ��ǰ�� ���¿� ���� ��� ��ǰ Ȥ�� �Ϻ� ��ǰ�� ��Ź�� ������ �� �ֽ��ϴ�.</li>
				<li>
					<span>��Ź �� 1�⳻ �ŷ��� ���� ���� ���, �ش� ��ǰ�� ��ǰ Ȥ�� �ı� �Ǵ� �����ᰡ ������ �� �ֽ��ϴ�.</span>
					<span><input type="radio" name="agree" id="agree_y" value="Y">���� <input type="radio" name="agree" id="agree_n" value="N" checked="checked">����</span>
				</li>
			</ul>
			<div id="demo"></div>
			<input type="hidden" name="arrPhoto">
			<div class="applyBtn">
				<button type="button" class="okBtn" id="trust_applyBtn" disabled="disabled" onclick="javascript:submitAction()">��Ź���� ��û�ϱ�</button>
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
			alert('5MB �̻��� ������ ���ε��� �� �����ϴ�.');
			return;
		}
		else if(get_file[0].type.indexOf('image') < 0){
			alert('�̹��� ���ϸ� �����ϼ���.');
			return;
		}

		//sel_files = [];
		sel_files.push(get_file[0]);

		/* FileReader ��ü ���� */
		var reader = new FileReader();

		/* reader ���۽� �Լ� ���� */
		reader.onload = (function (aImg) {
			console.log(1);

			return function (e) {
				console.log(3);
				/* base64 ���ڵ� �� ��Ʈ�� ������ */
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
				get_file[0] �� �о read ������ ����Ǹ� loadend �̺�Ʈ�� Ʈ���� �ǰ� 
				onload �� �����ߴ� return ���� �Ѿ��.
				�̿� �԰� base64 ���ڵ� �� ��Ʈ�� �����Ͱ� result �Ӽ��� �������.
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