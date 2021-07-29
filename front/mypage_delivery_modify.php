<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");

	if(strlen($_ShopInfo->getMemid())==0){
		echo "<html><head></head><body><script>alert('잘못된 접근입니다.');window:close();</script></body></html>";
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

		$onload="<script>alert('배송지 수정이 완료되었습니다.');window.opener.location.reload();self.close();</script>";
	}

	//배송지 정보 호출
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
	<TITLE>잠깐닷컴, 잠깐 빌려 쓰고 싶을 때</TITLE>
	<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
	<META http-equiv="X-UA-Compatible" content="IE=Edge" />

	<META name="description" content="잠깐닷컴">
	<META name="keywords" content="조명,카메라,렌탈,임대,방송장비">

	<script language="javascript">
		<!--
		function CheckForm(type,idx){
			if(document.form1.receiver_name.value.length==0) {
				alert("수신자명을 입력하세요.");
				document.form1.receiver_name.focus();
				return;
			}

			if(document.form1.receiver_tel21.value.length==0) {
				alert("휴대폰 번호를 입력하세요.");
				document.form1.receiver_tel21.focus();
				return;
			}
			if(document.form1.receiver_tel22.value.length==0) {
				alert("휴대폰 번호를 입력하세요.");
				document.form1.receiver_tel22.focus();
				return;
			}
			if(document.form1.receiver_tel23.value.length==0) {
				alert("휴대폰 번호를 입력하세요.");
				document.form1.receiver_tel23.focus();
				return;
			}

			if(!IsNumeric(document.form1.receiver_tel21.value)) {
				alert("휴대폰 번호는 숫자만 입력 가능합니다.");
				document.form1.receiver_tel21.focus();
				return;
			}
			if(!IsNumeric(document.form1.receiver_tel22.value)) {
				alert("휴대폰 번호는 숫자만 입력 가능합니다.");
				document.form1.receiver_tel22.focus();
				return;
			}
			if(!IsNumeric(document.form1.receiver_tel23.value)) {
				alert("휴대폰 번호는 숫자만 입력 가능합니다.");
				document.form1.receiver_tel23.focus();
				return;
			}

			if(document.form1.rpost1.value.length==0) {
				alert("우편번호를 입력하세요.");
				document.form1.rpost1.focus();
				return;
			}
			if(document.form1.raddr1.value.length==0) {
				alert("주소를 입력하세요.");
				document.form1.raddr1.focus();
				return;
			}
			if(document.form1.raddr2.value.length==0) {
				alert("주소를 입력하세요.");
				document.form1.raddr2.focus();
				return;
			}

			if(!confirm("배송지를 수정하시겠습니까?")){
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
		<h4 style="margin:0;padding:0;height:40px;line-height:40px;float:left;padding-left:20px;box-sizing:border-box">배송지 수정</h4>
		<a href="javascript:window.close();" style="float:right;display:block;width:40px;height:40px;line-height:40px;color:#fff;font-size:30px;text-decoration:none;text-align:center">&times;</a>
	</div>
	<div style="margin:0 auto;padding:30px;box-sizing:border-box;background:#fff;border:1px solid #ddd;list-style:none">
		<ul style="margin:0;padding:0;margin-bottom:15px;padding-bottom:15px;border-bottom:1px solid #eee;font-size:12px;">
			<li style="margin-bottom:4px;list-style:none">- 배송지를 미리 등록하시면 주문시 수신자를 별도로 입력하지 않으셔도 됩니다.</p>
			<li style="list-style:none">- 등록된 배송지 정보는 수정/삭제 메뉴를 통해서 관리가 가능합니다.</li>
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
					<th>수신자명</th>
					<td style="padding:5px 10px;"><input type="text" name="receiver_name" value="<?=$row->receiver_name?>" maxlength="10" class="input" style="width:44%;" /></td>
				</tr>
				<tr>
					<th>전화번호</th>
					<td style="padding:5px 10px;">
						<input type="text" name="receiver_tel11" value="<?=$receiver_tel11?>" maxlength="4" class="input" style="width:20%;" /> - 
						<input type="text" name="receiver_tel12" value="<?=$receiver_tel12?>" maxlength="4" class="input" style="width:20%;" /> - 
						<input type="text" name="receiver_tel13" value="<?=$receiver_tel13?>" maxlength="4" class="input" style="width:20%;" />
					</td>
				</tr>
				<tr>
					<th>휴대폰번호</th>
					<td style="padding:5px 10px;">
						<input type="text" name="receiver_tel21" value="<?=$receiver_tel21?>" maxlength="4" class="input" style="width:20%;" /> - 
						<input type="text" name="receiver_tel22" value="<?=$receiver_tel22?>" maxlength="4" class="input" style="width:20%;" /> - 
						<input type="text" name="receiver_tel23" value="<?=$receiver_tel23?>" maxlength="4" class="input" style="width:20%;" />
					</td>
				</tr>
				<tr>
					<th>이메일</th>
					<td style="padding:5px 10px;"><input type="text" name="receiver_email" value="<?=$row->receiver_email?>" maxlength="40" class="input" style="width:100%;" /></td>
				</tr>
				<tr>
					<th>주소</th>
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
    margin: 0px 1px 0px 0px;"><span class="btn_s_line2">주소검색</span></a>
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
    margin: 0px 1px 0px 0px;"><span class="btn_s_line2">배송지 수정하기</span></a></div>
		</form>
	</div>

	<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
	<script type="text/javascript">
		function addr_search_for_daumapi(post,addr1,addr2) {
			new daum.Postcode({
				oncomplete: function(data) {
					// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

					// 각 주소의 노출 규칙에 따라 주소를 조합한다.
					// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
					var fullAddr = ''; // 최종 주소 변수
					var extraAddr = ''; // 조합형 주소 변수

					// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
					if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
						fullAddr = data.roadAddress;

					} else { // 사용자가 지번 주소를 선택했을 경우(J)
						fullAddr = data.jibunAddress;
					}

					// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
					if(data.userSelectedType === 'R'){
						//법정동명이 있을 경우 추가한다.
						if(data.bname !== ''){
							extraAddr += data.bname;
						}
						// 건물명이 있을 경우 추가한다.
						if(data.buildingName !== ''){
							extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
						}
						// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
						fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
					}

					// 우편번호와 주소 정보를 해당 필드에 넣는다.
					document.getElementById(post).value = data.zonecode; //5자리 새우편번호 사용
					document.getElementById(addr1).value = fullAddr;

					// 커서를 상세주소 필드로 이동한다.
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