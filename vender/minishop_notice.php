<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$setup[page_num] = 10;
$setup[list_num] = 10;

$type=$_REQUEST["type"];
$artid=$_REQUEST["artid"];
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

if($type!="list" && $type!="view" && $type!="write" && $type!="delete") $type="list";

if($type=="view" || ($type=="write" && strlen($artid)>0) || $type=="delete") {
	$sql = "SELECT * FROM tblvendernotice WHERE vender='".$_VenderInfo->getVidx()."' AND date='".$artid."' ";
	$result=mysql_query($sql,get_db_conn());
	if(!$noticedata=mysql_fetch_object($result)) {
		echo "<html></head><body onload=\"alert('�ش� ���������� �������� �ʽ��ϴ�.')\"></body></html>";exit;
	}
	mysql_free_result($result);
	
	if($type=="view") {
		//������
		unset($prevdata);
		$sql = "SELECT date,subject FROM tblvendernotice WHERE vender='".$_VenderInfo->getVidx()."' ";
		$sql.= "AND date>'".$artid."' ORDER BY date ASC LIMIT 1 ";
		$result=mysql_query($sql,get_db_conn());
		$prevdata=mysql_fetch_object($result);
		mysql_free_result($result);

		//������
		unset($nextdata);
		$sql = "SELECT date,subject FROM tblvendernotice WHERE vender='".$_VenderInfo->getVidx()."' ";
		$sql.= "AND date<'".$artid."' ORDER BY date DESC LIMIT 1 ";
		$result=mysql_query($sql,get_db_conn());
		$nextdata=mysql_fetch_object($result);
		mysql_free_result($result);
	}
}

if($type=="write") {
	$mode=$_POST["mode"];
	$subject=$_POST["subject"];
	$content=$_POST["content"];
	if($mode=="insert") {
		if(strlen($subject)>0 && strlen($content)>0) {
			$sql = "INSERT tblvendernotice SET ";
			$sql.= "vender		= '".$_VenderInfo->getVidx()."', ";
			$sql.= "date		= '".date("YmdHis")."', ";
			$sql.= "ip			= '".getenv("REMOTE_ADDR")."', ";
			$sql.= "subject		= '".$subject."', ";
			$sql.= "content		= '".$content."' ";
			if(mysql_query($sql,get_db_conn())) {
				echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� �����Ͽ����ϴ�.');parent.location.href='".$_SERVER[PHP_SELF]."'\"></body></html>";exit;
			} else {
				echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.')\"></body></html>";exit;
			}
		}
	} else if($mode=="modify") {
		if(strlen($artid)>0 && strlen($subject)>0 && strlen($content)>0) {
			$sql = "UPDATE tblvendernotice SET ";
			$sql.= "subject		= '".$subject."', ";
			$sql.= "content		= '".$content."' ";
			$sql.= "WHERE vender = '".$_VenderInfo->getVidx()."' ";
			$sql.= "AND date = '".$artid."' ";
			
			if(mysql_query($sql,get_db_conn())) {
				echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� �����Ͽ����ϴ�.');parent.location.href='".$_SERVER[PHP_SELF]."?type=view&artid=".$artid."&block=".$block."&gotopage=".$gotopage."'\"></body></html>";exit;
			} else {
				echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.')\"></body></html>";exit;
			}
		}
	}
} else if($type=="delete") {
	if(strlen($artid)>0) {
		$sql = "DELETE FROM tblvendernotice ";
		$sql.= "WHERE vender = '".$_VenderInfo->getVidx()."' ";
		$sql.= "AND date = '".$artid."' ";
		
		if(mysql_query($sql,get_db_conn())) {
			echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� �����Ͽ����ϴ�.');parent.location.href='".$_SERVER[PHP_SELF]."?block=".$block."&gotopage=".$gotopage."'\"></body></html>";exit;
		} else {
			echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.')\"></body></html>";exit;
		}
	}
}
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function GoPage(block,gotopage) {
	document.location.href="<?=$_SERVER[PHP_SELF]?>?block="+block+"&gotopage="+gotopage;
}
function GoNoticeList(block,gotopage) {
	url="<?=$_SERVER[PHP_SELF]?>?block="+block+"&gotopage="+gotopage;
	document.location.href=url;
}
function GoNoticeView(artid,block,gotopage) {
	url="<?=$_SERVER[PHP_SELF]?>?type=view&artid="+artid;
	if(typeof block!="undefined") url+="&block="+block;
	if(typeof gotopage!="undefined") url+="&gotopage="+gotopage;
	document.location.href=url;
}
function GoWrite() {
	document.location.href="<?=$_SERVER[PHP_SELF]?>?type=write";
}
function GoModify(artid,block,gotopage) {
	url="<?=$_SERVER[PHP_SELF]?>?type=write&artid="+artid;
	if(typeof block!="undefined") url+="&block="+block;
	if(typeof gotopage!="undefined") url+="&gotopage="+gotopage;
	document.location.href=url;
}
function GoDelete(artid,block,gotopage) {
	if(confirm("�ش� ���� ���� �����ϰڽ��ϱ�?")) {
		url="<?=$_SERVER[PHP_SELF]?>?type=delete&artid="+artid;
		if(typeof block!="undefined") url+="&block="+block;
		if(typeof gotopage!="undefined") url+="&gotopage="+gotopage;
		document.location.href=url;
	}
}
function formSubmit() {
	if(document.form1.subject.value.length==0) {
		alert("�������� ������ �Է��ϼ���.");
		document.form1.subject.focus();
		return;
	}
	if(document.form1.content.value.length==0) {
		alert("�������� ������ �Է��ϼ���.");
		document.form1.content.focus();
		return;
	}
	if(confirm("�̴ϼ� ���������� �����Ͻðڽ��ϱ�?")) {
		document.form1.mode.value="<?=(strlen($artid)>0?"modify":"insert")?>";
		document.form1.target="processFrame";
		document.form1.submit();
	}
}

