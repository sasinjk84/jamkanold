<div style="clear:both;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
<div style="padding:10px 30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;overflow:hidden;">
	<div style="float:left;padding-left:50px;height:35px;line-height:35px;background:url('/data/design/img/sub/icon_basket.gif') no-repeat;color:#696969;font-size:25px;font-weight:600;">��ٱ���</div>
	<div style="float:right;margin-top:3px;"><img src="/data/design/img/sub/step_basket.gif" alt="" /></div>
	<div style="clear:both;"></div>
</div>
<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>
<!--
<div style="clear:both;margin-top:20px;height:6px;background:url('/data/design/img/main/top_boxline.gif') no-repeat;font-size:0px;"></div>
<div style="padding:30px;background:#ffffff url('/data/design/img/main/bg_boxline.gif') repeat-y;text-align:center;overflow:hidden;">
-->
	<div style="width:100%;margin:10px auto;">
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
	.linkButtonStyle{height:22px;padding:0px 8px;line-height:22px;border:1px solid #dddddd;}
	.grayButton{background:#666666;}
</style>

<!-- 
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
-->


<!--
<script language="javascript" type="text/javascript">
	function checkMoveFolder(el){
		if($j(el).val() == '0'){
			$j(el).parent().find('input[name=newFoldername]').css('display','none');
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
	<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th style="width:80px;">�������</th>
			<td>
				<select name="targetFolder" onchange="checkMoveFolder(this)" style="width:170px;">
					<option value="">��ü</option>
					<option value="0">�⺻����</option>
					<? foreach($folders as $bfidx=>$bfname){ ?>
					<option value="<?=$bfidx?>"><?=$bfname?></option>
					<? }?>
				</select>
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
<? } ?>
-->
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

	$j('#moveFolderBtn').css('cursor','pointer').on('click',function(e){
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
	$j('#allSel').on('click',function(e){
		var checkBoxes = $j('input:checkbox[name^=basket_select_item]');
		if($j(this).click('') && $j(this).data('value') == 'N'){
			checkBoxes.prop("checked", true);
			$j(this).data('value', 'Y');
		}else{			
			checkBoxes.prop("checked",false);
			$j(this).data('value', 'N');
		}
	});
	
	
	$j('input[name^=basket_select_item]').on('click',function(e){	
		if(!$j(this).is(':checked')){		
			$j('input:checkbox.allSel').prop("checked",false);
		}else if($j('input:checkbox[name^=basket_select_item]').length == $j('input:checkbox[name^=basket_select_item]:checked').length){
			$j('input:checkbox.allSel').prop("checked",true);
		}
		
		var totalprice = 0;totaldeliprice = 0;totaldiscprice = 0;
		$j('#basketForm').find('input[name^=basket_select_item]:checked').each(function(idx,item) {
			bidx = $j(item).val();
			totalprice += parseInt($j("#totalprice_"+bidx).val());
			totaldeliprice += parseInt($j("#totaldeliprice_"+bidx).val());
			totaldiscprice += parseInt($j("#totaldiscprice_"+bidx).val());
		});

		$j("#totalprice_str").html(number_format(totalprice));
		if(totaldeliprice>0){
			$j("#totaldeliprice_str").html("(+) "+number_format(totaldeliprice));
		}else{
			$j("#totaldeliprice_str").html(number_format(totaldeliprice));
		}
		$j("#totaldiscprice_str").html(number_format(totaldiscprice));
		$j("#sumprice_str").html(number_format(totalprice+totaldeliprice+totaldiscprice)+"��");
	});
	
	$j('#deleteBtn').css('cursor','pointer').on('click',function(e){
		e.preventDefault();
		deleteItems();
	});
	
});
</script>
<form name="basketform1" method="post" action="<?=$_SERVER[PHP_SELF]?>">
<input type="hidden" name="mode"/>
<input type="hidden" name="bfidx" value=''>
<input type="hidden" name="flag" value="1">
<input type="hidden" name="act" value="modifyFoldername">

<style>
	.linkButtonStyle{height:22px;padding:0px 8px;line-height:22px;border:1px solid #dddddd;}
	.grayButton{background:#666666;}
</style>
<div class="orderStateWrap">
<div style="padding:15px 0px;">
	<? $all_fd_style = $wishCate=='A' && $sfld=="" ? 'style="font-weight:bold;text-decoration:underline"':''; ?>
	<a href='?cate=A' <?=$all_fd_style?>>��ü����</a>
	<?
	//echo ( $wishCate=='A' && $sfld=="" ?'��':'');
	$basic_fd_style = $sfld=='0'? 'style="font-weight:bold;text-decoration:underline"':'';
	echo "<span style=\"padding:0px 10px;color:#dddddd;font-size:10px;\">|</span><a href='?sfld=0' ".$basic_fd_style.">�⺻����</a>";
	//echo ($sfld=="0"?'��':'');

	foreach ($folders as $bfidx=>$bfname) {	
		$fd_style = $wishCate==$bfidx? 'style="font-weight:bold;text-decoration:underline"':'';
		echo "<span style=\"padding:0px 10px;color:#dddddd;font-size:10px;\">|</span><a href='?sfld=".$bfidx."' ".$fd_style.">".$bfname."</a>";
		//echo ($wishCate==$bfidx?'��':'');
	}
	?>
	<a href="javascript:wishCateViewOnOff(cateSetDiv);"  class="btn_gray" style="margin-left:10px;"><span>������ �߰� +</span></a>

<!--

	<a href="javascript:wishCateViewOnOff(cateSetDiv);" class="linkButtonStyle grayButton">
		<span style="color:#ffffff;font-weight:600;letter-spacing:-1px;">���� �����ϱ�</span>
	</a>
	<a href='?sfld='><span style="padding:0px 15px;<?=($wishCate == 'A' ? 'color:#000000;font-weight:bold;' : '')?>">��ü����</span></a>
	<?
		//echo ($wishCate == 'A' ? '��' : '');
		foreach ($folders as $bfidx=>$bfname) {
			echo "<span style=\"padding:0px 10px;color:#dddddd;font-size:10px;\">|</span><a href='?sfld=".$bfidx."'><span style=\"padding:0px 15px;".($wishCate == $bfidx ? 'color:#ff4400;font-weight:bold;' : '')."\">".$bfname."</span></a>";
			//echo ($wishCate == $bfidx ? '��' : '');
		}
	?>
	-->
	<div id="cateSetDiv" style="display:none;margin:10px 0px;padding:20px;background:#f8f8f8;border:1px solid #f2f2f2;">
		<h2 style="font-size:20px;color:#333333;padding-bottom:15px;">��ٱ��� ���� ����</h2>
		<p><input type="text" name="newFoldername" class="input"/> <input type="button" name="btn_insert" id="btn_insert" value="���� ����" onclick="basketFolderInsert(this.form);" class="btn_line"><input type="button" name="btn_modify" id="btn_modify" style="display:none" value="���� ����" onclick="basketFolderModify(this.form);" class="btn_line"></p>
		<p>
		<?
			foreach ($folders as $bfidx=>$bfname) {
				echo "<span style=\"padding:10px 20px 10px 0px;\">".$bfname." <input type='button' value='����' onclick=\"wishCateModifyOpen('".$bfname."','".$bfidx."');\" class=\"btn_sline3\"> <input type='button' value='����' onclick=\"basketCateDelete(this.form, '".$bfidx."');\"  class=\"btn_sline3\"></span>";
			}
		?>
		</p>
	</div>

</div>
</div>

<script language="javascript" type="text/javascript">
function wishCateModifyOpen(title,idx){
	var btn_insert = document.getElementById('btn_insert');
	var btn_modify = document.getElementById('btn_modify');
	btn_insert.style.display = ( btn_insert.style.display == 'none' ) ? 'inline-block' : 'none';
	btn_modify.style.display = ( btn_modify.style.display == 'none' ) ? 'inline-block' : 'none';
	document.basketform1.newFoldername.value = title;
	document.basketform1.bfidx.value = idx;				
}

function moveWishItems(){
	<? if(count($folders) <1){ ?>
	alert('���� ������ �������ּ���');
	<? }else{ ?>
	$j("#cateMultiSelDiv").show();
	<? } ?>
}
</script>
<style>.linkButtonStyle{height:22px;padding:0px 7px;line-height:22px;border:1px solid #dddddd;}</style>
<div style="margin-top:20px;margin-bottom:20px;">
	<div style="float:left;">
			<a href="#" id="allSel" data-value="N"  class="btn_sline2" style="display:inline-block;padding:0px 12px;box-sizing:border-box;line-height:32px;">��ü����</a>
			<a href="#" id="deleteBtn"  class="btn_sline2" style="display:inline-block;padding:0px 12px;box-sizing:border-box;line-height:32px;">����</a>
			<a href="javascript:moveWishItems()" id="_moveFolderBtn_old"  class="btn_sline2" style="display:inline-block;padding:0px 12px;box-sizing:border-box;line-height:32px;">�̵�</a>
			<!-- <a href="javascript:GoDelete();" class="linkButtonStyle">������ ��ǰ ����</a> -->
			<!-- <a href="javascript:moveWishItems()" class="linkButtonStyle">������ ��ǰ �����̵�</a> -->
			<? /*
			<a href="javascript:wishToBasketMove('pester');" class="linkButtonStyle">������ ��ǰ ������ ����</a>
			<a href="javascript:wishToBasketMove('present');" class="linkButtonStyle">������ ��ǰ �����ϱ� ����</a>
			*/ ?>
			<span id="cateMultiSelDiv" style="display:none;width:500px;background:#f2f2f2;" class="orderStateWrap1">
				�ٸ������� ��ǰ�̵�
				<select name="targetFolder" class="select">
					<option value=''>��ü</option>
					<option value='0'>�⺻����</option>
					<?
					foreach ($folders as $bfidx=>$bfname) {
						echo "<option value='".$bfidx."'>".$bfname."</option>";
					}
					?>
				</select>
				<input type="button" value="Ȯ��" onclick="basketCateMove(this.form);">
				<input type="button" value="���" onclick="wishCateViewOnOff(cateMultiSelDiv);">
			</span>
	</div>
</div>
</form>



<div style="clear:both;margin-bottom:20px;"></div>

<form name="basketForm" id="basketForm" action="<?=$_SERVER['PHP_SELF']?>" method="post">
<input type="hidden" name="act" value="" />
<input type="hidden" name="ordertype" value="<?=$ordertype?>"/>
<input type="hidden" name="sfld" value="<?=$_REQUEST['sfld']?>"/>
<input type="hidden" name="moveFolder" value="" />
<input type="hidden" name="newFolder" value="" />
<input type="hidden" name="newFoldername" value="" />
<input type="hidden" name="sbasketidx" value="" />
<input type="hidden" name="sbasketquantity" value="" />
<input type="hidden" name="sbasketdelitype" value="" />

<table border="0" cellpadding="0" cellspacing="0" width="100%" class="orderlistTbl">
	<caption style="text-align:left; display:none;">��ٱ��� ����Ʈ</caption>	
	<thead>
		<tr>
			<th style="text-align:center; width:30px;">&nbsp;</th>
			<th style="text-align:center">��ǰ����</th>
			<th style="text-align:center; width:70px;">�ܰ�</th>
			<th style="text-align:center; width:70px;">����</th>
			<th style="text-align:center; width:80px;">��ǰ�ݾ�</th>
			<th style="text-align:center; width:80px">�Ⱓ</th>
			<th style="text-align:center; width:80px;">���αݾ�</th>
			<th style="text-align:center; width:80px;">������</th>
			<th style="text-align:center; width:100px;">���</th>
			<th style="text-align:center; width:100px;">�Ǹ��ڸ�</th>
			<th style="text-align:center; width:100px;">�ֹ��ݾ�</th>
		</tr>
	</thead>

	<tbody>
	<?	
		$disctotal = $producttotalprice = $totaldeliprice = 0;
		if($basketItems['productcnt'] <1){ ?>
		<tr><td colspan="11" style="text-align:center; height:30px;">��ٱ��Ͽ� ��ϵ� ��ǰ�� �����ϴ�.</td></tr>
	<?	}else{
			$timgsize = 50;
			$k=0;$range_diff=0;
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
							
							if($deliprice > $vendervalue['conf']['deli_mini']){
								$deliprice = 0;
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
							if($vendervalue['sumprice']>=$vendervalue['conf']['deli_mini']){
								$vendervalue['conf']['deli_price']=0;
							}
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
								}else{
									$deliPrtRowspan = "";
								}
							}
						}else{
							$deliPrtRowspan = "";
						}

					} else {
						$deliPrt = "";

						$deliCount = $basketItems['vender'][$vender]['deliCount'][$product['deli']]['1'];

						if( $deliCount > 1 ) {
							$deliPrtRowspan = " rowspan = '".$deliCount."'";
						}else{
							$deliPrtRowspan = "";
						}

					}

		?>		
		
		<? if($product['rental'] != '2'){ // �Ϲ� ��ǰ 
			$sell_prd = 1;
			$producttotalprice+= $product['sellprice']*$product['quantity'];

			//������
			$mem_reserve = getProductReserve($product['productcode']);
			$reserve_total += $product['sellprice']*$mem_reserve;

		?>
		<tr>
			<td class="tdstyle2" style="text-align:center"><input type="checkbox" name="basket_select_item[]" value="<?=$product['basketidx']?>" class="checkbox" ></td>
			<td class="tdstyle" style="text-align:center">	
			
				<div style="float:left; width:90px;"><img src="<?=$product['tinyimage']['src']?>" style="width:90px;" /></div>
<!--				<div style="float:left; width:55px;"><img src="<?=$product['tinyimage']['src']?>" <?=$imageSize?> /></div>-->
				<div style="float:left; width:200px; margin-left:5px; text-align:left;">
					<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?=rentalIcon($product['rental'])?><span style="color:#000;font-size:12px;font-weight:bold;"><?=viewproductname($product['productname'],$product['etctype'],$product['selfcode'],"")?></span></a>

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
					<div style="font-size:11px;">
						<?=$product['prmsg']? $product['prmsg']."<br>":"";?>
						<?							
							if($product['bankonly'] == 'Y'){ ?><img src="<?=$Dir?>images/common/bankonly.gif" border=0 align=absmiddle><? }// ���� ����
							if($product['setquota'] == 'Y'){ ?><img src="<?=$Dir?>images/common/setquota.gif" border=0 align=absmiddle><? }// ������

							$sptxt = array();
							if($product['cateAuth']['coupon'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon002x.gif hspace=1 alt=�������� ����Ұ� />';
							if($product['cateAuth']['reserve'] == 'N')	$sptxt[] = '<IMG SRC=/images/common/basket/001/basket_spe_icon001x.gif alt=������ ���Ұ� />';
							if($product['cateAuth']['gift'] == 'Y' && checkGiftSet()) $sptxt[] ='<IMG SRC=/images/common/basket/001/basket_spe_icon004o.gif alt=����ǰ ����Ұ� />';
							if($product['cateAuth']['refund'] == 'N')	$sptxt[] = '<img src=/images/common/basket/001/basket_spe_icon003x.gif hspace=1 alt=��ȯ/��ǰ �Ұ� />';

							if(_array($sptxt)) echo "<span style='font-weight:bold;'>".implode(' ',$sptxt)."</span>";
						?>
					</div>

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
				<?=number_format($product['sellprice'])?>��
			</td>
			<td class="tdstyle" align="center">
		<?		if ($sellChk) {	?>
					<input type=text name="quantity[<?=$product['basketidx']?>]" value="<?= $product['quantity'] ?>" maxlength="4" onkeyup="strnumkeyup(this)" style="text-align:center; background-color:#f5f5f5; color:#999999; WIDTH:27px; height:17px; border:1px solid #ccc;"><br />
					<a href="javascript:CheckForm('upd','<?=$product['basketidx']?>')"><IMG sRC="<?= $Dir ?>upload/img/icon/edit.gif" border="0" alt="����"></a>
		<?		} else { ?>
				<input type=text name="quantity[<?=$product['basketidx']?>]" value="<?= $product['quantity'] ?>" size="3" maxlength="4" readonly style="WIDTH:30px;BORDER:#DFDFDF 1px solid;HEIGHT:18px;BACKGROUND-COLOR:#F7F7F7;padding-top:2pt;padding-bottom:1pt;height:19px">
	<?			}	?>
			</td>
			<td class="tdstyle" align="center"><b><?=number_format($product['sellprice']*$product['quantity'])?>��</b></td>
			<td class="tdstyle" align="center">Buying</td>
			<td class="tdstyle" align="center"><?=!empty($product['group_discount'])?number_format($product['group_discount']).'��':'&nbsp;'?></td>
			<td class="tdstyle" align="center">
				<?
				if($mem_reserve>0){
					echo "+".number_format(abs($product['realprice']*$mem_reserve))."��<br>";
					echo "(".($mem_reserve*100)."%)";
				}else{
					echo "0��";
				}
				?>
			</td>
			
	<?	if($i==0){ ?>
			<td class="tdstyle" align="center" <?=$deliPrtRowspan?>><?=$deliPrt?></td>
			<td class="tdstyle2" style="text-align:center" <?=$deliPrtRowspan?>><?=$basketItems['vender'][$vender]['conf']['com_name']?></td>
	<?		}?>
			<td class="tdstyle" align="center">
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
			$rental_prd = 1;
			$tmpPinfo = rentProduct::read($product['pridx']);
			//foreach($product['items'] as $rentItem){
			$rentItem = $product['rentinfo'];
			$roptinfo = &$tmpPinfo['options'][$rentItem['optidx']];
			//echo  $prentinfo['codeinfo']['pricetype'];
			$sellprice = $rentItem['solvprice']['totalprice'] /$rentItem['quantity'];
			$producttotalprice+= $rentItem['solvprice']['totalprice'];
			$disctotal += abs($rentItem['solvprice']['discprice']);

			//������
			$mem_reserve = getProductReserve($product['productcode']);
			$reserve_total += $product['realprice']*$mem_reserve;

			$prentinfo['codeinfo'] = venderRentInfo($product['vender'],$product['pridx'],$product['productcode']);

			//$product['addcode'] = "������";
		?>
		<tr>
			<td class="tdstyle2" style="text-align:center">
				<? if($sellprice > 0){ ?>
				<input type="checkbox" isrental="1" name="basket_select_item[]" value="<?=$product['basketidx']?>" class="checkbox">
				<? }else{ ?>
				�Ұ�
				<? } ?>
			</td>
			<td class="tdstyle" style="text-align:center">			
				<div style="float:left; width:90px;"><img src="<?=$product['tinyimage']['src']?>" style="width:90px;" /></div>
<!--					<div style="float:left; width:55px;"><img src="<?=$product['tinyimage']['src']?>" <?=$imageSize?> /></div>-->
				<div style="float:left; width:200px; margin-left:5px; text-align:left;">					
					<a href="<?=$Dir.FrontDir?>productdetail.php?productcode=<?=$product['productcode']?>"><?=rentalIcon($product['rental'])?><?=viewproductname($product['productname'],$product['etctype'],$product['selfcode'],$product['addcode'])?></a>
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
					<p style="font-weight:bold;"><?=($product['prmsg']?$product['prmsg']:"")?></p>
					<p style="color:#aaa;"><?=($roptinfo['optionName']=="���ϰ���")?"":$roptinfo['optionName'];?><?=($prentinfo['codeinfo']['pricetype']=="long")?"����":"";?></p>
					
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

					<?
					echo "<p style='color:#ff0000;font-size:11px;padding-top:3px'>";
					if($product['booking_confirm']){//��ǰ�� ������ �ִ°��
						$total_sql="select count(*) as totalcnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$product['productcode']."'";
						$total_res=mysql_query($total_sql,get_db_conn());
						$total_row=mysql_fetch_object($total_res);

						if($product['booking_confirm']=="now"){
							echo "���� ��� ���� Ȯ��";
						}else{
							echo "���� �� ";

							$arrconfirmTime = explode(":",$product['booking_confirm']);
							if($arrconfirmTime[0]=="00"){
								echo $arrconfirmTime[1]."��";

								$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$product['productcode']."' and timestampdiff(minute,bank_date,prd_status_date)<=".$arrconfirmTime[1];
							}else{
								echo $arrconfirmTime[0]."�ð�";

								$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.productcode='".$product['productcode']."' and timestampdiff(hour,bank_date,prd_status_date)<=".$arrconfirmTime[0];
							}
							
							$confirm_res=mysql_query($confirm_sql,get_db_conn());
							$confirm_row=mysql_fetch_object($confirm_res);

							if($total_row->totalcnt>0){
								$bookingper = round(($confirm_row->cnt/$total_row->totalcnt) * 100,1);
							}else{
								$bookingper = 99;
							}

							echo "�̳�, ".$bookingper."%����";
						}
						echo "</p>";
					}else{
						$total_sql="select count(*) as totalcnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$product['vender']."'";
						$total_res=mysql_query($total_sql,get_db_conn());
						$total_row=mysql_fetch_object($total_res);

						if($basketItems['vender'][$vender]['conf']['booking_confirm']){
							if($basketItems['vender'][$vender]['conf']['booking_confirm']=="now"){
								echo "���� ��� ���� Ȯ��";
							}else{
								echo "���� �� ";

								$arrconfirmTime = explode(":",$basketItems['vender'][$vender]['conf']['booking_confirm']);
								if($arrconfirmTime[0]=="00"){
									echo $arrconfirmTime[1]."��";

									$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$product['vender']."' and timestampdiff(minute,bank_date,prd_status_date)<=".$arrconfirmTime[1];
								}else{
									echo $arrconfirmTime[0]."�ð�";

									$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$product['vender']."' and timestampdiff(hour,bank_date,prd_status_date)<=".$arrconfirmTime[0];
								}
								
								$confirm_res=mysql_query($confirm_sql,get_db_conn());
								$confirm_row=mysql_fetch_object($confirm_res);

								if($total_row->totalcnt>0){
									$bookingper = round(($confirm_row->cnt/$total_row->totalcnt) * 100,1);
								}else{
									$bookingper = 99;
								}

								echo "�̳�, ".$bookingper."%����";
							}
							
						}
					}
					echo "</p>";
					?>
					<p style='font-size:11px;padding-top:3px'>
					<?
					echo "�����ð� : ";
					if($prentinfo['codeinfo']['rent_stime']==$prentinfo['codeinfo']['rent_etime']){
						echo "24�ð�";
					}else{
						echo "���� ".$prentinfo['codeinfo']['rent_stime']."�� ~ ���� ".$prentinfo['codeinfo']['rent_etime']."��";
					}
					?>
					</p>
				</div>
			</td>
			<td class="tdstyle" align="center">
				<p style='font-size:11px;text-align:center'>
				<?
				switch($prentinfo['codeinfo']['pricetype']){
					case 'day': echo '24�ð�'; break;
					case 'time': echo $prentinfo['codeinfo']['base_time'].'�ð�'; break;
					case 'checkout': 
						if($prentinfo['codeinfo']['checkin_time']<$prentinfo['codeinfo']['checkout_time']){
							echo "1��";
						}else{
							echo "1��";
						}
						break;
					case 'period': echo ($prentinfo['codeinfo']['base_period']>1)? ($prentinfo['codeinfo']['base_period']-1).'�� '.$prentinfo['codeinfo']['base_period'].'��':$prentinfo['codeinfo']['base_period'].'��'; break;
					case 'long': echo ''; break;
				}
				echo "</p>";
				?>
				<?=number_format($tmpPinfo['prdprice'])?>��<?//=number_format($rentItem['solvprice']['prdrealprice'])?>
			</td>
			<td class="tdstyle" align="center">		
				<input type=text name="quantity[<?=$product['basketidx']?>]" value="<?=$rentItem['quantity'] ?>" maxlength="4" onkeyup="strnumkeyup(this)" style="text-align:center; background-color:#f5f5f5; color:#999999; WIDTH:27px; height:17px; border:1px solid #ccc;"><br />
				<a href="javascript:CheckForm('upd','<?=$product['basketidx']?>')"><IMG SRC="<?= $Dir ?>upload/img/icon/edit.gif" border="0" alt="����"></a>
			</td>
			<td class="tdstyle" align="center"><b><?//=number_format($sellprice)?><?=number_format($rentItem['solvprice']['prdrealprice']*$rentItem['quantity'])?>��</b></td>
			<td class="tdstyle" align="center">
				<?
				if($prentinfo['codeinfo']['pricetype']=="long"){
					echo $roptinfo['optionPay'];
				}else{
					if($rentItem['solvprice']['diff']['day']>0) echo $rentItem['solvprice']['diff']['day']."�� ";
					if($rentItem['solvprice']['diff']['hour']>0) echo $rentItem['solvprice']['diff']['hour']."�ð� ";

					if($sellprice > 0){ 		
						$range_s[$k]=$rentItem['solvprice']['range'][0];
						$range_e[$k]=$rentItem['solvprice']['range'][1];
						//echo $k."==".$range_s[$k]."/".$range_s[$k-1]."<br>".$range_e[$k]."/".$range_e[$k-1];
						if($range_s[$k]!=$range_s[$k-1] || $range_e[$k]!=$range_e[$k-1]){
							$range_diff++;
						}
						//echo "<br>".date('Y-m-d H',$rentItem['solvprice']['range'][0]).'<br>'.date('Y-m-d H',$rentItem['solvprice']['range'][1]+1);
						echo "<font style='font-size:11px'>";
						echo "<br>".date('m-d H',$rentItem['solvprice']['range'][0]).'<br>'.date('m-d H',$rentItem['solvprice']['range'][1]+1);
						echo "</font>";
					}else{ 
						echo "<font style='font-size:11px'>";
						$endDate = "<br>".date('Y-m-d H',strtotime($rentItem['end'])+1);
						echo substr($rentItem['start'],0,13).'<br>'.$endDate;	
						echo "</font>";
					} 
				?>
					<br /><a href="javascript:RangeChange('','<?=$product['basketidx']?>','<?=$rentItem['quantity']?>')"><IMG SRC="<?= $Dir ?>upload/img/icon/edit.gif" border="0" alt="����"></a>
				<? 
				} 

				/*
				if($sellprice > 0){ 				
					 if($rentItem['solvprice']['timegap'] == '1') echo date('Y-m-d H',$rentItem['solvprice']['range'][0]).'<br>'.date('Y-m-d H',$rentItem['solvprice']['range'][1]+1);
					 else echo date('Y-m-d',$rentItem['solvprice']['range'][0]).'<br>'.date('Y-m-d',$rentItem['solvprice']['range'][1]);
				}else{ 
					  if($rentItem['solvprice']['timegap'] == '1') echo substr($rentItem['start'],0,13).'<br>'.substr($rentItem['end'],0,13);						  
					  else echo substr($rentItem['start'],0,10).'<br>'.substr($rentItem['end'],0,10);
				} 
				*/
				?>
				
			</td>
			<td class="tdstyle" align="center"><?=number_format(abs($rentItem['solvprice']['discprice'])).'��'?></td>
			<td class="tdstyle" align="center">
				<?
				if($mem_reserve>0){
					echo "+".number_format(abs($product['realprice']*$mem_reserve))."��<br>";
					echo "(".($mem_reserve*100)."%)";
				}else{
					echo "0��";
				}
				?>
			</td>
			

	<?		if($i==0){ ?>
			<td class="tdstyle" align="center" <?=$deliPrtRowspan?>>
				<select name="deli_type[<?=$product['basketidx']?>]" onchange="javascript:CheckForm('delichange','<?=$product['basketidx']?>')">
					<option value=''>�����ϼ���</option>
					<?
					$deli_type = explode(',', $tmpPinfo['deli_type']);
					for($h=0,$end=count($deli_type);$h<$end;$h++) {
						if($product[deli_type]==$deli_type[$h]) $deli_selected = "selected";
						else $deli_selected = "";
						echo "<option value=\"".$deli_type[$h]."\" ".$deli_selected.">".$deli_type[$h]."</option>";
					}
					?>
				</select>
				<?="<br>".$deliPrt?>
			</td>
			<td class="tdstyle2" style="text-align:center" <?=$deliPrtRowspan?>>
				<?=$basketItems['vender'][$vender]['conf']['com_name']?>
				<?
				/*
				$total_sql="select count(*) as totalcnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$product['vender']."'";
				$total_res=mysql_query($total_sql,get_db_conn());
				$total_row=mysql_fetch_object($total_res);

				if($basketItems['vender'][$vender]['conf']['booking_confirm']){
					echo "<p style='color:#ff0000;font-size:10pt;text-align:center;padding-top:3px'>";
					if($basketItems['vender'][$vender]['conf']['booking_confirm']=="now"){
						echo "���� ��� ���� Ȯ��";
					}else{
						echo "���� ��<br>";

						$arrconfirmTime = explode(":",$basketItems['vender'][$vender]['conf']['booking_confirm']);
						if($arrconfirmTime[0]=="00"){
							echo $arrconfirmTime[1]."��";

							$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$product['vender']."' and timestampdiff(minute,bank_date,prd_status_date)<=".$arrconfirmTime[1];
						}else{
							echo $arrconfirmTime[0]."�ð�";

							$confirm_sql="select count(*) as cnt from tblorderinfo o left join tblorderproduct op on o.ordercode=op.ordercode where op.vender='".$product['vender']."' and timestampdiff(hour,bank_date,prd_status_date)<=".$arrconfirmTime[0];
						}
						
						$confirm_res=mysql_query($confirm_sql,get_db_conn());
						$confirm_row=mysql_fetch_object($confirm_res);

						if($total_row->totalcnt>0){
							$bookingper = round(($confirm_row->cnt/$total_row->totalcnt) * 100,1);
						}else{
							$bookingper = 99;
						}

						echo "�̳�,<br>".$bookingper."%����";
					}
					echo "</p>";
				}
				*/
				
				?>
			</td>
	<?		} ?>
			<td class="tdstyle" style="text-align:center">
			<input type="hidden" name="totalprice" id="totalprice_<?=$product['basketidx']?>"  value="<?=$rentItem['solvprice']['totalprice']+$rentItem['solvprice']['longrent']?>">
			<input type="hidden" name="totaldeliprice" id="totaldeliprice_<?=$product['basketidx']?>" value="<?=$vendervalue['conf']['deli_price']?>">
			<input type="hidden" name="totaldiscprice" id="totaldiscprice_<?=$product['basketidx']?>" value="<?=$rentItem['solvprice']['discprice']?>">
				<? //echo number_format($product['realprice']); 
				echo number_format($rentItem['solvprice']['totalprice']+$rentItem['solvprice']['discprice']+$rentItem['solvprice']['longrent'])	?>��<br />
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
			$k++;
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
	<input type="hidden" name="change_period_ok" id="change_period_ok" value="0">
	</form>
	<tfoot>
		<tr>
			<td colspan="11" align="center">
				<?
				$isperiod = $notperiod = 0;
				$today_reserve = 0;
				foreach($basketItems['vender'] as $vender=>$vendervalue){
					for( $i = 0 ; $i < count($vendervalue['products']) ; $i++ ){
						$product = $vendervalue['products'][$i];
						$prentinfo = rentProduct::read($product['pridx']);
						$prentinfo['codeinfo'] = venderRentInfo($product['vender'],$product['pridx'],$product['productcode']);
						
						if($product['today_reserve']!="Y"){//���Ͽ����� �ƴѰ��
							$today_reserve++;
						}else{
							$today_reserve = $today_reserve;
						}

						if($prentinfo['codeinfo']['pricetype']=="period"){
							$isperiod++;
						}else{
							$notperiod++;
						}
					}//for end					
				}//foreach end

				if($notperiod==0 && $isperiod>0){
					//$period = $rentItem['solvprice']['diff']['day']+1;
					$period = $rentItem['solvprice']['diff']['day'];
					$period .= "�� ";
				}else{
					if($rentItem['solvprice']['diff']['day']>0) $period = $rentItem['solvprice']['diff']['day']."�� ";
					if($rentItem['solvprice']['diff']['hour']>0) $period .= $rentItem['solvprice']['diff']['hour']."�ð� ";
				}

				if($range_diff>1){
					$rstart_day = "�뿩��";
					$rstart_time = "";
					$rend_day = "�ݳ���";
					$rend_time = "";
				}else{
					$rstart_day = substr($rentItem['start'],5,5);
					$rend_day = substr($rentItem['end'],5,5);
				}

				if($today_reserve==0){//���Ͽ��డ��
					$minDate = 0;
				}else{
					$minDate = 1;
				}
				?>
				<form id="rangChange" name="rangChange">
				<input type="hidden" name="act" value="basketRangeAllUpdate"/>
				<input type="hidden" name="ordertype" value="<?=$ordertype?>"/>
				<input type="hidden" name="sfld" value="<?=$_REQUEST['sfld']?>"/>
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<input type="text" name="pp_bookingSDate" id="pp_bookingSDate" value="<?=$rstart_day?>" class="input" onChange="rangeCheck(this.form)" style="color:#ff0000"><input type="hidden" name="pp_bookingStartDate" id="pp_bookingStartDate" value="<?=substr($rentItem['start'],0,10)?>" class="input">
							<? 
							if($notperiod>0){ 
							?>
							<select name="startTime" id="startTime" onChange="rangeCheck(this.form);disableCheck(this)" class="select1" style="color:#ff0000" gubun="select">
								<option value="">�ð�</option>
								<? 
								$stime = substr($rentItem['start'],11,2);

								if($prentinfo['codeinfo']['pricetype'] == 'checkout'){//������
									if($prentinfo['codeinfo']['checkout_time']==0 || $prentinfo['codeinfo']['checkin_time']>$prentinfo['codeinfo']['checkout_time']){
										$end_time = 23;
									}else{
										$end_time = $prentinfo['codeinfo']['checkout_time'];
									}
									for($i=$prentinfo['codeinfo']['checkin_time'];$i<=$end_time;$i++){
										$prentinfo['codeinfo']['checkin_time']=$prentinfo['codeinfo']['checkin_time']?$prentinfo['codeinfo']['checkin_time']:date("H")+1;
										$sel = $i==$prentinfo['codeinfo']['checkin_time']?'selected':'';

										echo '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'��</option>';
									}
								}else{
									for($i=0;$i<=23;$i++){ 
										if($range_diff>1){
											$sel = "";
										}else{
											$sel = $i==$stime?'selected':'';
										}

										if($basketItems['productcnt']==1 && $prentinfo['codeinfo']['rent_stime']!="0" && $prentinfo['codeinfo']['rent_etime']!="0" && ($i<$prentinfo['codeinfo']['rent_stime'] || $i>$prentinfo['codeinfo']['rent_etime'])){
											$optionStyle=" class='disabled'";
										}else{
											$optionStyle="";
										}
										echo '<option value="'.sprintf('%02d',$i).'" '.$sel.' '.$optionStyle.'>'.sprintf('%02d',$i).'��</option>';
									}
								}
								?>
							</select>
							<?}else{?>
							<input type="hidden" name="startTime" id="startTime" value="" gubun="input">
							<?}?>
						</td>
						<td style="padding-left:20px;">
							<input type="text" name="pp_bookingEDate" id="pp_bookingEDate" value="<?=$rend_day?>" class="input" onChange="rangeCheck(this.form);" style="color:#FFD9E0;" disabled><input type="hidden" name="pp_bookingEndDate" id="pp_bookingEndDate" value="<?=substr($rentItem['end'],0,10)?>" class="input">
							<? if($notperiod>0){ ?>
							<select name="endTime0" id="endTime0" class="select1" style="color:#FFD9E0"><option value="">�ð�</option></select>
							<select name="endTime" id="endTime" onChange="rangeCheck(this.form);disableCheck(this);changePeriod(this.form)" class="select1" style="color:#FF0000;display:none">
								<option value="">�ð�</option>
								<? 
								$etime = substr($rentItem['end'],11,2)+1;

								if($prentinfo['codeinfo']['pricetype'] == 'checkout'){//������
									if($prentinfo['codeinfo']['checkout_time']==0){
										$end_time = 23;
									}else{
										$end_time = $prentinfo['codeinfo']['checkout_time'];
									}
									for($i=0;$i<=$end_time;$i++){
										if($prentinfo['codeinfo']['checkout_time']==0 && $prentinfo['codeinfo']['pricetype']=="time"){
											$sel = $i==($prentinfo['codeinfo']['checkin_time']+$prentinfo['codeinfo']['base_time'])?'selected':'';
										}else{
											$sel = $i==$prentinfo['codeinfo']['checkout_time']?'selected':'';
										}
										
										echo '<option value="'.sprintf('%02d',$i).'" '.$sel.'>'.sprintf('%02d',$i).'��</option>';
									}
								}else{
									
									for($i=0;$i<=23;$i++){
										if($range_diff>1){
											$sel = "";
										}else{
											$sel = $i==$etime?'selected':'';
										}
										if($basketItems['productcnt']==1 && $prentinfo['codeinfo']['rent_stime']!="0" && $prentinfo['codeinfo']['rent_etime']!="0" && ($i<$prentinfo['codeinfo']['rent_stime'] || $i>$prentinfo['codeinfo']['rent_etime'])){
											$optionStyle=" class='disabled'";
										}else{
											$optionStyle="";
										}
										echo '<option value="'.sprintf('%02d',$i).'" '.$sel.' '.$optionStyle.'>'.sprintf('%02d',$i).'��</option>';
									}
								}
								?>
							</select>
							<?}else{?>
							<input type="hidden" name="endTime" id="endTime" value="">
							<?}?>
						</td>
						<td style="padding-left:20px;color:#0000ff;">
							<div id="rangTxt" style="float:left;color:#0000ff;vertical-align:middle">
							<? if($range_diff>1){ echo "�뿩���� ���� �ٸ��ϴ�.<br>�뿩���� �ٽ� �����ϼ���."; }else{ echo "�뿩�Ⱓ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$period;}?>
							</div>
						</td>
						<td>
							<div id="modifyBtn_div" style="float:left;display:none;padding-left:20px;">
							<? //if($range_diff>1){ ?>
							<!--input type="button" value="Ȯ��" onclick="changePeriod(this.form);" class="btn_sline2" style="padding:0px 12px;line-height:32px;background:#ff0000;color:#ffffff;font-weight:bold;"-->
							<? //} ?>
							</div>
						</td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
		<tr>
			<td colspan="11" style="padding:10px 0px;">
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
				<div style="float:left;">
					<input type="hidden" name="reserve_price" id="reserve_price" value="<?=$reserve_total?>">
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style="width:120px;">���� ������</td>
							<td style="text-align:right" id="rsvTxt"><font color="#ff0000"><?=number_format($reserve_total)?></font>��</td>
							<td style="padding-left:10px">
								<!--<span id="dis_txt">
								<a href="javascript:changeDiscount('dis')">������ ������� �ޱ�</a>
								</span>
								<span id="res_txt" style="display:none">
								<a href="javascript:changeDiscount('res')">������ ���� �ޱ�</a>
								</span>-->
							</td>
							<td style="width:500px;text-align:center">
								<?
								if($range_diff>1){
								?>
								<!--<span style="color:#ff0000">�뿩�Ⱓ�� �ٸ���ǰ�� �ֽ��ϴ�.</span> -->
								<?
								}
								?>
								<!--��۱Ⱓ <a style="color:#0000ff" href="javascript:RangeChange('all','<?=$product['basketidx']?>','')">�ϰ�����</a>-->
							</td>
						</tr>
					</table>
				</div>
				<div style="float:right;">
					<div style="font-size:12px; color:#777777; text-align:right; letter-spacing:-0.5pt; margin-bottom:5px; padding-right:10px; display:none"><?=$groupMemberSale?></div>										
					<table border="0" cellpadding="0" cellspacing="0" align="right">
						<tr>
							<td style="width:120px;">�� ��ǰ�ݾ�</td>
							<td style="text-align:right" id="totalprice_str"><?=number_format($producttotalprice)?></td>
						</tr>
						<tr>
							<td>��ۺ�</td>
							<td style="text-align:right" id="totaldeliprice_str"><?=$disp_deliprice?></td>
						</tr>
						<tr>
							<td>�� ���αݾ�</td>
							<td style="text-align:right" id="totaldiscprice_str"><?=($disctotal>0)? "-":"";?><?=number_format($disctotal)?></td>
						</tr>
						<tr>
							<td colspan="2" style="height:20px;"></td>
						</tr>
						<tr>
							<td>�����ݾ�</td>
							<td style="text-align:right"><span class="basket_etc_price3" style="font-weight:bold" id="sumprice_str"><?=$disp_sumprice?></span></td>
						</tr>
						<tr>
							<td colspan="2" width="10"></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</tfoot>
</table>

</div>
<? if($ordertype == 'recommand' || $basket_type=='recommand'){ ?>
<b style="color:#ff0000"><?=$product[sell_memid]?></b> �Բ� ��õ���� ��ǰ�Դϴ�.
<? } ?>
</div>
<!--<div style="height:6px;background:url('/data/design/img/main/bot_boxline.gif') no-repeat;font-size:0px;"></div>-->

<script language="javascript" type="text/javascript">
function checkRequest(){
	var chk = 0;deli=0;

	if ($j('#basketForm').find('input[name^=basket_select_item][isrental=1]:checked').length == 0) {
		alert('���õ� Rent ��ǰ�� �����ϴ�.');
	}else if($j("#change_period_ok").val()=="1"){
		alert('�뿩�Ⱓ ���� Ȯ���� ���� Ŭ�����ּ���.');
	} else {
		$j('#basketForm').find('input[name^=basket_select_item]:checked').each(function(idx,item) {
			
			bidx = $j(item).val();
			if($j("select[name^='deli_type["+bidx+"]']").val()==""){
				deli = deli +1;
			}else{
				if ( ! $j(item).attr('isrental')) {
					chk = chk + 1;
				}
			}
		});

		if (chk > 0) {
			alert('���õ� ��ǰ�� �Ǹ� ��ǰ�� ���ԵǾ� �ֽ��ϴ�.\n�뿩 ��ǰ�� �������ֽʽÿ�.');
		}else if(deli > 0 ){
			alert('��۹���� �������ֽʽÿ�.');
		} else {
			$j( "#checkRequest" ).dialog('open');
		}
	}
}

function CheckOrder() {
	if($j('#basketForm').find('input:checkbox[name^=basket_select_item][isrental=1]:checked').length > 0){
		alert('���õ� �׸� �뿩 ��ǰ�� ���ԵǾ� �ֽ��ϴ�.\n�Ǹ� ��ǰ�� �������ֽʽÿ�.');
	} else if ($j('#basketForm').find('input:checkbox[name^=basket_select_item]:checked').length == 0) {
		alert('���õ� �Ǹ� ��ǰ�� �����ϴ�.');
	}else{					
		$j('#basketForm').attr('action','/front/proc/basket.php');
		$j('#basketForm>input[name=act]').val('basketToOrder');
		$j('#basketForm').submit();
	}
}

$j(function(){
    $j( "#checkRequest" ).dialog({
	  autoOpen:false,
      modal: true,
      width: 500,
      height: 140,
      buttons: {
      
      }
    });
});
</script>
<style type="text/css">
.ui-widget-header{ background:#02B0dd;}
</style>
<div id="checkRequest" title="üũ �� ����" style="display:none;">
	<div style="line-height:150%" id="checkReserv">
		�Ϻ� ��ǰ ��Ż�� �Ұ����� ���, ���ڷ� �ȳ��Ǹ� �ݾ��� ȯ�ҵ˴ϴ�.
		<!--��ǰ���� �� �뿩��ü��Ȳ�� ���� ������ �Ұ����� ���� �ֽ��ϴ�.<br />
		�̶�, ����Ұ� �� �����ڷ� �ȳ��Ǹ� �ݾ��� ȯ��ó�� �˴ϴ�.<br />
		����, üũ �� �������� �� 12�ð� �̳� �Ա����� ���� ��� �ڵ��ֹ� ��ҵ˴ϴ�.<br/>
		�̿� �����Ͻø� Ȯ�ι�ư�� �����ֽʽÿ�.-->
		<div style="text-align:center; margin-top:15px;">
			<img src="/upload/img/btn_confirm.gif" alt="����" onclick="javascript:OrderSend()" style="cursor:pointer" />
			<img src="/upload/img/btn_cancel.gif" alt="���" onclick="javascript:$j('#checkRequest').dialog('close');" style="cursor:pointer" />
		</div>
	</div>
</div>

<table border="0" cellpadding="0" cellspacing="0" style="margin-top:40px;" align="center">
	<tr>
		<td>

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
			<? if($rental_prd==1){ ?>
			<A HREF="javascript:checkRequest();"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn9.gif" border="0" hspace="2" alt="üũ �� ����"></a>
			<? } ?>
			<? if($ordertype != 'recommand' && $basket_type!='recommand'){ ?>
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
				function OrderSend() {
					//if($j('#basketForm').find('input:checkbox[name^=basket_select_item][isrental=1]:checked').length > 0){
					//	alert('���õ� �׸� �뿩 ��ǰ�� ���ԵǾ� �ֽ��ϴ�.\n�Ǹ� ��ǰ�� �������ֽʽÿ�.');
					//} else if ($j('#basketForm').find('input:checkbox[name^=basket_select_item]:checked').length == 0) {
					//	alert('���õ� �Ǹ� ��ǰ�� �����ϴ�.');
					//}else{					
						$j('#basketForm').attr('action','/front/proc/basket.php');
						$j('#basketForm>input[name=act]').val('basketToOrder');
						$j('#basketForm').submit();
					//}
				}
				</script>

				<? /*
				<A HREF="<?=$Dir.FrontDir?>login.php?chUrl=<?=urlencode($Dir.FrontDir."order.php")?>"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn7.gif" border="0" hspace="2" alt="��ǰ�ֹ��ϱ�"></a> */?>
				<? if($sell_prd==1){ ?>
				<A HREF="javascript:CheckOrder()"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn7.gif" border="0" hspace="2" alt="��ǰ�ֹ��ϱ�"></a>
				<? } ?>
			<?
					}
				} else {
			?>
				<br><font color="#FF3300"><b>�ֹ������� �ּ� �ݾ��� <?=number_format($_data->bank_miniprice)?>�� �Դϴ�.</b></font>
			<?
				}
			?>
			<A HREF="<?=$shopping_url?>"><IMG SRC="<?=$Dir?>images/common/basket/<?=$_data->design_basket?>/basket_skin3_btn5.gif" border="0" hspace="2" alt="���ΰ���ϱ�"></a>
			<?
			if(count($basketItems['vender'])>1){
				echo "<p style='text-align:left;'>���� ����Ȯ�� ������ �Ǹ��ڸ��� Ȯ���ϼ���.<br> �뿩�Ұ��� ���簡 �ڵ� ��ҵ˴ϴ�.</p>";
			}
			?>
		</td>
	</tr>
</table>

<table style="width:100%;">
   <tr style="width:100%; text-align=center">
      <td align="center">
         <? /*
            $checkoutObj = new naverCheckout();
            $checkoutObj->setItesmType('basket');
            echo $checkoutObj->btn($_ShopInfo->getTempkey());
         */?>
      </td>
   </tr>
</table>

<script language="javascript" type="text/javascript">
function fixrenttime(){

	if($j("#pricetype").val()=="time"){
		var st = $j('#p_bookingSDate').datepicker('getDate');
		var ed = $j('#p_bookingEDate').datepicker('getDate');	
		diff = (ed.getTime() - st.getTime()) / (60 * 60 * 1000);
		
		if($j("#p_bookingSDate").val()=="�뿩��" || $j("#p_bookingEDate").val()=="�ݳ���"){
			return false;
		}
	}
	return true;
}

//�Ⱓ�ϰ�����(�˾�)
function RangeChange(mode,bidx,quantity){
	$j('#rentSchdulePop').html('');
	var sels = "";
	$j('#basketForm').find('input:checkbox[name^=basket_select_item]:checked').each(function(){
		sels += $j(this).val()+",";
	});
	$j.get('/ajaxback/rent.php',{'act':'basketRangeForm','mode':mode,'ordertype':'<?=$ordertype?>','sfld':'<?=$_REQUEST['sfld']?>','quantity':quantity,'basketidx':bidx,'basket_select_item':sels},function(data){
		if(data.err == 'ok'){
			$j('#rentSchdulePop').html(data.html);	
			$j('#rentSchdulePop').dialog('open');			
		}else{
			alert(data.err);
		}
	},'json');
}

//�Ⱓ����
function changePeriod(f){
	var ordertype = f.ordertype.value;
	var sfld = f.sfld.value;
	var p_bookingStartDate = f.pp_bookingStartDate.value;
	var p_bookingEndDate = f.pp_bookingEndDate.value;
	var startTime = f.startTime.value;
	var endTime = f.endTime.value;
	
	$j.post('/ajaxback/rent.php',{'act':'basketRangeAllUpdate','ordertype':ordertype,'sfld':sfld,'p_bookingStartDate':p_bookingStartDate,'p_bookingEndDate':p_bookingEndDate,'startTime':startTime,'endTime':endTime},function(data){
		if(data.err == 'ok'){
			alert("�Ⱓ�� ����Ǿ����ϴ�.");
			document.location.reload();			
		}else{
			alert(data.err);
		}
	},'json');
}

function disableCheck(obj) { 
	if (obj[obj.selectedIndex].className=='disabled') { 
		alert("�����Ͻ� �ð��� �����ð��� �ƴϱ� ������ �湮�� �Ұ����մϴ�.\n "); 
		for (var i=0; obj[i].className=="disabled"; i++); 
		obj.selectedIndex = i; 
		return; 
	} 
}

function rangeCheck(f){
	var pp_bookingStartDate = f.pp_bookingStartDate.value;
	var pp_bookingEndDate = f.pp_bookingEndDate.value;
	var startTime = f.startTime.value;
	var endTime = f.endTime.value;
	var start = pp_bookingStartDate;
	var end = pp_bookingEndDate;

	if($j("#startTime").attr("gubun")=="select"){
		if(pp_bookingStartDate!="" && startTime!=""){
			$j("#pp_bookingEDate").prop("disabled",false);
			$j("#pp_bookingEDate").css("color","#ff0000");
			$j("#endTime0").hide();
			$j("#endTime").show();
			//$j("#endTime").val("").prop("selected", true);
			//$j("#endTime").prop("disabled",false);
			//$j("#endTime").css("color","#ff0000");
		}
	}else{
		if(pp_bookingStartDate!=""){
			$j("#pp_bookingEDate").prop("disabled",false);
			$j("#pp_bookingEDate").css("color","#ff0000");
		}
		startTime = "00";
		endTime = "24";
	}

	
	if(startTime){start += " "+startTime;}
	if(endTime){end += " "+endTime;}
	
	if(pp_bookingStartDate !="" && startTime!="" && pp_bookingEndDate !="" && endTime!=""){
		$j("#modifyBtn_div").css("display",'');
		$j("#change_period_ok").val("1");
		$j.post('/ajaxback/rent.php',{'act':'dateDiff','start':start,'end':end},
			function(data){
				if(data.err == 'ok'){
					$j("#rangTxt").html(data.date_diff);
				}else{
					$j("#rangTxt").html(data.err);
				}
			},
		'json');
	}
}


$j(function(){
	if($j("#pp_bookingSDate")){
		$j("#pp_bookingSDate").datepicker({
		  showOn: "both",
		  dateFormat:"mm-dd",
		  dayNames: ["��","��","ȭ","��","��","��","��"],
		  buttonImage: "/images/mini_cal_calen.gif",
		  minDate: <?=$minDate?>,
		  buttonImageOnly: true,
		  buttonText: "�뿩��",	
		  altField: "#pp_bookingStartDate",
		  altFormat: "yy-mm-dd",
		  onClose: function( selectedDate ) {
		  }
		  ,onSelect: function( selectedDate ) {
			if($j("#pp_bookingEDate").val()<$j("#pp_bookingSDate").val()){
				$j("#pp_bookingEDate").val("�ݳ���");
			}
			rangeCheck(document.rangChange);
			fixrenttime();
		  }
		});

		$j("#pp_bookingEDate").datepicker({
		  showOn: "both",
		  dateFormat:"mm-dd",
		  dayNames: ["��","��","ȭ","��","��","��","��"],
		  buttonImage: "/images/mini_cal_calen.gif",
		  minDate: <?=$minDate?>,
		  buttonImageOnly: true,
		  buttonText: "�ݳ���",
		  altField: "#pp_bookingEndDate",
		  altFormat: "yy-mm-dd",
		  onClose: function( selectedDate ) {
		  }
		  ,onSelect: function( selectedDate ) {
			if($j("#pp_bookingSDate").val()=="�뿩��"){
				alert("�뿩�� ���� �����ϼ���.");$j("#pp_bookingEDate").val("�ݳ���");
			}
			if($j("#pp_bookingEDate").val()<$j("#p_bookingSDate").val()){
				alert("�ݳ����� �뿩�������� �� �����ϴ�.");$j("#pp_bookingEDate").val("�ݳ���");
			}
			rangeCheck(document.rangChange);
			fixrenttime();
			if($j("#startTime").attr("gubun")=="input"){
				changePeriod(document.rangChange);
			}
		  }
		});	
	}


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

