<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

if(strlen($_ShopInfo->getMemid())==0) {
	exit;
}
$r = mysql_query("select group_name,group_apply_coupon from tblmembergroup where group_code = '".$_ShopInfo->memgroup."'",get_db_conn());
$row = mysql_fetch_object($r);
if($row->group_apply_coupon == "N"){
	echo "
	<script>
		alert('".$row->group_name." ȸ�� ����� ���� ����� �Ұ����մϴ�.');
		self.close();
	</script>
	";
}
$usereserve=(int)$_POST["usereserve"];	//����� ������
$sumprice=$_POST["sumprice"];
$used=$_POST["used"];
?>

<html>
<head>
<title>���� ��ȸ �� ����</title>
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
window.moveTo(10,10);
window.resizeTo(612,650);
var all_list=new Array();
function prvalue() {
	var argv = prvalue.arguments;
	var argc = prvalue.arguments.length;

	this.classname		= "prvalue"
	this.debug			= false;
	this.bank_only		= new String((argc > 0) ? argv[0] : "N");
	this.sale_type		= new String((argc > 1) ? argv[1] : "");
	this.use_con_type2	= new String((argc > 2) ? argv[2] : "");
	this.sale_money		= new String((argc > 3) ? argv[3] : "");
	this.prname			= new String((argc > 4) ? argv[4] : "");
	this.prprice		= new String((argc > 5) ? argv[5] : "");
}

function CheckForm() {
	if(document.form1.coupon_code.selectedIndex<=0){
		alert("����Ͻ� ������ �����ϼ���.");
		document.form1.coupon_code.focus();
		return;
	}
	if(document.form1.bank_only.value=="Y" && !confirm('�ش� ������ ���ݰ����ÿ��� ��밡���մϴ�.\n�������Ա��� �����ϼž߸� ���� ����� �����մϴ�.')){
		document.form1.coupon_code.focus();
		return;
	}
	opener.document.form1.coupon_code.value=document.form1.coupon_code.options[document.form1.coupon_code.selectedIndex].text;
	opener.document.form1.bank_only.value=document.form1.bank_only.value;
	window.close();
}

function coupon_cancel() {
	opener.document.form1.coupon_code.value="";
	opener.document.form1.bank_only.value="N";
	window.close();
}
//-->
</SCRIPT>
</head>

<body topmargin="0" leftmargin="0" rightmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" width="100%">
<form name=form1 method=post>
<input type=hidden name=bank_only value="N">
<tr>
	<td><IMG src="<?=$Dir?>images/common/coupon_open_title.gif" border="0"></td>
</tr>
<tr>
	<td style="padding:10px;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><img src="<?=$Dir?>images/common/coupon_open_text01.gif" border="0" vspace="2"></td>
	</tr>
	<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<col width="65"></col>
		<col width=></col>
		<col width="130"></col>
		<col width="85"></col>
		<col width="80"></col>
		<tr>
			<td height="2" colspan="5" bgcolor="#000000"></td>
		</tr>
		<tr bgcolor="#F8F8F8" height="30" align="center">
			<td><font color="#333333"><b>������ȣ</b></font></td>
			<td><font color="#333333"><b>������</b></font></td>
			<td><font color="#333333"><b>���� �����ǰ</b></font></td>
			<td><font color="#333333"><b>���ѻ���</b></font></td>
			<td><font color="#333333"><b>����</b></font></td>
		</tr>
		<tr>
			<td height="1" colspan="5" bgcolor="#DDDDDD"></td>
		</tr>
