<?
if(!eregi(getenv("HTTP_HOST"),getenv("HTTP_REFERER"))) {
	header("HTTP/1.0 404 Not Found");
	exit;
} else {
?>
function reWriteName(form) {
	try {
		for(var i=0;i<form.elements.length;i++) {
			if(form.elements[i].name.length>0) {
				if (form.elements[i].name.indexOf("ins4eField")) {
					form["ins4eField["+form.elements[i].name+"]"].value = form.elements[i].value;
					form["ins4eField["+form.elements[i].name+"]"].name = form["ins4eField["+form.elements[i].name+"]"].name.replace("Field","");
				}
			}
		}
	} catch (e) {
		//alert(e.toString());
	}
}
<?
}
?>