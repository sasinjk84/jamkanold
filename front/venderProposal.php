<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
?>

<HTML>
<HEAD>
<TITLE><?=$_data->shoptitle?></TITLE>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=Edge" />

<META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
<META name="keywords" content="<?=$_data->shopkeyword?>">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<?include($Dir."lib/style.php")?>

<SCRIPT LANGUAGE="JavaScript">
	function sendForm( form ) {

		if(form.company.value.length==0) {
			alert("ȸ��� �Է��ϼ���.");
			form.company.focus(); return;
		}
		if(form.home_addr1.value.length==0) {
			alert("����� �ּҸ� �Է��ϼ���.");
			//f_addr_search('proposalFrom','home_post','home_addr1',2); return;
		}
		if(form.home_addr2.value.length==0) {
			alert("����� �� �ּҸ� �Է��ϼ���.");
			form.home_addr2.focus(); return;
		}

		if(form.name.value.length==0) {
			alert("����� ������ �Է��ϼ���.");
			form.name.focus(); return;
		}

		if(form.tell1.value.length==0) {
			alert("����� ��ȭ��ȣ�� �Է��ϼ���.");
			form.tell1.focus(); return;
		}
		if(form.tell2.value.length==0) {
			alert("����� ��ȭ��ȣ�� �Է��ϼ���.");
			form.tell2.focus(); return;
		}
		if(form.tell3.value.length==0) {
			alert("����� ��ȭ��ȣ�� �Է��ϼ���.");
			form.tell3.focus(); return;
		}

		if(form.phone1.value=='X') {
			alert("����� �ڵ��� ���ڸ��� �����ϼ���.");
			form.phone1.focus(); return;
		}
		if(form.phone2.value.length==0) {
			alert("����� �ڵ����� �Է��ϼ���.");
			form.phone2.focus(); return;
		}
		if(form.phone3.value.length==0) {
			alert("����� �ڵ����� �Է��ϼ���.");
			form.phone3.focus(); return;
		}

		if(form.mail.value.length==0) {
			alert("�̸����� �Է��ϼ���.");
			form.mail.focus(); return;
		}
		if(!IsMailCheck(form.mail.value)) {
			alert("�̸��� ������ �����ʽ��ϴ�.\n\nȮ���Ͻ� �� �ٽ� �Է��ϼ���.");
			form.mail.focus(); return;
		}

		if(form.contents.value.length==0) {
			alert("�󼼹��ǳ��� �Է��ϼ���.");
			form.contents.focus(); return;
		}

		form.action = '/front/venderProposal.process.php';
		form.method = 'POST';
		form.submit();

	}

	function f_addr_search(form,post,addr,gbn) {
		window.open("/front/addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");
	}
	//-->
</SCRIPT>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
	<!--
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
	//-->
</script>
</HEAD>

