<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

$gift_type=explode("|",$_data->gift_type);

$ordercode=$_POST["ordercode"];
$gift_price=$_POST["gift_price"];
$gift_mode=$_POST["gift_mode"];
$gift_multi=(strlen($gift_type[1])>0)?$gift_type[1]:"N";

if($gift_mode=="orderdetailpop" && strlen($_POST["gift_tempkey"])>0) {
	$gift_tempkey=$_POST["gift_tempkey"];
	$_ShopInfo->setGifttempkey($gift_tempkey);
	$_ShopInfo->Save();
}

if(strlen($_ShopInfo->getGifttempkey())==0) {
	echo "<html><head><title></title></head><body onload=\"window.close();\"></body></html>";
	exit;
}

$mode=$_POST["mode"];

if($mode=="result" && strlen($_ShopInfo->getGifttempkey())>0) {
	$seq=substr($_POST["seq"],0,-1);
	$gopt1=substr($_POST["gopt1"],0,-1);
	$gopt2=substr($_POST["gopt2"],0,-1);
	$gcnt=substr($_POST["gcnt"],0,-1);
	if(strlen($seq)>0) {
		$arseq=explode("|",$seq);
		$argopt1=explode("|",$gopt1);
		$argopt2=explode("|",$gopt2);
		$argcnt=explode("|",$gcnt);

		$sql = "SELECT COUNT(*) as cnt FROM tblorderproduct ";
		$sql.= "WHERE ordercode='".$ordercode."' AND tempkey='".$_ShopInfo->getGifttempkey()."' ";
		$result=mysql_query($sql,get_db_conn());
		$row=mysql_fetch_object($result);
		mysql_free_result($result);
		if ($row->cnt>0) {
			$k=0;
			for ($i=0;$i<count($arseq);$i++) {
				$argopt1[$i]=str_replace(","," : ",$argopt1[$i]);
				$argopt2[$i]=str_replace(","," : ",$argopt2[$i]);
				$productcode=sprintf("%'98d",$k)."GIFT";
				if($argcnt[$i]<=0) $argcnt[$i]=1;
				$sql = "SELECT * FROM tblgiftinfo WHERE gift_regdate='".$arseq[$i]."'";
				$result=mysql_query($sql,get_db_conn());
				if($row=mysql_fetch_object($result)) {
					$sql = "INSERT tblorderproduct SET ";
					$sql.= "ordercode	= '".$ordercode."', ";
					$sql.= "tempkey		= '".$_ShopInfo->getGifttempkey()."', ";
					$sql.= "productcode	= '".$productcode."', ";
					$sql.= "productname	= '����ǰ - ".addslashes($row->gift_name)."', ";
					$sql.= "opt1_name	= '".$argopt1[$i]."', ";
					$sql.= "opt2_name	= '".$argopt2[$i]."', ";
					$sql.= "quantity	= '".$argcnt[$i]."', ";
					$sql.= "price		= '0', ";
					$sql.= "date		= '".date("Ymd")."' ";
					mysql_query($sql,get_db_conn());
					if (strlen($row->gift_quantity)>0 && $row->gift_quantity>0) {
						mysql_query("UPDATE tblgiftinfo SET gift_quantity=gift_quantity-".$argcnt[$i]." WHERE gift_regdate='".$arseq[$i]."'",get_db_conn());
					}
					$k++;
				}
				mysql_free_result($result);
			}
		}
		$_ShopInfo->setGifttempkey("");
		$_ShopInfo->Save();
	}
	if ($gift_mode=="orderdetailpop") {
		echo "<html><head><title></title></head><body onload=\"alert('�ֹ��� �ּż� �����մϴ�.\\n\\n�����Ͻ� ����ǰ ������ ���Ϸ� �߼۵��� ������ �ֹ���ȸ���� Ȯ���Ͻ� �� �ֽ��ϴ�.');opener.history.go(0);window.close();\"></body></html>";
	} else {
		echo "<html><head><title></title></head><body onload=\"alert('�ֹ��� �ּż� �����մϴ�.\\n\\n�����Ͻ� ����ǰ ������ ���Ϸ� �߼۵��� ������ �ֹ���ȸ���� Ȯ���Ͻ� �� �ֽ��ϴ�.');window.close();\"></body></html>";
	}
	exit;
}

