<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");

$select_code=$_POST["select_code"];
$codeA=$_POST["codeA"];
$codeB=$_POST["codeB"];
$codeC=$_POST["codeC"];
$codeD=$_POST["codeD"];
$s_check=$_POST["s_check"];
$search=$_POST["search"];
$sort=$_POST["sort"];
if($sort!="order by productname asc" && $sort!="order by productname desc" && $sort!="order by productcode asc" && $sort!="order by productcode desc") {
	$sort="";
}

$setup[page_num] = 10;
$setup[list_num] = 13;

$block=$_REQUEST["block"];
$gotopage=$_REQUEST["gotopage"];
if ($block != "") {
	$nowblock = $block;
	$curpage  = $block * $setup[page_num] + $gotopage;
} else {
	$nowblock = 0;
}

if (($gotopage == "") || ($gotopage == 0)) {
	$gotopage = 1;
}

$t_count=0;
if($mode=="ALL" || strlen($select_code)>=3) {
	$qry = "WHERE vender='".$_VenderInfo->getVidx()."' ";
	if($mode!="ALL") {
		$qry.= "AND productcode LIKE '".$select_code."%' ";
		if(strlen($search)>0) {
			if($s_check=="name") {
				$qry.= "AND productname LIKE '%".$search."%' ";
			} else if($s_check=="code") {
				$qry.= "AND productcode='".$search."' ";
			} else if($s_check=="selfcode") {
				$qry.= "AND selfcode='".$search."' ";
			}
		}
	}
	$qry.= "AND display='Y' ";

	$sql = "SELECT COUNT(*) as t_count FROM tblproduct ".$qry." ";
	$result = mysql_query($sql,get_db_conn());
	$row = mysql_fetch_object($result);
	$t_count = $row->t_count;
	mysql_free_result($result);
	$pagecount = (($t_count - 1) / $setup[list_num]) + 1;
}
?>

<html>
<head>
<title>������ ������</title>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
<META http-equiv="X-UA-Compatible" content="IE=5" />
<link rel="stylesheet" href="style.css">
<script language=Javascript>
function GoPage(block,gotopage) {
	document.pageForm.block.value=block;
	document.pageForm.gotopage.value=gotopage;
	document.pageForm.submit();
}

function OrderSort(sort) {
	document.pageForm.block.value="";
	document.pageForm.gotopage.value="";
	document.pageForm.sort.value=sort;
	document.pageForm.submit();
}

function ACodeSendIt(code) {
	murl = "product_code.mgr.ctgr.php?code="+code;
	surl = "product_code.mgr.ctgr.php";
	durl = "product_code.mgr.ctgr.php";
	BCodeCtgr.location.href = murl;
	CCodeCtgr.location.href = surl;
	DCodeCtgr.location.href = durl;
}

function f_getTotalData(){
	prdList.code.value  = '';
	prdList.codeA.value = '';
	prdList.codeB.value = '';
	prdList.codeC.value = '';
	prdList.codeD.value = '';
	prdList.s_check.value = 'name';
	prdList.search.value = '';
	prdList.mode.value = 'ALL';

	prdList.submit();
}


function f_getData(){

  var DCtgr = DCodeCtgr.iForm.code.options[DCodeCtgr.iForm.code.selectedIndex].value;
  var CCtgr = CCodeCtgr.iForm.code.options[CCodeCtgr.iForm.code.selectedIndex].value;
  var BCtgr = BCodeCtgr.iForm.code.options[BCodeCtgr.iForm.code.selectedIndex].value;
  var ACtgr = prdList.code.options[prdList.code.selectedIndex].value;

  if(prdList.s_check.value == "name" || prdList.s_check.value == "code" || prdList.s_check.value == "selfcode"){
    if(prdList.s_check.options[prdList.s_check.selectedIndex].value =='name' || prdList.s_check.options[prdList.s_check.selectedIndex].value =='code' || prdList.s_check.options[prdList.s_check.selectedIndex].value =='selfcode'){
	  if(DCtgr == ''){
	    if(CCtgr == ''){
	      if(BCtgr == ''){
	        if(ACtgr == ''){
	          alert('�з��� �����ϼ���.');
	          return false;
	        }else{
	          prdList.select_code.value = ACtgr;
	        }
	      }else{
	        prdList.select_code.value = BCtgr;
	      }
	    }else{
	      prdList.select_code.value = CCtgr;
	    }
	  }else{
	    prdList.select_code.value = DCtgr;
	  }
    }
  }
  prdList.code.value = '';
  prdList.mode.value = '';
  prdList.codeA.value = ACtgr;
  prdList.codeB.value = BCtgr;
  prdList.codeC.value = CCtgr;
  prdList.codeD.value = DCtgr;

  prdList.submit();
}

