<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "co-1";
$MenuCode = "community";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$mode=$_POST["mode"];
$btype=$_POST["btype"];
if(strlen($btype)>0 && $btype!="L" && $btype!="W" && $btype!="I" && $btype!="B") {
	$onload="<script>alert(\"�Խ��� ���� ������ �߸��Ǿ����ϴ�.\");</script>";
	$btype="";
}

$prqnaboardname="";
if($btype=="L") {
	$etcfield=$_shopdata->etcfield;
	$prqnaboard=getEtcfield($etcfield,"PRQNA");
	if(strlen($prqnaboard)>0) {
		$sql = "SELECT board_name FROM tblboardadmin ";
		$sql.= "WHERE board='".$prqnaboard."' ";
		$result=mysql_query($sql,get_db_conn());
		if($row=mysql_fetch_object($result)) {
			$prqnaboardname=str_replace("<script>","",str_replace("</script>","",$row->board_name));
		} else {
			$etcfield=setEtcfield($etcfield,"PRQNA","");
			$prqnaboard="";
		}
		mysql_free_result($result);
	}
	$_shopdata->etcfield=$etcfield;
}

$board=$_POST["board"];
$board_name=$_POST["board_name"];
$board_skin=$_POST["board_skin"];
$board_width=$_POST["board_width"];
$list_num=$_POST["list_num"];
$page_num=$_POST["page_num"];
$max_filesize=$_POST["max_filesize"];
$use_imgresize="N";
$img_maxwidth=$_POST["img_maxwidth"];
$img_align=$_POST["img_align"];
$passwd=$_POST["passwd"];
$grant_write=$_POST["grant_write"];
$grant_view=$_POST["grant_view"];
$group_code=$_POST["group_code"];
$use_reply=$_POST["use_reply"];
$grant_reply=$_POST["grant_reply"];
$use_comment=$_POST["use_comment"];
$grant_comment=$_POST["grant_comment"];
$use_lock=$_POST["use_lock"];
$writer_gbn=(int)$_POST["writer_gbn"];
$prqna_gbn=$_POST["prqna_gbn"];

