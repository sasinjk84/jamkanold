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
$list_type=$_POST["list_type"];

if(strlen($code)==0) $code="ALL";

$body=$_POST["body"];
$added=$_POST["added"];


if($added=="Y") {
	$leftmenu="Y";
} else {
	$leftmenu="N";
}

$subject = '��ǰ ī�װ�';

$insertKey = "prlist";

// ��� / ����
if ( $mode=="store" OR $mode=="restore" ) {
	$MSG = adminDesingBackup ( $mode, $insertKey, $body, $subject, $code, '', $leftmenu );
	$onload="<script>alert(\"".$MSG."\");</script>";
}



if($mode=="update" && strlen($body)>0) {

	$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='prlist' AND code='".$code."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	if($row->cnt==0) {
		$sql = "INSERT tbldesignnewpage SET ";
		$sql.= "type		= 'prlist', ";
		$sql.= "subject		= '��ǰ ī�װ�', ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."', ";
		$sql.= "code		= '".$code."' ";
		mysql_query($sql,get_db_conn());
	} else {
		$sql = "UPDATE tbldesignnewpage SET ";
		$sql.= "leftmenu	= '".$leftmenu."', ";
		$sql.= "body		= '".$body."' ";
		$sql.= "WHERE type='prlist' AND code='".$code."' ";
		mysql_query($sql,get_db_conn());
	}
	mysql_free_result($result);

	$sql="";
	if($leftmenu=="Y") {	//ī�װ�ȭ�� ���������� ����
		$sql = "UPDATE tblproductcode SET list_type=CONCAT(SUBSTRING(list_type,1,5),'U') ";
	} else if($leftmenu=="N") {	//ī�װ�ȭ�� ���ø� ����
		$sql = "UPDATE tblproductcode SET list_type=SUBSTRING(list_type,1,5) ";
	}


	if(strlen($sql)>0) {
		$csql = $sql;

		$sql.= "WHERE 1=1 ";
		$sql.= "AND type not in('S','SX','SM','SMX') ";
		if(strlen($code)==12) {
			$codeA=substr($code,0,3);
			$codeB=substr($code,3,3);
			$codeC=substr($code,6,3);
			$codeD=substr($code,9,3);
			$sql.= "AND codeA='".$codeA."' AND codeB='".$codeB."' ";
			$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
			$msg="�ش� ��ǰ ī�װ� �������� ";

			if($leftmenu=="Y") {
				$script_p="parent.ModifyCodeDesign('".$code."','".substr($list_type,0,5)."U"."','0');";
			} else if($leftmenu=="N") {
				$script_p="parent.ModifyCodeDesign('".$code."','".substr($list_type,0,5)."','0');";
			}

		} else {
			$msg="��� ��ǰ ī�װ� �������� ";
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
	$sql = "DELETE FROM tbldesignnewpage WHERE type='prlist' AND code='".$code."' ";
	mysql_query($sql,get_db_conn());


	$csql = $sql = "UPDATE tblproductcode SET list_type=SUBSTRING(list_type,1,5) ";
	$sql.= "WHERE 1=1 ";
	if(strlen($code)==12) {
		$codeA=substr($code,0,3);
		$codeB=substr($code,3,3);
		$codeC=substr($code,6,3);
		$codeD=substr($code,9,3);
		$sql.= "AND codeA='".$codeA."' AND codeB='".$codeB."' ";
		$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
		$msg="�ش� ��ǰ ī�װ� �������� �⺻���� �����Ǵ� ���ø� ���������� ����Ǿ����ϴ�.";
		$script_p="parent.ModifyCodeDesign('".$code."','".substr($list_type,0,5)."','0');";
	} else {
		$msg="��� ��ǰ ī�װ� ������������ �����Ǿ����ϴ�.\\n�ش� ��ǰ ī�װ��� ������������ �������� �ʾҽ��ϴ�.";
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
	$sql = "SELECT body FROM tbldesigndefault WHERE type='prlist' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
	}
	mysql_free_result($result);
}

if($mode!="clear") {
	$body="";
	$added="";
	$sql = "SELECT leftmenu,body FROM tbldesignnewpage WHERE type='prlist' AND code='".$code."' ";
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
<style>td {line-height:18pt;}</style>
<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<? /*<script>LH.add("parent_resizeIframe('MainPrdtFrame')");</script> */ ?>

<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(mode) {
	if(mode=="update") {
		if(document.form1.body.value.length==0) {
			alert("��ǰ ī�װ� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		if(document.form1.code.value.length==12) {
			msg="�ش� ";
		} else {
			msg="��� ";
		}
		if(document.form1.added.checked==true) {
			if(!confirm(msg+"��ǰ ī�װ� �������� �������������� �����Ͻðڽ��ϱ�?")) {
				return;
			}
		} else {
			if(!confirm(msg+"��ǰ ī�װ� �������� �⺻���� �����Ǵ� ���ø� ���������� �����Ͻðڽ��ϱ�?\n\n�Է��Ͻ� ���������� �ҽ��� ����˴ϴ�.")) {
				return;
			}
		}
		document.form1.mode.value=mode;
		document.form1.submit();
	} else if(mode=="delete") {
		if(confirm("��ǰ ī�װ� ������������ �����Ͻðڽ��ϱ�?")) {
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
			alert("�̸������ ���ī�װ� ���뿡���� Ȯ�� �Ҽ� �����ϴ�.\nī�װ��� ������ �ּ���!");
			return;
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
	window.open("http://www.getmall.co.kr/macro/pages/prlist_macro.html","top_macro","height=800,width=680,scrollbars=no");
}
//-->
</SCRIPT>

<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 style="table-layout:fixed">
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=mode>
<input type=hidden name=code value="<?=$code?>">
<input type=hidden name=list_type value="<?=$list_type?>">
<tr>
	<td>
	<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD><IMG SRC="images/design_productcate_stitle1.gif" border="0"></TD>
		<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/prlist_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" /></a></TD>
		<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
	</TR>
	</TABLE>
	</td>
</tr>
<tr>
	<td height="3"></td>
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
		<TD width="100%" class="notice_blue" style="line-height:12pt;">
			1) �Ŵ����� <b>��ũ�θ�ɾ�</b>�� �����Ͽ� ������ �ϼ���.</span><br />
			2) <span class="font_orange" style="font-size:11px;"><u>��ǰ ī�װ� ��ũ�� ���� ����</u> : <b>/front/productlist.php, /front/productlist_text.php, /templet/product/list_U.php</b> (���� ������ ���� ������ �ݵ�� ����Ͻñ� �ٶ��ϴ�.)</span><br />
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
<tr>
	<td><textarea name=body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($body)?></textarea><br><input type=checkbox name=added value="Y" <?if($added=="Y")echo"checked";?>> <b><span class="font_orange">�����ϱ� üũ</span>(üũ�ؾ߸� �������� ����˴ϴ�. ��üũ�� �ҽ��� �����ǰ� ������ ���� �ʽ��ϴ�.)</b></td>
</tr>
<tr>
	<td><p>&nbsp;</p></td>
</tr>
<tr>
	<td align="center">
		<a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a>
		<!--
		<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="����ϱ�"></a>&nbsp;&nbsp;&nbsp;
		<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="��������ϱ�"></a>
		-->
	</td>
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
			<td ><p class="LIPoint"><B><span class="font_orange">��ǰ ī�װ� ��ũ�θ�ɾ�</span></B>(�ش� ��ũ�θ�ɾ�� �ٸ� ������ ������ �۾��� ����� �Ұ�����)</p></td>
		</tr>
		<tr>
			<td width="20" align="right" valign="top"></td>
			<td >
			<table border=0 cellpadding=0 cellspacing=0 width=100%>
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
				<td class=table_cell align=right style="padding-right:15"><!--[CODENAVI??????_??????]-->[CODENAVI]</td>
				<td class=td_con1 style="padding-left:5;">
				ī�װ� �׺���̼�
				<!--
						<br><img width=10 height=0>
						<FONT class=font_orange>��?????? : ���� ī�װ��� ���� ī�װ� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
						<br><img width=10 height=0>
						<FONT class=font_orange>��?????? : ���� ī�װ��� ����</FONT> - <FONT COLOR="red">"#"����</FONT>
						<br>
						<FONT class=font_blue>��) [CODENAVI] or [CODENAVI000000_FF0000]</FONT>
				-->
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
				<td class=table_cell align=right style="padding-right:15">[CODEEVENT]</td>
				<td class=td_con1 style="padding-left:5;">
				ī�װ��� �̺�Ʈ �̹���/html
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[CODEGROUP]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ ī�װ� �׷�
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<!--
			<tr>
				<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">��ǰ ī�װ� �׷� ���� ��Ÿ�� ����</td>
				<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;">
					<img width=10 height=0>
					<FONT class=font_orange>#group1_td - ����ī�װ� TD ��Ÿ�� ���� (������ �� ��׶����÷�)</FONT>
					<br><img width=100 height=0>
					<FONT class=font_blue>��) #group1_td { background-color:#E6E6E6;width:25%; }</FONT>
					<br><img width=0 height=7><br><img width=10 height=0>
					<FONT class=font_orange>#group2_td - ����ī�װ� TD ��Ÿ�� ���� (������ �� ��׶����÷�)</FONT>
					<br><img width=100 height=0>
					<FONT class=font_blue>��) #group2_td { background-color:#EFEFEF; }</FONT>
					<br><img width=0 height=7><br><img width=10 height=0>
					<FONT class=font_orange>#group_line - �����׷�� �����׷� ������ ���ζ��� �� ��Ÿ�� ����</FONT>
					<br><img width=100 height=0>
					<FONT class=font_blue>��) #group_line { background-color:#FFFFFF;height:1px; }</FONT>
					<pre style="line-height:15px">
					<B>[��� ��]</B> - ���� ������ �Ʒ��� ���� �����Ͻø� �˴ϴ�.
						<FONT class=font_blue>&lt;style>
							#group1_td { background-color:#E6E6E6;width:25%; }
							#group2_td { background-color:#EFEFEF; }
							#group_line { background-color:#FFFFFF;height:1px; }
						&lt;/style></FONT>
					</pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			-->
			<tr>
				<td class=table_cell align=right style="padding-right:15">[NEWITEM1??]</td>
				<td class=td_con1 style="padding-left:5;">
				���ǽűԻ�ǰ - �̹���A��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[NEWITEM2??]</td>
				<td class=td_con1 style="padding-left:5;">
				���ǽűԻ�ǰ - �̹���B��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[NEWITEM????????_??]</td>
				<td class=td_con1 style="padding-left:5;">
				���ǽűԻ�ǰ - �̹���A��/�̹���B��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���� ������ �űԻ�ǰ ���� (1:�̹���A��, 2:�̹���B��)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �űԻ�ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �űԻ�ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �űԻ�ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �űԻ�ǰ ������ ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �űԻ�ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>_?? : �űԻ�ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
							<br>
							<FONT class=font_blue>��) [NEWITEM142NNYN2_10], [NEWITEM222LYYY2_5]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[NEWITEM3??]</td>
				<td class=td_con1 style="padding-left:5;">
				���ǽűԻ�ǰ - ����Ʈ��
							<br><img width=10 height=0>
							<FONT class=font_orange>?? : �űԻ�ǰ �������� (01~20)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[NEWITEM3??????]</td>
				<td class=td_con1 style="padding-left:5;">
				�űԻ�ǰ - ����Ʈ��
							<br><img width=10 height=0>
							<FONT class=font_orange>?? : �űԻ�ǰ �������� (01~20)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �űԻ�ǰ ������ ǥ�ÿ��� (Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �űԻ�ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �űԻ�ǰ ������ ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �űԻ�ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
							<br>
							<FONT class=font_blue>��) [NEWITEM304YYY4]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BESTITEM1??]</td>
				<td class=td_con1 style="padding-left:5;">
				�����α��ǰ - �̹���A��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BESTITEM2??]</td>
				<td class=td_con1 style="padding-left:5;">
				�����α��ǰ - �̹���B��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BESTITEM????????_??]</td>
				<td class=td_con1 style="padding-left:5;">
				�����α��ǰ - �̹���A��/�̹���B��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���� ������ �α��ǰ ���� (1:�̹���A��, 2:�̹���B��)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �α��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �α��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �α��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �α��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �α��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>_?? : �α��ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
							<br>
							<FONT class=font_blue>��) [BESTITEM142NNYN2_10], [BESTITEM222LYYY2_5]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BESTITEM3??]</td>
				<td class=td_con1 style="padding-left:5;">
				�����α��ǰ - ����Ʈ��
							<br><img width=10 height=0>
							<FONT class=font_orange>?? : �α��ǰ �������� (01~20)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[BESTITEM3??????]</td>
				<td class=td_con1 style="padding-left:5;">
				�����α��ǰ - ����Ʈ��
							<br><img width=10 height=0>
							<FONT class=font_orange>?? : �α��ǰ �������� (01~20)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �α��ǰ ������ ǥ�ÿ��� (Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �α��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �α��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : �α��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
							<br>
							<FONT class=font_blue>��) [BESTITEM304YYY4]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[HOTITEM1??]</td>
				<td class=td_con1 style="padding-left:5;">
				������õ��ǰ - �̹���A��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[HOTITEM2??]</td>
				<td class=td_con1 style="padding-left:5;">
				������õ��ǰ - �̹���B��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[HOTITEM????????_??]</td>
				<td class=td_con1 style="padding-left:5;">
				������õ��ǰ - �̹���A��/�̹���B��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���� ������ ��õ��ǰ ���� (1:�̹���A��, 2:�̹���B��)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��õ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��õ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��õ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��õ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��õ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>_?? : ��õ��ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
							<br>
							<FONT class=font_blue>��) [HOTITEM142NNYN2_10], [HOTITEM222LYYY2_5]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[HOTITEM3??]</td>
				<td class=td_con1 style="padding-left:5;">
				������õ��ǰ - ����Ʈ��
							<br><img width=10 height=0>
							<FONT class=font_orange>?? : ��õ��ǰ �������� (01~20)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[HOTITEM3??????]</td>
				<td class=td_con1 style="padding-left:5;">
				������õ��ǰ - ����Ʈ��
							<br><img width=10 height=0>
							<FONT class=font_orange>?? : ��õ��ǰ �������� (01~20)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��õ��ǰ ������ ǥ�ÿ��� (Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��õ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��õ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��õ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
							<br>
							<FONT class=font_blue>��) [HOTITEM304YYY4]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRLIST1??]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ��� - �̹���A��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRLIST2??]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ��� - �̹���B��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRLIST????????_??]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ��� - �̹���A��/�̹���B��
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���� ������ ��ǰ��� ���� (1:�̹���A��, 2:�̹���B��)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1-8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N/L)</FONT> (L�� ��ǰ�� ���߾� ��� ǥ�õ�)
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��ǰ ������ ���ζ��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>_?? : ��ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
							<br>
							<FONT class=font_blue>��) [PRLIST142NNYN2_10], [PRLIST222LYYY2_5]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRLIST3??]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ��� - ����Ʈ��
							<br><img width=10 height=0>
							<FONT class=font_orange>?? : ��ǰ��� �������� (01~20)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRLIST3???????]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ��� - ����Ʈ��
							<br><img width=10 height=0>
							<FONT class=font_orange>?? : ��ǰ �������� (01~20)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��ǰ �̹��� ǥ�ÿ��� (Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ��� (Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��ǰ ���߰��� ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��ǰ ������ ǥ�ÿ���(Y/N)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��ǰ �±� ǥ�ð���(0-9) : 0�� ��� ǥ�þ���</FONT>
							<br>
							<FONT class=font_blue>��) [PRLIST304NYYY4]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[PRLIST4??_??]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ��� - ����������
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ���κ� ��ǰ����(2~4)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>? : ��������� ������ �Ұ��� �����Է�(1~8)</FONT>
							<br><img width=10 height=0>
							<FONT class=font_orange>_?? : ��ǰ����(���Ʒ�) ���� �ִ� 99�ȼ� (���Է½� 5�ȼ�)</FONT>
							<br>
							<FONT class=font_blue>��) [PRLIST423_5]</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right bgcolor=#E9A74E style="padding-right:15">������ǰ(�ű�/�α�/��õ/��ǰ���) ��Ÿ�� ����</td>
				<td class=td_con1 bgcolor=#FEEEE2 style="padding-left:5;">
							<img width=10 height=0>
							<FONT class=font_orange>#prlist_colline - �̹���/����Ʈ���� ���ζ��� �� ��Ÿ�� ����</FONT>
							<br><img width=100 height=0>
							<FONT class=font_blue>��) #prlist_colline { background-color:#f4f4f4;height:1px; }</FONT>
							<br><img width=0 height=7><br><img width=10 height=0>
							<FONT class=font_orange>#prlist_colline - �̹���/����Ʈ���� ���ζ��� �� ��Ÿ�� ����</FONT>
							<br><img width=100 height=0>
							<FONT class=font_blue>��) #prlist_rowline { background-color:#f4f4f4;width:1px; }</FONT>
				<pre style="line-height:15px">
