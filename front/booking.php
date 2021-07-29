<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-09-30
 * Time: 오후 4:31
 */

$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."lib/shopdata.php");

?>

<HTML>
<HEAD>
    <TITLE>쿠폰모음</TITLE>
    <META http-equiv="CONTENT-TYPE" content="text/html; charset=EUC-KR">
    <META name="description" content="<?=(strlen($_data->shopdescription)>0?$_data->shopdescription:$_data->shoptitle)?>">
    <META name="keywords" content="<?=$_data->shopkeyword?>">
    <script type="text/javascript" src="<?=$Dir?>lib/lib.js.php"></script>
    <?include($Dir."lib/style.php")?>

    <style>
    </style>

    <SCRIPT LANGUAGE="JavaScript">
        <!--

        //-->
    </script>
</HEAD>

<body <?=(substr($_data->layoutdata["MOUSEKEY"],0,1)=="Y"?" oncontextmenu=\"return false;\"":"")?><?=(substr($_data->layoutdata["MOUSEKEY"],1,1)=="Y"?" ondragstart=\"return false;\" onselectstart=\"return false;\"":"")?> leftmargin="0" marginwidth="0" topmargin="0" marginheight="0"><?=(substr($_data->layoutdata["MOUSEKEY"],2,1)=="Y"?"<meta http-equiv=\"ImageToolbar\" content=\"No\">":"")?>

<?
include ($Dir.MainDir.$_data->menu_type.".php");
?>
<? include ($Dir."lib/bottom.php") ?>

</BODY>
</HTML>
