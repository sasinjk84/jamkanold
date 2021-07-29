
<script language=javascript>  
<!--
    var CBA_window; 

    function openPCCWindow(){
        var CBA_window = window.open('', 'PCCWindow', 'width=430, height=560, resizable=1, scrollbars=no, status=0, titlebar=0, toolbar=0, left=300, top=200' );

        if(CBA_window == null){ 
			 alert(" ※ 윈도우 XP SP2 또는 인터넷 익스플로러 7 사용자일 경우에는 \n    화면 상단에 있는 팝업 차단 알림줄을 클릭하여 팝업을 허용해 주시기 바랍니다. \n\n※ MSN,야후,구글 팝업 차단 툴바가 설치된 경우 팝업허용을 해주시기 바랍니다.");
        }

        document.reqCBAForm.action = '/Siren24/pcc_V3_popup_seed.php';
        document.reqCBAForm.target = 'PCCWindow';
		document.reqCBAForm.submit();

		//return true;
    }	
openPCCWindow();
//-->
</script>
<!-- 본인확인서비스 요청 form --------------------------->
<form name="reqCBAForm" method="post" action = "" onsubmit="return openPCCWindow()">
    <input type="hidden" name="reqInfo"     value = "<? echo "$enc_reqInfo" ?>">
    <input type="hidden" name="retUrl"      value = "<? echo "$retUrl" ?>">
    <input type="hidden" name="verSion"		value = "1"> <!--모듈 버전정보-->
</form>
<!-- 본인확인서비스 요청 form --------------------------->