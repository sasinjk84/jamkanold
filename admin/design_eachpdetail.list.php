<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "de-5";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$mode=$_POST["mode"];
$code=$_POST["code"];
$detail_type=$_POST["detail_type"];

if(strlen($code)==0) $code="ALL";

$body=$_POST["body"];
$added=$_POST["added"];


if($added=="Y") {
	$leftmenu="Y";
} else {
	$leftmenu="N";
}

$subject = '��ǰ�� ȭ��';

$insertKey = "prdetail";

// ��� / ����
if ( $mode=="store" OR $mode=="restore" ) {
	$MSG = adminDesingBackup ( $mode, $insertKey, $body, $subject, $code, '', $leftmenu );
	$onload="<script>alert(\"".$MSG."\");</script>";
}



if($mode=="update" && strlen($body)>0) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='prdetail' AND code='".$code."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'prdetail', ";
		$sql.= "subject		= '��ǰ�� ȭ��', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."', ";
		$sql.= "code		= '".$code."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='prdetail' AND code='".$code."' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$sql="";
	if($leftmenu=="Y") {	//ī�װ�ȭ�� ���������� ����
		$sql = "UPDATE tblproductcode SET detail_type=CONCAT(SUBSTRING(detail_type,1,5),'U') ";
	} else if($leftmenu=="N") {	//ī�װ�ȭ�� ���ø� ����
		$sql = "UPDATE tblproductcode SET detail_type=SUBSTRING(detail_type,1,5) ";
	}
	if(strlen($sql)>0) {
		$csql = $sql;
		$sql.= "WHERE 1=1 ";
		$sql.= "AND type not in('S','SX','SM','SMX') ";
		$sql.= "AND codeA <> '999' ";
		if(strlen($code)==12) {
			$codeA=substr($code,0,3);
			$codeB=substr($code,3,3);
			$codeC=substr($code,6,3);
			$codeD=substr($code,9,3);
			$sql.= "AND codeA='".$codeA."' AND codeB='".$codeB."' ";
			$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
			$msg="�ش� ��ǰ�� ȭ�� �������� ";
			if($leftmenu=="Y") {
				$script_p="parent.ModifyCodeDesign('".$code."','".substr($detail_type,0,5)."U"."','0');";
			} else if($leftmenu=="N") {
				$script_p="parent.ModifyCodeDesign('".$code."','".substr($detail_type,0,5)."','0');";
			}
		} else {
			$msg="��� ��ǰ�� ȭ�� �������� ";
		}
		mysql_query($sql,get_db_conn());


		if(strlen($code)==12) {
			// ���� ī�װ��� �θ� ������ ���� ������ �κп� ���� ó��
			$cwhere = array();
			$cdep = false;
			for($i=0;$i<3;$i++){
				$key = 	'code'.chr(65+$i);
				if(${$key} == '000'){
					array_push($cwhere,$key."!='000'");
					break;
				}else array_push($cwhere,$key."='".${$key}."'");
			}
			array_push($cwhere,"dsameparent='1'");
			$csql .= " where ".implode(' and ',$cwhere);
			@mysql_query($csql,get_db_conn());
		}



		if($leftmenu=="Y") {
			$msg.="�������������� ����Ǿ����ϴ�.";
		} else if($leftmenu=="N") {
			$msg.="�⺻���� �����Ǵ� ���ø� ���������� ����Ǿ����ϴ�.";
		}
	}
	if(strlen($code)==12) {
		$onload="<script>".$script_p." alert(\"".$msg."\");</script>";
	} else {
		echo "<script>alert(\"".$msg."\");parent.location.reload();</script>";
		exit;
	}
} else if($mode=="delete") {
	$sql = "DELETE FROM tbldesignnewpage WHERE type='prdetail' AND code='".$code."' ";
	mysql_query($sql,get_db_conn());


	$csql = $sql = "UPDATE tblproductcode SET detail_type=SUBSTRING(detail_type,1,5) ";
	$sql.= "WHERE 1=1 ";
	if(strlen($code)==12) {
		$codeA=substr($code,0,3);
		$codeB=substr($code,3,3);
		$codeC=substr($code,6,3);
		$codeD=substr($code,9,3);
		$sql.= "AND codeA='".$codeA."' AND codeB='".$codeB."' ";
		$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
		$msg="�ش� ��ǰ�� ȭ�� �������� �⺻���� �����Ǵ� ���ø� ���������� ����Ǿ����ϴ�.";
		$script_p="parent.ModifyCodeDesign('".$code."','".substr($detail_type,0,5)."','0');";
	} else {
		$msg="��� ī�װ������� ������������ �����Ǿ����ϴ�.\\n�ش� ī�װ��� ������������ �������� �ʾҽ��ϴ�.";
	}
	mysql_query($sql,get_db_conn());

	if(strlen($code)==12) {
		// ���� ī�װ��� �θ� ������ ���� ������ �κп� ���� ó��
		$cwhere = array();
		$cdep = false;
		for($i=0;$i<3;$i++){
			$key = 	'code'.chr(65+$i);
			if(${$key} == '000'){
				array_push($cwhere,$key."!='000'");
				break;
			}else array_push($cwhere,$key."='".${$key}."'");
		}
		array_push($cwhere,"dsameparent='1'");
		$csql .= " where ".implode(' and ',$cwhere);
		@mysql_query($csql,get_db_conn());
	}

	if(strlen($code)==12) {
		$onload="<script>".$script_p." alert(\"".$msg."\");</script>";
	} else {
		echo "<script>alert(\"".$msg."\");parent.location.reload();</script>";
		exit;
	}
} else if($mode=="clear") {
	$body="";
	$sql = "SELECT body FROM tbldesigndefault WHERE type='prdetail' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($mode!="clear") {
	$body="";
	$added="";
	$sql = "SELECT leftmenu,body FROM tbldesignnewpage WHERE type='prdetail' AND code='".$code."' ";
	$result = mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$added=$row->leftmenu;
	} else {
		$added="Y";
	}
	mysql_free_result($result);
}

