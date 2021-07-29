<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");


$storefileURL = $_SERVER['DOCUMENT_ROOT']."/data/trust_store/";

$order=$_POST["order"]? $_POST["order"] : "regdate";
$code=$_POST["code"];
$addr=$_POST["addr"];

if($order=="commi"){
	$sort="ORDER BY CONVERT(substring_index(substring_index(product_commi,'//',1),':',-1),UNSIGNED) ASC";
}else if($order=="com_name"){
	$sort="ORDER BY com_name ASC";
}else{
	$sort="ORDER BY tm_idx DESC";
}


$setup[page_num] = 10;
$setup[list_num] = 18;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];
if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

$t_count=0;
$qry = "WHERE 1=1 AND delflag='N' ";
$qry .= "AND approve='Y' ";

if($mode=="search"){
	if($codeA){
		$qry .= "AND tm.product_commi LIKE '%".$codeA."%' ";
	}
	if($addr){
		$qry .= "AND tm.store_addr LIKE '%".$addr."%' ";
	}
}
$sql = "SELECT COUNT(*) as t_count FROM tbltrustmanage tm left join tblvenderinfo v on tm.vender=v.vender ".$qry." ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$t_count = $row->t_count;
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<link href="/js/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<script src="/js/jquery-ui-1.11.4/external/jquery/jquery.js"></script>
<script src="/js/jquery-ui-1.11.4/jquery-ui.js"></script>

<script language="JavaScript">
function trustApply(tm_idx){
	window.open("","apply","width=800,height=600,scrollbars=yes");
	document.popForm.tm_idx.value = tm_idx;
	document.popForm.submit();
}

function trustManage(tm_idx){
	var url = "";
	if(tm_idx) url = "trust_manage_pop.php?tm_idx="+tm_idx;
	else url = "trust_manage_pop.php";
	window.open(url,"trustmanage","scrollbars=yes,width=724,height=600");
}

$(function(){
	$('select[name=addr]').change(function() {
		$("#searchForm").submit();
	})

	$('select[name=codeA]').change(function() {
		$("#searchForm").submit();
	})

	$('input[name=order]').click(function() {
		$("#searchForm").submit();
	})
})

function GoPage(block,gotopage) {
	document.pageForm.block.value=block;
	document.pageForm.gotopage.value=gotopage;
	document.pageForm.submit();
}

</script>


