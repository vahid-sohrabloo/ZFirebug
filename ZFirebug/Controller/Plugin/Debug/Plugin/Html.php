<?php
/**
 * ZFirebug Zend Additions
 *
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 * @version    $Id$
 */

/**
 * @category   ZFirebug
 * @package    ZFirebug_Controller
 * @subpackage Plugins
 * @copyright  Copyright (c) 2009-2010 ZF Firebug Debuger (http://github.com/vahid-sohrabloo/ZFirebug)
 * @license   New BSD License
 */
class ZFirebug_Controller_Plugin_Debug_Plugin_Html implements ZFirebug_Controller_Plugin_Debug_Plugin_Interface
{
    /**
     * Contains plugin identifier name
     *
     * @var string
     */
    protected $_identifier = 'html';
    
	/**
	 * Contains message object
	 *
	 * @var Zend_Wildfire_Plugin_FirePhp_TableMessage
	 */

	public $message;
    /**
     * Create ZFirebug_Controller_Plugin_Debug_Plugin_Html
     *
     * @param string $tab
     * @paran string $panel
     * @return void
     */
    public function __construct()
    {
    	
    	$this->message = new Zend_Wildfire_Plugin_FirePhp_Message("LOG","");
		$this->message->setBuffered(true);
		$this->message->setLabel($this->getTab());
		$this->_auth = Zend_Auth::getInstance();

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
        return 'HTML';
    }

    /**
     * Gets content panel for the Debugbar
     *
     * @return string
     */
    public function getPanel()
    {
        $body = Zend_Controller_Front::getInstance()->getResponse()->getBody();
        $this->message->setMessage(round(strlen($body)/1024, 2).'K');
    }
}