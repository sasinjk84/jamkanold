<?php
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

header("Content-Type: text/plain");
header("Content-Type: text/html; charset=euc-kr");

array_walk($_POST,'_iconvFromUtf8');

$mode	 = $_POST["mode"];
$search	 = $_POST["search"];
$id		 = $_POST["id"];
$memo	 = $_POST["memo"];


if($mode == "id_search"){
	
	if($search){
		$sql = "SELECT if(m.id='".$_ShopInfo->getMemid()."',1,2) as myid, m.id, name, mobile, office_addr,if(rb_idx is NULL,'x',rb_idx) bookmark ";
		$sql.= "FROM tblmember m LEFT JOIN reseller_bookmark rb ON (m.id=rb.bookmark_id and rb.id='".$_ShopInfo->getMemid()."') ";
		$sql.= "WHERE member_out = 'N' AND group_code LIKE 'RP%' ";
		$sql.= "AND (m.id LIKE '%".$search."%' OR name LIKE '%".$search."%' OR mobile LIKE '%".$search."%') ";
		$sql.= "ORDER BY myid asc, bookmark asc, m.id asc";
	}else{
		$sql = "SELECT if(m.id='".$_ShopInfo->getMemid()."',1,2) as myid, m.id, name, mobile, office_addr,if(rb_idx is NULL,'x',rb_idx) bookmark ";
		$sql.= "FROM tblmember m LEFT JOIN reseller_bookmark rb ON (m.id=rb.bookmark_id and rb.id='".$_ShopInfo->getMemid()."') ";
		$sql.= "WHERE member_out = 'N' ";
		$sql.= "AND (group_code LIKE 'RP%' OR m.id='".$_ShopInfo->getMemid()."') ";
		$sql.= "ORDER BY myid asc, bookmark asc, m.id asc";
	}
	$result = mysql_query($sql,get_db_conn());
	
	while($row=mysql_fetch_object($result)) {
		$count++;

		if($row->id==$_ShopInfo->getMemid()){
			$favicon = "♠";
			$tbclass = "green";
		}else{
			if($row->bookmark!="x"){
				$favicon = "<span id=\"bookmark_".$row->id."\" style=\"cursor:pointer\" onclick=\"bookmarkID('bookmark_n','".$row->id."')\">★</span>";
				$tbclass = "gray";
			}else{
				$favicon = "<span id=\"bookmark_".$row->id."\" style=\"cursor:pointer\" onclick=\"bookmarkID('bookmark_y','".$row->id."')\">☆</span>";
				$tbclass = "";
			}
		}
		
		$m_sql = "SELECT memo ";
		$m_sql.= "FROM reseller_memo ";
		$m_sql.= "WHERE id='".$_ShopInfo->getMemid()."' AND bookmark_id='".$row->id."'";
		$mRes = mysql_query($m_sql,get_db_conn());
		$mRow=mysql_fetch_object($mRes);

		if($mRow->memo!=""){
			$placeholder = "";
			$display_y = "";
			$display_n = "none";
		}else{
			$placeholder = "간단하게 메모하실 수 있습니다.";
			$display_y = "none";
			$display_n = "";
		}

		mb_internal_encoding(mb_detect_encoding($row->name,'UTF-8,EUC-KR'));
		$len = mb_strlen($row->name);
		if($len>2){
			$name = mb_substr($row->name,0,1).str_repeat('*',$len-2).mb_substr($row->name,-1,1);
		}else if($len==2){
			$name = mb_substr($row->name,0,1).str_repeat('*',$len-1);
		}else{
			$name = $row->name;
		}
		
		if($row->office_addr){
			$row->office_addr = str_replace("="," ",$row->office_addr);
			mb_internal_encoding(mb_detect_encoding($row->office_addr,'UTF-8,EUC-KR'));
			$office_addr = ($len=mb_strlen($row->office_addr))>20 ? mb_substr($row->office_addr,0,10).str_repeat('*',$len-15).mb_substr($row->office_addr,-5,5) : $row->office_addr;
		}else{
			$office_addr = "";
		}
		
		$mobile = str_replace($search,"<b>".$search."</b>",$row->mobile);
		$mobile = preg_replace('/.(?!....)/u','*',$mobile);

		$return_html .= "<table class=\"itemListTbl ".$tbclass."\">\n";
		$return_html .= "<tr>\n";
		$return_html .= "	<td class=\"line\">".$favicon;
		$return_html .= "		<A HREF=\"javascript:selectid('".$row->id."');\"><span class=\"font_blue\"><U>".str_replace($search,"<b>".$search."</b>",$row->id)."</U></span></A></td>\n";
		$return_html .= "	<td class=\"line\">".str_replace($search,"<b>".$search."</b>",$name)."</td>\n";
		$return_html .= "	<td>".$mobile."</td>\n";
		$return_html .= "</tr>\n";
		$return_html .= "<tr>\n";
		$return_html .= "<td colspan=\"3\">\n";
		$return_html .= $office_addr;
		$return_html .= "</td>\n";
		$return_html .= "</tr>\n";
		$return_html .= "<tr>\n";
		$return_html .= "<td colspan=\"3\" align=\"right\">\n";
		$return_html .= "<input type=\"hidden\" name=\"btn_gubun\" id=\"btn_gubun\" value=\"0\">";
		$return_html .= "<span id=\"btn_memo_".$row->id."\" class=\"btn_memo\" onclick=\"javascript:memoView('".$row->id."')\" style=\"cursor:pointer\">+</span>";
		$return_html .= "</td>\n";
		$return_html .= "</tr>\n";
		$return_html .= "</table>\n";
		$return_html .= "<table id=\"tblmemo_".$row->id."\" style=\"display:".$display_y."\">";

		if($mRow->memo){ 
			$return_html .= "<tr id=\"memo_y\" style=\"display:".$display_y."\">";
			$return_html .= "<td>(메모) ".$mRow->memo."</td>";
			$return_html .= "</tr>";

			$memo_mode = "memo_modify";
		}else{
			$memo_mode = "memo_insert";
		}

		$return_html .= "<tr id=\"memo_n\" style=\"display:".$display_n."\">";
		$return_html .= "<td>";
		$return_html .= "<input type=\"text\" name=\"memo_".$row->id."\" id=\"memo_".$row->id."\" value=\"".$mRow->memo."\" placeholder=\"".$placeholder."\" style=\"width:300px\">";
		$return_html .= "<input type=\"button\" value=\"저장\" onclick=\"javascript:memoSave('".$memo_mode."','".$row->id."')\">";
		$return_html .= "</td>";
		$return_html .= "</tr>";
		$return_html .= "</table>";

	}

	if($count==0){
		$return_html = "<p align=\"center\">검색결과가 없습니다.</p>\n";
	}

	echo $return_html;

}else if($mode == "bookmark_y"){

	$sql = "insert into reseller_bookmark (id,bookmark_id) values ('".$_ShopInfo->getMemid()."','".$id."') ON DUPLICATE KEY UPDATE id = values(id),bookmark_id = values(bookmark_id)";
	if($update = mysql_query($sql,get_db_conn())){
		echo "ok";
	}

}else if($mode == "bookmark_n"){

	$sql = "DELETE FROM reseller_bookmark WHERE id='".$_ShopInfo->getMemid()."' AND bookmark_id='".$id."'";

	if($update = mysql_query($sql,get_db_conn())){
		echo "ok";
	}

}else if($mode == "memo_insert"){
	
	$sql = "INSERT INTO reseller_memo (id,bookmark_id,memo) VALUES ('".$_ShopInfo->getMemid()."','".$id."','".$memo."')";
	mysql_query($sql,get_db_conn());

	echo "ok";

}else if($mode == "memo_modify"){
	
	$sql = "UPDATE reseller_memo SET memo='".$memo."' WHERE id='".$_ShopInfo->getMemid()."' AND bookmark_id='".$id."'";
	mysql_query($sql,get_db_conn());
	echo "ok";

}
?>
