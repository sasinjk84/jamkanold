<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	INCLUDE ("access.php");

	####################### ������ ���ٱ��� check ###############
	$PageCode = "mo-1";
	$MenuCode = "mobile";
	if (!$_usersession->isAllowedTask($PageCode)) {
		INCLUDE ("AccessDeny.inc.php");
		exit;
	}

	#########################################################

	$mode=$_POST["mode"];
	$mode_result=$_POST["mode_result"];

	$code=$_POST["code"];
	$parentcode=$_POST["parentcode"];

	$mobile_display=$_POST["up_mobile_display"];
	if($mobile_display!="N") {
		$mobile_display="Y";
	}

	if ($mode=="insert" && strlen($up_code_name)>0) {


	} else if($mode=="modify" && strlen($code)==12) {

		$codeA=substr($code,0,3);
		$codeb=substr($code,3,3);
		$codeC=substr($code,6,3);
		$codeD=substr($code,9,3);

		$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeb='".$codeb."' ";
		$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
		$result = mysql_query($sql,get_db_conn());
		$row = mysql_fetch_object($result);

		mysql_free_result($result);

		if(!$row) {
			echo "<script>parent.HiddenFrame.alert('�ش� ��ǰī�װ� ������ �������� �ʽ��ϴ�.');parent.location.reload();</script>";
			exit;
		}

		$type=$row->type;

		if ($mode_result=="result") {	//�������� ������Ʈ

			$up_code_name = ereg_replace(";","",$up_code_name);
			$sql = "UPDATE tblproductcode SET ";
			$sql.= "mobile_display		= '".$mobile_display."'";
			$sql.= "WHERE codeA = '".$codeA."' AND codeb = '".$codeb."' ";
			$sql.= "AND codeC = '".$codeC."' AND codeD = '".$codeD."' ";

			$update = mysql_query($sql,get_db_conn());

			if ($update) {

				$onload="<script>parent.HiddenFrame.alert(' ī�װ� ���迩�� ������ �Ϸ�Ǿ����ϴ�.');</script>";

			} else {

			$onload="<script>parent.HiddenFrame.alert('��ǰī�װ� ���� ������ ������ �߻��Ͽ����ϴ�.');</script>";

			}

			$sql = "SELECT * FROM tblproductcode WHERE codeA='".$codeA."' AND codeb='".$codeb."' ";
			$sql.= "AND codeC='".$codeC."' AND codeD='".$codeD."' ";
			$result = mysql_query($sql,get_db_conn());
			$row = mysql_fetch_object($result);
			mysql_free_result($result);
		}

		$type=$row->type;
		$code_name=$row->code_name;
		$list_type=$row->list_type;
		$detail_type=$row->detail_type;
		$group_code=$row->group_code;
		$sort=$row->sort;
		$special=$row->special;
		$special_cnt=$row->special_cnt;
		$islist=$row->islist;
		$arr_special=explode(",",$special);
		$old_special=$special;
		$mobile_display=$row->mobile_display;

		unset($special);

		for($i=0;$i<count($arr_special);$i++) {
			$special[$arr_special[$i]]="Y";
		}

		if(strlen($old_special)==0) {
			$old_special="1,2,3";
		} else {
			if(!eregi("1",$old_special)) {
				$old_special.=",1";
			}
			if(!eregi("2",$old_special)) {
				$old_special.=",2";
			}
			if(!eregi("3",$old_special)) {
			$old_special.=",3";
			}
		}

		$arrspecialcnt=explode(",",$special_cnt);

		for ($i=0;$i<count($arrspecialcnt);$i++) {
			if (substr($arrspecialcnt[$i],0,2)=="1:") {
				$tmpsp1=substr($arrspecialcnt[$i],2);
			} else if (substr($arrspecialcnt[$i],0,2)=="2:") {
				$tmpsp2=substr($arrspecialcnt[$i],2);
			} else if (substr($arrspecialcnt[$i],0,2)=="3:") {
				$tmpsp3=substr($arrspecialcnt[$i],2);
			}
		}
		if(strlen($tmpsp1)>0) {
			$special_1=explode("X",$tmpsp1);
			$special_1_cols=(int)$special_1[0];
			$special_1_rows=(int)$special_1[1];
			$special_1_type=$special_1[2];
		}
		if(strlen($tmpsp2)>0) {
			$special_2=explode("X",$tmpsp2);
			$special_2_cols=(int)$special_2[0];
			$special_2_rows=(int)$special_2[1];
			$special_2_type=$special_2[2];
		}
		if(strlen($tmpsp3)>0) {
			$special_3=explode("X",$tmpsp3);
			$special_3_cols=(int)$special_3[0];
			$special_3_rows=(int)$special_3[1];
			$special_3_type=$special_3[2];
		}

		if($special_1_cols<=0) $special_1_cols=5;
		if($special_1_rows<=0) $special_1_rows=1;
		if(strlen($special_1_type)==0) $special_1_type="I";
		if($special_2_cols<=0) $special_2_cols=5;
		if($special_2_rows<=0) $special_2_rows=1;
		if(strlen($special_2_type)==0) $special_2_type="I";
		if($special_3_cols<=0) $special_3_cols=5;
		if($special_3_rows<=0) $special_3_rows=1;
		if(strlen($special_3_type)==0) $special_3_type="I";

		$type1=substr($type,0,1);

		if (ereg("X",$type)) {
			$type2="1";	//����ī�װ� ����
		} else {
			$type2="0";	//����ī�װ� ����
		}

		$gong="N";
		if (substr($row->list_type,0,1)=="b") {
			$gong="Y";
		}

		$code_loc = "";
		$sql = "SELECT code_name,type FROM tblproductcode WHERE codeA='".substr($code,0,3)."' ";

		if(substr($code,3,3)!="000") {
			$sql.= "AND (codeb='".substr($code,3,3)."' OR codeb='000') ";
			if(substr($code,6,3)!="000") {
				$sql.= "AND (codeC='".substr($code,6,3)."' OR codeC='000') ";
					if(substr($code,9,3)!="000") {
						$sql.= "AND (codeD='".substr($code,9,3)."' OR codeD='000') ";
					} else {
						$sql.= "AND codeD='000' ";
					}
			} else {
				$sql.= "AND codeC='000' ";
			}
		} else {
			$sql.= "AND codeb='000' AND codeC='000' ";
		}

		$sql.= "ORDER bY codeA,codeb,codeC,codeD ASC ";

		$result=mysql_query($sql,get_db_conn());
		$i=0;

		while($row=mysql_fetch_object($result)) {
			if($i>0) $code_loc.= " > ";
			$code_loc.= $row->code_name;
			$i++;
		}
		mysql_free_result($result);

	} else {

		$mode="insert";

	}

