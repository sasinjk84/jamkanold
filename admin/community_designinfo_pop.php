<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$board=$_POST["board"];

if(strlen($_ShopInfo->getId())==0 || strlen($board)==0){
	echo "<script>alert('�������� ��η� �����Ͻñ� �ٶ��ϴ�.');window.close();</script>";
	exit;
}

$sql = "SELECT board_name,board_skin FROM tblboardadmin WHERE board='".$board."' ";
$result=mysql_query($sql,get_db_conn());
$data=mysql_fetch_object($result);
mysql_free_result($result);

if(!$data) {
	echo "<script>alert(\"�ش� �Խ����� �������� �ʽ��ϴ�.\");window.close();</script>";
	exit;
}

$btype=substr($data->board_skin,0,1);
if($btype=="L") $btypename="�Ϲ��� �Խ���";
if($btype=="W") $btypename="������ �Խ���";
if($btype=="I") $btypename="�ٹ��� �Խ���";
if($btype=="B") $btypename="��α��� �Խ���";

$sql = "SELECT * FROM tblboardskin WHERE board_skin LIKE '".$btype."%' ORDER BY board_skin ASC";
$result=mysql_query($sql,get_db_conn());
$rows=mysql_num_rows($result);
if(!$rows) {
	echo "<script>alert(\"".$btypename." ��Ų ����� �ȵǾ� �Խ��� �߰��� �Ұ��մϴ�.\");window.close();</script>";
	exit;
}

$mode=$_POST["mode"];
$board_skin=$_POST["board_skin"];

if($mode=="modify" && strlen($board_skin)>0) {
	$sql = "UPDATE tblboardadmin SET board_skin='".$board_skin."' WHERE board='".$board."' ";
	mysql_query($sql,get_db_conn());
	echo "<script>alert(\"�Խ��� �������� ����Ǿ����ϴ�.\");window.close();</script>";
	exit;
}
?>

<html>
<head>
<meta http-equiv='Content-Type' content='text/html;charset=euc-kr'>
<title>�Խ��� ������ ����</title>
<link rel="stylesheet" href="style.css" type="text/css">
<style>td {line-height:14pt}</style>
<script type="text/javascript" src="lib.js.php"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--
window.resizeTo(470,330);

document.onkeydown = CheckKeyPress;
document.onkeyup = CheckKeyPress;
function CheckKeyPress() {
	ekey = event.keyCode;
	if(ekey==38 || ekey==40 || ekey==112 || ekey==17 || ekey==18 || ekey==25 || ekey==122 || ekey==116) {
		try {
			event.keyCode = 0;
			return false;
		} catch(e) {}
	}
}

function CheckForm(form) {
	try {
		selskin=false;
		for(i=0;i<form.board_skin.length;i++) {
			if(form.board_skin[i].checked==true) {
				selskin=true;
				break;
			}
		}
		if(selskin==false) {
			alert("�Խ��� �������� �����ϼ���.");
			return;
		}
	} catch (e) {
		return;
	}
	if(confirm("�Խ��� �������� �����Ͻðڽ��ϱ�?")) {
		form.mode.value="modify";
		form.submit();
	}
}

var skin_cnt = 0;

function ChangeDesign(tmp) {
	tmp=tmp + skin_cnt;
	document.form1["board_skin"][tmp].checked=true;
}

//-->
</SCRIPT>
</head>
<body leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 style="overflow-x:hidden;" ondragstart="return false" onselectstart="return false" oncontextmenu="return false">

<table border=0 cellpadding=0 cellspacing=0 width=100% style="table-layout:fixed;" id=table_body>
<TR>
	<TD width="100%">
	<table cellpadding="0" cellspacing="0" width="100%">
	<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
	<input type=hidden name=mode>
	<input type=hidden name=board value="<?=$board?>">
	<tr>
		<td><IMG SRC="images/community_list_function_4.gif"ALT=""></td>
		<td width="100%" background="images/member_mailallsend_imgbg.gif"></td>
	</tr>
	</table>
	</TD>
</TR>
<TR>
	<TD style="padding:3pt;">
	<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<TABLE cellSpacing=0 cellPadding="5" width="100%" border=0>
		<TR>
			<TD align=right valign="middle"><img src="images/btn_back.gif" width="31" height="31" border="0" onMouseover='moveright()' onMouseout='clearTimeout(righttime)' style="cursor:hand;"></TD>
			<TD width="100%">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr height=170>
				<td id=temp style="visibility:hidden;position:absolute;top:0;left:0">
