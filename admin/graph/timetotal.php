<?
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

if(strlen($_ShopInfo->getId())==0) exit;

$type=$_REQUEST["type"];
$date=$_REQUEST["date"];

$year=substr($date,0,4);
$mon=substr($date,4,2);
$day=substr($date,6,2);

Header("Content-type: image/gif");
$im=ImagecreateFromGif("img/timetotal_graph.gif");
$im2=ImagecreateFromGif("img/graph_dot.gif");
$im3=ImagecreateFromGif("img/graph_dot2.gif");
$im4=ImagecreateFromGif("img/graph_dot.gif");
$im5=ImagecreateFromGif("img/graph_dot.gif");
$im6=ImagecreateFromGif("img/graph_dot.gif");
$white = ImageColorAllocate($im,255,255,255);
$dot = ImageColorAllocate($im,255,60,0);
$dot2 = ImageColorAllocate($im,127,127,127);
$poly = ImageColorAllocate($im,252,212,166);
$black = ImageColorAllocate($im,0,0,0);
$blue = ImageColorAllocate($im,0,0,255);
$red = ImageColorAllocate($im,255,0,0);

$topvalue=0;
$toporder=0;
$toppage=0;

$month= date("m");
if($type=="d") {
	$prevdate=date("YmdH",mktime(0,0,0,$mon,$day-1,$year));
	if($date==date("Ymd")) $date=date("YmdH",mktime(date("H"),0,0,$mon,$day,$year));
	else $date=$date."99";
	$sql ="SELECT cnt,pagecnt,MID(date,7,4) as hour FROM tblcounter ";
	$sql.="WHERE (date>='".$prevdate."' && date<='".$date."')";
	$sql2 ="SELECT cnt,MID(date,7,4) as hour FROM tblcounterorder ";
	$sql2.="WHERE (date>='".$prevdate."' && date<='".$date."')";
} else if($type=="w") {
	$prevdate=date("Ymd00",mktime(0,0,0,date("m"),date("d")-7,date("Y")));
	$nextdate=date("Ymd99");
	$sql= "SELECT SUM(cnt) as cnt,SUM(pagecnt) as pagecnt,MID(date,9,2) as hour FROM tblcounter ";
	$sql.="WHERE (date<='".$nextdate."' AND date>='".$prevdate."') GROUP BY hour ";
	$sql2 ="SELECT SUM(cnt) as cnt,MID(date,9,2) as hour FROM tblcounterorder ";
	$sql2.="WHERE (date<='".$nextdate."' AND date>='".$prevdate."') GROUP BY hour ";
} else if($type=="m") {
	$date=date("Ym");
	$sql ="SELECT SUM(cnt) as cnt,SUM(pagecnt) as pagecnt,MID(date,9,2) as hour FROM tblcounter ";
	$sql.="WHERE date LIKE '".$date."%' GROUP BY hour ";
	$sql2 ="SELECT SUM(cnt) as cnt,MID(date,9,2) as hour FROM tblcounterorder ";
	$sql2.="WHERE date LIKE '".$date."%' GROUP BY hour ";
}

$result = mysql_query($sql,get_db_conn());
while($row = mysql_fetch_object($result)){
	$time[$row->hour]=$row->cnt;
	$page[$row->hour]=$row->pagecnt;
	if($topvalue<$row->cnt) $topvalue=$row->cnt;
	if($toppage<$row->pagecnt) $toppage=$row->pagecnt;
}
mysql_free_result($result);

$result = mysql_query($sql2,get_db_conn());
while($row = mysql_fetch_object($result)){
	$order[$row->hour]=$row->cnt;
	if($toporder<$row->cnt) $toporder=$row->cnt;
}
mysql_free_result($result);

if($topvalue<10) $max=10;
else if($topvalue<20) $max=20;
else if($topvalue<30) $max=30;
else if($topvalue<40) $max=40;
else if($topvalue<50) $max=50;
else if($topvalue<60) $max=60;
else if($topvalue<70) $max=70;
else if($topvalue<80) $max=80;
else if($topvalue<90) $max=90;
else if($topvalue<100) $max=100;
else {
	$max = ceil(($topvalue*1.1)/10)*10;
}
$value=($max/10);
$top=$max+$value;

if($toppage<10) $maxpage=10;
else if($toppage<20) $maxpage=20;
else if($toppage<30) $maxpage=30;
else if($toppage<40) $maxpage=40;
else if($toppage<50) $maxpage=50;
else if($toppage<60) $maxpage=60;
else if($toppage<70) $maxpage=70;
else if($toppage<80) $maxpage=80;
else if($toppage<90) $maxpage=90;
else if($toppage<100) $maxpage=100;
else {
	$maxpage = ceil(($toppage*1.1)/10)*10;
}
$pvalue=($maxpage/10);
$pagetop=$maxpage+$pvalue;