<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed"  height="100%" >
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=20></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">
	
		<section>
			<article class="vTitle">
				<h2>위탁계약 관리</h2>
				<div class="vTitle_bgimg"></div>
				<ul>
					<li class="notice_gray"><img src="images/icon_dot02.gif" border=0 hspace="4">잠깐닷컴의 위탁계약을 관리할 수 있는 페이지입니다.</li>
					<li class="notice_gray"><img src="images/icon_dot02.gif" border=0 hspace="4">보유상품의 직접관리가 어려울 경우 품목별 전문 위탁업체를 선택하여 관리 받을 수 있습니다.</li>
				</ul>
			</article>
			<article class="trList">
				<h4>계약관리목록</h4>
				<table>
					<?
					//받은위탁정보가져오기
					$sql = "SELECT ta.ta_idx,tm.product_commi,v.account_date,vi.adjust_lastday,ta.regdate,v.com_name,tm.store_addr FROM tbltrustagree ta ";
					$sql.= "left join tbltrustmanage tm on tm.vender=ta.take_vender ";
					$sql.= "left join tblvenderinfo v on v.vender=ta.give_vender ";
					$sql.= "left join vender_more_info vi on vi.vender=ta.take_vender ";
					$sql.= "WHERE v.delflag='N' AND ta.take_vender='".$_VenderInfo->getVidx()."' ";
					$sql.= "AND (ta.approve='Y' OR ta.approve='N')";
					$result=mysql_query($sql,get_db_conn());
					$takeCnt = mysql_num_rows($result);
					while($row=mysql_fetch_object($result)){
						switch($row->adjust_lastday) {
						case 0 : $account_date = "매월 ".$row->account_date."일";
							break;
						case 1 : $account_date = "매월 마지막일";
							break;
						case 2 : $account_date = "매월 15일과 마지막일";
							break;
						}

						$arrPr_commi = explode("//",$row->product_commi);
						for($i=0;$i<sizeof($arrPr_commi);$i++){
							$arrCommi[$i] = explode(":",$arrPr_commi[$i]);

							$sql_ = "SELECT code_name FROM tblproductcode ";
							$sql_.= "WHERE codeA='".$arrCommi[$i][0]."' ";
							$sql_.= "AND codeB='000' AND codeC='000' ";
							$sql_.= "AND codeD='000' AND type LIKE 'L%'";
							$cRes=mysql_query($sql_,get_db_conn());
							while($cRow=mysql_fetch_object($cRes)) {
								if($i==0){
									$mainCodeNm = $cRow->code_name;
									$mainCommi = $arrCommi[$i][1]."%";
								}else{
									$codeNm .= $cRow->code_name.",";
								}

								$pr_commi .= $cRow->code_name."(".$arrCommi[$i][1]."%),";
							}
							mysql_free_result($cRow);
						}
					?>
					<tr>
						<th scope="col">받은위탁<br><?=str_replace("-",".",$row->regdate)?></th>
						<td class="txtBold"><?=$row->com_name?></td>
						<td><?=$mainCodeNm?></td>
						<td><?=$mainCommi?></td>
						<td><?=$account_date?></td>
						<td class="addr"><?=$row->store_addr?></td>
						<td><button type="button" onclick="javascript:location.href='trust_view.php?type=take&ta_idx=<?=$row->ta_idx?>'">자세히 보기</button></td>
					</tr>
					<?
					}//받은위탁 end while

					//보낸위탁정보가져오기
					$sql = "SELECT ta.ta_idx,tm.product_commi,v.account_date,vi.adjust_lastday,ta.regdate,v.com_name,tm.store_addr FROM tbltrustmanage tm ";
					$sql.= "left join tbltrustagree ta on ta.take_vender=tm.vender ";
					$sql.= "left join tblvenderinfo v on tm.vender=v.vender ";
					$sql.= "left join vender_more_info vi on tm.vender=vi.vender ";
					$sql.= "WHERE v.delflag='N' AND ta.give_vender='".$_VenderInfo->getVidx()."' ";
					$sql.= "AND (ta.approve='Y' OR ta.approve='N')";
					$result=mysql_query($sql,get_db_conn());
					$giveCnt = mysql_num_rows($result);
					while($row=mysql_fetch_object($result)){
						switch($row->adjust_lastday) {
						case 0 : $account_date = "매월 ".$row->account_date."일";
							break;
						case 1 : $account_date = "매월 마지막일";
							break;
						case 2 : $account_date = "매월 15일과 마지막일";
							break;
						}

						$arrPr_commi = explode("//",$row->product_commi);
						for($i=0;$i<sizeof($arrPr_commi);$i++){
							$arrCommi[$i] = explode(":",$arrPr_commi[$i]);

							$sql_ = "SELECT code_name FROM tblproductcode ";
							$sql_.= "WHERE codeA='".$arrCommi[$i][0]."' ";
							$sql_.= "AND codeB='000' AND codeC='000' ";
							$sql_.= "AND codeD='000' AND type LIKE 'L%'";
							$cRes=mysql_query($sql_,get_db_conn());
							while($cRow=mysql_fetch_object($cRes)) {
								if($i==0){
									$mainCodeNm = $cRow->code_name;
									$mainCommi = $arrCommi[$i][1]."%";
								}else{
									$codeNm .= $cRow->code_name.",";
								}

								$pr_commi .= $cRow->code_name."(".$arrCommi[$i][1]."%),";
							}
							mysql_free_result($cRow);

						}
					
					?>
					<tr class="bgPink">
						<th scope="col">보낸위탁<br><?=str_replace("-",".",$row->regdate)?></th>
						<td class="txtBold"><?=$row->com_name?></td>
						<td><?=$mainCodeNm?></td>
						<td><?=$mainCommi?></td>
						<td><?=$account_date?></td>
						<td class="addr"><?=$row->store_addr?></td>
						<td><button type="button" onclick="javascript:location.href='trust_view.php?type=give&ta_idx=<?=$row->ta_idx?>'">자세히 보기</button></td>
					</tr>
					<?
					}//보낸위탁 end while

					if($takeCnt==0 && $giveCnt==0){
					?>
					<tr>
						<td>
							위탁관리 계약요청 혹은 계약완료 업체가 없습니다. 원하는 관리업체를 선택한 수 신청하시거나 관리업체 등록하실 수 있습니다.
						</td>
					</tr>
					<?
					}
					?>
				</table>
			</article>
			<article class="contsList">
				<form name="searchForm" id="searchForm" method="post" action="<?=$PHP_SELF?>">
				<input type="hidden" name="mode" value="search">
				<fieldset>
				<legend>위탁관리 업체목록 검색하기</legend>
				<div class="title">
					<h4>위탁관리 업체목록
					<select name="addr">
						<option value="">지역 전체보기</option>
						<?
						$sql = "SELECT store_sido,store_sigungu FROM tbltrustmanage ";
						$sql.= "GROUP BY store_sido,store_sigungu ";
						$sql.= "ORDER BY store_sido,store_sigungu ASC ";
						$result=mysql_query($sql,get_db_conn());
						while($row=mysql_fetch_object($result)) {
							$area = $row->store_sido." ".$row->store_sigungu;
							echo "<option value=\"".$area."\"";
							if($area==$addr){ echo "selected"; }
							echo ">".$area."</option>\n";
						}
						mysql_free_result($result);
						?>
					</select>
					<select name="codeA">
						<option value="">품목 전체보기</option>
						<?
						$sql = "SELECT codeA,code_name,type FROM tblproductcode ";
						$sql.= "WHERE codeB='000' AND codeC='000' ";
						$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
						$result=mysql_query($sql,get_db_conn());
						while($row=mysql_fetch_object($result)) {
							echo "<option value=\"".$row->codeA."\"";
							if($row->codeA==$codeA){ echo "selected"; }
							echo ">".$row->code_name."</option>\n";
						}
						mysql_free_result($result);
						?>
					</select>
					</h4>
				</div>
				<div class="order">
					<input type="radio" name="order" value="regdate" <?=$order=="regdate"? "checked":"";?>>등록일
					<input type="radio" name="order" value="commi" <?=$order=="commi"? "checked":"";?>>수수료
					<input type="radio" name="order" value="com_name" <?=$order=="com_name"? "checked":"";?>>업체명
				</div>
				</fieldset>
				</form>
				<div class="clear"></div>

				<div class="contsBox">
					<?
					$sql = "SELECT * FROM tbltrustmanage tm left join tblvenderinfo v on tm.vender=v.vender ";
					$sql.= "left join vender_more_info vi on tm.vender=vi.vender ";
					$sql.= $qry." ".$sort." ";
					$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
					$result=mysql_query($sql,get_db_conn());
					$i=0;
					while($row=mysql_fetch_object($result)) {
						$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);

						if($i%3==0){
							$firstClass = "first";
						}else{
							$firstClass = "";
						}

						switch($row->adjust_lastday) {
							case 0 : $account_date = "매월 ".$row->account_date."일";
								break;
							case 1 : $account_date = "매월 마지막일";
								break;
							case 2 : $account_date = "매월 15일과 마지막일";
								break;
						}
						
						//주품목,위탁수수료
						$arrPr_commi = explode("//",$row->product_commi);
						$arrCommi[0] = explode(":",$arrPr_commi[0]);

						$sql_ = "SELECT code_name FROM tblproductcode ";
						$sql_.= "WHERE codeA='".$arrCommi[0][0]."' ";
						$sql_.= "AND codeB='000' AND codeC='000' ";
						$sql_.= "AND codeD='000' AND type LIKE 'L%'";
						$cRes=mysql_query($sql_,get_db_conn());
						$cRow=mysql_fetch_object($cRes);
						$mainCodeNm = $cRow->code_name;
						$mainCommi = $arrCommi[0][1]."%";
						mysql_free_result($cRow);

						if($row->com_image){
							$comImg = "<img src=".$com_image_url.$row->com_image." width=\"125\" height=\"125\" alt=\"\">";
						}else{
							$comImg = "<img src=\"../images/no_img.gif\" width=\"125\" height=\"125\" >";
						}

					?>
					<div class="figure <?=$firstClass?>">
						<div class="irap"><?=$comImg?></div>
						<div class="figureCaption">
							<ul class="markup">
								<li><?=$row->com_name." | ".$row->id?></li>
								<li>위치:<?=$row->store_addr?></li>
								<li>주품목:<?=$mainCodeNm?></li>
								<li>위탁수수료:<?=$mainCommi?></li>
								<li>결산일:<?=$account_date?></li>
								<li>정산일:<?=(strlen($row->close_date)>0?"정산일 ".$row->close_date." 일전":"")?></li>
							</ul>
						</div>
						<? if($_VenderInfo->getVidx()<>$row->vender){ ?>
						<button type="button" onclick="javascript:trustApply('<?=$row->tm_idx?>')">내 품목 위탁관리 신청</button>
						<? } ?>
					</div>
					<?
					}
					?>

				</div>
				
				<div class="pageing">
					<form name="pageForm" method="post">
					<input type=hidden name='code' value='<?=$code?>'>
					<input type=hidden name='disptype' value='<?=$disptype?>'>
					<input type=hidden name='s_check' value='<?=$s_check?>'>
					<input type=hidden name='search' value='<?=$search?>'>
					<input type=hidden name='sort' value='<?=$sort?>'>
					<input type=hidden name='block' value='<?=$block?>'>
					<input type=hidden name='gotopage' value='<?=$gotopage?>'>
					<input type="hidden" name="list_num" value="<?=$setup[list_num]?>">
					</form>

					<table cellpadding="0" cellspacing="0" width="100%">
