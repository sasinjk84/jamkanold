<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	include_once($Dir."lib/shopdata.php");
	INCLUDE ("access.php");

	####################### ������ ���ٱ��� check ###############
	$PageCode = "mo-1";
	$MenuCode = "mobile";
	if (!$_usersession->isAllowedTask($PageCode)) {
		INCLUDE ("AccessDeny.inc.php");
		exit;
	}
	#########################################################

	#### PG ����Ÿ ���� ####
	$_ShopInfo->getPgdata();

	$pgid_info = GetEscrowType($_data->card_id);

	$mode = isset($_POST['mode'])? $_POST['mode'] : "";
	$interlock = isset($_POST['interlock'])? $_POST['interlock'] : "";
	$interlock_mode = isset($_POST['interlock_mode'])? $_POST['interlock_mode'] : "";
	$id = $pgid_info['ID'];
	$key = $pgid_info['KEY'];

	$isfile = $_SERVER[DOCUMENT_ROOT]."/authkey/pg";

	if(!is_file($isfile)){
		echo '<script>alert("���ڰ��� ������ �Ǿ� ���� �ʽ��ϴ�.\n�ش� ����� ���ڰ��� ���� �� ����� �����մϴ�.");history.go(-1);</script>';
		exit;
	}

	if(count($pgid_info) < 3){ //
		echo '<script>alert("���ڰ��� ������ �ùٸ��� �ʽ��ϴ�.\n�ش� ����� ���ڰ��� ���� ���� �� ����� �����մϴ�.");history.go(-1);</script>';
		exit;
	}else{
		$pg_type = trim($pgid_info['PG']);
		$getSql = "SELECT * FROM tblmobilepg WHERE 1=1";
		$getResult = mysql_query($getSql, get_db_conn());
		$getRow = mysql_fetch_object($getResult);
		@mysql_free_result($get_result);

		unset($onInterlock);
		unset($offInterlock);
		unset($realMode);
		unset($testMode);

		switch($getRow->pg_use){ //����ϼ� ���ڰ��� ��뼳�� Y:�����, N:������
			case "Y":
				$onInterlock = "checked";
				$offInterlock = "";
			break;
			case "N":
				$onInterlock = "";
				$offInterlock = "checked";
			break;
			default:
				$onInterlock = "";
				$offInterlock = "checked";
			break;
		}
		switch($getRow->pg_mode){ //������� ���� R:��������, T:�׽�Ʈ����
			case "R":
				$realMode = "checked";
				$testMode = "";
			break;
			case "T":
				$realMode = "";
				$testMode = "checked";
			break;
			default:
				$realMode = "";
				$testMode = "checked";
			break;
		}


		$addClass = "";
		switch($pg_type){
			case "A":
				$company = "KCP";
			break;
			case "B":
				$company = "LG U+(�� DACOM)";
			break;
			case "C":
				$company = "ALLTHEGATE : ����ϼ� ���ڰ����� ���� �������� �ʴ� PG���Դϴ�.";
				$addClass = "optionhide";
			break;
			case "D":
				$company = "INICIS : ����ϼ� ���ڰ����� ���� �������� �ʴ� PG���Դϴ�.";
				$addClass = "optionhide";
			break;
			case "E":
				$company = "NICE";
			break;
			default:
				$company = "���ڰ����� �������� �ʾҰų� �������� �ʴ� PG���Դϴ�.";
				$addClass = "optionhide";
			break;
		}
	}
	// A -> KCP, B -> LG, C->allthegae, D->inicis

	if($mode == "update"){
		$setSql = "UPDATE tblmobilepg SET ";
		$setSql .= "pg_use= '".$interlock."', ";
		$setSql .= "pg_type= '".$pg_type."', ";
		$setSql .= "pg_id= '".$id."', ";
		$setSql .= "pg_key= '".$key."', ";
		$setSql .= "pg_mode= '".$interlock_mode."', ";
		$setSql .= "pg_date= '".date("YmdHi")."' ";
		$setSql .= "WHERE pg_section='mobile'";

		if(mysql_query($setSql,get_db_conn())){

			$filename = "mall.conf";
			$file_loc = $_SERVER[DOCUMENT_ROOT]."/m/paygate/B/lgdacom/conf/";
			$file = $file_loc.$filename;

			if($pg_type == "B" && $interlock == "Y"){ // PG�簡 LGU+ �� ��뼳�� �� ��� ����


				if(is_file($file)){  //������ �����ϸ�
					$confirm = fopen($file, "r");
					$str = "";

					if($confirm){
						while(!feof($confirm)){
							$str.= fgets($confirm, 1024);
						}
						$param = array();
						$param[] = "";
						$param[] = ";log _dir";
						$param[] = "log_dir = ".$_SERVER[DOCUMENT_ROOT]."/m/paygate/B/lgdacom/log";
						$param[] = ";���� ID";
						$param[] = "t".$id." = ".$key;
						$param[] = $id." = ".$key;

						if(strpos($str, "add")){  //
							$param[0] = "add";
							$modify = fopen($file, "w+");
							$modify_str = strstr($str, "add");
							$loop = count($param);
							for($i=0;$i < $loop ; $i++){
								$setStr .= $param[$i]."\r\n";
								if($i == 2){
									$setStr .= "\r\n";
								}
							}
							$contents = str_replace($modify_str,$setStr,$str);
							fwrite($modify, $contents);
							fclose($modify);
						}else{

							$param[0] = ";add";
							$loop = count($param);
							$write =fopen($file, "a");

							for($i=0;$i < $loop ; $i++){

								if($i == 2){
									$line .= "\r\n\r\n";
								}else{
									$line .= "\r\n";
								}
								fwrite($write,"\r\n".$param[$i]);
							}
						}
					}
					fclose($confirm);
				}else{
					echo '<script>alert("���������� ������ �������� �ʽ��ϴ�");</script>';
				}
			}else{

				if(is_file($file)){  //������ �����ϸ�
					$confirm = fopen($file, "r");
					$str = "";
					while(!feof($confirm)){
							$str.= fgets($confirm, 1024);
					}
					fclose($confirm);
					if(strpos($str, "add")){  //
						$param[0] = "add";
						$del = fopen($file, "w+");
						$del_str = strstr($str, "add");
						$loop = count($param);
						for($i=0;$i < $loop ; $i++){
							$setStr .= $param[$i]."\r\n";
							if($i == 2){
								$setStr .= "\r\n";
							}
						}
						$contents = str_replace($del_str,"add",$str);
						fwrite($del, $contents);
						fclose($del);
					}

				}
			}
			echo '<script>alert("���������� ����Ǿ����ϴ�.");location.href="./mobile_payment.php";</script>';
			exit;
		}else{
			echo '<script>alert("���������� ������� �ʾҽ��ϴ�.");</script>';
		}
	}
