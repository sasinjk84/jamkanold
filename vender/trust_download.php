<?
//ok
$Dir="../";
include_once($Dir."lib/init.php");

$dir =$_GET["dir"];
$file_name  =$_GET["file_name"];

if(!$dir || !$file_name) exit;


$attachfileurl=getenv("DOCUMENT_ROOT")."/".RootPath.$dir.$file_name;

if(file_exists($attachfileurl)) {
	$file = $attachfileurl;

	if(strpos(" ".$file,"..")==true) exit;

	Header("Content-Disposition: attachment; filename=$file_name");
	Header("Content-Type: application/octet-stream;");
	Header("Pragma: no-cache");
	Header("Expires: 0");
	Header("Content-type: application/octet-stream");

	readfile($file);
}
?>