<?php
/**
 * ZFirebug Zend Additions
 *
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 */

/**
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 */
class ZFirebug_Controller_Plugin_Debug_Plugin_Variables  implements ZFirebug_Controller_Plugin_Debug_Plugin_Interface
{
	/**
	 * Contains plugin identifier name
	 *
	 * @var string
	 */
	protected $_identifier = 'variables';

	/**
	 * @var Zend_Controller_Request_Abstract
	 */
	protected $_request;

	/**
	 * Contains message object
	 *
	 * @var Zend_Wildfire_Plugin_FirePhp_TableMessage
	 */

	public $message;


	/**
	 * Create ZFirebug_Controller_Plugin_Debug_Plugin_Variables
	 *
	 * @return void
	 */
	public function __construct()
	{
			
			
		$this->message = new Zend_Wildfire_Plugin_FirePhp_TableMessage($this->getTab());
		$this->message->setBuffered(true);
		$this->message->setHeader(array('Key','Value'));
		$this->message->setOption('includeLineNumbers', false);

	}

	/**
	 * Gets identifier for this plugin
	 *
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}

	/**
	 * Gets menu tab for the Debugbar
	 *
	 * @return string
	 */
	public function getTab()
	{
		return ' Variables';
	}

	/**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{
		$this->_request = Zend_Controller_Front::getInstance()->getRequest();
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
		$viewVars = $viewRenderer->view->getVars();
		foreach($viewVars as $k=>$v){
			$this->message->addRow(array($k." - View",self::_cleanData($v)));
		}
		foreach($this->_request->getParams() as $k=>$v){
			$this->message->addRow(array($k." - Parameter",self::_cleanData($v)));
		}
		foreach($this->_request->getCookie() as $k=>$v){
			$this->message->addRow(array($k." - Cookie",self::_cleanData($v)));
		}
	}

	protected function _cleanData($value)
	{
			if (is_array($value))
			{
				ksort($value);
				foreach($value as $k=>$v){
					$retVal=array();
					$retVal[$k]=self::_cleanData($v);
				}
			}
			else if (is_object($value))
			{
				if(get_class($value)=="stdClass"){
					$retVal = $value;
				}
				else{
					$retVal = get_class($value)." Object";
				}
				
			}
			else{
				$retVal = $value;
			}
		return $retVal;
	}

}