<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$type=$_POST["type"];
$coupon_code=$_POST["coupon_code"];

$userlist=$_POST["userlist"];
$gubun=$_POST["gubun"];
$clicknum=$_POST["clicknum"];

if($gubun!="ALL" && $gubun!="MEMBER") $gubun="MEMBER";

if($type=="result") {
	if($gubun=="ALL") {
		$member="ALL";
		$sql = "UPDATE tblcouponinfo SET member='".$member."', display='Y' ";
		$sql.= "WHERE coupon_code='".$coupon_code."' ";
		$sql.= "AND vender='".$_VenderInfo->getVidx()."' ";
		mysql_query($sql,get_db_conn());

		echo "<html></head><body onload=\"alert('�ش� ���������� �߱޵Ǿ����ϴ�.\\n�α��ν� �ش� ȸ������ �ڵ� �߱޵˴ϴ�.');parent.location.href='".$_SERVER[PHP_SELF]."'\"></body></html>";exit;
	} else if($gubun=="MEMBER") {
		$sql = "SELECT id FROM tblcouponissue WHERE coupon_code='".$coupon_code."' ";
		$result = mysql_query($sql,get_db_conn());
		$i=0;
		while($row = mysql_fetch_object($result)) {
			$patten[$i]="(,".$row->id.",)";
			$replace[$i]=",";
			$i++;
		}
		mysql_free_result($result);
		if($i>0) $userlist = preg_replace($patten,$replace,$userlist.",");
		else $userlist.=",";
		$aruser = explode(",",$userlist);
		$cnt = count($aruser)-1;
		if($cnt>=1) {
			$date = date("YmdHis");
			$sql = "SELECT date_start,date_end FROM tblcouponinfo WHERE coupon_code='".$coupon_code."' AND vender='".$_VenderInfo->getVidx()."' AND member='' ";
			$result = mysql_query($sql,get_db_conn());
			if($row = mysql_fetch_object($result)){
				if($row->date_start>0) {
					$date_start=$row->date_start;
					$date_end=$row->date_end;
				} else {
					$date_start = substr($date,0,10);
					$date_end = date("Ymd",mktime(0,0,0,substr($date,4,2),substr($date,6,2)+abs($row->date_start),substr($date,0,4)))."23";
				}
				$sql = "INSERT INTO tblcouponissue (coupon_code,id,date_start,date_end,date) VALUES ";
				for($i=1;$i<$cnt;$i++){
					$sql.=" ('".$coupon_code."','".addslashes($aruser[$i])."','".$date_start."','".$date_end."','".$date."'),";
				}
				$sql=substr($sql,0,-1);
				mysql_query($sql,get_db_conn());

				if(!mysql_errno()) {
					$cnt--;
					$sql = "UPDATE tblcouponinfo SET display='Y', issue_no=issue_no+$cnt ";
					$sql.= "WHERE coupon_code='".$coupon_code."'";
					mysql_query($sql,get_db_conn());
					echo "<html></head><body onload=\"alert('�ش� ���������� �߱޵Ǿ����ϴ�.');parent.location.href='".$_SERVER[PHP_SELF]."'\"></body></html>";exit;
				}
			} else {   
				echo "<html></head><body onload=\"alert('�����ڵ尡 �߸��Ǿ����ϴ�.')\"></body></html>";exit;
			}
		} else {
			echo "<html></head><body onload=\"alert('���� �߱��� ȸ���� �����ϴ�.')\"></body></html>";exit;
		}
	}
}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
function CheckForm() {
	form=document.form2;
	if(form.coupon_code.value.length==0) {
		alert("�߱��� ���� ������ �ϼž� �մϴ�.");
		return;
	}
	if (form.gubun[1].checked==true && form.alluser.options.length<=0) {
		alert("���� �߱��� �ܰ�ȸ�� �߰��� �Ͻñ� �ٶ��ϴ�.");
		return;
	}

	if(form.gubun[1].checked==true) {
		form2.userlist.value="";
		for(i=1;i<form.alluser.options.length;i++) {
			form.userlist.value+=","+form.alluser.options[i].value;
		}
		if(form.userlist.value.length==0) {
			alert("������ �߱��� ȸ���� �����ϼ���.");
			form.alluser.focus();
			return;
		}
	}

	if(confirm("���������� �߱��Ͻðڽ��ϱ�?")) {
		form.type.value="result";
		form.target="processFrame";
		form.submit();
	}
}

