
/* Read Me */


******회원가입페이지***********
경로 : /front/member_join.php
설명 : $loginType 이 naver, tvcf 인 경우 해당업체에서 넘어오는 id 값 + 잠깐닷컴에서 발급하는 랜덤문자+숫자 값을 아이디로 사용함


******회원탈퇴***********
경로 : /lib/loginprocess.php	/admin/member_outlist.php
설명 : 회원의 탈퇴요청이 있는 경우 관리자확인 후 탈퇴처리함
	아이디제외하고 이메일포함 모든정보가 삭제처리됨