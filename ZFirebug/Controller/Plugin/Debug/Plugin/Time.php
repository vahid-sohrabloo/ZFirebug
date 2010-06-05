<?php
/**
 * ZFirebug Zend Additions
 *
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 * @version    $Id: Time.php 72 2009-05-15 14:16:21Z gugakfugl $
 */

/**
 * @see Zend_Session
 */
require_once 'Zend/Session.php';

/**
 * @see Zend_Session_Namespace
 */
require_once 'Zend/Session/Namespace.php';

/**
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 */
class ZFirebug_Controller_Plugin_Debug_Plugin_Time extends Zend_Controller_Plugin_Abstract implements ZFirebug_Controller_Plugin_Debug_Plugin_Interface
{
	/**
	 * Contains plugin identifier name
	 *
	 * @var string
	 */
	protected $_identifier = 'time';

	/**
	 * @var array
	 */
	protected $_timer = array();

	/**
	 * Creating time plugin
	 * @return void
	 */
	public function __construct()
	{
		Zend_Controller_Front::getInstance()->registerPlugin($this);
		$this->message = new Zend_Wildfire_Plugin_FirePhp_TableMessage($this->getTab());
		$this->message->setBuffered(true);
		$this->message->setHeader(array('Key','Time'));
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
		return "Timers";
	}

	/**
	 * Gets content panel for the Debugbar
	 *
	 * @return string
	 */
	public function getPanel()
	{
		

		if (isset($this->_timer) && count($this->_timer)) {
			foreach ($this->_timer as $name => $time) {
				$this->message->addRow(array($name,$time));
			}
		}
//		
//		if(!Zend_Session::isStarted())
//		{
//			Zend_Session::start();
//		}
//
//		$request = Zend_Controller_Front::getInstance()->getRequest();
//		$this_module = $request->getModuleName();
//		$this_controller = $request->getControllerName();
//		$this_action = $request->getActionName();
//
//		$timerNamespace = new Zend_Session_Namespace('ZFirebug_Time',false);
//		$timerNamespace->data[$this_module][$this_controller][$this_action][] = $this->_timer['postDispatch'];
//
//		$html .= '<h4>Overall Timers</h4>';
//
//		foreach($timerNamespace->data as $module => $controller)
//		{
//			if ($module == $this_module) {
//				$module = '<strong>'.$module.'</strong>';
//			}
//			$html .= $module . '<br />';
//			$html .= '<div class="pre">';
//			foreach($controller as $con => $action)
//			{
//				if ($con == $this_controller) {
//					$con = '<strong>'.$con.'</strong>';
//				}
//				$html .= '    ' . $con . '<br />';
//				$html .= '<div class="pre">';
//				foreach ($action as $key => $data)
//				{
//					if ($key == $this_action) {
//						$key = '<strong>'.$key.'</strong>';
//					}
//					$html .= '        ' . $key . '<br />';
//					$html .= '<div class="pre">';
//					$html .= '            Avg: ' . $this->_calcAvg($data) . ' ms / '.count($data).' requests<br />';
//					$html .= '            Min: ' . round(min($data), 2) . ' ms<br />';
//					$html .= '            Max: ' . round(max($data), 2) . ' ms<br />';
//					$html .= '</div>';
//				}
//				$html .= '</div>';
//			}
//			$html .= '</div>';
//		}
//		$html .= '<br />Reset timers by sending ZFDEBUG_RESET as a GET/POST parameter';
//
//		return $html;
	}

	/**
	 * Sets a time mark identified with $name
	 *
	 * @param string $name
	 */
	public function mark($name) {
		if (isset($this->_timer[$name]))
		$this->_timer[$name] = (microtime(true)-$_SERVER['REQUEST_TIME'])*1000-$this->_timer[$name];
		else
		$this->_timer[$name] = (microtime(true)-$_SERVER['REQUEST_TIME'])*1000;
	}

	public function routeStartup(Zend_Controller_Request_Abstract $request) {
		//TODO : Must call mark in ZFirebug_Controller_Plugin_Debug::dispatchLoopShutdown() 
		$this->mark("Front");
	}

	/**
	 * Defined by Zend_Controller_Plugin_Abstract
	 *
	 * @param Zend_Controller_Request_Abstract
	 * @return void
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		 
		$this->mark("Action :".$request->getModuleName()."_".$request->getControllerName()."_".$request->getActionName());

	}

	/**
	 * Defined by Zend_Controller_Plugin_Abstract
	 *
	 * @param Zend_Controller_Request_Abstract
	 * @return void
	 */
	public function postDispatch(Zend_Controller_Request_Abstract $request)
	{
		$this->mark("Action :".$request->getModuleName()."_".$request->getControllerName()."_".$request->getActionName());

	}
}