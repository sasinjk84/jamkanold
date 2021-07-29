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
		$str_rec="추천인 없음";
	} else {
		$str_rec=$rec_id;
	}
	if($recommand_type=="Y") {
		$sql = "SELECT rec_cnt FROM tblrecommendmanager ";
		$sql.= "WHERE rec_id='".$id."' ";
		$result2= mysql_query($sql,get_db_conn());
		if($row2=mysql_fetch_object($result2)) {
			$str_rec.=" <b><font color=#3A3A3A> ".$row2->rec_cnt."명이 당신을 추천하셨습니다.</font></b>";
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
	$straddform.="	<TD height=\"30\" colspan=2 align=center><B>추가정보를 입력하세요.</B></td>\n";
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
			$scriptform.="		alert('필수입력사항을 입력하세요.');\n";
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
	echo "<html><head><title></title></head><body onload=\"alert('".$id." 님이 도매회원으로 승인 되었습니다.'); opener.document.location.reload(); window.close()\"></body></html>";exit;
}else if($mode=="modify") {
	$onload="";
	for($i=0;$i<10;$i++) {
		if(strpos($etc[$i],"=")) {
			$onload="<script>alert('추가정보에 입력할 수 없는 문자가 포함되었습니다.');</script>";
			break;
		}
		if($i!=0) {
			$etcdata=$etcdata."=";
		}
		$etcdata=$etcdata.$etc[$i];
	}

	if(strlen($onload)>0) {

	} else if(strlen(trim($email))==0) {
		$onload="<script>alert(\"이메일을 입력하세요.\");</script>";
	} else if(!ismail($email)) {
		$onload="<script>alert(\"이메일 입력이 잘못되었습니다.\");</script>";
	} else if(strlen(trim($home_tel))==0) {
		$onload="<script>alert(\"집전화를 입력하세요.\");</script>";
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
			echo "<html><head><title></title></head><body onload=\"alert('".$id." 회원님의 개인정보 수정이 완료되었습니다.\\n\\n감사합니다.');window.close()\"></body></html>";
			exit;
		}
	}
}

?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>회원정보</title>
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
		alert("이메일을 입력하세요."); form.email.focus(); return;
	}
	if(!IsMailCheck(form.email.value)) {
		alert("이메일 형식이 맞지않습니다.\n\n확인하신 후 다시 입력하세요."); form.email.focus(); return;
	}
	if(form.home_tel.value.length==0) {
		alert("집전화번호를 입력하세요."); form.home_tel.focus(); return;
	}
	if(form.home_post1.value.length==0 || form.home_addr1.value.length==0) {
		alert("집주소를 입력하세요.");
		return;
	}
	if(form.home_addr2.value.length==0) {
		alert("집주소의 상세주소를 입력하세요."); form.home_addr2.focus(); return;
	}