?>

<html>
<head>
<title>����ǰ ����</title>
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
function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 27;

	window.resizeTo(oWidth,600);
}

<? if($gift_multi=="Y") { ?>
var totform=0;
var totprice=<?=$gift_price?>;
function CheckCalculate(frm,gbn) {
	objmp=document.all.maxprice;
	tmpprice=0;
	check_flag="";
	for(i=0;i<totform;i++) {
		form=document["tempform"+i];
		if (form.check.checked==true) {
			form.gcnt.disabled=false;
			if (isNaN(form.gcnt.value) || parseInt(form.gcnt.value)==0 ||form.gcnt.value.length==0) {
				alert ("����ǰ�� ������ 0���� ū ���ڷθ� �Է��ϼž� �մϴ�.");
				form.gcnt.focus();
				return false;
			}
			if (form.gquantity.value.length>0 && parseInt(form.gquantity.value)<parseInt(form.gcnt.value)) {
				alert ("����ǰ�� ��� �����մϴ� ������ �ٿ��� ������ �ּ���.");
				form.gcnt.focus();
				return false;
			}
			if (form.glimit.value>0 && parseInt(form.glimit.value)<parseInt(form.gcnt.value)) {
				alert ("������ ����ǰ�� �ִ� "+form.glimit.value+"�� ������ �����Ͻ� �� �ֽ��ϴ�. ������ �ٿ� �ּ���.");
				form.gcnt.focus();
				return false;
			}
			tmpprice=tmpprice+(parseInt(form.gprice.value)*parseInt(form.gcnt.value));
			check_flag+=i+",";
		} else {
			form.gcnt.disabled=true;
		}
	}

	if (tmpprice>totprice) {
		alert ("���ð����� ��������Ʈ�� "+totprice+"�� �����Դϴ�.\n���� �����Ͻ� ������Ʈ�� "+tmpprice+"�� �Դϴ�.\n�ٽ� ������ �ּ���..");
		if (typeof(frm)=="object") {
			if (gbn=="1") {
				objmp.value=parseInt(objmp.value)+(parseInt(frm.gprice.value)*parseInt(frm.pre_cnt.value));
			}
			frm.gcnt.selectedIndex=0;
			frm.check.checked=false;
			frm.pre_flag.value="N";
			frm.gcnt.focus();
			frm.gcnt.disabled=true;
			return;
		} else {
			form.check.checked=false;
			form.gcnt.focus();
			return false;
		}
	}
	if (typeof(frm)=="object") {
		SumPrice(frm,gbn);
	} else {
		if (check_flag.length==0) {
			alert ("����ǰ�� 1���̻� �����ϼž� �մϴ�.");
			return false;
		}
		return check_flag;
	}
}

function SumPrice(frm,gbn) {
	if (gbn=="0") {
		if (frm.check.checked==true && frm.pre_flag.value=="N") {
			document.all.maxprice.value=parseInt(document.all.maxprice.value)-(parseInt(frm.gprice.value)*parseInt(frm.gcnt.value));
			frm.pre_flag.value="Y";
		} else if (frm.check.checked==false && frm.pre_flag.value=="Y") {
			document.all.maxprice.value=parseInt(document.all.maxprice.value)+(parseInt(frm.gprice.value)*parseInt(frm.gcnt.value));
			frm.pre_flag.value="N";
		}
	} else if (gbn=="1") {
		if (frm.check.checked==true) {
			document.all.maxprice.value=parseInt(document.all.maxprice.value)-(parseInt(frm.gprice.value)*(parseInt(frm.gcnt.value)-parseInt(frm.pre_cnt.value)));
		} else {
			alert ("������ �����Ͻñ� ���� ���� �����ϱ⸦ üũ�ϼ���");
			frm.gcnt.selectedIndex=0;
		}
		frm.pre_cnt.value=frm.gcnt.value;
	}
}

function CheckMultiForm() {
	if (CheckCalculate("","0")!=false) {
		if (confirm("�����Ͻ� ����ǰ���� �����ðڽ��ϱ�?")) {
			check_flag=check_flag.substr(0,check_flag.length-1);
			check_flag=check_flag.split(",");
			document.form1.seq.value="";
			document.form1.gopt1.value="";
			document.form1.gopt2.value="";
			document.form1.gcnt.value="";
			for (i=0 ; i<check_flag.length ; i++) {
				form=document["tempform"+check_flag[i]];
				document.form1.seq.value=document.form1.seq.value+form.seq.value+"|";
				if (form.option1)
					document.form1.gopt1.value=document.form1.gopt1.value+form.option1.options[form.option1.selectedIndex].value+"|";
				else
					document.form1.gopt1.value=document.form1.gopt1.value+"|";
				if (form.option2)
					document.form1.gopt2.value=document.form1.gopt2.value+form.option2.options[form.option2.selectedIndex].value+"|";
				else
					document.form1.gopt2.value=document.form1.gopt2.value+"|";
				document.form1.gcnt.value=document.form1.gcnt.value+form.gcnt.value+"|";
			}
			document.form1.mode.value="result";
			document.form1.submit();
		}
	}
}
<? } else { ?>
function CheckForm(idx) {
	form=document["tempform"+idx];
	if (confirm("�ѹ� ������ ����ǰ�� �����Ͻ� �� �����ϴ�.\n"+form.gname.value+"(��)�� �����Ͻðڽ��ϱ�?")) {
		document.form1.seq.value=form.seq.value+"|";
		if(form.option1) document.form1.gopt1.value=form.option1.value+"|";
		else document.form1.gopt1.value="|";
		if(form.option2) document.form1.gopt2.value=form.option2.value+"|";
		else document.form1.gopt2.value="|";
		document.form1.gcnt.value="1|";
		document.form1.mode.value="result";

		document.form1.submit();
	}
}
<? } ?>

