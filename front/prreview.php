<?
$_cdata->detail_type = 'AD001';

//����Ʈ ����
$setup[page_num] = 10;
$setup[list_num] = 10;

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

$colspan=3;
$addsql="";
if(strlen($rselectreview)>0){
	switch($rselectreview){
		case "photo":
			$addsql .= "AND img IS NOT NULL AND img != '' ";
		break;
		case "best":
			$addsql .= "AND best = 'Y' ";
		break;
		case "basic":
			$addsql .= "AND img IS NULL OR img = '' ";
		break;
		case "all":
		default:
		break;
	}
}
if($reviewdate!="N") $colspan=4;
$qry = "WHERE productcode='".$productcode."' ";
if($_data->review_type=="A") $qry.= "AND display='Y' ";
$sql = "SELECT COUNT(*) as t_count, SUM(marks) as totmarks FROM tblproductreview ";
$sql.= $qry;
$sql.= $addsql;
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$t_count = (int)$row->t_count;
$totmarks = (int)$row->totmarks;
$marks=@ceil($totmarks/$t_count);
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;
?>

<div  style="padding:15px 0px;overflow:hidden;">
		<!--<td width="140"><span style="color: #000; font-size: 14px; font-weight: bold;">����ǰ��</span> (<?=$counttotal?>)</td>-->
		<p style="float:left">
			<div class="button1 <?=$sallreview?>"><a href="javascript:reviewSelect('all');"><span style="font-size: 13px;">��ü ��ǰ��(<?=$counttotal?>)</span></a></div>
			<div class="button1 <?=$sbestreview?>"><a href="javascript:reviewSelect('best');"><span style="font-size: 13px;">����Ʈ��ǰ��(<?=$countbest?>)</span></a></div>
			<div class="button1 <?=$sphotoreview?>"><a href="javascript:reviewSelect('photo');"><span style="font-size: 13px;">���� ��ǰ��(<?=$countphoto?>)</span></a></div>
			<div class="button1 <?=$sbasicreview?>"><a href="javascript:reviewSelect('basic');"><span style="font-size: 13px;">�Ϲ� ��ǰ��(<?=$countbasic?>)</span></a></div>
			<!--* �� <font color="#F02800" style="font-size:11px; letter-spacing:-0.5pt;"><b><?=$counttotal?>��</b></font>�� ����ıⰡ ��ϵǾ� �ֽ��ϴ�.-->
			<!--&nbsp;&nbsp;��պ��� : <?//=$reviewstarcount?>-->
		</p>
		<p style="float:right">
			<?
				if((strlen($_ShopInfo->getMemid())==0) && $_data->review_memtype=="Y") {
					echo "<A HREF=\"javascript:check_login()\" class=\"btn_line\">���� �ۼ��ϱ�</A>";
				} else {
					echo "<A HREF=\"javascript:review_write()\" class=\"btn_line\">���� �ۼ��ϱ�</A>";
				}
			?>
			<?if ($_data->ETCTYPE["REVIEW"]=="Y") {?>
				<A HREF="<?=$Dir.FrontDir?>reviewall.php"  class="btn_line">��ü ��ǰ�� ����</a>
			<?}?>
		</p>
</div>

