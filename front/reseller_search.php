<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");
include_once($Dir."lib/ext/func.php");
include_once($Dir."lib/ext/order_func.php");
include_once($Dir."lib/ext/coupon_func.php");

if(strlen($_ShopInfo->getMemid())==0) {
	_alert('비정상적인 접근 입니다.','0');
	exit;
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<title>추천인 검색</title>

<style>
	table, th, td, caption, div, input, select, textarea{FONT-FAMILY:돋움,verdana; font-size: 12px;color: #666666;line-height:16px;}

.itemListTbl{border:1px solid #ccc; width:100%; height:50px; margin:3px; padding:3px; empty-cells:show}
.itemListTbl .line {border-right:1px solid #ccc; padding-right:5px}
.green {background-color:#CEFBC9}
.gray {background-color:#EAEAEA}
/*

.orderTbl{}
.orderTbl caption{ text-align:left; border-bottom:1px solid #00F }
.orderTbl th{background:#efefef; border:1px solid #ccc; text-align:left; padding: 5px 0px 5px 15px; height:30px;font-size:11px;}
.orderTbl td{border:1px solid #ccc; text-align:left; padding: 5px 0px 5px 15px; border-left:0px;font-size:11px;}
.orderTbl td.noCont{border:0px; font-size:1px; height:7px; line-height:1px;}
*/
</style>

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>js/jquery-1.10.2.min.js"></script>

<script type="text/javascript">
<!--
var $j = jQuery.noConflict();

//window.moveTo(10,10);
//window.resizeTo(300,400);
//-->
</script>

</head>
<style type="text/css">
.couponName{ font-weight:bold; color:blue;}
</style>
<body topmargin="0" leftmargin="0">
<script language="javascript" type="text/javascript">
/*검색*/
function searchID(){
	var data = "";
	
	data = 'mode=id_search&search='+$j("#reseller_id").val();

	jQuery.ajax({
		url: "./reseller_search_result.php",
		type: "POST",
		data: data,
		contentType: "application/x-www-form-urlencoded;charset=euc-kr",
		success: function(res) {
			$j("#search_result").html(res);
		},
		error: function(result) {
			console.log(result);
		},
		timeout: 30000
	});
}

function selectid(id){
	$j("#reseller_id").val(id);
	searchID();
}

function idInput(){
	opener.form1.reseller_id.value=$j("#reseller_id").val();
	window.close();
}


function bookmarkID(mode,id){
	var data = "";
	
	data = 'mode='+mode+'&id='+id;

	jQuery.ajax({
		url: "./reseller_search_result.php",
		type: "POST",
		data: data,
		contentType: "application/x-www-form-urlencoded;charset=euc-kr",
		success: function(res) {
			searchID();
		},
		error: function(result) {
			console.log(result);
		},
		timeout: 30000
	});
}

function memoView(id){
	if($j("#btn_gubun").val()=="0"){
		$j(".btn_memo").hide();
		$j("#btn_memo_"+id).show();
		$j("#btn_memo_"+id).html("-");
		$j("#btn_gubun").val("1");

		$j("#tblmemo_"+id).show();
		$j("#tblmemo_"+id+" #memo_y").hide();
		$j("#tblmemo_"+id+" #memo_n").show();

	}else{
		$j(".btn_memo").show();
		$j("#btn_memo_"+id).html("+");
		$j("#btn_gubun").val("0");

		$j("#tblmemo_"+id).show();
		$j("#tblmemo_"+id+" #memo_y").show();
		$j("#tblmemo_"+id+" #memo_n").hide();
	}
}

function memoSave(mode,id){
	var data = "";
	
	data = 'mode='+mode+'&id='+id+'&memo='+$j("#memo_"+id).val();

	jQuery.ajax({
		url: "./reseller_search_result.php",
		type: "POST",
		data: data,
		contentType: "application/x-www-form-urlencoded;charset=euc-kr",
		success: function(res) {
			searchID();
		},
		error: function(result) {
			console.log(result);
		},
		timeout: 30000
	});
}
</script>
<form name="couponUseForm" id="couponUseForm">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th class="noCont">
				<input type="text" name="reseller_id" id="reseller_id" style="width:320px" onkeyup="javascript:searchID()">
				<input type="button" value="등록" onclick="javascript:idInput()">
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td id="search_result">
				<?
				$sql = "SELECT if(m.id='".$_ShopInfo->getMemid()."',1,2) as myid, m.id, name, mobile, office_addr,if(rb_idx is NULL,'x',rb_idx) bookmark ";
				$sql.= "FROM tblmember m LEFT JOIN reseller_bookmark rb ON (m.id=rb.bookmark_id and rb.id='".$_ShopInfo->getMemid()."') ";
				$sql.= "WHERE member_out = 'N' ";
				$sql.= "AND (group_code LIKE 'RP%' OR m.id='".$_ShopInfo->getMemid()."') ";
				$sql.= "ORDER BY myid asc, bookmark asc, m.id asc";

				$result = mysql_query($sql,get_db_conn());
				
				while($row=mysql_fetch_object($result)) {
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

				?>
				<table class="itemListTbl <?=$tbclass?>">
					<tr>
						<td class="line">
						<?=$favicon?>
						<A HREF="javascript:selectid('<?=$row->id?>');"><span class="font_blue"><B><U><?=$row->id?></U></B></span></A></td>
						<td class="line"><?=$name?></td>
						<td><?=preg_replace('/.(?!....)/u','*',$row->mobile)?></td>
					</tr>
					<tr>
						<td colspan="3"><?=$office_addr?></td>
					</tr>
					<tr>
						<td colspan="3" align="right">
							<input type="hidden" name="btn_gubun" id="btn_gubun" value="0">
							<span id="btn_memo_<?=$row->id?>" class="btn_memo" onclick="javascript:memoView('<?=$row->id?>')" style="cursor:pointer">+</span>
						</td>
					</tr>
				</table>
				<table id="tblmemo_<?=$row->id?>" style="display:<?=$display_y?>">
					<? if($mRow->memo){ ?>
					<tr id="memo_y" style="display:<?=$display_y?>">
						<td>(메모) <?=$mRow->memo?></td>
					</tr>
					<? } ?>
					<tr id="memo_n" style="display:<?=$display_n?>">
						<td>
							<input type="text" name="memo_<?=$row->id?>" id="memo_<?=$row->id?>" value="<?=($mRow->memo!="")? $mRow->memo : "";?>" placeholder="<?=$placeholder?>" style="width:300px">
							<input type="button" value="저장" onclick="javascript:memoSave('<?=($mRow->memo!="")? "memo_modify" : "memo_insert";?>','<?=$row->id?>')">
						</td>
					</tr>
				</table>
				<?
				}
				?>
			</td>
		</tr>
	</tbody>
</table>
</form>
</body>
</html>