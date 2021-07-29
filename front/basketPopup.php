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


// 카테고리 등록
if( $_POST['mode'] == 'cateInsert' AND  strlen( $_ShopInfo->getMemid() ) > 0 AND strlen($_POST['folderName']) > 0 ) {
	$sql = "select bfidx from basket_folder where name='".$_POST['folderName']."' and id='".$_ShopInfo->getMemid()."'";
	$res =  mysql_query($sql,get_db_conn());
	if(mysql_num_rows($res) >0) echo "err";	
	else{
		mysql_query("INSERT basket_folder SET name = '".$_POST['folderName']."', id = '".$_ShopInfo->getMemid()."' ", get_db_conn());
		$result['err']='ok';
		
		$folders = array();
		if(false !== $fres = mysql_query("select * from basket_folder where id='".$_ShopInfo->getMemid()."' order by bfidx desc",get_db_conn())){		
			while($frow = mysql_fetch_assoc($fres)){			
				if(!_empty($frow['name'])) $folders[$frow['bfidx']] = $frow['name'];
			}
		}
		$wishhtml = "<li style=\"overflow:hidden;\">";
		$wishhtml .= "<input type=\"radio\" class=\"checkbox\" name=\"selfd[]\" value=\"0\" checked>기본폴더";
		$wishhtml .= "<span style=\"float:right;\"><img src=\"/data/design/img/detail/icon_lock1.gif\"></span></li>";

		foreach ( $folders as $k=>$v ) {					
			$wishhtml .= "<li style=\"overflow:hidden;\"><input type=\"radio\" class=\"checkbox\" name=\"selfd[]\" value=\"".$k."\">".$v;
			$wishhtml .= "<p style=\"float:right;\"><input type='image' value='수정' src=\"/data/design/img/detail/icon_edit.gif\"  onclick=\"basketFolderModifyOpen('".$v."','".$k."');return false;\"> ";
			$wishhtml .= "<input type='image' src=\"/data/design/img/detail/icon_close.gif\" value='삭제' onclick=\"basketManage('cateDelete', '".$k."');return false;\"></span>";
			$wishhtml .= "</li>";
		}

		echo $wishhtml;
	}
	exit;
}

// 카테고리 수정
if( $_POST['mode'] == 'cateModify' AND  strlen( $_ShopInfo->getMemid() ) > 0 AND strlen($_POST['folderName']) > 0 ) {

	$sql = "select bfidx from basket_folder where name='".$_POST['folderName']."' and id='".$_ShopInfo->getMemid()."'";
	$res =  mysql_query($sql,get_db_conn());
	if(mysql_num_rows($res) >0) echo "err";	
	else{
		mysql_query("UPDATE basket_folder SET name = '".$_POST['folderName']."' WHERE bfidx = '".$_POST['delCateIdx']."' AND id = '".$_ShopInfo->getMemid()."' ", get_db_conn());

		$folders = array();
		if(false !== $fres = mysql_query("select * from basket_folder where id='".$_ShopInfo->getMemid()."' order by bfidx desc",get_db_conn())){		
			while($frow = mysql_fetch_assoc($fres)){			
				if(!_empty($frow['name'])) $folders[$frow['bfidx']] = $frow['name'];
			}
		}

		$wishhtml = "<li style=\"overflow:hidden;\">";
		$wishhtml .= "<input type=\"radio\" class=\"checkbox\" name=\"selfd[]\" value=\"0\" checked>기본폴더";
		$wishhtml .= "<span style=\"float:right;\"><img src=\"/data/design/img/detail/icon_lock1.gif\"></span></li>";

		foreach ( $folders as $k=>$v ) {					
			$wishhtml .= "<li style=\"overflow:hidden;\"><input type=\"radio\" class=\"checkbox\" name=\"selfd[]\" value=\"".$k."\">".$v;
			$wishhtml .= "<p style=\"float:right;\"><input type='image' value='수정' src=\"/data/design/img/detail/icon_edit.gif\"  onclick=\"basketFolderModifyOpen('".$v."','".$k."');return false;\"> ";
			$wishhtml .= "<input type='image' src=\"/data/design/img/detail/icon_close.gif\" value='삭제' onclick=\"basketManage('cateDelete', '".$k."');return false;\"></span>";
			$wishhtml .= "</li>";
		}

		echo $wishhtml;
	}
	exit;
}

// 카테고리 삭제
if( $_POST['mode'] == 'cateDelete' AND strlen( $_ShopInfo->getMemid() ) > 0 AND strlen($_POST['delCateIdx']) > 0 ) {
	mysql_query("DELETE FROM basket_folder WHERE bfidx = '".$_POST['delCateIdx']."' AND id = '".$_ShopInfo->getMemid()."' ", get_db_conn());
	
	$folders = array();
	if(false !== $fres = mysql_query("select * from basket_folder where id='".$_ShopInfo->getMemid()."' order by bfidx desc",get_db_conn())){		
		while($frow = mysql_fetch_assoc($fres)){			
			if(!_empty($frow['name'])) $folders[$frow['bfidx']] = $frow['name'];
		}
	}

	$wishhtml = "<li style=\"overflow:hidden;\">";
	$wishhtml .= "<input type=\"radio\" class=\"checkbox\" name=\"selfd[]\" value=\"0\" checked>기본폴더";
	$wishhtml .= "<span style=\"float:right;\"><img src=\"/data/design/img/detail/icon_lock1.gif\"></span></li>";

	foreach ( $folders as $k=>$v ) {					
		$wishhtml .= "<li style=\"overflow:hidden;\"><input type=\"radio\" class=\"checkbox\" name=\"selfd[]\" value=\"".$k."\">".$v;
		$wishhtml .= "<p style=\"float:right;\"><input type='image' value='수정' src=\"/data/design/img/detail/icon_edit.gif\"  onclick=\"basketFolderModifyOpen('".$v."','".$k."');return false;\"> ";
		$wishhtml .= "<input type='image' src=\"/data/design/img/detail/icon_close.gif\" value='삭제' onclick=\"basketManage('cateDelete', '".$k."');return false;\"></span>";
		$wishhtml .= "</li>";
	}

	echo $wishhtml;
	exit;
}


?>