<?
$pridx=$_pdata->pridx;

$qnablock=$_REQUEST["qnablock"];
$qnagotopage=$_REQUEST["qnagotopage"];

if ($qnablock != "") {
	$nowblock = $qnablock;
	$curpage  = $qnablock * $qnasetup->page_num + $qnagotopage;
} else {
	$nowblock = 0;
	$curpage="";
}

if (($qnagotopage == "") || ($qnagotopage == 0)) {
	$qnagotopage = 1;
}
$colspan=4;
if($qnasetup->datedisplay!="N") $colspan=5;

$sql = "SELECT COUNT(*) as t_count FROM tblboard WHERE board='".$qnasetup->board."' AND pridx='".$pridx."' ";
if ($qnasetup->use_reply != "Y") {
	$sql.= "AND pos = 0 AND depth = 0 ";
}
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
$t_count=$row->t_count;
mysql_free_result($result);
$pagecount = (($t_count - 1) / $qnasetup->list_num) + 1;

$qna_all=$Dir.BoardDir."board.php?board=".$qnasetup->board;
if($qnasetup->grant_write=="N") {
	$qna_write=$Dir.BoardDir."board.php?pagetype=write&board=".$qnasetup->board."&exec=write&pridx=".$pridx."";
} else if($qnasetup->grant_write=="Y") {
	if(strlen($_ShopInfo->getMemid())>0) {
		$qna_write=$Dir.BoardDir."board.php?pagetype=write&board=".$qnasetup->board."&exec=write&pridx=".$pridx."";
	} else {
		$qna_write="javascript:check_login()";
	}
} else {
	$qna_write="javascript:view_qnacontent('W')";
}

$isgrantview=false;
if($qnasetup->grant_view=="N" || $qnasetup->grant_view=="U") {
	$isgrantview=true;
} else if($qnasetup->grant_view=="Y" && strlen($_ShopInfo->getMemid())){
	$isgrantview=true;
}

if(strlen($qnasetup->group_code)==4) {
	$isgrantview=false;
	$qna_write="javascript:view_qnacontent('W')";
	if($qnasetup->group_code==$_ShopInfo->getMemgroup()) {
		$isgrantview=true;
		if($qnasetup->grant_write!="A") {
			$qna_write=$Dir.BoardDir."board.php?pagetype=write&board=".$qnasetup->board."&exec=write&pridx=".$pridx."";
		}
	}
}

?>

<div style="overflow:hidden;padding:30px 0px;">
	<p style="text-align:center;">��ǰ�� ���Ͽ� �ñ��� ���� �����ø� �����Ͽ� �ּ���.</p>
	<p style="float:right"><A HREF="<?=$qna_write?>" class="btn_line">��ǰ���� �ۼ��ϱ�</A> <A HREF="<?=$qna_all?>" class="btn_line">��ü ��ǰ���� ����</A></p>
</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-top:1px solid #ededed;">
<tr>
	<td colspan="2">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<col width="50"></col>
	<col></col>
	<col width="100"></col>
	<?if($qnasetup->datedisplay!="N"){?>
	<col width="100"></col>
	<?}?>
	<col width="50"></col>