function f_setPrdInfo(arg){
  var arr = new Array();
  arr[0] = eval("dispList.hidden_prdcode" + arg+ ".value");
  arr[1] = eval("dispList.hidden_prdname" + arg+ ".value");
  arr[2] = eval("dispList.hidden_prdprice" + arg+ ".value");

  eval("btnImg" + arg+ ".style.display='none' ");
  eval("btnTxt" + arg+ ".style.display='' ");

  opener.setCbmPrdInfo(arr);
}
</script>

</head>
<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>
<center>
<table width=600 height=500 border=0 cellspacing=0 cellpadding=0>
<tr>
	<td><img src=images/pop_title03.gif border=0></td>
</tr>
<tr>
	<td valign=top style=padding:10>
	<table width=100% border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td height=4></td>
	</tr>
	<tr>
		<td valign=top bgcolor=D4D4D4 style=padding:1>
		<table width=100% border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td valign=top bgcolor=F0F0F0 style=padding:7,12;padding-bottom:5>
			<table width=100% border=0 cellspacing=0 cellpadding=0>
			<form name="prdList" method="post">
			<input type="hidden" name="select_code" value="">
			<input type="hidden" name="codeA" value="">
			<input type="hidden" name="codeB" value="">
			<input type="hidden" name="codeC" value="">
			<input type="hidden" name="codeD" value="">
			<input type="hidden" name="mode">
			<tr>
				<td colspan=2 style=padding-left:10>
				<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td>�з� &nbsp;</td>
					<td style=padding-left:12>
					<table border=0 cellspacing=0 cellpadding=0>
					<tr>
						<td>
						<select name="code" style=width:100 onchange="ACodeSendIt(this.options[this.selectedIndex].value)">
						<option value="">--�����ϼ���--</option>
<?
						$sql = "SELECT SUBSTRING(productcode,1,3) as prcode FROM tblproduct ";
						$sql.= "WHERE vender='".$_VenderInfo->getVidx()."' ";
						$sql.= "AND display='Y' GROUP BY prcode ";
						$result=mysql_query($sql,get_db_conn());
						$codes="";
						while($row=mysql_fetch_object($result)) {
							$codes.=$row->prcode.",";
						}
						mysql_free_result($result);
						if(strlen($codes)>0) {
							$codes=substr($codes,0,-1);
							$prcodelist=ereg_replace(',','\',\'',$codes);
						}
						if(strlen($prcodelist)>0) {
							$sql = "SELECT codeA,codeB,codeC,codeD,code_name FROM tblproductcode ";
							$sql.= "WHERE codeA IN ('".$prcodelist."') AND codeB='000' AND codeC='000' ";
							$sql.= "AND codeD='000' AND type LIKE 'L%' ORDER BY sequence DESC ";
							$result=mysql_query($sql,get_db_conn());
							while($row=mysql_fetch_object($result)) {
								echo "<option value=\"".$row->codeA."\"";
								if($row->codeA==substr($select_code,0,3)) echo " selected";
								echo ">".$row->code_name."</option>\n";
							}
							mysql_free_result($result);
						}
