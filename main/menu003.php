<?
if(substr(getenv("SCRIPT_NAME"),-12)=="/menu003.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

if ($_data->frame_type!="N") include($Dir.MainDir.$_data->onetop_type.".php");
else if($_data->align_type=="Y") echo "<center>";
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
var quickview_path="<?=$Dir.FrontDir?>product.quickview.xml.php";
var quickfun_path="<?=$Dir.FrontDir?>product.quickfun.xml.php";
function sendmail() {
	window.open("<?=$Dir.FrontDir?>email.php","email_pop","height=100,width=100");
}

//ī�װ� ��
function categoryView(obj,type){
	var obj;
	var div = eval("document.all." + obj);

	if(type == 'open'){
		div.style.display = "block";
	}else if (type == 'over'){
		div.style.display = "block";
	}else if (type == 'out'){
		div.style.display = "none";
	}
}

function estimate(type) {
	if(type=="Y") {
		window.open("<?=$Dir.FrontDir?>estimate_popup.php","estimate_pop","height=100,width=100,scrollbars=yes");
	} else if(type=="O") {
		if(typeof(top.main)=="object") {
			top.main.location.href="<?=$Dir.FrontDir?>estimate.php";
		} else {
			document.location.href="<?=$Dir.FrontDir?>estimate.php";
		}
	}
}
function privercy() {
	window.open("<?=$Dir.FrontDir?>privercy.php","privercy_pop","height=570,width=590,scrollbars=yes");
}
function order_privercy() {
	window.open("<?=$Dir.FrontDir?>privercy.php","privercy_pop","height=570,width=590,scrollbars=yes");
}
function logout() {
	location.href="<?=$Dir.MainDir?>main.php?type=logout";
}
function sslinfo() {
	window.open("<?=$Dir.FrontDir?>sslinfo.php","sslinfo","width=100,height=100,scrollbars=no");
}
function memberout() {
	if(typeof(top.main)=="object") {
		top.main.location.href="<?=$Dir.FrontDir?>mypage_memberout.php";
	} else {
		document.location.href="<?=$Dir.FrontDir?>mypage_memberout.php";
	}
}
function notice_view(type,code) {
	if(type=="view") {
		window.open("<?=$Dir.FrontDir?>notice.php?type="+type+"&code="+code,"notice_view","width=450,height=450,scrollbars=yes");
	} else {
		window.open("<?=$Dir.FrontDir?>notice.php?type="+type,"notice_view","width=450,height=450,scrollbars=yes");
	}
}
function information_view(type,code) {
	if(type=="view") {
		window.open("<?=$Dir.FrontDir?>information.php?type="+type+"&code="+code,"information_view","width=600,height=500,scrollbars=yes");
	} else {
		window.open("<?=$Dir.FrontDir?>information.php?type="+type,"information_view","width=600,height=500,scrollbars=yes");
	}
}
function GoPrdtItem(prcode) {
	window.open("<?=$Dir.FrontDir?>productdetail.php?productcode="+prcode,"prdtItemPop","WIDTH=800,HEIGHT=700 left=0,top=0,toolbar=yes,location=yes,directories=yse,status=yes,menubar=yes,scrollbars=yes,resizable=yes");
}

<?if(substr($_data->layoutdata["MOUSEKEY"],3,1)=="Y"){?>
function funkeyclick() {
    if (navigator.appName=="Netscape" && (e.which==3 || e.which==2)) return;
    else if (navigator.appName=="Microsoft Internet Explorer" && (event.button==2 || event.button==3 || event.keyCode==93)) return;

    if(navigator.appName=="Microsoft Internet Explorer" && (event.ctrlKey && event.keyCode==78)) return false;
}
document.onmousedown=funkeyclick;
document.onkeydown=funkeyclick;
<?}?>
//-->
</SCRIPT>

<?
if(substr($_data->layoutdata["SHOPBGTYPE"],0,1)=="B") {			//���� ����
	echo "<style>\n";
	if(substr($_data->layoutdata["SHOPBGTYPE"],1,1)=="Y") {
		echo "#tableposition { background-color: transparent; }\n";
	} else {
		echo "#tableposition { background-color: #FFFFFF; }\n";
	}
	if(substr($_data->layoutdata["BGCOLOR"],0,1)=="N") {
		echo "BODY {background-color: ".(strlen(substr($_data->layoutdata["BGCOLOR"],1,7))==7?substr($_data->layoutdata["BGCOLOR"],1,7):"#FFFFFF")."}\n";
	} else {
		echo "BODY {background-color: transparent}\n";
	}
	echo "</style>\n";
} else if(substr($_data->layoutdata["SHOPBGTYPE"],0,1)=="I") {	//��׶��� ����
	echo "<style>\n";
	if(substr($_data->layoutdata["SHOPBGTYPE"],1,1)=="N") {
		echo "#tableposition { background-color: #FFFFFF; }\n";
	} else {
		echo "#tableposition { background-color: transparent; }\n";
	}
	if(file_exists($Dir.DataDir."shopimages/etc/background.gif")) {
		echo "BODY {\n";
		echo "background-image: url('".$Dir.DataDir."shopimages/etc/background.gif');\n";
		$background_repeat=array("A"=>"repeat","B"=>"repeat-x","C"=>"repeat-y","D"=>"no-repeat");
		echo "background-repeat: ".$background_repeat[substr($_data->layoutdata["BACKGROUND"],2,1)].";\n";
		$background_position=array("A"=>"top left","B"=>"top center","C"=>"top right","D"=>"center left","E"=>"center center","F"=>"center right","G"=>"bottom left","H"=>"bottom center","I"=>"bottom right");
		echo "background-position: ".$background_position[substr($_data->layoutdata["BACKGROUND"],1,1)].";\n";
		if(substr($_data->layoutdata["BACKGROUND"],0,1)=="Y") {
			echo "background-attachment: fixed;\n";
		}
	}
	echo "</style>\n";
}
?>

<table border="0" width="<?=($_data->layoutdata["SHOPWIDTH"]>0?$_data->layoutdata["SHOPWIDTH"]:"980")?>" cellpadding="0" cellspacing="0" id="tableposition">
<tr>
	<td>
	<table cellpadding="0" cellspacing="0" width="100%">
	<?if($sleftMn !="NO"){?>
	<col width="<?=$_data->layoutdata["SHOPLEFTMENUWIDTH"]?>"></col>
	<?}?>
	<col></col>
	<tr>
<?if($sleftMn !="NO"){?>
		<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr><td height="10"></td></tr>
		<tr>
			<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><IMG SRC="<?=$Dir?>images/<?=$_data->icon_type?>/main_skin3_top_category.gif" border="0"></td>
			</tr>
			<tr>
				<td valign="top" background="<?=$Dir?>images/<?=$_data->icon_type?>/main_skin3_top_cate_bg.gif">
				<?//if(strlen($brand_link)==0 || (strlen($brand_link)>0 && strlen($brand_qry)>0)) {?>
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<?
				$sql = "SELECT codeA as code, type, code_name FROM tblproductcode ";
				$sql.= "WHERE group_code!='NO' ";
				//$sql.= $brand_qry;
				$sql.= "AND (type='L' OR type='T' OR type='LX' OR type='TX') ORDER BY sequence DESC ";
				$result=mysql_query($sql,get_db_conn());
				$icount=0;

				//if(strlen($brand_link)>0) {
				//	$blistbrand_link = "productblist.php?".$brand_link;
				//} else {
					$blistbrand_link = "productlist.php?";
				//}
				while($row=mysql_fetch_object($result)) {
					if($icount!=0) {
						echo "<tr><td height=1></td></tr>\n";
					}
					echo "<tr>\n";
					echo "	<td class=\"categoryListTd\">";
					echo "	<A HREF=\"".$Dir.FrontDir.$blistbrand_link."code=".$row->code."\" onMouseOver=\"categoryView('categoryAll".$icount."','open')\" onMouseOut=\"categoryView('categoryAll".$icount."','out')\" style=\"display:block;\"><FONT class=leftprname>".$row->code_name."</FONT></A>\n";

					// 20140204 J.Bum
					$sql1 = "SELECT codeA,codeB,codeC,codeD,code_name,type FROM tblproductcode ";
					$sql1.= "WHERE codeA='".$row->code."' AND codeC='000' AND codeD='000' AND group_code!='NO' ";
					$sql1.= "AND (type='LM' || type='TM' || type='LMX' || type='TMX') ";
					$sql1.= "ORDER BY sequence DESC ";
					$result1=mysql_query($sql1, get_db_conn());
					$rows1=mysql_num_rows($result1);

					// 20140411 J.Bum
					$imagepath=$Dir.DataDir."shopimages/product/";

					$sql2 = "SELECT * FROM product_code_banner WHERE code='".$row->code."'";

					$result2 = mysql_query($sql2,get_db_conn());
					$b_row = mysql_fetch_object($result2);
					mysql_free_result($result2);

					$up_banner_file = $b_row->banner_file;
					$up_banner_url = $b_row->banner_url;
					$up_move_type = $b_row->move_type;
					$banner_img = "";
					$up_banner_width = getimagesize($imagepath.$up_banner_file);

					if($up_banner_width[0] > 200){
						$width = '200';
					}else{
						$width = $up_banner_width[0];
					}

					if (!empty($up_banner_file) && file_exists($imagepath.$up_banner_file)) {
						$banner_img = "<a href=\"http://".$up_banner_url."\" style=\"padding:0px;\" target=\"".$up_move_type."\"><img src=\"".$imagepath.$up_banner_file."\" width=\"".$width."\" border=\"0\" /></a>";
					}

					if($rows1 || $banner_img){ //���� ī�װ��� �ְų� ī�װ� ��� �̹����� ��ϵǰ� ������ ����ϼ�~
						echo "
								<div class=\"categoryList\">
									<div id=\"categoryAll".$icount."\" class=\"categoryAll\" onMouseOver=\"categoryView('categoryAll".$icount."','over')\" onMouseOut=\"categoryView('categoryAll".$icount."','out')\" style=\"display:none;\">
										<div style=\"position:absolute; left:-5px; top:10px;\"><IMG SRC=\"".$Dir."images/".$_data->icon_type."/main_skin3_cateicon.png\" border=\"0\" alt=\"\" /></div>
										<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">";

								while($row1=mysql_fetch_object($result1)) {
									echo "<tr><td><a href=\"".$Dir.FrontDir.$blistbrand_link."code=".$row->code.$row1->codeB."\" style=\"display:block;\">".$row1->code_name."</a></td></tr>";
								}
								mysql_free_result($result1);
						echo "		</table>";

						// ��� �̹��� ��ϵǰ� ������ ����ϼ�~
						if($banner_img){
							echo "<div style=\"margin-top:10px;\">".$banner_img."</div>";
						}
						echo "	</div>";
						echo "</div>";
					}

					echo "	</td>";
					echo "</tr>\n";
					$icount++;
				}
				mysql_free_result($result);
?>
				</table>
<? //} ?>
				</td>
			</tr>
			</table>
			</td>
		</tr>
<? if($_data->ETCTYPE["BRANDLEFT"]=="Y" && (($_data->ETCTYPE["BRANDLEFTL"]=="Y" && ($brandpagemark == "Y" || $mainpagemark == "Y")) || ($_data->ETCTYPE["BRANDLEFTL"]=="B" && $brandpagemark == "Y") || $_data->ETCTYPE["BRANDLEFTL"]=="A")) { ?>
		<tr><td height="25"></td></tr>
		<tr>
			<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><?=($_data->ETCTYPE["BRANDMAP"]=="Y"?"<a href=\"".$Dir.FrontDir."productbmap.php\">":"")?><IMG SRC="<?=$Dir?>images/<?=$_data->icon_type?>/main_skin3_brand_title.gif" border="0"></a></td>
			</tr>
			<tr>
				<td style="padding-left:15px;padding-right:15px;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td style="border:1px solid #E7E7E7;">
						<div style="height:<?=$_data->ETCTYPE["BRANDLEFTY"]?>px; overflow-x:hidden; overflow-y:scroll; padding:7px; margin:0; scrollbar-face-color:#FFFFFF; scrollbar-arrow-color:#DDDDDD; scrollbar-track-color:#FFFFFF; scrollbar-highlight-color:#DDDDDD; scrollbar-3dlight-color:#FFFFFF; scrollbar-shadow-color:#DDDDDD; scrollbar-darkshadow-color:#FFFFFF;">
							<ul style="margin:0; padding:0; list-style:none;">
							<?
								$sql = "SELECT bridx, brandname FROM tblproductbrand ";
								$sql.= "ORDER BY brandname ";
								get_db_cache($sql, $resval, "tblproductbrand.cache");
								while(list($key, $row)=each($resval)) {
									echo "<li style=\"width:100%;margin:0;padding:0;list-style:none;\"><A HREF=\"".$Dir.FrontDir."productblist.php?brandcode=".$row->bridx."\">".$row->brandname."</a></li>\n";
								}
							?>
							</ul>
						</div>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>
		</tr>
<? } ?>
<?
/*
		if($_data->estimate_ok=="Y" || $_data->estimate_ok=="O") {
				echo "<tr><td height=\"25\"></td></tr>";
				echo "<tr>\n";
				echo "	<td><A HREF=\"javascript:estimate('".$_data->estimate_ok."')\"><IMG SRC=\"".$Dir."images/".$_data->icon_type."/main_skin3_estimate.gif\" border=0></A></td>\n";
				echo "</tr>\n";
			}
*/
?>
		<tr>
			<td height="25"></td>
		</tr>
		<tr>
			<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><IMG SRC="<?=$Dir?>images/<?=$_data->icon_type?>/main_skin3_com_title.gif" border="0"></td>
			</tr>
			<tr>
				<td style="padding-left:15px;padding-right:15px;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
<?
				$sql = "SELECT board,board_name,use_hidden FROM tblboardadmin ORDER BY date DESC ";
				$result=mysql_query($sql,get_db_conn());
				while($row=mysql_fetch_object($result)) {
					if($row->use_hidden!="Y") {
						echo "<tr>\n";
						echo "	<td><IMG SRC=\"".$Dir."images/".$_data->icon_type."/main_skin3_communitynero.gif\" BORDER=0 align=absmiddle></td>\n";
						echo "	<td width=100% style=\"padding-left:5px;\"><A HREF=\"".$Dir.BoardDir."board.php?board=".$row->board."\"><FONT class=leftcommunity>".$row->board_name."</FONT></A></td>\n";
						echo "</tr>\n";
					}
				}
				mysql_free_result($result);
				$_data->ETCTYPE["REVIEW"]=(isset($_data->ETCTYPE["REVIEW"])?$_data->ETCTYPE["REVIEW"]:"");
				if ($_data->ETCTYPE["REVIEW"]=="Y") {
					echo "<tr>\n";
					echo "	<td><IMG SRC=\"".$Dir."images/".$_data->icon_type."/main_skin3_communitynero.gif\" BORDER=0 align=absmiddle></td>\n";
					echo "	<td width=100% style=\"padding-left:5px;\"><A HREF=\"".$Dir.FrontDir."reviewall.php\"><FONT class=leftcommunity>����ı� ����</FONT></A></td>\n";
					echo "</tr>\n";
				}
?>
				</table>
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td height="25"></td>
		</tr>
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td><IMG SRC="<?=$Dir?>images/<?=$_data->icon_type?>/main_skin3_customertitle.gif" border="0"></td>
			</tr>
			<tr>
				<td style="padding-left:15px;">
				<table cellpadding="0" cellspacing="0" width="100%">
<?
				if(strlen($_data->info_tel)>0) {
					$tmp_tel=explode(",",$_data->info_tel);
					for($i=0;$i<count($tmp_tel);$i++) {
						echo "<tr>\n";
						echo "	<td align=center><IMG SRC=\"".$Dir."images/".$_data->icon_type."/main_skin3_custo_tel.gif\" border=0></td>\n";
						echo "	<td width=100% class=leftcustomer>&nbsp;".trim($tmp_tel[$i])."</td>\n";
						echo "</tr>\n";
						if($i==2) break;
					}
				}
?>
				<tr>
					<td align="center"><IMG SRC="<?=$Dir?>images/<?=$_data->icon_type?>/main_skin3_custo_email.gif" border="0"></td>
					<td width="100%">&nbsp;<A HREF="javascript:sendmail();"><FONT class="leftcustomer">E-mail�����ϱ�</FONT></a></td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td></td>
			</tr>
			</table>
<?
			######���� �� �˸�����
			include($Dir."lib/leftevent.php");
			if(strlen($eventbody)>0) {
				echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
				echo "<tr><td width=100% height=5></td></tr>";
				echo "<tr><td>".$eventbody."</td></tr>";
				echo "</table>";
			}

			###### ���
			if($_data->banner_loc=="L") {
				include($Dir."lib/banner.php");
				if(strlen($bannerbody)>0) {
					echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>";
					echo "<tr><td width=100% height=5></td></tr>";
					echo "<tr><td>".$bannerbody."</td></tr>";
					echo "</table>";
				}
			}
?>
			<table cellpadding="0" cellspacing="0" width="100%">
			<tr><td><?=$_data->countpath?></td></tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
<?}?>
		<td align="center" valign="top" nowrap>