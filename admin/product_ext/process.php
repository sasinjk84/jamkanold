<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include ("../access.php");
include_once($Dir."lib/admin_more.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/class/thumbnail.php");

$imagepath=$Dir.DataDir."shopimages/multi";
//if(!_isInt($_POST['pridx'])) _alert('상품 식별 번호 전달 오류','-1');
if(!is_numeric($_POST['pridx'])) _alert('상품 식별 번호 전달 오류','-1');
if($_POST['pridx'] > 0){
	$sql = "select productcode from tblproduct where pridx='".$_POST['pridx']."'";
	if(false === $res = mysql_query($sql,get_db_conn())) _alert('데이터 베이스 연동 오류','-1');
	if(mysql_num_rows($res) < 1) _alert('상품정보를 찾을 수 없습니다.','-1');	
	$productcode = mysql_result($res,0,0);
}else{
	$productcode = 't'.abs($_POST['pridx']);
}


switch($_POST['act']){
	case 'update':
	case 'save':
	case 'insert':
		$oinfo = array();			
		if(_isInt($_POST['midx'])){
			$sql = "select * from product_multicontents where pridx='".$_POST['pridx']."' and midx='".$_POST['midx']."' limit 1";
			if(false === $res =mysql_query($sql,get_db_conn())) _alert('데이터 베이스 연동 오류','-1');
			if(mysql_num_rows($res)) $oinfo = mysql_fetch_assoc($res);
			else _alert('원데이터를 찾을 수 없습니다.','-1');
			$where = " where pridx='".$_POST['pridx']."' and midx='".$_POST['midx']."'";
		}else{
			$sql = "select max(midx) from product_multicontents where pridx='".$_POST['pridx']."'";
			if(false === $res =mysql_query($sql,get_db_conn())) _alert('데이터 베이스 연동 오류','-1');
			if(mysql_num_rows($res)) $oinfo['midx'] = intval(mysql_result($res,0,0))+1;
			else $oifno['midx'] = 1;
			$where = '';
		}
		
		$intype = ($_POST['type'] == 'code')?'code':'img';		
		
		if($intype == 'img'){
			$block = array('exe','sh','htaccess','php','html','htm','com');		
			$allow = array('gif','jpg','jpeg','png');
			if(!is_uploaded_file($_FILES['cont_img']['tmp_name']) || _empty($_FILES['cont_img']['name'])) _alert('정상적인 업로드 파일이 아닙니다.','-1');
			
			
			clearstatcache();		
			switch ($_FILES['cont_img']["error"]){
				case "1":
					_alert("업로드한 파일이 upload_max_filesize 보다 큽니다.",'-1');
					break;					
				case "2":			
					_alert("업로드한 파일이 HTML 폼에서 지정한 MAX_FILE_SIZE 지시어보다 큽니다.",'-1');
					break;
				case "3":
					_alert("파일이 일부분만 전송되었습니다.",'-1');
					break;
				case "4":
					_alert("파일이 전송되지 않았습니다.");
					break;			
				default:
					$tempname = str_replace(array(" ","-"),"_",$_FILES['cont_img']['name']); // 이름이 중간에 공백 또는 '-'있으면 '_' 로 바꿈					
					$name = explode('.',$tempname);
					$ext = strtolower(array_pop($name));				
					$name = implode('.',$name);
					
					if(_array($allow)){
						array_walk($allow,'strtolower');
						if(!in_array($ext,$allow)) _alert("허용되지 않은 형식의 파일입니다.",'-1');
					}
					
					if(_array($block) && in_array($ext,$block)) _alert("업로드 금지된 파일 형식입니다.",'-1');
										
					$ftmp = $productcode.'_'.$oinfo['midx'];
					$index = 0;
					$tmp_name = $ftmp.".".$ext;
					
					while(file_exists($imagepath."/".$tmp_name)){
						$tmp_name = $ftmp."_".$index.".".$ext;
						$index++; 
					}	
					if(!move_uploaded_file($_FILES['cont_img']['tmp_name'],$imagepath."/".$tmp_name)) _alert("업로드 실패",'-1');
					else{
						@chmod($imagepath.'/'.$tmp_name,0706);
						$cont = $tmp_name;
					}
					
					// 20160317 JDH 다중이미지 width, height 이미지 크기 변경코드
					$is_resize = false;
					$limit_width = 860; // width 고정값
					$limit_height = 580; // hieght 고정값
					$image_size = getimagesize($imagepath."/".$tmp_name);
					if($image_size[0] > $limit_width && $image_size[0] > $image_size[1]){
						$width_size = $image_size[0];
						$height_size = $image_size[1];

						$rate_size = $height_size / $width_size;

						$image_size[0] = $limit_width;
						$image_size[1] = round($limit_width * $rate_size);

						$is_resize = true;
					} else if($image_size[1] > $limit_height && $image_size[0] < $image_size[1]){
						$width_size = $image_size[0];
						$height_size = $image_size[1];

						$rate_size = $width_size / $height_size;

						$image_size[1] = $limit_height;
						$image_size[0] = round($limit_height * $rate_size);

						$is_resize = true;
					}

					$imgobj = new thumbnail;
					if($imgobj->_read($imagepath.'/'.$tmp_name)){
						$imgobj->_make($imagepath.'/thumb_'.$tmp_name,100,100);
					}

					if($is_resize){
						if($imgobj->_read($imagepath.'/'.$tmp_name)){
							$imgobj->_make($imagepath.'/'.$tmp_name,$image_size[0],$image_size[1]);
						}
					}

					break;
			}
		}else{
			$cont = _escape($_POST['cont_code'],false);
		}
		
		if(!_empty($where)) $sql = "update product_multicontents set type='".$intype."',cont='".$cont."'".$where;
		else $sql = "insert into product_multicontents set pridx='".$_POST['pridx']."',type='".$intype."',cont='".$cont."'".$where;
		if(false === mysql_query($sql,get_db_conn())) _alert('저장중 데이터 베이스 오류가 발생 했습니다.','-1');
		else  _alert('정상 처리 되었습니다.','multicontents.php?pridx='.$_POST['pridx']);
		break;
	case 'delete':
		if(!_isInt($_POST['midx'])) _alert('삭제 대상이 지정 되지 않았습니다.','-1');
		$sql = "select * from product_multicontents where pridx='".$_POST['pridx']."' and midx='".$_POST['midx']."' limit 1";
		
		if(false === $res =mysql_query($sql,get_db_conn())) _alert('데이터 베이스 연동 오류','-1');
		if(mysql_num_rows($res)) $info = mysql_fetch_assoc($res);
		else _alert('원데이터를 찾을 수 없습니다.','-1');

		if($info['type'] == 'img'){
			@unlink($imagepath.'/'.$info['cont']);
			@unlink($imagepath.'/thumb_'.$info['cont']);
		}
		
		$sql = "delete from product_multicontents where pridx='".$_POST['pridx']."' and midx='".$_POST['midx']."' limit 1";
		if(false === mysql_query($sql,get_db_conn())) _alert('삭제중 데이터 베이스 오류가 발생 했습니다.','-1');
		else  _alert('정상 처리 되었습니다.','multicontents.php?pridx='.$_POST['pridx']);		
		break;
	default:
		_alert('정의 되지 않은 요청 입니다.','-1');
		break;
}