?>
						</select>
						</td>
						<td><iframe name="BCodeCtgr" src="product_code.mgr.ctgr.php?code=<?=substr($select_code,0,3)?>&select_code=<?=$select_code?>" width="100" height="23" scrolling=no frameborder=no></iframe></td>
						<td><iframe name="CCodeCtgr" src="product_code.mgr.ctgr.php?code=<?=substr($select_code,0,6)?>&select_code=<?=$select_code?>" width="100" height="23" scrolling=no frameborder=no></iframe></td>
						<td><iframe name="DCodeCtgr" src="product_code.mgr.ctgr.php?code=<?=substr($select_code,0,9)?>&select_code=<?=$select_code?>" width="100" height="23" scrolling=no frameborder=no></iframe></td>
					</tr>
					</table>
					</td>
				</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td height=4 colspan=2></td>
			</tr>
			<tr>
				<td style=padding-left:10>
				<table border=0 cellspacing=0 cellpadding=0>
				<tr>
					<td>
					<select name="s_check" style=width:70 >
					<option value="name" <?if($s_check=="name")echo"selected";?>>��ǰ��</option>
					<option value="code" <?if($s_check=="code")echo"selected";?>>��ǰ�ڵ�</option>
					<option value="selfcode" <?if($s_check=="selfcode")echo"selected";?>>�����ڵ�</option>
					</select><input type=text name="search" value="<?=$search?>" onkeydown="if (event.keyCode == 13) return f_getData();"> <img src=images/btn_search05.gif border=0 align=absmiddle style="cursor:hand" onClick="f_getData()" >

					</td>
				</tr>
				</table>
				</td>
				<td align=right><a href="Javascript:f_getTotalData()"><img src=images/btn_all02.gif border=0></a></td>
			</tr>
			</form>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	<table width=100% border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td height=30></td>
	</tr>
	<tr>
		<td valign=top>
		<table width=100% border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td style=padding-left:5><img src=images/sub_title04.gif border=0></td>
			<td align=right valign=bottom>�� <b><?=$t_count?></b>���� ��ǰ�� �˻��Ǿ����ϴ�.</td>
		</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height=5></td>
	</tr>
	<tr>
		<td height=1 bgcolor=E6567B></td>
	</tr>
	<tr>
		<td valign=top bgcolor=E7E7E7 background=images/line02.gif style=background-repeat:repeat-x>
		<table width=100% border=0 cellspacing=1 cellpadding=0 style="table-layout:fixed">
		<form name="dispList">
		<tr height=28 align=center bgcolor=F5F5F5>
			<td width=10%><B>����</B></td>
			<td width=6%><B>��ȣ</B></td>
			<td width=20%><a href="javascript:OrderSort('<?=($sort=="order by productcode asc"?"order by productcode desc":"order by productcode asc")?>')"; onMouseover="self.status=''; return true; "><B>��ǰ�ڵ�</B></a></td>
			<td width=49%><a href="javascript:OrderSort('<?=($sort=="order by productname asc" || strlen($sort)==0?"order by productname desc":"order by productname asc")?>')"; onMouseover="self.status=''; return true; "><B>��ǰ��</B></a></td>
			<td width=15%><B>����</B></td>
		</tr>
