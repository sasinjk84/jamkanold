<script language="javascript" type="text/javascript">  
var maxitemidx = 0;

function getRewardData(aidx){
	$.post('/admin/attendance/process.php',{'aidx':aidx,'act':'getRewardItems'},function(data){
		if(data.msg == 'success'){
			if(data.items.length < 1){
				loadstr = '<tr><td colspan="'+$('#rewardTable').data('culCnt')+'" class="msgRow">��ϵ� ���� �׸��� �����ϴ�.</td></tr>';
				$('#rewardTable').find('tbody:eq(0)').html(loadstr);
			}else{
				$.each(data.items,function(el,itm){
					drawRewaredItem(itm);
				});
			}
		}else{
			alert(data.msg);
		}
	});
}

function drawRewaredItem(itm){									
	inrowhtml = $('#rewardTable').data('rowHtml');
	itemidx = parseInt(itm.itemidx);
	
	if(isNaN(itemidx) || itemidx < 0) itemidx = -1;
	
	
	repdate = ((itm.conse == 1)?'���� ':'�� ')+itm.ranges+' �� �湮';
	reprew = '';
	switch(itm.rewtype){
		case 'reserve':
			reprew += '������ '+itm.rewval+	' �� ����';
			break;
	}
	
	if(parseInt(itm.rewmax) >= 0){
		repdate += ' ���� ';
		if(parseInt(itm.rewmax) >0) reprew += '( �ִ� '+itm.rewmax+	' �� )';
		else reprew += '( �ݺ����� )';
	}

	
	var itemcnt = $('#rewardTable').data('itemcnt');
	
	
	if(itemcnt < 1){
		$('#rewardTable').find('tbody').html('');
	}
	
	try{		
		// ���� �׸�� ���� �Ǹ� �̺κе� ó�� �ؾ���.
		var inputitemvalue = itm.ridx+'_'+itm.conse+'_'+itm.ranges+'_'+itm.rewtype+'_'+itm.rewval+'_'+itm.rewmax;
		if(itemidx >=0){		
			var el = $('#attendanceEventForm').find("input[name=rewardItem\\[\\]]:eq("+itemidx+")");
			$(el).val(inputitemvalue);
			
			inrowhtml = inrowhtml.replace(/__NO__/g,itemidx+1).replace(/__CONDITION__/g,repdate).replace(/__REWARD__/g,reprew).replace(/__ITEMIDX__/g,itemidx);
			$(inrowhtml).replaceAll('#rewardTable>tbody>tr:eq('+itemidx+')');
		}else{
			var el = $('<input type="hidden" name="rewardItem[]" value="'+inputitemvalue+'" />').appendTo('#attendanceEventForm');
			var idx = $('#attendanceEventForm').find("input[name='rewardItem\[\]']").index(el);						
			inrowhtml = inrowhtml.replace(/__NO__/g,idx+1).replace(/__CONDITION__/g,repdate).replace(/__REWARD__/g,reprew).replace(/__ITEMIDX__/g,idx);
			$('#rewardTable').find('tbody').append(inrowhtml);	
			$('#rewardTable').data('itemcnt',itemcnt+1);
		}
		$('#rewardForm')[0].reset();

	}catch(e){
		alert('UI �� ���α׷��� ������ �ֽ��ϴ�.');
	}
}


function checkRegexp( o, regexp){
	if(typeof o == 'string') var str = o;
	else str = o.val();
	return regexp.test(str);
}

function rewardItemModify(idx){
	try{
		idx = parseInt(idx);		
		var el = $('#attendanceEventForm').find("input[name='rewardItem\[\]']:eq("+idx+")").val();
		var vals = el.split('_');
		
		$('#rewardForm').find('input[name=itemidx]').val(idx);
		$('#rewardForm').find('input[name=conse][value='+vals[1]+']').attr('checked',true);
		$('#rewardForm').find('input[name=ranges]').val(vals[2]);
		$('#rewardForm').find('select[name=rewtype]').find('option[value='+vals[3]+']').attr('selected',true);
		
		tmp = parseInt(vals[5]); // �ݺ� ����
		if(tmp >= 0){
			$('#rewardForm').find('#duptype').find('option[value=dup]').attr('selected',true);
			if(tmp > 0) $('#rewmax').val(tmp);
			else $('#rewmax').val('');
		}else{
			$('#rewardForm').find('#duptype').find('option[value=one]').attr('selected',true);
		}		
		toggleDuptype();
		
		$('#rewardForm').find('input[name=rewval]').val(vals[4]);
		$('#rewardSubmitBtn').val('����');
	}catch(e){
		alert('��� �׸��� ã���� ������ �߻��߽��ϴ�.a');
	}
	
}


