//이메일&아이디체크
var email_check = function(mail) {
	$j.ajax({
		type: 'POST',
		url: ck_path+'/lib/ajax_email_check.php',
		data: {
			'email': encodeURIComponent($j('#'+mail).val())
		},
		cache: false,
		async: false,
		success: function(result) {
		var msg = $j('#msg_'+mail);
			$j('#'+mail+'_enabled').val(result);
			switch(result) {
				case '110' : msg.html('이메일 주소를 입력하세요.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '120' : msg.html('이메일 주소가 형식에 맞지 않습니다.').css('display', '');$j('#'+mail).css('background', ''); break;
				//case '130' : msg.html('이미 존재하는 이메일 주소입니다.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '130' : msg.html($j('#'+mail).val()+'은 이미 가입된 계정입니다.<br />'+$j('#'+mail).val()+'으로 바로 로그인해 주세요.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '000' : msg.html('사용하셔도 좋은 이메일 주소입니다.').css('display', 'none');$j('#'+mail).css('background', '#fff'); break;
				default : alert( '잘못된 접근입니다.\n\n' + result ); break;
			}
		}
	});
}

//이메일&아이디체크2
var email_check2 = function(mail) {
	$j.ajax({
		type: 'POST',
		url: ck_path+'/lib/ajax_email_check2.php',
		data: {
			'email': encodeURIComponent($j('#'+mail).val())
		},
		cache: false,
		async: false,
		success: function(result) {
		var msg = $j('#msg_'+mail);
			$j('#'+mail+'_enabled').val(result);
			switch(result) {
				case '110' : msg.html('이메일 주소를 입력하세요.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '120' : msg.html('이메일 주소가 형식에 맞지 않습니다.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '130' : msg.html('이미 존재하는 이메일 주소입니다.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '001' : msg.html('기존회원입니다. 이메일 인증시 자동으로 연동됩니다.').css('display', '');$j('#'+mail).css('background', '#2da7e4'); break;
				case '000' : msg.html('사용하셔도 좋은 이메일 주소입니다.').css('display', 'none');$j('#'+mail).css('background', '#2da7e4'); break;
				default : alert( '잘못된 접근입니다.\n\n' + result ); break;
			}
		}
	});
}

function cert_key_open(){
	$j('#email_cert').css({"display":"none"});
	$j('#email_cert2').fadeIn();
	$j('#email').prop('readonly', true);
	cert_key_go();
}

var cert_key;
var cert_key_go = function(){
	var chars = "0123456789";
	var string_length = 7;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
	var rnum = Math.floor(Math.random() * chars.length);
	randomstring += chars.substring(rnum,rnum+1);
	}
	cert_key = randomstring;
	if($j('#email').val() != '' ){
	$j.ajax({
		type: 'POST',
		url: ck_path+'/lib/ajax_cert_key_mall.php',
		data: {
			'email': encodeURIComponent($j('#email').val()),
			'cert_key': encodeURIComponent(cert_key)
		},
		cache: false,
		async: false,
		success: function(result) {
			switch(result) {
				case '111' : alert( '이메일 주소가 잘못 입력되었습니다.'); break;
				case '222' : alert( '이메일 본문내용이 존재하지 않습니다.'); break;
				case '333' : alert( '이메일 주소와 인증번호가 넘어오지 못하였습니다.'); break;
				case '000' : alert( '인증메일을 발송하였습니다.'); break;
				default : alert( '잘못된 접근입니다.'); break;
			}
		}
	});
	}else{
		alert('이메일 주소가 잘못 입력되었습니다. 확인하여 주세요.');
	}
	//메일발송
}

function cert_key_ok(){
	if(cert_key == $j('#cret_num').val()){
		alert('이메일 인증이 완료되었습니다.');
		//인증확인값 입력
		$j('#email_cert2').css({"display":"none"});
		$j("#cert_value").val("000");
	}else{
		alert('인증번호가 잘못 입력되었습니다.');
	}
}

///-------

function cert_key_open_Simple(){
	$j('#email_certSimple').css({"display":"none"});
	$j('#email_cert2Simple').fadeIn();
	$j('#emailSimple').prop('readonly', true);
	cert_key_go_Simple();
}

var cert_key_Simple;
var cert_key_go_Simple = function(){
	var chars = "0123456789";
	var string_length = 7;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
	var rnum = Math.floor(Math.random() * chars.length);
	randomstring += chars.substring(rnum,rnum+1);
	}
	cert_key_Simple = randomstring;
	$j.ajax({
		type: 'POST',
		url: ck_path+'/lib/ajax_cert_key_mall.php',
		data: {
			'email': encodeURIComponent($j('#emailSimple').val()),
			'cert_key': encodeURIComponent(cert_key_Simple)
		},
		cache: false,
		async: false,
		success: function(result) {
			switch(result) {
				case '111' : alert( '이메일 주소가 잘못 입력되었습니다.'); break;
				case '000' : alert( '인증메일을 발송하였습니다.'); break;
				default : alert( '잘못된 접근입니다.'); break;
			}
		}
	});
	//메일발송
}

function cert_key_ok_Simple(){
	if(cert_key_Simple == $j('#cret_numSimple').val()){
		alert('이메일 인증이 완료되었습니다.');
		//인증확인값 입력
		$j('#email_cert2Simple').css({"display":"none"});
		$j("#cert_valueSimple").val("000");
	}else{
		alert('인증번호가 잘못 입력되었습니다.');
	}
}
///-------

function cert_key_open_Plus(){
	$j('#email_certPlus').css({"display":"none"});
	$j('#email_cert2Plus').fadeIn();
	$j('#emailPlus').prop('readonly', true);
	cert_key_go_Plus();
}

var cert_key_Plus;
var cert_key_go_Plus = function(){
	var chars = "0123456789";
	var string_length = 7;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
	var rnum = Math.floor(Math.random() * chars.length);
	randomstring += chars.substring(rnum,rnum+1);
	}
	cert_key_Plus = randomstring;
	$j.ajax({
		type: 'POST',
		url: ck_path+'/lib/ajax_cert_key_mall.php',
		data: {
			'email': encodeURIComponent($j('#emailPlus').val()),
			'cert_key': encodeURIComponent(cert_key_Plus)
		},
		cache: false,
		async: false,
		success: function(result) {
			switch(result) {
				case '111' : alert( '이메일 주소가 잘못 입력되었습니다.'); break;
				case '000' : alert( '인증메일을 발송하였습니다.'); break;
				default : alert( '잘못된 접근입니다.'); break;
			}
		}
	});
	//메일발송
}

function cert_key_ok_Plus(){
	if(cert_key_Plus == $j('#cret_numPlus').val()){
		alert('이메일 인증이 완료되었습니다.');
		//인증확인값 입력
		$j('#email_cert2Plus').css({"display":"none"});
		$j("#cert_valuePlus").val("000");
	}else{
		alert('인증번호가 잘못 입력되었습니다.');
	}
}