function ChoiceCoupon(code) {
	document.form1.type.value="choice";
	document.form1.coupon_code.value=code;
	document.form1.submit();
}

function CouponView(code) {
	window.open("about:blank","couponview","width=650,height=650,scrollbars=no");
	document.cform.coupon_code.value=code;
	document.cform.submit();
}

function ChangeType(val) {
	if(val.length==0 || val=="ALL") {
		document.form2.id.disabled=true;
		document.form2.search_mem.disabled=true;
		document.form2.mem_add.disabled=true;
		document.form2.mem_del.disabled=true;
		document.form2.alluser.disabled=true;
	} else if (val=="MEMBER") {
		document.form2.id.disabled=false;
		document.form2.search_mem.disabled=false;
		document.form2.mem_add.disabled=false;
		document.form2.mem_del.disabled=false;
		document.form2.alluser.disabled=false;
	}
}

function FindMember() {
	 document.form2.gubun[1].checked=true;
	 if(document.form2.coupon_code.value.length==0){
		alert('�߱��� ���ϴ� ������ ���� �����ϼ���');
		return;
	 }
	 window.open("about:blank","findmember","width=250,height=150,scrollbars=yes");
	 document.mform.submit();
}

function ToAdd() {
	id=document.form2.id.value;
	if(id.length==0) {
		alert("ȸ��ID�� �����Ͻñ� �ٶ��ϴ�.");
		FindMember();
		return;
	}
	alluser=document.form2.alluser;
	for(i=1;i<alluser.options.length;i++) {
		if(id==alluser.options[i].value) {
			alert("�̹� �߰��� ID�Դϴ�.\n\n�ٽ� Ȯ���Ͻñ� �ٶ��ϴ�.");
			document.form2.id.value="";
			return;
		}
	}

	new_option = document.createElement("OPTION");
	new_option.text=id;
	new_option.value=id;
	alluser.add(new_option);
	cnt=alluser.options.length - 1;
	alluser.options[0].text = "-------------------- ȸ�� �����߱� ���("+cnt+") --------------------";
	document.form2.id.value="";
}

