<?
if(substr(getenv("SCRIPT_NAME"),-14)=="/cache_rss.php"){
	header("HTTP/1.0 404 Not Found");
	exit;
}

header("Cache-Control: no-cache, must-revalidate"); 
header("Content-Type: text/xml; charset=EUC-KR");

$HOST_NAME = strtolower(getenv("HTTP_HOST"));

$cache_file_name=escapeshellcmd($Dir.DataDir."cache/rss/".$HOST_NAME."_".substr(getenv("REQUEST_URI"),strrpos(getenv("SCRIPT_NAME"),"/")+1));
$cache_file_name=str_replace(" ","",$cache_file_name);

$HTML_ERROR_EVENT="NO";
$HTML_CACHE_EVENT="NO";

function html_cache($buffer) {
	global $cache_file_name,$HTML_ERROR_EVENT;

	if ($HTML_ERROR_EVENT=="NO" && strlen($buffer)>3000) {
		$fp = fopen($cache_file_name,"w");
		fputs($fp,$buffer);
		fclose($fp);
		return $buffer;
	} else {
		return $buffer;
	}
}

function html_cache_out() {
	global $cache_file_name;
	readfile($cache_file_name); exit;
}


function error_cache($errno, $errstr, $errfile, $errline) {
	global $HTML_ERROR_EVENT;
	if (strpos($errstr,"mysql")!==FALSE) $HTML_ERROR_EVENT = $errstr;
}

$error_handler = set_error_handler("error_cache");

if (getenv("REQUEST_METHOD")=="GET") {
	if (file_exists($cache_file_name)==true) {
		$filecreatetime=(time()-filemtime($cache_file_name))/60;
		if($filecreatetime>5) {
			$HTML_CACHE_EVENT="OK";
			ob_start("html_cache");
		} else {
			html_cache_out();
		}
	} else {
		$HTML_CACHE_EVENT="OK";
		ob_start("html_cache");
	}
}
?>