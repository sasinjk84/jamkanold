<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

$mode=$_POST["mode"];


if ($mode == "insert" || $mode == "modify") {
	
	$tm_idx         = $_POST["tm_idx"];
	$vender         = $_POST["vender"];
	$store_post     = $_POST["store_post"];
	$store_addr     = $_POST["store_addr"];
	$store_sido		= $_POST["sido"];
	$store_sigungu	= $_POST["sigungu"];
	$store_area     = $_POST["store_area"];
	$product_commi  = $_POST["product_commi"];
	$homepage		= $_POST["homepage"];
	$release_time   = $_POST["release_time"];
	$p_name         = $_POST["p_name"];
	$p_mobile       = $_POST["p_mobile"];
	$p_email        = $_POST["p_email"];
	$comp_info      = $_POST["comp_info"];
	$approve        = "N";
	$refuse_reason  = $_POST["refuse_reason"];
	$settling_day   = $_POST["settling_day"];
	
	$product_commi = "";
	for($i=1;$i<=4;$i++){
		if($_POST["code".$i]!=$i){
			$product_commi .= $_POST["code".$i].":".$_POST["commi".$i]."//";
		}
	}

	#÷������
	if(!_empty($_FILES['store_register']['name'])){
		$storefileURL = $_SERVER['DOCUMENT_ROOT']."/data/trust_store/";

		$exte = explode(".",$_FILES['store_register']['name']);
		$exte = $exte[ count($exte)-1 ];
		$storefile_name = "store_register_".date("YmdHis").".".$exte;
		
		move_uploaded_file($_FILES['store_register']['tmp_name'],$storefileURL.$storefile_name);
	}
	
	if($mode=="insert"){
		$sql = "INSERT tbltrustmanage SET ";
		$sql.= "vender		= '".$vender."', ";
		$sql.= "store_post		= '".$store_post."', ";
		$sql.= "store_addr		= '".$store_addr."', ";
		$sql.= "store_sido		= '".$store_sido."', ";
		$sql.= "store_sigungu	= '".$store_sigungu."', ";
		$sql.= "store_area		= '".$store_area."', ";
		if(strlen($storefile_name)>0){
			$sql.= "store_register		= '".$storefile_name."', ";
		}
		$sql.= "product_commi		= '".$product_commi."', ";
		$sql.= "homepage	= '".$homepage."', ";
		$sql.= "release_time	= '".$release_time."', ";
		$sql.= "p_name		= '".$p_name."', ";
		$sql.= "p_mobile			= '".$p_mobile."', ";
		$sql.= "p_email		= '".$p_email."', ";
		$sql.= "comp_info		= '".$comp_info."', ";
		$sql.= "approve			= '".$approve."', ";
		$sql.= "refuse_reason			= '".$refuse_reason."', ";
		$sql.= "regdate			= now() ";
	}else{
		$sql = "UPDATE tbltrustmanage SET ";
		$sql.= "store_post		= '".$store_post."', ";
		$sql.= "store_addr		= '".$store_addr."', ";
		$sql.= "store_sido		= '".$store_sido."', ";
		$sql.= "store_sigungu	= '".$store_sigungu."', ";
		$sql.= "store_area		= '".$store_area."', ";
		if(strlen($storefile_name)>0){
			$sql.= "store_register		= '".$storefile_name."', ";
		}
		$sql.= "product_commi		= '".$product_commi."', ";
		$sql.= "homepage	= '".$homepage."', ";
		$sql.= "release_time	= '".$release_time."', ";
		$sql.= "p_name		= '".$p_name."', ";
		$sql.= "p_mobile			= '".$p_mobile."', ";
		$sql.= "p_email		= '".$p_email."', ";
		$sql.= "comp_info		= '".$comp_info."', ";
		$sql.= "approve			= '".$approve."', ";
		$sql.= "refuse_reason			= '".$refuse_reason."', ";
		$sql.= "regdate			= now() ";
		$sql.= "WHERE tm_idx='".$tm_idx."'";
	}

	if($insert = mysql_query($sql,get_db_conn())){
		echo "<script>alert(\"��Ź���� ��ü��� ��û�� �Ϸ�Ǿ����ϴ�. ��û������ �ùٸ��� �ʰų� ����ڿ����� ���� ���� ��� ��Ź���� ��ü��� ��û�� ��ҵǸ� ��û������ �ı�˴ϴ�.\");window.close();</script>";
		exit;
	}
}



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

