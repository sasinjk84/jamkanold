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
$body=$_POST["body"];
$added=$_POST["added"];

if((int)$code>0) {
	$sql = "SELECT * FROM tblproductbrand WHERE bridx='".$code."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$row) {
		$onload="<script>alert(\"�귣�� ������ �߸� �Ǿ����ϴ�.\");</script>";
	}
}

if(strlen($onload)==0) {
	if(strlen($mode)>0) {
		if(strlen($code)) {
			if(($code == "��ü" || $code == "ALL")) {
				$code = "ALL";
			}
		} else {
			$onload="<script>alert(\"�귣�� ������ �߸� �Ǿ����ϴ�.\");</script>";
		}
	}

	if(strlen($onload)==0) {

		if($added=="Y") {
			$leftmenu="Y";
		} else {
			$leftmenu="N";
		}

		$subject = '��ǰ �귣��';
		$insertKey = 'brlist';

		// ��� / ����
		if ( $mode=="store" OR $mode=="restore" ) {
			$MSG = adminDesingBackup ( $mode, $insertKey, $body, $subject, $code, '', $leftmenu );
			$onload="<script>alert(\"".$MSG."\");</script>";
		}


		if($mode == "update" && strlen($body)>0) {


			$sql = "SELECT COUNT(*) as cnt FROM tbldesignnewpage WHERE type='brlist' AND code='".$code."' ";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			if($row->cnt==0) {
				$sql = "INSERT tbldesignnewpage SET ";
				$sql.= "type		= 'brlist', ";
				$sql.= "subject		= '��ǰ �귣��', ";
				$sql.= "leftmenu	= '".$leftmenu."', ";
				$sql.= "body		= '".$body."', ";
				$sql.= "code		= '".$code."' ";
				mysql_query($sql,get_db_conn());
			} else {
				$sql = "UPDATE tbldesignnewpage SET ";
				$sql.= "leftmenu	= '".$leftmenu."', ";
				$sql.= "body		= '".$body."' ";
				$sql.= "WHERE type='brlist' AND code='".$code."' ";
				mysql_query($sql,get_db_conn());
			}
			mysql_free_result($result);

			$sql="";
			if($leftmenu=="Y") {	//�귣�� ȭ�� ���������� ����
				$sql = "UPDATE tblproductbrand SET list_type=CONCAT(SUBSTRING(list_type,1,4),'U') ";
			} else if($leftmenu=="N") {	//�귣��ȭ�� ���ø� ����
				$sql = "UPDATE tblproductbrand SET list_type=SUBSTRING(list_type,1,4) ";
			}
			if(strlen($sql)>0) {
				if((int)$code>0) {
					$sql.= "WHERE bridx='".$code."' ";
					$msg="�ش� ��ǰ �귣�� �������� ";
				} else {
					$msg="��� ��ǰ �귣�� �������� ";
				}
				mysql_query($sql,get_db_conn());

				if($leftmenu=="Y") {
					$msg.="�������������� ����Ǿ����ϴ�.";
				} else if($leftmenu=="N") {
					$msg.="�⺻���� �����Ǵ� ���ø� ���������� ����Ǿ����ϴ�.";
				}
			}
			if((int)$code>0) {
				$onload="<script>alert(\"".$msg."\");</script>";
			} else {
				echo "<script>alert(\"".$msg."\");parent.location.reload();</script>";
				exit;
			}
		} else if($mode=="delete") {
			$sql = "DELETE FROM tbldesignnewpage WHERE type='brlist' AND code='".$code."' ";
			mysql_query($sql,get_db_conn());

			$sql = "UPDATE tblproductbrand SET list_type=SUBSTRING(list_type,1,4) ";
			if((int)$code>0) {
				$sql.= "WHERE bridx='".$code."' ";
				$msg="�ش� ��ǰ �귣�� �������� �⺻���� �����Ǵ� ���ø� ���������� ����Ǿ����ϴ�.";
			} else {
				$msg="��� ��ǰ �귣�� ������������ �����Ǿ����ϴ�.\\n�ش� ��ǰ �귣���� ������������ �������� �ʾҽ��ϴ�.";
			}
			mysql_query($sql,get_db_conn());

			if((int)$code>0) {
				$onload="<script>alert(\"".$msg."\");</script>";
			} else {
				echo "<script>alert(\"".$msg."\");parent.location.reload();</script>";
				exit;
			}
		} else if($mode=="clear") {
			$body="";
			$sql = "SELECT body FROM tbldesigndefault WHERE type='brlist' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				$body=$row->body;
			}
			mysql_free_result($result);
		}
	}
}

if($mode!="clear") {
	$body="";
	$added="";
	if(strlen($code)>0) {
		if(($code == "��ü" || $code == "ALL")) {
			$code = "ALL";
		}
	}
	$sql = "SELECT leftmenu, body FROM tbldesignnewpage WHERE type='brlist' AND code='".$code."' ";
	$result = mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$body=$row->body;
		$added=$row->leftmenu;

		if($added=="Y" && $code == "ALL") {
			$design_default = "LU";
		}
	} else {
		$added="Y";
	}
	mysql_free_result($result);
}

if((int)$code>0) {
	$sql = "SELECT * FROM tblproductbrand WHERE bridx='".$code."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);

	if(strlen($row->list_type)==5) {
		$design_default = "LU";
	} else {
		$design_default = $row->list_type;
	}
}
?>