</script>
<table border=0 cellpadding=0 cellspacing=0 width=100% height=100% style="table-layout:fixed">
<col width=190></col>
<col width=20></col>
<col width=></col>
<col width=20></col>
<tr>
	<td width=190 valign=top nowrap background="images/minishop_leftbg.gif"><? include ("menu.php"); ?></td>
	<td width=20 nowrap></td>
	<td valign=top style="padding-top:20px">

	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>
		<table width="100%"  border="0" cellpadding="0" cellspacing="0" >
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td><img src="images/minishop_notice_title.gif"></td>
				</tr>
				<tr>
					<td height=5 background="images/minishop_titlebg.gif">
				</tr>
				</table>
			</td>
		</tr>
		<tr><td height=10></td></tr>
		<tr>
			<td>
				<table border=0 cellpadding=0 cellspacing=0 width=100% >
				<tr>
					<td colspan=3 >


						<table cellpadding="10" cellspacing="1" width="100%" bgcolor="#EFEFF2">
							<tr>
								<td  bgcolor="#F5F5F9" style="padding:20px">
									<table border=0 cellpadding=0 cellspacing=0 width=100%>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�̴ϼ� ������������ ��ϵ� ������ �̴ϼ� �������� ��µ˴ϴ�.</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>



					</td>
				</tr>
				</table>
				</td>
			</tr>

			<!-- ó���� ���� ��ġ ���� -->
			<tr><td height=40></td></tr>
			<tr>
				<td>
				




				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				</table>

				<?if($type=="list"){?>

				<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
				<col width=10%></col>
				<col width=></col>
				<col width=14%></col>
				<tr height=28 align=center bgcolor=F5F5F5>
					<td><B>��ȣ</B></td>
					<td><B>����</B></td>
					<td><B>�Խ���</B></td>
				</tr>
<?
				$sql = "SELECT COUNT(*) as t_count FROM tblvendernotice ";
				$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				$t_count = $row->t_count;
				mysql_free_result($result);
				$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

				$sql = "SELECT date,subject,access FROM tblvendernotice ";
				$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
				$sql.= "ORDER BY date DESC ";
				$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
				$result=mysql_query($sql,get_db_conn());
				$i=0;
				while($row=mysql_fetch_object($result)) {
					$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
					$date=substr($row->date,0,4)."/".substr($row->date,4,2)."/".substr($row->date,6,2);
					echo "<tr height=28 bgcolor=#FFFFFF>\n";
					echo "	<td align=center>".$number."</td>\n";
					echo "	<td style=\"padding:7,10\"><A HREF=\"javascript:GoNoticeView('".$row->date."','".$block."','".$gotopage."')\">".strip_tags($row->subject)."</A></td>\n";
					echo "	<td align=center>".$date."</td>\n";
					echo "</tr>\n";
					$i++;
				}
				mysql_free_result($result);
				if($i==0) {
					echo "<tr height=28 bgcolor=#FFFFFF><td colspan=3 align=center>��ϵ� ���������� �����ϴ�.</td></tr>\n";
				} else if($i>0) {
					$total_block = intval($pagecount / $setup[page_num]);
					if (($pagecount % $setup[page_num]) > 0) {
						$total_block = $total_block + 1;
					}
					$total_block = $total_block - 1;
					if (ceil($t_count/$setup[list_num]) > 0) {
						// ����	x�� ����ϴ� �κ�-����
						$a_first_block = "";
						if ($nowblock > 0) {
							$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev_end.gif border=0 align=absmiddle></a> ";
							$prev_page_exists = true;
						}
						$a_prev_page = "";
						if ($nowblock > 0) {
							$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev.gif border=0 align=absmiddle></a> ";

							$a_prev_page = $a_first_block.$a_prev_page;
						}
						if (intval($total_block) <> intval($nowblock)) {
							$print_page = "";
							for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
								if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
									$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></font> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
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
									$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></FONT> ";
								} else {
									$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
								}
							}
						}
						$a_last_block = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
							$last_gotopage = ceil($t_count/$setup[list_num]);
							$a_last_block .= " <a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><img src=".$Dir."images/minishop/btn_mininext_end.gif border=0 align=absmiddle></a>";
							$next_page_exists = true;
						}
						$a_next_page = "";
						if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
							$a_next_page .= " <a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_mininext.gif border=0 align=absmiddle></a>";
							$a_next_page = $a_next_page.$a_last_block;
						}
					} else {
						$print_page = "<B>1</B>";
					}
					$pageing=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
				}
