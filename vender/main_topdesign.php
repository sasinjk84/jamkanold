<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$mode=$_POST["mode"];

if($mode=="update") {
	$toptype=$_POST["toptype"];
	$topdesign=$_POST["topdesign"];
	$upfile=$_FILES["upfileimage"];

	$imagename=$Dir.DataDir."shopimages/vender/MAIN_".$_VenderInfo->getVidx().".gif";

	$iserror=false;
	if(strlen($toptype)==0) {
		$topdesign="";
		@unlink($imagename);
	} else if($toptype=="image") {
		$topdesign="";
		if($upfile[size] < 102400) {
			if (strlen($upfile[name])>0 && file_exists($upfile[tmp_name])) {
				$ext = strtolower(substr($upfile[name],strlen($upfile[name])-3,3));
				if($ext=="gif" || $ext=="jpg"){
					$imagenameorg="MAIN_".$_VenderInfo->getVidx().".gif";
					move_uploaded_file($upfile[tmp_name],$Dir.DataDir."shopimages/vender/".$imagenameorg);
					chmod($Dir.DataDir."shopimages/vender/".$imagenameorg,0664);
				} else {
					echo "<html></head><body onload=\"alert('�̹��� ����� gif, jpg ���ϸ� ��� �����մϴ�.\\n\\nȮ�� �� �ٽ� ����Ͻñ� �ٶ��ϴ�.')\"></body></html>";exit;
				}
			} else {
				echo "<html></head><body onload=\"alert('�̹��� ������ �ȵǾ��ų� �߸��� �̹��� �����Դϴ�.\\n\\n���� Ȯ�� �� �ٽ� ����Ͻñ� �ٶ��ϴ�.')\"></body></html>";exit;
			}
		} else {
			echo "<html></head><body onload=\"alert('�̹��� ����� �ִ� 100KB ���� ����� �����մϴ�.\\n\\n�̹��� �뷮�� �ٿ��� �ٽ� ����Ͻñ� �ٶ��ϴ�.')\"></body></html>";exit;
		}
	} else if($toptype=="html") {
		if(strlen($topdesign)==0) {
			echo "<html></head><body onload=\"alert('���� ������ �Է��ϼ���.')\"></body></html>";exit;
		}
		@unlink($imagename);
	}
	$sql = "UPDATE tblvenderstore SET ";
	$sql.= "main_toptype	= '".$toptype."', ";
	$sql.= "main_topdesign	= '".$topdesign."' ";
	$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
	if(mysql_query($sql,get_db_conn())) {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� �����Ͽ����ϴ�.');parent.location.reload()\"></body></html>";exit;
	} else {
		echo "<html></head><body onload=\"alert('��û�Ͻ� �۾��� ������ �߻��Ͽ����ϴ�.')\"></body></html>";exit;
	}
}


$main_toptype=$_venderdata->main_toptype;
$main_topdesign=$_venderdata->main_topdesign;
?>

<? INCLUDE "header.php"; ?>

