<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

if(substr($_venderdata->grant_product,1,1)!="Y") {
	echo "<html></head><body onload=\"alert('��ǰ���� ���� ������ �����ϴ�.\\n\\n���θ��� �����Ͻñ� �ٶ��ϴ�.');history.go(-1)\"></body></html>";exit;
}

$mode=$_POST["mode"];
if($mode=="update") {
	$aproductcode=(array)$_POST["aproductcode"];

	$aassembleproduct=(array)$_POST["aassembleproduct"];
	$aassembleuse=(array)$_POST["aassembleuse"];

	$aproductname=(array)$_POST["aproductname"];
	$aproductname2=(array)$_POST["aproductname2"];
	$aproduction=(array)$_POST["aproduction"];
	$aproduction2=(array)$_POST["aproduction2"];
	$aconsumerprice=(array)$_POST["aconsumerprice"];
	$aconsumerprice2=(array)$_POST["aconsumerprice2"];
	$abuyprice=(array)$_POST["abuyprice"];
	$abuyprice2=(array)$_POST["abuyprice2"];
	$asellprice=(array)$_POST["asellprice"];
	$asellprice2=(array)$_POST["asellprice2"];
	$areserve=(array)$_POST["areserve"];
	$areserve2=(array)$_POST["areserve2"];
	$areservetype=(array)$_POST["areservetype"];
	$areservetype2=(array)$_POST["areservetype2"];
	$aquantity=(array)$_POST["aquantity"];
	$aquantity2=(array)$_POST["aquantity2"];
	if(count($aproductcode)>0) {
		$movecount=0;
		$update_ymd = date("YmdH");
		$update_ymd2 = date("is");
		for($i=0;$i<count($aproductcode);$i++) {
			if (strlen($aproductcode[$i])>0 && ($aproductname[$i]!=$aproductname2[$i] || $aproduction[$i]!=$aproduction2[$i] || $aconsumerprice[$i]!=$aconsumerprice2[$i] || $abuyprice[$i]!=$abuyprice2[$i] || $asellprice[$i]!=$asellprice2[$i] || $areserve[$i]!=$areserve2[$i] || $areservetype[$i]!=$areservetype2[$i] || $aquantity[$i]!=$aquantity2[$i]) && strlen($asellprice[$i])>0 && strlen($areserve[$i])>0 && strlen($aproductname[$i])>0) {
				if (ereg("([0-9]{".strlen($asellprice[$i])."})",$asellprice[$i]) && ereg("([0-9.]{".strlen($areserve[$i])."})",$areserve[$i])) {   #�������� �˻�
					if (strlen($aquantity[$i])==0) $quantity="NULL";
					else if (ereg("([0-9]{".strlen($aquantity[$i])."})",$aquantity[$i]))
					$quantity = $aquantity[$i];
					if (strlen($abuyprice[$i])==0) $abuyprice[$i]="0";
					if (strlen($areserve[$i])==0) {
						$areserve[$i]=0;
					} else {
						$areserve[$i]=$areserve[$i]*1;
					}
					if($areservetype[$i]!="Y") {
						$areservetype[$i]="N";
					}
					$productname = ereg_replace("\\\\'","''",$aproductname[$i]);
					$production = ereg_replace("\\\\'","''",$aproduction[$i]);

					$sql = "UPDATE tblproduct SET ";
					$sql.= "productname			= '".$productname."', ";
					$sql.= "sellprice			= ".$asellprice[$i].", ";
					$sql.= "consumerprice		= ".$aconsumerprice[$i].", ";
					$sql.= "buyprice			= ".$abuyprice[$i].", ";
					$sql.= "reserve				= '".$areserve[$i]."', ";
					$sql.= "reservetype			= '".$areservetype[$i]."', ";
					$sql.= "production			= '".$production."', ";
					$sql.= "quantity			= ".$quantity." ";
					if(substr($_venderdata->grant_product,3,1)!="N") {
						$sql.= ", display		= 'N' ";
					}
					$sql.= "WHERE productcode='".$aproductcode[$i]."' ";
					$sql.= "AND vender='".$_VenderInfo->getVidx()."' ";
					if(mysql_query($sql,get_db_conn())) {
						if($asellprice[$i]!=$asellprice2[$i] && $aassembleuse[$i]!="Y") {
							if(strlen($aassembleproduct[$i])>0) {
								$sql = "SELECT productcode, assemble_pridx FROM tblassembleproduct ";
								$sql.= "WHERE productcode IN ('".str_replace(",","','",$aassembleproduct[$i])."') ";
								$result = mysql_query($sql,get_db_conn());
								while($row = @mysql_fetch_object($result)) {
									$sql = "SELECT SUM(sellprice) as sumprice FROM tblproduct ";
									$sql.= "WHERE pridx IN ('".str_replace("","','",$row->assemble_pridx)."') ";
									$sql.= "AND display ='Y' ";
									$sql.= "AND assembleuse!='Y' ";
									$result2 = mysql_query($sql,get_db_conn());
									if($row2 = @mysql_fetch_object($result2)) {
										$sql = "UPDATE tblproduct SET sellprice='".$row2->sumprice."' ";
										$sql.= "WHERE productcode = '".$row->productcode."' ";
										$sql.= "AND assembleuse='Y' ";
										mysql_query($sql,get_db_conn());
									}
									mysql_free_result($result2);
								}
							}
						}
						$movecount++;

						$update_date = $update_ymd.$update_ymd2;
						$log_content = "## ��ǰ�ϰ����� ## - ��ǰ�ڵ�: ".$aproductcode[$i]." ����: ".$asellprice[$i]." �Һ��ڰ� : ".$aconsumerprice[$i]."  ���԰� : ".$abuyprice." ����: $quantity ������ : ".$areserve[$i];
						
						$_VenderInfo->ShopVenderLog($_VenderInfo->getVidx(),$connect_ip,$log_content,$update_date);

						$update_ymd2++;
					}
				}
			}
		}
		if ($movecount!=0) {
			echo "<html></head><body onload=\"alert('".$movecount." ���� ��ǰ������ �����Ǿ����ϴ�.');parent.pageForm.submit();\"></body></html>";exit;
		} else {
			echo "<html></head><body onload=\"alert('������ ��ǰ������ �����ϴ�.')\"></body></html>";exit;
		}
	}
	exit;
}

