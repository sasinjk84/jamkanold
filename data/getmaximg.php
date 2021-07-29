<?
$Dir = '../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");
$retimg = '';
if(!_empty($_REQUEST['productcode'])){
	$sql = "select maximage,minimage,tinyimage from tblproduct where productcode='".$_REQUEST['productcode']."' limit 1";
	
	if(false !== $res = mysql_query($sql,get_db_conn())){
		if(mysql_num_rows($res)){
			for($i=0;$i<3;$i++){
				$tmp = mysql_result($res,0,$i);
				if(is_file($Dir.'data/shopimages/product/'.$tmp)){
					// $retimg = '<img src="'.$Dir.'data/shopimages/product/'.$tmp.'" />';
					$retimg = $Dir.'data/shopimages/product/'.$tmp;
					break;
				}
			}
		}
	}
}
exit($retimg);
/*
if(_empty($retimg)){
	
}*/
?>