<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include ("access.php");

$id=$_POST["id"];
$mode=$_POST["mode"];

if(strlen($_ShopInfo->getId())==0 || strlen($id)==0){
	echo "<script>window.close();</script>";
	exit;
}

$recommand_type=$_shopdata->recommand_type;
$member_addform=$_shopdata->member_addform;

$sql = "SELECT * FROM tblmember WHERE id='".$id."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	if($row->member_out=="Y") {
		echo "<script>window.close();</script>";
		exit;
	}

	if($mode!="modify" && $mode!="modify2") {
		$name=$row->name;
		if($_shopdata->resno_type!="N") {
			$resno1=substr($row->resno,0,6);
			$resno2=substr($row->resno,6,7);
		}
		$email=$row->email;
		$home_tel=$row->home_tel;
		$home_post1=$row->home_post;
		//$home_post2=substr($row->home_post,3,3);
		$home_addr=$row->home_addr;
		$home_addr_temp=explode("=",$home_addr);
		$home_addr1=$home_addr_temp[0];
		$home_addr2=$home_addr_temp[1];
		$mobile=$row->mobile;
		$office_post1=$row->office_post;
		//$office_post2=substr($row->office_post,3,3);
		$office_addr=$row->office_addr;
		$office_addr_temp=explode("=",$office_addr);
		$office_addr1=$office_addr_temp[0];
		$office_addr2=$office_addr_temp[1];
		$etc=explode("=",$row->etcdata);

		$comp_num = $row->comp_num;
		$comp_owner = $row->comp_owner;
		$comp_type1 = $row->comp_type1;
		$comp_type2 = $row->comp_type2;
		$wholesaletype = $row->wholesaletype;


		if($row->news_yn=="Y") {
			$news_mail_yn="Y";
			$news_sms_yn="Y";
		} else if($row->news_yn=="M") {
			$news_mail_yn="Y";
			$news_sms_yn="N";
		} else if($row->news_yn=="S") {
			$news_mail_yn="N";
			$news_sms_yn="Y";
		} else if($row->news_yn=="N") {
			$news_mail_yn="N";
			$news_sms_yn="N";
		}

		$loginType=$row->loginType;
		$gubun=$row->gubun;
		$sosok=$row->sosok;
		$jikjong=$row->jikjong;
		$jikgun=$row->jikgun;
		
		$bizno=$row->bizno;
		$biz_gubun=$row->biz_gubun;
		$bizcheck=$row->bizcheck;
	} else {
		$name=$row->name;
		if($_shopdata->resno_type=="M") {
			$resno1=trim($_POST["resno1"]);
			$resno2=trim($_POST["resno2"]);
		} else if($_shopdata->resno_type=="Y") {
			$resno1=substr($row->resno,0,6);
			$resno2=substr($row->resno,6,7);
		}
		$email=trim($_POST["email"]);
		$news_mail_yn=$_POST["news_mail_yn"];
		$news_sms_yn=$_POST["news_sms_yn"];
		$home_tel=trim($_POST["home_tel"]);
		$home_post1=trim($_POST["home_post1"]);
		$home_post2=trim($_POST["home_post2"]);
		$home_addr1=trim($_POST["home_addr1"]);
		$home_addr2=trim($_POST["home_addr2"]);
		$mobile=trim($_POST["mobile"]);
		$office_post1=trim($_POST["office_post1"]);
		$office_post2=trim($_POST["office_post2"]);
		$office_addr1=trim($_POST["office_addr1"]);
		$office_addr2=trim($_POST["office_addr2"]);
		$rec_id=trim($_POST["rec_id"]);
		$etc=$_POST["etc"];

		$comp_num =  trim($_POST["comp_num0"])."-".trim($_POST["comp_num1"])."-".trim($_POST["comp_num2"]);
		$comp_owner = trim($_POST["comp_owner"]);
		$comp_type1 = trim($_POST["comp_type1"]);
		$comp_type2 = trim($_POST["comp_type2"]);
		$wholesaletype = trim($_POST["wholesaletype"]);
	}
	$rec_id=$row->rec_id;
	if(strlen($rec_id)==0) {
		$str_rec="��õ�� ����";
	} else {
		$str_rec=$rec_id;
	}
	if($recommand_type=="Y") {
		$sql = "SELECT rec_cnt FROM tblrecommendmanager ";
		$sql.= "WHERE rec_id='".$id."' ";
		$result2= mysql_query($sql,get_db_conn());
		if($row2=mysql_fetch_object($result2)) {
			$str_rec.=" <b><font color=#3A3A3A> ".$row2->rec_cnt."���� ����� ��õ�ϼ̽��ϴ�.</font></b>";
		}
		mysql_free_result($result2);
	}
} else {
	echo "<script>window.close();</script>";
	exit;
}
mysql_free_result($result);