$code=$_POST["code"];
$disptype=$_POST["disptype"];
$s_check=$_POST["s_check"];
if(strlen($s_check)==0) $s_check="name";
$search=ltrim($_POST["search"]);
$sort=$_POST["sort"];
if($sort!="order by productname asc" && $sort!="order by productname desc" && $sort!="order by sellprice asc" && $sort!="order by sellprice desc" && $sort!="order by production asc" && $sort!="order by production desc") {
	$sort="order by regdate desc";
}

$qry = "WHERE 1=1 ";
if(strlen($code)>=3) {
	$qry.= "AND productcode LIKE '".$code."%' ";
}
$qry.= "AND vender='".$_VenderInfo->getVidx()."' ";
if($disptype=="Y") $qry.= "AND display='Y' ";
else if($disptype=="N") $qry.= "AND display='N' ";
if(strlen($search)>0) {
	if($s_check=="name") $qry.= "AND productname LIKE '%".$search."%' ";
	else if($s_check=="code") $qry.= "AND productcode='".$search."' ";
}


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

$t_count=0;
$sql = "SELECT COUNT(*) as t_count FROM tblproduct ".$qry." ";
$result = mysql_query($sql,get_db_conn());
$row = mysql_fetch_object($result);
$t_count = $row->t_count;
mysql_free_result($result);
$pagecount = (($t_count - 1) / $setup[list_num]) + 1;

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function ACodeSendIt(code) {
	document.sForm.code.value=code;
	murl = "product_myprd.ctgr.php?code="+code+"&depth=2";
	surl = "product_myprd.ctgr.php?depth=3";
	durl = "product_myprd.ctgr.php?depth=4";
	BCodeCtgr.location.href = murl;
	CCodeCtgr.location.href = surl;
	DCodeCtgr.location.href = durl;
}