<?
				echo "<script>skin_cnt=".$rows.";</script>\n";
				echo "<script>";
				$jj=0;
				$menucontents = "";
				$menucontents .= "<table border=0 cellpadding=0 cellspacing=0><tr>";
				$i=0;
				while($row=mysql_fetch_object($result)) {
					echo "thisSel = 'dotted #FFFFFF';";
					$menucontents .= "<td width=172 align=center valign=top style='padding:5'><img src='images/sample/board_".$row->board_skin.".gif' border=0 width=150 height=140 style='border:1 dotted #FFFFFF' hspace=10 onMouseOver='changeMouseOver(this);' onMouseOut='changeMouseOut(this,thisSel);' style='cursor:hand;' onclick='ChangeDesign(".$i.");'>";
					$menucontents .= "<br><input type=radio name='board_skin' value='".$row->board_skin."'";
					if($data->board_skin==$row->board_skin) $menucontents .= " checked";
					$menucontents .= "></td>";
					$jj++;
					$i++;
				}
				mysql_free_result($result);
				$menucontents .= "</tr></table>";
				echo "</script>";
?>  

				<script language="JavaScript1.2">
				<!--
				function changeMouseOver(img) {
					 img.style.border='1 dotted #999999';
				}
				function changeMouseOut(img,dot) {
					 img.style.border="1 "+dot;
				}

				var menuwidth=365
				var menuheight=170
				var scrollspeed=10
				var menucontents="<nobr><?=$menucontents?></nobr>";
				
				var iedom=document.all||document.getElementById
				if (iedom)
					document.write(menucontents)
				var actualwidth=''
				var cross_scroll, ns_scroll
				var loadedyes=0
				function fillup(){
					if (iedom){
						cross_scroll=document.getElementById? document.getElementById("test2") : document.all.test2
						cross_scroll.innerHTML=menucontents
						actualwidth=document.all? cross_scroll.offsetWidth : document.getElementById("temp").offsetWidth
					}
					else if (document.layers){
						ns_scroll=document.ns_scrollmenu.document.ns_scrollmenu2
						ns_scroll.document.write(menucontents)
						ns_scroll.document.close()
						actualwidth=ns_scroll.document.width
					}
					loadedyes=1
				}
				window.onload=fillup
				
				function moveleft(){
					if (loadedyes){
						if (iedom&&parseInt(cross_scroll.style.left)>(menuwidth-actualwidth)){
							cross_scroll.style.left=parseInt(cross_scroll.style.left)-scrollspeed
						}
						else if (document.layers&&ns_scroll.left>(menuwidth-actualwidth))
							ns_scroll.left-=scrollspeed
					}
					lefttime=setTimeout("moveleft()",50)
				}
				
				function moveright(){
					if (loadedyes){
						if (iedom&&parseInt(cross_scroll.style.left)<0)
							cross_scroll.style.left=parseInt(cross_scroll.style.left)+scrollspeed
						else if (document.layers&&ns_scroll.left<0)
							ns_scroll.left+=scrollspeed
					}
					righttime=setTimeout("moveright()",50)
				}
				
				if (iedom||document.layers){
					with (document){
						write('<td valign=top>')
						if (iedom){
							write('<div style="position:relative;width:'+menuwidth+';">');
							write('<div style="position:absolute;width:'+menuwidth+';height:'+menuheight+';overflow:hidden;">');
							write('<div id="test2" style="position:absolute;left:0">');
							write('</div></div></div>');
						}
						else if (document.layers){
							write('<ilayer width='+menuwidth+' height='+menuheight+' name="ns_scrollmenu">')
							write('<layer name="ns_scrollmenu2" left=0 top=0></layer></ilayer>')
						}
						write('</td>')
					}
				}
				//-->
				</script>
				</td>
			</tr>
			</table>
			</TD>
			<TD><img src="images/btn_next.gif" width="31" height="31" border="0" onMouseover='moveleft()' onMouseout='clearTimeout(lefttime)' style="cursor:hand;"></TD>
		</TR>
		</TABLE>
		</td>
	</tr>
	<tr>
		<td><img src="images/member_idsearch_line.gif" width="100%" height="1" border="0"></td>
	</tr>
	<tr>
		<td align="center"><input type="image" src="images/bnt_apply.gif" width="76" height="28" border="0" vspace="5" border=0 onclick="CheckForm(this.form)"><a href="javascript:window.close()"><img src="images/btn_cancel.gif" width="76" height="28" border="0" vspace="5" border=0 hspace="2"></a></td>
	</tr>
	</form>
	</table>
	</TD>
</TR>
</TABLE>
</body>
</html>

<?=$onload?>
