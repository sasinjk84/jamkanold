<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if (strpos(getenv("HTTP_REFERER"),FrontDir."primage")==false) exit;
$imagepath=$Dir.DataDir."shopimages/multi/";

$productcode=$_REQUEST["productcode"];
$maxsize=$_REQUEST["maxsize"];
?>

<html>
	<head>
		<title>��ǰȮ�뺸��</title>
		<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">
		<META http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta http-equiv="imagetoolbar" content="no">
		<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>

		<style>
			td {font-family:����;color:666666;font-size:9pt;}
			tr {font-family:����;color:666666;font-size:9pt;}
			BODY,TD,SELECT,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

			.thumeimage{display:table-cell;vertical-align:middle;height:50px;font-size:0px;border:1px solid #eeeeee;}
			.thumeimage:hover{border:1px solid #ec2f36;}
		</style>

		<SCRIPT LANGUAGE="JavaScript">
			<!--
			var imagepath="<?=$imagepath?>";
			var setcnt=0;
			function primg_preview(img,width,height) {
				if(parent.main.primg!=null) {
					setcnt=0;
					parent.main.primg.src=imagepath+img;
					parent.main.primg.width=width;
					parent.main.primg.height=height;
				} else {
					if(setcnt<=<?=MultiImgCnt?>) {
						setcnt++;
						setTimeout("primg_preview('"+img+"','"+width+"','"+height+"')",500);
					}
				}
			}

			function primg_preview3(img,width,height) {
				if(parent.main.primg!=null) {
					parent.main.primg.src=imagepath+img;
					parent.main.primg.width=width;
					parent.main.primg.height=height;
				}
			}

			function primg_preview2(img,width,height) {
				obj = event.srcElement;
				clearTimeout(obj._tid);
				obj._tid=setTimeout("primg_preview3('"+img+"','"+width+"','"+height+"')",500);
			}
			//-->
		</SCRIPT>
	</head>

	<body topmargin=0 leftmargin=0 rightmargin=0 marginheight=0 marginwidth=0 onselectstart="return false" ondragstart="return false" oncontextmenu="return false">
		<?
			$sql = "SELECT multi_dispos, multi_changetype, multi_bgcolor FROM tblshopinfo";
			$result=mysql_query($sql,get_db_conn());
			$row=mysql_fetch_object($result);
			mysql_free_result($result);

			$dispos=$row->multi_dispos;
			$changetype=$row->multi_changetype;
			$bgcolor=$row->multi_bgcolor;

			$sql = "SELECT * FROM tblmultiimages WHERE productcode='".$productcode."' ";
			$result=mysql_query($sql,get_db_conn());
			if($row=mysql_fetch_object($result)) {
				//$multi_imgs=array(&$row->primg01,&$row->primg02,&$row->primg03,&$row->primg04,&$row->primg05,&$row->primg06,&$row->primg07,&$row->primg08,&$row->primg09,&$row->primg10);
				$multi_imgs = array ();
				for( $i=1;$i<=MultiImgCnt;$i++ ){
					$k = str_pad($i,2,'0',STR_PAD_LEFT);
					array_push( $multi_imgs, &$row->{"primg".$k} );
				}

				$tmpsize=explode("",$row->size);
				$insize="";
				$updategbn="N";

				$y=0;
				for($i=0;$i<MultiImgCnt;$i++) {
					if(strlen($multi_imgs[$i])>0) {
						$yesimage[$y]=$multi_imgs[$i];
						if(strlen($tmpsize[$i])==0) {
							$size=getimagesize($Dir.DataDir."shopimages/multi/".$multi_imgs[$i]);
							$xsize[$y]=$size[0];
							$ysize[$y]=$size[1];
							$insize.="".$size[0]."X".$size[1];
							$updategbn="Y";
						} else {
							$insize.="".$tmpsize[$i];
							$tmp=explode("X",$tmpsize[$i]);
							$xsize[$y]=$tmp[0];
							$ysize[$y]=$tmp[1];
						}
						$y++;
					} else {
						$insize.="";
					}
				}

				//$maxnumsize=($maxsize/60);
				//if($y>=5 && $maxnumsize<=5) $y=5;
				//else if($maxnumsize<$y) $y=$maxnumsize;

				$makesize=$maxsize;
				for($i=0;$i<$y;$i++){
					if($xsize[$i]>$makesize || $ysize[$i]>$makesize) {
						if($xsize[$i]>=$ysize[$i]) {
							$tempxsize=$makesize;
							$tempysize=($ysize[$i]*$makesize)/$xsize[$i];
						} else {
							$tempxsize=($xsize[$i]*$makesize)/$ysize[$i];
							$tempysize=$makesize;
						}
						$xsize[$i]=$tempxsize;
						$ysize[$i]=$tempysize;
					}
				}
				if($updategbn=="Y"){
					$sql = "UPDATE tblmultiimages SET size='".substr($insize,1)."' ";
					$sql.= "WHERE productcode='".$productcode."'";
					mysql_query($sql,get_db_conn());
				}
				mysql_free_result($result);
			}
		?>

		<table align="center" border="0" cellpadding="0" cellspacing="0" width="<?=$maxsize?>" style="margin-top:10px;">
			<tr>
				<td align="center">
					<table border="0" cellpadding="0" cellspacing="0" bgcolor="#DADADA">
					<?
						for($i=0;$i<$y;$i++) {
							if($i==0) echo "<tr height=50 bgcolor=#FFFFFF>\n";
							if($i>0 && $i%15==0) echo "</tr><tr height=50 bgcolor=#FFFFFF>\n";
							echo "<td width=\"50\" align=\"center\" style=\"padding-right:4px;padding-bottom:4px;\"><div class=\"thumeimage\">";
							if($changetype=="0") {	//���콺 ����
								echo "<a href=\"javascript:primg_preview('".$yesimage[$i]."','".$xsize[$i]."','".$ysize[$i]."')\" onmouseover=\"primg_preview2('".$yesimage[$i]."','".$xsize[$i]."','".$ysize[$i]."')\">";
							} else {	//���콺 Ŭ��
								echo "<a href=\"javascript:primg_preview('".$yesimage[$i]."','".$xsize[$i]."','".$ysize[$i]."')\">";
							}
							echo "<img src=".$imagepath."s".$yesimage[$i]." border=0";
							if($xsize[$i]>$ysize[$i]) echo " width=50";
							else echo " height=50";
							echo "></a></div></td>";
						}
						if($i%15!=0) {
							if($i>15) {
								for($j=($i%15);$j<15;$j++) {
									echo "<td width=50 align=center bgcolor=#ffffff></td>";
								}
							}
							echo "</tr>\n";
						}
					?>
					</table>
				</td>
			</tr>
		</table>

		<script>
			primg_preview('<?=$yesimage[0]?>','<?=$xsize[0]?>','<?=$ysize[0]?>');
			parent.main.document.bgcolor='<?=$bgcolor?>';
		</script>
	</body>
</html>