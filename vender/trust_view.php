<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

$mode=$_POST["mode"];
$itemfileURL = "/data/trust_item/";

//��Ź��� ���ο���
if($mode=="change"){
	$sql = "UPDATE tbltrustagree SET ";
	$sql.= "approve	= '".$approve."' ";
	if($approve=="Y"){
		$sql.= ",contract_date	= now() ";
	}
	$sql.= "WHERE ta_idx='".$ta_idx."' ";
	if($update = mysql_query($sql,get_db_conn())){
		if($approve=="Y"){ //����
			//sms ������$data->p_mobile
			
			//�̸��� ������$data->p_email

			$message = "��Ź����û�� �����Ͽ����ϴ�.";
		}else{
			$message = "��Ź����û�� �����Ͽ����ϴ�.";
		}

		echo "<script>alert(\"".$message."\");document.location.href='trust_list.php';</script>";
		exit;
	}
}

//���ü���� ��ҽ� ����
if($mode=="delete"){

	//���ε�� ���� ����
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
	echo "<script>alert(\"��Ź����û�� ���/�����Ǿ����ϴ�.\");document.location.href='trust_list.php';</script>";
	exit;

}

if($mode=="cancel_agree"){
	$sql = "UPDATE tbltrustcancel SET ";
	$sql.= "cancel_agree	= '".$cancel_agree."' ";
	$sql.= "WHERE ta_idx='".$ta_idx."'";
	mysql_query($sql,get_db_conn());
	
	if($cancel_agree=="Y"){
		$agree = "����";
	}else{
		$agree = "����";
	}
	echo "<script>alert(\"��Ź���öȸ��û�� ".$agree."�Ǿ����ϴ�.\");document.location.href='trust_view.php?type=".$type."&ta_idx=".$ta_idx."';</script>";
	exit;
}

