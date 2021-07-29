<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

require_once($Dir."lib/phpmailer/class.phpmailer.php");

$shopurl=urldecode($_POST["shopurl"]);
$email=urldecode($_POST["email"]);
$cert_key=urldecode($_POST["cert_key"]);

//이메일 발송
$info_email	= "shop@jamkan.com";
$shopname = "jamkan";
$send_day = date("Y-m-d H:i:s");

$subject = "회원가입 이메일 인증번호입니다.";
$charset = "UTF-8";
$buffer="";

if($email and $cert_key){
	if(file_exists(DirPath."templet/mail/join_certificate.php")) {
		$fp=fopen(DirPath."templet/mail/join_certificate.php","r");
		if($fp) {
			while (!feof($fp)) {$buffer.= fgets($fp, 1024);}
		}
		fclose($fp);
		$body=$buffer;
	}

	if(strlen($body)>0) {
		$curdate = date("Y년 m월 d일");
		$pattern = array ("(\{이메일\})","(\{요청일\})","(\{인증번호\})","(\[URL\])","(\[SHOP\])","(\[CURDATE\])");
		$replace = array ($email,$send_day,$cert_key,$shopurl,$shopname,$curdate);
		$body	 = preg_replace($pattern,$replace,$body);
		if (strlen($shopname)>0) $mailshopname = "=?".$charset."?B?".base64_encode($shopname)."?=";
		$subject = '=?'.$charset.'?B?'.base64_encode(strtr($subject,"\r\n",'  ')).'?='; // 아웃룩 등에서 메일 제목 깨지는 것 관련해서 처리
		$header=getMailHeader($mailshopname,$info_email);
		if($email) {

			$mail = new PHPMailer();

			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host = "mail.yourdomain.com";	// SMTP server
			$mail->SMTPDebug = 1;						// enables SMTP debug information (for testing)
																// 1 = errors and messages
																// 2 = messages only
			$mail->SMTPAuth = true;						// enable SMTP authentication
			$mail->SMTPSecure = "ssl";					// sets the prefix to the servier
			$mail->Host = "smtp.gmail.com";			// sets GMAIL as the SMTP server
			$mail->Port = 465;								// set the SMTP port for the GMAIL server
			$mail->Username = "barem212@gmail.com";		// GMAIL username
			$mail->Password = "getmall1004";			// GMAIL password

			$mail->SetFrom('shop@jamkan.com', 'jamkan');
			$mail->AddReplyTo("shop@jamkan.com","jamkan");
			$mail->CharSet = "utf-8";
			$mail->Subject = $subject;
			$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
			$mail->MsgHTML($body);

			$address = $email;
			$mail->AddAddress($address, "jamkan");

			if(!$mail->Send()) {
				echo "111";
			} else {
				echo "000";
			}
		}else{
			echo "111";
		}
	}else{
		echo "222";
	}
}else{
	echo "333";
}
?>