<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include ("access.php");

	@set_time_limit(300);

	#########################################################


	$max=50;

	$Scrolltype=$_GET["Scrolltype"];

	$mode=$_GET["mode"];
	$selcodes=$_GET["selcodes"];
	$change=$_GET["change"];
	$prcode=$_GET["prcode"];
	$selcode=$_GET["selcode"];

	if ($mode=="sequence" && $change=="Y" && strlen($selcodes)>0 && strlen($pm_idx)>0) {
		$sql = "UPDATE tblmobileplanningmain SET product_list = '".$selcodes."' WHERE pm_idx='".$pm_idx."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert('����ȭ�� ��ǰ ���� ������ �Ϸ�Ǿ����ϴ�.');</script>\n";
	} else if ($mode=="modify" && strlen($selcodes)>0 && strlen($pm_idx)>0) {
		$sql = "UPDATE tblmobileplanningmain SET product_list = '".$selcodes."' WHERE pm_idx='".$pm_idx."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert('�ش� ��ǰ�� ����ȭ�� ������ǰ ī�װ��� �߰��Ͽ����ϴ�.');</script>\n";
	} else if ($mode=="insert" && strlen($selcodes)>0 && strlen($pm_idx)>0) {
		$sql = "INSERT tblmobileplanningmain SET ";
		$sql.= "pm_idx			= '".$pm_idx."', ";
		$sql.= "product_list	= '".$selcodes."' ";
		mysql_query($sql,get_db_conn());
		$onload="<script>alert('�ش� ��ǰ�� ����ȭ�� ������ǰ ī�װ��� �߰��Ͽ����ϴ�.');</script>\n";
	} else if ($mode=="delete" && strlen($pm_idx)>0) {
		if(strlen($selcodes)==0) {
			$sql = "UPDATE tblmobileplanningmain SET product_list = '".$selcodes."' WHERE pm_idx='".$pm_idx."' ";
		} else {
			$sql = "UPDATE tblmobileplanningmain SET product_list = '".$selcodes."' WHERE pm_idx='".$pm_idx."' ";
		}
		mysql_query($sql,get_db_conn());
		$onload="<script>alert('�ش� ��ǰ�� ���λ�ǰ���� �����Ͽ����ϴ�.');</script>\n";
	}

	$sql = "SELECT vendercnt FROM tblshopcount ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	$vendercnt=$row->vendercnt;
	mysql_free_result($result);

	if($vendercnt>0){
		$venderlist=array();
		$sql = "SELECT vender,id,com_name,delflag FROM tblvenderinfo ORDER BY id ASC ";
		$result=mysql_query($sql,get_db_conn());
		
		while($row=mysql_fetch_object($result)) {
			$venderlist[$row->vender]=$row;
		}
		mysql_free_result($result);
	}

	$imagepath=$Dir.DataDir."shopimages/product/";
?>

