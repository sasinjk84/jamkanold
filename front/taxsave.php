<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

function getParseTax($temp) {
	$val = array();
	$list = explode("<br>\n",$temp);
	for ($i=0;$i<count($list); $i++) {
		$data = explode("=",$list[$i]);
		$val[$data[0]] = $data[1];
	}
	return $val;
}

$ordercode=$_POST["ordercode"];
$productname=urldecode($_POST["productname"]);

if(strlen($ordercode)==0) {
	echo "<html></head><body onload=\"alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close()\"></body></html>";exit;
}

$tax_cnum="";
$sql = "SELECT tax_cnum,tax_cname,tax_cowner,tax_caddr,tax_ctel,tax_type,tax_rate,tax_mid,tax_tid ";
$sql.= "FROM tblshopinfo ";
$result=mysql_query($sql,get_db_conn());
$row=mysql_fetch_object($result);
mysql_free_result($result);

$tax_cnum=$row->tax_cnum;
$taxsavetype=$row->tax_type;
$tax_rate=$row->tax_rate;

$tax_no=$row->tax_cnum;
$kcp_mid=$row->tax_mid;
$kcp_tid=$row->tax_tid;

$tax_cnum1=substr($tax_cnum,0,3);
$tax_cnum2=substr($tax_cnum,3,2);
$tax_cnum3=substr($tax_cnum,5,5);

/*
if(strlen($tax_cnum)==0 || $taxsavetype=="N") {
	echo "<html></head><body onload=\"alert('�� ���θ������� ���ݿ����� �߱� ����� �������� �ʽ��ϴ�.\\n\\n���θ��� �����Ͻñ� �ٶ��ϴ�.');window.close()\"></body></html>";exit;
}
*/

$sql = "SELECT ordercode,tsdtime,tax_no,type,authno,id_info,mtrsno FROM tbltaxsavelist WHERE ordercode='".$ordercode."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	$date = date("Ymd",mktime(0,0,0,date("m"),date("d")-1,date("Y")));
	if($row->type=="Y" && $date."020000">=$row->tsdtime) {
		$query ="cashtype=QURY";
		$query.="&midbykcp=".$kcp_mid;
		$query.="&termid=".$kcp_tid;
		$query.="&cashipaddress1=203.238.36.160";
		$query.="&cashportno1=9981";
		$query.="&cashipaddress2=203.238.36.161";
		$query.="&cashportno2=9981";
		$query.="&tax_no=".$tax_no;

		$id_info=$row->id_info;
		$authno=$row->authno;

		$query.="&tsdtime=".substr($row->tsdtime,2);
		$query.="&id_info=".$row->id_info;
		$query.="&authno=".$row->authno;
		$query.="&mtrsno=".$row->mtrsno;

		//cgi ȣ��
		$host_url=getenv("HTTP_HOST");
		$host_cgi="/".RootPath.CashcgiDir."bin/cgiway.cgi";

		$resdata=SendSocketPost($host_url,$host_cgi,$query);
		$_taxdata=getParseTax($resdata);

		if(count($_taxdata)>0 && strlen($_taxdata["mrspc"])>0) {
			if($_taxdata["mrspc"]!="00") {
				$msg="���ݿ����� ��ȸ�� �����Ͽ����ϴ�.\\n\\n--------------------���л���--------------------\\n\\n".$_taxdata["resp_msg"];
				echo "<script>alert('".$msg."');window.close();</script>";
				exit;
			}
		} else {
			echo "<script>alert('���ݿ����� ���� ������ �����Ͽ����ϴ�.');window.close();</script>";
			exit;
		}

		include ($Dir."lib/taxsaveview.inc.php");

		exit;
	} else if($row->type=="Y") {
		$msg="�̹� ���ݿ������� �߱��ϼ̽��ϴ�.\\n\\n�߱޵� ���ݿ������� �߱� �� 1�� �Ŀ� ��üû Ȩ���������� Ȯ���� �����մϴ�.";
	} else {
		$msg="�̹� ���ݿ������� �߱޿�û�ϼ̽��ϴ�.";
	}
	if(strlen($msg)>0) {
		echo "<html></head><body onload=\"alert('".$msg."');window.close()\"></body></html>";exit;
	}
	exit;
}
mysql_free_result($result);

$mode=$_POST["mode"];

