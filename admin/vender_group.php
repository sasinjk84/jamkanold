<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include ("access.php");
include_once($Dir."lib/admin_more.php");

####################### ������ ���ٱ��� check ###############
$PageCode = "vd-1";
$MenuCode = "vender";
if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
}
#########################################################

$vgrouplist = array();
$sql = "select * from vender_group order by vgyearsell desc,vgcommi_self desc,vgcommi_main desc";

if(false !== $res = mysql_query($sql,get_db_conn())){
	if(0 < $vno = mysql_num_rows($res)){		
		while($row = mysql_fetch_assoc($res)){
			$row['vno'] = $vno--;
			array_push($vgrouplist,$row);
		}
	}
}



include "header.php"; ?>
<style type="text/css">
@import url(/admin/vender_extra/common.css);
</style>
<script language="javascript" type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery-ui-1.10.4.custom.min.js"></script>
<script language="javascript" type="text/javascript">
function resetForm(){
	var f = document.editGroup;
	f.reset();
	f.act.value = 'add';
	f.vgidx.value = '';
}

function editSet(vgidx){
	$.post('/admin/vender_extra/process.php',{'act':'getinfo','vgidx':vgidx},function(data){
		if(data.msg !='success'){
			alert(data.msg);
		}else{
			var f = document.editGroup;
			f.act.value = 'edit';
			f.vgidx.value = data.items.vgidx;
			f.vgname.value = data.items.vgname;
			f.vgyearsell.value = data.items.vgyearsell;
			f.vgcommi_self.value = data.items.vgcommi_self;
			f.vgcommi_main.value = data.items.vgcommi_main;
			$("#groupEditDiv" ).dialog("open");
		}
	},'json');
}

$(function(){

$("#groupEditDiv" ).dialog({
      autoOpen: false,
      height: 380,
      width: 400,
     // modal: true,
	  resizable:false,
	  draggable:false,
	  position:[100,100],
      buttons: {
		'�ʱ�ȭ': function(){
			resetForm();
        },
        "����": function() {
          var bValid = true;
		  document.editGroup.submit();  
          if ( bValid ) {           
            $( this ).dialog( "close" );
          }
        },
        '���': function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
    //   alert('t');
      }
    });
	
	
	
	$(".newAdd").click(function() {
		$( "#groupEditDiv" ).dialog("open");
    });
	$(".groupEditbtn").on('click',function(){
		editSet($(this).attr('vgidx'));
	});
	$(".groupDelbtn").on('click',function(){
		if(confirm('���� ���� �Ͻðڽ��ϱ�?')){
			document.editGroup.act.value = 'delete';
			document.editGroup.vgidx.value = $(this).attr('vgidx');
			document.editGroup.submit();
		}
	});
 
});
</script>
<link href="/css/ui-lightness/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
<table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed">
	<tr>
		<td valign="top"><table cellpadding="0" cellspacing="0" width=100% style="table-layout:fixed">
				<tr>
					<td><table cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed"  background="images/con_bg.gif">
							<tr>
								<td valign="top" style="width:198px"  background="images/leftmenu_bg.gif"><? include ("menu_vender.php"); ?></td>
								<td style="width:10px"></td>
								<td valign="top"><table cellpadding="0" cellspacing="0" width="100%">
										<tr>
											<td height="29" colspan="3"><table cellpadding="0" cellspacing="0" width="100%">
													<tr>
														<td height="28" class="link" align="left" background="images/con_link_bg.gif"><img src="images/top_link_house.gif" border="0" valign="absmiddle">������ġ : �������� &gt; ������ü ���� &gt; <span class="2depth_select">������ü �׷����</span></td>
													</tr>
												</table></td>
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
														<td style="background:url(images/title_bg.gif) 0px 40px repeat-x;"><IMG SRC="images/vender_group_title.gif"ALT="������ü �׷����"><a href="javascript:window.location.reload()">[���ΰ�ħ]</a></TD>
													</tr>
												</table>	
												<button class="newAdd">���� �����</button>
												<div id="groupEditDiv" style="display:none" title="�׷����">
												<form name="editGroup" action="/admin/vender_extra/process.php" method="post" enctype="multipart/form-data">
												<input type="hidden" name="act" value="add" />
												<input type="hidden" name="vgidx" value="" />
												<table class="formTbl" cellpadding="0" cellspacing="0">													
													<tr>														
														<th colspan="2" style="width:160px;">�׷��</th>
														<td><input type="text" name="vgname" value="" /></td>
													</tr>
													<tr>
														<th colspan="2">������</th>
														<td><input type="file" name="vgicon" /></td>
													</tr>
													<tr>
														<th colspan="2">�������</th>
														<td><input type="text" name="vgyearsell" value="" /></td>
													</tr>
													<tr>
														<th rowspan="2" style="border-right:1px solid #ddd">���������</th>
														<th>����</th>
														<td><input type="text" name="vgcommi_self" style="width:40px" value="" />%</td>
													</tr>
													<tr>
														<th>��Ź</th>
														<td><input type="text" name="vgcommi_main" style="width:40px;" value="" />%</td>														
													</tr>																							
												</table>												
												</form>
												</div>
												<table class="listTbl" cellpadding="0" cellspacing="0">
													<caption>�׷� ���</caption>
													<tr>
														<th rowspan="2" style="width:40px;">No.</th>
														<th rowspan="2">�׷��</th>
														<th rowspan="2" style="width:100px;">������</th>
														<th rowspan="2" style="width:120px;">�������</th>
														<th colspan="2" style="width:120px;">���������</th>
														<th rowspan="2" style="width:120px;">����</th>
													</tr>
													<tr>
														<th style="width:60px">����</th>
														<th style="width:60px">��Ź</th>
													</tr>
												<?
													
													if(_array($vgrouplist)){
														foreach($vgrouplist as $vgroup){ ?>
													<tr>
														<td style="text-align:center"><?=$vgroup['vno']?></td>
														<td><?=$vgroup['vgname']?></td>
														<td style="text-align:center"><? if(!_empty($vgroup['vgicon']) && file_exists($Dir.DataDir."shopimages/vender/".$vgroup['vgicon'])){ ?>
															<img src="<?=$Dir.DataDir."shopimages/vender/".$vgroup['vgicon']?>" />
															<? }else{ ?>
                                                            &nbsp;
                                                            <? } ?>
														</td>
														<td style="text-align:center"><?=number_format($vgroup['vgyearsell'])?></td>
														<td style="text-align:center"><?=number_format($vgroup['vgcommi_self'])?>%</td>
														<td style="text-align:center"><?=number_format($vgroup['vgcommi_main'])?>%</td>
														<td style="text-align:center"><input type="button" value="����" style="margin-right:5px;" vgidx="<?=$vgroup['vgidx']?>" class="groupEditbtn" /><input type="button" value="����" vgidx="<?=$vgroup['vgidx']?>" class="groupDelbtn" /></td>
													</tr>													
												<?		}
													}else{ ?>
													<tr>
														<td style="padding:5px 0px; text-align:center" colspan="5">��ϵ� �׷��� �����ϴ�.</td>
													</tr>
												<?	} ?>												
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
									</table></td>
							</tr>
						</table></td>
				</tr>
			</table></td>
	</tr>
</table>
<?=$onload?>
<? INCLUDE "copyright.php"; ?>