if($toporder<10) $maxorder=10;
else if($toporder<20) $maxorder=20;
else if($toporder<30) $maxorder=30;
else if($toporder<40) $maxorder=40;
else if($toporder<50) $maxorder=50;
else if($toporder<60) $maxorder=60;
else if($toporder<70) $maxorder=70;
else if($toporder<80) $maxorder=80;
else if($toporder<90) $maxorder=90;
else if($toporder<100) $maxorder=100;
else {
	$maxorder = ceil(($toporder*1.1)/10)*10;
}
$orvalue=($maxorder/10);
$topor=$maxorder+$orvalue;

for($i=0;$i<10;$i++) {
	$num=$value*(10-$i);
	$num2=$pvalue*(10-$i);
	$num3=$orvalue*(10-$i);

	$ynumber=79+((($i/2)-1)*36);
	$ynumber2=354+((($i/2)-1)*36);
	$ynumber3=622+((($i/2)-1)*36);

	$xnumber=35-strlen(number_format($num))*3;
	$xnumber2=35-strlen(number_format($num2))*3;
	$xnumber3=35-strlen(number_format($num3))*3;

	//imageString($im,2,$xnumber,$ynumber,number_format($num),$black);
	//imageString($im,2,$xnumber2,$ynumber2,number_format($num2),$black);
	//imageString($im,2,$xnumber3,$ynumber3,number_format($num3),$black);
	imagettftext($im,6,0,$xnumber,$ynumber,$black,"font/kroeger.ttf",number_format($num));
	imagettftext($im,6,0,$xnumber2,$ynumber2,$black,"font/kroeger.ttf",number_format($num2));
	imagettftext($im,6,0,$xnumber3,$ynumber3,$black,"font/kroeger.ttf",number_format($num3));
}

$date=$prevdate;
$count=0;
$curhour=date("dH");
if($day==date("d")) $end="YES";
for($i=0;$i<24;$i++){
	if($type=="d"){
		$predate=date("dH",mktime($i,0,0,$mon,$day-1,$year));
		$curdate=date("dH",mktime($i,0,0,$mon,$day,$year));
	}
	$x=45+(($i/2)*52)+5;
	$x2=45+(($i/2)*52)+5;
	$x3=45+(($i/2)*52)+5;
	if($type=="d"){
		$y=221-($time[$curdate]/$top)*200;
		$y2=221-($time[$predate]/$top)*200;
		$y3=495-($page[$curdate]/$pagetop)*200;
		$y4=495-($page[$predate]/$pagetop)*200;
		$y6=763-($order[$predate]/$topor)*200;
	}else{
		$curdate=substr("0".$i,-2);
		$y=221-($time[$curdate]/$top)*200;
		$y3=495-($page[$curdate]/$pagetop)*200;
	}
	$y5=763-($order[$curdate]/$topor)*200;

	if($i<>0){
		if($type=="d"){
			imageline($im,$prevx2+1,$prevy2,$x2+1,$y2,$dot2);
			imageline($im,$prevx2+1,$prevy4,$x2+1,$y4,$dot2);
			imageline($im,$prevx2+1,$prevy6,$x2+1,$y6,$dot2);
		}
		if($type<>"d" || ($type=="d" && ($curhour>=$curdate || $end!="YES"))){
			imageline($im,$prevx+1,$prevy,$x+1,$y,$dot);
			imageline($im,$prevx+1,$prevy3,$x+1,$y3,$dot);
		}
	}
	if($i<>0 && $i<>23){
		if($type=="d"){
			ImageCopyResized($im,$im3,$x2-1,$y2-1,0,0,5,5,5,5);
			ImageCopyResized($im,$im3,$x2-1,$y4-1,0,0,5,5,5,5);
			ImageCopyResized($im,$im3,$x2-1,$y6-1,0,0,5,5,5,5);
		}
		if($type<>"d" || ($type=="d" && ($curhour>=$curdate || $end!="YES"))){
			ImageCopyResized($im,$im2,$x-1,$y-1,0,0,5,5,5,5);
			ImageCopyResized($im,$im2,$x-1,$y3-1,0,0,5,5,5,5);
		}
	}
	if($order[$curdate]>0){
		$height=ceil(763-$y5);
		if($i==0) ImageCopyResized($im,$im5,$x3+2,$y5,0,0,5,$height,5,1);
		else if($i<>23) ImageCopyResized($im,$im4,$x3-2,$y5,0,0,7,$height,7,1);
		else ImageCopyResized($im,$im6,$x3-4,$y5,0,0,5,$height,5,1);
	}

	$prevx=$x;$prevy=$y;
	$prevx2=$x2;$prevy2=$y2;
	$prevy3=$y3;$prevy4=$y4;
	$prevy6=$y6;
}
ImageGif($im);
ImageDestroy($im);
imageDestroy($im2);
imageDestroy($im3);
imageDestroy($im4);
imageDestroy($im5);
imageDestroy($im6);
?>