<?
		if($t_count>0) {
			$sql = "SELECT productcode,productname,sellprice,selfcode FROM tblproduct ".$qry." ".$sort." ";
			$sql.= "LIMIT " . ($setup[list_num] * ($gotopage - 1)) . ", " . $setup[list_num];
			$result=mysql_query($sql,get_db_conn());
			$i=0;
			while($row=mysql_fetch_object($result)) {
				$number = ($t_count-($setup[list_num] * ($gotopage-1))-$i);
				echo "<tr height=28 align=center bgcolor=FFFFFF>\n";
				echo "	<td id=\"btnImg".$i."\"><a href=\"javascript:f_setPrdInfo('".$i."')\"><img src=images/btn_select04.gif border=0></a></td>\n";
				echo "	<td id=\"btnTxt".$i."\" style=\"display:none;\">����</td>\n";
				echo "	<td>".$number."</td>\n";
				echo "	<td>".$row->productcode."</td>\n";
				echo "	<td>".$row->productname.($row->selfcode?"-".$row->selfcode:"")."</td>\n";
				echo "	<td>".number_format($row->sellprice)." ��</td>\n";
				echo "	<input type=hidden name=\"hidden_prdcode".$i."\" value=\"".$row->productcode."\">\n";
				echo "	<input type=hidden name=\"hidden_prdname".$i."\" value=\"".strip_tags($row->productname)."\">\n";
				echo "	<input type=hidden name=\"hidden_prdprice".$i."\" value=\"".$row->sellprice."\">\n";
				echo "</tr>\n";
				$i++;
			}
			mysql_free_result($result);

			if($i>0) {
				$total_block = intval($pagecount / $setup[page_num]);
				if (($pagecount % $setup[page_num]) > 0) {
					$total_block = $total_block + 1;
				}
				$total_block = $total_block - 1;
				if (ceil($t_count/$setup[list_num]) > 0) {
					// ����	x�� ����ϴ� �κ�-����
					$a_first_block = "";
					if ($nowblock > 0) {
						$a_first_block .= "<a href='javascript:GoPage(0,1);' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='ù ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev_end.gif border=0 align=absmiddle></a> ";
						$prev_page_exists = true;
					}
					$a_prev_page = "";
					if ($nowblock > 0) {
						$a_prev_page .= "<a href='javascript:GoPage(".($nowblock-1).",".($setup[page_num]*($block-1)+$setup[page_num]).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_miniprev.gif border=0 align=absmiddle></a> ";

						$a_prev_page = $a_first_block.$a_prev_page;
					}
					if (intval($total_block) <> intval($nowblock)) {
						$print_page = "";
						for ($gopage = 1; $gopage <= $setup[page_num]; $gopage++) {
							if ((intval($nowblock*$setup[page_num]) + $gopage) == intval($gotopage)) {
								$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></font> ";
							} else {
								$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
							}
						}
					} else {
						if (($pagecount % $setup[page_num]) == 0) {
							$lastpage = $setup[page_num];
						} else {
							$lastpage = $pagecount % $setup[page_num];
						}
						for ($gopage = 1; $gopage <= $lastpage; $gopage++) {
							if (intval($nowblock*$setup[page_num]) + $gopage == intval($gotopage)) {
								$print_page .= "<FONT color=red><B>".(intval($nowblock*$setup[page_num]) + $gopage)."</B></FONT> ";
							} else {
								$print_page .= "<a href='javascript:GoPage(".$nowblock.",".(intval($nowblock*$setup[page_num]) + $gopage).");' onMouseOver=\"window.status='������ : ".(intval($nowblock*$setup[page_num]) + $gopage)."';return true\">[".(intval($nowblock*$setup[page_num]) + $gopage)."]</a> ";
							}
						}
					}
					$a_last_block = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$last_block = ceil($t_count/($setup[list_num]*$setup[page_num])) - 1;
						$last_gotopage = ceil($t_count/$setup[list_num]);
						$a_last_block .= " <a href='javascript:GoPage(".$last_block.",".$last_gotopage.");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='������ ������';return true\"><img src=".$Dir."images/minishop/btn_mininext_end.gif border=0 align=absmiddle></a>";
						$next_page_exists = true;
					}
					$a_next_page = "";
					if ((intval($total_block) > 0) && (intval($nowblock) < intval($total_block))) {
						$a_next_page .= " <a href='javascript:GoPage(".($nowblock+1).",".($setup[page_num]*($nowblock+1)+1).");' onMouseOut=\"window.status='';return true\" onMouseOver=\"window.status='���� ".$setup[page_num]." ������';return true\"><img src=".$Dir."images/minishop/btn_mininext.gif border=0 align=absmiddle></a>";
						$a_next_page = $a_next_page.$a_last_block;
					}
				} else {
					$print_page = "<B>1</B>";
				}
				$pageing=$a_div_prev_page.$a_prev_page.$print_page.$a_next_page.$a_div_next_page;
			}
		}
?>
		</form>
		</table>
		</td>
	</tr>
	<tr>
		<td height=12></td>
	</tr>
	<tr>
		<td align=center>
		<form name="pageForm" method="post">
		<input type=hidden name='codeA' value='<?=$codeA?>'>
		<input type=hidden name='codeB' value='<?=$codeB?>'>
		<input type=hidden name='codeC' value='<?=$codeC?>'>
		<input type=hidden name='codeD' value='<?=$codeD?>'>
		<input type=hidden name='s_check' value='<?=$s_check?>'>
		<input type=hidden name='select_code' value='<?=$select_code?>'>
		<input type=hidden name='search' value='<?=$search?>'>
		<input type=hidden name='sort' value='<?=$sort?>'>
		<input type=hidden name='mode' value='<?=$mode?>'>
		<input type=hidden name='code' value='<?=$code?>'>
		<input type=hidden name='block' value='<?=$block?>'>
		<input type=hidden name='gotopage' value='<?=$gotopage?>'>
		</form>
		<?=$pageing?>
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr><td height=<?=(394-($i*29))?>></td></tr>
<tr>
	<td height=30 align=right bgcolor=DEDEDE style=padding-top:3><a href=javascript:self.close()><img src=images/btn_close01.gif border=0 align=absmiddle></a>&nbsp;</td>
</tr>
</table>
</center>
</body>
</html>
