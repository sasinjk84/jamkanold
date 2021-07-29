<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
require_once($Dir."lib/ext/func.php");
$imagepath=$Dir.DataDir."shopimages/multi/";
try{
	switch($_REQUEST['act']){
		case 'get':
			if(!_isInt($_REQUEST['pridx']) || !_isInt($_REQUEST['midx'])) throw new ErrorException('대상지정 오류');
			$sql = "select * from product_multicontents where pridx='".$_REQUEST['pridx']."' and midx='".$_REQUEST['midx']."' limit 1";
			if(false === $res = mysql_query($sql,get_db_conn())) throw new ErrorException('데이터 베이스 오류');
			if(mysql_num_rows($res) <1) throw new ErrorException('대상을 찾지 못했습니다.');
			$result = mysql_fetch_assoc($res);

			/*$imgsize=GetImageSize($imagepath.$result['cont']);

			//이미지 가로 사이즈가 가로 860px 이상이면 가로 사이즈 860px로 고정
			if($imgsize[0]>860){
				$width=' width=860';
			}else{
				$width='';
			}

			//이미지 세로 사이즈가 가로보다 크고 세로 사이즈가 500px 이상이면 세로 사이즈 500px로 고정
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
			throw new ErrorException('정의되지 않은 요청 입니다.');
			break;
	}
	$result['err']='ok';
}catch(Exception $e){
	$result['err'] = $e->getMessage();	
}

// php  5.2.0 이상은 추가
$phpVer = str_replace(".","",phpversion());
if( $phpVer >= 520 ) array_walk($result,'_encode');

echo json_encode($result);
exit;
?>