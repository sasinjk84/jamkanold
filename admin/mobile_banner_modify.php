<?
	$Dir="../";
	include_once($Dir."lib/init.php");
	include_once($Dir."lib/lib.php");
	INCLUDE ("access.php");

	####################### ������ ���ٱ��� check ###############
	$PageCode = "mo-1";
	$MenuCode = "mobile";
	if (!$_usersession->isAllowedTask($PageCode)) {
	INCLUDE ("AccessDeny.inc.php");
	exit;
	}
	#########################################################
	$bdate = !_empty($_REQUEST['bdate'])?trim($_REQUEST['bdate']):"";
	$mode = !_empty($_POST['mode'])?trim($_POST['mode']):"";
	$attechfilename = !_empty($_FILES['attech']['name'])?trim($_FILES['attech']['name']):"";
	$filesrc = $Dir."m/upload/";
	$maxfilesize = 512000;
	$allowfile = array('image/pjpeg','image/jpeg','image/JPG','image/X-PNG','image/PNG','image/png','image/x-png','image/gif');
	
	if(strlen($bdate) > 0){
		//echo '<script>alert("�߸��� �����Դϴ�.");</script>';
	}
	
	$bannerSQL = "SELECT * FROM tblmobilebanner WHERE date = '".$bdate."' ";
	
	$banner=$regdate=$url=$src="";

	if(false !== $bannerRes = mysql_query($bannerSQL,get_db_conn())){
		$bannerrowcount = mysql_num_rows($bannerRes);
		if($bannerrowcount > 0){
			$bannerRow = mysql_fetch_assoc($bannerRes);
			$banner =$bannerRow['image']; 
			$src = $filesrc.$bannerRow['image'];
			$url = $bannerRow['url'];
			$regdate = $bannerRow['date'];
		}
	}
	
	if(strlen($mode)>0 && $mode == "modify"){

		$url = !_empty($_POST['url'])?trim($_POST['url']):"";
		if(strlen($attechfilename)>0){
			
			$attechfiletype = !_empty($_FILES['attech']['type'])?trim($_FILES['attech']['type']):"";
			$attechfilesize = !_empty($_FILES['attech']['size'])?trim($_FILES['attech']['size']):"";
			$attechtempfilename = !_empty($_FILES['attech']['tmp_name'])?trim($_FILES['attech']['tmp_name']):"";
			if(!in_array($attechfiletype,$allowfile)){
				echo '<script>alert("÷�� ������ ������ �ƴմϴ�.\n÷�ΰ����� ������ jpg, gif, png�Դϴ�.");location.href="./mobile_banner_modify.php?bdate='.$bdate.'";</script>';exit;
			}else{
				if($attechfilesize >$maxfilesize){
					echo '<script>alert("÷�� ������ ���� �뷮�� �ʰ� �Ǿ����ϴ�.\n�ִ� ÷�ΰ����� ���Ͽ뷮�� 500KB�Դϴ�.");location.href="./mobile_banner_modify.php?bdate='.$bdate.'";</script>';exit;
				}else{
					$filename = "banner_".date("His").$attechfilename;

					if(move_uploaded_file($attechtempfilename,$filesrc.$filename)){
						$queryattechname = $filename;
						if(is_file($src)){
							@unlink($src);
						}
					}
				}
			}
		}


		$bannerSQL = "UPDATE tblmobilebanner SET ";
		if(strlen($attechfilename)>0){
			$bannerSQL .= "image = '".$queryattechname."', ";
		}
		$bannerSQL .= "url = '".$url."' ";
		$bannerSQL .= "WHERE date = '".$bdate."' ";
		
		if(mysql_query($bannerSQL,get_db_conn())){
			echo '<script>alert("��ʰ� �����Ǿ����ϴ�.");self.close();</script>';exit;
		}else{
			echo '<script>alert("������ �������� �ʾҽ��ϴ�.");</script>';exit;
		}
	}
	
?>
<html>
	<head>
		<style>
			html,body,div,img{margin:0px;padding:0px;border:0px;}
		</style>
		<script>
			function formSubmit(){
				var _form = document.bannerForm;

				if(confirm("�����Ͻðڽ��ϱ�?")){
					_form.submit();
				}
				return;
			}
		</script>
	</head>
	<body>
		<div style="height:45px; line-height:45px; text-align:center; font-weight:bold; font-size:18px; background-color:#999; color:#FFF;">
			����� ���ι�ʼ���
		</div>
		<div style="text-align:center; padding:5px;">
			<?if(strlen($banner) > 0){?>
				<img src="<?=$src?>" />
			<?}else{?>
				��ϵ� ��ʰ� �����ϴ�.
			<?}?>
		</div>
		<div>
			<form action="<?=$_SERVER['PHP_SELF']?>" name="bannerForm" method="post" enctype="multipart/form-data">
				<table cellpadding="0" cellspacing="0" border="1" width="100%">
					<tr>
						<th>
							���� URL
						</th>
						<td>
							<input type="text" name="url" value="<?=$url?>"/>
						</td>
					</tr>
					<tr>
						<th>
							��� �̹���
						</th>
						<td>
							<input type="file" name="attech" value=""/>
							<br/>
							������ ���Ͻø� �̹����� ÷���Ͻñ� �ٶ��ϴ�.
							<br/>
							÷�ΰ��� �뷮 500KB
						</td>
					</tr>
				</table>
				<input type="hidden" name="bdate" value="<?=$regdate?>"/>
				<input type="hidden" name="mode" value="modify"/>
			</form>
			<div style="text-align:center;"><a href="javascript:formSubmit();"><img src="images/bnt_apply.gif" border="0" alt="�����ϱ�" /></a> <a href="javascript:self.close();"><img src="images/btn_cancel.gif" border="0" alt="����ϱ�" /></a></div>
		</div>
	</body>
</html>