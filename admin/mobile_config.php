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

	$logoimagepath = "../m/upload/";
	$bannerimagepath = "../m/upload/";

	$limit_bannerimg = 512000; // ����̹��� ���ε� ������ ����
	$limit_etcSize = 153600; //�ΰ� �� ī�Ƕ���Ʈ ������ ����
	$type=trim($_POST["type"]);

	function _getSizeConverter($size) {
		$unit_list = array("Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
		$set_sizeUnit = round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $unit_list[$i];
		return $set_sizeUnit;
	}
	$msg_size =  _getSizeConverter($limit_bannerimg);  //����̹��� �뷮
	$msg_etc_size = _getSizeConverter($limit_etcSize);
	
	//�⺻����
	$use_mobile_site = $_POST["use_mobile_site"];
	$use_auto_redirection = $_POST["use_auto_redirection"];
	$use_cross_link = $_POST["use_cross_link"];
	$use_bank = $_POST["use_bank"];
	$use_creditcard = $_POST["use_creditcard"];
	$use_mobilephone = $_POST["use_mobilephone"];
	$use_mobile_qna = $_POST["use_mobile_qna"];
	$use_mobile_qna_write = $_POST["use_mobile_qna_write"];
	$skin = $_POST["skin"];
	$skin_css = $_POST["skin_css"];
	$color_css = $_POST["color_css"];
	$logo=$_FILES["logo"];
	$icon=$_FILES["icon"];
	$copyright_text = strip_tags($_POST["copyright_text"], '<a><br><br/>');
	$copyright_image = $_FILES["copyright_image"];
	//SNS �߰�
	$sns_kakaotalk = isset($_POST["kakaotalk"])? $_POST["kakaotalk"]:'N';
	$sns_kakaostory = isset($_POST["kakaostory"])? $_POST["kakaostory"]:'N';
	$sns_facebook = isset($_POST["facebook"])? $_POST["facebook"]:'N';
	$sns_twitter = isset($_POST["twitter"])? $_POST["twitter"]:'N';
	
	$set_sns = $sns_kakaotalk.'|'.$sns_kakaostory.'|'.$sns_facebook.'|'.$sns_twitter;
	
	//���
	$up_image=$_FILES["up_image"];
	$up_border=$_POST["up_border"];
	$up_url_type=$_POST["up_url_type"];
	$up_url=$_POST["up_url"];
	$up_target=$_POST["up_target"];
	$up_banner_loc=$_POST["up_banner_loc"];
	$place=$_POST["place"];
	$CurrentTime = date("YmdHis");


	if ($type=="up") {
		if ($logo[name]) {
			$logo_ext = strtolower(substr($logo[name],strlen($logo[name])-3,3));
			$fix_logo_name = "logo.".$logo_ext;
			if ($logo[size]>$limit_etcSize) { //1024 * 150
				echo '<script>alert("�ø��� �̹��� �뷮�� '.$msg_etc_size.' ������ ���ϸ� �����մϴ�.");history.go(-1);</script>';exit;
			} else {
				move_uploaded_file($logo[tmp_name],$logoimagepath.$fix_logo_name);
				chmod($logoimagepath.$fix_logo_name,777);
				$sql_logo = "logo = '$fix_logo_name',";
			}
		}

		if ($icon[name]) {
			$icon_ext = strtolower(substr($icon[name],strlen($icon[name])-3,3));
			$fix_icon_name = "icon.".$icon_ext;

			if ($icon[size]>$limit_etcSize) {
				$onload = "<script>alert (\"�ø��� �̹��� �뷮�� 150KB ������ ���ϸ� �����մϴ�.\");</script>";
			}else{
				move_uploaded_file($icon[tmp_name],$logoimagepath.$fix_icon_name);
				@chmod($logoimagepath.$fix_icon_name,777);
				$sql_icon = "icon = '$fix_icon_name',";
			}
		}

		if ($copyright_image[name]) {
			$copyright_image_ext = strtolower(substr($copyright_image[name],strlen($copyright_image[name])-3,3));
			$fix_copyright_image_name = "copyright_image.".$copyright_image_ext;

			if ($copyright_image[size]>$limit_etcSize) {
				echo '<script>alert("�ø��� �̹��� �뷮�� '.$msg_etc_size.'������ ���ϸ� �����մϴ�.");history.go(-1);</script>';exit;
			} else {
				move_uploaded_file($copyright_image[tmp_name],$logoimagepath.$fix_copyright_image_name);
				@chmod($logoimagepath.$fix_copyright_image_name,777);

				$sql_copyright_image = "copyright_image = '$fix_copyright_image_name',";
			}
		}
		$skin_css = $skin.".css";
		$sql = "UPDATE tblmobileconfig SET
				use_mobile_site = '$use_mobile_site',
				use_auto_redirection = '$use_auto_redirection',
				use_cross_link = '$use_cross_link',
				use_bank = '$use_bank',
				use_creditcard = '$use_creditcard',
				use_mobilephone = '$use_mobilephone',
				use_mobile_qna = '$use_mobile_qna',
				use_mobile_qna_write = '$use_mobile_qna_write',
				use_mobile_sns = '$set_sns',
				skin = '$skin',
				skin_css = '$skin_css',
				color_css =  '$color_css',
				$sql_logo 	$sql_icon  $sql_copyright_image
				copyright_text = '$copyright_text'
		";

		mysql_query($sql,get_db_conn());

		$onload = "<script>alert('���� ������ �Ϸ�Ǿ����ϴ�.');</script>";

	} else if ($type=="logodel") {

		$query = "select logo from tblmobileconfig";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);

		@unlink($logoimagepath.$row['logo']);
		$onload="<script>alert ('���θ� �ΰ� ������ �Ϸ�Ǿ����ϴ�.');</script>";
		mysql_query("update tblmobileconfig set logo = ''");

	} else if ($type=="icondel") {

		$query = "select icon from tblmobileconfig";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);

		@unlink($logoimagepath.$row[icon]);
		$onload="<script>alert ('���θ� �������� ������ �Ϸ�Ǿ����ϴ�.');</script>";

		mysql_query("update tblmobileconfig set icon = ''");

	} else if ($type=="copyrightimagedel") {

		$query = "select copyright_image from tblmobileconfig";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);

		@unlink($logoimagepath.$row[icon]);
		$onload="<script>alert ('���θ� ī�Ƕ���Ʈ �̹��� ������ �Ϸ�Ǿ����ϴ�.');</script>";

		mysql_query("update tblmobileconfig set copyright_image = ''");

	} else if ($type=="bannerdel") {
		if ($up_url) {
			$sql = "SELECT image FROM tblmobilebanner ";
			$sql.= "WHERE date = '".$up_url."'";
			$result = mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				if($row->image && file_exists($bannerimagepath.$row->image)) {
					@unlink($bannerimagepath.$row->image);
				}
			}
			mysql_free_result($result);
			$sql = "DELETE FROM tblmobilebanner WHERE date = '".$up_url."'";
			mysql_query($sql,get_db_conn());
			$onload = "<script>alert('��� ������ �Ϸ�Ǿ����ϴ�.');</script>";
		}
	} else if ($type=="banneradd") {

		if ($up_image[size]<=$limit_bannerimg){
			if($up_image[name] && $up_url) {
				$sql = "SELECT COUNT(*) as cnt FROM tblmobilebanner ";
				$result = mysql_query($sql,get_db_conn());
				$row = mysql_fetch_object($result);
				mysql_free_result($result);
				$cnt=(int)$row->cnt;
				if ($cnt<10) {
					$banner_name = "banner_".date(His)."_".$up_image[name];
					move_uploaded_file($up_image[tmp_name],$bannerimagepath.$banner_name);
					chmod($bannerimagepath.$banner_name,777);
					$sql = "INSERT tblmobilebanner SET ";
					$sql.= "date		= '".$CurrentTime."', ";
					$sql.= "image		= '".$banner_name."', ";
					$sql.= "border		= '".$up_border."', ";
					$sql.= "url_type	= '".$up_url_type."', ";
					$sql.= "url			= '".$up_url."', ";
					$sql.= "target		= '".$up_target."' ";


					mysql_query($sql,get_db_conn());
					$onload="<script>alert('��� ����� �Ϸ�Ǿ����ϴ�.');</script>";
				} else {
					$onload="<script>alert('��� ����� �ִ� 10�������� ����� �����մϴ�.');</script>";
				}
			}
		}else{
			$onload="<script>alert('����̹��� �뷮�� �ִ� ".$msg_size."���� ��� �����մϴ�.');</script>";
		}
	} else if ($type=="bannersort") {
		$banner=explode(",",$place);
		$date1=date("Ym");
		$date=date("dHis");
		for($i=0;$i<count($banner);$i++){
			$date--;
			if (strlen($date)==7) {
				$date="0".$date;
			} else if (strlen($date)==6) {
				$date="00".$date;
			}
			$sql = "UPDATE tblmobilebanner SET date='$date1$date' ";
			$sql.= "WHERE date = '".$banner[$i]."'";
			mysql_query($sql,get_db_conn());
		}
	}

	$result = mysql_query("select * from tblmobileconfig");
	$row = mysql_fetch_array($result);

	//SNS ���� ���� ���� 
	if($row['use_mobile_sns'] != ''){
		$get_sns = explode('|',$row['use_mobile_sns']);
		$kakaotalk = isset($get_sns[0])? $get_sns[0]:'';
		$kakaostory = isset($get_sns[1])? $get_sns[1]:'';
		$facebook = isset($get_sns[2])? $get_sns[2]:'';
		$twitter = isset($get_sns[3])? $get_sns[3]:'';
	}