<?
	$imgdir=$Dir.BoardDir."images/skin/".$qnasetup->board_skin;
	$sql = "SELECT * FROM tblboard WHERE board='".$qnasetup->board."' AND pridx='".$pridx."' ";
	if ($qnasetup->use_reply != "Y") {
		$sql.= "AND pos = 0 AND depth = 0 ";
	}
	$sql.= "ORDER BY thread,pos LIMIT ".($qnasetup->list_num*($qnagotopage-1)).",".$qnasetup->list_num;
	$result=mysql_query($sql,get_db_conn());
	$qnarowcount = mysql_num_rows($result);
	$j=0;
	while($row=mysql_fetch_object($result)) {
		$number = ($t_count-($qnasetup->list_num * ($qnagotopage-1))-$j);
		$row->title = stripslashes($row->title);
		if($qnasetup->use_html!="Y") {
			$row->title = strip_tags($row->title);
			$row->content = strip_tags($row->content);
		}
		$row->title = strip_tags($row->title);
		$row->title=getTitle($row->title);
		$row->title=getStripHide($row->title);
		$row->content=getStripHide(stripslashes($row->content));
		if($row->use_html!="1") {
			$row->content=nl2br($row->content);
		}
		$row->name = stripslashes(strip_tags($row->name));

		if($qnasetup->datedisplay=="Y") {
			$date=date("Y/m/d H:i",$row->writetime);
		} else if($qnasetup->datedisplay=="O") {
			$date=date("Y/m/d",$row->writetime);
		}

		unset($subject);
		if ($row->deleted!="1") {
			if($isgrantview) {
				if($qnasetup->grant_view == "N" || (($qnasetup->grant_view == "U" || $qnasetup->grant_view =="Y") && strlen($_ShopInfo->getMemid())>0)){
					if($row->is_secret!="1") {
						$subject = "<a href=\"javascript:view_qnacontent('".$j."')\">";
					} else {
						$subject = "<a href=\"javascript:view_qnacontent('S')\">";
					}
				}else{
					$subject = "<a href=\"javascript:view_qnacontent('N')\">";
				}
			} else {
				$subject = "<a href=\"javascript:view_qnacontent('N')\">";
			}
		} else {
			$subject = "<a href=\"javascript:view_qnacontent('D')\">";
		}
		$depth = $row->depth;
		if($qnasetup->title_length>0) {
			$len_title = $qnasetup->title_length;
		}
		$wid = 1;
		if ($depth > 0) {
			if ($depth == 1) {
				$wid = 6;
			} else {
				$wid = (6 * $depth) + (4 * ($depth-1));
			}
			$subject .= "<img src=\"".$imgdir."/x.gif\" width=\"".$wid."\" height=\"2\" border=\"0\">";
			$subject .= "<img src=\"".$imgdir."/re_mark.gif\" border=\"0\">";
			if ($len_title) {
				$len_title = $len_title - (3 * $depth);
			}
		}
		$title = $row->title;
		if ($len_title) {
			$title = titleCut($len_title,$title);
		}
		$subject .=  $title;
		if ($row->deleted!="1") {
			$subject .= "</a>";
		}
		unset($new_img);
		$isnew=false;
		if($qnasetup->newimg=="0") {	//1��
			if(date("Ymd",$row->writetime)==date("Ymd")) {
				$isnew=true;
			}
		} else if($qnasetup->newimg=="1") {//2��
			if(date("Ymd",$row->writetime+(60*60*24*1))>=date("Ymd")) {
				$isnew=true;
			}
		} else if($qnasetup->newimg=="2") {//24�ð�
			if(($row->writetime+(60*60*24))>=time()) {
				$isnew=true;
			}
		} else if($qnasetup->newimg=="3") {//36�ð�
			if(($row->writetime+(60*60*36))>=time()) {
				$isnew=true;
			}
		} else if($qnasetup->newimg=="4") {//48�ð�
			if(($row->writetime+(60*60*48))>=time()) {
				$isnew=true;
			}
		}

		if ($isnew) {
			$subject .= "&nbsp;<img src=\"".$imgdir."/icon_new.gif\" border=\"0\" align=\"absmiddle\">";
			$new_img .= "<img src=\"".$imgdir."/icon_new.gif\" border=\"0\" align=\"absmiddle\">";
		}
		if ($qnasetup->use_comment=="Y" && $row->total_comment > 0) {
			$subject .= "&nbsp;<img src=\"".$imgdir."/icon_memo.gif\" border=\"0\" align=\"absmiddle\">&nbsp;<font style=\"font-size:8pt;\">(<font color=\"#FF0000\">".$row->total_comment."</font>)</font>";
		}

		$comment_tot = $row->total_comment;
		$user_name = $row->name;
		$str_name = $user_name;
		$hit = $row->access;

		echo "<tr>\n";
		echo "	<td>".$number."</td>\n";
		echo "	<td style=\"text-align:left;padding:13px 0px;\">".$subject."</td>\n";
		echo "	<td>".$str_name."</td>\n";
		if($qnasetup->datedisplay!="N"){
			echo "	<td>".$date."</td>\n";
		}
		echo "	<td>".$hit."</td>\n";
		echo "</tr>\n";
		if($isgrantview) {
			if($row->is_secret!="1") {
				echo "<tr id=\"qnacontent".$j."\" style=\"display:none\">\n";
				echo "	<td colspan=\"".$colspan."\">\n";
			echo "	<table border=0 cellpadding=0 cellspacing=0 width=100% bgcolor=#f9f9f9 style=\"table-layout:fixed;\">\n";
			echo "	<tr>\n";
			echo "		<td style=\"border:#f9f9f9 solid 0px;\">\n";
			echo "		<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed;\">\n";
			echo "		<tr>\n";
			echo "			<td align=center style=\"padding:0\">\n";
			echo "			<table border=0 cellpadding=0 cellspacing=0 width=100% style=\"table-layout:fixed\">\n";
			echo "			<tr>\n";
			echo "				<td style=\"padding:25px 10px 15px 25px;\">\n";
			echo "				<table border=0 cellpadding=0 cellspacing=0 width=100% >\n";
				echo "				<tr><td>\n";
				if( strlen($row->filename) > 0 ) {
					$qnaFiles = $Dir."data/shopimages/board/qna/".$row->filename;
					$qnaSize = getimagesize ($qnaFiles);
					if( $qnaSize[0] > 0 ) {
						//$width = ($qnaSize[0] > 600)?"600":$qnaSize[0];
						//$width = " width='".$width."' ";
						$width = " width=\"269\" ";
						echo "<center><img src='".$qnaFiles."' ".$width." border='0'></center><BR>";
					} else {
						//echo "<center>[ �ٿ�ε� : <a href='".$Dir."/board/download.php?board=qna&file_name=".rawurlencode($row->filename)."' target='_top'>".$row->filename."</a> ]</center><BR>";
					}
				}
				echo "				</td>\n";
				echo "				<td width=100% height=100% valign=\"top\"><table border=0 cellpadding=0 cellspacing=0 width=100% height=100%><tr><td valign=top height=100%>".$row->content."</td></tr><tr><td align=right><a href=\"javascript:view_qnacontent('".$j."')\" class=\"btn_sline\">x</a></td></tr></table></td></tr>";
				echo "				</tr>\n";
				/*
				echo "				<tr>\n";
				echo "					<td align=\"right\"><a href=\"javascript:view_qnacontent('".$j."')\"><img src=\"".$Dir."images/common/event_popup_close.gif\" border=\"0\"></a></td>\n";
				echo "				</tr>\n";
				*/
				echo "				</table>\n";
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
		}
		echo "<tr><td colspan=\"".$colspan."\" height=\"1\" bgcolor=\"#ededed\"></td></tr>\n";
		$j++;
	}
	mysql_free_result($result);
	$a_div_prev_page=$a_prev_page=$print_page=$a_next_page=$a_div_next_page="";
	if($qnarowcount<=0) {
		echo "<tr><td colspan=\"".$colspan."\" height=\"40\" align=\"center\">��ϵ� ��ǰ���ǰ� �����ϴ�.</td></tr>\n";
	} else {
		$total_block = intval($pagecount / $qnasetup->page_num);

		if (($pagecount % $qnasetup->page_num) > 0) {
			$total_block = $total_block + 1;
		}

		$total_block = $total_block - 1;

		$a_first_block="";
		$a_last_block="";
		$a_prev_page="";
		$a_next_page="";
		$print_page="";
		$lastpage="";
		if (ceil($t_count/$qnasetup->list_num) > 0) {
			// ����	x�� ����ϴ� �κ�-����
			$a_first_block = "";
			if ($nowblock > 0) {
				$a_first_block .= "<a href=\"javascript:GoPage(\"prqna\",0,1);\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><FONT class=\"prlist\">[1...]</FONT></a>&nbsp;&nbsp;";
			}
			if ($nowblock > 0) {
				$a_prev_page .= "<a href=\"javascript:GoPage(\"prqna\",".($nowblock-1).",".($qnasetup->page_num*($qnablock-1)+$qnasetup->page_num).");\" onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$qnasetup->page_num." ������';return true\"><FONT class=\"prlist\">[prev]</FONT></a>&nbsp;&nbsp;";

				$a_prev_page = $a_first_block.$a_prev_page;
			}
			if (intval($total_block) <> intval($nowblock)) {
				for ($gopage = 1; $gopage <= $qnasetup->page_num; $gopage++) {
					if ((intval($nowblock*$qnasetup->page_num) + $gopage) == intval($qnagotopage)) {
						$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$qnasetup->page_num) + $gopage)."</font> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(\"prqna\",".$nowblock.",".(intval($nowblock*$qnasetup->page_num) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$qnasetup->page_num) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$qnasetup->page_num) + $gopage)."]</FONT></a> ";
					}
				}
			} else {
				if (($pagecount % $qnasetup->page_num) == 0) {
					$lastpage = $qnasetup->page_num;
				} else {
					$lastpage = $pagecount % $qnasetup->page_num;
				}

				for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
					if (intval($nowblock*$qnasetup->page_num) + $gopage == intval($qnagotopage)) {
						$print_page .= "<FONT class=\"choiceprlist\">".(intval($nowblock*$qnasetup->page_num) + $gopage)."</FONT> ";
					} else {
						$print_page .= "<a href='javascript:GoPage(\"prqna\",".$nowblock.",".(intval($nowblock*$qnasetup->page_num) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$qnasetup->page_num) + $gopage)."';return true\"><FONT class=\"prlist\">[".(intval($nowblock*$qnasetup->page_num) + $gopage)."]</FONT></a> ";
					}
				}
			}		// ������ �������� ǥ�úκ�-��

			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$last_block = ceil($t_count/($qnasetup->list_num*$qnasetup->page_num)) - 1;
				$last_gotopage = ceil($t_count/$qnasetup->list_num);

				$a_last_block .= "&nbsp;&nbsp;<a href='javascript:GoPage(\"prqna\",".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><FONT class=\"prlist\">[...".$last_gotopage."]</FONT></a>";
			}

			if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
				$a_next_page .= "&nbsp;&nbsp;<a href='javascript:GoPage(\"prqna\",".($nowblock+1).",".($qnasetup->page_num*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$qnasetup->page_num." ������';return true\"><FONT class=\"prlist\">[next]</FONT></a>";
				$a_next_page = $a_next_page.$a_last_block;
			}
		} else {
			$print_page = "<FONT class=\"prlist\">1</FONT>";
		}
	}
?>
	<tr>
		<td colspan="<?=$colspan?>" align="center" style="padding-top:10" style="font-size:11px;"><?=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page?></td>
	</tr>
	</table>
	</td>
</tr>
</table>