<?
		$total_block = intval($pagecount / $setup[page_num]);

		if (($pagecount % $setup[page_num]) > 0) {
			$total_block = $total_block + 1;
		}

		$total_block = $total_block - 1;

		if (ceil($t_count/$setup[list_num]) > 0) {
			// 이전	x개 출력하는 부분-시작
			$a_first_block = "";
			if ($nowblock > 0) {
				$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='첫 페이지';return true\"><IMG src=\"images/icon_first.gif\" border=0 align=\"absmiddle\"></a>&nbsp;&nbsp;";

				$prev_page_exists = true;
			}

			$a_prev_page = "";
			if ($nowblock > 0) {
				$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='이전 ".$setup[page_num]." 페이지';return true\">[prev]</a>&nbsp;&nbsp;";

				$a_prev_page = $a_first_block.$a_prev_page;
			}

			// 일반 블럭에서의 페이지 표시부분-시작

			if (intval($total_block) <> intval($nowblock)) {
				$print_page = "";
				for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
					if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
						$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='페이지 : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
					}
				}
			} else {
				if (($pagecount % $setup[page_num]) == 0) {
					$lastpage = $setup[page_num];
				} else {
					$lastpage = $pagecount % $setup[page_num];
				}

				for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
					if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
						$print_page .= "<span class=font_orange2><B>[".(intval($nowblock*$setup[page_num]) + $gopage)."]</B></span> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='페이지 : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
					}
				}
			}		// 마지막 블럭에서의 표시부분-끝


			$a_last_block = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
				$last_gotopage = ceil($t_count/$setup[list_num]);

				$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='마지막 페이지';return true\"><IMG src=\"images/icon_last.gif\" border=0 align=\"absmiddle\" width=\"17\" height=\"14\"></a>";

				$next_page_exists = true;
			}

			// 다음 10개 처리부분...

			$a_next_page = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='다음 ".$setup[page_num]." 페이지';return true\">[next]</a>";

				$a_next_page = $a_next_page.$a_last_block;
			}
		} else {
			$print_page = "<B>[1]</B>";
		}
		echo "<tr>\n";
		echo "	<td width=\"100%\" class=\"font_size\"><p align=\"center\">\n";
		echo "		".$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
		echo "	</td>\n";
		echo "</tr>\n";
