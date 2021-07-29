//�̸���&���̵�üũ
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
				case '110' : msg.html('�̸��� �ּҸ� �Է��ϼ���.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '120' : msg.html('�̸��� �ּҰ� ���Ŀ� ���� �ʽ��ϴ�.').css('display', '');$j('#'+mail).css('background', ''); break;
				//case '130' : msg.html('�̹� �����ϴ� �̸��� �ּ��Դϴ�.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '130' : msg.html($j('#'+mail).val()+'�� �̹� ���Ե� �����Դϴ�.<br />'+$j('#'+mail).val()+'���� �ٷ� �α����� �ּ���.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '000' : msg.html('����ϼŵ� ���� �̸��� �ּ��Դϴ�.').css('display', 'none');$j('#'+mail).css('background', '#fff'); break;
				default : alert( '�߸��� �����Դϴ�.\n\n' + result ); break;
			}
		}
	});
}

//�̸���&���̵�üũ2
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
				case '110' : msg.html('�̸��� �ּҸ� �Է��ϼ���.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '120' : msg.html('�̸��� �ּҰ� ���Ŀ� ���� �ʽ��ϴ�.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '130' : msg.html('�̹� �����ϴ� �̸��� �ּ��Դϴ�.').css('display', '');$j('#'+mail).css('background', ''); break;
				case '001' : msg.html('����ȸ���Դϴ�. �̸��� ������ �ڵ����� �����˴ϴ�.').css('display', '');$j('#'+mail).css('background', '#2da7e4'); break;
				case '000' : msg.html('����ϼŵ� ���� �̸��� �ּ��Դϴ�.').css('display', 'none');$j('#'+mail).css('background', '#2da7e4'); break;
				default : alert( '�߸��� �����Դϴ�.\n\n' + result ); break;
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
				case '111' : alert( '�̸��� �ּҰ� �߸� �ԷµǾ����ϴ�.'); break;
				case '222' : alert( '�̸��� ���������� �������� �ʽ��ϴ�.'); break;
				case '333' : alert( '�̸��� �ּҿ� ������ȣ�� �Ѿ���� ���Ͽ����ϴ�.'); break;
				case '000' : alert( '���������� �߼��Ͽ����ϴ�.'); break;
				default : alert( '�߸��� �����Դϴ�.'); break;
			}
		}
	});
	}else{
		alert('�̸��� �ּҰ� �߸� �ԷµǾ����ϴ�. Ȯ���Ͽ� �ּ���.');
	}
	//���Ϲ߼�
}

function cert_key_ok(){
	if(cert_key == $j('#cret_num').val()){
		alert('�̸��� ������ �Ϸ�Ǿ����ϴ�.');
		//����Ȯ�ΰ� �Է�
		$j('#email_cert2').css({"display":"none"});
		$j("#cert_value").val("000");
	}else{
		alert('������ȣ�� �߸� �ԷµǾ����ϴ�.');
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
				case '111' : alert( '�̸��� �ּҰ� �߸� �ԷµǾ����ϴ�.'); break;
				case '000' : alert( '���������� �߼��Ͽ����ϴ�.'); break;
				default : alert( '�߸��� �����Դϴ�.'); break;
			}
		}
	});
	//���Ϲ߼�
}

function cert_key_ok_Simple(){
	if(cert_key_Simple == $j('#cret_numSimple').val()){
		alert('�̸��� ������ �Ϸ�Ǿ����ϴ�.');
		//����Ȯ�ΰ� �Է�
		$j('#email_cert2Simple').css({"display":"none"});
		$j("#cert_valueSimple").val("000");
	}else{
		alert('������ȣ�� �߸� �ԷµǾ����ϴ�.');
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
				case '111' : alert( '�̸��� �ּҰ� �߸� �ԷµǾ����ϴ�.'); break;
				case '000' : alert( '���������� �߼��Ͽ����ϴ�.'); break;
				default : alert( '�߸��� �����Դϴ�.'); break;
			}
		}
	});
	//���Ϲ߼�
}

function cert_key_ok_Plus(){
	if(cert_key_Plus == $j('#cret_numPlus').val()){
		alert('�̸��� ������ �Ϸ�Ǿ����ϴ�.');
		//����Ȯ�ΰ� �Է�
		$j('#email_cert2Plus').css({"display":"none"});
		$j("#cert_valuePlus").val("000");
	}else{
		alert('������ȣ�� �߸� �ԷµǾ����ϴ�.');
	}
}