function formSubmit() {
	try {
		if (typeof(document.form2["aproductcode[]"])!="object") {
			alert("������ ��ǰ�� �������� �ʽ��ϴ�.");
			return;
		}

		var i=0;
		while(true) {
			if(document.getElementById("areserve"+i) && document.getElementById("areservetype"+i)) {
				if (document.getElementById("areserve"+i).value.length>0) {
					if(document.getElementById("areservetype"+i).value=="Y") {
						if(isDigitSpecial(document.getElementById("areserve"+i).value,".")) {
							alert("�������� ���ڿ� Ư������ �Ҽ���\(.\)���θ� �Է��ϼ���.");
							document.getElementById("areserve"+i).focus();
							return;
						}
						
						if(getSplitCount(document.getElementById("areserve"+i).value,".")>2) {
							alert("������ �Ҽ���\(.\)�� �ѹ��� ��밡���մϴ�.");
							document.getElementById("areserve"+i).focus();
							return;
						}

						if(getPointCount(document.getElementById("areserve"+i).value,".",2)==true) {
							alert("�������� �Ҽ��� ���� ��°�ڸ������� �Է� �����մϴ�.");
							document.getElementById("areserve"+i).focus();
							return;
						}

						if(Number(document.getElementById("areserve"+i).value)>100 || Number(document.getElementById("areserve"+i).value)<0) {
							alert("�������� 0 ���� ũ�� 100 ���� ���� ���� �Է��� �ּ���.");
							document.getElementById("areserve"+i).focus();
							return;
						}
					} else {
						if(isDigitSpecial(document.getElementById("areserve"+i).value,"")) {
							alert("�������� ���ڷθ� �Է��ϼ���.");
							document.getElementById("areserve"+i).focus();
							return;
						}
					}
				}
				i++;
			} else {
				break;
			}
		}
	} catch (e) {
		return;
	}
<?if(substr($_venderdata->grant_product,3,1)=="N") {?>
	if(confirm("��ǰ������ ���� �Ͻðڽ��ϱ�?")) {
<?}else{?>
	if(confirm("��ǰ������ �����Ͻø� ��ǰ���� ���°� [OFF]�˴ϴ�.\n\n���θ� ����� ���� �� [ON]���°� �ǿ��� ���� �����Ͻñ� �ٶ��ϴ�.\n\n��ǰ������ ���� �Ͻðڽ��ϱ�?")) {
<?}?>
		document.form2.mode.value="update";
		document.form2.target="processFrame";
		document.form2.submit();
	}
}

function SearchPrd() {
	document.sForm.submit();
}

function GoPage(block,gotopage) {
	document.pageForm.block.value=block;
	document.pageForm.gotopage.value=gotopage;
	document.pageForm.submit();
}

function OrderSort(sort) {
	document.pageForm.block.value="";
	document.pageForm.gotopage.value="";
	document.pageForm.sort.value=sort;
	document.pageForm.submit();
}

function chkFieldMaxLenFunc(thisId,reserveTypeID) {
	if(document.getElementById(reserveTypeID)) {
		if (document.getElementById(reserveTypeID).value=="Y") { max=5; addtext="/Ư������(�Ҽ���)";} else { max=6; }

		if(document.getElementById(thisId)) {
			if (document.getElementById(thisId).value.bytes() > max) {
				alert("�Է��� �� �ִ� ��� ������ �ʰ��Ǿ����ϴ�.\n\n" + "����"+addtext+" " + max + "�� �̳��� �Է��� �����մϴ�.");
				document.getElementById(thisId).value = document.getElementById(thisId).value.cut(max);
				document.getElementById(thisId).focus();
			}
		}
	}
}

function getSplitCount(objValue,splitStr)
{
	var split_array = new Array();
	split_array = objValue.split(splitStr);
	return split_array.length;
}

function getPointCount(objValue,splitStr,falsecount)
{
	var split_array = new Array();
	split_array = objValue.split(splitStr);
	
	if(split_array.length!=2) {
		if(split_array.length==1) {
			return false;
		} else {
			return true;
		}
	} else {
		if(split_array[1].length>falsecount) {
			return true;
		} else {
			return false;
		}
	}
}

function isDigitSpecial(objValue,specialStr)
{
	if(specialStr.length>0) {
		var specialStr_code = parseInt(specialStr.charCodeAt(i));

		for(var i=0; i<objValue.length; i++) {
			var code = parseInt(objValue.charCodeAt(i));
			var ch = objValue.substr(i,1).toUpperCase();
			
			if((ch<"0" || ch>"9") && code!=specialStr_code) {
				return true;
				break;
			}
		}
	} else {
		for(var i=0; i<objValue.length; i++) {
			var ch = objValue.substr(i,1).toUpperCase();
			if(ch<"0" || ch>"9") {
				return true;
				break;
			}
		}
	}
}
</script>

<table border=0 cellpadding=0 cellspacing=0 width=100% height="100%" style="table-layout:fixed">
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
					<td><img src="images/product_allupdate_title.gif"></td>
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
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">ī�װ� �з�/�˻� ����Ͽ� �ϰ����� �����մϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�ǸŰ�, �Һ��ڰ�, ���԰�, ������, ���� �Է½� �޸�(,)�� �Է��� �� �����ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�ϰ� ���� �� �ݵ�� [�����ϱ�] ��ư�� Ŭ���ؾ� ����˴ϴ�.</td>
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
				





				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr>
					<td valign=top bgcolor=D4D4D4 style=padding:1>
					<table border=0 cellpadding=0 cellspacing=0 width=100%>
					<tr>
						<td valign=top bgcolor=F0F0F0 style=padding:10>
						<table border=0 cellpadding=0 cellspacing=0 width=100%>
						<form name="sForm" method="post">
						<input type="hidden" name="code" value="<?=$code?>">
						<tr>
							<td>
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<tr>
								<td>
								<select name="code1" style=width:155 onchange="ACodeSendIt(this.options[this.selectedIndex].value)">
								<option value="">------ �� �� �� ------</option>
<?
								$sql = "SELECT SUBSTRING(productcode,1,3) as prcode FROM tblproduct ";
								$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
								$sql.= "GROUP BY prcode ";
								$result=mysql_query($sql,get_db_conn());
								$codes="";
								while($row=mysql_fetch_object($result)) {
									$codes.=$row->prcode.",";
								}
								mysql_free_result($result);
								if(strlen($codes)>0) {
									$codes=substr($codes,0,-1);
									$prcodelist=ereg_replace(',','\',\'',$codes);
								}
								if(strlen($prcodelist)>0) {
									$sql = "SELECT codeA,codeB,codeC,codeD,code_name FROM tblproductcode ";
									$sql.= "WHERE codeA IN ('".$prcodelist."') AND codeB='000' AND codeC='000' ";
									$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
									$result=mysql_query($sql,get_db_conn());
									while($row=mysql_fetch_object($result)) {
										echo "<option value=\"".$row->codeA."\"";
										if($row->codeA==substr($code,0,3)) echo " selected";
										echo ">".$row->code_name."</option>\n";
									}
									mysql_free_result($result);
								}
?>
								</select>
								</td>
								<td></td>
								<td>
								<iframe name="BCodeCtgr" src="product_myprd.ctgr.php?code=<?=substr($code,0,3)?>&select_code=<?=$code?>&depth=2" width="155" height="21" scrolling=no frameborder=no></iframe>
								</td>
								<td></td>
								<td><iframe name="CCodeCtgr" src="product_myprd.ctgr.php?code=<?=substr($code,0,6)?>&select_code=<?=$code?>&depth=3" width="155" height="21" scrolling=no frameborder=no></iframe></td>
								<td></td>
								<td><iframe name="DCodeCtgr" src="product_myprd.ctgr.php?code=<?=substr($code,0,9)?>&select_code=<?=$code?>&depth=4" width="155" height="21" scrolling=no frameborder=no></iframe></td>
							</tr>
							</table>
							</td>
						</tr>
						<tr><td height=5></td></tr>
						<tr>
							<td>
							<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<col width=></col>
							<col width=155></col>
							<tr>
								<td>
								<select name=disptype style="width:100%">
								<option value="">����/����ǰ ��ü</option>
								<option value="Y" <?if($disptype=="Y")echo"selected";?>>������ǰ�� �˻�</option>
								<option value="N" <?if($disptype=="N")echo"selected";?>>����ǰ�� �˻�</option>
								</select>
								</td>

								<td></td>

								<td>
								<select name="s_check" style="width:100%">
								<option value="name" <?if($s_check=="name")echo"selected";?>>��ǰ������ �˻�</option>
								<option value="code" <?if($s_check=="code")echo"selected";?>>��ǰ�ڵ�� �˻�</option>
								</select>
								</td>

								<td></td>

								<td><input type=text name=search value="<?=$search?>" style="width:100%"></td>

								<td></td>

								<td><A HREF="javascript:SearchPrd()"><img src=images/btn_inquery03.gif border=0></A></td>
							</tr>
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

				<table border=0 cellpadding=0 cellspacing=0 width=100%>
				<tr><td height=20></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				<tr>
					<td bgcolor=E7E7E7>
					<table width=100% border=0 cellspacing=1 cellpadding=0 style="table-layout:fixed">
					<col width=40></col>
					<col width=></col>
					<col width=100></col>
					<col width=70></col>
					<col width=70></col>
					<col width=70></col>
					<col width=95></col>
					<col width=40></col>

					<form name=form2 method=post>
					<input type=hidden name=mode>

					<tr height=35 align=center bgcolor=F5F5F5>
						<td align=center><B>��ȣ</B></td>
						<td align=center><a href="javascript:OrderSort('<?=($sort=="order by productname asc"?"order by productname desc":"order by productname asc")?>')"; onMouseover="self.status=''; return true; "><B>��ǰ��</B></a></td>
						<td align=center><a href="javascript:OrderSort('<?=($sort=="order by production asc"?"order by production desc":"order by production asc")?>')"; onMouseover="self.status=''; return true; "><B>������</B></a></td>
						<td align=center><a href="javascript:OrderSort('<?=($sort=="order by sellprice asc"?"order by sellprice desc":"order by sellprice asc")?>')"; onMouseover="self.status=''; return true; "><B>�ǸŰ�</B></a></td>
						<td align=center><B>�Һ��ڰ�</B></td>
						<td align=center><B>���԰�</B></td>
						<td align=center><B>������(��)</B></td>
						<td align=center><B>����</B></td>
					</tr>
<?
					$colspan=8;
					if($t_count>0) {
						$sql = "SELECT productcode,productname,sellprice,consumerprice,buyprice,reserve,reservetype, ";
						$sql.= "production,quantity,assembleproduct,assembleuse FROM tblproduct ".$qry." ".$sort." ";
						$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
						$result=mysql_query($sql,get_db_conn());
						$i=0;
						while($row=mysql_fetch_object($result)) {
							$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
							echo "<tr height=30 bgcolor=#FFFFFF>\n";
							echo "	<td align=center style=\"font-size:8pt\">".$number."</td>\n";
							echo "	<td align=center style=\"padding:5\"><input type=text name=\"aproductname[]\" maxlength=250 value=\"".str_replace("\"","&quot",$row->productname)."\" style=\"font-size:8pt;width:100%\" onKeyDown=\"chkFieldMaxLen(250)\"></td>\n";
							echo "	<td align=center style=\"padding:5\"><input type=text name=\"aproduction[]\" maxlength=20 value=\"".str_replace("\"","&quot",$row->production)."\" style=\"font-size:8pt;width:100%\"></td>\n";
							if($row->assembleuse=="Y") { 
								echo "	<td class=\"td_con1\" align=\"right\" style=\"font-size:8pt;\"><input type=hidden name=\"asellprice[]\" value=\"".$row->sellprice."\">".$row->sellprice."</td>\n";
							} else {
								echo "	<td align=center style=\"padding:5\"><input type=text name=\"asellprice[]\" maxlength=8 value=\"".$row->sellprice."\" style=\"font-size:8pt;width:100%;text-align:right\"></td>\n";
							}
							echo "	<td align=center style=\"padding:5\"><input type=text name=\"aconsumerprice[]\" maxlength=8 value=\"".$row->consumerprice."\" style=\"font-size:8pt;width:100%;text-align:right\"></td>\n";
							echo "	<td align=center style=\"padding:5;\"><input type=text name=\"abuyprice[]\" maxlength=8 value=\"".$row->buyprice."\" style=\"font-size:8pt;width:100%;text-align:right\"></td>\n";
							echo "	<td align=center style=\"padding:5\"><input type=text name=\"areserve[]\" size=6 maxlength=6 value=\"".$row->reserve."\"  style=\"font-size:8pt;text-align:right\" id=\"areserve".$i."\" onKeyUP=\"chkFieldMaxLenFunc(this.id,'areservetype".$i."');\"><select name=\"areservetype[]\" style=\"width:36px;font-size:8pt;margin-left:1px;\" id=\"areservetype".$i."\" onchange=\"chkFieldMaxLenFunc('areserve".$i."',this.id);\"><option value=\"N\"".($row->reservetype!="Y"?" selected":"").">��<option value=\"Y\"".($row->reservetype!="Y"?"":" selected").">%</select></td>\n";
							echo "	<td align=center style=\"padding:5\"><input type=text name=\"aquantity[]\" maxlength=3 value=\"".$row->quantity."\"  style=\"font-size:8pt;width:100%;text-align:right\"></td>\n";
							echo "</tr>\n";
							echo "<input type=hidden name=\"aproductcode[]\" value=\"".$row->productcode."\">\n";
							echo "<input type=hidden name=\"aassembleproduct[]\" value=\"".$row->assembleproduct."\">\n";
							echo "<input type=hidden name=\"aassembleuse[]\" value=\"".$row->assembleuse."\">\n";
							echo "<input type=hidden name=\"aproductname2[]\" value=\"".str_replace("\"","&quot",$row->productname)."\">\n";
							echo "<input type=hidden name=\"aproduction2[]\" value=\"".str_replace("\"","&quot",$row->production)."\">\n";
							echo "<input type=hidden name=\"aconsumerprice2[]\" value=\"".$row->consumerprice."\">\n";
							echo "<input type=hidden name=\"abuyprice2[]\" value=\"".$row->buyprice."\">\n";
							echo "<input type=hidden name=\"asellprice2[]\" value=\"".$row->sellprice."\">\n";
							echo "<input type=hidden name=\"areserve2[]\" value=\"".$row->reserve."\">\n";
							echo "<input type=hidden name=\"areservetype2[]\" value=\"".($row->reservetype!="Y"?"N":"Y")."\">\n";
							echo "<input type=hidden name=\"aquantity2[]\" value=\"".$row->quantity."\">\n";
							$i++;
						}
						mysql_free_result($result);

						if($i>0) {
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
					} else {
						echo "<tr height=28 bgcolor=#FFFFFF><td colspan=".$colspan." align=center>��ȸ�� ������ �����ϴ�.</td></tr>\n";
					}
?>
					</form>

					</table>
					</td>
				</tr>
				<tr><td height=10></td></tr>
				<tr>
					<td align=center>
					<form name="pageForm" method="post">
					<input type=hidden name='code' value='<?=$code?>'>
					<input type=hidden name='disptype' value='<?=$disptype?>'>
					<input type=hidden name='s_check' value='<?=$s_check?>'>
					<input type=hidden name='search' value='<?=$search?>'>
					<input type=hidden name='sort' value='<?=$sort?>'>
					<input type=hidden name='block' value='<?=$block?>'>
					<input type=hidden name='gotopage' value='<?=$gotopage?>'>
					</form>

					<?=$pageing?>

					</td>
				</tr>
				<tr><td height=15></td></tr>
				<tr>
					<td align=center>
					<A HREF="javascript:formSubmit()"><img src=images/btn_save01.gif border=0></A>
					</td>
				</tr>
				</table>

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

<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>