<? INCLUDE "header.php"; ?>
<style>td {line-height:18pt;}</style>
<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>LH.add("parent_resizeIframe('MainPrdtFrame')");</script>
<SCRIPT LANGUAGE="JavaScript">
<!--
function CheckForm(mode) {
	if(document.form1.code.value.length==0) {
		alert("��ǰ �귣�带 ������ �ּ���.");
		parent.document.form1.up_brandlist.focus();
		return;
	}

	if(mode=="update") {
		if(document.form1.body.value.length==0) {
			alert("��ǰ �귣�� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
			return;
		}
		if(document.form1.code.value=="ALL") {
			msg="��� ";
		} else {
			msg="�ش� ";
		}
		if(document.form1.added.checked==true) {
			if(!confirm(msg+"��ǰ �귣�� �������� �������������� �����Ͻðڽ��ϱ�?")) {
				return;
			}
		} else {
			if(!confirm(msg+"��ǰ �귣�� �������� �⺻���� �����Ǵ� ���ø� ���������� �����Ͻðڽ��ϱ�?\n\n�Է��Ͻ� ���������� �ҽ��� ����˴ϴ�.")) {
				return;
			}
		}
		document.form1.mode.value=mode;
		document.form1.submit();
	} else if(mode=="delete") {
		if(confirm("��ǰ �귣�� ������������ �����Ͻðڽ��ϱ�?")) {
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
			alert("��ǰ �귣�� ������ ������ �Է��ϼ���.");
			document.form1.body.focus();
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

<?
echo "parent.document.all[\"preview_img\"].style.display=\"none\";";
if(strlen($design_default)>0) {
	echo "parent.document.all[\"preview_img\"].src=\"images/sample/brand".$design_default.".gif\";\n";
	echo "parent.document.all[\"preview_img\"].style.display=\"\";";
}
?>

//��ũ�� ����(�˾�)
function macroview(){
	window.open("http://www.getmall.co.kr/macro/pages/productbrand_macro.html","productbrand_macro","height=800,width=680,scrollbars=no");
}

//-->
</SCRIPT>

<table cellpadding="0" cellspacing="0" width="100%">
<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode>
<input type=hidden name=code value="<?=$code?>">
<tr>
	<td>
	<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<TD><IMG SRC="images/design_productbrand_stitle1.gif" border="0"></TD>
		<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif">
			&nbsp;&nbsp;<a href="javascript:macroview();"><img src="images/btn_macroview.gif" border="0" align="absmiddle" alt="" /></a>&nbsp;&nbsp;<a href="http://www.getmall.co.kr/macro/data/productbrand_design.zip"><img src="images/btn_defaultcode.gif" border="0" align="absmiddle" alt="" />
		</TD>
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
		<TD width="100%" class="notice_blue" style="line-height:12pt;"><p>1) �Ŵ����� <b>��ũ�θ�ɾ�</b>�� �����Ͽ� ������ �ϼ���.</span><br>2) [�⺻������]+[�����ϱ�], [�����ϱ�]�ϸ� �⺻���ø����� ����(���������� �ҽ� ����)�� -> ���ø� �޴����� ���ϴ� ���ø� ����.<br>3) �⺻�� �����̳� �����ϱ� ���̵� ���ø� �����ϸ� ������������ �����˴ϴ�.(���������� �ҽ��� ������)<br>
		&nbsp;<b>&nbsp;&nbsp;</b>�� ��ǰ �귣�� ������ ���� ������ <a href="javascript:parent.topframe.GoMenu(4,'product_brand.php');"><span class="font_blue"><b>��ǰ���� > ī�װ�/��ǰ���� > ��ǰ �귣�� ���� ����</span></b></a> ���� ������ �ּ���.</p></TD>
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
	<td height="3"></td>
</tr>
<tr>
	<td><textarea name=body style="WIDTH: 100%; HEIGHT: 300px" class="textarea"><?=htmlspecialchars($body)?></textarea><br><input type=checkbox name=added value="Y" <?if($added=="Y")echo"checked";?>> <b><span class="font_orange">�����ϱ� üũ</span>(üũ�ؾ߸� �������� ����˴ϴ�. ��üũ�� �ҽ��� �����ǰ� ������ ���� �ʽ��ϴ�.)</b></td>
</tr>
<tr>
	<td height="20"></td>
</tr>
<tr>
	<td align="center"><a href="javascript:CheckForm('update');"><img src="images/botteon_save.gif" width="113" height="38" border="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('clear');"><img src="images/botteon_bok.gif" width="124" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('delete');"><img src="images/botteon_del.gif" width="113" height="38" border="0" hspace="0"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('preview');"><img src="images/botteon_prev.gif" width="113" height="38" border="0" hspace="2"></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('store');"><img src="images/botteon_store.gif" border="0" hspace="2" alt="����ϱ�"></a>&nbsp;&nbsp;&nbsp;<a href="javascript:CheckForm('restore');"><img src="images/botteon_restore.gif" border="0" hspace="2" alt="��������ϱ�"></a></td>
</tr>
<tr>
	<td height=10></td>
</tr>
</form>
</table>
<script>
function prevPage(){
	if(document.form1.body.value.length==0) {
		alert("������ ������ �Է��ϼ���.");
		document.form1.body.focus();
		return;
	}

	f = document.prevForm;
	f.mode.value = 'blist';
	f.code.value = document.form1.body.value;
	f.submit();
}
</script>

<form name="prevForm" method="post" action="design_prev_post.php" target="_blank">
	<input type="hidden" name="code">
	<input type="hidden" name="mode">
	<input type="hidden" name="brandcode" value="<?=$code?>">
</form>

<?=$onload?>

</body>
</html>