unset($straddform);
unset($scriptform);
unset($stretc);
if(strlen($member_addform)>0) {
	$straddform.="<tr>\n";
	$straddform.="	<TD height=\"30\" colspan=2 align=center><B>�߰������� �Է��ϼ���.</B></td>\n";
	$straddform.="</tr>\n";
	$straddform.="<tr>\n";
	$straddform.="	<TD colspan=\"2\" width=\"100%\" background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>\n";
	$straddform.="</tr>\n";

	$fieldarray=explode("=",$member_addform);
	$num=sizeof($fieldarray)/3;
	for($i=0;$i<$num;$i++) {
		if (substr($fieldarray[$i*3],-1,1)=="^") {
			$fieldarray[$i*3]="<img src=\"images/icon_point2.gif\" width=\"8\" height=\"11\" border=\"0\">".substr($fieldarray[$i*3],0,strlen($fieldarray[$i*3])-1);
			$field_check[$i]="OK";
		}

		$stretc.="<tr>\n";
		$stretc.="	<TD class=\"table_cell\" width=\"140\">".$fieldarray[$i*3]."</td>\n";

		$etcfield[$i]="<input type=text name=\"etc[".$i."]\" value=\"".$etc[$i]."\" size=\"".$fieldarray[$i*3+1]."\" maxlength=\"".$fieldarray[$i*3+2]."\" id=\"etc_".$i."\" class=\"input\">";

		$stretc.="	<TD class=\"td_con1\" width=\"360\">".$etcfield[$i]."</TD>\n";
		$stretc.="</tr>\n";
		$stretc.="<tr>\n";
		$stretc.="	<TD colspan=\"2\" width=\"100%\" background=\"images/table_con_line.gif\"><img src=\"images/table_con_line.gif\" width=\"4\" height=\"1\" border=\"0\"></TD>\n";
		$stretc.="</tr>\n";

		if ($field_check[$i]=="OK") {
			$scriptform.="try {\n";
			$scriptform.="	if (document.getElementById('etc_".$i."').value==0) {\n";
			$scriptform.="		alert('�ʼ��Է»����� �Է��ϼ���.');\n";
			$scriptform.="		document.getElementById('etc_".$i."').focus();\n";
			$scriptform.="		return;\n";
			$scriptform.="	}\n";
			$scriptform.="} catch (e) {}\n";
		}
	}
	$straddform.=$stretc;
}