?>
<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>LH.add("parent_resizeIframe('MainPrdtFrame')");</script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(mode) {
	if(mode=="update") {
		if(document.form1.body.value.length==0) {
			alert("��ǰ�� ȭ�� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		if(document.form1.code.value.length==12) {
			msg="�ش� ";
		} else {
			msg="��� ";
		}
		if(document.form1.added.checked==true) {
			if(!confirm(msg+"��ǰ�� ȭ�� �������� �������������� �����Ͻðڽ��ϱ�?")) {
				return;
			}
		} else {
			if(!confirm(msg+"��ǰ�� ȭ�� �������� �⺻���� �����Ǵ� ���ø� ���������� �����Ͻðڽ��ϱ�?\n\n�Է��Ͻ� ���������� �ҽ��� ����˴ϴ�.")) {
				return;
			}
		}
		document.form1.mode.value=mode;
		document.form1.submit();
	} else if(mode=="delete") {
		if(confirm("��ǰ�� ȭ�� ������������ �����Ͻðڽ��ϱ�?")) {
			document.form1.mode.value=mode;
			document.form1.submit();
		}
	} else if(mode=="clear") {
		alert("�⺻�� ���� �� [�����ϱ�]�� Ŭ���ϼ���. Ŭ�� �� �������� ����˴ϴ�.");
		document.form1.mode.value=mode;
		document.form1.submit();
	}

	// ���
	if(mode=="store") {
		if(confirm("<?=$subject?> �������� ����Ͻðڽ��ϱ�?\n\n�������� �����̴ٸ� \"�����ϱ�\"�� ���� �Ͻ��� ����Ͻñ� �ٶ��ϴ�.\n���� ����� ����ҽ��� ��ü�մϴ�.")) {
			document.form1.mode.value=mode;
			document.form1.submit();
		}
	}
	// ����
	if(mode=="restore") {
		if(confirm("<?=$subject?> �������� ������� �Ͻðڽ��ϱ�?\n\n���� �ϰ� �Ǹ� �ٷ� ������ ���� �˴ϴ�.")) {
			document.form1.mode.value=mode;
			document.form1.submit();
		}
	}

	// �̸�����
	if(mode=="preview") {
		if(document.form1.body.value.length==0) {
			alert("��ǰ ī�װ� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		if(document.form1.code.value.length==12) {
		} else {
			//alert("�̸������ ���ī�װ� ���뿡���� Ȯ�� �Ҽ� �����ϴ�.\nī�װ��� ������ �ּ���!");
			//return;
		}

		document.form1.mode.value='<?=$insertKey?>';
		document.form1.target="preview";
		document.form1.action="designPreview.php";
		document.form1.submit();
		document.form1.target="";
		document.form1.action="<?=$_SERVER[PHP_SELF]?>";
	}

}

//��ũ�� ����(�˾�)
function macroview(){
	window.open("http://www.getmall.co.kr/macro/pages/prdetail_macro.html","top_macro","height=800,width=680,scrollbars=no");
}
//-->
</SCRIPT>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
<tr>
	<td>
	<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD><IMG SRC="images/design_productdt_stitle1.gif" WIDTH="240" HEIGHT=31 ALT=""></TD>
		<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/prdetail_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" /></a></TD>
		<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
	</TR>
	</TABLE>
	</td>
</tr>
<tr>
	<td height=3></td>
</tr>
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
		<TD width="100%" class="notice_blue">
			1) �Ŵ����� <b>��ũ�θ�ɾ�</b>�� �����Ͽ� ������ �ϼ���.</span><br />
			2) <span class="font_orange" style="font-size:11px;"><u>��ǰ�� ȭ�� ��ũ�� ���� ����</u> : <b> /front/productdetail.php, /front/productdetail_text.php, /templet/product/detail_U.php</b> (���� ������ ���� ������ �ݵ�� ����Ͻñ� �ٶ��ϴ�.)</span><br />
			3) [�⺻������]+[�����ϱ�], [�����ϱ�]�ϸ� �⺻���ø����� ����(���������� �ҽ� ����)�� -> ���ø� �޴����� ���ϴ� ���ø� ����.<br />
			4) �⺻�� �����̳� �����ϱ� ���̵� ���ø� �����ϸ� ������������ �����˴ϴ�.(���������� �ҽ��� ������)
		</TD>
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
<tr>
	<td height="20"></td>