<?
		$id=$_ShopInfo->getMemid();
		$sql = "SELECT a.coupon_code, a.coupon_name, a.sale_type, a.sale_money, a.bank_only, a.productcode, ";
		$sql.= "a.mini_price, a.use_con_type1, a.use_con_type2, a.use_point, a.vender, b.date_start, b.date_end ";
		$sql.= "FROM tblcouponinfo a, tblcouponissue b ";
		$sql.= "WHERE b.id='".$id."' AND a.coupon_code=b.coupon_code AND b.date_start<='".date("YmdH")."' ";
		$sql.= "AND (b.date_end>='".date("YmdH")."' OR b.date_end='') ";
		$sql.= "AND b.used='N' ";
		$result = mysql_query($sql,get_db_conn());
		$cnt=0;
		while($row=mysql_fetch_object($result)) {
			$coupon_code[$cnt]		= $row->coupon_code;
			$use_con_type2[$cnt]	= $row->use_con_type2;
			$sale_type[$cnt]		= $row->sale_type;
			$use_con_type1[$cnt]	= $row->use_con_type1;
			$sale_money[$cnt]		= $row->sale_money;
			$mini_price[$cnt]		= $row->mini_price;
			$vender[$cnt]			= $row->vender;
			$bank_only[$cnt]		= $row->bank_only;

			if($row->sale_type<=2) {
				$dan="%";
			} else {
				$dan="��";
			}
			if($row->sale_type%2==0) {
				$sale = "����";
			} else {
				$sale = "����";
			}

			if($row->productcode=="ALL") {
				if($row->vender==0) {
					$product="��ü��ǰ";
				} else {
					$product="�ش� ������ü ��ü��ǰ";
				}
				$productcode[$cnt][]="ALL";
			} else {
				$product = "";

				$arrproduct=explode(",",$row->productcode);
				for($a=0;$a<count($arrproduct);$a++) {
					if($a>0) $product.=", ";

					$prleng=strlen($arrproduct[$a]);

					$codeA=substr($arrproduct[$a],0,3);
					$codeB=substr($arrproduct[$a],3,3);
					$codeC=substr($arrproduct[$a],6,3);
					$codeD=substr($arrproduct[$a],9,3);

					$likecode=$codeA;
					if($codeB!="000") $likecode.=$codeB;
					if($codeC!="000") $likecode.=$codeC;
					if($codeD!="000") $likecode.=$codeD;

					if($prleng==18) $productcode[$cnt][]=$arrproduct[$a];
					else $productcode[$cnt][]=$likecode;

					$sql2 = "SELECT code_name FROM tblproductcode WHERE codeA='".substr($arrproduct[$a],0,3)."' ";
					if(substr($arrproduct[$a],3,3)!="000") {
						$sql2.= "AND (codeB='".substr($arrproduct[$a],3,3)."' OR codeB='000') ";
						if(substr($arrproduct[$a],6,3)!="000") {
							$sql2.= "AND (codeC='".substr($arrproduct[$a],6,3)."' OR codeC='000') ";
							if(substr($arrproduct[$a],9,3)!="000") {
								$sql2.= "AND (codeD='".substr($arrproduct[$a],9,3)."' OR codeD='000') ";
							} else {
								$sql2.= "AND codeD='000' ";
							}
						} else {
							$sql2.= "AND codeC='000' ";
						}
					} else {
						$sql2.= "AND codeB='000' AND codeC='000' ";
					}
					$sql2.= "ORDER BY codeA,codeB,codeC,codeD ASC ";
					$result2=mysql_query($sql2,get_db_conn());
					$i=0;
					while($row2=mysql_fetch_object($result2)) {
						if($i>0) $product.= " > ";
						$product.= $row2->code_name;
						$i++;
					}
					if($row->vender>0) $product.=" (�Ϻλ�ǰ ����)";
					mysql_free_result($result2);

					if($prleng==18) {
						$sql2 = "SELECT productname as product FROM tblproduct WHERE productcode='".$arrproduct[$a]."' ";
						$result2 = mysql_query($sql2,get_db_conn());
						if($row2 = mysql_fetch_object($result2)) {
							$product.= " > ".$row2->product;
						}
						mysql_free_result($result2);
					}
				}
			}

			$cnt++;

			if($row->use_con_type2=="N") {
				if($row->vender==0) {
					$product="[".$product."] ����";
				} else {
					$product="[".$product."] ������ �Ϻλ�ǰ";
				}
			}
			$s_time=mktime((int)substr($row->date_start,8,2),0,0,(int)substr($row->date_start,4,2),(int)substr($row->date_start,6,2),(int)substr($row->date_start,0,4));
			$e_time=mktime((int)substr($row->date_end,8,2),0,0,(int)substr($row->date_end,4,2),(int)substr($row->date_end,6,2),(int)substr($row->date_end,0,4));

			$date=date("Y.m.d H",$s_time)."�� ~ ".date("Y.m.d H",$e_time)."��";
			if($cnt>1) echo "<tr><td height=\"1\" colspan=\"5\" bgcolor=\"#DDDDDD\"></td></tr>\n";
			echo "<tr align=\"center\">\n";
			echo "	<td>".$row->coupon_code."</td>\n";
			echo "	<td>\n";
			echo "	<TABLE cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\">\n";
			echo "	<TR>\n";
			echo "		<TD height=\"16\"><font color=\"#333333\">".$row->coupon_name."</font></TD>\n";
			echo "	</TR>\n";
			echo "	<TR>\n";
			echo "		<TD height=\"16\"><IMG src=\"".$Dir."images/common/coupon_open_btn1.gif\" align=\"absMiddle\" border=\"0\" style=\"MARGIN-RIGHT:2px\"><font color=\"#000000\" style=\"FONT-SIZE:11px;LETTER-SPACING:-0.5pt\"><b>".$date."</b></TD>\n";
			echo "	</TR>\n";
			echo "	</TABLE>\n";
			echo "	</td>\n";
			echo "	<td><font color=\"#333333\">".$product."</font></td>\n";
			echo "	<td><font color=\"#333333\">".($row->mini_price=="0"?"���� ����":number_format($row->mini_price)."�� �̻�")."</font></td>\n";
			echo "	<td><font color=\"".($sale=="����"?"#FF0000":"#0000FF")."\">".number_format($row->sale_money).$dan.$sale."</font></td>\n";
			echo "</tr>\n";
		}
		mysql_free_result($result);
		if($cnt==0) {
			echo "<tr height=\"30\"><td colspan=\"5\" align=\"center\">������ ���������� �����ϴ�.</td></tr>\n";
		}
		echo "<tr><td height=\"1\" colspan=\"5\" bgcolor=\"#DDDDDD\"></td></tr>\n";