if($mode=="modify2") {
	$sql = "UPDATE tblmember SET wholesaletype	= '".$wholesaletype."' WHERE id='".$id."' ";
	$update=mysql_query($sql,get_db_conn());
	echo "<html><head><title></title></head><body onload=\"alert('".$id." ���� ����ȸ������ ���� �Ǿ����ϴ�.'); opener.document.location.reload(); window.close()\"></body></html>";exit;
}else if($mode=="modify") {
	$onload="";
	for($i=0;$i<10;$i++) {
		if(strpos($etc[$i],"=")) {
			$onload="<script>alert('�߰������� �Է��� �� ���� ���ڰ� ���ԵǾ����ϴ�.');</script>";
			break;
		}
		if($i!=0) {
			$etcdata=$etcdata."=";
		}
		$etcdata=$etcdata.$etc[$i];
	}

	if(strlen($onload)>0) {

	} else if(strlen(trim($email))==0) {
		$onload="<script>alert(\"�̸����� �Է��ϼ���.\");</script>";
	} else if(!ismail($email)) {
		$onload="<script>alert(\"�̸��� �Է��� �߸��Ǿ����ϴ�.\");</script>";
	} else if(strlen(trim($home_tel))==0) {
		$onload="<script>alert(\"����ȭ�� �Է��ϼ���.\");</script>";
	} else {
		if(!$onload) {
			$home_post=$home_post1.$home_post2;
			$office_post=$office_post1.$office_post2;
			if($news_mail_yn=="Y" && $news_sms_yn=="Y") {
				$news_yn="Y";
			} else if($news_mail_yn=="Y") {
				$news_yn="M";
			} else if($news_sms_yn=="Y") {
				$news_yn="S";
			} else {
				$news_yn="N";
			}

			$home_addr=$home_addr1."=".$home_addr2;
			$office_addr="";
			//if(strlen($office_post)==6) $office_addr=$office_addr1."=".$office_addr2;
			$office_addr=$office_addr1."=".$office_addr2;

			$sql = "UPDATE tblmember SET ";
			$sql.= "email		= '".$email."', ";
			$sql.= "news_yn		= '".$news_yn."', ";
			$sql.= "home_post	= '".$home_post."', ";
			$sql.= "home_addr	= '".$home_addr."', ";
			$sql.= "home_tel	= '".$home_tel."', ";
			$sql.= "mobile		= '".$mobile."', ";
			$sql.= "office_post	= '".$office_post."', ";
			$sql.= "office_addr	= '".$office_addr."', ";
			$sql.= "office_tel	= '".$office_tel."', ";
			$sql.= "comp_num		= '".$comp_num."', ";
			$sql.= "comp_owner	= '".$comp_owner."', ";
			$sql.= "comp_type1	= '".$comp_type1."', ";
			$sql.= "comp_type2	= '".$comp_type2."', ";
			$sql.= "wholesaletype	= '".$wholesaletype."', ";
			$sql.= "etcdata		= '".$etcdata."', ";
			$sql.= "gubun		= '".$gubun."', ";
			$sql.= "sosok		= '".$sosok."', ";
			$sql.= "jikjong		= '".$jikjong."', ";
			$sql.= "jikgun		= '".$jikgun."' ";
			$sql.= "WHERE id='".$id."' ";
			$update=mysql_query($sql,get_db_conn());
			echo "<html><head><title></title></head><body onload=\"alert('".$id." ȸ������ �������� ������ �Ϸ�Ǿ����ϴ�.\\n\\n�����մϴ�.');window.close()\"></body></html>";
			exit;
		}
	}
}

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>ȸ������</title>
<link rel="stylesheet" href="style.css" type="text/css">
<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
document.onkeydown = CheckKeyPress;
document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;

	if(ekey == 38 || ekey == 40 || ekey == 112 || ekey ==17 || ekey == 18 || ekey == 25 || ekey == 122 || ekey == 116) {
		event.keyCode = 0;
		return false;
	}
}

function PageResize() {
	var oWidth = document.all.table_body.clientWidth + 10;
	var oHeight = document.all.table_body.clientHeight + 75;

	window.resizeTo(oWidth,oHeight);
}

function CheckForm() {
	form=document.form1;
	if(form.email.value.length==0) {
		alert("�̸����� �Է��ϼ���."); form.email.focus(); return;
	}
	if(!IsMailCheck(form.email.value)) {
		alert("�̸��� ������ �����ʽ��ϴ�.\n\nȮ���Ͻ� �� �ٽ� �Է��ϼ���."); form.email.focus(); return;
	}
	if(form.home_tel.value.length==0) {
		alert("����ȭ��ȣ�� �Է��ϼ���."); form.home_tel.focus(); return;
	}
	if(form.home_post1.value.length==0 || form.home_addr1.value.length==0) {
		alert("���ּҸ� �Է��ϼ���.");
		return;
	}
	if(form.home_addr2.value.length==0) {
		alert("���ּ��� ���ּҸ� �Է��ϼ���."); form.home_addr2.focus(); return;
	}

<?=$scriptform?>

	if(confirm("<?=$id?> ȸ������ ���������� �����Ͻðڽ��ϱ�?")==true) {
		form.mode.value="modify";
		form.submit();
	}
}

