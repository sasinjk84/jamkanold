<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

$ordercode=$_POST["ordercode"];
$tblgbn="temp";

$type=$_POST["type"];
$mode=$_POST["mode"];

$order_msg=$_POST["order_msg"];

$rescode=$_POST["rescode"];
$pay_admin_proc=$_POST["pay_admin_proc"];

if($type=="sort") {
	$sort=$_POST["sort"];
}

if($ordercode==NULL) {
	echo "<script>alert('�߸��� �����Դϴ�.');window.close();</script>";
	exit;
}

$sql="SELECT * FROM tblorderinfo".$tblgbn." WHERE ordercode='".$ordercode."'";
$result=mysql_query($sql,get_db_conn());
$_ord=mysql_fetch_object($result);
mysql_free_result($result);
if(!$_ord) {
	echo "<script>alert(\"�ش� �ֹ������� �������� �ʽ��ϴ�.\");window.close();</script>";
	exit;
}
$curdate = date("YmdHi",mktime(date("H"),date("i")-30,0,date("m"),date("d"),date("Y")));

$delicomlist=array();
//$sql="SELECT * FROM tbldelicompany ORDER BY company_name ";
$sql="SELECT * FROM tbldelicompany ORDER BY code ";

$result=mysql_query($sql,get_db_conn());
while($row=mysql_fetch_object($result)) {
	$delicomlist[]=$row;
}
mysql_free_result($result);

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>�ֹ��󼼳��� ����</title>
<link rel="stylesheet" href="style.css" type="text/css">
<STYLE TYPE="text/css"> 
<!--
body { font-size: 9pt}
td { font-size: 9pt; line-height: 15pt}
tr { font-size: 9pt}
.break {page-break-before: always;}

@media print {
	.page_screen { display:none; }
	.page_print { display:inline; }
}
@media screen {
	.page_screen { display:inline; }
	.page_print { display:none; }
}

--> 
</STYLE> 
<SCRIPT LANGUAGE="JavaScript">
<!--
//document.onkeydown = CheckKeyPress;
//document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;

	if(ekey == 38 || ekey == 40 || ekey == 112 || ekey ==17 || ekey == 18 || ekey == 25 || ekey == 122 || ekey == 116) {
	   event.keyCode = 0;
	   return false;
	 }
}

function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 30;
	//var oHeight = document.all.table_body.clientHeight + 55;
	var oHeight=650;

	window.resizeTo(oWidth,oHeight);
}

var countdeli=countdelinum=countdecan=countbank=countbacan=countvican=counttrcan=countokcan=countokhold=0;

function PagePrint(){
	if(confirm("�ֹ��󼼳����� ����Ʈ �Ͻðڽ��ϱ�?")) {
		print();
	}
}

function ProductMouseOver(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.primage"+cnt);
	obj._tid = setTimeout("ProductViewImage(WinObj)",200);
}
function ProductViewImage(WinObj) {
	WinObj.style.visibility = "visible";
}
function ProductMouseOut(Obj) {
	obj = event.srcElement;
	Obj = document.getElementById(Obj);
	Obj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}

function DeliSearch(deli_url){
	window.open(deli_url,"�������","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizeble=yes,copyhistory=no,width=600,height=550");
}

function EtcMouseOver(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.etcdtl"+cnt);
	obj._tid = setTimeout("EtcView(WinObj)",200);
}
function EtcView(WinObj) {
	WinObj.style.visibility = "visible";
}
function EtcMouseOut(cnt) {
	obj = event.srcElement;
	WinObj=eval("document.all.etcdtl"+cnt);
	WinObj.style.visibility = "hidden";
	clearTimeout(obj._tid);
}

//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="PagePrint();return false;" onLoad="PageResize();">

<table border=0 cellpadding=0 cellspacing=0 width=650 style="table-layout:fixed;" id=table_body>
<tr class="page_screen">
	<td width=100% align=center>
	<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr><td height=10></td></tr>
	<tr>
		<td align=right style="padding-right:2pt">
		<A HREF="javascript:window.close()">[�� ��]</A>
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td align=center style="padding-left:3">
	<!-- �ֹ����� ���� -->
	<table border=1 cellpadding=0 cellspacing=0 width=100% bordercolorlight=#E2B892 bordercolordark=#ffffff style="table-layout:fixed">
	<col width=25></col>
	<col width=></col>
	<col width=95></col>
	<col width=30></col>
	<col width=45></col>
	<col width=55></col>
	<tr bgcolor=#efefef>
		<td align=center>No</td>
		<td align=center>��ǰ��</td>
		<td align=center>���û���</td>
		<td align=center>����</td>
		<td align=center>������</td>
		<td align=center>����</td>
	</tr>