//-->
</SCRIPT>
</head>

<body topmargin=0 leftmargin=0 rightmargin=0 marginheight=0 marginwidth=0 onLoad="PageResize();">
<center>
<table border="0" cellpadding="0" cellspacing="0" width="740" style="table-layout:fixed;" id="table_body">
<tr><td><IMG src="<?=$Dir?>images/common/gift_choice_title.gif" border="0"></td></tr>
<tr>
	<td align="center">
	<table border="0" cellpadding="0" cellspacing="0" width="96%">
	<? if($gift_multi=="Y") { ?>
	<tr><td height="5"></td></tr>
	<tr>
		<td align="right" style="padding-bottom:3"><A HREF="javascript:CheckMultiForm()"><img src="<?=$Dir?>images/common/gift_choicebtn.gif" border=0></A></td>
	</tr>
	<? } else { ?>
	<tr><td height="10"></td></tr>
	<? } ?>
	<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<col width="33%"></col>
		<col width="34%"></col>
		<col width="33%"></col>
<?
		$imagepath=$Dir.DataDir."shopimages/etc/";
		if($gift_multi=="Y") {
			echo "<tr>\n";
			echo "	<td colspan=\"3\">\n";
			echo "	<table cellpadding=\"1\" cellspacing=\"1\" width=\"100%\" bgcolor=\"#EBEBEB\">\n";
			echo "	<tr>\n";
			echo "		<td height=\"30\" align=\"center\" bgcolor=\"#F9F9F9\"><b><font color=\"black\">���� ������ ��������Ʈ�� <input type=text name=maxprice size=\"7\" value=\"".$gift_price."\" style=\"HEIGHT:15px;font-size:10pt;color:#FF6600;font-weight:bold;text-align:right;border:0;background:#F9F9F9;\" readonly>�� �Դϴ�.</b></font></td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</td>\n";
			echo "</tr>\n";
			echo "<tr><td height=\"10\"></td></tr>\n";
			$sql = "SELECT * FROM tblgiftinfo WHERE gift_startprice<=".$gift_price." ";
			$sql.= "AND (gift_quantity is NULL OR gift_quantity>0) ORDER BY gift_startprice ";
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			while($row=mysql_fetch_object($result)) {
				$row->gift_name = str_replace("\"","",$row->gift_name);
				if($i==0) echo "<tr>\n";
				else if($i%3==0) echo "</tr><tr><td colspan=\"3\" height=\"20\"></td></tr><tr>\n";
				echo "<td align=\"center\">\n";
				echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" background=\"".$Dir."images/common/gift_choice_list_bg.gif\">\n";
				echo "<form name=\"tempform".$i."\" method=post>\n";
				echo "<input type=hidden name=seq value=\"".$row->gift_regdate."\">\n";
				echo "<input type=hidden name=gname value=\"".$row->gift_name."\">\n";
				echo "<input type=hidden name=gprice value=\"".$row->gift_startprice."\">\n";
				echo "<input type=hidden name=gquantity value=\"".$row->gift_quantity."\">\n";
				echo "<input type=hidden name=glimit value=\"".$row->gift_limit."\">\n";
				echo "<input type=hidden name=pre_flag value=\"N\">\n";
				echo "<input type=hidden name=pre_cnt value=\"1\">\n";
				echo "<tr>\n";
				echo "	<td><img src=\"".$Dir."images/common/gift_choice_list_top.gif\"></td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<td>\n";
				echo "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed\">\n";
				echo "	<tr><td align=\"center\"><B>".$row->gift_name." <font color=\"#FF6600\">(".number_format($row->gift_startprice)." ��)</font></B></td></tr>\n";
				echo "	</table>\n";
				echo "	</td>\n";
				echo "</tr>\n";
				echo "<tr><td align=\"center\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\"><tr><td height=\"1\" bgcolor=\"#f4f4f4\"></td></tr></table></td></tr>\n";
				echo "<tr><td height=\"10\"></td></tr>\n";
				if (strlen($row->gift_image)>0 && file_exists($imagepath.$row->gift_image)) {
					echo "<tr><td align=\"center\"><img src=\"".$imagepath.$row->gift_image."\" border=\"0\"></td></tr>\n";
				} else {
					echo "<tr><td align=\"center\"><img src=\"".$Dir."images/no_img.gif\" border=\"0\"></td></tr>\n";
				}
				echo "<tr><td height=\"10\"></td></tr>\n";

				if (strlen($row->gift_option1)>0 || strlen($row->gift_option2)>0) {
					echo "<tr><td align=\"center\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\"><tr><td height=\"1\" bgcolor=\"#f4f4f4\"></td></tr></table></td></tr>\n";
					echo "<tr><td height=\"5\"></td></tr>\n";
					echo "<tr>\n";
					echo "	<td align=\"center\">\n";
					echo "	<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\">\n";
					if (strlen($row->gift_option1)>0) {
						$gift_option1=explode(",",$row->gift_option1);
						echo "	<tr>\n";
						echo "		<td align=\"right\">".$gift_option1[0]." :&nbsp;</td>\n";
						echo "		<td>\n";
						echo "		<select name=\"option1\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\">\n";
						for ($j=1;$j<count($gift_option1);$j++) {
							echo "		<option value=\"".$gift_option1[0].",".$gift_option1[$j]."\" style=\"color:FFFFFF\">".$gift_option1[$j]."</option>\n";
						}
						echo "		</select>\n";
						echo "		</td>\n";
						echo "	</tr>\n";
					}
					if (strlen($row->gift_option2)>0) {
						$gift_option2=explode(",",$row->gift_option2);
						echo "	<tr>\n";
						echo "		<td align=\"right\">".$gift_option2[0]." :&nbsp;</td>\n";
						echo "		<td>\n";
						echo "		<select name=\"option2\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\">\n";
						for ($j=1;$j<count($gift_option2);$j++) {
							echo "		<option value=\"".$gift_option2[0].",".$gift_option2[$j]."\" style=\"color:FFFFFF\">".$gift_option2[$j]."</option>\n";
						}
						echo "		</select>\n";
						echo "		</td>\n";
						echo "	</tr>\n";
					}
					echo "	</table>\n";
					echo "	</td>\n";
					echo "</tr>\n";
				}
				if (strlen($row->gift_option1)>0 || strlen($row->gift_option2)>0) {
					echo "<tr><td height=\"5\"></td></tr>\n";
				}

				echo "<tr>\n";
				echo "	<td align=\"center\">\n";
				echo "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\">\n";
				echo "	<tr>\n";
				echo "		<TD height=\"30\" bgcolor=\"#F3F3F3\" style=\"border-top-width:1pt; border-bottom-width:1pt; border-top-color:rgb(222,222,222); border-bottom-color:rgb(222,222,222); border-top-style:solid; border-bottom-style:solid;\">\n";
				echo "		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
				echo "		<tr>\n";
				echo "			<td width=\"50%\" align=\"right\" style=\"padding-right:7px;\"><input type=checkbox name=\"check\" value=\"Y\" onclick=\"CheckCalculate(this.form,'0')\"> �����ϱ�</td>\n";
				echo "			<td width=\"50%\" style=\"padding-left:7px;\">���� : ";
				echo "			<select name=\"gcnt\" onchange=\"CheckCalculate(this.form,'1')\" disabled style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\">\n";
				for($j=1;$j<=50;$j++) echo "		<option value=\"".$j."\" style=\"color:FFFFFF\">".$j."</option>\n";
				echo "			</select>\n";
				echo "			</td>\n";
				echo "		</tr>\n";
				echo "		</table>\n";
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "	</table>\n";
				echo "	</td>\n";
				echo "</tr>\n";

				echo "<tr>\n";
				echo "	<td><img src=\"".$Dir."images/common/gift_choice_list_bottom.gif\"></td>\n";
				echo "</tr>\n";
				echo "</form>\n";
				echo "</table>\n";
				echo "</td>\n";
				$i++;
			}
			mysql_free_result($result);
			if ($i>0 && $i%3==0) echo "</tr>\n";
			else if($i>0 && $i%3!=0) {
				for($j=0;$j<=($i%3);$j++) {
					echo "<td align=\"center\"></td>\n";
				}
				echo "</tr>\n";
			}
			echo "<script>totform=".$i."</script>\n";
		} else {
			$sql = "SELECT * FROM tblgiftinfo WHERE gift_startprice<=".$gift_price." AND gift_endprice>".$gift_price." ";
			$sql.= "AND (gift_quantity is NULL OR gift_quantity>0) ORDER BY gift_regdate ";
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			while($row=mysql_fetch_object($result)) {
				$row->gift_name = str_replace("\"","",$row->gift_name);
				if($i==0) echo "<tr>\n";
				else if($i%3==0) echo "</tr><tr><td colspan=\"3\" height=\"20\"></td></tr><tr>\n";
				echo "<td align=\"center\">\n";
				echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" height=\"100%\" background=\"".$Dir."images/common/gift_choice_list_bg.gif\">\n";
				echo "<form name=\"tempform".$i."\" method=post>\n";
				echo "<input type=hidden name=seq value=\"".$row->gift_regdate."\">\n";
				echo "<input type=hidden name=gname value=\"".$row->gift_name."\">\n";
				echo "<tr>\n";
				echo "	<td><img src=\"".$Dir."images/common/gift_choice_list_top.gif\"></td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "	<td align=\"center\">\n";
				echo "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"table-layout:fixed\">\n";
				echo "	<tr><td align=\"center\"><B>".$row->gift_name."</B></td></tr>\n";
				echo "	</table>\n";
				echo "	</td>\n";
				echo "</tr>\n";
				echo "<tr><td align=\"center\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\"><tr><td height=\"1\" bgcolor=\"#f4f4f4\"></td></tr></table></td></tr>\n";
				echo "<tr><td height=\"10\"></td></tr>\n";
				if (strlen($row->gift_image)>0 && file_exists($imagepath.$row->gift_image)) {
					echo "<tr><td align=\"center\"><img src=\"".$imagepath.$row->gift_image."\" border=\"0\"></td></tr>\n";
				} else {
					echo "<tr><td align=\"center\"><img src=\"".$Dir."images/no_img.gif\" border=\"0\"></td></tr>\n";
				}
				echo "<tr><td height=\"10\"></td></tr>\n";

				if (strlen($row->gift_option1)>0 || strlen($row->gift_option2)>0) {
					echo "<tr><td align=\"center\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\"><tr><td height=\"1\" bgcolor=\"#f4f4f4\"></td></tr></table></td></tr>\n";
					echo "<tr><td height=\"5\"></td></tr>\n";
					echo "<tr>\n";
					echo "	<td align=\"center\">\n";
					echo "	<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
					if (strlen($row->gift_option1)>0) {
						$gift_option1=explode(",",$row->gift_option1);
						echo "	<tr>\n";
						echo "		<td align=\"right\">".$gift_option1[0]." :&nbsp;</td>\n";
						echo "		<td>\n";
						echo "		<select name=\"option1\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\">\n";
						for ($j=1;$j<count($gift_option1);$j++) {
							echo "		<option value=\"".$gift_option1[0].",".$gift_option1[$j]."\" style=\"color:#FFFFFF;\">".$gift_option1[$j]."</option>\n";
						}
						echo "		</select>\n";
						echo "		</td>\n";
						echo "	</tr>\n";
					}
					if (strlen($row->gift_option2)>0) {
						$gift_option2=explode(",",$row->gift_option2);
						echo "	<tr>\n";
						echo "		<td align=\"right\">".$gift_option2[0]." :&nbsp;</td>\n";
						echo "		<td>\n";
						echo "		<select name=\"option2\" style=\"font-size:11px;background-color:#404040;letter-spacing:-0.5pt;\">\n";
						for ($j=1;$j<count($gift_option2);$j++) {
							echo "		<option value=\"".$gift_option2[0].",".$gift_option2[$j]."\" style=\"color:#FFFFFF;\">".$gift_option2[$j]."</option>\n";
						}
						echo "		</select>\n";
						echo "		</td>\n";
						echo "	</tr>\n";
					}
					echo "	</table>\n";
					echo "	</td>\n";
					echo "</tr>\n";
				}
				if (strlen($row->gift_option1)>0 || strlen($row->gift_option2)>0) {
					echo "<tr><td height=\"5\"></td></tr>\n";
					echo "<tr><td align=\"center\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"90%\"><tr><td height=\"1\" bgcolor=\"#f4f4f4\"></td></tr></table></td></tr>\n";
					echo "<tr><td height=\"10\"></td></tr>\n";
				}
				echo "<tr>\n";
				echo "	<td align=\"center\"><A HREF=\"javascript:CheckForm(".$i.");\"><img src=\"".$Dir."images/common/gift_choicebtn.gif\" border=0></A></td>\n";
				echo "</tr>\n";

				echo "<tr>\n";
				echo "	<td><img src=\"".$Dir."images/common/gift_choice_list_bottom.gif\"></td>\n";
				echo "</tr>\n";
				echo "</form>\n";
				echo "</table>\n";
				echo "</td>\n";
				$i++;
			}
			mysql_free_result($result);
			if ($i>0 && $i%3==0) echo "</tr>\n";
			else if($i>0 && $i%3!=0) {
				for($j=0;$j<=($i%3);$j++) {
					echo "<td align=\"center\"></td>\n";
				}
				echo "</tr>\n";
			}
		}
?>
		</table>
		</td>
	</tr>
	<? if($gift_multi=="Y") { ?>
	<tr>
		<td align=right><A HREF="javascript:CheckMultiForm()"><img src="<?=$Dir?>images/common/gift_choicebtn.gif" border="0"></A></td>
	</tr>
	<tr><td height="20"></td></tr>
	<? } ?>
	</table>
	</td>
</tr>
<tr><td height="20"></td></tr>

<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode>
<input type=hidden name=ordercode value="<?=$ordercode?>">
<input type=hidden name=gift_mode value="<?=$gift_mode?>">
<input type=hidden name=seq>
<input type=hidden name=gopt1>
<input type=hidden name=gopt2>
<input type=hidden name=gcnt>
</form>

</table>
</center>
</body>
</html>