?>
					</table>	
				</div>
			</article>
			<?
			$sql = "SELECT * FROM tbltrustmanage WHERE vender='".$_VenderInfo->getVidx()."'";
			$result=mysql_query($sql,get_db_conn());
			$data=mysql_fetch_object($result);

			if($data->approve=="N"){
			?>
			<article class="trust_apply">
				<h4>위탁관리 업체신청</h4>
				<div class="desc">· 물품 창고를 보유하고 있으며 온라인 쇼핑관리가 가능할 경우, 잠깐닷컴의 위탁업체로 등록하여 많은 물품을 위탁받을 수 있습니다.</div>
				<div class="applyBtn">
					<button type="button" id="redBtn">위탁관리 업체등록 신청중입니다</button>
				</div>
			</article>
			<?
			}else if($data->approve=="Y"){//승인
			?>
			<article class="trust_apply">
				<h4>위탁관리 업체신청</h4>
				<div class="desc">· 위탁관리 업체로 등록이 완료됐습니다. 위탁관리 업체 목록에서 등록하신 업체정보를 다시 확인해주세요.</div>
				<div class="applyBtn">
					<button type="button" id="redBtn">위탁관리 업체등록 완료</button>
				</div>
			</article>
			<?
			}else if($data->approve=="R"){//거절
			?>
			<article class="trust_apply">
				<h4>위탁관리 업체신청</h4>
				<div class="desc">
					· 위탁관리 업체로 등록이 거절됐습니다. 위탁관리에는 보유 창고에 대한 명확한 증빙자료가 필요합니다. 신청하신 정보를 다시 확인해주세요.<br>
					· <b>거절사유</b> : <?=$data->refuse_reason?>
				</div>
				<div class="applyBtn">
					<button type="button" id="redBtn" onclick="javascript:trustManage('<?=$data->tm_idx?>')">위탁관리 업체등록 재신청하기</button>
				</div>
			</article><br><br>
			<?
			}else{
			?>
			<article class="trust_apply">
				<h4>위탁관리 업체신청</h4>
				<div class="desc">· 물품 창고를 보유하고 있으며 온라인 쇼핑관리가 가능할 경우, 잠깐닷컴의 위탁업체로 등록하여 많은 물품을 위탁받을 수 있습니다.</div>
				<div class="applyBtn">
					<button type="button" id="redBtn" onclick="javascript:trustManage()">위탁관리 업체등록 신청하기</button>
				</div>
			</article>
			<?
			}
			?>
		</section>

	</td>
	<td>


<form name="popForm" method=post action="trust_apply_pop.php" target="apply">
<input type=hidden name="tm_idx">
</form>


<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>
<?
//승인여부 확인체크하기
if($data->approve!="N" && $data->approve_check=="N"){
	$sql = "UPDATE tbltrustmanage SET ";
	$sql.= "approve_check	= 'Y' ";
	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."'";
	mysql_query($sql,get_db_conn());
}
?>
<?=$onload?>

<? INCLUDE "copyright.php"; ?>