<?=$scriptform?>

	if(confirm("<?=$id?> 회원님의 개인정보를 수정하시겠습니까?")==true) {
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
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">이름</TD>
		<TD class="td_con1"><b><span class="font_orange"><?=$name?></span></b></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">아이디</TD>
		<TD class="td_con1"><b><?=$id?></b></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<? if($_shopdata->wholesalemember == 'Y' && in_array($wholesaletype,array('Y','R'))){ ?>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">사업자번호</TD>
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
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">대표자</TD>
		<TD class="td_con1"><input type=text name=comp_owner value="<?=$comp_owner?>" maxlength=100 style="width:30%" class="input"></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">업태</TD>
		<TD class="td_con1"><input type=text name=comp_type1 value="<?=$comp_type1?>" maxlength=100 style="width:100%" class="input"></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">종목</TD>
		<TD class="td_con1"><input type=text name=comp_type2 value="<?=$comp_type2?>" maxlength=100 style="width:100%" class="input"></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<? } ?>
	<? if($_shopdata->resno_type!="N"){?>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">주민등록번호</TD>
		<TD class="td_con1"><?=$resno1?> - <?=str_repeat("*",strlen($resno2))?></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<? }?>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">이메일</TD>
		<TD class="td_con1"><input type=text name=email value="<?=$email?>" maxlength=100 style="width:100%" class="input"></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">메일정보 수신여부</TD>
		<TD class="td_con1"><input type=radio id="idx_news_mail_yn0" name=news_mail_yn value="Y" <?if($news_mail_yn=="Y")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_mail_yn0>수신함</label> <input type=radio id="idx_news_mail_yn1" name=news_mail_yn value="N" <?if($news_mail_yn=="N")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_mail_yn1>수신안함</label></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<TR>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">SMS정보 수신여부</TD>
		<TD class="td_con1"><input type=radio id="idx_news_sms_yn0" name=news_sms_yn value="Y" <?if($news_sms_yn=="Y")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_sms_yn0>수신함</label> <input type=radio id="idx_news_sms_yn1" name=news_sms_yn value="N" <?if($news_sms_yn=="N")echo"checked";?>><label style='cursor:hand;' onMouseOver="style.textDecoration='underline'" onMouseOut="style.textDecoration='none'" for=idx_news_sms_yn1>수신안함</label></TD>
	</TR>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">집전화</TD>
		<TD class="td_con1"><input type=text name=home_tel value="<?=$home_tel?>" maxlength=15 style="width:120" class="input"></TD>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">집주소</TD>
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
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">비상전화(휴대폰)</TD>
		<TD class="td_con1"><input type=text name=mobile value="<?=$mobile?>" maxlength=15 style="width:120" class="input"></TD>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">회사주소</TD>
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
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">회원구분</TD>
		<TD class="td_con1">
			<input type=radio id="gubun01" name="gubun" value="일반" <?=($gubun=="일반")? "checked" : "";?> class="radio">
			<label for="gubun01">일반</label>
			<input type=radio id="gubun02" name="gubun" value="전문가" <?=($gubun=="전문가")? "checked" : "";?> class="radio">
			<label for="gubun02">전문가</label>
			<input type=radio id="gubun03" name="gubun" value="교수" <?=($gubun=="교수")? "checked" : "";?> class="radio">
			<label for="gubun03">교수</label>
			<input type=radio id="gubun04" name="gubun" value="학생" <?=($gubun=="학생")? "checked" : "";?> class="radio">
			<label for="gubun04">학생</label>
			<input type=radio id="gubun05" name="gubun" value="기업" <?=($gubun=="기업")? "checked" : "";?> class="radio">
			<label for="gubun05">기업</label>
		</TD>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">소속</td>
		<td class="td_con1">
			<INPUT type="text" name="sosok" id="sosok" value="<?=$sosok?>" style="WIDTH:96%;" class="input" />
		</td>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">직종</td>
		<td class="td_con1">
			<select name="jikjong">
				<option value="">선택</option>
				<option value="1" <?=($jikjong=="1")? "selected":"";?>>광고대행사</option>
				<option value="2" <?=($jikjong=="2")? "selected":"";?>>프로덕션</option>
				<option value="4" <?=($jikjong=="4")? "selected":"";?>>포스트프로덕션</option>
				<option value="8" <?=($jikjong=="8")? "selected":"";?>>녹음/CM Song</option>
				<option value="16" <?=($jikjong=="16")? "selected":"";?>>촬영</option>
				<option value="32" <?=($jikjong=="32")? "selected":"";?>>조명</option>
				<option value="64" <?=($jikjong=="64")? "selected":"";?>>미술/셋트</option>
				<option value="128" <?=($jikjong=="128")? "selected":"";?>>아트디렉터</option>
				<option value="256" <?=($jikjong=="256")? "selected":"";?>>메이크업/코디</option>
				<option value="512" <?=($jikjong=="512")? "selected":"";?>>기획사/카피</option>
				<option value="1024" <?=($jikjong=="1024")? "selected":"";?>>필름/현상/NTC</option>
				<option value="2048" <?=($jikjong=="2048")? "selected":"";?>>광고관련단체</option>
				<option value="4096" <?=($jikjong=="4096")? "selected":"";?>>광고주</option>
				<option value="8192" <?=($jikjong=="8192")? "selected":"";?>>성우</option>
				<option value="16384" <?=($jikjong=="16384")? "selected":"";?>>해외코디</option>
				<option value="32768" <?=($jikjong=="32768")? "selected":"";?>>매체사</option>
				<option value="65536" <?=($jikjong=="65536")? "selected":"";?>>사진촬영</option>
				<option value="131072" <?=($jikjong=="131072")? "selected":"";?>>사진제판</option>
				<option value="262144" <?=($jikjong=="262144")? "selected":"";?>>디자인회사</option>
				<option value="524288" <?=($jikjong=="524288")? "selected":"";?>>인쇄</option>
				<option value="1048576" <?=($jikjong=="1048576")? "selected":"";?>>SP(옥외)</option>
				<option value="2097152" <?=($jikjong=="2097152")? "selected":"";?>>마케팅/리서치</option>
				<option value="4194304" <?=($jikjong=="4194304")? "selected":"";?>>이벤트</option>
				<option value="8388608" <?=($jikjong=="8388608")? "selected":"";?>>모델에이젼시</option>
				<option value="16777216" <?=($jikjong=="16777216")? "selected":"";?>>관련학과</option>
				<option value="33554432" <?=($jikjong=="33554432")? "selected":"";?>>기타</option>
				<option value="67108864" <?=($jikjong=="67108864")? "selected":"";?>>스토리보드</option>
				<option value="134217728" <?=($jikjong=="134217728")? "selected":"";?>>로케이션/헌팅</option>
			</select>
		</td>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_con_line.gif"></TD>
	</TR>
	<tr>
		<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">직군</td>
		<td class="td_con1">
			<select name="jikgun">
				<option value="">선택</option>
				<option value="1" <?=($jikgun=="1")? "selected":"";?>>CD</option>
				<option value="2" <?=($jikgun=="2")? "selected":"";?>>PD</option>
				<option value="4" <?=($jikgun=="4")? "selected":"";?>>CW</option>
				<option value="8" <?=($jikgun=="8")? "selected":"";?>>디자이너</option>
				<option value="16" <?=($jikgun=="16")? "selected":"";?>>AE</option>
				<option value="32" <?=($jikgun=="32")? "selected":"";?>>마케팅</option>
				<option value="64" <?=($jikgun=="64")? "selected":"";?>>감독</option>
				<option value="128" <?=($jikgun=="128")? "selected":"";?>>조감독</option>
				<option value="256" <?=($jikgun=="256")? "selected":"";?>>조수</option>
				<option value="512" <?=($jikgun=="512")? "selected":"";?>>관리/사무</option>
				<option value="1024" <?=($jikgun=="1024")? "selected":"";?>>아트디렉터</option>
				<option value="2048" <?=($jikgun=="2048")? "selected":"";?>>플래너</option>
				<option value="4096" <?=($jikgun=="4096")? "selected":"";?>>TD</option>
				<option value="8192" <?=($jikgun=="8192")? "selected":"";?>>스타일리스트</option>
				<option value="16384" <?=($jikgun=="16384")? "selected":"";?>>칼라리스트</option>
				<option value="32768" <?=($jikgun=="32768")? "selected":"";?>>광고홍보</option>
				<option value="65536" <?=($jikgun=="65536")? "selected":"";?>>음악</option>
				<option value="131072" <?=($jikgun=="131072")? "selected":"";?>>학생</option>
				<option value="262144" <?=($jikgun=="262144")? "selected":"";?>>기타</option>
				<option value="524288" <?=($jikgun=="524288")? "selected":"";?>>가수</option>
				<option value="1048576" <?=($jikgun=="1048576")? "selected":"";?>>캐스팅디렉터</option>
			</select>
		</td>
	</tr>
	<?if($recommand_type=="Y") {?>
	<tr>
		<TD class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">추천회원ID</TD>
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
		<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">사업자등록증</td>
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
			if(confirm('해당 회원을 도매회원으로 승인 하시겠습니까?')){
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
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if(data.userSelectedType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById(post).value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById(addr1).value = fullAddr;

			// 커서를 상세주소 필드로 이동한다.
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