<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/ext/func.php");

$form=$_REQUEST["form"];
$post=$_REQUEST["post"];
$addr=$_REQUEST["addr"];
$gbn=$_REQUEST["gbn"];

$area=trim($_POST["area"]);
$mode=$_POST["mode"];

if (strlen($area)>2 && (strpos(getenv("HTTP_REFERER"),"addr_search.php")==false || strpos(getenv("HTTP_REFERER"),getenv("HTTP_HOST"))==false)) {
	exit;
}
?>

<html>
<head>
<title>�����ȣ �˻�</title>
<meta http-equiv="CONTENT-TYPE" content="text/html;charset=EUC-KR">

<style>
td	{font-family:"����,����";color:#4B4B4B;font-size:12px;line-height:17px;}
BODY,DIV,form,TEXTAREA,center,option,pre,blockquote {font-family:Tahoma;color:000000;font-size:9pt;}

A:link    {color:#635C5A;text-decoration:none;}
A:visited {color:#545454;text-decoration:none;}
A:active  {color:#5A595A;text-decoration:none;}
A:hover  {color:#545454;text-decoration:underline;}

.input{font-size:12px;BORDER-RIGHT: #DCDCDC 1px solid; BORDER-TOP: #C7C1C1 1px solid; BORDER-LEFT: #C7C1C1 1px solid; BORDER-BOTTOM: #DCDCDC 1px solid; HEIGHT: 18px; BACKGROUND-COLOR: #ffffff;padding-top:2pt; padding-bottom:1pt; height:19px}
.select{color:#444444;font-size:12px;}
.textarea {border:solid 1;border-color:#e3e3e3;font-family:����;font-size:9pt;color:333333;overflow:auto; background-color:transparent}

#searchFormLocal form, #searchFormAPI form {margin:0px; padding:0px;}
#searchResultAPI {margin-top:10px; border-top:1px solid #f3f3f3; border-bottom:1px solid #f3f3f3;}
</style>
<script language="javascript" type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
<SCRIPT LANGUAGE="JavaScript">
<!--

var $j = jQuery.noConflict();

function EnterCheck() {
	if (document.form.area.value.length==0) {
		alert("��(��/��/��) �̸��� �Է��ϼ���.");
		document.form.area.focus();
		return;
	} else {
		if (document.form.area.value.length<2) {
			alert("��(��/��/��) �̸��� 2�� �̻� �Է��ϼ���.");
			document.form.area.focus();
			return;
		}
		document.form.submit();
	}
}

var form="<?=$form?>";
var post="<?=$post?>";
var addr="<?=$addr?>";
var gbn="<?=$gbn?>";
function do_submit(post1,post2,straddr) {
	try {
		if(gbn=="2") {
			opener.document[form][post+"1"].value=post1;
			opener.document[form][post+"2"].value=post2;
		} else {
			opener.document[form][post].value=post1+"-"+post2;
		}
		opener.document[form][addr].value=straddr;
		//opener.document[form][addr].focus();
		
		if(addr.substr(addr.length-1,1) == '1'){
			var addr2 = addr.substr(0,addr.length-1)+'2';
			if(opener.document[form][addr2]) opener.document[form][addr2].value = '';
		}
		window.close();
	} catch (e) {
		alert("������ �߻��Ͽ����ϴ�.");
	}
}

function do_submit2(post1,post2,straddr,ext){
	try {
		if(gbn=="2") {
			opener.document[form][post+"1"].value=post1;
			opener.document[form][post+"2"].value=post2;
		} else {
			opener.document[form][post].value=post1+"-"+post2;
		}

		opener.document[form][addr].value=straddr;

		if(addr.substr(addr.length-1,1) == '1'){
			var addr2 = addr.substr(0,addr.length-1)+'2';
			if(opener.document[form][addr2]){
				opener.document[form][addr2].value = ext;
			}
		}
		//opener.document[form][addr].focus();
		window.close();
	} catch (e) {
		alert("������ �߻��Ͽ����ϴ�.");
	}
}

//�� ó��
function addrTab(index) {
	for (i=1; i<=2; i++)
		if (index == i) {
			thisMenu = eval("addr" + index + ".style");
			thisMenu.display = "";
		} else {
			otherMenu = eval("addr" + i + ".style");
			otherMenu.display = "none";
		}
}
//-->
</SCRIPT>

<script language="javascript" type="text/javascript">
$j(function(){
	initSidoCode();
	$j("select[name='sidocode']").on('change',function(e){	getSigunguname($j(this).val());});

	$j('#apiForm').submit(function(e){
		e.preventDefault();
		findZipAPI();
	});
});

function findZipAPI(){
	var sidocode = $j("#apiForm").find("select[name='sidocode']").val();
	var sigunguname = $j("#apiForm").find("select[name='sigunguname']").val();
	var roadname = $j("#apiForm").find("input[name='roadname']").val();
	var bldmainnum = $j("#apiForm").find("input[name='bldmainnum']").val();
	if($j.trim(sidocode).length < 1){
		alert('��/�� �� �������ּ���');
	}else if($j.trim(sigunguname).length < 1){
		alert('��/��/�� �� �������ּ���');
	}else if($j.trim(roadname).length < 1){
		alert('���θ��� �Է����ּ���');
	}else{
		initResult();
		//$j.post('/lib/api.php',{'apiname':'roadzip','method':'search','sidocode':sidocode,'sigunguname':escape(sigunguname),'roadname':escape(roadname),'bldmainnum':escape(bldmainnum),'perpage':'-1'},
		var obj = {'apiname':'roadzip','method':'search','sidocode':sidocode,'sigunguname':escape(sigunguname),'roadname':escape(roadname),'bldmainnum':escape(bldmainnum),'perpage':'20'};
		requestAPIsearch(obj);
	}
}

function requestAPIsearch(obj){
	var $page;
	var $totalpage;
	$j.post('/lib/api.php',obj,
			function(data){
				var $rst = $j(data).find('result');

				$page = $j($rst).attr('page');
				$totalpage = $j($rst).attr('totalpage');

				var $cnt = $j($rst).attr('itemcount');
				var $itm = $j($rst).find('item');
				if(parseInt($cnt) <1){
					$j('#searchResultAPI').find('table:eq(0)').find('tbody').append('<tr><td class="noResult">�˻� ����� �����ϴ�.</td></tr>');
				}else{
					dispAPIresult($itm);
				}
			},"xml").done(function(){
				if(parseInt($page) < parseInt($totalpage)){
					obj.page = parseInt($page)+1;
					requestAPIsearch(obj);
				}
				}).fail(function(){ alert('api ���� �κп� ������ �ֽ��ϴ�. (1)');});
}

function dispAPIresult($itm){
	$j($itm).each(function(idx,itm){
		var eclass = (idx > 0 && idx%2 == 1)?'evenItem':'oddItem';

		var zipcode = $j(itm).find('zipnum').text();
		zipcode = zipcode.replace(/([0-9]{3})([0-9]{3})/g, '$1-$2');

		var addr = $j(itm).find('sidoname').text()+' '+$j(itm).find('sigunguname').text()+' '+$j(itm).find('roadname').text();
		var bldmainnum = $j(itm).find('bldmainnum').text();
		var bldsubnum  = $j(itm).find('bldsubnum ').text();
		var bldname  = $j(itm).find('bldname').text();
		var dbldname  = $j(itm).find('dbldname').text();
		var ext = '';
		if($j.trim(bldmainnum).length) ext+= bldmainnum;
		if($j.trim(bldsubnum ).length) ext+= '-'+bldsubnum ;

		if($j.trim(bldname).length) ext+= ' '+bldname;
		if($j.trim(dbldname ).length) ext+= '-'+dbldname ;


		var addrold = $j(itm).find('sidoname').text()+' '+$j(itm).find('sigunguname').text()+' '+$j(itm).find('dongname').text();
		var jimain = $j(itm).find('jibunmain').text();
		var jisub = $j(itm).find('jibunsub').text();
		if($j.trim(jimain).length) addrold+= ' '+jimain;
		if($j.trim(jisub).length) addrold+= '-'+jisub;


		$j('#searchResultAPI').find('table:eq(0)').find('tbody').append('<tr><td class="zipCodeStr '+eclass+'"><A HREF="javascript:do_submit2(\''+zipcode.substr(0,3)+'\',\''+zipcode.substr(4,3)+'\',\''+addr+'\',\''+ext+'\');">'+zipcode+'</a></td><td class="zipAddrStr '+eclass+'"><A HREF="javascript:do_submit2(\''+zipcode.substr(0,3)+'\',\''+zipcode.substr(4,3)+'\',\''+addr+'\',\''+ext+'\');">'+addr+' '+ext+'<br><span class="oldAddress">'+addrold+'</span></a></td></tr>');
	});
}


function getSigunguname(sidocode){
	initSigunguname();

	$j.post('/lib/api.php',{'apiname':'roadzip','method':'getgugun','sidocode':sidocode},
		function(data){
			var $emsg = $j(data).find('msg:eq(0)').text();
			if($j.trim($emsg).length){
				alert($emsg);
			}else{
				var $rst = $j(data).find('result');
				var $cnt = $j($rst).attr('itemcount');
				var $itm = $j($rst).find('item');
				var target = $j("select[name='sigunguname']");
				$j(target).find('option:eq(0)').text('�������ּ���');
				$j($itm).each(function(idx,opt){
					$j(target).append('<option value="'+$j(opt).find('sigunguname').text()+'">'+$j(opt).find('sigunguname').text()+'</option>');
				});
			}
		},"xml").done(function(){}
	).fail(function(){ alert('api ���� �κп� ������ �ֽ��ϴ�. (2)');});
}

function initSigunguname(){
	$j("select[name='sigunguname']").find('option:gt(0)').remove();
}

function initResult(){
	$j('#searchResultAPI').find('table:eq(0)').find('tbody').html('');
}

function initSidoCode(){
	$j("select[name='sidocode']").find('option:gt(0)').remove();
	initSigunguname();

	$j.post('/lib/api.php',{'apiname':'roadzip','method':'getsido'},
	function(data){
		var $emsg = $j(data).find('msg:eq(0)').text();
		if($j.trim($emsg).length){
			alert($emsg);
		}else{
			var $rst = $j(data).find('result');
			var $cnt = $j($rst).attr('itemcount');
			var $itm = $j($rst).find('item');
			var target = $j("select[name='sidocode']");
			$j(target).find('option:eq(0)').text('�������ּ���');
			$j($itm).each(function(idx,opt){
				$j(target).append('<option value="'+$j(opt).find('code').text()+'">'+$j(opt).find('name').text()+'</option>');
			});
		}
	 }
	,"xml").done(function(){}
	).fail(function(){ alert('api ���� �κп� ������ �ֽ��ϴ�. (3)');});
}
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" onLoad="window.resizeTo(467,350);document.form.area.focus();">

<TABLE WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0">
	<TR>
		<TD><img src="<?=$Dir?>images/search_zipcode_title.gif" border="0"></TD>
	</TR>
	<TR>
		<TD style="padding:10px;">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="100%">
						<div id="addr1" style="display:;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%" background="<?=$Dir?>images/common/tab_bg.gif">
								<tr>
									<td style="cursor:pointer;"><img src="<?=$Dir?>images/common/tab_addr1_on.gif" border="0" alt="�����ּ� �˻�" /></td>
									<td onClick="addrTab(2)" style="cursor:pointer;"><img src="<?=$Dir?>images/common/tab_addr2.gif" alt="���θ��ּ� �˻�" /></td>
									<td width="100%"></td>
								</tr>
							</table>
							<div id="searchFormLocal">
								<form method="POST" name="form" action="<?= $_SERVER[PHP_SELF] ?>?form=<?=$form?>&post=<?=$post?>&addr=<?=$addr?>&gbn=<?=$gbn?>">
								<input type="hidden" name=mode value="srch">
								<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tr>
										<td colspan="2" style="padding:10px 0px;">
											<!--<img src="<?=$Dir?>images/search_zipcode_text1.gif" border="0"><br />-->
											<span style="font-size:11px; color:#ff6600; letter-spacing:-1px; font-family:����; line-height:14px;">ã�����ϴ� �ּ��� �� (��/��/��) �� �Է��ϼ���.<br />��) �б���, �����2��, ���̸�</span>
										</td>
									</tr>
									<tr>
										<td width="100%" valign="top"><input type="text" name="area" value="<?=$area?>" class="input" style="WIDTH:100%;"></td>
										<td><a href="javascript:EnterCheck();"><img src="<?=$Dir?>images/search_zipcode_btn.gif" border="0" hspace="5"></a></td>
									</tr>
								</table>
								</form>
							</div>

							<div id="searchResultLocal" style="display:;">
								<table cellpadding="0" cellspacing="0" width="100%">
<?
		if($mode=="srch" && strlen($area)>0) {
			$sql = "SELECT * FROM tblpostalcode ";
			$sql.= "WHERE addr_dong LIKE '%".$area."%' ";
			if(false === $result=mysql_query($sql,get_db_conn())){ ?>
						<tr><td style="padding:10px 0px; text-align:center; font-weight:bold">DB ����� ������ �ֽ��ϴ�.</td></tr>
		<?	}else{
				if(mysql_num_rows($result) < 1){ ?>
						<tr><td style="padding:10px 0px; text-align:center; font-weight:bold">�˻��� ����� �����ϴ�.</td></tr>
		<?		}else{
					$odd = false;
					while($row=mysql_fetch_object($result)) {
						$trbg = ($odd)?"#F3F3F3":"#FFFFFF";
						$odd = !$odd;
						$temp = substr($row->post,0,3)."-".substr($row->post,3,3);
						$temp2 = $row->addr_do." ".$row->addr_si." ".$row->addr_dong." ".$row->addr_bunji;
						$temp3 = $row->addr_do." ".$row->addr_si." ".$row->addr_dong;  ?>

						<tr>
							<td style="text-align:center; width:80px; background:<?=$trbg?>;"><A HREF="javascript:do_submit('<?=substr($row->post,0,3)?>','<?=substr($row->post,3,3)?>','<?=$temp3?>');"><img src="<?=$Dir?>images/search_zipcode_point3.gif" border="0"><span style="color:#FF6C00; font-weight:bold"><?=$temp?></span></A></td>
							<td style="padding:4px 0px; background:<?=$trbg?>;"><A HREF="javascript:do_submit('<?=substr($row->post,0,3)?>','<?=substr($row->post,3,3)?>','<?=$temp3?>');"><span style="text-decoration:underline;"><?=$temp2?></span></a></td>
						</tr>
		<?			} // end while?>
						<tr><td><hr size="1" color="#F3F3F3"></td></tr>
						<tr>
							<td colspan="2" style="height:30px; text-align:center; color:#EE4900; font-weight:bold">�ش� �ּҸ� ���� �� ������ �ּҸ� �Է��ϼ���.</td>
						</tr>
						<tr><td><hr size="1" color="#F3F3F3"></td></tr>
		<?		} // end if
			} // end if 2
			@mysql_free_result($result);
		} else { ?>
						<tr><td><hr size="1" color="#F3F3F3"></td></tr>
						<tr>
							<td style="height:30px; text-align:center; color:#EE4900; font-weight:bold">�ؿ� �ּ��� ��� �Է¶��� "�ؿ�"�� �Է��ϼ���.</td>
						</tr>
						<tr><td><hr size="1" color="#F3F3F3"></td></tr>
<?		} ?>
					</table>
				</div>
			</div>

			<div id="addr2" style="display:none;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%" background="<?=$Dir?>images/common/tab_bg.gif">
					<tr>
						<td onClick="addrTab(1)" style="cursor:pointer;"><img src="<?=$Dir?>images/common/tab_addr1.gif" border="0" alt="�����ּ� �˻�" /></td>
						<td style="cursor:pointer;"><img src="<?=$Dir?>images/common/tab_addr2_on.gif" border="0" alt="�����ּ� �˻�" /></td>
						<td width="100%"></td>
					</tr>
				</table>
				<div id="searchFormAPI">
					<form method="POST" name="apiForm" id="apiForm" action="">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td colspan="5" style="padding:14px 0px; border-bottom:1px solid #f3f3f3;"><span style="font-size:11px; color:#ff6600; letter-spacing:-1px; font-family:����; line-height:14px;">��/�� �� ��/��/���� �����Ͻ��� ���θ�� �ǹ� ��ȣ�� �Է��ϼ���.</span></td>
						</tr>
						<tr><td colspan="5" height="10"></td></tr>
						<tr>
							<td width="45">��/��</td>
							<td><select name="sidocode" style="width:120px;"><option value="">-- ��� ȣ���� --</option></select></td>
							<td>��/��/��</td>
							<td><select name="sigunguname" style="width:120px;"><option value="">�������ּ���</option><option value="">�������ּ���</option><option value="">�������ּ���</option><option value="">�������ּ���</option></select></td>
							<td></td>
						</tr>
						<tr>
							<td>���θ�</td>
							<td><input type="text" name="roadname" value="" style="width:120px;" /></td>
							<td>�ǹ���ȣ</td>
							<td><input type="text" name="bldmainnum" value="" style="width:120px;"></td>
							<td><input type="image" src="<?=$Dir?>images/search_zipcode_btn.gif" border="0" /></td>
						</tr>
					</table>
					</form>
				</div>
				<div id="searchResultAPI" style="display:;">
					<style type="text/css">
						#apiResultTbl{}
						#apiResultTbl td.noResult{ text-align:center;padding-top:10;color:#EE4900; font-weight:bold; }
						#apiResultTbl td.zipCodeStr{text-align:center; width:70px; color:#FF6C00; font-weight:bold; }
						#apiResultTbl td.zipCodeStr a:link {color:#FF6C00;}
						#apiResultTbl td.zipAddrStr{ font-weight:bold; padding:5px 0px; }
						#apiResultTbl td.oddItem{ background:#ffffff; }
						#apiResultTbl td.evenItem{ background:#F3F3F3; }
						#apiResultTbl .oldAddress{ font-weight:normal; }
					</style>
					<table cellpadding="0" cellspacing="0" width="100%" id="apiResultTbl">
						<tbody>
						</tbody>
					</table>
					<div id="APIpageStr"></div>
				</div>
			</div>
		</td>
	</tr>
	<tr><td height="10"></td></tr>
	<tr>
		<td align="center"><a href="javascript:window.close()"><img src="<?=$Dir?>images/search_zipcode_btn_close.gif" border="0"></a></td>
	</tr>
	</table>
	</TD>
</TR>
</TABLE>
</body>
</html>