?>

<? INCLUDE "header.php"; ?>
<style>
	.optionhide{display:none;}
</style>
<script type="text/javascript" src="lib.js.php"></script>
<script type="text/javascript" src="<?=$Dir?>js/jquery-1.8.3.min.js"></script>
<script>  var $j = jQuery.noConflict(); </script>
<script language="JavaScript">


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
														<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : ����ϼ� &gt; <span class="2depth_select">���ڰ�������</span></td>
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
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD>
																	<IMG SRC="images/mobile_payment_title.gif" border="0"></TD>
																</tr>
																<tr>
																	<TD width="100%" background="images/title_bg.gif" height="21"></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr>
														<td height="3"></td>
													</tr>
													<tr>
														<td style="padding-bottom:3pt;">
															<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
																<TR>
																	<TD><IMG SRC="images/distribute_01.gif"></TD>
																	<TD COLSPAN="2" background="images/distribute_02.gif"></TD>
																	<TD><IMG SRC="images/distribute_03.gif"></TD>
																</TR>
																<TR>
																	<TD background="images/distribute_04.gif"></TD>
																	<TD class="notice_blue"><IMG SRC="images/distribute_img.gif" ></TD>
																	<TD width="100%" class="notice_blue">����ϼ��θ��� PG���ڰ����� �����ϰ� �����ϽǼ� �ֽ��ϴ�.</TD>
																	<TD background="images/distribute_07.gif"></TD>
																</TR>
																<TR>
																	<TD><IMG SRC="images/distribute_08.gif"></TD>
																	<TD COLSPAN="2" background="images/distribute_09.gif"></TD>
																	<TD><IMG SRC="images/distribute_10.gif"></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr>
														<td height="20"></td>
													</tr>
													<tr>
														<td>
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD><IMG SRC="images/mobile_payment_stitle.gif" border="0"></TD>
																	<TD width="100%" background="images/shop_basicinfo_stitle_bg.gif"></TD>
																	<TD><IMG SRC="images/shop_basicinfo_stitle_end.gif" WIDTH=10 HEIGHT=31 ALT=""></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr>
														<td height=3></td>
													</tr>
													<tr>
														<td>
															<form name=pgForm action="<?=$_SERVER[PHP_SELF]?>" method=post enctype="multipart/form-data">
																<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0 class="container_line">
																	<tr>
																		<td class="table_cell" width=180>
																			������ ���� ����
																		</td>
																		<td class="td_con1">
																			<table cellpadding=0 cellspacing=0>
																				<tr>
																					<td>
																						<span class="inner_text"><?=$company?></span>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<tr class="<?=$addClass?>">
																		<td class="table_cell inner_line" width=180>
																			����ϼ� ���ڰ��� ��� ����
																		</td>
																		<td class="td_con1 inner_line">
																			<table cellpadding=0 cellspacing=0 >
																				<tr>
																					<td>
																						<input type="radio" class="interlock" name="interlock" value="Y" <?=$onInterlock?>><label>���ڰ��� �����</label>
																					</td>
																				</tr>
																				<tr>
																					<td>
																						<input type="radio" class="interlock" name="interlock" value="N" <?=$offInterlock?>><label>���ڰ��� ������</label>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr>
																	<!-- <tr class="state_interlock <?=$addClass?>">
																		<td class="table_cell inner_line" width="180">
																			����ϼ� ���ڰ��� ��� ���
																		</td>
																		<td class="td_con1 inner_line">
																			<table cellpadding=0 cellspacing=0>
																				<tr>
																					<td>
																						<input type="radio" name="interlock_mode" value="R" <?=$realMode?>><label>��������</label>&nbsp;
																						<input type="radio" name="interlock_mode" value="T" <?=$testMode?>><label>�׽�Ʈ����</label>
																					</td>
																				</tr>
																			</table>
																		</td>
																	</tr> -->
																</TABLE>
																<input type="hidden" name="mode" value="update">
															</form>
														</td>
													</tr>
													<tr>
														<td height=20></td>
													</tr>
													<tr>
														<td align="center"><a href="#" id="btn_submit"><img src="./images/botteon_save.gif"></a></td>
													</tr>
													<tr>
														<td height=20></td>
													</tr>
													<tr>
														<td>
															<TABLE WIDTH="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0>
																<TR>
																	<TD><IMG SRC="images/manual_top1.gif" WIDTH=15 height="45" ALT=""></TD>
																	<TD><IMG SRC="images/manual_title.gif" WIDTH=113 height="45" ALT=""></TD>
																	<TD width="100%" background="images/manual_bg.gif" height="35"></TD>
																	<TD background="images/manual_bg.gif"></TD>
																	<td background="images/manual_bg.gif"><IMG SRC="images/manual_top2.gif" WIDTH=18 height="45" ALT=""></td>
																</TR>
																<TR>
																	<TD background="images/manual_left1.gif"></TD>
																	<TD COLSPAN=3 width="100%" valign="top" class="menual_bg" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;">
																		<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
																			<col width=20></col>
																			<col width=></col>
																			<tr>
																				<td align="right" valign="top"><img src="images/icon_8.gif" width="13" height="18" border="0"></td>
																				<td><span class="font_dotline">����� ���ڰ��� ����</span></td>
																			</tr>
																			<tr>
																				<td align="right">&nbsp;</td>
																				<td class="space_top" style="letter-spacing:-0.5pt;">
																				- ����ϼ� ���ڰ��� ������ ��� ���θ� PG���� ���¸� �⺻���� �ϹǷ� ���� ���θ� PG������ ���� �����Ͽ��� �մϴ�.<br/>
																				- ����ϼ� ���ڰ����� ��� ���� �ſ�ī�常 �����մϴ�<br/>
																				- ������ ���� ������ ��� ���θ��� ������ PG�� ������ �����ɴϴ�.<br/>
																				- ����ϼ� ���ڰ����� ��� Android, iOS�� �����˴ϴ�. <br/>
																				- �ֽ� ����� OS�� ������ �������� ������ ������� ���� �� �ֽ��ϴ�<br/>
																				- KCP ����ϼ� ���ڰ����� ��� PHPȯ�濡�� SOAP ����� �����Ǿ��־�� �������մϴ� <br/>
																				- LGU+ ����ϼ� ���ڰ����� ���  ����â2.0 ��Ŀ����� ���� ���� �մϴ�<br/>
																				- ���� ����ϼ��� ���������� PG��� KCP, LGU+�� ���� �̿� PG��� �������� �ʽ��ϴ�.
																				</td>
																			</tr>
																			<tr>
																				<td colspan="2" height="20"></td>
																			</tr>
																		</table>
																	</TD>
																	<TD background="images/manual_right1.gif"><IMG SRC="images/manual_right1.gif" WIDTH=18 HEIGHT="2" ALT=""></TD>
																</TR>
																<TR>
																	<TD><IMG SRC="images/manual_left2.gif" WIDTH=15 HEIGHT=8 ALT=""></TD>
																	<TD COLSPAN=3 background="images/manual_down.gif"></TD>
																	<TD><IMG SRC="images/manual_right2.gif" WIDTH=18 HEIGHT=8 ALT=""></TD>
																</TR>
															</TABLE>
														</td>
													</tr>
													<tr>
														<td height="50"></td>
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

