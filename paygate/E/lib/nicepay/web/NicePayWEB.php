<?php
require_once dirname(__FILE__).'/WebMessageDTO.php';
require_once dirname(__FILE__).'/../log/LogMode.php';
require_once dirname(__FILE__).'/../log/NicePayLogJournal.php';
require_once dirname(__FILE__).'/../core/MessageIdVersionFactory.php';
require_once dirname(__FILE__).'/../core/ErrorMessageHandler.php';
require_once dirname(__FILE__).'/../core/ServiceFactory.php';
require_once dirname(__FILE__).'/../core/SecureMessageProcessor.php';
require_once dirname(__FILE__).'/../core/IoAdaptorTransport.php';
require_once dirname(__FILE__).'/../validator/CommonMessageValidator.php';
require_once dirname(__FILE__).'/WebParamGatherFactory.php';
require_once dirname(__FILE__).'/PayCommonWebParamGather.php';
require_once dirname(__FILE__).'/../validator/GoodsMessageDataValidator.php';
require_once dirname(__FILE__).'/../validator/MerchantMessageDataValidator.php';
require_once dirname(__FILE__).'/../validator/BuyerMessageDataValidator.php';
require_once dirname(__FILE__).'/../validator/BodyMessageValidatorFactory.php';
require_once dirname(__FILE__).'/../validator/CellPhoneRegItemBodyValidator.php';
require_once dirname(__FILE__).'/../validator/CellPhoneSelfDeliverBodyValidator.php';
require_once dirname(__FILE__).'/../validator/CellPhoneSmsDeliverBodyValidator.php';
require_once dirname(__FILE__).'/../validator/CellPhoneItemConfirmBodyValidator.php';
require_once dirname(__FILE__).'/../validator/CancelBodyMessageValidator.php';
require_once dirname(__FILE__).'/../exception/ServiceExceptionCallbackHandler.php';
require_once dirname(__FILE__).'/../exception/NetCancelCallback.php';
require_once dirname(__FILE__).'/../exception/NetCancelCallback.php';
require_once dirname(__FILE__).'/../message/MessageTemplateCreator.php';

/**
 * 
 * @author kblee
 *
 */
class NicePayWEB{
	
	/**
	 * 
	 * @var $webMessageDTO
	 */
	private $webMessageDTO;
	
	/**
	 * 
	 */
	public function NicePayWEB(){
		$this->webMessageDTO = new WebMessageDTO();
	}
	
	/**
	 * 
	 * @param $request
	 */
	public function doService($request){
	
		try {
			
			// ???? ???? ???? ????
			$eventLogEnable = $this->webMessageDTO->getParameter(EVENT_LOG);
			if("1" == $eventLogEnable){
				LogMode::enableEventLogMode();
			}
			
			// APP???? ???? ???? ???? ?? ??????
			$appLogEnable = $this->webMessageDTO->getParameter(APP_LOG);
			if("1" == $appLogEnable){
				LogMode::enableAppLogMode();
			}
			
			if("1"== $this->webMessageDTO->getParameter(APP_LOG) || "1" == $this->webMessageDTO->getParameter(EVENT_LOG)){
				$logJournal = NicePayLogJournal::getInstance();
                $directoryPath = $this->webMessageDTO->getParameter(NICEPAY_LOG_HOME);
				$logJournal->setLogDirectoryPath($directoryPath);
				$logJournal->configureNicePayLog4PHP();
			}
			
			$serviceMode  = $this->webMessageDTO->getParameter(SERVICE_MODE);
			// ???????????? ???? version?? ID????
			$messageIdVersionFactory = new MessageIdVersionFactory();
			$messageIdVersionSetter = $messageIdVersionFactory->create($serviceMode,$this->webMessageDTO->getParameter(PAY_METHOD));
			$messageIdVersionSetter->fillIdAndVersion($this->webMessageDTO);
			
			// ???? ?????? ?????? ????
			$parameterSetValidator = new CommonMessageValidator();
			$parameterSetValidator->validate($this->webMessageDTO);
			
			//???? Gather
			$commonWebGather = new PayCommonWebParamGather();
			$commonWebGather->charset =  $this->webMessageDTO->getParameter(CHARSET);

	   	    $commonGatherParam = $commonWebGather->gather($request);
			$this->webMessageDTO->add($commonGatherParam);
			
			// ?????????? http request value gather
			$webParamGatherFactory = new WebParamGatherFactory();
			$webParamGather = $webParamGatherFactory->createParamGather($serviceMode,$this->webMessageDTO->getParameter(PAY_METHOD));
			if($webParamGather!=null){
				$gatherParam = $webParamGather->gather($request);
				$this->webMessageDTO->add($gatherParam);
			}
			// ?????? ????
			$this->paramValidateByValidate($serviceMode);
			// ?????? ????
			$ioAdaptorService = $this->createIoAdaptorService($serviceMode,$this->webMessageDTO->getParameter(PAY_METHOD));
			// ?????? ???? 
			$responseWebMDTO = $ioAdaptorService->service($this->webMessageDTO);
			$responseWebMDTO->setParameter(SERVICE_MODE, $serviceMode);
			
			// event log ?????? 
			if(LogMode::isEventLogable()){
				$logJournal->writeEventLog($responseWebMDTO);
			}
			
			return $responseWebMDTO;
			
			
		}catch(ServiceException $e){
			echo $e->getErrorMessage();
			// ServiceException ?????? ???? ???????????? ?????? ????????.
			$callbackHandler = new ServiceExceptionCallbackHandler();
			$netCancelCallback = new NetCancelCallback();
			$netCancelCallback->setWebMessageDTO($this->webMessageDTO);
			$netCancelCallback->setServiceException($e);
			$callbackHandler->doHandle(array($netCancelCallback));
			$errorHandler = new ErrorMessageHandler();
			// ????????, ???????????? ????????.
			return $errorHandler->doHandle($e);
		}catch(Exception $e){
				echo $e->getMessage();
				// ????????, ???????????? ????????.
				$errorHandler = new ErrorMessageHandler();
				// ????????, ???????????? ????????.
				return $errorHandler->doHandle($e);
			}
			
		}
		