?>
		</table>
		</td>
	</tr>
	<?if($used!="N"){?>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td><img src="<?=$Dir?>images/common/coupon_open_text02.gif" border="0" vspace="2"></td>
	</tr>
	<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<col width=></col>
		<col width="80"></col>
		<col width="100"></col>
		<col width="70"></col>
		<col width="70"></col>
		<tr>
			<td height="2" colspan="5" bgcolor="#000000"></td>
		</tr>
		<tr bgcolor="#F8F8F8" height="30" align="center">
			<td><font color="#333333"><b>��ǰ��</b></font></td>
			<td><font color="#333333"><b>���밡��</b></font></td>
			<td><font color="#333333"><b>��������</b></font></td>
			<td><font color="#333333"><b>���ξ�(%)</b></font></td>
			<td><font color="#333333"><b>������(%)</b></font></td>
		</tr>
		<tr>
			<td height="1" colspan="5" bgcolor="#DDDDDD"></td>
		</tr>
<?
		$sql = "SELECT a.opt1_idx,a.opt2_idx,a.optidxs,a.quantity,b.productcode,b.productname,b.sellprice, ";
		$sql.= "b.option_price,b.option_quantity,b.option1,b.option2,b.vender,b.sellprice*a.quantity as realprice, ";
//		$sql.= "b.etcapply_coupon,b.etcapply_reserve,b.etcapply_gift FROM tblbasket a, tblproduct b ";
		$sql.= "b.etcapply_coupon,b.etcapply_reserve,b.etcapply_gift FROM tblbasket_normal a, tblproduct b ";
		$sql.= "WHERE a.tempkey='".$_ShopInfo->getTempkey()."' ";
		$sql.= "AND a.productcode=b.productcode ";
		echo $sql;
		$result=mysql_query($sql,get_db_conn());
		$sumprice=array();
		$basketcnt=array();
		$prcode=array();
		$prname=array();
		$productall=array();
		while($row = mysql_fetch_object($result)) {
			if($row->etcapply_coupon=="Y" || $row->etcapply_reserve=="Y" || $row->etcapply_gift=="Y") {
				continue;
			} else {
				//������ ���� �Ұ� ī�װ� ��ȸ�� �Ͽ� ������ �Ұ��� ��� ���� �����ǰ���� ����.
				$R_codeA=substr($row->productcode,0,3);
				$R_codeB=substr($row->productcode,3,3);
				$R_codeC=substr($row->productcode,6,3);
				$R_codeD=substr($row->productcode,9,3);

				$sql = "SELECT COUNT(*) as cnt FROM tblproductcode ";
				$sql.= "WHERE codeA='".$R_codeA."' AND codeB='".$R_codeB."' AND codeC='".$R_codeC."' AND codeD='".$R_codeD."' ";
				$sql.= "AND noreserve='Y' ";
				$result2=mysql_query($sql,get_db_conn());
				$row2=mysql_fetch_object($result2);
				mysql_free_result($result2);
				if($row2->cnt>0) {
					continue;
				}
			}
			if(strlen($prcode[0])>0) {
				if(substr($row->productcode,0,12)==substr($prcode[0],0,12)) $prcode[0]=substr($prcode[0],0,12);
				else if(substr($row->productcode,0,9)==substr($prcode[0],0,9)) $prcode[0]=substr($prcode[0],0,9);
				else if(substr($row->productcode,0,6)==substr($prcode[0],0,6)) $prcode[0]=substr($prcode[0],0,6);
				else if(substr($row->productcode,0,3)==substr($prcode[0],0,3)) $prcode[0]=substr($prcode[0],0,3);
				else $prcode[0]="";
			}
			if((int)$basketcnt[0]==0) {
				$prcode[0]=$row->productcode;
				$prname[0]=str_replace('"','',strip_tags($row->productname));
			} else {
				$prname[0].="<br>".str_replace('"','',strip_tags($row->productname));
			}
			$productall[0][$basketcnt[0]]["prcode"]=$row->productcode;
			$productall[0][$basketcnt[0]]["prname"]=str_replace('"','',strip_tags($row->productname));
			if($row->vender>0) {
				if(strlen($prcode[$row->vender])>0) {
					if(substr($row->productcode,0,12)==substr($prcode[$row->vender],0,12)) $prcode[$row->vender]=substr($prcode[$row->vender],0,12);
					else if(substr($row->productcode,0,9)==substr($prcode[$row->vender],0,9)) $prcode[$row->vender]=substr($prcode[$row->vender],0,9);
					else if(substr($row->productcode,0,6)==substr($prcode[$row->vender],0,6)) $prcode[$row->vender]=substr($prcode[$row->vender],0,6);
					else if(substr($row->productcode,0,3)==substr($prcode[$row->vender],0,3)) $prcode[$row->vender]=substr($prcode[$row->vender],0,3);
					else $prcode[$row->vender]="";
				}
				if((int)$basketcnt[$row->vender]==0) {
					$prcode[$row->vender]=$row->productcode;
					$prname[$row->vender]=str_replace('"','',strip_tags($row->productname));
				} else {
					$prname[$row->vender].="<br>".str_replace('"','',strip_tags($row->productname));
				}
				$productall[$row->vender][$basketcnt[$row->vender]]["prcode"]=$row->productcode;
				$productall[$row->vender][$basketcnt[$row->vender]]["prname"]=str_replace('"','',strip_tags($row->productname));
			}

			if(ereg("^(\[OPTG)([0-9]{4})(\])$",$row->option1)){
				$optioncode = substr($row->option1,5,4);
				$row->option1="";
				$row->option_price="";
				if($row->optidxs!="") {
					$tempoptcode = substr($row->optidxs,0,-1);
					$exoptcode = explode(",",$tempoptcode);

					$sqlopt = "SELECT * FROM tblproductoption WHERE option_code='".$optioncode."' ";
					$resultopt = mysql_query($sqlopt,get_db_conn());
					if($rowopt = mysql_fetch_object($resultopt)){
						$optionadd = array (&$rowopt->option_value01,&$rowopt->option_value02,&$rowopt->option_value03,&$rowopt->option_value04,&$rowopt->option_value05,&$rowopt->option_value06,&$rowopt->option_value07,&$rowopt->option_value08,&$rowopt->option_value09,&$rowopt->option_value10);
						$opti=0;
						$option_choice = $rowopt->option_choice;
						$exoption_choice = explode("",$option_choice);
						while(strlen($optionadd[$opti])>0){
							if($exoptcode[$opti]>0){
								$opval = explode("",str_replace('"','',$optionadd[$opti]));
								$exop = explode(",",str_replace('"','',$opval[$exoptcode[$opti]]));
								$row->realprice+=($row->quantity*$exop[1]);
							}
							$opti++;
						}
					}
				}
			}

			if (strlen($row->option_price)==0) {
				$price = $row->realprice;
			} else if (strlen($row->opt1_idx)>0) {
				$option_price = $row->option_price;
				$pricetok=explode(",",$option_price);
				$price = $pricetok[$row->opt1_idx-1]*$row->quantity;
			}
			$productall[0][$basketcnt[0]]["price"]=$price;
			$sumprice[0] += $price;

			if($row->vender>0) {
				$productall[$row->vender][$basketcnt[$row->vender]]["price"]=$price;
				$sumprice[$row->vender] += $price;
			}

			$basketcnt[0]++;
			if($row->vender>0) $basketcnt[$row->vender]++;

			if(strlen($row->productcode)==18) {
				$prname2[0][$row->productcode]=str_replace('"','',strip_tags($row->productname));

				$prprice[0][$row->productcode]=$price;
				$prprice[0][substr($row->productcode,0,3)]+=$price;
				if((int)$prbasketcnt[0][substr($row->productcode,0,3)]==0) {
					$prname2[0][substr($row->productcode,0,3)]=str_replace('"','',strip_tags($row->productname));
				} else {
					$prname2[0][substr($row->productcode,0,3)].="<br>".str_replace('"','',strip_tags($row->productname));
				}
				$prbasketcnt[0][substr($row->productcode,0,3)]++;

				$prprice[0][substr($row->productcode,0,6)]+=$price;
				if((int)$prbasketcnt[0][substr($row->productcode,0,6)]==0) {
					$prname2[0][substr($row->productcode,0,6)]=str_replace('"','',strip_tags($row->productname));
				} else {
					$prname2[0][substr($row->productcode,0,6)].="<br>".str_replace('"','',strip_tags($row->productname));
				}
				$prbasketcnt[0][substr($row->productcode,0,6)]++;

				$prprice[0][substr($row->productcode,0,9)]+=$price;
				if((int)$prbasketcnt[0][substr($row->productcode,0,9)]==0) {
					$prname2[0][substr($row->productcode,0,9)]=str_replace('"','',strip_tags($row->productname));
				} else {
					$prname2[0][substr($row->productcode,0,9)].="<br>".str_replace('"','',strip_tags($row->productname));
				}
				$prbasketcnt[0][substr($row->productcode,0,9)]++;

				$prprice[0][substr($row->productcode,0,12)]+=$price;
				if((int)$prbasketcnt[0][substr($row->productcode,0,12)]==0) {
					$prname2[0][substr($row->productcode,0,12)]=str_replace('"','',strip_tags($row->productname));
				} else {
					$prname2[0][substr($row->productcode,0,12)].="<br>".str_replace('"','',strip_tags($row->productname));
				}
				$prbasketcnt[0][substr($row->productcode,0,12)]++;

				if($row->vender>0) {
					$prname2[$row->vender][$row->productcode]=str_replace('"','',strip_tags($row->productname));

					$prprice[$row->vender][$row->productcode]=$price;
					$prprice[$row->vender][substr($row->productcode,0,3)]+=$price;
					if((int)$prbasketcnt[$row->vender][substr($row->productcode,0,3)]==0) {
						$prname2[$row->vender][substr($row->productcode,0,3)]=str_replace('"','',strip_tags($row->productname));
					} else {
						$prname2[$row->vender][substr($row->productcode,0,3)].="<br>".str_replace('"','',strip_tags($row->productname));
					}
					$prbasketcnt[$row->vender][substr($row->productcode,0,3)]++;

					$prprice[$row->vender][substr($row->productcode,0,6)]+=$price;
					if((int)$prbasketcnt[$row->vender][substr($row->productcode,0,6)]==0) {
						$prname2[$row->vender][substr($row->productcode,0,6)]=str_replace('"','',strip_tags($row->productname));
					} else {
						$prname2[$row->vender][substr($row->productcode,0,6)].="<br>".str_replace('"','',strip_tags($row->productname));
					}
					$prbasketcnt[$row->vender][substr($row->productcode,0,6)]++;

					$prprice[$row->vender][substr($row->productcode,0,9)]+=$price;
					if((int)$prbasketcnt[$row->vender][substr($row->productcode,0,9)]==0) {
						$prname2[$row->vender][substr($row->productcode,0,9)]=str_replace('"','',strip_tags($row->productname));
					} else {
						$prname2[$row->vender][substr($row->productcode,0,9)].="<br>".str_replace('"','',strip_tags($row->productname));
					}
					$prbasketcnt[$row->vender][substr($row->productcode,0,9)]++;

					$prprice[$row->vender][substr($row->productcode,0,12)]+=$price;
					if((int)$prbasketcnt[$row->vender][substr($row->productcode,0,12)]==0) {
						$prname2[$row->vender][substr($row->productcode,0,12)]=str_replace('"','',strip_tags($row->productname));
					} else {
						$prname2[$row->vender][substr($row->productcode,0,12)].="<br>".str_replace('"','',strip_tags($row->productname));
					}
					$prbasketcnt[$row->vender][substr($row->productcode,0,12)]++;
				}
			}
			$prname2[0][$prcode[0]]=$prname[0];
			$prprice[0][$prcode[0]]=$sumprice[0];

			$prname2[$row->vender][$prcode[$row->vender]]=$prname[$row->vender];
			$prprice[$row->vender][$prcode[$row->vender]]=$sumprice[$row->vender];

		}
		mysql_free_result($result);
?>
		<tr height="26" align="center">
			<td id=idx_prname style="color:#333333"><?=$prname[0]?></td>
			<td id=idx_prprice style="color:#333333"><?=number_format(($sumprice[0]>0?($sumprice[0]-$usereserve):0))."��";?></td>
			<td><select name=coupon_code onChange="change_group(options.value)" style="font-size:11px;background-color:#404040;letter-spacing:-0.5pt;">
			<option value="" style="color:#FFFFFF;">��������</option>
<?
			$prscript="";
			//if($prcode=="") $prcode="ALL";
			for($i=0;$i<=$cnt;$i++) {
				if($prcode[$vender[$i]]=="") $prcode[$vender[$i]]="ALL";

				$isoptiondisplay=false;
				for($a=0;$a<count($productcode[$i]);$a++) {
					$num = strlen($productcode[$i][$a]);
					$tempprcode = substr($prcode[$vender[$i]],0,$num);

					if(($productcode[$i][$a]=="ALL" || ($use_con_type2[$i]=="Y" && $tempprcode==$productcode[$i][$a]) || ($use_con_type1[$i]=="Y" && $use_con_type2[$i]=="Y" && $productcode[$i][$a]!="ALL" && strlen($prname2[$vender[$i]][$productcode[$i][$a]])>0) || ($use_con_type2[$i]=="N" && $use_con_type1[$i]=="N" && strlen($prname2[$vender[$i]][$productcode[$i][$a]])==0) || ($use_con_type1[$i]=="Y" && $use_con_type2[$i]=="N" && $productcode[$i][$a]!="ALL" && $sumprice[$vender[$i]]-$prprice[$vender[$i]][$productcode[$i][$a]]-$usereserve>0)) && ($mini_price[$i]==0 || $mini_price[$i]<=($sumprice[$vender[$i]]-$usereserve)) && isset($prprice[$vender[$i]])==true) {
						$isoptiondisplay=true;
					}

					if($use_con_type2[$i]=="N") {
						$tmp_prname="";
						$tmp_sumprice=0;
						$tmp_prprice=0;
						$kk=0;
						$temparr=$productall[$vender[$i]];
						if(is_array($temparr)) {
							while(list($key,$val)=each($temparr)) {
								if(substr($val["prcode"],0,$num)!=$productcode[$i][$a]) {
									if($kk>0) $tmp_prname.="<br> ";
									$tmp_prname.=$val["prname"];
									$tmp_prprice+=$val["price"];
									$kk++;
								}
								$tmp_sumprice+=$val["price"];
							}
						}
					} else {
						$tmp_prname="";
						$tmp_sumprice=0;
						$tmp_prprice=0;
						$kk=0;
						$temparr=$productall[$vender[$i]];
						if(is_array($temparr)) {
							while(list($key,$val)=each($temparr)) {
								if((substr($val["prcode"],0,$num)==$productcode[$i][$a]) || $productcode[$i][$a]=="ALL") {
									if($kk>0) $tmp_prname.="<br> ";
									$tmp_prname.=$val["prname"];
									$tmp_prprice+=$val["price"];
									$kk++;
								}
								$tmp_sumprice+=$val["price"];
							}
						}
					}
				}

				if($isoptiondisplay==true) {
					echo "<option value=\"".$i."\" style=\"color:#FFFFFF;\">".$coupon_code[$i]."</option>\n";
				}

				$prscript.="var prval=new prvalue();\n";
				$prscript.="prval.bank_only=\"".$bank_only[$i]."\";\n";
				$prscript.="prval.sale_type=\"".$sale_type[$i]."\";\n";
				$prscript.="prval.use_con_type2=\"".$use_con_type2[$i]."\";\n";
				$prscript.="prval.sale_money=\"".$sale_money[$i]."\";\n";

				$prscript.="prval.prname=\"".$tmp_prname."\";\n";
				$prscript.="prval.prprice=\"".number_format($tmp_prprice-$usereserve)."\";\n";
				$prscript.="all_list[".$i."]=prval;\n";
				$prscript.="prval=null;\n";
			}
?>
			</select></td>
			<? echo "<script>\n".$prscript."</script>\n"; ?>
			<td id=idx_sale_money1 style="color:red">��</td>
			<td id=idx_sale_money2 style="color:red">��</td>
		</tr>
		<input type=hidden name=prname value="<?=$prname[0]?>">
		<input type=hidden name=prprice value="<?=number_format(($sumprice[0]>0?($sumprice[0]-$usereserve):0))."��";?>">
		<input type=hidden name=sale_money1 value="��">
		<input type=hidden name=sale_money2 value="��">
		<tr>
			<td height="1" colspan="5" bgcolor="#DDDDDD"></td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="35" align="center"><a href="javascript:CheckForm();"><img src="<?=$Dir?>images/common/coupon_open_btn01.gif" border="0"></a><a href="javascript:coupon_cancel();"><img src="<?=$Dir?>images/common/coupon_open_btn02.gif" border="0" hspace="5"></a></td>
	</tr>
	<?} else {?>
	<tr>
		<td height="35" align="center"><a href="javascript:window.close();"><img src="<?=$Dir?>images/common/coupon_open_btn01.gif" border="0"></a></td>
	</tr>
	<?}?>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td style="padding-left:2px;padding-right:2px;">
		<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/coupon_open_table_01.gif" border="0"></TD>
		</TR>
		<TR>
			<TD background="<?=$Dir?>images/common/coupon_open_table_02.gif" style="padding-top:10px;padding-bottom:10px;padding-right:15px;padding-left:15px;">
			<TABLE cellSpacing="0" cellPadding="0" width="100%">
			<TR>
				<TD><IMG src="<?=$Dir?>images/common/coupon_open_text4.gif" border="0" vspace="6"></TD>
			</TR>
			<TR>
				<TD style="LINE-HEIGHT:16px;LETTER-SPACING:-0.5pt;padding-left:18px"><B>1 �ܰ�</B> - ���� ���ÿ���  <font color="#FF6600"><b>�����Ͻ� &quot;������ȣ&quot;�� ����</b></font>�Ͻø� ���αݾ�(�Ǵ� �����ݾ�)�� ��Ÿ���ϴ�.</TD>
			</TR>
			<TR>
				<TD style="LINE-HEIGHT:16px;LETTER-SPACING:-0.5pt;padding-left:64px">(��������(����)�� ���, ������(������)�� ��Ÿ���ϴ�.)</TD>
			</TR>
			<TR>
				<TD style="LINE-HEIGHT:16px;LETTER-SPACING:-0.5pt;padding-left:18px"><B>2 �ܰ�</B> - &quot;Ȯ��&quot; ��ư�� Ŭ���Ͻø�, �������� ������ �Ϸ�˴ϴ�.</TD>
			</TR>
			<TR>
				<TD><HR color="#e5e5e5" noShade SIZE="1"></TD>
			</TR>
			<TR>
				<TD><IMG src="<?=$Dir?>images/common/coupon_open_text5.gif" border="0" vspace="6"></TD>
			</TR>
			<TR>
				<TD style="FONT-SIZE: 12px;LINE-HEIGHT:16px;LETTER-SPACING:-0.5pt;padding-left:18px"">�� �� �������� <font color="#FF6600"><b>��밡�� �ݾ�</b></font>�� ������ �ֽ��ϴ�.<BR>�� ������ �� �ֹ��� ���ؼ� ����� �����մϴ�.<BR>�� �� �������� �������� ������ �ֽ��ϴ�.<BR>�� �ֹ� �� ��ǰ/ȯ��/����� ��� �ѹ� <font color="#FF6600"><b><u>����Ͻ� ���� ������ �ٽ� ����Ͻ� �� �����ϴ�.</u></b></font><BR>�� ���� ����ǰ���� ������ ������ <font color="#FF6600">�ش� ǰ�񿡼��� ��밡��</font> �մϴ�.<BR>�� ����/����(%) ������ ���������� ���� ������ ���� �����ݾ׿� ����˴ϴ�.<BR>�� �ش� ��ǰ�� ���� ������ <font color="#FF6600">�ش� ��ǰ�� ���Ž� ������ ����</font>�մϴ�.</TD>
			</TR>
			</TABLE>
			</TD>
		</TR>
		<TR>
			<TD><IMG SRC="<?=$Dir?>images/common/coupon_open_table_03.gif" border="0"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	</table>
	</td>
