<div style="clear:both;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
<div style="padding:10px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
	<div style="float:left;padding-left:50px;height:35px;line-height:35px;background:url('/data/design/img/sub/icon_basket.gif') no-repeat;color:#696969;font-size:25px;font-weight:600;">��ٱ���</div>
	<div style="float:right;margin-top:3px;"><img src="/data/design/img/sub/step_basket.gif" alt="" /></div>
	<div style="clear:both;"></div>
</div>
<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>

<div style="clear:both;margin-top:20px;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>

<div style="padding:40px 0px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;text-align:center;overflow:hidden;">
	<div style="width:96%;margin:0px auto;">
<?
	// ����Ʈ ���� ����
	if($_data->oneshot_ok=="Y") {
		$codeA=$_POST["codeA"];
		$codeB=$_POST["codeB"];
		$codeC=$_POST["codeC"];
		$codeD=$_POST["codeD"];
		$likecode=$codeA.$codeB.$codeC.$codeD;
?>

<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td><img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/oneshot_primage001_stext.gif" border="0"></td>
	</tr>
	<tr>
		<td bgcolor="#E8E8E8" style="padding:8px;">

			<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
				<tr>
					<td bgcolor="#ffffff" style="padding:15px;">

						<form name=form1 method=post action="<?=$_SERVER[PHP_SELF]?>">
						<table cellpadding="0" cellspacing="0" width="100%" border="0">
							<input type=hidden name=productcode>
							<input type=hidden name=quantity>
							<input type=hidden name=option1>
							<input type=hidden name=option2>
							<input type=hidden name=assembleuse>
							<input type=hidden name=package_num>
							<tr>
								<td><IMG SRC="<?=$Dir?>images/common/basket/oneshot_primage001.gif" border="0" width=50 height=50 name="oneshot_primage"></td>
								<td align="center">

									<table cellpadding="0" cellspacing="0">
										<tr>
											<td style="padding:2px;"><select name="codeA" onchange="SearchChangeCate(this,1);CheckCode();" style="width:150;font-size:11px;"><option value="">--- 1�� ī�װ� ���� ---</option></SELECT></td>
											<td style="padding:2px;"><select name="codeB" onchange="SearchChangeCate(this,2);CheckCode();" style="width:150;font-size:11px;"><option value="">--- 2�� ī�װ� ���� ---</option></SELECT></td>
											<td style="padding:2px;"><select name="codeC" onchange="SearchChangeCate(this,3);CheckCode();" style="width:150;font-size:11px;"><option value="">--- 3�� ī�װ� ���� ---</option></SELECT></td>
										</tr>
										<TR>
											<TD style="padding:2px;"><select name="codeD" onchange="CheckCode();" style="width:150;font-size:11px;"><option value="">--- 4�� ī�װ� ���� ---</option></SELECT></td>
											<td colspan="2" style="padding:2px;"><select name="tmpprcode" onchange="CheckProduct();" style="width:306px;font-size:11px;"><option value="">��ǰ ����</option>
												<?
													if(strlen($likecode)==12) {
														$sql = "SELECT a.productcode,a.productname,a.sellprice,a.tinyimage,a.quantity,a.option1,a.option2,a.etctype,a.selfcode,a.assembleuse,a.package_num ";
														$sql.= "FROM tblproduct AS a ";
														$sql.= "LEFT OUTER JOIN tblproductgroupcode b ON a.productcode=b.productcode ";
														$sql.= "WHERE a.productcode LIKE '".$likecode."%' AND a.display='Y' ";
														$sql.= "AND (a.group_check='N' OR b.group_code='".$_ShopInfo->getMemgroup()."') ";
														$sql.= "ORDER BY a.productname ";
														$result=mysql_query($sql,get_db_conn());
														$ii=0;
														$prlistscript="<script>\n";
														while($row=mysql_fetch_object($result)) {
															if(strlen(dickerview($row->etctype,$row->sellprice,1))==0) {
																$miniq = 1;
																if (strlen($row->etctype)>0) {
																	$etctemp = explode("",$row->etctype);
																	for ($i=0;$i<count($etctemp);$i++) {
																		if (substr($etctemp[$i],0,6)=="MINIQ=") $miniq=substr($etctemp[$i],6);  // �ּ��ֹ�����
																	}
																}
																echo "<option value=\"".$ii."\">".strip_tags(str_replace("<br>", " ", viewselfcode($row->productname,$row->selfcode)))." - ".number_format($row->sellprice)."��";
																if(strlen($row->quantity)!=0 && $row->quantity<=0) echo " (ǰ��)";
																echo "</option>\n";

																if(strlen($row->quantity)!=0 && $row->quantity<=0) {
																	$tmpq=0;
																} else {
																	$tmpq=$row->quantity;
																	if($row->quantity==NULL) $tmpq=1000;
																}
																$prlistscript.="var plist=new pralllist();\n";
																$prlistscript.="plist.productcode='".$row->productcode."';\n";
																$prlistscript.="plist.tinyimage='".$row->tinyimage."';\n";
																$prlistscript.="plist.option1=1;\n";
																$prlistscript.="plist.option2=1;\n";
																$prlistscript.="plist.quantity=".$tmpq.";\n";
																$prlistscript.="plist.miniq=".$miniq.";\n";
																$prlistscript.="plist.assembleuse='".($row->assembleuse=="Y"?"Y":"N")."';\n";
																$prlistscript.="plist.package_num='".((int)$row->package_num>0?$row->package_num:"")."';\n";
																$prlistscript.="prall[".$ii."]=plist;\n";
																$prlistscript.="plist=null;\n";
																$ii++;
															}
														}
														mysql_free_result($result);
														$prlistscript.="</script>\n";
													}
												?>
												</SELECT>
											</td>
										</tr>
									</table>

								</td>
								<td><a href="javascript:OneshotBasketIn();"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn1.gif" border="0"></a></td>
							</tr>
						</table>
						</form>


					</td>
				</tr>
			</table>

		</td>
	</tr>
	<?
		$sql = "SELECT * FROM tblproductcode ";
		if(strlen($_ShopInfo->getMemid())==0 || $_ShopInfo->getMemid()=="deleted") {
			$sql.= "WHERE group_code='' ";
		} else {
			$sql.= "WHERE (group_code='' OR group_code='ALL' OR group_code='".$_ShopInfo->getMemgroup()."') ";
		}
		$sql.= "AND (type!='T' AND type!='TX' AND type!='TM' AND type!='TMX') ORDER BY sequence DESC ";
		$i=0;
		$ii=0;
		$iii=0;
		$iiii=0;
		$strcodelist = "";
		$strcodelist.= "<script>\n";
		$result = mysql_query($sql,get_db_conn());
		$selcode_name="";
		while($row=mysql_fetch_object($result)) {
			$strcodelist.= "var clist=new CodeList();\n";
			$strcodelist.= "clist.codeA='".$row->codeA."';\n";
			$strcodelist.= "clist.codeB='".$row->codeB."';\n";
			$strcodelist.= "clist.codeC='".$row->codeC."';\n";
			$strcodelist.= "clist.codeD='".$row->codeD."';\n";
			$strcodelist.= "clist.type='".$row->type."';\n";
			$strcodelist.= "clist.code_name='".$row->code_name."';\n";
			if($row->type=="L" || $row->type=="T" || $row->type=="LX" || $row->type=="TX") {
				$strcodelist.= "lista[".$i."]=clist;\n";
				$i++;
			}
			if($row->type=="LM" || $row->type=="TM" || $row->type=="LMX" || $row->type=="TMX") {
				if ($row->codeC=="000" && $row->codeD=="000") {
					$strcodelist.= "listb[".$ii."]=clist;\n";
					$ii++;
				} else if ($row->codeD=="000") {
					$strcodelist.= "listc[".$iii."]=clist;\n";
					$iii++;
				} else if ($row->codeD!="000") {
					$strcodelist.= "listd[".$iiii."]=clist;\n";
					$iiii++;
				}
			}
			$strcodelist.= "clist=null;\n\n";
		}
		mysql_free_result($result);
		$strcodelist.= "CodeInit();\n";
		$strcodelist.= "</script>\n";

		echo $strcodelist;

		echo $prlistscript;

		echo "<script>SearchCodeInit('".$codeA."','".$codeB."','".$codeC."','".$codeD."');</script>";
	?>
	<tr><td height="30"></td></tr>
</table>
<?
	} // ���ǵ� ���� ��
?>

<style type="text/css">
	ul.basketFolderTab{ margin:0px; padding:0px; display:table; border-collapse:collapse}
	ul.basketFolderTab li{ display:inline-block; padding:10px 20px; float:left; cursor:pointer; position:relative; font-size:14px; font-weight:bold; color:#02b0dd;}
	ul.basketFolderTab li.active{ font-weight:bold; color:#ea2f36; font-size:15px;}
	ul.basketFolderTab .modifyFolderBtn{color:white; background:#000; padding:2px; margin-right:2px; border:0px;}
	ul.basketFolderTab .deleteFolderBtn{color:white; background:#000; padding:2px; border:0px;}
	.moveFolderBtn{ cursor:pointer}
</style>

<? if(!_empty($_ShopInfo->getMemid())){ ?>
<ul class="basketFolderTab">
	<li bfidx="">��ü</li>
	<? foreach($folders as $bfidx=>$bfname){ 
			$class = ($_REQUEST['sfld'] == $bfidx)?'active':'';
	?>
	<li bfidx="<?=$bfidx?>"  bfname="<?=$bfname?>" class="<?=$class?>"><?=$bfname?>
		<div class="folderBtns" style="position:absolute; display:none;width:70px;right:0px; top:8px;">
			<input type="button" value="����" class="modifyFolderBtn" style="" />
			<input type="button" value="����" class="deleteFolderBtn" style="color:white; background:#000; padding:2px; border:0px;" />
		</div>
</li>
	<?	} ?>
</ul>

<script language="javascript" type="text/javascript">
	function checkMoveFolder(el){
		if($j(el).val() == '0'){
			$j(el).parent().find('input[name=newFoldername]').css('display','');
		}else{
			$j(el).parent().find('input[name=newFoldername]').css('display','none');
		}
	}
</script>

<div id="moveFolderDialog" style="display:none" title="�ٸ� ������ �̵�">
	<form name="moveFolder" id="moveFolder" action="<?=$_SERVER['PHP_SELF']?>" method="post">
	<input type="hidden" name="act" value="moveFolder" />
	<input type="hidden" name="ordertype" value="<?=$_REQUEST['ordertype']?>" />
	<input type="hidden" name="sfld" value="<?=$_REQUEST['sfld']?>" />
	<table border="0" cellpadding="0" cellspacing="0" style="border-right:1px solid #ccc;  border-top:1px solid #ccc;">
		<tr>
			<th style="width:80px; border-left:1px solid #ccc;  border-bottom:1px solid #ccc; background:#efefef; padding:3px">�������</th>
			<td style="padding-left:5px; border-left:1px solid #ccc; border-bottom:1px solid #ccc;">
				<select name="targetFolder" onchange="checkMoveFolder(this)">
					<option value="0">���λ���</option>
					<? foreach($folders as $bfidx=>$bfname){ ?>
					<option value="<?=$bfidx?>"><?=$bfname?></option>
					<? }?>
				</select>
				<input type="text" name="newFoldername" value="" style="display:" />
			</td>
		</tr>
	</table>
	</form>
</div>
<div id="modifyFolderDialog" style="display:none" title="���� ���� ����">
<form name="editFolder" id="editFolder" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="act" value="modifyFoldername" />
<input type="hidden" name="ordertype" value="<?=$_REQUEST['ordertype']?>" />
<input type="hidden" name="bfidx" value="" />
<input type="hidden" name="sfld" value="<?=$_REQUEST['sfld']?>" />
<table border="0" cellpadding="0" cellspacing="0" style="border-right:1px solid #ccc;  border-top:1px solid #ccc;">
	<tr>
		<th style="width:80px; border-left:1px solid #ccc;  border-bottom:1px solid #ccc; background:#efefef; padding:3px">�����̸�</th>
		<td style="padding-left:5px; border-left:1px solid #ccc; border-bottom:1px solid #ccc;"><input type="text" name="oldname" value="" readonly="readonly" style="border:0px" /></td>
	</tr>
	<tr>
		<th style="background:#efefef;border-left:1px solid #ccc; padding:3px;  border-bottom:1px solid #ccc;">���̸�</th>
		<td style="padding-left:5px;border-left:1px solid #ccc;  border-bottom:1px solid #ccc;"><input type="text" name="newFoldername" value="" style="margin-right:5px;"/></td>
	</tr>
</table>
<span>*������ �̸��� ���� ��� ���յ˴ϴ�.</span>
</form>
</div>
<script language="javascript" type="text/javascript">
var selItem =null;

function openFolderEditDialog(bfidx,bfname){
	$j('#modifyFolderDialog').find('#editFolder').find('input[name=bfidx]').val(bfidx);
	$j('#modifyFolderDialog').find('#editFolder').find('input[name=oldname]').val(bfname);
	$j('#modifyFolderDialog').dialog('open');
	$j('#modifyFolderDialog').find('#editFolder').find('input[name=newFoldername]').focus();
}



function recommandOrder(){
	if(checkSelect() <1){
		alert('Ÿȸ������ ��õ�� ��ǰ�� ���� ���ּ���');
	}else{
		$j('#basketForm>input[name=act]').val('basketToRecommand');
		$j('#basketForm').attr('action','/front/proc/basket.php');
		$j('#basketForm').submit();
	}
}

$j(function(){	
	$j('#modifyFolderDialog').dialog({
		autoOpen:false,
		resizable: false,
    	modal: true,
	    buttons: {
        	"����":function(){
         		/*
				$j.ajax({
				    type : 'POST',
				    url : 'url',
				    data : $j(this).find('#editFolder').serialize() + "&act=modifyfoldername"
					
				});*/
				$j(this).find('#editFolder').submit();
	        },
        	"���": function() {				
		          $j(this).dialog( "close" );
    	    }
      	},
		close:function(){
			$j(this).find('#editFolder').find('input[name=bfidx]').val('');
			$j(this).find('#editFolder').find('input[name=oldname]').val('');
			$j(this).find('#editFolder').find('input[name=newFoldername]').val('');
			selItem = null;
		}
    });
	
	$j('#moveFolderDialog').dialog({
		autoOpen:false,
		resizable: false,
    	modal: true,
	    buttons: {
        	"����":function(){
				var target = $j('#moveFolder').find('select[name=targetFolder]').val();
				if(target == '0'){
					newfolder = $j('#moveFolder').find('input[name=newFoldername]').val();
				}else{
					newfolder = '';
				}
				
				if($j.trim(target).length < 1){
					alert('�������� �Է��ϼ���');
				}else{
					$j('#basketForm>input[name=act]').val('moveFolder');	
					$j('#basketForm>input[name=moveFolder]').val(target);	
					$j('#basketForm>input[name=newFolder]').val(newfolder);	
					
					$j('#basketForm').submit();
				}
	        },
        	"���": function() {
	          $j(this).dialog("close");
    	    }
      	},
		close:function(){
			selItem = null;
		}
    });
	
	$j('#moveFolder').on('submit',function(e){
		e.preventDefault();
	});
	
	
	$j('.moveFolderBtn').css('cursor','pointer').on('click',function(e){
		e.preventDefault();
		if($j('#basketForm').find('input:checkbox[name^=basket_select_item]:checked').length < 1){
			alert('���õ� �׸��� �����ϴ�.');
		}else{						
			$j('#moveFolderDialog').dialog('open');
		}
			
		
			
	});
	
	
	
	
	
	
	var $folderli = $j('.basketFolderTab>li');
	$folderli.css('cursor','pointer');
	$j('.basketFolderTab>li').on('click',function(e){			
		e.preventDefault();
		if(selItem == null){
			var bfidx = $j(this).attr('bfidx');
			if(!$j(this).hasClass('active')){
				document.location.replace('?sfld='+bfidx);
			}
		}
	});	
	
	$j('.basketFolderTab>li:gt(0)').on('mouseover',function(e){	
		$j(this).css('padding-right','90px');
		$j(this).find('.folderBtns').css('display','');
	});

	$j('.basketFolderTab>li:gt(0)').on('mouseleave',function(e){
		$j(this).find('.folderBtns').css('display','none');
		$j(this).css('padding-right','20px');
	});
	
	$j('.modifyFolderBtn').on('click',function(e){						
		e.preventDefault();
		selItem = $j(this).parent().parent();
		openFolderEditDialog($j(selItem).attr('bfidx'),$j(selItem).attr('bfname'));
	});
	
	$j('.deleteFolderBtn').on('click',function(e){
		e.preventDefault();		
		selItem = $j(this).parent().parent();
		if(confirm("���Ե� ��ǰ�� ���� �˴ϴ�. \r\n���� ���� �Ͻðڽ��ϱ�?")){
			$j('#editFolder').find('input[name=act]').val('delFolder');
			$j('#editFolder').find('input[name=bfidx]').val($j(selItem).attr('bfidx'));
			$j('#editFolder').submit();	
		}
	});

});
</script>

<? } ?>
<script language="javascript" type="text/javascript">
function deleteItems(){
	if($j('#basketForm').find('input:checkbox[name^=basket_select_item]:checked').length < 1){
		alert('���õ� �׸��� �����ϴ�.');
	}else if(confirm('�ش� �׸��� ���� ���� �Ͻðڽ��ϱ�?')){
		$j('#basketForm>input[name=act]').val('deleteItem');
		$j('#basketForm').submit();
	}
}

$j(function(){
	$j('input:checkbox.allSel').on('click',function(e){
		var checkBoxes = $j('input:checkbox[name^=basket_select_item]');			
		if($j(this).is(':checked')){			
			checkBoxes.prop("checked", true);
		}else{			
			checkBoxes.prop("checked",false);
		}
	});
	
	
	$j('input[name^=basket_select_item]').on('click',function(e){	
		if(!$j(this).is(':checked')){		
			$j('input:checkbox.allSel').prop("checked",false);
		}else if($j('input:checkbox[name^=basket_select_item]').length == $j('input:checkbox[name^=basket_select_item]:checked').length){
			$j('input:checkbox.allSel').prop("checked",true);
		}
	});
	
	$j('.deleteBtn').css('cursor','pointer').on('click',function(e){
		e.preventDefault();
		deleteItems();
	});
	
});
</script>
<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td><input type="checkbox" name="allSel" class="allSel" /></td>
		<td>��μ���</td>
		<td style="padding:0px 5px;"><img src="/images/common/basket/001/btn_seldel.gif" class="deleteBtn" alt="���� �׸� ����" /></td>
		<? if(!_empty($_ShopInfo->getMemid())){ ?>
		<td><img src="/images/common/basket/001/btn_movefolder.gif" class="moveFolderBtn" alt="�ٸ������� �̵�" /></td>
		<? }?>
	</tr>
</table>
<form name="basketForm" id="basketForm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="act" value="" />
<input type="hidden" name="ordertype" value="<?=$ordertype?>"/>
<input type="hidden" name="sfld" value="<?=$_REQUEST['sfld']?>"/>
<input type="hidden" name="moveFolder" value="" />
<input type="hidden" name="newFolder" value="" />
<input type="hidden" name="sbasketidx" value="" />
<input type="hidden" name="sbasketquantity" value="" />
<table border="0" cellpadding="0" cellspacing="0" width="100%" class="itemListTbl" align="center" style=" margin-top:10px;">
	<caption style="text-align:left; display:none;">��ٱ��� ����Ʈ</caption>	
	<thead>
		<tr>
			<th class="thstyle" style="text-align:center; width:30px;">&nbsp;</th>
			<th class="thstyle" style="text-align:center">��ǰ����</th>
			<th class="thstyle" style="text-align:center; width:80px;">����</th>
			<th class="thstyle" style="text-align:center; width:120px">�Ⱓ</th>
			<th class="thstyle" style="text-align:center; width:140px;">��ǰ�ݾ�</th>
			<th class="thstyle" style="text-align:center; width:120px;">���αݾ�</th>
			<th class="thstyle" style="text-align:center; width:120px;">��ۺ�</th>
			<th class="thstyle" style="text-align:center; width:100px;">�Ǹ��ڸ�</th>
			<th class="thstyle" style="text-align:center">�ֹ��ݾ�</th>
		</tr>
	</thead>

	<tbody>
	<?	
		$disctotal = $producttotalprice = $totaldeliprice = 0;
		if($basketItems['productcnt'] <1){ ?>
		<tr><td colspan="9" style="text-align:center; height:30px;">��ٱ��Ͽ� ��ϵ� ��ǰ�� �����ϴ�.</td></tr>
	<?	}else{
			$timgsize = 50;
			foreach($basketItems['vender'] as $vender=>$vendervalue){
				for( $i = 0 ; $i < count($vendervalue['products']) ; $i++ ){
					$product = $vendervalue['products'][$i];
					
					$disctotal += $product['group_discount']*$product['quantity'];
											
					$imageSize = ($product['tinyimage'][$product['tinyimage']['big']] > $timgsize)?$product['tinyimage']['big'].'="'.$timgsize.'"':'';
					$sellChk = ((_empty($product['sell_startdate']) && _empty($product['sell_enddate'])) || ($product['sell_startdate'] >=time() || time()>=$product['sell_enddate']));

					if ($product['deli_type'] == "�ù�") {
						// ��ۺ� ���� ���� ���� ���
						$venderDeliPrint = "";
						$venderDeliPrintCHK = false;
						if( strlen($vendervalue['deli_after']) == 0 AND $vendervalue['conf']['deli_mini'] < 1000000000 AND $vendervalue['delisumprice'] > 0 ) { // ������ �ƴҰ��
							$venderDeliPrint .= "<b>������ ����</b>". ( $vendervalue['conf']['groupDeli'] > 1 ? "(ȸ�� ��� ��ۺ� ��å ����)" : "" );

							if( $vendervalue['delisumprice'] >= $vendervalue['conf']['deli_mini'] ){
								$venderDeliPrint .= "<font color='#ff6600'><strong>[�����]</strong></font>";
								$venderDeliPrintCHK = true;
							}
							$venderDeliPrint .= "&nbsp;:&nbsp;���űݾ��� <b>".number_format($vendervalue['conf']['deli_mini'])."��</b> �̻��� ��� (������ۻ�ǰ ".( $vendervalue['conf']['deli_pricetype'] == "Y" ? "����" : "����" ).")";
						}

						// ��۷�
						$deliPrtChk="";
						$deliPrtRowspan = "";
						if($product['deli_price']>0){
							if($product['deli']=="Y"){
								$deliprice = $product['deli_price']*$product['quantity'];
							}else if($product['deli']=="N") {
								$deliprice = $product['deli_price'];
							}

							$delimsg = "����";
							if ($deliprice > 0) {
								$totaldeliprice += $deliprice;
								$delimsg = number_format($deliprice)."��";
							}
							$deliPrt = "������<br>(".$delimsg.")";
						}else if($product['deli']=="F" || $product['deli']=="G"){
							$deliPrt = ($product['deli']=="F"?'��������':'����');
						}else{
							$deliPrt  = "�⺻��ۺ�<br/>(";
							if ($vendervalue['conf']['deli_price'] > 0) {
								if ($vender == 0 && $venderDeliPrintCHK == true) {
									$deliPrt .= "����";
								} else {
									$totaldeliprice += $vendervalue['conf']['deli_price'];
									$deliPrt .= number_format($vendervalue['conf']['deli_price'])."��";
								}
							} else {
								$deliPrt .= "����";
							}
							$deliPrt .= ")";
							$deliPrtChk = $vender."D";
						}

						// ��ۺ� ���̺� ����
						if( strlen($deliPrtChk) > 0 ) {
							$deliPrtArr[$deliPrtChk]++;
							if( $deliPrtArr[$deliPrtChk] > 1 ) {
								$deliPrt = "";
							} else{
								$deliCount = $basketItems['vender'][$vender]['deliCount'][$product['deli']][($product['deli_price']>0?"1":"0")];
								if( $deliCount > 1 ) {
									$deliPrtRowspan = " rowspan = '".$deliCount."'";
								}
							}
						}
					} else {
						$deliPrt = $product['deli_type'];
					}

		?>		
		
		<? if($product['rental'] != '2'){ // �Ϲ� ��ǰ 
				$producttotalprice+= $product['sellprice']*$product['quantity'];
		?>
		<tr>
			<td class="tdstyle2" style="text-align:center"><input type="checkbox" name="basket_select_item[]" value="<?=$product['basketidx']?>" ></td>
			<td class="tdstyle" style="text-align:center">			
				<div style="float:left; width:55px;"><img src="<?=$product['tinyimage']['src']?>" <?=$imageSize?> /></div>
				<div style="float:left; margin-left:5px; text-align:left;">					
					<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?=rentalIcon($product['rental'])?><font color="#000000" style="font-size:12px;"><b><?=viewproductname($product['productname'],$product['etctype'],$product['selfcode'],$product['addcode'])?></b></font></a>

					<?=($sellChk)?"":"<font color=\"#FF0000\">[�Ǹ�����]</font>"?>
					<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?//=viewproductname($productname,$product['etctype'],$product['selfcode'],$product['addcode']) ?><?=$bankonly_html?><?=$setquota_html?><? //=$deli_str ?></font></a><br />				
					
					
					<?
					/*
					<? if ($_data->reserve_maxuse>=0) { ?>
						<img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_icon003.gif" border="0" vspace="1" style="position:relative; top:0.2em;"> <font color="#666666"><? echo number_format($product['reserve']) ?> ��</font><br />
					<? } else { ?>
						<font color="#444444">����</font><br />
					<? } ?>
*/ ?>
					<span style="font-size:11px;">
						<?							
							if($product['bankonly'] == 'Y'){ ?><img src="<?=$Dir?>images/common/bankonly.gif" border=0 align=absmiddle><? }// ���� ����							
							if($product['setquota'] == 'Y'){ ?><img src="<?=$Dir?>images/common/setquota.gif" border=0 align=absmiddle><? }// ������

							$sptxt = array();
							if($product['cateAuth']['coupon'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon002x.gif hspace=1 alt=�������� ����Ұ� />';
							if($product['cateAuth']['reserve'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon001x.gif alt=������ ���Ұ� />';
							if($product['cateAuth']['gift'] == 'Y' && checkGiftSet()) $sptxt[] ='<IMG SRC=/images/common/basket/001/basket_spe_icon004o.gif alt=����ǰ ����Ұ� />';
							if($product['cateAuth']['refund'] == 'N')	$sptxt[] = '<img src=/images/common/basket/001/basket_spe_icon003x.gif hspace=1 alt=��ȯ/��ǰ �Ұ� />';
							if(_array($sptxt)) echo implode(' ',$sptxt)."<br />";
						?>
					</span>
					<span>
						<?
							//�ɼ� 1
							if (_array($product['option1'])) {
								echo "<img src=\"".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_icon002.gif\" border=\"0\" align=\"absmiddle\">";
								$tok = $product['option1'];
								$count=count($tok);
								echo "&nbsp; ".$tok[0]." ";
								echo "<select name=option1 size=1 onchange=\"CheckForm('upd','".$product['basketidx']."')\">\n";
								for($o1=1;$o1<$count;$o1++){
									if(strlen($tok[$o1])>0){
										$sel = ($o1==$product['opt1_idx']) ? " selected" : "";
										echo "<option value=\"".$o1."\" ".$sel.">".$tok[$o1]."</option>";
									}
								}
								echo "</select>";
							}
							// �ɼ� 2
							if (_array($product['option2'])) {
								$tok = $product['option2'];
								$count=count($tok);
								echo "&nbsp; ".$tok[0]." ";
								echo "<select name=option2 size=1 onchange=\"CheckForm('upd','".$product['basketidx']."')\">\n";
								for($o2=1;$o2<$count;$o2++){
									if(strlen($tok[$o2])>0){
										$sel = ($o2==$product['opt2_idx']) ? " selected" : "";
										echo "<option value=\"".$o2."\" ".$sel.">".$tok[$o2]."</option>";
									}
								}
								echo "</select>";
							}
						?>
					</span>
				</div>
			</td>
			<td class="tdstyle" align="center">
		<?		if ($sellChk) {	?>
					<input type=text name="quantity[<?=$product['basketidx']?>]" value="<?= $product['quantity'] ?>" maxlength="4" onkeyup="strnumkeyup(this)" style="text-align:center; background-color:#f5f5f5; color:#999999; WIDTH:27px; height:17px; border:1px solid #ccc;"><br />
					<a href="javascript:CheckForm('upd','<?=$product['basketidx']?>')"><IMG sRC="<?= $Dir ?>upload/img/icon/edit.gif" border="0" alt="����"></a>
		<?		} else { ?>
				<input type=text name="quantity[<?=$product['basketidx']?>]" value="<?= $product['quantity'] ?>" size="3" maxlength="4" readonly style="WIDTH:30px;BORDER:#DFDFDF 1px solid;HEIGHT:18px;BACKGROUND-COLOR:#F7F7F7;padding-top:2pt;padding-bottom:1pt;height:19px">
	<?			}	?>
			</td>
			<td class="tdstyle" align="center">Buying</td>
			<td class="tdstyle" align="center"><b><?=number_format($product['sellprice'])?>��</b></td>
			<td class="tdstyle" align="center"><?=!empty($product['group_discount'])?number_format($product['group_discount']).'��':'&nbsp;'?></td>
	<?		if(!_empty($deliPrt)){ ?>
			<td class="tdstyle" align="center" <?=$deliPrtRowspan?>><?=$deliPrt?></td>
	<?		} ?>
			<td class="tdstyle" style="text-align:center"><?=$basketItems['vender'][$vender]['conf']['com_name']?></td>
			<td class="tdstyle2" align="center">
				<font color="#666666"><?=number_format($product['realprice'])?> ��</font><br />
				<? /*
					if($sellChk){
						if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
							echo "<a href=\"javascript:go_wishlist('".($formcount)."');\"><IMG SRC=\"".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_btn3.gif\" border=\"0\" alt=\"���ϱ�\"></a><br />";
						} else {
							echo "<a href=\"javascript:check_login();\"><IMG SRC=\"".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_btn3.gif\" border=\"0\" alt=\"���ϱ�\"></a><br />";
						}
					} */
				?>
				<a href="javascript:CheckForm('del',<?=$product['basketidx']?>)"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn4.gif" border="0" vspace="3" alt="����"></a>
			</td>
		</tr>
		<?
		}else{ // ��Ż��ǰ 			
			$tmpPinfo = rentProduct::read($product['pridx']);				
			//foreach($product['items'] as $rentItem){				
				$rentItem = $product['rentinfo'];
				$roptinfo = &$tmpPinfo['options'][$rentItem['optidx']];
				$sellprice = $rentItem['solvprice']['totalprice'] /$rentItem['quantity'];
				$producttotalprice+= $rentItem['solvprice']['totalprice'];
				$disctotal += abs($rentItem['solvprice']['discprice']);
		?>
		<tr>
			<td class="tdstyle2" style="text-align:center">
				<? if($sellprice > 0){ ?>
			<input type="checkbox" isrental="1" name="basket_select_item[]" value="<?=$product['basketidx']?>" >
				<? }else{ ?>
					�Ұ�
				<? } ?>
			</td>
			
				<td class="tdstyle" style="text-align:center">			
					<div style="float:left; width:55px;"><img src="<?=$product['tinyimage']['src']?>" <?=$imageSize?> /></div>
					<div style="float:left; margin-left:5px; text-align:left;">					
						<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?=rentalIcon($product['rental'])?><font color="#000000" style="font-size:12px;"><b><?=viewproductname($product['productname'],$product['etctype'],$product['selfcode'],$product['addcode'])?></b></font></a>
						<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?//=viewproductname($productname,$product['etctype'],$product['selfcode'],$product['addcode']) ?><?=$bankonly_html?><?=$setquota_html?><? //=$deli_str ?></font></a>
						<? if(!_empty($rentItem['reservationCode'])){ 
								$remaintime = time()-strtotime(substr($rentItem['reservationCode'],0,12));
								$rtmp = solvTimestamp($remaintime);
								$hour = 24-$rtmp['hour']-($rtmp['minute']>0?1:0);
								$remainstr = (($hour>0)?$hour.'�ð�':'').(($rtmp['minute']>0)?'&nbsp;'.$rtmp['minute'].'��':'');
								//$tmpremain = solvTimestamp($remaintime);
						?>
						<span style=" color:red">[������ <?=$remainstr.' ����'?>]</span>
						<? } ?><br />
						<span>
							<?=$roptinfo['optionName']?>
						</span>
						
						<span style="font-size:11px;">
							<?							
								if($product['bankonly'] == 'Y'){ ?><img src="<?=$Dir?>images/common/bankonly.gif" border=0 align=absmiddle><? }// ���� ����							
								if($product['setquota'] == 'Y'){ ?><img src="<?=$Dir?>images/common/setquota.gif" border=0 align=absmiddle><? }// ������
	
								$sptxt = array();
								if($product['cateAuth']['coupon'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon002x.gif hspace=1 alt=�������� ����Ұ� />';
								if($product['cateAuth']['reserve'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon001x.gif alt=������ ���Ұ� />';
								if($product['cateAuth']['gift'] == 'Y' && checkGiftSet()) $sptxt[] ='<IMG SRC=/images/common/basket/001/basket_spe_icon004o.gif alt=����ǰ ����Ұ� />';
								if($product['cateAuth']['refund'] == 'N')	$sptxt[] = '<img src=/images/common/basket/001/basket_spe_icon003x.gif hspace=1 alt=��ȯ/��ǰ �Ұ� />';
								if(_array($sptxt)) echo implode(' ',$sptxt)."<br />";
							?>
						</span>
						
					</div>
				</td>
				<td class="tdstyle" align="center">		
					<input type=text name="quantity[<?=$product['basketidx']?>]" value="<?=$rentItem['quantity'] ?>" maxlength="4" onkeyup="strnumkeyup(this)" style="text-align:center; background-color:#f5f5f5; color:#999999; WIDTH:27px; height:17px; border:1px solid #ccc;"><br />
					<a href="javascript:CheckForm('upd','<?=$product['basketidx']?>')"><IMG SRC="<?= $Dir ?>upload/img/icon/edit.gif" border="0" alt="����"></a>
				</td>
				<td class="tdstyle" align="center">
				<?
					if($sellprice > 0){ 				
						 if($rentItem['solvprice']['timegap'] == '1') echo date('Y-m-d H',$rentItem['solvprice']['range'][0]).'<br>'.date('Y-m-d H',$rentItem['solvprice']['range'][1]+1);
						 else echo date('Y-m-d',$rentItem['solvprice']['range'][0]).'<br>'.date('Y-m-d',$rentItem['solvprice']['range'][1]);
					}else{ 
						  if($rentItem['solvprice']['timegap'] == '1') echo substr($rentItem['start'],0,13).'<br>'.substr($rentItem['end'],0,13);						  
						  else echo substr($rentItem['start'],0,10).'<br>'.substr($rentItem['end'],0,10);
				  } ?>
					<br /><a href="javascript:RangeChange('<?=$product['basketidx']?>','<?=$rentItem['quantity']?>')"><IMG SRC="<?= $Dir ?>upload/img/icon/edit.gif" border="0" alt="����"></a>
				</td>
				<td class="tdstyle" align="center"><b><?=number_format($sellprice)?>��</b></td>
				<td class="tdstyle" align="center"><?=number_format(abs($rentItem['solvprice']['discprice'])).'��'?></td>
				<td class="tdstyle" align="center">
			<? if ($deliPrt) { echo $deliPrt; } else { echo "&nbsp;"; }?>
				</td>
				<td class="tdstyle" style="text-align:center"><?=$basketItems['vender'][$vender]['conf']['com_name']?></td>
				<td class="tdstyle2" style="text-align:center">
					<? echo number_format($product['realprice']); //number_format($rentItem['solvprice']['totalprice'])?>��<br />
					<? /*
						if($sellChk){
							if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
								echo "<a href=\"javascript:go_wishlist('".($formcount)."');\"><IMG SRC=\"".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_btn3.gif\" border=\"0\" alt=\"���ϱ�\"></a><br />";
							} else {
								echo "<a href=\"javascript:check_login();\"><IMG SRC=\"".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_btn3.gif\" border=\"0\" alt=\"���ϱ�\"></a><br />";
							}
						} */
					?>
					<a href="javascript:CheckForm('del','<?=$product['basketidx']?>')"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn4.gif" border="0" vspace="3" alt="����"></a>
				</td>
			</tr>	
<?			//	} // end rental foreach
			}
	}// end for
		?>
		<? /*
		<tr>
			<td colspan="9" bgcolor="#f9f9f9" style="padding:15px 10px; text-align:right;">
				<div style="font-size:11px; margin-bottom:5px;">
				<?=$venderDeliPrint?>
				</div>
				��ۺ� : <b><?=number_format($vendervalue['deliprice'])?></b>�� / <b>�հ� : </b><span style="color:#ff6600; font-size:15px; font-family:tahoma; font-weight:bold;"><?=number_format($vendervalue['sumprice'])?>��</span>
			</td>
		</tr>
		<tr><td colspan=9 height=1 bgcolor="#DDDDDD"></td></tr> */ ?>
	<?
				} // end foreach
			} // end if
	?>
	</tbody>
	<tfoot>
		<tr><td colspan=9 height=1 bgcolor="#DDDDDD"></td></tr>
		<tr>
			<td colspan="9"  style="padding:10px 0px;">
				<? /*
				<div style="float:left;">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="padding-left:10px;"><img src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_icontitle.gif" alt="" /></td>
							<td style="padding-left:5px;"><img style="cursor:pointer;" src="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_icon004.gif" onclick="javascript:CheckForm('del','sel')" alt="�����ϱ�" /></a></td>
						</tr>
					</table>
				</div>
*/?>

				<?
					if ($totaldeliprice > 0) {
						$disp_sumprice = number_format($totaldeliprice + $basketItems['sumprice']).'��';
						$disp_deliprice = '(+)'.number_format($totaldeliprice);
					} else {
						$disp_sumprice = number_format($basketItems['sumprice']).'��';
						if ($deliPrt) {
							$disp_deliprice = '����';
						} else {
							$disp_deliprice = 0;
						}
					}
				?>
				<div style="float:right;">
					<div style="font-size:11px; color:#777777; text-align:right; letter-spacing:-0.5pt; margin-bottom:5px; padding-right:10px; display:none"><?=$groupMemberSale?></div>										
					<table border="0" cellpadding="0" cellspacing="0" align="right">
						<tr>
							<td style="width:120px;">�� ��ǰ�ݾ�</td>
							<td style="text-align:right"><?=number_format($producttotalprice)?></td>
						</tr>
						<tr>
							<td>��ۺ�</td>
							<td style="text-align:right"><?=$disp_deliprice?></td>
						</tr>
						<tr>
							<td>�� ���αݾ�</td>
							<td style="text-align:right">(-)<?=number_format($disctotal)?></td>
						</tr>
						<tr>
							<td colspan="2" style="height:20px;"></td>
						</tr>
						<tr>
							<td>�����ݾ�</td>
							<td style="text-align:right"><span class="basket_etc_price3" style="font-weight:bold"><?=$disp_sumprice?></span></td>
						</tr>
						<tr>
							<td colspan="2" width="10"></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr><td colspan=9 height=1 bgcolor="#DDDDDD"></td></tr>
	</tfoot>
</table>
</form>
	</div>
</div>
<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>

<script language="javascript" type="text/javascript">
function checkRequest(){
	if($j('#basketForm').find('input[name^=basket_select_item][isrental=1]:checked').length <1){
		alert('���õ� Rent ��ǰ�� �����ϴ�.');
	}else{
		var oact = $j('#basketForm').find('input[name=act]').val();
		$j('#basketForm').find('input[name=act]').val('checkRequest');
		var param = $j('#basketForm').serializeArray();
		$j('#basketForm').find('input[name=act]').val(oact);
		
		$j.post('/front/proc/basket.php',param,function(data){
			if(data.err != 'ok'){
				alert(data.err);
			}else{
				$j( "#checkRequest" ).dialog('open');		
			}
		},'json');
	}
	
}

function preReserv(){
	if($j('#basketForm').find('input[name^=basket_select_item][isrental=1]:checked').length <1){
		alert('���õ� Rent ��ǰ�� �����ϴ�.');
	}else{
		var oact = $j('#basketForm').find('input[name=act]').val();
		$j('#basketForm').find('input[name=act]').val('reservation');
		var param = $j('#basketForm').serializeArray();
		$j('#basketForm').find('input[name=act]').val(oact);
		
		$j.post('/front/proc/basket.php',param,function(data){
			if(data.err != 'ok'){
				alert(data.err);
			}else{
				$j('#checkRequest').find('#checkReserv').css('display','none');
				$j('#checkRequest').find('#reservOk').find('#limitDate').html(data.limitstr);
				$j('#checkRequest').find('#reservOk').css('display','');
//				$j( "#checkRequest" ).dialog('close');		
			}
		},'json');
	}
}

$j(function(){
    $j( "#checkRequest" ).dialog({
	 autoOpen:false,
      modal: true,
      buttons: {
      
      }
    });
});
</script>
<style type="text/css">
.ui-widget-header{ background:#02B0dd;}
</style>
<div id="checkRequest" title="üũ �� ����">
	<div style="line-height:150%" id="checkReserv">
		��ǰ ���� ���� Ȯ�� �� ���� �帮�ڽ��ϴ�.<br />
		����(24�ð� ��ȿ)�� �̸� �ϼŵ� �˴ϴ�.<br />
		�����Ͻðڽ��ϱ�?
		<div style="text-align:center; margin-top:15px;">
			<img src="/upload/img/btn_confirm.gif" alt="Ȯ��" onclick="javascript:preReserv()" style="cursor:pointer" />
			<img src="/upload/img/btn_cancel.gif" alt="���" onclick="javascript:$j('#checkRequest').dialog('close');" style="cursor:pointer" />
		</div>
	</div>
	<div style="line-height:150%; display:none" id="reservOk">
		����Ǿ����ϴ�.<br />
		<span style="color:#02b0dd" id="limitDate"></span>�̰����� ������<br />
		�ڵ���ҵɼ� �ֽ��ϴ�.
		<div style="text-align:center; margin-top:15px;">
			<img src="/upload/img/btn_confirm.gif" alt="Ȯ��" onclick="javascript:$j('#checkRequest').dialog('close');" style="cursor:pointer" />
		</div>
	</div>
</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top:40px;">
	<tr>
		<td align="center">

			<?
				if(strlen($code)>0) {
					if($brandcode>0) {
						$shopping_url=$Dir.FrontDir."productblist.php?code=".substr($code,0,12)."&brandcode=".$brandcode;
					} else {
						$shopping_url=$Dir.FrontDir."productlist.php?code=".substr($code,0,12);
					}
				} else {
					$shopping_url=$Dir.MainDir."main.php";
				}
			?>
			<!--<A HREF="javascript:estimatePop();"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn8.gif" border="0" hspace="2" alt="������ ����"></a>
			<A HREF="javascript:basket_clear()"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn6.gif" border="0" hspace="2" alt="��ٱ��� ����"></a>-->
			<A HREF="javascript:checkRequest();"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn9.gif" border="0" hspace="2" alt="üũ �� ����"></a>
			<? if($ordertype != 'recommand'){ ?>
			<A HREF="javascript:recommandOrder()"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/btn_recom.gif" border="0" hspace="2" alt="Ÿȸ������ ��õ"></a>
			<? } ?>
			<?
				if ($basketItems['sumprice']>=$_data->bank_miniprice) {
					if( $ordertype == "pester" ) {
						if (strlen($_ShopInfo->getMemid())>0 && $_ShopInfo->getMemid()!="deleted") {
							echo "<a href=\"javascript:chkPester();\"><img src=".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_icon005.gif border=\"0\" hspace=\"2\" alt=\"������\" /></a>";
						} else {
							echo "<a href=\"javascript:check_login();\"><img src=".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_icon005.gif border=\"0\" hspace=\"2\" alt=\"������\" /></a>";
						}
					}

					if( $ordertype == "present" ) {
						echo "<A HREF=\"#none\"><img src=\"".$Dir."images/common/basket/".$_data->design_basket."/basket_skin3_icon006.gif\" border=\"0\" onclick=\"chkPresent()\" hspace=\"2\" alt=\"�����ϱ�\" /></a>";
					}
				}

				if ($basketItems['sumprice']>=$_data->bank_miniprice ) {
					if( $ordertype == "" ) {
			?>
				<script language="javascript" type="text/javascript">
				function CheckOrder() {
					if($j('#basketForm').find('input:checkbox[name^=basket_select_item]:checked').length < 1){
						alert('���õ� �׸��� �����ϴ�.');
					}else{					
						$j('#basketForm').attr('action','/front/proc/basket.php');
						$j('#basketForm>input[name=act]').val('basketToOrder');
						$j('#basketForm').submit();
					}
				}				
				</script>
				<? /*
				<A HREF="<?=$Dir.FrontDir?>login.php?chUrl=<?=urlencode($Dir.FrontDir."order.php")?>"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn7.gif" border="0" hspace="2" alt="��ǰ�ֹ��ϱ�"></a>
				<A HREF="javascript:CheckOrder()"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn7.gif" border="0" hspace="2" alt="��ǰ�ֹ��ϱ�"></a>*/ ?>
			<?
					}
				} else {
			?>
				<br><font color="#FF3300"><b>�ֹ������� �ּ� �ݾ��� <?=number_format($_data->bank_miniprice)?>�� �Դϴ�.</b></font>
			<?
				}
			?>
			<A HREF="<?=$shopping_url?>"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn5.gif" border="0" hspace="2" alt="���ΰ���ϱ�"></a>
		</td>
	</tr>
</table>
<script language="javascript" type="text/javascript">
function fixrenttime(){
		if($j("#p_bookingEndDate").val() == $j("#p_bookingStartDate").val()){
		if($j('#endTime').val() <24){
			alert('���� �ð��� �ּ� 24 �ð� �Դϴ�.');
			$j('#endTime').find('option[value=11]').attr('selected',true);
			return false;
		}
	}
		return true;
}

function RangeChange(bidx,quantity){
	$j('#rentSchdulePop').html('');
	$j.get('/ajaxback/rent.php',{'act':'basketRangeForm','ordertype':'<?=$ordertype?>','sfld':'<?=$_REQUEST['sfld']?>','quantity':quantity,'basketidx':bidx},function(data){
		if(data.err == 'ok'){
			$j('#rentSchdulePop').html(data.html);
			
			if($j("#p_bookingStartDate" )){
				$j("#p_bookingStartDate" ).datepicker({
				  showOn: "both",
				  dateFormat:'yy-mm-dd',
				  buttonImage: "/images/mini_cal_calen.gif",
				  minDate: 1,
				  buttonImageOnly: true,
				  buttonText: "�����",				  
				  onSelect: function( selectedDate ) {
					$j("#p_bookingEndDate" ).datepicker( "option", "minDate", selectedDate );
					fixrenttime();
				  }
				});
		
				$j("#p_bookingEndDate" ).datepicker({
				  showOn: "both",
				  dateFormat:'yy-mm-dd',
				  buttonImage: "/images/mini_cal_calen.gif",
				  minDate: 1,
				  buttonImageOnly: true,
				  buttonText: "�ݳ���",
				  onSelect: function( selectedDate ) {
					$j( "#p_bookingStartDate" ).datepicker( "option", "maxDate", selectedDate );
					fixrenttime();
				  }
				});	
			}
	
			$j('#rentSchdulePop').dialog('open');
			
			
		}else{
			alert(data.err);
		}
	},'json');
}

$j(function(){
	$j('#rentSchdulePop').dialog(
	{'autoOpen':false,
	resizable: false,
	modal: true,
    buttons: {
        "����": function() {
         $j(this).find('form').submit();//dialog( "close" );
    },
       "�ݱ�": function() {
          $j( this ).dialog( "close" );
        }
      }
	}
	);
});
</script>
<div id="rentSchdulePop" title="�Ⱓ����">

</div>