<? include "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script> 
<script language="JavaScript">
	<!--
	function openForm(pm_idx) {
		window.open("mobile_main_planning_write.php?pm_idx="+pm_idx,"","width=680,height=600");	
	}

	function del(pm_idx){
		if(confirm("�����Ͻ� ������Ҹ� �����ұ��?")){	
			ifrm_ctrl.location.href="mobile_main_planning_ctrl.php?mode=del&pm_idx="+pm_idx;
		}
	}

	document.onkeydown = CheckKeyPress;
	var all_list_i = new Array(); //num�� ���� ����Ʈ�� ����
	var preselectnum = ""; //num�� ���� ��������Ʈ�� ����
	var all_list = new Array();
	var selnum="";
	var ProductInfoStop="";

	function CheckKeyPress(updownValue) {
		prevobj=null;
		selobj=null;

		if(updownValue){
			ekey = updownValue;
		}else{
			ekey = event.keyCode;
		}
		
		if(selnum!="" && (ekey==38 || ekey==40 || ekey=="up" || ekey=="down")) {
			var h=0;

			h=all_list_i[selnum];
			if(ekey==38 || ekey == "up") {			//���� �̵�
				kk=h-1;
			} else {	//�Ʒ��� �̵�
				kk=h+1;
			}

			prevobj=all_list[kk];

			if(prevobj!=null) {
				selobj=all_list[h];

				t1=prevobj.sort;
				prevobj.sort=selobj.sort;
				selobj.sort=t1;

				o1=prevobj.no;
				prevobj.no=selobj.no;
				selobj.no=o1;

				all_list[h]=prevobj;
				all_list[kk]=selobj;

				all_list_i[prevobj.num]=h; //prevobj.num�� ���� ����Ʈ�� ����
				all_list_i[selobj.num]=kk; //selobj.num�� ���� ����Ʈ�� ����
				preselectnum=prevobj.num; //prevobj.num�� ���� ��������Ʈ�� ����

				takeChange(prevobj);
				takeChange(selobj);

				all_list[kk].selected=false;
				selnum="";
				document.form1.change.value="Y";
				ChangeList(all_list[kk].num);
			}
		}
	}

	function takeChange(argObj){
		var innerHtmlStr = "";

		innerHtmlStr="<td>"+argObj.num+"</td>";
		innerHtmlStr+="<td><a href=\"javascript:updown_click('"+argObj.num+"','up')\"><img src=\"images/btn_plus.gif\" border=\"0\" style=\"margin-bottom:3px;\"></a><br><a href=\"javascript:updown_click('"+argObj.num+"','down')\"><img src=\"images/btn_minus.gif\" border=\"0\" style=\"margin-top:3px;\"></a></td>";
		<?if($vendercnt>0) {echo "innerHtmlStr+=argObj.venderidx;\n";}?>
		innerHtmlStr+=argObj.imgidx;
		innerHtmlStr+=argObj.nameidx;
		innerHtmlStr+=argObj.sellidx;
		innerHtmlStr+=argObj.quantityidx;
		innerHtmlStr+=argObj.displayidx;
		innerHtmlStr+=argObj.editidx;
		innerHtmlStr+=argObj.deleteidx;
		document.all["idx_inner_"+argObj.sort].innerHTML="<table onclick=\"ChangeList('"+argObj.num+"');\" border=\"0\" cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\" style=\"table-layout:fixed\"><col width=40></col><col width=12></col><?=($vendercnt>0?"<col width=70></col>":"")?><col width=50></col><col width=></col><col width=70></col><col width=45></col><col width=45></col><col width=45></col><col width=45></col><tr align=\"center\">"+innerHtmlStr+"</tr></table>";
	}

	function updown_click(num,updownValue){
		if(selnum != num){
			ChangeList(num);
		}
		CheckKeyPress(updownValue);
	}

	function ChangeList(num) {
		if(ProductInfoStop){
			ProductInfoStop = "";
		}else{
			if(all_list[all_list_i[num]].selected==true) {
				preselectnum="";  //����num �� ����
				selnum="";
				all_list[all_list_i[num]].selected=false;
				document.all["idx_inner_"+all_list[all_list_i[num]].sort].style.backgroundColor="#FFFFFF";
				
			} else {
				if(preselectnum>0) { //���� ���õǾ� �ִ� �� ����
					all_list[all_list_i[preselectnum]].selected=false;
					document.all["idx_inner_"+all_list[all_list_i[preselectnum]].sort].style.backgroundColor="#FFFFFF";
				}
				preselectnum=num;  //����num �� ����
				selnum=num;
				all_list[all_list_i[num]].selected=true;
				document.all["idx_inner_"+all_list[all_list_i[num]].sort].style.backgroundColor="#EFEFEF";
			}
			jumpdivshow(num,all_list[all_list_i[num]].selected);
		}
	}

	function jumpdivshow(num,selectValue) {
		if(document.getElementById("idx_inner_"+all_list[all_list_i[num]].sort) && document.getElementById("jumpdiv")) {
			var inneridxObj = document.getElementById("idx_inner_"+all_list[all_list_i[num]].sort);
			var jumpdivObj = document.getElementById("jumpdiv");

			jumpdivObj.style.display="none";
			
			if(selectValue==true) {
				jumpdivObj.style.display="";
				
				if(inneridxObj.offsetHeight>jumpdivObj.offsetHeight) {
					jumpdivObj.style.top = inneridxObj.offsetTop+((inneridxObj.offsetHeight-jumpdivObj.offsetHeight)/2);
				} else {
					jumpdivObj.style.top = inneridxObj.offsetTop-(jumpdivObj.offsetHeight-inneridxObj.offsetHeight-1);
				}
				jumpdivObj.style.left = (inneridxObj.offsetWidth-jumpdivObj.offsetWidth)/2;
			}
		}
	}

	function CheckJump(updownValue) {
		prevobj=null;
		selobj=null;

		h=all_list_i[selnum];
		if(updownValue == "up") {			//���� �̵�
			kk=h-1;
		} else {	//�Ʒ��� �̵�
			kk=h+1;
		}

		if(all_list[kk]!=null) {
			prevobj=all_list[kk];
			selobj=all_list[h];

			t1=prevobj.sort;
			prevobj.sort=selobj.sort;
			selobj.sort=t1;

			o1=prevobj.no;
			prevobj.no=selobj.no;
			selobj.no=o1;

			all_list[h]=prevobj;
			all_list[kk]=selobj;

			all_list_i[prevobj.num]=h; //prevobj.num�� ���� ����Ʈ�� ����
			all_list_i[selobj.num]=kk; //selobj.num�� ���� ����Ʈ�� ����
			preselectnum=prevobj.num; //prevobj.num�� ���� ��������Ʈ�� ����

			takeChange(prevobj);

			selnum=all_list[kk].num;
			all_list[kk].selected=true;
		}
	}

	function jumpgo() {
	
		form = document.form1;

		if(selnum.length) {
			if(form.jumpnumber.value.length>0 && all_list_i[form.jumpnumber.value]>-1 && all_list[all_list_i[form.jumpnumber.value]] && all_list[all_list_i[form.jumpnumber.value]].sort>-1) {
				if(form.jumpnumber.value!=selnum) {
					var updowntype = "down";
					var selnum_Obj = all_list[all_list_i[selnum]];
					var jumpnumber_Obj = all_list[all_list_i[form.jumpnumber.value]];

					var selnum_sort = selnum_Obj.sort;
					var jumpnumber_sort = jumpnumber_Obj.sort;
					var num_subtract = selnum_sort-jumpnumber_sort;
					var preselectnum_num="";

					preselectnum = selnum_Obj.num;
					if(num_subtract>0) {
						updowntype = "up";
					}

					num_subtract = Math.abs(num_subtract);

					for(var i=0; i<num_subtract; i++) {
						CheckJump(updowntype);
						if(i==0) {
							preselectnum_num = preselectnum;
						}
					}
					takeChange(selnum_Obj);
					preselectnum = preselectnum_num;

					form.jumpnumber.value="";
					document.form1.change.value="Y";
					selnum="";
					selnum_Obj.selected=false;
					ChangeList(selnum_Obj.num);
				}
			} else {
				if(form.jumpnumber.value.length==0) {
					alert("�̵���ġ No�� �Է��� �ּ���.");
				} else {
					alert("�̵���ġ No�� �������� �ʴ� ��ȣ �Դϴ�.");
				}
			}
		}
	}

	function ObjList() {
		var argv = ObjList.arguments;   
		var argc = ObjList.arguments.length;

		//Property ����
		this.classname		= "ObjList";
		this.debug			= false;
		this.num			= new String((argc > 0) ? argv[0] : "0");
		this.productcode	= new String((argc > 1) ? argv[1] : "");
		this.imgidx			= new String((argc > 2) ? argv[2] : "");
		this.nameidx		= new String((argc > 3) ? argv[3] : "");
		this.sellidx		= new String((argc > 4) ? argv[4] : "");
		this.quantityidx	= new String((argc > 5) ? argv[5] : "");
		this.displayidx		= new String((argc > 6) ? argv[6] : "");
		this.editidx		= new String((argc > 7) ? argv[7] : "");
		this.deleteidx		= new String((argc > 8) ? argv[8] : "");
		this.no				= new String((argc > 9) ? argv[9] : "");
		this.sort			= new String((argc > 10) ? argv[10] : "");
		this.selected		= new Boolean((argc > 11) ? argv[11] : false );
		<?if($vendercnt>0) {echo "this.venderidx		= new String((argc > 12) ? argv[12] : \"\");\n";}?>
	}

	function move_save(){
		if (document.form1.change.value!="Y") {
			alert("���� ������ ���� �ʾҽ��ϴ�.");
			return;
		}
		if (!confirm("������ ������� �����Ͻðڽ��ϱ�?")){
			return;
		}

		val="";

		for(i=0;i<all_list.length;i++){
			val+=","+all_list[i].productcode;
		}


		if(val.length>0){

			val=val.substring(1);
			document.form1.mode.value = "sequence";
			document.form1.selcodes.value=val;
			document.form1.submit();
		}
	}

	<?if($vendercnt>0){?>
		function viewVenderInfo(vender) {
		ProductInfoStop = "1";
		window.open("about:blank","vender_infopop","width=100,height=100,scrollbars=yes");
		document.vForm.vender.value=vender;
		document.vForm.target="vender_infopop";
		document.vForm.submit();
		}
	<?}?>

	function ProductMouseOver(Obj) {
		obj = event.srcElement;
		WinObj=document.getElementById(Obj);
		obj._tid = setTimeout("ProductViewImage(WinObj)",200);
	}
	function ProductViewImage(WinObj) {
		WinObj.style.display = "";

		if(!WinObj.height){
			WinObj.height = WinObj.offsetTop;
		}

		WinObjPY = WinObj.offsetParent.offsetHeight;
		WinObjST = WinObj.height-WinObj.offsetParent.scrollTop;
		WinObjSY = WinObjST+WinObj.offsetHeight;

		if(WinObjPY < WinObjSY){
			WinObj.style.top = WinObj.offsetParent.scrollTop-WinObj.offsetHeight+WinObjPY;
		}else if(WinObjST < 0){
			WinObj.style.top = WinObj.offsetParent.scrollTop;
		}else{
			WinObj.style.top = WinObj.height;
		}
	}

	function ProductMouseOut(Obj) {
		obj = event.srcElement;
		WinObj = document.getElementById(Obj);
		WinObj.style.display = "none";
		clearTimeout(obj._tid);
	}

	function InsertSpecial() {
		if (document.form1.prcode.value.length==0) {
			alert("���� ������ǰ�� �߰��� ��ǰ�� �����ϼ���.");
			document.form1.prcode.focus();
			return;
		}
		num = all_list.length-1;
		if(num+1>=50){
			alert('���� ������ǰ�� �ִ� 50������ ��ϰ����մϴ�.');
			return;
		}
		if (confirm("�ش� ��ǰ�� ���� ������ǰ���� �����Ͻðڽ��ϱ�?")){
			temp = "";
			for (i=0;i<=num;i++) {
				if(all_list[i].productcode == document.form1.prcode.value){
					alert('�̹� ��ϵ� ��ǰ�Դϴ�.');
					return;
				} 
				if (i==0) {
					temp = all_list[i].productcode;
				}else {
					temp+=","+all_list[i].productcode;
				}
			}
			if(num==-1) {
				temp=document.form1.prcode.value;
			}else {
				temp+=","+document.form1.prcode.value;
			}
			document.form1.selcodes.value = temp;
			document.form1.submit();
		}
	}

	function ChangeSpecial(val) {
	
		document.form1.submit();
	}

	function Delete(delcode) {	
		ProductInfoStop = "1";
		
		if(!confirm("�ش� ��ǰ�� ���λ�ǰ���� �����Ͻðڽ��ϱ�?")){
			return;
		}
		val="";
		for(i=0;i<all_list.length;i++){
			if(delcode!=all_list[i].productcode){
				val+=","+all_list[i].productcode;
			}
		}

		if(val.length>0){
			val=val.substring(1);
		}
		document.form1.mode.value="delete";
		document.form1.selcodes.value=val;

		document.form1.submit();
	}

	function ProductInfo(prcode) {
		ProductInfoStop = "1";
		code=prcode.substring(0,12);
		popup="YES";
		document.form_reg.code.value=code;
		document.form_reg.prcode.value=prcode;
		document.form_reg.popup.value=popup;
		if (popup=="YES") {
			document.form_reg.action="product_register.add.php";
			document.form_reg.target="register";
			window.open("about:blank","register","width=820,height=700,scrollbars=yes,status=no");
		} else {
			document.form_reg.action="product_register.php";
			document.form_reg.target="";
		}
		document.form_reg.submit();
	}

	function DivScrollActive(arg1){
		if(!self.id){
			self.id = self.name;
		}

		if(document.getElementById("divscroll") && document.getElementById("ListTTableId") && document.getElementById("ListLTableId") && parent.document.getElementById(self.id)){
			if(!document.getElementById("divscroll").height){
				document.getElementById("divscroll").height=document.getElementById("divscroll").offsetHeight;
			}

			if(arg1>0){
				if(document.getElementById("ListLTableId").offsetHeight > document.getElementById("divscroll").offsetHeight){
					document.getElementById("divscroll").style.height="100%";
					parent.document.getElementById(self.id).style.height=document.getElementById("ListTTableId").offsetHeight;
				}
			}else{
				document.getElementById("divscroll").style.height=document.getElementById("divscroll").height;
				parent.document.getElementById(self.id).style.height="100%";
			}
		}

		document.form1.Scrolltype.value = arg1;
	}
	function openSel(page_mode,pm_idx) {
		window.open("mobile_product_search.php?page_mode="+page_mode+"&pm_idx="+pm_idx+"&","collection","width=900,height=800,scrollbars=yes");
	}