function f_addr_search(form,post,addr,gbn) {
	window.open("<?=$Dir.FrontDir?>addr_search.php?form="+form+"&post="+post+"&addr="+addr+"&gbn="+gbn,"f_post","resizable=yes,scrollbars=yes,x=100,y=200,width=370,height=250");
}

//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 oncontextmenu="return false" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">
<TABLE WIDTH="550" BORDER=0 CELLPADDING=0 CELLSPACING=0 style="table-layout:fixed;" id=table_body>
<TR>
	<TD>
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><img src="images/member_list_info_title.gif" border="0"></td>
		<td width="100%" background="images/popup_top_bg.gif"></td>
		<td align=right><img src="images/member_mailallsend_img2.gif" width="20" height="31" border="0"></td>
	</tr>
	</table>
	</TD>
</TR>
<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
<input type=hidden name=mode>
<input type=hidden name=id value="<?=$id?>">
<input type="hidden" name="wholesaletype" value="<?=($_shopdata->wholesalemember == 'Y' && in_array($wholesaletype,array('Y','R'))?$wholesaletype:'')?>" />
<tr>
	<TD style="padding:10pt;">
	<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
	<col width=140></col>
	<col width=360></col>
	<TR>
		<TD colspan=2 background="images/table_top_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�̸�</TD>
		<TD class="td_con1"><b><span class="font_orange"><?=$name?></span></b></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���̵�</TD>
		<TD class="td_con1"><b><?=$id?></b></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<? if($_shopdata->wholesalemember == 'Y' && in_array($wholesaletype,array('Y','R'))){ ?>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ڹ�ȣ</TD>
		<TD class="td_con1">
			<?
				$comp_num = explode("-",$comp_num);
			?>
			<input type=text name=comp_num0 value="<?=$comp_num[0]?>" style="width:30px;" class="input">
			-
			<input type=text name=comp_num1 value="<?=$comp_num[1]?>" style="width:20px;" class="input">
			-
			<input type=text name=comp_num2 value="<?=$comp_num[2]?>" style="width:50px;" class="input">
		</TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǥ��</TD>
		<TD class="td_con1"><input type=text name=comp_owner value="<?=$comp_owner?>" maxlength=100 style="width:30%" class="input"></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
		<TD class="td_con1"><input type=text name=comp_type1 value="<?=$comp_type1?>" maxlength=100 style="width:100%" class="input"></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</TD>
		<TD class="td_con1"><input type=text name=comp_type2 value="<?=$comp_type2?>" maxlength=100 style="width:100%" class="input"></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<? } ?>
	<? if($_shopdata->resno_type!="N"){?>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ֹε�Ϲ�ȣ</TD>
		<TD class="td_con1"><?=$resno1?> - <?=str_repeat("*",strlen($resno2))?></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<? }?>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�̸���</TD>
		<TD class="td_con1"><input type=text name=email value="<?=$email?>" maxlength=100 style="width:100%" class="input"></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ���ſ���</TD>
		<TD class="td_con1"><input type=radio id="idx_news_mail_yn0" name=news_mail_yn value="Y" <?if($news_mail_yn=="Y")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_mail_yn0>������</label> <input type=radio id="idx_news_mail_yn1" name=news_mail_yn value="N" <?if($news_mail_yn=="N")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_mail_yn1>���ž���</label></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">SMS���� ���ſ���</TD>
		<TD class="td_con1"><input type=radio id="idx_news_sms_yn0" name=news_sms_yn value="Y" <?if($news_sms_yn=="Y")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_sms_yn0>������</label> <input type=radio id="idx_news_sms_yn1" name=news_sms_yn value="N" <?if($news_sms_yn=="N")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_sms_yn1>���ž���</label></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ȭ</TD>
		<TD class="td_con1"><input type=text name=home_tel value="<?=$home_tel?>" maxlength=15 style="width:120" class="input"></TD>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ּ�</TD>
		<TD class="td_con1">
		<table cellpadding="1" cellspacing="0" width="100%">
		<col width=100></col>
		<col width=></col>
		<tr>
			<td><input type=text name="home_post1" id="home_post1" value="<?=$home_post1?>" style="width:50" readonly class="input"></td>
			<td><A class=board_list hideFocus style="selector-dummy: true" onfocus=this.blur(); href="javascript:addr_search_for_daumapi('home_post1','home_addr1','home_addr2');"><IMG src="images/icon_addra.gif" border=0 width="74" height="18"></A></td>
		</tr>
		<tr>
			<td colspan="2"><input type=text name="home_addr1" id="home_addr1" value="<?=$home_addr1?>" maxlength=100 readonly class="input" style="width:100%"></td>
		</tr>
		<tr>
			<td colspan="2"><input type=text name="home_addr2" id="home_addr2" value="<?=$home_addr2?>" maxlength=100 class="input" style="width:100%"></td>
		</tr>
		</table>
		</TD>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�����ȭ(�޴���)</TD>
		<TD class="td_con1"><input type=text name=mobile value="<?=$mobile?>" maxlength=15 style="width:120" class="input"></TD>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ���ּ�</TD>
		<TD class="td_con1">
		<table cellpadding="1" cellspacing="0" width="100%">
		<col width=100></col>
		<col width=></col>
		<tr>
			<td><input type=text name="office_post1" id="office_post1" value="<?=$office_post1?>" style="width:50" readonly class="input"></td>
			<td><A class=board_list hideFocus style="selector-dummy: true" onfocus=this.blur(); href="javascript:addr_search_for_daumapi('office_post1','office_addr1','office_addr2');"><IMG src="images/icon_addra.gif" border=0 width="74" height="18"></A></td>
		</tr>
		<tr>
			<td colspan="2"><input type=text name="office_addr1" id="office_addr1" value="<?=$office_addr1?>" maxlength=100 readonly class="input" style="width:100%"></td>
		</tr>
		<tr>
			<td colspan="2"><input type=text name="office_addr2" id="office_addr2" value="<?=$office_addr2?>" maxlength=100 class="input" style="width:100%"></td>
		</tr>
		</table>
		</TD>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ȸ������</TD>
		<TD class="td_con1">
			<input type=radio id="gubun01" name="gubun" value="�Ϲ�" <?=($gubun=="�Ϲ�")? "checked" : "";?> class="radio">
			<label for="gubun01">�Ϲ�</label>
			<input type=radio id="gubun02" name="gubun" value="������" <?=($gubun=="������")? "checked" : "";?> class="radio">
			<label for="gubun02">������</label>
			<input type=radio id="gubun03" name="gubun" value="����" <?=($gubun=="����")? "checked" : "";?> class="radio">
			<label for="gubun03">����</label>
			<input type=radio id="gubun04" name="gubun" value="�л�" <?=($gubun=="�л�")? "checked" : "";?> class="radio">
			<label for="gubun04">�л�</label>
			<input type=radio id="gubun05" name="gubun" value="���" <?=($gubun=="���")? "checked" : "";?> class="radio">
			<label for="gubun05">���</label>
		</TD>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">�Ҽ�</td>
		<td class="td_con1">
			<INPUT type="text" name="sosok" id="sosok" value="<?=$sosok?>" style="WIDTH:96%;" class="input" />
		</td>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</td>
		<td class="td_con1">
			<select name="jikjong">
				<option value="">����</option>
				<option value="1" <?=($jikjong=="1")? "selected":"";?>>��������</option>
				<option value="2" <?=($jikjong=="2")? "selected":"";?>>���δ���</option>
				<option value="4" <?=($jikjong=="4")? "selected":"";?>>����Ʈ���δ���</option>
				<option value="8" <?=($jikjong=="8")? "selected":"";?>>����/CM Song</option>
				<option value="16" <?=($jikjong=="16")? "selected":"";?>>�Կ�</option>
				<option value="32" <?=($jikjong=="32")? "selected":"";?>>����</option>
				<option value="64" <?=($jikjong=="64")? "selected":"";?>>�̼�/��Ʈ</option>
				<option value="128" <?=($jikjong=="128")? "selected":"";?>>��Ʈ����</option>
				<option value="256" <?=($jikjong=="256")? "selected":"";?>>����ũ��/�ڵ�</option>
				<option value="512" <?=($jikjong=="512")? "selected":"";?>>��ȹ��/ī��</option>
				<option value="1024" <?=($jikjong=="1024")? "selected":"";?>>�ʸ�/����/NTC</option>
				<option value="2048" <?=($jikjong=="2048")? "selected":"";?>>������ô�ü</option>
				<option value="4096" <?=($jikjong=="4096")? "selected":"";?>>������</option>
				<option value="8192" <?=($jikjong=="8192")? "selected":"";?>>����</option>
				<option value="16384" <?=($jikjong=="16384")? "selected":"";?>>�ؿ��ڵ�</option>
				<option value="32768" <?=($jikjong=="32768")? "selected":"";?>>��ü��</option>
				<option value="65536" <?=($jikjong=="65536")? "selected":"";?>>�����Կ�</option>
				<option value="131072" <?=($jikjong=="131072")? "selected":"";?>>��������</option>
				<option value="262144" <?=($jikjong=="262144")? "selected":"";?>>������ȸ��</option>
				<option value="524288" <?=($jikjong=="524288")? "selected":"";?>>�μ�</option>
				<option value="1048576" <?=($jikjong=="1048576")? "selected":"";?>>SP(����)</option>
				<option value="2097152" <?=($jikjong=="2097152")? "selected":"";?>>������/����ġ</option>
				<option value="4194304" <?=($jikjong=="4194304")? "selected":"";?>>�̺�Ʈ</option>
				<option value="8388608" <?=($jikjong=="8388608")? "selected":"";?>>�𵨿�������</option>
				<option value="16777216" <?=($jikjong=="16777216")? "selected":"";?>>�����а�</option>
				<option value="33554432" <?=($jikjong=="33554432")? "selected":"";?>>��Ÿ</option>
				<option value="67108864" <?=($jikjong=="67108864")? "selected":"";?>>���丮����</option>
				<option value="134217728" <?=($jikjong=="134217728")? "selected":"";?>>�����̼�/����</option>
			</select>
		</td>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����</td>
		<td class="td_con1">
			<select name="jikgun">
				<option value="">����</option>
				<option value="1" <?=($jikgun=="1")? "selected":"";?>>CD</option>
				<option value="2" <?=($jikgun=="2")? "selected":"";?>>PD</option>
				<option value="4" <?=($jikgun=="4")? "selected":"";?>>CW</option>
				<option value="8" <?=($jikgun=="8")? "selected":"";?>>�����̳�</option>
				<option value="16" <?=($jikgun=="16")? "selected":"";?>>AE</option>
				<option value="32" <?=($jikgun=="32")? "selected":"";?>>������</option>
				<option value="64" <?=($jikgun=="64")? "selected":"";?>>����</option>
				<option value="128" <?=($jikgun=="128")? "selected":"";?>>������</option>
				<option value="256" <?=($jikgun=="256")? "selected":"";?>>����</option>
				<option value="512" <?=($jikgun=="512")? "selected":"";?>>����/�繫</option>
				<option value="1024" <?=($jikgun=="1024")? "selected":"";?>>��Ʈ����</option>
				<option value="2048" <?=($jikgun=="2048")? "selected":"";?>>�÷���</option>
				<option value="4096" <?=($jikgun=="4096")? "selected":"";?>>TD</option>
				<option value="8192" <?=($jikgun=="8192")? "selected":"";?>>��Ÿ�ϸ���Ʈ</option>
				<option value="16384" <?=($jikgun=="16384")? "selected":"";?>>Į�󸮽�Ʈ</option>
				<option value="32768" <?=($jikgun=="32768")? "selected":"";?>>����ȫ��</option>
				<option value="65536" <?=($jikgun=="65536")? "selected":"";?>>����</option>
				<option value="131072" <?=($jikgun=="131072")? "selected":"";?>>�л�</option>
				<option value="262144" <?=($jikgun=="262144")? "selected":"";?>>��Ÿ</option>
				<option value="524288" <?=($jikgun=="524288")? "selected":"";?>>����</option>
				<option value="1048576" <?=($jikgun=="1048576")? "selected":"";?>>ĳ���õ���</option>
			</select>
		</td>
	</tr>
	<?if($recommand_type=="Y") {?>
	<tr>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��õȸ��ID</TD>
		<TD class="td_con1" width="360"><?=$str_rec?></TD>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<?}?>
	<?
	if(strlen($straddform)>0) {
		echo $straddform;
	}
	?>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ڵ����</td>
		<td class="td_con1">
			<a href="../vender/trust_download.php?dir=/data/shopimages/bizcheck/&file_name=<?=$bizcheck?>"><?=$bizcheck?></a>
		</td>
	</tr>
	<TR>
		<TD colspan=2 background="images/table_top_line.gif"></TD>
	</TR>
	</TABLE>
	</TD>
