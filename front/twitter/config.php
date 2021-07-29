<?php

if(strlen($Dir)==0) $Dir="../../";

include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

/**
 * @file
 * A single location to store configuration.
 */

/*
$snsResult = mysql_query( "SELECT * FROM `tblmembersnsinfo` WHERE `type` = 't' " , get_db_conn() );
$snsRow = mysql_fetch_assoc( $snsResult );

define('CONSUMER_KEY', $snsRow['appid']);
define('CONSUMER_SECRET', $snsRow['secret']);
*/

$oauth_token = "";
if( strlen($_REQUEST['oauth_token']) > 10 ) {

	$oauth_token = $_REQUEST['oauth_token'];
	$snsMemResult = mysql_query( "SELECT * FROM `tblmembersnsinfo` WHERE `type` = 't' AND `oauth_token` = '".$oauth_token."' " , get_db_conn() );
	$snsMemRow = mysql_fetch_assoc( $snsMemResult );

	define('TWITTER_ID', $snsMemRow['appid']);
	define('TWITTER_SECRET', $snsMemRow['secret']);

	define('CONSUMER_KEY', $snsMemRow['appid']);
	define('CONSUMER_SECRET', $snsMemRow['secret']);
}

define('OAUTH_CALLBACK', "http://".$_SERVER['HTTP_HOST']."/front/twitter/callback.php");

?>