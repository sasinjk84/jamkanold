<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
?>
<html>
<head>
<META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">

<link rel="stylesheet" href="style.css">
<script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
<script language="JavaScript">
function CheckLogin() {
	if (!document.loginform.id.value) {
		alert("아이디를 입력하세요.");
		document.loginform.id.focus();
		return false;
	}
	if (!document.loginform.passwd.value) {
		alert("비밀번호를 입력하세요.");
		document.loginform.passwd.focus();
		return false;
	}
	<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["ADMIN"]=="Y") {?>
	if(typeof document.loginform.ssllogin!="undefined") {
		if(document.loginform.ssllogin.checked==true) {
			document.loginform.action='https://<?=$_data->ssl_domain?><?=($_data->ssl_port!="443"?":".$_data->ssl_port:"")?>/<?=RootPath.SecureDir?>venderlogin.php';
		}
	}
	<?}?>
}

function sslinfo() {
	window.open("<?=$Dir.FrontDir?>sslinfo.php","sslinfo","width=100,height=100,scrollbars=no");
}

</script>
</head>
<body onload="document.loginform.id.focus()" BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0 background="/admin/images/login_bg.jpg">

<!--
<table cellpadding="0" cellspacing="0" width="840" align="center">
<form method=post name=loginform action="<?=$Dir.VenderDir?>loginproc.php" onsubmit="return CheckLogin();">
<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["VLOGN"]=="Y") {?>
<input type=hidden name=shopurl value="<?=getenv("HTTP_HOST")?>">
<?}?>
<tr>
	<td colspan="3"  height="146"></td>
</tr>
<tr>
    <tr>
        <td colspan="3"  height="23" background="/admin/images/login_o_t_topbg.jpg"></td>
    </tr>
    <tr>
        <td width="22" background="/admin/images/login_o_t_leftbg.jpg"></td>
		<td>
			<TABLE  BORDER=0 CELLPADDING=0 CELLSPACING=0>
				<TR>
					<TD width="429" background="/admin/images/login_table_bg.jpg" valign="top">
                        <table align="center" cellpadding="0" cellspacing="0" width="80%">
                            <tr>
                                <td  height="30"></td>
                            </tr>
                            <tr>
                                <td ><img src="/admin/images/admin_img01_minishop.gif" border="0"></td>
                            </tr>
                            <tr>
                                <td  height="13"></td>
                            </tr>
                            <tr>
                                <td >
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td width="236" style="padding-bottom:4px"><input type="text" name="id" value="" size="20" maxlength="20" style="color:#333333; background-color:rgb(242,242,242); border-width:1px; border-color:#EBEBEB; border-style:solid; height:32px;width:96%;font-size:16px;font-weight:bold;padding-top:6px; padding-left:6px;" tabindex="1"></td>
                                            <td width="107" rowspan="2"><input type="image" src="/admin/images/admin_btn01.gif"  border="0" tabindex="3" style="width:70px;height:70"></td>
                                        </tr>
                                        <tr>
                                            <td width="236"><input type="password" name="passwd" value="" size="20" maxlength="20" style="color:#333333; background-color:rgb(242,242,242); border-width:1px; border-color:#EBEBEB; border-style:solid; height:32px;width:96%;font-size:16px;font-weight:bold;padding-top:6px; padding-left:6px;" tabindex="2"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td  height="27">
								<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["VLOGN"]=="Y") {?>
								<input type=checkbox name=ssllogin value="Y"> <A HREF="javascript:sslinfo()">보안 접속</A>
								<?}?></td>
                            </tr>
                            <tr>
                                <td><span style="color:#818289;font-family:돋움;font-size:11px;letter-spacing:0pt;line-height:16px"><?=getenv("HTTP_HOST")?>에서 부여받은 <b>운영자/부운영자</b>의<br>ID와 Password를 입력하세요.<br />
								로그인이 되지 않을 경우 입점문의로 문의바랍니다.</span>
								</td>
							</tr>
							<tr>
								<td>
									<div style="width:100%;">
										<ul style="list-style:none; margin:0px; padding:0px;">
											<li style="color:#818289; font-family:돋움; font-size:11px; font-weight:bold;">비밀번호관리에 주의하세요!</span></li>
											<li style="font-family:돋움; font-size:11px; font-weight:bold;"><a href="/front/venderProposal.php">[입점문의 바로가기]</a></li>
										</ul>
									</div>
								</td>
                            </tr>
                        </table>
					</TD>
					<TD width="366" background="/admin/images/login_banner_bg.jpg" valign="top">
					<table  cellpadding="0" cellspacing="0" width="345" style="margin-top:8px;margin-left:10px;">
						<tr>
							<td><iframe src="http://www.getmall.co.kr/frames/login_banner.php"  WIDTH="345px" height="190px" frameborder="0" scrolling="no" marginwidth="0" marginheight="0" name="loginbanner" ></iframe></td>
						</tr>
						<tr>
							<td height="5"></td>
						</tr>
						<tr>
							<td align="center">
								<table cellpadding="0" cellspacing="0" width="90%">
									<tr>
										<td colspan="2" style="padding-top:5px;padding-bottom:10px;"><img src="/admin/images/login_notice.gif" border="0"></td>
									</tr>
									<tr>
										<td>
											<table cellpadding="0" cellspacing="0" width="100%"><iframe src="http://www.getmall.co.kr/frames/login_notice.php"  WIDTH="100%" height="100px" frameborder="0" scrolling="no" marginwidth="0" marginheight="0" name="loginnotice" ></iframe>	</table>
										</td>
									</tr>
								 </table>
							</td>
						</tr>
						<tr><td height="18"></td></tr>
					 </table>
					</TD>
				</TR>
			</TABLE>
        </td>
        <td width="23" background="/admin/images/login_o_t_rightbg.jpg"></td>
    </tr>
    <tr>
        <td colspan="3" width="840" height="57" background="/admin/images/login_o_t_downbg.jpg"></td>
    </tr>
    <tr>
        <td colspan="3" align="center"><a href="http://getmall.co.kr" target="_blank"><img src="/admin/images/login_getmall_logo.gif" border="0" align="absmiddle"><span style="font-family:Verdana; font-size:9px;"><font color="#999999">Copyright&copy;</font><font color="#FF6600"><b>getmall.co.kr</b></font><font color="#999999">.
