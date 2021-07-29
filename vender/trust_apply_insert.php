<?
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/venderlib.php");
INCLUDE ("access.php");
include_once($Dir."lib/admin_more.php");

$tm_idx=$_POST["tm_idx"];
$mode=$_POST["mode"];

$imageKind = array('image/pjpeg','image/jpeg','image/JPG','image/X-PNG','image/PNG','image/png','image/x-png');
$itemfileURL = $_SERVER['DOCUMENT_ROOT']."/data/trust_item/";

$upload_photo = "";

for($i=0;$i<$_POST['image_count'];$i++){
	$image_id = "image_".$i;
	//$image_file = time().$i.".jpg";
	//$image_file = $_FILES[$image_id]['name'];
	
	$exte = explode(".",$_FILES[$image_id]['name']);
	$exte = $exte[ count($exte)-1 ];
	$image_file = "item_".date("YmdHis").$i.".".$exte;
	
	$upload_photo.=$image_file.","; //배열로 다시 저장함

	if(isset($_FILES[$image_id]) && !$_FILES[$image_id]['error']){
		if(in_array($_FILES[$image_id]['type'],$imageKind)){
			if(move_uploaded_file($_FILES[$image_id]['tmp_name'],$itemfileURL.$image_file)){
				//echo "success";
			}else{
				echo "error";
			}
		}else{
			echo "not image type";
		}
	}
}
echo $upload_photo;

?>