<? include ($Dir.MainDir.$_data->menu_type.".php"); ?>

	<style>
		.partnerinfoL { width:160px; padding:10px 0px;}
		.partnerinfoL2 { width:160px;padding:10px 0px;}
		.partnerinfoR {padding:10px 0px;}
	</style>

	<!-- ���� �� �������� ������ ��� �޴� -->
	<div class="currentTitle">
		<div class="categoryTitle"style="margin-top:30px;padding: 0px 0px 35px 0px;
    letter-spacing: -1px;
    color: #4a4a4a;
    font-size: 33px;
    text-align: center;
    font-family: "Noto Sans KR",Dotum,sans-serif;">���� �� ��������</div>
		<!--<div class="current"><img src="/data/design/img/sub/icon_home.gif" border="0" alt="" /> Ȩ &gt; <SPAN class="nowCurrent">���� �� ��������</span></div>-->
	</div>
	<!-- ���� �� �������� ������ ��� �޴� -->

	<div style="width:70%;margin:0px auto;padding:20px 30px;border:1px solid #ededed;overflow:hidden;margin-bottom:40px;">
		<p style="color:#F02800;">(��)�� �ʼ��Է� �׸��Դϴ�.</p>
		<table cellpadding="0" cellspacing="6" width="100%">
			<FORM name="proposalFrom">
			<tr>
				<td class="partnerinfoL"><font color="#F02800">��</font>���ǳ���</td>
				<td class="partnerinfoR">
					<?
						$sql = "SELECT * FROM `tblVenderProposalType` ";
						$result=mysql_query($sql,get_db_conn());
						while($row=mysql_fetch_object($result)) {
							$sel = ( $sel_i == 0 ) ? "checked":"";
							$sel_i++;
							echo "<input type=\"radio\"  class=\"radio\" name=\"type\" id='name".$row->idx."' value=\"".$row->name."\" ".$sel."><label style='cursor:hand;' onMouseOver=\"style.textDecoration='underline';\" onMouseOut=\"style.textDecoration='none';\" for='name".$row->idx."'>".$row->name."</label>&nbsp;&nbsp;";
						}
					?>
				</td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL"><font color="#F02800">��</font>ȸ���</td>
				<td class="partnerinfoR"><input type="text" name="company" maxlength="20" style="width:360px; " class="input"></td>
			</tr>
			<tr>
				<td class="partnerinfoL"><font color="#F02800">��</font>����� ������ �ּ�</td>
				<td class="partnerinfoR">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<input type="text" name="home_post" id="home_post" value="" style="width:80px; " class="input" readonly>
								<A class=btn_gray board_list hideFocus style="selector-dummy: true" onfocus=this.blur(); href="javascript:addr_search_for_daumapi('home_post','home_addr1','');" ><span>�ּҰ˻�</span></A>
								<!--a href="javascript:f_addr_search('proposalFrom','home_post','home_addr1',2);"><img src="/images/common/mbjoin/001/memberjoin_skin1_btn2.gif" border="0" align="absmiddle" hspace="3"></a-->
							</td>
						</tr>
						<tr>
							<td><input type=text name=home_addr1 id=home_addr1 value="" maxlength=100 readonly style="width:360px; " class="input"></td>
						</tr>
						<tr>
							<td><input type=text name=home_addr2 id=home_addr2 value="" maxlength=100 style="width:360px; " class="input"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL"><font color="#F02800">��</font>����� ��</td>
				<td class="partnerinfoR"><input type="text" name="name" maxlength="20" style="width:100px; " class="input"></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL"><font color="#F02800">��</font>��ȭ��ȣ</td>
				<td class="partnerinfoR">
					<input type="text" name="tell1" maxlength="4" style="width:50px; " class="input">
					-
					<input type="text" name="tell2" maxlength="4" style="width:60px; " class="input">
					-
					<input type="text" name="tell3" maxlength="4" style="width:60px; " class="input">
				</td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL"><font color="#F02800">��</font>�޴���</td>
				<td class="partnerinfoR">
					<select name="phone1" class="select">
						<option value="X" selected="selected">����</option>
						<option value="010">010</option>
						<option value="011">011</option>
						<option value="016">016</option>
						<option value="017">017</option>
						<option value="018">018</option>
						<option value="019">019</option>
					</select>
					-
					<input type="text" name="phone2" maxlength="4" style="width:60px; " class="input"> - <input type="text" name="phone3" maxlength="4" style="width:60px; " class="input">
				</td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL"><font color="#F02800">��</font>�̸���</td>
				<td class="partnerinfoR"><input type="text" name="mail" maxlength="40" style="width:360px; " class="input"></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL2">������Ʈ �ּ�</td>
				<td class="partnerinfoR"><input type="text" name="site" style="width:360px; " class="input"></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL2">���⵵ �����</td>
				<td class="partnerinfoR"><input type="text" name="preSell" maxlength="20" style="width:100px; " class="input"></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL2">������</td>
				<td class="partnerinfoR"><input type="text" name="memNo" maxlength="10" style="width:100px; " class="input"></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL2">���ո�, ���¸��� ��<br />�� �� ������</td>
				<td class="partnerinfoR"><textarea name="mall" style="width:100%; height:80px;" class="textarea"></textarea></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL"><font color="#F02800">��</font>�� ���ǳ���</td>
				<td class="partnerinfoR"><textarea name="contents" style="width:100%; height:160px;" class="textarea"></textarea></td>
			</tr>
			<input type="hidden" name="mode" value="venderProposalInsert">
			</FORM>

			<tr><td colspan="2" height="10"></tr>
			<tr><td colspan="2" align="center" style="padding:20px 0px;">
			<span class="btn_grayB" onclick="sendForm(proposalFrom);" style="cursor:pointer;">�����ϱ�</span>
		</table>
	</div>

<? include ($Dir."lib/bottom.php"); ?>

</BODY>
</HTML>