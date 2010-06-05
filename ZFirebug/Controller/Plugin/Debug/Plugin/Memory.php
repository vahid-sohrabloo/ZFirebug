<?php
/**
 * ZFirebug Zend Additions
 *
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 * @version    $Id: Memory.php 56 2009-05-12 14:11:18Z gugakfugl $
 */

/**
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 */
class ZFirebug_Controller_Plugin_Debug_Plugin_Memory extends Zend_Controller_Plugin_Abstract implements ZFirebug_Controller_Plugin_Debug_Plugin_Interface
{
    /**
     * Contains plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'memory';

    /**
     * @var array
     */
    protected $_memory = array();

    /**
     * Creating time plugin
     * @return void
     */
    public function __construct()
    {
    	$this->message = new Zend_Wildfire_Plugin_FirePhp_TableMessage($this->getTab());
		$this->message->setBuffered(true);
		$this->message->setHeader(array('Key','Size'));
		$this->message->setOption('includeLineNumbers', false);
        Zend_Controller_Front::getInstance()->registerPlugin($this);
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
        if (function_exists('memory_get_peak_usage')) {
            return "Memory Usage : ".round(memory_get_peak_usage()/1024) . 'K of '.ini_get("memory_limit");
        }
        return 'MemUsage n.a.';
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        if (isset($this->_memory) && count($this->_memory)) {
            foreach ($this->_memory as $key => $value) {
            	$this->message->addRow(array($key,round($value/1024)." K"));
            }
        }
    }
    
    /**
     * Sets a memory mark identified with $name
     *
     * @param string $name
     */
    public function mark($name) {
        if (!function_exists('memory_get_peak_usage')) {
            return;
        }
        if (isset($this->_memory[$name]))
            $this->_memory[$name] = memory_get_peak_usage()-$this->_memory[$name];
        else
            $this->_memory[$name] = memory_get_peak_usage();
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