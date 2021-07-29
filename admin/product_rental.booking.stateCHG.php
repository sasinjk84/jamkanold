<?php
/**
 * Created by PhpStorm.
 * User: x2chi-objet
 * Date: 2014-11-26
 * Time: ¿ÀÀü 11:07
 */
$Dir="../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
INCLUDE ("access.php");

extract($_REQUEST);

echo rentProdSchdCHG ( $idx, "", $value );
?>