function ToDelete() {
	alluser=document.form2.alluser;
	for(i=1;i<alluser.options.length;i++) {
		if(alluser.options[i].selected==true){
			alluser.options[i]=null;
			cnt=alluser.options.length - 1;
			alluser.options[0].text = "-------------------- ȸ�� �����߱� ���("+cnt+") --------------------";
			return;
		}
	}
	alert("������ ID�� �����ϼ���.");
	alluser.focus();
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
					<td><img src="images/coupon_supply_title.gif"></td>
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
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�����߱� ������ ��üȸ��/����ȸ��(���̵���ȸ) �߱��� �� �ֽ��ϴ�.</td>
										</tr>
										<tr>
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�����ڵ带 Ŭ���Ͻø� �߱޵� ������ ���� �������� ���� �� �ֽ��ϴ�.</td>
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
				



				
				<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
				<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=type>
				<input type=hidden name=coupon_code value="<?=$coupon_code?>">
				<tr>
					<td><img src="images/coupon_supply_stitle01.gif" border=0 align=absmiddle alt="���� �߱��� ���� ����"></td>
				</tr>
				<tr><td height=10></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				<tr>
					<td bgcolor=E7E7E7>
					<table width=100% border=0 cellspacing=1 cellpadding=0 style="table-layout:fixed">
					<col width=40></col>
					<col width=80></col>
					<col width=></col>
					<col width=80></col>
					<col width=90></col>
					<col width=130></col>
					<tr height=35 align=center bgcolor=F5F5F5>
						<td align=center><B>����</B></td>
						<td align=center><B>�����ڵ�</B></td>
						<td align=center><B>������</B></td>
						<td align=center><B>������<B></td>
						<td align=center><B>�ݾ�/������</B></td>
						<td align=center><B>��ȿ�Ⱓ</B></td>
					</tr>
<?
					$colspan=6;
					$sql = "SELECT * FROM tblcouponinfo WHERE vender='".$_VenderInfo->getVidx()."' ";
					$sql.= "AND issue_type='N' AND member='' ";
					$result = mysql_query($sql,get_db_conn());
					$cnt=0;
					while($row=mysql_fetch_object($result)) {
						$cnt++;
						if($row->sale_type<=2) $dan="%";
						else $dan="��";
						if($row->sale_type%2==0) $sale = "����";
						else $sale = "����";
						if($row->date_start>0) {
							$date = substr($row->date_start,2,2).".".substr($row->date_start,4,2).".".substr($row->date_start,6,2)." ~ ".substr($row->date_end,2,2).".".substr($row->date_end,4,2).".".substr($row->date_end,6,2);
						} else {
							$date = abs($row->date_start)."�ϵ���";
						}
						echo "<tr height=30 bgcolor=#FFFFFF>\n";
						echo "	<td align=center><input type=checkbox name=ckbox ".($coupon_code==$row->coupon_code?"checked":"")." onclick=\"ChoiceCoupon('".$row->coupon_code."')\"></td>\n";
						echo "	<td align=center><A HREF=\"javascript:CouponView('".$row->coupon_code."');\"><B>".$row->coupon_code."</B></A></td>\n";
						echo "	<td style=\"padding-left:5;color:#003399\"><nobr>".$row->coupon_name."</td>\n";
						echo "	<td align=center>".substr($row->date,0,4).".".substr($row->date,4,2).".".substr($row->date,6,2)."</td>\n";
						echo "	<td align=center><font color=\"".($sale=="����"?"#FF0000":"#0000FF")."\">".number_format($row->sale_money).$dan." ".$sale."</td>\n";
						echo "	<td align=center>".$date."</td>\n";
						echo "</tr>\n";
					}
					mysql_free_result($result);
					if($cnt==0) {
						echo "<tr><td height=30 bgcolor=#FFFFFF colspan=".$colspan." align=center>�߱޵� ������ �����ϴ�. ������ �����Ͻ� �� �߱��Ͻñ� �ٶ��ϴ�.</td></tr>\n";
					}
?>
					</table>
					</td>
				</tr>
				</form>

				<tr><td height=20></td></tr>

				<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
				<input type=hidden name=type>
				<input type=hidden name=coupon_code value="<?=$coupon_code?>">
				<input type=hidden name=userlist>

				<tr>
					<td><img src="images/coupon_supply_stitle02.gif" border=0 align=absmiddle alt="�߱��� ȸ�� ����"></td>
				</tr>
				<tr><td height=10></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				<tr>
					<td bgcolor=E7E7E7>
					<table width=100% border=0 cellspacing=1 cellpadding=0 style="table-layout:fixed">
					<tr bgcolor=FFFFFF>
						<td height=33 style="padding:7,10">
						<input type=radio id="idx_gubun1" name=gubun value="ALL" onclick="ChangeType(this.value) ;" <?=($gubun=="ALL"?"checked":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_gubun1><B>�ܰ���� ���ȸ�� ��ü �߱�</B></label> <span class="notice_blue">* �ܰ�������� ����� ��� ȸ��</span>
						</td>
					</tr>
					<tr bgcolor=FFFFFF>
						<td height=33 style="padding:7,10">
						<input type=radio id="idx_gubun3" name=gubun value="MEMBER" onclick="ChangeType(this.value) ;" <?=($gubun=="MEMBER"?"checked":"")?>><label style='cursor:hand;' onmouseover="style.textDecoration='underline'" onmouseout="style.textDecoration='none'" for=idx_gubun3><B>�ܰ���� ���ȸ�� �����߱�</B></label>
						<img width=10 height=0>
						ȸ��ID : <input class="input" type=text name=id onfocus="blur()" onclick="FindMember()" style="width:120">
						<input type=button name="search_mem" value="ȸ��ID ��ȸ" class=button onclick="FindMember()">
						<img width=10 height=0>
						<input type=button value="�߰�" name=mem_add class=button onClick="ToAdd();">
						<input type=button value="����" name=mem_del class=button onClick="ToDelete();">
						</td>
					</tr>
					<tr bgcolor=FFFFFF>
						<td style="padding:20">
						<select name=alluser size=12 style="width:380;">
						<option value="" style="background-color:#FFFF00">-------------------- ȸ�� �����߱� ���(0) --------------------</option>
						</select>
						</td>
					</tr>
					</table>
					<script>ChangeType('<?=$gubun?>');</script>
					</td>
				</tr>
				<tr><td height=20></td></tr>
				<tr>
					<td align=center>
					<A HREF="javascript:CheckForm()"><img src=images/btn_couponsupply.gif border=0></A>
					</td>
				</tr>
				</form>

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

<form name=mform action="member_find.php" method=post target=findmember>
<input type=hidden name=formname value="form2">
</form>

<form name=cform action="coupon_view.php" method=post target=couponview>
<input type=hidden name=coupon_code>
</form>

</table>

<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>