<B>[��� ��]</B> - ���� ������ �Ʒ��� ���� �����Ͻø� �˴ϴ�.

<FONT class=font_blue>&lt;style>
  #prlist_colline { background-color:#f4f4f4;height:1px; }
  #prlist_rowline { background-color:#f4f4f4;width:1px; }
&lt;/style></FONT></pre>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[TOTAL]</td>
				<td class=td_con1 style="padding-left:5;">
				�� ��ǰ�� <FONT class=font_blue>(��:[TOTAL]��)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTNEW]</td>
				<td class=td_con1 style="padding-left:5;">
					�űԵ�� ��ǰ�� ����  <FONT class=font_blue>(��:&lt;a href=[SORTNEW]>�űԵ�ϼ�&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTBEST]</td>
				<td class=td_con1 style="padding-left:5;">
					�α��ǰ(�Ǹŷ�)�� ����  <FONT class=font_blue>(��:&lt;a href=[SORTBEST]>�α��ǰ��&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTPRODUCTUP]</td>
				<td class=td_con1 style="padding-left:5;">
				������ �������� ����  <FONT class=font_blue>(��:&lt;a href=[SORTPRODUCTUP]>���������&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTPRODUCTDN]</td>
				<td class=td_con1 style="padding-left:5;">
				������ �������� ���� <FONT class=font_blue>(��:&lt;a href=[SORTPRODUCTDN]>���������&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTNAMEUP]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ�� �������� ���� <FONT class=font_blue>(��:&lt;a href=[SORTNAMEUP]>��ǰ�����&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTNAMEDN]</td>
				<td class=td_con1 style="padding-left:5;">
				��ǰ�� �������� ���� <FONT class=font_blue>(��:&lt;a href=[SORTNAMEDN]>��ǰ�����&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTPRICEUP]</td>
				<td class=td_con1 style="padding-left:5;">
				���� ��ǰ���ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTPRICEUP]>���ݼ���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTPRICEDN]</td>
				<td class=td_con1 style="padding-left:5;">
				���� ��ǰ���ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTPRICEDN]>���ݼ���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTRESERVEUP]</td>
				<td class=td_con1 style="padding-left:5;">
				���� �����ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTRESERVEUP]>�����ݼ���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTRESERVEDN]</td>
				<td class=td_con1 style="padding-left:5;">
				���� �����ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTRESERVEDN]>�����ݼ���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15">[SORTRESERVEDN]</td>
				<td class=td_con1 style="padding-left:5;">
				���� �����ݼ� <FONT class=font_blue>(��:&lt;a href=[SORTRESERVEDN]>�����ݼ���&lt;/a>)</font>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15">[ONNEW]</td>
				<td class=td_con1 style="padding-left:5;">
					�űԵ�� ��ǰ�� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[ONBEST]</td>
				<td class=td_con1 style="padding-left:5;">
					�α��ǰ�� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[ONPRICEUP]</td>
				<td class=td_con1 style="padding-left:5;">
					���� ���ݼ� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[ONPRICEDN]</td>
				<td class=td_con1 style="padding-left:5;">
					���� ���ݼ� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[ONRESERVEDN]</td>
				<td class=td_con1 style="padding-left:5;">
					�����ݼ� ���� ǥ�� <FONT class=font_blue>(class="sortOn", /lib/style.php ���Ͽ��� css ����)</FONT>
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>
			<tr>
				<td class=table_cell align=right style="padding-right:15">[LISTSELECT]</td>
				<td class=td_con1 style="padding-left:5;">
					��ǰ��°��� ����
				</td>
			</tr>
			<tr><td colspan=2 height=1 bgcolor=#dddddd></td></tr>

			<tr>
				<td class=table_cell align=right style="padding-right:15">[PAGE]</td>
				<td class=td_con1 style="padding-left:5;">
					������ ǥ��
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
			<td width="20" align="right"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
			<td ><p class="LIPoint">����,�帲�������� �����ͷ� �ۼ��� �̹�����ε� �۾������� Ʋ���� �� ������ �����ϼ���!</p></td>
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
	f.mode.value = 'prlist';
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