function rewardItemDelete(idx){
	if(confirm("�̹� ���޵� ������ ȸ�� ���� �ʽ��ϴ�. \r\n�⼮�̺�Ʈ ������ �ϼž� ���� ������ �ݿ��˴ϴ�.")){		
		var $trs = $('#rewardTable').find('tbody').find('tr:gt('+idx+')');
		$.each($trs,function(idx,el){
			$mbel = $(el).find('.rewardItemMod');
			if($mbel){
				oidx = parseInt($($mbel).attr('idx'));
				$(el).find('td:eq(0)').html(oidx--);
				$($mbel).attr('idx',oidx);
				$(el).find('.rewardItemDel').attr('idx',oidx);
			}
		});		
		
		$('#attendanceEventForm').find("input[name=rewardItem\\[\\]]:eq("+idx+")").remove();
		$('#rewardTable>tbody>tr:eq('+idx+')').remove();
				
		$('#rewardTable').data('itemcnt',$('#rewardTable').find('tbody').find('tr').length);		
	}
}

function toggleDuptype(){
	var duptype = $('#duptype').val();
	if(duptype == 'one'){
		$('#dupmaxarea').css('display','none');
	}else if(duptype == 'dup'){
		$('#dupmaxarea').css('display','');
	}
}

$(function() {
	$( "#stdate" ).datepicker({  		
		dateFormat: "yy-mm-dd",
  		changeMonth: true,
		minDate:0,
		onClose: function( selectedDate ) {
			$( "#to" ).datepicker( "option", "minDate", selectedDate );
  		}
	});
	$( "#enddate" ).datepicker({
	  defaultDate: "+1w",
	  changeMonth: true,
	  dateFormat: "yy-mm-dd",
	  minDate:1,
	  onClose: function( selectedDate ) {
		$( "#from" ).datepicker("option","maxDate",selectedDate );
  	}
	});
	
	$('#rewardTable').data('rowHtml',$('#rewardTable').find('tbody:eq(0)').html());	
	$('#rewardTable').data('culCnt',$('#rewardTable').find('thead:eq(0)').find('th').length);	
	$('#rewardTable').data('itemcnt',0);
	
	<? if(_isInt($result['item']['aidx'])){?>
		loadstr = '<tr><td colspan="'+$('#rewardTable').data('culCnt')+'" class="msgRow">������ �ε���</td></tr>';		
	<? }else{ ?>
		loadstr = '<tr><td colspan="'+$('#rewardTable').data('culCnt')+'" class="msgRow">��ϵ� ���� �׸��� �����ϴ�.</td></tr>';
	<? } ?>
	$('#rewardTable').find('tbody:eq(0)').html(loadstr);
	
	$('#rewardSubmitBtn').removeAttr('disabled');
	$('#rewardResetBtn').removeAttr('disabled');
	
	
	$(document).on("click",".rewardItemMod",function(el){		
		var idx = $(this).attr('idx');	
		rewardItemModify(idx);		
	});
	
	
	$(document).on("click",".rewardItemDel",function(){		
		var idx = $(this).attr('idx');
		rewardItemDelete(idx);		
	});	
	
	$(document).on('change','#duptype',function(){
		toggleDuptype();
	});

	$('#rewardForm').bind('reset',function(){
		$('#rewardSubmitBtn').val('�߰�');
		$('#dupmaxarea').css('display','none');
	});
	
	$('#attendanceEventForm').submit(
		function(e){
			vaild = true;
			var title = $(this).find('input[name=title]').val();
			var stdate = $(this).find('input[name=stdate]').val();
			var enddate = $(this).find('input[name=enddate]').val();
			if($.trim(title) < 1){
				alert('�̺�Ʈ Ÿ�̵��� �Է��ϼ���.');
				$(this).find('input[name=title]').focus();
				vaild = false;
			}else if($.trim(stdate) < 1){
				alert('�������� �Է��ϼ���.');
				$(this).find('input[name=stdate]').focus();
				vaild = false;
			}else if($.trim(stdate) < 1){
				alert('�������� �Է��ϼ���.');
				$(this).find('input[name=enddate]').focus();
				vaild = false;
			}else{
				vaild = true;
			}			
			if(!vaild){
				e.preventDefault();	
			}else{
				return;
			}
		}
	);
	
	
	$('#rewardForm').submit(
		function(e){
			e.preventDefault();
			var vaild = true;
			var itm = {'conse':null,'rewtype':null,'ranges':null,'rewval':null,'duptype':null,'rewmax':0} // �� ��ȭ�� �׸� ����ȭ �ʿ�
			
			if(!checkRegexp($(this).find('input[name=ranges]:eq(0)'),/^([0-9])+$/)){
				alert('�ϼ� ���� ���ڸ� �Է��ϼž� �մϴ�.');
				$(this).find('input[name=ranges]:eq(0)').val('').focus();
				vaild = false;
			}else{
				itm.ridx = $(this).find('input[name=ridx]:eq(0)').val();				
				if($.trim(itm.ridx) < 1) itm.ridx =0;
				
				itm.itemidx = $(this).find('input[name=itemidx]').val();
				itm.conse = $(this).find('select[name=conse]').val();
				itm.rewtype = $(this).find('select[name=rewtype]').val();
				itm.ranges = $(this).find('input[name=ranges]:eq(0)').val();
				itm.rewval = parseInt($(this).find('input[name=rewval]:eq(0)').val());
				itm.duptype = $(this).find('select[name=duptype]').val();
				itm.rewmax = $(this).find('input[name=rewmax]:eq(0)').val();
				
				switch(itm.rewtype){
					case 'reserve':
						if(!checkRegexp($(this).find('input[name=rewval]:eq(0)'),/^([0-9])+$/)){
							alert('���� ������ ���� ���ڸ� �Է��ϼž� �մϴ�.');
							$(this).find('input[name=rewval]:eq(0)').val('').focus();
							vaild = false;
						}
						break;
					default:
						alert('�ùٸ��� ���� ���� ���� �� �Դϴ�.');
						vaild = false;
						break;
				}
				
				if(vaild){
					if(itm.duptype == 'dup'){
						if($.trim(itm.rewmax) < 1){
							itm.rewmax = 0;
						}else if(!checkRegexp(itm.rewmax,/^([0-9])+$/)){
							alert('�ִ� ���� �ݾ� ���� ���ڸ� �Է��ϼž� �մϴ�.');
							$(this).find('input[name=rewmax]:eq(0)').val('').focus();
							vaild = false;
						}else if(itm.rewval >= parseInt(itm.rewmax) || parseInt(itm.rewmax)%itm.rewval >0){
							alert('�ִ� ���� �ݾ��� 1ȸ ���޾��� ����� �Է��ϼž� �մϴ�.');
							$(this).find('input[name=rewmax]:eq(0)').val('').focus();
							vaild = false;
						}
					}else{
						$(this).find('input[name=rewmax]:eq(0)').val('');
						itm.rewmax = -1;
						itm.duptype = 'one';
					}
				}
			}
			
			if(vaild){
				drawRewaredItem(itm);
			}			
		});
		
	<? if(_isInt($result['item']['aidx'])){?>
	getRewardData('<?=$result['item']['aidx']?>');
	<? } ?>
	/*
	
	$("#rewardModal" ).dialog({
      autoOpen: false,
      height: 430,
      width: 350,
     // modal: true,
	  resizable:false,
	  draggable:false,
	  position:[100,100],
      buttons: {
        "����": function() {
          var bValid = true;
          allFields.removeClass( "ui-state-error" );
 
          bValid = bValid && checkLength( name, "username", 3, 16 );
          bValid = bValid && checkLength( email, "email", 6, 80 );
          bValid = bValid && checkLength( password, "password", 5, 16 );
 
          bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
          // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
          bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
          bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
 
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
 
    $(".rewardFormBtn")    
      .click(function() {
		var ridx= $(this).attr('ridx');
		if(ridx){
			$('#rewardForm').find('input[name=ridx]').val(ridx);
		}else{
			$('#rewardForm').find('input[name=ridx]').val('');
		}
		
		offset = $(this).offset();
		alert(offset.top);
		alert(offset.left);
	
		$( "#rewardModal" ).dialog("open");
		var position = $( "#rewardModal" ).dialog( "option", "position" );
		for(p in position){
			alert(p);
			alert(position[p]);
		}
		
      });*/
});
</script>
<div style="width:100%; margin-top:20px;">
	<img src="images/market_attendance_stitle2.gif" width="192" height="31" alt="�⼮üũ �̺�Ʈ ����" style="margin-bottom:3px;">
	<form name="attendanceEventForm" id="attendanceEventForm" method="post" action="/admin/attendance/process.php">
	<input type="hidden" name="act" value="update" />
	<input type="hidden" name="aidx" value="<?=$result['item']['aidx']?>" />
	<table cellspacing="0" cellpadding="0" width="100%" border="0" class="formTbl">
		<tbody>
			<tr>
				<th style="width:120px;">�̺�ƮŸ��Ʋ</th>
				<td><input type="text" name="title" value="<?=$result['item']['title']?>" /></td>
			</tr>
			<tr>
				<th>�Ⱓ</th>
				<td><input type="text" name="stdate" id="stdate" style="width:80px;" class="datepicker" readonly="readonly" value="<?=$result['item']['stdate']?>" /> ~ <input type="text" name="enddate" id="enddate" style="width:80px;" class="datepicker" value="<?=$result['item']['enddate']?>" readonly="readonly" /></td>
			</tr>
			<tr>
				<th>�޸�</th>
				<td><textarea name="memo" id="memo" style="width:98%; height:150px"><?=$result['item']['memo']?></textarea></td>
			</tr>
		</tbody>
	</table>
	<div style="margin-top:10px; margin-bottom:30px; padding-bottom:10px;" class="font_size">	
	<div style="width:10%; float:left;"></div>
	<div style="width:79%; float:left; text-align:center"><input type="button" value="���" style="margin-right:20px;"><input type="submit" value="����"></div>
	<div style="width:10%; float:right; text-align:right"><button class="listBtn">���</button></div>