<script>
	//console.log($j('input[name="interlock"]:checked').val());

	if($j('input[name="interlock"]:checked').val() == "N"){
		$j('.state_interlock').hide();
	}
	$j('input[name="interlock"]').click(function(){
		if(this.value == "Y"){
			$j('.state_interlock').show();
		}else{
			$j('.state_interlock').hide();
		}
	});

	$j('input[name="interlock_mode"]').click(function(){
		if(this.value == "R"){
			alert("�ŷ��� ���������� �̷�����ϴ�.");
		}else{
			alert("�ŷ��� �̷������ �������� ������,\n�׽�Ʈ���� �ֹ���� ó���� ���� �ʽ��ϴ�.")
		}
	});

	$j('#btn_submit').click(function(){
		//console.log($j('input[name="interlock"]:checked').length);

		if($j('input[name="interlock"]:checked').length <= 0){
			alert("���ڰ��� ��� ���� ���¸� Ȯ�� �ϼ���.");
			return false;
		}

		/*if($j('input[name="interlock"]:checked').val() == "Y"){
			if($j('input[name="interlock_mode"]:checked').length <= 0){
				alert("���ڰ��� ��� ��� ���¸� Ȯ�� �ϼ���.");
				return false;
			}
		}*/

		if(confirm("����� �� PG ���� ���°� ����˴ϴ�.")){
			var _form = document.pgForm;
			_form.submit();
		}

	});

</script>
<? INCLUDE "copyright.php"; ?>