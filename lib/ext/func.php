<?php
if(!defined('NL')) define('NL',"\r\n");

// ���������� ���� Ȯ���ؼ� ���ʷ� ���� �� ���� ��ȯ
function pick(){
	$args = func_get_args();
	$i=0;
	while($i < count($args)){
		if(!_empty($args[$i])) return $args[$i];
		$i++;
	}
	return;
}

// ������ ���������ʰų� ���� ���� ���
// empty �� 0 �� ��� true �� ��ȯ
function _empty($val){
	if(strlen(trim($val)) > 0) return false;
	else return true;
}



// �ڿ�����
// _isInt(Ȯ���� ��[,0�� ��������� ����
// �������̰� 1�̻� (0 ��� �ɼ��� true �̸� 0 �̻�) �ϰ�� treu, �׿ܴ� false
function _isInt($val,$allowzero=false){
	if(is_numeric($val) && (($allowzero && $val >= 0) || $val > 0)) return true;
	else return false;
}

//Ÿ���� �迭�̸� ��Ұ� 1�� �̻��ϰ�� ��
function _array($arr){
	return (is_array($arr) && !empty($arr))?true:false;
}

//���� ���� ���
function _pr($obj){
	@header("Content-type: text/html; charset=euc-kr");
	echo '<pre>'.print_r($obj,true).'</pre>';
}


// ���� ���ͽ��� ���� addslsahes
function _addslashes($str){
	if(_array($str)){
		foreach($str as $k=>$v) $str[$k] = _addslashes($v);
	}else{
		if(!get_magic_quotes_gpc()) $str = addslashes($str);
	}
	return $str;
}

// Sql injection ������ mysql_real_escape_string �� % �� _ �� escape ���� �ʴ´�.
// _escape(��[,���ڿ� ����ǥ ó�� ����]);
// �ɼ��� true �̸� ���ڿ��� ��� ���� ����ǥ�� ���� ���·� ��� (default)
function _escape($str,$usesql=true){
	if(_array($str)){
		foreach($str as $k=>$v) $str[$k] = _escape($v,$usesql);
	}else{
		if(get_magic_quotes_gpc()) $str = stripslashes($str);
		$str = mysql_real_escape_string($str);
		if($usesql) $str = "'".$str."'";
	}
	return $str;
}

// �迭�� ��������� trim ó�� �׿ܴ� trim �� ����
function _trim(&$val){
	if(is_array($val)) $val = array_map("trim",$val);
	else $val = trim($val);
	return $val;
}

//���� ���鹮�ڰ� ������ ��
function inspace($val){
	return eregi("[[:space:]]",$val);
}

// ���ڿ� ���� (�̻��� �����϶��� . �� ǥ��)
function strcut($str,$len,$suffix="."){
	if($len >= strlen($str)) return $str;
	$klen = $len - 1;
	while(ord($str[$klen]) & 0x80) $klen--;
	return substr($str, 0, $len - (($len + $klen + 1) % 2)).$suffix;
}


function sizebyte($size,$range=2,$humansize=false,$shotunity=false){
	$unity = array('B','KB','MB','GB','TB');
	if(!_isInt($size,true)) return '0';
	if(!_isInt($range,true)) $range = 0;
	$base = ($humansize !== false)?1000:1024;
	$e = floor(log($size,$base));
	if($e > count($unity)) return 'Out of Range';
	$rsize = round($size/pow($base,$e),$range);
	return $rsize.' '.$unity[$e];
}


//���â ���� ������ �̵�
// _alert(�޽���[,�̵��� �ּ� [,������ ��ü ����]]);
// ������ ��ü ���ΰ� false �̸� ������ �̵����� ó�� (history �� �ּ� �߰�����)
// �̿��� ���� ������ ġȯ���� ó��( history �󿡼� ������ �ּҰ� ��ü�� default)
function _alert($msg,$url=NULL,$replace=true){	//������ ��ȯ
	@header("Content-type: text/html; charset=euc-kr");
	ob_get_clean();
	ob_start();
	$compexit = false; // ���α׷� ���� ���� 
	echo '<script type="text/javascript" language="javascript">'.NL;
	if(!empty($msg)) echo "window.alert('".addslashes($msg)."');".NL;
	if(!_empty($url)){
		if(is_numeric($url)){
			if($url == 0) echo 'window.close();'.NL;
			else echo "window.history.go(-".abs($url).");".NL;
		}else{
			if($replace) echo "document.location.replace('".trim($url)."');".NL;
			else echo "document.location.href('".trim($url)."');".NL;
		}
		$compexit = true; // ������ �ּ� ������ �ִ� ���� ���� ó����.
	}
	echo '</script>'.NL;
	ob_end_flush();
	if($compexit === true) exit;
}

