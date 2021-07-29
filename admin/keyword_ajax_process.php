<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
//INCLUDE ("access.php");

header("Content-Type: text/plain");
header("Content-Type: text/html; charset=euc-kr");

array_walk($_POST,'_iconvFromUtf8');


$mode = $_POST["mode"];
$code	 = $_POST["code"];
$kwgroup = $_POST["kwgroup"];
$keyword = $_POST["keyword"];


if ($mode == "kwgroup_insert") {
	
	$sql = "INSERT tblkwgroup SET ";
	$sql.= "kwgroup		= '".$kwgroup."' ";

	$return_html = '';
	if($insert = mysql_query($sql,get_db_conn())){

		$sql_ = "SELECT LAST_INSERT_ID() ";
		$res = mysql_fetch_row(mysql_query($sql_,get_db_conn()));
		$kw_idx = $res[0];

		$return_html .= '<option value="'.$kw_idx.'">'.$kwgroup.'</option>';
	}

	echo $return_html;

}else if($mode == "tbl_kw_list"){
	
	$ksql = "SELECT kw.kg_idx,kwgroup,use_yn ";
	$ksql.= "FROM tblkeyword kw LEFT JOIN tblkwgroup kg ON kw.kg_idx=kg.kg_idx ";
	$ksql.= "WHERE kw.kg_idx='".$kg_idx."' ";
	if($catekeyword){
		$ksql.= "AND (productcode='' OR productcode='".$prcode."') ";
	}else{
		$ksql.= "AND productcode='' ";
	}
	$ksql.= "AND use_yn='Y' GROUP BY kw.kg_idx";
	$kres = mysql_query($ksql,get_db_conn());
	
	while($krow = mysql_fetch_object($kres)){
		echo "<li id=\"div_".$krow->kg_idx."\">";
		echo "<input type=\"hidden\" name=\"kg_idx[]\" value=\"".$krow->kg_idx."\"></span>";
		echo "<span><a href=\"javascript:delKwGroup('".$krow->kg_idx."')\"><span class=\"button\">x</span></a>";
		echo "</span>";
		echo "<span style=\"padding:5px;font-weight:bold\">".$krow->kwgroup;
		echo "</span>";
													
		echo "<span id=\"".$krow->kg_idx."_kwlist\">";
		echo "<input type=\"checkbox\" name=\"ckall_".$krow->kg_idx."\" id=\"ckall_".$krow->kg_idx."\" value=\"Y\" onclick=\"javascript:kwcheckAll('".$krow->kg_idx."')\" class=\"checkbox\" > <label for=\"ckall_".$krow->kg_idx."\">전체</label>";

		$ksql2 = "SELECT kw_idx,keyword FROM tblkeyword ";
		$ksql2.= "WHERE kg_idx='".$krow->kg_idx."' ";
		if($catekeyword){
			$ksql2.= "AND (productcode='' OR productcode='".$prcode."') ";
		}else{
			$ksql2.= "AND productcode='' ";
		}
		$ksql2.= "ORDER BY kw_idx";
		$kres2 = mysql_query($ksql2,get_db_conn());

		$return_html = "";
		while($krow2 = mysql_fetch_object($kres2)){
			if(strpos($catekeyword,$krow2->kw_idx.":".$krow->kwgroup.":".$krow2->keyword)>-1){
				$checked = "checked";
			}else{
				$checked = "";
			}
			
			$return_html .= "<input type=\"checkbox\" name=\"".$krow2->kw_idx."_kw[]\" id=\"".$krow2->kw_idx."\" class=\"ck_".$krow->kg_idx." checkbox\" value=\"".$krow2->kw_idx.":".$krow->kwgroup.":".$krow2->keyword."\"  onclick=\"addcatekw('".$krow2->kw_idx."','".$krow->kwgroup."','".$krow2->keyword."')\" ".$checked.">";
			$return_html .= "<label for=\"".$krow2->kw_idx."\">".$krow2->keyword."</label>";

			
		}
		$return_html .= "</span>";

		$return_html .= "<span id=\"".$krow->kg_idx."addDiv\">";
		$return_html .= "<a href=\"javascript:addKwText('".$krow->kg_idx."')\"><span class=\"button\">추가</span></a>";
		$return_html .= "</span>";
		$return_html .= "<span id=\"".$krow->kg_idx."addDiv2\" style=\"display:none\">";
		$return_html .= "<input type=\"hidden\" id=\"".$krow->kg_idx."_kwgroup\" name=\"".$krow->kg_idx."_kwgroup\"\" value=\"".$krow->kwgroup."\">";
		$return_html .= "<input type=\"text\" id=\"".$krow->kg_idx."_kw_text\" name=\"".$krow->kg_idx."_kw_text\" placeholder=\"키워드를 입력하세요.\" class=\"text\">";
		$return_html .= "<a href=\"javascript:insertKwText('".$krow->kg_idx."')\"><span class=\"button\">추가</span></a>";
		$return_html .= "<a href=\"javascript:cancelKwText('".$krow->kg_idx."')\"><span class=\"button\">취소</span></a>";
		$return_html .= "</span>";
		$return_html .= "</li>";
	}

	echo $return_html;

}else if ($mode == "tbl_kw_insert") {
	
	$ksql2 = "SELECT kwgroup FROM tblkwgroup ";
	$ksql2.= "WHERE kg_idx='".$kg_idx."' ";
	$kres2 = mysql_query($ksql2,get_db_conn());
	$krow2 = mysql_fetch_object($kres2);

	$sql = "INSERT tblkeyword SET ";
	$sql.= "kg_idx  		= '".$kg_idx."', ";
	$sql.= "code			= '".$code."', ";
	$sql.= "productcode		= '".$prcode."', ";
	$sql.= "keyword			= '".$keyword."', ";
	$sql.= "use_yn			= 'Y' ";

	$return_html = '';
	if($insert = mysql_query($sql,get_db_conn())){

		$sql_ = "SELECT LAST_INSERT_ID() ";
		$res = mysql_fetch_row(mysql_query($sql_,get_db_conn()));
		$kw_idx = $res[0];
		
		$return_html .= "<input type=\"checkbox\" name=\"".$kw_idx."_kw[]\" class=\"ck_".$kw_idx."\" value=\"".$kw_idx.":".$krow2->kwgroup.":".$keyword."\"  onclick=\"addcatekw('".$kw_idx."','".$krow2->kwgroup."','".$keyword."')\">";
		$return_html .= $keyword;
	}

	echo $return_html;

}else if($mode == "prdkeyword_insert"){
	
	$codeA = substr($code,0,3)."000000000";
	$codeB = substr($code,0,6)."000000";
	$codeC = substr($code,0,9)."000";

	$ksql = "SELECT kw.kg_idx,kwgroup,use_yn ";
	$ksql.= "FROM tblkeyword kw LEFT JOIN tblkwgroup kg ON kw.kg_idx=kg.kg_idx ";
	//$ksql.= "WHERE code like '".$code."%' ";
	$ksql.= "WHERE (code='".$code."' OR code='".$codeA."' OR code='".$codeB."' OR code='".$codeC."') ";
	$ksql.= "AND productcode='' AND use_yn='Y' GROUP BY kw.kg_idx";
	$kres = mysql_query($ksql,get_db_conn());
	
	$return_html = "";
	$return_html .= "<li>";
	$return_html .= "<span>사용</span>";
	$return_html .= "<span>분류</span>";
	$return_html .= "<span>검색키워드</span>";
	$return_html .= "</li>";
	while($krow = mysql_fetch_object($kres)){
		$return_html .= "<li id=\"div_".$krow->kg_idx."\">";
		$return_html .= "<input type=\"hidden\" name=\"kg_idx[]\" value=\"".$krow->kg_idx."\"></span>";
		$return_html .= "<span><input type=\"checkbox\" name=\"".$krow->kg_idx."_useyn\" value=\"Y\" ";
		if ($krow->use_yn=="Y") $return_html .= "checked"; else $return_html .= "";
		$return_html .= ">";
		$return_html .= "</span>";
		$return_html .= "<span style=\"padding:5px;font-weight:bold\">".$krow->kwgroup;
		$return_html .= "<button type=\"button\" onclick=\"delKwGroup('".$krow->kg_idx."')\" style=\"margin:2px;\">X</button> ";
		$return_html .= "</span>";

		$return_html .= "<span id=\"".$krow->kg_idx."_kwlist\">";
		$return_html .= "<input type=\"checkbox\" name=\"ckall_".$krow->kg_idx."\" id=\"ckall_".$krow->kg_idx."\" value=\"Y\" checked onclick=\"javascript:kwcheckAll('".$krow->kg_idx."')\"> 전체";
		
		$ksql2 = "SELECT kw_idx,keyword FROM tblkeyword ";
		//$ksql2.= "WHERE code like '".$code."%' ";
		$ksql2.= "WHERE (code='".$code."' OR code='".$codeA."' OR code='".$codeB."' OR code='".$codeC."') ";
		$ksql2.= "AND productcode='' AND kg_idx='".$krow->kg_idx."' ORDER BY kw_idx";
		$kres2 = mysql_query($ksql2,get_db_conn());

		while($krow2 = mysql_fetch_object($kres2)){
			
			$return_html .= "<input type=\"checkbox\" name=\"".$krow2->kw_idx."_kw[]\" class=\"ck_".$krow->kg_idx."\" value=\"".$krow2->kw_idx.":".$krow->kwgroup.":".$krow2->keyword."\"  onclick=\"addcatekw('".$krow2->kw_idx."','".$krow->kwgroup."','".$krow2->keyword."')\">";
			$return_html .= $krow2->keyword;
			//$return_html .= "<input type=\"hidden\" name=\"".$krow->kg_idx."_kw[]\" value=\"".$krow2->keyword."\">";
			//$return_html .= "<button type=\"button\" onclick=\"delKwText(this)\" style=\"margin:2px;\">X</button> ";
		}
		$return_html .= "</span>";

		$return_html .= "<span id=\"".$krow->kg_idx."addDiv\">";
		$return_html .= "<button type=\"button\" onclick=\"addKwText('".$krow->kg_idx."')\">추가</button>";
		$return_html .= "</span>";
		$return_html .= "<span id=\"".$krow->kg_idx."addDiv2\" style=\"display:none\">";
		$return_html .= "<input type=\"hidden\" id=\"".$krow->kg_idx."_kwgroup\" name=\"".$krow->kg_idx."_kwgroup\"\" value=\"".$krow->kwgroup."\">";
		$return_html .= "<input type=\"text\" id=\"".$krow->kg_idx."_kw_text\" name=\"".$krow->kg_idx."_kw_text\" placeholder=\"키워드를 입력하세요.\">";
		$return_html .= "<button type=\"button\" onclick=\"insertKwText('".$krow->kg_idx."')\">추가</button>";
		$return_html .= "<button type=\"button\" onclick=\"cancelKwText('".$krow->kg_idx."')\">취소</button>";
		$return_html .= "</span>";
		$return_html .= "</li>";
	}

	echo $return_html;

}else if($mode == "vender_prdkeyword_insert"){
	
	$codeA = substr($code,0,3)."000000000";
	$codeB = substr($code,0,6)."000000";
	$codeC = substr($code,0,9)."000";

	$ksql = "SELECT kw.kg_idx,kwgroup,use_yn ";
	$ksql.= "FROM tblkeyword kw LEFT JOIN tblkwgroup kg ON kw.kg_idx=kg.kg_idx ";
	$ksql.= "WHERE (code='".$code."' OR code='".$codeA."' OR code='".$codeB."' OR code='".$codeC."') ";
	$ksql.= "AND productcode='' AND use_yn='Y' GROUP BY kw.kg_idx";
	$kres = mysql_query($ksql,get_db_conn());
	
	$return_html = "";
	$return_html .= "<li>";
	$return_html .= "<span>사용</span>";
	$return_html .= "<span>분류</span>";
	$return_html .= "<span>검색키워드</span>";
	$return_html .= "</li>";
	while($krow = mysql_fetch_object($kres)){
		$return_html .= "<li id=\"div_".$krow->kg_idx."\">";
		$return_html .= "<input type=\"hidden\" name=\"kg_idx[]\" value=\"".$krow->kg_idx."\"></span>";
		//$return_html .= "<span><button type=\"button\" onclick=\"delKwGroup('".$krow->kg_idx."')\" style=\"margin:2px;\">X</button></span>";
		$return_html .= "<span style=\"padding:5px;font-weight:bold\">".$krow->kwgroup."</span>";

		$return_html .= "<span id=\"".$krow->kg_idx."_kwlist\">";
		//$return_html .= "<input type=\"checkbox\" name=\"ckall_".$krow->kg_idx."\" id=\"ckall_".$krow->kg_idx."\" value=\"Y\" onclick=\"javascript:kwcheckAll('".$krow->kg_idx."')\"> 전체";
		
		$ksql2 = "SELECT kw_idx,keyword FROM tblkeyword ";
		//$ksql2.= "WHERE code like '".$code."%' ";
		$ksql2.= "WHERE (code='".$code."' OR code='".$codeA."' OR code='".$codeB."' OR code='".$codeC."') ";
		$ksql2.= "AND productcode='' AND kg_idx='".$krow->kg_idx."' ORDER BY kw_idx";
		$kres2 = mysql_query($ksql2,get_db_conn());

		while($krow2 = mysql_fetch_object($kres2)){
			
			//$return_html .= "<input type=\"hidden\" name=\"kw_idx[]\" value=\"".$krow2->kw_idx."\"></span>";
			//$return_html .= "<input type=\"checkbox\" name=\"".$krow->kg_idx."_kw[]\" class=\"ck_".$krow->kg_idx."\" value=\"".$krow2->keyword."\">";
			$return_html .= "<input type=\"checkbox\" name=\"".$krow2->kw_idx."_kw[]\" class=\"ck_".$krow->kg_idx."\" value=\"".$krow2->kw_idx.":".$krow->kwgroup.":".$krow2->keyword."\"  onclick=\"addcatekw('".$krow2->kw_idx."','".$krow->kwgroup."','".$krow2->keyword."')\">";
			$return_html .= $krow2->keyword;
		}
		$return_html .= "</span>";

		$return_html .= "<span id=\"".$krow->kg_idx."addDiv\">";
		$return_html .= "<button type=\"button\" onclick=\"addKwText('".$krow->kg_idx."')\">추가</button>";
		$return_html .= "</span>";
		$return_html .= "<span id=\"".$krow->kg_idx."addDiv2\" style=\"display:none\">";
		$return_html .= "<input type=\"hidden\" id=\"".$krow->kg_idx."_kwgroup\" name=\"".$krow->kg_idx."_kwgroup\"\" value=\"".$krow->kwgroup."\">";
		$return_html .= "<input type=\"text\" id=\"".$krow->kg_idx."_kw_text\" name=\"".$krow->kg_idx."_kw_text\" placeholder=\"키워드를 입력하세요.\">";
		$return_html .= "<button type=\"button\" onclick=\"insertKwText('".$krow->kg_idx."')\">추가</button>";
		$return_html .= "<button type=\"button\" onclick=\"cancelKwText('".$krow->kg_idx."')\">취소</button>";
		$return_html .= "</span>";
		$return_html .= "</li>";
	}

	echo $return_html;


}
?>