//��Ź���ü���� ��ҽ� ��һ����ۼ�
if($mode=="cancel"){

	$approve        = "N";
	$cancel_reason  = $_POST["cancel_reason"];
	
	#÷������
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
		echo "<script>alert(\"��Ź��� öȸ��û�� �Ϸ�Ǿ����ϴ�.\");location.href='trust_list.php';</script>";
		exit;
	}

}else{

	if($type=="give"){	//������Ź : ������Ź��ü������������
		$title = "������Ź";

		$sql = "SELECT tm.product_commi,vi.adjust_lastday,ta.ta_idx,ta.approve,ta.approve_check,ta.regdate as rDate,v.*,tm.store_addr ";
		$sql.= "FROM tbltrustagree ta ";
		$sql.= "left join tbltrustmanage tm on ta.take_vender=tm.vender ";
		$sql.= "left join tblvenderinfo v on v.vender=ta.take_vender ";
		$sql.= "left join vender_more_info vi on vi.vender=ta.take_vender ";
		$sql.= "WHERE ta_idx='".$ta_idx."' ";
	}else{ //������Ź
		$title = "������Ź";

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

	//���ο��� Ȯ��üũ�ϱ�
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
			if(confirm("��Ź����û�� ����Ͻðڽ��ϱ�?")){
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
			alert("��һ����� �Է����ּ���");
			$('#cancel_reason').focus();
			return false;
		}else if($('#upload').val()==""){
			alert("���������� ���ε����ּ���");
			return false;
		}else{
			if(confirm("��Ź������ �� öȸ�Ͻðڽ��ϱ�?")){
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
		if(confirm("öȸ�� ��Ź����� �����Ͻðڽ��ϱ�?")){
			document.appForm.mode.value="delete";
			document.appForm.submit();
		}
	});


	$('#cancel_agree_y').click(function(){
		if(confirm("��Ź���öȸ��û�� �����Ͻðڽ��ϱ�?")){
			document.cancelForm.mode.value = "cancel_agree";
			document.cancelForm.cancel_agree.value = "Y";
			document.cancelForm.submit();
		}
	});

	$('#cancel_agree_n').click(function(){
		if(confirm("��Ź���öȸ��û�� �����Ͻðڽ��ϱ�?")){
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
				<h2>��Ź��� ����</h2>
				<div class="vTitle_bgimg"></div>
				<ul>
					<li class="notice_gray"><img src="images/icon_dot02.gif" border=0 hspace="4">�������� ��Ź����� ������ �� �ִ� �������Դϴ�.</li>
					<li class="notice_gray"><img src="images/icon_dot02.gif" border=0 hspace="4">������ǰ�� ���������� ����� ��� ǰ�� ���� ��Ź��ü�� �����Ͽ� ���� ���� �� �ֽ��ϴ�.</li>
				</ul>
			</article>
			<article class="trList">
				<h4>��Ź��� ��ü����</h4>
				<table>
					<tr>
						<th scope="col"><?=$title?><br><?=str_replace("-",".",$data->rDate)?></th>
						<td class="txtBold"><?=$data->com_name?></td>
						<td><?=$mainCodeNm?></td>
						<td><?=$mainCommi?></td>
						<td><?=$account_date?></td>
						<td class="addr"><?=$data->store_addr?></td>
						<td><button type="button" onclick="document.location.href='trust_list.php'">���ư���</button></td>
					</tr>
				</table>
				<div class="trDesc">
					<ul>
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
							<span class="sub">�μ���</span>
							<span class="unit"><?=$data->p_buseo?></span>
						</li>
						<li>
							<span class="sub">����</span>
							<span class="unit"><?=$data->p_level?></span>
						</li>
					</ul>
				</div>
				<div class="trDesc2">
					<h4>��Ź���� ������</h4>
					<ul>
						<li><?=$pr_commi?></li>
					</ul>
				</div>
			</article>
			<article class="prDesc">
				<h4>��Źǰ�� ������</h4>
				<?
				$sql = "SELECT * FROM tbltrustitem ";
				$sql.= "WHERE ta_idx='".$data->ta_idx."' ";
				$sql.= "ORDER BY ti_idx ASC ";
				$result=mysql_query($sql,get_db_conn());
				while($row=mysql_fetch_object($result)) {
					//ǰ���̸� ��������
					$sql2 = "SELECT code_name FROM tblproductcode ";
					$sql2.= "WHERE codeA='".$row->codeA."' ";
					$result2=mysql_query($sql2,get_db_conn());
					$row2=mysql_fetch_object($result2);

					$row->item_photo = substr($row->item_photo,0,-3);
					$arrItemPhoto = explode(" | ",$row->item_photo);
				?>
				<ul class="desc1">
					<li>
						<span class="sub">ǰ��</span>
						<span class="unit_small2 txtBox"><?=$row2->code_name?></span>
					</li>	
					<li class="second">
						<span class="sub">����</span>
						<span class="unit_small txtBox"><?=$row->item_amount?></span>
					</li>
				</ul>
				<div class="clear"></div>
				<ul class="desc2">
					<li>
						<span class="sub">Ư¡</span>
						<span class="unit_big txtBox"><?=nl2br($row->item_desc)?></span>
					</li>
					<li>
						<span class="sub">����</span>
						<span class="unit">
							<b>÷������</b> 
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
					<? if($type=="take"){ //���� ��Ź ?>
						<? if($data->approve=="N"){?>
						<button type="button" class="okBtn" id="approve">��Ź�������ϱ�</button>
						<button type="button" class="noBtn" id="refuse">��Ź�������ϱ�</button>
						<? }?>
					<? }else{ ?>
						<button type="button" class="noBtn" id="trustCancel">��Ź������/öȸ</button>
					<? } ?>
				</div>
				<div class="txt">
					<? if($type=="take"){ //���� ��Ź ?>
						<? if($data->approve=="N"){?>
						�� ��Ź����� 2���� �� �������� ������ ���� ��û�� �ڵ����� ��ҵ˴ϴ�.
						<? }?>
					<? }else{ ?>
						�� ��Ź��û�� ��� Ȥ�� ��Ź��������� öȸ�� ��û�� �� �ֽ��ϴ�. ��Ź��� ������� �� ����ü�� ���� �� ��Ź��ǰ�� ������ �����˴ϴ�.<br> ������� �� ��Ź���� ��ǰ�� �����ް� ������ ��ڿ��� �Ű��ϸ� ���� �����Ḧ ����ް� ��Ź���� ����� ������ ����˴ϴ�.
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
					<legend>��Ź������/öȸ</legend>
					<div class="cancelForm">
						<ul>
							<li>
								<span class="sub">����</span> 
								<span><textarea name="cancel_reason" id="cancel_reason"><?=$cData->cancel_reason?></textarea></span>
							</li>
							<li>
								<span class="sub">÷��</span> 
								<span>
									<button type="button" onclick="fileUploadAction();">���� �ø���</button>
									*��� �� öȸ ������ ���õ� �������� ���ε�
									<input type="file" name="upload" id="upload" style="display:none">
									<div id="preview" class="preview"></div>
									<?=$cData->cancel_doc?>
								</span>
							</li>
						</ul>
						<?
						//öȸ��û�ϱ� ���� ���
						if($cancelCnt==0){
						?>
						<ul>
							<li>��Ź��û�� ��� Ȥ�� ��Ź��������� öȸ�� ��û�� �� �ֽ��ϴ�.</li>
							<li>��Ź��� ������� �� ����ü�� ���� �� ��Ź��ǰ�� ������ �����˴ϴ�.</li>
							<li>������� �� ��Ź���� ��ǰ�� �����ް� ������ ��ڿ��� �Ű��ϼ���.</li>
							<li>
								<span>��� ���� �Ϸ� �� ���� ���Ͽ� ���� ������ �����Ḧ ���޹ް� ��Ź���� ����� ������ ����˴ϴ�.</span>
								<span><input type="radio" name="agree" id="agree_y" value="Y">���� <input type="radio" name="agree" id="agree_n" value="N" checked="checked">����</span>
							</li>
						</ul>
						<div class="applyBtn">
							<button type="button" class="okBtn" id="cancelSubmit" disabled="disabled">��û�ϱ�</button>
							<button type="button" class="okBtn" id="cancelBack">���ư���</button>
						</div>
						<? 
						}else{	
						?>
						<div class="applyBtn">
							<?
							if($type=="take" && $cData->status==0){
								if($cData->cancel_agree==""){
							?>
								��Ź��� ��ü�� ��Ź���öȸ��û�� �ϼ̽��ϴ�.<br>
								�����Ͻðڽ��ϱ�?<br>
								<button type="button" class="okBtn" id="cancel_agree_y">�����ϱ�</button>
								<button type="button" class="okBtn" id="cancel_agree_n">�����ϱ�</button>
							<?
								}else if($cData->cancel_agree=="Y"){
									echo "��Ź��� ��ü�� ��Ź���öȸ��û�� �����ϼ̽��ϴ�.";
								}else if($cData->cancel_agree=="N"){
									echo "��Ź��� ��ü�� ��Ź���öȸ��û�� �����ϼ̽��ϴ�.";
								}
							}else{
								//ó������(��û�� 0,��ǰȸ���� 1,�������޿Ϸ� 2,öȸ�Ϸ� 3)
								if($cData->status==0) $cancelMsg = "���öȸ��û���Դϴ�.";
								if($cData->status==1) $cancelMsg = "��ǰȸ�����Դϴ�.";
								if($cData->status==2) $cancelMsg = "�������޿Ϸ�Ǿ����ϴ�.";
								if($cData->status==3) $cancelMsg = "���öȸ�Ϸ�Ǿ����ϴ�.";

								if($cData->status==0 && $cData->cancel_agree=="N"){
									echo "��Ź��� ��ü�� ��Ź���öȸ�� �����߽��ϴ�.<br>����ü�� ���� �� �ٽ� �������ּ���.<br>����ü�� ���ո��� ������ ���ؼ��� ���ǽ�û ������ �� �ֽ��ϴ�.<br><a href='./shop_counsel.php'>���ǽ�û�Ϸ� ���⢺<a>";
								}else{
							?>
									<button type="button" class="okBtn"><?=$cancelMsg?></button>
							<?
								}
							}
							?>
							<? if($cData->status==3){ ?>
							<button type="button" class="noBtn" id="delete">��೻������ϱ�</button>
							�� ��೻���� �����ϼž� �����û �����մϴ�.
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
		alert('5MB �̻��� ������ ���ε��� �� �����ϴ�.');
		return;
	}

	$("#preview").append(get_file[0].name);
})


function fileUploadAction() {
	console.log("fileUploadAction");
	$("#upload").trigger('click');
}

</script>