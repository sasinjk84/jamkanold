<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");

	if(strlen($_ShopInfo->getMemid())==0){
		echo "<html><head></head><body><script>alert('�߸��� �����Դϴ�.');window:close();</script></body></html>";
	}

	$idx=$_REQUEST['idx'];

	if($type=='modify'){
		$receiver_name=$_POST['receiver_name'];
		$receiver_tel1=$_POST['receiver_tel11']."-".$_POST['receiver_tel12']."-".$_POST['receiver_tel13'];
		$receiver_tel2=$_POST['receiver_tel21']."-".$_POST['receiver_tel22']."-".$_POST['receiver_tel23'];
		$receiver_email=$_POST['receiver_email'];
		$receiver_post=$_POST['rpost1'];
		$receiver_addr1=$_POST['raddr1'];
		$receiver_addr2=$_POST['raddr2'];
		$receiver_addr=mysql_escape_string($receiver_addr1)."=".mysql_escape_string($receiver_addr2);

		$sql="UPDATE tblorderreceiver SET ";
		$sql.="receiver_name='".$receiver_name."', ";
		$sql.="receiver_tel1='".$receiver_tel1."', ";
		$sql.="receiver_tel2='".$receiver_tel2."', ";
		$sql.="receiver_email='".$receiver_email."', ";
		$sql.="receiver_post='".$receiver_post."', ";
		$sql.="receiver_addr='".$receiver_addr."' ";
		$sql.="WHERE idx=".$idx." ";
		mysql_query($sql,get_db_conn());

		$onload="<script>alert('����� ������ �Ϸ�Ǿ����ϴ�.');window.opener.location.reload();self.close();</script>";
	}

	//����� ���� ȣ��
	$sql="SELECT * FROM tblorderreceiver WHERE idx=".$idx." ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);

	$receiver_tel_temp=explode("-",$row->receiver_tel1);
	$receiver_tel11=$receiver_tel_temp[0];
	$receiver_tel12=$receiver_tel_temp[1];
	$receiver_tel13=$receiver_tel_temp[2];

	$receiver_tel2_temp=explode("-",$row->receiver_tel2);
	$receiver_tel21=$receiver_tel2_temp[0];
	$receiver_tel22=$receiver_tel2_temp[1];
	$receiver_tel23=$receiver_tel2_temp[2];

	$receiver_addr_temp=explode("=",$row->receiver_addr);
	$receiver_addr1=$receiver_addr_temp[0];
	$receiver_addr2=$receiver_addr_temp[1];
?>
<HTML>
	<HEAD>
	<TITLE>������, ��� ���� ���� ���� ��</TITLE>
	<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
	<META http-equiv="X-UA-Compatible" content="IE=Edge" />

	<META name="description" content="������">
	<META name="keywords" content="����,ī�޶�,��Ż,�Ӵ�,������">

	<script language="javascript">
		<!--
		function CheckForm(type,idx){
			if(document.form1.receiver_name.value.length==0) {
				alert("�����ڸ��� �Է��ϼ���.");
				document.form1.receiver_name.focus();
				return;
			}

			if(document.form1.receiver_tel21.value.length==0) {
				alert("�޴��� ��ȣ�� �Է��ϼ���.");
				document.form1.receiver_tel21.focus();
				return;
			}
			if(document.form1.receiver_tel22.value.length==0) {
				alert("�޴��� ��ȣ�� �Է��ϼ���.");
				document.form1.receiver_tel22.focus();
				return;
			}
			if(document.form1.receiver_tel23.value.length==0) {
				alert("�޴��� ��ȣ�� �Է��ϼ���.");
				document.form1.receiver_tel23.focus();
				return;
			}

			if(!IsNumeric(document.form1.receiver_tel21.value)) {
				alert("�޴��� ��ȣ�� ���ڸ� �Է� �����մϴ�.");
				document.form1.receiver_tel21.focus();
				return;
			}
			if(!IsNumeric(document.form1.receiver_tel22.value)) {
				alert("�޴��� ��ȣ�� ���ڸ� �Է� �����մϴ�.");
				document.form1.receiver_tel22.focus();
				return;
			}
			if(!IsNumeric(document.form1.receiver_tel23.value)) {
				alert("�޴��� ��ȣ�� ���ڸ� �Է� �����մϴ�.");
				document.form1.receiver_tel23.focus();
				return;
			}

			if(document.form1.rpost1.value.length==0) {
				alert("�����ȣ�� �Է��ϼ���.");
				document.form1.rpost1.focus();
				return;
			}
			if(document.form1.raddr1.value.length==0) {
				alert("�ּҸ� �Է��ϼ���.");
				document.form1.raddr1.focus();
				return;
			}
			if(document.form1.raddr2.value.length==0) {
				alert("�ּҸ� �Է��ϼ���.");
				document.form1.raddr2.focus();
				return;
			}

			if(!confirm("������� �����Ͻðڽ��ϱ�?")){
				return;
			}
			document.form1.type.value=type;
			document.form1.submit();
		}
		//-->
	</script>

	<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