</tr>
<TR>
	<TD align=center>
		<? if($wholesaletype == 'R'){ ?><a href="javascript:allowWholesale()"><img src="images/btn_pass.gif" border="0"></a>
		<script language="javascript" type="text/javascript">
		function allowWholesale(){
			if(confirm('�ش� ȸ���� ����ȸ������ ���� �Ͻðڽ��ϱ�?')){
				document.form1.wholesaletype.value ='Y';
				document.form1.mode.value="modify2";
				document.form1.submit();
			}
		}
		</script>
		<? } ?>
		<a href="javascript:CheckForm();"><img src="images/btn_ok1.gif" width="36" height="18" border="0" hspace="2"></a>
		<a href="javascript:window.close();"><img src="images/btn_close.gif" width="36" height="18" border="0"></a>
	</TD>
</TR>
</form>
</TABLE>

<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
function addr_search_for_daumapi(post,addr1,addr2) {
	new daum.Postcode({
		oncomplete: function(data) {
			// �˾����� �˻���� �׸��� Ŭ�������� ������ �ڵ带 �ۼ��ϴ� �κ�.

			// �� �ּ��� ���� ��Ģ�� ���� �ּҸ� �����Ѵ�.
			// �������� ������ ���� ���� ��쿣 ����('')���� �����Ƿ�, �̸� �����Ͽ� �б� �Ѵ�.
			var fullAddr = ''; // ���� �ּ� ����
			var extraAddr = ''; // ������ �ּ� ����

			// ����ڰ� ������ �ּ� Ÿ�Կ� ���� �ش� �ּ� ���� �����´�.
			if (data.userSelectedType === 'R') { // ����ڰ� ���θ� �ּҸ� �������� ���
				fullAddr = data.roadAddress;

			} else { // ����ڰ� ���� �ּҸ� �������� ���(J)
				fullAddr = data.jibunAddress;
			}

			// ����ڰ� ������ �ּҰ� ���θ� Ÿ���϶� �����Ѵ�.
			if(data.userSelectedType === 'R'){
				//���������� ���� ��� �߰��Ѵ�.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// �ǹ����� ���� ��� �߰��Ѵ�.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// �������ּ��� ������ ���� ���ʿ� ��ȣ�� �߰��Ͽ� ���� �ּҸ� �����.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// �����ȣ�� �ּ� ������ �ش� �ʵ忡 �ִ´�.
			document.getElementById(post).value = data.zonecode; //5�ڸ� �������ȣ ���
			document.getElementById(addr1).value = fullAddr;

			// Ŀ���� ���ּ� �ʵ�� �̵��Ѵ�.
			if (addr2 != "") {
				document.getElementById(addr2).focus();
			}
		}
	}).open();
}
</script>

<?=$onload?>
</body>
</html>