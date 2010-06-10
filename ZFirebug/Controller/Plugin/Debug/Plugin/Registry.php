<?php
/**
 * ZFirebug Zend Additions
 *
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 * @version    $Id: $
 */

/**
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 */
class ZFirebug_Controller_Plugin_Debug_Plugin_Registry implements ZFirebug_Controller_Plugin_Debug_Plugin_Interface
{
    /**
     * Contains plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'registry';

    /**
     * Contains Zend_Registry
     *
     * @var Zend_Registry
     */
    protected $_registry;
    
    /**
	 * Contains message object
	 *
	 * @var Zend_Wildfire_Plugin_FirePhp_TableMessage
	 */

	public $message;

    /**
     * Create ZFirebug_Controller_Plugin_Debug_Plugin_Registry
     *
     * @return void
     */
    public function __construct()
    {
    	$this->message = new Zend_Wildfire_Plugin_FirePhp_TableMessage($this->getTab());
		$this->message->setBuffered(true);
		$this->message->setHeader(array('Key','Class'));
		$this->message->setOption('includeLineNumbers', false);
        $this->_registry = Zend_Registry::getInstance();
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
        return ' Registry';
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
    	$html = '<h4>Registered Instances</h4>';
    	$this->_registry->ksort();
    	foreach($this->_registry as $k=>$v){
    		if(is_object($v))
    		$this->message->addRow(array($k,get_class($v)));
    		else
    		$this->message->addRow(array($k,$v));
    	}
    }

}