$p_name = $data->p_name? $data->p_name : $_venderdata->p_name;
$p_mobile = $data->p_mobile? $data->p_mobile : $_venderdata->p_mobile;
$p_email = $data->p_email? $data->p_email : $_venderdata->p_email;

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
<script type="text/javascript" src="/js/jquery.validate.min.js"></script>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
function addr_search_for_daumapi(post,addr1,addr2) {
	new daum.Postcode({
		oncomplete: function(data) {
			// �˾����� �˻���� �׸��� Ŭ�������� ������ �ڵ带 �ۼ��ϴ� �κ�.

			// �� �ּ��� ���� ��Ģ�� ���� �ּҸ� �����Ѵ�.
			// �������� ������ ���� ���� ��쿣 ����('')���� �����Ƿ�, �̸� �����Ͽ� �б� �Ѵ�.
			var fullAddr = ''; // ���� �ּ� ����
			var extraAddr = ''; // ������ �ּ� ����

			// ����ڰ� ������ �ּ� Ÿ�Կ� ���� �ش� �ּ� ���� �����´�.
			if (data.userSelectedType === 'R') { // ����ڰ� ���θ� �ּҸ� �������� ���
				fullAddr = data.roadAddress;

			} else { // ����ڰ� ���� �ּҸ� �������� ���(J)
				fullAddr = data.jibunAddress;
			}

			// ����ڰ� ������ �ּҰ� ���θ� Ÿ���϶� �����Ѵ�.
			if(data.userSelectedType === 'R'){
				//���������� ���� ��� �߰��Ѵ�.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// �ǹ����� ���� ��� �߰��Ѵ�.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// �������ּ��� ������ ���� ���ʿ� ��ȣ�� �߰��Ͽ� ���� �ּҸ� �����.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// �����ȣ�� �ּ� ������ �ش� �ʵ忡 �ִ´�.
			document.getElementById(post).value = data.zonecode; //5�ڸ� �������ȣ ���
			document.getElementById(addr1).value = fullAddr;

			document.getElementById("sido").value = data.sido; //��/�� �̸� 
			document.getElementById("sigungu").value = data.sigungu; //


			// Ŀ���� ���ּ� �ʵ�� �̵��Ѵ�.
			if (addr2 != "") {
				document.getElementById(addr2).focus();
			}
		}
	}).open();
}

$(document).ready(function () {    

	if($("input[type=file]").val()!="" && $("input[name=code1]").val()!=""){
//		$('#trust_applyBtn').removeAttr("disabled");
	}else{
	//	$('#trust_applyBtn').attr("disabled", "disabled");
	}

/*
	$('#applyForm').validate({
		rules: {
			store_area : {required:true,number:true},
			//store_register: {required:true},
			store_register: {required:function(){ return $('#old_store_register').val();}},
			code1: "required",
			commi1: {required:true,number:true},
			commi2: {number:true},
			commi3: {number:true},
			commi4: {number:true}
		},

		messages: {
			store_area: {
				required:"��ǰ���� â�� �Ը� �Է��ϼ���.",
				number: "���ڸ� �Է��Ͻ� �� �ֽ��ϴ�."
			},
			store_register:"����༭�� ÷���ϼ���.",
			code1:"��ǰ ������ �����ϼ���.",
			commi1: {
				required:"��ǰ �����Ḧ �Է��ϼ���.",
				number: "���ڸ� �Է��Ͻ� �� �ֽ��ϴ�."
			},
			commi2: {
				number: "���ڸ� �Է��Ͻ� �� �ֽ��ϴ�."
			},
			commi3: {
				number: "���ڸ� �Է��Ͻ� �� �ֽ��ϴ�."
			},
			commi4: {
				number: "���ڸ� �Է��Ͻ� �� �ֽ��ϴ�."
			}
		},
		
		submitHandler: function (frm){
			if($('#code1').val()==$('#code2').val() || $('#code1').val()==$('#code3').val() || $('#code1').val()==$('#code4').val() || $('#code2').val()==$('#code3').val() || $('#code2').val()==$('#code4').val() || $('#code3').val()==$('#code4').val()){
				alert("������ ������ ��ǰ������ �ٸ��� �������ּ���.  ");
				return;
			}else if($('#code2').val()!="2" && $('#commi2').val()==""){
				alert("�����Ḧ �Է����ּ���.  ");
				$('#commi2').focus();
				return;
			}else if($('#code3').val()!="3" && $('#commi3').val()==""){
				alert("�����Ḧ �Է����ּ���.  ");
				$('#commi3').focus();
				return;
			}else if($('#code4').val()!="4" && $('#commi4').val()==""){
				alert("�����Ḧ �Է����ּ���.  ");
				$('#commi4').focus();
				return;
			}else{
				$('#trust_applyBtn').removeAttr("disabled");
				frm.submit();   //��ȿ�� �˻縦 ����� ����
			}
		},

		success: function(e){
		}
	});
*/


	$('#trust_applyBtn').click(function(){ 

		if($('#store_area').val()==""){
			alert("��ǰ���� â�� �Ը� �Է��ϼ���.");
			$('#store_area').focus();
			return false;
		}

		if (isNaN($('#store_area').val())){
			alert("��ǰ���� â�� �Ը�� ���ڸ� �Է��Ͻ� �� �ֽ��ϴ�.");
			$('#store_area').val("");
			$('#store_area').focus();
			return false;
		}

		if($('#old_store_register').val()=="" && $('#store_register').val()==""){
			alert("����â�� ����༭�� ÷���ϼ���.");
			return false;
		}

		if($('#code1').val()==""){
			alert("��ǰ ������ �����ϼ���.");
			$('#code1').focus();
			return false;
		}

		if($('#commi1').val()==""){
			alert("��ǰ �����Ḧ �Է��ϼ���.");
			$('#commi1').focus();
			return false;
		}

		if (isNaN($('#commi1').val()) || isNaN($('#commi2').val()) || isNaN($('#commi3').val()) || isNaN($('#commi4').val())){
			alert("��ǰ ������� ���ڸ� �Է��Ͻ� �� �ֽ��ϴ�.");
			return false;
		}

		if($('#code1').val()==$('#code2').val() || $('#code1').val()==$('#code3').val() || $('#code1').val()==$('#code4').val() || $('#code2').val()==$('#code3').val() || $('#code2').val()==$('#code4').val() || $('#code3').val()==$('#code4').val()){
			alert("������ ������ ��ǰ������ �ٸ��� �������ּ���.  ");
			return;
		}else if($('#code2').val()!="2" && $('#commi2').val()==""){
			alert("�����Ḧ �Է����ּ���.  ");
			$('#commi2').focus();
			return;
		}else if($('#code3').val()!="3" && $('#commi3').val()==""){
			alert("�����Ḧ �Է����ּ���.  ");
			$('#commi3').focus();
			return;
		}else if($('#code4').val()!="4" && $('#commi4').val()==""){
			alert("�����Ḧ �Է����ּ���.  ");
			$('#commi4').focus();
			return;
		}

		$('#applyForm').submit();   //��ȿ�� �˻縦 ����� ����

	});


});
</script>
</head>

<body topmargin=0 leftmargin=0  marginheight=0 marginwidth=0>

	
<section>
	<article class="applyForm">
		<h4 class="txt">��Ź���� ��ü��� ��û��</h4>
		<form name="applyForm" id="applyForm" method="post" action="<?=$PHP_SELF?>" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="<?=$tm_idx? "modify":"insert";?>">
		<input type="hidden" name="vender" value="<?=$_VenderInfo->getVidx()?>">
		<input type="hidden" name="tm_idx" value="<?=$tm_idx?>">
		<fieldset>
			<legend>��Ź���� ��ü��� ��û�ϱ�</legend>
			<ul class="mainForm">
				<li>
					<span class="sub">��ǰ���� â�� ��ġ</span>
					<span class="unit">
						<button type="button" id="addrSearch" onClick="addr_search_for_daumapi('store_post','store_addr','');">�ּҰ˻�</button><input type="text" name="store_post" id="store_post" class="addrBox1" value="<?=$data->store_post?>"><br>
						<input type="text" name="store_addr" id="store_addr" class="inputBox" value="<?=$data->store_addr?>">
						<input type="hidden" name="sido" id="sido" class="inputBox" value="<?=$data->store_sido?>">
						<input type="hidden" name="sigungu" id="sigungu" class="inputBox" value="<?=$data->store_sigungu?>">
					</span>
				</li>	
				<li>
					<span class="sub">��ǰ���� â�� �Ը�(��)</span>
					<span class="unit"><input type="text" name="store_area" id="store_area" class="inputBox" placeholder="��" style="text-align:right" value="<?=$data->store_area?>"></span>
				</li>
				<li>
					<span class="sub">����â�� ����༭<font class="red small">(�纻,�����ֿ� ���ǰ� �����ؾ���)</font></span>
					<span class="unit">
						<input type="file" class="inputBox" name="store_register" id="store_register">
						<input type="hidden" name="old_store_register" id="old_store_register" value="<?=$data->store_register?>">
						<?=$data->store_register?>
					</span>
				</li>
				<li>
					<span class="sub">
						��Ź���� ��ǰ ���� �� ������ ����<br>
						<font class="red small">�� ������ ���� �ÿ��� ���������Ḧ �����ϰ� ���޵˴ϴ�.</font>
					</span>
					<span class="unit">
						<ul class="comi">
							<?
							for($i=1;$i<=4;$i++){
								if($i==1) $title = "MAIN";
								else $title = "SUB";

								$arrCommi[$i-1] = explode(":",$arrPr_commi[$i-1]);

							?>
							<li>
								<span class="title"><?=$title?></span>
								<select name="code<?=$i?>" id="code<?=$i?>">
									<option value="<?=$i?>">ī�װ�����</option>
								<?
								$sql = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
								$sql.= "WHERE codeB='000' AND codeC='000' ";
								$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
								$result=mysql_query($sql,get_db_conn());
								while($row=mysql_fetch_object($result)) {
									$ctype=substr($row->type,-1);
									if($ctype!="X") $ctype="";
									echo "<option value=\"".$row->codeA."\" ctype='".$ctype."'";
									if($row->codeA==substr($arrCommi[$i-1][0],0,3)) echo " selected";
									echo ">".$row->code_name."";
									if($ctype=="X") echo " (���Ϻз�)";
									echo "</option>\n";
								}
								mysql_free_result($result);
								?>
								</select>
								<input type="text" name="commi<?=$i?>" id="commi<?=$i?>" value="<?=$arrCommi[$i-1][1]?>"> %
							</li>
							<?
							} 
							?>
						</ul>
					</span>
				</li>
				<li>
					<span class="sub">� ���� �¶��� ���θ�</span>
					<span class="unit"><input type="text" class="inputBox" name="homepage" value="<?=$data->homepage?>" placeholder="���� ��� �̱���"></span>
				</li>
				<li>
					<span class="sub">��ǰ ��� ���� �ð�</span>
					<span class="unit"><input type="text" class="inputBox" name="release_time" value="<?=$data->release_time?>" placeholder="ex)08:00~20:00"></span>
				</li>
				<li>
					<span class="sub">���� ����� ����</span>
					<span class="unit"><input type="text" class="inputBox" name="p_name" value="<?=$p_name?>"></span>
				</li>
				<li>
					<span class="sub">���� ����� ����ó</span>
					<span class="unit"><input type="text" class="inputBox" name="p_mobile" value="<?=$p_mobile?>"></span>
				</li>
				<li>
					<span class="sub">���� ����� �̸���</span>
					<span class="unit"><input type="text" class="inputBox" name="p_email" value="<?=$p_email?>"></span>
				</li>
				<li>
					<span class="sub">��ü�Ұ���</span>
					<span class="unit2">
						<textarea name="comp_info" id="comp_info" placeholder="��Ź������ ��û�ϴ� ��ü�� �����Ǵ� ���Դϴ�. �ֿ� ���� �� �̷� ���� �����մϴ�."><?=$data->comp_info?></textarea><br>
						<font class="red">�� ��Ź���� ��ü��� �ɻ翡�� �ּ� �������� �ҿ�˴ϴ�. ��� �Ϸ� �� ����Ͻ� ����ó�� �ȳ��帳�ϴ�.</font>
					</span>
				</li>
			</ul>
			<div class="applyBtn">
				<button type="button" class="okBtn" id="trust_applyBtn">��Ź���� ��û�ϱ�</button>
			</div>
		</fieldset>
		</form>
	</article>

</section>

</body>
</html>