		/**
		 * 
		 * @param $serviceMode
		 */
		private function paramValidateByValidate($serviceMode){
			// ???????????? ???? ????????,????????  ?????? ????
			if(PAY_SERVICE_CODE == $serviceMode){
								
				if("CASHRCPT" != $this->webMessageDTO->getParameter(PAY_METHOD)){
					$goodsValidator = new GoodsMessageDataValidator();
					$goodsValidator->validate($this->webMessageDTO);
					
					$merchantValidator = new MerchantMessageDataValidator();
					$merchantValidator->validate($this->webMessageDTO);
					
					$buyerValidator = new BuyerMessageDataValidator();
					$buyerValidator->validate($this->webMessageDTO);
				}
								
				$bodyValidatorFactory = new BodyMessageValidatorFactory();
				$bodyValidator = $bodyValidatorFactory->createValidator($this->webMessageDTO->getParameter(PAY_METHOD));
				
				if($bodyValidator!=null){
					$bodyValidator->validate($this->webMessageDTO);
				}
				
			}else if(CANCEL_SERVICE_CODE == $serviceMode){
				$cancelValidator = new CancelBodyMessageValidator();
				$cancelValidator->validate($this->webMessageDTO);
			}else if(ESCROW_SERVICE_CODE == $serviceMode){
				$bodyValidatorFactory = new BodyMessageValidatorFactory();
				$bodyValidator = $bodyValidatorFactory->createValidator($this->webMessageDTO->getParameter(PAY_METHOD));
				
				if($bodyValidator!=null){
					$bodyValidator->validate($this->webMessageDTO);
				}
			}
			
			/*
			else if(CELLPHONE_REG_ITEM == $serviceMode){
				$cellphoneRegItemValidator = new CellPhoneRegItemBodyValidator();
				$cellphoneRegItemValidator->validate($this->webMessageDTO);
			}else if(CELLPHONE_SELF_DLVER == $serviceMode){
				$cellphoneSelfDeliverValidator = new CellPhoneSelfDeliverBodyValidator();
				$cellphoneSelfDeliverValidator->validate($this->webMessageDTO);
			}else if(CELLPHONE_SMS_DLVER == $serviceMode){
				$cellphoneSmsDeliverValidator = new CellPhoneSmsDeliverBodyValidator();
				$cellphoneSmsDeliverValidator->validate($this->webMessageDTO);
			}else if(CELLPHONE_ITEM_CONFM == $serviceMode){
				$cellphoneItemConfirmValidator = new CellPhoneItemConfirmBodyValidator();
				$cellphoneItemConfirmValidator->validate($this->webMessageDTO);
			}
			*/
		}



		/**
		 * Creates the io adaptor service.
		 * 
		 * @param serviceMode the service mode
		 * 
		 * @return the io adaptor service
		 * 
		 * @throws ServiceException the service exception
		 */
		private function createIoAdaptorService($serviceMode,$payMethod){
			// ???? ?????? ????
			$msgTemplateCreator = new MessageTemplateCreator();
			
			$requestTemplateDocument = $msgTemplateCreator->createRequestDocumentTemplate($serviceMode,$payMethod);
			$responseTemplateDocument = $msgTemplateCreator->createResponseDocumentTemplate($serviceMode,$payMethod);
			
			// ???????????? ????
			$serviceFactory = new ServiceFactory();
			$ioAdaptorService  = $serviceFactory->createService($serviceMode);
			$ioAdaptorService->setRequestTemplateDocument($requestTemplateDocument);
			$ioAdaptorService->setResponseTemplateDocument($responseTemplateDocument);
			
			
			// ?????????? ???? ?????? ?????? ???? ????
			if(PAY_SERVICE_CODE == $serviceMode){
				$ioAdaptorService->registerSecureMessageProcessor(new SecureMessageProcessor());
			}
				
			// socket ???? ?????? ????
			$ioAdaptorTransport = new IoAdaptorTransport();
			$ioAdaptorService->setTransport($ioAdaptorTransport);
			return $ioAdaptorService;
		}
		
		/**
		 * Sets the param.
		 * 
		 * @param key the key
		 * @param value the value
		 */
		public function setParam($key, $value){
			$this->webMessageDTO->setParameter($key, $value);
		}
		
		/**
		 * Gets the param.
		 * 
		 * @param key the key
		 * 
		 * @return the param
		 */
		public function getParam($key){
			return $this->webMessageDTO->getParameter($key);
		}
		
		/**
		 * Sets the secure target params.
		 * 
		 * @param targetParams the target params
		 */
		public function setSecureTargetParams($targetParams){
			$this->webMessageDTO->setParameter(SECURE_PARAMS, $targetParams);
		}
		
	}
?>