//-->
</script>

<iframe name="ifrm_ctrl" width=0 height=0 frameborder=0 align=top scrolling="no" marginheight="0" marginwidth="0"></iframe>
<form name="form_reg" action="product_register.php" method="post">
	<input type="hidden" name="code">
	<input type="hidden" name="prcode">
	<input type="hidden" name="popup">
</form>
<form name="vForm" action="vender_infopop.php" method="post">
	<input type="hidden" name="vender">
</form>
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td valign="top">
			<table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
							<col width=198></col>
							<col width=10></col>
							<col width=></col>
							<tr>
								<td valign="top"  background="images/leftmenu_bg.gif">
									<? include ("menu_mobile.php"); ?>
								</td>
								<td></td>
								<td valign="top">
									<table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td height="29" colspan="3">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td height="28" class="link" align="left" background="images/con_link_bg.gif">
															<img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ����ϼ� &gt; <span class="2depth_select">���� ��ǰ ��������</span>
														</td>
													</tr>
												</table>
											</td>
										</tr>   
										<tr>
											<td width="16"><img src="images/con_t_01.gif" width="16" height="16" border="0"></td>
											<td background="images/con_t_01_bg.gif"></td>
											<td width="16"><img src="images/con_t_02.gif" width="16" height="16" border="0"></td>
										</tr>
										<tr>
											<td width="16" background="images/con_t_04_bg1.gif"></td>
											<td bgcolor="#ffffff" style="padding:10px">
												<table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td>
															<table WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<tr>
																	<td><img src="images/mobile_main_planning_title.gif" border="0"></td>
																</tr>
																<tr>
																	<td width="100%" background="images/title_bg.gif" height="21"></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td height="3"></td>
													</tr>
													<tr>
														<td style="padding-bottom:3pt;">
															<table WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
																<tr>
																	<td><img src="images/distribute_01.gif"></td>
																	<td colspan="2" background="images/distribute_02.gif"></td>
																	<td><img src="images/distribute_03.gif"></td>
																</tr>
																<tr>
																	<td background="images/distribute_04.gif"></td>
																	<td class="notice_blue"><img src="images/distribute_img.gif" ></td>
																	<td width="100%" class="notice_blue">����� ���θ� ����ȭ���� ������ǰ�� �����Ͻ� �� �ֽ��ϴ�.</td>
																	<td background="images/distribute_07.gif"></td>
																</tr>
																<tr>
																	<td><img src="images/distribute_08.gif"></td>
																	<td colspan="2" background="images/distribute_09.gif"></td>
																	<td><img src="images/distribute_10.gif"></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td height="20"></td>
													</tr>
													<tr>
														<td>
															<table width="100%" border=0 cellpadding=0 cellspacing=0>
																<tr>
																	<td><img src="images/mobile_main_planning_stitle.gif" border="0" alt="���λ�ǰ ��������"></td>
																	<td width="100%" background="images/shop_basicinfo_stitle_bg.gif"></td>
																	<td><img src="images/shop_basicinfo_stitle_end.gif" width=10 height=31 alt=""></td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td height=3></td>
													</tr>
													<tr>
														<td>
															<form name=frm action="<?=$_SERVER[PHP_SELF]?>" method=post>
																<input type=hidden name=>
																<input type=hidden name=>

																<table cellspacing=0 cellpadding=0 width="100%" border=0>
																	<colgroup>
																		<col width="20"></col>
																		<col width=""></col>
																		<col width="80"></col>
																		<col width="50"></col>
																		<col width="50"></col>
																		<col width="50"></col>
																		<col width="50"></col>
																	</colgroup>
																	<tr>
																		<td background="images/table_top_line.gif" width="761" colspan="8"></td>
																	</tr>
																	<tr>
																		<td class="table_cell"><p align="center">no</td>
																		<td class="table_cell1"><p align="center">��ǰ������</td>
																		<td class="table_cell1"><p align="center">�������</td>
																		<td class="table_cell1"><p align="center">��¼�</td>
																		<td class="table_cell1"><p align="center">�������</td>
																		<td class="table_cell1"><p align="center">����</td>
																		<td class="table_cell1"><p align="center">����</td>
																	</tr>
																	<tr>
																		<td colspan="8" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></td>
																	</tr>
																	<?
																		$query = "select * from tblmobileplanningmain order by pm_idx ASC";
																		$result = mysql_query($query,get_db_conn());
																		$i = 1;
																		while($row = mysql_fetch_array($result)){
																			$arr_pm_idx[$i] = $row[pm_idx];
																			$arr_title[$i] = $row[title];
																	?>

																	<tr align="center">
																	<td class="td_con2"><?=$i?></td>
																		<td class="td_con2"><?=$row[title]?></td>
																		<td class="td_con2"><?=$row[display_type]?></td>
																		<td class="td_con2"><?=$row[product_cnt]?></td>
																		<td class="td_con2"><?=$row[display]?></td>
																		<td class="td_con2"><a href="javascript:openForm('<?=$row[pm_idx]?>')"><img src="images/btn_edit.gif" border="0" alt="" /></a></td>
																		<td class="td_con2"><a href="javascript:del('<?=$row[pm_idx]?>')"><img src="images/btn_del.gif" /></a></td>
																	</tr>
																	<tr>
																		<td colspan="8" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></td>
																	</tr>
																	<?
																			$i++;
																		}
																	?>
																	<tr>
																		<td colspan="8" width="760" background="images/table_con_line.gif"><img src="images/table_con_line.gif" width="4" height="1" border="0"></td>
																	</tr>
																</table>
															</form>
														</td>
													</tr>
													<tr>
														<td align="right"style="padding-top:10px">
															<img src="images/mobile_main_planning_btn.gif" id="uploadButton" border="0" style="cursor:hand" onclick="openForm('');" alt="��ǰ���� �߰�">
														</td>
													</tr>
													<tr>
														<td align="center" height=10></td>
													</tr>
													<tr>
														<td height=20></td>
													</tr>
													<tr>
														<td>
															<table id="listttableid" cellspacing=0 cellpadding=0 width="100%" border=0>
																<tr>
																	<td style="border:#ededed 4px solid;">
																		<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=get>
																			<table cellspacing=0 cellpadding=0 width="100%" border=0>
																				<!-- html-->
																				<col width="70"></col>
																				<col width=""></col>
																				<tr>
																					<td colspan=2 background="images/table_top_line.gif"></td>
																				</tr>
																				<tr>
																					<td class="table_cell" width="170"><img src="images/icon_point2.gif" border="0">���� ��ǰ���� ���� ����</td>
																					<td class="td_con1">
																						<table cellspacing=0 cellpadding=0 border=0 width="100%">
																							<tr>
																								<td>
																									<?
																									
																										for($i=1; $i<= sizeof($arr_pm_idx);$i++){

																											if($pm_idx=="" && $i==1){
																												$checked = "checked";
																											}else{		
																												$checked = "";		
																												if($arr_pm_idx[$i]==$pm_idx){
																													$checked = "checked";
																												}				
																											}
																									?>					
																										<input type="radio" name="pm_idx" value="<?=$arr_pm_idx[$i]?>" <? echo $checked; ?> onClick="ChangeSpecial(this.value);" /><?=$arr_title[$i]?> 
																									<?
																										}	
																									?>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																				<tr>
																					<td colspan="2" background="images/table_con_line.gif"></td>
																				</tr>
																				<tr>
																					<td colspan="2">
																						<table cellpadding="0" cellspacing="0" width="100%">
																							<tr>
																								<td background="images/table_top_line.gif"></td>
																							</tr>
																							<tr>
																								<td>
																									<table id="listltableid" border="0" cellspacing="0" cellpadding="0" width="100%" style="table-layout:fixed">
																										<?
																										$colspan=9;
																										if($vendercnt>0) $colspan++;
																										?>
																										<col width=40></col>
																										<col width=12></col>
																										<col width=70></col>
																										<col width=50></col>
																										<col width=></col>
																										<col width=70></col>
																										<col width=45></col>
																										<col width=45></col>
																										<col width=45></col>
																										<col width=45></col>
																										<tr align="center">
																											<td class="table_cell" colspan="2">no</td>
																											<td class="table_cell1">������ü</td>
																											<td class="table_cell1" colspan="2">��ǰ��</td>
																											<td class="table_cell1">�ǸŰ���</td>
																											<td class="table_cell1">����</td>
																											<td class="table_cell1">����</td>
																											<td class="table_cell1">����</td>
																											<td class="table_cell1">����</td>
																										</tr>
																										<?
																											$pm_idx=$_REQUEST["pm_idx"];
																											if($pm_idx==""){ 
																												$pm_idx=$arr_pm_idx[1];
																											}

																											$image_i=0;
																											if(strlen($pm_idx)>0) {
																												$mode="insert";
																												$sp_prcode="";
																												$sql = "SELECT product_list FROM tblmobileplanningmain WHERE pm_idx='".$pm_idx."' ";
																												$result = mysql_query($sql,get_db_conn());
																												if($row = mysql_fetch_object($result)){
																													$cnt_prcode=$row->product_list;
																													$sp_prcode=ereg_replace(',','\',\'',$cnt_prcode);
																													$mode="modify";
																												}
																												mysql_free_result($result);
																												
																												if(strlen($sp_prcode)>0) {
																												$sql = "SELECT option_price, productcode,productname,production,sellprice,consumerprice, ";
																												$sql.= "buyprice,quantity,reserve,reservetype,addcode,display,vender,tinyimage,selfcode,assembleuse ";
																												$sql.= "FROM tblproduct ";
																												$sql.= "WHERE productcode IN ('".$sp_prcode."')";

																												$result = mysql_query($sql,get_db_conn());

																												while($row=mysql_fetch_object($result)) {
																													$arraycode[$row->productcode]=$row->productcode;
																													$arrayquantity[$row->productcode]=$row->quantity;
																													$arraydisplay[$row->productcode]=$row->display;
																													$arrayoption_price[$row->productcode]=$row->option_price;
																													$arrayproductname[$row->productcode]=$row->productname;
																													$arrayproduction[$row->productcode]=$row->production;
																													$arraysellprice[$row->productcode]=$row->sellprice;
																													$arrayconsumerprice[$row->productcode]=$row->consumerprice;
																													$arraybuyprice[$row->productcode]=$row->buyprice;
																													$arrayreserve[$row->productcode]=$row->reserve;
																													$arrayreservetype[$row->productcode]=$row->reservetype;
																													$arrayaddcode[$row->productcode]=$row->addcode;
																													$arrayvender[$row->productcode]=$row->vender;
																													$arraytinyimage[$row->productcode]=$row->tinyimage;
																													$arrayselfcode[$row->productcode]=$row->selfcode;
																													$arrayassembleuse[$row->productcode]=$row->assembleuse;
																												}

																												$viewproduct = explode(",",$cnt_prcode);
																												$cnt =count($viewproduct);
																												$j=0;
																												$strlist="<script>\n";
																												$jj=$cnt;
																												$ii=0;
																												for($i=0;$i<$cnt;$i++){
																													if(strlen($arraycode[$viewproduct[$i]])>0){
																														$j++;
																														$strlist.= "var objlist=new ObjList();\n";
																														$strlist.= "objlist.num=\"".$j."\";\n";
																														$strlist.= "all_list_i[objlist.num]=".$ii.";\n";
																														$strlist.= "objlist.productcode=\"".$arraycode[$viewproduct[$i]]."\";\n";
																														if($vendercnt>0) {
																															$strlist.= "objlist.venderidx=\"<td class=\\\"td_con1\\\"><B>".(strlen($venderlist[$arrayvender[$viewproduct[$i]]]->vender)>0?"<span onclick=\\\"viewVenderInfo(".$arrayvender[$viewproduct[$i]].");\\\">".$venderlist[$arrayvender[$viewproduct[$i]]]->id."</span>":"-")."</B></td>\";\n";
																														}
																														if (strlen($arraytinyimage[$viewproduct[$i]])>0 && file_exists($imagepath.$arraytinyimage[$viewproduct[$i]])==true){
																															$strlist.= "objlist.imgidx=\"<td class=\\\"td_con1\\\"><img src=\\\"".$imagepath.$arraytinyimage[$viewproduct[$i]]."\\\" height=\\\"40\\\" width=\\\"40\\\" border=\\\"1\\\" onMouseOver=\\\"ProductMouseOver('primage".$image_i."')\\\" onMouseOut=\\\"ProductMouseOut('primage".$image_i."');\\\"><div id=\\\"primage".$image_i."\\\" style=\\\"position:absolute; z-index:100; display:none;\\\"><table border=\\\"0\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" width=\\\"170\\\"><tr bgcolor=\\\"#FFFFFF\\\"><td align=\\\"center\\\" width=\\\"100%\\\" height=\\\"150\\\" style=\\\"border:#000000 solid 1px;\\\"><img src=\\\"".$imagepath.$arraytinyimage[$viewproduct[$i]]."\\\" border=\\\"0\\\"></td></tr></table></div></td>\";\n";
																														} else {
																															$strlist.= "objlist.imgidx=\"<td class=\\\"td_con1\\\"><img src=images/space01.gif onMouseOver=\\\"ProductMouseOver('primage".$image_i."')\\\" onMouseOut=\\\"ProductMouseOut('primage".$image_i."');\\\"><div id=\\\"primage".$image_i."\\\" style=\\\"position:absolute; z-index:100; display:none;\\\"><table border=\\\"0\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" width=\\\"170\\\"><tr bgcolor=\\\"#FFFFFF\\\"><td align=\\\"center\\\" width=\\\"100%\\\" height=\\\"150\\\" style=\\\"border:#000000 solid 1px;\\\"><img src=\\\"".$Dir."images/product_noimg.gif\\\" border=\\\"0\\\"></td></tr></table></div></td>\";\n";
																														}

																														$strlist.= "objlist.nameidx=\"<td class=\\\"td_con1\\\" align=\\\"left\\\" style=\\\"word-break:break-all;\\\"><img src=\\\"images/producttype".($arrayassembleuse[$viewproduct[$i]]=="Y"?"y":"n").".gif\\\" border=\\\"0\\\" align=\\\"absmiddle\\\" hspace=\\\"2\\\">".addslashes($arrayproductname[$viewproduct[$i]].($arrayselfcode[$viewproduct[$i]]?"-".$arrayselfcode[$viewproduct[$i]]:"").($arrayaddcode[$viewproduct[$i]]?"-".$arrayaddcode[$viewproduct[$i]]:""))."&nbsp;</td>\";\n";
																														$strlist.= "objlist.sellidx=\"<td align=\\\"right\\\" class=\\\"td_con1\\\"><img src=\\\"images/won_icon.gif\\\" border=\\\"0\\\" style=\\\"margin-right:2px;\\\"><span class=\\\"font_orange\\\">".number_format($arraysellprice[$viewproduct[$i]])."</span><br><img src=\\\"images/reserve_icon.gif\\\" border=\\\"0\\\" style=\\\"margin-right:2px;\\\">".($arrayreservetype[$viewproduct[$i]]!="Y"?number_format($arrayreserve[$viewproduct[$i]]):$arrayreserve[$viewproduct[$i]]."%")."</td>\";\n";
																														if (strlen($arrayquantity[$viewproduct[$i]])==0) {
																															$strlist.= "objlist.quantityidx=\"<td class=\\\"td_con1\\\">������</td>\";\n";
																														}else if ($arrayquantity[$viewproduct[$i]]<=0) {
																															$strlist.= "objlist.quantityidx=\"<td class=\\\"td_con1\\\"><span class=\\\"font_orange\\\"><b>ǰ��</b></span></td>\";\n";
																														}else{
																															$strlist.= "objlist.quantityidx=\"<td class=\\\"td_con1\\\">".$arrayquantity[$viewproduct[$i]]."</td>\";\n";
																														}
																														$strlist.= "objlist.displayidx=\"<td class=\\\"td_con1\\\">".($arraydisplay[$viewproduct[$i]]=="Y"?"�Ǹ���</font>":"<font color=\\\"#FF4C00\\\">������</font>")."</td>\";\n";

																														$strlist.= "objlist.editidx=\"<td class=\\\"td_con1\\\"><img src=\\\"images/icon_newwin1.gif\\\" border=\\\"0\\\" onclick=\\\"ProductInfo('".$arraycode[$viewproduct[$i]]."');\\\" style=\\\"cursor:hand;\\\"></td>\";\n";
																														$strlist.= "objlist.deleteidx=\"<td class=\\\"td_con1\\\"><img src=\\\"images/icon_del1.gif\\\" border=\\\"0\\\" onclick=\\\"Delete('".$arraycode[$viewproduct[$i]]."');\\\" style=\\\"cursor:hand;\\\"></td>\";\n";
																														$strlist.= "objlist.no=\"".$jj--."\";\n";
																														$strlist.= "objlist.sort=\"".$ii."\";\n";
																														$strlist.= "objlist.selected=false;\n";
																														$strlist.= "all_list[".$ii."]=objlist;\n";
																														$strlist.= "objlist=null;\n";

																														echo "<tr>\n";
																														echo "	<td colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></td>\n";
																														echo "</tr>\n";
																														echo "<tr>\n";
																														echo "	<td id=\"idx_inner_".$ii."\" colspan=\"".$colspan."\" style=\"background-color:'#FFFFFF';\" onmouseover=\"if(this.style.backgroundColor != '#efefef')this.style.backgroundColor='#F4F7FC';\" onmouseout=\"if(this.style.backgroundColor != '#efefef')this.style.backgroundColor='#FFFFFF';\">\n";
																														echo "	<table border=\"0\" cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\" style=\"table-layout:fixed;\" onclick=\"ChangeList('".$j."');\">\n";
																														echo "	<col width=40></col><col width=12></col>".($vendercnt>0?"<col width=70></col>":"")."<col width=50></col><col width=></col><col width=70></col><col width=45></col><col width=45></col><col width=45></col><col width=45></col>\n";
																														echo "	<tr align=\"center\">\n";
																														echo "		<td>".$j."</td>\n";
																														echo "		<td><a href=\"javascript:updown_click('".$j."','up')\"><img src=\"images/btn_plus.gif\" border=\"0\" style=\"margin-bottom:3px;\"></a><br><a href=\"javascript:updown_click('".$j."','down')\"><img src=\"images/btn_minus.gif\" border=\"0\" style=\"margin-top:3px;\"></a></td>\n";
																														
																														if($vendercnt>0) {
																															echo "		<td class=\"td_con1\"><B>".(strlen($venderlist[$arrayvender[$viewproduct[$i]]]->vender)>0?"<span onclick=\"viewVenderInfo(".$arrayvender[$viewproduct[$i]].");\">".$venderlist[$arrayvender[$viewproduct[$i]]]->id."</span>":"-")."</B></td>\n";
																														}

																														echo "		<td class=\"td_con1\">";
																														if (strlen($arraytinyimage[$viewproduct[$i]])>0 && file_exists($imagepath.$arraytinyimage[$viewproduct[$i]])==true){
																															echo "<img src=\"".$imagepath.$arraytinyimage[$viewproduct[$i]]."\" height=\"40\" width=\"40\" border=\"1\" onMouseOver=\"ProductMouseOver('primage".$image_i."')\" onMouseOut=\"ProductMouseOut('primage".$image_i."');\">";
																														} else {
																															echo "<img src=images/space01.gif onMouseOver=\"ProductMouseOver('primage".$image_i."')\" onMouseOut=\"ProductMouseOut('primage".$image_i."');\">";
																														}


																														echo "<div id=\"primage".$image_i."\" style=\"position:absolute; z-index:100; display:none;\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"170\">\n";
																														echo "		<tr bgcolor=\"#FFFFFF\">\n";
																														if (strlen($arraytinyimage[$viewproduct[$i]])>0 && file_exists($imagepath.$arraytinyimage[$viewproduct[$i]])==true){
																															echo "		<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"".$imagepath.$arraytinyimage[$viewproduct[$i]]."\" border=\"0\"></td>\n";
																														} else {
																															echo "		<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"".$Dir."images/product_noimg.gif\" border=\"0\"></td>\n";
																														}
																														echo "		</tr>\n";
																														echo "		</table>\n";
																														echo "		</div>\n";
																														echo "		</td>\n";
																														echo "		<td class=\"td_con1\" align=\"left\" style=\"word-break:break-all;\"><img src=\"images/producttype".($arrayassembleuse[$viewproduct[$i]]=="Y"?"y":"n").".gif\" border=\"0\" align=\"absmiddle\" hspace=\"2\">".$arrayproductname[$viewproduct[$i]].($arrayselfcode[$viewproduct[$i]]?"-".$arrayselfcode[$viewproduct[$i]]:"").($arrayaddcode[$viewproduct[$i]]?"-".$arrayaddcode[$viewproduct[$i]]:"")."&nbsp;</td>\n";
																														echo "		<td align=right class=\"td_con1\"><img src=\"images/won_icon.gif\" border=\"0\" style=\"margin-right:2px;\"><span class=\"font_orange\">".number_format($arraysellprice[$viewproduct[$i]])."</span><br><img src=\"images/reserve_icon.gif\" border=\"0\" style=\"margin-right:2px;\">".($arrayreservetype[$viewproduct[$i]]!="Y"?number_format($arrayreserve[$viewproduct[$i]]):$arrayreserve[$viewproduct[$i]]."%")."</td>\n";
																														echo "		<td class=\"td_con1\">";
																														if (strlen($arrayquantity[$viewproduct[$i]])==0) {
																															echo "������";
																														}else if ($arrayquantity[$viewproduct[$i]]<=0) {
																															echo "<span class=\"font_orange\"><b>ǰ��</b></span>";
																														}else {
																															echo $arrayquantity[$viewproduct[$i]];
																														}
																														echo "		</td>\n";
																														echo "		<td class=\"td_con1\">".($arraydisplay[$viewproduct[$i]]=="Y"?"<font color=\"#0000FF\">�Ǹ���</font>":"<font color=\"#FF4C00\">������</font>")."</td>";
																														echo "		<td class=\"td_con1\"><img src=\"images/icon_newwin1.gif\" border=\"0\" onclick=\"ProductInfo('".$arraycode[$viewproduct[$i]]."');\" style=\"cursor:hand;\"></td>\n";
																														echo "		<td class=\"td_con1\"><img src=\"images/icon_del1.gif\" border=\"0\" onclick=\"Delete('".$arraycode[$viewproduct[$i]]."');\" style=\"cursor:hand;\"></td>\n";
																														echo "	</tr>\n";
																														echo "	</table>\n";
																														echo "	</td>\n";
																														echo "</tr>\n";
																														$ii++;
																														$image_i++;	
																													}
																												}
																												
																												mysql_free_result($result);
																												$strlist.="</script>\n";
																												echo $strlist;
																												if ($j==0) {
																													echo "<tr><td colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></td></tr><tr><td class=\"td_con2\" colspan=\"".$colspan."\" align=\"center\">��ϵ� ��ǰ�� �����ϴ�.</td></tr>";
																												}
																											} else {
																												echo "<tr><td colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></td></tr><tr><td class=\"td_con2\" colspan=\"".$colspan."\" align=\"center\">��ϵ� ��ǰ�� �����ϴ�.</td></tr>";
																											}

																											
																											} else {
																												echo "<tr><td colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></td></tr><tr><td class=\"td_con2\"			colspan=\"".$colspan."\" align=\"center\">��ϵ� ��ǰ�� �����ϴ�.</td></tr>";
																											}
																										?>
																										<tr>
																											<td height="1" colspan="<?=$colspan?>" background="images/table_con_line.gif"></td>
																										</tr>
																									</table>
																								</td>
																							</tr>
																							<tr>
																								<td background="images/table_top_line.gif"></td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																				<input type=hidden name=mode value="modify">
																			</table>
																		<input type=hidden name=prcode>
																		<input type=hidden name=selcode>
																		<input type=hidden name=scrolltype value="0">
																		<input type=hidden name=change>
																		<input type=hidden name=selcodes>
																		<input type=hidden name=num value="">
																	</form>
																	</td>
																</tr>
																<tr>
																	<td height="15"></td>
																</tr>
																<tr>
																	<td colspan="2" align=center>
																		<a href="javascript:openSel('planning','<?=$pm_idx?>');"><img src="images/botteon_prinsert.gif" border="0"></a>
																		<a href="javascript:move_save();"><img src="images/btn_mainarray.gif" border="0"></a>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td height="50"></td>
													</tr>
													<tr>
														<td>
															<table cellpadding=0 cellspacing=0 width=100%>
																<tr>
																	<td><img src="images/manual_top1.gif" width=15 height=45 alt=""></td>
																	<td><img src="images/manual_title.gif" width=113 height=45 alt=""></td>
																	<td width="100%" background="images/manual_bg.gif"></td>
																	<td background="images/manual_bg.gif"></td>
																	<td><img src="images/manual_top2.gif" width=18 height=45 alt=""></td>
																</tr>
																<tr>
																	<td background="images/manual_left1.gif"></td>
																	<td colspan=3 width="100%" valign="top"  style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg">
																			<table cellpadding="0" cellspacing="0" width="100%">
																				<tr>
																					<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																					<td ><span class="font_dotline">���� ��ǰ ���� ����</span></td>
																				</tr>
																				<tr>
																					<td width="20" align="right">&nbsp;</td>
																					<td  class="space_top">
																						- ���� ��ǰ����(���� ����)�� �⺻ ������ ���������ϳ� ��ϰ����� ���� �ִ� 4���� ���ѵ˴ϴ�.<br/>
																					</td>
																					<tr>
																						<td width="20" align="right">&nbsp;</td>
																						<td  class="space_top">&nbsp; </td>
																					</tr>
																				</tr>
																				<tr>
																					<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																					<td ><span class="font_dotline">���� ��ǰ ���� ���</span></td>
																				</tr>
																				<tr>
																					<td width="20" align="right">&nbsp;</td>
																					<td  class="space_top">
																						- ���� ��ǰ���� ���� ���ÿ��� ���ϴ� ���� ���� �� "��ǰ����ϱ�" ��ư�� Ŭ���Ͻø� ����� �� �ִ� �˾�â(���� ����� ���θ� ��ǰ���� â)�� ��Ÿ���ϴ�.<br/>
																						- ����ϼ��θ� ��ǰ���� â���� ���ϴ� ī�װ� �� �������� �˻� �� �Ʒ� ��ϵ� ��ǰ ��Ͽ��� ���ϴ� ��ǰ�� Ŭ�� �Ͻø� �˴ϴ�.<br/>
																						- ������ �������� ���ǿ��� ���� ��ǰ�� �ѳ��� �����˴ϴ�<br/>
																						- �� ���ǿ� ��ϰ����� ��ǰ���� ���ѵ��� ������ ����ϼ����� ����Ǵ� ��ǰ�� ���� �ִ� �ش� ������ ��¼��� �ʰ����� �ʽ��ϴ�.<br/>
																						- ����ϼ� ���ο� ������ ��ǰ�� ��ǰ�������� ���� ���� �� ��� ������� �ʽ��ϴ�.<br/>
																					</td>
																					<tr>
																						<td width="20" align="right">&nbsp;</td>
																						<td  class="space_top">&nbsp; </td>
																					</tr>
																				</tr>
																				<tr>
																					<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																					<td ><span class="font_dotline">���� ��ǰ ���� ����</span></td>
																				</tr>
																				<tr>
																					<td width="20" align="right">&nbsp;</td>
																					<td  class="space_top">
																						- ���ϴ� ���� ������ ���� "����" ��ư�� Ŭ�� �Ͻø� ������ ��ǰ�� ���� �Ͻ� �� �ֽ��ϴ�.<br/>
																					<tr>
																						<td width="20" align="right">&nbsp;</td>
																						<td  class="space_top">&nbsp; </td>
																					</tr>
																				</tr>
																				<tr>
																					<td width="20" align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																					<td ><span class="font_dotline">��ǰ �������� �� ����</span></td>
																				</tr>
																				<tr>
																					<td width="20" align="right">&nbsp;</td>
																					<td  class="space_top">
																						- ���ϴ� ���� ������ ���� "��â" ��ư�� Ŭ�� �Ͻø� �ش� ��ǰ������ �����Ҽ� �ִ� �˾�â(���� ��ǰ ���/����/���� â)�� ��Ÿ���ϴ�.<br/>
																						- ��ǰ ���/����/���� â���� �ش� ��ǰ ���� ���� �� �Ʒ� "��ǰ���� �����ϱ�" ��ư�� Ŭ���Ͻø� �����Ͻ� �� �ֽ��ϴ�.<br/>
																						- ��ǰ ���/����/���� â���� �����Ͻ� ��� ���θ� ��ü���� �ش� ��ǰ ������ ����ǹǷ� ������ ���� �Ͻñ� �ٶ��ϴ�<br/>
																						- ��ǰ ���/����/���� â���� �ϴ� "�����ϱ�" ��ư�� Ŭ���� �ش� ��ǰ�� ���� �Ͻ� �� ������ ���θ� ��ü���� �����ǹǷ� ������ ���� �Ͻñ� �ٶ��ϴ�<br/>
																					<tr>
																						<td width="20" align="right">&nbsp;</td>
																						<td  class="space_top">&nbsp; </td>
																					</tr>
																				</tr>
																			</table>
																		</td>
																	<td background="images/manual_right1.gif"><img src="images/manual_right1.gif" width=18 height="2" alt=""></td>
																</tr>
																<tr>
																	<td><img src="images/manual_left2.gif" width=15 height=8 alt=""></td>
																	<td colspan=3 background="images/manual_down.gif"><img src="images/manual_down.gif" width="4" height=8 alt=""></td>
																	<td><img src="images/manual_right2.gif" width=18 height=8 alt=""></td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
											<td width="16" background="images/con_t_02_bg.gif"></td>
										</tr>
										<tr>
											<td width="16"><img src="images/con_t_04.gif" width="16" height="16" border="0"></td>
											<td background="images/con_t_04_bg.gif"></td>
											<td width="16"><img src="images/con_t_03.gif" width="16" height="16" border="0"></td>
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
</table>
<? include "copyright.php"; ?>