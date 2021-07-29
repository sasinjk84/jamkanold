<?php
require_once dirname(__FILE__).'/SecureMessageCreator.php';

/**
 * 
 * @author kblee
 *
 */
class SecureValueSetter{
	
	/**
	 * 
	 */
	public function SecureValueSetter(){
		
	}
	
	
	
	/**
	 * 
	 * @param  $paramSet
	 */
	public function fillValue($paramSet){
		$ediDate = $paramSet->getParameter(EDIT_DATE);
		$mid = $paramSet->getParameter(MID);
		$amt = $paramSet->getParameter(GOODS_AMT);
		$mkey = $paramSet->getParameter(MERCHANT_KEY);
		$buffer = array();
		
		
		$buffer = array_merge($buffer,str_split($ediDate));
		$buffer = array_merge($buffer,str_split($mid));
		$buffer = array_merge($buffer,str_split($amt));
		$buffer = array_merge($buffer,str_split($mkey));
		
		
		$messageCreator = new SecureMessageCreator();
		$encryptMessage = $messageCreator->createMessage(implode($buffer));
		
		$paramSet->setParameter(ENCRYPT_DATA, $encryptMessage);
		
		if(LogMode::isAppLogable()){
			$logJournal = NicePayLogJournal::getInstance();
			$logJournal->writeAppLog("webMessageDTO after secure value fill : [".$paramSet->toString()."]");
		}
		
		return $paramSet;
	}
}
?>