<?
	$colspan=6;
	$sql = "SELECT * FROM tblorderproduct".$tblgbn." WHERE ordercode='".$_ord->ordercode."' ";
	if(strlen($sort)>0) $sql.="ORDER BY ".$sort." ";
	$result=mysql_query($sql,get_db_conn());
	$sumquantity=0;
	$totalprice=0;
	$in_reserve=0;
	$cnt=0;
	$taxsaveprname="";
	while($row=mysql_fetch_object($result)) {
		if(ereg("^(COU)([0-9]{8})(X)$",$row->productcode)) {
			if($row->price!=0 && $row->price!=NULL) {
				$etcdata[]=$row;
				continue;
			}
		} else if(ereg("^(9999999999)([0-9]{1})(X|R)$",$row->productcode)) {
			$etcdata[]=$row;
			continue;
		} else {
			$prdata[]=$row;
		}

		$taxsaveprname.=$row->productname.",";
		$cnt++;
		$sumprice=$row->quantity*$row->price;
		$reserve=$row->quantity*$row->reserve;
		$tempopt1=$row->opt1_name;
		if($row->productcode=="99999999999R") $norecan="Y";
		if ($row->productcode!="99999999999X" && substr($row->productcode,0,3)!="COU" && $row->productcode!="99999999999R") {
			$sumquantity+=$row->quantity;
		}
		$in_reserve+=$reserve;
		$totalprice+=$sumprice;

		$optvalue="";
		if(ereg("^(\[OPTG)([0-9]{3})(\])$",$row->opt1_name)) {
			$optioncode=$row->opt1_name;
			$row->opt1_name="";
			$sql = "SELECT opt_name FROM tblorderoption".$tblgbn." WHERE ordercode='".$_ord->ordercode."' AND productcode='".$row->productcode."' ";
			$sql.= "AND opt_idx='".$optioncode."' ";
			$result2=mysql_query($sql,get_db_conn());
			if($row2=mysql_fetch_object($result2)) {
				$optvalue=$row2->opt_name;
			}
			mysql_free_result($result2);
		}
		echo "<tr bgcolor=#FFFFFF>\n";
		echo "	<td align=center style=\"font-size:8pt\"><font color=#878787>".$cnt."</td>\n";

		if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.gif")) $file=$row->productcode."3.gif";
		else if(file_exists($Dir.DataDir."shopimages/product/".$row->productcode."3.jpg")) $file=$row->productcode."3.jpg";
		else $file="NO";
      
		if($file!="NO") {
			echo "	<td style=\"font-size:8pt;padding:2,5;line-height:10pt\">\n";
			echo "	<span style=\"line-height:10pt\" onMouseOver='ProductMouseOver($cnt)' onMouseOut=\"ProductMouseOut('primage".$cnt."');\">".$row->productname."";
			echo "	<div id=primage".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
			echo "	<table border=0 cellspacing=1 cellpadding=0 bgcolor=#000000 width=170>\n";
			echo "	<tr bgcolor=#FFFFFF>\n";
			echo "		<td align=center width=100% height=150><img name=bigimgs src=\"".$Dir.DataDir."shopimages/product/".$file."\"></td>\n";
			echo "	</tr>\n";
			echo "	<tr bgcolor=#FFFFFF>\n";
			echo "		<td height=54 bgcolor=#f5f5f5><table border=0><tr><td style=\"line-height:12pt\">���� �ֹ���,����/�̵� ��ǰ�� �̹����� ��ġ���� ������ ������ <font color=red>�����Ͽ� ���</font>�ٶ��ϴ�.</td></tr></table></td>\n";
			echo "	</tr>\n";
			echo "	</table>\n";
			echo "	</div>\n";
			if(strlen($row->addcode)>0) echo "<br><font color=blue>Ư��ǥ�� : ".$row->addcode."</font>";
			if(strlen($optvalue)>0) echo "<br><font color=red>�ɼǻ��� : ".$optvalue."</font>";
			echo "	</td>\n";
		} else {
			echo "	<td style=\"font-size:8pt;padding:2,5;line-height:10pt\">";
			echo $row->productname;
			if(strlen($row->addcode)>0) echo "<br><font color=blue>Ư��ǥ�� : ".$row->addcode."</font>";
			if(strlen($optvalue)>0) echo "<br><font color=red>�ɼǻ��� : ".$optvalue."</font>";
			echo "	</td>\n";
		}

		echo "	<td style=\"font-size:8pt;padding:2,5;line-height:11pt\">";
		echo (strlen($row->opt1_name)>0?$row->opt1_name."<br>":"&nbsp;");
		if(strlen($row->opt2_name)>0) echo $row->opt2_name;
		echo "	</td>\n";
		if ($row->productcode=="99999999999X" || substr($row->productcode,0,3)=="COU" || $row->productcode=="99999999999R") {
			echo "	<td>&nbsp;</td>\n";
		} else {
			echo "	<td style=\"font-size:8pt\" align=center".($row->quantity>1?" bgcolor=#FDE9D5><font color=#000000><b>":">").$row->quantity."</td>\n";
		}
		echo "	<td align=right style=\"font-size:8pt\">".(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X" && substr($row->productcode,-4)!="GIFT"?number_format($reserve)."&nbsp;":"&nbsp;")."</td>\n";
		echo "	<td align=right style=\"font-size:8pt\">".(substr($row->productcode,-4)!="GIFT"?number_format($sumprice)."&nbsp;":"&nbsp;")."</td>\n";
		echo "</tr>\n";
	}
	mysql_free_result($result);

	echo "<tr height=30 bgcolor=#efefef>\n";
	echo "	<td align=center colspan=".($_ord->paymethod!="B" || $mode!="update"?$colspan:($colspan+1))."><B>�߰����/����/��������</B></td>";
	echo "</tr>\n";

	if(count($etcdata)>0) {
		for($j=0;$j<count($etcdata);$j++) {
			$cnt++;
			$sumprice=$etcdata[$j]->price;
			$reserve=$etcdata[$j]->reserve;
			$in_reserve+=$reserve;
			$totalprice+=$sumprice;
			echo "<tr>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td style=\"font-size:8pt;padding:2,5;line-height:10pt\">".$etcdata[$j]->productname." <span class=\"page_screen\"><A style=\"cursor:hand\" onMouseOver='EtcMouseOver($cnt)' onMouseOut=\"EtcMouseOut($cnt);\"><font style=\"font-size:8pt;color:#FC5F29\">more</font></A>";
			echo "	<div id=etcdtl".$cnt." style=\"position:absolute; z-index:100; visibility:hidden;\">\n";
			echo "	<table border=0 cellpadding=0 cellspacing=0 width=300 bgcolor=#A47917>\n";
			echo "	<tr><td align=center style=\"color:#FFFFFF;padding:5\"><B>###### �ش� ��ǰ�� ######</B></td></tr>\n";
			echo "	<tr><td style=\"font-size:8pt;color:#FFFFFF;padding:10;padding-top:0;line-height:11pt\">".$etcdata[$j]->order_prmsg."</td></tr>\n";
			echo "	</table>\n";
			echo "	</div>\n";
			echo "	</span>\n";
			echo "	</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td align=right style=\"font-size:8pt\">";
			if($etcdata[$j]->vender>0) {
				if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X" && $etcdata[$j]->productcode!="99999999990X" && $etcdata[$j]->productcode!="99999999997X") {
					echo ($reserve>0?number_format($reserve):"")."&nbsp;";
				} else {
					echo "&nbsp;";
				}
			} else {
				if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X" && $etcdata[$j]->productcode!="99999999990X" && $etcdata[$j]->productcode!="99999999997X") {
					echo ($reserve>0?number_format($reserve):"")."&nbsp;";
				} else {
					echo "&nbsp;";
				}
			}
			echo "	</td>\n";
			echo "	<td align=right style=\"font-size:8pt\">".(substr($etcdata[$j]->productcode,-4)!="GIFT"?number_format($sumprice)."&nbsp;":"&nbsp;")."</td>\n";

			echo "</tr>\n";
		}
	}

	if($_ord) {
		if (strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
			$sql = "SELECT memo,group_code FROM tblmember WHERE id='".$_ord->id."' ";
			$result=mysql_query($sql,get_db_conn());
			if ($row=mysql_fetch_object($result)) {
				$usermemo=$row->memo;
				$group_code=$row->group_code;
			}
			mysql_free_result($result);

			if(strlen($group_code)>0) {
				$sql = "SELECT group_name FROM tblmembergroup WHERE group_code='".$group_code."' ";
				$result=mysql_query($sql,get_db_conn());
				if($row=mysql_fetch_object($result)) {
					$group_name = $row->group_name;
				}
				mysql_free_result($result);
			} 
		}

		$dc_price=(int)$_ord->dc_price;
		$salemoney=0;
		$salereserve=0;
		if($dc_price<>0) {
			if($dc_price>0) $salereserve=$dc_price;
			else $salemoney=-$dc_price;
			if (strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
				$sql = "SELECT b.group_name FROM tblmember a, tblmembergroup b ";
				$sql.= "WHERE a.id='".$_ord->id."' AND b.group_code=a.group_code AND MID(b.group_code,1,1)!='M'";
				$result=mysql_query($sql,get_db_conn());
				if($row=mysql_fetch_object($result)) {
					$group_name=$row->group_name;
				}
				mysql_free_result($result);
			}
			echo "<tr bgcolor=#FFFFE6>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td style=\"font-size:8pt;padding:2,5\"><font color=red>�׷�ȸ�� ����/���� : ".$group_name."</font></td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td align=right style=\"font-size:8pt\">".($salereserve>0?number_format($salereserve)."&nbsp;":"&nbsp;")."</td>\n";
			echo "	<td align=right style=\"font-size:8pt\">".($salemoney>0?"-".number_format($salemoney)."&nbsp;":"&nbsp;")."</td>\n";
			echo "</tr>\n";
			$in_reserve+=$salereserve;
		}
		if($_ord->reserve>0) {
			echo "<tr bgcolor=#F5F5F5>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td style=\"font-size:8pt;padding:2,5\"><font color=#0054A6>�����ݻ���</font></td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td align=right style=\"font-size:8pt\">-".number_format($_ord->reserve)."&nbsp;</td>\n";
			echo "</tr>\n";
		}
		$totalprice=$totalprice+$_ord->deli_price-$salemoney-$_ord->reserve;
		if($_shopdata->card_payfee>0 && preg_match("/^(C|P|M){1}/",$_ord->paymethod) && $_ord->price<>$totalprice) {
			echo "<tr bgcolor=#F5F5F5>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td style=\"font-size:8pt;padding:2,5\"><font color=#F26622>ī�������</font></td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td>&nbsp;</td>\n";
			echo "	<td align=right style=\"font-size:87pt\">".number_format($_ord->price-$totalprice)."&nbsp;</td>\n";
			echo "</tr>\n";
		}
		$temp = substr($_ord->ordercode,0,4)."/".substr($_ord->ordercode,4,2)."/".substr($_ord->ordercode,6,2)." ".substr($_ord->ordercode,8,2).":".substr($_ord->ordercode,10,2).":".substr($_ord->ordercode,12,2);
		$message=explode("[MEMO]",$_ord->order_msg);
		$message[0]=ereg_replace("\"","&quot;",$message[0]);
		$message[0]=str_replace("\"","",$message[0]);

		$message[0]=ereg_replace("\r\n","<br>\n&nbsp;&nbsp;",$message[0]);
		$mes1 = explode("<br>",$message[0]);
		$mescnt = count($mes1);
		$message[0]="";
		for($i=0;$i<$mescnt;$i++) {
			//$message[0].=messageview2($mes1[$i],80)."<br>";
		}
		echo "<tr ";
		if($_ord->reserve==0) echo " bgcolor=#F5F5F5";
		echo ">\n";
		echo "	<td>&nbsp;</td>\n";
		echo "	<td style=\"font-size:8pt;padding:5,27\"><B>�� �հ�</B> </td>\n";
		echo "	<td>&nbsp;</td>\n";
		echo "	<td align=center style=\"font-size:8pt\">".$sumquantity."</td>\n";
		echo "	<td align=right style=\"font-size:8pt\">".(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X"?number_format($in_reserve)."&nbsp;":"&nbsp")."</td>\n";
		echo "	<td align=right style=\"font-size:8pt\"> ".number_format($_ord->price)."&nbsp;</td>\n";

		echo "</tr>\n";

        echo "<tr>\n";
		echo "	<td colspan=".$colspan." bgcolor=#fafafa align=center>\n";
		echo "	<table border=0 cellpadding=0 cellspacing=0 width=96%>\n";
		echo "	<col width=90 style=\"padding-left:3\"></col>\n";
		echo "	<col width=></col>\n";
		echo "	<tr><td colspan=2 height=5></td></tr>\n";
		echo "	<tr>\n";
		echo "		<td>�ֹ� ����</td>\n";
		echo "		<td>: ".$temp;
		if(($_ord->del_gbn=="Y" || $_ord->del_gbn=="R") && !preg_match("/^(Y)$/",$_ord->deli_gbn)) {
			echo " &nbsp;&nbsp;&nbsp;<font color=blue>[�ֹ��ڰ� ������� ��ư�� ���� �ֹ���]</font>";
		}
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td>�ֹ���</td>\n";
		echo "		<td>: ".$_ord->sender_name;
		if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
			echo "(".$_ord->id.") ";
			if(strlen($group_name)>0) echo " [ �׷�� : ".$group_name." ] ";
			if(strlen(trim($usermemo))>0) {
				echo "<div id=\"membermemo_layer\" style=\"position:absolute; z-index:20; width:300;\"><table border=0 cellspacing=0 cellpadding=1 bgcolor=#7F7F65><tr><td style=\"padding:3\"><font color=#ffffff>".$usermemo."&nbsp;</td></tr></table></div>";
			}
		} else {
			echo "(��ȸ���ֹ�)";
		}
		echo "		</td>\n";
		echo "	</tr>\n";
		if (strlen($_ord->ip)>0) { 
			$ip = $_ord->ip;
			echo "	<tr>\n";
			echo "		<td>�ֹ���IP</td>\n";
			echo "		<td>: ".$ip."</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td>����ó</td>\n";
		echo "		<td>: ��ȭ : ".$_ord->sender_tel." ,";
		echo "		&nbsp;&nbsp;&nbsp;&nbsp; �̸��� : ".$_ord->sender_email."</a>";
		echo "		</td>\n";
		echo "	</tr>\n";

		if(strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X") {
			$sql = "SELECT COUNT(*) as cnt, SUM(price) as money FROM tblorderinfo ";
			$sql.= "WHERE id='".$_ord->id."' AND deli_gbn='Y'";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				$ordercnt=$row->cnt;
				$ordersum=$row->money;
			}
			mysql_free_result($result);
			echo "	<tr>\n";
			echo "		<td bgcolor=#7F7F65><font color=#ffffff>���� �ֹ�</font></td>\n";
			echo "		<td bgcolor=#7F7F65 style=\"color:#ffffff\">: ";
			if($ordercnt!=0) {
				echo "�ֹ�Ƚ�� ".$ordercnt."��, ���ֹ��ݾ� ".number_format($ordersum)." (��ۿϷ� ����) ";
			} else {
				echo "ù���� ���Դϴ�.";
			}
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr>\n";
		echo "		<td>�޴º�</td>\n";
		echo "		<td>: ".$_ord->receiver_name."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td valign=top style=\"padding-top:5px\">�޴� �ּ�</td>\n";
		echo "		<td>: \n";
		$address = eregi_replace("\n"," ",trim($_ord->receiver_addr));
		$address = eregi_replace("\r"," ",$address);
		$pos=strpos($address,"�ּ�");
		if ($pos>0) {
			$post = trim(substr($address,0,$pos));
			$address = substr($address,$pos+7);
		}
		$post = ereg_replace("�����ȣ : ","",$post);
		$arpost = explode("-",$post);
		echo "		�����ȣ : ".$arpost[0]."-".$arpost[1]."<br>\n";
		echo "		&nbsp;&nbsp;��&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��: ".$address."\n";
		echo "		</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td>����ó</td>\n";
		echo "		<td>: ��ȭ : ".$_ord->receiver_tel1." , ".$_ord->receiver_tel2."</td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td>���� ���</td>\n";
		echo "		<td>: ";

		$pgdate = date("YmdHi",mktime(date("H")-2,date("i"),0,date("m"),date("d"),date("Y")));
		$arpm=array("B"=>"������","V"=>"������ü","O"=>"�������","Q"=>"�������(�Ÿź�ȣ)","C"=>"�ſ�ī��",/*"P"=>"�ſ�ī��(�Ÿź�ȣ)",*/"M"=>"�ڵ���");

		if($_ord->pay_data=="�ſ�ī����� - ī���ۼ���" && substr($_ord->ordercode,0,12)<=$pgdate) $_ord->pay_data=$arpm[substr($_ord->paymethod,0,1)]." ����";

		if (preg_match("/^(B|O|Q){1}/",$_ord->paymethod)) {
			if($_ord->paymethod=="B") echo "<font color=#FF5D00>�������Ա�</font>\n";
			else if(substr($_ord->paymethod,0,1)=="O") echo "<font color=#FF5D00>�������</font>\n";
			else echo "�Ÿź�ȣ - �������";

			if(!preg_match("/^(C|D)$/",$_ord->deli_gbn) || $_ord->paymethod=="B") echo "�� ".$_ord->pay_data." ��";
			else echo "�� ���� ��� ��";

			if (strlen($_ord->bank_date)>=12) {
				echo "</td>\n</tr>\n";
				echo "<tr>\n";
				echo "	<td><FONT COLOR=red><B>�Ա�Ȯ��</B></FONT></td>\n";
				echo "	<td>: <B><font color=red>".substr($_ord->bank_date,0,4)."/".substr($_ord->bank_date,4,2)."/".substr($_ord->bank_date,6,2)." (".substr($_ord->bank_date,8,2).":".substr($_ord->bank_date,10,2).")</font></B>";
			} else if(strlen($_ord->bank_date)==9) {
				echo "</td>\n</tr>\n";
				echo "<tr>\n";
				echo "	<td><FONT COLOR=red><B>�Ա�Ȯ��</B></FONT></td>\n";
				echo "	<td>: <B><font color=red>ȯ��</font></B>";
			}
		} else if(substr($_ord->paymethod,0,1)=="M") {
			echo "�ڵ��� ������ ";
			if ($_ord->pay_flag=="0000") {
				if($_ord->pay_admin_proc=="C") echo "�� <font color=red>������� �Ϸ�</font> ��";
				else echo "<font color=red>������ ���������� �̷�������ϴ�.</font>";
			}
			else echo "������ ���еǾ����ϴ�.";
			echo " ��";
		} else if(substr($_ord->paymethod,0,1)=="P") {
			echo "�Ÿź�ȣ - �ſ�ī��";
			if($_ord->pay_flag=="0000") {
				if($_ord->pay_admin_proc=="C") echo "�� <font color=red>ī����� ��ҿϷ�</font> ��";
				else if($_ord->pay_admin_proc=="Y") echo "�� ī�� ���� �Ϸ� * �����մϴ�. : ���ι�ȣ ".$_ord->pay_auth_no." ��";
			}
			else echo "�� ".$_ord->pay_data." ��";
		} else if (substr($_ord->paymethod,0,1)=="C") {
			echo "<font color=#FF5D00>�ſ�ī��</font>\n";
			if($_ord->pay_flag=="0000") {
				if($_ord->pay_admin_proc=="C") echo "�� <font color=red>ī����� ��ҿϷ�</font> ��";
				else if($_ord->pay_admin_proc=="Y") echo "�� ī�� ���� �Ϸ� * �����մϴ�. : ���ι�ȣ ".$_ord->pay_auth_no." ��";
			}
			else echo "�� ".$_ord->pay_data." ��";
		} else if (substr($_ord->paymethod,0,1)=="V") {
			echo "�ǽð� ������ü : ";
			if ($_ord->pay_flag=="0000") {
				if($_ord->pay_admin_proc=="C") echo "�� <font color=005000> [ȯ��]</font> ��";
				else echo "<font color=red>".$_ord->pay_data."</font>";
			}
			else echo "������ ���еǾ����ϴ�.";
		}

		if(preg_match("/^(Q|P){1}/",$_ord->paymethod) && preg_match("/^(Y)$/",$_ord->escrow_result) && $_ord->deli_gbn!="C") echo " - <font color=red><b>[����Ȯ��]</b></font>";
		else if(preg_match("/^(Q|P){1}/",$_ord->paymethod) && preg_match("/^(C)$/",$_ord->escrow_result) && $_ord->deli_gbn=="C") echo " - <font color=red><b>[�������]</b></font>";
		echo "		</td>\n";
		echo "	</tr>\n";
		$ardelivery=array("Y"=>"�߼���","N"=>"�̹߼�","C"=>"�ֹ����","X"=>"��ۿ�û","S"=>"�߼��غ�","D"=>"��ҿ�û","E"=>"ȯ�Ҵ��","H"=>"���(���꺸��)");
		echo "	<tr>\n";
		echo "		<td>�߼� ����</td>\n";
		echo "		<td>: <font color=#A00000>".$ardelivery[$_ord->deli_gbn]."</font>";
		if(strlen($_ord->deli_date)==14) {
			echo " - �߼ۼ����� : ".substr($_ord->deli_date,0,4)."/".substr($_ord->deli_date,4,2)."/".substr($_ord->deli_date,6,2)." (".substr($_ord->deli_date,8,2).":".substr($_ord->deli_date,10,2).")";
		}
		echo "		</td>\n";
		echo "	</tr>\n";
		if($in_reserve>0 && $_ord->deli_gbn=="N" && strlen($_ord->ordercode)==20 && substr($_ord->ordercode,-1)!="X"){
			echo "	<tr>\n";
			echo "		<td>&nbsp;</td>\n";
			echo "		<td><font color=#0000FF>&nbsp;&nbsp;* ��ۿϷ� ��ư�� ������ ȸ������ ������</font> <font color=#A00000>".number_format($in_reserve)."��</font><font color=#0000FF>�� �����˴ϴ�.</font></td>\n";
			echo "	</tr>\n";
		}
		echo "	<tr height=22>\n";
		echo "		<td valign=top style=\"padding-top:5\">�ֹ���û����</td>\n";
		echo "		<td>: ".$message[0]."</td>\n";
		echo "	</tr>\n";

		for($j=0;$j<count($prdata);$j++) {
			if(strlen($prdata[$j]->order_prmsg)>0) {
				echo "	<tr height=22 class=\"page_screen\">\n";
				echo "		<td valign=middle>�ֹ��޼���</td>\n";
				echo "		<td style=\"padding-left:7\">";
				echo "	<FONT COLOR=\"#000000\"><B>��ǰ�� :</B></FONT> ".$prdata[$j]->productname."<BR>\n";
				echo "<textarea style=\"width:95%;height:38;overflow-x:hidden;overflow-y:auto;\" readonly>".$prdata[$j]->order_prmsg."</textarea>\n";
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "<tr><td colspan=2 height=5></td></tr>\n";

				echo "	<tr height=22 class=\"page_print\">\n";
				echo "		<td valign=middle>�ֹ��޼���</td>\n";
				echo "		<td style=\"padding-left:7\">";
				echo "		<FONT COLOR=\"#000000\"><B>��ǰ�� :</B></FONT> ".$prdata[$j]->productname."<BR>\n";
				echo "		".$prdata[$j]->order_prmsg."";
				echo "		</td>\n";
				echo "	</tr>\n";
				echo "<tr><td colspan=2 height=3></td></tr>\n";
			}
		}
		if(strlen($message[1])>0) {
			echo "	<tr height=58>\n";
			echo "		<td valign=top style=\"padding-top:8\">�ֹ����� �޸�</td>\n";
			echo "		<td style=\"padding-top:3\">: \n";
			echo "		".$message[1]."\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		if(strlen($message[2])>0) {
			echo "	<tr height=58>\n";
			echo "		<td valign=top style=\"padding-top:8\">���˸���</td>\n";
			echo "		<td style=\"padding-top:3\">: \n";
			echo "		".$message[2]."\n";
			echo "		</td>\n";
			echo "	</tr>\n";
		}
		echo "	</table>\n";
		echo "	</td>\n";
		echo "</tr>\n";
	}
?>
	</table>
	<!-- �ֹ����� �� -->
	</td>
</tr>

<tr><td height=10></td></tr>

</table>

<?=$onload?>

</body>
</html>