<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

$get_vender=$_GET["vender"];


$tm_idx=$_POST["tm_idx"];
$mode=$_POST["mode"];
$approve=$_POST["approve"];
$refuse_reason=$_POST["refuse_reason"];

//��Ź���� ��ü��� ����
if($mode=="insert"){
	$sql = "UPDATE tbltrustmanage SET ";
	$sql.= "approve	= '".$approve."' ";
	if($approve=="R"){
		$sql.= ",refuse_reason	= '".$refuse_reason."' ";
	}
	$sql.= "WHERE tm_idx='".$tm_idx."' ";
	if($update = mysql_query($sql,get_db_conn())){
		if($approve=="Y"){ //����
			$message = "��Ź���� ��ü�� ����� �����߽��ϴ�.";
		}else if($approve=="C"){ //���
			$message = "��Ź���� ��ü ����� ����߽��ϴ�.";
		}else{
			$message = "��Ź���� ��ü�� ����� �����߽��ϴ�.";
		}

		echo "<script>alert(\"".$message."\");opener.document.form3.action='vender_infomodify.php';opener.document.form3.submit();window.close();</script>";
		exit;
	}
}


//��Ź���� ��ü��������
if($mode=="modify"){

	$product_commi = "";
	for($i=1;$i<=4;$i++){
		if($_POST["code".$i]!=$i){
			$product_commi .= $_POST["code".$i].":".$_POST["commi".$i]."//";
		}
	}

	$sql = "UPDATE tbltrustmanage SET ";
	$sql.= "store_post		= '".$store_post."', ";
	$sql.= "store_addr		= '".$store_addr."', ";
	$sql.= "store_sido		= '".$store_sido."', ";
	$sql.= "store_sigungu	= '".$store_sigungu."', ";
	$sql.= "store_area		= '".$store_area."', ";
	$sql.= "product_commi		= '".$product_commi."', ";
	$sql.= "homepage	= '".$homepage."', ";
	$sql.= "release_time	= '".$release_time."', ";
	$sql.= "p_name		= '".$p_name."', ";
	$sql.= "p_mobile			= '".$p_mobile."', ";
	$sql.= "p_email		= '".$p_email."', ";
	$sql.= "comp_info		= '".$comp_info."' ";
	$sql.= "WHERE tm_idx='".$tm_idx."'";
	if($update = mysql_query($sql,get_db_conn())){
		echo "<script>alert(\"�����Ǿ����ϴ�.\");opener.document.form3.action='vender_infomodify.php';opener.document.form3.submit();window.close();</script>";
		exit;
	}
}



$sql = "SELECT * FROM tbltrustmanage ";
$sql.= "WHERE vender='".$get_vender."' ";
$result=mysql_query($sql,get_db_conn());
$data=mysql_fetch_object($result);

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
<link rel="stylesheet" href="../vender/style.css" type="text/css">
<style>
.sub1 {width:100px;font-weight:bold}
.sub2 {width:60px;text-align:center;}
.mainForm li {border-bottom:1px solid #eeeeee}
.smallBtn {width:80px; margin-bottom:10px; padding:5px;}
.cancelForm textarea {width:600px;height:80px}
</style>
<script type="text/javascript" src="lib.js.php"></script>
<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
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

	$('#trust_modifyBtn').click(function(){ 

		if($('#store_area').val()==""){
			alert("��ǰ���� â�� �Ը� �Է��ϼ���.");
			return false;
		}

		if (isNaN($('#store_area').val())){
			alert("��ǰ���� â�� �Ը�� ���ڸ� �Է��Ͻ� �� �ֽ��ϴ�.");
			return false;
		}

		if($('#code1').val()==""){
			alert("��ǰ ������ �����ϼ���.");
			return false;
		}

		if($('#commi1').val()==""){
			alert("��ǰ �����Ḧ �Է��ϼ���.");
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

		$('input[name=mode]').val('modify');
		$('#appForm').submit();   //��ȿ�� �˻縦 ����� ����


	});

	$('#trust_cancelBtn').click(function(){ 
		if(confirm("��Ź������ü �������Ͻðڽ��ϱ�?")){
			$('input[name=approve]').val('C');
			$('input[name=mode]').val('insert');
			$('#appForm').submit();
		}
	});

	$('#trust_applyBtn').click(function(){ 
		$('input[name=approve]').val('Y');
		$('input[name=mode]').val('insert');
		$('#appForm').submit();
	});

	$('#refuse').click(function(){ 
		$('.cancelForm').show();
	});
	$('#cancelBack').click(function(){ 
		$('.cancelForm').hide();
	});

	$('#cancelSubmit').click(function(){
		if($('#refuse_reason').val()==""){
			alert("���������� �Է����ּ���");
			$('#refuse_reason').focus();
			return false;
		}else{
			if(confirm("��Ź���� ��Ͼ�ü ��û�� �����Ͻðڽ��ϱ�?")){
				$('input[name=approve]').val('R');
				$('input[name=mode]').val('insert');
				$('#appForm').submit();
			}
		}
	});

});
</script>
</head>

<body topmargin=0 leftmargin=0  marginheight=0 marginwidth=0>

	
<section>
	<article class="applyForm">
		<h4 class="txt">��Ź���� ��ü��� ��û��</h4>
		<form name="appForm" id="appForm" method="post" action="<?=$PHP_SELF?>">
		<input type="hidden" name="mode">
		<input type="hidden" name="tm_idx" value="<?=$data->tm_idx?>">
		<input type="hidden" name="approve">
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
					<span class="sub">����â�� ����༭</span>
					<span class="unit">
						<a href="../vender/trust_download.php?dir=/data/trust_store/&file_name=<?=$data->store_register?>"><?=$data->store_register?></a>
					</span>
				</li>
				<li>
					<span class="sub">
						��Ź���� ��ǰ ���� �� ������ ����
					</span>
					<span class="unit">
						<ul class="comi">
							<?//=$pr_commi?>
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
					<span class="unit"><input type="text" class="inputBox" name="p_name" value="<?=$data->p_name?>"></span>
				</li>
				<li>
					<span class="sub">���� ����� ����ó</span>
					<span class="unit"><input type="text" class="inputBox" name="p_mobile" value="<?=$data->p_mobile?>"></span>
				</li>
				<li>
					<span class="sub">���� ����� �̸���</span>
					<span class="unit"><input type="text" class="inputBox" name="p_email" value="<?=$data->p_email?>"></span>
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
				<?
				if($data->approve=="Y"){
				?>
				<button type="button" class="okBtn" id="trust_modifyBtn">���������ϱ�</button>
				<button type="button" class="noBtn" id="trust_cancelBtn">�������ϱ�</button>
				<?
				}else{
				?>
				<button type="submit" class="okBtn" id="trust_applyBtn">��Ź���� ��ü��� �����ϱ�</button>
				<button type="button" class="noBtn" id="refuse">��Ź���� ��ü��� �����ϱ�</button>
				<?
				}
				?>
			</div>

			<div class="cancelForm">
				<span class="sub">�������� 
				<textarea name="refuse_reason" id="refuse_reason"></textarea></span>
				<div class="applyBtn">
					<button type="button" class="smallBtn" id="cancelSubmit">����</button>
					<button type="button" class="smallBtn" id="cancelBack">���</button>
				</div>
			</div>
			<div class="clear"></div>
		</fieldset>
		</form>
	</article>

</section>

</body>
</html>