?>

<? INCLUDE "header.php"; ?>
<?=$onload?>
<script type="text/javascript" src="lib.js.php"></script>
<script language="JavaScript">
	function CheckForm(type) {
		if (type=="logodel") {
			if (!confirm("���θ� �ΰ� �����Ͻðڽ��ϱ�?")) {
				return;
			}
		} else if (type=="icondel") {
			if (!confirm("���θ� �������� �����Ͻðڽ��ϱ�?")) {
				return;
			}
		} else {
			if(form1.use_mobile_site[0].checked=="" && form1.use_mobile_site[1].checked=="") {
				alert("����Ͽ� ���θ� ��뿩�θ� ������ �ּ���.");
				return;
			}

			if(form1.skin.value=="") {
				alert("skin �� ������ �ּ���.");
				return;
			}
		}
		form1.type.value=type;
		form1.submit();
	}
	function BannerDel(date) {
		if(confirm("��ʸ� �����Ͻðڽ��ϱ�?")) {

			form2.type.value="bannerdel";
			form2.up_url.value = date;
			form2.submit();
		}
	}

	function BannerAdd() {
		if(!form2.up_image.value){
			alert('��� �̹����� ����ϼ���');
			form2.up_image.focus();
			return;
		}
		if(!form2.up_url.value){
			alert('��ʿ� ������ URL�� �Է��ϼ���. \n(��: www.abc.com)');
			form2.up_url.focus();
			return;
		}
		form2.type.value="banneradd";
		form2.submit();
	}

	function BannerSort(cnt){
		arr_sort = new Array();
		var _val;
		for(i=1;i<=cnt;i++){
			_val=form2.bannerplace[i].options[form2.bannerplace[i].selectedIndex].value;
			if (arr_sort[_val]) {
				alert("��� ������ �ߺ��ǰų� �߸��Ǿ����ϴ�.");
				return;
			} else {
				arr_sort[_val] = form2.bannerdate[i].value;
			}
		}
		var result = arr_sort.join(",").substring(1);

		document.form2.place.value=result;
		document.form2.type.value="bannersort";
		document.form2.submit();
	}

	function setSkinCss(skin_css) {
		document.form1.skin_css.value = skin_css;
		document.getElementById("id_skin_css").style.display='none';
	}

	function setColorCss(skin_css) {
		document.form1.color_css.value = skin_css;
		document.getElementById("id_color_css").style.display='none';
	}

	function showPicker(str) {
		if(str=="skin") {
			document.getElementById("id_skin_css").style.display='';
		} else if (str=="color") {
			document.getElementById("id_color_css").style.display='';
		}
	}

	function BannerModify(tdate){
		var _form = document.bannerModify;
		var popurl = "./mobile_banner_modify.php";
		var popname = "modifypop";

		window.open(popurl,popname,"width=600,height=600");
		_form.bdate.value = tdate;
		_form.target = popname;
		_form.action = popurl;
		_form.submit();
	}