All Rights reserved.</font></span></a></td>
    </tr>


</form>
</table>

-->
<style>
		html, body {margin: 0;padding: 0;height: 100%;width:100%;font-family:Montserrat,Noto Sans KR,맑은고딕,Nanum Gothic,나눔고딕,돋움,sans-serif;}
		body {background: #d5dae3;}
		.h1{font-size:27px;color:#465065;letter-spacing:-1px;font-weight:600;}
		.venderLoginWrap{display:table;width:100%;height:100%;}
		.venderLoginWrap .loginalign{display:table-cell;height:100%;vertical-align:middle;text-align:center;}
		.venderLoginWrap .loginalign .contents{display:inline-block;*display:inline;*zoom:1;width:460px;height:370px;background:#ffffff;text-align:center;border-radius:10px;box-shadow: 8px 5px 5px #c7cbd4;padding:70px;line-height:150%;letter-spacing:-1px;}
		.contents a{text-decoration:none;color:#465065;}
		.venderLoginWrap .loginalign .contents .notice{font-size:13px;color:#b1b7c4;letter-spacing:-1px;padding-bottom:20px;}
		.input{width:100%;height:40px;color:#465065;background:#ffffff;border:0px;border-bottom:1px solid #EBEBEB;font-size:18px;padding-left:10px;}
		.idPassword{width:80%;margin:0px auto;}
		.copyright{text-align:center}
		.copyright a{text-decoration:none;}
		.copyright a span{color:#9fa7b8;font-size:13px;}
		.btn{background:rgba(62, 218, 140, 0.8);width:370px;height:70px;color:#ffffff;font-size:20px;font-weight:bold;border:none;border-radius:40px 40px 40px 40px}
		.btn1{background:#8f9aad;height:70px;color:#ffffff;font-size:20px;font-weight:bold;border:none;border-radius:40px 40px 40px 40px;position:absolute;right:0px;top:0px;background:#8f9aad;display:inline-block;width:40%;}
		.btn1 span{color:#ffffff;display:inline-block;padding:25px 0px 0px 13px;}
		.font_blue{color:#8eaadd;}
</style>
<div  class="venderLoginWrap">
	<div class="loginalign">
		<div class="contents">
			<h1 class="h1">미니샵(입점운영자) 로그인</h1>
			<p class="notice"><?=getenv("HTTP_HOST")?>에서 부여받은 <b>운영자/부운영자</b>의 ID와 Password를 입력하세요.<br />로그인이 되지 않을 경우 입점문의로 문의바랍니다.</span><br>
			<span class="font_blue">( 비밀번호관리에 주의하세요! )</span></p>
			<div class="idPassword">
				<form method=post name=loginform action="<?=$Dir.VenderDir?>loginproc.php" onsubmit="return CheckLogin();">
				<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["VLOGN"]=="Y") {?>
				<input type=hidden name=shopurl value="<?=getenv("HTTP_HOST")?>">
				<?}?>
				<p><input type="text" name="id" value="" size="20" maxlength="20"class="input" tabindex="1" placeholder="아이디"></p>
				<p><input type="password" name="passwd" value="" size="20" maxlength="20" class="input" tabindex="2" placeholder="비밀번호"></p>
				<p>
					<?if($_data->ssl_type=="Y" && strlen($_data->ssl_domain)>0 && strlen($_data->ssl_port)>0 && $_data->ssl_pagelist["VLOGN"]=="Y") {?>
					<input type=checkbox name=ssllogin value="Y"> <A HREF="javascript:sslinfo()">보안 접속</A>
					<?}?>
				</p>
				<p style="position:relative;">
				<input class="btn" type="submit" value="로그인" style="position:absolute;left:0px;top:0px;width:70%;z-index:9999;">
				<a class="btn1" href="/front/venderProposal.php" target="_blank"><span>입점문의</span></a>
				<!--<input type="image" src="images/admin_btn01.gif" style="border:none;" tabindex="3" />--></p>
			</form>
			</div>
		</div>
		<p class="copyright"><a href="http://jamkan.com" target="_blank"><span>Managed by jamkan.com </span></a></p>
	</div>
</div>



</center>
</body>
</html>