function cateAjax(type,code){
	var _object = document.getElementById('catelistwrap');
	$.ajax({
		type:'POST',
		url:'/m/inc/cate_ajax.php',
		dataType:"html",
		data:{code:code,type:type},
		success:function(msg){
			
			if(type!='p'){

				$('.btn_box_m').hide();
				$('.btn_box_p').show();
			}else{
				if(_object.style.display != 'block'){
					alert("ī�װ��� ����� �����Ǿ��ֽ��ϴ�.");
				}
				
				$('.btn_box_p').hide();
				$('.btn_box_m').show();
			}
			$('#catelist').html(decodeURIComponent(msg));
		}
	});
}


function cateAll(){
	var _object = document.getElementById('catelistwrap');

	if(_object.style.display == 'block'){
		_object.style.display = 'none';
		$('#allList').text('��ġ��');
	}else{
		_object.style.display = 'block';
		$('#allList').text('����');
	}
}