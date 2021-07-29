<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("../access.php");
include_once($Dir."lib/admin_more.php");
include_once($Dir."lib/ext/product_func.php");
//if(!_isInt($_REQUEST['pridx'])) _alert('상품 식별 번호가 전달 되지 않았습니다.','0');
if(!is_numeric($_REQUEST['pridx'])) _alert('상품 식별 번호가 전달 되지 않았습니다.','0');

// 임시 파일 삭제
$chkStamp = strtotime('-1 day');
$sql = "select * from product_multicontents where pridx < 1 and pridx >= -".$chkStamp."";
if(false !== $chkres = mysql_query($sql,get_db_conn())){
	if(mysql_num_rows($chkres)){
		while($info = mysql_fetch_assoc($chkres)){
			if($info['type'] == 'img'){
				@unlink($imagepath.'/'.$info['cont']);
				@unlink($imagepath.'/thumb_'.$info['cont']);
			}
		}
		$sql = "delete from product_multicontents where pridx < 1 and pridx >= -".$chkStamp."";
		@mysql_query($sql,get_db_conn());
	}
}

$sql = "select * from product_multicontents where pridx='".$_REQUEST['pridx']."'";
if(false === $res = mysql_query($sql,get_db_conn())) _alert('데이터 베이스 연결 오류','0');
$items = array();

$imagepath=$Dir.DataDir."shopimages/multi/";
if(mysql_num_rows($res)){
	while($row = mysql_fetch_assoc($res)){
		if($row['type'] == 'img'){
			if(file_exists($imagepath.'/'.$row['cont'])) $row['content'] = '<img src="'.$imagepath.'/thumb_'.$row['cont'].'" >';			
			else continue;
		}else{
			$row['content'] = htmlspecialchars($row['cont']);
		}
		array_push($items,$row);
	}
}
?>
<body style="margin:0px; padding:0px;">
<style type="text/css">
.tableBase{border-top:1px solid #b9b9b9;font-size:12px;}
.tableBase caption{text-align:left; background:#000; color:#fff; padding:5px; font-weight:bold; }
.tableBase th{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;background:#f8f8f8;}
.tableBase .firstTh{border-left:none;background:#f8f8f8;}
.tableBase td{padding:8px 0px;border-bottom:1px solid #ededed;border-left:1px solid #ededed;}
.tableBase .firstTd{padding-left:10px;border-left:none;}
</style>
<script language="javascript" type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery-ui-1.9.2.custom.min.js"></script>
<script language="javascript" type="text/javascript">
function toggleInputCont(){
	var f = document.contForm;
	type = $(f).find('select[name=type]').val();
	$.each($(f).find('[name^=cont]'),function(idx,el){
		if($(el).attr('name') == 'cont_'+type){
			$(el).css('display','block');	
		}else{
			$(el).css('display','none');	
		}
	});	
}
function checkVal(){
	var f = document.contForm;
	if(f.act.value == 'delete') return true;
	contname = 'cont_'+$(f).find('select[name=type]').val();
	contel = $('[name='+contname+']');
	if($.trim($(contel).val()).length < 1){
		alert('내용을 입력하세요');
		return false;
	}
	return true;
}

function resetForm(){
	var f = document.contForm;
	f.act.value = 'save';
	f.midx.value = '';
	f.type.options[0].selected = true;
	f.cont_img.value = '';
	f.cont_code.value = '';
	f.cont_img.style.display = 'block';
	f.cont_code.style.display = 'none';	
}

function deleteEl(midx){
	var f = document.contForm;
	if(confirm('정말 삭제 하시겠습니까?')){
		f.act.value= 'delete';
		f.midx.value = midx;
		f.submit();
	}
}

function editEl(midx){
	resetForm();
	
	var f = document.contForm;
	f.act.value= 'save';
	f.midx.value = midx;
	var el = $('#cont_'+midx);
	type = $(el).attr('cont_type');	
	cont = $(el).find('.rcont:eq(0)').html();	
	$(f).find('select[name=type]>option[value='+type+']').attr('selected',true);
	toggleInputCont();
	$(f).find('[name=cont_'+type+']').val(cont);
	
}
</script>
<form name="contForm" method="post" action="process.php" enctype="multipart/form-data" onSubmit="javascript:return checkVal()">
<input type="hidden" name="act" value="save" />
<input type="hidden" name="pridx" value="<?=$_REQUEST['pridx']?>">
<input type="hidden" name="midx" value="" />
<table border="0" cellpadding="0" cellspacing="0" class="tableBase" style="width:100%">
	<tr>
		<th style="width:120px;" class="firstTh">구분</th>
		<th>내용</th>
	</tr>
	<tr>
		<td style="text-align:center"><select name="type" onChange="toggleInputCont()">
				<option value="img">이미지</option>
				<option value="code">동영상url</option>
			</select>
		</td>
		
		<td><input type="file" name="cont_img">
			<textarea name="cont_code" style="width:100%; height:30px; display:none"></textarea>
		</td>
	</tr>	
</table>
<div style="text-align:center">
	<input type="submit" value="저장" style="margin-right:5px;" />
	<input type="button" value="취소" onClick="javascript:resetForm();" />
</div>
</form>

<table border="0" cellpadding="0" cellspacing="0" class="tableBase" style="width:100%">
	<caption>멀티 컨텐츠 관리</caption>
	<tr>
		<th class="firstTh" style="width:30px;">No.</th>
		<th style="width:80px;">구분</th>
		<th>내용</th>
		<th style="width:100px;">관리</th>
	</tr>	
	<?
	if(_array($items)){
	//	$vno = count($items);
		$vno = 1;
		foreach($items as $item){ ?>
	<tr id="cont_<?=$item['midx']?>" cont_type="<?=$item['type']?>">
		<td class="firstTd"><?=$vno++?></td>
		<td><?=($item['type']=='img'?'이미지':'동영상url')?></td>
		<td><?=$item['content']?><span class="rcont" style="display:none"><?=$item['cont']?></span></td>
		<td style="text-align:center"><input type="button" value="수정" onClick="javascript:editEl('<?=$item['midx']?>')" /><input type="button" value="삭제" onClick="javascript:deleteEl('<?=$item['midx']?>')" /></td>
	</tr>
	<?	}//end foreach
	}else{ ?>
	<tr>
		<td colspan="4" style=" text-align:center">등록된 컨텐츠가 없습니다.</td>
	</tr>
<?	}
	?>
</table>
</body>