<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include ("../access.php");
include_once($Dir."lib/admin_more.php");
include_once($Dir."lib/ext/product_func.php");
include_once($Dir."lib/class/thumbnail.php");

$imagepath=$Dir.DataDir."shopimages/multi";
//if(!_isInt($_POST['pridx'])) _alert('��ǰ �ĺ� ��ȣ ���� ����','-1');
if(!is_numeric($_POST['pridx'])) _alert('��ǰ �ĺ� ��ȣ ���� ����','-1');
if($_POST['pridx'] > 0){
	$sql = "select productcode from tblproduct where pridx='".$_POST['pridx']."'";
	if(false === $res = mysql_query($sql,get_db_conn())) _alert('������ ���̽� ���� ����','-1');
	if(mysql_num_rows($res) < 1) _alert('��ǰ������ ã�� �� �����ϴ�.','-1');	
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
			if(false === $res =mysql_query($sql,get_db_conn())) _alert('������ ���̽� ���� ����','-1');
			if(mysql_num_rows($res)) $oinfo = mysql_fetch_assoc($res);
			else _alert('�������͸� ã�� �� �����ϴ�.','-1');
			$where = " where pridx='".$_POST['pridx']."' and midx='".$_POST['midx']."'";
		}else{
			$sql = "select max(midx) from product_multicontents where pridx='".$_POST['pridx']."'";
			if(false === $res =mysql_query($sql,get_db_conn())) _alert('������ ���̽� ���� ����','-1');
			if(mysql_num_rows($res)) $oinfo['midx'] = intval(mysql_result($res,0,0))+1;
			else $oifno['midx'] = 1;
			$where = '';
		}
		
		$intype = ($_POST['type'] == 'code')?'code':'img';		
		
		if($intype == 'img'){
			$block = array('exe','sh','htaccess','php','html','htm','com');		
			$allow = array('gif','jpg','jpeg','png');
			if(!is_uploaded_file($_FILES['cont_img']['tmp_name']) || _empty($_FILES['cont_img']['name'])) _alert('�������� ���ε� ������ �ƴմϴ�.','-1');
			
			
			clearstatcache();		
			switch ($_FILES['cont_img']["error"]){
				case "1":
					_alert("���ε��� ������ upload_max_filesize ���� Ů�ϴ�.",'-1');
					break;					
				case "2":			
					_alert("���ε��� ������ HTML ������ ������ MAX_FILE_SIZE ���þ�� Ů�ϴ�.",'-1');
					break;
				case "3":
					_alert("������ �Ϻκи� ���۵Ǿ����ϴ�.",'-1');
					break;
				case "4":
					_alert("������ ���۵��� �ʾҽ��ϴ�.");
					break;			
				default:
					$tempname = str_replace(array(" ","-"),"_",$_FILES['cont_img']['name']); // �̸��� �߰��� ���� �Ǵ� '-'������ '_' �� �ٲ�					
					$name = explode('.',$tempname);
					$ext = strtolower(array_pop($name));				
					$name = implode('.',$name);
					
					if(_array($allow)){
						array_walk($allow,'strtolower');
						if(!in_array($ext,$allow)) _alert("������ ���� ������ �����Դϴ�.",'-1');
					}
					
					if(_array($block) && in_array($ext,$block)) _alert("���ε� ������ ���� �����Դϴ�.",'-1');
										
					$ftmp = $productcode.'_'.$oinfo['midx'];
					$index = 0;
					$tmp_name = $ftmp.".".$ext;
					
					while(file_exists($imagepath."/".$tmp_name)){
						$tmp_name = $ftmp."_".$index.".".$ext;
						$index++; 
					}	
					if(!move_uploaded_file($_FILES['cont_img']['tmp_name'],$imagepath."/".$tmp_name)) _alert("���ε� ����",'-1');
					else{
						@chmod($imagepath.'/'.$tmp_name,0706);
						$cont = $tmp_name;
					}
					
					// 20160317 JDH �����̹��� width, height �̹��� ũ�� �����ڵ�
					$is_resize = false;
					$limit_width = 860; // width ������
					$limit_height = 580; // hieght ������
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
		if(false === mysql_query($sql,get_db_conn())) _alert('������ ������ ���̽� ������ �߻� �߽��ϴ�.','-1');
		else  _alert('���� ó�� �Ǿ����ϴ�.','multicontents.php?pridx='.$_POST['pridx']);
		break;
	case 'delete':
		if(!_isInt($_POST['midx'])) _alert('���� ����� ���� ���� �ʾҽ��ϴ�.','-1');
		$sql = "select * from product_multicontents where pridx='".$_POST['pridx']."' and midx='".$_POST['midx']."' limit 1";
		
		if(false === $res =mysql_query($sql,get_db_conn())) _alert('������ ���̽� ���� ����','-1');
		if(mysql_num_rows($res)) $info = mysql_fetch_assoc($res);
		else _alert('�������͸� ã�� �� �����ϴ�.','-1');

		if($info['type'] == 'img'){
			@unlink($imagepath.'/'.$info['cont']);
			@unlink($imagepath.'/thumb_'.$info['cont']);
		}
		
		$sql = "delete from product_multicontents where pridx='".$_POST['pridx']."' and midx='".$_POST['midx']."' limit 1";
		if(false === mysql_query($sql,get_db_conn())) _alert('������ ������ ���̽� ������ �߻� �߽��ϴ�.','-1');
		else  _alert('���� ó�� �Ǿ����ϴ�.','multicontents.php?pridx='.$_POST['pridx']);		
		break;
	default:
		_alert('���� ���� ���� ��û �Դϴ�.','-1');
		break;
}