</div>

	</form>
	
<!-- 	<div id="rewardModal">-->
	<div style="font-weight:bold; border-top:2px solid #000">���� ����</div>
	<form name="rewardForm" id="rewardForm">
	<input type="hidden" name="itemidx" value="" />
	<input type="hidden" name="ridx" value="" />
	<span style="color:red">* ���������� ����/�߰� �� ���� �۾��� ���� ���� ��ư�� ������ ������ ���� �ϼž߸� �ݿ� �˴ϴ�.</span>
	<table cellspacing="0" cellpadding="0" border="0" class="formTbl" width="100%">			
		<tbody>
			<tr>
				<th style="width:120px; height:28px;">����</th>
				<td style="padding:0px 5px;"><select name="conse" id="conse">
						<option value="0">�հ�(��)</option>
						<option value="1">����</option>
					</select>
					<input type="text" name="ranges" value="" style="margin-left:5px; width:30px;" />�� �⼮��
					<select name="rewtype" id="rewtype" style="margin-left:5px;">
						<option value="reserve">������</option>
					</select>��
					<input type="text" name="rewval" id="rewval" value="" style="margin-left:5px; width:100px;">���� 
					<select name="duptype" id="duptype">
						<option value="one">�ѹ���</option>
						<option value="dup">�ݺ��ؼ�</option>
					</select>
					<span id="dupmaxarea" style="display:none">
					<input type="text" name="rewmax" id="rewmax" value="" style="width:100px" />��(<span style="color:orange">����ΰų� 0 �̸� ������</span>) ����
					</span>
					����
				</td>
				<th style=" padding:0px 5px; width:150px;">
					<input type="submit" id="rewardSubmitBtn" value="�߰�" style=" margin-right:10px;">
					<input type="reset" id="rewardResetBtn" value="�ʱ�ȭ" disabled>
				</th>
			</tr>
			
		</tbody>
	</table>
	</form>
	<table cellspacing="0" cellpadding="0" width="100%" border="0" id="rewardTable" class="formTbl" style="margin-top:15px;">	
		<thead>
			<tr>
				<th style="height:28px; width:80px;">No.</th>
				<th>����</th>
				<th>����</th>
				<th style="width:120px;">����</th>
			</tr>
		</thead>		
		<tbody>
			<tr>
				<td style="text-align:center; height:28px;">__NO__</td>
				<td>__CONDITION__</td>
				<td>__REWARD__</td>
				<td style="text-align:center"><input type="button" value="����" idx="__ITEMIDX__" class="rewardItemMod" style="margin-right:5px;"><input type="button" value="����" idx="__ITEMIDX__" class="rewardItemDel" style="margin-right:5px;"></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- �޴��� -->
<table width="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
	<tr>
		<td><img src="images/manual_top1.gif" width="15" height="45" alt=""></td>
		<td><img src="images/manual_title.gif" width="113" height="45" alt=""></td>
		<td width="100%" background="images/manual_bg.gif" height="35"></td>
		<td background="images/manual_bg.gif"></td>
		<td background="images/manual_bg.gif"><img src="images/manual_top2.gif" width="18" height="45" alt=""></td>
	</tr>
	<tr>
		<td background="images/manual_left1.gif"></td>
		<td COLSPAN="3" width="100%" valign="top" bgcolor="white" style="padding-top:8pt; padding-bottom:8pt; padding-left:4pt;" class="menual_bg"></td>
		<td background="images/manual_right1.gif"></td>
	</tr>
	<tr>
		<td><img src="images/manual_left2.gif" width="15" height="8" alt=""></td>
		<td COLSPAN="3" background="images/manual_down.gif"></td>
		<td><img src="images/manual_right2.gif" width="18" height="8" alt=""></td>
	</tr>
</table>

<!-- #�޴��� --> 