</script>

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
														<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ����ϼ� &gt; <span class="2depth_select">�⺻����</span></td>
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
														<td height="8"></td>
													</tr>
													<tr>
														<td>
															<table width="100%" border=0 cellpadding=0 cellspacing=0>
																<tr>
																	<td><img src="images/mobile_stitle.gif" border="0" alt="����ϱ⺻����"></td>
																</tr>
																<tr>
																	<td width="100%" background="images/title_bg.gif" height="21"></TD>
																</tr>
															</table>
														</td>
													</tr>
													<tr>
														<td height="3"></td>
													</tr>
													<tr>
														<td style="padding-bottom:3pt;">
															<table width="100%" border="0" cellpadding="0" cellspacing="0">
																<tr>
																	<td><img src="images/distribute_01.gif"></td>
																	<td colspan="2" background="images/distribute_02.gif"></td>
																	<td><img src="images/distribute_03.gif"></td>
																</tr>
																<tr>
																	<td background="images/distribute_04.gif"></td>
																	<td class="notice_blue"><img src="images/distribute_img.gif" ></td>
																	<td width="100%" class="notice_blue">����ϻ���Ʈ�� �⺻ȯ���� �����ϽǼ� �ֽ��ϴ�.</td>
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
																	<td><img src="images/mobile_sstitle01.gif" border="0" alt="����ϻ���Ʈ �⺻����"></td>
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
															<!-- ȯ�漳�� ���� -->
															<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
																<input type="hidden" name="type" value="up">
																<table cellspacing=0 cellpadding=0 width="100%" border=0 style="border-top:1px solid #BBBBBB;">
																	<tr>
																		<td background="images/table_top_line.gif" colspan=2></td>
																	</tr>
																	<tr>
																		<td class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">����Ͽ� ���θ� ��� ����</td>
																		<td class="td_con1" >
																			<table  border="0" cellpadding="0" cellspacing="0">
																				<tr>
																					<td class="td_con1b">
																						<input type="radio" name="use_mobile_site" value="Y" <? if($row[use_mobile_site]=="Y") echo "checked";?>>���<span class="font_orange2">('m.�����ּ�'�� ����)</span>
																					</td>
																				</tr>
																				<tr>
																					<td class="td_con1b">
																						<input type="radio" name="use_mobile_site"  value="N" <? if($row[use_mobile_site]=="N") echo "checked";?>>������<span class="font_orange2">(����Ͽ� �ּ� ���ӽ� pc�� ȭ������ ���ӵ�</span>)
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2" background="images/table_con_line.gif"></td>
																	</tr>
																	<tr>
																		<td class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">����� ���ӽ� �ڵ� ���� ����</td>
																		<td class="td_con1">
																			<table  border="0" cellpadding="0" cellspacing="0">
																				<tr>
																					<td class="td_con1b">
																						<input type="radio" name="use_auto_redirection"  value="Y" <? if($row[use_auto_redirection]=="Y") echo "checked";?>>���<span class="font_orange2">('www.�����ּ�' �Է½ÿ��� ����Ϸ� ������ ��� ����Ͽ� ȭ������ �ڵ����� �̵���)</span>
																					</td>
																				</tr>
																				<tr>
																					<td class="td_con1b">
																						<input type="radio" name="use_auto_redirection"  value="N" <? if($row[use_auto_redirection]=="N") echo "checked";?>>������<span class="font_orange2">(����Ͽ��� 'www.�����ּ�' �Է½� PC�� ȭ��(���� ���θ� ȭ��) �״�� ������)</span>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2" background="images/table_con_line.gif"></td>
																	</tr>
																	<tr>
																		<td class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">PCȭ�� �ٷΰ��� ����</span></td>
																		<td class="td_con1">
																			<table  border="0" cellpadding="0" cellspacing="0">
																				<tr>
																					<td class="td_con1b">
																						<input type="radio" name="use_cross_link" value="Y" <? if($row[use_cross_link]=="Y") echo "checked";?>>���<span class="font_orange2">(����ϼ� �ϴܿ� "PC����" ��ư���� �����)</span>
																					</td>
																				</tr>
																				<tr>
																					<td class="td_con1b">
																						<input type="radio" name="use_cross_link" value="N" <? if($row[use_cross_link]=="N") echo "checked";?>>������<span class="font_orange2">(����ϼ� �ϴܿ� "PC����" ��ư�� ����ȵ�)</span>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2" background="images/table_con_line.gif"></td>
																	</tr>
																	<tr>
																		<td class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">�������� ����</td>
																		<td class="td_con1" >
																			<table  border="0" cellpadding="0" cellspacing="0" width="100%">
																				<tr>
																					<td width="90" class="td_con1b" >
																						<input type="checkbox" name="use_bank" value="Y" <? if($row[use_bank]=="Y") echo "checked";?>>�������Ա�
																					</td>
																				</tr>
																				<tr>
																					<td class="td_con1b" >
																						<input type="checkbox" name="use_creditcard" value="Y" <? if($row[use_creditcard]=="Y") echo "checked";?>>�ſ�ī��(<span class="font_orange2">����Ʈ�� ������� ���񽺸� ��û�ϼž� �մϴ�.</span>)
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2" background="images/table_con_line.gif"></td>
																	</tr>
																	<tr>
																		<td class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">Q&A ���� ����</td>
																		<td class="td_con1" >
																			<table  border="0" cellpadding="0" cellspacing="0">
																				<tr>
																					<td class="td_con1b">
																						<input type="radio" name="use_mobile_qna" value="Y" <? if($row[use_mobile_qna]=="Y") echo "checked";?>>���<span class="font_orange2">('����ϼ��� ��ǰQ&A �Խ��� ������)</span>
																					</td>
																				</tr>
																				<tr>
																					<td class="td_con1b">
																						<input type="radio" name="use_mobile_qna"  value="N" <? if($row[use_mobile_qna]=="N") echo "checked";?>>������<span class="font_orange2">('����� ���� ��ǰQ&A �Խ��� ���� ���� ����')</span>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2" background="images/table_con_line.gif"></td>
																	</tr>
																	<tr>
																		<td class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">Q&A ���� ����</td>
																		<td class="td_con1" >
																			<table  border="0" cellpadding="0" cellspacing="0">
																				<tr>
																					<td class="td_con1b">
																						<input type="radio" name="use_mobile_qna_write" value="Y" <? if($row[use_mobile_qna_write]=="Y") echo "checked";?>>���<span class="font_orange2">('����ϼ��� ��ǰQ&A ���� �����)</span>
																					</td>
																				</tr>
																				<tr>
																					<td class="td_con1b">
																						<input type="radio" name="use_mobile_qna_write"  value="N" <? if($row[use_mobile_qna_write]=="N") echo "checked";?>>������<span class="font_orange2">('����� ���� ��ǰQ&A �Խ��� ���� ���� ����')</span>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2" background="images/table_con_line.gif"></td>
																	</tr>
																	<tr>
																		<td class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">SNS ���� ����</td>
																		<td class="td_con1" >
																			<table  border="0" cellpadding="0" cellspacing="0">
																				<tr>
																					<td class="td_con1b">
																						<input type="checkbox" name="kakaotalk" value="Y" <? if($kakaotalk=="Y") echo "checked";?>/><span>īī����</span>
																						<input type="checkbox" name="kakaostory" value="Y" <? if($kakaostory=="Y") echo "checked";?>/><span>īī�����丮</span>
																						<input type="checkbox" name="facebook" value="Y" <? if($facebook=="Y") echo "checked";?>/><span>���̽���</span>
																						<input type="checkbox" name="twitter" value="Y" <? if($twitter=="Y") echo "checked";?>/><span>Ʈ����</span>
																						<span class="font_orange2">(����ϼ� ��ǰ�󼼺��� ȭ�鿡 ����˴ϴ�.)</span>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2" background="images/table_con_line.gif"></td>
																	</tr>
																	<tr>
																		<td class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">��Ų����</td>
																		<td class="td_con1" >
																			<select name="skin">
																				<option value="default" <?if($row[skin] == "default"){?> selected <?}?>>Default</option>
																			</select>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2" background="images/table_con_line.gif"></td>
																	</tr>
																	<tr>
																		<td class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">�ΰ���</td>
																		<td class="td_con1" >
																			<table width="100%">
																				<tr>
																					<td><input type=file name="logo" style="width:480px; font-size:12px; border:1px solid #DCDCDC; background-color:#ffffff;"></td>
																				</tr>
																				<tr>
																					<td class=linebottomleft style="PADDING-RIGHT: 5px; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" align=left width="100%" bgColor=#ffffff>
																						<p>
																							<? if ($row[logo]) {?>
																								<img src="<?=$logoimagepath?><?=$row[logo]?>" border=0 style="border-width:1pt; border-color:rgb(235,235,235); border-style:solid;"> <a href="javascript:CheckForm('logodel');"><img src="images/btn_del.gif" width="50" height="22" border="0" hspace="3"></a>
																							<? } else { ?>
																								��ϵ� �ΰ� �����ϴ�.
																							<? } ?>
																						</p>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2" background="images/table_con_line.gif"></td>
																	</tr>
																	<tr>
																		<td class="table_cell" width="180"><img src="images/icon_point2.gif" width="8" height="11" border="0">ī�Ƕ���Ʈ</td>
																		<td class="td_con1" >
																			<table width="100%">
																				<tr>
																					<td>
																						<input type=file name="copyright_image" style="width:480px; font-size:12px;border:1px solid #DCDCDC;background-color:#ffffff;">
																					</td>
																				</tr>
																				<tr>
																					<td class="td_con1b" style="PADDING-RIGHT: 5px; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" align=left width="100%" bgColor=#ffffff>
																						<p>
																							<? if ($row[copyright_image]) { ?>
																							<img src="<?=$logoimagepath?><?=$row[copyright_image]?>" border=0 style="border-width:1pt; border-color:rgb(235,235,235); border-style:solid;"> <a href="javascript:CheckForm('copyrightimagedel');"><img src="images/btn_del.gif" width="50" height="22" border="0" hspace="3"></a>
																							<? } else { ?>
																							��ϵ� ī�Ƕ���Ʈ �̹����� �����ϴ�.
																							<? } ?>
																						</p>
																					</td>
																				</tr>
																				<tr>
																					<td class="td_con1b" style="PADDING-RIGHT: 5px; PADDING-LEFT: 0; PADDING-BOTTOM: 5px; PADDING-TOP: 5px" align=left width="100%" bgColor=#ffffff>
																						<!-- <input type=text name="copyright_text" style="WIDTH: 100%" class="input" value="<?=$row[copyright_text]?>"> -->
																						<textarea name="copyright_text" style="width:90%; height:65px;" class="input"><?=$row[copyright_text]?></textarea>
																						<div><span class="font_orange2">(�ؽ�Ʈ�� copyright�� ��ü�մϴ�. ī�Ƕ���Ʈ �̹����� ���� ���, �̹��� ����� �켱�մϴ�)</span></div>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="2" background="images/table_con_line.gif"></td>
																	</tr>
																	<tr>
																		<td background="images/table_top_line.gif" colspan=2></td>
																	</tr>
																</table>
															<!-- ȯ�漳�� �� -->
															</form>
														</td>
													</tr>
													<tr>
														<td align="center">
															<a href="javascript:CheckForm('up')"><img src="images/botteon_save.gif" id="uploadButton" width="113" height="38" border="0"></a>
														</td>
													</tr>
													<tr><td height="30"></td></tr>
													<tr>
														<td>
															<!-- ��� ��� ����-->
															<form name="form2" action="<?=$_SERVER[PHP_SELF]?>" method="post" enctype="multipart/form-data">
																<input type="hidden" name="type">
																<input type="hidden" name="place">
																<input type="hidden" name="bannerplace">
																<input type="hidden" name="bannerdate">
																<table cellpadding=0 cellspacing=0 border=0 width=100%>
																	<tr>
																		<td>
																			<table width="100%" border=0 cellpadding=0 cellspacing=0>
																				<tr>
																					<td><img src="images/shop_logobanner_stitle2.gif" width="192" height=31 alt=""></td>
																					<td width="100%" background="images/shop_basicinfo_stitle_bg.gif"></td>
																					<td><img src="images/shop_basicinfo_stitle_end.gif" width=10 height=31 alt=""></td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr><td height=3></td></tr>
																	<tr>
																		<td>
																			<table border="0" cellpadding="0" cellspacing="0">
																				<tr>
																					<td background="images/distribute_04.gif"></td>
																					<td class="notice_blue" valign="top"><img src="images/distribute_img.gif" ></td>
																					<td width="100%" class="notice_blue">
																						��ʼ��� ����� ��ġ�� ����Ǵ� �� ����� ���� ���� ��� �����ϼž� �մϴ�.<br />
																						(��: "����1"�� ��ʸ� "����3" ���� ������ ��� - 1�� ����� ������ 3, 3�� ����� ������ 1)
																					</td>
																					<td background="images/distribute_07.gif"></td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr><td height=5></td></tr>
																	<tr>
																		<td>
																			<table cellspacing=0 cellpadding=0 width="100%" border=0>
																				<tr>
																					<td background="images/table_top_line.gif" width="760" colspan="4"></td>
																				</tr>
																				<tr>
																					<td class="table_cell" width="33"><p align="center">����</td>
																					<td class="table_cell1"><p align="center">����̹���</td>
																					<td class="table_cell1" width="439"><p align="center">��ũ�ּ�</td>
																					<td class="table_cell1" width="80"><p align="center">����/����</td>
																				</tr>
																				<tr>
																					<td colspan="4" width="760" background="images/table_con_line.gif"></td>
																				</tr>
																				<?
																					$sql0 = "SELECT COUNT(*) as cnt FROM tblmobilebanner ";
																					$result = mysql_query($sql0,get_db_conn());
																					$row = mysql_fetch_object($result);
																					mysql_free_result($result);
																					$cnt = $row->cnt;

																					$sql = "SELECT * FROM tblmobilebanner ORDER BY date DESC";
																					$result = mysql_query($sql,get_db_conn());
																					$count=1;
																					while($row=mysql_fetch_object($result)){
																					$image = $row->image;
																					$url = $row->url;
																				?>
																				<tr>
																					<td class="td_con" noWrap align=middle width=60>
																						<p align="center">
																							<select name=bannerplace class="select">
																							<? for($i=1;$i<=$cnt;$i++){
																								echo "<option value=\"".$i."\"";
																								if($i==$count) {
																									echo " selected";
																								}
																								echo ">".($i);
																							   }
																							?>
																							</select>
																							<input type="hidden" name=bannerdate value="<?=$row->date?>">
																						</p>
																					</td>
																					<td class="td_con1" width="151"><img src="<?=$bannerimagepath.$image?>" border="<?=$row->border?>" width=320 height="100"class="imgline"></td>
																					<td class="td_con1" width="447">
																						<a href=http<?=($row->url_type=="S"?"s":"")?>://<?=$url?> target=<?=$row->target?>><font color=#0000a0>http<?=($row->url_type=="S"?"s":"")?>://<?=$url?></font></a>
																					</td>
																					<td class="td_con1" width="88">
																						<p align="center">
																							<a href="javascript:BannerModify('<?=$row->date?>');"><img src="images/btn_edit.gif" border="0" alt="" /></a>
																							<a href="javascript:BannerDel('<?=$row->date?>');"><img src="images/btn_del.gif" border="0" alt="" /></a>
																						</p>
																					</td>
																				</tr>
																				<tr>
																					<td colspan="4" width="760" background="images/table_con_line.gif"></td>
																				</tr>
																				<?
																					$count++;
																					}
																					mysql_free_result($result);
																					if($cnt==0) {
																				?>
																				<tr>
																					<td class=lineleft colspan=4 align=center height=30>
																						<font color=#383838>��ϵ� ��ʰ� �����ϴ�.</font>
																					</td>
																				</tr>
																				<tr>
																					<td colspan="4" width="760" background="images/table_con_line.gif"></td>
																				</tr>
																				<? } ?>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td background="images/table_top_line.gif"></td>
																	</tr>
																	<tr><td height="20"></td></tr>
																	<tr>
																		<td align="center">
																			<?
																				if ($cnt > 0) {
																					echo "<a href=\"javascript:BannerSort('$cnt');\"><img src=\"images/botteon_save.gif\" width=\"113\" height=\"38\" border=\"0\" alt=\"\" /></a>\n";
																				}
																			?>
																		</td>
																	</tr>
																	<tr><td height="30"></td></tr>
																	<tr>
																		<td>
																			<table width="100%" border=0 cellpadding=0 cellspacing=0>
																				<tr>
																					<td><img src="images/mobile_sstitle03.gif" alt="" /></td>
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
																		<td style="padding-top:3pt; padding-bottom:3pt;">
																			<table width="100%" border=0 cellpadding=0 cellspacing=0>
																				<tr>
																					<td><img src="images/distribute_01.gif"></td>
																					<td colspan=2 background="images/distribute_02.gif"></td>
																					<td><img src="images/distribute_03.gif"></td>
																				</tr>
																				<tr>
																					<td background="images/distribute_04.gif"></td>
																					<td class="notice_blue" valign="top"><img src="images/distribute_img.gif" ></td>
																					<td width="100%" class="notice_blue">
																						<p>1) <b>GIF(gif), JPG(jpg), PNG(png)���ϸ�</b> ��� �����մϴ�.<br>
																							2) �̹��� �������� ��� <b>���α������� 100% ó���˴ϴ�.</b><br>3) �̹��� �뷮 <b><?=$msg_size?> ����</b> �� ���ε� �����մϴ�.
																						</p>
																					</td>
																					<td background="images/distribute_07.gif"></td>
																				</tr>
																				<tr>
																					<td><img src="images/distribute_08.gif"></td>
																					<td colspan=2 background="images/distribute_09.gif"></td>
																					<td><IMG SRC="images/distribute_10.gif"></td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td>
																			<table cellpadding="0" cellspacing="0" width="100%">
																				<tr>
																					<td bgcolor="#ededed" style="padding:4pt;">
																						<table cellpadding="0" cellspacing="0" width="100%" bgcolor="white">
																							<tr>
																								<td width="100%">
																									<table cellspacing=0 cellpadding=0 width="100%" border=0>
																										<tr>
																											<td  colspan="2" height="35" background="images/blueline_bg.gif">
																												<p align="center">
																													<b><font color="#333333">��ʵ���ϱ�</font></b>
																												</p>
																											</td>
																										</tr>
																										<tr>
																											<td colspan="2" width="760" background="images/table_con_line.gif"></td>
																										</tr>
																										<tr>
																											<td width="148" class="table_cell"><b><img src="images/icon_point2.gif" width="8" height="11" border="0"></b>��� �̹���</td>
																											<td  class="td_con1"> <input type=file name=up_image style="width:480px; font-size:12px;border:1px solid #DCDCDC;background-color:#ffffff;"></td>
																										</tr>
																										<tr>
																											<td colspan="2" width="760" background="images/table_con_line.gif"></td>
																										</tr>
																										<tr>
																											<td width="148" class="table_cell"><b><img src="images/icon_point2.gif" width="8" height="11" border="0"></b>���� URL</td>
																											<td  class="td_con1">
																												http://
																												<input type=text name=up_url size="72"  onKeyUp="chkFieldMaxLen(200)" class="input" >
																											</td>
																										</tr>
																										<tr>
																											<td colspan="2" width="760" background="images/table_con_line.gif"></td>
																										</tr>
																											<input type="hidden" name="up_type" value="H" />
																											<input type="hidden" name="up_target" value="_self" />
																											<input type="hidden" name="up_border" value="0" />
																									</table>
																								</td>
																							</tr>
																						</table>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr>
																		<td style="padding-top:15pt;">
																			<p align="center">
																			<a href="javascript:BannerAdd();"><img src="images/botteon_insert.gif" border="0" alt="" /></a>
																		</td>
																	</tr>
																	<tr>
																		<td height="20"></td>
																	</tr>
																</table>

																<!-- ��� ��� �� -->
															</form>
														</td>
													</tr>
													<tr>
														<td height="50"></td>
													</tr>
													<tr>
														<td>
														<!-- �޴��� ���� -->
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
																					<td ><span class="font_dotline">����ϼ��θ� �⺻ ȯ�漳��</span></td>
																				</tr>
																				<tr>
																					<td width="20" align="right">&nbsp;</td>
																					<td  class="space_top">
																						- ����ϼ� Q&A ������ ��� ���⼳���� �����ϸ� �׿� ���� ������ ��� PC������ �����մϴ�.<br/>
																						- �������� ���ÿ��� ����ϼ����� ����� ����Ÿ���� �����Ͻø� �˴ϴ�.<br/>
																						- ���ڰ��� ����� �������� ���ÿ��� ���õǾ��ִ��� ���θ� PG������, ����� ���ڰ��� �������� "���ڰ��� �����"���� �������� ���� ��� �ֹ������� ���ڰ��� ����Ÿ���� ����Ͻ� �� �����ϴ�.</br>
																						- ����ϼ� ���ڰ��� ������ ��� ���θ� PG ���� �� ���� ����ϼ� �޴����� "���ڰ�������" �޴����� �����Ͻ� �� �ֽ��ϴ�<br/>
																						- ���ڰ����� �ſ�ī��, �������, ������ü ���� ���ϸ� ���� ����ϼ������� �ſ�ī�常 �����մϴ�.<br/>
																						- ����ϼ� �ΰ� �� ī�Ƕ���Ʈ �̹����� ���ϻ������ <?=$msg_etc_size?>�� ���ѵ˴ϴ�.<br/>
																						- �ΰ��̹����� ���������� ����100px, ����40px �̸� �̿� �ٸ���� ���α��� 40px�� �����˴ϴ�.<br/>
																						- ����ϼ� copyright�� �̹����� ����Ͻ� ��� ������� ����̽��� �ػ󵵿� ���� ���̾ƿ� �� ���� ���°� ���� �Ҽ� ������ �ش� �κ����� ���� ������ ��� A/S���� ����� �ƴϹǷ� �ؽ�Ʈ ����� �����մϴ�.<br/>
																						- ����ϼ� copyright�� �ؽ�Ʈ�� ��Ͻ� �⺻ ��� ���� �Ǹ�, &ltbr&gt,&lta&gt �� ����Ͻ� �� ������ �̿� �ٸ� �±״� ���� ���� �ʽ��ϴ�.
																					
																					</td>
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
														<!-- �޴��� �� -->
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
										<tr>
											<td height="20"></td>
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
<style>
	form{margin:0px;padding:0px;border:0px;}
</style>
<form name="bannerModify" method="post">
	<input type="hidden" name="bdate" value=""/> 
</form>
<? INCLUDE "copyright.php"; ?>
