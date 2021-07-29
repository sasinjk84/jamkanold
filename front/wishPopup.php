<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/product_func.php");

header("Content-Type: text/plain");
header("Content-Type: text/html; charset=euc-kr");

array_walk($_POST,'_iconvFromUtf8');


$mode=$_POST["mode"];
$sels=(array)$_POST["sels"];
$wish_idx=$_POST["wish_idx"];


// 카테고리 등록
if( $_POST['mode'] == 'cateInsert' AND  strlen( $_ShopInfo->getMemid() ) > 0 AND strlen($_POST['cateTitle']) > 0 ) {
	mysql_query("INSERT tblwishlist_category SET title = '".$_POST['cateTitle']."', memid = '".$_ShopInfo->getMemid()."' ", get_db_conn());
	$result['err']='ok';
	
	$wishCateList = wishCateList();

	$wishhtml = "<li style=\"overflow:hidden;\">";
	$wishhtml .= "<input type=\"checkbox\" class=\"checkbox\" name=\"sel[]\" value=\"0\" checked>기본폴더";
	$wishhtml .= "<span style=\"float:right;\"><img src=\"/data/design/img/detail/icon_lock1.gif\"></span></li>";

	foreach ( $wishCateList as $k=>$v ) {					
		$wishhtml .= "<li style=\"overflow:hidden;\"><input type=\"checkbox\" class=\"checkbox\" name=\"sel[]\" value=\"".$k."\">".$v;
		$wishhtml .= "<p style=\"float:right;\"><input type='image' value='수정'src=\"/data/design/img/detail/icon_edit.gif\"  onclick=\"wishCateModifyOpen('".$v."','".$k."');return false;\"> ";
		$wishhtml .= "<input type='image' src=\"/data/design/img/detail/icon_close.gif\" value='삭제' onclick=\"wishManage('cateDelete', '".$k."');return false;\"></span>";
		$wishhtml .= "</li>";
	}


	echo $wishhtml;
	exit;
}

// 카테고리 수정
if( $_POST['mode'] == 'cateModify' AND  strlen( $_ShopInfo->getMemid() ) > 0 AND strlen($_POST['cateTitle']) > 0 ) {
	mysql_query("UPDATE tblwishlist_category SET title = '".$_POST['cateTitle']."' WHERE idx = '".$_POST['delCateIdx']."' AND memid = '".$_ShopInfo->getMemid()."' ", get_db_conn());

	$wishCateList = wishCateList();

	$wishhtml = "<li style=\"overflow:hidden;\">";
	$wishhtml .= "<input type=\"checkbox\" class=\"checkbox\" name=\"sel[]\" value=\"0\" checked>기본폴더";
	$wishhtml .= "<span style=\"float:right;\"><img src=\"/data/design/img/detail/icon_lock1.gif\"></span></li>";

	foreach ( $wishCateList as $k=>$v ) {					
		$wishhtml .= "<li style=\"overflow:hidden;\"><input type=\"checkbox\" class=\"checkbox\" name=\"sel[]\" value=\"".$k."\">".$v;
		$wishhtml .= "<p style=\"float:right;\"><input type='image' value='수정'src=\"/data/design/img/detail/icon_edit.gif\"  onclick=\"wishCateModifyOpen('".$v."','".$k."');return false;\"> ";
		$wishhtml .= "<input type='image' src=\"/data/design/img/detail/icon_close.gif\" value='삭제' onclick=\"wishManage('cateDelete', '".$k."');return false;\"></span>";
		$wishhtml .= "</li>";
	}

	echo $wishhtml;
	exit;
}

// 카테고리 삭제
if( $_POST['mode'] == 'cateDelete' AND strlen( $_ShopInfo->getMemid() ) > 0 AND strlen($_POST['delCateIdx']) > 0 ) {
	mysql_query("DELETE FROM tblwishlist WHERE category = '".$_POST['delCateIdx']."' AND id = '".$_ShopInfo->getMemid()."' ", get_db_conn());
	mysql_query("DELETE FROM tblwishlist_category WHERE idx = '".$_POST['delCateIdx']."' AND memid = '".$_ShopInfo->getMemid()."' ", get_db_conn());
	
	$wishCateList = wishCateList();

	$wishhtml = "<li style=\"overflow:hidden;\">";
	$wishhtml .= "<input type=\"checkbox\" class=\"checkbox\" name=\"sel[]\" value=\"0\" checked>기본폴더";
	$wishhtml .= "<span style=\"float:right;\"><img src=\"/data/design/img/detail/icon_lock1.gif\"></span></li>";

	foreach ( $wishCateList as $k=>$v ) {					
		$wishhtml .= "<li style=\"overflow:hidden;\"><input type=\"checkbox\" class=\"checkbox\" name=\"sel[]\" value=\"".$k."\">".$v;
		$wishhtml .= "<p style=\"float:right;\"><input type='image' value='수정'src=\"/data/design/img/detail/icon_edit.gif\"  onclick=\"wishCateModifyOpen('".$v."','".$k."');return false;\"> ";
		$wishhtml .= "<input type='image' src=\"/data/design/img/detail/icon_close.gif\" value='삭제' onclick=\"wishManage('cateDelete', '".$k."');return false;\"></span>";
		$wishhtml .= "</li>";
	}

	echo $wishhtml;
	exit;
}


?>