<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");


echo "상품 옵션 수량 복구 페이지<br/>";

if(false !== ($result = mysql_query("SELECT pridx FROM tblproduct",get_db_conn()))) {
	while(list($pridx) = mysql_fetch_row($result)) {
		$sql = "SELECT count(*) FROM rent_product_option WHERE pridx = ".$pridx;
		$opt_res = mysql_query($sql, get_db_conn());
		if (mysql_num_rows($opt_res) > 0) {
			list($count) = mysql_fetch_row($opt_res);
			if ($count > 0) {
				mysql_query("UPDATE rent_product_option SET productCount=10 WHERE pridx=".$pridx, get_db_conn());
				$totcount = $count * 10;
				mysql_query("UPDATE tblproduct SET quantity=".$totcount." WHERE pridx=".$pridx, get_db_conn());
			} else {
				mysql_query("UPDATE tblproduct SET quantity=10 WHERE pridx=".$pridx, get_db_conn());
			}
		}
	}
}

