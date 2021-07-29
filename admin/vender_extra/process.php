<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/admin_more.php");
switch($_POST['act']){
	case 'add':
		unset($_POST['vgidx']);
	case 'edit':
		$oinfo = array();
		$indata = array();
		if(_isInt($_POST['vgidx'])){			
			$sql = "select * from vender_group where vgidx='".$_POST['vgidx']."' limit 1";
			if(false === $res = mysql_query($sql,get_db_conn())){
				_alert('원정보를 찾을수 없습니다.','-1');
				exit;
			}else if(mysql_num_rows($res) < 1){
				_alert('원정보를 찾을수 없습니다.','-1');
				exit;
			}
			$oinfo = mysql_fetch_assoc($res);
		}
	
		if(_array($_FILES['vgicon']) && $_FILES['vgicon']['error'] != 4){		
			if($_FILES['vgicon']['error'] > 0){ _alert('파일 업로드 오류','-1'); exit; }					
			
			$imginfo = getimagesize($_FILES['vgicon']['tmp_name']);
			switch($imginfo[2]){
				case '1': $iconext = 'gif'; break;
				case '2': $iconext = 'jpg'; break;
				case '3': $iconext = 'png'; break;
				default: _alert('아이콘은 gif,jpg,png 형태만 가능합니다.','-1'); exit;
			}
			
			if(!_empty($oinfo['vgicon'])) @unlink($Dir.DataDir."shopimages/vender/".$oinfo['vgicon']);				
			
			$fname = 'ico_'.time();
			$index = 0;
			do $indata['vgicon'] = $fname.(($index++ > 0)?"_".$index:'').".".$iconext;
			while(file_exists($Dir.DataDir."shopimages/vender/".$indata['vgicon']));

			if(!move_uploaded_file($_FILES['vgicon']['tmp_name'],$Dir.DataDir."shopimages/vender/".$indata['vgicon'])){ _alert('파일 저장 실패','-1'); exit; }
			
			$indata['vgicon'] = _escape($indata['vgicon']);
		}
		
		$indata['vgname'] = _escape($_POST['vgname']);
		$indata['vgyearsell'] = _escape($_POST['vgyearsell']);
		$indata['vgcommi_self'] = _escape($_POST['vgcommi_self']);
		$indata['vgcommi_main'] = _escape($_POST['vgcommi_main']);
	
		$sql = '';
		foreach($indata as $cul=>$val){
			if($cul == 'vgidx') continue;
			$sql .= $cul."=".$val.",";
		}
		$sql = substr($sql,0,-1);
		if(_array($oinfo)) $sql = "update vender_group set ".$sql." where vgidx='".$oinfo['vgidx']."'";
		else $sql = "insert into vender_group set ".$sql;
		
		if(false === mysql_query($sql,get_db_conn())) _alert('DB 입력 오류','-1');
		else _alert('정상 처리 되었습니다.','/admin/vender_group.php');	
		break;
	case 'getinfo': // json 처리
		$result = array('msg'=>'success','items'=>array());
		if(!_isInt($_REQUEST['vgidx'])) $result['msg'] =  '식별 번호가 올바르지 않습니다.';
		else{
			$sql = "select * from vender_group where vgidx='".$_REQUEST['vgidx']."' limit 1";
			if(false === $res = mysql_query($sql,get_db_conn())) $result['msg'] = 'DB 오류로 정보를 조회 할 수 없습니다.';
			else if(mysql_num_rows($res) < 1) $result['msg'] = '정보를 찾을수 없습니다.';
			else $result['items'] = mysql_fetch_assoc($res);
		}
		if(str_replace(".","",phpversion()) >= 520 ) array_walk($result,'_encode');		
		echo json_encode($result);
		break;
	case 'delete': // json 처리
		if(!_isInt($_REQUEST['vgidx'])) _alert('식별 번호가 올바르지 않습니다.','-1');
		else{
			$sql = "delete from vender_group where vgidx='".$_REQUEST['vgidx']."' limit 1";
			if(false === $res = mysql_query($sql,get_db_conn())) _alert('삭제 오류','-1');
			else _alert('삭제 되었습니다.','/admin/vender_group.php');
		}
		break;
	default:
		break;
}
?>