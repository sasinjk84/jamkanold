<?
if(substr(getenv("SCRIPT_NAME"),-10)=="header.php") {
	header("HTTP/1.0 404 Not Found");
	exit;
}
?>
<html>
<head>
<title>������ ������</title>
<META http-equiv="Content-Type" content="text/html; charset=EUC-KR">
<link rel="stylesheet" href="style.css">
<script type="text/javascript" src="<?=$Dir?>js/rental.js"></script>
<script language="JavaScript">
var bottomtxt = "���θ� ������ �������� ���Ű��� ȯ���մϴ�.";
function _shopstatus() {
	window.status = bottomtxt;
	timerID= setTimeout("_shopstatus()", 30);
}
_shopstatus();


if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
    'use strict';
    if (this == null) {
      throw new TypeError();
    }
    var n, k, t = Object(this),
        len = t.length >>> 0;

    if (len === 0) {
      return -1;
    }
    n = 0;
    if (arguments.length > 1) {
      n = Number(arguments[1]);
      if (n != n) { // shortcut for verifying if it's NaN
        n = 0;
      } else if (n != 0 && n != Infinity && n != -Infinity) {
        n = (n > 0 || -1) * Math.floor(Math.abs(n));
      }
    }
    if (n >= len) {
      return -1;
    }
    for (k = n >= 0 ? n : Math.max(len - Math.abs(n), 0); k < len; k++) {
      if (k in t && t[k] === searchElement) {
        return k;
      }
    }
    return -1;
  };
}


	// F5 ���� ��ħ ����
	document.onkeydown = function() {
		if (event.keyCode == 116) {
			event.returnValue = false;
			event.keyCode = 0;
		}
	};

</script>



<script language="JavaScript">
<!--
// iframe ��������
function autoResize(id){

	var ifrm = document.getElementById(id);
	var oBody = ifrm.contentWindow.document.body;

	var newheight;
	//var newwidth;
	
	if(document.getElementById){
		ifrm.style.height = 800;
		newheight=ifrm.contentWindow.document.body.scrollHeight;
		//newheight=oBody.scrollHeight + (oBody.offsetHeight - oBody.clientHeight);
		//newwidth=ifrm.contentWindow.document .body.scrollWidth;
		//newwidth=oBody.scrollWidth + (oBody.offsetWidth - oBody.clientWidth);
	}
	
	ifrm.style.height= newheight;
	//alert(newheight);
	//ifrm.width= (newwidth) + "px";
}
//-->
</script>


</head>
<body background="images/con_bg.gif" text="black" link="blue" vlink="purple" alink="red" class="bg" oncontextmenu="return false" oncontextmenu="alert('�ٿ��ֱ⸦ �Ͻ÷��� ControlŰ + V�� ���� �����ø� �˴ϴ�.');return false;" >
