<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "pr-1";
$MenuCode = "nomenu";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################
$Scrolltype=$_REQUEST["Scrolltype"];

$mode=$_POST["mode"];
$code=$_POST["code"];
$prcode=$_POST["prcode"];
$change=$_POST["change"];
$prcodes=$_POST["prcodes"];

if(strlen($code)==12) {
	$sql = "SELECT type FROM tblproductcode WHERE codeA='".substr($code,0,3)."' ";
	$sql.= "AND codeB='".substr($code,3,3)."' ";
	$sql.= "AND codeC='".substr($code,6,3)."' AND codeD='".substr($code,9,3)."' ";
	$result=mysql_query($sql,get_db_conn());
	$row=mysql_fetch_object($result);
	mysql_free_result($result);
	if(!$row) {
		$code="";
	}
	$type = $row->type;
} else {
	$code="";
}

if ($mode=="sequence" && $change=="Y" && strlen($prcodes)>0) {
	$date1=date("Ym");
	$date=date("dHis");
	$productcode = explode(",",$prcodes);
	$codeabcd = explode(",",$codes);
	$cnt = count($productcode);
	for($i=0;$i<$cnt;$i++){
		$date=$date-1;
		if (strlen($date)==7) $date="0".$date;
		else if (strlen($date)==6) $date="00".$date;

		if(!ereg("T",$type)) {
			$sql = "UPDATE tblproduct SET date = '".$date1.$date."' ";
			$sql.= "WHERE productcode='".$productcode[$i]."' ";
		} else {
			$likecode=substr($code,0,3);
			if(substr($code,3,3)!="000") {
				$likecode.=substr($code,3,3);
				if(substr($code,6,3)!="000") {
					$likecode.=substr($code,6,3);
					if(substr($code,9,3)!="000") {
						$likecode.=substr($code,9,3);
					}
				}
			}
			$sql = "UPDATE tblproducttheme SET date = '".$date1.$date."' ";
			$sql.= "WHERE code LIKE '".$likecode."%'  AND code='".$codeabcd[$i]."' ";
			$sql.= "AND productcode = '".substr($productcode[$i],-18)."' ";
		}
		mysql_query($sql,get_db_conn());
	}
	$onload="<script>alert('��ǰ���� ������ �Ϸ�Ǿ����ϴ�.');</script>\n";

	$log_content = "[��ϻ�ǰ �������� ����] ī�װ��ڵ� : $code";
	ShopManagerLog($_ShopInfo->getId(),$connect_ip,$log_content);
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

<? INCLUDE "header.php"; ?>
<style>td {line-height:18pt;}</style>
<script type="text/javascript" src="lib.js.php"></script>
<script>var LH = new LH_create();</script>
<script for=window event=onload>LH.exec();</script>
<script>LH.add("parent_resizeIframe('ListFrame')");</script>

<script language="JavaScript">
function CheckForm() {

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
	
	if(updownValue)
		ekey = updownValue;
	else
		ekey = event.keyCode;

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

function takeChange(argObj)
{
	var innerHtmlStr = "";

	innerHtmlStr="<TD>"+argObj.num+"</td>";
	innerHtmlStr+="<TD><a href=\"javascript:updown_click('"+argObj.num+"','up')\"><img src=\"images/btn_plus.gif\" border=\"0\" style=\"margin-bottom:3px;\"></a><br><a href=\"javascript:updown_click('"+argObj.num+"','down')\"><img src=\"images/btn_minus.gif\" border=\"0\" style=\"margin-top:3px;\"></a></td>";
	<?if($vendercnt>0) {echo "innerHtmlStr+=argObj.venderidx;\n";}?>
	innerHtmlStr+=argObj.imgidx;
	innerHtmlStr+=argObj.nameidx;
	innerHtmlStr+=argObj.sellidx;
	innerHtmlStr+=argObj.quantityidx;
	innerHtmlStr+=argObj.displayidx;
	innerHtmlStr+=argObj.editidx;
	document.all["idx_inner_"+argObj.sort].innerHTML="<TABLE onclick=\"ChangeList('"+argObj.num+"');\" border=\"0\" cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\" style=\"table-layout:fixed\"><col width=40></col><col width=12></col><?=($vendercnt>0?"<col width=70></col>":"")?><col width=50></col><col width=></col><col width=70></col><col width=45></col><col width=45></col><col width=45></col><tr align=\"center\">"+innerHtmlStr+"</tr></table>";
}

function updown_click(num,updownValue)
{
	if(selnum != num)
		ChangeList(num);
	
	CheckKeyPress(updownValue);
}

function ChangeList(num) {
	if(ProductInfoStop)
		ProductInfoStop = "";
	else
	{
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
	this.no				= new String((argc > 8) ? argv[8] : "");
	this.sort			= new String((argc > 9) ? argv[9] : "");
	this.selected		= new Boolean((argc > 10) ? argv[10] : false );
	<?if($vendercnt>0) {echo "this.venderidx		= new String((argc > 11) ? argv[11] : \"\");\n";}?>
}

function move_save()
{
	if (document.form1.change.value!="Y") {
		alert("���� ������ ���� �ʾҽ��ϴ�.");
		return;
	}
	if (!confirm("������ ������� �����Ͻðڽ��ϱ�?")) return;
	val="";
	val2="";
	for(i=0;i<all_list.length;i++)
	{
		var all_list_pcode = all_list[i].productcode.split('|');

		val +=","+all_list_pcode[0];
		val2+=","+all_list_pcode[1];
	}
	
	if(val.length>0)
	{
		val=val.substring(1);
		val2=val2.substring(1);
		document.form1.mode.value = "sequence";
		document.form1.prcodes.value=val;
		document.form1.codes.value=val2;
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
	
	if(!WinObj.height)
		WinObj.height = WinObj.offsetTop;

	WinObjPY = WinObj.offsetParent.offsetHeight;
	WinObjST = WinObj.height-WinObj.offsetParent.scrollTop;
	WinObjSY = WinObjST+WinObj.offsetHeight;

	if(WinObjPY < WinObjSY)
		WinObj.style.top = WinObj.offsetParent.scrollTop-WinObj.offsetHeight+WinObjPY;
	else if(WinObjST < 0)
		WinObj.style.top = WinObj.offsetParent.scrollTop;
	else
		WinObj.style.top = WinObj.height;
}
function ProductMouseOut(Obj) {
	obj = event.srcElement;
	WinObj = document.getElementById(Obj);
	WinObj.style.display = "none";
	clearTimeout(obj._tid);
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

function DivScrollActive(arg1)
{
	if(!self.id)
		self.id = self.name;
	
	if(document.getElementById("divscroll") && document.getElementById("ListTTableId") && document.getElementById("ListLTableId") && parent.document.getElementById(self.id))
	{
		if(!document.getElementById("divscroll").height)
			document.getElementById("divscroll").height=document.getElementById("divscroll").offsetHeight;

		if(arg1>0)
		{
			if(document.getElementById("ListLTableId").offsetHeight > document.getElementById("divscroll").offsetHeight)
			{
				document.getElementById("divscroll").style.height="100%";
				parent.document.getElementById(self.id).style.height=document.getElementById("ListTTableId").offsetHeight;
			}
		}
		else
		{
			document.getElementById("divscroll").style.height=document.getElementById("divscroll").height;
			parent.document.getElementById(self.id).style.height="100%";
		}
	}
	
	document.form1.Scrolltype.value = arg1;
}
</script>
<table id="ListTTableId" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" style="table-layout:fixed">
<form name=form1 action="<?=$_SERVER[PHP_SELF]?>" method=post>
<input type=hidden name=code value="<?=$code?>">
<input type=hidden name=prcode>
<input type=hidden name=Scrolltype value="<?=$Scrolltype?>">
<tr>
	<td width="100%" bgcolor="#FFFFFF"><IMG SRC="images/product_mainlist_text.gif" border="0"></td>
</tr>
<tr>
	<td width="100%" height="100%" valign="top" bgcolor="#FFFFFF" style="BORDER:#FF8730 2px solid;padding-left:5px;padding-right:5px;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td><span style="font-size:8pt; letter-spacing:-0.5pt;" class="font_orange">* ���������� ������ ���ϴ� ��ǰ�� ���� �� Ű���� ��(��)��(��) Ű�� �̵��� �ּ���.</span></td>
			<td align="right"><a href="javascript:DivScrollActive(1);"><span style="letter-spacing:-0.5pt;" class="font_orange"><b>��ü��ħ</b></span></a>&nbsp;&nbsp;<a href="javascript:DivScrollActive(0);"><b>��ħ�ݱ�</b></a></td>
		</tr>
		</table>
		</td>
	</tr>
	<TR>
		<TD colspan="2" background="images/table_top_line.gif"></TD>
	</TR>
	<TR>
		<TD width="100%">
		<DIV id="divscroll" style="position:relative;z-index:1;width:100%;height:523px;bgcolor:#FFFFFF;overflow-x:hidden;overflow-y:auto;">
		<div id="jumpdiv" style="position:absolute; display:'none';">
		<table border="0" cellspacing="1" cellpadding="0" bgcolor="#B9B9B9" width="210">
		<col width="100"></col>
		<col width=""></col>
		<tr bgcolor="#FFFFFF">
			<td bgcolor="#F8F8F8" style="padding:2px;" align="center"><img src="images/icon_point5.gif" border="0"><b>�̵���ġ No</b></td>
			<td style="padding:3px;"><input type=text name="jumpnumber" value="" size="4" maxlength="5" style="height:19;font-size:8pt"><a href="javascript:jumpgo();"><img src="images/btn_ok3.gif" border="0" align="absmiddle" hspace="5"></a></td>
		</tr>
		</table>
		</div>
		<TABLE id="ListLTableId" border="0" cellSpacing="0" cellPadding="0" width="100%" style="table-layout:fixed">
<?
		$colspan=8;
		if($vendercnt>0) $colspan++;
?>
		<col width=40></col>
		<col width=12></col>
		<?if($vendercnt>0){?>
		<col width=70></col>
		<?}?>
		<col width=50></col>
		<col width=></col>
		<col width=70></col>
		<col width=45></col>
		<col width=45></col>
		<col width=45></col>
		<TR align="center">
			<TD class="table_cell" colspan="2">No</TD>
			<?if($vendercnt>0){?>
			<TD class="table_cell1">������ü</TD>
			<?}?>
			<TD class="table_cell1" colspan="2">��ǰ��/�����ڵ�/Ư�̻���</TD>
			<TD class="table_cell1">�ǸŰ���</TD>
			<TD class="table_cell1">����</TD>
			<TD class="table_cell1">����</TD>
			<TD class="table_cell1">����</TD>
		</TR>
<?
		$image_i=0;
		if(strlen($code)==12) {
			$page_numberic_type=1;
			if(ereg("X",$type)) {
				$likecode=$code;
			} else {
				$likecode=substr($code,0,3);
				if(substr($code,3,3)!="000") {
					$likecode.=substr($code,3,3);
					if(substr($code,6,3)!="000") {
						$likecode.=substr($code,6,3);
						if(substr($code,9,3)!="000") {
							$likecode.=substr($code,9,3);
						}
					}
				}
			}

			if (!ereg("T",$type)) {		//�⺻ī�װ�
				$sql = "SELECT a.option_price,a.productcode,a.productname,a.production,a.sellprice,a.consumerprice, ";
				$sql.= "a.buyprice,a.quantity,a.reserve,a.reservetype,a.addcode,a.display,a.vender,a.tinyimage,a.selfcode,a.assembleuse ";
				$sql.= "FROM tblproduct AS a ";
				$sql.= "WHERE a.productcode LIKE '".$likecode."%' ";
				$sql.= "ORDER BY a.date DESC ";
			} else {	//����ī�װ�
				$sql = "SELECT a.option_price,a.productcode,a.productname,a.production,a.sellprice,a.consumerprice, ";
				$sql.= "a.buyprice,a.quantity,a.reserve,a.reservetype,a.addcode,a.display,a.vender,a.tinyimage,b.code,a.selfcode,a.assembleuse ";
				$sql.= "FROM tblproduct AS a, tblproducttheme AS b ";
				$sql.= "WHERE b.code LIKE '".$likecode."%' ";
				$sql.= "AND a.productcode=b.productcode ";
				$sql.= "ORDER BY b.date DESC ";
			}
			
			$result = mysql_query($sql,get_db_conn());
			$cnt = @mysql_num_rows($result);
			
			if($cnt>0)
			{
				$j=0;
				$strlist="<script>\n";
				$jj=$cnt;
				$ii=0;
				while($row=mysql_fetch_object($result)) {
					$j++;
					$strlist.= "var objlist=new ObjList();\n";
					$strlist.= "objlist.num=\"".$j."\";\n";
					$strlist.= "all_list_i[objlist.num]=".$ii.";\n";

					$strlist.= "objlist.productcode=\"".(!ereg("T",$type)?$row->productcode:$row->productcode."|".$row->code)."\";\n";
					if($vendercnt>0) {$strlist.= "objlist.venderidx=\"<TD class=\\\"td_con1\\\"><B>".(strlen($venderlist[$row->vender]->vender)>0?"<span onclick=\\\"viewVenderInfo(".$row->vender.");\\\">".$venderlist[$row->vender]->id."</span>":"-")."</B></td>\";\n";}
					if (strlen($row->tinyimage)>0 && file_exists($imagepath.$row->tinyimage)==true){
						$strlist.= "objlist.imgidx=\"<TD class=\\\"td_con1\\\"><img src=\\\"".$imagepath.$row->tinyimage."\\\" height=\\\"40\\\" width=\\\"40\\\" border=\\\"1\\\" onMouseOver=\\\"ProductMouseOver('primage".$image_i."')\\\" onMouseOut=\\\"ProductMouseOut('primage".$image_i."');\\\"><div id=\\\"primage".$image_i."\\\" style=\\\"position:absolute; z-index:100; display:none;\\\"><table border=\\\"0\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" width=\\\"170\\\"><tr bgcolor=\\\"#FFFFFF\\\"><td align=\\\"center\\\" width=\\\"100%\\\" height=\\\"150\\\" style=\\\"border:#000000 solid 1px;\\\"><img src=\\\"".$imagepath.$row->tinyimage."\\\" border=\\\"0\\\"></td></tr></table></div></td>\";\n";
					} else {
						$strlist.= "objlist.imgidx=\"<TD class=\\\"td_con1\\\"><img src=images/space01.gif onMouseOver=\\\"ProductMouseOver('primage".$image_i."')\\\" onMouseOut=\\\"ProductMouseOut('primage".$image_i."');\\\"><div id=\\\"primage".$image_i."\\\" style=\\\"position:absolute; z-index:100; display:none;\\\"><table border=\\\"0\\\" cellspacing=\\\"0\\\" cellpadding=\\\"0\\\" width=\\\"170\\\"><tr bgcolor=\\\"#FFFFFF\\\"><td align=\\\"center\\\" width=\\\"100%\\\" height=\\\"150\\\" style=\\\"border:#000000 solid 1px;\\\"><img src=\\\"".$Dir."images/product_noimg.gif\\\" border=\\\"0\\\"></td></tr></table></div></td>\";\n";
					}
					$strlist.= "objlist.nameidx=\"<TD class=\\\"td_con1\\\" align=\\\"left\\\" style=\\\"word-break:break-all;\\\"><img src=\\\"images/producttype".($row->assembleuse=="Y"?"y":"n").".gif\\\" border=\\\"0\\\" align=\\\"absmiddle\\\" hspace=\\\"2\\\">".addslashes($row->productname.($row->selfcode?"-".$row->selfcode:"").($row->addcode?"-".$row->addcode:""))."&nbsp;</td>\";\n";
					$strlist.= "objlist.sellidx=\"<TD align=\\\"right\\\" class=\\\"td_con1\\\"><img src=\\\"images/won_icon.gif\\\" border=\\\"0\\\" style=\\\"margin-right:2px;\\\"><span class=\\\"font_orange\\\">".number_format($row->sellprice)."</span><br><img src=\\\"images/reserve_icon.gif\\\" border=\\\"0\\\" style=\\\"margin-right:2px;\\\">".($row->reservetype!="Y"?number_format($row->reserve):$row->reserve."%")."</td>\";\n";
					if (strlen($row->quantity)==0) $strlist.= "objlist.quantityidx=\"<TD class=\\\"td_con1\\\">������</td>\";\n";
					else if ($row->quantity<=0) $strlist.= "objlist.quantityidx=\"<TD class=\\\"td_con1\\\"><span class=\\\"font_orange\\\"><b>ǰ��</b></span></td>\";\n";
					else $strlist.= "objlist.quantityidx=\"<TD class=\\\"td_con1\\\">".$row->quantity."</td>\";\n";
					
					$strlist.= "objlist.displayidx=\"<TD class=\\\"td_con1\\\">".($row->display=="Y"?"<font color=\\\"#0000FF\\\">�Ǹ���</font>":"<font color=\\\"#FF4C00\\\">������</font>")."</td>\";\n";

					$strlist.= "objlist.editidx=\"<TD class=\\\"td_con1\\\"><img src=\\\"images/icon_newwin1.gif\\\" border=\\\"0\\\" onclick=\\\"ProductInfo('".$row->productcode."');\\\" style=\\\"cursor:hand;\\\"></td>\";\n";
					$strlist.= "objlist.no=\"".$jj--."\";\n";
					$strlist.= "objlist.sort=\"".$ii."\";\n";
					$strlist.= "objlist.selected=false;\n";
					$strlist.= "all_list[".$ii."]=objlist;\n";
					$strlist.= "objlist=null;\n";
					
					echo "<tr>\n";
					echo "	<TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></TD>\n";
					echo "</tr>\n";
					echo "<tr>\n";
					echo "	<td id=\"idx_inner_".$ii."\" colspan=\"".$colspan."\" style=\"background-color:'#FFFFFF';\" onmouseover=\"if(this.style.backgroundColor != '#efefef')this.style.backgroundColor='#F4F7FC';\" onmouseout=\"if(this.style.backgroundColor != '#efefef')this.style.backgroundColor='#FFFFFF';\" style=\"cursor:hand;\">\n";
					echo "	<TABLE border=\"0\" cellSpacing=\"0\" cellPadding=\"0\" width=\"100%\" style=\"table-layout:fixed;\" onclick=\"ChangeList('".$j."');\">\n";
					echo "	<col width=40></col><col width=12></col>".($vendercnt>0?"<col width=70></col>":"")."<col width=50></col><col width=></col><col width=70></col><col width=45></col><col width=45></col><col width=45></col>\n";
					echo "	<tr align=\"center\">\n";
					echo "		<TD>".$j."</td>\n";
					echo "		<TD><a href=\"javascript:updown_click('".$j."','up')\"><img src=\"images/btn_plus.gif\" border=\"0\" style=\"margin-bottom:3px;\"></a><br><a href=\"javascript:updown_click('".$j."','down')\"><img src=\"images/btn_minus.gif\" border=\"0\" style=\"margin-top:3px;\"></a></td>\n";
					if($vendercnt>0) {
						echo "	<TD class=\"td_con1\"><B>".(strlen($venderlist[$row->vender]->vender)>0?"<span onclick=\"viewVenderInfo(".$row->vender.");\">".$venderlist[$row->vender]->id."</span>":"-")."</B></td>\n";
					}
					echo "		<TD class=\"td_con1\">";
					if (strlen($row->tinyimage)>0 && file_exists($imagepath.$row->tinyimage)==true){
						echo "<img src=\"".$imagepath.$row->tinyimage."\" height=\"40\" width=\"40\" border=\"1\" onMouseOver=\"ProductMouseOver('primage".$image_i."')\" onMouseOut=\"ProductMouseOut('primage".$image_i."');\">";
					} else {
						echo "<img src=images/space01.gif onMouseOver=\"ProductMouseOver('primage".$image_i."')\" onMouseOut=\"ProductMouseOut('primage".$image_i."');\">";
					}
					
					echo "<div id=\"primage".$image_i."\" style=\"position:absolute; z-index:100; display:none;\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"170\">\n";
					echo "		<tr bgcolor=\"#FFFFFF\">\n";
					if (strlen($row->tinyimage)>0 && file_exists($imagepath.$row->tinyimage)==true) {
						echo "		<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"".$imagepath.$row->tinyimage."\" border=\"0\"></td>\n";
					} else {
						echo "		<td align=\"center\" width=\"100%\" height=\"150\" style=\"border:#000000 solid 1px;\"><img src=\"".$Dir."images/product_noimg.gif\" border=\"0\"></td>\n";
					}
					echo "		</tr>\n";
					echo "		</table>\n";
					echo "		</div>\n";
					echo "		</td>\n";
					echo "		<TD class=\"td_con1\" align=\"left\" style=\"word-break:break-all;\"><img src=\"images/producttype".($row->assembleuse=="Y"?"y":"n").".gif\" border=\"0\" align=\"absmiddle\" hspace=\"2\">".$row->productname.($row->selfcode?"-".$row->selfcode:"").($row->addcode?"-".$row->addcode:"")."&nbsp;</td>\n";
					echo "		<TD align=right class=\"td_con1\"><img src=\"images/won_icon.gif\" border=\"0\" style=\"margin-right:2px;\"><span class=\"font_orange\">".number_format($row->sellprice)."</span><br><img src=\"images/reserve_icon.gif\" border=\"0\" style=\"margin-right:2px;\">".($row->reservetype!="Y"?number_format($row->reserve):$row->reserve."%")."</TD>\n";
					echo "		<TD class=\"td_con1\">";
					if (strlen($row->quantity)==0) echo "������";
					else if ($row->quantity<=0) echo "<span class=\"font_orange\"><b>ǰ��</b></span>";
					else echo $row->quantity;
					echo "		</TD>\n";
					echo "		<TD class=\"td_con1\">".($row->display=="Y"?"<font color=\"#0000FF\">�Ǹ���</font>":"<font color=\"#FF4C00\">������</font>")."</td>";
					echo "		<TD class=\"td_con1\"><img src=\"images/icon_newwin1.gif\" border=\"0\" onclick=\"ProductInfo('".$row->productcode."');\" style=\"cursor:hand;\"></td>\n";
					echo "	</tr>\n";
					echo "	</table>\n";
					echo "	</td>\n";
					echo "</tr>\n";
					$ii++;
					$image_i++;
				}

				mysql_free_result($result);

				$strlist.="</script>\n";
				echo $strlist;
			} else {
				$page_numberic_type="";
				echo "<tr><TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></TD></tr><tr><td class=\"td_con2\" colspan=\"".$colspan."\" align=\"center\">��ϵ� ��ǰ�� �����ϴ�.</td></tr>";
			}
		} else {
			$page_numberic_type="";
			echo "<tr><TD colspan=\"".$colspan."\" background=\"images/table_con_line.gif\"></TD></tr><tr><td class=\"td_con2\" colspan=\"".$colspan."\" align=\"center\">��ϵ� ��ǰ�� �����ϴ�.</td></tr>";
		}
?>
		<TR>
			<TD height="1" colspan="<?=$colspan?>" background="images/table_con_line.gif"></TD>
		</TR>
		</TABLE>
		</div>
		</td>
	</TR>
	<TR>
		<TD background="images/table_top_line.gif"></TD>
	</TR>
	<TR>
		<TD><span style="font-size:8pt; letter-spacing:-0.5pt;" class="font_orange">* ���������� ������ ���ϴ� ��ǰ�� ���� �� Ű���� ��(��)��(��) Ű�� �̵��� �ּ���.</span></TD>
	</TR>
	<TR>
		<TD align=center><a href="javascript:move_save();"><img src="images/btn_mainarray.gif" border="0"></a></TD>
	</TR>
	<tr>
		<td height="10"></td>
	</tr>
	<input type=hidden name=mode value="<?=$mode?>">
	<input type=hidden name=change>
	<input type=hidden name=prcodes>
	<input type=hidden name=codes>
	</form>

	<form name=form_reg action="product_register.php" method=post>
	<input type=hidden name=code>
	<input type=hidden name=prcode>
	<input type=hidden name=popup>
	</form>

	<?if($vendercnt>0){?>
	<form name=vForm action="vender_infopop.php" method=post>
	<input type=hidden name=vender>
	</form>
	<?}?>
	</TABLE>
	</td>
</tr>
</table>
<?="<script>DivScrollActive(".(int)$Scrolltype.");</script>"?>
<?=$onload?>
</body>
</html>