</tr>
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=mode>
<input type=hidden name=code value="<?=$code?>">
<input type=hidden name=detail_type value="<?=$detail_type?>">
<tr>
	<td><textarea name=body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($body)?></textarea><br><input type=checkbox name=added value="Y" <?if($added=="Y")echo"checked";?>> <b><span class="font_orange">�����ϱ� üũ</span>(üũ�ؾ߸� �������� ����˴ϴ�. ��üũ�� �ҽ��� �����ǰ� ������ ���� �ʽ��ϴ�.)</b></td>
</tr>
<tr>
	<td><p>&nbsp;</p></td>
</tr>
<tr>
	<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="����ϱ�"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="��������ϱ�"></a></td>
</tr>
</form>
<tr>
	<td height=20></td>
</tr>
<tr>
	<td>
	<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 HEIGHT=45 ALT=""></TD>
		<TD><IMG SRC="images/manual_title.gif" WIDTH=113 HEIGHT=45 ALT=""></TD>
		<TD width="100%" background="images/manual_bg.gif"></TD>
		<TD background="images/manual_bg.gif"></TD>
		<TD><IMG SRC="images/manual_top2.gif" WIDTH=18 HEIGHT=45 ALT=""></TD>
	</TR>
	<TR>
		<TD background="images/manual_left1.gif"></TD>
		<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
			<td><p class="LIPoint"><B><span class="font_orange">��ǰ�� ȭ�� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
		</tr>
		<tr>
			<td width="20" align="right" valign="top"></td>
			<td>
			<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
			<col width=150></col>
			<col width=></col>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[CODENAME]</td>
				<td class=td_con1 style="padding-left:5;">
				���� ī�װ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[CODENAVI??????_??????]</td>
				<td class=td_con1 style="padding-left:5;">
				ī�װ� �׺���̼�
						<br><img width=10 height=0>
						<FONT class=font_orange>��?????? : ���� ī�װ��� ���� ī�װ� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
						<br><img width=10 height=0>
						<FONT class=font_orange>��?????? : ���� ī�װ��� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
						<br>
						<FONT class=font_blue>��) [CODENAVI] or [CODENAVI000000_FF0000]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[CLIPCOPY]</td>
				<td class=td_con1 style="padding-left:5;">
				�����ּ� ���� ��ư <FONT class=font_blue>(��:&lt;a href=[CLIPCOPY]>�ּҺ���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[IFVENDER]<br>[IFENDVENDER]</td>
				<td class=td_con1 style="padding-left:5;">
				������ü ��ǰ�� ��� (������ü ��ǰ�� ��쿡�� ���� ���)
		<pre style="line-height:15px">
<font class=font_blue><B>[IFVENDER]</B>
  ����
<B>[IFENDVENDER]</B></font></pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_IMAGE]</td>
				<td class=td_con1 style="padding-left:5;">
					������ü �̹��� - [IFVENDER] [IFENDVENDER] ���뿡 ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_NAME]</td>
				<td class=td_con1 style="padding-left:5;">
					������ü�� <FONT class=font_blue>(�� : ���޾�ü : [VENDER_NAME])</font> - [IFVENDER] [IFENDVENDER] ���뿡 ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_OWNER]</td>
				<td class=td_con1 style="padding-left:5;">
					������ü ��ǥ�ڸ� <FONT class=font_blue>(�� : ��ǥ�� : [VENDER_OWNER])</font> - [IFVENDER] [IFENDVENDER] ���뿡 ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_MINISHOP]</td>
				<td class=td_con1 style="padding-left:5;">
					��ü �̴ϼ� ��ũ <FONT class=font_blue>(�� : &lt;a href=[VENDER_MINISHOP]>[VENDER_NAME]&lt;/a>)</font> - [IFVENDER] [IFENDVENDER] ���뿡 ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_PRDTCNT]</td>
				<td class=td_con1 style="padding-left:5;">
					��ü��ǰ�� <FONT class=font_blue>(�� : ��ü��ǰ�� : &lt;B>[VENDER_PRDTCNT]&lt;/B>��)</font> - [IFVENDER] [IFENDVENDER] ���뿡 ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDER_REGIST]</td>
				<td class=td_con1 style="padding-left:5;">
					�ܰ������ ��ư <FONT class=font_blue>(�� : &lt;a href=[VENDER_REGIST]>�ܰ������&lt;/a>)</font> - [IFVENDER] [IFENDVENDER] ���뿡 ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15">[VENDERPRODUCT]</td>
				<td class=td_con1 style="padding-left:5;">
					������ü ��� ��Ÿ��ǰ (����� ���� �ֱ� 3�� ��ǰ ���)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15"><B>[STARTFORM]</B></td>
				<td class=td_con1 style="padding-left:5;">
					���� ���� (��������, ��ٱ���/�ٷα��ŵ��� �ϱ� ���ؼ��� �� �־��־����) - ���� ������ �κп����� <B>[ENDFORM]</B> �Է�
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRNAME]</td>
				<td class=td_con1 style="padding-left:5;">
					��ǰ��
				</td>
			</tr>
			<!--
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[COUPON1]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ�����̹��� + �������� ǥ��
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[COUPON2]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ�����̹����� ǥ��
				</td>
			</tr>
			-->
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRMSG]</td>
				<td class=td_con1 style="padding-left:5;">
					��ǰ ȫ������
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PREV]</td>
				<td class=td_con1 style="padding-left:5;">
				������ǰ <FONT class=font_blue>(��:&lt;a href=[PREV]>������ǰ&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[NEXT]</td>
				<td class=td_con1 style="padding-left:5;">
				������ǰ <FONT class=font_blue>(��:&lt;a href=[NEXT]>������ǰ&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRIMAGE]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ�̹���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[GONGTABLE]</td>
				<td class=td_con1 style="padding-left:5;">
				�������� ���ݺ���ǥ (���۰�, ���簡)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[GONGINFO]</td>
				<td class=td_con1 style="padding-left:5;">
				�������� ��ǰ���� ǥ�� (���ݺ���ǥ �� ���߰���,���簡��,���ż���,��ǰ�ɼ� ��)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRINFO]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ���� - ��ǰ���� ���� ������ �������
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SELLPRICE]</td>
				<td class=td_con1 style="padding-left:5;">
				�ǸŰ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[GONGPRICE]</td>
				<td class=td_con1 style="padding-left:5;">
				�������� ���簡
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[DOLLAR]</td>
				<td class=td_con1 style="padding-left:5;">
				�ؿ�ȭ��
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRODUCTION]</td>
				<td class=td_con1 style="padding-left:5;">
				������
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[MADEIN]</td>
				<td class=td_con1 style="padding-left:5;">
				������ <FONT class=font_blue>(��:������ : [MADEIN])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BRAND]</td>
				<td class=td_con1 style="padding-left:5;">
				�귣�� <FONT class=font_blue>(��:�귣�� : [BRAND])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BRANDLINK]</td>
				<td class=td_con1 style="padding-left:5;">
				�귣�� ��� <FONT class=font_blue>(��:&lt;a href=[BRANDLINK]>�ٷΰ���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[MODEL]</td>
				<td class=td_con1 style="padding-left:5;">
				�𵨸� <FONT class=font_blue>(��:�𵨸� : [MODEL])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[GIFT_PRICE]</td>
				<td class=td_con1 style="padding-left:5;">
				����ǰ ��å-���Ű���(���̾�)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[OPENDATE]</td>
				<td class=td_con1 style="padding-left:5;">
				����� <FONT class=font_blue>(��:����� : [OPENDATE])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SELFCODE]</td>
				<td class=td_con1 style="padding-left:5;">
				�����ڵ� <FONT class=font_blue>(��:�����ڵ� : [SELFCODE])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[ADDCODE]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ Ư�̻��� <FONT class=font_blue>(��:Ư�̻��� : [ADDCODE])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[DELIPRICE]</td>
				<td class=td_con1 style="padding-left:5;">
				��ۺ� <FONT class=font_blue>(��:��ۺ� : [DELIPRICE])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell width=180 align=right style="padding-right:15" nowrap>[IFUSPEC?]<br>[IFENDUSPEC?]</td>
				<td class=td_con1 width=100% style="padding-left:5;">
				����� ���� ���� �׸� �̸� �Ǵ� ���� ���� ��쿡�� ���
				<br><img width=10 height=0>
				<FONT class=font_orange>? : ����� ���� ���� ��ȣ 1,2,3,4,5</FONT> - ��ȣ�� �׻� ������ �̷����� �˴ϴ�.
				<pre style="line-height:15px">
