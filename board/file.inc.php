<?
if(substr(getenv("SCRIPT_NAME"),-13)=="/file.inc.php") {
	header("HTTP/1.0 404 Not Found");
	exit;
}

//�Խ��� ���丮 ���� �� ����
function ProcessBoardDir($board,$mode) {
	if(strlen($board)==0 || strlen($mode)==0) {
		$resdata="FAIL";
	} else {
		if($mode=="create") {
			if(is_dir(DirPath.DataDir."shopimages/board")==false) {
				mkdir(DirPath.DataDir."shopimages/board");
				chmod(DirPath.DataDir."shopimages/board",0707);
			}
			if(is_dir(DirPath.DataDir."shopimages/board/".$board)==false) {
				mkdir(DirPath.DataDir."shopimages/board/".$board);
				chmod(DirPath.DataDir."shopimages/board/".$board,0707);
			}
			$resdata="OK";
		} else if($mode=="delete") {
			if(is_dir(DirPath.DataDir."shopimages/board/".$board)==false) {
				$resdata="OK";
			} else {
				proc_rmdir(DirPath.DataDir."shopimages/board/".$board);
				$resdata="OK";
			}
		} else {
			$resdata="FAIL";
		}
	}
}

// ���ϵ��
function ProcessBoardFileIn($board,$file_name) {
	$resdata="SUCCESS";

	if (empty($board) || empty($file_name)) {
		$resdata="FAIL";
	}

	$file       = DirPath.DataDir."cache/board/".$board.".".$file_name;
	$file_mini  = DirPath.DataDir."cache/board/".$board.".thumbnail.".$file_name;
	$dirpath    = DirPath.DataDir."shopimages/board/".$board;
	$file2      = DirPath.DataDir."shopimages/board/".$board."/".$file_name;
	$file2_mini = DirPath.DataDir."shopimages/board/".$board."/thumbnail.".$file_name;

	if (file_exists("$file")==false) {
		$resdata="FAIL";
	} else if ($resdata!="FAIL") {
		if (is_dir("$dirpath")==false) {
			mkdir($dirpath);
			chmod($dirpath,0777);
		}
		copy ($file,$file2);
		unlink($file);

		if (file_exists($file_mini)==true) {
			copy ($file_mini,$file2_mini);
			unlink($file_mini);
		}
	}
	return $resdata;
}

//���ϻ���
function ProcessBoardFileDel($board,$file_name) {
	$resdata="SUCCESS";

	if (empty($board) || empty($file_name)) {
		$mess="FAIL";
	}

	$file      = DirPath.DataDir."shopimages/board/".$board."/".$file_name;
	$file_mini = DirPath.DataDir."shopimages/board/".$board."/thumbnail.".$file_name;

	if (file_exists("$file")==false) {
		$resdata="FAIL";
	} else {
		unlink($file);
		if (file_exists("$file_mini")==true) {
			unlink($file_mini);
		}
	}
	return $resdata;
}

//���ϼ���
function ProcessBoardFileModify($board,$file_name,$oldfile_name) {
	$resdata="SUCCESS";

	if (empty($board) || empty($file_name)) {
		$resdata="FAIL";
	}

	$file       = DirPath.DataDir."cache/board/".$board.".".$file_name;
	$file_mini  = DirPath.DataDir."cache/board/".$board.".thumbnail.".$file_name;
	$dirpath    = DirPath.DataDir."shopimages/board/".$board;
	$file2      = DirPath.DataDir."shopimages/board/".$board."/".$file_name;
	$file2_mini = DirPath.DataDir."shopimages/board/".$board."/thumbnail.".$file_name;

	$file3 = DirPath.DataDir."shopimages/board/".$board."/".$oldfile_name;
	$file3_mini = DirPath.DataDir."shopimages/board/".$board."/thumbnail.".$oldfile_name ;

	if (file_exists("$file")==false) {
		$resdata="FAIL";
	} else if ($resdata!="FAIL") {
		if (is_dir("$dirpath")==false) {
			mkdir($dirpath);
			chmod($dirpath,0777);
		}
		copy ($file,$file2);
		unlink($file);

		if (file_exists($file_mini)==true) {
			copy ($file_mini,$file2_mini);
			unlink($file_mini);
		}
	}

	//���������� ����
	if ($resdata=="SUCCESS" && strlen($oldfile_name)>0) {
		@unlink($file3);
		@unlink($file3_mini);
	}
	return $resdata;
}

//���� �뷮 ���Ѵ�.
function ProcessBoardFileSize($board,$file_name) {
	$size = "0";

	if (empty($BOARD) || empty($file_name)) {
		$size="0";
	}

	$file=DirPath.DataDir."shopimages/board/".$board."/".$file_name;

	if (file_exists("$file")==false) {
		$size="0";
	} else {
		$size = filesize($file);
		if ($size>=1024 && $size<=1024000) {
			$size = (int)($size/1024);
			$size = "$size KB";
		} else if ($size>=1024000) {
			$size = (int)($size/102400);
			$size /= 10;
			$size = "$size M";
		}
	}
	return $size;
}

// ������ ����ũ�⸦ ���Ѵ�.
function ProcessBoardFileWidth($board,$file_name) {
	$resdata="0";

	if (empty($board) || empty($file_name)) {
		$resdata="0";
	}

	$file=DirPath.DataDir."shopimages/board/".$board."/".$file_name;

	if (file_exists("$file")==false) {
		$resdata="0";
	} else {
		$size=getimagesize($file);
		$resdata=$size[0];
	}
	return $resdata;
}

//÷������ �ٿ�ε� ��ũ
function FileDownload($board,$file_name) {
	return "<a href=\"".DirPath.BoardDir."download.php?board=".$board."&file_name=".urlencode($file_name)."\" target=_top>".$file_name."</a>";
}

//�̹��� ÷������ url
function ImageAttachUrl($board,$file_name) {
	$ext = strtolower(substr(strrchr($file_name,"."),1));
	if($ext=="gif" || $ext=="jpg" || $ext=="png") {
		return DirPath.DataDir."shopimages/board/".$board."/".urlencode($file_name);
	} else {
		return "";
	}
}

function ImageMiniUrl($board,$file_name) {
	$ext = strtolower(substr(strrchr($file_name,"."),1));
	if($ext=="gif" || $ext=="jpg" || $ext=="png") {
		return DirPath.DataDir."shopimages/board/".$board."/thumbnail.".urlencode($file_name);
	} else {
		return "";
	}
}
?>