</head>
<body style="margin:0;padding:0">
	<div style="background:#000;color:#fff;overflow:hidden">
		<h4 style="margin:0;padding:0;height:40px;line-height:40px;float:left;padding-left:20px;box-sizing:border-box">����� ����</h4>
		<a href="javascript:window.close();" style="float:right;display:block;width:40px;height:40px;line-height:40px;color:#fff;font-size:30px;text-decoration:none;text-align:center">&times;</a>
	</div>
	<div style="margin:0 auto;padding:30px;box-sizing:border-box;background:#fff;border:1px solid #ddd;list-style:none">
		<ul style="margin:0;padding:0;margin-bottom:15px;padding-bottom:15px;border-bottom:1px solid #eee;font-size:12px;">
			<li style="margin-bottom:4px;list-style:none">- ������� �̸� ����Ͻø� �ֹ��� �����ڸ� ������ �Է����� �����ŵ� �˴ϴ�.</p>
			<li style="list-style:none">- ��ϵ� ����� ������ ����/���� �޴��� ���ؼ� ������ �����մϴ�.</li>
		</ul>
		<form name="form1" id="form1" action="<?=$_SERVER[PHP_SELF]?>" method="post">
			<input type="hidden" name="type" value="modify" />
			<input type="hidden" name="idx" value="<?=$idx?>" />
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<colgroup>
					<col width="25%" />
					<col width="" />
				</colgroup>
				<tr>
					<th>�����ڸ�</th>
					<td style="padding:5px 10px;"><input type="text" name="receiver_name" value="<?=$row->receiver_name?>" maxlength="10" class="input" style="width:44%;" /></td>
				</tr>
				<tr>
					<th>��ȭ��ȣ</th>
					<td style="padding:5px 10px;">
						<input type="text" name="receiver_tel11" value="<?=$receiver_tel11?>" maxlength="4" class="input" style="width:20%;" /> - 
						<input type="text" name="receiver_tel12" value="<?=$receiver_tel12?>" maxlength="4" class="input" style="width:20%;" /> - 
						<input type="text" name="receiver_tel13" value="<?=$receiver_tel13?>" maxlength="4" class="input" style="width:20%;" />
					</td>
				</tr>
				<tr>
					<th>�޴�����ȣ</th>
					<td style="padding:5px 10px;">
						<input type="text" name="receiver_tel21" value="<?=$receiver_tel21?>" maxlength="4" class="input" style="width:20%;" /> - 
						<input type="text" name="receiver_tel22" value="<?=$receiver_tel22?>" maxlength="4" class="input" style="width:20%;" /> - 
						<input type="text" name="receiver_tel23" value="<?=$receiver_tel23?>" maxlength="4" class="input" style="width:20%;" />
					</td>
				</tr>
				<tr>
					<th>�̸���</th>
					<td style="padding:5px 10px;"><input type="text" name="receiver_email" value="<?=$row->receiver_email?>" maxlength="40" class="input" style="width:100%;" /></td>
				</tr>
				<tr>
					<th>�ּ�</th>
					<td style="padding:5px 10px;">
						<div>
							<input type="text" name="rpost1" value="<?=$row->receiver_post?>" maxlength="5" id="rpost1" class="input" style="width:30%;background:#f5f5f5;" onclick="addr_search_for_daumapi('rpost1','raddr1','raddr2')" readOnly />
							<a href="javascript:addr_search_for_daumapi('rpost1','raddr1','raddr2');" style="display: inline-block;
    padding: 6px;
    background: #ffffff;
    border: 1px solid #dddddd;
    border-radius: 1px;
    text-decoration: none;
    font-size: 12px;
    margin: 0px 1px 0px 0px;"><span class="btn_s_line2">�ּҰ˻�</span></a>
						</div>
						<div style="margin:4px 0px;">
							<input type="text" name="raddr1" value="<?=$receiver_addr1?>" maxlength="50" id="raddr1" class="input" style="width:100%;background:#f5f5f5;" readonly />
						</div>
						<input type="text" name="raddr2" value="<?=$receiver_addr2?>" maxlength="50" id="raddr2" class="input" style="width:100%;background:#f5f5f5;" />
					</td>
				</tr>
			</table>
			<div style="margin-top:20px;text-align:center;"><a href="#" onclick="CheckForm('modify','<?=$idx?>')" style="display: inline-block;
    padding: 6px;
    background: #ffffff;
    border: 1px solid #dddddd;
    border-radius: 1px;
    text-decoration: none;
    font-size: 12px;
    margin: 0px 1px 0px 0px;"><span class="btn_s_line2">����� �����ϱ�</span></a></div>
		</form>
	</div>

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

					// Ŀ���� ���ּ� �ʵ�� �̵��Ѵ�.
					if (addr2 != "") {
						document.getElementById(addr2).focus();
					}
				}
			}).open();
		}
	</script>

	<?=$onload?>
</body>
</html>