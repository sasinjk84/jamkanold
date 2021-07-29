<?php
require_once dirname(__FILE__).'/WebParamGather.php';
/**
 * 
 * @author kblee
 *
 */
class CardWebParamGather implements WebParamGather{
	
	/**
	 * 
	 */
	public function CardWebParamGather(){
		
	}
	
	/**
	 * 
	 * @param $request
	 */
	public function gather($request){
		$webParam = new WebMessageDTO();
		
		//card code
		$cardCode = isset($request["FormBankCd"]) ? $request["FormBankCd"] : "";
		$webParam->setParameter(CARD_CODE,$cardCode);
		
		//card pwd
		$cardPwd = isset($request["CardPwd"]) ? $request["CardPwd"] : "";
		$webParam->setParameter(CARD_PWD, $cardPwd);
		
		// card no
		$cardNo = isset($request["CardNo"]) ? $request["CardNo"] : "";
		$webParam->setParameter(CARD_NO, $cardNo);
		
		// cardexpire
		$cardExpire =isset($request["CardExpire"]) ? $request["CardExpire"] : "";
		$webParam->setParameter(CARD_EXPIRE,$cardExpire);
		
		$cardPoint = isset($request["CardPoint"]) ? $request["CardPoint"] : "";
		$webParam->setParameter(CARD_POINT,$cardPoint);
		
		// card interest
		$cardInterest = isset($request["CardInterest"]) ? $request["CardInterest"] : "";
		$webParam->setParameter(CARD_INTEREST, $cardInterest);
		// card quota
		$cardQuota = isset($request["CardQuota"]) ? $request["CardQuota"] : "";
		$webParam->setParameter(CARD_QUOTA, $cardQuota);
		
		//AUTH_FLAG
		$authFlag = isset($request["AuthFlg"]) ? $request["AuthFlg"] : "";
		$webParam->setParameter(CARD_AUTH_FLAG, $authFlag);
		
		
		//AUTH_TYPE
		$authType = isset($request["AuthType"]) ? $request["AuthType"] : "";
		$webParam->setParameter(CARD_AUTH_TYPE, $authType);
		
		//KEYIN_CL
		$keyinCl = isset($request["KeyInCl"]) ? $request["KeyInCl"] : "";
		$webParam->setParameter(CARD_KEYIN_CL, $keyinCl);
		
		// CARD TYPE ����
		$buyerAuthName = $request[BUYER_AUTH_NO];
		$cardType = "";
		if(strlen($buyerAuthName) == 10){
			$cardType = "02"; //���
		}else{
			$cardType = "01"; //����
		}
		$webParam->setParameter(CARD_TYPE, $cardType);
		
		
		// mpi
		/*
		$eci = isset($request["eci"]) ? $request["eci"] : "";
		$xid = $request["xid"];
		$cavv = $request["cavv"];
		$joinCode = $request["joinCode"];
		$webParam->setParameter(CARD_ECI, $eci);
		$webParam->setParameter(CARD_XID, $xid);
		$webParam->setParameter(CARD_CAVV, $cavv);
		$webParam->setParameter(CARD_JOIN_CODE,$joinCode);
		
		// isp
		$kvpPgid = $request["KVP_PGID"];
		$kvpCardCode = $request["KVP_CARDCODE"];
		$kvpSessionKeyEnc = $request["KVP_SESSIONKEY"];
		$kvpEncData = $request["KVP_ENCDATA"];
		$KVP_NOINT_INF = $request["KVP_NOINT_INF"];// 255
		$KVP_QUOTA_INF = $request["KVP_QUOTA_INF"];// 255
		  
		  
		$KVP_NOINT = $request["KVP_NOINT"];// 2
		$KVP_QUOTA = $request["KVP_QUOTA"];// 2
		  
		  
		$KVP_CARDCODE = $request["KVP_CARDCODE"]; // 20
		$KVP_CONAME = $request["KVP_CONAME"]; // 40
		
		$webParam->setParameter(ISP_PGID, $kvpPgid);
		$webParam->setParameter(ISP_CODE, $kvpCardCode);
		$webParam->setParameter(ISP_SESSION_KEY, $kvpSessionKeyEnc);
		$webParam->setParameter(ISP_ENC_DATA, $kvpEncData);
		
		$webParam->setParameter(ISP_NOINT_INF, $KVP_NOINT_INF);
		$webParam->setParameter(ISP_QUOTA_INF, $KVP_QUOTA_INF);
		
		$webParam->setParameter(ISP_NOINT,$KVP_NOINT);
		$webParam->setParameter(ISP_QUOTA, $KVP_QUOTA);
		$webParam->setParameter(ISP_CARDCODE, $KVP_CARDCODE);
		$webParam->setParameter(ISP_CONAME, $KVP_CONAME);
		*/
		
		$transType = $request["TransType"] == null ? "0" : $request["TransType"];
		$webParam->setParameter(TRANS_TYPE,$transType);
		
		$trKey = $request["TrKey"] == null ? "0" : $request["TrKey"];
		$webParam->setParameter(TR_KEY,$trKey);
		
		
		return $webParam;
	}
	
}
?>