<script type="text/javascript" src="lib.js.php"></script>
<script language=javascript src="PrdtDispInfoFucn.js.php" type="text/javascript"></script>
<script language="JavaScript">
var shop="layer1";
var ArrLayer = new Array ("layer1","layer2","layer3");
function ViewLayer(gbn){
	try{
		if(document.all){
			for(i=0;i<ArrLayer.length;i++) {
				if (ArrLayer[i] == gbn)
					document.all[ArrLayer[i]].style.display="";
				else
					document.all[ArrLayer[i]].style.display="none";
			}
		} else if(document.getElementById){
			for(i=0;i<ArrLayer.length;i++) {
				if (ArrLayer[i] == gbn)
//					document.getElementByld[ArrLayer[i]].style.display="";
					document.getElementById(ArrLayer[i]).style.display="";
				else
	//				document.getElementByld[ArrLayer[i]].style.display="none";
					document.getElementById(ArrLayer[i]).style.display="none";
			}
		} else if(document.layers){
			for(i=0;i<ArrLayer.length;i++) {
				if (ArrLayer[i] == gbn)
					document.layers[ArrLayer[i]].display="";
				else
					document.layers[ArrLayer[i]].display="none";
			}
		}
		if(gbn=="layer1") {
			document.all["top_templt_img"].src="images/sample/display/top_design0.gif";
		} else {
			document.all["top_templt_img"].src="images/sample/display/top_design1.gif";
		}
	}catch(e){
		alert(e.message);	
	}
	shop=gbn;
}
</script>
<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed" height="100%">
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
					<td><img src="images/main_topdesign_title.gif"></td>
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
											<td class="notice_gray" height="20"><img src="images/icon_dot02.gif" border=0 hspace="4">�̴ϼ� ���� ��ܿ� ���� ��� �˸��� ���� �����̳� �̺�Ʈ�� ���� ��� �������� �߰��� �ּ���.</td>
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

				<form name=iForm action="" method=post enctype="multipart/form-data">
				<input type=hidden name=mode>
				<input type=hidden name=preview_type value="main_topevent">
				<input type=hidden name=image_path value="">

				<tr>
					<td><img src="images/main_topdesign_stitle01.gif" border=0 align=absmiddle></td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=#cccccc></td></tr>
				<tr>
					<td align=center bgcolor=F8F8F8 style=padding:25>
					<img id="top_templt_img" name="top_templt_img" src=images/sample/display/top_design<?=(strlen($main_toptype)==0?"0":"1")?>.gif border=0>
					</td>
				</tr>
				<tr><td height=5></td></tr>
				<tr><td height=1 bgcolor=#CDCDCD></td></tr>
				<tr>
					<td valign=top>
					<table width=100% border=0 cellspacing=0 cellpadding=0>
					<tr>
						<td width=140 bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>����Ÿ�� ����</B></td>
						<td style=padding:7,10>
						<input type=radio id="idx_toptype1" name=toptype value="" style="border:none" <?if(strlen($main_toptype)==0)echo"checked";?> onclick="ViewLayer('layer1')" <?=$disabled?>> <label style='cursor:hand;' for=idx_toptype1>������</label>
						<img width=10 height=0>
						<input type=radio id="idx_toptype2" name=toptype value="image" style="border:none" <?if($main_toptype=="image")echo"checked";?> onclick="ViewLayer('layer2')" <?=$disabled?>> <label style='cursor:hand;'  for=idx_toptype2>�̹���</label>
						<img width=10 height=0>
						<input type=radio id="idx_toptype3" name=toptype value="html" style="border:none" <?if($main_toptype=="html")echo"checked";?> onclick="ViewLayer('layer3')" <?=$disabled?>> <label style='cursor:hand;'  for=idx_toptype3>HTML����</label>
						</td>
					</tr>
					<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
					<tr>
						<td colspan=2>
						<div id=layer1 style="margin-left:0;display:hide; display:<?=(strlen($main_toptype)==0?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">

						</div>
						<div id=layer2 style="margin-left:0;display:hide; display:<?=($main_toptype=="image"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<tr>
							<td bgcolor=F5F5F5 background=images/line01.gif style=background-repeat:repeat-y;background-position:right;padding:9><B>�̹��� ����</B></td>
							<td style=padding:7,10>
							<input type=file name=upfileimage size=38 <?=$disabled?> class=button>
							</td>
						</tr>
						<tr><td height=1 colspan=2 bgcolor=E7E7E7></td></tr>
						<tr>
							<td colspan=2>
<?
							if($main_toptype=="image" && file_exists($Dir.DataDir."shopimages/vender/MAIN_".$_VenderInfo->getVidx().".gif")) {
								echo "<img src=\"".$Dir.DataDir."shopimages/vender/MAIN_".$_VenderInfo->getVidx().".gif\" border=0 width=100% height=100 align=absmiddle>";
							} else {
								echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>\n";
								echo "<tr><td height=100 bgcolor=#fafafa align=center>�̹����� ����ϼ���</td></tr>\n";
								echo "</table>\n";
							}
?>
							</td>
						</tr>
						</table>
						</div>
						<div id=layer3 style="margin-left:0;display:hide; display:<?=($main_toptype=="html"?"block":"none")?> ;border-style:solid; border-width:0; border-color:black;background:#FFFFFF;padding:0;">
						<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed">
						<tr>
							<td colspan=2>
							<textarea name=topdesign rows=8 cols=86 wrap=off style="width:100%" <?=$disabled?>><?=$main_topdesign?></textarea>
							</td>
						</tr>
						</table>
						</div>
						</td>
					</tr>

					</table>
					</td>
				</tr>
				<tr><td height=1 bgcolor=#CDCDCD></td></tr>
				<tr><td height=20></td></tr>
				<tr>
					<td align=center>
					<A HREF="javascript:formEventSubmit('preview')"><img src="images/btn_preview01.gif" border=0></A>
					&nbsp;
					<A HREF="javascript:formEventSubmit('')"><img src="images/btn_save01.gif" border=0></A>
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
</table>

<iframe name="processFrame" src="about:blank" width="0" height="0" scrolling=no frameborder=no></iframe>

<?=$onload?>

<? INCLUDE "copyright.php"; ?>