if($mode=="insert" && strlen($btype)>0) {
	$date=date("YmdHis");
	if(strlen($board)==0) {
		echo "<html></head><body onload=\"alert('�Խ��� �ڵ带 �Է��ϼ���.'); history.go(-1);\"></body></html>";exit;
	}
	if(strlen($board_name)==0) {
		echo "<html></head><body onload=\"alert('�Խ��� ������ �Է��ϼ���.'); history.go(-1);\"></body></html>";exit;
	}
	if(strlen($board_width)==0) $board_width=690;
	if(strlen($list_num)==0) $list_num=20;
	if(strlen($page_num)==0) $page_num=10;
	if(strlen($max_filesize)==0) $max_filesize=2;
	if(strlen($img_maxwidth)==0) $img_maxwidth=650;
	if(strlen($use_lock)==0) $use_lock="N";
	if(strlen($use_reply)==0) $use_reply="Y";
	if(strlen($use_comment)==0) $use_comment="Y";
	if(strlen($use_imgresize)==0) $use_imgresize="Y";
	if(strlen($img_align)==0) $img_align="center";
	if(strlen($use_lock)==0) $use_lock="N";
	if(strlen($use_reply)==0) $use_reply="Y";
	if(strlen($use_comment)==0) $use_comment="Y";
	if(strlen($use_imgresize)==0) $use_imgresize="Y";
	if(strlen($grant_reply)==0) $grant_reply="N";

	//�Խ��� �ڵ� �ߺ� üũ �� �ڵ� ���� üũ

	$sql = "SELECT thread_no FROM tblboardadmin WHERE board='qna' ";
	$result = mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	$thread_no=$row->thread_no;

	$sql = "INSERT tblboardadmin SET ";
	$sql.= "board			= '".$board."', ";
	$sql.= "board_name		= '".$board_name."', ";
	$sql.= "passwd			= '".$passwd."', ";
	$sql.= "thread_no		= '".$thread_no."', ";
	$sql.= "board_skin		= '".$board_skin."', ";
	$sql.= "board_width		= '".$board_width."', ";
	$sql.= "list_num		= '".$list_num."', ";
	$sql.= "page_num		= '".$page_num."', ";
	$sql.= "writer_gbn		= '".$writer_gbn."', ";
	$sql.= "max_filesize	= '".$max_filesize."', ";
	$sql.= "img_maxwidth	= '".$img_maxwidth."', ";
	$sql.= "img_align		= '".$img_align."', ";
	$sql.= "date			= '".$date."', ";
	$sql.= "use_lock		= '".$use_lock."', ";
	$sql.= "use_reply		= '".$use_reply."', ";
	$sql.= "use_comment		= '".$use_comment."', ";
	$sql.= "use_imgresize	= '".$use_imgresize."', ";
	$sql.= "group_code		= '".$group_code."', ";
	$sql.= "grant_write		= '".$grant_write."', ";
	$sql.= "grant_view		= '".$grant_view."', ";
	$sql.= "grant_reply		= '".$grant_reply."', ";
	$sql.= "grant_comment	= '".$grant_reply."' ";
	$insert=mysql_query($sql,get_db_conn());
	if (mysql_errno()!=0) $onload="<script>alert(\"�Խ��� �ڵ尡 �ߺ��Ǿ� ������ �߻��Ͽ����ϴ�.\");</script>";
	else {
		if($btype=="L") {
			if($prqna_gbn=="Y") {
				$etcfield=setEtcfield($etcfield,"PRQNA",$board);
				$_shopdata->etcfield=$etcfield;
			}
		}
		$onload="<script>alert(\"�Խ��� ������ �Ϸ�Ǿ����ϴ�.\");</script>";
		include($Dir.BoardDir."file.inc.php");
		ProcessBoardDir($board,"create");
	}
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
var skin_cnt = 0;

<?if($btype=="L"){?>
function check_qnaboard(gbn) {
<?if(strlen($prqnaboard)>0){?>
	if(confirm("���� \"<?=$prqnaboardname?>\" �Խ����� ��ǰQNA�� ������Դϴ�.\n\n���� �Խ����� ��ǰQNA�� �����Ͻðڽ��ϱ�?")) {
		document.form1.prqna_gbn[0].checked=true;
	} else {
		document.form1.prqna_gbn[1].checked=true;
	}
<?}?>
}
<?}?>

function CheckForm(form) {
	if(skin_cnt>1) {
		try {
			selskin=false;
			for(i=0;i<form.board_skin.length;i++) {
				if(form.board_skin[i].checked==true) {
					selskin=true;
					break;
				}
			}
			if(selskin==false) {
				alert("�Խ��� �������� �����ϼ���.");
				return;
			}
		} catch (e) {}
	}
	if(form.board.value.length==0) {
		alert("�Խ��� �ڵ带 �Է��ϼ���.");
		form.board.focus();
		return;
	}
	if(form.board_name.value.length==0) {
		alert("�Խ��� ������ �Է��ϼ���.");
		form.board_name.focus();
		return;
	}
	if(form.board_width.value.length==0) {
		alert("�Խ��� ���̸� �Է��ϼ���.");
		form.board_width.focus();
		return;
	}
	if(!IsNumeric(form.board_width.value)) {
		alert("�Խ��� ���̴� ���ڸ� �Է� �����մϴ�.");
		form.board_width.focus();
		return;
	}
	if(form.list_num.value.length==0) {
		alert("�Խñ� ��ϼ��� �Է��ϼ���.");
		form.list_num.focus();
		return;
	}
	if(!IsNumeric(form.list_num.value)) {
		alert("�Խ��� ��ϼ��� ���ڸ� �Է� �����մϴ�.");
		form.list_num.focus();
		return;
	}
	if(form.page_num.value==0) {
		alert("������ ��ϼ��� �Է��ϼ���.");
		form.page_num.focus();
		return;
	}
	if(!IsNumeric(form.page_num.value)) {
		alert("������ ��ϼ��� ���ڸ� �Է� �����մϴ�.");
		form.page_num.focus();
		return;
	}
	if(form.passwd.value.length==0) {
		alert("�Խ��� ���� ��й�ȣ�� �Է��ϼ���.");
		form.passwd.focus();
		return;
	}
	form.mode.value="insert";
	form.submit();
}

function ChoiceType(type) {
	document.form1.btype.value=type;
	document.form1.submit();
}

function ChangeDesign(tmp) {
	tmp=tmp + skin_cnt;
	document.form1["board_skin"][tmp].checked=true;
}

function big_view(bigimg) {
	window.open("community_register_bigpop.php?bigimg="+bigimg,"bigpop","left=50, height=100,width=100");
}
</script>

<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>

	<td valign="top">
	<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
	<tr>
		<td   background="images/con_bg.gif">
		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed" >
		<col width=190></col>
		<col width=10></col>
		<col width=></col>
		<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
		<input type=hidden name=mode>
		<input type=hidden name=btype value="<?=$btype?>">
		<tr>
			<td valign="top" background="images/leftmenu_bg.gif" >
			<? include ("menu_community.php"); ?>
			</td>

			<td></td>

			<td valign="top">




<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="29" colspan="3">
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : Ŀ�´�Ƽ &gt; Ŀ�´�Ƽ ����  &gt; <span class="2depth_select">�Խ��� �������� ����</span></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
        <td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_01_bg.gif"></td>
        <td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr>
        <td width="16" background="images/con_t_04_bg1.gif"></td>
        <td bgcolor="#ffffff" style="padding:10px">








			<?if(strlen($btype)==0){?>

			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/community_register_title.gif" ALT=""></TD>
					</tr><tr>
					<TD width="100%" background="images/title_bg.gif" height=21></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">Ŀ�´�Ƽ ���ݿ� �´� �پ��� �Խ����� ������ �� �ֽ��ϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/community_list_stitle3.gif" border="0"></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT="">&nbsp;</TD>
					</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=140></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><A href="javascript:big_view('community_btypeimg_L1.gif');"><IMG src="images/community_btypeimg_L.gif" border=0></A></TD>
					<TD class="td_con1">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=></col>
					<col width=117></col>
					<tr>
						<td><b>�Ϲ��� �Խ���</b><br>���� ���� ���Ǵ� �Ϲ����� ����Ʈ ������ �Խ����Դϴ�.<br>(��ũ�� �Խ��� ����)<br></td>
						<td align=right rowspan="2"><a href="javascript:ChoiceType('L');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><A href="javascript:big_view('community_btypeimg_W1.gif');"><IMG src="images/community_btypeimg_W.gif" border=0></A></TD>
					<TD class="td_con1">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=></col>
					<col width=117></col>
					<tr>
						<td><b>������ �Խ���</b><br>������ ������ �Խ������μ� ������ �Խ��� ���ݿ� ��︮�� �Խ����Դϴ�.<br>(��ũ�� �Խ��� ����)<br></td>
						<td align=right rowspan="2"><a href="javascript:ChoiceType('W');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><A href="javascript:big_view('community_btypeimg_I1.gif');"><IMG src="images/community_btypeimg_I.gif" border=0></A></TD>
					<TD class="td_con1">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=></col>
					<col width=117></col>
					<tr>
						<td><b>�ٹ��� �Խ���</b><br>�ٹ��� ���� �̹����� �ٹ����·� �����Ǵ� ������ �Խ����Դϴ�.<br>(��ũ�� �Խ��� ����)<br></td>
						<td align=right rowspan="2"><a href="javascript:ChoiceType('I');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><A href="javascript:big_view('community_btypeimg_B1.gif');"><IMG src="images/community_btypeimg_B.gif" border=0></A></TD>
					<TD class="td_con1">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=></col>
					<col width=117></col>
					<tr>
						<td><b>��α��� �Խ���</b><br>���� ���� ���ǰ� �ִ� ��α� ������ �Խ����Դϴ�.</td>
						<td align=right rowspan="2"><a href="javascript:ChoiceType('B');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a></td>
					</tr>
					</table>
					</TD>
				</TR>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=30></td></tr>

			<?}else{?>
<?
			$sql = "SELECT * FROM tblboardskin WHERE board_skin LIKE '".$btype."%' ORDER BY board_skin ASC";
			$result=mysql_query($sql,get_db_conn());
			$rows=mysql_num_rows($result);
			if(!$rows) {
				echo "<script>alert(\"�ش� �Խ��� ������ ��Ų ����� �ȵǾ� �Խ��� �߰��� �Ұ��մϴ�.\");location='".$_SERVER[PHP_SELF]."';</script>";
				exit;
			}
?>
			<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
			<tr><td height="8"></td></tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/community_register_title.gif" ALT=""></TD>
					</tr>
				<tr>
					<TD width="100%" background="images/title_bg.gif" height="21"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="3"></td></tr>
			<tr>
				<td style="padding-bottom:3pt;">
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif"></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
					<TD width="100%" class="notice_blue">Ŀ�´�Ƽ ���ݿ� �´� �پ��� �Խ����� �����Ͻ� �� �ֽ��ϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif"></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="20"></td></tr>
<?
			if($rows==1) {
				$row=mysql_fetch_object($result);
				mysql_free_result($result);
				echo "<input type=hidden name=\"board_skin\" value=\"".$row->board_skin."\">\n";
			} else if($rows>1) {
				echo "<script>skin_cnt=".$rows.";</script>\n";
?>
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="100%" bgcolor="#ededed" style="padding:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
					<tr>
						<td width="100%">
						<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
						<TR>
							<TD align=center height="30" background="images/blueline_bg.gif"><b><font color="#333333">��ϵ� �Խ��� ��Ų �����ϱ�</font></b></TD>
						</TR>
						<TR>
							<TD width="100%" background="images/table_con_line.gif"></TD>
						</TR>
						<TR>
							<TD width="100%" style="padding:10pt;">
							<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td>
								<TABLE cellSpacing=0 cellPadding="4" width="100%" border=0 align="center">
								<col width=31></col>
								<col width=></col>
								<col width=31></col>
								<TR>
									<TD height="160" align=right valign="middle"><img src="images/btn_back.gif" width="31" height="31" border="0" onMouseover='moveright()' onMouseout='clearTimeout(righttime)' style="cursor:hand;"></TD>
									<TD height="160" valign="top" align="center">
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
									<tr>
										<td id="temp" style="visibility:hidden;position:absolute;top:0;left:0">
<?
										echo "<script>";
										$jj=0;
										$menucontents = "";
										$menucontents .= "<table border=0 cellpadding=0 cellspacing=0><tr>";
										$i=0;
										while($row=mysql_fetch_object($result)) {
											echo "thisSel = 'dotted #FFFFFF';";
											$menucontents .= "<td width=160 align=center><img src='images/sample/board_".$row->board_skin.".gif' border=0 width=150 height=140 style='border-width:1pt; border-color:#FFFFFF; border-style:solid;' hspace='5' onMouseOver='changeMouseOver(this);' onMouseOut='changeMouseOut(this,thisSel);' style='cursor:hand;' onclick='ChangeDesign(".$i.");'>";
											$menucontents .= "<br><input type=radio name='board_skin' value='".$row->board_skin."'";
											if($data->board_skin==$row->board_skin OR strlen($data->board_skin) == 0 ) $menucontents .= " checked";
											$menucontents .= "></td>";
											$jj++;
											$i++;
										}
										mysql_free_result($result);
										$menucontents .= "</tr></table>";
										echo "</script>";
?>

										<script language="JavaScript1.2">
										<!--
										function changeMouseOver(img) {
											 img.style.border='1 dotted #999999';
										}
										function changeMouseOut(img,dot) {
											 img.style.border="1 "+dot;
										}

										var menuwidth=650
										var menuheight=220
										var scrollspeed=10
										var menucontents="<nobr><?=$menucontents?></nobr>";

										var iedom=document.all||document.getElementById
										if (iedom)
											document.write(menucontents)
										var actualwidth=''
										var cross_scroll, ns_scroll
										var loadedyes=0
										function fillup(){
											if (iedom){
												cross_scroll=document.getElementById? document.getElementById("test2") : document.all.test2
												cross_scroll.innerHTML=menucontents
												actualwidth=document.all? cross_scroll.offsetWidth : document.getElementById("temp").offsetWidth
											}
											else if (document.layers){
												ns_scroll=document.ns_scrollmenu.document.ns_scrollmenu2
												ns_scroll.document.write(menucontents)
												ns_scroll.document.close()
												actualwidth=ns_scroll.document.width
											}
											loadedyes=1
										}
										window.onload=fillup

										function moveleft(){
											if (loadedyes){
												if (iedom&&parseInt(cross_scroll.style.left)>(menuwidth-actualwidth)){
													cross_scroll.style.left=parseInt(cross_scroll.style.left)-scrollspeed
												}
												else if (document.layers&&ns_scroll.left>(menuwidth-actualwidth))
													ns_scroll.left-=scrollspeed
											}
											lefttime=setTimeout("moveleft()",50)
										}

										function moveright(){
											if (loadedyes){
												if (iedom&&parseInt(cross_scroll.style.left)<0)
													cross_scroll.style.left=parseInt(cross_scroll.style.left)+scrollspeed
												else if (document.layers&&ns_scroll.left<0)
													ns_scroll.left+=scrollspeed
											}
											righttime=setTimeout("moveright()",50)
										}

										if (iedom||document.layers){
											with (document){
												write('<td valign=top>')
												if (iedom){
													write('<div style="position:relative;width:'+menuwidth+';">');
													write('<div style="position:absolute;width:'+menuwidth+';height:'+menuheight+';overflow:hidden;">');
													write('<div id="test2" style="position:absolute;left:0">');
													write('</div></div></div>');
												}
												else if (document.layers){
													write('<ilayer width='+menuwidth+' height='+menuheight+' name="ns_scrollmenu">')
													write('<layer name="ns_scrollmenu2" left=0 top=0></layer></ilayer>')
												}
												write('</td>')
											}
										}
										//-->
										</script>
										</td>
									</tr>
									</table>
									</TD>
									<TD height="160"><img src="images/btn_next.gif" width="31" height="31" border="0" onMouseover='moveleft()' onMouseout='clearTimeout(lefttime)' style="cursor:hand;"></TD>
								</TR>
								</TABLE>
								</td>
							</tr>
							</table>
							</TD>
						</TR>
						</TABLE>
						</td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
<?
			}
?>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/community_register_stitle2.gif" ALT=""></TD>
					<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;</TD>
					<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/distribute_01.gif" WIDTH=7 HEIGHT=7 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_02.gif"></TD>
					<TD><IMG SRC="images/distribute_03.gif" WIDTH=8 HEIGHT=7 ALT=""></TD>
				</TR>
				<TR>
					<TD background="images/distribute_04.gif"></TD>
					<TD class="notice_blue"><IMG SRC="images/distribute_img.gif"></TD>
					<TD width="100%" class="notice_blue">�Խ����� �⺻����� ���� �� �� �ֽ��ϴ�.</TD>
					<TD background="images/distribute_07.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/distribute_08.gif" WIDTH=7 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=2 background="images/distribute_09.gif"></TD>
					<TD><IMG SRC="images/distribute_10.gif" WIDTH=8 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=3></td></tr>
			<tr>
				<td>
				<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
				<col width=140></col>
				<col width=></col>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� �ڵ�</TD>
					<TD class="td_con1"><INPUT maxLength=20 size=20 name=board class="input_selected"> <span class="font_orange">* �Խ��� ���� �ڵ带 �Է��ϼ���. (��:qna2)</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� ����</TD>
					<TD class="td_con1"><INPUT maxLength=200 size=60 name=board_name class="input_selected"> <span class="font_orange">* HTML�� �ۼ� ����</span>
					</TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� ����</TD>
					<TD class="td_con1"><INPUT onkeyup="return strnumkeyup(this);" style="TEXT-ALIGN: right" maxLength="10" size="10" value=690 name=board_width class="input"> <span class="font_blue">(���� : 690�ȼ�, �Խ��� ����ũ�� 100�����̸� %�� �����˴ϴ�.)</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խñ� ��ϼ�</TD>
					<TD class="td_con1"><INPUT onkeyup="return strnumkeyup(this);" style="TEXT-ALIGN: right" maxLength="10" size="10" value=20 name=list_num class="input"> <span class="font_blue">(���� : 20)</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<TR>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">������ ��ϼ�</TD>
					<TD class="td_con1"><INPUT onkeyup="return strnumkeyup(this);" style="TEXT-ALIGN: right" maxLength="10" size="10" value=10 name=page_num class="input"> <span class="font_blue">(���� : 10)</span></TD>
				</TR>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� ÷������</TD>
					<TD class="td_con1">

					<SELECT name=max_filesize class="select" onchange="if(this.value>10) alert('ȣ���û翡�� ������ ���ε� ���� �뷮�� Ȯ�� �� �����Ͻñ� �ٶ��ϴ�.');">
					<OPTION value=1>100KB</OPTION>
					<OPTION value=2 selected>200KB</OPTION>
					<OPTION value=3>300KB</OPTION>
					<OPTION value=4>400KB</OPTION>
					<OPTION value=5>500KB</OPTION>
					<OPTION value=6>600KB</OPTION>
					<OPTION value=7>700KB</OPTION>
					<OPTION value=8>800KB</OPTION>
					<OPTION value=9>900KB</OPTION>
					<OPTION value=10>&nbsp;&nbsp;&nbsp;1MB</OPTION>
					<OPTION value=20>&nbsp;&nbsp;&nbsp;2MB</OPTION>
					</SELECT>
					 (���� : 200KB)
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� �̹��� ����</TD>
					<TD class="td_con1">�̹��� �ִ� ������ : <INPUT onkeyup="return strnumkeyup(this);" size="10" value=650 name=img_maxwidth class="input" style="text-align:right;">�ȼ� / �̹��� ���� : <INPUT id=idx_img_align0 type=radio CHECKED value=center name=img_align><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_img_align0>��� ����</LABEL> <INPUT id=idx_img_align1 type=radio value=left name=img_align><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_img_align1>���� ����</LABEL><br><span class="font_blue"> * �̹��� �ִ� ������� �Խ����� ���̺��� ���� ������� �����Ͻñ� �ٶ��ϴ�.</span></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� ��й�ȣ</TD>
					<TD class="td_con1"><INPUT name=passwd class="input"> <span class="font_blue"> * ��й�ȣ�� ������� �ʵ��� �����Ͻñ� �ٶ��ϴ�.</span></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� ���ٱ���</TD>
					<TD class="td_con1">
					<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td>
						 �Խù� ���� :
						<SELECT name=grant_write class="select"  style="width:130px">
						<OPTION value=N selected>ȸ��/��ȸ��</OPTION>
						<OPTION value=Y>ȸ������</OPTION>
						<OPTION value=A>����������</OPTION>
						</SELECT>

						</td>
					</tr>
					<tr>
						<td>
						 �Խù� ���� :
						<SELECT  style="width:130px" name=grant_view class="select">
						<OPTION value=N selected>ȸ��/��ȸ��</OPTION>
						<OPTION value=U>��ȸ�������ȸ</OPTION>
						<OPTION value=Y>��ȸ����ȸ�Ұ�</OPTION>
						</SELECT>
						<br>
						</td>
					</tr>
					<tr>
						<td>
						 Ư����޸� �а� ���� :
						<SELECT style="WIDTH: 307px" name=group_code class="select">
						<option value="">�ش� ����� �����ϼ���.</option>
<?
						$sql = "SELECT group_code,group_name FROM tblmembergroup ";
						$result=mysql_query($sql,get_db_conn());
						while($row=mysql_fetch_object($result)) {
							echo "<option value=\"".$row->group_code."\">".$row->group_name."</option>";
						}
						mysql_free_result($result);
?>
						</SELECT>
						<br>
						</td>
					</tr>
					<tr>
						<td style="padding-top:8pt;" class="font_blue">
						 * [�Խù� ����]�� <B>��ȸ�������ȸ</B> ���ý� ��ȸ���� �����ȸ�� �����ϳ� ���뺸�Ⱑ<span style="letter-spacing:-0.5pt;"> �ȵ˴ϴ�.(�α��� �İ���)</span>
						<br>
						 * [Ư����޸� �а� ����]���� ȸ������� ���õ� ��� �ش� ȸ������� ȸ���� ���� �� ���Ⱑ �����մϴ�.<br>&nbsp;&nbsp;(�ٸ� [�Խù� ����]�� ������ �������������� ���ý� �ش� ȸ������� ��ȸ �� ���뺸�⸸ �����մϴ�.)
						</td>
					</tr>
					</table>
					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<?if($btype=="L"){?>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� �亯���</TD>
					<TD class="td_con1">
					<INPUT id=idx_use_reply0 onclick=this.form.grant_reply.disabled=false; type=radio CHECKED value=Y name=use_reply><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_reply0>�����</LABEL>(�亯 ���� :
					<SELECT name=grant_reply class="select">
					<OPTION value=N selected>ȸ��/��ȸ��</OPTION>
					<OPTION value=Y>ȸ������</OPTION>
					<OPTION value=A>����������</OPTION>
					</SELECT>
					)
					<INPUT id=idx_use_reply1 onclick=this.form.grant_reply.disabled=true; type=radio value=N name=use_reply><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_reply1>������</LABEL><br>
					</TD>
				</tr>
				<?}else{?>
				<input type=hidden name=use_replay value="N">
				<input type=hidden name=grant_reply value="N">
				<?}?>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Խ��� ��۱��</TD>
					<TD class="td_con1">
					<INPUT id=idx_use_comment0 onclick=this.form.grant_comment.disabled=false; type=radio CHECKED value=Y name=use_comment><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_comment0>�����</LABEL>(��� ���� :
					<SELECT name=grant_comment class="select">
					<OPTION value=N selected>ȸ��/��ȸ��</OPTION>
					<OPTION value=Y>ȸ������</OPTION>
					<OPTION value=A>����������</OPTION>
					</SELECT>
					)
					<INPUT id=idx_use_comment1 onclick=this.form.grant_comment.disabled=true; type=radio value=N name=use_comment><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_comment1>������</LABEL>
					<br>

					</TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��б� ���</TD>
					<TD class="td_con1"><INPUT id=idx_use_lock0 type=radio value=A name=use_lock><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_lock0>�� �ǹ����</LABEL>  &nbsp;&nbsp;<INPUT id=idx_use_lock1 type=radio value=Y name=use_lock><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_lock1>�� ���û��</LABEL>  &nbsp;<INPUT id=idx_use_lock2 type=radio CHECKED value=N name=use_lock><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_use_lock2>������� ����</LABEL><br>&nbsp;<span class="font_blue"> * �Խù� �ۼ��� �ۼ��� ���ΰ� �����ڸ� �� �� �ֵ��� �ϴ� ����Դϴ�.</span></TD>
				</tr>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ۼ��� ǥ��</TD>
					<TD class="td_con1"><INPUT id=idx_writer_gbn0 type=radio CHECKED value=0 name=writer_gbn><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_writer_gbn0>ȸ�� �̸�</LABEL>  &nbsp;&nbsp;&nbsp;<INPUT id=idx_writer_gbn1 type=radio value=1 name=writer_gbn><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_writer_gbn1>ȸ�� ���̵�</LABEL><br>&nbsp;<span class="font_blue"> * �Խ��ǿ� ��µǴ� ȸ���� �ۼ��� ǥ�� ����� ������ �� �ֽ��ϴ�.</span></TD>
				</tr>
				<?if($btype=="L"){?>
				<TR>
					<TD colspan="2" background="images/table_con_line.gif"></TD>
				</TR>
				<tr>
					<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ Q&A ���</TD>
					<TD class="td_con1">
					<INPUT id=idx_prqna_gbn0 type=radio value="Y" name=prqna_gbn <?if($prqna_gbn=="Y")echo"checked";?> onclick="check_qnaboard('Y')"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_prqna_gbn0>���� �Խ����� ��ǰQNA�� �����</LABEL>

					&nbsp;&nbsp;&nbsp;<INPUT id=idx_prqna_gbn1 type=radio name=prqna_gbn value="" <?if($prqna_gbn!="Y")echo"checked";?> onclick="check_qnaboard('')"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for=idx_prqna_gbn1>������</LABEL>
					<br>&nbsp;<span class="font_blue"> * ��ǰ���������� ��ǰQ&A �Խ������� ���� �Խ����� ������ �� �ֽ��ϴ�.</span></TD>
				</tr>
				<?}?>
				<TR>
					<TD colspan=2 background="images/table_top_line.gif"></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height=10></td></tr>
			<tr>
				<td align=center><a href="javascript:CheckForm(document.form1);"><img src="images/botteon_newboard.gif" width="156" height="38" border="0"></a></td>
			</tr>

			<?}?>

			<tr>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
					<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
					<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
					<TD background="images/manual_bg.gif"></TD>
					<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
				</TR>
				<TR>
					<TD background="images/manual_left1.gif"></TD>
					<TD COLSPAN=3 width="100%" valign="top" class=menual_bg style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<col width=20></col>
					<col width=></col>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�Խ��� Ÿ�� ����</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �ѹ� ������ �Խ����� �ٸ� Ÿ������ ������ �Ұ� �մϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��ϵ� �Խ����� ���� ������ <a href="javascript:parent.topframe.GoMenu(8,'community_list.php');"><span class="font_blue">Ŀ�´�Ƽ > Ŀ�´�Ƽ ���� > �Խ��� ����Ʈ ����</a></span> �޴��� ����Ͻñ� �ٶ��ϴ�.</td>
					</tr>
					<tr>
						<td colspan="2" height="20"></td>
					</tr>
					<tr>
						<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
						<td><span class="font_dotline">�Խ��� �⺻ ��ɼ���</span></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- �ش� �Խ��� ���ݿ� ���� �⺻����� �����Ͻñ� �ٶ��ϴ�.</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td class="space_top" style="letter-spacing:-0.5pt;">- ��ϵ� �Խ����� ���� ������ <a href="javascript:parent.topframe.GoMenu(8,'community_list.php');"><span class="font_blue">Ŀ�´�Ƽ > Ŀ�´�Ƽ ���� > �Խ��� ����Ʈ ����</a></span> �޴��� ����Ͻñ� �ٶ��ϴ�.</td>
					</tr>
					</table>
					</TD>
					<TD background="images/manual_right1.gif"></TD>
				</TR>
				<TR>
					<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
					<TD COLSPAN=3 background="images/manual_down.gif"></TD>
					<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
				</TR>
				</TABLE>
				</td>
			</tr>
			<tr><td height="50"></td></tr>
			</table>




</td>
        <td width="16" background="images/con_t_02_bg.gif"></td>
    </tr>
    <tr>
        <td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
        <td background="images/con_t_04_bg.gif"></td>
        <td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
    </tr>
    <tr><td height="20"></td></tr>
</table>




			</td>
		</tr>
		</form>
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?=$onload?>
<? INCLUDE "copyright.php"; ?>