<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
require_once($Dir."lib/ext/func.php");
$imagepath=$Dir.DataDir."shopimages/multi/";
try{
	switch($_REQUEST['act']){
		case 'get':
			if(!_isInt($_REQUEST['pridx']) || !_isInt($_REQUEST['midx'])) throw new ErrorException('������� ����');
			$sql = "select * from product_multicontents where pridx='".$_REQUEST['pridx']."' and midx='".$_REQUEST['midx']."' limit 1";
			if(false === $res = mysql_query($sql,get_db_conn())) throw new ErrorException('������ ���̽� ����');
			if(mysql_num_rows($res) <1) throw new ErrorException('����� ã�� ���߽��ϴ�.');
			$result = mysql_fetch_assoc($res);

			/*$imgsize=GetImageSize($imagepath.$result['cont']);

			//�̹��� ���� ����� ���� 860px �̻��̸� ���� ������ 860px�� ����
			if($imgsize[0]>860){
				$width=' width=860';
			}else{
				$width='';
			}

			//�̹��� ���� ����� ���κ��� ũ�� ���� ����� 500px �̻��̸� ���� ������ 500px�� ����
			if($imgsize[1] > $imgsize[0] && $imgsize[1]>500){
				$height=' height=500';
			}else{
				$height='';
			}*/

			if($result['type'] == 'img'){
				if(file_exists($imagepath.'/'.$result['cont'])) $result['content'] = '<img src="'.$imagepath.$result['cont'].'"'.$width.$height.'" />';
			}else{
				if(preg_match('!^(http|https)://.+!i',$result['cont'])) $result['content'] = '<iframe width="100%" height="490" src="'.$result['cont'].'" frameborder="0" allowfullscreen></iframe>';
				else $result['content'] = $result['cont'];
			}
			break;
		default:
			throw new ErrorException('���ǵ��� ���� ��û �Դϴ�.');
			break;
	}
	$result['err']='ok';
}catch(Exception $e){
	$result['err'] = $e->getMessage();	
}

// php  5.2.0 �̻��� �߰�
$phpVer = str_replace(".","",phpversion());
if( $phpVer >= 520 ) array_walk($result,'_encode');

echo json_encode($result);
exit;
?>