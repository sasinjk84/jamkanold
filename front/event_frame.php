<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$type = $_REQUEST[type];	//design
$one = $_REQUEST[one];		//������������ ������������...
$num = $_REQUEST[num];		//�̺�Ʈ ������ȣ

include ($Dir.TempletDir."event/event".$type.".php");
?>