?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>LH.add("parent_resizeIframe('PropertyFrame')");</script>

<script language="JavaScript">
	<!--
	function DesignList(idx) {
		document.form1.gong[idx].checked=true;
			if(document.form1.gong[0].checked==true) {
				gong="N";
			} else {
				gong="Y";
			}
		up_list_type=document.form1.up_list_type.value;
		window.open("design_productlist.php?code="+up_list_type+"&gong="+gong,"design","height=450,width=380,scrollbars=yes");
	}

	function DesignDetail(idx) {
		document.form1.gong[idx].checked=true;
			if(document.form1.gong[0].checked==true) {
				gong="N";
			} else {
				gong="Y";
			}
		up_detail_type=document.form1.up_detail_type.value;
		window.open("design_productdetail.php?code="+up_detail_type+"&gong="+gong,"design2","height=450,width=380,scrollbars=yes");
	}

	function ChangeSequence() {
		txt=document.form1.fcode.options[document.form1.fcode.selectedIndex].text;
		if((num=txt.indexOf("(�����ī�װ�)"))>0) {
			document.form1.selectedfcodename.value=txt.substr(0,num);
		} else {
			document.form1.selectedfcodename.value = txt;
		}
	}

	function Save() {

		mode = document.form1.mode.value;
		document.form1.submit();
	}
