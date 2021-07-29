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
			alert("회사명를 입력하세요.");
			form.company.focus(); return;
		}
		if(form.home_addr1.value.length==0) {
			alert("사업장 주소를 입력하세요.");
			//f_addr_search('proposalFrom','home_post','home_addr1',2); return;
		}
		if(form.home_addr2.value.length==0) {
			alert("사업장 상세 주소를 입력하세요.");
			form.home_addr2.focus(); return;
		}

		if(form.name.value.length==0) {
			alert("담당자 성명을 입력하세요.");
			form.name.focus(); return;
		}

		if(form.tell1.value.length==0) {
			alert("담당자 전화번호를 입력하세요.");
			form.tell1.focus(); return;
		}
		if(form.tell2.value.length==0) {
			alert("담당자 전화번호를 입력하세요.");
			form.tell2.focus(); return;
		}
		if(form.tell3.value.length==0) {
			alert("담당자 전화번호를 입력하세요.");
			form.tell3.focus(); return;
		}

		if(form.phone1.value=='X') {
			alert("담당자 핸드폰 앞자리를 선택하세요.");
			form.phone1.focus(); return;
		}
		if(form.phone2.value.length==0) {
			alert("담당자 핸드폰을 입력하세요.");
			form.phone2.focus(); return;
		}
		if(form.phone3.value.length==0) {
			alert("담당자 핸드폰을 입력하세요.");
			form.phone3.focus(); return;
		}

		if(form.mail.value.length==0) {
			alert("이메일을 입력하세요.");
			form.mail.focus(); return;
		}
		if(!IsMailCheck(form.mail.value)) {
			alert("이메일 형식이 맞지않습니다.\n\n확인하신 후 다시 입력하세요.");
			form.mail.focus(); return;
		}

		if(form.contents.value.length==0) {
			alert("상세문의내용 입력하세요.");
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
	//-->
</script>
</HEAD>

<? include ($Dir.MainDir.$_data->menu_type.".php"); ?>

	<style>
		.partnerinfoL { width:160px; padding:10px 0px;}
		.partnerinfoL2 { width:160px;padding:10px 0px;}
		.partnerinfoR {padding:10px 0px;}
	</style>

	<!-- 제휴 및 입점문의 페이지 상단 메뉴 -->
	<div class="currentTitle">
		<div class="categoryTitle"style="margin-top:30px;padding: 0px 0px 35px 0px;
    letter-spacing: -1px;
    color: #4a4a4a;
    font-size: 33px;
    text-align: center;
    font-family: "Noto Sans KR",Dotum,sans-serif;">제휴 및 입점문의</div>
		<!--<div class="current"><img src="/data/design/img/sub/icon_home.gif" border="0" alt="" /> 홈 &gt; <SPAN class="nowCurrent">제휴 및 입점문의</span></div>-->
	</div>
	<!-- 제휴 및 입점문의 페이지 상단 메뉴 -->

	<div style="width:70%;margin:0px auto;padding:20px 30px;border:1px solid #ededed;overflow:hidden;margin-bottom:40px;">
		<p style="color:#F02800;">(＊)는 필수입력 항목입니다.</p>
		<table cellpadding="0" cellspacing="6" width="100%">
			<FORM name="proposalFrom">
			<tr>
				<td class="partnerinfoL"><font color="#F02800">＊</font>문의내용</td>
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
				<td class="partnerinfoL"><font color="#F02800">＊</font>회사명</td>
				<td class="partnerinfoR"><input type="text" name="company" maxlength="20" style="width:360px; " class="input"></td>
			</tr>
			<tr>
				<td class="partnerinfoL"><font color="#F02800">＊</font>사업장 소재지 주소</td>
				<td class="partnerinfoR">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<input type="text" name="home_post" id="home_post" value="" style="width:80px; " class="input" readonly>
								<A class=btn_gray board_list hideFocus style="selector-dummy: true" onfocus=this.blur(); href="javascript:addr_search_for_daumapi('home_post','home_addr1','');" ><span>주소검색</span></A>
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
				<td class="partnerinfoL"><font color="#F02800">＊</font>담당자 명</td>
				<td class="partnerinfoR"><input type="text" name="name" maxlength="20" style="width:100px; " class="input"></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL"><font color="#F02800">＊</font>전화번호</td>
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
				<td class="partnerinfoL"><font color="#F02800">＊</font>휴대폰</td>
				<td class="partnerinfoR">
					<select name="phone1" class="select">
						<option value="X" selected="selected">선택</option>
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
				<td class="partnerinfoL"><font color="#F02800">＊</font>이메일</td>
				<td class="partnerinfoR"><input type="text" name="mail" maxlength="40" style="width:360px; " class="input"></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL2">웹사이트 주소</td>
				<td class="partnerinfoR"><input type="text" name="site" style="width:360px; " class="input"></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL2">전년도 매출액</td>
				<td class="partnerinfoR"><input type="text" name="preSell" maxlength="20" style="width:100px; " class="input"></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL2">직원수</td>
				<td class="partnerinfoR"><input type="text" name="memNo" maxlength="10" style="width:100px; " class="input"></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL2">종합몰, 오픈마켓 및<br />그 외 입점몰</td>
				<td class="partnerinfoR"><textarea name="mall" style="width:100%; height:80px;" class="textarea"></textarea></td>
			</tr>
			<tr><td colspan="2" height="1" bgcolor="#E9E9E9"></td></tr>

			<tr>
				<td class="partnerinfoL"><font color="#F02800">＊</font>상세 문의내용</td>
				<td class="partnerinfoR"><textarea name="contents" style="width:100%; height:160px;" class="textarea"></textarea></td>
			</tr>
			<input type="hidden" name="mode" value="venderProposalInsert">
			</FORM>

			<tr><td colspan="2" height="10"></tr>
			<tr><td colspan="2" align="center" style="padding:20px 0px;">
			<span class="btn_grayB" onclick="sendForm(proposalFrom);" style="cursor:pointer;">문의하기</span>
		</table>
	</div>

<? include ($Dir."lib/bottom.php"); ?>

</BODY>
</HTML>