<div id="reviewwrite" style="display:none;">

	<div id="div_reviewwrite_container">
		<form name="reviewWriteForm" action="/front/reviewwrite_proc.php" method="post" enctype="multipart/form-data">
			<input type="hidden" name="mode" />
			<input type="hidden" name="code" value="<?=$code?>" />
			<input type="hidden" name="productcode" value="<?=$productcode?>" />
			<input type="hidden" name="sort" value="<?=$sort?>" />
			<?=($brandcode>0?"<input type=\"hidden\" name=\"brandcode\" value=\"".$brandcode."\" />\n":"")?>

			<table cellpadding="0" cellspacing="0" class="reviewMarkTbl">
				<tr>
					<th>* ǰ��</th>
					<td>
						<select name="quality">
							<option value="1">��</option>
							<option value="2">�ڡ�</option>
							<option value="3">�ڡڡ�</option>
							<option value="4">�ڡڡڡ�</option>
							<option value="5" selected>�ڡڡڡڡ�</option>
						</select>
					</td>
					<th>* ����</th>
					<td>
						<select name="price">
							<option value="1">��</option>
							<option value="2">�ڡ�</option>
							<option value="3">�ڡڡ�</option>
							<option value="4">�ڡڡڡ�</option>
							<option value="5" selected>�ڡڡڡڡ�</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>* ���</th>
					<td>
						<select name="delitime">
							<option value="1">��</option>
							<option value="2">�ڡ�</option>
							<option value="3">�ڡڡ�</option>
							<option value="4">�ڡڡڡ�</option>
							<option value="5" selected>�ڡڡڡڡ�</option>
						</select>
					</td>
					<th>* ��õ</th>
					<td>
						<select name="recommend">
							<option value="1">��</option>
							<option value="2">�ڡ�</option>
							<option value="3">�ڡڡ�</option>
							<option value="4">�ڡڡڡ�</option>
							<option value="5" selected>�ڡڡڡڡ�</option>
						</select>
					</td>
				</tr>
			</table>

			<table cellpadding="0" cellspacing="0" class="reviewWriteTbl">
				<tr>
					<th>�ۼ���</th>
					<td><input type="text" name="rname" maxlength="10" class="input" value=""/></td>
				</tr>
				<tr>
					<th>����</th>
					<td><textarea name="rcontent" style="WIDTH:100%; HEIGHT:40px; padding:3pt; line-height:17px; border:solid 1px; border-color:#DFDFDF; font-size:9pt; color:333333;"></textarea></td>
				</tr>
				<tr>
					<th>÷������</th>
					<td><input type="file" name="attech" class="input" value=""/></td>
				</tr>
			</table>
			<div class="reviewInfoDiv">
				<b>��</b> �ۼ��� ��ǰ���� ����/������ �Ұ��ϴ� ������ �ֽñ� �ٶ��ϴ�.<br />
				<b>��</b> ������ ������� ���� ���� �����ڿ� ���� ���Ƿ� ������ �� �ֽ��ϴ�.<br />
				<b>��</b> ÷�������� �̹��� ����(GIF, JPG, PNG)�� ��� �����մϴ�.
			</div>
		</form>
	</div>
	<div style="padding:10px 0px 20px 0px; text-align:center;">
		<A HREF="javascript:write_review();"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_reviewok.gif" border="0" alt="" /></a>
		<A HREF="javascript:review_write();"><img src="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/btn_reviewcancel.gif" border="0" alt="" /></a>
	</div>
</div>

<div>
	<table cellpadding="0" cellspacing="0" width="100%" style="border-top:1px solid #ededed;">
		<col width="80"></col>
		<col width=></col>
		<?if($reviewdate!="N"){?>
		<col width="80"></col>
		<?}?>
		<col width="90"></col>
<!--
		<tr><td height="1" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_line2.gif" colspan="<?=$colspan?>"></td></tr>
		<tr height="30" align="center" bgcolor="#F8F8F8" style="letter-spacing:-0.5pt;">
			<td><b>�ۼ���</b></td>
			<td><b>��ǰ��</b></td>
			<?if($reviewdate!="N"){?>
			<td><b>�ۼ���</b></td>
			<?}?>
			<td><b>����</b></td>
		</tr>
		<tr>
			<td height="1" colspan="<?=$colspan?>" background="<?=$Dir?>images/common/product/<?=$_cdata->detail_type?>/pdetail_skin_line4.gif"></td>
		</tr>