if($mode=="update") {
	$up_tr_code=$_POST["up_tr_code"];
	$up_gbn=$_POST["up_gbn"];
	$up_resno1=$_POST["up_resno1"];
	$up_resno2=$_POST["up_resno2"];
	$up_mobile1=$_POST["up_mobile1"];
	$up_mobile2=$_POST["up_mobile2"];
	$up_mobile3=$_POST["up_mobile3"];
	$up_comnum1=$_POST["up_comnum1"];
	$up_comnum2=$_POST["up_comnum2"];
	$up_comnum3=$_POST["up_comnum3"];

	$sql = "SELECT price,paymethod,bank_date,sender_name,sender_email,sender_tel,del_gbn FROM tblorderinfo ";
	$sql.= "WHERE ordercode='".$ordercode."' ";
	$result=mysql_query($sql,get_db_conn());
	if($row=mysql_fetch_object($result)) {
		$up_name=$row->sender_name;
		$up_email=$row->sender_email;
		$up_tel=$row->sender_tel;
		$up_productname=$productname;

		if(preg_match("/^(B|O|Q){1}/", $row->paymethod) && $row->deli_gbn!="C") {
			if($tax_rate==10) {
				$up_amt1=$row->price;
				$up_amt4=floor(($up_amt1/1.1)*0.1);
				$up_amt2=$up_amt1-$up_amt4;
				$up_amt3=0;
			} else {
				$up_amt1=$row->price;
				$up_amt2=0;
				$up_amt3=0;
				$up_amt4=0;
			}

			if($up_amt1<1) {
				echo "<html></head><body onload=\"alert('���űݾ��� 1�� �̻� ���� ���ݿ����� �߱��� �����մϴ�.');window.close()\"></body></html>";exit;
			}

			if($up_tr_code=="0") {	//����
				if($up_gbn=="0") {
					$up_id_info=$up_resno1.$up_resno2;	//�ֹι�ȣ
				} else {
					$up_id_info=$up_mobile1.$up_mobile2.$up_mobile3;	//�ڵ�����ȣ
				}
			} else {	//�����
				$up_id_info=$up_comnum1.$up_comnum2.$up_comnum3;	//����ڹ�ȣ
			}

			$tsdtime=date("YmdHis");
			$sql = "INSERT tbltaxsavelist SET ";
			$sql.= "ordercode		= '".$ordercode."', ";
			$sql.= "tsdtime			= '".$tsdtime."', ";
			$sql.= "tr_code			= '".$up_tr_code."', ";
			$sql.= "tax_no			= '".$tax_cnum."', ";
			$sql.= "id_info			= '".$up_id_info."', ";
			$sql.= "name			= '".$up_name."', ";
			$sql.= "tel				= '".$up_tel."', ";
			$sql.= "email			= '".$up_email."', ";
			$sql.= "productname		= '".$up_productname."', ";
			$sql.= "amt1			= ".$up_amt1.", ";
			$sql.= "amt2			= ".$up_amt2.", ";
			$sql.= "amt3			= ".$up_amt3.", ";
			$sql.= "amt4			= ".$up_amt4.", ";
			$sql.= "type			= 'N' ";
			mysql_query($sql,get_db_conn());

			if(mysql_error()) {
				echo "<html></head><body onload=\"alert('���ݿ����� �߱޿�û�� �����Ͽ����ϴ�.');history.go(-1)\"></body></html>";exit;
			} else {
				//�ڵ��߱�
				if($taxsavetype=="Y" && strlen($row->bank_date)==14 && $row->deli_gbn!="C") {
					$flag="Y";
					include($Dir."lib/taxsave.inc.php");
				}
				if(strlen($msg)>0) {
					echo "<html></head><body onload=\"alert('".$msg."');window.close()\"></body></html>";exit;
				} else {
					echo "<html></head><body onload=\"alert('���ݿ����� �����߱� ��û�� �Ϸ�Ǿ����ϴ�.\\n\\n�����ڰ� Ȯ�� �� �߱��ص帳�ϴ�.\\n\\n�߱޵� ���ݿ������� �߱� 1�� �Ŀ� Ȯ���� �����մϴ�.');window.close()\"></body></html>";exit;
				}
			}
		}
	}
	mysql_free_result($result);
	exit;
}
?>

<html>
<head>
<title>���ݿ����� �߱޽�û</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">