<FONT class=font_blue>   <B>[IFUSPEC1]</B>
     ����� ���� ���� �׸� 1 �̸� �Ǵ� ���� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDUSPEC1]</B>
               ��
               ��
               ��
<FONT class=font_blue>   <B>[IFUSPEC5]</B>
     ����� ���� ���� �׸� 5 �̸� �Ǵ� ���� <FONT COLOR="red"><B>����</B></FONT> ����� ����
   <B>[IFENDUSPEC5]</B></pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[USPECNAME?]<br>[USPECVALUE?]</td>
				<td class=td_con1 style="padding-left:5;">
				����� ���� ����
						<br><img width=10 height=0>
						<FONT class=font_orange>? : ����� ���� ���� ��ȣ 1,2,3,4,5</FONT>
<pre style="line-height:15px">
<FONT class=font_blue>   [IFUSPEC1]
     <B>[USPECNAME1] : [USPECVALUE1]</B>
   [IFENDUSPEC1]
               ��
               ��
               ��
<FONT class=font_blue>   [IFUSPEC5]
     <B>[USPECNAME5] : [USPECVALUE5]</B>
   [IFENDUSPEC5]</pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[CONSUMPRICE]</td>
				<td class=td_con1 style="padding-left:5;">
				���߰��� <FONT class=font_blue>(��:���߰��� : [CONSUMPRICE]��)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[RESERVE]</td>
				<td class=td_con1 style="padding-left:5;">������ <FONT class=font_blue>(��:������ : [RESERVE]��)</font></td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class="table_cell" align="right" style="padding-right:15px;">[ABLE_COUPON_POP]</td>
				<td class=td_con1 style="padding-left:5;">������� ��ũ(�˾�) <FONT class=font_blue>(��:&lt;a href=[ABLE_COUPON_POP]>���밡�� ��ü����&lt;/a&gt;)</font></td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QUANTITY]</td>
				<td class=td_con1 style="padding-left:5;">
				�����Է¹ڽ� <FONT class=font_blue>(��:[QUANTITY]��)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QUANTITY_UP]</td>
				<td class=td_con1 style="padding-left:5;">
				�������� �Լ� <FONT class=font_blue>(��:&lt;a href=[QUANTITY_UP]>����&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QUANTITY_DN]</td>
				<td class=td_con1 style="padding-left:5;">
				�������� �Լ� <FONT class=font_blue>(��:&lt;a href=[QUANTITY_DN]>����&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell bgcolor=#F0F0F0>
				<pre style="line-height:15px">
[IFOPTION]
    [IFOPTION1]
        [OPTION1]
    [IFENDOPTION1]
    [IFOPTION2]
        [OPTION2]
    [IFENDOPTION2]
[IFENDOPTION]</pre>
				</td>
				<td class=td_con1 style="padding-left:5;">
					��ǰ�ɼ� ó�� ��ũ�� ����
					<pre style="line-height:15px">
  [IFOPTION]		- �ɼ��� ���� ���
         [IFOPTION1]		- �ɼ�1�� ���� ���
               [OPTION1]	- ù��° �ɼ� ���� <FONT COLOR="red">(�� : &lt;div align=center>[OPTION1]&lt;/div>)</FONT>
         [IFENDOPTION1]	- �ɼ�1 ��
         [IFOPTION2]		- �ɼ�2�� ���� ���
               [OPTION2]	- �ι�° �ɼ� ���� <FONT COLOR="red">(�� : &lt;div align=center>[OPTION2]&lt;/div>)</FONT>
         [IFENDOPTION2]	- �ɼ�2 ��
  [IFENDOPTION]		- �ɼ� ��ü ��</pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[IFPACKAGE]<br>[IFENDPACKAGE]</td>
				<td class=td_con1 style="padding-left:5;">
				��Ű���� ��� (��Ű���� ���õ� ��쿡�� ���� ���)
		<pre style="line-height:15px">
<font class=font_blue><B>[IFPACKAGE]</B>
  ����
<B>[IFENDPACKAGE]</B></font></pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class="table_cell" align="right" style="padding-right:15px;">[SNSBUTTON]</td>
				<td class="td_con1" style="padding-left:5px;">
					SNS ��ǰȫ�� �� �̸���, SMS
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15px;">[IFPESTER]<br>[PESTER]<br>[IFENDPESTER]</td>
				<td class=td_con1 style="padding-left:5px;">
					������
					<pre style="line-height:15px">
  [IFPESTER]	- ������ ��� �������
	&lt;a href=[PESTER]>������(�ؽ�Ʈ or �̹���) &lt;/a>
  [IFENDPESTER]	- ������ ��
					</pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[IFPRESENT]<br>[PRESENT]<br>[IFENDPRESENT]</td>
				<td class=td_con1 style="padding-left:5;">
				�����ϱ�
					<pre style="line-height:15px">
  [IFPRESENT]	- �����ϱ� ��� �������
	&lt;a href=[PRESENT]>�����ϱ�(�ؽ�Ʈ or �̹���) &lt;/a>
  [IFENDPRESENT]	- �����ϱ� ��
					</pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PACKAGESELECT]</td>
				<td class=td_con1 style="padding-left:5;">
				��Ű������ <FONT class=font_blue>(��:��Ű������ : [PACKAGESELECT])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BASKETIN]</td>
				<td class=td_con1 style="padding-left:5;">
				��ٱ��� ��� <FONT class=font_blue>(��:&lt;a href=[BASKETIN]>��ٱ��� ���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BARO]</td>
				<td class=td_con1 style="padding-left:5;">
				�ٷα��� <FONT class=font_blue>(��:&lt;a href=[BARO]>�ٷα���&lt/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[WISHIN]</td>
				<td class=td_con1 style="padding-left:5;">
				WishList��� <FONT class=font_blue>(��:&lt;a href=[WISHIN]>���ø���Ʈ ���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15"><B>[ENDFORM]</B></td>
				<td class=td_con1 style="padding-left:5;">
				���� �� (��������, ��ٱ���/�ٷα��ŵ��� �ϱ� ���ؼ��� �� �־��־����)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class="table_cell" align="right" style="padding-right:15px;"><B>[PRODUCTDETAIL_EVENT]</B></td>
				<td class=td_con1 style="padding-left:5;">
					��ǰ�� �����̺�Ʈ ���� <FONT class=font_blue>(���θ�� > �̺�Ʈ/����ǰ ��� ���� > ��ǰ�� �����̺�Ʈ �������� ��ϰ���)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr><td colspan=2 height=5></td></tr>

			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PACKAGETABLE]</td>
				<td class=td_con1 style="padding-left:5;">
				��Ű�� ���� ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[ASSEMBLETABLE]</td>
				<td class=td_con1 style="padding-left:5;">
				�ڵ�/���� ��ǰ ����
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[COUPON_LIST]</td>
				<td class=td_con1 style="padding-left:5;">
				���� ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[TAGLIST]</td>
				<td class=td_con1 style="padding-left:5;">
				�±׸��
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[TAGREGINPUT_�Է��� ��Ÿ��]</td>
				<td class=td_con1 style="padding-left:5;">
				�±��Է��� <FONT class=font_blue>(��:[TAGREGINPUT_width:160px;height:22;])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[TAGREGOK]</td>
				<td class=td_con1 style="padding-left:5;">
				�±״ޱ� ��ư <FONT class=font_blue>(��:&lt;a href=[TAGREGOK]>�±״ޱ�&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[COLLECTION]</td>
				<td class=td_con1 style="padding-left:5;">
				���û�ǰ (Ÿ��Ʋ ������) : ��ǰ����=>���û�ǰ�������� ������ ��� ���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[DELIINFO]</td>
				<td class=td_con1 style="padding-left:5;">
				���/��ȯ/ȯ������
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[DETAIL]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ������
				</td>
			</tr>

			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRODUCTINFOGOSI]</td>
				<td class=td_con1 style="padding-left:5;">
					��ǰ������� <FONT class=font_blue>(���� ���̺��� ��Ÿ�� ������ /css/common.css ���Ͽ��� productInfoGosi Ŭ������ ���� ����)</font>
				</td>
			</tr>


			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr><td colspan=2 height=5></td></tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15"><B>[REVIEW_STARTFORM]</B></td>
				<td class=td_con1 style="padding-left:5;">
				���䰡 ���۵Ǵ� ��ġ�� �� �Է��ؾ��� (���� ���� ��ġ��)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEWALL]</td>
				<td class=td_con1 style="padding-left:5;">
				��ü���亸�� ��ư <FONT class=font_blue>(��:&lt;a href=[REVIEWALL]>��ü���亸��&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_WRITE]</td>
				<td class=td_con1 style="padding-left:5;">
				���� �ۼ��� ���� ��ư <FONT class=font_blue>(��:&lt;a href=[REVIEW_WRITE]>���侲��&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_TOTAL]</td>
				<td class=td_con1 style="padding-left:5;">
				��ϵ� ���� �� ���� <FONT class=font_blue>(��:[REVIEW_TOTAL]���� ���䰡 ��ϵǾ����ϴ�.)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<!--
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_AVERAGE??????_??????]</td>
				<td class=td_con1 style="padding-left:5;">
				���� ���� ��� <FONT class=font_blue>��) ������� : [REVIEW_AVERAGE] or [REVIEW_AVERAGEcacaca_000000]</font> <FONT class=font_orange>��??????</font> : ��5�� �⺻ ����, <FONT class=font_orange>��??????</font> : ��� �� ���� - "#"������ 6�ڸ� ����
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			-->
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_HIDE_START]</td>
				<td class=td_con1 style="padding-left:5;">
				���� �������� ���侲�� ��ư Ŭ���ÿ��� ���̷��� ����ϼ���.
				<br>������ �κп� [REVIEW_HIDE_END] �� �Է��ؾ��մϴ�.
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_WRITE_FORM]</td>
				<td class=td_con1 style="padding-left:5;">
					���� �ۼ� �Է��� (/front/prreview.php ���� ���������մϴ�.)
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			
			<!--
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_SHOW_START]</td>
				<td class=td_con1 style="padding-left:5;">
				���� �������� �׻� ���̷��� ����ϼ���. ������ �κп� [REVIEW_SHOW_END] �� �Է��ؾ��մϴ�.
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_NAME_�Է��� ��Ÿ��]</td>
				<td class=td_con1 style="padding-left:5;">
				�̸� �Է���
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_AREA_������ ��Ÿ��]</td>
				<td class=td_con1 style="padding-left:5;">
				���� ������ <FONT class=font_blue>(��:[REVIEW_AREA_width:95%;height:40;])</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARKS??????]</td>
				<td class=td_con1 style="padding-left:5;">
				�������
				<br><img width=10 height=0>
				<FONT class=font_orange>?????? : �� ���� ("#"����)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARK1]</td>
				<td class=td_con1 style="padding-left:5;">
				���� (�� 1�� ���ùڽ�) <FONT class=font_blue>��) [REVIEW_MARK1]��</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARK2]</td>
				<td class=td_con1 style="padding-left:5;">
				���� (�� 2�� ���ùڽ�) <FONT class=font_blue>��) [REVIEW_MARK2]�ڡ�</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARK3]</td>
				<td class=td_con1 style="padding-left:5;">
				���� (�� 3�� ���ùڽ�) <FONT class=font_blue>��) [REVIEW_MARK3]�ڡڡ�</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARK4]</td>
				<td class=td_con1 style="padding-left:5;">
				���� (�� 4�� ���ùڽ�) <FONT class=font_blue>��) [REVIEW_MARK4]�ڡڡڡ�</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_MARK5]</td>
				<td class=td_con1 style="padding-left:5;">
				���� (�� 5�� ���ùڽ�) <FONT class=font_blue>��) [REVIEW_MARK5]�ڡڡڡڡ�</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			-->
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BTN_REVIEW_WRITE]</td>
				<td class=td_con1 style="padding-left:5;">
					���侲�� ��ư <FONT class=font_blue>(��:&lt;a href=[BTN_REVIEW_WRITE]>���侲��&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_HIDE_END]</td>
				<td class=td_con1 style="padding-left:5;">
				���� �������� ���侲�� ��ư Ŭ���ÿ��� ���̰� �ϴ� [REVIEW_HIDE_START] ���ÿ� ������ ����
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<!--
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_SHOW_END]</td>
				<td class=td_con1 style="padding-left:5;">
				���� �������� �׻� ���̰� �ϴ� [REVIEW_SHOW_START] ���ÿ� ������ ����
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			-->
			<tr>
				<td class=table_cell align=right style="padding-right:15"><B>[REVIEW_ENDFORM]</B></td>
				<td class=td_con1 style="padding-left:5;">
				���䰡 ������ ��ġ�� �� �Է������
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[REVIEW_LIST]</td>
				<td class=td_con1 style="padding-left:5;">
				���� ����Ʈ
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr><td colspan=2 height=5></td></tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QNA_ALL]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰQ&A �Խ��� ���� <FONT class=font_blue>(��:&lt;a href=[QNA_ALL]>��ǰQ&A�Խ��ǰ���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QNA_WRITE]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰQ&A �Խ��� �����ư <FONT class=font_blue>(��:&lt;a href=[QNA_WRITE]>��ǰQ&A�Խ��� �۾���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QNA_LIST]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰQ&A ����Ʈ
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[QNA_TOTAL]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰQ&A �� ����
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SNSCOMMENT]</td>
				<td class=td_con1 style="padding-left:5;">
				SNSȫ��
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[GONGGUCOMMENT]</td>
				<td class=td_con1 style="padding-left:5;">
				�������Ž�û
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			</table>
			</td>
		</tr>
		<tr>
			<td width="20" colspan="2"><p>&nbsp;</p></td>
		</tr>
		<tr>
			<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
			<td><p class="LIPoint">����,�帲�������� �����ͷ� �ۼ��� �̹�����ε� �۾������� Ʋ���� �� ������ �����ϼ���!</p></td>
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
<tr>
	<td height="50"></td>
</tr>
</table>
<script>
function prevPage(){
	if(document.form1.body.value.length==0) {
		alert("������ ������ �Է��ϼ���.");
		document.form1.body.focus();
		return;
	}

	f = document.prevForm;
	f.mode.value = 'prdetail';
	f.code.value = document.form1.body.value;
	f.submit();
}
</script>
<script type="text/javascript">
<!--
	parent.autoResize('MainPrdtFrame');
//-->
</script>

<form name="prevForm" method="post" action="design_prev_post.php" target="_blank">
	<input type="hidden" name="code">
	<input type="hidden" name="mode">
</form>

<?=$onload?>

</body>
</html>