-->
<?
	$sql = "SELECT * FROM tblproductreview ".$qry." ";
	$sql.= $addsql;
	$sql.= "ORDER BY date DESC ";
	$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
	$result=mysql_query($sql,get_db_conn());
	$j=0;
	while($row=mysql_fetch_object($result)){
		$number = ($t_count-($setup[list_num] * ($gotopage-1))-$j);

		$date=substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2);
		$content=explode("=",$row->content);
		echo "<tr>\n";
		echo "	<td >";
		for($i=0;$i<$row->marks;$i++) {
			//echo "<FONT color=#000000><B>��</B></FONT>";
			echo "<img src=\"/images/003/star_point1.gif\" alt=\"\" />";
		}
		for($i=$row->marks;$i<5;$i++) {
			//echo "<FONT color=#DEDEDE><B>��</B></FONT>";
			echo "<img src=\"/images/003/star_point2.gif\" alt=\"\" />";
		}
		echo "	</td>\n";
		echo "	<td style=\"text-align:left;padding:13px;\">";
		if($reviewlist=="Y") {
			echo "<A HREF=\"javascript:view_review(".$j.")\">".titleCut(60,$content[0])."</A>";
		} else {
			echo "<A HREF=\"javascript:review_open('".$row->productcode."',".$row->num.")\">".titleCut(60,$content[0])."</A>";
		}
		if(strlen($content[1])>0) echo "<img src=\"".$Dir."images/common/review/review_replyicn.gif\" border=0 align=absmiddle>";
		echo "	</td>\n";
		echo "	<td>".$row->name."</td>\n";
		if($reviewdate!="N") {
			echo "	<td>".$date."</td>\n";
		}
		echo "</tr>\n";

		if($reviewlist=="Y") {
			echo "<tr class=\"reviewspan\" style=\"display:none;\">\n";
			echo "	<td colspan=".$colspan.">\n";
			echo "	<table border=0 cellpadding=0 cellspacing=0 width=100% bgcolor=#f0f0f0 style=\"table-layout:fixed;\">\n";
			echo "	<tr>\n";
			echo "		<td style=\"border:#f0f0f0 solid 0px;\">\n";
			echo "		<table border=0 cellpadding=0 cellspacing=0 width=100% bgcolor=#F1F1F1 style=\"table-layout:fixed;\">\n";
			echo "		<tr>\n";
			echo "			<td align=center style=\"padding:0\">\n";
			echo "			<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";
			echo "			<tr>\n";
			echo "				<td bgcolor=\"#FFFFFF\" style=\"border-top:#ededed solid 1px; border-left:hidden; border-right:hidden; padding:15px;background-color:#f9f9f9;\">\n";
			echo "				<table border=0 cellpadding=0 cellspacing=0 width=100% background=#f5f5f5>\n";
			
			if(!empty($row->img) && file_exists($Dir.DataDir."shopimages/productreview/".$row->img)){
				echo "				<tr><td><img src=\"".$Dir.DataDir."shopimages/productreview/".$row->img."\" border=\"0\" width=\"260\" /></td>\n";
			}
			echo "					<td width=100% height=100% valign=\"top\" style=\"padding:15px\"><table border=0 cellpadding=0 cellspacing=0 width=100% height=100%><tr><td valign=top height=100%>".nl2br($content[0])."</td></tr><tr><td align=right><a href=\"javascript:view_review(".$j.")\" class=\"btn_sline\">x</a></td></tr></table></td></tr>";
			
			if(strlen($content[1])>0) {
				echo "				<tr><td style=\"padding:5 5 5 10px\"><img src=\"".$Dir."images/common/review/review_replyicn2.gif\" align=absmiddle border=0> ".nl2br($content[1])."</td></tr>\n";
			}
			echo "				</table>\n";
			echo "				</td>\n";
			echo "			</tr>\n";
			echo "			</table>\n";
			echo "			</td>\n";
			echo "		</tr>\n";
			echo "		</table>\n";
			echo "		</td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
		}

		echo "<tr><td height=\"1\" colspan=\"".$colspan."\" style=\"background:#ededed\"></td></tr>\n";
		$j++;
	}
	mysql_free_result($result);
	if($j==0) {
		echo "<tr><td colspan=\"".$colspan."\" height=\"40\" align=\"center\">��ϵ� ����ıⰡ �����ϴ�.</td></tr>\n";
		//echo "<tr><td height=\"1\" colspan=\"".$colspan."\" background=\"".$Dir."images/common/product/".$_cdata->detail_type."/pdetail_skin_line4.gif\"></td></tr>\n";
	}
?>
	</table>

	<table cellpadding="0" cellspacing="0" width="100%" style="margin-top:10px;">
<?
	 if($j != 0) {
		$total_block = intval($pagecount / $setup[page_num]);

		if (($pagecount % $setup[page_num]) > 0) {
			$total_block = $total_block + 1;
		}

		$total_block = $total_block - 1;

		if (ceil($t_count/$setup[list_num]) > 0) {
			// ����	x�� ����ϴ� �κ�-����
			$a_first_block = "";
			if ($nowblock > 0) {
				$a_first_block .= "<a href='javascript:GoPage(\"review\",0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><FONT class=\"prlist\">[1...]</FONT></a>&nbsp;&nbsp;";

				$prev_page_exists = true;
			}

			$a_prev_page = "";
			if ($nowblock > 0) {
				$a_prev_page .= "<a href='javascript:GoPage(\"review\",".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><FONT class=\"prlist\">[prev]</FONT></a>&nbsp;&nbsp;";

				$a_prev_page = $a_first_block.$a_prev_page;
			}

			// �Ϲ� �������� ������ ǥ�úκ�-����

			if (intval($total_block) <> intval($nowblock)) {
				$print_page = "";
				for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
					if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
						$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$setup[page_num]) + $gopage)."</font> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(\"review\",".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</FONT></a> ";
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
						$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$setup[page_num]) + $gopage)."</font> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(\"review\",".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</FONT></a> ";
					}
				}
			}		// ������ �������� ǥ�úκ�-��


			$a_last_block = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
				$last_gotopage = ceil($t_count/$setup[list_num]);

				$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(\"review\",".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><FONT class=\"prlist\">[...".$last_gotopage."]</FONT></a>";

				$next_page_exists = true;
			}

			// ���� 10�� ó���κ�...

			$a_next_page = "";
			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(\"review\",".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><FONT class=\"prlist\">[next]</FONT></a>";

				$a_next_page = $a_next_page.$a_last_block;
			}
		} else {
			$print_page = "<FONT class=\"prlist\">1</FONT>";
		}
	}
?>
		<tr>
			<td align="center" style="font-size:11px;">
				<?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?>
			</td>
		</tr>
	</table>
</div>