// ���ڿ� ��� �ϰ� ����
function _exit($msg){
	@header("Content-type: text/html; charset=euc-kr");
	exit($msg);
}


function _encode(&$value,$key){
	if(is_array($value)){
		array_walk($value,'_encode');
	}else{
		//$value = urlencode($value);
		$value = iconv('EUC-KR','UTF-8',$value);
	}
}

function _iconvFromUtf8(&$value,$key){
	if(is_array($value)){
		@array_walk($value,'_iconvFromUtf8');
	}else{
		//$value = urlencode($value);
		$value = iconv('UTF-8','EUC-KR',$value);
	}
}


/* imagecolorallocate �� ����� ���� �ϳ� ���� RGB �ڵ带 ������ 16���� ���ڿ��� ���� �޾� ó����
    EX)  $white = _imagecolorallocate($im,'#ffffff');

*/
function _imagecolorallocate($im,$colorstr){
	$colorstr = str_replace('#','',$colorstr);
	$colorstr = str_split($colorstr,2);
	for($i=0;$i<3;$i++) $colorstr[$i] = hexdec($colorstr[$i]);
	return imagecolorallocate($im,$colorstr[0],$colorstr[1],$colorstr[2]);
}
/*
if (!function_exists('json_encode')) {
    function json_encode($data) {
        switch ($type = gettype($data)) {
            case 'NULL':
                return 'null';
            case 'boolean':
                return ($data ? 'true' : 'false');
            case 'integer':
            case 'double':
            case 'float':
                return $data;
            case 'string':
                return '"' . strtr(addslashes($data), array("\\'"  => "'")) . '"';
            case 'object':
                $data = get_object_vars($data);
            case 'array':
                $output_index_count = 0;
                $output_indexed = array();
                $output_associative = array();
                foreach ($data as $key => $value) {
                    $output_indexed[] = json_encode($value);
                    $output_associative[] = json_encode($key) . ':' . json_encode($value);
                    if ($output_index_count !== NULL && $output_index_count++ !== $key) {
                        $output_index_count = NULL;
                    }
                }
                if ($output_index_count !== NULL) {
                    return '[' . implode(',', $output_indexed) . ']';
                } else {
                    return '{' . implode(',', $output_associative) . '}';
                }
            default:
                return ''; // Not supported
        }
    }
} */


function readAuthKey(){
	global $Dir;
	$authkey = '';
	if($f=@file($Dir.AuthkeyDir.".shopaccess")) {
		$authkey=trim($f[0]);
	}
	return $authkey;
}



function objectToArray($object){
	if(!is_object($object) && !is_array($object)){
		return $object;
	}
	if(is_object($object)){
		$object = get_object_vars($object);
	}
	return array_map('objectToArray',$object);
}

// �ð� ���� �߰�
function datediff($fdate,$sdate=''){
	$stamp1 = _strtotime($fdate,true);
	$stamp2 = !_empty($sdate)?_strtotime($sdate):time();
	return solvTimestamp($stamp1-$stamp2);
}

function datediff_rent($fdate,$sdate=''){	
	$stamp1 = _strtotime($fdate,true);
	$stamp2 = !_empty($sdate)?_strtotime($sdate):time();
	return solvTimestamp($stamp1-$stamp2,1);
}



function solvTimestamp($diff,$addSec=0){
	$result = array();
	if(_isInt($addSec)) $diff+=$addSec;
	$result['diff']    = $diff;	
	$result['isold']  = $diff < 0;
	$result['day']    = intval($diff / (24*60*60));	
	$tmp = $diff%(24*60*60);
	$result['hour']   = intval($tmp/3600);
	$tmp = $tmp%3600;
	$result['minute'] = intval($tmp/60);	
	$result['second'] = $tmp%60;
	return $result;	
}

function _strtotime($date,$isend=false){
	if(is_bool($isend)){
		if(preg_match('/^(2[0-9]{3})-?(0[1-9]|10|11|12)-?([0-2][1-9]|3[0-1]|[1-2]0)\s?(2[0-4]|[0-1]?[0-9])?(:[0-5]?[0-9]?)?(:[0-5]?[0-9]?)?.*$/',$date,$mat)){
			$date = $mat[1].'-'.$mat[2].'-'.$mat[3];		
			if(!_empty($mat[4])){
				if($isend){
					if($mat[4] == '24') $date .= ' '.($mat[4]-1).':59:59';
					else if(!_empty($mat[5]) && $mat[5] == ':59') $date .= ' '.$mat[4].$mat[5].':59';
					else $date = date('Y-m-d H:i:s',strtotime('-1 second',strtotime($date.' '.$mat[4].':00:00')));
				}else{
					$date .= ' '.$mat[4].':00:00';
				}
			}else if($isend){
				$date .= ' 23:59:59';
			}
		}
	}
	return strtotime($date);
}



?>