<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$num=$_REQUEST["num"];
$type=$_REQUEST["type"];
$no=$_REQUEST["no"];

$sql = "SELECT * FROM tbleventpopup WHERE num='".$num."' ";
$result=mysql_query($sql,get_db_conn());
if($row=mysql_fetch_object($result)) {
	if($type=="close") {
		if($no=="yes") {
			$cookiename="eventpopup_".$row->num;
			if($row->cookietime==2)			//�ٽÿ�������
				setcookie($cookiename,$row->end_date,time()+(60*60*24*30),"/".RootPath);
			else if($row->cookietime==1)	//�Ϸ絿�� ��������
				setcookie($cookiename,$row->end_date,time()+(60*60*24*1),"/".RootPath);
			else							//������ ���ᶧ���� ��������
				setcookie($cookiename,$row->end_date,0,"/".RootPath);
		}
		mysql_free_result($result);
		echo "<script>window.close();</script>";
		exit;
	} else {
		if($row->frame_type=="1") {	//���������� ���
			if($row->scroll_yn=="Y") $scroll="yes";
			else $scroll="no";

			echo "<html>\n";
			echo "<head>\n";
			echo "<title>".$row->title."</title>\n";
			echo "<meta http-equiv=\"CONTENT-TYPE\" content=\"text/html; charset=EUC-KR\">\n";
			echo "</head>\n";
			echo "<frameset rows=\"*,26\" border=0 MARGINWIDTH=0 MARGINHEIGHT=0 noresize>\n";
			echo "<frame src=\"".$Dir.FrontDir."event_frame.php?type=".$row->design."&one=1&num=".$row->num."\" name=event MARGINWIDTH=0 MARGINHEIGHT=0 scrolling=".$scroll.">\n";
			echo "<frame src=\"".$Dir.FrontDir."event_bottom.php?num=".$row->num."\" name=bottom MARGINWIDTH=0 MARGINHEIGHT=0 scrolling=no>\n";
			echo "</frameset>\n";
			echo "</html>";
			mysql_free_result($result);
		} else if($row->frame_type=="0") {	//���������� ���
			include ($Dir.TempletDir."event/event".$row->design.".php");
		} else {	//���̾� Ÿ���� �׳� �ݴ´�.
			echo "<script>window.close();</script>";
			exit;
		}
	}
} else {
	echo "<script>window.close();</script>";
	exit;
}
?>