//-->
</script>
<?
if($mode=="insert") {
?>
<!-- ���ʷε��� sks -->
<table border="0" cellpadding="0" cellspacing="0" width="520" height="400">
	<tr>
		<td width="100%" bgcolor="#FFFFFF"><img src="images/product_cate_function_title.gif" width="91" height="24" alt=""></td>
	</tr>
	<tr>
		<td width="100%" height="100%" valign="top" style="border:#FF8730 2px solid;padding-left:5px;padding-right:5px;">
			<div style="text-align:center;padding-top:50px">����� �������� ������ ī�װ��� ������ ����ϼ���~</div>
		</td>
	</tr>
</table>
<?
} else{
?>
<table border="0" cellpadding="0" cellspacing="0" width="520" height="100%">
	<tr>
		<td width="100%" bgcolor="#FFFFFF"><img src="images/product_cate_function_title.gif" width="91" height="24" alt=""></td>
	</tr>
	<tr>
		<td width="100%" height="100%" valign="top" style="bORDER:#FF8730 2px solid;padding-left:5px;padding-right:5px;">
			<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post onsubmit="return false">
				<table cellspacing=0 cellpadding=0 width="100%" border=0>
					<col width=141></col>
					<col width=""></col>
					<tr>
						<td colspan="2" height="10"></td>
					</tr>
					<tr>
						<td colspan="2" background="images/table_con_line.gif"></td>
					</tr>
					<tr>
						<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b>ī�װ� �ڵ�</b></td>
						<td class="td_con1"><b><?=$code?></b></td>
					</tr>
					<tr>
						<td colspan="2" background="images/table_con_line.gif"></td>
					</tr>
					<tr>
						<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0"><b>ī�װ���</b></td>
						<td class="td_con1"><?=htmlspecialchars($code_name)?></td>
					</tr>
					<tr>
						<td colspan="2" background="images/table_con_line.gif"></td>
					</tr>
					<tr>
						<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ī�װ���ġ</td>
						<td class="td_con1"><?=$code_loc?></td>
					</tr>
					<tr>
						<td colspan="2" background="images/table_con_line.gif"></td>
					</tr>
					<tr>
						<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ī�װ�Ÿ��</td>
						<td class="td_con1">
							<?
								if ($mode=="modify" || (strlen($parentcode)==12 && strlen($type1)>0)) {
									if ($type1=="L") {
										echo "�⺻ ī�װ�";
									} else if ($type1=="T") {
										echo "���� ī�װ�";
									}
								} else {
									echo "<input type=radio id=\"idx_type1_1\" name=up_type1 value=\"L\" checked style=\"bORDER-RIGHT: medium none; bORDER-TOP: medium none; bORDER-LEFT: medium none; bORDER-bOTTOM: medium none;\"><label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_type1_1>�⺻ ī�װ�</label> <input type=radio id=\"idx_type1_2\" name=up_type1 value=\"T\" style=\"bORDER-RIGHT: medium none; bORDER-TOP: medium none; bORDER-LEFT: medium none; bORDER-bOTTOM: medium none;\"><label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" for=idx_type1_2>���� ī�װ�</label>";
								}
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2" background="images/table_con_line.gif"></td>
					</tr>
					<tr>
						<td class="table_cell">
							<img src="images/icon_point2.gif" width="8" height="11" border="0">����ī�װ�����
						</td>
						<td class="td_con1">
							<?
								if ($mode=="modify" || (strlen($parentcode)==12 && $type2==1)) {
									if ($type2=="0") {
										echo "����ī�װ� ����";
									} else {
										echo "����ī�װ� ����";
									}
								} else {
									echo "<input type=radio id=\"idx_type2_1\" name=up_type2 value=\"0\" checked style=\"bORDER-RIGHT: medium none; bORDER-TOP: medium none; bORDER-LEFT: medium none; bORDER-bOTTOM: medium none;\"><label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" onclick=\"javascript:alert('ī�װ� ��Ͻ� �ѹ� ������ ����ī�װ������� ������ �Ұ��� �ϹǷ� ������ ������ �ּ���.');\" for=idx_type2_1>����ī�װ� ����</label> <input id=\"idx_type2_2\" type=radio name=up_type2 value=\"1\" style=\"border-right: medium none; border-top: medium none; border-left: medium none; border-bottom: medium none;\"><label style='cursor:hand;' onmouseover=\"style.textDecoration='underline'\" onmouseout=\"style.textDecoration='none'\" onclick=\"javascript:alert('ī�װ� ��Ͻ� �ѹ� ������ ����ī�װ������� ������ �Ұ��� �ϹǷ� ������ ������ �ּ���.');\" for=idx_type2_2>����ī�װ� ����</labal>";
								}
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2" background="images/table_con_line.gif"></td>
					</tr>
					<tr>
						<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">���ٰ��� ȸ�����</td>
						<td class="td_con1">
							<?
								if($group_code=="NO") {
									echo "disabled";
								}
							?>
							<?
								$gcode_array = array("","ALL");
								$gname_array = array("����� ���ٰ���","���θ� ȸ���� ���ٰ���");
								$sql = "SELECT group_code,group_name FROM tblmembergroup ";
								$result = mysql_query($sql,get_db_conn());
								$num=2;

								while($row = mysql_fetch_object($result)) {
									$gcode_array[$num]=$row->group_code;
									$gname_array[$num++]=$row->group_name;
								}
								mysql_free_result($result);

								for($i=0;$i<$num;$i++){
									if($group_code==$gcode_array[$i]){
										echo $gname_array[$i];
									}
								}
							?>

						</td>
					</tr>
					<tr>
						<td colspan="2" background="images/table_con_line.gif"></td>
					</tr>
					<tr>
						<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">��ǰ����</td>
						<td class="td_con1">
							<?
								if ($sort=="date") {
									$str = "��ǰ ���/������¥ ����";
								} else if ($sort=="date2"){
									$str = "��ǰ ���/������¥ ���� + ǰ����ǰ �ڷ�";
								} else if ($sort=="productname") {
									$str = "��ǰ�� ������ ����";
								} else if ($sort=="production") {
									$str = "������ ������ ����";
								} else if ($sort=="price") {
									$str = "��ǰ �ǸŰ��� ����";
								}

								echo $str;
							?>
						</td>
					</tr>
					<tr>
						<td colspan="2" background="images/table_con_line.gif"></td>
					</tr>
					<tr>
						<td class="table_cell"><img src="images/icon_point2.gif" width="8" height="11" border="0">ī�װ� ���迩��</td>
						<td class="td_con1"><? if($group_code=="NO") { echo "�����"; } else {	echo "����";}?> </td>
					</tr>
					<tr>
						<td colspan="2" background="images/table_con_line.gif"></td>
					</tr>
					<tr>
						<td class="table_cell" width="140"><img src="images/icon_point2.gif" width="8" height="11" border="0">����ϼ�����<br />&nbsp;&nbsp;ī�װ� ���迩��</td>
						<td class="td_con1">
							<input type=radio  name=up_mobile_display value="N" <? if($mobile_display=="N") echo "checked";?>> <span style="color:#ff6600"><strong>�����</strong></span>&nbsp;
							<input type=radio  name=up_mobile_display value="Y" <? if($mobile_display=="Y") echo "checked";?>> <span style="color:#ff6600"><strong>���̱�</strong></span>
						</td>
					</tr>
					<tr>
						<td colspan="2" background="images/table_con_line.gif"></td>
					</tr>
					<tr>
						<td colspan="2" height="10"></td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<a href="javascript:Save();"><img src="images/botteon_catemodify.gif" width="118" height="38" border="0" hspace="0"></a>&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan="2" height="10"></td>
					</tr>
				</table>
				<input type=hidden name=mode value="<?=$mode?>">
				<input type=hidden name=code value="<?=$code?>">
				<input type=hidden name=parentcode value="<?=$parentcode?>">
				<input type=hidden name=mode_result value="result">
				<input type=hidden name=up_list_type value="<?=$list_type?>">
				<input type=hidden name=up_detail_type value="<?=$detail_type?>">
				<input type=hidden name=old_special value="<?=$old_special?>">
				<input type=hidden name=up_special>
			</form>
		</td>
	</tr>
</table>
</form>
<form name=form2 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=mode>
<input type=hidden name=code>
</form>
<?
}
?>
<?=$onload?>
</body>
</html>