<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<style>
td	{font-family:"����,����";color:#4B4B4B;font-size:12px;line-height:17px;}
BODY,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

A:link    {color:#635C5A;text-decoration:none;}
A:visited {color:#545454;text-decoration:none;}
A:active  {color:#5A595A;text-decoration:none;}
A:hover  {color:#545454;text-decoration:underline;}
.input{font-size:12px;BORDER-RIGHT: #DCDCDC 1px solid; BORDER-TOP: #C7C1C1 1px solid; BORDER-LEFT: #C7C1C1 1px solid; BORDER-BOTTOM: #DCDCDC 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff;padding-top:2pt; padding-bottom:1pt; height:19px}
.select{color:#444444;font-size:12px;}
.textarea {border:solid 1;border-color:#e3e3e3;font-family:����;font-size:9pt;color:333333;overflow:auto; background-color:transparent}
</style>
<SCRIPT LANGUAGE="JavaScript">
<!--
window.resizeTo(272,270);

function CheckForm() {
	if(document.form1.up_tr_code[0].checked==true) {
		if(document.form1.up_gbn[0].checked==true) {
			if(document.form1.up_resno1.value.length==0 || document.form1.up_resno2.value.length==0 || document.form1.up_resno1.value.length!=6 || document.form1.up_resno2.value.length!=7) {
				alert("�ֹι�ȣ�� ��Ȯ�� �Է��ϼ���.");
				document.form1.up_resno1.focus();
				return;
			}
			if(!chkResNo(document.form1.up_resno1.value+"-"+document.form1.up_resno2.value)) {
				alert("�ֹι�ȣ �Է��� �߸��Ǿ����ϴ�.");
				document.form1.up_resno1.focus();
				return;
			}
		} else {
			mobile1=document.form1.up_mobile1;
			mobile2=document.form1.up_mobile2;
			mobile3=document.form1.up_mobile3;
			if(mobile1.value.length==0 || mobile2.value.length==0 || mobile3.value.length==0) {
				alert("�ڵ�����ȣ�� ��Ȯ�� �Է��ϼ���.");
				mobile1.focus();
				return;
			}
			if(!IsNumeric(mobile1.value)) {
				alert("�ڵ�����ȣ�� ���ڸ� �Է��ϼ���.");
				mobile1.focus();
				return;
			}
			if(!IsNumeric(mobile2.value)) {
				alert("�ڵ�����ȣ�� ���ڸ� �Է��ϼ���.");
				mobile2.focus();
				return;
			}
			if(!IsNumeric(mobile3.value)) {
				alert("�ڵ�����ȣ�� ���ڸ� �Է��ϼ���.");
				mobile3.focus();
				return;
			}
			if(mobile1.value=="010" || mobile1.value=="011" || mobile1.value=="016" || mobile1.value=="017" || mobile1.value=="018" || mobile1.value=="019") {
				if(mobile2.value.length<3 && mobile3.value.length<4) {
					alert("�ڵ�����ȣ�� ��Ȯ�� �Է��ϼ���.");
					mobile2.focus();
					return;
				}
			} else {
				alert("�ڵ�����ȣ�� ��Ȯ�� �Է��ϼ���.");
				mobile1.focus();
				return;
			}
		}
	} else {
		//����ڹ�ȣ üũ
		biz1=document.form1.up_comnum1.value;
		biz2=document.form1.up_comnum2.value;
		biz3=document.form1.up_comnum3.value;
		if(!chkBizNo(biz1+""+biz2+""+biz3)) {
			alert("����ڹ�ȣ �Է��� �߸��Ǿ����ϴ�.");
			document.form1.up_comnum1.focus();
			return;
		}
	}

	document.form1.mode.value="update";
	document.form1.submit();
}

function ViewLayer(layer) {
	if(layer=="layer2") {
		document.all["layer1"].style.display="none";
		document.all["layer2"].style.display="";
		document.form1.up_gbn[2].checked=true;

		document.form1.up_comnum1.disabled=false;
		document.form1.up_comnum2.disabled=false;
		document.form1.up_comnum3.disabled=false;

		document.form1.up_resno1.disabled=true;
		document.form1.up_resno2.disabled=true;
		document.form1.up_mobile1.disabled=true;
		document.form1.up_mobile2.disabled=true;
		document.form1.up_mobile3.disabled=true;

		document.form1.up_resno1.value="";
		document.form1.up_resno2.value="";
		document.form1.up_mobile1.value="";
		document.form1.up_mobile2.value="";
		document.form1.up_mobile3.value="";

	} else {
		document.all["layer2"].style.display="none";
		document.all["layer1"].style.display="";
		document.form1.up_gbn[0].checked=true;

		document.form1.up_comnum1.disabled=true;
		document.form1.up_comnum2.disabled=true;
		document.form1.up_comnum3.disabled=true;

		document.form1.up_comnum1.value="";
		document.form1.up_comnum2.value="";
		document.form1.up_comnum3.value="";

		document.form1.up_mobile1.disabled=true;
		document.form1.up_mobile2.disabled=true;
		document.form1.up_mobile3.disabled=true;

		document.form1.up_resno1.disabled=false;
		document.form1.up_resno2.disabled=false;
	}
}
function change_gbn(gbn) {
	if(gbn==0) {
		document.form1.up_resno1.disabled=false;
		document.form1.up_resno2.disabled=false;
		document.form1.up_mobile1.disabled=true;
		document.form1.up_mobile2.disabled=true;
		document.form1.up_mobile3.disabled=true;
		document.form1.up_mobile1.value="";
		document.form1.up_mobile2.value="";
		document.form1.up_mobile3.value="";
	} else if(gbn==1) {
		document.form1.up_mobile1.disabled=false;
		document.form1.up_mobile2.disabled=false;
		document.form1.up_mobile3.disabled=false;
		document.form1.up_resno1.disabled=true;
		document.form1.up_resno2.disabled=true;
		document.form1.up_resno1.value="";
		document.form1.up_resno2.value="";
	}
}

//-->
</SCRIPT>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" width="100%">
	<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
	<input type=hidden name=mode>
	<input type=hidden name=ordercode value="<?=$ordercode?>">
	<input type=hidden name=productname value="<?=urlencode($productname)?>">
	<tr>
		<td><IMG src="<?=$Dir?>images/common/taxsave_title.gif" border="0"></td>
	</tr>
	<tr>
		<td height="25" align="center"><b><font color="#FF3300"><INPUT type=radio name="up_tr_code" value="0" onclick="ViewLayer('layer1');" CHECKED id="idx_tr_code0" style="border:none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_tr_code0">����</LABEL>&nbsp;&nbsp;<INPUT type=radio name="up_tr_code" value="1" onclick="ViewLayer('layer2');" id="idx_tr_code1" style="border:none"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand; TEXT-DECORATION: none" onmouseout="style.textDecoration='none'" for="idx_tr_code1">�����</LABEL></font></b></td>
	</tr>
	<tr>
		<td>
			<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
				<TR>
					<TD><IMG SRC="<?=$Dir?>images/common/taxsave_table_01b.gif" border="0"></TD>
				</TR>
				<TR>
					<TD background="<?=$Dir?>images/common/taxsave_table_02b.gif" style="padding-right:5pt;padding-left:5pt;" align="center">
						<div id=layer1 style="width=230; margin-left:0;display:hide; display:block ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr height="25">
								<td width="36%"><INPUT type=radio name="up_gbn" value="0" id="idx_gbn0" style="BORDER:none;" onclick="change_gbn(0);" CHECKED><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_gbn0">�ֹι�ȣ</LABEL></td>
								<td width="64%"><INPUT type=text name="up_resno1" size="6" maxLength="6" onkeyup="strnumkeyup(this);" class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <INPUT type=text name="up_resno2" maxLength="7" size="7" onkeyup="strnumkeyup(this)" class="input" style="BACKGROUND-COLOR:#F7F7F7;"></td>
							</tr>
							<tr height="25">
								<td width="36%"><INPUT type=radio name="up_gbn" value="1" id="idx_gbn1" style="BORDER:none;" onclick="change_gbn(1);"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_gbn1">�޴���</td>
								<td width="64%"><INPUT type=text name="up_mobile1" size="3" maxLength="3" onkeyup="strnumkeyup(this);" disabled class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <INPUT type=text name="up_mobile2" size="4" maxLength="4" onkeyup="strnumkeyup(this);" disabled class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <INPUT type=text name="up_mobile3" size="4" maxLength="4" onkeyup="strnumkeyup(this);" disabled class="input" style="BACKGROUND-COLOR:#F7F7F7;"></td>
							</tr>
						</table>
						</div>
						<div id=layer2 style="width=230;margin-left:0;display:hide; display:none ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr height="25">
								<td width="36%"><INPUT type=radio name="up_gbn" value="2" id="idx_gbn2" style="BORDER:none;"><LABEL onmouseover="style.textDecoration='underline'" style="CURSOR: hand" onmouseout="style.textDecoration='none'" for="idx_gbn2">����ڹ�ȣ</LABEL></td>
								<td width="64%"><INPUT type=text name="up_comnum1" size="3" maxLength="3" onkeyup="strnumkeyup(this);" disabled class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <INPUT type=text name="up_comnum2" size="2" maxLength="2" onkeyup="strnumkeyup(this);" disabled class="input" style="BACKGROUND-COLOR:#F7F7F7;"> - <INPUT type=text name="up_comnum3" size="5" maxLength="5" onkeyup="strnumkeyup(this);" disabled class="input" style="BACKGROUND-COLOR:#F7F7F7;"></td>
							</tr>
						</table>
						</div>
					</TD>
				</TR>
				<TR>
					<TD><IMG SRC="<?=$Dir?>images/common/taxsave_table_03b.gif" border="0"></TD>
				</TR>
			</TABLE>
		</td>
	</tr>
	<tr>
		<td align="center"><A HREF="javascript:CheckForm()"><img src="<?=$Dir?>images/common/orderdetailpop_cashbill.gif" border="0" vspace="6"></a></td>
	</tr>
	</form>
</table>
</body>
</html>