?>
				</table>
				
				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td width="100%" align=center style="padding-top:10"><?=$pageing?></td>
					<td style="padding-top:3"><A HREF="javascript:GoWrite()"><img src="images/btn_noticewrite.gif" border=0></A></td>
				</tr>
				</table>

				<?}else if($type=="view"){?>

				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<tr>
					<td valign=top style=background-repeat:repeat-x bgcolor="e7e7e7">
					<table width=100% border=0 cellspacing=0 cellpadding=0>
					<tr>
						<td bgcolor=F5F5F5>
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td style="background-repeat:repeat-y;background-position:right;padding:9;word-break:break-all;" width="88%">
							<B>�� �� : <?=$noticedata->subject?></B>
							</td>
							<td align="left"><?=substr($noticedata->date,0,4)."/".substr($noticedata->date,4,2)."/".substr($noticedata->date,6,2)?></td>
						</tr>
						</table>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				<tr><td height=1 bgcolor=#E7E7E7></td></tr>
				<tr>
					<td valign="top" height="250" bgcolor=ffffff style="background-repeat:repeat-y;background-position:right;padding:9;word-break:break-all;">
					<?=nl2br($noticedata->content)?>
					</td>
				</tr>
				<tr><td height=1 bgcolor=#E7E7E7></td></tr>
				<tr><td height=5></td></tr>
				<tr>
					<td align=right>
					<A HREF="javascript:GoModify('<?=$noticedata->date?>','<?=$block?>','<?=$gotopage?>');"><img src="images/btn_noticeedit.gif" border=0></A>
					<A HREF="javascript:GoDelete('<?=$noticedata->date?>','<?=$block?>','<?=$gotopage?>');"><img src="images/btn_noticedelete.gif" border=0></A>
					<A HREF="javascript:GoWrite()"><img src="images/btn_noticewrite.gif" border=0></A>
					</td>
				</tr>
				<tr><td height=5></td></tr>
				<tr>
					<td align=center>
					<?if(is_object($prevdata)){?>
					<A HREF="javascript:GoNoticeView('<?=$prevdata->date?>','<?=$block?>','<?=$gotopage?>')"><img src="images/btn_prev01.gif" border=0></A>&nbsp;
					<?}?>
					<A HREF="javascript:GoNoticeList('<?=$block?>','<?=$gotopage?>')"><img src="images/btn_list.gif" border=0></A>
					<?if(is_object($nextdata)){?>
					&nbsp;<A HREF="javascript:GoNoticeView('<?=$nextdata->date?>','<?=$block?>','<?=$gotopage?>')"><img src="images/btn_next01.gif" border=0></A>
					<?}?>
					</td>
				</tr>
				
				<?if(is_object($prevdata) || is_object($nextdata)){?>

				<tr><td height=25></td></tr>
				<tr>
					<td>
					<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
					<tr><td height=1 bgcolor=#cccccc></td></tr>
					</table>

					<table border=0 cellpadding=0 cellspacing=1 width=100% bgcolor=E7E7E7 style="table-layout:fixed">
					<col width=10%></col>
					<col width=></col>
					<col width=14%></col>
					<tr height=28 align=center bgcolor=F5F5F5>
						<td><B>��ȣ</B></td>
						<td><B>����</B></td>
						<td><B>�Խ���</B></td>
					</tr>
					<?if(is_object($prevdata)){?>
					<tr height=28 bgcolor=#FFFFFF>
						<td align=center>������</td>
						<td style="padding:7,10;word-break:break-all;"><A HREF="javascript:GoNoticeView('<?=$prevdata->date?>','<?=$block?>','<?=$gotopage?>')"><?=strip_tags($prevdata->subject)?></A></td>
						<td align=center><?=substr($prevdata->date,0,4)."/".substr($prevdata->date,4,2)."/".substr($prevdata->date,6,2)?></td>
					</tr>
					<?}?>
					<?if(is_object($nextdata)){?>
					<tr height=28 bgcolor=#FFFFFF>
						<td align=center>������</td>
						<td style="padding:7,10;word-break:break-all;"><A HREF="javascript:GoNoticeView('<?=$nextdata->date?>','<?=$block?>','<?=$gotopage?>')"><?=strip_tags($nextdata->subject)?></A></td>
						<td align=center><?=substr($nextdata->date,0,4)."/".substr($nextdata->date,4,2)."/".substr($nextdata->date,6,2)?></td>
					</tr>
					<?}?>
					</table>
					</td>
				</tr>

				<?}?>

				</table>

				<?}else if($type=="write"){?>

				<table width=100% border=0 cellspacing=0 cellpadding=0>
				<col width="80"></col>
				<col width=""></col>
				<form name=form1 method=post>
				<input type=hidden name=type value="<?=$type?>">
				<input type=hidden name=mode>
				<?=(strlen($noticedata->date)>0?"<input type=hidden name=artid value=\"".$noticedata->date."\">\n":"")?>
				<?=(strlen($block)>0?"<input type=hidden name=block value=\"".$block."\">\n":"")?>
				<?=(strlen($gotopage)>0?"<input type=hidden name=gotopage value=\"".$gotopage."\">\n":"")?>
				<tr> 
					<td align="center" bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�� ��</B></td>
					<td style=padding:7,10 bgcolor="#FFFFFF">
					<input class=input type=text name="subject" value="<?=$noticedata->subject?>" size="60" maxlength=40 required>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr> 
					<td align="center" bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�� ��</B></td>
					<td style=padding:7,10 bgcolor="#FFFFFF">
					<textarea  class=textarea name="content" rows=10 cols="" style="width:100%;word-break:break-all;" maxbyte=10000 required><?=$noticedata->content?></textarea>
					</td>
				</tr>
				<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
				<tr><td colspan=2 height=25></td></tr>
				<tr>
					<td colspan=2 align=center>
					<A HREF="javascript:formSubmit()"><img src="images/btn_regist05.gif" border=0></A>
					&nbsp;&nbsp;
					<A HREF="javascript:history.go(-1);"><img src="images/btn_cancel05.gif" border=0></A>
					</td>
				</tr>

				</form>

				</table>
				<iframe name="processFrame" src="about:blank;" width="0" height="0" scrolling=no frameborder=no></iframe>
				<?}?>

				</td>
			</tr>
			<!-- ó���� ���� ��ġ �� -->

			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>

	</td>
</tr>
</table>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>