</tr>
</form>
</table>
<SCRIPT LANGUAGE="JavaScript">
<!--
function change_group(idx){
	if(idx.length>0) {
		idx = parseInt(idx);
		sale_money="";
		for(var i=0; i<all_list[idx].sale_money.length; i++) {
			var tmp = all_list[idx].sale_money.length-(i+1)
			if(i%3==0 && i!=0) sale_money = ',' + sale_money
			sale_money = all_list[idx].sale_money.charAt(tmp) + sale_money
		}
		if(all_list[idx].sale_type%2==0){
			money1 = document.form1.sale_money1;
			money2 = document.form1.sale_money2;
		} else{
			money1 = document.form1.sale_money2;
			money2 = document.form1.sale_money1;
		}
		if(all_list[idx].sale_type<=2) {
			money1.value=sale_money+"%";
		} else {
			money1.value=sale_money+"��";
		}
		money2.value="��";
		if(all_list[idx].sale_type%2==0){
			document.all["idx_sale_money1"].innerHTML=money1.value;
			document.all["idx_sale_money2"].innerHTML=money2.value;
		} else{
			document.all["idx_sale_money1"].innerHTML=money2.value;
			document.all["idx_sale_money2"].innerHTML=money1.value;
		}

		document.all["idx_prname"].innerHTML=all_list[idx].prname;
		document.all["idx_prprice"].innerHTML=all_list[idx].prprice+"��";
		document.form1.bank_only.value=all_list[idx].bank_only;
	} else {
		document.form1.sale_money1.value="��";
		document.form1.sale_money2.value="��";
		document.form1.bank_only.value="N";

		document.all["idx_sale_money1"].innerHTML=document.form1.sale_money1.value;
		document.all["idx_sale_money2"].innerHTML=document.form1.sale_money2.value;
		document.all["idx_prname"].innerHTML=document.form1.prname.value;
		document.all["idx_prprice"].innerHTML=document.form1.prprice.value;
	}
}

//-->
</SCRIPT>
</body>
</html>